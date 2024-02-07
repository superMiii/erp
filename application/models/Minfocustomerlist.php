<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Minfocustomerlist extends CI_Model
{

    /** List Datatable */
    public function serverside()
    {

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


        $i_customer_status = $this->input->post('i_customer_status', TRUE);
        if ($i_customer_status == '') {
            $i_customer_status = $this->uri->segment(5);
        }

        if ($i_customer_status != 'ALL') {
            $customer_status = "AND a.i_customer_status = '$i_customer_status' ";
        } else {
            $customer_status = "";
        }


        $i_customer_group = $this->input->post('i_customer_group', TRUE);
        if ($i_customer_group == '') {
            $i_customer_group = $this->uri->segment(6);
        }

        if ($i_customer_group != 'ALL') {
            $customer_group = "AND a.i_customer_group = '$i_customer_group' ";
        } else {
            $customer_group = "";
        }

        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("SELECT
                DISTINCT 
                e_customer_name as t,
                to_char(d_customer_entry, 'DD FMMonth YYYY') AS reg,
                b.i_area_id || ' - ' || initcap(b.e_area_name) as e_area_name,
                initcap(c.e_city_name) as e_city_name,
                i_customer_id  || ' - ' || a.e_customer_name as e_customer_name,
                initcap(a.e_customer_address) as e_customer_address,
                e_customer_groupname,
                case when a.f_customer_pkp = true then 'PKP' else 'NonPKP' end as ppn, 
                e_customer_npwpcode,
                initcap(a.e_customer_owner) as e_customer_owner,
                e_ktp_owner,
                e_customer_phone,
                n_customer_discount1,
                n_customer_discount2,
                n_customer_discount3,
                initcap(f.e_customer_typename) as e_customer_typename,
                initcap(g.e_customer_statusname) as e_customer_statusname,
                initcap(h.e_price_groupname) as e_price_groupname,
                n_customer_top,
                plafon::money as pla
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
            inner join tr_customer_group p on (p.i_customer_group=a.i_customer_group)
            inner join tm_user_area i on (i.i_area=a.i_area and i.i_user='$this->i_user')
            WHERE
                a.f_customer_active = 't'
                AND a.i_company = '$this->i_company'
                $area
                $price_group
                $customer_group
                $customer_status
            ORDER BY
                1
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
        return $this->db->query("SELECT distinct
                a.i_price_group, i_price_groupid , e_price_groupname
            FROM 
                tr_price_group a
            inner join tr_customer b on (b.i_price_group=a.i_price_group)
            WHERE 
                (e_price_groupname ILIKE '%$cari%')
                AND a.i_company = '$this->i_company' 
                AND f_price_groupactive = 'true' 
                $area
            ORDER BY 3 ASC
        ", FALSE);
    }

    public function get_customer_status($cari)
    {
        return $this->db->query("SELECT 
                DISTINCT
                i_customer_status, 
                i_customer_statusid, 
                e_customer_statusname
            FROM 
                tr_customer_status
            WHERE 
                (e_customer_statusname ILIKE '%$cari%')
                AND i_company = '$this->i_company' 
                AND f_customer_statusactive = true
            ORDER BY 3 ASC
        ", FALSE);
    }

    public function get_customer_group($cari, $i_area)
    {
        if ($i_area != 'NA') {
            $area = "AND i_area = '$i_area' ";
        } else {
            $area = "";
        }
        return $this->db->query("SELECT distinct
                a.i_customer_group, e_customer_groupname
            FROM 
                tr_customer_group a
            inner join tr_customer b on (b.i_customer_group=a.i_customer_group)
            WHERE 
                (e_customer_groupname ILIKE '%$cari%')
                AND a.i_company = '$this->i_company' 
                AND f_customer_groupactive = 'true' 
                $area
            ORDER BY 2 ASC
        ", FALSE);
    }

    /** Get Data Untuk Edit */
    public function get_data($i_area, $i_price_group, $i_customer_group, $i_customer_status)
    {
        if ($i_area != 'NA') {
            $area = "AND a.i_area = '$i_area' ";
        } else {
            $area = "";
        }

        if ($i_price_group != 'ALL') {
            $price_group = "AND a.i_price_group = '$i_price_group' ";
        } else {
            $price_group = "";
        }

        if ($i_customer_status != 'ALL') {
            $customer_status = "AND a.i_customer_status = '$i_customer_status' ";
        } else {
            $customer_status = "";
        }

        if ($i_customer_group != 'ALL') {
            $customer_group = "AND a.i_customer_group = '$i_customer_group' ";
        } else {
            $customer_group = "";
        }

        return $this->db->query("SELECT
        DISTINCT 
                e_customer_name as t,
                to_char(d_customer_entry, 'DD FMMonth YYYY') AS reg,
                b.i_area_id || ' - ' || initcap(b.e_area_name) as e_area_name,
                initcap(c.e_city_name) as e_city_name,
                i_customer_id  || ' - ' || a.e_customer_name as e_customer_name,
                initcap(a.e_customer_address) as e_customer_address,
                e_customer_groupname,
                case when a.f_customer_pkp = true then 'PKP' else 'NonPKP' end as ppn, 
                e_customer_npwpcode,
                initcap(a.e_customer_owner) as e_customer_owner,
                e_ktp_owner,
                e_customer_phone,
                n_customer_discount1,
                n_customer_discount2,
                n_customer_discount3,
                initcap(f.e_customer_typename) as e_customer_typename,
                initcap(g.e_customer_statusname) as e_customer_statusname,
                initcap(h.e_price_groupname) as e_price_groupname,
                n_customer_top,
                plafon as pla
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
            inner join tr_customer_group p on (p.i_customer_group=a.i_customer_group)
            inner join tm_user_area i on (i.i_area=a.i_area and i.i_user='$this->i_user')
            WHERE
                a.f_customer_active = 't'
                AND a.i_company = '$this->i_company'
                $area
                $price_group
                $customer_group
                $customer_status
            ORDER BY
                1
        ", FALSE);
    }
}

/* End of file Mmaster.php */
