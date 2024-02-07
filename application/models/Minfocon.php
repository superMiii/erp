<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Minfocon extends CI_Model
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

        if ($i_product != 'ALL') {
            $pro = "AND b.i_product = '$i_product' ";
        } else {
            $pro = "";
        }

        $dfrom  = date('Y-m-d', strtotime($dfrom));
        $dto    = date('Y-m-d', strtotime($dto));
        $f_pusat = $this->session->f_pusat;

        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("SELECT
                    b.i_convertion as id,
                    b.i_convertion_id as code,
                    b.d_convertion as tgl,
	                b.i_product as i_product,
                    d.i_product_id as p1,
                    d.e_product_name p2,
                    b.n_convertion as p3,
                    c.i_product_id as q1,
                    c.e_product_name as q2,
                    a.n_convertion as q3
                from
                    tm_convertion_item a
                inner join tm_convertion b on (b.i_convertion=a.i_convertion)
                inner join tr_product c on (c.i_product=a.i_product)
                inner join tr_product d on (d.i_product=b.i_product)
                WHERE
                    b.i_company = '$this->i_company'
                    AND b.d_convertion BETWEEN '$dfrom' AND '$dto'
                    AND b.f_convertion_cancel = 'f'
                    $pro
                ORDER BY
                    b.i_convertion_id DESC ", FALSE);

                    $datatables->hide('i_product');
                    return $datatables->generate();
                }

    /** Get Area */
    public function get_pro($cari)
    {
        return $this->db->query("SELECT 
        DISTINCT
        a.i_product, 
        i_product_id, 
        initcap(b.e_product_name) AS e_product_name
    FROM 
        tm_convertion a
    inner join tr_product b on (b.i_product=a.i_product)
    WHERE 
        (b.e_product_name ILIKE '%$cari%' OR i_product_id ILIKE '%$cari%')
        AND a.i_company = '$this->i_company' 
        AND f_convertion_cancel = 'f'
    order by 3 asc
        ", FALSE);
    }


    /** Get Data Untuk Edit */
    public function get_data($dfrom, $dto, $i_product)
    {
        if ($i_product != 'ALL') {
            $pro = "AND b.i_product = '$i_product' ";
        } else {
            $pro = "";
        }
        $dfrom  = date('Y-m-d', strtotime($dfrom));
        $dto    = date('Y-m-d', strtotime($dto));
        $f_pusat = $this->session->f_pusat;
        return $this->db->query("SELECT
                     b.i_convertion as id,
                    b.i_convertion_id as code,
                    b.d_convertion as tgl,
	                b.i_product as i_product,
                    d.i_product_id as p1,
                    d.e_product_name p2,
                    b.n_convertion as p3,
                    c.i_product_id as q1,
                    c.e_product_name as q2,
                    a.n_convertion as q3
                from
                    tm_convertion_item a
                inner join tm_convertion b on (b.i_convertion=a.i_convertion)
                inner join tr_product c on (c.i_product=a.i_product)
                inner join tr_product d on (d.i_product=b.i_product)
                WHERE
                    b.i_company = '$this->i_company'
                    AND b.d_convertion BETWEEN '$dfrom' AND '$dto'
                    AND b.f_convertion_cancel = 'f'
                    $pro
                ORDER BY
                    b.i_convertion_id DESC ", FALSE);
    }
}

/* End of file Mmaster.php */
