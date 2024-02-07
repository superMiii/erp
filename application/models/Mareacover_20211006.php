<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mareacover extends CI_Model
{

    /** List Datatable */
    public function serverside()
    {
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("SELECT
            DISTINCT
                a.i_area_cover AS id,
                i_area_cover_id,
                e_area_cover_name,
                b.e_area_name,
                a.f_area_cover_active AS f_status
            FROM
                tr_area_cover a
            LEFT JOIN tr_area_cover_item ab ON
                (ab.i_area_cover = a.i_area_cover)
            LEFT JOIN tr_area b ON
                (b.i_area = ab.i_area)
            WHERE
                a.i_company = '$this->i_company'
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
                $data      .= "<a href='" . base_url() . $this->folder . '/view/' . encrypt_url($id) . "' title='View Data'><i class='fa fa-eye fa-lg warning darken-4 mr-1'></i></a>";
                $data      .= "<a href='" . base_url() . $this->folder . '/edit/' . encrypt_url($id) . "' title='Edit Data'><i class='fa fa-pencil success darken-4 fa-lg mr-1'></i></a>";
                return $data;
            });
        }
        return $datatables->generate();
    }

    public function changestatus($id)
    {
        $this->db->select('f_area_cover_active');
        $this->db->from('tr_area_cover');
        $this->db->where('i_area_cover', $id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $status = $query->row()->f_area_cover_active;
        } else {
            $status = 'f';
        }
        if ($status == 'f') {
            $fstatus = 't';
        } else {
            $fstatus = 'f';
        }
        $table = array(
            'f_area_cover_active' => $fstatus,
        );
        $this->db->where('i_area_cover', $id);
        $this->db->update('tr_area_cover', $table);
    }

    /** Cek Apakah Data Sudah Ada Pas Simpan */
    public function cek($kode)
    {
        return $this->db->query("
            SELECT 
                i_area_cover_id
            FROM 
            tr_area_cover
            WHERE 
                trim(upper(i_area_cover_id)) = trim(upper('$kode'))
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

    /** Cari City */
    public function get_city($i_area, $cari)
    {
        return $this->db->query("
            SELECT 
                i_city , i_city_id , e_city_name 
            FROM 
                tr_city tsg 
            WHERE 
                (e_city_name ILIKE '%$cari%' OR i_city_id ILIKE '%$cari%')
                AND i_company = '$this->i_company' AND i_area = '$i_area' 
                AND f_city_active = true
            ORDER BY 3 ASC
        ", FALSE);
    }

    /** Simpan Data */
    public function save()
    {
        $query = $this->db->query("SELECT max(i_area_cover)+1 AS id FROM tr_area_cover", TRUE);
        if ($query->num_rows() > 0) {
            $id = $query->row()->id;
            if ($id == null) {
                $id = 1;
            } else {
                $id = $id;
            }
        } else {
            $id = 1;
        }
        $table = array(
            'i_company'           => $this->i_company,
            'i_area_cover'        => $id,
            'i_area_cover_id'     => strtoupper($this->input->post('kode')),
            'e_area_cover_name'   => ucwords(strtolower($this->input->post('nama'))),
        );
        $this->db->insert('tr_area_cover', $table);

        $i_city = $this->input->post('i_city');
        $i_area = $this->input->post('i_area');
        $f_all_city = $this->input->post('f_all_city');
        if ($f_all_city=='on') {
            $sql = $this->db->get_where('tr_city',['f_city_active'=>true, 'i_company'=> $this->i_company, 'i_area'=> $i_area]);
            if ($sql->num_rows()>0) {
                foreach ($sql as $key) {
                    $table = array(
                        'i_area_cover'        => $id,
                        'i_area'              => $i_area,
                        'i_city'              => $key->i_city,
                    );
                    $this->db->insert('tr_area_cover_item', $table);
                }
            }
        }else{
            if (is_array($i_city) || is_object($i_city)) {
                foreach ($i_city as $city) {
                    $table = array(
                        'i_area_cover'        => $id,
                        'i_area'              => $i_area,
                        'i_city'              => $city,
                    );
                    $this->db->insert('tr_area_cover_item', $table);
                }
            }
        }       
    }

    /** Get Data Untuk Edit */
    public function getdata($id)
    {
        return $this->db->query("SELECT
                DISTINCT 
                a.*,
                c.i_area,
                c.i_area_id,
                c.e_area_name
            FROM
                tr_area_cover a
            LEFT JOIN tr_area_cover_item b ON
                (b.i_area_cover = a.i_area_cover)
            LEFT JOIN tr_area c ON
                (c.i_area = b.i_area)
            WHERE
                a.i_area_cover = '$id'
        ", FALSE);
    }

    /** Get Data Untuk Edit */
    public function getdatacity($id)
    {
        return $this->db->query("SELECT
                DISTINCT 
                a.*,
                b.i_city_id,
                b.e_city_name
            FROM
                tr_area_cover_item a
            LEFT JOIN tr_city b ON
                (b.i_city = a.i_city)
            WHERE
                a.i_area_cover = '$id'
        ", FALSE);
    }

    /** Cek Apakah Data Sudah Ada Pas Edit */
    public function cek_edit($kode, $kodeold)
    {
        return $this->db->query("
            SELECT 
                i_area_cover_id
            FROM 
                tr_area_cover 
            WHERE 
                trim(upper(i_area_cover_id)) <> trim(upper('$kodeold'))
                AND trim(upper(i_area_cover_id)) = trim(upper('$kode'))
                AND i_company = '$this->i_company'
        ", FALSE);
    }

    /** Update Data */
    public function update()
    {
        $table = array(
            'i_company'           => $this->i_company,
            'i_area'              => $this->input->post('i_area'),
            'i_city'              => $this->input->post('i_city'),
            'i_area_cover_id'     => strtoupper($this->input->post('kode')),
            'e_area_cover_name'   => ucwords(strtolower($this->input->post('nama'))),
            'd_area_cover_update' => current_datetime(),
        );
        $this->db->where('i_area_cover', $this->input->post('id'));
        $this->db->update('tr_area_cover', $table);
    }
}

/* End of file Mmaster.php */