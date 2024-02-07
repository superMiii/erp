<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mcek6 extends CI_Model
{
    public function serverside()
    {
        $month = $this->input->post('month', TRUE);
        if ($month == '') {
            $month = $this->uri->segment(3);
        }

        $year = $this->input->post('year', TRUE);
        if ($year == '') {
            $year = $this->uri->segment(4);
        }

        
        // var_dump($i_store);
        // die;

        $i_periode = $year . $month;

        if ($i_periode != '') {
            $so = "AND i_periode = '$i_periode' ";
        } else {
            $so = "AND i_periode =''";
        }

        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("SELECT
                a.i_store ,
                i_mutasi_saldoawal,
                b.e_store_name ,
                i_periode,
                a.i_product,
                c.i_product_id,
                c.e_product_name ,
                n_saldo_awal 
            from
                tm_mutasi_saldoawal a
            inner join tr_store b on (b.i_store=a.i_store)
            inner join tr_product c on (c.i_product=a.i_product)
            where 
                a.i_company = '$this->i_company'
	            and b.f_store_pusat ='t'
                $so
            order by 2 desc
        ", FALSE);
        return $datatables->generate();
    }


    /** Get Data Untuk Edit */
    public function get_data($year, $month)
    {
        $i_periode = $year . $month;

        if ($i_periode != '') {
            $so = "AND i_periode = '$i_periode' ";
        } else {
            $so = "AND i_periode =''";
        }
        return $this->db->query("SELECT
                    a.i_store ,
                    i_mutasi_saldoawal,
                    b.e_store_name ,
                    i_periode,
                    a.i_product,
                    c.i_product_id,
                    c.e_product_name ,
                    n_saldo_awal 
                from
                    tm_mutasi_saldoawal a
                inner join tr_store b on (b.i_store=a.i_store)
                inner join tr_product c on (c.i_product=a.i_product)
                where 
                    a.i_company = '$this->i_company'
                    and b.f_store_pusat ='t'
                    $so
                order by 2 asc
        ", FALSE);
    }



    public function miru($year, $month)
    {
        $miru = date('Ym', strtotime('+1 month', strtotime($year.'-'.$month)));
        return $this->db->query("SELECT distinct 
                i_periode
            FROM 
                tm_mutasi_saldoawal                
            WHERE 
                i_company = '$this->i_company' and i_periode = '$miru'
        ", FALSE);
    }
    
    public function update_1($year, $month)
    {
        $i_periode_close = $year.$month;
        $i_periode = date('Ym', strtotime('+1 month', strtotime($year.'-'.$month)));     
        $mmk = date('Y').'-'.$month;
        $datefrom = date('Y-m-01', strtotime($mmk));
        $dateto = date('Y-m-t', strtotime($mmk));
        $datejangkafrom = '9999-09-09';
        $datejangkato = '9999-09-29';        

        $i_store = $this->db->get_where('tr_store', ['f_store_pusat' => true, 'i_company' => $this->i_company])->row()->i_store;

        $this->db->query("INSERT INTO tm_mutasi_saldoawal (i_company,i_store,i_store_location,i_periode,i_product,i_product_grade,i_product_motif,n_saldo_awal)
            SELECT 
                tt.i_company,
                $i_store AS i_store,
                $i_store AS i_store_location,
                '$i_periode' AS i_periode,
                tt.i_product,
                tt.i_product_grade, 
                tt.i_product_motif,
                tt.n_saldo_akhir as n_saldo_awal
            from
                f_mutasi_stock4($this->i_company,
                '$i_periode_close',
                '$datefrom',
                '$dateto',
                '$datejangkafrom',
                '$datejangkato') as tt
            order by
                5
                 ", FALSE);
    }

    public function update_2($year, $month)
    {
        $i_periode_close = $year.$month;
        $i_periode = date('Ym', strtotime('-1 month', strtotime($year.'-'.$month)));     
        $mmk = date('Y').'-'.$month;
        $datefrom = date('Y-m-01', strtotime('-1 month', strtotime($mmk)));
        $dateto = date('Y-m-t', strtotime('-1 month', strtotime($mmk)));
        $datejangkafrom = '9999-09-09';
        $datejangkato = '9999-09-29'; 

        $i_store = $this->db->get_where('tr_store', ['f_store_pusat' => true, 'i_company' => $this->i_company])->row()->i_store;

        $this->db->query("UPDATE tm_mutasi_saldoawal 
                SET n_saldo_awal=miru.n_saldo_akhir
                from (SELECT
                        tt.i_company,
                        $i_store AS i_store,
                        $i_store AS i_store_location,
                        '$i_periode_close' AS i_periode,
                        tt.i_product,
                        tt.i_product_grade, 
                        tt.i_product_motif,
                        tt.n_saldo_akhir
                    from
                        f_mutasi_stock4($this->i_company,
                        '$i_periode',
                        '$datefrom',
                        '$dateto',
                        '$datejangkafrom',
                        '$datejangkato') as tt
                    order by
                        5) as miru
                where tm_mutasi_saldoawal.i_company=miru.i_company 
                        and tm_mutasi_saldoawal.i_store=miru.i_store 
                        and tm_mutasi_saldoawal.i_periode=miru.i_periode 
                        and tm_mutasi_saldoawal.i_product=miru.i_product 
                        and tm_mutasi_saldoawal.i_product_grade=miru.i_product_grade 
                        and tm_mutasi_saldoawal.i_product_motif=miru.i_product_motif 
                                ", FALSE);
    }    



    public function karen($year, $month)
    {
        $i_periode = date('Ym', strtotime('+1 month', strtotime($year.'-'.$month))); /** Penambahan Bulan Sebanyak 1 Bulan */
        $query = $this->db->get_where("tm_periode",["i_company" => $this->i_company,"f_stock" => "t"]);
        if ($query->num_rows()>0) {
            $data = array(
                'i_periode' => $i_periode, 
            );
            $this->db->where('i_company', $this->i_company);
            $this->db->where('f_stock', 't');
            $this->db->update('tm_periode', $data);
        }else{
            $data = array(
                'i_company' => $this->i_company,
                'i_periode' => $i_periode, 
                'f_stock'   => 't', 
            );
            $this->db->insert('tm_periode', $data);
        }
    }
    

}

/* End of file Mmaster.php */
