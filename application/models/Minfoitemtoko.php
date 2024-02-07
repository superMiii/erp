<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Minfoitemtoko extends CI_Model
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

        $i_customer = $this->input->post('i_customer', TRUE);
        if ($i_customer == '') {
            $i_customer = $this->uri->segment(6);
        }

        if ($i_customer != 'ALL') {
            $customer = "AND a.i_customer = '$i_customer' ";
        } else {
            $customer = "";
        }


        $i_product = $this->input->post('i_product', TRUE);
        if ($i_product == '') {
            $i_product = $this->uri->segment(7);
        }

        if ($i_product != 'ALL') {
            $product = "AND b.i_product = '$i_product' ";
        } else {
            $product = "";
        }

        $dfrom  = date('Y-m-d', strtotime($dfrom));
        $dto    = date('Y-m-d', strtotime($dto));

        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("SELECT
                a.i_nota ,
                a.i_nota_id ,
                c.i_customer_id ,
                c.e_customer_name ,
                d.i_product_id ,
                d.e_product_name ,
                e.e_price_groupname,
                b.v_unit_price::money as v_unit_price,
                b.n_deliver ,
                (b.v_unit_price * b.n_deliver)::money as tot,
                (b.v_nota_discount1 + b.v_nota_discount2 + b.v_nota_discount3 + b.v_nota_discount4)::money as dis,
                ((b.v_unit_price * b.n_deliver)-(b.v_nota_discount1 + b.v_nota_discount2 + b.v_nota_discount3 + b.v_nota_discount4))::money  as jml
            from
                tm_nota a
                inner join tm_nota_item b on (b.i_nota=a.i_nota)
                inner join tr_customer c on (c.i_customer=a.i_customer)
                inner join tr_product d on (d.i_product=b.i_product)
                inner join tr_price_group e on (e.i_price_group=c.i_price_group)
            WHERE
                a.f_nota_cancel = 'f'
                AND a.i_company = '$this->i_company'
                AND a.d_nota BETWEEN '$dfrom' AND '$dto'
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
            $area = "AND a.i_area = '$i_area' ";
        } else {
            $area = "";
        }
        if ($i_customer != 'ALL') {
            $customer = "AND a.i_customer = '$i_customer' ";
        } else {
            $customer = "";
        }

        if ($i_product != 'ALL') {
            $product = "AND b.i_product = '$i_product' ";
        } else {
            $product = "";
        }

        $dfrom  = date('Y-m-d', strtotime($dfrom));
        $dto    = date('Y-m-d', strtotime($dto));
        return $this->db->query("SELECT
                    a.i_nota ,
                    a.i_nota_id ,
                    c.i_customer_id ,
                    c.e_customer_name ,
                    d.i_product_id ,
                    d.e_product_name ,
                    e.e_price_groupname,
                    b.v_unit_price ,
                    b.n_deliver ,
                    b.v_unit_price * b.n_deliver as tot,
                    b.v_nota_discount1 + b.v_nota_discount2 + b.v_nota_discount3 + b.v_nota_discount4 as dis,
                    (b.v_unit_price * b.n_deliver)-(b.v_nota_discount1 + b.v_nota_discount2 + b.v_nota_discount3 + b.v_nota_discount4) as jml
                from
                    tm_nota a
                    inner join tm_nota_item b on (b.i_nota=a.i_nota)
                    inner join tr_customer c on (c.i_customer=a.i_customer)
                    inner join tr_product d on (d.i_product=b.i_product)
                    inner join tr_price_group e on (e.i_price_group=c.i_price_group)
                WHERE
                    a.f_nota_cancel = 'f'
                    AND a.i_company = '$this->i_company'
                    AND a.d_nota BETWEEN '$dfrom' AND '$dto'
                    $area
                    $customer
                    $product
                ORDER BY
                    1 ASC
        ", FALSE);
    }
}

/* End of file Mmaster.php */
