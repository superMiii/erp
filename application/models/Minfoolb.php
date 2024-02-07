<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Minfoolb extends CI_Model
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
            $coa = "AND b.i_coa = '$i_coa' ";
        } else {
            $coa = "";
        }

        $dfrom  = date('Y-m-d', strtotime($dfrom));
        $dto    = date('Y-m-d', strtotime($dto));

        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("SELECT
                a.d_bukti as tgl, 
                a.d_bukti,
                c.e_coa_name,
                b.i_rv_id,
                d.e_area_name,
                f.e_rv_refference_type_name,
                a.v_rv_saldo::money AS v_rv_saldo,
                a.e_remark
            FROM
                tm_rv_item a
            INNER JOIN tm_rv b ON
                (b.i_rv = a.i_rv)
            INNER JOIN tr_coa c ON
                (c.i_coa = b.i_coa)
            INNER JOIN tr_area d ON
                (d.i_area = a.ara)
            INNER JOIN tm_user_area e ON
                (e.i_area = a.ara)
            INNER JOIN tr_coa g ON 
                (g.i_coa = a.i_coa AND g.f_alokasi_bank_masuk= 't')
            LEFT JOIN tr_rv_refference_type f ON
                (f.i_rv_refference_type = a.i_rv_refference_type)
            WHERE
                b.f_rv_cancel = 'f'
                AND b.i_company = '$this->i_company'
                AND e.i_user = '$this->i_user'
                AND a.d_bukti BETWEEN '$dfrom' AND '$dto'
                AND a.v_rv_saldo > 0
	            and c.e_coa_name not LIKE '%Kas Besar%'
                $area
                $coa
            ORDER BY
                1 ASC
        ", FALSE);
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
            WHERE 
                (e_area_name ILIKE '%$cari%' OR i_area_id ILIKE '%$cari%')
                AND a.i_company = '$this->i_company' 
                AND f_area_active = true
                AND b.i_user = '$this->i_user' 
            ORDER BY 3 ASC
        ", FALSE);
    }

    public function get_coa($cari)
    {
        return $this->db->query("SELECT 
                a.i_coa,
                i_coa_id,
                e_coa_name
            FROM
                tr_coa a
            INNER JOIN tr_rv_type b ON
                (b.i_coa_group = a.i_coa_group
                    AND a.i_company = b.i_company)
            INNER JOIN tr_pv_type c ON
                (c.i_coa_group = a.i_coa_group
                    AND a.i_company = c.i_company)
            INNER JOIN tm_user_kas_rv d ON
                (d.i_rv_type = b.i_rv_type
                    AND d.i_user = '$this->i_user')
            INNER JOIN tm_user_kas_pv e ON
                (e.i_pv_type = c.i_pv_type
                    AND e.i_user = '$this->i_user')
            WHERE
                a.i_company = '$this->i_company'
                AND a.f_coa_status = 't'
                AND (a.i_coa_id ILIKE '%$cari%' OR a.e_coa_name ILIKE '%$cari%')
                and b.i_rv_type_id = 'BM'
            ORDER BY
                2
        ", FALSE);
    }

    /** Get Data Untuk Edit */
    public function get_data($dfrom, $dto, $i_area, $i_coa)
    {
        if ($i_area != 'NA') {
            $area = "AND a.i_area = '$i_area' ";
        } else {
            $area = "";
        }
        if ($i_coa != 'ALL') {
            $coa = "AND b.i_coa = '$i_coa' ";
        } else {
            $coa = "";
        }
        $dfrom  = date('Y-m-d', strtotime($dfrom));
        $dto    = date('Y-m-d', strtotime($dto));
        return $this->db->query("SELECT
                a.d_bukti as tgl, 
                a.d_bukti,
                c.e_coa_name,
                b.i_rv_id,
                d.e_area_name,
                f.e_rv_refference_type_name,
                a.v_rv_saldo AS v_rv_saldo,
                a.e_remark
            FROM
                tm_rv_item a
            INNER JOIN tm_rv b ON
                (b.i_rv = a.i_rv)
            INNER JOIN tr_coa c ON
                (c.i_coa = b.i_coa)
            INNER JOIN tr_area d ON
                (d.i_area = a.ara)
            INNER JOIN tm_user_area e ON
                (e.i_area = a.ara)
            INNER JOIN tr_coa g ON 
                (g.i_coa = a.i_coa AND g.f_alokasi_bank_masuk= 't')
            LEFT JOIN tr_rv_refference_type f ON
                (f.i_rv_refference_type = a.i_rv_refference_type)
            WHERE
                b.f_rv_cancel = 'f'
                AND b.i_company = '$this->i_company'
                AND e.i_user = '$this->i_user'
                AND a.d_bukti BETWEEN '$dfrom' AND '$dto'
                AND a.v_rv_saldo > 0
	            and c.e_coa_name not LIKE '%Kas Besar%'
                $area
                $coa
            ORDER BY
                1 ASC
        ", FALSE);
    }
}

/* End of file Mmaster.php */
