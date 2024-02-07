<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Minfoso extends CI_Model
{
    public function serverside()
    {
        // $dfrom = $this->input->post('dfrom', TRUE);
        // if ($dfrom == '') {
        //     $dfrom = $this->uri->segment(3);
        // }

        // $dto = $this->input->post('dto', TRUE);
        // if ($dto == '') {
        //     $dto = $this->uri->segment(4);
        // }

        $i_stockopname = $this->input->post('i_stockopname', TRUE);
        if ($i_stockopname == '') {
            $i_stockopname = $this->uri->segment(5);
        }

        if ($i_stockopname != '0') {
            $so = "a.i_stockopname = '$i_stockopname' ";
        } else {
            $so = "a.i_stockopname = '0'";
        }

        // $dfrom  = date('Y-m-d', strtotime($dfrom));
        // $dto    = date('Y-m-d', strtotime($dto));

        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("select
                a.i_stockopname,
                d.i_product_id,
                d.e_product_name,
                b.e_product_motifname,
                c.e_product_gradename,
                n_stockopname 
            from
                tm_stockopname_item a
            inner join tr_product_motif b on
                (b.i_product_motif = a.i_product_motif)
            inner join tr_product_grade c on
                (c.i_product_grade = a.i_product_grade)
            inner join tr_product d on
                (d.i_product = a.i_product)
            where
                $so
            order by
                1 asc
        ", FALSE);
        return $datatables->generate();
    }

    public function get_so($cari)
    {
        return $this->db->query("SELECT 
                c.e_area_name,
                a.*
            FROM 
                tm_stockopname a                
            inner join tr_area c on (a.i_area=c.i_area)
            INNER JOIN tm_user_area b 
                ON (b.i_area = a.i_area) 
            WHERE 
                (i_stockopname_id ILIKE '%$cari%')
                AND a.i_company = '$this->i_company' 
                AND f_stockopname_cancel = false
                AND b.i_user = '$this->i_user' 
            ORDER BY 4 ASC
        ", FALSE);
    }

    /** Get Data Untuk Edit */
    public function get_data($i_stockopname)
    {
        if ($i_stockopname != '0') {
            $so = "a.i_stockopname = '$i_stockopname' ";
        } else {
            $so = "a.i_stockopname = '0'";
        }
        // $dfrom  = date('Y-m-d', strtotime($dfrom));
        // $dto    = date('Y-m-d', strtotime($dto));
        return $this->db->query("SELECT
            a.i_stockopname,
                d.i_product_id,
                d.e_product_name,
                b.e_product_motifname,
                c.e_product_gradename,
                n_stockopname ,
                i_stockopname_item
            from
                tm_stockopname_item a
            inner join tr_product_motif b on
                (b.i_product_motif = a.i_product_motif)
            inner join tr_product_grade c on
                (c.i_product_grade = a.i_product_grade)
            inner join tr_product d on
                (d.i_product = a.i_product)
            where
                $so
            order by
                1 asc
        ", FALSE);
    }
}

/* End of file Mmaster.php */
