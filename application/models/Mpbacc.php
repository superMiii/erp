<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mpbacc extends CI_Model
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
                AND f_gs_cancel = 'f'
                AND d_acc ISNULL
                AND a.d_gs BETWEEN '$dfrom' AND '$dto'
                $area
            ORDER BY
                1 DESC 
        ", FALSE);

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
            $i_periode  = $data['i_periode'];
            $dfrom      = $data['dfrom'];
            $dto        = $data['dto'];
            $i_area     = $data['i_area'];
            $data       = '';
            if ($i_periode >= get_periode()) {
                if (check_role($this->i_menu, 3)) {
                    $data      .= "<a href='" . base_url() . $this->folder . '/edit/' . encrypt_url($id)  . '/' .  encrypt_url($dfrom) . '/' . encrypt_url($dto) . '/' . encrypt_url($i_area) . "' title='Edit Data'><i class='fa fa-check-circle-o success darken-4 fa-lg mr-1'></i></a>";
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

    /** Get Data Product */
    public function get_product($cari)
    {
        return $this->db->query("SELECT
                i_product,
                i_product_id,
                initcap(e_product_name) AS e_product_name
            FROM
                tr_product
            WHERE
                (i_product_id ILIKE '%$cari%'
                    OR e_product_name ILIKE '%$cari%')
                AND i_company = '$this->i_company'
            ORDER BY
                e_product_name ASC
        ", FALSE);
    }

    /** Get Data Detail Product */
    public function get_product_detail($i_product)
    {
        return $this->db->query("SELECT
                a.i_product,
                b.i_product_grade,
                a.i_product_motif,
                b.v_price,
                initcap(e_product_name) AS e_product_name,
                c.e_product_gradename as e_product_motifname
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
            WHERE
                a.i_product = '$i_product'
                AND c.f_default = 't'
                AND c.f_product_gradeactive = 't'
                AND d.f_default = 't'
                AND a.i_company = '$this->i_company'
        ", FALSE);
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
                COALESCE(n_quantity_deliver,0) AS acc,
                b.i_product_id,
                b.e_product_name,
                g.e_product_gradename as e_product_gradename
            FROM
                tm_gs_item2 a
            INNER JOIN tr_product b ON
                (b.i_product = a.i_product)
            INNER JOIN tr_product_motif c ON
                (c.i_product_motif = a.i_product_motif)
            inner join tr_product_grade g on (g.i_product_grade=a.i_product_grade)
            WHERE
                a.i_gs = '$id'
            ORDER BY
                a.n_item_no ASC
        ", FALSE);
    }

    /** Approve Data */
    public function approve($id, $note)
    {
        $table = array(
            'i_acc' => $this->session->e_user_name,
            'd_acc' => current_date(),
            'e_acc' => $note,
        );
        $this->db->where('i_gs', $id);
        $this->db->update('tm_gs2', $table);


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
                        'n_quantity_deliver' => $this->input->post('n_acc')[$i],
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
}

/* End of file Mmaster.php */
