<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Msalesbymonthly extends CI_Model
{

    /** List Datatable */
    public function serverside($dfrom, $dto)
    {
        $tmp = explode("-", $dfrom);
        $hr = $tmp[0];
        $bl = $tmp[1];
        $th = $tmp[2] - 1;
        $thnow = $tmp[2];
        $thbl = $thnow . "-" . $bl;
        $dfromprev = $hr . "-" . $bl . "-" . $th;
        $tsasih = date('Y-m', strtotime('-24 month', strtotime($thbl))); //tambah tanggal sebanyak 6 bulan
        if ($tsasih != '') {
            $smn = explode("-", $tsasih);
            $thn = $smn[0];
            $bln = $smn[1];
        }
        $taunsasih = $thn . $bln;

        $tmp = explode("-", $dto);
        $hri = $tmp[0];
        $bln = $tmp[1];
        $th = $tmp[2] - 1;
        $thn = $tmp[2];
        $thblto = $thn . $bln;
        if ((intval($th) % 4 != 0) && ($bln == '02') && ($hri == '29')) $hri = '28';
        $dtoprev = $hri . "-" . $bln . "-" . $th;

        $tsasih = date('Y-m-d', strtotime('-24 month', strtotime($dto))); //tambah tanggal sebanyak 2 tahun
        $dtos = date('Y-m-d', strtotime($dto));
        return $this->db->query("SELECT
                a.i_periode,
                sum(ob) AS ob,
                sum(oa) AS oa,
                sum(qty) AS qnota,
                sum(vnota) AS vnota,
                sum(oaprev)AS prevoa,
                sum(qtyprev) AS prevqnota,
                sum(vnotaprev) AS prevvnota
            FROM
                (/* Hitung OB */
                SELECT
                    a.i_periode,
                    count(ob) AS ob,
                    0 AS oa,
                    0 AS qty,
                    0 AS vnota,
                    0 AS oaprev,
                    0 AS qtyprev,
                    0 AS vnotaprev
                FROM
                    (
                    SELECT
                        DISTINCT ON
                        (a.ob) a.ob AS ob,
                        to_char(a.d_nota, 'mm') AS i_periode
                    FROM
                        (
                        SELECT
                            a.i_customer AS ob,
                            a.i_area,
                            a.d_nota
                        FROM
                            tm_nota a,
                            tr_area c
                        WHERE
                            to_char(a.d_nota, 'yyyymm')>= '$taunsasih'
                                AND to_char(a.d_nota, 'yyyymm') <= '$thblto'
                                    AND a.f_nota_cancel = 'false'
                                    AND a.i_area = c.i_area
                                    AND a.i_company = '$this->i_company'
                            UNION ALL
                                SELECT
                                    b.i_customer AS ob,
                                    b.i_area,
                                    NULL AS d_nota
                                FROM
                                    tr_customer b,
                                    tr_area c
                                WHERE
                                    b.f_customer_active = 'true'
                                    AND b.i_area = c.i_area
                                    AND b.i_customer NOT IN(
                                    SELECT
                                        a.i_customer
                                    FROM
                                        tm_nota a,
                                        tr_area c
                                    WHERE
                                        to_char(a.d_nota, 'yyyymm')>= '$taunsasih'
                                            AND to_char(a.d_nota, 'yyyymm') <= '$thblto'
                                                AND a.f_nota_cancel = 'false'
                                                AND a.i_area = c.i_area
                                                AND a.i_company = '$this->i_company' ) ) AS a )AS a
                GROUP BY
                    a.i_periode
            UNION ALL /* Hitung OA */
                SELECT
                    a.i_periode,
                    0 AS ob,
                    count(oa) AS oa,
                    0 AS qty,
                    0 AS vnota,
                    0 AS oaprev,
                    0 AS qtyprev,
                    0 AS vnotaprev
                FROM
                    (
                    SELECT
                        DISTINCT ON
                        (to_char(a.d_nota, 'yyyymm'),
                        a.i_customer) a.i_customer AS oa,
                        to_char(a.d_nota, 'mm') AS i_periode
                    FROM
                        tm_nota a,
                        tr_customer b
                    WHERE
                        (a.d_nota >= to_date('$dfrom', 'dd-mm-yyyy')
                            AND a.d_nota <= to_date('$dto', 'dd-mm-yyyy'))
                            AND a.f_nota_cancel = 'false'
                            AND NOT a.i_nota ISNULL
                            AND a.i_customer = b.i_customer
                            AND a.i_area = b.i_area
                            AND a.i_company = '$this->i_company') AS a
                GROUP BY
                    a.i_periode
            UNION ALL /*Hitung Qty*/
                SELECT
                    to_char(a.d_nota, 'mm') AS i_periode,
                    0 AS ob,
                    0 AS oa,
                    sum(b.n_deliver) AS qty,
                    0 AS vnota,
                    0 AS oaprev,
                    0 AS qtyprev,
                    0 AS vnotaprev
                FROM
                    tm_nota a,
                    tm_nota_item b,
                    tr_customer c,
                    tr_area f
                WHERE
                    (a.d_nota >= to_date('$dfrom', 'dd-mm-yyyy')
                        AND a.d_nota <= to_date('$dto', 'dd-mm-yyyy'))
                    AND a.f_nota_cancel = 'f'
                    AND a.i_nota = b.i_nota
                    AND f.i_area = a.i_area
                    AND a.i_customer = c.i_customer
                    AND a.i_company = '$this->i_company'
                GROUP BY
                    to_char(a.d_nota, 'mm')
            UNION ALL /*Hitung Nota*/
                SELECT
                    to_char(a.d_nota, 'mm') AS i_periode,
                    0 AS ob,
                    0 AS oa,
                    0 AS qty,
                    sum(a.v_nota_netto) AS vnota,
                    0 AS oaprev,
                    0 AS qtyprev,
                    0 AS vnotaprev
                FROM
                    tm_nota a,
                    tr_customer c,
                    tr_area f
                WHERE
                    (a.d_nota >= to_date('$dfrom', 'dd-mm-yyyy')
                        AND a.d_nota <= to_date('$dto', 'dd-mm-yyyy'))
                    AND a.f_nota_cancel = 'f'
                    AND f.i_area = a.i_area
                    AND a.i_customer = c.i_customer
                    AND a.i_company = '$this->i_company'
                GROUP BY
                    to_char(a.d_nota, 'mm')
            UNION ALL /*Hitung OA Prevth*/
                SELECT
                    a.i_periode,
                    0 AS ob,
                    0 AS oa,
                    0 AS qty,
                    0 AS vnota,
                    count(oa) AS oaprev,
                    0 AS qtyprev,
                    0 AS vnotaprev
                FROM
                    (
                    SELECT
                        DISTINCT ON
                        (to_char(a.d_nota, 'yyyymm'),
                        a.i_customer) a.i_customer AS oa,
                        to_char(a.d_nota, 'mm') AS i_periode
                    FROM
                        tm_nota a,
                        tr_customer b
                    WHERE
                        (a.d_nota >= to_date('$dfromprev', 'dd-mm-yyyy')
                            AND a.d_nota <= to_date('$dtoprev', 'dd-mm-yyyy'))
                            AND a.f_nota_cancel = 'false'
                            AND a.i_customer = b.i_customer
                            AND a.i_area = b.i_area
                            AND a.i_company = '$this->i_company') AS a
                GROUP BY
                    a.i_periode
            UNION ALL /*Hitung Qty Prevth*/
                SELECT
                    to_char(a.d_nota, 'mm') AS i_periode,
                    0 AS ob,
                    0 AS oa,
                    0 AS qty,
                    0 AS vnota,
                    0 AS oaprev,
                    sum(b.n_deliver) AS qtyprev,
                    0 AS vnotaprev
                FROM
                    tm_nota a,
                    tm_nota_item b,
                    tr_customer c,
                    tr_area f
                WHERE
                    (a.d_nota >= to_date('$dfromprev', 'dd-mm-yyyy')
                        AND a.d_nota <= to_date('$dtoprev', 'dd-mm-yyyy'))
                    AND a.f_nota_cancel = 'f'
                    AND a.i_nota = b.i_nota
                    AND f.i_area = a.i_area
                    AND a.i_customer = c.i_customer
                    AND a.i_company = '$this->i_company'
                GROUP BY
                    to_char(a.d_nota, 'mm')
            UNION ALL /*Hitung Nota Prevth*/
                SELECT
                    to_char(a.d_nota, 'mm') AS i_periode,
                    0 AS ob,
                    0 AS oa,
                    0 AS qty,
                    0 AS vnota,
                    0 AS oaprev,
                    0 AS qtyprev,
                    sum(a.v_nota_netto) AS vnotaprev
                FROM
                    tm_nota a,
                    tr_customer c,
                    tr_area f
                WHERE
                    (a.d_nota >= to_date('$dfromprev', 'dd-mm-yyyy')
                        AND a.d_nota <= to_date('$dtoprev', 'dd-mm-yyyy'))
                    AND a.f_nota_cancel = 'f'
                    AND f.i_area = a.i_area
                    AND a.i_customer = c.i_customer
                    AND a.i_company = '$this->i_company'
                GROUP BY
                    to_char(a.d_nota, 'mm') ) AS a
            GROUP BY
                a.i_periode
            ORDER BY
                a.i_periode
        ", FALSE);
    }
}

/* End of file Mmaster.php */
