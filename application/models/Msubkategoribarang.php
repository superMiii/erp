<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Msubkategoribarang extends CI_Model
{

    /** List Datatable */
    public function serverside()
    {
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("SELECT
                i_product_subcategory,
                e_product_categoryname,
                i_product_subcategoryid,
                e_product_subcategoryname,
                f_product_subcategoryactive AS f_status
            FROM
                tr_product_subcategory a
            INNER JOIN tr_product_category c ON
                (c.i_product_category = a.i_product_category)
            WHERE 
                a.i_company = '$this->i_company'
            ORDER BY
                4", FALSE);

        $datatables->edit('f_status', function ($data) {
            $id         = $data['i_product_subcategory'];
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
                $id         = trim($data['i_product_subcategory']);
                $data       = '';
                $data      .= "<a href='" . base_url() . $this->folder . '/edit/' . encrypt_url($id) . "' title='Edit Data'><i class='fa fa-pencil-square success darken-4 fa-lg mr-1'></i></a>";
                return $data;
            });
        }
        return $datatables->generate();
    }

    public function changestatus($id)
    {
        $this->db->select('f_product_subcategoryactive');
        $this->db->from('tr_product_subcategory');
        $this->db->where('i_product_subcategory', $id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $status = $query->row()->f_product_subcategoryactive;
        } else {
            $status = 'f';
        }
        if ($status == 'f') {
            $fstatus = 't';
        } else {
            $fstatus = 'f';
        }
        $table = array(
            'f_product_subcategoryactive' => $fstatus,
        );
        $this->db->where('i_product_subcategory', $id);
        $this->db->update('tr_product_subcategory', $table);
    }

    /** Cek Apakah Data Sudah Ada Pas Simpan */
    public function cek($i_product_category, $i_product_subcategoryid)
    {
        return $this->db->query("
            SELECT 
                i_product_subcategoryid
            FROM 
                tr_product_subcategory 
            WHERE 
                trim(upper(i_product_subcategoryid)) = trim(upper('$i_product_subcategoryid'))
                AND i_company = $this->i_company
                AND i_product_category = $i_product_category
        ", FALSE);
    }

    /** Simpan Data */
    public function save($i_product_category, $i_product_subcategoryid, $e_product_subcategoryname)
    {
        $table = array(
            'i_company'                   => $this->i_company,
            'i_product_category'          => $i_product_category,
            'i_product_subcategoryid'     => $i_product_subcategoryid,
            'e_product_subcategoryname'   => $e_product_subcategoryname,
            'd_product_subcategoryentry'  => current_datetime(),
        );
        $this->db->insert('tr_product_subcategory', $table);
    }

    /** Get Data Untuk Edit */
    public function getdata($id)
    {
        return $this->db->query("
            SELECT 
                *
            FROM 
                tr_product_subcategory 
            WHERE
                i_product_subcategory = '$id'
        ", FALSE);
    }

    /** Cek Apakah Data Sudah Ada Pas Edit */
    public function cek_edit($i_company_old, $i_product_category, $i_product_subcategoryid, $i_product_subcategoryid_old)
    {
        return $this->db->query("
            SELECT 
                i_product_subcategoryid
            FROM 
                tr_product_subcategory 
            WHERE 
                trim(upper(i_product_subcategoryid)) = trim(upper('$i_product_subcategoryid'))
                AND trim(upper(i_product_subcategoryid)) <> trim(upper('$i_product_subcategoryid_old'))
                AND i_company = $this->i_company
                AND i_company <> $i_company_old
                AND i_product_category = $i_product_category
        ", FALSE);
    }

    /** Update Data */
    public function update($i_product_category, $i_product_subcategory, $i_product_subcategoryid, $e_product_subcategoryname)
    {
        $table = array(
            'i_company'                   => $this->i_company,
            'i_product_category'          => $i_product_category,
            'i_product_subcategoryid'     => $i_product_subcategoryid,
            'e_product_subcategoryname'   => $e_product_subcategoryname,
            'd_product_subcategoryupdate' => current_datetime(),
        );
        $this->db->where('i_product_subcategory', $i_product_subcategory);
        $this->db->update('tr_product_subcategory', $table);
    }
}

/* End of file Mmaster.php */
