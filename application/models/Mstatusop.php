<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mstatusop extends CI_Model
{

    /** List Datatable */
    public function serverside()
    {
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("SELECT
                i_status_op,
                i_status_op_id,
                e_status_op_name,
                f_status_op_active AS f_status
            FROM
                tr_status_op
            WHERE 
                i_company = '$this->i_company'
            ORDER BY
                4", FALSE);

        $datatables->edit('f_status', function ($data) {
            $id         = $data['i_status_op'];
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
                $id         = trim($data['i_status_op']);
                $data       = '';
                $data      .= "<a href='" . base_url() . $this->folder . '/edit/' . encrypt_url($id) . "' title='Edit Data'><i class='icon-note'></i></a>";
                return $data;
            });
        }
        return $datatables->generate();
    }

    public function changestatus($id)
    {
        $this->db->select('f_status_op_active');
        $this->db->from('tr_status_op');
        $this->db->where('i_status_op', $id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $status = $query->row()->f_status_op_active;
        } else {
            $status = 'f';
        }
        if ($status == 'f') {
            $fstatus = 't';
        } else {
            $fstatus = 'f';
        }
        $table = array(
            'f_status_op_active' => $fstatus,
        );
        $this->db->where('i_status_op', $id);
        $this->db->update('tr_status_op', $table);
    }

    /** Cek Apakah Data Sudah Ada Pas Simpan */
    public function cek($i_status_op_id)
    {
        return $this->db->query("
            SELECT 
                i_status_op_id
            FROM 
                tr_status_op 
            WHERE 
                trim(upper(i_status_op_id)) = trim(upper('$i_status_op_id'))
                AND i_company = $this->i_company
        ", FALSE);
    }

    /** Simpan Data */
    public function save($i_status_op_id, $e_status_op_name)
    {
        $table = array(
            'i_company'         => $this->i_company,
            'i_status_op_id'     => $i_status_op_id,
            'e_status_op_name'   => $e_status_op_name,
            'd_status_op_entry'  => current_datetime(),
        );
        $this->db->insert('tr_status_op', $table);
    }

    /** Get Data Untuk Edit */
    public function getdata($id)
    {
        return $this->db->query("
            SELECT 
                *
            FROM 
                tr_status_op 
            WHERE
                i_status_op = '$id'
        ", FALSE);
    }

    /** Cek Apakah Data Sudah Ada Pas Edit */
    public function cek_edit($i_company_old, $i_status_op_id, $i_status_op_id_old)
    {
        return $this->db->query("
            SELECT 
                i_status_op_id
            FROM 
                tr_status_op 
            WHERE 
                trim(upper(i_status_op_id)) = trim(upper('$i_status_op_id'))
                AND trim(upper(i_status_op_id)) <> trim(upper('$i_status_op_id_old'))
                AND i_company = $this->i_company
                AND i_company <> $i_company_old
        ", FALSE);
    }

    /** Update Data */
    public function update($i_status_op, $i_status_op_id, $e_status_op_name)
    {
        $table = array(
            'i_company'         => $this->i_company,
            'i_status_op_id'     => $i_status_op_id,
            'e_status_op_name'   => $e_status_op_name,
            'd_status_op_update' => current_datetime(),
        );
        $this->db->where('i_status_op', $i_status_op);
        $this->db->update('tr_status_op', $table);
    }
}

/* End of file Mmaster.php */
