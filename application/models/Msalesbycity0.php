<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Msalesbycity extends CI_Model
{

    /** List Datatable */
    public function serverside($dfrom,$dto)
    {
        $tmp=explode("-",$dfrom);
        $hr=$tmp[0];
        $bl=$tmp[1];
        $th=$tmp[2]-1;
        $thnow=$tmp[2];
        $thbl=$thnow."-".$bl;
        $dfromprev=$hr."-".$bl."-".$th;

        $tsasih = date('Y-m', strtotime('-24 month', strtotime($thbl))); //tambah tanggal sebanyak 6 bulan
        if($tsasih!=''){
        $smn = explode("-", $tsasih);
        $thn = $smn[0];
        $bln = $smn[1];
        }
        $taunsasih = $thn.$bln;
      
        $tmp=explode("-",$dto);
        $hr=$tmp[0];
        $bl=$tmp[1];
        $th=$tmp[2]-1;
        $thnya=$tmp[2];
        $thblto=$thnya.$bl;
        if((intval($th)%4!=0)&&($bl=='02')&&($hr=='29')) $hr='28';
        $dtoprev=$hr."-".$bl."-".$th;
        return $this->db->query("SELECT
                a.e_area_island,
                a.e_area_name,
                a.i_area_id,
                a.i_city_id,
                a.e_city_name,
                sum(a.vnota) AS vnota,
                sum(qnota) AS qnota,
                sum(a.ob) AS ob,
                sum(a.oa) AS oa,
                sum(a.prevvnota) AS prevvnota,
                sum(a.prevqnota) AS prevqnota,
                sum(a.prevoa) AS prevoa
            FROM
                (
                SELECT
                    a.e_area_island,
                    a.e_area_name,
                a.i_area_id,
                a.i_city_id,
                    a.e_city_name,
                    sum(a.vnota) AS vnota,
                    sum(qnota) AS qnota,
                    sum(a.ob) AS ob,
                    sum(a.oa) AS oa,
                    sum(a.prevvnota) AS prevvnota,
                    sum(a.prevqnota) AS prevqnota,
                    sum(a.prevoa) AS prevoa
                FROM
                    (
                    /*-- ============================== Start This Year ============================================ --*/
                    SELECT
                        a.e_area_island,
                        a.e_area_name,
                a.i_area_id,
                a.i_city_id,
                        a.e_city_name,
                        0 AS vnota,
                        0 AS qnota,
                        count(ob) AS ob,
                        0 AS oa,
                        0 AS prevvnota,
                        0 AS prevqnota,
                        0 AS prevoa
                    FROM
                        (
                        SELECT
                            DISTINCT ON
                            (a.ob) a.ob AS ob,
                            a.e_area_island,
                            a.e_area_name,
                a.i_area_id,
                a.i_city_id,
                            a.e_city_name
                        FROM
                            (
                            SELECT
                                a.i_customer AS ob,
                                c.e_area_island,
                                c.e_area_name,
                c.i_area_id,
                d.i_city_id,
                                d.e_city_name
                            FROM
                                tm_nota a,
                                tr_area c,
                                tr_city d,
                                tr_customer e
                            WHERE
                                to_char(a.d_nota, 'yyyymm')>= '$taunsasih'
                                    AND to_char(a.d_nota, 'yyyymm') <= '$thblto'
                                        AND a.f_nota_cancel = 'false'
                                        AND a.i_area = c.i_area
                                        AND a.i_company = '$this->i_company'
                                        AND a.i_customer = e.i_customer
                                        AND a.i_area = e.i_area
                                        AND e.i_city = d.i_city
                                        AND e.i_area = d.i_area
                                        AND a.i_area = d.i_area
                                UNION ALL
                                    SELECT
                                        b.i_customer AS ob,
                                        c.e_area_island,
                                        c.e_area_name,
                c.i_area_id,
                d.i_city_id,
                                        d.e_city_name
                                    FROM
                                        tr_area c,
                                        tr_customer b,
                                        tr_city d
                                    WHERE
                                        b.i_company = '$this->i_company'
                                        AND b.f_customer_active = 'true'
                                        AND b.i_area = c.i_area
                                        AND b.i_area = d.i_area
                                        AND b.i_city = d.i_city )AS a ) AS a
                    GROUP BY
                        a.e_area_island,
                        a.e_area_name,
                a.i_area_id,
                a.i_city_id,
                        a.e_city_name
                UNION ALL 
                    /*-- Hitung Rp.Nota --*/
                    SELECT
                        b.e_area_island,
                        b.e_area_name,
                b.i_area_id,
                c.i_city_id,
                        c.e_city_name,
                        sum(a.v_nota_netto) AS vnota,
                        0 AS qnota,
                        0 AS ob,
                        0 AS oa,
                        0 AS prevvnota,
                        0 AS prevqnota,
                        0 AS prevoa
                    FROM
                        tm_nota a,
                        tr_area b,
                        tr_city c,
                        tr_customer d
                    WHERE
                        (a.d_nota >= to_date('$dfrom', 'dd-mm-yyyy')
                            AND a.d_nota <= to_date('$dto', 'dd-mm-yyyy'))
                        AND a.f_nota_cancel = 'f'
                        AND a.i_company = '$this->i_company'
                        AND a.i_area = b.i_area
                        AND a.i_customer = d.i_customer
                        AND a.i_area = d.i_area
                        AND d.i_area = c.i_area
                        AND a.i_area = c.i_area
                        AND d.i_city = c.i_city
                    GROUP BY
                        b.e_area_island,
                        b.e_area_name,
                b.i_area_id,
                c.i_city_id,
                        c.e_city_name
                UNION ALL 
                    /*-- Hitung Qty --*/
                    SELECT
                        b.e_area_island,
                        b.e_area_name,
                b.i_area_id,
                e.i_city_id,
                        e.e_city_name,
                        0 AS vnota,
                        sum(c.n_deliver) AS qnota,
                        0 AS ob,
                        0 AS oa,
                        0 AS prevvnota,
                        0 AS prevqnota,
                        0 AS prevoa
                    FROM
                        tm_nota a,
                        tr_area b,
                        tm_nota_item c,
                        tr_customer d,
                        tr_city e
                    WHERE
                        (a.d_nota >= to_date('$dfrom', 'dd-mm-yyyy')
                            AND a.d_nota <= to_date('$dto', 'dd-mm-yyyy'))
                        AND a.f_nota_cancel = 'f'
                        AND a.i_company = '$this->i_company'
                        AND a.i_area = b.i_area
                        AND a.i_nota = c.i_nota
                        AND a.i_area = d.i_area
                        AND a.i_customer = d.i_customer
                        AND a.i_area = e.i_area
                        AND d.i_city = e.i_city
                        AND d.i_area = e.i_area
                    GROUP BY
                        b.e_area_island,
                        b.e_area_name,
                b.i_area_id,
                e.i_city_id,
                        e.e_city_name
                UNION ALL 
                    /*-- Hitung OA --*/
                    SELECT
                        a.e_area_island,
                        a.e_area_name,
                a.i_area_id,
                a.i_city_id,
                        a.e_city_name,
                        0 AS vnota,
                        0 AS qnota,
                        0 AS ob,
                        count(a.oa) AS oa,
                        0 AS prevvnota,
                        0 AS prevqnota,
                        0 AS prevoa
                    FROM
                        (
                        SELECT
                            DISTINCT ON
                            (to_char(a.d_nota, 'yyyymm'),
                            a.i_customer) a.i_customer AS oa,
                            b.e_area_island,
                            b.e_area_name,
                b.i_area_id,
                d.i_city_id,
                            d.e_city_name
                        FROM
                            tm_nota a,
                            tr_area b,
                            tr_customer c,
                            tr_city d
                        WHERE
                            (a.d_nota >= to_date('$dfrom', 'dd-mm-yyyy')
                                AND a.d_nota <= to_date('$dto', 'dd-mm-yyyy'))
                                AND a.f_nota_cancel = 'false'
                                AND a.i_company = '$this->i_company'
                                AND a.i_area = b.i_area
                                AND a.i_customer = c.i_customer
                                AND a.i_area = c.i_area 
                                AND c.f_customer_active='true'
                                AND a.i_area = d.i_area
                                AND c.i_area = d.i_area
                                AND c.i_city = d.i_city ) AS a
                    GROUP BY
                        a.e_area_island,
                        a.e_area_name,
                a.i_area_id,
                a.i_city_id,
                        a.e_city_name
                    /*--=============================================End This Year=============================================*/
                UNION ALL 
                    /*--=============================================Start Prev Year===========================================*/
                    /*-- Hitung Rp.Nota --*/
                    SELECT
                        b.e_area_island,
                        b.e_area_name,
                b.i_area_id,
                c.i_city_id,
                        c.e_city_name,
                        0 AS vnota,
                        0 AS qnota,
                        0 AS ob,
                        0 AS oa,
                        sum(a.v_nota_netto) AS prevvnota,
                        0 AS prevqnota,
                        0 AS prevoa
                    FROM
                        tm_nota a,
                        tr_area b,
                        tr_city c,
                        tr_customer d
                    WHERE
                        (a.d_nota >= to_date('$dfromprev', 'dd-mm-yyyy')
                            AND a.d_nota <= to_date('$dtoprev', 'dd-mm-yyyy'))
                        AND a.f_nota_cancel = 'f'
                        AND a.i_company = '$this->i_company'
                        AND a.i_area = b.i_area
                        AND a.i_customer = d.i_customer
                        AND a.i_area = d.i_area
                        AND d.i_area = c.i_area
                        AND a.i_area = c.i_area
                        AND d.i_city = c.i_city
                    GROUP BY
                        b.e_area_island,
                        b.e_area_name,
                b.i_area_id,
                c.i_city_id,
                        c.e_city_name
                UNION ALL 
                    /*-- Hitung Qty --*/
                    SELECT
                        b.e_area_island,
                        b.e_area_name,
                b.i_area_id,
                e.i_city_id,
                        e.e_city_name,
                        0 AS vnota,
                        0 AS qnota,
                        0 AS ob,
                        0 AS oa,
                        0 AS prevvnota,
                        sum(c.n_deliver) AS prevqnota,
                        0 AS prevoa
                    FROM
                        tm_nota a,
                        tr_area b,
                        tm_nota_item c,
                        tr_customer d,
                        tr_city e
                    WHERE
                        (a.d_nota >= to_date('$dfromprev', 'dd-mm-yyyy')
                            AND a.d_nota <= to_date('$dtoprev', 'dd-mm-yyyy'))
                        AND a.f_nota_cancel = 'f'
                        AND a.i_company = '$this->i_company'
                        AND a.i_area = b.i_area
                        AND a.i_nota = c.i_nota
                        AND a.i_area = d.i_area
                        AND a.i_customer = d.i_customer
                        AND a.i_area = e.i_area
                        AND d.i_city = e.i_city
                        AND d.i_area = e.i_area
                    GROUP BY
                        b.e_area_island,
                        b.e_area_name,
                b.i_area_id,
                e.i_city_id,
                        e.e_city_name
                UNION ALL 
                    /*--Hitung OA--*/
                    SELECT
                        a.e_area_island,
                        a.e_area_name,
                a.i_area_id,
                a.i_city_id,
                        a.e_city_name,
                        0 AS vnota,
                        0 AS qnota,
                        0 AS ob,
                        0 AS oa,
                        0 AS prevvnota,
                        0 AS prevqnota,
                        count(oa) AS prevoa
                    FROM
                        (
                        SELECT
                            DISTINCT ON
                            (to_char(a.d_nota, 'yyyymm'),
                            a.i_customer) a.i_customer AS oa,
                            b.e_area_island,
                            b.e_area_name,
                b.i_area_id,
                d.i_city_id,
                            d.e_city_name
                        FROM
                            tm_nota a,
                            tr_area b,
                            tr_customer c,
                            tr_city d
                        WHERE
                            (a.d_nota >= to_date('$dfromprev', 'dd-mm-yyyy')
                                AND a.d_nota <= to_date('$dtoprev', 'dd-mm-yyyy'))
                                AND a.f_nota_cancel = 'false'
                                AND a.i_company = '$this->i_company'
                                AND a.i_area = b.i_area
                                AND a.i_customer = c.i_customer
                                AND a.i_area = c.i_area 
                                AND c.f_customer_active='true'
                                AND a.i_area = d.i_area
                                AND c.i_area = d.i_area
                                AND c.i_city = d.i_city ) AS a
                    GROUP BY
                        a.e_area_island,
                        a.e_area_name,
                a.i_area_id,
                a.i_city_id,
                        a.e_city_name ) AS a
                GROUP BY
                    a.e_area_island,
                    a.e_area_name,
                a.i_area_id,
                a.i_city_id,
                    a.e_city_name ) AS a
            GROUP BY
                a.e_area_island,
                a.e_area_name,
                a.i_area_id,
                a.i_city_id,
                a.e_city_name
            ORDER BY                
            a.i_area_id
        ", FALSE);
    }
}

/* End of file Mmaster.php */
