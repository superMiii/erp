<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Minforb extends CI_Model
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
            $supplier = "AND b.i_supplier = '$i_supplier' ";
        } else {
            $supplier = "";
        }

        $dfrom  = date('Y-m-d', strtotime($dfrom));
        $dto    = date('Y-m-d', strtotime($dto));

        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("SELECT
                b.i_bbr,
                b.i_bbr_id ,
                b.d_bbr ,
                c.e_supplier_name ,
                b.e_remark,
                b.f_dn as f_dn,
                d.i_product_id ,
                d.e_product_name ,
                e.e_product_motifname ,
                a.v_unit_price::money as v_unit_price,
                a.n_quantity 
            from
                tm_bbr_item a
            inner join tm_bbr b on (b.i_bbr=a.i_bbr)
            inner join tr_supplier c on (c.i_supplier=b.i_supplier)
            inner join tr_product d on (d.i_product=a.i_product)
            inner join tr_product_motif e on (e.i_product_motif=a.i_product_motif)
            WHERE
                f_bbr_cancel = 'f'
                AND b.i_company = '$this->i_company'
                AND b.d_bbr BETWEEN '$dfrom' AND '$dto'
                $supplier
            ORDER BY
                a.i_bbr_item ASC
        ", FALSE);

        $datatables->edit('f_dn', function ($data) {
            if ($data['f_dn'] == 'f') {
                $status = "Belum Debet Nota";
                $color  = 'warning';
            } else {
                $color  = 'teal';
                $status = "Sudah Debet Nota";
            }
            $data = "<span class='badge bg-" . $color . " badge-pill'>" . $status . "</span>";
            return $data;
        });
        return $datatables->generate();
    }

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
            $supplier = "AND b.i_supplier = '$i_supplier' ";
        } else {
            $supplier = "";
        }
        $dfrom  = date('Y-m-d', strtotime($dfrom));
        $dto    = date('Y-m-d', strtotime($dto));
        return $this->db->query("SELECT
                b.i_bbr,
                b.i_bbr_id ,
                b.d_bbr ,
                c.e_supplier_name ,
                b.e_remark,
                case when b.f_dn='f' then 'Belum Debet Nota' else 'Sudah Debet Nota' end as f_dn,
                d.i_product_id ,
                d.e_product_name ,
                e.e_product_motifname ,
                a.v_unit_price as v_unit_price,
                a.n_quantity 
            from
                tm_bbr_item a
            inner join tm_bbr b on (b.i_bbr=a.i_bbr)
            inner join tr_supplier c on (c.i_supplier=b.i_supplier)
            inner join tr_product d on (d.i_product=a.i_product)
            inner join tr_product_motif e on (e.i_product_motif=a.i_product_motif)
            WHERE
                f_bbr_cancel = 'f'
                AND b.i_company = '$this->i_company'
                AND b.d_bbr BETWEEN '$dfrom' AND '$dto'
                $supplier
            ORDER BY
                a.i_bbr_item ASC
        ", FALSE);
    }
}

/* End of file Mmaster.php */
