<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Minfopemenuhanspb extends CI_Model
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


        $pro = $this->input->post('pro', TRUE);
        if ($pro == '') {
            $pro = $this->uri->segment(7);
        }

        if ($pro == '3') {
            $epro = "AND a.i_promo notnull";
        } elseif ($pro == '2') {
            $epro = "AND a.i_promo isnull";
        } else {
            $epro = "";
        }

        $i_salesman = $this->input->post('i_salesman', TRUE);
        if ($i_salesman == '') {
            $i_salesman = $this->uri->segment(8);
        }

        if ($i_salesman != 'ALL') {
            $salesman = "AND a.i_salesman = '$i_salesman' ";
        } else {
            $salesman = "";
        }
        

        $i_product = $this->input->post('i_product', TRUE);
        if ($i_product == '') {
            $i_product = $this->uri->segment(9);
        }

        if ($i_product != 'NA') {
            $prod = "AND i.i_product = '$i_product' ";
        } else {
            $prod = "";
        }

        $miu = $this->input->post('miu', TRUE);
        if ($miu == '') {
            $miu = $this->uri->segment(10);
        }

        if ($miu == '3') {
            $miui = "AND a.f_so_stockdaerah = 't'";
        } elseif ($miu == '2') {
            $miui = "AND a.f_so_stockdaerah = 'f'";
        } else {
            $miui = "";
        }

        $dfrom  = date('Y-m-d', strtotime($dfrom));
        $dto    = date('Y-m-d', strtotime($dto));

        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("SELECT
                DISTINCT 
                a.i_so,
                b.i_area_id || ' - ' || initcap(b.e_area_name) AS e_area_name,
                initcap(e.e_city_name) AS e_city_name,
                initcap(b.e_area_island) AS e_area_island,
                case when a.f_so_stockdaerah='t' then 'CABANG' else 'PUSAT' end as pinuh,
                a.i_so_id,
                to_char(d_so, 'FMMonth') AS bulan,
                to_char(d_so, 'YYYY') AS tahun,
                a.d_so,
                c.i_customer_id  || ' - ' || initcap(c.e_customer_name) AS e_customer_name,
                initcap(d.e_customer_typename) AS e_customer_typename,
                f.i_salesman_id || ' - ' || initcap(f.e_salesman_name) AS e_salesman_name,
                g.e_company_name,
                i.i_product_id,
                i.e_product_name,
                k.e_product_subcategoryname,
                j.e_product_categoryname,
                h.n_order,
	            coalesce (m.n_deliver, 0) as n_sj,
                COALESCE (n.n_deliver, 0) AS n_deliver,
                h.v_unit_price::money AS v_unit_price,
                (h.w1 + h.w2 + h.w3 + h.w4)::money AS v_discount,
                o.i_nota_id,
                o.d_nota,
	            zz.i_promo_id || ' - ' || zz.e_promo_name as pro
            FROM
                tm_so a
            INNER JOIN tr_area b ON
                (b.i_area = a.i_area)
            INNER JOIN tr_customer c ON
                (c.i_customer = a.i_customer)
            INNER JOIN tr_customer_type d ON
                (d.i_customer_type = c.i_customer_type)
            INNER JOIN tr_city e ON
                (e.i_city = c.i_city)
            INNER JOIN tr_salesman f ON
                (f.i_salesman = a.i_salesman)
            INNER JOIN tr_company g ON
                (g.i_company = a.i_company)
            inner join (select
                i_so,
                n_order,
                i_product,
                v_unit_price,
                case when n_order > 0 then n_so_discount1 else 0 end as n_so_discount1,
                case when n_order > 0 then n_so_discount2 else 0 end as n_so_discount2,
                case when n_order > 0 then round(v1 / n_order,2) else 0 end as w1,
                case when n_order > 0 then round(v2 / n_order,2) else 0 end as w2,
                case when n_order > 0 then round(v3 / n_order,2) else 0 end as w3,
                case when n_order > 0 then round(v4 / n_order,2) else 0 end as w4
            from (
            select
                i_so ,
                n_order,
                i_product,
                v_unit_price,
                n_so_discount1,
                n_so_discount2,
                case when n_order =0 then 0 else v_so_discount1 end as v1,
                case when n_order =0 then 0 else v_so_discount2 end as v2,
                case when n_order =0 then 0 else v_so_discount3 end as v3,
                case when n_order =0 then 0 else v_so_discount4 end as v4
            from
                tm_so_item ) as mr) h on (h.i_so = a.i_so)
            INNER JOIN tr_product i ON
                (i.i_product = h.i_product)
            INNER JOIN tr_product_category j ON
                (j.i_product_category = i.i_product_category)
            INNER JOIN tr_product_subcategory k ON
                (k.i_product_subcategory = i.i_product_subcategory)
            LEFT JOIN tm_do l ON
                (l.i_so = a.i_so AND l.f_do_cancel = 'f')
            LEFT JOIN tm_do_item m ON
                (m.i_do = l.i_do
                    AND h.i_product = m.i_product)
            LEFT JOIN tm_nota_item n ON
                (n.i_do = m.i_do
                    AND m.i_product = n.i_product)
            LEFT JOIN tm_nota o ON
                (o.i_nota = n.i_nota)
            inner join tm_user_area u on
                (u.i_area = b.i_area and u.i_user = '$this->i_user')
            left join tm_promo zz on (zz.i_promo=a.i_promo)
            WHERE
                a.f_so_cancel = 'f'
                AND a.i_company = '$this->i_company'
                AND a.d_so BETWEEN '$dfrom' AND '$dto'
                $area
                $customer
                $epro
                $salesman
                $prod
                $miui
            ORDER BY
                a.d_so ASC
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

    /** Get Customer */
    public function get_salesman($cari, $i_area)
    {
        if ($i_area != 'NA') {
            $area = "AND d.i_area = '$i_area' ";
        } else {
            $area = "";
        }
        return $this->db->query("SELECT distinct
                a.i_salesman ,
	            e_salesman_name
            FROM 
                tr_salesman a 
                inner join tr_salesman_areacover b on (b.i_salesman=a.i_salesman)
                inner join tr_area_cover_item c on (c.i_area_cover=b.i_area_cover)
                inner join tr_area d on (d.i_area=c.i_area)
                INNER JOIN tm_user_area e ON (e.i_area = d.i_area)
            WHERE 
                (e_salesman_name ILIKE '%$cari%')
                AND a.i_company = '$this->i_company' 
                AND a.f_salesman_active = 'true' 
                AND e.i_user = '$this->i_user' 
                $area
            ORDER BY 2 ASC
        ", FALSE);
    }

    public function get_prod($cari)
    {
        return $this->db->query("SELECT 
                i_product, 
                i_product_id, 
                e_product_name
            FROM 
                tr_product
            WHERE 
                (e_product_name ILIKE '%$cari%' OR i_product_id ILIKE '%$cari%')
                AND i_company = '$this->i_company' 
                AND f_product_active = true
            ORDER BY 3 ASC
        ", FALSE);
    }

    /** Get Data Untuk Edit */
    public function get_data($dfrom, $dto, $i_area, $i_customer, $pro, $i_salesman, $i_product, $miu)
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

        if ($pro == '3') {
            $epro = "AND a.i_promo notnull";
        } elseif ($pro == '2') {
            $epro = "AND a.i_promo isnull";
        } else {
            $epro = "";
        }        

        if ($i_salesman != 'ALL') {
            $salesman = "AND a.i_salesman = '$i_salesman' ";
        } else {
            $salesman = "";
        }

        if ($i_product != 'NA') {
            $prod = "AND i.i_product = '$i_product' ";
        } else {
            $prod = "";
        }

        if ($miu == '3') {
            $miui = "AND a.f_so_stockdaerah = 't'";
        } elseif ($miu == '2') {
            $miui = "AND a.f_so_stockdaerah = 'f'";
        } else {
            $miui = "";
        }

        $dfrom  = date('Y-m-d', strtotime($dfrom));
        $dto    = date('Y-m-d', strtotime($dto));
        return $this->db->query("SELECT
                DISTINCT 
                a.i_so,
                b.i_area_id || ' - ' || initcap(b.e_area_name) AS e_area_name,
                initcap(b.e_area_island) AS e_area_island,
                case when a.f_so_stockdaerah='t' then 'CABANG' else 'PUSAT' end as pinuh,
                a.i_so_id,
                to_char(d_so, 'FMMonth') AS bulan,
                to_char(d_so, 'YYYY') AS tahun,
                a.d_so,
                c.i_customer_id  || ' - ' || initcap(c.e_customer_name) AS e_customer_name,
                initcap(d.e_customer_typename) AS e_customer_typename,
                initcap(e.e_city_name) AS e_city_name,
                f.i_salesman_id || ' - ' || initcap(f.e_salesman_name) AS e_salesman_name,
                g.e_company_name,
                i.i_product_id,
                i.e_product_name,
                k.e_product_subcategoryname,
                j.e_product_categoryname,
                h.n_order,
	            coalesce (m.n_deliver, 0) as n_sj,
                COALESCE (n.n_deliver, 0) AS n_deliver,
                h.v_unit_price,
                (h.w1 + h.w2 + h.w3 + h.w4) AS v_discount,
                o.i_nota_id,
                o.d_nota,
	            zz.i_promo_id || ' - ' || zz.e_promo_name as pro
            FROM
                tm_so a
            INNER JOIN tr_area b ON
                (b.i_area = a.i_area)
            INNER JOIN tr_customer c ON
                (c.i_customer = a.i_customer)
            INNER JOIN tr_customer_type d ON
                (d.i_customer_type = c.i_customer_type)
            INNER JOIN tr_city e ON
                (e.i_city = c.i_city)
            INNER JOIN tr_salesman f ON
                (f.i_salesman = a.i_salesman)
            INNER JOIN tr_company g ON
                (g.i_company = a.i_company)
            inner join (select
                i_so,
                n_order,
                i_product,
                v_unit_price,
                case when n_order > 0 then n_so_discount1 else 0 end as n_so_discount1,
                case when n_order > 0 then n_so_discount2 else 0 end as n_so_discount2,
                case when n_order > 0 then round(v1 / n_order,2) else 0 end as w1,
                case when n_order > 0 then round(v2 / n_order,2) else 0 end as w2,
                case when n_order > 0 then round(v3 / n_order,2) else 0 end as w3,
                case when n_order > 0 then round(v4 / n_order,2) else 0 end as w4
            from (
            select
                i_so ,
                n_order,
                i_product,
                v_unit_price,
                n_so_discount1,
                n_so_discount2,
                case when n_order =0 then 0 else v_so_discount1 end as v1,
                case when n_order =0 then 0 else v_so_discount2 end as v2,
                case when n_order =0 then 0 else v_so_discount3 end as v3,
                case when n_order =0 then 0 else v_so_discount4 end as v4
            from
                tm_so_item ) as mr) h on (h.i_so = a.i_so)
            INNER JOIN tr_product i ON
                (i.i_product = h.i_product)
            INNER JOIN tr_product_category j ON
                (j.i_product_category = i.i_product_category)
            INNER JOIN tr_product_subcategory k ON
                (k.i_product_subcategory = i.i_product_subcategory)
            LEFT JOIN tm_do l ON
                (l.i_so = a.i_so AND l.f_do_cancel = 'f')
            LEFT JOIN tm_do_item m ON
                (m.i_do = l.i_do
                    AND h.i_product = m.i_product)
            LEFT JOIN tm_nota_item n ON
                (n.i_do = m.i_do
                    AND m.i_product = n.i_product)
            LEFT JOIN tm_nota o ON
                (o.i_nota = n.i_nota)
            left join tm_promo zz on (zz.i_promo=a.i_promo)
            WHERE
                a.f_so_cancel = 'f'
                AND a.i_company = '$this->i_company'
                AND a.d_so BETWEEN '$dfrom' AND '$dto'
                $area
                $customer
                $epro
                $salesman
                $prod
                $miui
            ORDER BY
                d_so ASC
        ", FALSE);
    }
}

/* End of file Mmaster.php */
