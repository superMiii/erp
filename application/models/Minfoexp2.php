<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Minfoexp2 extends CI_Model
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

        $i_store = $this->input->post('i_store', TRUE);
        if ($i_store == '') {
            $i_store = $this->uri->segment(6);
        }

        if ($i_store != 'ALL') {
            $store = "AND c.i_store = '$i_store' ";
        } else {
            $store = "";
        }

        $dfrom  = date('Y-m-d', strtotime($dfrom));
        $dto    = date('Y-m-d', strtotime($dto));

        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("SELECT
                a.i_do,
                a.d_do,
                c.e_store_name,
                a.i_do_id ,
                d.e_customer_name ,
                el.raya::money as tot,
                '' as a,
                d.e_customer_address ,
                e.e_city_name ,
                '' as b,
                '' as c,
                'Rp. ' as d,
                ' %' as e,
                '' as f,
                '' as g,
                '' as h,
                '' as i,	
                f.e_salesman_name ,
                '' as j
                from
                    tm_do a 
                inner join tm_so b on (a.i_so=b.i_so)
                inner join tr_store c on (c.i_store=b.i_store)
                inner join tr_customer d on (d.i_customer=a.i_customer)
                inner join tr_city e on (e.i_city=d.i_city)
                inner join tr_salesman f on (f.i_salesman=a.i_salesman)
                inner join (select i_do,
                                (raya-(d1+d2+d3+d4))+(n_so_ppn/100)*(raya-(d1+d2+d3+d4)) as raya
                            from (
                            select i_do,
                                rr.i_so,
                                raya,
                                raya*(n_so_discount1/100) as d1,
                                (raya- (raya * (n_so_discount1/100)) )*(n_so_discount2/100) as d2,
                                (raya- (raya*(n_so_discount1/100)) - ((raya- (raya * (n_so_discount1/100)) )*(n_so_discount2/100)))*(n_so_discount3/100) as d3,
                                (raya- (raya*(n_so_discount1/100)) - (raya- (raya * (n_so_discount1/100)) )*(n_so_discount2/100) - (raya- (raya*(n_so_discount1/100)) - ((raya- (raya * (n_so_discount1/100)) )*(n_so_discount2/100)))*(n_so_discount3/100)) * (n_so_discount4/100) as d4
                            from (
                            select
                                    i_do,r.i_so,sum(tot) as raya
                            from(
                                select
                                        a.i_do,c.i_so,b.n_deliver * c.v_unit_price as tot
                                from
                                        tm_do a
                                inner join tm_do_item b on (b.i_do = a.i_do)
                                inner join tm_so_item c on (c.i_so = a.i_so and c.i_product = b.i_product)
                                    ) as r group by	1,2) as rr
                            inner join (select distinct i_so,n_so_discount1,n_so_discount2,n_so_discount3,n_so_discount4 from tm_so_item) as ss on (ss.i_so=rr.i_so)
                            ) as yy
                            inner join (select distinct i_so,n_so_ppn from tm_so) as se on (se.i_so=yy.i_so)
                            ) el on (el.i_do=a.i_do)
            inner join tm_user_area u on (u.i_area = a.i_area and u.i_user = '$this->i_user')
            WHERE
                a.f_do_cancel = 'f'
                AND a.i_company = '$this->i_company'
                AND a.d_do BETWEEN '$dfrom' AND '$dto'
                $area
                $store
            ORDER BY
                1 asc
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

    /** Get store */
    public function get_store($cari, $i_area)
    {
        if ($i_area != 'NA') {
            $area = "AND i_area = '$i_area' ";
        } else {
            $area = "";
        }
        return $this->db->query("SELECT DISTINCT
                a.i_store, i_store_id , e_store_name
            FROM 
                tr_store a 
	        inner join tr_area b on (b.i_store=a.i_store)	
            WHERE 
                (e_store_name ILIKE '%$cari%' OR i_store_id ILIKE '%$cari%')
                AND a.i_company = '$this->i_company' 
                AND f_store_active = 'true' 
                $area
            ORDER BY 3 ASC
        ", FALSE);
    }

    /** Get Data Untuk Edit */
    public function get_data($dfrom, $dto, $i_area, $i_store)
    {
        if ($i_area != 'NA') {
            $area = "AND a.i_area = '$i_area' ";
        } else {
            $area = "";
        }
        if ($i_store != 'ALL') {
            $store = "AND c.i_store = '$i_store' ";
        } else {
            $store = "";
        }
        $dfrom  = date('Y-m-d', strtotime($dfrom));
        $dto    = date('Y-m-d', strtotime($dto));
        return $this->db->query("SELECT
                a.i_do,
                a.d_do,
                c.e_store_name,
                a.i_do_id ,
                d.e_customer_name ,
                el.raya as tot,
                '' as a,
                d.e_customer_address ,
                e.e_city_name ,
                '' as b,
                '' as c,
                'Rp. ' as d,
                ' %' as e,
                '' as f,
                '' as g,
                '' as h,
                '' as i,	
                f.e_salesman_name ,
                '' as j
                from
                    tm_do a 
                inner join tm_so b on (a.i_so=b.i_so)
                inner join tr_store c on (c.i_store=b.i_store)
                inner join tr_customer d on (d.i_customer=a.i_customer)
                inner join tr_city e on (e.i_city=d.i_city)
                inner join tr_salesman f on (f.i_salesman=a.i_salesman)
                inner join (select i_do,
                                (raya-(d1+d2+d3+d4))+(n_so_ppn/100)*(raya-(d1+d2+d3+d4)) as raya
                            from (
                            select i_do,
                                rr.i_so,
                                raya,
                                raya*(n_so_discount1/100) as d1,
                                (raya- (raya * (n_so_discount1/100)) )*(n_so_discount2/100) as d2,
                                (raya- (raya*(n_so_discount1/100)) - ((raya- (raya * (n_so_discount1/100)) )*(n_so_discount2/100)))*(n_so_discount3/100) as d3,
                                (raya- (raya*(n_so_discount1/100)) - (raya- (raya * (n_so_discount1/100)) )*(n_so_discount2/100) - (raya- (raya*(n_so_discount1/100)) - ((raya- (raya * (n_so_discount1/100)) )*(n_so_discount2/100)))*(n_so_discount3/100)) * (n_so_discount4/100) as d4
                            from (
                            select
                                    i_do,r.i_so,sum(tot) as raya
                            from(
                                select
                                        a.i_do,c.i_so,b.n_deliver * c.v_unit_price as tot
                                from
                                        tm_do a
                                inner join tm_do_item b on (b.i_do = a.i_do)
                                inner join tm_so_item c on (c.i_so = a.i_so and c.i_product = b.i_product)
                                    ) as r group by	1,2) as rr
                            inner join (select distinct i_so,n_so_discount1,n_so_discount2,n_so_discount3,n_so_discount4 from tm_so_item) as ss on (ss.i_so=rr.i_so)
                            ) as yy
                            inner join (select distinct i_so,n_so_ppn from tm_so) as se on (se.i_so=yy.i_so)
                            ) el on (el.i_do=a.i_do)
            inner join tm_user_area u on (u.i_area = a.i_area and u.i_user = '$this->i_user')
            WHERE
                a.f_do_cancel = 'f'
                AND a.i_company = '$this->i_company'
                AND a.d_do BETWEEN '$dfrom' AND '$dto'
                $area
                $store
            ORDER BY
                1 asc
        ", FALSE);
    }
}

/* End of file Mmaster.php */
