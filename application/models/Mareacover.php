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
                i_area_cover AS id,
                i_area_cover_id,
                e_area_cover_name,
                f_area_cover_active AS f_status
            FROM
                tr_area_cover
            WHERE 
                i_company = '$this->i_company'
            ORDER BY
                e_area_cover_name 
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
        $datatables->add('action', function ($data) {
            $id         = trim($data['id']);
            $data       = '';
            $data      .= "<a href='" . base_url() . $this->folder . '/view/' . encrypt_url($id) . "' title='View Data'><i class='fa fa-eye fa-lg warning darken-4 mr-1'></i></a>";
            if (check_role($this->i_menu, 3)) {
                $data      .= "<a href='" . base_url() . $this->folder . '/edit/' . encrypt_url($id) . "' title='Edit Data'><i class='fa fa-pencil-square success darken-4 fa-lg mr-1'></i></a>";
            }
            return $data;
        });
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

    /** Get Data Area */
    public function get_area($cari)
    {
        return $this->db->query("SELECT 
                i_area,
                i_area_id,
                e_area_name
            FROM 
                tr_area
            WHERE 
                (e_area_name ILIKE '%$cari%' or i_area_id ILIKE '%$cari%')
                AND f_area_active = 't'
                AND i_company = '$this->i_company'
            ORDER BY 
                e_area_name
        ", FALSE);
    }

    /** Get Data City */
    public function get_city($cari, $i_area)
    {
        return $this->db->query("SELECT 
                i_city,
                i_city_id,
                e_city_name
            FROM 
                tr_city
            WHERE 
                (e_city_name ILIKE '%$cari%')
                AND f_city_active = 't'
                AND i_area = '$i_area'
                AND i_company = '$this->i_company'
            ORDER BY 
                e_city_name
        ", FALSE);
    }

    /** Cek Apakah Data Sudah Ada Pas Simpan */
    public function cek($kode)
    {
        return $this->db->query("SELECT 
                i_area_cover_id
            FROM 
            tr_area_cover
            WHERE 
                trim(upper(i_area_cover_id)) = trim(upper('$kode'))
                AND i_company = '$this->i_company'
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
            'i_area_cover_id'     => strtoupper($this->input->post('i_area_cover_id')),
            'e_area_cover_name'   => ucwords(strtolower($this->input->post('e_area_cover_name'))),
        );
        $this->db->insert('tr_area_cover', $table);

        $jmlx = $this->input->post('jmlx', TRUE);
        if ($jmlx > 0) {
            for ($x = 1; $x <= $jmlx; $x++) {
                $i_area = $this->input->post('i_area' . $x, TRUE);
                $f_checked = $this->input->post('f_checked' . $x, TRUE);
                if ($i_area != '' || $i_area != null) {
                    if ($f_checked == 'f') {
                        $city = $this->input->post('i_city' . $x . '[]', TRUE);
                        if (is_array($city) || is_object($city)) {
                            foreach ($city as $i_city) {
                                $detail = array(
                                    'i_area_cover'  => $id,
                                    'i_area'        => $i_area,
                                    'i_city'        => $i_city,
                                );
                                $this->db->insert('tr_area_cover_item', $detail);
                            }
                        }
                    } else {
                        $sql = $this->db->get_where('tr_city', ['f_city_active' => true, 'i_company' => $this->i_company, 'i_area' => $i_area]);
                        if ($sql->num_rows() > 0) {
                            foreach ($sql->result() as $key) {
                                $detail = array(
                                    'i_area_cover'        => $id,
                                    'i_area'              => $i_area,
                                    'i_city'              => $key->i_city,
                                );
                                $this->db->insert('tr_area_cover_item', $detail);
                            }
                        }
                    }
                }
            }
        }
    }

    /** Get Data Untuk Edit */
    public function getdata($id)
    {
        return $this->db->query("SELECT 
                *
            FROM 
                tr_area_cover
            WHERE
                i_area_cover = '$id'
        ", FALSE);
    }

    /** Get Data Untuk Edit */
    public function getdetail($id)
    {
        return $this->db->query("SELECT
                a.i_area,
                b.i_area_id,
                b.e_area_name,
                jsonb_agg(a.i_city || '|(' || c.i_city_id || ') - ' || c.e_city_name) AS city
            FROM
                tr_area_cover_item a
            LEFT JOIN tr_area b ON
                (b.i_area = a.i_area)
            LEFT JOIN tr_city c ON
                (c.i_city = a.i_city)
            WHERE
                i_area_cover = '$id'
            GROUP BY
                1,2,3
            ORDER BY
                3
        ", FALSE);
    }

    /** Cek Apakah Data Sudah Ada Pas Edit */
    public function cek_edit($i_area_cover_id, $i_area_cover_id_old)
    {
        return $this->db->query("SELECT 
                i_area_cover_id
            FROM 
                tr_area_cover 
            WHERE 
                trim(upper(i_area_cover_id)) <> trim(upper('$i_area_cover_id_old'))
                AND trim(upper(i_area_cover_id)) = trim(upper('$i_area_cover_id'))
                AND i_company = '$this->i_company'
        ", FALSE);
    }

    /** Update Data */
    public function update()
    {
        $id = $this->input->post('i_area_cover');
        $table = array(
            'i_company'           => $this->i_company,
            'i_area_cover_id'     => strtoupper($this->input->post('i_area_cover_id')),
            'e_area_cover_name'   => ucwords(strtolower($this->input->post('e_area_cover_name'))),
            'd_area_cover_update' => current_datetime(),
        );
        $this->db->where('i_area_cover', $id);
        $this->db->update('tr_area_cover', $table);

        $jmlx = $this->input->post('jmlx', TRUE);
        if ($jmlx > 0) {
            $this->db->where('i_area_cover', $id);
            $this->db->delete('tr_area_cover_item');
            for ($x = 1; $x <= $jmlx; $x++) {
                $i_area = $this->input->post('i_area' . $x, TRUE);
                $f_checked = $this->input->post('f_checked' . $x, TRUE);
                if ($i_area != '' || $i_area != null) {
                    if ($f_checked == 'f') {
                        $city = $this->input->post('i_city' . $x . '[]', TRUE);
                        if (is_array($city) || is_object($city)) {
                            foreach ($city as $i_city) {
                                $detail = array(
                                    'i_area_cover'  => $id,
                                    'i_area'        => $i_area,
                                    'i_city'        => $i_city,
                                );
                                $this->db->insert('tr_area_cover_item', $detail);
                            }
                        }
                    } else {
                        $sql = $this->db->get_where('tr_city', ['f_city_active' => true, 'i_company' => $this->i_company, 'i_area' => $i_area]);
                        if ($sql->num_rows() > 0) {
                            foreach ($sql->result() as $key) {
                                $detail = array(
                                    'i_area_cover'        => $id,
                                    'i_area'              => $i_area,
                                    'i_city'              => $key->i_city,
                                );
                                $this->db->insert('tr_area_cover_item', $detail);
                            }
                        }
                    }
                }
            }
        }
    }
}

/* End of file Mmaster.php */
