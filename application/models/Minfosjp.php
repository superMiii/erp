<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Minfosjp extends CI_Model
{

    /**** List Datatable ***/
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

        if ($i_store != 'ALL') {
            $store = "AND a.i_store = '$i_store' ";
        } else {
            $store = "";
        }


        $dfrom  = date('Y-m-d', strtotime($dfrom));
        $dto    = date('Y-m-d', strtotime($dto));

        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("SELECT
                a.i_gs,
                a.i_company,
                a.i_gs_id,
                a.d_gs,
                a.d_gs_receive ,
                b.i_area_id || ' - ' || initcap(b.e_area_name) as e_area_name,
                c.i_sr_id ,
                c.d_sr ,
                d.e_store_name ,
                a.v_gs::money as v_gs ,
                a.v_gs_receive::money as v_gs_receive,
                '$dfrom' AS dfrom,
                '$dto' AS dto,
                '$i_store' AS i_store
            from
                tm_gs a
            inner join tr_area b on	(b.i_area = a.i_area)
            inner join tm_sr c on (c.i_sr=a.i_sr)
            inner join tr_store d on (d.i_store=a.i_store)
            where 
                a.d_gs BETWEEN '$dfrom' AND '$dto'
                AND a.i_company = '$this->i_company'
                $store
            order by 2
        ", FALSE);

        /** Cek Hak Akses, Apakah User Bisa Edit */
        $datatables->add('action', function ($data) {
            $i_company  = $data['i_company'];
            $dfrom      = $data['dfrom'];
            $dto        = $data['dto'];
            $i_store    = $data['i_store'];
            $i_gs  = $data['i_gs'];
            $data       = '';
            $data      .= "<a href='" . base_url() . $this->folder . '/view/' . encrypt_url($i_gs) . '/' . encrypt_url($i_company) . '/' . encrypt_url($dfrom) . '/' . encrypt_url($dto) . '/' . encrypt_url($i_store) . "' title='View Mutasi'><i class='fa fa-eye fa-lg warning darken-4 mr-1'></i></a>";
            return $data;
        });
        $datatables->hide('i_company');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('i_store');
        return $datatables->generate();
    }

    /** Get Area */
    public function get_store($cari)
    {
        return $this->db->query("select distinct 
                a.*
            from
                tr_store a
            inner join tm_gs b on
                (b.i_store = a.i_store)
                order by 2
        ", FALSE);
    }

    /**** List Mutasi ***/
    public function get_data_detail($i_gs, $i_company, $dfrom, $dto, $i_store)
    {

        $dfrom  = date('Y-m-d', strtotime($dfrom));
        $dto    = date('Y-m-d', strtotime($dto));

        return $this->db->query("SELECT
                a.i_gs ,
                a.i_gs_item ,
                a.i_gs_item_id ,
                E.i_area_id || ' - ' || E.e_area_name AS e_area,
                c.i_product_id ,
                c.e_product_name,
                d.e_product_motifname ,
                a.n_quantity_order ,
                a.n_quantity_deliver ,
                a.n_quantity_receive
            from
                tm_gs_item a
            inner join tm_gs b on (b.i_gs=a.i_gs)
            inner join tr_product c on (c.i_product=a.i_product)
            inner join tr_product_motif d on (d.i_product_motif=a.i_product_motif)
            INNER JOIN TR_AREA E ON (E.I_AREA = B.I_AREA)
            WHERE
                a.i_gs = '$i_gs'
            order by n_item_no desc 
        ", FALSE);
    }

    /** Get Data Untuk Export */
    public function get_data($dfrom, $dto, $i_store)
    {

        $dfrom  = date('Y-m-d', strtotime($dfrom));
        $dto    = date('Y-m-d', strtotime($dto));
        if ($i_store != 'ALL') {
            $store = "AND a.i_store = '$i_store' ";
        } else {
            $store = "";
        }
        return $this->db->query("SELECT
                a.i_gs,
                a.i_company,
                a.i_gs_id,
                a.d_gs,
                a.d_gs_receive ,
                b.i_area_id || ' - ' || initcap(b.e_area_name) as e_area_name,
                c.i_sr_id ,
                c.d_sr ,
                d.e_store_name ,
                a.v_gs as v_gs ,
                a.v_gs_receive as v_gs_receive,
                '$dfrom' AS dfrom,
                '$dto' AS dto,
                '$i_store' AS i_store
            from
                tm_gs a
            inner join tr_area b on	(b.i_area = a.i_area)
            inner join tm_sr c on (c.i_sr=a.i_sr)
            inner join tr_store d on (d.i_store=a.i_store)
            where 
                a.d_gs BETWEEN '$dfrom' AND '$dto'
                AND a.i_company = '$this->i_company'
                $store
            order by 2
        ", FALSE);
    }
}

/* End of file Mmaster.php */
