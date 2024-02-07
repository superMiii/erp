<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Minfoalokasibk extends CI_Model
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

        $i_supplier = $this->input->post('i_supplier', TRUE);
        if ($i_supplier == '') {
            $i_supplier = $this->uri->segment(5);
        }

        if ($i_supplier != 'ALL') {
            $supplier = "AND e.i_supplier = '$i_supplier' ";
        } else {
            $supplier = "";
        }

        $dfrom  = date('Y-m-d', strtotime($dfrom));
        $dto    = date('Y-m-d', strtotime($dto));

        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("SELECT
                distinct a.i_alokasi,
                initcap(f.e_supplier_name) as e_supplier_name,
                w.i_kuk_id as referensi,
                h.e_pv_refference_type_name as jenis_pembayaran,
	            bb.e_coa_name,
                b.i_pv_id as i_pv_id,
                b.d_pv as d_pv,
                a.i_alokasi_id ,
                a.d_alokasi,
                e.i_nota_id,
                e.d_nota ,
                b.v_pv::money as jumlahbm,
                a.v_jumlah::money as jumlahalokasi,
                e.v_nota_netto::money as jumlah_nota,
                e.v_sisa::money as sisa_nota,
                e.i_nota_supplier
            from
                tm_alokasi_bk a
            inner join tm_pv b on
                (b.i_pv = a.i_pv)                
            inner join tr_coa bb on (bb.i_coa=b.i_coa)
            inner join tm_alokasi_bk_item d on
                (d.i_alokasi = a.i_alokasi)
            inner join tm_nota_pembelian e on
                (e.i_nota = d.i_nota)
            inner join tr_supplier f on
                (f.i_supplier = a.i_supplier)
            inner join tm_pv_item g on
                (g.i_pv_item = d.i_pv_item)
            inner join tr_pv_refference_type h on
                (h.i_pv_refference_type = g.i_pv_refference_type)
            inner join tm_kuk w on
                (w.i_kuk = g.i_pv_refference)
            WHERE
                a.f_alokasi_cancel = 'f'
                AND a.i_company = '$this->i_company'
                AND a.d_alokasi BETWEEN '$dfrom' AND '$dto'
                $supplier
            ORDER BY
                a.d_alokasi ASC
        ", FALSE);
        return $datatables->generate();
    }


    public function get_supplier($cari)
    {
        return $this->db->query("SELECT 
                i_supplier, i_supplier_id , initcap(e_supplier_name) AS e_supplier_name
            FROM 
                tr_supplier
            WHERE 
                (e_supplier_name ILIKE '%$cari%' OR i_supplier_id ILIKE '%$cari%')
                AND i_company = '$this->i_company' 
                AND f_supplier_active = 'true' 
            ORDER BY 3 ASC
        ", FALSE);
    }

    /** Get Data Untuk Edit */
    public function get_data($dfrom, $dto, $i_supplier)
    {
        if ($i_supplier != 'ALL') {
            $supplier = "AND a.i_supplier = '$i_supplier' ";
        } else {
            $supplier = "";
        }
        $dfrom  = date('Y-m-d', strtotime($dfrom));
        $dto    = date('Y-m-d', strtotime($dto));
        return $this->db->query("SELECT
                    distinct a.i_alokasi,
                    initcap(f.e_supplier_name) as e_supplier_name,
                    w.i_kuk_id as referensi,
                    h.e_pv_refference_type_name as jenis_pembayaran,
                    bb.e_coa_name,
                    b.i_pv_id as i_pv_id,
                    b.d_pv as d_pv,
                    a.i_alokasi_id ,
                    a.d_alokasi,
                    e.i_nota_id,
                    e.d_nota,
                    b.v_pv as jumlahbm,
                    a.v_jumlah as jumlahalokasi,
                    e.v_nota_netto as jumlah_nota,
                    e.v_sisa as sisa_nota,
                    e.i_nota_supplier
                from
                    tm_alokasi_bk a
                inner join tm_pv b on
                    (b.i_pv = a.i_pv)                
                inner join tr_coa bb on (bb.i_coa=b.i_coa)
                inner join tm_alokasi_bk_item d on
                    (d.i_alokasi = a.i_alokasi)
                inner join tm_nota_pembelian e on
                    (e.i_nota = d.i_nota)
                inner join tr_supplier f on
                    (f.i_supplier = a.i_supplier)
                inner join tm_pv_item g on
                    (g.i_pv_item = d.i_pv_item)
                inner join tr_pv_refference_type h on
                    (h.i_pv_refference_type = g.i_pv_refference_type)
                inner join tm_kuk w on
                    (w.i_kuk = g.i_pv_refference)
                WHERE
                    a.f_alokasi_cancel = 'f'
                    AND a.i_company = '$this->i_company'
                    AND a.d_alokasi BETWEEN '$dfrom' AND '$dto'
                    $supplier
                ORDER BY
                    a.d_alokasi ASC
        ", FALSE);
    }
}

/* End of file Mmaster.php */
