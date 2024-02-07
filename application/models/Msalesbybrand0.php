<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Msalesbybrand extends CI_Model
{

    /** List Datatable */
    public function serverside($dfrom, $dto)
    {
        $pecah1     = explode('-', $dfrom);
        $tgl1       = $pecah1[0];
        $bln1       = $pecah1[1];
        $tahun1     = $pecah1[2];
        $tahunprev1 = intval($tahun1) - 1;

        $pecah2     = explode('-', $dto);
        $tgl2       = $pecah2[0];
        $bln2       = $pecah2[1];
        $tahun2     = $pecah2[2];
        $tahunprev2 = intval($tahun2) - 1;

        if ((intval($tahunprev2) % 4 != 0) && ($bln2 == '02') && ($tgl2 == '29')) $tgl2 = '28';

        $gabung1 = $tgl1 . '-' . $bln1 . '-' . $tahunprev1;
        $gabung2 = $tgl2 . '-' . $bln2 . '-' . $tahunprev2;

        return $this->db->query("SELECT
                sum(a.oa) AS oa,
                sum(a.oaprev) AS oaprev,
                sum(a.ob) AS ob,
                sum(a.vnota) AS vnota,
                sum(a.vnotaprev) AS vnotaprev,
                sum(a.qty) AS qty,
                sum(a.qtyprev) AS qtyprev,
                a.e_product_groupname
            FROM
                (
                SELECT
                    sum(z.oa) AS oa,
                    0 AS oaprev,
                    sum(z.ob) AS ob,
                    sum(z.vnota) AS vnota,
                    0 AS vnotaprev,
                    sum(z.qty) AS qty,
                    0 AS qtyprev,
                    z.e_product_groupname
                FROM
                    (
                    SELECT
                        0 AS oa,
                        0 AS ob,
                        sum(a.vnota) AS vnota,
                        sum(a.qty) AS qty,
                        a.e_product_groupname
                    FROM
                        (
                        SELECT
                            c.i_product_group,
                            c.e_product_groupname,
                            sum((a.n_deliver * a.v_unit_price-(((a.n_deliver * a.v_unit_price)/ NULLIF(b.v_nota_gross,0))* b.v_nota_discount))) AS vnota,
                            sum(a.n_deliver) AS qty
                        FROM
                            tm_nota_item a,
                            tm_nota b,
                            tr_product_group c,
                            tr_product d
                        WHERE
                            b.f_nota_cancel = 'f'
                            AND a.i_nota = b.i_nota
                            AND a.i_product = d.i_product
                            AND d.i_product_group = c.i_product_group
                            AND b.i_company = '$this->i_company'
                            AND b.d_nota >= to_date('$dfrom', 'dd-mm-yyyy')
                            AND b.d_nota <= to_date('$dto', 'dd-mm-yyyy')
                        GROUP BY
                            c.i_product_group,
                            c.e_product_groupname ) AS a
                    GROUP BY
                        a.e_product_groupname
                UNION ALL
                    SELECT
                        count(a.oa) AS oa,
                        0 AS ob,
                        0 AS vnota,
                        0 AS qty,
                        a.e_product_groupname
                    FROM
                        (
                        SELECT
                            DISTINCT ON
                            (to_char(a.d_nota, 'yyyymm'),
                            a.i_customer) a.i_customer AS oa,
                            e.e_product_groupname,
                            e.i_product_group
                        FROM
                            tm_nota a,
                            tm_nota_item b,
                            tr_customer c,
                            tr_product d,
                            tr_product_group e,
                            tr_customer f
                        WHERE
                            (a.d_nota >= to_date('$dfrom', 'dd-mm-yyyy')
                                AND a.d_nota <= to_date('$dto', 'dd-mm-yyyy'))
                                AND a.f_nota_cancel = 'false'
                                AND a.i_nota = b.i_nota
                                AND a.i_customer = c.i_customer
                                AND b.i_product = d.i_product
                                AND d.i_product_group = e.i_product_group
                                AND a.i_company = '$this->i_company'
                                AND a.i_customer = f.i_customer
                                AND a.i_area = f.i_area
                            GROUP BY
                                e.i_product_group,
                                e.e_product_groupname,
                                a.i_customer,
                                to_char(a.d_nota, 'yyyymm') ) AS a
                    GROUP BY
                        a.e_product_groupname ) AS z
                GROUP BY
                    z.e_product_groupname
            UNION ALL /*------------------------------------------- batas tahun lalu -----------------------------------------*/
                SELECT
                    0 AS oa,
                    sum(z.oaprev) AS oaprev,
                    0 AS ob,
                    0 AS vnota,
                    sum(z.vnotaprev) AS vnotaprev,
                    0 AS qty,
                    sum(z.qtyprev) AS qtyprev,
                    z.e_product_groupname
                FROM
                    (
                    SELECT
                        0 AS oaprev,
                        sum(a.vnota) AS vnotaprev,
                        sum(a.qty) AS qtyprev,
                        a.e_product_groupname
                    FROM
                        (
                        SELECT
                            c.i_product_group,
                            c.e_product_groupname,
                            sum((a.n_deliver * a.v_unit_price-(((a.n_deliver * a.v_unit_price)/ b.v_nota_gross)* b.v_nota_discount))) AS vnota,
                            sum(a.n_deliver) AS qty
                        FROM
                            tm_nota_item a,
                            tm_nota b,
                            tr_product_group c,
                            tr_product d
                        WHERE
                            b.f_nota_cancel = 'f'
                            AND a.i_nota = b.i_nota
                            AND a.i_product = d.i_product
                            AND d.i_product_group = c.i_product_group
                            AND b.i_company = '$this->i_company'
                            AND b.d_nota >= to_date('$gabung1', 'dd-mm-yyyy')
                                AND b.d_nota <= to_date('$gabung2', 'dd-mm-yyyy')
                            GROUP BY
                                c.i_product_group,
                                c.e_product_groupname ) AS a
                    GROUP BY
                        a.e_product_groupname
                UNION ALL
                    SELECT
                        count(a.oa) AS oaprev,
                        0 AS vnotaprev,
                        0 AS qtyprev,
                        a.e_product_groupname
                    FROM
                        (
                        SELECT
                            DISTINCT ON
                            (to_char(a.d_nota, 'yyyymm'),
                            a.i_customer) a.i_customer AS oa,
                            e.e_product_groupname,
                            e.i_product_group
                        FROM
                            tm_nota a,
                            tm_nota_item b,
                            tr_customer c,
                            tr_product d,
                            tr_product_group e,
                            tr_customer f
                        WHERE
                            (a.d_nota >= to_date('$gabung1', 'dd-mm-yyyy')
                                AND a.d_nota <= to_date('$gabung2', 'dd-mm-yyyy'))
                                AND a.f_nota_cancel = 'false'
                                AND a.i_nota = b.i_nota
                                AND a.i_customer = c.i_customer
                                AND b.i_product = d.i_product
                                AND d.i_product_group = e.i_product_group
                                AND a.i_company = '$this->i_company'
                                AND a.i_customer = f.i_customer
                                AND a.i_area = f.i_area
                            GROUP BY
                                e.i_product_group,
                                e.e_product_groupname,
                                a.i_customer,
                                to_char(a.d_nota, 'yyyymm') ) AS a
                    GROUP BY
                        a.e_product_groupname ) AS z
                GROUP BY
                    z.e_product_groupname ) AS a
            GROUP BY
                a.e_product_groupname
            ORDER BY
                a.e_product_groupname
        ", FALSE);
    }
}

/* End of file Mmaster.php */
