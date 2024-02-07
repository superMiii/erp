<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Minforp extends CI_Model
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
            $area = "AND b.i_area = '$i_area' ";
        } else {
            $area = "";
        }

        $i_customer = $this->input->post('i_customer', TRUE);
        if ($i_customer == '') {
            $i_customer = $this->uri->segment(6);
        }

        if ($i_customer != 'ALL') {
            $customer = "AND b.i_customer = '$i_customer' ";
        } else {
            $customer = "";
        }


        $i_product = $this->input->post('i_product', TRUE);
        if ($i_product == '') {
            $i_product = $this->uri->segment(7);
        }

        if ($i_product != 'ALL') {
            $product = "AND a.i_product = '$i_product' ";
        } else {
            $product = "";
        }

        $dfrom  = date('Y-m-d', strtotime($dfrom));
        $dto    = date('Y-m-d', strtotime($dto));

        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("SELECT
                b.i_nota_id AS id,
                b.i_nota_id,	
                b.d_nota ,
                e.i_area_id || ' - ' || e.e_area_name as areaa,
                f.i_customer_id as codee,
                f.e_customer_name as cus,
                c.i_product_id ,
                c.e_product_name ,
                d.e_product_motifname ,
                a.n_deliver ,
                a.v_unit_price::money as v_unit_price,
                b.v_nota_gross::money as v_nota_gross,
                b.v_nota_ppn::money as v_nota_ppn,
                b.v_nota_discount::money as v_nota_discount,
                b.v_nota_netto::money as v_nota_netto
            from
                tm_nota_item a
            inner join tm_nota b on (b.i_nota=a.i_nota)
            inner join tr_product c on (c.i_product=a.i_product)
            inner join tr_product_motif d on (d.i_product_motif=a.i_product_motif)
            inner join tr_area e on (e.i_area=b.i_area)
            inner join tr_customer f on (f.i_customer=b.i_customer)
            WHERE
                b.f_nota_cancel = 'f'
                AND b.i_company = '$this->i_company'
                AND b.d_nota BETWEEN '$dfrom' AND '$dto'
                $area
                $customer
                $product
            ORDER BY
                1 ASC
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
    public function get_customer($cari, $i_area)
    {
        if ($i_area != 'NA') {
            $area = "AND i_area = '$i_area' ";
        } else {
            $area = "";
        }
        return $this->db->query("SELECT 
                i_customer, i_customer_id , e_customer_name
            FROM 
                tr_customer
            WHERE 
                (e_customer_name ILIKE '%$cari%' OR i_customer_id ILIKE '%$cari%')
                AND i_company = '$this->i_company' 
                AND f_customer_active = 'true' 
                $area
            ORDER BY 3 ASC
        ", FALSE);
    }

    public function get_product($cari)
    {
        return $this->db->query("SELECT 
                i_product, i_product_id , e_product_name
            FROM 
                tr_product
            WHERE 
                (e_product_name ILIKE '%$cari%' OR i_product_id ILIKE '%$cari%')
                AND i_company = '$this->i_company' 
            ORDER BY 3 ASC
        ", FALSE);
    }

    /** Get Data Untuk Edit */
    public function get_data($dfrom, $dto, $i_area, $i_customer, $i_product)
    {
        if ($i_area != 'NA') {
            $area = "AND b.i_area = '$i_area' ";
        } else {
            $area = "";
        }
        if ($i_customer != 'ALL') {
            $customer = "AND b.i_customer = '$i_customer' ";
        } else {
            $customer = "";
        }

        if ($i_product != 'ALL') {
            $product = "AND a.i_product = '$i_product' ";
        } else {
            $product = "";
        }

        $dfrom  = date('Y-m-d', strtotime($dfrom));
        $dto    = date('Y-m-d', strtotime($dto));
        return $this->db->query("SELECT
                    b.i_nota_id AS id,
                    b.i_nota_id,	
                    b.d_nota ,
                    e.i_area_id || ' - ' || e.e_area_name as areaa,
                f.i_customer_id as codee,
                f.e_customer_name as cus,
                    c.i_product_id ,
                    c.e_product_name ,
                    d.e_product_motifname ,
                    a.n_deliver ,
                    a.v_unit_price,
                    b.v_nota_gross ,
                    b.v_nota_ppn ,
                    b.v_nota_discount ,
                    b.v_nota_netto 
                from
                    tm_nota_item a
                inner join tm_nota b on (b.i_nota=a.i_nota)
                inner join tr_product c on (c.i_product=a.i_product)
                inner join tr_product_motif d on (d.i_product_motif=a.i_product_motif)
                inner join tr_area e on (e.i_area=b.i_area)
                inner join tr_customer f on (f.i_customer=b.i_customer)
                WHERE
                    b.f_nota_cancel = 'f'
                    AND b.i_company = '$this->i_company'
                    AND b.d_nota BETWEEN '$dfrom' AND '$dto'
                    $area
                    $customer
                    $product
                ORDER BY
                    1 ASC
        ", FALSE);
    }
}

/* End of file Mmaster.php */
