<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Minfospbcus2 extends CI_Model
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
            $year = $this->uri->segment(3);
        }
        if ($year != '0' && $year != null) {
            $year = $year;
        } else {
            $year = date('Y');
        }

        $i_area = $this->input->post('i_area', TRUE);
        if ($i_area == '') {
            $i_area = $this->uri->segment(4);
        }

        if ($i_area != '0' && $i_area != null) {
            $area = "AND j.i_area = '$i_area' ";
        } else {
            $area = "";
        }

        $i_customer = $this->input->post('i_customer', TRUE);
        if ($i_customer == '') {
            $i_customer = $this->uri->segment(5);
        }

        if ($i_customer != '0' && $i_customer != null) {
            $customer = "AND k.i_customer = '$i_customer' ";
        } else {
            $customer = "";
        }

        return $this->db->query("SELECT 
            j.i_area_id || ' - ' || j.e_area_name as an,
            k.i_customer_id || ' - ' || k.e_customer_name as cn,
            l.i_product_id as mr,
            l.e_product_name as pn,
	        latest_v_unit_price::money as latest_v_unit_price,
            mdo.bulan as bulan,
            mdo.n_deliver as n_deliver
        from (
                WITH cte AS ( SELECT DISTINCT 
                        b.i_area, a.i_product, b.i_customer, y.bulan
                    FROM tm_nota_item a
                    INNER JOIN tm_nota b ON (a.i_nota = b.i_nota)
                    cross join ( select to_char(DATE '1001-01-01' + (interval '1' month * generate_series(0, 11)), 'MM') as bulan) y
                    WHERE
                        b.i_company = '$this->i_company'
                        AND b.f_nota_cancel = 'f'
                        AND to_char(b.d_nota, 'YYYY') = '$year'
                            )
                    select
                        y.i_area,
                        y.i_product,
                        y.i_customer,
                        jsonb_agg(y.bulan order by y.bulan) AS bulan,
                        jsonb_agg(COALESCE(x.n_deliver, 0)order by y.bulan) AS n_deliver
                    FROM
                        (
                        select b.i_area, a.i_product, b.i_customer, to_char(b.d_nota ,'mm') as bulan, sum(a.n_deliver) AS n_deliver
                        FROM tm_nota_item a
                        INNER JOIN tm_nota b ON (a.i_nota = b.i_nota)
                        where
                            b.i_company = '$this->i_company'
                            AND b.f_nota_cancel = 'f'
                            AND to_char(b.d_nota, 'YYYY') = '$year'
                        GROUP BY 1,2,3,4
                        ) AS x
                    RIGHT JOIN cte y ON
                        (x.i_area=y.i_area and x.i_product = y.i_product and x.i_customer =y.i_customer  AND x.bulan = y.bulan)
                    GROUP by 1,2,3
                    ) as mdo
                inner join tr_area j on (j.i_area=mdo.i_area)
                inner join tr_customer k on (k.i_customer=mdo.i_customer)
                inner join tr_product l on (l.i_product=mdo.i_product)
                inner join tm_user_area uu on (uu.i_area = j.i_area and uu.i_user = '$this->i_user')
                left join (SELECT 
                    kr.i_customer ,
                    yz.i_product ,
                    yz.v_unit_price as latest_v_unit_price
                from
                    tm_nota kr
                inner join tm_nota_item yz on
                    (kr.i_nota = yz.i_nota)	
                where (kr.i_customer ,yz.i_product, yz.d_do) in (select
                    kr.i_customer , yz.i_product ,
                    max(yz.d_do) as d_do 
                from
                    tm_nota kr
                inner join tm_nota_item yz on
                    (kr.i_nota = yz.i_nota)	group by 1,2) 
                ) as miu on (miu.i_customer = mdo.i_customer and miu.i_product = mdo.i_product)
                where
                    k.i_company = '$this->i_company'
                    $area
                    $customer     
                order by j.i_area, k.e_customer_name, l.e_product_name     
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


    public function serverside2($year, $i_area, $i_customer)
    // public function serverside()
    {
        if ($year != '0' && $year != null) {
            $year = $year;
        } else {
            $year = date('Y');
        }


        if ($i_area != '0' && $i_area != null) {
            $area = "AND j.i_area = '$i_area' ";
        } else {
            $area = "";
        }

        if ($i_customer != '0' && $i_customer != null) {
            $customer = "AND k.i_customer = '$i_customer' ";
        } else {
            $customer = "";
        }

        return $this->db->query("SELECT 
                j.i_area_id || ' - ' || j.e_area_name as an,
                k.i_customer_id || ' - ' || k.e_customer_name as cn,
                l.i_product_id as mr,
                l.e_product_name as pn,
	            latest_v_unit_price as latest_v_unit_price,
                mdo.bulan as bulan,
                mdo.n_deliver as n_deliver
            from (
                    WITH cte AS ( SELECT DISTINCT 
                            b.i_area, a.i_product, b.i_customer, y.bulan
                        FROM tm_nota_item a
                        INNER JOIN tm_nota b ON (a.i_nota = b.i_nota)
                        cross join ( select to_char(DATE '1001-01-01' + (interval '1' month * generate_series(0, 11)), 'MM') as bulan) y
                        WHERE
                            b.i_company = '$this->i_company'
                            AND b.f_nota_cancel = 'f'
                            AND to_char(b.d_nota, 'YYYY') = '$year'
                                )
                        select
                            y.i_area,
                            y.i_product,
                            y.i_customer,
                            jsonb_agg(y.bulan order by y.bulan) AS bulan,
                            jsonb_agg(COALESCE(x.n_deliver, 0)order by y.bulan) AS n_deliver
                        FROM
                            (
                            select b.i_area, a.i_product, b.i_customer, to_char(b.d_nota ,'mm') as bulan, sum(a.n_deliver) AS n_deliver
                            FROM tm_nota_item a
                            INNER JOIN tm_nota b ON (a.i_nota = b.i_nota)
                            where
                                b.i_company = '$this->i_company'
                                AND b.f_nota_cancel = 'f'
                                AND to_char(b.d_nota, 'YYYY') = '$year'
                            GROUP BY 1,2,3,4
                            ) AS x
                        RIGHT JOIN cte y ON
                            (x.i_area=y.i_area and x.i_product = y.i_product and x.i_customer =y.i_customer  AND x.bulan = y.bulan)
                        GROUP by 1,2,3
                        ) as mdo
                    inner join tr_area j on (j.i_area=mdo.i_area)
                    inner join tr_customer k on (k.i_customer=mdo.i_customer)
                    inner join tr_product l on (l.i_product=mdo.i_product)
                inner join tm_user_area uu on (uu.i_area = j.i_area and uu.i_user = '$this->i_user')
                left join (SELECT 
                    kr.i_customer ,
                    yz.i_product ,
                    yz.v_unit_price as latest_v_unit_price
                from
                    tm_nota kr
                inner join tm_nota_item yz on
                    (kr.i_nota = yz.i_nota)	
                where (kr.i_customer ,yz.i_product, yz.d_do) in (select
                    kr.i_customer , yz.i_product ,
                    max(yz.d_do) as d_do 
                from
                    tm_nota kr
                inner join tm_nota_item yz on
                    (kr.i_nota = yz.i_nota)	group by 1,2) 
                ) as miu on (miu.i_customer = mdo.i_customer and miu.i_product = mdo.i_product)
                    where
                        k.i_company = '$this->i_company'
                        $area
                        $customer     
                    order by j.i_area, k.e_customer_name, l.e_product_name     
        ", FALSE);
    }
}

/* End of file Mmaster.php */
