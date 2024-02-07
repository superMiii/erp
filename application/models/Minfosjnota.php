<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Minfosjnota extends CI_Model
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
            $area = "AND w.i_area = '$i_area' ";
        } else {
            $area = "";
        }

        $i_customer = $this->input->post('i_customer', TRUE);
        if ($i_customer == '') {
            $i_customer = $this->uri->segment(6);
        }

        if ($i_customer != 'ALL') {
            $customer = "AND w.i_customer = '$i_customer' ";
        } else {
            $customer = "";
        }

        $dfrom  = date('Y-m-d', strtotime($dfrom));
        $dto    = date('Y-m-d', strtotime($dto));

        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("SELECT
                distinct w.i_company,
                d.i_area_id || ' - ' || initcap(d.e_area_name) as e_area_name,
                c.i_customer_id || ' - ' || c.e_customer_name as customer,
                w.i_do_id ,
                w.d_do ,
                round((v_total - (v_so_discounttotal + v_diskon1 + v_diskon2 + v_diskon3)) * ((x.n_so_ppn + 100)/ 100))::money as v_do,
                a.i_nota_id,
                a.d_nota,
                a.v_nota_gross::money as v_nota_gross,
                a.v_nota_ppn::money as v_nota_ppn ,
                a.v_nota_discount::money as v_nota_discount ,
                a.v_nota_netto::money as v_nota_netto ,
                a.v_sisa::money as v_sisa
            from
                tm_do w
            left join tm_nota_item b on
                (b.i_do = w.i_do)
            left join tm_nota a on
                (a.i_nota = b.i_nota)
            inner join tr_customer c on
                (c.i_customer = w.i_customer)
            inner join tr_area d on
                (d.i_area = w.i_area)
            inner join tm_user_area u on
                (u.i_area = d.i_area
                    and u.i_user = '1')
            inner join (
                select
                    a.i_company,
                    a.i_do,
                    b.n_so_ppn,
                    b.v_so_discounttotal,
                    sum(d.n_deliver * e.v_unit_price) as v_total,
                    sum(d.n_deliver * e.v_unit_price * (e.n_so_discount1 / 100)) as v_diskon1,
                    sum((d.n_deliver * e.v_unit_price - d.n_deliver * e.v_unit_price * (e.n_so_discount1 / 100))*(e.n_so_discount2 / 100)) as v_diskon2,
                    sum(((d.n_deliver * e.v_unit_price)-(d.n_deliver * e.v_unit_price * (e.n_so_discount1 / 100))-((d.n_deliver * e.v_unit_price - d.n_deliver * e.v_unit_price * (e.n_so_discount1 / 100))*(e.n_so_discount2 / 100))) * (e.n_so_discount3 / 100)) as v_diskon3
                from
                    tm_do a
                inner join tm_so b on
                    (b.i_so = a.i_so)
                inner join tm_do_item d on
                    (d.i_do = a.i_do)
                inner join tm_so_item e on
                    (e.i_so = a.i_so
                        and d.i_product = e.i_product)
                inner join tm_user_area u on
                    (u.i_area = b.i_area
                        and u.i_user = '$this->i_user')
                group by
                    1,
                    2,
                    3,
                    4) as x on
                (x.i_do = w.i_do)
            inner join tr_company co on
                (co.i_company = x.i_company)
            WHERE
                w.f_do_cancel = 'f'
                AND w.i_company = '$this->i_company'
                AND w.d_do BETWEEN '$dfrom' AND '$dto'
                $area
                $customer
            ORDER BY
                4 ASC
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
            INNER JOIN tm_do w 
                ON (w.i_area = a.i_area) 
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
            $area = "AND a.i_area = '$i_area' ";
        } else {
            $area = "";
        }
        return $this->db->query("SELECT 
                a.i_customer, i_customer_id , e_customer_name
            FROM 
                tr_customer a
            INNER JOIN tm_do w 
                ON (w.i_customer = a.i_customer) 
            WHERE 
                (e_customer_name ILIKE '%$cari%' OR i_customer_id ILIKE '%$cari%')
                AND a.i_company = '$this->i_company' 
                AND a.f_customer_active = 'true' 
                $area
            ORDER BY 3 ASC
        ", FALSE);
    }

    /** Get Data Untuk Edit */
    public function get_data($dfrom, $dto, $i_area, $i_customer)
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
        $dfrom  = date('Y-m-d', strtotime($dfrom));
        $dto    = date('Y-m-d', strtotime($dto));
        return $this->db->query("SELECT
                DISTINCT 
                w.i_company,
                d.i_area_id || ' - ' || initcap(d.e_area_name) as e_area_name,
                c.i_customer_id || ' ] ' || c.e_customer_name AS customer,
                w.i_do_id ,
                w.d_do ,
                round((v_total - (v_so_discounttotal + v_diskon1 + v_diskon2 + v_diskon3)) * ((x.n_so_ppn+100)/100)) AS v_do,
                a.i_nota_id ,
                a.d_nota,
                a.v_nota_gross,
                a.v_nota_ppn ,
                a.v_nota_discount ,
                a.v_nota_netto ,
                a.v_sisa 
            from
                tm_do w
                left join tm_nota_item b on (b.i_do = w.i_do)
                left join tm_nota a on (a.i_nota=b.i_nota)
                inner join tr_customer c on (c.i_customer= w.i_customer)
                inner join tr_area d on (d.i_area = w.i_area)
            inner join (select
                    a.i_company,
                    a.i_do,
                    b.n_so_ppn,
                    b.v_so_discounttotal,
                    sum(d.n_deliver * e.v_unit_price) as v_total,
                    sum(d.n_deliver * e.v_unit_price * (e.n_so_discount1 / 100)) as v_diskon1,
                    sum((d.n_deliver * e.v_unit_price - d.n_deliver * e.v_unit_price * (e.n_so_discount1 / 100))*(e.n_so_discount2 / 100)) as v_diskon2,
                    sum(((d.n_deliver * e.v_unit_price)-(d.n_deliver * e.v_unit_price * (e.n_so_discount1 / 100))-((d.n_deliver * e.v_unit_price - d.n_deliver * e.v_unit_price * (e.n_so_discount1 / 100))*(e.n_so_discount2 / 100))) * (e.n_so_discount3 / 100)) as v_diskon3
            from
                    tm_do a
            inner join tm_so b on
                    (b.i_so = a.i_so)
            inner join tm_do_item d on
                    (d.i_do = a.i_do)
            inner join tm_so_item e on
                    (e.i_so = a.i_so
                    and d.i_product = e.i_product)
            group by
                    1,
                    2,
                    3,
                    4 ) as x on (x.i_do = w.i_do)
            inner join tr_company co on (co.i_company=x.i_company)
            WHERE
                w.f_do_cancel = 'f'
                AND w.i_company = '$this->i_company'
                AND w.d_do BETWEEN '$dfrom' AND '$dto'
                $area
                $customer
            ORDER BY
                4 ASC
        ", FALSE);
    }
}

/* End of file Mmaster.php */
