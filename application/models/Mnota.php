<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mnota extends CI_Model
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
        $datatables->query("SELECT DISTINCT
                a.i_nota,
                a.d_nota as tgl,
                a.i_nota AS id,
                a.d_nota,
                a.i_nota_id,
                to_char(a.d_nota, 'YYYYMM') AS i_periode,
                e.i_so_id,
                to_char(e.d_so, 'DD Month YYYY') AS d_so,
                f.i_customer_id || ' ~ ' || f.e_customer_name AS customer,
                g.e_area_name,                
                case when e.f_so_stockdaerah = 't' then 'CABANG' else 'PUSAT' end as e_pemenuhan,
                a.v_nota_netto as v_nota_netto,
                a.v_sisa as v_sisa,
                (a.d_jatuh_tempo + hh.n_toleransi) as d_jatuh_tempo,
                a.f_nota_cancel AS f_status,
                a.n_print,
                '$dfrom' as dfrom,
                '$dto' as dto,
                '$i_area' as i_area
            FROM
                tm_nota a
            INNER JOIN tm_nota_item b ON
                (b.i_nota = a.i_nota)
            INNER JOIN tm_so e ON
                (e.i_so = a.i_so)
            INNER JOIN tr_customer f ON
                (f.i_customer = a.i_customer)
            INNER JOIN tr_area g ON
                (g.i_area = a.i_area)
            INNER JOIN tm_user_area h ON
                (h.i_area = g.i_area)
            INNER JOIN tr_city hh ON
                (hh.i_city = f.i_city)
            WHERE
                a.i_company = '$this->i_company'
                AND h.i_user = '$this->i_user'
                AND a.d_nota BETWEEN '$dfrom' AND '$dto'
                $area
            ORDER BY
                a.i_nota ASC
        ", FALSE);

        $datatables->edit('v_nota_netto', function ($data) {
            return format_rupiah($data['v_nota_netto']);
        });

        $datatables->edit('v_sisa', function ($data) {
            return format_rupiah($data['v_sisa']);
        });

        // $datatables->edit('n_print', function ($data) {
        //     if ($data['n_print'] == '0') {
        //         $data = "<span class='badge bg-yellow bg-darken-3 badge-pill'>" . $this->lang->line('Belum') . "</span>";
        //     } else {
        //         $data = "<span class='badge bg-blue bg-darken-1 badge-pill'>" . $this->lang->line('Sudah') . ' ' . $data['n_print'] . ' x' . "</span>";
        //     }
        //     return $data;
        // });
        $datatables->edit('n_print', function ($data) {
            $id         = $data['i_nota'];
            if ($data['n_print'] == '0') {
                $status = $this->lang->line('Belum');
                $color  = 'success';
            } else {
                $status = $this->lang->line('Sudah');
                $color  = 'danger';
            }
            $data = "<button class='btn btn-outline-" . $color . " btn-sm round' onclick='changestatus(\"" . $this->folder . "\",\"" . $id . "\");'>" . $status . "</button>";
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
            $n_print        = $data['n_print'];
            $v_nota_netto   = $data['v_nota_netto'];
            $v_sisa         = $data['v_sisa'];
            $i_periode      = $data['i_periode'];
            $dfrom          = $data['dfrom'];
            $dto            = $data['dto'];
            $i_area         = $data['i_area'];
            $data           = '';
            $data          .= "<a href='" . base_url() . $this->folder . '/view/' . encrypt_url($id) . '/' . encrypt_url($dfrom) . '/' . encrypt_url($dto) . '/' . encrypt_url($i_area) . "' title='View Data'><i class='fa fa-eye fa-lg warning darken-4 mr-1'></i></a>";
            /* if (check_role($this->id_menu, 3) && $f_status == 'f') {
                $data      .= "<a href='" . base_url() . $this->folder . '/edit/' . encrypt_url($id) . "' title='Edit Data'><i class='fa fa-pencil success darken-4 fa-lg mr-1'></i></a>";
            } */
            if (check_role($this->id_menu, 5) && $f_status == 'f' && $n_print == '0') {
                $data      .= "<a href='#' onclick='openLink(\"" . $this->folder . "\",\"" . encrypt_url($id) . "\"); return false;' title='Print Data'><i class='fa fa-print fa-lg blue darken-4 mr-1'></i></a>";
            }
            if ($i_periode >= get_periode3()) {
                if (check_role($this->id_menu, 4) && $f_status == 'f' && $v_nota_netto == $v_sisa) {
                    $data      .= "<a href='#' onclick='sweetdeletev33raya(\"" . $this->folder . "\",\"" . $id . "\",\"" . encrypt_url($dfrom)  . "\",\"" . encrypt_url($dto)  . '/' . encrypt_url($i_area) . "\");' title='Delete Data'><i class='fa fa-trash fa-lg red darken-4'></i></a>";
                }
            }
            return $data;
        });
        $datatables->hide('i_periode');
        $datatables->hide('id');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('i_nota');
        $datatables->hide('i_area');
        return $datatables->generate();
    }

    public function changestatus($id)
    {
        $this->db->select('n_print');
        $this->db->from('tm_nota');
        $this->db->where('i_nota', $id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $status = $query->row()->n_print;
        } else {
            $status = '0';
        }
        if ($status == '0') {
            $fstatus = '0';
        } else {
            $fstatus = '0';
        }
        $table = array(
            'n_print' => $fstatus,
        );
        $this->db->where('i_nota', $id);
        $this->db->update('tm_nota', $table);
    }

    /** List Datatable */
    public function serversidex()
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
        $datatables->query("SELECT DISTINCT
                a.d_do as tgl,
                a.i_do AS id,
                a.d_do,
                a.i_do_id,
                to_char(d.d_so, 'DD Month YYYY') AS d_so,
                d.i_so_id,
                to_char(c.d_sl, 'DD Month YYYY') AS d_sl,
                to_char(a.d_do, 'YYYYMM') AS i_periode,
                c.i_sl_id,
                e.i_customer_id || ' ~ ' || e.e_customer_name AS customer,
                f.e_area_name,
                case when d.f_so_stockdaerah = 't' then 'CABANG' else 'PUSAT' end as e_pemenuhan,
                '$dfrom' AS dfrom,
                '$dto' AS dto,
                '$i_area' as i_area
            FROM
                tm_do a
            LEFT JOIN tm_sl_item b ON
                (b.i_do = a.i_do)
            LEFT JOIN tm_sl c ON
                (c.i_sl = b.i_sl)
            INNER JOIN tm_so d ON
                (d.i_so = a.i_so)
            INNER JOIN tr_customer e ON
                (e.i_customer = a.i_customer)
            INNER JOIN tr_area f ON
                (f.i_area = a.i_area)
            INNER JOIN tm_user_area g ON
                (g.i_area = a.i_area)
            WHERE
                a.i_company = '$this->i_company'
                AND g.i_user = '$this->i_user'
                AND a.d_do BETWEEN '$dfrom' AND '$dto'
                AND c.f_sl_batal = 'f'
                AND d.i_status_so = '7'
                AND a.f_do_cancel = 'f'
                $area
            ORDER BY
                1 ASC
        ", FALSE);

        /** Cek Hak Akses, Apakah User Bisa Edit */
        $datatables->add('action', function ($data) {
            $id         = trim($data['id']);
            $i_periode  = $data['i_periode'];
            $dfrom      = $data['dfrom'];
            $dto        = $data['dto'];
            $i_area     = $data['i_area'];
            $data       = '';
            if ($i_periode >= get_periode3()) {
                if (check_role($this->id_menu, 3)) {
                    $data      .= "<a href='" . base_url() . $this->folder . '/add/' . encrypt_url($id) . '/' . encrypt_url($dfrom) . '/' . encrypt_url($dto) . '/' . encrypt_url($i_area) . "' title='Tambah Data'><i class='fa fa-check-circle-o success darken-4 fa-lg mr-1'></i></a>";
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

    public function cek0()
    {
        return $this->db->query("UPDATE
                    tm_so
                set
                    i_status_so = 9
                from (select
                    ss.i_so 
                from tm_so ss
                inner join tm_nota nt on (nt.i_so=ss.i_so) where ss.i_company = '$this->i_company'  and i_status_so != 9) as so
                where tm_so.i_so=so.i_so 
                AND tm_so.i_company = '$this->i_company' 
        ", FALSE);
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

    /** Running Number Dokumen*/
    public function running_number($thbl, $tahun)
    {
        $cek = $this->db->query("
            SELECT 
                substring(i_nota_id, 1, 2) AS kode 
            FROM tm_nota 
            WHERE i_company = '$this->i_company'
            ORDER BY i_nota DESC");
        if ($cek->num_rows() > 0) {
            $kode = $cek->row()->kode;
        } else {
            $kode = 'FP';
        }
        $query  = $this->db->query("
            SELECT
                max(substring(i_nota_id, 9, 6)) AS max
            FROM
                tm_nota
            WHERE to_char (d_nota, 'yyyy') >= '$tahun'
            AND i_company = '$this->i_company'
            AND substring(i_nota_id, 1, 2) = '$kode'
            AND substring(i_nota_id, 4, 2) = substring('$thbl',1,2)
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
                i_nota_id
            FROM 
                tm_nota 
            WHERE 
                upper(trim(i_nota_id)) = upper(trim('$i_document'))
                AND i_company = '$this->i_company'
        ", FALSE);
    }

    /** Simpan Data */
    public function save()
    {
        $so = array(
            'i_status_so' => 9,
        );
        $this->db->where('i_so', $this->input->post('i_so'));
        $this->db->update('tm_so', $so);

        $query = $this->db->query("SELECT max(i_nota)+1 AS id FROM tm_nota", TRUE);
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

        $f_plus_meterai  = $this->session->f_plus_meterai;
        $v_meterai_limit = (float)$this->session->v_meterai_limit;
        $v_nota          = (float)str_replace(",", "", $this->input->post('v_nota_netto'));
        if ($f_plus_meterai == 't' && $v_nota >= $v_meterai_limit) {
            $v_meterai   = (float)$this->session->v_meterai;
        } else {
            $v_meterai   = 0;
        };
        $f_masalah  = ($this->input->post('f_masalah') == 'on') ? 't' : 'f';
        $f_insentif = ($this->input->post('f_insentif') == 'on') ? 't' : 'f';

        $notta = str_replace("_", "", $this->input->post('i_document'));
        $soo = $this->input->post('i_so');

        $header = array(
            'i_company'                 => $this->session->i_company,
            'i_nota'                    => $id,
            'i_nota_id'                 => $notta,
            'i_area'                    => $this->input->post('i_area'),
            'i_customer'                => $this->input->post('i_customer'),
            'd_nota'                    => $this->input->post('d_nota'),
            'd_jatuh_tempo'             => date('Y-m-d', strtotime($this->input->post('d_jatuh_tempo'))),
            'd_nota_entry'              => current_datetime(),
            'e_remark'                  => $this->input->post('e_remark'),
            'f_masalah'                 => $f_masalah,
            'f_insentif'                => $f_insentif,
            'v_nota_gross'              => str_replace(",", "", $this->input->post('v_nota_gross')),
            'v_nota_ppn'                => str_replace(",", "", $this->input->post('v_nota_ppn')),
            'v_nota_discount'           => str_replace(",", "", $this->input->post('v_nota_discount')),
            'v_nota_netto'              => str_replace(",", "", $this->input->post('v_nota_netto')),
            'v_sisa'                    => str_replace(",", "", $this->input->post('v_nota_netto')),
            'n_print'                   => 0,
            'n_faktur_komersialprint'   => 0,
            'v_meterai'                 => $v_meterai,
            'v_meterai_sisa'            => $v_meterai,
            'i_so'                      => $soo,
            'f_so_stockdaerah'          => $this->input->post('f_so_stockdaerah'),
        );
        $this->db->insert('tm_nota', $header);

        if ($this->input->post('jml') > 0) {
            $i = 0;
            if (is_array($this->input->post('i_product')) || is_object($this->input->post('i_product'))) {
                foreach ($this->input->post('i_product') as $i_product) {
                    $item = array(
                        'i_nota'           => $id,
                        'i_do'             => $this->input->post('i_do')[$i],
                        'd_do'             => $this->input->post('d_do')[$i],
                        'i_product'        => $i_product,
                        'i_product_grade'  => $this->input->post('i_product_grade')[$i],
                        'i_product_motif'  => $this->input->post('i_product_motif')[$i],
                        'n_deliver'        => str_replace(",", "", $this->input->post('n_deliver')[$i]),
                        'v_unit_price'     => str_replace(",", "", $this->input->post('v_unit_price')[$i]),
                        'e_product_name'   => $this->input->post('e_product_name')[$i],
                        'n_item_no'        => $i,
                        'n_nota_discount1' => str_replace(",", "", $this->input->post('n_nota_discount1')[$i]),
                        'v_nota_discount1' => str_replace(",", "", $this->input->post('v_nota_discount1')[$i]),
                        'n_nota_discount2' => str_replace(",", "", $this->input->post('n_nota_discount2')[$i]),
                        'v_nota_discount2' => str_replace(",", "", $this->input->post('v_nota_discount2')[$i]),
                        'n_nota_discount3' => str_replace(",", "", $this->input->post('n_nota_discount3')[$i]),
                        'v_nota_discount3' => str_replace(",", "", $this->input->post('v_nota_discount3')[$i]),
                        'n_nota_discount4' => str_replace(",", "", $this->input->post('n_nota_discount4')[$i]),
                        'v_nota_discount4' => str_replace(",", "", $this->input->post('v_nota_discount4')[$i]),
                    );
                    $this->db->insert('tm_nota_item', $item);
                    $i++;
                }
            }
        } else {
            die;
        }

        $query2 = $this->db->query("select
                                        e.i_sl 
                                    from
                                    tm_so b 
                                    inner join tm_do c on (c.i_so=b.i_so)
                                    inner join tm_sl_item d on (d.i_do=c.i_do)
                                    inner join tm_sl e on (e.i_sl=d.i_sl) where b.i_so = '$soo' ", FALSE);
                if ($query2->num_rows() > 0) {
                    foreach ($query2->result() as $key) {
                        $this->db->query("UPDATE tm_sl SET i_nota_id = '$notta' WHERE i_sl = '$key->i_sl' ", FALSE);
                    }
                }
    }

    /** Get Data Untuk Tambah */
    public function get_data($id)
    {
        return $this->db->query("SELECT 
                a.*,
                d.i_so as soso,
                d.d_so,
                d.i_so_id,
                b.i_area_id,
                b.e_area_name,
                e.i_salesman_id,
                e.e_salesman_name,
                c.i_customer_id,
                c.e_customer_name,
                f.e_price_groupname,
                d.e_customer_pkpnpwp,
                d.n_so_ppn,
                d.f_so_plusppn,
                d.v_so_discounttotal,
                COALESCE(d.n_so_toplength, 0) AS n_so_toplength
            FROM 
                tm_do a
            INNER JOIN tr_area b ON 
                (b.i_area = a.i_area)
            INNER JOIN tr_customer c ON 
                (c.i_customer = a.i_customer)
            INNER JOIN tr_salesman e ON 
                (e.i_salesman = a.i_salesman)
            INNER JOIN tm_so d ON 
                (d.i_so = a.i_so and d.i_company = a.i_company)
            INNER JOIN tr_price_group f ON
	            (f.i_price_group = d.i_price_group)
            WHERE a.i_do = '$id'
        ", FALSE);
    }

    /** Get Detail Untuk Tambah */
    public function get_data_detail($id)
    {
        return $this->db->query("SELECT
                a.i_do,
                b.d_do AS tanggal_do,
                a.i_product,
                c.i_product_id,
                c.e_product_name,
                a.i_product_grade,
                a.i_product_motif,
                a.n_deliver,
                d.v_unit_price,
                a.e_product_name,
                a.n_deliver,
                d.n_so_discount1 AS n_so_discount1,
                d.v_so_discount1 AS v_so_discount1,
                d.n_so_discount2 AS n_so_discount2,
                d.v_so_discount2 AS v_so_discount2,
                d.n_so_discount3 AS n_so_discount3,
                d.v_so_discount3 AS v_so_discount3,
                d.n_so_discount4 AS n_so_discount4,
                d.v_so_discount4 AS v_so_discount4
            FROM
                tm_do_item a
            INNER JOIN tm_do b ON
                (b.i_do = a.i_do)
            INNER JOIN tr_product c ON
                (c.i_product = a.i_product)
            INNER JOIN tm_so_item d ON
                (d.i_so = b.i_so
                AND a.i_product = d.i_product)
            /* INNER JOIN tm_so e ON
                (e.i_so = d.i_so) */
            WHERE
                a.i_do = '$id'
                AND a.n_deliver > 0
            ORDER BY
                c.e_product_name ASC 
        ", FALSE);
    }

    /** Get Data Untuk Edit */
    public function get_data_edit($id)
    {
        return $this->db->query("SELECT
                DISTINCT a.*,
                a.i_customer,
                h.i_salesman,
                c.i_do,
                c.i_do_id,
                c.d_do,
                d.i_so,
                d.d_so,
                d.i_so_id,
                d.n_so_ppn,
                d.f_so_plusppn,
                d.v_so_discounttotal,
                g.i_area_id,
                g.e_area_name,
                h.i_salesman_id,
                h.e_salesman_name,
                e.i_customer_id,
                e.e_customer_name,
                e.e_customer_address,
                e.e_customer_phone,
                i.e_city_name,
                f.e_price_groupname,
                d.e_customer_pkpnpwp,
                COALESCE(d.n_so_toplength, 0) AS n_so_toplength,
                e.f_top30
            FROM
                tm_nota a
            INNER JOIN tm_nota_item b ON
                (b.i_nota = a.i_nota)
            INNER JOIN tm_do c ON
                (c.i_do = b.i_do)
            INNER JOIN tm_so d ON
                (d.i_so = c.i_so)
            INNER JOIN tr_customer e ON
                (e.i_customer = a.i_customer)
            INNER JOIN tr_price_group f ON
                (f.i_price_group = d.i_price_group)
            INNER JOIN tr_area g ON
                (g.i_area = a.i_area)
            INNER JOIN tr_salesman h ON
                (h.i_salesman = d.i_salesman)
            INNER JOIN tr_city i ON
                (i.i_city = e.i_city)
            WHERE
                a.i_nota = '$id'
        ", FALSE);
    }

    /** Get Detail Untuk Edit */
    public function get_data_detail_edit($id)
    {
        return $this->db->query("SELECT 
                a.*,
                b.i_product_id,
                b.e_product_name,
                c.e_product_motifname
            FROM 
                tm_nota_item a
            INNER JOIN tr_product b ON 
                (b.i_product = a.i_product)
            INNER JOIN tr_product_motif c ON 
                (c.i_product_motif = a.i_product_motif)
            WHERE a.i_nota = '$id'
            order by b.e_product_name
        ", FALSE);
    }

    /** Cek Apakah Data Sudah Ada Pas Edit */
    public function cek_edit()
    {
        $i_document = str_replace("_", "", $this->input->post('i_document'));
        $i_document_old = str_replace("_", "", $this->input->post('i_document_old'));
        return $this->db->query("
             SELECT 
                i_nota_id
             FROM 
                tm_nota 
             WHERE 
                trim(upper(i_nota_id)) <> trim(upper('$i_document_old'))
                AND trim(upper(i_nota_id)) = trim(upper('$i_document'))
                AND i_company = '$this->i_company'
         ", FALSE);
    }

    /** Update Data */
    public function update()
    {
        // $so = array(
        //     'i_status_so' => 9,
        // );
        // $this->db->where('i_so', $this->input->post('i_so'));
        // $this->db->update('tm_so', $so);

        $id = $this->input->post('id');
        $f_masalah  = ($this->input->post('f_masalah') == 'on') ? 't' : 'f';
        $f_insentif = ($this->input->post('f_insentif') == 'on') ? 't' : 'f';
        $f_plus_meterai  = $this->session->f_plus_meterai;
        $v_meterai_limit = (float)$this->session->v_meterai_limit;
        $v_nota          = (float)str_replace(",", "", $this->input->post('v_nota_netto'));
        if ($f_plus_meterai == 't' && $v_nota >= $v_meterai_limit) {
            $v_meterai   = (float)$this->session->v_meterai;
        } else {
            $v_meterai   = 0;
        };
        $header = array(
            'i_company'                 => $this->session->i_company,
            'i_nota_id'                 => str_replace("_", "", $this->input->post('i_document')),
            'i_area'                    => $this->input->post('i_area'),
            'i_customer'                => $this->input->post('i_customer'),
            'd_nota'                    => date('Y-m-d', strtotime($this->input->post('d_document'))),
            'd_jatuh_tempo'             => date('Y-m-d', strtotime($this->input->post('d_jatuh_tempo'))),
            'd_nota_update'             => current_datetime(),
            'e_remark'                  => $this->input->post('e_remark'),
            'f_masalah'                 => $f_masalah,
            'f_insentif'                => $f_insentif,
            'v_nota_gross'              => str_replace(",", "", $this->input->post('v_nota_gross')),
            'v_nota_ppn'                => str_replace(",", "", $this->input->post('v_nota_ppn')),
            'v_nota_discount'           => str_replace(",", "", $this->input->post('v_nota_discount')),
            'v_nota_netto'              => str_replace(",", "", $this->input->post('v_nota_netto')),
            'v_sisa'                    => str_replace(",", "", $this->input->post('v_nota_netto')),
            'n_print'                   => 0,
            'n_faktur_komersialprint'   => 0,
            'n_pajak_print'             => 0,
            'v_meterai'                 => $v_meterai,
            'v_meterai_sisa'            => $v_meterai,
        );
        $this->db->where('i_nota', $id);
        $this->db->update('tm_nota', $header);

        if ($this->input->post('jml') > 0) {
            $i = 0;
            if (is_array($this->input->post('i_product')) || is_object($this->input->post('i_product'))) {
                $this->db->where('i_nota', $id);
                $this->db->delete('tm_nota_item');
                foreach ($this->input->post('i_product') as $i_product) {
                    $item = array(
                        'i_nota'           => $id,
                        'i_do'             => $this->input->post('i_do')[$i],
                        'd_do'             => $this->input->post('d_do')[$i],
                        'i_product'        => $i_product,
                        'i_product_grade'  => $this->input->post('i_product_grade')[$i],
                        'i_product_motif'  => $this->input->post('i_product_motif')[$i],
                        'n_deliver'        => str_replace(",", "", $this->input->post('n_deliver')[$i]),
                        'v_unit_price'     => str_replace(",", "", $this->input->post('v_unit_price')[$i]),
                        'e_product_name'   => $this->input->post('e_product_name')[$i],
                        'n_item_no'        => $i,
                        'n_nota_discount1' => str_replace(",", "", $this->input->post('n_nota_discount1')[$i]),
                        'v_nota_discount1' => str_replace(",", "", $this->input->post('v_nota_discount1')[$i]),
                        'n_nota_discount2' => str_replace(",", "", $this->input->post('n_nota_discount2')[$i]),
                        'v_nota_discount2' => str_replace(",", "", $this->input->post('v_nota_discount2')[$i]),
                        'n_nota_discount3' => str_replace(",", "", $this->input->post('n_nota_discount3')[$i]),
                        'v_nota_discount3' => str_replace(",", "", $this->input->post('v_nota_discount3')[$i]),
                        'n_nota_discount4' => str_replace(",", "", $this->input->post('n_nota_discount4')[$i]),
                        'v_nota_discount4' => str_replace(",", "", $this->input->post('v_nota_discount4')[$i]),
                    );
                    $this->db->insert('tm_nota_item', $item);
                    $i++;
                }
            }
        } else {
            die;
        }
    }

    /** Cancel Data */
    public function cancel($id, $alasan, $i_nota_id)
    {
        $table = array(
            'f_nota_cancel' => 't',
        );
        $this->db->where('i_nota', $id);
        $this->db->update('tm_nota', $table);

        $this->db->query("INSERT INTO tx_del (i_company, i_user, e_user_name, e_dokumen, e_alasan)
        VALUES ('$this->i_company', '$this->i_user', '$this->e_user_name', 'No NOTA Penjualan : $i_nota_id', '$alasan')");

        $this->db->query("update
                tm_do
        set
            f_do_cancel = 't'
        where 
                i_do = (
            select
                    distinct i_do
            from
                    tm_nota_item
            where
                    i_nota = $id)");

        $this->db->query("update
                tm_so
            set
                f_so_cancel = 't'
            where
                i_so = (
                select
                    i_so
                from
                    tm_do
                where
                    i_do = (
                    select
                        distinct i_do
                    from
                        tm_nota_item
                    where
                        i_nota = $id))");
    }

    /** Update Print */
    public function update_print($id)
    {
        $this->db->query("UPDATE tm_nota SET n_print = n_print + 1 WHERE i_nota = '$id' ", FALSE);
    }
}

/* End of file Mmaster.php */
