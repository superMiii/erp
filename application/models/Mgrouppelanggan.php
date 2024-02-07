<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mgrouppelanggan extends CI_Model
{

    /** List Datatable */
    public function serverside()
    {
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
            select i_customer_group , i_customer_groupid , 
			e_customer_groupname , f_customer_groupactive 
			from tr_customer_group where i_company = '$this->i_company'
            order by i_customer_group desc
        ", FALSE);

        $datatables->edit('f_customer_groupactive', function ($data) {
            $id         = $data['i_customer_group'];
            if ($data['f_customer_groupactive'] == 't') {
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
                $id         = trim($data['i_customer_group']);
                $data       = '';
                $data      .= "<a href='" . base_url() . $this->folder . '/edit/' . encrypt_url($id) . "' title='Edit Data'><i class='fa fa-pencil-square success darken-4 fa-lg mr-1'></i></a>";
                return $data;
            });
        }
        return $datatables->generate();
    }

    public function changestatus($id)
    {
        $this->db->select('f_customer_groupactive');
        $this->db->from('tr_customer_group');
        $this->db->where('i_customer_group', $id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $status = $query->row()->f_customer_groupactive;
        } else {
            $status = 'f';
        }
        if ($status == 'f') {
            $fstatus = 't';
        } else {
            $fstatus = 'f';
        }
        $table = array(
            'f_customer_groupactive' => $fstatus,
        );
        $this->db->where('i_customer_group', $id);
        $this->db->update('tr_customer_group', $table);
    }

    /** Cek Apakah Data Sudah Ada Pas Simpan */
    public function cek($kode)
    {
        return $this->db->query("
            SELECT 
                i_customer_groupid
            FROM 
                tr_customer_group
            WHERE 
                trim(upper(i_customer_groupid)) = trim(upper('$kode'))
                AND i_company = '$this->i_company'
        ", FALSE);
    }

    /** Simpan Data */
    public function save($kode, $nama)
    {
        $table = array(
            'i_company'    => $this->i_company,
            'i_customer_groupid' => $kode,
            'e_customer_groupname' => $nama,
            'd_customer_groupentry'  => date('Y-m-d H:i:s'),
        );
        $this->db->insert('tr_customer_group', $table);
    }

    /** Get Data Untuk Edit */
    public function getdata($id)
    {
        return $this->db->query("
            SELECT 
                *
            FROM 
                tr_customer_group 
            WHERE
                i_customer_group = '$id'
        ", FALSE);
    }

    /** Cek Apakah Data Sudah Ada Pas Edit */
    public function cek_edit($kode, $kodeold)
    {
        return $this->db->query("
            SELECT 
                i_customer_groupid
            FROM 
                tr_customer_group 
            WHERE 
                trim(upper(i_customer_groupid)) <> trim(upper('$kodeold'))
                AND trim(upper(i_customer_groupid)) = trim(upper('$kode'))
                AND i_company = '$this->i_company'
        ", FALSE);
    }

    /** Update Data */
    public function update($id, $kode, $nama)
    {
        $table = array(
            'i_customer_groupid' => $kode,
            'e_customer_groupname' => $nama,
            'd_customer_groupupdate'  => date('Y-m-d H:i:s'),
        );
        $this->db->where('i_customer_group', $id);
        $this->db->update('tr_customer_group', $table);
    }
}

/* End of file Mmaster.php */
