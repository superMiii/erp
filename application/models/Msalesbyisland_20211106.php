<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Msalesbyisland extends CI_Model
{

    public function get_area()
    {
        return $this->db->query("SELECT DISTINCT
                a.i_area,
                a.i_area_id,
                a.e_area_name
            FROM 
                tr_area a
            INNER JOIN tm_user_area b ON 
                (b.i_area = a.i_area)
            WHERE 
                a.f_area_active = 't' 
                AND b.i_user = '$this->i_user'
                AND a.i_company = '$this->i_company'
            ORDER BY 3
        ", FALSE);
    }

    /** List Datatable */
    public function serverside($dfrom,$dto,$i_area)
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

        $where = ($i_area=='NA') ? '' : "AND a.i_area = '$i_area'" ;
        return $this->db->query("SELECT
                a.e_area_island,
                /* a.e_area_name,
                a.e_city_name, */
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
                    a.i_area,
                    a.e_area_island,
                    a.e_area_name,
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
                        a.i_area,
                        a.e_area_island,
                        a.e_area_name,
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
                            a.i_area,
                            a.e_area_island,
                            a.e_area_name,
                            a.e_city_name
                        FROM
                            (
                            SELECT
                                a.i_customer AS ob,
                                c.i_area,
                                c.e_area_island,
                                c.e_area_name,
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
                                        AND NOT a.i_nota ISNULL
                                        AND a.i_customer = e.i_customer
                                        AND a.i_area = e.i_area
                                        AND e.i_city = d.i_city
                                        AND e.i_area = d.i_area
                                        AND a.i_area = d.i_area
                                        AND a.i_company = '$this->i_company'
                                        AND c.i_company = '$this->i_company'
                                        AND d.i_company = '$this->i_company'
                                        AND e.i_company = '$this->i_company'
                                UNION ALL
                                    SELECT
                                        b.i_customer AS ob,
                                        c.i_area,
                                        c.e_area_island,
                                        c.e_area_name,
                                        d.e_city_name
                                    FROM
                                        tr_area c,
                                        tr_customer b,
                                        tr_city d
                                    WHERE
                                        b.i_customer_status <> '4'
                                        AND b.f_customer_active = 'true'
                                        AND b.i_area = c.i_area
                                        AND b.i_area = d.i_area
                                        AND b.i_city = d.i_city
                                        AND c.i_company = '$this->i_company'
                                        AND d.i_company = '$this->i_company'
                                        AND b.i_company = '$this->i_company' ) AS a ) AS a
                    GROUP BY
                        a.i_area,
                        a.e_area_island,
                        a.e_area_name,
                        a.e_city_name
                UNION ALL 
                    /*-- Hitung Rp.Nota --*/
                    SELECT
                        b.i_area,
                        b.e_area_island,
                        b.e_area_name,
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
                        AND a.i_area = b.i_area
                        AND a.i_customer = d.i_customer
                        AND a.i_area = d.i_area
                        AND d.i_area = c.i_area
                        AND a.i_area = c.i_area
                        AND d.i_city = c.i_city
                        AND a.i_company = '$this->i_company'
                        AND c.i_company = '$this->i_company'
                        AND d.i_company = '$this->i_company'
                        AND b.i_company = '$this->i_company'
                    GROUP BY
                        b.i_area,
                        b.e_area_island,
                        b.e_area_name,
                        c.e_city_name
                UNION ALL 
                    /*-- Hitung Qty --*/
                    SELECT
                        b.i_area,
                        b.e_area_island,
                        b.e_area_name,
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
                        AND NOT a.i_nota ISNULL
                        AND a.i_area = b.i_area
                        AND a.i_nota = c.i_nota
                        AND a.i_area = d.i_area
                        AND a.i_customer = d.i_customer
                        AND a.i_area = e.i_area
                        AND d.i_city = e.i_city
                        AND d.i_area = e.i_area
                        AND a.i_company = '$this->i_company'
                        AND b.i_company = '$this->i_company'
                        AND d.i_company = '$this->i_company'
                        AND e.i_company = '$this->i_company'
                    GROUP BY
                        b.i_area,
                        b.e_area_island,
                        b.e_area_name,
                        e.e_city_name
                UNION ALL 
                    /*-- Hitung OA --*/
                    SELECT
                        a.i_area,
                        a.e_area_island,
                        a.e_area_name,
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
                            b.i_area,
                            b.e_area_island,
                            b.e_area_name,
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
                                AND NOT a.i_nota ISNULL
                                AND a.i_area = b.i_area
                                AND a.i_customer = c.i_customer
                                AND a.i_area = c.i_area 
                                AND c.f_customer_active='true'
                                AND a.i_area = d.i_area
                                AND c.i_area = d.i_area
                                AND c.i_city = d.i_city
                                AND a.i_company = '$this->i_company'
                                AND b.i_company = '$this->i_company'
                                AND c.i_company = '$this->i_company'
                                AND d.i_company = '$this->i_company' ) AS a
                    GROUP BY
                        a.i_area,
                        a.e_area_island,
                        a.e_area_name,
                        a.e_city_name
                    /*--=============================================End This Year=============================================*/
                UNION ALL 
                    /*--=============================================Start Prev Year===========================================*/
                    /*-- Hitung Rp.Nota --*/
                    SELECT
                        b.i_area,
                        b.e_area_island,
                        b.e_area_name,
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
                        AND NOT a.i_nota ISNULL
                        AND a.i_area = b.i_area
                        AND a.i_customer = d.i_customer
                        AND a.i_area = d.i_area
                        AND d.i_area = c.i_area
                        AND a.i_area = c.i_area
                        AND d.i_city = c.i_city
                        AND a.i_company = '$this->i_company'
                        AND b.i_company = '$this->i_company'
                        AND c.i_company = '$this->i_company'
                        AND d.i_company = '$this->i_company'
                    GROUP BY
                        b.i_area,
                        b.e_area_island,
                        b.e_area_name,
                        c.e_city_name
                UNION ALL 
                    /*-- Hitung Qty --*/
                    SELECT
                        b.i_area,
                        b.e_area_island,
                        b.e_area_name,
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
                        AND NOT a.i_nota ISNULL
                        AND a.i_area = b.i_area
                        AND a.i_nota = c.i_nota
                        AND a.i_area = d.i_area
                        AND a.i_customer = d.i_customer
                        AND a.i_area = e.i_area
                        AND d.i_city = e.i_city
                        AND d.i_area = e.i_area
                        AND a.i_company = '$this->i_company'
                        AND b.i_company = '$this->i_company'
                        AND d.i_company = '$this->i_company'
                        AND e.i_company = '$this->i_company'
                    GROUP BY
                        b.i_area,
                        b.e_area_island,
                        b.e_area_name,
                        e.e_city_name
                UNION ALL 
                    /*-- Hitung OA --*/
                    SELECT
                        a.i_area,
                        a.e_area_island,
                        a.e_area_name,
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
                            b.i_area,
                            b.e_area_island,
                            b.e_area_name,
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
                                AND NOT a.i_nota ISNULL
                                AND a.i_area = b.i_area
                                AND a.i_customer = c.i_customer
                                AND a.i_area = c.i_area 
                                AND c.f_customer_active='true'
                                AND a.i_area = d.i_area
                                AND c.i_area = d.i_area
                                AND c.i_city = d.i_city
                                AND a.i_company = '$this->i_company'
                                AND b.i_company = '$this->i_company'
                                AND c.i_company = '$this->i_company'
                                AND d.i_company = '$this->i_company' ) AS a
                    GROUP BY
                        a.i_area,
                        a.e_area_island,
                        a.e_area_name,
                        a.e_city_name ) AS a
                GROUP BY
                    a.i_area,
                    a.e_area_island,
                    a.e_area_name,
                    a.e_city_name ) AS a
            INNER JOIN tm_user_area b ON (b.i_area = a.i_area AND b.i_user = '$this->i_user')
            $where
            GROUP BY
                a.e_area_island/* ,
                a.e_area_name,
                a.e_city_name */
            ORDER BY
                a.e_area_island/* ,
                a.e_area_name,
                a.e_city_name */
        ", FALSE);
    }
}

/* End of file Mmaster.php */
