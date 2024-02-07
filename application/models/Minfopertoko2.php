<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Minfopertoko2 extends CI_Model
{

    public function get_group()
    {
        return $this->db->query("SELECT to_char(DATE '1001-01-01' + (interval '1' month * generate_series(0,11)), 'FMMonth') AS bulan ", FALSE);
    }

    /** List Datatable */
    /* public function serverside($year, $area) */
    public function serverside()
    {
        $year = $this->input->post('year', TRUE);
        if ($year == '') {
            $year = $this->uri->segment(4);
        }
        if ($year != '0' && $year != null) {
            $year = $year;
        } else {
            $year = date('Y');
        }

        $i_area = $this->input->post('i_area', TRUE);
        if ($i_area == '') {
            $i_area = $this->uri->segment(3);
        }

        if ($i_area != '0' && $i_area != null) {
            $area = "AND a.i_area = '$i_area' ";
        } else {
            $area = "";
        }

        return $this->db->query("WITH raya AS (
            SELECT
            DISTINCT a.i_customer,
	        b.i_customer_id || '|' || b.e_customer_name || '|' || c.e_city_name || '|' || rr.e_area_name as e_customer_name,
            y.bulan
        FROM
            tm_nota a
        INNER JOIN tr_customer b ON
            (a.i_customer = b.i_customer)
        inner join tr_city c on (c.i_city= b.i_city)
        inner join tr_area rr on (rr.i_area=b.i_area)
        inner join tm_user_area u on (u.i_area = rr.i_area and u.i_user = '$this->i_user')
        CROSS JOIN (select to_char(DATE '1001-01-01' + (interval '1' month * generate_series(0,11)), 'MM') AS bulan) y
        WHERE
            a.i_company = '$this->i_company'
            AND a.f_nota_cancel = 'f'            
            AND to_char(a.d_nota, 'YYYY') = '$year'
            AND b.f_pareto = 't'
             $area
             )
        SELECT
            y.e_customer_name,
            jsonb_agg(y.bulan order by y.bulan) AS bulan,
            jsonb_agg(COALESCE(x.v_nota_netto, 0) order by y.bulan) AS v_nota_netto
        FROM
            (
            SELECT
                a.i_customer,
                b.e_customer_name,
                to_char(d_nota, 'MM') AS bulan,
                sum(v_nota_netto) AS v_nota_netto
            FROM
                tm_nota a
            INNER JOIN tr_customer b ON (b.i_customer = a.i_customer)
            WHERE
                f_nota_cancel = 'f'
                AND a.i_company = '$this->i_company'
                AND to_char(a.d_nota, 'YYYY') = '$year'
                AND b.f_pareto = 't'
                 $area
            GROUP BY
                1,
                2,
                3) AS x
        RIGHT JOIN raya y ON
            (x.i_customer = y.i_customer
                AND x.bulan = y.bulan)
        GROUP BY
            1
        order by 1 
        ", FALSE);
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




    public function serverside2($year, $i_area)
    // public function serverside()
    {
        if ($year != '0' && $year != null) {
            $year = $year;
        } else {
            $year = date('Y');
        }


        if ($i_area != '0' && $i_area != null) {
            $area = "AND a.i_area = '$i_area' ";
        } else {
            $area = "";
        }

        return $this->db->query("WITH raya AS (
            SELECT
            DISTINCT a.i_customer,
	        b.i_customer_id || '|' || b.e_customer_name || '|' || c.e_city_name || '|' || rr.e_area_name as e_customer_name,
            y.bulan
        FROM
            tm_nota a
        INNER JOIN tr_customer b ON
            (a.i_customer = b.i_customer)
        inner join tr_city c on (c.i_city= b.i_city)
        inner join tr_area rr on (rr.i_area=b.i_area)
        inner join tm_user_area u on (u.i_area = rr.i_area and u.i_user = '$this->i_user')
        CROSS JOIN (select to_char(DATE '1001-01-01' + (interval '1' month * generate_series(0,11)), 'MM') AS bulan) y
        WHERE
            a.i_company = '$this->i_company'
            AND a.f_nota_cancel = 'f'            
            AND to_char(a.d_nota, 'YYYY') = '$year'
            AND b.f_pareto = 't'
             $area
             )
        SELECT
            y.e_customer_name,
            jsonb_agg(y.bulan order by y.bulan) AS bulan,
            jsonb_agg(COALESCE(x.v_nota_netto, 0) order by y.bulan) AS v_nota_netto
        FROM
            (
            SELECT
                a.i_customer,
                b.e_customer_name,
                to_char(d_nota, 'MM') AS bulan,
                sum(v_nota_netto) AS v_nota_netto
            FROM
                tm_nota a
            INNER JOIN tr_customer b ON (b.i_customer = a.i_customer)
            WHERE
                f_nota_cancel = 'f'
                AND a.i_company = '$this->i_company'
                AND to_char(a.d_nota, 'YYYY') = '$year'
            AND b.f_pareto = 't'
                 $area
            GROUP BY
                1,
                2,
                3) AS x
        RIGHT JOIN raya y ON
            (x.i_customer = y.i_customer
                AND x.bulan = y.bulan)
        GROUP BY
            1
        order by 1 
        ", FALSE);
    }
}

/* End of file Mmaster.php */
