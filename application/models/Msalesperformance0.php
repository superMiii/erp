<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Msalesperformance extends CI_Model
{

    /** List Datatable */
    public function serverside($dfrom, $dto)
    {
        $dfrom  = date('Ym', strtotime($dfrom));
        $dto    = date('Ym', strtotime($dto));
        return $this->db->query("SELECT
                i_periode,
                sum(v_target)::money AS v_target,
                sum(v_so)::money AS v_so,
                sum(v_do)::money AS v_do,
                sum(v_nota)::money AS v_nota
            FROM
                (
                SELECT
                    to_char((substring(i_periode, 1,4)||'-'||substring(i_periode, 5,2)||'-01')::date, 'FMMonth YYYY') AS i_periode,
                    sum(v_target) AS v_target,
                    0 AS v_so,
                    0 AS v_do,
                    0 AS v_nota
                FROM
                    tm_target_item
                WHERE
                    i_periode BETWEEN '$dfrom' AND '$dto'
                    AND i_company = '$this->i_company'
                GROUP BY
                    1
            UNION ALL
                SELECT
                    to_char(d_so, 'FMMonth YYYY') AS i_periode,
                    0 AS v_target,
                    sum(v_so) AS v_so,
                    0 AS v_do,
                    0 AS v_nota
                FROM
                    tm_so
                WHERE 
                    to_char(d_so, 'YYYYmm') >= '$dfrom' AND to_char(d_so, 'YYYYmm') <= '$dto'
                    AND tm_so.i_company = '$this->i_company'
                    AND f_so_cancel = 'f'
                GROUP BY
                    1
            UNION ALL
                SELECT
                    to_char(d_do, 'FMMonth YYYY') AS i_periode,
                    0 AS v_target,
                    0 AS v_so,
                    round((v_total - (v_so_discounttotal + v_diskon1 + v_diskon2 + v_diskon3)) * ((x.n_so_ppn+100)/100)) AS v_do,
                    0 AS v_nota
                FROM
                    (
                    SELECT
                        a.i_company,
                        a.d_do,
                        b.n_so_ppn,
                        b.v_so_discounttotal,
                        sum(d.n_deliver * e.v_unit_price) AS v_total,
                        sum(d.n_deliver * e.v_unit_price * (e.n_so_discount1 / 100)) AS v_diskon1,
                        sum((d.n_deliver * e.v_unit_price - d.n_deliver * e.v_unit_price * (e.n_so_discount1 / 100))*(e.n_so_discount2 / 100)) AS v_diskon2,
                        sum(((d.n_deliver * e.v_unit_price)-(d.n_deliver * e.v_unit_price * (e.n_so_discount1 / 100))-((d.n_deliver * e.v_unit_price - d.n_deliver * e.v_unit_price * (e.n_so_discount1 / 100))*(e.n_so_discount2 / 100))) * (e.n_so_discount3 / 100)) AS v_diskon3
                    FROM
                        tm_do a
                    INNER JOIN tm_so b ON
                        (b.i_so = a.i_so)
                    INNER JOIN tm_do_item d ON
                        (d.i_do = a.i_do)
                    INNER JOIN tm_so_item e ON
                        (e.i_so = a.i_so
                            AND d.i_product = e.i_product)
                    WHERE 
                        to_char(d_do, 'YYYYmm') >= '$dfrom' AND to_char(d_do, 'YYYYmm') <= '$dto'
                        AND a.i_company = '$this->i_company'
                        AND a.f_do_cancel = 'f'
                    GROUP BY
                        1,
                        2,
                        3,
                        4
            ) AS x
                inner join tr_company co on (co.i_company=x.i_company)
            UNION ALL
                SELECT
                    to_char(d_nota, 'FMMonth YYYY') AS i_periode,
                    0 AS v_target,
                    0 AS v_so,
                    0 AS v_do,
                    sum(v_nota_netto) AS v_nota
                FROM
                    tm_nota
                WHERE 
                    to_char(d_nota, 'YYYYmm') >= '$dfrom' AND to_char(d_nota, 'YYYYmm') <= '$dto'
                    AND tm_nota.i_company = '$this->i_company'
                    AND f_nota_cancel = 'f'
                GROUP BY
                    1
                ) AS x
            GROUP BY
                1
            ORDER BY
                1
        ", FALSE);
    }
}

/* End of file Mmaster.php */
