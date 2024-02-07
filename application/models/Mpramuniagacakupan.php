<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mpramuniagacakupan extends CI_Model
{

    /** List Datatable */
    public function serverside()
    {
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
            SELECT
                i_salesman_areacover AS id,
                i_salesman_id,
                b.e_salesman_name,
                c.i_area_cover_id || ' - ' || c.e_area_cover_name as el
            FROM
                tr_salesman_areacover a
            INNER JOIN tr_salesman b ON
                (b.i_salesman = a.i_salesman)
            INNER JOIN tr_area_cover c ON
                (c.i_area_cover = a.i_area_cover)
            WHERE 
                a.i_company = '$this->i_company'
            ORDER BY
                b.e_salesman_name ASC
        ", FALSE);

        /** Cek Hak Akses, Apakah User Bisa Edit */
        if (check_role($this->id_menu, 3)) {
            $datatables->add('action', function ($data) {
                $id         = trim($data['id']);
                $data       = '';
                $data      .= "<a href='" . base_url() . $this->folder . '/edit/' . encrypt_url($id) . "' title='Edit Data'><i class='fa fa-pencil-square success darken-4 fa-lg mr-1'></i></a>";
                if (check_role($this->id_menu, 4)) {
                    $data      .= "<a href='#' onclick='sweetdelete(\"" . $this->folder . "\",\"" . $id . "\");' title='Hapus Data'><i class='fa fa-trash fa-lg red darken-4'></i></a>";
                }
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
    public function cek($i_area_cover, $i_salesman)
    {
        return $this->db->query("
            SELECT 
                i_area_cover
            FROM 
                tr_salesman_areacover
            WHERE 
                i_area_cover = '$i_area_cover'
                AND i_salesman = '$i_salesman'
                AND i_company = '$this->i_company'
        ", FALSE);
    }

    /** Cari area */
    public function get_area($cari)
    {
        return $this->db->query("
             SELECT 
                i_area_cover , i_area_cover_id , e_area_cover_name 
            FROM 
                tr_area_cover 
            WHERE 
                (e_area_cover_name ILIKE '%$cari%' OR i_area_cover_id ILIKE '%$cari%')
                AND i_company = '$this->i_company' AND f_area_cover_active = true
            ORDER BY 3 ASC
        ", FALSE);
    }

    /** Cari Salesman */
    public function get_salesman($cari)
    {
        return $this->db->query("
             SELECT 
                i_salesman , i_salesman_id , e_salesman_name 
            FROM 
                tr_salesman 
            WHERE 
                (e_salesman_name ILIKE '%$cari%' OR i_salesman_id ILIKE '%$cari%')
                AND i_company = '$this->i_company' AND f_salesman_active = true
            ORDER BY 3 ASC
        ", FALSE);
    }

    /** Simpan Data */
    public function save()
    {
        $table = array(
            'i_company'                  => $this->i_company,
            'i_salesman'                 => $this->input->post('i_salesman'),
            'i_area_cover'               => $this->input->post('i_area_cover'),
            'd_salesman_areacoverstart'  => $this->input->post('d_salesman_areacoverstart'),
            'd_salesman_areacoverfinish' => $this->input->post('d_salesman_areacoverfinish'),
            'd_salesman_areacoverentry'  => current_datetime(),
        );
        $this->db->insert('tr_salesman_areacover', $table);
    }

    /** Get Data Untuk Edit */
    public function getdata($id)
    {
        return $this->db->query("
            SELECT
                a.*,    
                b.i_salesman_id,
                b.e_salesman_name,
                c.i_area_cover,
                c.i_area_cover_id,
                c.e_area_cover_name
            FROM
                tr_salesman_areacover a
            INNER JOIN tr_salesman b ON
                (b.i_salesman = a.i_salesman)
            INNER JOIN tr_area_cover c ON
                (c.i_area_cover = a.i_area_cover)
            WHERE
                i_salesman_areacover = '$id'
        ", FALSE);
    }

    /** Cek Apakah Data Sudah Ada Pas Edit */
    public function cek_edit($i_area_cover, $i_salesman, $i_area_cover_old, $i_salesman_old)
    {
        return $this->db->query("
            SELECT 
                i_area_cover
            FROM 
                tr_salesman_areacover
            WHERE 
                i_area_cover = '$i_area_cover'
                AND i_area_cover <> '$i_area_cover_old'
                AND i_salesman = '$i_salesman'
                AND i_salesman <> '$i_salesman_old'
                AND i_company = '$this->i_company'
        ", FALSE);
    }

    /** Update Data */
    public function update()
    {
        $table = array(
            'i_company'                  => $this->i_company,
            'i_salesman'                 => $this->input->post('i_salesman'),
            'i_area_cover'               => $this->input->post('i_area_cover'),
            'd_salesman_areacoverstart'  => $this->input->post('d_salesman_areacoverstart'),
            'd_salesman_areacoverfinish' => $this->input->post('d_salesman_areacoverfinish'),
            'd_salesman_areacoverupdate' => current_datetime(),
        );
        $this->db->where('i_salesman_areacover', $this->input->post('id'));
        $this->db->update('tr_salesman_areacover', $table);
    }

    public function delete($id)
    {
        $this->db->where('i_salesman_areacover', $id);
        $this->db->delete('tr_salesman_areacover');
    }
}

/* End of file Mmaster.php */