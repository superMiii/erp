<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mcek5 extends CI_Model
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
        

        $i_periode = $year . $month;

        if ($i_periode != '') {
            $so = "AND i_periode = '$i_periode' ";
        } else {
            $so = "AND i_periode =''";
        }

        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("SELECT
                i_coa_saldo,
                i_coa ,
                i_periode ,
                e_coa_name ,
                v_saldo_awal::money as v_saldo_awal,
                v_mutasi_debet::money as v_mutasi_debet,
                v_mutasi_kredit::money as v_mutasi_kredit,
                v_saldo_akhir::money as v_saldo_akhir
            from
                tm_coa_saldo
            where i_company = '$this->i_company'
            $so
            order by 2 asc
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
                    i_coa_saldo,
                    i_coa ,
                    i_periode ,
                    e_coa_name ,
                    v_saldo_awal as v_saldo_awal,
                    v_mutasi_debet as v_mutasi_debet,
                    v_mutasi_kredit as v_mutasi_kredit,
                    v_saldo_akhir as v_saldo_akhir
                from
                    tm_coa_saldo
                where i_company = '$this->i_company'
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
                tm_coa_saldo                
            WHERE 
                i_company = '$this->i_company' and i_periode = '$miru'
        ", FALSE);
    }
    
    public function update_coa_saldo($year, $month)
    {
        $i_periode_close = $year.$month;
        $i_periode = date('Ym', strtotime('+1 month', strtotime($year.'-'.$month))); /** Penambahan Bulan Sebanyak 1 Bulan */
        $this->db->query("INSERT INTO tm_coa_saldo (i_company,i_periode,i_coa,e_coa_name,v_saldo_awal,d_entry)
            SELECT
                '$this->i_company' AS i_company,
                '$i_periode' AS i_periode,
                x.i_coa,
                co.e_coa_name,
                ((sum(v_saldo_awal) + sum(v_mutasi_debet)) - abs(sum(v_mutasi_kredit)))  AS v_saldo_awal,
                now() AS d_entry
            FROM
                (
                SELECT
                    i_coa,
                    v_saldo_awal,
                    0 AS v_mutasi_debet,
                    0 AS v_mutasi_kredit
                FROM
                    tm_coa_saldo
                WHERE
                    i_company = '$this->i_company'
                    AND i_periode = '$i_periode_close'
            UNION ALL
                SELECT
                    a.i_coa,
                    0 AS v_saldo_awal,
                    sum(b.v_rv) AS v_mutasi_debet,
                    0 AS v_mutasi_kredit
                FROM
                    tm_rv a 
		        inner join tm_rv_item b on (b.i_rv=a.i_rv)
                WHERE
                    i_company = '$this->i_company'
                    AND to_char(d_rv, 'YYYYMM') = '$i_periode_close'
                    AND f_rv_cancel = 'f'
                GROUP BY
                    1
            UNION ALL
                SELECT
                    a.i_coa,
                    0 AS v_saldo_awal,
                    0 AS v_mutasi_debet,
                    sum(b.v_pv) AS v_mutasi_kredit
                FROM
                    tm_pv a
		        inner join tm_pv_item b on (b.i_pv=a.i_pv)
                WHERE
                    i_company = '$this->i_company'
                    AND to_char(d_pv, 'YYYYMM') = '$i_periode_close'
                    AND f_pv_cancel = 'f'
                GROUP BY
                    1
            ) AS x
            inner join tr_coa co on (co.i_coa=x.i_coa)
            GROUP BY
                3,4
            ON CONFLICT (i_company, i_periode, i_coa) DO UPDATE 
            SET v_saldo_awal = excluded.v_saldo_awal, 
                d_update = now()
                 ", FALSE);
    }

    public function update_coa_saldo2($year, $month)
    {
        $i_periode_close = $year.$month;
        $this->db->query("UPDATE tm_coa_saldo 
                SET v_mutasi_debet=miru.debt,
                    v_mutasi_kredit=miru.krdt,
                    v_saldo_akhir=miru.ahir,
                    d_update= now() 
                from (SELECT
                        $this->i_company AS i_company,
                        '$i_periode_close' AS i_periode,
                        x.i_coa,
                        sum(v_mutasi_debet) as debt,
                        sum(v_mutasi_kredit) as krdt,
                        ((sum(v_saldo_awal) + sum(v_mutasi_debet)) - abs(sum(v_mutasi_kredit))) AS ahir,
                        now() AS d_entry
                    FROM
                        (
                        SELECT
                            i_coa,
                            v_saldo_awal,
                            0 AS v_mutasi_debet,
                            0 AS v_mutasi_kredit
                        FROM
                            tm_coa_saldo
                        WHERE
                            i_company = '$this->i_company'
                            AND i_periode = '$i_periode_close'
                    UNION ALL
                        SELECT
                            a.i_coa,
                            0 AS v_saldo_awal,
                            sum(b.v_rv) AS v_mutasi_debet,
                            0 AS v_mutasi_kredit
                        FROM
                            tm_rv a 
		                inner join tm_rv_item b on (b.i_rv=a.i_rv)
                        WHERE
                            i_company = '$this->i_company'
                            AND to_char(d_rv, 'YYYYMM') = '$i_periode_close'
                            AND f_rv_cancel = 'f'
                        GROUP BY
                            1
                    UNION ALL
                        SELECT
                            a.i_coa,
                            0 AS v_saldo_awal,
                            0 AS v_mutasi_debet,
                            sum(b.v_pv) AS v_mutasi_kredit
                        FROM
                            tm_pv a
		                inner join tm_pv_item b on (b.i_pv=a.i_pv)
                        WHERE
                            i_company = '$this->i_company'
                            AND to_char(d_pv, 'YYYYMM') = '$i_periode_close'
                            AND f_pv_cancel = 'f'
                        GROUP BY
                            1
                    ) AS x
                    GROUP by 3) as miru
        where tm_coa_saldo.i_company=miru.i_company 
        and tm_coa_saldo.i_periode=miru.i_periode 
        and tm_coa_saldo.i_coa=miru.i_coa 
                 ", FALSE);
    }
    
    
    public function update_coa_saldo3($year, $month)
    {
        $i_periode_close = $year.$month;
        $i_periode = date('Ym', strtotime('-1 month', strtotime($year.'-'.$month))); 
        $this->db->query("UPDATE tm_coa_saldo 
                SET v_saldo_awal=miru.awal,
                    d_update=now() 
                from (SELECT
                        $this->i_company AS i_company,
                        '$i_periode_close' AS i_periode,
                        x.i_coa,
                        ((sum(v_saldo_awal) + sum(v_mutasi_debet)) - abs(sum(v_mutasi_kredit))) AS awal
                    FROM
                        (
                        SELECT
                            i_coa,
                            v_saldo_awal,
                            0 AS v_mutasi_debet,
                            0 AS v_mutasi_kredit
                        FROM
                            tm_coa_saldo
                        WHERE
                            i_company = '$this->i_company'
                            AND i_periode = '$i_periode'
                    UNION ALL
                        SELECT
                            a.i_coa,
                            0 AS v_saldo_awal,
                            sum(b.v_rv) AS v_mutasi_debet,
                            0 AS v_mutasi_kredit
                        FROM
                            tm_rv a 
		                inner join tm_rv_item b on (b.i_rv=a.i_rv)
                        WHERE
                            i_company = '$this->i_company'
                            AND to_char(d_rv, 'YYYYMM') = '$i_periode'
                            AND f_rv_cancel = 'f'
                        GROUP BY
                            1
                    UNION ALL
                        SELECT
                            a.i_coa,
                            0 AS v_saldo_awal,
                            0 AS v_mutasi_debet,
                            sum(b.v_pv) AS v_mutasi_kredit
                        FROM
                            tm_pv a
		                inner join tm_pv_item b on (b.i_pv=a.i_pv)
                        WHERE
                            i_company = '$this->i_company'
                            AND to_char(d_pv, 'YYYYMM') = '$i_periode'
                            AND f_pv_cancel = 'f'
                        GROUP BY
                            1
                    ) AS x
                    GROUP by 3) as miru
        where tm_coa_saldo.i_company=miru.i_company 
        and tm_coa_saldo.i_periode=miru.i_periode 
        and tm_coa_saldo.i_coa=miru.i_coa 
                 ", FALSE);
    }



    public function karen($year, $month)
    {
        $i_periode = date('Ym', strtotime('+1 month', strtotime($year.'-'.$month))); /** Penambahan Bulan Sebanyak 1 Bulan */
        $query = $this->db->get_where("tm_periode",["i_company" => $this->i_company,"f_kasbank" => "t"]);
        if ($query->num_rows()>0) {
            $data = array(
                'i_periode' => $i_periode, 
            );
            $this->db->where('i_company', $this->i_company);
            $this->db->where('f_kasbank', 't');
            $this->db->update('tm_periode', $data);
        }else{
            $data = array(
                'i_company' => $this->i_company,
                'i_periode' => $i_periode, 
                'f_kasbank' => 't', 
            );
            $this->db->insert('tm_periode', $data);
        }
    }
    

}

/* End of file Mmaster.php */
