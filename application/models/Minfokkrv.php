<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Minfokkrv extends CI_Model
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

        if ($i_area != 'NA') {
            $area = "AND b.i_area = '$i_area' ";
        } else {
            $area = "";
        }

        $i_coa = $this->input->post('i_coa', TRUE);
        if ($i_coa == '') {
            $i_coa = $this->uri->segment(6);
        }

        if ($i_coa != 'ALL') {
            $coa = "AND a.i_coa = '$i_coa' ";
        } else {
            $coa = "";
        }

        $dfrom  = date('Y-m-d', strtotime($dfrom));
        $dto    = date('Y-m-d', strtotime($dto));

        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("SELECT DISTINCT
                    a.i_rv_item ,
                    b.i_rv ,
                    b.i_rv_id ,
                    b.d_rv ,
                    d.i_area_id || ' - ' || d.e_area_name as e_area_name ,
                    a.e_coa_name ,
                    a.e_remark ,
                    a.v_rv::money as v_rv
                from
                    tm_rv_item a
                inner join tm_rv b on (b.i_rv=a.i_rv)
                inner join tr_coa c on (c.i_coa=a.i_coa and c.f_kas_kecil='t')
                inner join tr_area d on (d.i_area=b.i_area)
                INNER JOIN tm_user_area g ON (g.i_area = d.i_area  and g.i_user = '$this->i_user')
                inner join tr_rv_type r on (r.i_rv_type=b.i_rv_type and r.i_rv_type_id='KK')
                WHERE
                b.f_rv_cancel = 'f'
                AND b.i_company = '$this->i_company'
                AND b.d_rv BETWEEN '$dfrom' AND '$dto'
                $area
                $coa
                ORDER BY
                    3 ASC
                    ", FALSE);
        $datatables->hide('i_rv_item');
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
            inner join tm_rv d on (d.i_area = a.i_area)
            inner join tr_coa e on (e.i_coa=d.i_coa and e.f_kas_kecil='t')
            WHERE 
                (e_area_name ILIKE '%$cari%' OR i_area_id ILIKE '%$cari%')
                AND a.i_company = '$this->i_company' 
                AND f_area_active = true
                AND b.i_user = '$this->i_user' 
            ORDER BY 3 ASC
        ", FALSE);
    }

    /** Get Customer */
    public function get_coa($cari)
    {
        return $this->db->query("SELECT DISTINCT
                i_coa, i_coa_id , e_coa_name
            FROM 
                tr_coa
            WHERE 
                (e_coa_name ILIKE '%$cari%' OR i_coa_id ILIKE '%$cari%')
                AND i_company = '$this->i_company' 
                AND f_kas_kecil = 'true' 
            ORDER BY 3 ASC
        ", FALSE);
    }

    /** Get Data Untuk Edit */
    public function get_data($dfrom, $dto, $i_area, $i_coa)
    {
        if ($i_area != 'NA') {
            $area = "AND b.i_area = '$i_area' ";
        } else {
            $area = "";
        }

        if ($i_coa != 'ALL') {
            $coa = "AND a.i_coa = '$i_coa' ";
        } else {
            $coa = "";
        }

        $dfrom  = date('Y-m-d', strtotime($dfrom));
        $dto    = date('Y-m-d', strtotime($dto));
        return $this->db->query("SELECT DISTINCT
                    a.i_rv_item ,
                    b.i_rv ,
                    b.i_rv_id ,
                    b.d_rv ,
                    d.i_area_id || ' - ' || d.e_area_name as e_area_name ,
                    a.e_coa_name ,
                    a.e_remark ,
                    a.v_rv as v_rv
                from
                    tm_rv_item a
                inner join tm_rv b on (b.i_rv=a.i_rv)
                inner join tr_coa c on (c.i_coa=a.i_coa and c.f_kas_kecil='t')
                inner join tr_area d on (d.i_area=b.i_area)
                INNER JOIN tm_user_area g ON (g.i_area = d.i_area  and g.i_user = '$this->i_user')
                inner join tr_rv_type r on (r.i_rv_type=b.i_rv_type and r.i_rv_type_id='KK')
                WHERE
                b.f_rv_cancel = 'f'
                AND b.i_company = '$this->i_company'
                AND b.d_rv BETWEEN '$dfrom' AND '$dto'
                $area
                $coa
                ORDER BY
                    3 ASC
        ", FALSE);
    }
}

/* End of file Mmaster.php */
