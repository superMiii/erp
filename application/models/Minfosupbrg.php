<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Minfosupbrg extends CI_Model
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

        $i_product_status = $this->input->post('i_product_status', TRUE);
        if ($i_product_status == '') {
            $i_product_status = $this->uri->segment(3);
        }

        if ($i_product_status != 'ALL') {
            $sts = "AND a.i_product_status = '$i_product_status' ";
        } else {
            $sts = "";
        }

        $i_supplier = $this->input->post('i_supplier', TRUE);
        if ($i_supplier == '') {
            $i_supplier = $this->uri->segment(5);
        }

        if ($i_supplier != 'ALL') {
            $supplier = "AND f.i_supplier = '$i_supplier' ";
        } else {
            $supplier = "";
        }

        // $dfrom  = date('Y-m-d', strtotime($dfrom));
        // $dto    = date('Y-m-d', strtotime($dto));

        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("SELECT
                    a.i_product ,
                    case
                        when d.i_product_statusid = 'STP1' then a.i_product_id || ' (*STP)'
                        when d.i_product_statusid = 'STP2' then a.i_product_id || ' (#STP)'
                        else a.i_product_id
                    end as i_product_id,
                    a.e_product_name ,
                    b.e_product_categoryname ,
                    d.e_product_statusname ,
                    case
                        when a.f_product_active = 't' then 'Aktif'
                        else 'Nonaktif'
                    end as act,
                    f.e_supplier_name,
                    e.v_price::money as v_price 
                from
                    tr_product a
                inner join tr_product_category b on
                    (b.i_product_category = a.i_product_category)
                inner join tr_product_motif c on
                    (c.i_product_motif = a.i_product_motif)
                inner join tr_product_status d on
                    (d.i_product_status = a.i_product_status)
                left join tr_supplier_price e on
                    (e.i_product = a.i_product and f_sup_aktif='t')
                left join tr_supplier f on
                    (f.i_supplier = e.i_supplier)
                WHERE
                    a.i_company = '$this->i_company'
                    $sts
                    $supplier
                ORDER BY
                    2 ASC
        ", FALSE);
        return $datatables->generate();
    }

    /** Get Supplier */
    public function get_product_status($cari)
    {
        return $this->db->query("SELECT 
                i_product_status,  
                initcap(e_product_statusname) AS e_product_statusname
            FROM 
                tr_product_status
            WHERE 
                (e_product_statusname ILIKE '%$cari%')
                AND i_company = '$this->i_company' 
                AND f_product_statusactive = 'true' 
            ORDER BY 2 ASC
        ", FALSE);
    }

    /** Get Supplier */
    public function get_supplier($cari)
    {
        return $this->db->query("SELECT distinct
                 a.i_supplier, i_supplier_id , initcap(e_supplier_name) AS e_supplier_name
             FROM 
                 tr_supplier a
                left join tr_supplier_price e on
                    (e.i_supplier = a.i_supplier  and f_sup_aktif='t')
                inner join tr_product f on
                    (f.i_product = e.i_product)
             WHERE 
                 (e_supplier_name ILIKE '%$cari%' OR i_supplier_id ILIKE '%$cari%')
                 AND a.i_company = '$this->i_company' 
                 AND f_supplier_active = 'true' 
             ORDER BY 3 ASC
         ", FALSE);
    }

    /** Get Data Untuk Edit */
    public function get_data($i_product_status, $i_supplier)
    {
        if ($i_product_status != 'ALL') {
            $sts = "AND a.i_product_status = '$i_product_status' ";
        } else {
            $sts = "";
        }
        if ($i_supplier != 'ALL') {
            $supplier = "AND f.i_supplier = '$i_supplier' ";
        } else {
            $supplier = "";
        }
        return $this->db->query("SELECT
                a.i_product ,
                case
                    when d.i_product_statusid = 'STP1' then a.i_product_id || ' (*STP)'
                    when d.i_product_statusid = 'STP2' then a.i_product_id || ' (#STP)'
                    else a.i_product_id
                end as i_product_id,
                a.e_product_name ,
                b.e_product_categoryname ,
                d.e_product_statusname ,
                case
                    when a.f_product_active = 't' then 'Aktif'
                    else 'Nonaktif'
                end as act,
                f.e_supplier_name,
                e.v_price as v_price 
            from
                tr_product a
            inner join tr_product_category b on
                (b.i_product_category = a.i_product_category)
            inner join tr_product_motif c on
                (c.i_product_motif = a.i_product_motif)
            inner join tr_product_status d on
                (d.i_product_status = a.i_product_status)
            left join tr_supplier_price e on
                (e.i_product = a.i_product  and f_sup_aktif='t')
            left join tr_supplier f on
                (f.i_supplier = e.i_supplier)
            WHERE
                a.i_company = '$this->i_company'
                $sts
                $supplier
            ORDER BY
                2 ASC
        ", FALSE);
    }
}

/* End of file Mmaster.php */
