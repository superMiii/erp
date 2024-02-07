<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mviewstok extends CI_Model
{

    /** List Datatable */
    public function serverside($teangan)
    {
        return $this->db->query("SELECT
                a.n_quantity_stock as nstok,
                b.i_product_id as iproduct,
                b.e_product_name as ebr,
                c.e_product_motifname as imotif,
                d.e_product_gradename as igrade,
                e.e_store_name as igudang
            from
                tm_ic as a
            inner join
                    tr_product as b on
                a.i_product = b.i_product
            inner join 
                    tr_product_motif as c on
                a.i_product_motif = c.i_product_motif
            inner join
                    tr_product_grade as d on
                a.i_product_grade = d.i_product_grade
            inner join
                    tr_store as e on
                (a.i_store = e.i_store and e.f_store_active='t')
            where
                a.i_company = '$this->i_company'
                and e.f_store_active ='t'
                and (b.e_product_name ilike '%$teangan%'
                    or b.i_product_id ilike '%$teangan%')
            order by
                b.i_product_id asc
        ", FALSE);
    }
}

/* End of file Mmaster.php */
