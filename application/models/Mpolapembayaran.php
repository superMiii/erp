<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mpolapembayaran extends CI_Model
{

    /** List Datatable */
    public function serverside()
    {
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
            select i_customer_payment , i_customer_paymentid , 
			e_customer_paymentname , f_customer_paymentactive 
			from tr_customer_payment where i_company = '$this->i_company'
            order by i_customer_payment desc
        ", FALSE);

        $datatables->edit('f_customer_paymentactive', function ($data) {
            $id         = $data['i_customer_payment'];
            if ($data['f_customer_paymentactive'] == 't') {
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
                $id         = trim($data['i_customer_payment']);
                $data       = '';
                $data      .= "<a href='" . base_url() . $this->folder . '/edit/' . encrypt_url($id) . "' title='Edit Data'><i class='fa fa-pencil-square success darken-4 fa-lg mr-1'></i></a>";
                return $data;
            });
        }
        return $datatables->generate();
    }

    public function changestatus($id)
    {
        $this->db->select('f_customer_paymentactive');
        $this->db->from('tr_customer_payment');
        $this->db->where('i_customer_payment', $id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $status = $query->row()->f_customer_paymentactive;
        } else {
            $status = 'f';
        }
        if ($status == 'f') {
            $fstatus = 't';
        } else {
            $fstatus = 'f';
        }
        $table = array(
            'f_customer_paymentactive' => $fstatus,
        );
        $this->db->where('i_customer_payment', $id);
        $this->db->update('tr_customer_payment', $table);
    }

    /** Cek Apakah Data Sudah Ada Pas Simpan */
    public function cek($kode)
    {
        return $this->db->query("
            SELECT 
                i_customer_paymentid
            FROM 
                tr_customer_payment
            WHERE 
                trim(upper(i_customer_paymentid)) = trim(upper('$kode'))
                AND i_company = '$this->i_company'
        ", FALSE);
    }

    /** Simpan Data */
    public function save($kode, $nama)
    {
        $table = array(
            'i_company'    => $this->i_company,
            'i_customer_paymentid' => $kode,
            'e_customer_paymentname' => $nama,
            'd_customer_paymententry'  => date('Y-m-d H:i:s'),
        );
        $this->db->insert('tr_customer_payment', $table);
    }

    /** Get Data Untuk Edit */
    public function getdata($id)
    {
        return $this->db->query("
            SELECT 
                *
            FROM 
                tr_customer_payment 
            WHERE
                i_customer_payment = '$id'
        ", FALSE);
    }

    /** Cek Apakah Data Sudah Ada Pas Edit */
    public function cek_edit($kode, $kodeold)
    {
        return $this->db->query("
            SELECT 
                i_customer_paymentid
            FROM 
                tr_customer_payment 
            WHERE 
                trim(upper(i_customer_paymentid)) <> trim(upper('$kodeold'))
                AND trim(upper(i_customer_paymentid)) = trim(upper('$kode'))
                AND i_company = '$this->i_company'
        ", FALSE);
    }

    /** Update Data */
    public function update($id, $kode, $nama)
    {
        $table = array(
            'i_customer_paymentid' => $kode,
            'e_customer_paymentname' => $nama,
            'd_customer_paymentupdate'  => date('Y-m-d H:i:s'),
        );
        $this->db->where('i_customer_payment', $id);
        $this->db->update('tr_customer_payment', $table);
    }
}

/* End of file Mmaster.php */
