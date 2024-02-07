<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Msalesbyspbvssj extends CI_Model
{

    /** List Datatable */
    public function serverside($dfrom, $dto)
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

        return $this->db->query("SELECT
                DISTINCT 
                b.i_customer_id,
                initcap(b.e_customer_name) AS e_customer_name,
                c.e_customer_statusname,
                d.i_area_id,
                initcap(d.e_area_name) AS e_area_name,
                initcap(e.e_city_name) AS e_city_name,
                a.i_so_id,
                a.d_so,
                CASE
                    WHEN a.d_approve1 > a.d_approve2 THEN a.d_approve1
                    WHEN a.d_approve1 < a.d_approve2 THEN a.d_approve2
                    ELSE a.d_so
                END AS d_approve,
                CASE
                    WHEN a.d_approve1 > a.d_approve2 THEN (a.d_approve1 - a.d_so)
                    WHEN a.d_approve1 < a.d_approve2 THEN a.d_approve2 - a.d_so
                    ELSE a.d_so - a.d_so
                END AS so_approve_hari,
                f.i_do_id,
                f.d_do,
                f.d_do - a.d_so AS so_sj_hari,
                h.i_nota_id,
                h.d_nota,
                h.d_nota - f.d_do AS sj_nota_hari,
                h.d_nota - a.d_so AS so_nota_hari,
                h.v_nota_netto
            FROM
                tm_so a
            INNER JOIN tr_customer b ON
                (b.i_customer = a.i_customer)
            INNER JOIN tr_customer_status c ON
                (c.i_customer_status = b.i_customer_status)
            INNER JOIN tr_area d ON
                (d.i_area = a.i_area)
            INNER JOIN tr_city e ON
                (e.i_city = b.i_city)
            INNER JOIN tm_do f ON
                (f.i_do = a.i_so
                    AND f.f_do_cancel = 'f')
            INNER JOIN (
                SELECT
                    DISTINCT i_do,
                    i_nota
                FROM
                    tm_nota_item) g ON
                (g.i_do = f.i_do)
            INNER JOIN tm_nota h ON
                (h.i_nota = g.i_nota
                    AND h.f_nota_cancel = 'f')
            WHERE
                a.f_so_cancel = 'f'
                AND a.i_company = '$this->i_company'
                AND a.d_so BETWEEN '$dfrom' AND '$dto'
            ORDER BY
                a.i_so_id,
                d.i_area_id
        ", FALSE);
    }
}

/* End of file Mmaster.php */
