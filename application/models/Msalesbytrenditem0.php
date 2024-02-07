<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Msalesbytrenditem extends CI_Model
{

    /** List Datatable */
    public function serverside($dfrom,$dto)
    {
        if($dfrom!=''){
            $tmp=explode("-",$dfrom);
            $th=$tmp[2];
            $bl=$tmp[1];
            $hr=$tmp[0];
            $dfrom =$hr."-".$bl."-".$th;
            $thdfromkurang=$th-1;
            $dfromsebelumnya =$hr."-".$bl."-".$thdfromkurang;
            $thskrng=$th;
        }

        if($dto!=''){
            $tmp=explode("-",$dto);
            $th=$tmp[2];
            $bl=$tmp[1];
            $hr=$tmp[0];
            $dto =$hr."-".$bl."-".$th;
            $thdtokurang=$th-1;
            if((intval($thdtokurang)%4!=0)&&($bl=='02')&&($hr=='29')) $hr='28';
            $dtosebelumnya =$hr."-".$bl."-".$thdtokurang;
            $thnsebelumnya=$th-1;
        }
        return $this->db->query("xSELECT
                a.i_area,
                initcap(a.e_area_name) AS e_area_name,
                initcap(a.e_customer_name) AS e_customer_name,
                a.i_customer_id,
                initcap(a.e_customer_typename) AS e_customer_typename,
                a.i_product_id,
                initcap(a.e_product_name) AS e_product_name,
                count(ob) AS ob,
                sum(a.vnota) AS vnota,
                sum(a.qnota) AS qnota,
                sum(a.oa) AS oa,
                sum(a.prevvnota) AS prevvnota,
                sum(a.prevqnota) AS prevqnota,
                sum(a.prevoa) AS prevoa
            FROM
                (
                SELECT
                    a.i_area,
                    f.e_area_name,
                    c.e_customer_name,
                    c.i_customer_id,
                    z.e_customer_typename,
                    x.i_product_id,
                    b.e_product_name,
                    count(c.i_customer_id) AS ob,
                    sum(v_nota_netto) AS vnota,
                    0 AS qnota,
                    0 AS oa,
                    0 AS prevvnota,
                    0 AS prevqnota,
                    0 AS prevoa
                FROM
                    tm_nota a,
                    tm_nota_item b,
                    tr_customer c,
                    tr_area f,
                    tr_customer_type z,
                    tr_product x
                WHERE
                    (a.d_nota >= to_date('$dfrom', 'dd-mm-yyyy')
                        AND a.d_nota <= to_date('$dto', 'dd-mm-yyyy'))
                    AND a.f_nota_cancel = 'f'
                    AND a.i_nota = b.i_nota
                    AND f.i_area = a.i_area
                    AND a.i_customer = c.i_customer
                    AND c.i_customer_type = z.i_customer_type
                    AND a.i_company = '$this->i_company'
                    AND b.i_product = x.i_product
                GROUP BY
                    c.e_customer_name,
                    c.i_customer_id,
                    a.i_area,
                    f.e_area_name,
                    z.e_customer_typename,
                    x.i_product_id,
                    b.e_product_name
            UNION ALL
                SELECT
                    a.i_area,
                    f.e_area_name,
                    c.e_customer_name,
                    c.i_customer_id,
                    z.e_customer_typename,
                    x.i_product_id,
                    b.e_product_name,
                    0 AS ob,
                    0 AS vnota,
                    sum(b.n_deliver) AS qnota,
                    0 AS oa,
                    0 AS prevvnota,
                    0 AS prevqnota,
                    0 AS prevoa
                FROM
                    tm_nota a,
                    tm_nota_item b,
                    tr_customer c,
                    tr_area f,
                    tr_customer_type z,
                    tr_product x
                WHERE
                    (a.d_nota >= to_date('$dfrom', 'dd-mm-yyyy')
                        AND a.d_nota <= to_date('$dto', 'dd-mm-yyyy'))
                    AND a.f_nota_cancel = 'f'
                    AND a.i_nota = b.i_nota
                    AND f.i_area = a.i_area
                    AND a.i_customer = c.i_customer
                    AND c.i_customer_type = z.i_customer_type
                    AND a.i_company = '$this->i_company'
                    AND b.i_product = x.i_product
                GROUP BY
                    c.e_customer_name,
                    c.i_customer_id,
                    a.i_area,
                    f.e_area_name,
                    z.e_customer_typename,
                    x.i_product_id,
                    b.e_product_name
            UNION ALL
                SELECT
                    b.i_area,
                    b.e_area_name,
                    b.e_customer_name,
                    b.i_customer_id,
                    b.e_customer_typename,
                    b.i_product_id,
                    b.e_product_name,
                    0 AS ob,
                    0 AS vnota,
                    0 AS qnota,
                    count(b.i_customer_id) AS oa,
                    0 AS prevvnota,
                    0 AS prevqnota,
                    0 AS prevoa
                FROM
                    (
                    SELECT
                        DISTINCT ON
                        (to_char(a.d_nota, 'yyyymm'),
                        c.i_customer_id) c.i_customer_id,
                        z.e_customer_typename,
                        a.i_area,
                        f.e_area_name,
                        c.e_customer_name,
                        x.i_product_id,
                        b.e_product_name
                    FROM
                        tm_nota a,
                        tm_nota_item b,
                        tr_customer c,
                        tr_area f,
                        tr_customer_type z,
                        tr_product x
                    WHERE
                        (a.d_nota >= to_date('$dfrom', 'dd-mm-yyyy')
                            AND a.d_nota <= to_date('$dto', 'dd-mm-yyyy'))
                            AND a.f_nota_cancel = 'f'
                            AND f.i_area = a.i_area
                            AND a.i_customer = c.i_customer
                            AND b.i_product = x.i_product
                            AND c.i_customer_type = z.i_customer_type
                            AND a.i_company = '$this->i_company'
                        GROUP BY
                            c.e_customer_name,
                            c.i_customer_id,
                            a.i_area,
                            f.e_area_name,
                            a.d_nota,
                            z.e_customer_typename,
                            x.i_product_id,
                            b.e_product_name ) AS b
                GROUP BY
                    b.e_customer_name,
                    b.i_customer_id,
                    b.i_area,
                    b.e_area_name,
                    b.e_customer_typename,
                    b.i_product_id,
                    b.e_product_name
            UNION ALL /*--nah ini thnsebelumnya dibawah--*/
                SELECT
                    a.i_area,
                    f.e_area_name,
                    c.e_customer_name,
                    c.i_customer_id,
                    z.e_customer_typename,
                    x.i_product_id,
                    b.e_product_name,
                    count(c.i_customer_id) AS ob,
                    0 AS vnota,
                    0 AS qnota,
                    0 AS oa,
                    sum(v_nota_netto) AS prevvnota,
                    0 AS prevqnota,
                    0 AS prevoa
                FROM
                    tm_nota a,
                    tm_nota_item b,
                    tr_customer c,
                    tr_area f,
                    tr_customer_type z,
                    tr_product x
                WHERE
                    (a.d_nota >= to_date('$dfromsebelumnya', 'dd-mm-yyyy')
                        AND a.d_nota <= to_date('$dtosebelumnya', 'dd-mm-yyyy'))
                    AND a.f_nota_cancel = 'f'
                    AND f.i_area = a.i_area
                    AND a.i_customer = c.i_customer
                    AND c.i_customer_type = z.i_customer_type
                    AND a.i_company = '$this->i_company'
                    AND b.i_product = x.i_product
                GROUP BY
                    c.e_customer_name,
                    c.i_customer_id,
                    a.i_area,
                    f.e_area_name,
                    z.e_customer_typename,
                    x.i_product_id,
                    b.e_product_name
            UNION ALL
                SELECT
                    a.i_area,
                    f.e_area_name,
                    c.e_customer_name,
                    c.i_customer_id,
                    z.e_customer_typename,
                    x.i_product_id,
                    b.e_product_name,
                    0 AS ob,
                    0 AS vnota,
                    0 AS qnota,
                    0 AS oa,
                    0 AS prevvnota,
                    sum(b.n_deliver) AS prevqnota,
                    0 AS prevoa
                FROM
                    tm_nota a,
                    tm_nota_item b,
                    tr_customer c,
                    tr_area f,
                    tr_customer_type z,
                    tr_product x
                WHERE
                    (a.d_nota >= to_date('$dfromsebelumnya', 'dd-mm-yyyy')
                        AND a.d_nota <= to_date('$dtosebelumnya', 'dd-mm-yyyy'))
                    AND a.f_nota_cancel = 'f'
                    AND a.i_nota = b.i_nota
                    AND f.i_area = a.i_area
                    AND a.i_customer = c.i_customer
                    AND c.i_customer_type = z.i_customer_type
                    AND a.i_company = '$this->i_company'
                    AND b.i_product = x.i_product
                GROUP BY
                    to_char(a.d_nota, 'yyyy'),
                    c.e_customer_name,
                    c.i_customer_id,
                    a.i_area,
                    f.e_area_name,
                    z.e_customer_typename,
                    x.i_product_id,
                    b.e_product_name
            UNION ALL
                SELECT
                    b.i_area,
                    b.e_area_name,
                    b.e_customer_name,
                    b.i_customer_id,
                    b.e_customer_typename,
                    b.i_product_id,
                    b.e_product_name,
                    0 AS ob,
                    0 AS vnota,
                    0 AS qnota,
                    0 AS oa,
                    0 AS prevvnota,
                    0 AS prevqnota,
                    count(b.i_customer_id) AS prevoa
                FROM
                    (
                    SELECT
                        DISTINCT ON
                        (to_char(a.d_nota, 'yyyymm'),
                        c.i_customer_id) c.i_customer_id,
                        z.e_customer_typename,
                        a.i_area,
                        f.e_area_name,
                        c.e_customer_name,
                        x.i_product_id,
                        b.e_product_name
                    FROM
                        tm_nota a,
                        tm_nota_item b,
                        tr_customer c,
                        tr_area f,
                        tr_customer_type z,
                        tr_product x
                    WHERE
                        (a.d_nota >= to_date('$dfromsebelumnya', 'dd-mm-yyyy')
                            AND a.d_nota <= to_date('$dtosebelumnya', 'dd-mm-yyyy'))
                            AND a.f_nota_cancel = 'f'
                            AND f.i_area = a.i_area
                            AND a.i_customer = c.i_customer
                            AND b.i_product = x.i_product
                            AND c.i_customer_type = z.i_customer_type
                            AND a.i_company = '$this->i_company'
                        GROUP BY
                            c.e_customer_name,
                            c.i_customer_id,
                            a.i_area,
                            f.e_area_name,
                            a.d_nota,
                            z.e_customer_typename,
                            x.i_product_id,
                            b.e_product_name ) AS b
                GROUP BY
                    b.e_customer_name,
                    b.i_customer_id,
                    b.i_area,
                    b.e_area_name,
                    b.e_customer_typename,
                    b.i_product_id,
                    b.e_product_name) AS a
            GROUP BY
                a.i_area,
                a.e_area_name,
                a.e_customer_name,
                a.i_customer_id,
                a.e_customer_typename,
                a.i_product_id,
                a.e_product_name
            ORDER BY
                a.i_area,
                a.i_customer_id,
                a.i_product_id
        ", FALSE);
    }
}

/* End of file Mmaster.php */
