<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Minfoopvsdo extends CI_Model
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
                i_po_item,
                i_product_id,
                e_product_name,
                n_order,
                v_po::money AS v_po,
                n_delivery,
                v_gr::money AS v_gr,
                (n_delivery / n_order) * 100 AS persen_qty,
                (n_delivery / n_order) * 100 AS persen_rp,
                e_supplier_name
            FROM
                (
                SELECT
                    a.i_po,
                    a.d_po,
                    b.i_po_item,
                    c.i_product_id,
                    c.e_product_name,
                    b.n_order,
                    (b.n_order * b.v_product_mill) - b.v_po_discount AS v_po,
                    COALESCE ((e.n_deliver),0) AS n_delivery,
                    COALESCE (((e.n_deliver-e.n_dis_sup) * e.v_product_mill) - e.v_gr_discount,0) AS v_gr,
                    initcap(d.e_supplier_name) AS e_supplier_name
                FROM
                    tm_po a
                INNER JOIN tm_po_item b ON
                    (b.i_po = a.i_po)
                INNER JOIN tr_product c ON
                    (c.i_product = b.i_product)
                INNER JOIN tr_supplier d ON
                    (d.i_supplier = a.i_supplier)
                LEFT JOIN (
                    SELECT
                        e.i_po,
                        e.i_product,
                        e.n_deliver,
                        e.v_gr_discount,
                        e.v_product_mill,
			            e.n_dis_sup
                    FROM
                        tm_gr_item e
                    INNER JOIN tm_gr f ON
                        (f.i_gr = e.i_gr)
                    WHERE
                        f.i_company = '$this->i_company'
                        AND f.f_gr_cancel = 'f') e ON
                    (e.i_po = b.i_po
                        AND b.i_product = e.i_product)
                WHERE
                    a.i_company = '$this->i_company'
                    AND a.f_po_cancel = 'f'
                    AND a.d_po BETWEEN '$dfrom' AND '$dto'
                    $supplier
            ) AS x
            ORDER BY
                x.d_po,
                x.i_po,
                x.i_po_item
            ASC
        ", FALSE);
        $datatables->edit('persen_qty', function ($data) {
            return number_format($data['persen_qty'], 2) . '%';
        });
        $datatables->edit('persen_rp', function ($data) {
            return number_format($data['persen_rp'], 2) . '%';
        });
        $datatables->hide('persen_qty');
        $datatables->hide('persen_rp');
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
                i_po_item,
                i_product_id,
                e_product_name,
                n_order,
                v_po AS v_po,
                n_delivery,
                v_gr AS v_gr,
                (n_delivery / n_order) * 100 AS persen_qty,
                (n_delivery / n_order) * 100 AS persen_rp,
                e_supplier_name
            FROM
                (
                SELECT
                    a.i_po,
                    a.d_po,
                    b.i_po_item,
                    c.i_product_id,
                    c.e_product_name,
                    b.n_order,
                    (b.n_order * b.v_product_mill) - b.v_po_discount AS v_po,
                    COALESCE ((e.n_deliver),0) AS n_delivery,
                    COALESCE (((e.n_deliver-e.n_dis_sup) * e.v_product_mill) - e.v_gr_discount,0) AS v_gr,
                    initcap(d.e_supplier_name) AS e_supplier_name
                FROM
                    tm_po a
                INNER JOIN tm_po_item b ON
                    (b.i_po = a.i_po)
                INNER JOIN tr_product c ON
                    (c.i_product = b.i_product)
                INNER JOIN tr_supplier d ON
                    (d.i_supplier = a.i_supplier)
                LEFT JOIN (
                    SELECT
                        e.i_po,
                        e.i_product,
                        e.n_deliver,
                        e.v_gr_discount,
                        e.v_product_mill,
                        e.n_dis_sup
                    FROM
                        tm_gr_item e
                    INNER JOIN tm_gr f ON
                        (f.i_gr = e.i_gr)
                    WHERE
                        f.i_company = '$this->i_company'
                        AND f.f_gr_cancel = 'f') e ON
                    (e.i_po = b.i_po
                        AND b.i_product = e.i_product)
                WHERE
                    a.i_company = '$this->i_company'
                    AND a.f_po_cancel = 'f'
                    AND a.d_po BETWEEN '$dfrom' AND '$dto'
                    $supplier
            ) AS x
            ORDER BY
                x.d_po,
                x.i_po,
                x.i_po_item
            ASC
        ", FALSE);
    }
}

/* End of file Mmaster.php */
