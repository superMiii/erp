<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Minfospmb2 extends CI_Model
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

        $i_area = $this->input->post('i_area', TRUE);
        if ($i_area == '') {
            $i_area = $this->uri->segment(5);
        }

        if ($i_area != 'ALL') {
            $area = "AND a.i_area = '$i_area' ";
        } else {
            $area = "";
        }


        $dfrom  = date('Y-m-d', strtotime($dfrom));
        $dto    = date('Y-m-d', strtotime($dto));

        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("SELECT
                a.i_sr,
                a.i_company,
                a.i_sr_id,
                a.d_sr,
                b.i_area_id || ' - ' || initcap(b.e_area_name) as e_area_name,
                a.e_remark ,
                '$dfrom' AS dfrom,
                '$dto' AS dto,
                '$i_area' AS i_area
            from
                tm_sr a
            inner join tr_area b on	(b.i_area = a.i_area)
            where 
                a.d_sr BETWEEN '$dfrom' AND '$dto'
                AND a.i_company = '$this->i_company'
                $area
            order by 2
        ", FALSE);

        /** Cek Hak Akses, Apakah User Bisa Edit */
        $datatables->add('action', function ($data) {
            $i_company  = $data['i_company'];
            $dfrom      = $data['dfrom'];
            $dto        = $data['dto'];
            $i_area     = $data['i_area'];
            $i_sr       = $data['i_sr'];
            $data       = '';
            $data      .= "<a href='" . base_url() . $this->folder . '/view/' . encrypt_url($i_sr) . '/' . encrypt_url($i_company) . '/' . encrypt_url($dfrom) . '/' . encrypt_url($dto) . '/' . encrypt_url($i_area) . "' title='View Mutasi'><i class='fa fa-eye fa-lg warning darken-4 mr-1'></i></a>";
            return $data;
        });
        $datatables->hide('i_company');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('i_area');
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
    inner join tm_sr c on (c.i_area = a.i_area)
    WHERE 
        (e_area_name ILIKE '%$cari%' OR i_area_id ILIKE '%$cari%')
        AND a.i_company = '$this->i_company' 
        AND f_area_active = true
        AND b.i_user = '$this->i_user' 
        ", FALSE);
    }

    /**** List Mutasi ***/
    public function get_data_detail($i_sr, $i_company, $dfrom, $dto, $i_area)
    {

        $dfrom  = date('Y-m-d', strtotime($dfrom));
        $dto    = date('Y-m-d', strtotime($dto));

        return $this->db->query("SELECT
                i_sr_item,
                a.i_sr,
                c.i_area_id || ' - ' || initcap(c.e_area_name) as e_area_name,
                d.i_product_id ,
                d.e_product_name ,
                a.e_remark,
                n_order ,
                n_acc ,
                n_deliver 
            from
                tm_sr_item a
            inner join tm_sr b on (b.i_sr=a.i_sr)
            inner join tr_area c on (c.i_area=b.i_area)
            inner join tr_product d on (d.i_product=a.i_product)
            WHERE
                a.i_sr = '$i_sr'
            order by n_item_no desc 
        ", FALSE);
    }

    /** Get Data Untuk Export */
    public function get_data($dfrom, $dto, $i_area)
    {

        $dfrom  = date('Y-m-d', strtotime($dfrom));
        $dto    = date('Y-m-d', strtotime($dto));
        if ($i_area != 'ALL') {
            $area = "AND a.i_area = '$i_area' ";
        } else {
            $area = "";
        }
        return $this->db->query("SELECT
               a.i_sr,
                a.i_company,
                a.i_sr_id,
                a.d_sr,
                b.i_area_id || ' - ' || initcap(b.e_area_name) as e_area_name,
                a.e_remark ,
                '$dfrom' AS dfrom,
                '$dto' AS dto,
                '$i_area' AS i_area
            from
                tm_sr a
            inner join tr_area b on	(b.i_area = a.i_area)
            where 
                a.d_sr BETWEEN '$dfrom' AND '$dto'
                AND a.i_company = '$this->i_company'
                $area
            order by 2
        ", FALSE);
    }
}

/* End of file Mmaster.php */
