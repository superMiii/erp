<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mdinastf extends CI_Model
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
            $store = "AND a.i_store = '$i_store' ";
        } else {
            $store = "";
        }

        $dfrom  = date('Y-m-d', strtotime($dfrom));
        $dto    = date('Y-m-d', strtotime($dto));

        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("SELECT distinct
                d_dinas as tgl,
                i_dinas AS id,
                d_dinas,
                i_dinas_id,
                e_staff_name,
                e_area,
                e_kota,
                n_lama_dinas || '  Hari' as n_lama_dinas,
                to_char(d_berangkat, 'DD FMMonth YYYY') AS d_berangkat,
                to_char(d_kembali, 'DD FMMonth YYYY') AS d_kembali,
                v_anggaran_biaya::money as v_anggaran_biaya,
                v_spb_target::money as v_spb_target,
                f_dinas_cancel AS f_status,
                case when a.d_dcc is not null then 'TIDAK DISETUJUI : ' || a.i_dcc || ' : ' || a.e_dcc  else d.e_status_dn_name end as e_status_dn_name,
                i_acc1,
                i_acc2,
                i_acc3,
                i_acc4,
                i_acc5,    
                d_dcc, 
                (v_transfer+v_tf1+v_tf2+v_tf3+v_tf4)::money as v_transfer,
                to_char(d_dinas, 'YYYYMM') as i_periode,        
                '$dfrom' AS dfrom,
                '$dto' AS dto,
                '$i_store' AS i_store
            FROM
                tm_dinas a
            INNER JOIN tr_status_dn d ON (d.i_status_dn = a.i_status_dn)
            INNER JOIN tm_user_store su ON (su.i_store = a.i_store)
            WHERE
                a.i_company = '$this->i_company'
                AND d_dinas BETWEEN '$dfrom' AND '$dto'
                AND su.i_user = '$this->i_user'
                AND a.d_dcc ISNULL
                AND a.i_status_dn = '6'
                $store
            ORDER BY
                1 DESC 
        ", FALSE);

        $datatables->edit('f_status', function ($data) {
            if ($data['f_status'] == 't') {
                $status = $this->lang->line('Batal');
                $color  = 'red';
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

        /** Cek Hak Akses, Apakah User Bisa Edit */
        $datatables->add('action', function ($data) {
            $id         = trim($data['id']);
            $f_status   = $data['f_status'];
            $i_acc1     = $data['i_acc1'];
            $i_acc2     = $data['i_acc2'];
            $d_dcc      = $data['d_dcc'];
            $dfrom      = $data['dfrom'];
            $dto        = $data['dto'];
            $i_store     = $data['i_store'];
            $i_periode  = $data['i_periode'];
            $data       = '';
            $data      .= "<a href='" . base_url() . $this->folder . '/view/' . encrypt_url($id) . '/' . encrypt_url($dfrom) . '/' . encrypt_url($dto) . '/' . encrypt_url($i_store) . "' title='View Data'><i class='fa fa-eye fa-lg warning darken-4 mr-1'></i></a>";
            // if ($i_periode >= get_periode()) {
                if (check_role($this->i_menu, 3)) {
                    $data      .= "<a href='" . base_url() . $this->folder . '/edit/' . encrypt_url($id)  . '/' .  encrypt_url($dfrom) . '/' . encrypt_url($dto) . '/' . encrypt_url($i_store) . "' title='Edit Data'><i class='fa fa-pencil-square success darken-4 fa-lg mr-1'></i></a>";
                }
            // }
            return $data;
        });
        $datatables->hide('i_periode');
        $datatables->hide('id');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('i_store');
        $datatables->hide('e_status_dn_name');
        $datatables->hide('d_dcc');
        return $datatables->generate();
    }
    public function get_store0($cari)
    {
        return $this->db->query("SELECT 
                a.i_store, 
                d.e_store_loc_name AS e_name
            FROM 
                tm_user_store a 
            INNER JOIN tr_store_loc d 
                ON (a.i_store = d.i_store) 
            WHERE 
                (e_store_loc_name ILIKE '%$cari%')
                AND a.i_company = '$this->i_company' 
                AND f_store_loc_active = true
                AND a.i_user = '$this->i_user' 
            ORDER BY 1 ASC
                ", FALSE);
    }

    public function get_store($cari)
    {
        return $this->db->query("SELECT DISTINCT
                a.i_store AS id,
                b.e_store_loc_name AS e_name
            FROM
                tr_store a
            INNER JOIN tr_store_loc b ON
                (b.i_store = a.i_store)
            INNER JOIN tr_area c ON
                (c.i_store = a.i_store)
            INNER JOIN tm_user_area d ON
                (d.i_area = c.i_area
                    AND d.i_user = '$this->i_user')
            WHERE
                (e_store_name ILIKE '%$cari%'
                    OR i_store_id ILIKE '%$cari%'
                    OR i_store_loc_id ILIKE '%$cari%'
                    OR e_store_loc_name ILIKE '%$cari%')
                AND f_store_active = 't'
                AND b.f_store_loc_active = 't'
                AND a.i_company = '$this->i_company'
            ORDER BY
                1;
        ", FALSE);
    }

    /** Get Data Untuk Edit */
    public function getdata($id)
    {
        return $this->db->query("SELECT 
                a.*,
                b.e_dn_atasan_name ,
                c.e_dn_departement_name ,
                d.e_dn_jabatan_name ,
                e.e_store_loc_name 
            FROM 
                tm_dinas a
                inner join tr_dn_atasan b on (b.i_dn_atasan=a.i_dn_atasan)
                inner join tr_dn_departement c on (c.i_dn_departement=a.i_dn_departement)
                inner join tr_dn_jabatan d on (d.i_dn_jabatan=a.i_dn_jabatan)
                inner join tr_store_loc e on (e.i_store=a.i_store)
            WHERE
                a.i_dinas = '$id'
        ", FALSE);
    }

    /** Cek Apakah Data Sudah Ada Pas Edit */
    public function cek_edit()
    {
        $i_document = $this->input->post('i_document', TRUE);
        $i_document_old = $this->input->post('i_document_old', TRUE);
        return $this->db->query("SELECT 
                i_dinas_id
            FROM 
                tm_dinas
            WHERE 
                trim(upper(i_dinas_id)) <> trim(upper('$i_document_old'))
                AND trim(upper(i_dinas_id)) = trim(upper('$i_document'))
                AND i_company = '$this->i_company'
        ", FALSE);
    }

    /** Update Data */
    public function update()
    {
        $id             = $this->input->post('id');
        $v_transfer     = str_replace(",", "", $this->input->post('v_transfer'));
        $v_tf1          = str_replace(",", "", $this->input->post('v_tf1'));
        $v_tf2          = str_replace(",", "", $this->input->post('v_tf2'));
        $v_tf3          = str_replace(",", "", $this->input->post('v_tf3'));
        $v_tf4          = str_replace(",", "", $this->input->post('v_tf4'));
        $v_transfer     = (strlen($v_transfer)>0) ? $v_transfer : 0 ;
        $v_tf1          = (strlen($v_tf1)>0) ? $v_tf1 : 0 ;
        $v_tf2          = (strlen($v_tf2)>0) ? $v_tf2 : 0 ;
        $v_tf3          = (strlen($v_tf3)>0) ? $v_tf3 : 0 ;
        $v_tf4          = (strlen($v_tf4)>0) ? $v_tf4 : 0 ;
        $f_selesai      = ($this->input->post('f_selesai') == 'on') ? 't' : 'f';
        switch($f_selesai){
            case 'f':
                $dn2 = '6';
                break;
            case 't':
                $dn2 = '7';
                break;
        }
        $header = array(
            'i_company'         => $this->session->i_company,
            'i_dinas'           => $id,
            'e_transfer'        => $this->input->post('e_user_name'),
            'v_transfer'        => $v_transfer,
            'v_tf1'             => $v_tf1,
            'v_tf2'             => $v_tf2,
            'v_tf3'             => $v_tf3,
            'v_tf4'             => $v_tf4,
            'i_status_dn'       => $dn2,
            'f_selesai'         => $f_selesai,
        );
        $this->db->where('i_dinas', $id);
        $this->db->update('tm_dinas', $header);        
    }

}

/* End of file Mmaster.php */
