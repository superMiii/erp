<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Minfopertokospb2 extends CI_Model
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

        return $this->db->query("WITH raya as (
            select
                distinct a.i_customer,
	            b.i_customer_id || '|' || b.e_customer_name || '|' || c.e_city_name || '|' || rr.e_area_name as e_customer_name,
                y.bulan
            from
                tm_so a
            inner join tr_customer b on
                (a.i_customer = b.i_customer)
            inner join tr_city c on
                (c.i_city = b.i_city)
            inner join tr_area rr on (rr.i_area=b.i_area)
            inner join tm_user_area u on (u.i_area = rr.i_area and u.i_user = '$this->i_user')
            cross join (
                select
                    to_char(DATE '1001-01-01' + (interval '1' month * generate_series(0, 11)), 'MM') as bulan) y
            where
                a.i_company = '$this->i_company'
                and a.f_so_cancel = 'f'
                and to_char(a.d_so, 'YYYY') = '$year'
                AND b.f_pareto = 't'
                        $area
                        )
                        select
                y.e_customer_name,
                jsonb_agg(y.bulan order by y.bulan) as bulan,
                jsonb_agg(coalesce(x.v_so, 0) order by y.bulan) as v_nota_netto
            from
                (
                select
                    a.i_customer,
                    b.e_customer_name,
                    to_char(d_so, 'MM') as bulan,
                    sum(v_so) as v_so
                from
                    tm_so a
                inner join tr_customer b on
                    (b.i_customer = a.i_customer)
                where
                    f_so_cancel = 'f'
                    and a.i_company = '$this->i_company'
                    and to_char(a.d_so, 'YYYY') = '$year'
                    AND b.f_pareto = 't'
                            $area
                group by
                    1,
                    2,
                    3) as x
            right join raya y on
                (x.i_customer = y.i_customer
                    and x.bulan = y.bulan)
            group by
                1
            order by
                1
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

        return $this->db->query("WITH raya as (
            select
                distinct a.i_customer,
	            b.i_customer_id || '|' || b.e_customer_name || '|' || c.e_city_name || '|' || rr.e_area_name as e_customer_name,
                y.bulan
            from
                tm_so a
            inner join tr_customer b on
                (a.i_customer = b.i_customer)
            inner join tr_city c on
                (c.i_city = b.i_city)
            inner join tr_area rr on (rr.i_area=b.i_area)
            inner join tm_user_area u on (u.i_area = rr.i_area and u.i_user = '$this->i_user')
            cross join (
                select
                    to_char(DATE '1001-01-01' + (interval '1' month * generate_series(0, 11)), 'MM') as bulan) y
            where
                a.i_company = '$this->i_company'
                and a.f_so_cancel = 'f'
                and to_char(a.d_so, 'YYYY') = '$year'
                AND b.f_pareto = 't'
                        $area
                        )
                        select
                y.e_customer_name,
                jsonb_agg(y.bulan order by y.bulan) as bulan,
                jsonb_agg(coalesce(x.v_so, 0) order by y.bulan) as v_nota_netto
            from
                (
                select
                    a.i_customer,
                    b.e_customer_name,
                    to_char(d_so, 'MM') as bulan,
                    sum(v_so) as v_so
                from
                    tm_so a
                inner join tr_customer b on
                    (b.i_customer = a.i_customer)
                where
                    f_so_cancel = 'f'
                    and a.i_company = '$this->i_company'
                    and to_char(a.d_so, 'YYYY') = '$year'
                    AND b.f_pareto = 't'
                            $area
                group by
                    1,
                    2,
                    3) as x
            right join raya y on
                (x.i_customer = y.i_customer
                    and x.bulan = y.bulan)
            group by
                1
            order by
                1
        ", FALSE);
    }
}

/* End of file Mmaster.php */
