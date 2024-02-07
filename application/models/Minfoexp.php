<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Minfoexp extends CI_Model
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
            $area = "AND e.i_area = '$i_area' ";
        } else {
            $area = "";
        }

        $i_customer = $this->input->post('i_customer', TRUE);
        if ($i_customer == '') {
            $i_customer = $this->uri->segment(6);
        }

        if ($i_customer != 'ALL') {
            $customer = "AND e.i_customer = '$i_customer' ";
        } else {
            $customer = "";
        }

        $dfrom  = date('Y-m-d', strtotime($dfrom));
        $dto    = date('Y-m-d', strtotime($dto));

        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("SELECT
                a.i_sl_ekspedisi_item,
                f.i_area_id || ' - ' || f.e_area_name as araa,
                e.i_do_id,
                e.d_do,
                g.i_customer_id,
                g.e_customer_name,
                c.i_sl_id,
                c.d_sl ,
                b.e_sl_ekspedisi 
            from
                tm_sl_ekspedisi_item a
            inner join tr_sl_ekspedisi b on (b.i_sl_ekspedisi=a.i_sl_ekspedisi)
            inner join tm_sl c on (c.i_sl = a.i_sl)
            inner join tm_sl_item d on (d.i_sl=c.i_sl)
            inner join tm_do e on (e.i_do=d.i_do)
            inner join tr_area f on (f.i_area=e.i_area)
            inner join tr_customer g on (g.i_customer=e.i_customer)
            inner join tm_user_area u on (u.i_area = f.i_area and u.i_user = '$this->i_user')
            WHERE
                c.f_sl_batal = 'f'
                AND c.i_company = '$this->i_company'
                AND c.d_sl BETWEEN '$dfrom' AND '$dto'
                $area
                $customer
            ORDER BY
                1 asc
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

    /** Get Data Untuk Edit */
    public function get_data($dfrom, $dto, $i_area, $i_customer)
    {
        if ($i_area != 'NA') {
            $area = "AND e.i_area = '$i_area' ";
        } else {
            $area = "";
        }
        if ($i_customer != 'ALL') {
            $customer = "AND e.i_customer = '$i_customer' ";
        } else {
            $customer = "";
        }
        $dfrom  = date('Y-m-d', strtotime($dfrom));
        $dto    = date('Y-m-d', strtotime($dto));
        return $this->db->query("SELECT
                a.i_sl_ekspedisi_item,
                f.i_area_id || ' - ' || f.e_area_name as araa,
                e.i_do_id,
                e.d_do,
                g.i_customer_id,
                g.e_customer_name,
                c.i_sl_id,
                c.d_sl ,
                b.e_sl_ekspedisi 
            from
                tm_sl_ekspedisi_item a
            inner join tr_sl_ekspedisi b on (b.i_sl_ekspedisi=a.i_sl_ekspedisi)
            inner join tm_sl c on (c.i_sl = a.i_sl)
            inner join tm_sl_item d on (d.i_sl=c.i_sl)
            inner join tm_do e on (e.i_do=d.i_do)
            inner join tr_area f on (f.i_area=e.i_area)
            inner join tr_customer g on (g.i_customer=e.i_customer)
            inner join tm_user_area u on (u.i_area = f.i_area and u.i_user = '$this->i_user')
            WHERE
                c.f_sl_batal = 'f'
                AND c.i_company = '$this->i_company'
                AND c.d_sl BETWEEN '$dfrom' AND '$dto'
                $area
                $customer
            ORDER BY
                1 asc
        ", FALSE);
    }
}

/* End of file Mmaster.php */
