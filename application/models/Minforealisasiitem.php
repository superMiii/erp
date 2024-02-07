<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Minforealisasiitem extends CI_Model
{

    /** List Datatable */
    public function serverside()
    {
        $dfrom = $this->input->post('dfrom', TRUE);
        if ($dfrom == '') {
            $dfrom = $this->uri->segment(3);
        }

        $dto = $this->input->post('dto', TRUE);
        if ($dto == '') {
            $dto = $this->uri->segment(4);
        }

        $i_area = $this->input->post('i_area', TRUE);
        if ($i_area == '') {
            $i_area = $this->uri->segment(5);
        }

        if ($i_area != 'ALL') {
            $area = "AND a.i_area = '$i_area' ";
        } else {
            $area = "";
        }

        $i_product = $this->input->post('i_product', TRUE);
        if ($i_product == '') {
            $i_product = $this->uri->segment(6);
        }

        if ($i_product != 'NA') {
            $prod = "AND ii.i_product = '$i_product' ";
        } else {
            $prod = "";
        }

        $dfrom  = date('Y-m-d', strtotime($dfrom));
        $dto    = date('Y-m-d', strtotime($dto));
        $f_pusat = $this->session->f_pusat;

        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("SELECT
                    a.i_so as id,
                    a.i_so_id,
                    c.i_customer_id || ' ~ ' || initcap(c.e_customer_name) as e_customer_name,
                    initcap(d.e_area_name) as e_area_name,
                    initcap(b.e_salesman_name) as e_salesman_name,
                    case
                        when a.f_so_stockdaerah = 't' then 'CABANG'
                        else 'PUSAT'
                    end as e_pemenuhan,
                    ii.i_product ,
                    ie.i_product_id,
                    ie.e_product_name ,
                    ii.n_order 
                FROM
                    tm_so a
                    inner join tr_salesman b on	(b.i_salesman = a.i_salesman)
                    inner join tr_customer c on	(c.i_customer = a.i_customer)
                    inner join tr_area d on	(d.i_area = a.i_area)
                    inner join tm_user_area f on (f.i_area = a.i_area)
                    inner join tm_so_item ii on (ii.i_so=a.i_so)
                    inner join tr_product ie on (ie.i_product=ii.i_product)
                WHERE
                    f.i_user = '$this->i_user'
                    AND a.i_company = '$this->i_company'
                    AND a.d_so BETWEEN '$dfrom' AND '$dto'
                    AND a.i_status_so = '3'
                    AND a.f_so_cancel = 'f'
                    AND c.d_approve notnull
                    And case when '$f_pusat' = 'f' then a.f_so_stockdaerah='t' else a.f_so_stockdaerah='f' end
                    $area
                    $prod
                ORDER BY
                    1,7 DESC
                            ", FALSE);

                    $datatables->hide('i_product');
                    return $datatables->generate();
                }

    /** Get Area */
    public function get_area($cari)
    {
        return $this->db->query("SELECT 
        DISTINCT
        a.i_area, 
        i_area_id, 
        initcap(e_area_name) AS e_area_name
    FROM 
        tr_area a
    INNER JOIN tm_user_area b 
        ON (b.i_area = a.i_area) 
    inner join tm_do c on (c.i_area = a.i_area)
    WHERE 
        (e_area_name ILIKE '%$cari%' OR i_area_id ILIKE '%$cari%')
        AND a.i_company = '$this->i_company' 
        AND f_area_active = true
        AND b.i_user = '$this->i_user' 
        ", FALSE);
    }

    public function get_prod($cari)
    {
        return $this->db->query("SELECT 
                i_product, 
                i_product_id, 
                e_product_name
            FROM 
                tr_product
            WHERE 
                (e_product_name ILIKE '%$cari%' OR i_product_id ILIKE '%$cari%')
                AND i_company = '$this->i_company' 
                AND f_product_active = true
            ORDER BY 3 ASC
        ", FALSE);
    }


    /** Get Data Untuk Edit */
    public function get_data($dfrom, $dto, $i_area, $i_product)
    {
        if ($i_area != 'ALL') {
            $area = "AND a.i_area = '$i_area' ";
        } else {
            $area = "";
        }

        if ($i_product != 'NA') {
            $prod = "AND ii.i_product = '$i_product' ";
        } else {
            $prod = "";
        }

        $dfrom  = date('Y-m-d', strtotime($dfrom));
        $dto    = date('Y-m-d', strtotime($dto));
        $f_pusat = $this->session->f_pusat;
        return $this->db->query("SELECT
                        a.i_so as id,
                        a.i_so_id,
                        c.i_customer_id || ' ~ ' || initcap(c.e_customer_name) as e_customer_name,
                        initcap(d.e_area_name) as e_area_name,
                        initcap(b.e_salesman_name) as e_salesman_name,
                        case
                            when a.f_so_stockdaerah = 't' then 'CABANG'
                            else 'PUSAT'
                        end as e_pemenuhan,
                        ii.i_product ,
                        ie.i_product_id,
                        ie.e_product_name ,
                        ii.n_order 
                    FROM
                        tm_so a
                        inner join tr_salesman b on	(b.i_salesman = a.i_salesman)
                        inner join tr_customer c on	(c.i_customer = a.i_customer)
                        inner join tr_area d on	(d.i_area = a.i_area)
                        inner join tm_user_area f on (f.i_area = a.i_area)
                        inner join tm_so_item ii on (ii.i_so=a.i_so)
                        inner join tr_product ie on (ie.i_product=ii.i_product)
                    WHERE
                        f.i_user = '$this->i_user'
                        AND a.i_company = '$this->i_company'
                        AND a.d_so BETWEEN '$dfrom' AND '$dto'
                        AND a.i_status_so = '3'
                        AND a.f_so_cancel = 'f'
                        AND c.d_approve notnull
                        And case when '$f_pusat' = 'f' then a.f_so_stockdaerah='t' else a.f_so_stockdaerah='f' end
                        $area
                        $prod
                    ORDER BY
                        a.i_so DESC
                    ", FALSE);
    }
}

/* End of file Mmaster.php */
