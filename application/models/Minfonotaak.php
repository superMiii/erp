<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Minfonotaak extends CI_Model
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

        if ($i_area != 'NA') {
            $area = "AND a.i_area = '$i_area' ";
        } else {
            $area = "";
        }

        // $i_customer = $this->input->post('i_customer', TRUE);
        // if ($i_customer == '') {
        //     $i_customer = $this->uri->segment(6);
        // }

        // if ($i_customer != 'ALL') {
        //     $customer = "AND a.i_customer = '$i_customer' ";
        // } else {
        //     $customer = "";
        // }

        $dfrom  = date('Y-m-d', strtotime($dfrom));
        $dto    = date('Y-m-d', strtotime($dto));

        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("SELECT 
                i_nota,
                i_nota_id,
                d_nota,
                b.n_customer_top,
                ct.n_toleransi as n_toleransi,
                d_jatuh_tempo + ct.n_toleransi as d_jatuh_tempo,
                b.i_customer_id ,
                b.e_customer_name	,
                e.e_salesman_name,
                i_faktur_komersial ,
                i_seri_pajak ,
                d_pajak ,
                v_nota_gross::money as kotor,
                v_nota_ppn::money as ppn ,
                v_nota_discount::money as disc,
                v_nota_netto::money as bersih,
                c.i_area_id ,
                c.e_area_name  
            from
                tm_nota a
                inner join tr_customer b on (b.i_customer=a.i_customer)
                inner join tr_area c on (c.i_area =a.i_area )
	            inner join tr_city ct on (ct.i_city =b.i_city)
                left join tm_do d on (d.i_do_id =a.i_nota_id  and d.i_company =a.i_company)
                inner join tr_salesman e on (e.i_salesman =d.i_salesman )
            where
                a.f_nota_cancel = 'f'
                and a.i_company = '$this->i_company'
                and a.d_nota between '$dfrom' and '$dto'
                $area
            order by
                3 asc
        ", FALSE);
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
    // public function get_customer($cari, $i_area)
    // {
    //     if ($i_area != 'NA') {
    //         $area = "AND i_area = '$i_area' ";
    //     } else {
    //         $area = "";
    //     }
    //     return $this->db->query("SELECT 
    //             i_customer, i_customer_id , e_customer_name
    //         FROM 
    //             tr_customer
    //         WHERE 
    //             (e_customer_name ILIKE '%$cari%' OR i_customer_id ILIKE '%$cari%')
    //             AND i_company = '$this->i_company' 
    //             AND f_customer_active = 'true' 
    //             $area
    //         ORDER BY 3 ASC
    //     ", FALSE);
    // }

    /** Get Data Untuk Edit */
    public function get_data($dfrom, $dto, $i_area)
    {
        if ($i_area != 'NA') {
            $area = "AND a.i_area = '$i_area' ";
        } else {
            $area = "";
        }
        // if ($i_customer != 'ALL') {
        //     $customer = "AND a.i_customer = '$i_customer' ";
        // } else {
        //     $customer = "";
        // }
        $dfrom  = date('Y-m-d', strtotime($dfrom));
        $dto    = date('Y-m-d', strtotime($dto));
        return $this->db->query("SELECT
                i_nota,
                i_nota_id,
                d_nota,
                ct.n_toleransi as n_toleransi,
                d_jatuh_tempo + ct.n_toleransi as d_jatuh_tempo,
                b.i_customer_id ,
                b.e_customer_name	,
                b.n_customer_top,
                e.e_salesman_name,
                i_faktur_komersial ,
                i_seri_pajak ,
                d_pajak ,
                v_nota_gross ,
                v_nota_ppn ,
                v_nota_discount ,
                v_nota_netto ,
                c.i_area_id ,
                c.e_area_name  
            from
                tm_nota a
                inner join tr_customer b on (b.i_customer=a.i_customer)
                inner join tr_area c on (c.i_area =a.i_area )
	            inner join tr_city ct on (ct.i_city =b.i_city)
                left join tm_do d on (d.i_do_id =a.i_nota_id  and d.i_company =a.i_company)
                inner join tr_salesman e on (e.i_salesman =d.i_salesman )
            where
                a.f_nota_cancel = 'f'
                and a.i_company = '$this->i_company'
                and a.d_nota between '$dfrom' and '$dto'
                $area
            order by
                3 asc
        ", FALSE);
    }
}

/* End of file Mmaster.php */
