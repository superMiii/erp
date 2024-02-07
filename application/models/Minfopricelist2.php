<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Minfopricelist2 extends CI_Model
{

    public function get_group()
    {
        return $this->db->query("SELECT * FROM tr_price_group WHERE f_price_groupactive = 't' AND i_company = '$this->i_company' ORDER BY i_price_group ASC ", FALSE);
    }

    /** List Datatable */
    public function serverside()
    {
        return $this->db->query("SELECT
        distinct 
            y.i_product_id,
            y.e_product_name,
            y.e_price_groupname,
            y.v_price,
            round( r.v_price) as v_price2,
            za.e_product_groupname ,
            zb.e_product_categoryname,
            zc.e_product_subcategoryname,
            z.d_product_entry as d_product_entry,
            w.e_product_gradename,
            case
                when z.f_product_active = 'TRUE' then 'Aktif'
                else 'Tidak Aktif'
            end as f_product_active,
            zd.e_product_statusname ,
            rr.i_supplier_id ,
            rr.e_supplier_name ,
            w.n_quantity_stock,
            rz.e_supplier_groupname
        from
            (
            select
                i_product_id,
                e_product_name,
                jsonb_agg(e_price_groupname order by i_price_group) as e_price_groupname,
                jsonb_agg(v_price order by i_price_group) as v_price
            from
                (
                select
                    c.i_product_id,
                    a.i_price_group,
                    c.e_product_name,
                    b.e_price_groupname,
                    coalesce (v_price,
                    0) as v_price
                from
                    tr_customer_price a
                inner join tr_price_group b on
                    (b.i_price_group = a.i_price_group
                        and a.i_company = b.i_company)
                right join tr_product c on
                    (c.i_product = a.i_product
                        and a.i_company = c.i_company)
                where
                    a.i_company = '$this->i_company'
                order by
                    1,
                    2
                        ) as x
            group by
                1,
                2
            order by
                1) as y
        inner join tr_product z on
            (z.i_product_id = y.i_product_id)
        left join tr_supplier_price r on
            (r.i_product = z.i_product and f_sup_aktif='t')
        inner join tr_product_group za on
            (za.i_product_group = z.i_product_group)
        inner join tr_product_category zb on
            (zb.i_product_category = z.i_product_category)
        inner join tr_product_subcategory zc on
            (zc.i_product_subcategory = z.i_product_subcategory)
        inner join tr_product_status zd on
            (zd.i_product_status = z.i_product_status)
        left join tr_supplier rr on
            (rr.i_supplier = r.i_supplier)
        left join tr_supplier_group rz on
            (rz.i_supplier_group = rr.i_supplier_group)
        inner join (
            select
                i_product,
                i.e_product_gradename,
                j.f_store_pusat,
                n_quantity_stock
            from
                tm_ic u
            inner join tr_product_grade i on
                (u.i_product_grade = i.i_product_grade)
            inner join tr_store j on
                (j.i_store = u.i_store)
            where
                j.f_store_pusat = 't') as w on
            (w.i_product = z.i_product)   
        where
            z.i_company = '$this->i_company'
        ORDER BY 2
        ", FALSE);
    }
}

/* End of file Mmaster.php */
