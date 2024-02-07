<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Minforata2lambat extends CI_Model
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

        if ($i_area != 'ALL') {
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
                a.d_jatuh_tempo,
                aa.i_area_id || ' - ' || aa.e_area_name as areaa,
                ab.i_customer_id as kodee,
                ab.e_customer_name as namee,
                a.i_nota_id,
                a.v_nota_netto::money as v_nota_netto,
                a.d_nota,
                b.n_customer_top,
                c.n_toleransi,
                CAST((a.d_nota + CAST(b.n_customer_top || 'days' AS INTERVAL) + CAST(c.n_toleransi || 'days' AS INTERVAL)) AS date) AS tempo_tol,
                e.d_alokasi as alo,               
	            d.v_jumlah::money as v_jumlah,
                bc.e_rv_refference_type_name,
                d.e_remark,
	            current_date as tgll,
                0 AS umur,
                '$dfrom' AS dfrom,
                '$dto' AS dto
            from
                tm_nota a
                inner join tr_customer b on (b.i_customer=a.i_customer)
                inner join tr_city c on (c.i_city=b.i_city)
                inner join tm_alokasi_item d on (d.i_nota=a.i_nota)
                inner join tm_alokasi e on (e.i_alokasi=d.i_alokasi)
                inner join tr_area aa on (aa.i_area=a.i_area)
                inner join tr_customer ab on (ab.i_customer=a.i_customer)
                inner join tm_rv_item bb on (bb.i_rv_item=d.i_rv_item)
                inner join tr_rv_refference_type bc on (bb.i_rv_refference_type=bc.i_rv_refference_type)
                WHERE
                    a.i_company = '$this->i_company'
                    AND a.f_nota_cancel = 'f'
                    AND a.d_jatuh_tempo BETWEEN '$dfrom' AND '$dto'
                    $area
                    $customer
                ORDER BY
                    1
        ", FALSE);
        $datatables->edit('umur', function ($data) {
            $pecah1 = explode("-", $data['tempo_tol']);
            $date1  = $pecah1[2];
            $month1 = $pecah1[1];
            $year1  = $pecah1[0];
            $jd1    = GregorianToJD($month1, $date1, $year1);
            $pecah2 = explode('-', $data['alo']);
            $date2  = $pecah2[2];
            $month2 = $pecah2[1];
            $year2  = $pecah2[0];
            $jd2    = GregorianToJD($month2, $date2, $year2);
            $lama   = $jd2 - $jd1;
            if ($lama < 0) {
                $data = "<span class='badge bg-yellow bg-darken-3 badge-pill'>" . $lama . "</span>";
            } else {
                $data = "<span class='badge bg-Success bg-darken-1 badge-pill'>" . $lama . "</span>";
            }
            return $data;
        });
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('tgll');
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
        if ($i_area != 'ALL') {
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

    /** Get Data Untuk Export */
    public function get_data($dfrom, $dto, $i_area, $i_customer)
    {
        $dfrom  = date('Y-m-d', strtotime($dfrom));
        $dto    = date('Y-m-d', strtotime($dto));
        if ($i_area != 'ALL') {
            $area = "AND a.i_area = '$i_area' ";
        } else {
            $area = "";
        }

        if ($i_customer != 'ALL') {
            $customer = "AND a.i_customer = '$i_customer' ";
        } else {
            $customer = "";
        }

        return $this->db->query("SELECT
                a.d_jatuh_tempo,
                aa.i_area_id || ' - ' || aa.e_area_name as areaa,
                ab.i_customer_id as kodee,
                ab.e_customer_name as namee,
                a.i_nota_id,
                a.v_nota_netto as v_nota_netto,
                a.d_nota,
                b.n_customer_top,
                c.n_toleransi,
                CAST((a.d_nota + CAST(b.n_customer_top || 'days' AS INTERVAL) + CAST(c.n_toleransi || 'days' AS INTERVAL)) AS date) AS tempo_tol,
                e.d_alokasi as alo,                
	            d.v_jumlah ,
                bc.e_rv_refference_type_name,
                d.e_remark,
	            current_date as tgll,
                0 AS umur,
                '$dfrom' AS dfrom,
                '$dto' AS dto
            from
                tm_nota a
                inner join tr_customer b on (b.i_customer=a.i_customer)
                inner join tr_city c on (c.i_city=b.i_city)
                inner join tm_alokasi_item d on (d.i_nota=a.i_nota)
                inner join tm_alokasi e on (e.i_alokasi=d.i_alokasi)
                inner join tr_area aa on (aa.i_area=a.i_area)
                inner join tr_customer ab on (ab.i_customer=a.i_customer)
                inner join tm_rv_item bb on (bb.i_rv_item=d.i_rv_item)
                inner join tr_rv_refference_type bc on (bb.i_rv_refference_type=bc.i_rv_refference_type)
                WHERE
                    a.i_company = '$this->i_company'
                    AND a.f_nota_cancel = 'f'
                    AND a.d_jatuh_tempo BETWEEN '$dfrom' AND '$dto'
                    $area
                    $customer
                ORDER BY
                    1
        ", FALSE);
    }
}

/* End of file Mmaster.php */
