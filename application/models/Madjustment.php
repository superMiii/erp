<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Madjustment extends CI_Model
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

        $i_store = $this->input->post('i_store', TRUE);
        if ($i_store == '') {
            $i_store = $this->uri->segment(5);
        }

        if ($i_store != '0') {
            $store = "AND a.i_store = '$i_store' ";
        } else {
            $store = "";
        }

        $dfrom  = date('Y-m-d', strtotime($dfrom));
        $dto    = date('Y-m-d', strtotime($dto));

        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("SELECT
                d_adjustment as tgl,
                i_adjustment AS id,
                d_adjustment,
                i_adjustment_id,
                to_char(d_adjustment, 'YYYYMM') as i_periode,
                i_stockopname_id,
                c.e_store_name,
                a.e_remark,
                a.f_adjustment_cancel AS f_status,
                d_approve,
                initcap(g.e_user_name) AS e_name,
                '$dfrom' AS dfrom,
                '$dto' AS dto
            FROM
                tm_adjustment a
            INNER JOIN tr_area b ON
                (b.i_area = a.i_area)
            INNER JOIN tr_store c ON
                (c.i_store = a.i_store)
            INNER JOIN tm_user_area e ON
                (e.i_area = b.i_area)
            INNER JOIN tm_stockopname f ON 
                (f.i_stockopname = a.i_stockopname)
            LEFT JOIN tm_user g 
                ON (g.i_user = a.i_approve::int)
            WHERE
                a.i_company = $this->i_company
                AND e.i_user = $this->i_user
                AND d_adjustment BETWEEN '$dfrom' AND '$dto'
                $store
            ORDER BY 1 DESC 
        ", FALSE);

        $datatables->edit('f_status', function ($data) {
            if ($data['f_status'] == 't') {
                $status = $this->lang->line('Tidak Aktif');
                $color  = 'red';
            } elseif ($data['f_status'] == 'f' && ($data['d_approve'] == '' || $data['d_approve'] == null)) {
                $color  = 'warning';
                $status = $this->lang->line('Menunggu Persetujuan');
            } else {
                $color  = 'teal';
                $status = $this->lang->line('Disetujui Oleh') . ' ' . $data['e_name'];
            }
            $data = "<span class='badge bg-" . $color . " badge-pill'>" . $status . "</span>";
            return $data;
        });

        /** Cek Hak Akses, Apakah User Bisa Edit */
        $datatables->add('action', function ($data) {
            $id         = trim($data['id']);
            $f_status   = $data['f_status'];
            $dfrom      = $data['dfrom'];
            $dto        = $data['dto'];
            $d_approve  = $data['d_approve'];
            $i_periode  = $data['i_periode'];
            $data       = '';
            $data      .= "<a href='" . base_url() . $this->folder . '/view/' . encrypt_url($id) . '/' . encrypt_url($dfrom) . '/' . encrypt_url($dto) . "' title='View Data'><i class='fa fa-eye fa-lg warning darken-4 mr-1'></i></a>";
            if ($i_periode >= get_periode3()) {
                if ($d_approve == null || $d_approve == '') {
                    if (check_role($this->i_menu, 3) && $f_status == 'f') {
                        $data      .= "<a href='" . base_url() . $this->folder . '/edit/' . encrypt_url($id)  . '/' .  encrypt_url($dfrom) . '/' . encrypt_url($dto) . "' title='Edit Data'><i class='fa fa-pencil-square success darken-4 fa-lg mr-1'></i></a>";
                    }
                    if (check_role($this->i_menu, 4) && $f_status == 'f') {
                        $data      .= "<a href='#' onclick='sweetdeletev2link(\"" . $this->folder . "\",\"" . $id . "\",\"" . encrypt_url($dfrom)  . "\",\"" . encrypt_url($dto)  . "\");' title='Delete Data'><i class='fa fa-trash fa-lg red darken-4'></i></a>";
                    }
                }
            }
            return $data;
        });
        $datatables->hide('e_name');
        $datatables->hide('d_approve');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('i_periode');
        $datatables->hide('id');
        return $datatables->generate();
    }

    /** Running Number Dokumen*/
    public function running_number($thbl, $tahun)
    {
        $cek = $this->db->query("SELECT 
                substring(i_adjustment_id, 1, 3) AS kode 
            FROM tm_adjustment 
            WHERE i_company = '$this->i_company'
            ORDER BY i_adjustment DESC");
        if ($cek->num_rows() > 0) {
            $kode = $cek->row()->kode;
        } else {
            $kode = 'ADJ';
        }
        $query  = $this->db->query("SELECT
                max(substring(i_adjustment_id, 10, 6)) AS max
            FROM
                tm_adjustment
            WHERE to_char (d_adjustment, 'yyyy') >= '$tahun'
            AND i_company = '$this->i_company'
            AND substring(i_adjustment_id, 1, 3) = '$kode'
            AND substring(i_adjustment_id, 5, 2) = substring('$thbl',1,2)
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
                i_adjustment_id
            FROM 
                tm_adjustment 
            WHERE 
                upper(trim(i_adjustment_id)) = upper(trim('$i_document'))
                AND i_company = '$this->i_company'
        ", FALSE);
    }

    /** Get Area */
    public function get_store0($cari)
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
    ORDER BY 3 ASC
        ", FALSE);
    }

    /** Get Data Gudang */
    public function get_store($cari)
    {
        return $this->db->query("SELECT DISTINCT
                b.i_store_loc AS id,
                b.i_store_loc_id AS code,
                b.e_store_loc_name AS name,
                e_store_name AS e_name
            FROM
                tr_store a
            INNER JOIN tr_store_loc b ON
                (b.i_store = a.i_store)
            INNER JOIN tr_area c ON
                (c.i_store = a.i_store)
            INNER JOIN tm_user_area d ON
                (d.i_area = c.i_area
                    AND d.i_user = '$this->i_user')
            WHERE
                (e_store_name ILIKE '%$cari%'
                    OR i_store_id ILIKE '%$cari%'
                    OR i_store_loc_id ILIKE '%$cari%'
                    OR e_store_loc_name ILIKE '%$cari%')
                AND f_store_active = 't'
                AND b.f_store_loc_active = 't'
                AND a.i_company = '$this->i_company'
            ORDER BY
                e_store_name;
        ", FALSE);
        /* return $this->db->query("
            SELECT
                DISTINCT 	
                a.i_store,
                i_store_id,
                e_store_name
            FROM
                tr_store a
            INNER JOIN tr_area b ON
                (b.i_store = a.i_store)
            WHERE
                (e_store_name ILIKE '%$cari%'
                OR i_store_id ILIKE '%$cari%')
                AND f_store_active = 't'
                AND a.i_company = '$this->i_company'
                AND b.i_area IN (
                SELECT
                    i_area
                FROM
                    tm_user_area
                WHERE
                    i_user = '$this->i_user'
                    AND i_company = '$this->i_company')
            ORDER BY
                e_store_name
        ", FALSE); */
    }

    /** Get Data Area */
    public function get_area($i_store)
    {
        return $this->db->query("SELECT
                a.i_store,
                b.i_area
            FROM
                tr_store_loc a
            INNER JOIN tr_area b ON
                (b.i_store = a.i_store)
            WHERE
                i_store_loc = '$i_store'
        ", FALSE);
        /* return $this->db->query("
            SELECT
                i_area AS id,
                i_area_id AS code,
                initcap(e_area_name) AS name
            FROM
                tr_area
            WHERE
                (e_area_name ILIKE '%$cari%'
                    OR i_area_id ILIKE '%$cari%')
                AND f_area_active = 't'
                AND i_company = '$this->i_company'
            ORDER BY 3
        ", FALSE); */
    }

    /** Get Data Product */
    public function get_product($i_store_loc, $i_store, $i_area, $d_so)
    {
        $d_so = ($d_so != '' || $d_so != null) ? date('Ym', strtotime('-1 month', strtotime($d_so))) : date('Ym', strtotime('-1 month', strtotime(date('Y-m'))));
        $query = $this->db->query("SELECT
                i_adjustment
            FROM
                tm_adjustment
            WHERE
                i_store = '$i_store'
                AND i_store_loc = '$i_store_loc'
                AND i_area = '$i_area'
                AND to_char(d_adjustment, 'YYYYmm') = '$d_so'", FALSE);
        if ($query->num_rows() > 0) {
            return $this->db->query("SELECT
                    a.i_product,
                    c.i_product_id,
                    initcap(c.e_product_name) AS e_product_name,
                    a.i_product_grade,
                    d.e_product_gradename,
                    a.i_product_motif,
                    e.e_product_motifname
                FROM
                    tm_adjustment_item a
                INNER JOIN tm_adjustment b ON
                    (b.i_adjustment = a.i_adjustment)
                INNER JOIN tr_product c ON
                    (c.i_product = a.i_product)
                INNER JOIN tr_product_grade d ON
                    (d.i_product_grade = a.i_product_grade)
                INNER JOIN tr_product_motif e ON
                    (e.i_product_motif = a.i_product_motif)
                WHERE
                    b.i_company = '$this->i_company'
                    AND b.f_adjustment_cancel = 'f'
                    AND b.i_store = '$i_store'
                    AND b.i_store_loc = '$i_store_loc'
                    AND b.i_area = '$i_area'
                    AND to_char(b.d_adjustment, 'YYYYmm') = '$d_so'
                    -- AND c.f_product_active = 't'
                ORDER BY c.e_product_name ASC
            ", FALSE);
        } else {
            return $this->db->query("SELECT
                    a.i_product,
                    c.i_product_id,
                    initcap(c.e_product_name) AS e_product_name,
                    a.i_product_grade,
                    d.e_product_gradename,
                    a.i_product_motif,
                    e.e_product_motifname
                FROM
                    tm_ic a
                INNER JOIN tr_product c ON
                    (c.i_product = a.i_product)
                INNER JOIN tr_product_grade d ON
                    (d.i_product_grade = a.i_product_grade)
                INNER JOIN tr_product_motif e ON
                    (e.i_product_motif = a.i_product_motif)
                WHERE
                    a.i_company = '$this->i_company'
                    AND a.i_store = '$i_store'
                    AND a.i_store_location = '$i_store_loc'
                    -- AND a.f_product_active = 't'
                    -- AND c.f_product_active = 't'
                ORDER BY c.e_product_name ASC
        ", FALSE);
        }
    }

    /** Get Data Product */
    public function get_product_add($i_store_loc, $i_store, $cari)
    {
        return $this->db->query("SELECT
                a.i_product,
                c.i_product_id,
                initcap(c.e_product_name) AS e_product_name,
                a.i_product_grade,
                d.e_product_gradename,
                a.i_product_motif,
                e.e_product_motifname
            FROM
                tm_ic a
            INNER JOIN tr_product c ON
                (c.i_product = a.i_product)
            INNER JOIN tr_product_grade d ON
                (d.i_product_grade = a.i_product_grade)
            INNER JOIN tr_product_motif e ON
                (e.i_product_motif = a.i_product_motif)
            WHERE
                a.i_company = '$this->i_company'
                AND (c.i_product_id ILIKE '%$cari%' OR c.e_product_name ILIKE '%$cari%')
                AND a.i_store = '$i_store'
                AND a.i_store_location = '$i_store_loc'
                -- AND a.f_product_active = 't'
                -- AND c.f_product_active = 't'
            ORDER BY c.e_product_name ASC
        ", FALSE);
    }

    /** Get Data Stockopname */
    public function get_stockopname($i_store_loc, $i_store, $i_area, $cari)
    {
        return $this->db->query("SELECT
                i_stockopname,
                i_stockopname_id,
                to_char(d_stockopname, 'DD FMMonth YYYY') AS d_stockopname
            FROM
                tm_stockopname
            WHERE
                i_store = '$i_store'
                AND i_store_loc = '$i_store_loc'
                AND i_company = '$this->i_company'
                AND (i_stockopname_id ILIKE '%$cari%' )
                AND f_stockopname_cancel = 'f'
            ORDER BY
                i_stockopname DESC
        ", FALSE);
    }

    /** Get Data Detail Product */
    public function get_product_detail($i_store_loc, $i_store, $i_product)
    {
        return $this->db->query("SELECT
                a.i_product,
                c.i_product_id,
                initcap(c.e_product_name) AS e_product_name,
                a.i_product_grade,
                d.e_product_gradename,
                a.i_product_motif,
                e.e_product_motifname
            FROM
                tm_ic a
            INNER JOIN tr_product c ON
                (c.i_product = a.i_product)
            INNER JOIN tr_product_grade d ON
                (d.i_product_grade = a.i_product_grade)
            INNER JOIN tr_product_motif e ON
                (e.i_product_motif = a.i_product_motif)
            WHERE
                a.i_company = '$this->i_company'
                AND a.i_product = '$i_product'
                AND a.i_store = '$i_store'
                AND a.i_store_location = '$i_store_loc'
                -- AND a.f_product_active = 't'
                -- AND c.f_product_active = 't'
            ORDER BY c.e_product_name ASC
        ", FALSE);
    }

    /** Simpan Data */
    public function save()
    {
        $query = $this->db->query("SELECT max(i_adjustment)+1 AS id FROM tm_adjustment", TRUE);
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
            'i_company'          => $this->i_company,
            'i_adjustment'       => $id,
            'i_adjustment_id'    => $this->input->post('i_document'),
            'i_stockopname'      => $this->input->post('i_stockopname'),
            'i_area'             => $this->input->post('i_area'),
            'i_store'            => $this->input->post('i_store'),
            'i_store_loc'        => $this->input->post('i_store_loc'),
            'd_adjustment'       => $this->input->post('d_document'),
            'd_adjustment_entry' => current_datetime(),
            'e_remark'           => $this->input->post('e_remark0'),
        );
        $this->db->insert('tm_adjustment', $header);
        /* if ($this->input->post('jml') > 0) { */
        $i = 0;
        if (is_array($this->input->post('i_product')) || is_object($this->input->post('i_product'))) {
            foreach ($this->input->post('i_product') as $i_product) {
                $item = array(
                    'i_adjustment'     => $id,
                    'i_product'        => $i_product,
                    'i_product_grade'  => $this->input->post('i_product_grade')[$i],
                    'i_product_motif'  => $this->input->post('i_product_motif')[$i],
                    'n_adjustment'     => $this->input->post('n_adjustment')[$i],
                    'e_product_name'   => $this->input->post('e_product_name')[$i],
                    'e_remark'         => $this->input->post('e_remark')[$i],
                    'n_item_no'        => $i,
                );
                $this->db->insert('tm_adjustment_item', $item);
                $i++;
            }
        }
        /* } */ else {
            die;
        }
    }

    /** Get Data Untuk Edit */
    public function getdata($id)
    {
        return $this->db->query("SELECT
                a.*,
                c.e_store_name,
                b.i_store_loc_id,
                b.e_store_loc_name,
                d.i_stockopname_id,
                to_char(d.d_stockopname, 'DD FMMonth YYYY') AS d_stockopname,
                initcap(g.e_user_name) AS e_name
            FROM
                tm_adjustment a
            INNER JOIN tr_store_loc b ON
                (b.i_store_loc = a.i_store_loc)
            INNER JOIN tr_store c ON
                (c.i_store = a.i_store)
            INNER JOIN tm_stockopname d ON
                (d.i_stockopname = a.i_stockopname)
            LEFT JOIN tm_user g 
                ON (g.i_user = a.i_approve::int)                
            WHERE
                a.i_adjustment = '$id'
        ", FALSE);
    }

    /** Get Data Untuk Edit */
    public function getdetail($id)
    {
        return $this->db->query("SELECT
                a.*,
                b.e_product_motifname,
                c.e_product_gradename,
                d.i_product_id
            FROM
                tm_adjustment_item a
            INNER JOIN tr_product_motif b ON
                (b.i_product_motif = a.i_product_motif)
            INNER JOIN tr_product_grade c ON
                (c.i_product_grade = a.i_product_grade)
            INNER JOIN tr_product d ON
                (d.i_product = a.i_product)
            WHERE
                a.i_adjustment = '$id'
            ORDER BY
                a.n_item_no ASC
        ", FALSE);
    }

    /** Cek Apakah Data Sudah Ada Pas Edit */
    public function cek_edit()
    {
        $i_document = $this->input->post('i_document', TRUE);
        $i_document_old = $this->input->post('i_document_old', TRUE);
        return $this->db->query("SELECT 
                i_adjustment_id
            FROM 
                tm_adjustment 
            WHERE 
                trim(upper(i_adjustment_id)) <> trim(upper('$i_document_old'))
                AND trim(upper(i_adjustment_id)) = trim(upper('$i_document'))
                AND i_company = '$this->i_company'
        ", FALSE);
    }

    /** Update Data */
    public function update()
    {
        $id = $this->input->post('id');
        $header = array(
            'i_company'            => $this->i_company,
            'i_adjustment_id'      => $this->input->post('i_document'),
            'i_stockopname'        => $this->input->post('i_stockopname'),
            'i_area'               => $this->input->post('i_area'),
            'i_store'              => $this->input->post('i_store'),
            'i_store_loc'          => $this->input->post('i_store_loc'),
            'd_adjustment'         => $this->input->post('d_document'),
            'd_adjustment_update'  => current_datetime(),
            'e_remark'             => $this->input->post('e_remark0'),
        );
        $this->db->where('i_adjustment', $id);
        $this->db->update('tm_adjustment', $header);
        /* if ($this->input->post('jml') > 0) { */
        if (is_array($this->input->post('i_product')) || is_object($this->input->post('i_product'))) {
            $this->db->where('i_adjustment', $id);
            $this->db->delete('tm_adjustment_item');
            $i = 0;
            foreach ($this->input->post('i_product') as $i_product) {
                $item = array(
                    'i_adjustment'      => $id,
                    'i_product'         => $i_product,
                    'i_product_grade'   => $this->input->post('i_product_grade')[$i],
                    'i_product_motif'   => $this->input->post('i_product_motif')[$i],
                    'n_adjustment'      => $this->input->post('n_adjustment')[$i],
                    'e_product_name'    => $this->input->post('e_product_name')[$i],
                    'e_remark'          => $this->input->post('e_remark')[$i],
                    'n_item_no'         => $i,
                );
                $this->db->insert('tm_adjustment_item', $item);
                $i++;
            }
        }
        /* } */ else {
            die;
        }
    }

    /** Cancel Data */
    public function cancel($id)
    {
        $table = array(
            'f_adjustment_cancel' => 't',
        );
        $this->db->where('i_adjustment', $id);
        $this->db->update('tm_adjustment', $table);
    }
}

/* End of file Mmaster.php */
