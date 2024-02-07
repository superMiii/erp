<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mgroupsupplier extends CI_Model
{

    /** List Datatable */
    public function serverside()
    {
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
            select i_supplier_group , i_supplier_groupid , 
			e_supplier_groupname , f_supplier_groupactive 
			from tr_supplier_group where i_company = '$this->i_company'
            order by i_supplier_group desc
        ", FALSE);

        $datatables->edit('f_supplier_groupactive', function ($data) {
            $id         = $data['i_supplier_group'];
            if ($data['f_supplier_groupactive'] == 't') {
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
                $id         = trim($data['i_supplier_group']);
                $data       = '';
                $data      .= "<a href='" . base_url() . $this->folder . '/edit/' . encrypt_url($id) . "' title='Edit Data'><i class='fa fa-pencil-square success darken-4 fa-lg mr-1'></i></a>";
                return $data;
            });
        }
        return $datatables->generate();
    }

    public function changestatus($id)
    {
        $this->db->select('f_supplier_groupactive');
        $this->db->from('tr_supplier_group');
        $this->db->where('i_supplier_group', $id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $status = $query->row()->f_supplier_groupactive;
        } else {
            $status = 'f';
        }
        if ($status == 'f') {
            $fstatus = 't';
        } else {
            $fstatus = 'f';
        }
        $table = array(
            'f_supplier_groupactive' => $fstatus,
        );
        $this->db->where('i_supplier_group', $id);
        $this->db->update('tr_supplier_group', $table);
    }

    /** Cek Apakah Data Sudah Ada Pas Simpan */
    public function cek($kode)
    {
        return $this->db->query("
            SELECT 
                i_supplier_groupid
            FROM 
                tr_supplier_group
            WHERE 
                trim(upper(i_supplier_groupid)) = trim(upper('$kode'))
                AND i_company = '$this->i_company'
        ", FALSE);
    }

    /** Simpan Data */
    public function save($kode, $nama)
    {
        $table = array(
            'i_company'    => $this->i_company,
            'i_supplier_groupid' => $kode,
            'e_supplier_groupname' => $nama,
        );
        $this->db->insert('tr_supplier_group', $table);
    }

    /** Get Data Untuk Edit */
    public function getdata($id)
    {
        return $this->db->query("
            SELECT 
                *
            FROM 
                tr_supplier_group 
            WHERE
                i_supplier_group = '$id'
        ", FALSE);
    }

    /** Cek Apakah Data Sudah Ada Pas Edit */
    public function cek_edit($kode, $kodeold)
    {
        return $this->db->query("
            SELECT 
                i_supplier_groupid
            FROM 
                tr_supplier_group 
            WHERE 
                trim(upper(i_supplier_groupid)) <> trim(upper('$kodeold'))
                AND trim(upper(i_supplier_groupid)) = trim(upper('$kode'))
                AND i_company = '$this->i_company'
        ", FALSE);
    }

    /** Update Data */
    public function update($id, $kode, $nama)
    {
        $table = array(
            'i_supplier_groupid' => $kode,
            'e_supplier_groupname' => $nama,
            'd_supplier_groupupdate'  => date('Y-m-d H:i:s'),
        );
        $this->db->where('i_supplier_group', $id);
        $this->db->update('tr_supplier_group', $table);
    }
}

/* End of file Mmaster.php */
