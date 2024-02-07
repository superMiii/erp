<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mbank extends CI_Model
{

    /** List Datatable */
    public function serverside()
    {
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
            select i_bank , i_bank_id , 
			e_bank_name , e_rekening_no,  e_rekening_name, f_bank_active 
			from tr_bank where i_company = '$this->i_company'
            order by i_bank desc
        ", FALSE);

        $datatables->edit('f_bank_active', function ($data) {
            $id         = $data['i_bank'];
            if ($data['f_bank_active'] == 't') {
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
                $id         = trim($data['i_bank']);
                $data       = '';
                $data      .= "<a href='" . base_url() . $this->folder . '/edit/' . encrypt_url($id) . "' title='Edit Data'><i class='fa fa-pencil-square success darken-4 fa-lg mr-1'></i></a>";
                return $data;
            });
        }
        return $datatables->generate();
    }

    public function changestatus($id)
    {
        $this->db->select('f_bank_active');
        $this->db->from('tr_bank');
        $this->db->where('i_bank', $id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $status = $query->row()->f_bank_active;
        } else {
            $status = 'f';
        }
        if ($status == 'f') {
            $fstatus = 't';
        } else {
            $fstatus = 'f';
        }
        $table = array(
            'f_bank_active' => $fstatus,
        );
        $this->db->where('i_bank', $id);
        $this->db->update('tr_bank', $table);
    }

    /** Cek Apakah Data Sudah Ada Pas Simpan */
    public function cek($kode)
    {
        return $this->db->query("
            SELECT 
                i_bank_id
            FROM 
                tr_bank
            WHERE 
                trim(upper(i_bank_id)) = trim(upper('$kode'))
                AND i_company = '$this->i_company'
        ", FALSE);
    }

    /** Simpan Data */
    public function save($kode, $nama, $norek, $namarek)
    {
        $table = array(
            'i_company'    => $this->i_company,
            'i_bank_id' => $kode,
            'e_bank_name' => $nama,
            'e_rekening_no' => $norek,
            'e_rekening_name' => $namarek,
            'd_bank_entry'  => date('Y-m-d H:i:s'),
        );
        $this->db->insert('tr_bank', $table);
    }

    /** Get Data Untuk Edit */
    public function getdata($id)
    {
        return $this->db->query("
            SELECT 
                *
            FROM 
                tr_bank 
            WHERE
                i_bank = '$id'
        ", FALSE);
    }

    /** Cek Apakah Data Sudah Ada Pas Edit */
    public function cek_edit($kode, $kodeold)
    {
        return $this->db->query("
            SELECT 
                i_bank_id
            FROM 
                tr_bank 
            WHERE 
                trim(upper(i_bank_id)) <> trim(upper('$kodeold'))
                AND trim(upper(i_bank_id)) = trim(upper('$kode'))
                AND i_company = '$this->i_company'
        ", FALSE);
    }

    /** Update Data */
    public function update($id, $kode, $nama, $norek, $namarek)
    {
        $table = array(
            'i_bank_id' => $kode,
            'e_bank_name' => $nama,
            'e_rekening_no' => $norek,
            'e_rekening_name' => $namarek,
            'd_bank_update'  => date('Y-m-d H:i:s'),
        );
        $this->db->where('i_bank', $id);
        $this->db->update('tr_bank', $table);
    }
}

/* End of file Mmaster.php */
