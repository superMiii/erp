<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Minforinciansj extends CI_Model
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

        $i_product = $this->input->post('i_product', TRUE);
        if ($i_product == '') {
            $i_product = $this->uri->segment(5);
        }

        if ($i_product != 'NA') {
            $prod = "AND a.i_product = '$i_product' ";
        } else {
            $prod = "";
        }

        $i_customer = $this->input->post('i_customer', TRUE);
        if ($i_customer == '') {
            $i_customer = $this->uri->segment(6);
        }

        if ($i_customer != 'ALL') {
            $customer = "AND b.i_customer = '$i_customer' ";
        } else {
            $customer = "";
        }

        $pro = $this->input->post('pro', TRUE);
        if ($pro == '') {
            $pro = $this->uri->segment(7);
        }

        if ($pro == '3') {
            $epro = "AND b.f_so_stockdaerah = 't'";
        } elseif ($pro == '2') {
            $epro = "AND b.f_so_stockdaerah = 'f'";
        } else {
            $epro = "";
        }

        $dfrom  = date('Y-m-d', strtotime($dfrom));
        $dto    = date('Y-m-d', strtotime($dto));

        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("SELECT
                    a.i_do_item as id,
                    b.i_do_id ,
                    to_char(b.d_do, 'DD FMMonth YYYY') AS tgl,
                    case when b.f_so_stockdaerah='f' then 'PUSAT' else 'CABANG' end as pemenuhan ,
                    d.i_customer_id || ' - ' || d.e_customer_name as namee,
                    c.i_product_id ,
                    a.e_product_name ,
                    a.n_deliver 
                from
                    tm_do_item a 
                inner join tm_do b on (b.i_do =a.i_do )
                inner join tr_product c on (c.i_product =a.i_product )
                inner join tr_customer d on (d.i_customer =b.i_customer )
                INNER JOIN tm_user_area g ON
                    (g.i_area = d.i_area  and g.i_user = '$this->i_user')
            WHERE
                b.f_do_cancel = 'f'
                AND b.i_company = '$this->i_company'
                AND b.d_do BETWEEN '$dfrom' AND '$dto'
                $prod
                $customer
                $epro
            ORDER BY
                1 ASC
        ", FALSE);
        return $datatables->generate();
    }

    /** Get Area */
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

    /** Get Customer */
    public function get_customer($cari, $i_product)
    {
        return $this->db->query("SELECT DISTINCT
                a.i_customer, i_customer_id , e_customer_name
            FROM 
                tr_customer a
            INNER JOIN tm_user_area g ON
                (g.i_area = a.i_area  and g.i_user = '$this->i_user')
            WHERE 
                (e_customer_name ILIKE '%$cari%' OR i_customer_id ILIKE '%$cari%')
                AND a.i_company = '$this->i_company' 
                AND f_customer_active = 'true' 
            ORDER BY 3 ASC
        ", FALSE);
    }

    /** Get Data Untuk Edit */
    public function get_data($dfrom, $dto, $i_product, $i_customer, $pro)
    {
        if ($i_product != 'NA') {
            $prod = "AND a.i_product = '$i_product' ";
        } else {
            $prod = "";
        }
        if ($i_customer != 'ALL') {
            $customer = "AND b.i_customer = '$i_customer' ";
        } else {
            $customer = "";
        }
        if ($pro == '3') {
            $epro = "AND b.f_so_stockdaerah = 't'";
        } elseif ($pro == '2') {
            $epro = "AND b.f_so_stockdaerah = 'f'";
        } else {
            $epro = "";
        }

        $dfrom  = date('Y-m-d', strtotime($dfrom));
        $dto    = date('Y-m-d', strtotime($dto));
        return $this->db->query("SELECT
                        a.i_do_item as id,
                        b.i_do_id ,
                        to_char(b.d_do, 'DD FMMonth YYYY') AS tgl,
                        case when b.f_so_stockdaerah='f' then 'PUSAT' else 'CABANG' end as pemenuhan ,
                        d.i_customer_id || ' - ' || d.e_customer_name as namee,
                        c.i_product_id ,
                        a.e_product_name ,
                        a.n_deliver 
                    from
                        tm_do_item a 
                    inner join tm_do b on (b.i_do =a.i_do )
                    inner join tr_product c on (c.i_product =a.i_product )
                    inner join tr_customer d on (d.i_customer =b.i_customer )
                    INNER JOIN tm_user_area g ON
                        (g.i_area = d.i_area and g.i_user = '$this->i_user')
                WHERE
                    b.f_do_cancel = 'f'
                    AND b.i_company = '$this->i_company'
                    AND b.d_do BETWEEN '$dfrom' AND '$dto'
                $prod
                $customer
                $epro
                ORDER BY
                    1 ASC
        ", FALSE);
    }
}

/* End of file Mmaster.php */
