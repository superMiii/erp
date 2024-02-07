<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Minfodnstatus extends CI_Model
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
            $store = "AND r.i_store = '$i_store' ";
        } else {
            $store = "";
        }

        $i_status_so = $this->input->post('i_status_so', TRUE);
        if ($i_status_so == '') {
            $i_status_so = $this->uri->segment(6);
        }

        if ($i_status_so != 'ALL') {
            $status_so = "AND r.i_status_dn = '$i_status_so' ";
        } else {
            $status_so = "";
        }


        $dfrom  = date('Y-m-d', strtotime($dfrom));
        $dto    = date('Y-m-d', strtotime($dto));
        $f_pusat = $this->session->f_pusat;

        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("SELECT
                        i_dinas ,
                        o.e_status_dn_name as e_status_dn_name,
                        i_dinas_id ,
                        y.e_store_loc_name ,
                        e_staff_name ,
                        e.e_dn_jabatan_name ,
                        e_area ,
                        e_kota ,
                        n_lama_dinas ,
                        to_char(d_berangkat, 'DD FMMonth YYYY') AS d_berangkat,
                        to_char(d_kembali, 'DD FMMonth YYYY') AS d_kembali,
                        v_anggaran_biaya::money as v_anggaran_biaya,
                        v_spb_target::money as v_spb_target,
                        (v_transfer + v_tf1 + v_tf2 + v_tf3+ v_tf4)::money as tf,
                        v_nota_tertagih::money as v_nota_tertagih
                    from
                        tm_dinas r
                    inner join tr_store_loc y on (y.i_store=r.i_store)
                    inner join tr_dn_jabatan e on (e.i_dn_jabatan =r.i_dn_jabatan)
                    inner join tr_status_dn o on (o.i_status_dn=r.i_status_dn)
                    INNER JOIN tm_user_store su ON (su.i_store = r.i_store)
                WHERE
                    r.i_company = '$this->i_company'
                    AND r.d_dinas BETWEEN '$dfrom' AND '$dto'
                    AND f_dinas_cancel = 'f'
                    AND su.i_user = '$this->i_user'
                    and r.d_dcc is null
                    $store
                    $status_so
                ORDER BY
                    1 ", FALSE);

                $datatables->edit('e_status_dn_name', function ($data) {
                    if ($data['e_status_dn_name'] == "SELESAI") {
                        $color  = 'black';
                        $status = "SELESAI";
                    } elseif ($data['e_status_dn_name'] == "SUDAH DISETUJUI") {
                        $color  = 'black';
                        $status = "SUDAH DISETUJUI";
                    } elseif ($data['e_status_dn_name'] == "Menunggu Approve GM") {
                        $color  = 'amber';
                        $status = "Menunggu Approve GM";
                    } elseif ($data['e_status_dn_name'] == "Menunggu Approve FADH") {
                        $color  = 'purple';
                        $status = "Menunggu Approve FADH";
                    } elseif ($data['e_status_dn_name'] == "Menunggu Approve SDH") {
                        $color  = 'blue';
                        $status = "Menunggu Approve SDH";
                    } elseif ($data['e_status_dn_name'] == "Menunggu Approve Supervisor") {
                        $color  = 'teal';
                        $status = "Menunggu Approve Supervisor";
                    } elseif ($data['e_status_dn_name'] == "Menunggu Approve Admin Sales") {
                        $color  = 'success';
                        $status = "Menunggu Approve Admin Sales";
                    } else {
                        $color  = 'red';
                        $status = $data['e_status_dn_name'];
                    }
                    $data = "<span class='badge bg-" . $color . " badge-pill'>" . $status . "</span>";
                    return $data;
                });

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


    public function get_status_so($cari)
    {
        return $this->db->query("SELECT DISTINCT
                a.*
            from
                tr_status_dn a
            inner join tm_dinas b on (b.i_status_dn = a.i_status_dn)
                order by 1
        ", FALSE);
    }


    /** Get Data Untuk Edit */
    public function get_data($dfrom, $dto, $i_store, $i_status_so)
    {

        if ($i_store != '0') {
            $store = "AND r.i_store = '$i_store' ";
        } else {
            $store = "";
        }

        if ($i_status_so != 'ALL') {
            $status_so = "AND r.i_status_dn = '$i_status_so' ";
        } else {
            $status_so = "";
        }
        
        $dfrom  = date('Y-m-d', strtotime($dfrom));
        $dto    = date('Y-m-d', strtotime($dto));
        $f_pusat = $this->session->f_pusat;
        return $this->db->query("SELECT
                            i_dinas ,
                            o.e_status_dn_name as e_status_dn_name,
                            i_dinas_id ,
                            y.e_store_loc_name ,
                            e_staff_name ,
                            e.e_dn_jabatan_name ,
                            e_area ,
                            e_kota ,
                            n_lama_dinas ,
                            to_char(d_berangkat, 'DD FMMonth YYYY') AS d_berangkat,
                            to_char(d_kembali, 'DD FMMonth YYYY') AS d_kembali,
                            v_anggaran_biaya as v_anggaran_biaya,
                            v_spb_target as v_spb_target,
                            (v_transfer + v_tf1 + v_tf2 + v_tf3+ v_tf4) as tf,
                            v_nota_tertagih as v_nota_tertagih
                        from
                            tm_dinas r
                        inner join tr_store_loc y on (y.i_store=r.i_store)
                        inner join tr_dn_jabatan e on (e.i_dn_jabatan =r.i_dn_jabatan)
                        inner join tr_status_dn o on (o.i_status_dn=r.i_status_dn)
                        INNER JOIN tm_user_store su ON (su.i_store = r.i_store)
                    WHERE
                        r.i_company = '$this->i_company'
                        AND r.d_dinas BETWEEN '$dfrom' AND '$dto'
                        AND f_dinas_cancel = 'f'
                        AND su.i_user = '$this->i_user'
                        and r.d_dcc is null
                        $store
                        $status_so
                    ORDER BY
                        1 ", FALSE);
    }
}

/* End of file Mmaster.php */
