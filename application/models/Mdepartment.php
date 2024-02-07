<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mdepartment extends CI_Model
{

    /** List Datatable */
    public function serverside()
    {
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
            Select i_department, i_department_id, e_department_name, e_deskripsi ,f_status from tr_department where i_department <> 1
            order by i_department desc
        ", FALSE);

        $datatables->edit('f_status', function ($data) {
            $id         = $data['i_department'];
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
                $id         = trim($data['i_department']);
                $data       = '';
                $data      .= "<a href='" . base_url() . $this->folder . '/edit/' . encrypt_url($id) . "' title='Edit Data'><i class='fa fa-check-circle-o success darken-4 fa-lg mr-1'></i></a>";
                return $data;
            });
        }
        return $datatables->generate();
    }

    public function changestatus($id)
    {
        $this->db->select('f_status');
        $this->db->from('tr_department');
        $this->db->where('i_department', $id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $status = $query->row()->f_status;
        } else {
            $status = 'f';
        }
        if ($status == 'f') {
            $fstatus = 't';
        } else {
            $fstatus = 'f';
        }
        $table = array(
            'f_status' => $fstatus,
        );
        $this->db->where('i_department', $id);
        $this->db->update('tr_department', $table);
    }

    /** Cek Apakah Data Sudah Ada Pas Simpan */
    public function cek($kode)
    {
        return $this->db->query("
            SELECT 
                i_department_id
            FROM 
                tr_department
            WHERE 
                trim(upper(i_department_id)) = trim(upper('$kode'))
        ", FALSE);
    }

    /** Simpan Data */
    public function save($kode, $nama, $deskripsi)
    {
        $table = array(
            'i_department_id' => $kode,
            'e_department_name' => $nama,
            'e_deskripsi'  => $deskripsi,
        );
        $this->db->insert('tr_department', $table);
    }

    /** Get Data Untuk Edit */
    public function getdata($id)
    {
        return $this->db->query("
            SELECT 
                *
            FROM 
                tr_department 
            WHERE
                i_department = '$id'
        ", FALSE);
    }

    /** Cek Apakah Data Sudah Ada Pas Edit */
    public function cek_edit($kode, $kodeold)
    {
        return $this->db->query("
            SELECT 
                i_department_id
            FROM 
                tr_department 
            WHERE 
                trim(upper(i_department_id)) <> trim(upper('$kodeold'))
                AND trim(upper(i_department_id)) = trim(upper('$kode'))
        ", FALSE);
    }

    /** Update Data */
    public function update($id, $kode, $nama, $deskripsi)
    {
        $table = array(
            'i_department_id' => $kode,
            'e_department_name' => $nama,
            'e_deskripsi'  => $deskripsi,
            'd_department_update'  => date('Y-m-d H:i:s'),
        );
        $this->db->where('i_department', $id);
        $this->db->update('tr_department', $table);
    }
}

/* End of file Mmaster.php */
