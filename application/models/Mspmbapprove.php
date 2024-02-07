<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mspmbapprove extends CI_Model
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
                d_sr as tgl,
                a.i_sr AS id,
                d_sr,
                i_sr_id,
                to_char(d_sr, 'YYYYMM') as i_periode,
                b.e_area_name,
                a.e_remark,
                f_sr_cancel AS f_status,
                '$dfrom' as dfrom,
                '$dto' as dto,
                '$i_area' AS i_area
            FROM
                tm_sr a
            INNER JOIN tr_area b ON
                (b.i_area = a.i_area)
            INNER JOIN tm_user_area c ON
                (c.i_area = b.i_area)
            WHERE
                c.i_user = '$this->i_user'
                AND a.i_company = '$this->i_company'
                AND f_sr_acc = 't'
                AND f_sr_cancel = 'f'
                AND d_approve1 ISNULL 
                AND d_approve2 ISNULL
                AND a.d_sr BETWEEN '$dfrom' AND '$dto'
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
            $dfrom          = $data['dfrom'];
            $dto            = $data['dto'];
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
    public function get_area0($cari)
    {
        return $this->db->query("SELECT
                a.i_area ,
                a.e_store_loc_name AS e_area_name
            FROM
                tr_store_loc a 
            inner join tm_user_store b on (b.i_store=a.i_store AND b.i_user = '$this->i_user' )
            inner join tr_store c on (c.i_store=a.i_store)
            WHERE 
                (a.e_store_loc_name ILIKE '%$cari%')
                AND a.i_company = '$this->i_company' 
                AND f_store_loc_active = true  
                and c.f_store_pusat ='f'              
            ORDER BY 1 ASC
        ", FALSE);
    }
    
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
                e.e_product_motifname
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
            order by a.e_product_name
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
                tm_sr a
            LEFT JOIN tr_store b ON 
                (b.i_store = a.i_store)
            LEFT JOIN tr_store_loc d ON 
                (d.i_store_loc = a.i_store_location)
            INNER JOIN tr_area c ON 
                (c.i_area = a.i_area)
            WHERE
                i_sr = '$id'
        ", FALSE);
    }

    /** Get Data Untuk Edit */
    public function getdetail($id)
    {
        return $this->db->query("SELECT
                a.*,
                COALESCE(n_acc,0) AS acc,
                case
                    when s.i_product_statusid = 'STP1' then b.i_product_id || ' (*STP)'
                    when s.i_product_statusid = 'STP2' then b.i_product_id || ' (#STP)'
                    else b.i_product_id
                end as i_product_id,
                b.e_product_name,
                g.e_product_gradename as e_product_motifname
            FROM
                tm_sr_item a
            INNER JOIN tr_product b ON
                (b.i_product = a.i_product)
            INNER JOIN tr_product_motif c ON
                (c.i_product_motif = a.i_product_motif)
            inner join tr_product_status s on (s.i_product_status = b.i_product_status)
            inner join tr_product_grade g on (g.i_product_grade=a.i_product_grade)
            WHERE
                a.i_sr = '$id'
            order by b.e_product_name
        ", FALSE);
    }

    /** Approve Data */
    public function approve($id, $note)
    {
        $table = array(
            'i_approve1' => $this->session->e_user_name,
            'd_approve1' => current_date(),
            'e_approve1' => $note,
        );
        $this->db->where('i_sr', $id);
        $this->db->update('tm_sr', $table);
    }
}

/* End of file Mmaster.php */
