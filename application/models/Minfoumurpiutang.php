<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Minfoumurpiutang extends CI_Model
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

        // $i_customer = $this->input->post('i_customer', TRUE);
        // if ($i_customer == '') {
        //     $i_customer = $this->uri->segment(6);
        // }

        // if ($i_customer != 'ALL') {
        //     $customer = "AND a.i_customer = '$i_customer' ";
        // } else {
        //     $customer = "";
        // }

        $dfrom  = date('Y-m-d', strtotime($dfrom));
        $dto    = date('Y-m-d', strtotime($dto));

        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("SELECT 
                    i_nota,
                    i_nota_id,
                    codee,
                    e_customer_name,
                    d_nota,
                    n_customer_top,
                    n_toleransi,
                    d_jatuh_tempo,
                    d_jatuh_tempo_top,
                    netto,
                    sisa,
                    CONCAT(n_toleransi + n_customer_top, ' Hari') as lila,
                    0 AS umur,
                    '$dto' AS dto
                FROM(
                    SELECT
                        a.i_nota,
                        b.i_area_id || ' - ' || initcap(b.e_area_name) AS e_area_name,
                        d.i_do_id,
                        d.d_do,
                        a.i_nota_id,
                        a.d_nota,
                        g.n_customer_top,
                        h.n_toleransi,
                        a.d_jatuh_tempo,
                        CAST((a.d_nota + CAST(g.n_customer_top || 'days' AS INTERVAL) + CAST(h.n_toleransi || 'days' AS INTERVAL)) AS date) AS d_jatuh_tempo_top,
                        f.i_salesman_id || ' - ' || initcap(f.e_salesman_name) AS e_salesman_name,
                        g.i_customer_id AS codee,
                        initcap(g.e_customer_name) AS e_customer_name,
                        initcap(h.e_city_name) AS e_city_name,
                        g.e_customer_address,
                        g.e_customer_phone,
                        CASE
                            WHEN g.f_customer_pkp = TRUE THEN 'Ya'
                            ELSE 'Tidak'
                        END AS pkp,
                        a.v_nota_netto::money AS netto,
                        a.v_sisa::money AS sisa,
                        CASE
                            WHEN v_nota_netto <> v_sisa THEN 'Sisa'
                            ELSE 'Utuh'
                        END AS status
                    FROM
                        tm_nota a
                    INNER JOIN tr_area b ON
                        (b.i_area = a.i_area)
                    INNER JOIN (
                        SELECT
                            DISTINCT i_nota,
                            i_do
                        FROM
                            tm_nota_item) c ON
                        (c.i_nota = a.i_nota)
                    INNER JOIN tm_do d ON
                        (d.i_do = c.i_do)
                    INNER JOIN tm_so e ON
                        (e.i_so = d.i_so)
                    INNER JOIN tr_salesman f ON
                        (f.i_salesman = e.i_salesman)
                    INNER JOIN tr_customer g ON
                        (g.i_customer = a.i_customer)
                    INNER JOIN tr_city h ON
                        (h.i_city = g.i_city)
                        inner join tm_user_area u on
                            (u.i_area = b.i_area and u.i_user = '$this->i_user')
                    WHERE
                        a.i_company = '$this->i_company'
                        AND a.f_nota_cancel = 'f'
                        AND v_sisa > 0
                        $area
                ) AS x
        ", FALSE);
        $datatables->edit('umur', function ($data) {
            $pecah1 = explode("-", $data['dto']);
            $date1  = $pecah1[2];
            $month1 = $pecah1[1];
            $year1  = $pecah1[0];
            $jd1    = GregorianToJD($month1, $date1, $year1);
            $pecah2 = explode('-', $data['d_jatuh_tempo_top']);
            $date2  = $pecah2[2];
            $month2 = $pecah2[1];
            $year2  = $pecah2[0];
            $jd2    = GregorianToJD($month2, $date2, $year2);
            $lama   = $jd1 - $jd2;
            if ($lama > 0 && $lama <= 7) {
                $data = "<span class='badge bg-teal bg-darken-3 badge-pill'>" . $lama . "</span>";
            } elseif ($lama >= 8 && $lama <= 15) {
                $data = "<span class='badge bg-yellow bg-darken-3 badge-pill'>" . $lama . "</span>";
            } elseif ($lama > 15) {
                $data = "<span class='badge bg-red bg-darken-3 badge-pill'>" . $lama . "</span>";
            } else {
                $data = "<span class='badge badge-pill'>" . $lama . "</span>";
            }
            return $data;
        });
        $datatables->hide('d_jatuh_tempo');
        $datatables->hide('dto');
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
    // public function get_customer($cari, $i_area)
    // {
    //     if ($i_area != 'NA') {
    //         $area = "AND i_area = '$i_area' ";
    //     } else {
    //         $area = "";
    //     }
    //     return $this->db->query("SELECT 
    //             i_customer, i_customer_id , e_customer_name
    //         FROM 
    //             tr_customer
    //         WHERE 
    //             (e_customer_name ILIKE '%$cari%' OR i_customer_id ILIKE '%$cari%')
    //             AND i_company = '$this->i_company' 
    //             AND f_customer_active = 'true' 
    //             $area
    //         ORDER BY 3 ASC
    //     ", FALSE);
    // }

    /** Get Data Untuk Edit */
    public function get_data($dfrom, $dto, $i_area)
    {
        if ($i_area != 'NA') {
            $area = "AND a.i_area = '$i_area' ";
        } else {
            $area = "";
        }
        // if ($i_customer != 'ALL') {
        //     $customer = "AND a.i_customer = '$i_customer' ";
        // } else {
        //     $customer = "";
        // }
        $dfrom  = date('Y-m-d', strtotime($dfrom));
        $dto    = date('Y-m-d', strtotime($dto));
        return $this->db->query("SELECT
                    i_nota,
                e_area_name,
                i_do_id,
                d_do,
                i_nota_id,
                d_nota,
                n_customer_top,
                n_toleransi,
                CONCAT(n_toleransi + n_customer_top, ' Hari') as lila,
                d_jatuh_tempo,
                d_jatuh_tempo_top,
                e_salesman_name,
                codee,
                e_customer_name,
                e_city_name,
                e_customer_address ,
                e_customer_phone ,
                pkp,
                v_nota_netto,
                v_sisa,
                status
            FROM(
                SELECT
                    a.i_nota,
                    b.i_area_id || ' - ' || initcap(b.e_area_name) AS e_area_name,
                    d.i_do_id,
                    d.d_do,
                    a.i_nota_id,
                    a.d_nota,
                    g.n_customer_top,
                    h.n_toleransi,
                    a.d_jatuh_tempo,
                    CAST((a.d_nota + CAST(g.n_customer_top || 'days' AS INTERVAL) + CAST(h.n_toleransi || 'days' AS INTERVAL)) AS date) AS d_jatuh_tempo_top,
                    f.i_salesman_id || ' - ' || initcap(f.e_salesman_name) AS e_salesman_name,
                        g.i_customer_id AS codee,
                        initcap(g.e_customer_name) AS e_customer_name,
                    initcap(h.e_city_name) AS e_city_name,
		            g.e_customer_address ,
		            g.e_customer_phone ,
                    CASE
                        WHEN g.f_customer_pkp = TRUE THEN 'Ya'
                        ELSE 'Tidak'
                    END AS pkp,
                    a.v_nota_netto,
                    a.v_sisa,
                    CASE
                        WHEN v_nota_netto <> v_sisa THEN 'Sisa'
                        ELSE 'Utuh'
                    END AS status
                FROM
                    tm_nota a
                INNER JOIN tr_area b ON
                    (b.i_area = a.i_area)
                INNER JOIN (
                    SELECT
                        DISTINCT i_nota,
                        i_do
                    FROM
                        tm_nota_item) c ON
                    (c.i_nota = a.i_nota)
                INNER JOIN tm_do d ON
                    (d.i_do = c.i_do)
                INNER JOIN tm_so e ON
                    (e.i_so = d.i_so)
                INNER JOIN tr_salesman f ON
                    (f.i_salesman = e.i_salesman)
                INNER JOIN tr_customer g ON
                    (g.i_customer = a.i_customer)
                INNER JOIN tr_city h ON
                    (h.i_city = g.i_city)
                    inner join tm_user_area u on
                        (u.i_area = b.i_area and u.i_user = '$this->i_user')
                WHERE
                    a.i_company = '$this->i_company'
                    AND a.f_nota_cancel = 'f'
                    AND v_sisa > 0
                    $area
            ) AS x
        ", FALSE);
    }
}

/* End of file Mmaster.php */
