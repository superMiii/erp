<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mcity extends CI_Model
{

    /** List Datatable */
    public function serverside()
    {
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
            select a.i_city, a.i_city_id, a.e_city_name,  b.e_area_name, a.n_toleransi , a.f_city_active 
            from tr_city a
            left join tr_area b on (a.i_area = b.i_area)
            where a.i_company = '$this->i_company'  order by a.i_city desc
        ", FALSE);

        $datatables->edit('f_city_active', function ($data) {
            $id         = $data['i_city'];
            if ($data['f_city_active'] == 't') {
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
                $id         = trim($data['i_city']);
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
        $this->db->select('f_city_active');
        $this->db->from('tr_city');
        $this->db->where('i_city', $id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $status = $query->row()->f_city_active;
        } else {
            $status = 'f';
        }
        if ($status == 'f') {
            $fstatus = 't';
        } else {
            $fstatus = 'f';
        }
        $table = array(
            'f_city_active' => $fstatus,
        );
        $this->db->where('i_city', $id);
        $this->db->update('tr_city', $table);
    }

    /** Cek Apakah Data Sudah Ada Pas Simpan */
    public function cek($kode)
    {
        return $this->db->query("
            SELECT 

                i_city_id
            FROM 
                tr_city
            WHERE 
                trim(upper(i_city_id)) = trim(upper('$kode'))
                AND i_company = '$this->i_company'
        ", FALSE);
    }

    /** Cari area */
    public function get_area($cari)
    {
        return $this->db->query("
             SELECT 
                i_area , i_area_id , e_area_name 
            FROM 
                tr_area tsg 
            WHERE 
                (e_area_name ILIKE '%$cari%' or i_area_id ILIKE '%$cari%')
                and i_company = '$this->i_company' and f_area_active = true
            ORDER BY 3 ASC
        ", FALSE);
    }
    /** Simpan Data */
    public function save($kode, $nama, $iarea, $ttop)
    {
        $table = array(
            'i_company'    => $this->i_company,
            'i_city_id' => $kode,
            'e_city_name' => $nama,
            'i_area' => $iarea,
            'n_toleransi' => $ttop,
            'd_city_entry'  => date('Y-m-d H:i:s'),
        );
        $this->db->insert('tr_city', $table);
    }

    /** Get Data Untuk Edit */
    public function getdata($id)
    {
        return $this->db->query("
            SELECT 
                a.*, b.i_area_id, b.e_area_name
            FROM 
                tr_city a
                left join tr_area b on (a.i_area = b.i_area)
            WHERE
                a.i_city = '$id'
        ", FALSE);
    }

    /** Cek Apakah Data Sudah Ada Pas Edit */
    public function cek_edit($kode, $kodeold)
    {
        return $this->db->query("
            SELECT 
                i_city_id
            FROM 
                tr_city 
            WHERE 
                trim(upper(i_city_id)) <> trim(upper('$kodeold'))
                AND trim(upper(i_city_id)) = trim(upper('$kode'))
                AND i_company = '$this->i_company'
        ", FALSE);
    }

    /** Update Data */
    public function update($id, $iarea, $kode, $nama, $ttop)
    {
        $table = array(
            'i_city_id' => $kode,
            'e_city_name' => $nama,
            'i_area' => $iarea,
            'n_toleransi' => $ttop,
            'd_city_update'  => date('Y-m-d H:i:s'),
        );
        $this->db->where('i_city', $id);
        $this->db->update('tr_city', $table);
    }
}

/* End of file Mmaster.php */
