<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Minfosoh3 extends CI_Model
{

    /** List Datatable */
    public function serverside()
    {
        // $dfrom = $this->input->post('dfrom', TRUE);
        // if ($dfrom == '') {
        //     $dfrom = $this->uri->segment(3);
        // }

        // $dto = $this->input->post('dto', TRUE);
        // if ($dto == '') {
        //     $dto = $this->uri->segment(4);
        // }

        $i_store = $this->input->post('i_store', TRUE);
        if ($i_store == '') {
            $i_store = $this->uri->segment(3);
        }

        if ($i_store != '') {
            $store = "AND a.i_store = '$i_store' ";
        } else {
            $store = "";
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

        // $dfrom  = date('Y-m-d', strtotime($dfrom));
        // $dto    = date('Y-m-d', strtotime($dto));

        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("SELECT 
                a.i_company,
                b.i_product_id ,
                b.e_product_name ,
                c.e_product_motifname ,
                d.e_product_gradename ,
                a.n_quantity_stock ,
                CAST(hh.hg AS INT)::money  as hg,
                (a.n_quantity_stock*CAST(hh.hg AS INT))::money as jj,
                e.e_store_name 
            from
                tm_ic a
            inner join tr_product b on
                (b.i_product = a.i_product)
            inner join tr_product_motif c on
                (c.i_product_motif = a.i_product_motif)
            inner join tr_product_grade d on
                (d.i_product_grade = a.i_product_grade)
            inner join tr_store e on
                (e.i_store = a.i_store)
            left join (select i_product, max(v_price) as hg from tr_supplier_price where i_company = '$this->i_company' group by 1) hh on (hh.i_product=a.i_product)
            where
                a.i_company = '$this->i_company'
                AND a.i_store = '$i_store'
            order by
                3 asc
        ", FALSE);
        return $datatables->generate();
    }

    /** Get Area */
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
    public function get_data($i_store)
    {
        if ($i_store != 'NA') {
            $store = "AND a.i_store = '$i_store' ";
        } else {
            $store = "";
        }
        // if ($i_customer != 'ALL') {
        //     $customer = "AND a.i_customer = '$i_customer' ";
        // } else {
        //     $customer = "";
        // }
        // $dfrom  = date('Y-m-d', strtotime($dfrom));
        // $dto    = date('Y-m-d', strtotime($dto));
        return $this->db->query("SELECT
                a.i_company,
                b.i_product_id ,
                b.e_product_name ,
                c.e_product_motifname ,
                d.e_product_gradename ,
                a.n_quantity_stock ,
                e.e_store_name ,
                CAST(hh.hg AS INT) as hg,
                (a.n_quantity_stock*CAST(hh.hg AS INT)) as jj
            from
                tm_ic a
            inner join tr_product b on
                (b.i_product = a.i_product)
            inner join tr_product_motif c on
                (c.i_product_motif = a.i_product_motif)
            inner join tr_product_grade d on
                (d.i_product_grade = a.i_product_grade)
            inner join tr_store e on
                (e.i_store = a.i_store)
            left join (select i_product, max(v_price) as hg from tr_supplier_price where i_company = '$this->i_company' group by 1) hh on (hh.i_product=a.i_product)
            where
                a.i_company = '$this->i_company'
                $store
            order by
                3 asc
        ", FALSE);
    }
}

/* End of file Mmaster.php */
