<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mjenispromo extends CI_Model
{

    /** List Datatable */
    public function serverside()
    {
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("SELECT
                i_promo_type,
                i_promo_type_id,
                e_promo_type_name,
                f_promo_type_active AS f_status
            FROM
                tr_promo_type
            WHERE 
                i_company = '$this->i_company'
            ORDER BY
                4", FALSE);

        $datatables->edit('f_status', function ($data) {
            $id         = $data['i_promo_type'];
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
                $id         = trim($data['i_promo_type']);
                $data       = '';
                $data      .= "<a href='" . base_url() . $this->folder . '/edit/' . encrypt_url($id) . "' title='Edit Data'><i class='fa fa-pencil-square success darken-4 fa-lg mr-1'></i></a>";
                return $data;
            });
        }
        return $datatables->generate();
    }

    public function changestatus($id)
    {
        $this->db->select('f_promo_type_active');
        $this->db->from('tr_promo_type');
        $this->db->where('i_promo_type', $id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $status = $query->row()->f_promo_type_active;
        } else {
            $status = 'f';
        }
        if ($status == 'f') {
            $fstatus = 't';
        } else {
            $fstatus = 'f';
        }
        $table = array(
            'f_promo_type_active' => $fstatus,
        );
        $this->db->where('i_promo_type', $id);
        $this->db->update('tr_promo_type', $table);
    }

    /** Cek Apakah Data Sudah Ada Pas Simpan */
    public function cek($i_promo_type_id)
    {
        return $this->db->query("
            SELECT 
                i_promo_type_id
            FROM 
                tr_promo_type 
            WHERE 
                trim(upper(i_promo_type_id)) = trim(upper('$i_promo_type_id'))
                AND i_company = $this->i_company
        ", FALSE);
    }

    /** Simpan Data */
    public function save($i_promo_type_id, $e_promo_type_name)
    {
        $table = array(
            'i_company'         => $this->i_company,
            'i_promo_type_id'     => $i_promo_type_id,
            'e_promo_type_name'   => $e_promo_type_name,
            'd_promo_type_entry'  => current_datetime(),
        );
        $this->db->insert('tr_promo_type', $table);
    }

    /** Get Data Untuk Edit */
    public function getdata($id)
    {
        return $this->db->query("
            SELECT 
                *
            FROM 
                tr_promo_type 
            WHERE
                i_promo_type = '$id'
        ", FALSE);
    }

    /** Cek Apakah Data Sudah Ada Pas Edit */
    public function cek_edit($i_company_old, $i_promo_type_id, $i_promo_type_id_old)
    {
        return $this->db->query("
            SELECT 
                i_promo_type_id
            FROM 
                tr_promo_type 
            WHERE 
                trim(upper(i_promo_type_id)) = trim(upper('$i_promo_type_id'))
                AND trim(upper(i_promo_type_id)) <> trim(upper('$i_promo_type_id_old'))
                AND i_company = $this->i_company
                AND i_company <> $i_company_old
        ", FALSE);
    }

    /** Update Data */
    public function update($i_promo_type, $i_promo_type_id, $e_promo_type_name)
    {
        $table = array(
            'i_company'         => $this->i_company,
            'i_promo_type_id'     => $i_promo_type_id,
            'e_promo_type_name'   => $e_promo_type_name,
            'd_promo_type_update' => current_datetime(),
        );
        $this->db->where('i_promo_type', $i_promo_type);
        $this->db->update('tr_promo_type', $table);
    }
}

/* End of file Mmaster.php */
