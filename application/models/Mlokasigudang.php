<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mlokasigudang extends CI_Model
{

    /** List Datatable */
    public function serverside()
    {
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("SELECT
                i_store_loc AS id,
                i_store_loc_id,
                e_store_loc_name,
                e_store_name,
                f_store_loc_active AS f_status
            FROM
                tr_store_loc a
            INNER JOIN tr_store b ON
                (b.i_store = a.i_store)
            WHERE
                a.i_company = '$this->i_company'
            ORDER BY
                i_store_loc 
        ", FALSE);

        $datatables->edit('f_status', function ($data) {
            $id         = $data['id'];
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
                $id         = trim($data['id']);
                $data       = '';
                $data      .= "<a href='" . base_url() . $this->folder . '/edit/' . encrypt_url($id) . "' title='Edit Data'><i class='fa fa-pencil-square success darken-4 fa-lg mr-1'></i></a>";
                return $data;
            });
        }
        //$datatables->hide();
        return $datatables->generate();
    }

    public function changestatus($id)
    {
        $this->db->select('f_store_loc_active');
        $this->db->from('tr_store_loc');
        $this->db->where('i_store_loc', $id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $status = $query->row()->f_store_loc_active;
        } else {
            $status = 'f';
        }
        if ($status == 'f') {
            $fstatus = 't';
        } else {
            $fstatus = 'f';
        }
        $table = array(
            'f_store_loc_active' => $fstatus,
        );
        $this->db->where('i_store_loc', $id);
        $this->db->update('tr_store_loc', $table);
    }

    /** Cek Apakah Data Sudah Ada Pas Simpan */
    public function cek($kode)
    {
        return $this->db->query("
            SELECT 
                i_store_loc_id
            FROM 
                tr_store_loc
            WHERE 
                trim(upper(i_store_loc_id)) = trim(upper('$kode'))
                AND i_company = '$this->i_company'
        ", FALSE);
    }

    /** Cari store */
    public function get_store($cari)
    {
        return $this->db->query("
            SELECT 
                i_store , i_store_id , e_store_name 
            FROM 
                tr_store tsg 
            WHERE 
                (e_store_name ILIKE '%$cari%' or i_store_id ILIKE '%$cari%')
                and i_company = '$this->i_company' and f_store_active = true
            ORDER BY 3 ASC
        ", FALSE);
    }
    /** Simpan Data */
    public function save($i_store_loc_id, $e_store_loc_name, $i_store)
    {
        $table = array(
            'i_company'    => $this->i_company,
            'i_store_loc_id' => $i_store_loc_id,
            'e_store_loc_name' => $e_store_loc_name,
            'i_store' => $i_store,
            'd_store_loc_entry' => current_datetime(),
        );
        $this->db->insert('tr_store_loc', $table);
    }

    /** Get Data Untuk Edit */
    public function getdata($id)
    {
        return $this->db->query("
            SELECT 
                a.*, b.i_store_id, b.e_store_name
            FROM 
                tr_store_loc a
            LEFT JOIN tr_store b ON 
                (a.i_store = b.i_store)
            WHERE
                a.i_store_loc = '$id'
        ", FALSE);
    }

    /** Cek Apakah Data Sudah Ada Pas Edit */
    public function cek_edit($kode, $kodeold)
    {
        return $this->db->query("
            SELECT 
                i_store_loc_id
            FROM 
                tr_store_loc 
            WHERE 
                trim(upper(i_store_loc_id)) <> trim(upper('$kodeold'))
                AND trim(upper(i_store_loc_id)) = trim(upper('$kode'))
                AND i_company = '$this->i_company'
        ", FALSE);
    }

    /** Update Data */
    public function update($id, $i_store_loc_id, $e_store_loc_name, $i_store)
    {
        $table = array(
            'i_company'    => $this->i_company,
            'i_store_loc_id' => $i_store_loc_id,
            'e_store_loc_name' => $e_store_loc_name,
            'i_store' => $i_store,
            'd_store_loc_entry' => current_datetime(),
        );
        $this->db->where('i_store_loc', $id);
        $this->db->update('tr_store_loc', $table);
    }
}

/* End of file Mmaster.php */
