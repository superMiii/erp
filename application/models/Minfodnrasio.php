<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Minfodnrasio extends CI_Model
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

        $i_store = $this->input->post('i_store', TRUE);
        if ($i_store == '') {
            $i_store = $this->uri->segment(5);
        }

        

        if ($i_store != '0') {
            $store = "AND e.i_store = '$i_store' ";
        } else {
            $store = "";
        }

        // $i_status_so = $this->input->post('i_status_so', TRUE);
        // if ($i_status_so == '') {
        //     $i_status_so = $this->uri->segment(6);
        // }

        // if ($i_status_so != 'ALL') {
        //     $status_so = "AND r.i_status_dn = '$i_status_so' ";
        // } else {
        //     $status_so = "";
        // }


        $dfrom  = date('Y-m-d', strtotime($dfrom));
        $dto    = date('Y-m-d', strtotime($dto));
        $f_pusat = $this->session->f_pusat;

        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("SELECT
                        e.d_berangkat as id,         
                        e.d_berangkat ,
                        e.d_kembali ,
                        e.n_lama_dinas || ' Hari' as lama,
                        e.e_staff_name ,
                        f.e_store_loc_name ,
                        e.e_area ,
                        e.e_kota ,
                        e.v_realisasi_biaya::money as v_realisasi_biaya ,
                        e.v_spb_realisasi::money as v_spb_realisasi,
                        e.v_nota_tertagih::money as v_nota_tertagih,
                        e.n_biaya_vs_spb 
                    from
                        tm_dinas e
                    inner join tr_store_loc f on (f.i_store=e.i_store)
                    INNER JOIN tm_user_store su ON (su.i_store = e.i_store)
                WHERE
                    e.i_company = '$this->i_company'
                    AND e.d_dinas BETWEEN '$dfrom' AND '$dto'
                    AND f_dinas_cancel = 'f'
                    AND su.i_user = '$this->i_user'
                    and v_spb_realisasi > 0
                    $store
                ORDER BY
                    1 ", FALSE);

                // $datatables->edit('e_status_dn_name', function ($data) {
                //     if ($data['e_status_dn_name'] == "SELESAI") {
                //         $color  = 'black';
                //         $status = "SELESAI";
                //     } elseif ($data['e_status_dn_name'] == "SUDAH DISETUJUI") {
                //         $color  = 'black';
                //         $status = "SUDAH DISETUJUI";
                //     } elseif ($data['e_status_dn_name'] == "Menunggu Approve GM") {
                //         $color  = 'amber';
                //         $status = "Menunggu Approve GM";
                //     } elseif ($data['e_status_dn_name'] == "Menunggu Approve FADH") {
                //         $color  = 'purple';
                //         $status = "Menunggu Approve FADH";
                //     } elseif ($data['e_status_dn_name'] == "Menunggu Approve SDH") {
                //         $color  = 'blue';
                //         $status = "Menunggu Approve SDH";
                //     } elseif ($data['e_status_dn_name'] == "Menunggu Approve Supervisor") {
                //         $color  = 'teal';
                //         $status = "Menunggu Approve Supervisor";
                //     } elseif ($data['e_status_dn_name'] == "Menunggu Approve Admin Sales") {
                //         $color  = 'success';
                //         $status = "Menunggu Approve Admin Sales";
                //     } else {
                //         $color  = 'red';
                //         $status = $data['e_status_dn_name'];
                //     }
                //     $data = "<span class='badge bg-" . $color . " badge-pill'>" . $status . "</span>";
                //     return $data;
                // });

                    return $datatables->generate();
                }


    public function get_store0($cari)
    {
        return $this->db->query("SELECT 
        DISTINCT
        a.i_store, 
        e_store_loc_name AS e_store_name
    FROM 
        tr_store_loc a 
    INNER JOIN tm_user_store c 
        ON (a.i_store = c.i_store) 
    WHERE 
        (e_store_loc_name ILIKE '%$cari%')
        AND a.i_company = '$this->i_company' 
        AND f_store_loc_active = true
        AND c.i_user = '$this->i_user' 
    ORDER BY a.i_store ASC
        ", FALSE);
    }


    // public function get_status_so($cari)
    // {
    //     return $this->db->query("SELECT DISTINCT
    //             a.*
    //         from
    //             tr_status_dn a
    //         inner join tm_dinas b on (b.i_status_dn = a.i_status_dn)
    //             order by 1
    //     ", FALSE);
    // }


    /** Get Data Untuk Edit */
    public function get_data($dfrom, $dto, $i_store)
    {

        if ($i_store != '0') {
            $store = "AND e.i_store = '$i_store' ";
        } else {
            $store = "";
        }

        // if ($i_status_so != 'ALL') {
        //     $status_so = "AND r.i_status_dn = '$i_status_so' ";
        // } else {
        //     $status_so = "";
        // }
        
        $dfrom  = date('Y-m-d', strtotime($dfrom));
        $dto    = date('Y-m-d', strtotime($dto));
        $f_pusat = $this->session->f_pusat;
        return $this->db->query("SELECT
                    e.d_berangkat as id,         
                    e.d_berangkat ,
                    e.d_kembali ,
                    e.n_lama_dinas || ' Hari' as lama,
                    e.e_staff_name ,
                    f.e_store_loc_name ,
                    e.e_area ,
                    e.e_kota ,
                    e.v_realisasi_biaya as v_realisasi_biaya ,
                    e.v_spb_realisasi as v_spb_realisasi,
                    e.v_nota_tertagih as v_nota_tertagih,
                    e.n_biaya_vs_spb 
                from
                    tm_dinas e
                inner join tr_store_loc f on (f.i_store=e.i_store)
                INNER JOIN tm_user_store su ON (su.i_store = e.i_store)
            WHERE
                e.i_company = '$this->i_company'
                AND e.d_dinas BETWEEN '$dfrom' AND '$dto'
                AND f_dinas_cancel = 'f'
                AND su.i_user = '$this->i_user'
                and v_spb_realisasi > 0
                $store
            ORDER BY
                1  ", FALSE);
    }
}

/* End of file Mmaster.php */
