<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mekspedisi extends CI_Model
{

    /** List Datatable */
    public function serverside()
    {
        $i_area = $this->input->post('i_area', TRUE);
        if ($i_area == '') {
            $i_area = $this->uri->segment(5);
        }

        if ($i_area != 'NA') {
            $area = "AND a.i_area = '$i_area' ";
        } else {
            $area = "";
        }
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("SELECT
                i_sl_ekspedisi AS id,
                i_sl_ekspedisi_id,
                e_sl_ekspedisi,
                e_area_name,
                e_sl_ekspedisi_address,
                e_sl_ekspedisi_city,
                e_sl_ekspedisi_phone,
                e_sl_ekspedisi_fax,
                f_sl_ekpedisi_active AS f_status
            FROM
                tr_sl_ekspedisi a
            INNER JOIN tr_area b ON 
                (b.i_area = a.i_area)
            inner join tm_user_area u on
                (u.i_area = b.i_area and u.i_user = '$this->i_user')
            WHERE
                a.i_company = '$this->i_company'
                $area
            ORDER BY e_sl_ekspedisi ASC ", FALSE);

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
                $data      .= "<a href='" . base_url() . $this->folder . '/edit/' . encrypt_url($id) . "' title='Edit Data'><i class='icon-note'></i></a>";
                return $data;
            });
        }
        return $datatables->generate();
    }

    /** Get Area */
    public function get_area($cari)
    {
        return $this->db->query("SELECT 
                DISTINCT
                a.i_area, 
                i_area_id, 
                initcap(e_area_name) AS e_area_name
            FROM 
                tr_area a
            INNER JOIN tm_user_area b 
                ON (b.i_area = a.i_area) 
            WHERE 
                (e_area_name ILIKE '%$cari%' OR i_area_id ILIKE '%$cari%')
                AND a.i_company = '$this->i_company' 
                AND f_area_active = true
                AND b.i_user = '$this->i_user' 
            ORDER BY 3 ASC
        ", FALSE);
    }

    public function changestatus($id)
    {
        $this->db->select('f_sl_ekpedisi_active');
        $this->db->from('tr_sl_ekspedisi');
        $this->db->where('i_sl_ekspedisi', $id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $status = $query->row()->f_sl_ekpedisi_active;
        } else {
            $status = 'f';
        }
        if ($status == 'f') {
            $fstatus = 't';
        } else {
            $fstatus = 'f';
        }
        $table = array(
            'f_sl_ekpedisi_active' => $fstatus,
        );
        $this->db->where('i_sl_ekspedisi', $id);
        $this->db->update('tr_sl_ekspedisi', $table);
    }

    /** Cek Apakah Data Sudah Ada Pas Simpan */
    public function cek($i_sl_ekspedisi_id)
    {
        return $this->db->query("
            SELECT 
                i_sl_ekspedisi_id   
            FROM 
                tr_sl_ekspedisi 
            WHERE 
                trim(upper(i_sl_ekspedisi_id)) = trim(upper('$i_sl_ekspedisi_id'))
                AND i_company = $this->i_company
        ", FALSE);
    }

    /** Simpan Data */
    public function save()
    {
        $table = array(
            'i_company'             => $this->i_company,
            'i_sl_ekspedisi_id'     => strtoupper($this->input->post('i_sl_ekspedisi_id')),
            'i_area'                => ucwords(strtolower($this->input->post('i_area'))),
            'e_sl_ekspedisi'        => $this->input->post('e_sl_ekspedisi'),
            'e_sl_ekspedisi_address' => $this->input->post('e_sl_ekspedisi_address'),
            'e_sl_ekspedisi_city'   => $this->input->post('e_sl_ekspedisi_city'),
            'e_sl_ekspedisi_phone'  => $this->input->post('e_sl_ekspedisi_phone'),
            'e_sl_ekspedisi_fax'    => $this->input->post('e_sl_ekspedisi_fax'),
            'd_entry'               => current_datetime(),
        );
        $this->db->insert('tr_sl_ekspedisi', $table);
    }

    /** Get Data Untuk Edit */
    public function getdata($id)
    {
        return $this->db->query("
            SELECT 
                a.*,  
                b.i_area_id,
                b.e_area_name
            FROM 
                tr_sl_ekspedisi a
            INNER JOIN tr_area b ON 
                (b.i_area = a.i_area)
            WHERE
                i_sl_ekspedisi = '$id'
        ", FALSE);
    }

    /** Cek Apakah Data Sudah Ada Pas Edit */
    public function cek_edit($i_sl_ekspedisi_id_old, $i_sl_ekspedisi_id)
    {
        return $this->db->query("
            SELECT 
                i_sl_ekspedisi_id
            FROM 
                tr_sl_ekspedisi 
            WHERE 
                trim(upper(i_sl_ekspedisi_id)) = trim(upper('$i_sl_ekspedisi_id'))
                AND trim(upper(i_sl_ekspedisi_id)) <> trim(upper('$i_sl_ekspedisi_id_old'))
                AND i_company = $this->i_company
        ", FALSE);
    }

    /** Update Data */
    public function update()
    {
        $table = array(
            'i_company'             => $this->i_company,
            'i_sl_ekspedisi_id'     => strtoupper($this->input->post('i_sl_ekspedisi_id')),
            'i_area'                => ucwords(strtolower($this->input->post('i_area'))),
            'e_sl_ekspedisi'        => $this->input->post('e_sl_ekspedisi'),
            'e_sl_ekspedisi_address' => $this->input->post('e_sl_ekspedisi_address'),
            'e_sl_ekspedisi_city'   => $this->input->post('e_sl_ekspedisi_city'),
            'e_sl_ekspedisi_phone'  => $this->input->post('e_sl_ekspedisi_phone'),
            'e_sl_ekspedisi_fax'    => $this->input->post('e_sl_ekspedisi_fax'),
            'd_update'              => current_datetime(),
        );
        $this->db->where('i_sl_ekspedisi', $this->input->post('i_sl_ekspedisi', TRUE));
        $this->db->update('tr_sl_ekspedisi', $table);
    }
}

/* End of file Mmaster.php */
