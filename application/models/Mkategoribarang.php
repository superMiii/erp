<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mkategoribarang extends CI_Model
{

    /** List Datatable */
    public function serverside()
    {
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("SELECT
                i_product_category,
                i_product_categoryid,
                e_product_categoryname,
                f_product_categoryactive AS f_status
            FROM
                tr_product_category
            WHERE 
                i_company = '$this->i_company'
            ORDER BY
                4", FALSE);

        $datatables->edit('f_status', function ($data) {
            $id         = $data['i_product_category'];
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
                $id         = trim($data['i_product_category']);
                $data       = '';
                $data      .= "<a href='" . base_url() . $this->folder . '/edit/' . encrypt_url($id) . "' title='Edit Data'><i class='fa fa-pencil-square success darken-4 fa-lg mr-1'></i></a>";
                return $data;
            });
        }
        return $datatables->generate();
    }

    public function changestatus($id)
    {
        $this->db->select('f_product_categoryactive');
        $this->db->from('tr_product_category');
        $this->db->where('i_product_category', $id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $status = $query->row()->f_product_categoryactive;
        } else {
            $status = 'f';
        }
        if ($status == 'f') {
            $fstatus = 't';
        } else {
            $fstatus = 'f';
        }
        $table = array(
            'f_product_categoryactive' => $fstatus,
        );
        $this->db->where('i_product_category', $id);
        $this->db->update('tr_product_category', $table);
    }

    /** Cek Apakah Data Sudah Ada Pas Simpan */
    public function cek($i_product_categoryid)
    {
        return $this->db->query("
            SELECT 
                i_product_categoryid
            FROM 
                tr_product_category 
            WHERE 
                trim(upper(i_product_categoryid)) = trim(upper('$i_product_categoryid'))
                AND i_company = $this->i_company
        ", FALSE);
    }

    /** Simpan Data */
    public function save($i_product_categoryid, $e_product_categoryname)
    {
        $table = array(
            'i_company'                => $this->i_company,
            'i_product_categoryid'     => $i_product_categoryid,
            'e_product_categoryname'   => $e_product_categoryname,
            'd_product_categoryentry'  => current_datetime(),
        );
        $this->db->insert('tr_product_category', $table);
    }

    /** Get Data Untuk Edit */
    public function getdata($id)
    {
        return $this->db->query("
            SELECT 
                *
            FROM 
                tr_product_category 
            WHERE
                i_product_category = '$id'
                AND i_company = '$this->i_company'
        ", FALSE);
    }

    /** Cek Apakah Data Sudah Ada Pas Edit */
    public function cek_edit($i_company_old, $i_product_categoryid, $i_product_categoryid_old)
    {
        return $this->db->query("
            SELECT 
                i_product_categoryid
            FROM 
                tr_product_category 
            WHERE 
                trim(upper(i_product_categoryid)) = trim(upper('$i_product_categoryid'))
                AND trim(upper(i_product_categoryid)) <> trim(upper('$i_product_categoryid_old'))
                AND i_company = $this->i_company
                AND i_company <> $i_company_old
        ", FALSE);
    }

    /** Update Data */
    public function update($i_product_category, $i_product_categoryid, $e_product_categoryname)
    {
        $table = array(
            'i_company'                => $this->i_company,
            'i_product_categoryid'     => $i_product_categoryid,
            'e_product_categoryname'   => $e_product_categoryname,
            'd_product_categoryupdate' => current_datetime(),
        );
        $this->db->where('i_product_category', $i_product_category);
        $this->db->update('tr_product_category', $table);
    }
}

/* End of file Mmaster.php */
