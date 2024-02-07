<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mkelompokharga extends CI_Model
{

    /** List Datatable */
    public function serverside()
    {
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
            select i_price_group , i_price_groupid , 
			e_price_groupname , coalesce(n_price_groupmargin, 0) || '%' as margin, f_price_groupactive 
			from tr_price_group where i_company = '$this->i_company'
            order by i_price_group desc
        ", FALSE);

        $datatables->edit('f_price_groupactive', function ($data) {
            $id         = $data['i_price_group'];
            if ($data['f_price_groupactive'] == 't') {
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
                $id         = trim($data['i_price_group']);
                $data       = '';
                $data      .= "<a href='" . base_url() . $this->folder . '/edit/' . encrypt_url($id) . "' title='Edit Data'><i class='fa fa-pencil-square success darken-4 fa-lg mr-1'></i></a>";
                return $data;
            });
        }
        return $datatables->generate();
    }

    public function changestatus($id)
    {
        $this->db->select('f_price_groupactive');
        $this->db->from('tr_price_group');
        $this->db->where('i_price_group', $id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $status = $query->row()->f_price_groupactive;
        } else {
            $status = 'f';
        }
        if ($status == 'f') {
            $fstatus = 't';
        } else {
            $fstatus = 'f';
        }
        $table = array(
            'f_price_groupactive' => $fstatus,
        );
        $this->db->where('i_price_group', $id);
        $this->db->update('tr_price_group', $table);
    }

    /** Cek Apakah Data Sudah Ada Pas Simpan */
    public function cek($kode)
    {
        return $this->db->query("
            SELECT 
                i_price_groupid
            FROM 
                tr_price_group
            WHERE 
                trim(upper(i_price_groupid)) = trim(upper('$kode'))
                AND i_company = '$this->i_company'
        ", FALSE);
    }

    /** Simpan Data */
    public function save($kode, $nama, $margin)
    {
        $table = array(
            'i_company'    => $this->i_company,
            'i_price_groupid' => $kode,
            'e_price_groupname' => $nama,
            'n_price_groupmargin' => $margin,
            'd_price_groupentry'  => date('Y-m-d H:i:s'),
        );
        $this->db->insert('tr_price_group', $table);
    }

    /** Get Data Untuk Edit */
    public function getdata($id)
    {
        return $this->db->query("
            SELECT 
                *
            FROM 
                tr_price_group 
            WHERE
                i_price_group = '$id'
        ", FALSE);
    }

    /** Cek Apakah Data Sudah Ada Pas Edit */
    public function cek_edit($kode, $kodeold)
    {
        return $this->db->query("
            SELECT 
                i_price_groupid
            FROM 
                tr_price_group 
            WHERE 
                trim(upper(i_price_groupid)) <> trim(upper('$kodeold'))
                AND trim(upper(i_price_groupid)) = trim(upper('$kode'))
                AND i_company = '$this->i_company'
        ", FALSE);
    }

    /** Update Data */
    public function update($id, $kode, $nama, $margin)
    {
        $table = array(
            'i_price_groupid' => $kode,
            'e_price_groupname' => $nama,
            'n_price_groupmargin' => $margin,
            'd_price_groupupdate'  => date('Y-m-d H:i:s'),
        );
        $this->db->where('i_price_group', $id);
        $this->db->update('tr_price_group', $table);
    }
}

/* End of file Mmaster.php */
