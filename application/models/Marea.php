<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Marea extends CI_Model
{

    /** List Datatable */
    public function serverside()
    {
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
            select i_area , i_area_id , e_area_name , b.e_store_name , f_area_active 
from tr_area a 
        left join tr_store b on (a.i_store = b.i_store)
        where a.i_company = '$this->i_company'
        order by i_area desc
        ", FALSE);

        $datatables->edit('f_area_active', function ($data) {
            $id         = $data['i_area'];
            if ($data['f_area_active'] == 't') {
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
                $id         = trim($data['i_area']);
                $data       = '';
                $data      .= "<a href='" . base_url() . $this->folder . '/edit/' . encrypt_url($id) . "' title='Edit Data'><i class='fa fa-pencil-square success darken-4 fa-lg mr-1'></i></a>";
                return $data;
            });
        }
        return $datatables->generate();
    }

    public function changestatus($id)
    {
        $this->db->select('f_area_active');
        $this->db->from('tr_area');
        $this->db->where('i_area', $id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $status = $query->row()->f_area_active;
        } else {
            $status = 'f';
        }
        if ($status == 'f') {
            $fstatus = 't';
        } else {
            $fstatus = 'f';
        }
        $table = array(
            'f_area_active' => $fstatus,
        );
        $this->db->where('i_area', $id);
        $this->db->update('tr_area', $table);
    }

    /** Cek Apakah Data Sudah Ada Pas Simpan */
    public function cek($kode)
    {
        return $this->db->query("
            SELECT 
                i_area_id
            FROM 
                tr_area
            WHERE 
                trim(upper(i_area_id)) = trim(upper('$kode'))
                AND i_company = '$this->i_company'
        ", FALSE);
    }

    public function get_store($cari)
    {
        return $this->db->query("
            SELECT 
                i_store , i_store_id , e_store_name 
            FROM 
                tr_store 
            WHERE 
                (e_store_name ILIKE '%$cari%' or i_store_id ILIKE '%$cari%')
                and i_company = '$this->i_company' and f_store_active = true /*and f_store_pusat = 'f'*/
            ORDER BY 3 ASC
        ", FALSE);
    }

    /** Simpan Data */
    public function save($kode, $nama, $istore)
    {
        $table = array(
            'i_company'    => $this->i_company,
            'i_area_id' => $kode,
            'e_area_name' => $nama,
            'i_store' => $istore
        );
        $this->db->insert('tr_area', $table);
    }

    /** Get Data Untuk Edit */
    public function getdata($id)
    {
        return $this->db->query("
            SELECT a.*, b.i_store_id, b.e_store_name FROM tr_area a
            left join tr_store b on (a.i_store = b.i_store)
            where a.i_area = '$id'
        ", FALSE);
    }

    /** Cek Apakah Data Sudah Ada Pas Edit */
    public function cek_edit($kode, $kodeold)
    {
        return $this->db->query("
            SELECT 
                i_area_id
            FROM 
                tr_area 
            WHERE 
                trim(upper(i_area_id)) <> trim(upper('$kodeold'))
                AND trim(upper(i_area_id)) = trim(upper('$kode'))
                AND i_company = '$this->i_company'
        ", FALSE);
    }

    /** Update Data */
    public function update($id, $kode, $nama, $istore)
    {
        $table = array(
            'i_area_id' => $kode,
            'e_area_name' => $nama,
            'i_store' => $istore,
            'd_area_update'  => date('Y-m-d H:i:s'),
        );
        $this->db->where('i_area', $id);
        $this->db->update('tr_area', $table);
    }
}

/* End of file Mmaster.php */
