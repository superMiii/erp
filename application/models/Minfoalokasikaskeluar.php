<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Minfoalokasikaskeluar extends CI_Model
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
            $supplier = "AND a.i_supplier = '$i_supplier' ";
        } else {
            $supplier = "";
        }

        $dfrom  = date('Y-m-d', strtotime($dfrom));
        $dto    = date('Y-m-d', strtotime($dto));

        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("SELECT
                c.i_alokasi,
                h.e_supplier_name as e_supplier_name,
                a.i_nota,
                a.i_nota_id,
                a.d_nota,
                e.i_pv_id,
                e.d_pv,
                c.i_alokasi_id,
                c.d_alokasi,
                e.v_pv::money as jumlahbm,
                c.v_jumlah::money as jumlahalokasi,
                c.v_lebih::money as v_lebih,
                a.v_nota_netto::money as jumlah_nota,
                a.v_sisa::money as sisa_nota,
                b.e_remark
            from
                tm_nota_pembelian a 
                inner join tm_alokasi_kb_item b on (b.i_nota=a.i_nota)
                inner join tm_alokasi_kb c on (c.i_alokasi=b.i_alokasi)
                inner join tm_pv_item d on (d.i_pv_item =c.i_pv_item)
                inner join tm_pv e on (e.i_pv=c.i_pv)
                inner join tr_supplier h on	(h.i_supplier = a.i_supplier)
            WHERE
                c.f_alokasi_cancel = 'f'
                AND c.i_company = '$this->i_company'
                AND c.d_alokasi BETWEEN '$dfrom' AND '$dto'
                $supplier
            ORDER BY
                c.d_alokasi ASC
        ", FALSE);
        $datatables->hide('i_nota');
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
            c.i_alokasi,
            h.e_supplier_name as e_supplier_name,
            a.i_nota,
            a.i_nota_id,
            a.d_nota,
            e.i_pv_id,
            e.d_pv,
            c.i_alokasi_id,
            c.d_alokasi,
            e.v_pv::money as jumlahbm,
            c.v_jumlah::money as jumlahalokasi,
            c.v_lebih::money as v_lebih,
            a.v_nota_netto::money as jumlah_nota,
            a.v_sisa::money as sisa_nota,
            b.e_remark
        from
            tm_nota_pembelian a 
            inner join tm_alokasi_kb_item b on (b.i_nota=a.i_nota)
            inner join tm_alokasi_kb c on (c.i_alokasi=b.i_alokasi)
            inner join tm_pv_item d on (d.i_pv_item =c.i_pv_item)
            inner join tm_pv e on (e.i_pv=c.i_pv)
            inner join tr_supplier h on	(h.i_supplier = a.i_supplier)
        WHERE
            c.f_alokasi_cancel = 'f'
            AND c.i_company = '$this->i_company'
            AND c.d_alokasi BETWEEN '$dfrom' AND '$dto'
            $supplier
        ORDER BY
            c.d_alokasi ASC
        ", FALSE);
    }
}

/* End of file Mmaster.php */
