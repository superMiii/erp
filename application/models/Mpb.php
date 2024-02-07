<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mpb extends CI_Model
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
                d_gs as tgl,
                a.i_gs AS id,
                d_gs,
                i_gs_id,
                to_char(d_gs, 'YYYYMM') as i_periode,
                b.e_area_name,
                f_gs_cancel AS f_status,
                a.d_acc as d_approve1,
                a.d_gs_receive as d_approve2,
                a.n_print,                
                '$dfrom' AS dfrom,
                '$dto' AS dto,
                '$i_area' AS i_area
            FROM
                tm_gs2 a
            INNER JOIN tr_area b ON
                (b.i_area = a.i_area)
            INNER JOIN tm_user_area c ON
                (c.i_area = b.i_area)
            WHERE
                c.i_user = '$this->i_user'
                AND a.i_company = '$this->i_company'
                AND d_gs BETWEEN '$dfrom' AND '$dto'
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

        $datatables->edit('f_status', function ($data) {
            if ($data['f_status'] == 't') {
                $status = $this->lang->line('Tidak Aktif');
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
            $d_approve1 = $data['d_approve1'];
            $d_approve2 = $data['d_approve2'];
            $dfrom      = $data['dfrom'];
            $dto        = $data['dto'];
            $i_area     = $data['i_area'];
            $i_periode  = $data['i_periode'];
            $data       = '';
            $data      .= "<a href='" . base_url() . $this->folder . '/view/' . encrypt_url($id) . '/' . encrypt_url($dfrom) . '/' . encrypt_url($dto) . '/' . encrypt_url($i_area) . "' title='View Data'><i class='fa fa-eye fa-lg warning darken-4 mr-1'></i></a>";
            if ($i_periode >= get_periode()) {
                if (check_role($this->i_menu, 3) && $f_status == 'f'  && ($d_approve1 == null || $d_approve1 == '') && ($d_approve2 == null || $d_approve2 == '')) {
                    $data      .= "<a href='" . base_url() . $this->folder . '/edit/' . encrypt_url($id)  . '/' .  encrypt_url($dfrom) . '/' . encrypt_url($dto) . '/' . encrypt_url($i_area) . "' title='Edit Data'><i class='fa fa-pencil-square success darken-4 fa-lg mr-1'></i></a>";
                }
                if (check_role($this->i_menu, 5) && $f_status == 'f') {
                    $data      .= "<a href='#' onclick='openLink(\"" . $this->folder . "\",\"" . encrypt_url($id) . "\"); return false;' title='Print Data'><i class='fa fa-print fa-lg blue darken-4 mr-1'></i></a>";
                }
                if (check_role($this->i_menu, 4) && $f_status == 'f' && ($d_approve1 == null || $d_approve1 == '') && ($d_approve2 == null || $d_approve2 == '')) {
                    $data      .= "<a href='#' onclick='sweetdeletev33(\"" . $this->folder . "\",\"" . $id . "\",\"" . encrypt_url($dfrom)  . "\",\"" . encrypt_url($dto)  . '/' . encrypt_url($i_area) . "\");' title='Delete Data'><i class='fa fa-trash fa-lg red darken-4'></i></a>";
                }
            }
            return $data;
        });
        $datatables->hide('d_approve1');
        $datatables->hide('d_approve2');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('i_area');
        $datatables->hide('i_periode');
        $datatables->hide('id');
        return $datatables->generate();
    }

    /** Running Number Dokumen*/
    public function running_number($thbl, $tahun)
    {
        $cek = $this->db->query("SELECT 
                substring(i_gs_id, 1, 2) AS kode 
            FROM tm_gs2 
            WHERE i_company = '$this->i_company'
            ORDER BY i_gs DESC");
        if ($cek->num_rows() > 0) {
            $kode = $cek->row()->kode;
        } else {
            $kode = 'PB';
        }
        $query  = $this->db->query("SELECT
                max(substring(i_gs_id, 9, 6)) AS max
            FROM
                tm_gs2
            WHERE to_char (d_gs, 'yyyy') >= '$tahun'
            AND i_company = '$this->i_company'
            AND substring(i_gs_id, 1, 2) = '$kode'
            AND substring(i_gs_id, 4, 2) = substring('$thbl',1,2)
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
        $i_document = $this->input->post('i_document', TRUE);
        return $this->db->query("SELECT 
                i_gs_id
            FROM 
                tm_gs2
            WHERE 
                upper(trim(i_gs_id)) = upper(trim('$i_document'))
                AND i_company = '$this->i_company'
        ", FALSE);
    }

    /** Get Data Gudang */
    public function get_store($cari)
    {
        return $this->db->query("SELECT 
        DISTINCT
        a.i_store, 
        i_store_id, 
        initcap(e_store_name) AS e_store_name
    FROM 
        tr_store a
    INNER JOIN tr_area b 
        ON (b.i_store = a.i_store) 
    INNER JOIN tm_user_area c 
        ON (b.i_area = c.i_area) 
    WHERE 
        (e_store_name ILIKE '%$cari%' OR i_store_id ILIKE '%$cari%')
        AND a.i_company = '$this->i_company' 
        AND f_store_active = true
        AND c.i_user = '$this->i_user' 
        AND f_store_pusat = 'f'
    ORDER BY 3 ASC
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

    /** Get Data Product */
    public function get_product($cari, $i_store)
    {
        return $this->db->query("SELECT
                a.i_product,
                a.i_product_id,
                initcap(a.e_product_name) AS e_product_name
            FROM
                tr_product a
            INNER JOIN tr_customer_price b ON
                (b.i_product = a.i_product)
            INNER JOIN tr_product_grade c ON
                (c.i_product_grade = b.i_product_grade)
            INNER JOIN tr_price_group d ON
                (d.i_price_group = b.i_price_group)
            INNER JOIN tr_product_motif e ON
                (e.i_product_motif = a.i_product_motif)
            inner join tm_ic t on (t.i_product=a.i_product)
            inner join tr_store tt on (tt.i_store=t.i_store)
            WHERE
                (i_product_id ILIKE '%$cari%'
                    OR a.e_product_name ILIKE '%$cari%')
                AND a.i_company = '$this->i_company'
                AND c.f_default = 't'
                AND c.f_product_gradeactive = 't'
                AND d.f_default2 = 't'
                and tt.f_store_pusat = 'f'
                and t.n_quantity_stock > 0
                and tt.i_store = '$i_store'
            ORDER BY
                e_product_name ASC
        ", FALSE);
    }

    /** Get Data Detail Product */
    public function get_product_detail($i_product, $i_store)
    {
        return $this->db->query("SELECT
                a.i_product,
                b.i_product_grade,
                a.i_product_motif,
                b.v_price,
                initcap(a.e_product_name) AS e_product_name,
                c.e_product_gradename as e_product_motifname,
                p.n_quantity_stock as n_stk
            FROM
                tr_product a
            INNER JOIN tr_customer_price b ON
                (b.i_product = a.i_product)
            INNER JOIN tr_product_grade c ON
                (c.i_product_grade = b.i_product_grade)
            INNER JOIN tr_price_group d ON
                (d.i_price_group = b.i_price_group)
            INNER JOIN tr_product_motif e ON
                (e.i_product_motif = a.i_product_motif)
            inner join tm_ic p on (p.i_product=a.i_product)
            WHERE
                a.i_product = '$i_product'
                AND c.f_default = 't'
                AND c.f_product_gradeactive = 't'
                AND d.f_default2 = 't'
                AND a.i_company = '$this->i_company'
                and p.i_store = '$i_store'
        ", FALSE);
    }

    /** Simpan Data */
    public function save()
    {
        $query = $this->db->query("SELECT max(i_gs)+1 AS id FROM tm_gs2", TRUE);
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
            'i_company'         => $this->session->i_company,
            'i_gs'              => $id,
            'i_area'            => $this->input->post('i_area'),
            'i_gs_id'           => strtoupper($this->input->post('i_document')),
            'd_gs'              => $this->input->post('d_document'),
            'd_gs_entry'        => current_datetime(),
            'e_remark'          => $this->input->post('e_remark'),
            'i_store'           => $this->input->post('i_store'),
            'i_store_loc'       => $this->input->post('i_store'),
            'n_print'           => 0,
        );
        $this->db->insert('tm_gs2', $header);
        if ($this->input->post('jml') > 0) {
            $i = 0;
            if (is_array($this->input->post('i_product')) || is_object($this->input->post('i_product'))) {
                foreach ($this->input->post('i_product') as $i_product) {
                    $item = array(
                        'i_gs'              => $id,
                        'i_product'         => $i_product,
                        'i_product_grade'   => $this->input->post('i_product_grade')[$i],
                        'i_product_motif'   => $this->input->post('i_product_motif')[$i],
                        'n_quantity_order'  => $this->input->post('n_order')[$i],
                        /* 'n_stock'           => $this->input->post('n_stock')[$i], */
                        'v_unit_price'      => $this->input->post('v_unit_price')[$i],
                        'e_remark'          => $this->input->post('e_remarkitem')[$i],
                        // 'i_area'            => $this->input->post('i_area'),
                        'n_item_no'         => $i,
                        /* 'n_acc'             => $this->input->post('n_acc')[$i], */
                        /* 'n_saldo'           => $this->input->post('n_saldo')[$i], */
                    );
                    $this->db->insert('tm_gs_item2', $item);
                    $i++;
                }
            }
        } else {
            die;
        }
    }

    /** Get Data Untuk Edit */
    public function getdata($id)
    {
        return $this->db->query("SELECT 
                a.*,
                b.e_store_name,
                c.i_area_id,
                c.e_area_name
            FROM 
                tm_gs2 a
            LEFT JOIN tr_store b ON 
                (b.i_store = a.i_store)
            LEFT JOIN tr_store_loc d ON 
                (d.i_store_loc = a.i_store_loc)
            INNER JOIN tr_area c ON 
                (c.i_area = a.i_area)
            WHERE
                i_gs = '$id'
        ", FALSE);
    }

    /** Get Data Untuk Edit */
    public function getdetail($id)
    {
        return $this->db->query("SELECT
            a.*,
            c.i_product_id ,
            c.e_product_name ,
            d.e_product_motifname ,
            e.e_product_gradename ,
            b.i_store ,
            b.i_store_loc,
	        p.n_quantity_stock as n_stk
        from
            tm_gs_item2 a
        inner join tm_gs2 b on (b.i_gs=a.i_gs)
        inner join tr_product c on (c.i_product=a.i_product)
        inner join tr_product_motif d on (d.i_product_motif=a.i_product_motif)
        inner join tr_product_grade e on (e.i_product_grade=a.i_product_grade)
        inner join tm_ic p on (p.i_product=a.i_product and p.i_store=b.i_store)
        WHERE a.i_gs = '$id'
        ", FALSE);
    }

    /** Cek Apakah Data Sudah Ada Pas Edit */
    public function cek_edit()
    {
        $i_document = $this->input->post('i_document', TRUE);
        $i_document_old = $this->input->post('i_document_old', TRUE);
        return $this->db->query("SELECT 
                i_gs_id
            FROM 
                tm_gs2
            WHERE 
                trim(upper(i_gs_id)) <> trim(upper('$i_document_old'))
                AND trim(upper(i_gs_id)) = trim(upper('$i_document'))
                AND i_company = '$this->i_company'
        ", FALSE);
    }

    /** Update Data */
    public function update()
    {
        $id = $this->input->post('id');
        $header = array(
            'i_company'         => $this->session->i_company,
            'i_area'            => $this->input->post('i_area'),
            'i_gs_id'           => $this->input->post('i_document'),
            'd_gs'              => $this->input->post('d_document'),
            'd_gs_update'       => current_datetime(),
            'e_remark'          => $this->input->post('e_remark'),
            'i_store'           => $this->input->post('i_store'),
            'i_store_loc'       => $this->input->post('i_store'),
            'n_print'           => 0,
        );
        $this->db->where('i_gs', $id);
        $this->db->update('tm_gs2', $header);
        $jml = $this->input->post('jml');
        if ($this->input->post('jml') > 0) {
            $i = 0;
            if (is_array($this->input->post('i_product')) || is_object($this->input->post('i_product'))) {
                $this->db->where('i_gs', $id);
                $this->db->delete('tm_gs_item2');
                foreach ($this->input->post('i_product') as $i_product) {
                    $item = array(
                        'i_gs'              => $id,
                        'i_product'         => $i_product,
                        'i_product_grade'   => $this->input->post('i_product_grade')[$i],
                        'i_product_motif'   => $this->input->post('i_product_motif')[$i],
                        'n_quantity_order'           => $this->input->post('n_order')[$i],
                        /* 'n_stock'           => $this->input->post('n_stock')[$i], */
                        'v_unit_price'      => $this->input->post('v_unit_price')[$i],
                        'e_remark'          => $this->input->post('e_remarkitem')[$i],
                        // 'i_area'            => $this->input->post('i_area'),
                        'n_item_no'         => $i,
                        /* 'n_acc'             => $this->input->post('n_acc')[$i], */
                        /* 'n_saldo'           => $this->input->post('n_saldo')[$i], */
                    );
                    $this->db->insert('tm_gs_item2', $item);
                    $i++;
                }
            }
        } else {
            die;
        }
    }

    /** Cancel Data */
    public function cancel($id)
    {
        $table = array(
            'f_gs_cancel' => 't',
        );
        $this->db->where('i_gs', $id);
        $this->db->update('tm_gs2', $table);
    }

    public function update_print($id)
    {
        $this->db->query("UPDATE tm_gs2 SET n_print = n_print + 1 WHERE i_gs = '$id' ", FALSE);
    }
}

/* End of file Mmaster.php */
