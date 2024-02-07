<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Msalesbyproduct extends CI_Model
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
                x.i_product_id,
                z.e_product_name,
                sum(z.ob) AS ob,
                sum(z.oa) AS oa,
                sum(z.oaprev) AS oaprev,
                sum(z.jml) AS jml,
                sum(z.jmlprev) AS jmlprev,
                COALESCE (sum(z.netitem),
                0) AS netitem,
                sum(z.netitemprev) AS netitemprev,
                COALESCE ((sum(z.netitem)/(
                SELECT
                    sum(v_nota_netto) AS v_nota
                FROM
                    tm_nota
                WHERE
                    d_nota >= to_date('$dfrom', 'dd-mm-yyyy')
                    AND d_nota <= to_date('$dto', 'dd-mm-yyyy')
                    AND i_company = '$this->i_company'
                    AND f_nota_cancel = 'false')* 100),
                0) AS ctrnetsales
            FROM
                (
                SELECT
                    b.i_product,
                    sum(b.oa) AS oa,
                    0 AS oaprev,
                    sum(b.ob) AS ob,
                    b.e_product_name,
                    sum(b.jml) AS jml,
                    0 AS jmlprev,
                    sum(b.netitem) AS netitem,
                    0 AS netitemprev
                FROM
                    (
                    SELECT
                        a.i_product,
                        count(a.i_customer) AS oa,
                        0 AS oaprev,
                        0 AS ob,
                        a.e_product_name,
                        sum(a.jml) AS jml,
                        0 AS netitem
                    FROM
                        (
                        SELECT
                            DISTINCT ON
                            (to_char(b.d_nota, 'yyyymm'),
                            b.i_customer) a.i_product,
                            b.i_customer,
                            c.e_product_name,
                            0 AS jml
                        FROM
                            tm_nota_item a,
                            tm_nota b,
                            tr_product c,
                            tr_customer d
                        WHERE
                            a.i_nota = b.i_nota
                            AND a.i_product = c.i_product
                            AND b.f_nota_cancel = 'false'
                            AND (b.d_nota >= to_date('$dfrom', 'dd-mm-yyyy')
                                AND b.d_nota <= to_date('$dto', 'dd-mm-yyyy'))
                                AND b.i_company = '$this->i_company'
                                AND b.i_customer = d.i_customer
                                AND b.i_area = d.i_area
                            GROUP BY
                                a.i_product,
                                b.i_customer,
                                c.e_product_name,
                                b.i_nota,
                                b.d_nota )AS a
                    GROUP BY
                        a.i_product,
                        a.e_product_name
                UNION ALL
                    SELECT
                        a.i_product,
                        0 AS oa,
                        0 AS oaprev,
                        0 AS ob,
                        a.e_product_name,
                        sum(a.jml) AS jml,
                        sum(a.netitem) AS netitem
                    FROM
                        (
                        SELECT
                            a.i_product,
                            c.e_product_name,
                            sum((a.n_deliver * a.v_unit_price-(((a.n_deliver * a.v_unit_price) / NULLIF(b.v_nota_gross, 0))* b.v_nota_discount))) AS netitem,
                            sum(a.n_deliver) AS jml
                        FROM
                            tm_nota_item a,
                            tm_nota b,
                            tr_product c
                        WHERE
                            a.i_nota = b.i_nota
                            AND a.i_product = c.i_product
                            AND b.f_nota_cancel = 'false'
                            AND (b.d_nota >= to_date('$dfrom', 'dd-mm-yyyy')
                                AND b.d_nota <= to_date('$dto', 'dd-mm-yyyy'))
                                AND b.i_company = '$this->i_company'
                            GROUP BY
                                a.i_product,
                                c.e_product_name )AS a
                    GROUP BY
                        a.i_product,
                        a.e_product_name ) AS b
                GROUP BY
                    b.i_product,
                    b.e_product_name /*-------------------------------------------tahun lalu-----------------------------------------------------------*/
            UNION ALL /*-------------------------------------------tahun lalu ----------------------------------------------------------*/
                SELECT
                    b.i_product,
                    0 AS oa,
                    sum(b.oaprev) AS oaprev,
                    0 AS ob,
                    b.e_product_name,
                    0 AS jml,
                    sum(b.jmlprev) AS jmlprev,
                    0 AS netitem,
                    sum(b.netitemprev) AS netitemprev
                FROM
                    (
                    SELECT
                        a.i_product,
                        count(a.i_customer) AS oaprev,
                        a.e_product_name,
                        0 AS jmlprev,
                        0 AS netitemprev
                    FROM
                        (
                        SELECT
                            DISTINCT ON
                            (to_char(b.d_nota, 'yyyymm'),
                            b.i_customer) a.i_product,
                            b.i_customer,
                            c.e_product_name,
                            0 AS jmlprev
                        FROM
                            tm_nota_item a,
                            tm_nota b,
                            tr_product c,
                            tr_customer d
                        WHERE
                            a.i_nota = b.i_nota
                            AND a.i_product = c.i_product
                            AND b.f_nota_cancel = 'false'
                            AND (b.d_nota >= to_date('$gabung1', 'dd-mm-yyyy')
                                AND b.d_nota <= to_date('$gabung2', 'dd-mm-yyyy'))
                                AND b.i_company = '$this->i_company'
                                AND b.i_customer = d.i_customer
                                AND b.i_area = d.i_area
                            GROUP BY
                                a.i_product,
                                b.i_customer,
                                c.e_product_name,
                                b.i_nota,
                                b.d_nota )AS a
                    GROUP BY
                        a.i_product,
                        a.e_product_name
                UNION ALL
                    SELECT
                        a.i_product,
                        0 AS oaprev,
                        a.e_product_name,
                        sum(a.jmlprev) AS jmlprev,
                        sum(a.netitemprev) AS netitemprev
                    FROM
                        (
                        SELECT
                            a.i_product,
                            c.e_product_name,
                            sum((a.n_deliver * a.v_unit_price-(((a.n_deliver * a.v_unit_price)/ b.v_nota_gross)* b.v_nota_discount))) AS netitemprev,
                            sum(a.n_deliver) AS jmlprev
                        FROM
                            tm_nota_item a,
                            tm_nota b,
                            tr_product c
                        WHERE
                            a.i_nota = b.i_nota
                            AND a.i_product = c.i_product
                            AND b.f_nota_cancel = 'false'
                            AND (b.d_nota >= to_date('$gabung1', 'dd-mm-yyyy')
                                AND b.d_nota <= to_date('$gabung2', 'dd-mm-yyyy'))
                                AND b.i_company = '$this->i_company'
                            GROUP BY
                                a.i_product,
                                c.e_product_name )AS a
                    GROUP BY
                        a.i_product,
                        a.e_product_name ) AS b
                GROUP BY
                    b.i_product,
                    b.e_product_name ) AS z
            INNER JOIN tr_product x ON
                (x.i_product = z.i_product)
            GROUP BY
                x.i_product_id,
                z.e_product_name
            ORDER BY
                ctrnetsales DESC,
                x.i_product_id
        ", FALSE);
    }
}

/* End of file Mmaster.php */
