<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mcompany extends CI_Model
{

    /** List Datatable */
    public function serverside()
    {
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
            select i_company , i_company_id, e_company_name , 
            case when f_company_plusppn = true then 'Ya' else 'Tidak' end as ppn, 
            f_company_active as f_status from tr_company tc 
            order by e_company_name asc
        ", FALSE);

        $datatables->edit('f_status', function ($data) {
            $id         = $data['i_company'];
            if ($data['f_status'] == 't') {
                $status = $this->lang->line('Aktif');
                $color  = 'success';
            } else {
                $status = $this->lang->line('Tidak Aktif');
                $color  = 'danger';
            }
            $data = "<button class='btn btn-outline-" . $color . " btn-sm round' onclick='changestatus(\"" . $this->folder . "\",\"" . $id . "\");'>" . $status . "</button>";
            return $data;
        });

        /** Cek Hak Akses, Apakah User Bisa Edit */
        if (check_role($this->id_menu, 3)) {
            $datatables->add('action', function ($data) {
                $id         = trim($data['i_company']);
                $data       = '';
                $data      .= "<a href='" . base_url() . $this->folder . '/edit/' . encrypt_url($id) . "' title='Edit Data'><i class='fa fa-check-circle-o success darken-4 fa-lg mr-1'></i></a>";
                return $data;
            });
        }
        return $datatables->generate();
    }

    public function changestatus($id)
    {
        $this->db->select('f_company_active');
        $this->db->from('tr_company');
        $this->db->where('i_company', $id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $status = $query->row()->f_company_active;
        } else {
            $status = 'f';
        }
        if ($status == 'f') {
            $fstatus = 't';
        } else {
            $fstatus = 'f';
        }
        $table = array(
            'f_company_active' => $fstatus,
        );
        $this->db->where('i_company', $id);
        $this->db->update('tr_company', $table);
    }

    /** Cek Apakah Data Sudah Ada Pas Simpan */
    public function cek($kode)
    {
        return $this->db->query("
            SELECT 
                i_company_id
            FROM 
                tr_company 
            WHERE 
                trim(upper(i_company_id)) = trim(upper('$kode'))
        ", FALSE);
    }

    /** Simpan Data */
    public function save($kode, $nama, $ppn, $nppn, $pemilik, $alamat, $telp, $fax, $npwp_kode, $npwp_nama, $npwp_alamat, $rekening, $an, $bang, $rekening2, $an2, $bang2, $rekening3, $an3, $bang3, $mtr, $v_meterai, $v_meterai_limit)
    {
        $table = array(
            'i_company_id' => $kode,
            'e_company_name'  => $nama,
            'f_company_plusppn'  => $ppn,
            'n_ppn' => $nppn,
            'e_company_owner' => $pemilik,
            'e_company_address'  => $alamat,
            'e_company_phone'  => $telp,
            'e_company_fax' => $fax,
            'e_company_npwp_code' => $npwp_kode,
            'e_company_npwp_name'  => $npwp_nama,
            'e_company_npwp_address'  => $npwp_alamat,
            'e_company_account_number' => $rekening,
            'e_company_account_name' => $an,
            'e_company_account_bank' => $bang,
            'e_company_account_number2' => $rekening2,
            'e_company_account_name2' => $an2,
            'e_company_account_bank2' => $bang2,
            'e_company_account_number3' => $rekening3,
            'e_company_account_name3' => $an3,
            'e_company_account_bank3' => $bang3,
            'f_plus_meterai' => $mtr,
            'v_meterai' => $v_meterai,
            'v_meterai_limit' => $v_meterai_limit
        );
        $this->db->insert('tr_company', $table);
    }

    /** Get Data Untuk Edit */
    public function getdata($id)
    {
        return $this->db->query("
            SELECT 
                *
            FROM 
                tr_company 
            WHERE
                i_company = '$id'
        ", FALSE);
    }

    /** Cek Apakah Data Sudah Ada Pas Edit */
    public function cek_edit($kode, $oldkode)
    {
        return $this->db->query("
            SELECT 
                i_company_id
            FROM 
                tr_company 
            WHERE 
                trim(upper(i_company_id)) <> trim(upper('$oldkode'))
                AND trim(upper(i_company_id)) = trim(upper('$kode'))
        ", FALSE);
    }

    /** Update Data */
    public function update($id, $kode, $nama, $ppn, $nppn, $pemilik, $alamat, $telp, $fax, $npwp_kode, $npwp_nama, $npwp_alamat, $rekening, $an, $bang, $rekening2, $an2, $bang2, $rekening3, $an3, $bang3, $mtr, $v_meterai, $v_meterai_limit)
    {
        $table = array(
            'i_company_id' => $kode,
            'e_company_name'  => $nama,
            'f_company_plusppn'  => $ppn,
            'd_company_update' => date('Y-m-d H:i:s'),
            'n_ppn' => $nppn,
            'e_company_owner' => $pemilik,
            'e_company_address'  => $alamat,
            'e_company_phone'  => $telp,
            'e_company_fax' => $fax,
            'e_company_npwp_code' => $npwp_kode,
            'e_company_npwp_name'  => $npwp_nama,
            'e_company_npwp_address'  => $npwp_alamat,
            'e_company_account_number' => $rekening,
            'e_company_account_name' => $an,
            'e_company_account_bank' => $bang,
            'e_company_account_number2' => $rekening2,
            'e_company_account_name2' => $an2,
            'e_company_account_bank2' => $bang2,
            'e_company_account_number3' => $rekening3,
            'e_company_account_name3' => $an3,
            'e_company_account_bank3' => $bang3,
            'f_plus_meterai' => $mtr,
            'v_meterai' => $v_meterai,
            'v_meterai_limit' => $v_meterai_limit
        );
        $this->db->where('i_company', $id);
        $this->db->update('tr_company', $table);
    }
}

/* End of file Mmaster.php */
