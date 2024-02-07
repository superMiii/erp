<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mgudang extends CI_Model
{

    /** List Datatable */
    public function serverside()
    {
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("SELECT
                i_store,
                i_store_id,
                e_store_name,
                case when f_store_pusat = true then 'PUSAT' else 'Cabang' end as pemenuhan,
                f_store_active AS f_status
            FROM
                tr_store
            WHERE 
                i_company = '$this->i_company'
            ORDER BY
                4", FALSE);

        $datatables->edit('f_status', function ($data) {
            $id         = $data['i_store'];
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
                $id         = trim($data['i_store']);
                $data       = '';
                $data      .= "<a href='" . base_url() . $this->folder . '/edit/' . encrypt_url($id) . "' title='Edit Data'><i class='fa fa-pencil-square success darken-4 fa-lg mr-1'></i></a>";
                return $data;
            });
        }
        return $datatables->generate();
    }

    public function changestatus($id)
    {
        $this->db->select('f_store_active');
        $this->db->from('tr_store');
        $this->db->where('i_store', $id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $status = $query->row()->f_store_active;
        } else {
            $status = 'f';
        }
        if ($status == 'f') {
            $fstatus = 't';
        } else {
            $fstatus = 'f';
        }
        $table = array(
            'f_store_active' => $fstatus,
        );
        $this->db->where('i_store', $id);
        $this->db->update('tr_store', $table);
    }

    /** Cek Apakah Data Sudah Ada Pas Simpan */
    public function cek($i_store_id)
    {
        return $this->db->query("
            SELECT 
                i_store_id
            FROM 
                tr_store 
            WHERE 
                trim(upper(i_store_id)) = trim(upper('$i_store_id'))
                AND i_company = $this->i_company
        ", FALSE);
    }

    /** Simpan Data */
    public function save($i_store_id, $e_store_name, $pusat)
    {
        $table = array(
            'i_company'         => $this->i_company,
            'i_store_id'     => $i_store_id,
            'e_store_name'   => $e_store_name,
            'f_store_pusat'   => $pusat,
            'd_store_entry'  => current_datetime(),
        );
        $this->db->insert('tr_store', $table);
    }

    /** Get Data Untuk Edit */
    public function getdata($id)
    {
        return $this->db->query("
            SELECT 
                *
            FROM 
                tr_store 
            WHERE
                i_store = '$id'
        ", FALSE);
    }

    /** Cek Apakah Data Sudah Ada Pas Edit */
    public function cek_edit($i_company_old, $i_store_id, $i_store_id_old)
    {
        return $this->db->query("
            SELECT 
                i_store_id
            FROM 
                tr_store 
            WHERE 
                trim(upper(i_store_id)) = trim(upper('$i_store_id'))
                AND trim(upper(i_store_id)) <> trim(upper('$i_store_id_old'))
                AND i_company = $this->i_company
                AND i_company <> $i_company_old
        ", FALSE);
    }

    /** Update Data */
    public function update($i_store, $i_store_id, $e_store_name, $pusat)
    {
        $table = array(
            'i_company'         => $this->i_company,
            'i_store_id'     => $i_store_id,
            'e_store_name'   => $e_store_name,
            'f_store_pusat'   => $pusat,
            'd_store_update' => current_datetime(),
        );
        $this->db->where('i_store', $i_store);
        $this->db->update('tr_store', $table);
    }
}

/* End of file Mmaster.php */
