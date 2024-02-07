<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Minfomutasipiutang extends CI_Model
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
                x.i_customer,
                initcap(e_area_name) AS e_area_name,
                initcap(e_customer_name) AS e_customer_name,
                sum(v_saldo_awal)::money AS v_saldo_awal,
                sum(v_penjualan)::money AS v_penjualan,
                sum(v_alokasi_bm)::money AS v_alokasi_bm,
                sum(v_alokasi_knr)::money AS v_alokasi_knr,
                sum(v_alokasi_kn)::money AS v_alokasi_kn,
                sum(v_alokasi_hll)::money AS v_alokasi_hll,
                sum(v_pembulatan)::money AS v_pembulatan,
                ((sum(v_saldo_awal) + sum(v_penjualan)) - (sum(v_alokasi_bm)+ sum(v_alokasi_knr)+ sum(v_alokasi_kn)+ sum(v_alokasi_hll)))::money AS v_saldo_akhir,
                '$datefrom' AS dfrom,
                '$dateto' AS dto
            FROM
                (
            
            /*** Get Saldo Awal ***/
                SELECT
                    i_company,
                    i_area,
                    i_customer,
                    n_saldo_awal AS v_saldo_awal,
                    0 AS v_penjualan,
                    0 AS v_alokasi_bm,
                    0 AS v_alokasi_knr,
                    0 AS v_alokasi_kn,
                    0 AS v_alokasi_hll,
                    0 AS v_pembulatan
                FROM
                    tm_saldoawal_piutang
                WHERE
                    i_company = '$this->i_company'
                    AND i_periode = '$i_periode'
            /*** End Get Saldo Awal ***/
                    
            UNION ALL
            
            /*** Get Penjualan ***/
                SELECT
                    a.i_company,
                    a.i_area,
                    a.i_customer,
                    0 AS v_saldo_awal,
                    a.v_sisa AS v_penjualan,
                    0 AS v_alokasi_bm,
                    0 AS v_alokasi_knr,
                    0 AS v_alokasi_kn,
                    0 AS v_alokasi_hll,
                    0 AS v_pembulatan
                FROM
                    tm_nota a
                WHERE
                    a.i_company = '$this->i_company'
                    AND a.f_nota_cancel = 'f'
                    AND a.d_nota BETWEEN '$datefrom' AND '$dateto'
            /*** End Get Penjualan ***/
                    
            UNION ALL 
            
            /*** Get Alokasi Bank Masuk ***/
                SELECT
                    a.i_company,
                    a.i_area,
                    a.i_customer,
                    0 AS v_saldo_awal,
                    0 AS v_penjualan,
                    a.v_jumlah AS v_alokasi_bm,
                    0 AS v_alokasi_knr,
                    0 AS v_alokasi_kn,
                    0 AS v_alokasi_hll,
                    0 AS v_pembulatan
                FROM
                    tm_alokasi a
                INNER JOIN tm_alokasi_item b ON
                    (b.i_alokasi = a.i_alokasi)
                WHERE
                    a.i_company = '$this->i_company'
                    AND a.f_alokasi_cancel = 'f'
                    AND a.d_alokasi BETWEEN '$datefrom' AND '$dateto'
            /*** Get Alokasi Bank Masuk ***/
                    
            UNION ALL 
            
            /*** Get Alokasi Kredit Nota Retur ***/
                SELECT
                    a.i_company,
                    a.i_area,
                    a.i_customer,
                    0 AS v_saldo_awal,
                    0 AS v_penjualan,
                    0 AS v_alokasi_bm,
                    a.v_jumlah AS v_alokasi_knr,
                    0 AS v_alokasi_kn,
                    0 AS v_alokasi_hll,
                    0 AS v_pembulatan
                FROM
                    tm_alokasiknr a
                WHERE
                    a.f_alokasi_cancel = 'f'
                    AND a.d_alokasi BETWEEN '$datefrom' AND '$dateto'
                    AND a.i_company = '$this->i_company'
            /*** End Get Alokasi Kredit Nota Retur ***/
                    
            UNION ALL 
            
            /*** Get Alokasi Kredit Nota Non Retur ***/
                SELECT
                    a.i_company,
                    a.i_area,
                    a.i_customer,
                    0 AS v_saldo_awal,
                    0 AS v_penjualan,
                    0 AS v_alokasi_bm,
                    0 AS v_alokasi_knr,
                    a.v_jumlah AS v_alokasi_kn,
                    0 AS v_alokasi_hll,
                    0 AS v_pembulatan
                FROM
                    tm_alokasikn a
                WHERE
                    a.f_alokasi_cancel = 'f'
                    AND a.d_alokasi BETWEEN '$datefrom' AND '$dateto'
                    AND a.i_company = '$this->i_company'
            /*** End Get Alokasi Kredit Nota Non Retur ***/
                    
            ) AS x
            INNER JOIN tr_area y ON
                (y.i_area = x.i_area)
            INNER JOIN tr_customer z ON
                (z.i_customer = x.i_customer)
            GROUP BY
                2,3,4,5
        ", FALSE);

         /** Cek Hak Akses, Apakah User Bisa Edit */
        $datatables->add('action', function ($data) {
            $i_company  = $data['i_company'];
            $i_customer = $data['i_customer'];
            $dfrom      = $data['dfrom'];
            $dto        = $data['dto'];
            $data       = '';
            $data      .= "<a href='" . base_url() . $this->folder . '/view/' . encrypt_url($i_customer) . '/'. encrypt_url($i_company) . '/' . encrypt_url($dfrom) . '/' . encrypt_url($dto) . "' title='View Mutasi'><i class='fa fa-eye fa-lg warning darken-4 mr-1'></i></a>";
            return $data;
        });
        $datatables->hide('i_company');
        $datatables->hide('i_customer');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        return $datatables->generate();
    }

    /**** List Mutasi ***/
    public function get_data_detail($i_customer, $i_company, $dfrom, $dto)
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
                        tm_saldoawal_piutang
                    WHERE
                        i_customer = '$i_customer'
                        AND i_periode = '$i_periode'
                        AND i_company = '$i_company'	
                
                ) AS z
            /*** End Saldo Awal ***/

            UNION ALL

            /*** DEBET ***/

            /*** Masuk Penjualan ***/ 
                SELECT
                    1 AS id,
                    'Nota : '||a.i_nota_id AS i_refference_id,
                    a.d_nota AS tanggal,
                    'Penjualan Ke '||initcap(b.e_customer_name) AS e_note,
                    0 AS saldo,
                    a.v_nota_netto AS debet,
                    0 AS credit
                FROM
                    tm_nota a
                INNER JOIN tr_customer b ON
                    (b.i_customer = a.i_customer)
                WHERE
                    a.i_company = '$this->i_company'
                    AND a.i_customer = '$i_customer'
                    AND a.f_nota_cancel = 'f'
                    AND a.d_nota BETWEEN '$datefrom' AND '$dateto'
            /*** End Masuk Penjualan ***/ 

            /*** END DEBET ***/
            
            UNION ALL

            /*** CREDIT ***/
            
            /*** Keluar Alokasi ***/ 
                SELECT
                    2 AS id,
                    'BM : '||a.i_alokasi_id AS i_refference_id,
                    a.d_alokasi AS tanggal,
                    'Alokasi Ke '||initcap(b.e_customer_name) AS e_note,
                    0 AS saldo,
                    0 AS debet,
                    a.v_jumlah AS credit
                FROM
                    tm_alokasi a
                INNER JOIN tr_customer b ON
                    (b.i_customer = a.i_customer)
                WHERE
                    a.i_company = '$this->i_company'
                    AND a.i_customer = '$i_customer'
                    AND a.f_alokasi_cancel = 'f'
                    AND a.d_alokasi BETWEEN '$datefrom' AND '$dateto'
            /*** End Alokasi Bank Masuk ***/ 

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
                x.i_customer,
                initcap(e_area_name) AS e_area_name,
                initcap(e_customer_name) AS e_customer_name,
                sum(v_saldo_awal) AS v_saldo_awal,
                sum(v_penjualan) AS v_penjualan,
                sum(v_alokasi_bm) AS v_alokasi_bm,
                sum(v_alokasi_knr) AS v_alokasi_knr,
                sum(v_alokasi_kn) AS v_alokasi_kn,
                sum(v_alokasi_hll) AS v_alokasi_hll,
                sum(v_pembulatan) AS v_pembulatan,
                ((sum(v_saldo_awal) + sum(v_penjualan)) - (sum(v_alokasi_bm)+ sum(v_alokasi_knr)+ sum(v_alokasi_kn)+ sum(v_alokasi_hll))) AS v_saldo_akhir,
                '$datefrom' AS dfrom,
                '$dateto' AS dto
            FROM
                (
            
            /*** Get Saldo Awal ***/
                SELECT
                    i_company,
                    i_area,
                    i_customer,
                    n_saldo_awal AS v_saldo_awal,
                    0 AS v_penjualan,
                    0 AS v_alokasi_bm,
                    0 AS v_alokasi_knr,
                    0 AS v_alokasi_kn,
                    0 AS v_alokasi_hll,
                    0 AS v_pembulatan
                FROM
                    tm_saldoawal_piutang
                WHERE
                    i_company = '$this->i_company'
                    AND i_periode = '$i_periode'
            /*** End Get Saldo Awal ***/
                    
            UNION ALL
            
            /*** Get Penjualan ***/
                SELECT
                    a.i_company,
                    a.i_area,
                    a.i_customer,
                    0 AS v_saldo_awal,
                    a.v_sisa AS v_penjualan,
                    0 AS v_alokasi_bm,
                    0 AS v_alokasi_knr,
                    0 AS v_alokasi_kn,
                    0 AS v_alokasi_hll,
                    0 AS v_pembulatan
                FROM
                    tm_nota a
                WHERE
                    a.i_company = '$this->i_company'
                    AND a.f_nota_cancel = 'f'
                    AND a.d_nota BETWEEN '$datefrom' AND '$dateto'
            /*** End Get Penjualan ***/
                    
            UNION ALL 
            
            /*** Get Alokasi Bank Masuk ***/
                SELECT
                    a.i_company,
                    a.i_area,
                    a.i_customer,
                    0 AS v_saldo_awal,
                    0 AS v_penjualan,
                    a.v_jumlah AS v_alokasi_bm,
                    0 AS v_alokasi_knr,
                    0 AS v_alokasi_kn,
                    0 AS v_alokasi_hll,
                    0 AS v_pembulatan
                FROM
                    tm_alokasi a
                INNER JOIN tm_alokasi_item b ON
                    (b.i_alokasi = a.i_alokasi)
                WHERE
                    a.i_company = '$this->i_company'
                    AND a.f_alokasi_cancel = 'f'
                    AND a.d_alokasi BETWEEN '$datefrom' AND '$dateto'
            /*** Get Alokasi Bank Masuk ***/
                    
            UNION ALL 
            
            /*** Get Alokasi Kredit Nota Retur ***/
                SELECT
                    a.i_company,
                    a.i_area,
                    a.i_customer,
                    0 AS v_saldo_awal,
                    0 AS v_penjualan,
                    0 AS v_alokasi_bm,
                    a.v_jumlah AS v_alokasi_knr,
                    0 AS v_alokasi_kn,
                    0 AS v_alokasi_hll,
                    0 AS v_pembulatan
                FROM
                    tm_alokasiknr a
                WHERE
                    a.f_alokasi_cancel = 'f'
                    AND a.d_alokasi BETWEEN '$datefrom' AND '$dateto'
                    AND a.i_company = '$this->i_company'
            /*** End Get Alokasi Kredit Nota Retur ***/
                    
            UNION ALL 
            
            /*** Get Alokasi Kredit Nota Non Retur ***/
                SELECT
                    a.i_company,
                    a.i_area,
                    a.i_customer,
                    0 AS v_saldo_awal,
                    0 AS v_penjualan,
                    0 AS v_alokasi_bm,
                    0 AS v_alokasi_knr,
                    a.v_jumlah AS v_alokasi_kn,
                    0 AS v_alokasi_hll,
                    0 AS v_pembulatan
                FROM
                    tm_alokasikn a
                WHERE
                    a.f_alokasi_cancel = 'f'
                    AND a.d_alokasi BETWEEN '$datefrom' AND '$dateto'
                    AND a.i_company = '$this->i_company'
            /*** End Get Alokasi Kredit Nota Non Retur ***/
                    
            ) AS x
            INNER JOIN tr_area y ON
                (y.i_area = x.i_area)
            INNER JOIN tr_customer z ON
                (z.i_customer = x.i_customer)
            GROUP BY
                2,3,4,5
        ", FALSE);
    }
}

/*** End of file Mmaster.php ***/
