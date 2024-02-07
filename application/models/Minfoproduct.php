<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Minfoproduct extends CI_Model
{

    public function serverside()
    {

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

        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("SELECT
                    a.d_product_entry,
                    to_char(a.d_product_entry, 'YYYY-MM-DD') as tgl,
                    case
                        when d.i_product_statusid = 'STP1' then a.i_product_id || ' (*STP)'
                        when d.i_product_statusid = 'STP2' then a.i_product_id || ' (#STP)'
                        else a.i_product_id
                    end as i_product_id,
                    a.e_product_name,
                    b.e_product_categoryname,
                    c.e_product_motifname ,
                    d.e_product_statusname,
                    case
                        when a.f_product_active = 't' then 'Aktif'
                        else 'Nonaktif'
                    end as act,	
                    case
                        when a.f_pareto = 't' then 'PARETO'
                        else 'NONPARETO'
                    end as prt
                from
                    tr_product a
                inner join tr_product_category b on (b.i_product_category = a.i_product_category)
                inner join tr_product_motif c on (c.i_product_motif = a.i_product_motif)
                inner join tr_product_status d on (d.i_product_status = a.i_product_status)
                WHERE
                    a.i_company = '$this->i_company'
                    $sts
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
    public function get_data($i_product_status)
    {
        if ($i_product_status != 'ALL') {
            $sts = "AND a.i_product_status = '$i_product_status' ";
        } else {
            $sts = "";
        }
        // if ($i_supplier != 'ALL') {
        //     $supplier = "AND f.i_supplier = '$i_supplier' ";
        // } else {
        //     $supplier = "";
        // }
        return $this->db->query("SELECT
                a.d_product_entry,
                to_char(a.d_product_entry, 'YYYY-MM-DD') as tgl,
                case
                    when d.i_product_statusid = 'STP1' then a.i_product_id || ' (*STP)'
                    when d.i_product_statusid = 'STP2' then a.i_product_id || ' (#STP)'
                    else a.i_product_id
                end as i_product_id,
                a.e_product_name,
                b.e_product_categoryname,
                c.e_product_motifname ,
                d.e_product_statusname,
                case
                    when a.f_product_active = 't' then 'Aktif'
                    else 'Nonaktif'
                end as act,	
                case
                    when a.f_pareto = 't' then 'PARETO'
                    else 'NONPARETO'
                end as prt
            from
                tr_product a
            inner join tr_product_category b on (b.i_product_category = a.i_product_category)
            inner join tr_product_motif c on (c.i_product_motif = a.i_product_motif)
            inner join tr_product_status d on (d.i_product_status = a.i_product_status)
            WHERE
                a.i_company = '$this->i_company'
                $sts
            ORDER BY
                2 ASC
        ", FALSE);
    }
}

/* End of file Mmaster.php */
