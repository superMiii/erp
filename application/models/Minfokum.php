<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Minfokum extends CI_Model
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

        $i_salesman = $this->input->post('i_salesman', TRUE);
        if ($i_salesman == '') {
            $i_salesman = $this->uri->segment(6);
        }

        if ($i_salesman != 'ALL') {
            $salesman = "AND a.i_salesman = '$i_salesman' ";
        } else {
            $salesman = "";
        }

        $dfrom  = date('Y-m-d', strtotime($dfrom));
        $dto    = date('Y-m-d', strtotime($dto));

        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("SELECT
                i_kum,
                i_kum_id,
                d_kum ,
                b.i_area_id || ' - ' || initcap(b.e_area_name) as e_area_name,
                c.i_customer_id as codee,
                initcap(c.e_customer_name) as e_customer_name,
                d.e_salesman_name ,
                e.e_bank_name ,
                a.e_atasnama,
                a.v_jumlah::MONEY AS v_jumlah ,
                f.i_dt_id 
            from
                tm_kum a
            inner join tr_area b on (b.i_area=a.i_area)
            inner join tr_customer c on (c.i_customer=a.i_customer)
            inner join tr_salesman d on (d.i_salesman=a.i_salesman)
            inner join tr_bank e on (e.i_bank=a.i_bank)
            left join tm_dt f on (f.i_dt=a.i_dt)
            WHERE
                a.f_kum_cancel = 'f'
                AND a.i_company = '$this->i_company'
                AND a.d_kum BETWEEN '$dfrom' AND '$dto'
                $area
                $salesman
            ORDER BY
                2 ASC
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

    /** Get salesman */
    public function get_salesman($cari, $i_area)
    {
        if ($i_area != 'NA') {
            $area = "AND i_area = '$i_area' ";
        } else {
            $area = "";
        }
        return $this->db->query("SELECT 
                distinct 
                a.i_salesman,
                i_salesman_id ,
                e_salesman_name,
                c.i_area 
            from
                tr_salesman a
            left join tr_salesman_areacover b on (a.i_salesman=b.i_salesman)
            left join tr_area_cover_item c on (c.i_area_cover=b.i_area_cover)
            WHERE 
                (e_salesman_name ILIKE '%$cari%' OR i_salesman_id ILIKE '%$cari%')
                AND a.i_company = '$this->i_company' 
                AND f_salesman_active = 'true' 
                $area
            ORDER BY 3 ASC
        ", FALSE);
    }

    /** Get Data Untuk Edit */
    public function get_data($dfrom, $dto, $i_area, $i_salesman)
    {
        if ($i_area != 'NA') {
            $area = "AND a.i_area = '$i_area' ";
        } else {
            $area = "";
        }
        if ($i_salesman != 'ALL') {
            $salesman = "AND a.i_salesman = '$i_salesman' ";
        } else {
            $salesman = "";
        }
        $dfrom  = date('Y-m-d', strtotime($dfrom));
        $dto    = date('Y-m-d', strtotime($dto));
        return $this->db->query("SELECT
                    i_kum,
                    i_kum_id,
                    d_kum ,
                    b.i_area_id || ' - ' || initcap(b.e_area_name) as e_area_name,
                    c.i_customer_id as codee,
                    initcap(c.e_customer_name) as e_customer_name,
                    d.e_salesman_name ,
                    e.e_bank_name ,
                    a.e_atasnama,
                    a.v_jumlah ,
                    f.i_dt_id 
                from
                    tm_kum a
                inner join tr_area b on (b.i_area=a.i_area)
                inner join tr_customer c on (c.i_customer=a.i_customer)
                inner join tr_salesman d on (d.i_salesman=a.i_salesman)
                inner join tr_bank e on (e.i_bank=a.i_bank)
                left join tm_dt f on (f.i_dt=a.i_dt)
                WHERE
                    a.f_kum_cancel = 'f'
                    AND a.i_company = '$this->i_company'
                    AND a.d_kum BETWEEN '$dfrom' AND '$dto'
                    $area
                    $salesman
                ORDER BY
                    2 ASC
        ", FALSE);
    }
}

/* End of file Mmaster.php */
