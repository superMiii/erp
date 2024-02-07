<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Minfofakturbeli extends CI_Model
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
                i_nota,
                i_nota_id,
                i_nota_supplier,
                d_nota,
                b.e_supplier_name ,
                v_nota_netto::money as v_nota_netto ,
                v_sisa::money as v_sisa
            from
                tm_nota_pembelian a
            inner join tr_supplier b on (b.i_supplier=a.i_supplier)
            WHERE
                f_nota_cancel = 'f'
                AND a.i_company = '$this->i_company'
                AND d_nota BETWEEN '$dfrom' AND '$dto'
                $supplier
            ORDER BY
                4 ASC
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
        return $this->db->query("SELECT distinct
                a.i_supplier, i_supplier_id , initcap(e_supplier_name) AS e_supplier_name
            FROM 
                tr_supplier a
                inner join tm_nota_pembelian b on (a.i_supplier=b.i_supplier)
            WHERE 
                (e_supplier_name ILIKE '%$cari%' OR i_supplier_id ILIKE '%$cari%')
                AND a.i_company = '$this->i_company' 
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
               i_nota,
                i_nota_id,
                i_nota_supplier,
                d_nota,
                b.e_supplier_name ,
                v_nota_netto ,
                v_sisa 
            from
                tm_nota_pembelian a
            inner join tr_supplier b on (b.i_supplier=a.i_supplier)
            WHERE
                f_nota_cancel = 'f'
                AND a.i_company = '$this->i_company'
                AND d_nota BETWEEN '$dfrom' AND '$dto'
                $supplier
            ORDER BY
                4 ASC
        ", FALSE);
    }
}

/* End of file Mmaster.php */
