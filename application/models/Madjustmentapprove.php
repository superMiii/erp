<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Madjustmentapprove extends CI_Model
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
            INNER JOIN tr_store_loc d ON
                (d.i_store_loc = a.i_store_loc)
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
                AND d_approve ISNULL
                and f_adjustment_cancel = 'f'
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
            $data      .= "<a href='" . base_url() . $this->folder . '/view/' . encrypt_url($id) . '/' . encrypt_url($dfrom) . '/' . encrypt_url($dto) . "' title='View Data'><i class='fa fa-check-circle-o success darken-4 fa-lg mr-1'></i></a>";
            if ($i_periode >= get_periode3()) {
                return $data;
            }
        });
        $datatables->hide('e_name');
        $datatables->hide('d_approve');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('i_periode');
        $datatables->hide('id');
        return $datatables->generate();
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
                AND a.f_product_active = 't'
                AND c.f_product_active = 't'
                AND a.i_store = '$i_store'
                AND a.i_store_location = '$i_store_loc'
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
                AND i_area = '$i_area'
                AND i_company = '$this->i_company'
                AND (i_stockopname_id ILIKE '%$cari%' )
                AND f_stockopname_cancel = 'f'
            ORDER BY
                i_stockopname ASC
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
                AND a.f_product_active = 't'
                AND c.f_product_active = 't'
                AND a.i_store = '$i_store'
                AND a.i_store_location = '$i_store_loc'
            ORDER BY c.e_product_name ASC
        ", FALSE);
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
                to_char(d.d_stockopname, 'DD FMMonth YYYY') AS d_stockopname
            FROM
                tm_adjustment a
            INNER JOIN tr_store_loc b ON
                (b.i_store_loc = a.i_store_loc)
            INNER JOIN tr_store c ON
                (c.i_store = a.i_store)
            INNER JOIN tm_stockopname d ON
                (d.i_stockopname = a.i_stockopname)                
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

    /** Approve Data */
    public function approve($id)
    {
        $table = array(
            'i_approve' => $this->i_user,
            'd_approve' => current_date(),
        );
        $this->db->where('i_adjustment', $id);
        $this->db->update('tm_adjustment', $table);
    }
}

/* End of file Mmaster.php */
