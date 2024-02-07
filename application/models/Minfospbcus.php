<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Minfospbcus extends CI_Model
{

    /**** List Datatable ***/
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

        if ($i_area != 'ALL') {
            $area = "AND a.i_area = '$i_area' ";
        } else {
            $area = "";
        }

        $dfrom  = date('Y-m-d', strtotime($dfrom));
        $dto    = date('Y-m-d', strtotime($dto));

        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("SELECT
                a.i_company ,
                a.i_so ,
                a.i_so_id ,
                a.d_so ,
                c.i_customer_id  || ' - ' || c.e_customer_name as e_customer_name ,
                b.i_area_id || ' - ' || b.e_area_name as e_area_name ,
                d.e_salesman_name ,
                g.e_price_groupname ,
                e.e_store_name ,
                f.e_status_so_name ,
                a.v_so::money as v_so ,
                case when a.i_so_refference notnull then (select i_so_id from tm_so r where r.i_so=a.i_so_refference) else '' end as reff,
                '$dfrom' AS dfrom,
                '$dto' AS dto,
                '$i_area' AS i_area
            from
                tm_so a
                inner join tr_area b on (b.i_area=a.i_area)
                inner join tr_customer c on (c.i_customer=a.i_customer)
                inner join tr_salesman d on (d.i_salesman=a.i_salesman)
                left join tr_store e on (e.i_store=a.i_store)
                inner join tr_status_so f on (f.i_status_so=a.i_status_so)
                inner join tr_price_group g on (g.i_price_group=a.i_price_group)
                inner join tm_user_area u on (u.i_area = b.i_area and u.i_user = '$this->i_user')
            where 
                a.d_so BETWEEN '$dfrom' AND '$dto'
	            and a.f_so_cancel = 'f'
                AND a.i_company = '$this->i_company'
                $area
            order by c.e_customer_name, a.i_so_id  desc 
        ", FALSE);

        /** Cek Hak Akses, Apakah User Bisa Edit */
        $datatables->add('action', function ($data) {
            $i_company  = $data['i_company'];
            $dfrom      = $data['dfrom'];
            $dto        = $data['dto'];
            $i_area    = $data['i_area'];
            $i_so      = $data['i_so'];
            $data       = '';
            $data      .= "<a href='" . base_url() . $this->folder . '/view/' . encrypt_url($i_so) . '/' . encrypt_url($i_company) . '/' . encrypt_url($dfrom) . '/' . encrypt_url($dto) . '/' . encrypt_url($i_area) . "' title='View Mutasi'><i class='fa fa-eye fa-lg warning darken-4 mr-1'></i></a>";
            return $data;
        });
        $datatables->hide('i_company');
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

    /**** List Mutasi ***/
    public function get_data_detail($i_so)
    {
        return $this->db->query("SELECT
                b.i_company ,
                a.i_so_item ,
                b.i_so_id ,
                b.d_so ,
                c.i_product_id ,
                c.e_product_name ,
                d.e_product_motifname ,
                a.n_order ,
                a.n_deliver,
                v_unit_price as v_unit_price  
            from
                tm_so_item a
                inner join tm_so b on (b.i_so =a.i_so )
                inner join tr_product c on (c.i_product=a.i_product)
                inner join tr_product_motif d on (d.i_product_motif =a.i_product_motif )
            WHERE
                a.i_so = '$i_so'
            order by n_item_no desc 
        ", FALSE);
    }

    /** Get Data Untuk Export */
    public function get_data($dfrom, $dto, $i_area)
    {

        $dfrom  = date('Y-m-d', strtotime($dfrom));
        $dto    = date('Y-m-d', strtotime($dto));
        if ($i_area != 'ALL') {
            $area = "AND a.i_area = '$i_area' ";
        } else {
            $area = "";
        }
        return $this->db->query("SELECT
               a.i_company ,
                a.i_so ,
                a.i_so_id ,
                a.d_so ,
                c.i_customer_id  || ' - ' || c.e_customer_name as e_customer_name ,
                b.i_area_id || ' - ' || b.e_area_name as e_area_name ,
                d.e_salesman_name ,
                g.e_price_groupname ,
                e.e_store_name ,
                f.e_status_so_name ,
                a.v_so as v_so ,
                case when a.i_so_refference notnull then (select i_so_id from tm_so r where r.i_so=a.i_so_refference) else '' end as reff,
                '$dfrom' AS dfrom,
                '$dto' AS dto,
                '$i_area' AS i_area
            from
                tm_so a
                inner join tr_area b on (b.i_area=a.i_area)
                inner join tr_customer c on (c.i_customer=a.i_customer)
                inner join tr_salesman d on (d.i_salesman=a.i_salesman)
                left join tr_store e on (e.i_store=a.i_store)
                inner join tr_status_so f on (f.i_status_so=a.i_status_so)
                inner join tr_price_group g on (g.i_price_group=a.i_price_group)
                inner join tm_user_area u on (u.i_area = b.i_area and u.i_user = '$this->i_user')
            where 
                a.d_so BETWEEN '$dfrom' AND '$dto'
	            and a.f_so_cancel = 'f'
                AND a.i_company = '$this->i_company'
                $area
            order by c.e_customer_name, a.i_so_id  desc 
        ", FALSE);
    }
}

/* End of file Mmaster.php */
