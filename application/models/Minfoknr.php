<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Minfoknr extends CI_Model
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
                a.i_kn AS id,
                a.i_kn_id,
                to_char(a.d_kn, 'DD FMMonth YYYY') AS d_kn,
                d.e_area_name,
                e.i_customer_id,
                e.e_customer_name,
                f.e_salesman_name,
                a.v_netto::money AS v_netto,
                a.v_sisa::money AS v_sisa
            FROM
                tm_kn a
            INNER JOIN tr_area d ON
                (d.i_area = a.i_area)
            INNER JOIN tr_customer e ON
                (e.i_customer = a.i_customer)
            INNER JOIN tr_salesman f ON
                (f.i_salesman = a.i_salesman)
            INNER JOIN tm_user_area g ON
                (g.i_area = a.i_area)
            WHERE
                a.i_company = '$this->i_company'
                AND a.d_kn BETWEEN '$dfrom' AND '$dto'
                AND g.i_user = '$this->i_user'
                AND a.f_kn_retur = 'f'
                $area
                $customer
            ORDER BY
                3 ASC
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
            inner join tm_kn bb on (bb.i_area=a.i_area AND bb.f_kn_retur = 'f')
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
                inner join tm_kn b on (b.i_customer=a.i_customer AND b.f_kn_retur = 'f')
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
                a.i_kn AS id,
                a.i_kn_id,
                to_char(a.d_kn, 'DD FMMonth YYYY') AS d_kn,
                d.e_area_name,
                e.i_customer_id,
                e.e_customer_name,
                f.e_salesman_name,
                a.v_netto AS v_netto,
                a.v_sisa AS v_sisa
            FROM
                tm_kn a
            INNER JOIN tr_area d ON
                (d.i_area = a.i_area)
            INNER JOIN tr_customer e ON
                (e.i_customer = a.i_customer)
            INNER JOIN tr_salesman f ON
                (f.i_salesman = a.i_salesman)
            INNER JOIN tm_user_area g ON
                (g.i_area = a.i_area)
            WHERE
                a.i_company = '$this->i_company'
                AND a.d_kn BETWEEN '$dfrom' AND '$dto'
                AND g.i_user = '$this->i_user'
                AND a.f_kn_retur = 'f'
                $area
                $customer
            ORDER BY
                3 ASC
        ", FALSE);
    }
}

/* End of file Mmaster.php */
