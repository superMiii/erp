<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Minfogs2 extends CI_Model
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

        $i_store = $this->input->post('i_store', TRUE);
        if ($i_store == '') {
            $i_store = $this->uri->segment(5);
        }

        $i_product = $this->input->post('i_product', TRUE);
        if ($i_product == '') {
            $i_product = $this->uri->segment(6);
        }

        if ($i_store != '0') {
            $store = "AND a.i_store = '$i_store' ";
        } else {
            $store = "";
        }

        if ($i_product != 'ALL') {
            $pro = "AND k.i_product = '$i_product' ";
        } else {
            $pro = "";
        }

        $dfrom  = date('Y-m-d', strtotime($dfrom));
        $dto    = date('Y-m-d', strtotime($dto));
        $f_pusat = $this->session->f_pusat;

        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("SELECT 
                        a.i_gs ,
                        a.i_gs_id ,
                        a.d_gs ,
                        a.d_gs_receive ,
                        aa.e_store_loc_name ,
                        n.i_product_id ,
                        n.e_product_name ,
                        k.n_quantity_deliver ,
                        k.n_quantity_receive ,
                        a.e_remark 
                    from
                        tm_gs_item2 k
                    inner join tm_gs2 a on (k.i_gs=a.i_gs)
                    inner join tr_product n on (n.i_product=k.i_product)
                    inner join tr_store_loc aa on (a.i_store_loc=aa.i_store_loc)
                    INNER JOIN tm_user_store g ON (g.i_store = a.i_store and g.i_user = '$this->i_user')
                WHERE
                    a.i_company = '$this->i_company'
                    AND a.d_gs BETWEEN '$dfrom' AND '$dto'
                    AND a.f_gs_cancel = 'f'
                    $pro
                    $store
                ORDER BY
                    1 ", FALSE);

                    return $datatables->generate();
                }


    /** Get Area */
    public function get_store0($cari)
    {
        return $this->db->query("SELECT 
        DISTINCT
        a.i_store, 
        i_store_id, 
        initcap(e_store_name) AS e_store_name
    FROM 
        tr_store a
    INNER JOIN tr_area b 
        ON (b.i_store = a.i_store) 
    INNER JOIN tm_user_area c 
        ON (b.i_area = c.i_area) 
    WHERE 
        (e_store_name ILIKE '%$cari%' OR i_store_id ILIKE '%$cari%')
        AND a.i_company = '$this->i_company' 
        AND f_store_active = true
        AND c.i_user = '$this->i_user' 
    ORDER BY 3 ASC
        ", FALSE);
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
        tm_adjustment_item a
    inner join tr_product b on (b.i_product=a.i_product)
    WHERE 
        (b.e_product_name ILIKE '%$cari%' OR i_product_id ILIKE '%$cari%')
        AND b.i_company = '$this->i_company'
    order by 3 asc
        ", FALSE);
    }


    /** Get Data Untuk Edit */
    public function get_data($dfrom, $dto, $i_product,$i_store)
    {

        if ($i_store != '0') {
            $store = "AND a.i_store = '$i_store' ";
        } else {
            $store = "";
        }

        if ($i_product != 'ALL') {
            $pro = "AND k.i_product = '$i_product' ";
        } else {
            $pro = "";
        }
        $dfrom  = date('Y-m-d', strtotime($dfrom));
        $dto    = date('Y-m-d', strtotime($dto));
        $f_pusat = $this->session->f_pusat;
        return $this->db->query("SELECT 
                            a.i_gs ,
                            a.i_gs_id ,
                            a.d_gs ,
                            a.d_gs_receive ,
                            aa.e_store_loc_name ,
                            n.i_product_id ,
                            n.e_product_name ,
                            k.n_quantity_deliver ,
                            k.n_quantity_receive ,
                            a.e_remark 
                        from
                            tm_gs_item2 k
                        inner join tm_gs2 a on (k.i_gs=a.i_gs)
                        inner join tr_product n on (n.i_product=k.i_product)
                        inner join tr_store_loc aa on (a.i_store_loc=aa.i_store_loc)
                        INNER JOIN tm_user_store g ON (g.i_store = a.i_store and g.i_user = '$this->i_user')
                    WHERE
                        a.i_company = '$this->i_company'
                        AND a.d_gs BETWEEN '$dfrom' AND '$dto'
                        AND a.f_gs_cancel = 'f'
                    $pro
                    $store
                    ORDER BY
                    1 ", FALSE);
    }
}

/* End of file Mmaster.php */
