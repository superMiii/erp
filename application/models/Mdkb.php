<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mdkb extends CI_Model
{

    /** List Datatable */
    public function serverside()
    {
        $dfrom = $this->input->post('dfrom', TRUE);
        if ($dfrom == '') {
            $dfrom = $this->uri->segment(3);
        }

        $dto = $this->input->post('dto', TRUE);
        if ($dto == '') {
            $dto = $this->uri->segment(4);
        }

        $i_area = $this->input->post('i_area', TRUE);
        if ($i_area == '') {
            $i_area = $this->uri->segment(5);
        }

        if ($i_area != '0') {
            $area = "AND a.i_area = '$i_area' ";
        } else {
            $area = "";
        }


        $dfrom  = date('Y-m-d', strtotime($dfrom));
        $dto    = date('Y-m-d', strtotime($dto));

        $f_pusat = $this->session->f_pusat;

        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("SELECT distinct
                a.d_sl as tgl,
                a.i_sl AS id,
                a.d_sl,
                a.i_sl_id,
                to_char(a.d_sl, 'YYYYMM') as i_periode,
                b.e_area_name,
                a.v_sl::money AS v_sl,
                initcap(a.e_sopir_name) AS e_sopir_name,
                a.i_kendaraan, 
                case when f.f_so_stockdaerah = 't' then 'CABANG' else 'PUSAT' end as e_pemenuhan,
                a.i_nota_id,
                a.f_sl_batal AS f_status,
                case when a.d_approve1 is not null then 't' else 'f' end as persetujuan,
                a.n_print,
                '$dfrom' AS dfrom,
                '$dto' AS dto,
                '$i_area' AS i_area
            FROM
                tm_sl a
            INNER JOIN tr_area b ON
                (b.i_area = a.i_area)
            INNER JOIN tm_user_area c ON 
                (c.i_area = a.i_area)
            inner join tm_sl_item d on (d.i_sl=a.i_sl)
            inner join tm_do e on (e.i_do=d.i_do)
            inner join tm_so f on (f.i_so=e.i_so)
            inner join tm_user us on (us.i_user=c.i_user)
            WHERE
                c.i_user = '$this->i_user'
                AND a.i_company = '$this->i_company'
                And case when '$f_pusat' = 'f' then f.f_so_stockdaerah='t' else us.f_status = 't' end
                AND a.d_sl BETWEEN '$dfrom' AND '$dto'
                $area
            ORDER BY
                1 DESC
        ", FALSE);

        $datatables->edit('n_print', function ($data) {
            if ($data['n_print'] == '0') {
                $data = "<span class='badge bg-yellow bg-darken-3 badge-pill'>" . $this->lang->line('Belum') . "</span>";
            } else {
                $data = "<span class='badge bg-blue bg-darken-1 badge-pill'>" . $this->lang->line('Sudah') . ' ' . $data['n_print'] . ' x' . "</span>";
            }
            return $data;
        });

        $datatables->edit('persetujuan', function ($data) {
            if ($data['persetujuan'] == 'f') {
                $status = $this->lang->line('Menunggu Persetujuan');
                $color  = 'red';
            } else {
                $color  = 'teal';
                $status = $this->lang->line('Disetujui');
            }
            $data = "<span class='badge bg-" . $color . " badge-pill'>" . $status . "</span>";
            return $data;
        });

        $datatables->edit('f_status', function ($data) {
            if ($data['f_status'] == 't') {
                $status = $this->lang->line('Batal');
                $color  = 'red';
            } else {
                $color  = 'teal';
                $status = $this->lang->line('Aktif');
            }
            $data = "<span class='badge bg-" . $color . " badge-pill'>" . $status . "</span>";
            return $data;
        });

        /** Cek Hak Akses, Apakah User Bisa Edit */
        $datatables->add('action', function ($data) {
            $id             = trim($data['id']);
            $f_status       = $data['f_status'];
            $persetujuan    = $data['persetujuan'];
            $i_periode      = $data['i_periode'];
            $dfrom          = $data['dfrom'];
            $dto            = $data['dto'];
            $i_area         = $data['i_area'];
            $i_nota_id      = $data['i_nota_id'];
            $data           = '';
            $data           .= "<a href='" . base_url() . $this->folder . '/view/' . encrypt_url($id) . '/' . encrypt_url($dfrom) . '/' . encrypt_url($dto) . '/' . encrypt_url($i_area) . "' title='View Data'><i class='fa fa-eye fa-lg warning darken-4 mr-1'></i></a>";
            if ($i_periode >= get_periode()) {
                if (check_role($this->id_menu, 5) && $f_status == 'f') {
                    $data      .= "<a href='#' onclick='openLink(\"" . $this->folder . "\",\"" . encrypt_url($id) . "\"); return false;' title='Print Data'><i class='fa fa-print fa-lg blue darken-4 mr-1'></i></a>";
                }
                if (check_role($this->id_menu, 3) && $f_status == 'f' && $persetujuan == 'f') {
                    $data      .= "<a href='" . base_url() . $this->folder . '/edit/' . encrypt_url($id)  . '/' .  encrypt_url($dfrom) . '/' . encrypt_url($dto) . '/' . encrypt_url($i_area) . "' title='Edit Data'><i class='fa fa-pencil-square success darken-4 fa-lg mr-1'></i></a>";
                }
                if ($i_nota_id == null) {
                    if (check_role($this->id_menu, 4) && $f_status == 'f' && $persetujuan == 'f') {
                        $data      .= "<a href='#' onclick='sweetdeletev33(\"" . $this->folder . "\",\"" . $id . "\",\"" . encrypt_url($dfrom)  . "\",\"" . encrypt_url($dto)  . '/' . encrypt_url($i_area) . "\");' title='Delete Data'><i class='fa fa-trash fa-lg red darken-4'></i></a>";
                    }
                }
            }
            return $data;
        });
        $datatables->hide('i_periode');
        $datatables->hide('id');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('i_area');
        return $datatables->generate();
    }


    /** Get Area */
    public function get_area($cari)
    {
        return $this->db->query("SELECT 
                DISTINCT
                a.i_area, 
                i_area_id, 
                initcap(e_area_name) AS e_area_name
            FROM 
                tr_area a
            INNER JOIN tm_user_area b 
                ON (b.i_area = a.i_area) 
            WHERE 
                (e_area_name ILIKE '%$cari%' OR i_area_id ILIKE '%$cari%')
                AND a.i_company = '$this->i_company' 
                AND f_area_active = true
                AND b.i_user = '$this->i_user' 
            ORDER BY 3 ASC
        ", FALSE);
    }

    /** Get DO */
    public function get_do($cari, $i_area)
    {
        $f_pusat = $this->session->f_pusat;
        return $this->db->query("SELECT
                a.i_do,
                a.i_do_id,
                a.d_do,
                '[ ' || c.i_customer_id || ' ] - ' || c.e_customer_name AS e_customer_name
            FROM
                tm_do a
            INNER JOIN tm_so b ON
                (b.i_so = a.i_so)
            INNER JOIN tr_customer c ON
                (c.i_customer = a.i_customer)
            WHERE
                a.f_do_cancel = 'f'
                AND b.f_so_cancel = 'f'
                AND b.i_status_so = '6'
                AND a.i_do_id ILIKE '%$cari%'
                AND a.i_area = '$i_area'
                AND a.i_company = '$this->i_company'
                And case when '$f_pusat' = 'f' then b.f_so_stockdaerah='t' else b.f_so_stockdaerah='f' end
                AND a.i_do NOT IN (SELECT i_do FROM tm_sl_item a, tm_sl b WHERE a.i_sl = b.i_sl AND b.f_sl_batal='f' AND b.i_company = '$this->i_company' AND b.i_area = '$i_area')
            ORDER BY
                a.i_do_id ASC 
        ", FALSE);
    }

    /** Get DO Detail */
    public function get_detail_do($i_do)
    {
        return $this->db->query("SELECT
                i_do,
                i_do_id,
                d_do,
                to_char(d_do, 'DD')||' '||trim(to_char(d_do, 'Month'))||' '||to_char(d_do, 'YYYY') AS date_do,
                e_customer_name,
                round((v_total - (v_so_discounttotal + v_diskon1 + v_diskon2 + v_diskon3)) * (1+(n_so_ppn/100))) AS v_jumlah
            FROM
                (
                SELECT
                    a.i_do,
                    a.i_do_id,
                    a.d_do,
                    b.v_so_discounttotal,
                    b.n_so_ppn,
                    '[ ' || c.i_customer_id || ' ] - ' || c.e_customer_name AS e_customer_name,
                    sum(d.n_deliver * e.v_unit_price) AS v_total,
                    sum(d.n_deliver * e.v_unit_price * (e.n_so_discount1 / 100)) AS v_diskon1,
                    sum((d.n_deliver * e.v_unit_price - d.n_deliver * e.v_unit_price * (e.n_so_discount1 / 100))*(e.n_so_discount2 / 100)) AS v_diskon2,
                    sum(((d.n_deliver * e.v_unit_price)-(d.n_deliver * e.v_unit_price * (e.n_so_discount1 / 100))-((d.n_deliver * e.v_unit_price - d.n_deliver * e.v_unit_price * (e.n_so_discount1 / 100))*(e.n_so_discount2 / 100))) * (e.n_so_discount3 / 100)) AS v_diskon3
                FROM
                    tm_do a
                INNER JOIN tm_so b ON
                    (b.i_so = a.i_so)
                INNER JOIN tr_customer c ON
                    (c.i_customer = a.i_customer)
                INNER JOIN tm_do_item d ON
                    (d.i_do = a.i_do)
                INNER JOIN tm_so_item e ON
                    (e.i_so = a.i_so
                        AND d.i_product = e.i_product)
                WHERE
                    a.i_do = '$i_do'
                GROUP BY
                    1,2,3,4,5,6
                ) AS x
            ORDER BY
                i_do ASC
        ", FALSE);
    }

    /** Get Ekspedisi */
    public function get_ekspedisi($cari, $i_area)
    {
        return $this->db->query("
            SELECT
                a.i_sl_ekspedisi,
                i_sl_ekspedisi_id,
                e_sl_ekspedisi
            FROM
                tr_sl_ekspedisi a 
                inner join tr_sl_ekspedisi_item b on (b.i_sl_ekspedisi=a.i_sl_ekspedisi)
            WHERE
                f_sl_ekpedisi_active = 't'
                AND (e_sl_ekspedisi ILIKE '%$cari%' OR 
                    i_sl_ekspedisi_id ILIKE '%$cari%')
                AND b.i_area = '$i_area'
                AND i_company = '$this->i_company'
            ORDER BY
                a.i_sl_ekspedisi ASC 
        ", FALSE);
    }

    /** Running Number Dokumen*/
    public function running_number($thbl, $tahun)
    {
        $cek = $this->db->query("
            SELECT 
                substring(i_sl_id, 1, 3) AS kode 
            FROM tm_sl 
            WHERE i_company = '$this->i_company'
            ORDER BY i_sl DESC");
        if ($cek->num_rows() > 0) {
            $kode = $cek->row()->kode;
        } else {
            $kode = 'DKB';
        }
        $query  = $this->db->query("
            SELECT
                max(substring(i_sl_id, 10, 6)) AS max
            FROM
                tm_sl
            WHERE to_char (d_sl, 'yyyy') >= '$tahun'
            AND i_company = '$this->i_company'
            AND substring(i_sl_id, 1, 3) = '$kode'
            AND substring(i_sl_id, 5, 2) = substring('$thbl',1,2)
        ", false);
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $no = $row->max;
            }
            $number = $no + 1;
            settype($number, "string");
            $n = strlen($number);
            while ($n < 6) {
                $number = "0" . $number;
                $n = strlen($number);
            }
            $number = $kode . "-" . $thbl . "-" . $number;
            return $number;
        } else {
            $number = "000001";
            $nomer  = $kode . "-" . $thbl . "-" . $number;
            return $nomer;
        }
    }

    /** Cek Apakah Data Sudah Ada */
    public function cek_code()
    {
        $i_document = str_replace("_", "", $this->input->post('i_document'));
        return $this->db->query("
            SELECT 
                i_sl_id
            FROM 
                tm_sl 
            WHERE 
                upper(trim(i_sl_id)) = upper(trim('$i_document'))
                AND i_company = '$this->i_company'
        ", FALSE);
    }

    /** Simpan Data */
    public function save($tgl, $i_document)
    {
        $query = $this->db->query("SELECT max(i_sl)+1 AS id FROM tm_sl", TRUE);
        if ($query->num_rows() > 0) {
            $id = $query->row()->id;
            if ($id == null) {
                $id = 1;
            } else {
                $id = $id;
            }
        } else {
            $id = 1;
        }

        $header = array(
            'i_company'             => $this->session->i_company,
            'i_sl'                  => $id,
            'i_sl_id'               => $i_document,
            'i_sl_kirim'            => $this->input->post('i_sl_kirim'),
            'i_sl_via'              => $this->input->post('i_sl_via'),
            'i_area'                => $this->input->post('i_area'),
            'i_kendaraan'           => $this->input->post('i_kendaraan'),
            'd_sl'                  => formatYMD($tgl),
            'd_entry'               => current_datetime(),
            'e_sopir_name'          => $this->input->post('e_sopir_name'),
            'v_sl'                  => str_replace(",", "", $this->input->post('v_sl')),
        );
        $this->db->insert('tm_sl', $header);

        $i = 0;
        if (is_array($this->input->post('i_do')) || is_object($this->input->post('i_do'))) {
            foreach ($this->input->post('i_do') as $i_do) {
                $item = array(
                    'i_sl'             => $id,
                    'i_do'             => $i_do,
                    'd_sl'             => $this->input->post('d_document'),
                    'd_do'             => $this->input->post('d_do')[$i],
                    'e_remark'         => $this->input->post('e_remark')[$i],
                    'v_jumlah'         => str_replace(",", "", $this->input->post('v_jumlah')[$i]),
                    'n_item_no'        => $i,
                );
                $this->db->insert('tm_sl_item', $item);
                $query = $this->db->query("SELECT i_so FROM tm_do WHERE i_do = '$i_do' ", FALSE);
                if ($query->num_rows() > 0) {
                    foreach ($query->result() as $key) {
                        $this->db->query("UPDATE tm_so SET i_status_so = '7' WHERE i_so = '$key->i_so' ", FALSE);
                    }
                }
                $i++;
            }
        } else {
            die;
        }

        if ($this->input->post('jmlx') > 0) {
            $ii = 0;
            if (is_array($this->input->post('i_sl_ekspedisi')) || is_object($this->input->post('i_sl_ekspedisi'))) {
                foreach ($this->input->post('i_sl_ekspedisi') as $i_sl_ekspedisi) {
                    if ($i_sl_ekspedisi != '' || $i_sl_ekspedisi != null) {
                        $remak = ($this->input->post('e_remark_ekspedisi')[$ii] == '') ? null :  $this->input->post('e_remark_ekspedisi')[$ii];
                        $item = array(
                            'i_sl'             => $id,
                            'i_sl_ekspedisi'   => $i_sl_ekspedisi,
                            'e_remark'         => $remak,
                            'n_item_no'        => $ii,
                        );
                        $this->db->insert('tm_sl_ekspedisi_item', $item);
                    }
                    $ii++;
                }
            }
        }
    }

    /** Get Data Untuk Edit */
    public function get_data($id)
    {
        return $this->db->query("SELECT 
                a.*,
                b.i_area_id,
                b.e_area_name,
                c.e_sl_kirim_name,
                d.e_sl_via_name
            FROM 
                tm_sl a
            INNER JOIN tr_area b ON 
                (b.i_area = a.i_area)
            INNER JOIN tr_sl_kirim c ON 
                (c.i_sl_kirim = a.i_sl_kirim)
            INNER JOIN tr_sl_via d ON
                (d.i_sl_via = a.i_sl_via)
            WHERE i_sl = '$id'
        ", FALSE);
    }

    /** Get Detail Untuk Edit */
    public function get_data_detail($id)
    {
        return $this->db->query("SELECT 
                a.*,
                b.i_do_id,
                b.d_do,
                to_char(b.d_do, 'DD')||' '||trim(to_char(b.d_do, 'Month'))||' '||to_char(b.d_do, 'YYYY') AS date_do,
                bb.i_so_id,
                bb.d_so,
                c.i_customer_id,
                c.e_customer_name AS e_customer,
                d.e_city_name,
                '[ ' || c.i_customer_id || ' ] - ' || c.e_customer_name AS e_customer_name
            FROM 
                tm_sl_item a
            INNER JOIN tm_do b ON 
                (b.i_do = a.i_do)
            INNER JOIN tm_so bb ON 
                (bb.i_so = b.i_so)
            INNER JOIN tr_customer c ON 
                (c.i_customer = b.i_customer)
            LEFT JOIN tr_city d ON
                (d.i_city = c.i_city)
            WHERE i_sl = '$id'
                /* AND b.f_do_cancel = 'f' */
        ", FALSE);
    }

    /** Get Detail Untuk Edit */
    public function get_data_detail_ekspedisi($id)
    {
        return $this->db->query("SELECT
                a.*,
                b.i_sl_ekspedisi_id,
                b.e_sl_ekspedisi
            FROM
                tm_sl_ekspedisi_item a
            INNER JOIN tr_sl_ekspedisi b ON
                (b.i_sl_ekspedisi = a.i_sl_ekspedisi)
            WHERE
                a.i_sl = '$id'
            ORDER BY
                n_item_no ASC
            /* AND b.f_do_cancel = 'f' */
        ", FALSE);
    }

    /** Cek Apakah Data Sudah Ada Pas Edit */
    public function cek_edit()
    {
        $i_document = str_replace("_", "", $this->input->post('i_document'));
        $i_document_old = str_replace("_", "", $this->input->post('i_document_old'));
        return $this->db->query("
            SELECT 
                i_sl_id
            FROM 
                tm_sl 
            WHERE 
                trim(upper(i_sl_id)) <> trim(upper('$i_document_old'))
                AND trim(upper(i_sl_id)) = trim(upper('$i_document'))
                AND i_company = '$this->i_company'
         ", FALSE);
    }

    /** Update Data */
    public function update()
    {
        $id = $this->input->post('id');
        $header = array(
            'i_company'             => $this->session->i_company,
            'i_sl_id'               => str_replace("_", "", strtoupper($this->input->post('i_document'))),
            'i_sl_kirim'            => $this->input->post('i_sl_kirim'),
            'i_sl_via'              => $this->input->post('i_sl_via'),
            'i_area'                => $this->input->post('i_area'),
            'i_kendaraan'           => $this->input->post('i_kendaraan'),
            'd_sl'                  => $this->input->post('d_document'),
            'd_update'              => current_datetime(),
            'e_sopir_name'          => $this->input->post('e_sopir_name'),
        );
        $this->db->where('i_sl', $id);
        $this->db->update('tm_sl', $header);

        if ($this->input->post('jml') > 0) {

            $query2 = $this->db->query("select i_so as soo from tm_sl_item a inner join tm_do b on (b.i_do=a.i_do) where i_sl = '$id' ", FALSE);
                // if ($query2->num_rows() > 0) {
                //     foreach ($query2->result() as $key) {
                //         $this->db->query("UPDATE tm_so SET i_status_so = '7' WHERE i_so = '$key->soo' ", FALSE);
                //     }
                // }
            $this->db->where('i_sl', $id);
            $this->db->delete('tm_sl_item');
            $i = 0;
            foreach ($this->input->post('i_do') as $i_do) {
                $item = array(
                    'i_sl'             => $id,
                    'i_do'             => $i_do,
                    'd_sl'             => $this->input->post('d_document'),
                    'd_do'             => $this->input->post('d_do')[$i],
                    'e_remark'         => $this->input->post('e_remark')[$i],
                    'v_jumlah'         => str_replace(",", "", $this->input->post('v_jumlah')[$i]),
                    'n_item_no'        => $i,
                );
                $this->db->insert('tm_sl_item', $item);
                $query = $this->db->query("SELECT i_so FROM tm_do WHERE i_do = '$i_do' ", FALSE);
                if ($query->num_rows() > 0) {
                    foreach ($query->result() as $key) {
                        $this->db->query("UPDATE tm_so SET i_status_so = '7' WHERE i_so = '$key->i_so' ", FALSE);
                    }
                }
                $i++;
            }
        } else {
            die;
        }

        if ($this->input->post('jmlx') > 0) {
            if (is_array($this->input->post('i_sl_ekspedisi')) || is_object($this->input->post('i_sl_ekspedisi'))) {
                $this->db->where('i_sl', $id);
                $this->db->delete('tm_sl_ekspedisi_item');
                $ii = 0;
                foreach ($this->input->post('i_sl_ekspedisi') as $i_sl_ekspedisi) {
                    if ($i_sl_ekspedisi != '' || $i_sl_ekspedisi != null) {
                        $remak = ($this->input->post('e_remark_ekspedisi')[$ii] == '') ? null :  $this->input->post('e_remark_ekspedisi')[$ii];
                        $item = array(
                            'i_sl'             => $id,
                            'i_sl_ekspedisi'   => $i_sl_ekspedisi,
                            'e_remark'         => $remak,
                            'n_item_no'        => $ii,
                        );
                        $this->db->insert('tm_sl_ekspedisi_item', $item);
                    }
                    $ii++;
                }
            }
        }

        /* if ($this->input->post('jmlx') > 0) {
            $ii = 0;
            foreach ($this->input->post('i_sl_ekspedisi') as $i_sl_ekspedisi) {
                $item = array(
                    'i_sl'             => $id,
                    'i_sl_ekspedisi'   => $i_sl_ekspedisi,
                    'e_remark'         => $this->input->post('e_remark')[$ii],
                    'n_item_no'        => $ii,
                );
                $this->db->insert('tm_sl_ekspedisi_item', $item);
                $ii++;
            }
        } */
    }

    /** Cancel Data */
    public function cancel($id)
    {
        $table = array(
            'f_sl_batal' => 't',
        );
        $this->db->where('i_sl', $id);
        $this->db->update('tm_sl', $table);

        $query = $this->db->query("SELECT i_so FROM tm_do WHERE i_do IN (SELECT i_do FROM tm_sl_item WHERE i_sl = '$id')", FALSE);
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $key) {
                $this->db->query("UPDATE tm_so SET i_status_so = '6' WHERE i_so = '$key->i_so' ", FALSE);
            }
        }
    }

    /** Update Print */
    public function update_print($id)
    {
        $this->db->query("UPDATE tm_sl SET n_print = n_print + 1 WHERE i_sl = '$id' ", FALSE);
    }
}

/* End of file Mmaster.php */
