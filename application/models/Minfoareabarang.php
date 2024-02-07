<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Minfoareabarang extends CI_Model
{

    public function get_group()
    {
        return $this->db->query("SELECT * FROM tr_area WHERE f_area_active = 't' AND i_company = '$this->i_company' ORDER BY i_area ASC ", FALSE);
    }

    /** List Datatable */
    public function serverside()
    {
        $dfrom = $this->input->post('dfrom', TRUE);
        if ($dfrom == '') {
            $dfrom = $this->uri->segment(3);
            if ($dfrom == '') {
                $dfrom = '01-' . date('m-Y');
            }
        }
        $dto = $this->input->post('dto', TRUE);
        if ($dto == '') {
            $dto = $this->uri->segment(4);
            if ($dto == '') {
                $dto = date('d-m-Y');
            }
        }
        $dfrom  = date('Y-m-d', strtotime($dfrom));
        $dto    = date('Y-m-d', strtotime($dto));
        return $this->db->query("WITH cte AS (
            SELECT
                DISTINCT a.i_product,
                d.e_product_categoryname,
                c.i_product_id,
                c.e_product_name,
                y.i_area,
                y.e_area_name,
                y.i_area_id
            FROM
                tm_nota_item a
            INNER JOIN tm_nota b ON
                (a.i_nota = b.i_nota)
            INNER JOIN tr_product c ON
                (c.i_product = a.i_product)
            INNER JOIN tr_product_category d ON
                (d.i_product_category = c.i_product_category)
            CROSS JOIN tr_area y
            WHERE
                y.i_company = '$this->i_company'
                AND b.i_company = '$this->i_company'
                AND b.f_nota_cancel = 'f'
                AND b.d_nota BETWEEN '$dfrom' AND '$dto'
            )
            SELECT
                y.i_product,
                y.e_product_categoryname,
                y.i_product_id,
                y.e_product_name,
                jsonb_agg(y.i_area) AS i_area,
                jsonb_agg(y.e_area_name) AS e_area_name,
                jsonb_agg(y.i_area_id) AS i_area_id,
                jsonb_agg(COALESCE(x.n_deliver, 0)) AS n_deliver
            FROM
                (
                SELECT
                    a.i_product ,
                    b.i_area,
                    sum(a.n_deliver) AS n_deliver
                FROM
                    tm_nota_item a
                INNER JOIN tm_nota b ON
                    (a.i_nota = b.i_nota)
                WHERE
                    b.f_nota_cancel = 'f'
                    AND b.i_company = '$this->i_company'
                    AND b.d_nota BETWEEN '$dfrom' AND '$dto'
                GROUP BY
                    1,
                    2
            ) AS x
            RIGHT JOIN cte y ON
                (x.i_product = y.i_product
                    AND x.i_area = y.i_area)
            GROUP BY
                1,
                2,
                3,
                4
        ", FALSE);
    }



    public function serverside2($dfrom, $dto)
    // public function serverside()
    {
        $dfrom = $this->input->post('dfrom', TRUE);
        if ($dfrom == '') {
            $dfrom = $this->uri->segment(3);
        }

        $dto = $this->input->post('dto', TRUE);
        if ($dto == '') {
            $dto = $this->uri->segment(4);
        }

        $dfrom  = date('Y-m-d', strtotime($dfrom));
        $dto    = date('Y-m-d', strtotime($dto));

        return $this->db->query("WITH cte AS (
            SELECT
                DISTINCT a.i_product,
                d.e_product_categoryname,
                c.i_product_id,
                c.e_product_name,
                y.i_area,
                y.e_area_name,
                y.i_area_id
            FROM
                tm_nota_item a
            INNER JOIN tm_nota b ON
                (a.i_nota = b.i_nota)
            INNER JOIN tr_product c ON
                (c.i_product = a.i_product)
            INNER JOIN tr_product_category d ON
                (d.i_product_category = c.i_product_category)
            CROSS JOIN tr_area y
            WHERE
                y.i_company = '$this->i_company'
                AND b.i_company = '$this->i_company'
                AND b.f_nota_cancel = 'f'
                AND b.d_nota BETWEEN '$dfrom' AND '$dto'
            )
            SELECT
                y.i_product,
                y.e_product_categoryname,
                y.i_product_id,
                y.e_product_name,
                jsonb_agg(y.i_area) AS i_area,
                jsonb_agg(y.e_area_name) AS e_area_name,
                jsonb_agg(y.i_area_id) AS i_area_id,
                jsonb_agg(COALESCE(x.n_deliver, 0)) AS n_deliver
            FROM
                (
                SELECT
                    a.i_product ,
                    b.i_area,
                    sum(a.n_deliver) AS n_deliver
                FROM
                    tm_nota_item a
                INNER JOIN tm_nota b ON
                    (a.i_nota = b.i_nota)
                WHERE
                    b.f_nota_cancel = 'f'
                    AND b.i_company = '$this->i_company'
                    AND b.d_nota BETWEEN '$dfrom' AND '$dto'
                GROUP BY
                    1,
                    2
            ) AS x
            RIGHT JOIN cte y ON
                (x.i_product = y.i_product
                    AND x.i_area = y.i_area)
            GROUP BY
                1,
                2,
                3,
                4
        ", FALSE);
    }
}

/* End of file Mmaster.php */
