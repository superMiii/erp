<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Minfopembelian extends CI_Model
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

        $i_supplier = $this->input->post('i_supplier', TRUE);
        if ($i_supplier == '') {
            $i_supplier = $this->uri->segment(5);
        }

        if ($i_supplier != 'ALL') {
            $supplier = "AND a.i_supplier = '$i_supplier' ";
        } else {
            $supplier = "";
        }

        $dfrom  = date('Y-m-d', strtotime($dfrom));
        $dto    = date('Y-m-d', strtotime($dto));

        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("SELECT
                b.i_gr_item,
                a.d_gr ,
                c.i_product_id,
                c.e_product_name,
                b.n_deliver,
                b.v_product_mill::money AS v_product_mill,
                (b.v_gr_discount::money) AS v_gr_discount,
                ((b.n_deliver * b.v_product_mill) - b.v_gr_discount)::money AS v_gr,
                d.e_supplier_name
            FROM
                tm_gr a
            INNER JOIN tm_gr_item b ON
                (b.i_gr = a.i_gr)
            INNER JOIN tr_product c ON
                (c.i_product = b.i_product)
            INNER JOIN tr_supplier d ON
                (d.i_supplier = a.i_supplier)
            WHERE
                f_gr_cancel = 'f'
                AND a.i_company = '$this->i_company'
                AND d_gr BETWEEN '$dfrom' AND '$dto'
                $supplier
            ORDER BY
                a.d_gr,
                b.i_gr_item ASC
        ", FALSE);
        return $datatables->generate();
    }

    /** Get Area */
    /* public function get_area($cari)
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
    } */

    /** Get Supplier */
    public function get_supplier($cari)
    {
        return $this->db->query("SELECT 
                i_supplier, i_supplier_id , initcap(e_supplier_name) AS e_supplier_name
            FROM 
                tr_supplier
            WHERE 
                (e_supplier_name ILIKE '%$cari%' OR i_supplier_id ILIKE '%$cari%')
                AND i_company = '$this->i_company' 
                AND f_supplier_active = 'true' 
            ORDER BY 3 ASC
        ", FALSE);
    }

    /** Get Data Untuk Edit */
    public function get_data($dfrom, $dto, $i_supplier)
    {
        if ($i_supplier != 'ALL') {
            $supplier = "AND a.i_supplier = '$i_supplier' ";
        } else {
            $supplier = "";
        }
        $dfrom  = date('Y-m-d', strtotime($dfrom));
        $dto    = date('Y-m-d', strtotime($dto));
        return $this->db->query("SELECT
                b.i_gr_item,
                a.d_gr ,
                c.i_product_id,
                c.e_product_name,
                b.n_deliver,
                b.v_product_mill,
                b.v_gr_discount,
                ((b.n_deliver * b.v_product_mill) - b.v_gr_discount) AS v_gr,
                d.e_supplier_name
            FROM
                tm_gr a
            INNER JOIN tm_gr_item b ON
                (b.i_gr = a.i_gr)
            INNER JOIN tr_product c ON
                (c.i_product = b.i_product)
            INNER JOIN tr_supplier d ON
                (d.i_supplier = a.i_supplier)
            WHERE
                f_gr_cancel = 'f'
                AND a.i_company = '$this->i_company'
                AND d_gr BETWEEN '$dfrom' AND '$dto'
                $supplier
            ORDER BY
                a.d_gr,
                b.i_gr_item ASC
        ", FALSE);
    }
}

/* End of file Mmaster.php */
