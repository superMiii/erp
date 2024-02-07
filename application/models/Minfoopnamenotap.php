<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Minfoopnamenotap extends CI_Model
{

    /** List Datatable */
    public function serverside()
    {
        $dto = $this->input->post('dto', TRUE);
        if ($dto == '') {
            $dto = $this->uri->segment(3);
        }
        $f_jatuh = $this->input->post('f_jatuh', TRUE);
        if ($f_jatuh == '') {
            $f_jatuh = $this->uri->segment(4);
            if ($f_jatuh == '') {
                $f_jatuh = 'f';
            }
        }
        $dto    = date('Y-m-d', strtotime($dto));
        if ($f_jatuh == 'f') {
            $where = "WHERE x.d_nota <= '$dto' ";
        } else {
            $where = "WHERE x.d_jatuh_tempo_top <= '$dto' ";
        }
        $i_supplier = $this->input->post('i_supplier', TRUE);
        if ($i_supplier == '') {
            $i_supplier = $this->uri->segment(5);
            if ($i_supplier == '') {
                $i_supplier = 'NA';
            }
        }
        if ($i_supplier != 'NA') {
            $supplier = "AND c.i_supplier = '$i_supplier' ";
        } else {
            $supplier = "";
        }

        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("SELECT
                i_nota,
                e_supplier_name,
                pkp,
                n_supplier_top,
                i_nota_id,
                d_nota,
                d_jatuh_tempo,
                d_jatuh_tempo_top,
                netto,
                sisa,
                status,
                0 AS umur,
                '$dto' AS dto
            FROM(
                SELECT    
                    a.i_nota ,
                    initcap(c.e_supplier_name) as e_supplier_name,
                    case
                        when c.f_supplier_pkp = true then 'Ya'
                        else 'Tidak'
                    end as pkp,
                    c.n_supplier_top,
                    a.i_nota_id ,
                    a.d_nota ,
                    a.d_jatuh_tempo,
                    cast((a.d_nota + cast(c.n_supplier_top || 'days' as interval)) as date) as d_jatuh_tempo_top,
                    a.v_nota_netto::money as netto,
                    a.v_sisa::money as sisa,
                    case
                        when v_nota_netto <> v_sisa then 'Sisa'
                        else 'Utuh'
                    end as status
                from
                    tm_nota_pembelian a
                inner join tr_supplier c on
                    (c.i_supplier = a.i_supplier)
                where
                    a.i_company = '$this->i_company'
                    AND a.f_nota_cancel = 'f'
                    AND v_sisa > 0
                    $supplier
            ) AS x
            $where
            ORDER BY
                2,
                5 ASC
        ", FALSE);
        $datatables->edit('umur', function ($data) {
            $pecah1 = explode("-", $data['dto']);
            $date1  = $pecah1[2];
            $month1 = $pecah1[1];
            $year1  = $pecah1[0];
            $jd1    = GregorianToJD($month1, $date1, $year1);
            $pecah2 = explode('-', $data['d_jatuh_tempo_top']);
            $date2  = $pecah2[2];
            $month2 = $pecah2[1];
            $year2  = $pecah2[0];
            $jd2    = GregorianToJD($month2, $date2, $year2);
            $lama   = $jd1 - $jd2;
            if ($lama > 0 && $lama <= 7) {
                $data = "<span class='badge bg-teal bg-darken-3 badge-pill'>" . $lama . "</span>";
            } elseif ($lama >= 8 && $lama <= 15) {
                $data = "<span class='badge bg-yellow bg-darken-3 badge-pill'>" . $lama . "</span>";
            } elseif ($lama > 15) {
                $data = "<span class='badge bg-red bg-darken-3 badge-pill'>" . $lama . "</span>";
            } else {
                $data = "<span class='badge badge-pill'>" . $lama . "</span>";
            }
            return $data;
        });
        $datatables->hide('d_jatuh_tempo');
        $datatables->hide('dto');
        return $datatables->generate();
    }

    /** Get Area */
    public function get_supplier($cari)
    {
        return $this->db->query("SELECT 
                DISTINCT
                i_supplier ,
                i_supplier_id ,
                initcap(e_supplier_name) as e_supplier_name
            from
                tr_supplier a 
            WHERE 
                (e_supplier_name ILIKE '%$cari%')
                AND a.i_company = '$this->i_company' 
                AND f_supplier_active = true
            ORDER BY 3 ASC
        ", FALSE);
    }

    /** Get Data Untuk Export */
    public function get_data($dto, $f_jatuh, $i_supplier)
    {
        $dto    = date('Y-m-d', strtotime($dto));
        if ($f_jatuh == 'f') {
            $where = "WHERE x.d_nota <= '$dto' ";
        } else {
            $where = "WHERE x.d_jatuh_tempo_top <= '$dto' ";
        }
        if ($i_supplier != 'NA') {
            $supplier = "AND c.i_supplier = '$i_supplier' ";
        } else {
            $supplier = "";
        }
        return $this->db->query("SELECT
                i_nota,
                e_supplier_name,
                pkp,
                n_supplier_top,
                i_nota_id,
                d_nota,
                d_jatuh_tempo,
                d_jatuh_tempo_top,
                netto,
                sisa,
                status,
                0 AS umur,
                '$dto' AS dto
            FROM(
                SELECT    
                    a.i_nota ,
                    initcap(c.e_supplier_name) as e_supplier_name,
                    case
                        when c.f_supplier_pkp = true then 'Ya'
                        else 'Tidak'
                    end as pkp,
                    c.n_supplier_top,
                    a.i_nota_id ,
                    a.d_nota ,
                    a.d_jatuh_tempo,
                    cast((a.d_nota + cast(c.n_supplier_top || 'days' as interval)) as date) as d_jatuh_tempo_top,
                    a.v_nota_netto as netto,
                    a.v_sisa as sisa,
                    case
                        when v_nota_netto <> v_sisa then 'Sisa'
                        else 'Utuh'
                    end as status
                from
                    tm_nota_pembelian a
                inner join tr_supplier c on
                    (c.i_supplier = a.i_supplier)
                where
                    a.i_company = '$this->i_company'
                    AND a.f_nota_cancel = 'f'
                    AND v_sisa > 0
                    $supplier
            ) AS x
            $where
            ORDER BY
                d_jatuh_tempo_top,
                i_nota_id ASC
        ", FALSE);
    }
}

/* End of file Mmaster.php */
