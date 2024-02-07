<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Minforincianpembelian extends CI_Model
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
            $supplier = "AND e.i_supplier = '$i_supplier' ";
        } else {
            $supplier = "";
        }

        $dfrom  = date('Y-m-d', strtotime($dfrom));
        $dto    = date('Y-m-d', strtotime($dto));

        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("SELECT
                b.i_nota,
                b.i_nota_id ,
                b.d_nota ,
                d.i_gr_id ,
                d.d_gr ,
                e.e_supplier_name,
                f.i_product_id ,
                f.e_product_name ,
                c.n_deliver as kirim ,
                c.v_product_mill::money as jml ,
                c.v_gr_discount::money as disc ,
                ((c.n_deliver * c.v_product_mill)-c.v_gr_discount)::money as tot
            from
                tm_nota_pembelian_item a
                inner join tm_nota_pembelian b on (b.i_nota = a.i_nota)
                inner join tm_gr_item c on (c.i_gr = a.i_gr) 
                inner join tm_gr d on (c.i_gr = d.i_gr) 
                inner join tr_supplier e on (e.i_supplier = b.i_supplier)
                inner join tr_product f on (f.i_product = c.i_product) 
            WHERE
                d.f_gr_cancel = 'f'
                AND b.i_company = '$this->i_company'
                AND d.d_gr BETWEEN '$dfrom' AND '$dto'
                $supplier
            ORDER BY
                5 ASC
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
               b.i_nota,
                b.i_nota_id ,
                b.d_nota ,
                d.i_gr_id ,
                d.d_gr ,
                e.e_supplier_name,
                f.i_product_id ,
                f.e_product_name ,
                c.n_deliver as kirim ,
                c.v_product_mill as jml ,
                c.v_gr_discount as disc ,
                ((c.n_deliver * c.v_product_mill)-c.v_gr_discount) as tot
            from
                tm_nota_pembelian_item a
                inner join tm_nota_pembelian b on (b.i_nota = a.i_nota)
                inner join tm_gr_item c on (c.i_gr = a.i_gr) 
                inner join tm_gr d on (c.i_gr = d.i_gr) 
                inner join tr_supplier e on (e.i_supplier = b.i_supplier)
                inner join tr_product f on (f.i_product = c.i_product) 
            WHERE
                d.f_gr_cancel = 'f'
                AND b.i_company = '$this->i_company'
                AND d.d_gr BETWEEN '$dfrom' AND '$dto'
                $supplier
            ORDER BY
                5 ASC
        ", FALSE);
    }
}

/* End of file Mmaster.php */
