<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Minfomutasihutang extends CI_Model
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
        $i_periode = date('Ym', strtotime($dfrom));
        $datefrom = date('Y-m-d', strtotime($dfrom));
        $dateto = date('Y-m-d', strtotime($dto));
        $datejangkafrom = date('Y-m-01', strtotime($dfrom));
        $datejangkato = date('Y-m-d', strtotime('-1 days', strtotime($dfrom)));
        if ($datejangkafrom == $datefrom) {
            $datejangkafrom = '9999-09-09';
            $datejangkato = '9999-09-29';
        }

        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("SELECT
                0 AS id,
                x.i_company,
                x.i_supplier,
                initcap(e_supplier_name) AS e_supplier_name,
                sum(v_saldo_awal)::money AS v_saldo_awal,
                sum(v_dpp)::money AS v_dpp,
                sum(v_ppn)::money AS v_ppn,
                sum(v_debet)::money AS v_debet,
                sum(v_credit)::money AS v_credit,
                ((sum(v_saldo_awal) + sum(v_debet)) - sum(v_credit))::money AS v_saldo_akhir,
                '$datefrom' AS dfrom,
                '$dateto' AS dto
            FROM
                (
            
            /*** Get Saldo Awal ***/
                SELECT
                    i_company,
                    i_supplier,
                    n_saldo_awal AS v_saldo_awal,
                    0 AS v_dpp,
                    0 AS v_ppn,
                    0 AS v_debet,
                    0 AS v_credit
                FROM
                    tm_saldoawal_hutang 
                WHERE
                    i_company = '$this->i_company'
                    AND i_periode = '$i_periode'
            /*** End Get Saldo Awal ***/
                    
            UNION ALL
            
            /*** Get Pembelian ***/
                SELECT
                    a.i_company,
                    a.i_supplier,
                    0 AS v_saldo_awal,
                    a.v_nota_netto - a.v_nota_ppn AS v_dpp,
                    a.v_nota_ppn AS v_ppn,
                    a.v_nota_netto AS v_debet,
                    0 AS v_credit
                FROM
                    tm_nota_pembelian a
                WHERE
                    a.i_company = '$this->i_company'
                    AND a.f_nota_cancel = 'f'
                    AND a.d_nota BETWEEN '$datefrom' AND '$dateto'
            /*** End Get Pembelian ***/
                    
            UNION ALL 
            
            /*** Get Alokasi Bank dan Kas Besar Keluar ***/
                SELECT
                    a.i_company,
                    a.i_supplier,
                    0 AS v_saldo_awal,
                    0 AS v_dpp,
                    0 AS v_ppn,
                    0 AS v_debet,
                    sum(a.v_bank) + sum(v_kas) AS v_credit
                FROM
                    (
                    /*** Get Bank Keluar ***/
                    SELECT
                        a.i_company,
                        a.i_supplier,
                        a.v_jumlah AS v_bank,
                        0 AS v_kas
                    FROM tm_alokasi_bk a
                    WHERE a.i_company = '$this->i_company'
                    AND a.f_alokasi_cancel = 'f'
                    AND a.d_alokasi BETWEEN '$datefrom' AND '$dateto'
                    /*** End Get Bank Keluar ***/
                    UNION ALL
                    /*** Get Kas Besar Keluar ***/
                    SELECT
                        a.i_company,
                        a.i_supplier,
                        0 AS v_bank,
                        a.v_jumlah AS v_kas
                    FROM tm_alokasi_kb a
                    WHERE a.i_company = '$this->i_company'
                    AND a.f_alokasi_cancel = 'f'
                    AND a.d_alokasi BETWEEN '$datefrom' AND '$dateto'
                    /*** End Get Kas Besar Keluar ***/
                    ) AS a
                    GROUP BY 1,2
            /*** End Get Alokasi Bank dan Kas Besar Keluar ***/
                    
            ) AS x
            INNER JOIN tr_supplier y ON
                (y.i_supplier = x.i_supplier)
            GROUP BY
                2,3,4
        ", FALSE);

         /** Cek Hak Akses, Apakah User Bisa Edit */
        $datatables->add('action', function ($data) {
            $i_company  = $data['i_company'];
            $i_supplier = $data['i_supplier'];
            $dfrom      = $data['dfrom'];
            $dto        = $data['dto'];
            $data       = '';
            $data      .= "<a href='" . base_url() . $this->folder . '/view/' . encrypt_url($i_supplier) . '/'. encrypt_url($i_company) . '/' . encrypt_url($dfrom) . '/' . encrypt_url($dto) . "' title='View Mutasi'><i class='fa fa-eye fa-lg warning darken-4 mr-1'></i></a>";
            return $data;
        });
        $datatables->hide('i_company');
        $datatables->hide('i_supplier');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        return $datatables->generate();
    }

    /**** List Mutasi ***/
    public function get_data_detail($i_supplier, $i_company, $dfrom, $dto)
    {
        $i_periode = date('Ym', strtotime($dfrom));
        $datefrom = date('Y-m-d', strtotime($dfrom));
        $dateto = date('Y-m-d', strtotime($dto));
        $datejangkafrom = date('Y-m-01', strtotime($dfrom));
        $datejangkato = date('Y-m-d', strtotime('-1 days', strtotime($dfrom)));
        if ($datejangkafrom != $datefrom) {
            $tanggalsadldo = $datejangkafrom;
        }else{
            $tanggalsadldo = $datefrom;
        }
        if ($datejangkafrom == $datefrom) {
            $datejangkafrom = '9999-09-09';
            $datejangkato = '9999-09-29';
        }

        return $this->db->query("SELECT
                i_refference_id,
                to_char(tanggal,'dd-mm-yyyy') AS tanggal,
                e_note,
                debet,
                credit,
                (sum(debet + saldo) OVER(
                ORDER BY tanggal,
                id,
                i_refference_id) - sum(credit) OVER(
                ORDER BY tanggal,
                id,
                i_refference_id)) AS belance
            FROM
                (
                /*** Saldo Awal ***/
                SELECT
                    0 AS id,
                    '<b>Saldo Awal</b>' AS i_refference_id,
                    '$tanggalsadldo' AS tanggal,
                    '' AS e_note,
                    sum(n_saldo_awal) + (sum(n_saldo_akhir)) AS saldo,
                    0 AS debet,
                    0 AS credit
                FROM
                    (
                    SELECT
                        n_saldo_awal,
                        0 AS n_saldo_akhir
                    FROM
                        tm_saldoawal_hutang a
                    WHERE
                        i_supplier = '$i_supplier'
                        AND i_periode = '$i_periode'
                        AND i_company = '$i_company'	
                
            ) AS z
            /*** End Saldo Awal ***/

            UNION ALL

            /*** DEBET ***/
            /*** Masuk Pembelian ***/ 
                SELECT
                    1 AS id,
                    'Nota : '||b.i_nota_id AS i_refference_id,
                    b.d_nota AS tanggal,
                    'Pembelian Dari Supplier '|| initcap(s.e_supplier_name) AS e_note,
                    0 AS saldo,
                    v_nota_netto AS debet,
                    0 AS credit
                FROM
                    tm_nota_pembelian b
                INNER JOIN tr_supplier s ON 
                    (s.i_supplier = b.i_supplier)
                WHERE
                    b.i_supplier = '$i_supplier'
                    AND b.f_nota_cancel = 'f'
                    AND b.i_company = '$this->i_company'
                    AND b.d_nota BETWEEN '$datefrom' AND '$dateto'
            /*** End Masuk Pembelian ***/ 

            /*** END DEBET ***/
            
            UNION ALL

            /*** CREDIT ***/
            
            /*** Keluar Alokasi Bank ***/ 
                SELECT
                    2 AS id,
                    'BK : '||a.i_alokasi_id AS i_refference_id,
                    a.d_alokasi AS tanggal,
                    'Alokasi Keluar '||a.e_bank_name AS e_note,
                    0 AS saldo,
                    0 AS debet,
                    v_jumlah AS credit
                FROM tm_alokasi_bk a
                WHERE a.i_company = '$this->i_company'
                AND a.i_supplier = '$i_supplier'
                AND a.f_alokasi_cancel = 'f'
                AND a.d_alokasi BETWEEN '$datefrom' AND '$dateto'
            /*** End Keluar Alokasi Bank ***/ 

            UNION ALL    
            
            /*** Keluar Alokasi Kas ***/ 
                SELECT
                    3 AS id,
                    'KB : '||a.i_alokasi_id AS i_refference_id,
                    a.d_alokasi AS tanggal,
                    'Alokasi Kas Keluar' AS e_note,
                    0 AS saldo,
                    0 AS debet,
                    v_jumlah AS credit
                FROM tm_alokasi_kb a
                WHERE a.i_company = '$this->i_company'
                AND a.i_supplier = '$i_supplier'
                AND a.f_alokasi_cancel = 'f'
                AND a.d_alokasi BETWEEN '$datefrom' AND '$dateto'
            /*** End Keluar Alokasi Kas ***/ 
            /*** END CREDIT ***/
            ) AS x
        ", FALSE);
    }

    /**** Get Data Untuk Export ***/
    public function get_data($dfrom, $dto)
    {
        $i_periode = date('Ym', strtotime($dfrom));
        $datefrom = date('Y-m-d', strtotime($dfrom));
        $dateto = date('Y-m-d', strtotime($dto));
        $datejangkafrom = date('Y-m-01', strtotime($dfrom));
        $datejangkato = date('Y-m-d', strtotime('-1 days', strtotime($dfrom)));
        if ($datejangkafrom == $datefrom) {
            $datejangkafrom = '9999-09-09';
            $datejangkato = '9999-09-29';
        }
        return $this->db->query("SELECT
        0 AS id,
                x.i_company,
                x.i_supplier,
                initcap(e_supplier_name) AS e_supplier_name,
                sum(v_saldo_awal) AS v_saldo_awal,
                sum(v_dpp) AS v_dpp,
                sum(v_ppn) AS v_ppn,
                sum(v_debet) AS v_debet,
                sum(v_credit) AS v_credit,
                ((sum(v_saldo_awal) + sum(v_debet)) - sum(v_credit)) AS v_saldo_akhir,
                '$datefrom' AS dfrom,
                '$dateto' AS dto
            FROM
                (
            
            /*** Get Saldo Awal ***/
                SELECT
                    i_company,
                    i_supplier,
                    n_saldo_awal AS v_saldo_awal,
                    0 AS v_dpp,
                    0 AS v_ppn,
                    0 AS v_debet,
                    0 AS v_credit
                FROM
                    tm_saldoawal_hutang 
                WHERE
                    i_company = '$this->i_company'
                    AND i_periode = '$i_periode'
            /*** End Get Saldo Awal ***/
                    
            UNION ALL
            
            /*** Get Pembelian ***/
                SELECT
                    a.i_company,
                    a.i_supplier,
                    0 AS v_saldo_awal,
                    a.v_nota_netto - a.v_nota_ppn AS v_dpp,
                    a.v_nota_ppn AS v_ppn,
                    a.v_nota_netto AS v_debet,
                    0 AS v_credit
                FROM
                    tm_nota_pembelian a
                WHERE
                    a.i_company = '$this->i_company'
                    AND a.f_nota_cancel = 'f'
                    AND a.d_nota BETWEEN '$datefrom' AND '$dateto'
            /*** End Get Pembelian ***/
                    
            UNION ALL 
            
            /*** Get Alokasi Bank dan Kas Besar Keluar ***/
                SELECT
                    a.i_company,
                    a.i_supplier,
                    0 AS v_saldo_awal,
                    0 AS v_dpp,
                    0 AS v_ppn,
                    0 AS v_debet,
                    sum(a.v_bank) + sum(v_kas) AS v_credit
                FROM
                    (
                    /*** Get Bank Keluar ***/
                    SELECT
                        a.i_company,
                        a.i_supplier,
                        a.v_jumlah AS v_bank,
                        0 AS v_kas
                    FROM tm_alokasi_bk a
                    WHERE a.i_company = '$this->i_company'
                    AND a.f_alokasi_cancel = 'f'
                    AND a.d_alokasi BETWEEN '$datefrom' AND '$dateto'
                    /*** End Get Bank Keluar ***/
                    UNION ALL
                    /*** Get Kas Besar Keluar ***/
                    SELECT
                        a.i_company,
                        a.i_supplier,
                        0 AS v_bank,
                        a.v_jumlah AS v_kas
                    FROM tm_alokasi_kb a
                    WHERE a.i_company = '$this->i_company'
                    AND a.f_alokasi_cancel = 'f'
                    AND a.d_alokasi BETWEEN '$datefrom' AND '$dateto'
                    /*** End Get Kas Besar Keluar ***/
                    ) AS a
                    GROUP BY 1,2
            /*** End Get Alokasi Bank dan Kas Besar Keluar ***/
                    
            ) AS x
            INNER JOIN tr_supplier y ON
                (y.i_supplier = x.i_supplier)
            GROUP BY
                2,3,4
        ", FALSE);
    }
}

/*** End of file Mmaster.php ***/
