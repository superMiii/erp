<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mstatusbarang extends CI_Model
{

    /** List Datatable */
    public function serverside()
    {
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("SELECT
                i_product_status,
                i_product_statusid,
                e_product_statusname,
                f_product_statusactive AS f_status
            FROM
                tr_product_status
            WHERE 
                i_company = '$this->i_company'
            ORDER BY
                4", FALSE);

        $datatables->edit('f_status', function ($data) {
            $id         = $data['i_product_status'];
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
                $id         = trim($data['i_product_status']);
                $data       = '';
                $data      .= "<a href='" . base_url() . $this->folder . '/edit/' . encrypt_url($id) . "' title='Edit Data'><i class='fa fa-pencil-square success darken-4 fa-lg mr-1'></i></a>";
                return $data;
            });
        }
        return $datatables->generate();
    }

    public function changestatus($id)
    {
        $this->db->select('f_product_statusactive');
        $this->db->from('tr_product_status');
        $this->db->where('i_product_status', $id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $status = $query->row()->f_product_statusactive;
        } else {
            $status = 'f';
        }
        if ($status == 'f') {
            $fstatus = 't';
        } else {
            $fstatus = 'f';
        }
        $table = array(
            'f_product_statusactive' => $fstatus,
        );
        $this->db->where('i_product_status', $id);
        $this->db->update('tr_product_status', $table);
    }

    /** Cek Apakah Data Sudah Ada Pas Simpan */
    public function cek($i_product_statusid)
    {
        return $this->db->query("
            SELECT 
                i_product_statusid
            FROM 
                tr_product_status 
            WHERE 
                trim(upper(i_product_statusid)) = trim(upper('$i_product_statusid'))
                AND i_company = $this->i_company
        ", FALSE);
    }

    /** Simpan Data */
    public function save($i_product_statusid, $e_product_statusname)
    {
        $table = array(
            'i_company'              => $this->i_company,
            'i_product_statusid'     => $i_product_statusid,
            'e_product_statusname'   => $e_product_statusname,
            'd_product_statusentry'  => current_datetime(),
        );
        $this->db->insert('tr_product_status', $table);
    }

    /** Get Data Untuk Edit */
    public function getdata($id)
    {
        return $this->db->query("
            SELECT 
                *
            FROM 
                tr_product_status 
            WHERE
                i_product_status = '$id'
        ", FALSE);
    }

    /** Cek Apakah Data Sudah Ada Pas Edit */
    public function cek_edit($i_company_old, $i_product_statusid, $i_product_statusid_old)
    {
        return $this->db->query("
            SELECT 
                i_product_statusid
            FROM 
                tr_product_status 
            WHERE 
                trim(upper(i_product_statusid)) = trim(upper('$i_product_statusid'))
                AND trim(upper(i_product_statusid)) <> trim(upper('$i_product_statusid_old'))
                AND i_company = $this->i_company
                AND i_company <> $i_company_old
        ", FALSE);
    }

    /** Update Data */
    public function update($i_product_status, $i_product_statusid, $e_product_statusname)
    {
        $table = array(
            'i_company'              => $this->i_company,
            'i_product_statusid'     => $i_product_statusid,
            'e_product_statusname'   => $e_product_statusname,
            'd_product_statusupdate' => current_datetime(),
        );
        $this->db->where('i_product_status', $i_product_status);
        $this->db->update('tr_product_status', $table);
    }
}

/* End of file Mmaster.php */
