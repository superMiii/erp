<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Minfokasbank extends CI_Model
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
            if ($i_area == '') {
                $i_area = 0;
            }
        }
        if ($i_area != '0') {
            $area = "AND a.i_area = '$i_area' ";
        } else {
            $area = "";
        }

        $i_coa = $this->input->post('i_coa', TRUE);
        if ($i_coa == '') {
            $i_coa = $this->uri->segment(6);
            if ($i_coa == '') {
                $i_coa = 0;
            }
        }

        // $i_coa2 = $this->input->post('i_coa2', TRUE);
        // if ($i_coa2 == '') {
        //     $i_coa2 = $this->uri->segment(7);
        //     if ($i_coa2 == '') {
        //         $i_coa2 = 0;
        //     }
        // }
        
        // if ($i_coa2 != '0') {
        //     $i_coa2 = "AND i_coa = '$i_coa2' ";
        // } else {
        //     $i_coa2 = "";
        // }

        $tanggal_saldo  = date('Y-m-01', strtotime($dfrom));
        $i_periode  = date('Ym', strtotime($dfrom));
        $dfrom  = date('Y-m-d', strtotime($dfrom));
        $dto    = date('Y-m-d', strtotime($dto));
        return $this->db->query("SELECT
                id,
                kode,
                araa,
                tanggal,
                tanggal_bukti,
                e_remark,
                i_coa_id,
                e_coa_name,
                debet AS debet,
                credit AS credit,
                (sum(debet + saldo) OVER(
            ORDER BY
                x,
                tanggal,
                tanggal_bukti,
                id,
                kode) - sum(credit) OVER(
            ORDER BY
                x,
                tanggal,
                tanggal_bukti,
                id,
                kode)) AS belance
            FROM
                (
                SELECT
                    0 AS x,
                    0 AS id,
                    NULL AS kode,
		            null as araa,
                    ('$tanggal_saldo')::date AS tanggal,
                    ('$tanggal_saldo')::date AS tanggal_bukti,
                    NULL AS e_remark,
                    NULL AS i_coa_id,
                    NULL AS e_coa_name,
                    v_saldo_awal AS saldo,
                    0 AS debet,
                    0 AS credit
                FROM
                    tm_coa_saldo
                WHERE
                    i_periode = '$i_periode'
                    AND i_company = '$this->i_company'
                    AND i_coa = '$i_coa'
            UNION ALL
                SELECT
                    1 AS x,
                    b.i_rv_item AS id,
                    i_rv_id AS kode,
		            e_area_name as araa,
                    a.d_rv AS tanggal,
                    b.d_bukti AS tanggal_bukti,
                    b.e_remark, 
                    d.i_coa_id,
                    d.e_coa_name, 
                    0 AS saldo,
                    b.v_rv AS debet,
                    0 AS credit
                FROM
                    tm_rv a
                INNER JOIN tm_rv_item b ON
                    (b.i_rv = a.i_rv)
                INNER JOIN tr_area c ON
                    (c.i_area = a.i_area)
                INNER JOIN tr_coa d ON
                    (d.i_coa = b.i_coa)
                    inner join tm_user_area u on
                        (u.i_area = c.i_area and u.i_user = '$this->i_user')
                WHERE
                    a.f_rv_cancel = 'f'
                    AND a.d_rv BETWEEN '$dfrom' AND '$dto'
                    AND a.i_company = '$this->i_company'
                    AND a.i_coa = '$i_coa'
                    $area
            UNION ALL
                SELECT
                    1 AS x,
                    b.i_pv_item AS id,
                    i_pv_id AS kode,
		            e_area_name as araa,
                    a.d_pv AS tanggal,
                    b.d_bukti AS tanggal_bukti,
                    b.e_remark, 
                    d.i_coa_id,
                    d.e_coa_name, 
                    0 AS saldo,
                    0 AS debet,
                    b.v_pv AS credit
                FROM
                    tm_pv a
                INNER JOIN tm_pv_item b ON
                    (b.i_pv = a.i_pv)
                INNER JOIN tr_area c ON
                    (c.i_area = a.i_area)
                    inner join tm_user_area u on
                        (u.i_area = c.i_area and u.i_user = '$this->i_user')
                INNER JOIN tr_coa d ON
                    (d.i_coa = b.i_coa)
                WHERE
                    a.f_pv_cancel = 'f'
                    AND a.d_pv BETWEEN '$dfrom' AND '$dto'
                    AND a.i_company = '$this->i_company'
                    AND a.i_coa = '$i_coa'
                    $area
            ) AS x
        ", FALSE);
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

    /** Get CoA */
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
            ORDER BY
                2
        ", FALSE);
    }

    // public function get_coa2($cari)
    // {
    //     return $this->db->query("SELECT
    //             a.i_coa as i_coa2,
    //             i_coa_id as i_coa_id2,
    //             e_coa_name as e_coa_name2
    //         FROM
    //             tr_coa a
    //         WHERE
    //             a.i_company = '$this->i_company'
    //             AND a.f_coa_status = 't'
    //             AND (a.i_coa_id ILIKE '%$cari%' OR a.e_coa_name ILIKE '%$cari%')
    //             and a.f_kas_besar ='t'
    //         ORDER BY
    //             3
    //     ", FALSE);
    // }

    /** Get Data Untuk Edit */
    public function get_data($dfrom, $dto, $i_area, $i_coa)
    {
        if ($dfrom == '') {
            $dfrom = $this->uri->segment(3);
        }

        if ($dto == '') {
            $dto = $this->uri->segment(4);
        }

        $i_area = $this->input->post('i_area', TRUE);
        if ($i_area == '') {
            $i_area = $this->uri->segment(3);
            if ($i_area == '') {
                $i_area = 0;
            }
        }
        if ($i_area != '0') {
            $area = "AND a.i_area = '$i_area' ";
        } else {
            $area = "";
        }

        if ($i_coa == '') {
            $i_coa = $this->uri->segment(6);
        }

        $tanggal_saldo  = date('Y-m-01', strtotime($dfrom));
        $i_periode  = date('Ym', strtotime($dfrom));
        $dfrom  = date('Y-m-d', strtotime($dfrom));
        $dto    = date('Y-m-d', strtotime($dto));
        return $this->db->query("SELECT
                id,
                kode,
                araa,
                tanggal,
                tanggal_bukti,
                e_remark,
                i_coa_id,
                e_coa_name,
                debet AS debet,
                credit AS credit,
                sum(debet + saldo) OVER(
            ORDER BY
                x,
                tanggal,
                tanggal_bukti,
                id,
                kode) - sum(credit) OVER(
            ORDER BY
                x,
                tanggal,
                tanggal_bukti,
                id,
                kode) AS saldo
            FROM
                (
                SELECT
                    0 AS x,
                    0 AS id,
                    NULL AS kode,
		            null as araa,
                    ('$tanggal_saldo')::date AS tanggal,
                    ('$tanggal_saldo')::date AS tanggal_bukti,
                    NULL AS e_remark,
                    NULL AS i_coa_id,
                    NULL AS e_coa_name,
                    v_saldo_awal AS saldo,
                    0 AS debet,
                    0 AS credit
                FROM
                    tm_coa_saldo
                WHERE
                    i_periode = '$i_periode'
                    AND i_company = '$this->i_company'
                    AND i_coa = '$i_coa'
            UNION ALL
                SELECT
                    1 AS x,
                    b.i_rv_item AS id,
                    i_rv_id AS kode,
		            e_area_name as araa,
                    a.d_rv AS tanggal,
                    b.d_bukti AS tanggal_bukti,
                    b.e_remark, 
                    d.i_coa_id,
                    d.e_coa_name, 
                    0 AS saldo,
                    b.v_rv AS debet,
                    0 AS credit
                FROM
                    tm_rv a
                INNER JOIN tm_rv_item b ON
                    (b.i_rv = a.i_rv)
                INNER JOIN tr_area c ON
                    (c.i_area = a.i_area)
                    inner join tm_user_area u on
                        (u.i_area = c.i_area and u.i_user = '$this->i_user')
                INNER JOIN tr_coa d ON
                    (d.i_coa = b.i_coa)
                WHERE
                    a.f_rv_cancel = 'f'
                    AND a.d_rv BETWEEN '$dfrom' AND '$dto'
                    AND a.i_company = '$this->i_company'
                    AND a.i_coa = '$i_coa'
                    $area
            UNION ALL
                SELECT
                    1 AS x,
                    b.i_pv_item AS id,
                    i_pv_id AS kode,
		            e_area_name as araa,
                    a.d_pv AS tanggal,
                    b.d_bukti AS tanggal_bukti,
                    b.e_remark, 
                    d.i_coa_id,
                    d.e_coa_name, 
                    0 AS saldo,
                    0 AS debet,
                    b.v_pv AS credit
                FROM
                    tm_pv a
                INNER JOIN tm_pv_item b ON
                    (b.i_pv = a.i_pv)
                INNER JOIN tr_area c ON
                    (c.i_area = a.i_area)
                    inner join tm_user_area u on
                        (u.i_area = c.i_area and u.i_user = '$this->i_user')
                INNER JOIN tr_coa d ON
                    (d.i_coa = b.i_coa)
                WHERE
                    a.f_pv_cancel = 'f'
                    AND a.d_pv BETWEEN '$dfrom' AND '$dto'
                    AND a.i_company = '$this->i_company'
                    AND a.i_coa = '$i_coa'
                    $area
            ) AS x
        ", FALSE);
    }
}

/* End of file Mmaster.php */
