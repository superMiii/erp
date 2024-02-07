<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Minfostokonhand extends CI_Model
{

    /** List Datatable */
    public function serverside()
    {

        $i_store = $this->input->post('i_store', TRUE);
        if ($i_store == '') {
            $i_store = $this->uri->segment(3);
        }

        if ($i_store != '') {
            $store = "AND a.i_store = '$i_store' ";
        } else {
            $store = "";
        }


        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("SELECT distinct
                a.i_company,
                b.i_product_id ,
                b.e_product_name ,
                c.e_product_motifname ,
                d.e_product_gradename ,
                a.n_quantity_stock,
                coalesce(f.v_price,0) as v_price,
                (a.n_quantity_stock * f.v_price)::money as tot,
                case when b.f_product_active = 't' then 'AKTIF' else 'TDAK AKTIF' end as akt
            from
                tm_ic a
            inner join tr_product b on (b.i_product = a.i_product)
            inner join tr_product_motif c on (c.i_product_motif = a.i_product_motif)
            inner join tr_product_grade d on (d.i_product_grade = a.i_product_grade)
            inner join tr_store e on (e.i_store = a.i_store)
            inner join tr_customer_price f on (f.i_product=a.i_product)
            inner join tr_price_group g on (g.i_price_group=f.i_price_group and g.i_price_groupid ='H3')
            where
                a.i_company = '$this->i_company'
                AND a.i_store = '$i_store'
            order by
                3 asc
        ", FALSE);
        $datatables->hide('v_price');
        return $datatables->generate();
    }

    /** Get Area */
    public function get_store($cari)
    {
        $f_pusat = $this->session->f_pusat;
        return $this->db->query("SELECT 
        DISTINCT
        a.i_store, 
        i_store_id, 
        initcap(e_store_name) AS e_store_name
    FROM 
        tr_store a
    INNER JOIN tr_area b 
        ON (b.i_store = a.i_store) 
    INNER JOIN tm_user_area c 
        ON (b.i_area = c.i_area) 
    inner join tm_user d on (d.i_user=c.i_user)
    WHERE 
        (e_store_name ILIKE '%$cari%' OR i_store_id ILIKE '%$cari%')
        AND a.i_company = '$this->i_company' 
        AND f_store_active = true
        AND c.i_user = '$this->i_user' 
        And case when '$f_pusat' = 'f' then a.f_store_pusat ='f' else a.f_store_active = 't' end
    ORDER BY 3 ASC
        ", FALSE);
    }

    /** Get Data Untuk Edit */
    public function get_data($i_store)
    {
        if ($i_store != 'NA') {
            $store = "AND a.i_store = '$i_store' ";
        } else {
            $store = "";
        }
        return $this->db->query("SELECT
                a.i_company,
                b.i_product_id ,
                b.e_product_name ,
                c.e_product_motifname ,
                d.e_product_gradename ,
                a.n_quantity_stock,
                coalesce(f.v_price,0) as v_price,
                (a.n_quantity_stock * f.v_price) as tot,
                case when b.f_product_active = 't' then 'AKTIF' else 'TDAK AKTIF' end as akt
            from
                tm_ic a
            inner join tr_product b on (b.i_product = a.i_product)
            inner join tr_product_motif c on (c.i_product_motif = a.i_product_motif)
            inner join tr_product_grade d on (d.i_product_grade = a.i_product_grade)
            inner join tr_store e on (e.i_store = a.i_store)
            inner join tr_customer_price f on (f.i_product=a.i_product)
            inner join tr_price_group g on (g.i_price_group=f.i_price_group and g.i_price_groupid ='H3')
            where
                a.i_company = '$this->i_company'
                $store
            order by
                3 asc
        ", FALSE);
    }
}

/* End of file Mmaster.php */
