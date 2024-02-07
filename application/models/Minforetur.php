<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Minforetur extends CI_Model
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
                a.i_ttb ,
                a.i_ttb_id ,
                a.d_ttb ,	
                b.i_area_id || ' - ' || b.e_area_name as e_area_name,
                c.i_customer_id   || ' - ' || c.e_customer_name as e_customer_name,
                d.e_salesman_name ,
                case when f_ttb_pkp = 't' then 'PKP' else 'NONPKP' end as f_ttb_pkp ,
                a.v_ttb_netto::money as v_ttb_netto ,
                e.e_alasan_retur_name ,
                '$dfrom' AS dfrom,
                '$dto' AS dto,
                '$i_area' AS i_area
            from
                tm_ttbretur a
            inner join tr_area b on (b.i_area=a.i_area)
            inner join tr_customer c on (c.i_customer=a.i_customer)
            inner join tr_salesman d on (d.i_salesman=a.i_salesman)
            inner join tr_alasan_retur e on (e.i_alasan_retur=a.i_alasan_retur)
            inner join tm_user_area u on (u.i_area = b.i_area and u.i_user = '$this->i_user')
            where 
                a.d_ttb BETWEEN '$dfrom' AND '$dto'
	            and a.f_ttb_cancel = 'f'
                AND a.i_company = '$this->i_company'
                $area
            order by 3
        ", FALSE);

        /** Cek Hak Akses, Apakah User Bisa Edit */
        $datatables->add('action', function ($data) {
            $i_company  = $data['i_company'];
            $dfrom      = $data['dfrom'];
            $dto        = $data['dto'];
            $i_area    = $data['i_area'];
            $i_ttb      = $data['i_ttb'];
            $data       = '';
            $data      .= "<a href='" . base_url() . $this->folder . '/view/' . encrypt_url($i_ttb) . '/' . encrypt_url($i_company) . '/' . encrypt_url($dfrom) . '/' . encrypt_url($dto) . '/' . encrypt_url($i_area) . "' title='View Mutasi'><i class='fa fa-eye fa-lg warning darken-4 mr-1'></i></a>";
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
                distinct 
                a.i_area,
                a.i_area_id,
                a.e_area_name
            from
                tr_area a
            inner join tm_ttbretur b on
                (b.i_area = a.i_area)
            INNER JOIN tm_user_area u 
                ON (u.i_area = a.i_area AND u.i_user = '$this->i_user' ) 
            WHERE 
                (e_area_name ILIKE '%$cari%' OR i_area_id ILIKE '%$cari%')
                AND a.i_company = '$this->i_company' 
                AND f_area_active = true
            order by
                3
        ", FALSE);
    }

    /**** List Mutasi ***/
    public function get_data_detail($i_ttb)
    {
        return $this->db->query("SELECT
                a.i_ttb_item ,
                a.i_ttb ,
                c.i_nota_id ,
                c.d_nota ,
                d.i_product_id ,
                d.e_product_name ,
                e.e_product_motifname ,
                a.n_quantity ,
                a.n_quantity_receive ,
                a.v_unit_price ,
                a.v_ttb_discount1 ,
                a.v_ttb_discount2 ,
                a.v_ttb_discount3 ,
                (a.v_unit_price * a.n_quantity) - (a.v_ttb_discount1 + a.v_ttb_discount2 + a.v_ttb_discount3) as tot,
                b.n_ppn_r
            from
                tm_ttbretur_item a
            inner join tm_ttbretur b on (b.i_ttb=a.i_ttb)
            inner join tm_nota c on (c.i_nota=a.i_nota)
            inner join tr_product d on (d.i_product=a.i_product1)
            inner join tr_product_motif e on (e.i_product_motif=a.i_product1_motif)
            WHERE
                a.i_ttb = '$i_ttb'
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
                a.i_ttb ,
                a.i_ttb_id ,
                a.d_ttb ,	
                b.i_area_id || ' - ' || b.e_area_name as e_area_name,
                c.i_customer_id   || ' - ' || c.e_customer_name as e_customer_name,
                d.e_salesman_name ,
                case when f_ttb_pkp = 't' then 'PKP' else 'NONPKP' end as f_ttb_pkp ,
                a.v_ttb_netto as v_ttb_netto ,
                e.e_alasan_retur_name ,
                '$dfrom' AS dfrom,
                '$dto' AS dto,
                '$i_area' AS i_area
            from
                tm_ttbretur a
            inner join tr_area b on (b.i_area=a.i_area)
            inner join tr_customer c on (c.i_customer=a.i_customer)
            inner join tr_salesman d on (d.i_salesman=a.i_salesman)
            inner join tr_alasan_retur e on (e.i_alasan_retur=a.i_alasan_retur)
            inner join tm_user_area u on (u.i_area = b.i_area and u.i_user = '$this->i_user')
            where 
                a.d_ttb BETWEEN '$dfrom' AND '$dto'
	            and a.f_ttb_cancel = 'f'
                AND a.i_company = '$this->i_company'
                $area
            order by 3
        ", FALSE);
    }
}

/* End of file Mmaster.php */
