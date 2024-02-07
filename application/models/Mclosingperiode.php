<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mclosingperiode extends CI_Model
{

    /** Update Periode */
    public function update_periode($year, $month)
    {
        $i_periode = date('Ym', strtotime('+1 month', strtotime($year.'-'.$month))); /** Penambahan Bulan Sebanyak 1 Bulan */
        $query = $this->db->get_where("tm_periode",["i_company" => $this->i_company,"f_all" => "t"]);
        if ($query->num_rows()>0) {
            $data = array(
                'i_periode' => $i_periode, 
            );
            $this->db->where('i_company', $this->i_company);
            $this->db->where('f_all', 't');
            $this->db->update('tm_periode', $data);
        }else{
            $data = array(
                'i_company' => $this->i_company,
                'i_periode' => $i_periode, 
                'f_all'     => 't', 
            );
            $this->db->insert('tm_periode', $data);
        }
    }

    /** Update Log */
    public function update_log($year, $month)
    {
        $i_periode = $year.$month;
        $this->db->query("INSERT INTO tm_log_all SELECT * FROM tm_log WHERE to_char(waktu, 'YYYYMM') = '$i_periode' ", FALSE);
        $this->db->query("DELETE FROM tm_log WHERE to_char(waktu, 'YYYYMM') = '$i_periode' ", FALSE);
    }

    /** Update CoA Saldo */
    public function update_coa_saldo($year, $month)
    {
        // $i_periode_close = $year.$month;
        // $i_periode = date('Ym', strtotime('+1 month', strtotime($year.'-'.$month))); /** Penambahan Bulan Sebanyak 1 Bulan */
        // $this->db->query("INSERT INTO tm_coa_saldo (i_company,i_periode,i_coa,v_saldo_awal,v_saldo_akhir,d_entry)
        //     SELECT
        //         '$this->i_company' AS i_company,
        //         '$i_periode' AS i_periode,
        //         i_coa,
        //         (sum(v_saldo_awal) + sum(v_mutasi_debet) - abs(sum(v_mutasi_kredit))) AS v_saldo_awal,
        //         (sum(v_saldo_awal) + sum(v_mutasi_debet) - abs(sum(v_mutasi_kredit))) AS v_saldo_akhir,
        //         now() AS d_entry
        //     FROM
        //         (
        //         SELECT
        //             i_coa,
        //             v_saldo_awal,
        //             0 AS v_mutasi_debet,
        //             0 AS v_mutasi_kredit
        //         FROM
        //             tm_coa_saldo
        //         WHERE
        //             i_company = '$this->i_company'
        //             AND i_periode = '$i_periode_close'
        //     UNION ALL
        //         SELECT
        //             i_coa,
        //             0 AS v_saldo_awal,
        //             sum(v_rv) AS v_mutasi_debet,
        //             0 AS v_mutasi_kredit
        //         FROM
        //             tm_rv
        //         WHERE
        //             i_company = '$this->i_company'
        //             AND to_char(d_rv, 'YYYYMM') = '$i_periode_close'
        //             AND f_rv_cancel = 'f'
        //         GROUP BY
        //             1
        //     UNION ALL
        //         SELECT
        //             i_coa,
        //             0 AS v_saldo_awal,
        //             0 AS v_mutasi_debet,
        //             sum(v_pv) AS v_mutasi_kredit
        //         FROM
        //             tm_pv
        //         WHERE
        //             i_company = '$this->i_company'
        //             AND to_char(d_pv, 'YYYYMM') = '$i_periode_close'
        //             AND f_pv_cancel = 'f'
        //         GROUP BY
        //             1
        //     ) AS x
        //     GROUP BY
        //         3
        //     ON CONFLICT (i_company, i_periode, i_coa) DO UPDATE 
        //     SET v_saldo_awal = excluded.v_saldo_awal, 
        //         v_saldo_akhir = excluded.v_saldo_akhir, 
        //         d_update = now()
        //          ", FALSE);
    }
}

/* End of file Mmaster.php */
