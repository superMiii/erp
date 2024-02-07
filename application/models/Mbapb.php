<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mbapb extends CI_Model
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

        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("SELECT
                a.d_cgr as tgl,
                a.i_cgr AS id,
                a.d_cgr,
                a.i_cgr_id,
                to_char(a.d_cgr, 'YYYYMM') as i_periode,
                b.i_customer_id || ' ~ ' || b.e_customer_name AS e_customer_name,
                c.e_area_name,
                a.v_cgr::money AS v_cgr,
                a.f_cgr_cancel AS f_status,
                a.n_print,
                '$dfrom' AS dfrom,
                '$dto' AS dto,
                '$i_area' AS i_area
            FROM
                tm_cgr a
            INNER JOIN tr_customer b ON
                (b.i_customer = a.i_customer)
            INNER JOIN tr_area c ON
                (c.i_area = a.i_area)
            INNER JOIN tm_user_area d ON
                (d.i_area = a.i_area)
            WHERE
                d.i_user = '$this->i_user'
                AND a.i_company = '$this->i_company'
                AND a.d_cgr BETWEEN '$dfrom' AND '$dto'
                $area
            ORDER BY
                i_cgr DESC
        ", FALSE);

        $datatables->edit('n_print', function ($data) {
            if ($data['n_print'] == '0') {
                $data = "<span class='badge bg-yellow bg-darken-3 badge-pill'>" . $this->lang->line('Belum') . "</span>";
            } else {
                $data = "<span class='badge bg-blue bg-darken-1 badge-pill'>" . $this->lang->line('Sudah') . ' ' . $data['n_print'] . ' x' . "</span>";
            }
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
            $id         = trim($data['id']);
            $f_status   = $data['f_status'];
            $i_periode  = $data['i_periode'];
            $dfrom      = $data['dfrom'];
            $dto        = $data['dto'];
            $i_area     = $data['i_area'];
            $data       = '';
            $data      .= "<a href='" . base_url() . $this->folder . '/view/' . encrypt_url($id) . '/' . encrypt_url($dfrom) . '/' . encrypt_url($dto) . '/' . encrypt_url($i_area) . "' title='View Data'><i class='fa fa-eye fa-lg warning darken-4 mr-1'></i></a>";

            if ($i_periode >= get_periode()) {
                if (check_role($this->id_menu, 3) && $f_status == 'f') {
                    $data      .= "<a href='" . base_url() . $this->folder . '/edit/' . encrypt_url($id)  . '/' .  encrypt_url($dfrom) . '/' . encrypt_url($dto) . '/' . encrypt_url($i_area) . "' title='Edit Data'><i class='fa fa-pencil-square success darken-4 fa-lg mr-1'></i></a>";
                }
                if (check_role($this->id_menu, 5) && $f_status == 'f') {
                    $data      .= "<a href='#' onclick='openLink(\"" . $this->folder . "\",\"" . encrypt_url($id) . "\"); return false;' title='Print Data'><i class='fa fa-print fa-lg blue darken-4 mr-1'></i></a>";
                }
                if (check_role($this->id_menu, 4) && $f_status == 'f') {
                    $data      .= "<a href='#' onclick='sweetdeletev33(\"" . $this->folder . "\",\"" . $id . "\",\"" . encrypt_url($dfrom)  . "\",\"" . encrypt_url($dto)  . '/' . encrypt_url($i_area) . "\");' title='Delete Data'><i class='fa fa-trash fa-lg red darken-4'></i></a>";
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

    /** Get Customer */
    public function get_customer($cari, $i_area)
    {
        return $this->db->query("
            SELECT 
                DISTINCT
                i_customer, 
                i_customer_id, 
                e_customer_name 
            FROM 
                tr_customer
            WHERE 
                (e_customer_name ILIKE '%$cari%' OR i_customer_id ILIKE '%$cari%')
                AND i_company = '$this->i_company' 
                AND f_customer_active = true
                AND i_area = '$i_area' 
            ORDER BY 3 ASC
        ", FALSE);
    }

    /** Get DO */
    public function get_do($cari, $i_area, $i_customer)
    {
        $d_do = date('Y-m-d', strtotime('-6 month', strtotime(date('Y-m-d'))));
        return $this->db->query("SELECT
                a.i_do,
                a.i_do_id
            FROM
                tm_do a
            INNER JOIN tr_customer c ON
                (c.i_customer = a.i_customer)
            WHERE
                a.f_do_cancel = 'f'
                AND a.i_do_id ILIKE '%$cari%'
                AND a.i_area = '$i_area'
                AND a.i_customer = '$i_customer'
                AND a.i_company = '$this->i_company'
                AND a.d_do >= '$d_do'
                AND a.i_do NOT IN (
                    SELECT
                        i_do
                    FROM
                        tm_cgr_item a,
                        tm_cgr b
                    WHERE
                        a.i_cgr = b.i_cgr
                        AND b.f_cgr_cancel = 'f'
                        AND b.d_cgr >= '$d_do')
            ORDER BY
                a.i_do ASC 
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
                i_sl_ekspedisi,
                i_sl_ekspedisi_id,
                e_sl_ekspedisi
            FROM
                tr_sl_ekspedisi
            WHERE
                f_sl_ekpedisi_active = 't'
                AND (e_sl_ekspedisi ILIKE '%$cari%' OR 
                    i_sl_ekspedisi_id ILIKE '%$cari%')
                AND i_area = '$i_area'
                AND i_company = '$this->i_company'
            ORDER BY
                i_sl_ekspedisi ASC 
        ", FALSE);
    }

    /** Running Number Dokumen*/
    public function running_number($thbl, $tahun)
    {
        $cek = $this->db->query("
            SELECT 
                substring(i_cgr_id, 1, 3) AS kode 
            FROM tm_cgr 
            WHERE i_company = '$this->i_company'
            ORDER BY i_cgr DESC");
        if ($cek->num_rows() > 0) {
            $kode = $cek->row()->kode;
        } else {
            $kode = 'CGR';
        }
        $query  = $this->db->query("
            SELECT
                max(substring(i_cgr_id, 10, 6)) AS max
            FROM
                tm_cgr
            WHERE to_char (d_cgr, 'yyyy') >= '$tahun'
            AND i_company = '$this->i_company'
            AND substring(i_cgr_id, 1, 3) = '$kode'
            AND substring(i_cgr_id, 5, 2) = substring('$thbl',1,2)
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
                i_cgr_id
            FROM 
                tm_cgr 
            WHERE 
                upper(trim(i_cgr_id)) = upper(trim('$i_document'))
                AND i_company = '$this->i_company'
        ", FALSE);
    }

    /** Simpan Data */
    public function save()
    {
        $query = $this->db->query("SELECT max(i_cgr)+1 AS id FROM tm_cgr", TRUE);
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

        $ym = date('ym', strtotime($this->input->post('d_document')));
        $Y = date('Y', strtotime($this->input->post('d_document')));
        $i_cgr_id = $this->running_number($ym, $Y);

        $header = array(
            'i_company'             => $this->session->i_company,
            'i_cgr'                 => $id,
            'i_cgr_id'              => $i_cgr_id,
            'd_cgr'                 => $this->input->post('d_document'),
            'i_area'                => $this->input->post('i_area'),
            'i_customer'            => $this->input->post('i_customer'),
            'i_sl_kirim'            => $this->input->post('i_sl_kirim'),
            'n_bal'                 => $this->input->post('n_bal'),
            'v_cgr'                 => str_replace(",", "", $this->input->post('v_cgr')),
            'd_entry'               => current_datetime(),
        );
        $this->db->insert('tm_cgr', $header);

        if ($this->input->post('jml') > 0) {
            $i = 0;
            if (is_array($this->input->post('i_do')) || is_object($this->input->post('i_do'))) {
                foreach ($this->input->post('i_do') as $i_do) {
                    $item = array(
                        'i_cgr'            => $id,
                        'i_do'             => $i_do,
                        'd_cgr'            => $this->input->post('d_document'),
                        'd_do'             => $this->input->post('d_do')[$i],
                        'v_jumlah'         => str_replace(",", "", $this->input->post('v_jumlah')[$i]),
                        'e_remark'         => $this->input->post('e_remark')[$i],
                        'n_item_no'        => $i,
                    );
                    $this->db->insert('tm_cgr_item', $item);
                    $i++;
                }
            }
        } else {
            die;
        }

        if ($this->input->post('jmlx') > 0) {
            $ii = 0;
            if (is_array($this->input->post('i_sl_ekspedisi')) || is_object($this->input->post('i_sl_ekspedisi'))) {
                foreach ($this->input->post('i_sl_ekspedisi') as $i_sl_ekspedisi) {
                    $item = array(
                        'i_cgr'            => $id,
                        'i_cgr_ekspedisi'  => $i_sl_ekspedisi,
                        'e_remark'         => $this->input->post('e_remark')[$ii],
                        'n_item_no'        => $ii,
                    );
                    $this->db->insert('tm_cgr_ekspedisi_item', $item);
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
                d.i_customer_id,
                d.e_customer_name,
                c.e_sl_kirim_name
            FROM 
                tm_cgr a
            INNER JOIN tr_area b ON 
                (b.i_area = a.i_area)
            INNER JOIN tr_sl_kirim c ON 
                (c.i_sl_kirim = a.i_sl_kirim)
            INNER JOIN tr_customer d ON
                (d.i_customer = a.i_customer)
            WHERE i_cgr = '$id'
        ", FALSE);
    }

    /** Get Detail Untuk Edit */
    public function get_data_detail($id)
    {
        return $this->db->query("SELECT a.*, 
                b.i_do_id, 
                to_char(a.d_do, 'DD')||' '||trim(to_char(a.d_do, 'Month'))||' '||to_char(a.d_do, 'YYYY') AS date_do
            FROM 
                tm_cgr_item a
            INNER JOIN tm_do b ON 
                (b.i_do = a.i_do) 
            WHERE i_cgr = '$id'", FALSE);
    }

    /** Get Detail Untuk Edit */
    public function get_data_detail_ekspedisi($id)
    {
        return $this->db->query("SELECT
                a.*,
                b.i_sl_ekspedisi_id,
                b.e_sl_ekspedisi
            FROM
                tm_cgr_ekspedisi_item a
            INNER JOIN tr_sl_ekspedisi b ON
                (b.i_sl_ekspedisi = a.i_cgr_ekspedisi)
            WHERE
                a.i_cgr = '$id'
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
                i_cgr_id
            FROM 
                tm_cgr 
            WHERE 
                trim(upper(i_cgr_id)) <> trim(upper('$i_document_old'))
                AND trim(upper(i_cgr_id)) = trim(upper('$i_document'))
                AND i_company = '$this->i_company'
         ", FALSE);
    }

    /** Update Data */
    public function update()
    {
        $id = $this->input->post('id');
        $header = array(
            'i_company'             => $this->session->i_company,
            'i_cgr_id'              => strtoupper($this->input->post('i_document')),
            'd_cgr'                 => $this->input->post('d_document'),
            'i_area'                => $this->input->post('i_area'),
            'i_customer'            => $this->input->post('i_customer'),
            'i_sl_kirim'            => $this->input->post('i_sl_kirim'),
            'n_bal'                 => $this->input->post('n_bal'),
            'v_cgr'                 => str_replace(",", "", $this->input->post('v_cgr')),
            'd_update'              => current_datetime(),
        );
        $this->db->where('i_cgr', $id);
        $this->db->update('tm_cgr', $header);

        if ($this->input->post('jml') > 0) {
            $i = 0;
            if (is_array($this->input->post('i_do')) || is_object($this->input->post('i_do'))) {
                $this->db->where('i_cgr', $id);
                $this->db->delete('tm_cgr_item');
                foreach ($this->input->post('i_do') as $i_do) {
                    $item = array(
                        'i_cgr'            => $id,
                        'i_do'             => $i_do,
                        'd_cgr'            => $this->input->post('d_document'),
                        'd_do'             => $this->input->post('d_do')[$i],
                        'v_jumlah'         => str_replace(",", "", $this->input->post('v_jumlah')[$i]),
                        'e_remark'         => $this->input->post('e_remark')[$i],
                        'n_item_no'        => $i,
                    );
                    $this->db->insert('tm_cgr_item', $item);
                    $i++;
                }
            }
        } else {
            die;
        }

        if ($this->input->post('jmlx') > 0) {
            $ii = 0;
            if (is_array($this->input->post('i_sl_ekspedisi')) || is_object($this->input->post('i_sl_ekspedisi'))) {
                $this->db->where('i_cgr', $id);
                $this->db->delete('tm_cgr_ekspedisi_item');
                foreach ($this->input->post('i_sl_ekspedisi') as $i_sl_ekspedisi) {
                    $item = array(
                        'i_cgr'            => $id,
                        'i_cgr_ekspedisi'  => $i_sl_ekspedisi,
                        'e_remark'         => $this->input->post('e_remark')[$ii],
                        'n_item_no'        => $ii,
                    );
                    $this->db->insert('tm_cgr_ekspedisi_item', $item);
                    $ii++;
                }
            }
        }
    }

    /** Cancel Data */
    public function cancel($id)
    {
        $table = array(
            'f_cgr_cancel' => 't',
        );
        $this->db->where('i_cgr', $id);
        $this->db->update('tm_cgr', $table);
    }

    /** Update Print */
    public function update_print($id)
    {
        $this->db->query("UPDATE tm_sl SET n_print = n_print + 1 WHERE i_sl = '$id' ", FALSE);
    }
}

/* End of file Mmaster.php */
