<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Minfopricelist extends CI_Model
{

    public function get_group()
    {
        return $this->db->query("SELECT * FROM tr_price_group WHERE f_price_groupactive = 't' AND i_company = '$this->i_company' ORDER BY i_price_group ASC ", FALSE);
    }

    /** List Datatable */
    public function serverside()
    {
        return $this->db->query("SELECT
                i_product_id,
                e_product_name,
                e_product_statusname,
                jsonb_agg(e_price_groupname order by i_price_group) as e_price_groupname,
                jsonb_agg(v_price order by i_price_group) as v_price
            FROM (
                SELECT
                    /*a.i_product,*/
                case
                    when s.i_product_statusid = 'STP1' then c.i_product_id || ' (*STP)'
                    when s.i_product_statusid = 'STP2' then c.i_product_id || ' (#STP)'
                    else c.i_product_id
                end as i_product_id,
                    s.e_product_statusname,
                    a.i_price_group,
                    c.e_product_name,
                    b.e_price_groupname,
                    COALESCE (v_price, 0) AS v_price 
                FROM
                    tr_customer_price a
                INNER JOIN tr_price_group b ON
                    (b.i_price_group = a.i_price_group AND a.i_company = b.i_company)
                RIGHT JOIN tr_product c ON (c.i_product = a.i_product AND a.i_company = c.i_company)
                inner join tr_product_status s on
                    (s.i_product_status = c.i_product_status)
                WHERE a.i_company = '$this->i_company'
                ORDER BY
                    1,2
                ) AS x
            GROUP BY 1,2,3
            ORDER BY 1
        ", FALSE);
    }

    public function serverside2()
    // public function serverside()
    {
        return $this->db->query("SELECT
                i_product_id,
                e_product_name,
                e_product_statusname,
                jsonb_agg(e_price_groupname order by i_price_group) as e_price_groupname,
                jsonb_agg(v_price order by i_price_group) as v_price
            FROM (
                SELECT
                    /*a.i_product,*/
                case
                    when s.i_product_statusid = 'STP1' then c.i_product_id || ' (*STP)'
                    when s.i_product_statusid = 'STP2' then c.i_product_id || ' (#STP)'
                    else c.i_product_id
                end as i_product_id,
                    s.e_product_statusname,
                    a.i_price_group,
                    c.e_product_name,
                    b.e_price_groupname,
                    COALESCE (v_price, 0) AS v_price 
                FROM
                    tr_customer_price a
                INNER JOIN tr_price_group b ON
                    (b.i_price_group = a.i_price_group AND a.i_company = b.i_company)
                RIGHT JOIN tr_product c ON (c.i_product = a.i_product AND a.i_company = c.i_company)
                inner join tr_product_status s on
                    (s.i_product_status = c.i_product_status)
                WHERE a.i_company = '$this->i_company'
                ORDER BY
                    1,2
                ) AS x
            GROUP BY 1,2,3
            ORDER BY 1
        ", FALSE);
    }
}

/* End of file Mmaster.php */
