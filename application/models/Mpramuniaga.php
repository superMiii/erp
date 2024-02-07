<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mpramuniaga extends CI_Model
{

    /** List Datatable */
    public function serverside()
    {
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("SELECT
                i_salesman,
                i_salesman_id,
                e_salesman_name,
                f_salesman_active AS f_status
            FROM
                tr_salesman
            WHERE 
                i_company = '$this->i_company'
            ORDER BY
                4", FALSE);

        $datatables->edit('f_status', function ($data) {
            $id         = $data['i_salesman'];
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
                $id         = trim($data['i_salesman']);
                $data       = '';
                $data      .= "<a href='" . base_url() . $this->folder . '/edit/' . encrypt_url($id) . "' title='Edit Data'><i class='fa fa-pencil-square success darken-4 fa-lg mr-1'></i></a>";
                return $data;
            });
        }
        return $datatables->generate();
    }

    public function changestatus($id)
    {
        $this->db->select('f_salesman_active');
        $this->db->from('tr_salesman');
        $this->db->where('i_salesman', $id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $status = $query->row()->f_salesman_active;
        } else {
            $status = 'f';
        }
        if ($status == 'f') {
            $fstatus = 't';
        } else {
            $fstatus = 'f';
        }
        $table = array(
            'f_salesman_active' => $fstatus,
        );
        $this->db->where('i_salesman', $id);
        $this->db->update('tr_salesman', $table);
    }

    /** Cek Apakah Data Sudah Ada Pas Simpan */
    public function cek($i_salesman_id)
    {
        return $this->db->query("
            SELECT 
                i_salesman_id
            FROM 
                tr_salesman 
            WHERE 
                trim(upper(i_salesman_id)) = trim(upper('$i_salesman_id'))
                AND i_company = $this->i_company
        ", FALSE);
    }

    /** Simpan Data */
    public function save($i_salesman_id, $e_salesman_name)
    {
        $table = array(
            'i_company'         => $this->i_company,
            'i_salesman_id'     => $i_salesman_id,
            'e_salesman_name'   => $e_salesman_name,
            'd_salesman_entry'  => current_datetime(),
        );
        $this->db->insert('tr_salesman', $table);
    }

    /** Get Data Untuk Edit */
    public function getdata($id)
    {
        return $this->db->query("
            SELECT 
                *
            FROM 
                tr_salesman 
            WHERE
                i_salesman = '$id'
        ", FALSE);
    }

    /** Cek Apakah Data Sudah Ada Pas Edit */
    public function cek_edit($i_company_old, $i_salesman_id, $i_salesman_id_old)
    {
        return $this->db->query("
            SELECT 
                i_salesman_id
            FROM 
                tr_salesman 
            WHERE 
                trim(upper(i_salesman_id)) = trim(upper('$i_salesman_id'))
                AND trim(upper(i_salesman_id)) <> trim(upper('$i_salesman_id_old'))
                AND i_company = $this->i_company
                AND i_company <> $i_company_old
        ", FALSE);
    }

    /** Update Data */
    public function update($i_salesman, $i_salesman_id, $e_salesman_name)
    {
        $table = array(
            'i_company'         => $this->i_company,
            'i_salesman_id'     => $i_salesman_id,
            'e_salesman_name'   => $e_salesman_name,
            'd_salesman_update' => current_datetime(),
        );
        $this->db->where('i_salesman', $i_salesman);
        $this->db->update('tr_salesman', $table);
    }
}

/* End of file Mmaster.php */
