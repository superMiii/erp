<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Minfocusnpwp extends CI_Model
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

        $i_area = $this->input->post('i_area', TRUE);
        if ($i_area == '') {
            $i_area = $this->uri->segment(3);
        }

        if ($i_area != 'NA') {
            $area = "AND a.i_area = '$i_area' ";
        } else {
            $area = "";
        }

        $i_price_group = $this->input->post('i_price_group', TRUE);
        if ($i_price_group == '') {
            $i_price_group = $this->uri->segment(4);
        }

        if ($i_price_group != 'ALL') {
            $price_group = "AND a.i_price_group = '$i_price_group' ";
        } else {
            $price_group = "";
        }

        // $dfrom  = date('Y-m-d', strtotime($dfrom));
        // $dto    = date('Y-m-d', strtotime($dto));

        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("SELECT
                DISTINCT 
                i_customer,
                b.i_area_id || ' - ' || initcap(b.e_area_name) as e_area_name,
                i_customer_id || ' - ' || initcap(a.e_customer_name) as e_customer_name,
                case when a.f_customer_pkp = true then 'PKP' else 'NonPKP' end as ppn, 
                e_customer_npwpcode,                
                e_customer_npwpname,
                e_customer_npwpaddress
            from
                tr_customer a
            inner join tr_area b on
                (b.i_area = a.i_area)
            inner join tr_city c on
                (c.i_city = a.i_city)
            inner join tr_customer_type d on
                (d.i_customer_type = a.i_customer_type)
            inner join tr_city e on
                (e.i_city = c.i_city)
            inner join tr_customer_type f on
                (f.i_customer_type = a.i_customer_type)
            inner join tr_customer_status g on
                (g.i_customer_status = a.i_customer_status)
            inner join tr_price_group h on
                (h.i_price_group = a.i_price_group)
            inner join tm_user_area i on (i.i_area=a.i_area and i.i_user='$this->i_user')
            WHERE
                a.f_customer_active = 't'
                AND a.i_company = '$this->i_company'
                $area
            ORDER BY
                e_customer_name ASC
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
    public function get_price_group($cari, $i_area)
    {
        if ($i_area != 'NA') {
            $area = "AND i_area = '$i_area' ";
        } else {
            $area = "";
        }
        return $this->db->query("SELECT 
                i_price_group, i_price_groupid , e_price_groupname
            FROM 
                tr_price_group
            WHERE 
                (e_price_groupname ILIKE '%$cari%')
                AND i_company = '$this->i_company' 
                AND f_price_groupactive = 'true' 
                $area
            ORDER BY 3 ASC
        ", FALSE);
    }

    /** Get Data Untuk Edit */
    // public function get_data($i_area, $i_price_group)
    public function get_data($i_area)
    {
        if ($i_area != 'NA') {
            $area = "AND a.i_area = '$i_area' ";
        } else {
            $area = "";
        }
        // if ($i_price_group != 'ALL') {
        //     $price_group = "AND a.i_price_group = '$i_price_group' ";
        // } else {
        //     $price_group = "";
        // }
        // $dfrom  = date('Y-m-d', strtotime($dfrom));
        // $dto    = date('Y-m-d', strtotime($dto));
        return $this->db->query("SELECT
        DISTINCT 
                i_customer,
                b.i_area_id || ' - ' || initcap(b.e_area_name) as e_area_name,
                i_customer_id || ' - ' || initcap(a.e_customer_name) as e_customer_name,
                case when a.f_customer_pkp = true then 'PKP' else 'NonPKP' end as ppn, 
                e_customer_npwpcode,                
                e_customer_npwpname,
                e_customer_npwpaddress
            from
                tr_customer a
            inner join tr_area b on
                (b.i_area = a.i_area)
            inner join tr_city c on
                (c.i_city = a.i_city)
            inner join tr_customer_type d on
                (d.i_customer_type = a.i_customer_type)
            inner join tr_city e on
                (e.i_city = c.i_city)
            inner join tr_customer_type f on
                (f.i_customer_type = a.i_customer_type)
            inner join tr_customer_status g on
                (g.i_customer_status = a.i_customer_status)
            inner join tr_price_group h on
                (h.i_price_group = a.i_price_group)
            inner join tm_user_area i on (i.i_area=a.i_area and i.i_user='$this->i_user')
            WHERE
                a.f_customer_active = 't'
                AND a.i_company = '$this->i_company'
                $area
            ORDER BY
                e_customer_name ASC
                ", FALSE);
    }
}

/* End of file Mmaster.php */
