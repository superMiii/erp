<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mstatuspelanggan extends CI_Model
{

    /** List Datatable */
    public function serverside()
    {
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
            select i_customer_status , i_customer_statusid , 
			e_customer_statusname , f_customer_statusactive 
			from tr_customer_status where i_company = '$this->i_company'
            order by i_customer_status desc
        ", FALSE);

        $datatables->edit('f_customer_statusactive', function ($data) {
            $id         = $data['i_customer_status'];
            if ($data['f_customer_statusactive'] == 't') {
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
                $id         = trim($data['i_customer_status']);
                $data       = '';
                $data      .= "<a href='" . base_url() . $this->folder . '/edit/' . encrypt_url($id) . "' title='Edit Data'><i class='fa fa-pencil-square success darken-4 fa-lg mr-1'></i></a>";
                return $data;
            });
        }
        return $datatables->generate();
    }

    public function changestatus($id)
    {
        $this->db->select('f_customer_statusactive');
        $this->db->from('tr_customer_status');
        $this->db->where('i_customer_status', $id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $status = $query->row()->f_customer_statusactive;
        } else {
            $status = 'f';
        }
        if ($status == 'f') {
            $fstatus = 't';
        } else {
            $fstatus = 'f';
        }
        $table = array(
            'f_customer_statusactive' => $fstatus,
        );
        $this->db->where('i_customer_status', $id);
        $this->db->update('tr_customer_status', $table);
    }

    /** Cek Apakah Data Sudah Ada Pas Simpan */
    public function cek($kode)
    {
        return $this->db->query("
            SELECT 
                i_customer_statusid
            FROM 
                tr_customer_status
            WHERE 
                trim(upper(i_customer_statusid)) = trim(upper('$kode'))
                AND i_company = '$this->i_company'
        ", FALSE);
    }

    /** Simpan Data */
    public function save($kode, $nama)
    {
        $table = array(
            'i_company'    => $this->i_company,
            'i_customer_statusid' => $kode,
            'e_customer_statusname' => $nama,
            'd_customer_statusentry'  => date('Y-m-d H:i:s'),
        );
        $this->db->insert('tr_customer_status', $table);
    }

    /** Get Data Untuk Edit */
    public function getdata($id)
    {
        return $this->db->query("
            SELECT 
                *
            FROM 
                tr_customer_status 
            WHERE
                i_customer_status = '$id'
        ", FALSE);
    }

    /** Cek Apakah Data Sudah Ada Pas Edit */
    public function cek_edit($kode, $kodeold)
    {
        return $this->db->query("
            SELECT 
                i_customer_statusid
            FROM 
                tr_customer_status 
            WHERE 
                trim(upper(i_customer_statusid)) <> trim(upper('$kodeold'))
                AND trim(upper(i_customer_statusid)) = trim(upper('$kode'))
                AND i_company = '$this->i_company'
        ", FALSE);
    }

    /** Update Data */
    public function update($id, $kode, $nama)
    {
        $table = array(
            'i_customer_statusid' => $kode,
            'e_customer_statusname' => $nama,
            'd_customer_statusupdate'  => date('Y-m-d H:i:s'),
        );
        $this->db->where('i_customer_status', $id);
        $this->db->update('tr_customer_status', $table);
    }
}

/* End of file Mmaster.php */
