<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Minforincianretur extends CI_Model
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


        $dfrom  = date('Y-m-d', strtotime($dfrom));
        $dto    = date('Y-m-d', strtotime($dto));

        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("SELECT
                a.i_ttb ,
                a.i_ttb_id ,
                to_char(a.d_ttb, 'DD FMMonth YYYY') AS tgl,
                e.e_salesman_name ,	 
                c.i_area_id || ' - ' || c.e_area_name as e_area_name,
                i_customer_id  || ' - ' || d.e_customer_name as e_customer_name,
                g.e_alasan_retur_name ,
                f.i_product_id,
                f.e_product_name,
                h.e_product_motifname,
                b.n_quantity ,
                b.n_quantity_receive ,
                b.v_unit_price::money as hr,
                b.v_ttb_discount1::money as d1,
                b.v_ttb_discount2::money as d2,
                b.v_ttb_discount3::money as d3,
                ((b.v_unit_price * b.n_quantity) - (b.v_ttb_discount1 + b.v_ttb_discount2 + b.v_ttb_discount3))::money as tot,
                a.n_ppn_r,
                a.v_ttb_netto::money as v_ttb_netto 
            from
                tm_ttbretur a 	
            inner join tm_ttbretur_item b on (b.i_ttb=a.i_ttb)
            inner join tr_area c on (c.i_area=a.i_area)
            inner join tr_customer d on (d.i_customer=a.i_customer)
            inner join tr_salesman e on (e.i_salesman=a.i_salesman)
            inner join tr_product f on (f.i_product=b.i_product1)
            inner join tr_product_motif h on (h.i_product_motif=b.i_product1_motif)
            inner join tr_alasan_retur g on (g.i_alasan_retur=a.i_alasan_retur)
            WHERE
                a.f_ttb_cancel = 'f'
                AND a.i_company = '$this->i_company'
                AND a.d_ttb BETWEEN '$dfrom' AND '$dto'
                $area
                $customer
            ORDER BY
                a.d_ttb ASC
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
            inner join tm_ttbretur r on (r.i_area=a.i_area)
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
            $area = "AND a.i_area = '$i_area' ";
        } else {
            $area = "";
        }
        return $this->db->query("SELECT DISTINCT
                a.i_customer, i_customer_id , e_customer_name
            FROM 
                tr_customer a
            inner join tm_ttbretur r on (r.i_customer=a.i_customer)
            WHERE 
                (e_customer_name ILIKE '%$cari%' OR i_customer_id ILIKE '%$cari%')
                AND a.i_company = '$this->i_company' 
                AND f_customer_active = 'true' 
                $area
            ORDER BY 3 ASC
        ", FALSE);
    }

    /** Get Data Untuk Edit */
    public function get_data($dfrom, $dto, $i_area, $i_customer)
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

        $dfrom  = date('Y-m-d', strtotime($dfrom));
        $dto    = date('Y-m-d', strtotime($dto));
        return $this->db->query("SELECT
                a.i_ttb ,
                a.i_ttb_id ,
                to_char(a.d_ttb, 'DD FMMonth YYYY') AS tgl,
                e.e_salesman_name ,	 
                c.i_area_id || ' - ' || c.e_area_name as e_area_name,
                i_customer_id  || ' - ' || d.e_customer_name as e_customer_name,
                g.e_alasan_retur_name ,
                f.i_product_id,
                f.e_product_name,
                h.e_product_motifname,
                b.n_quantity ,
                b.n_quantity_receive ,
                b.v_unit_price as hr,
                b.v_ttb_discount1 as d1,
                b.v_ttb_discount2 as d2,
                b.v_ttb_discount3 as d3,
                ((b.v_unit_price * b.n_quantity) - (b.v_ttb_discount1 + b.v_ttb_discount2 + b.v_ttb_discount3)) as tot,
                a.n_ppn_r,
                a.v_ttb_netto as v_ttb_netto  
            from
                tm_ttbretur a 	
            inner join tm_ttbretur_item b on (b.i_ttb=a.i_ttb)
            inner join tr_area c on (c.i_area=a.i_area)
            inner join tr_customer d on (d.i_customer=a.i_customer)
            inner join tr_salesman e on (e.i_salesman=a.i_salesman)
            inner join tr_product f on (f.i_product=b.i_product1)
            inner join tr_product_motif h on (h.i_product_motif=b.i_product1_motif)
            inner join tr_alasan_retur g on (g.i_alasan_retur=a.i_alasan_retur)
            WHERE
                a.f_ttb_cancel = 'f'
                AND a.i_company = '$this->i_company'
                AND a.d_ttb BETWEEN '$dfrom' AND '$dto'
                $area
                $customer
            ORDER BY
                a.d_ttb ASC
        ", FALSE);
    }
}

/* End of file Mmaster.php */
