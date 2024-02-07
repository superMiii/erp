<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mbarang extends CI_Model
{

    /** List Datatable */
    public function serverside()
    {
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("SELECT distinct 
                a.e_product_name as idd,
                a.i_product as id,
	            i_product_id,
	            a.e_product_name,
	            g.e_product_gradename,
                s.e_product_statusname,
                to_char(d_product_entry, 'YYYY-MM-DD') as tgl,
	            a.f_product_active as f_status
            FROM
            tr_product a
            inner join tr_product_motif m on (a.i_product_motif = m.i_product_motif)
            inner join tm_ic i on (i.i_product=a.i_product)
            inner join tr_product_grade g on (g.i_product_grade=i.i_product_grade)
            inner join tr_product_status s on (s.i_product_status = a.i_product_status)
            WHERE
                a.i_company = '$this->i_company'
            ORDER BY 1 ASC ", FALSE);

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
                $data      .= "<a href='" . base_url() . $this->folder . '/edit/' . encrypt_url($id) . "' title='Edit Data'><i class='fa fa-pencil-square success darken-4 fa-lg mr-1'></i></a>";
                return $data;
            });
        }
        $datatables->hide('idd');
        return $datatables->generate();
    }

    public function changestatus($id)
    {
        $this->db->select('f_product_active');
        $this->db->from('tr_product');
        $this->db->where('i_product', $id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $status = $query->row()->f_product_active;
        } else {
            $status = 'f';
        }
        if ($status == 'f') {
            $fstatus = 't';
        } else {
            $fstatus = 'f';
        }
        $table = array(
            'f_product_active' => $fstatus,
        );
        $this->db->where('i_product', $id);
        $this->db->update('tr_product', $table);
    }

    /** Cari Sub Category */
    public function get_subcategory($i_product_category, $cari)
    {
        return $this->db->query("
              SELECT 
                 i_product_subcategory, i_product_subcategoryid , e_product_subcategoryname 
             FROM 
                 tr_product_subcategory
             WHERE 
                 (e_product_subcategoryname ILIKE '%$cari%' or i_product_subcategoryid ILIKE '%$cari%')
                 AND i_company = '$this->i_company' AND f_product_subcategoryactive = true
                 AND i_product_category = '$i_product_category'
             ORDER BY 3 ASC
         ", FALSE);
    }

    /** Cek Apakah Data Sudah Ada Pas Simpan */
    public function cek($i_product_id)
    {
        return $this->db->query("
            SELECT 
                i_product_id
            FROM 
                tr_product 
            WHERE 
                trim(upper(i_product_id)) = trim(upper('$i_product_id'))
                AND i_company = $this->i_company
        ", FALSE);
    }

    /** Simpan Data */
    public function save()
    {
        $table = array(
            'i_company'             => $this->i_company,
            'i_product_id'          => strtoupper($this->input->post('i_product_id', TRUE)),
            'e_product_name'        => ucwords(strtolower($this->input->post('e_product_name', TRUE))),
            'i_product_group'       => $this->input->post('i_product_group', TRUE),
            'i_product_category'    => $this->input->post('i_product_category', TRUE),
            'i_product_subcategory' => $this->input->post('i_product_subcategory', TRUE),
            'i_product_status'      => $this->input->post('i_product_status', TRUE),
            'i_product_series'      => $this->input->post('i_product_series', TRUE),
            'i_product_color'       => $this->input->post('i_product_color', TRUE),
            'i_product_motif'       => $this->input->post('i_product_motif', TRUE),
        );
        $this->db->insert('tr_product', $table);
    }

    /** Get Data Untuk Edit */
    public function getdata($id)
    {
        return $this->db->query("
            SELECT 
                a.*,  
                b.i_product_subcategoryid,
                b.e_product_subcategoryname
            FROM 
                tr_product a
            INNER JOIN tr_product_subcategory b ON 
                (b.i_product_subcategory = a.i_product_subcategory)
            WHERE
                i_product = '$id'
        ", FALSE);
    }

    /** Cek Apakah Data Sudah Ada Pas Edit */
    public function cek_edit($i_product_id_old, $i_product_id)
    {
        return $this->db->query("
            SELECT 
                i_product_id
            FROM 
                tr_product 
            WHERE 
                trim(upper(i_product_id)) = trim(upper('$i_product_id'))
                AND trim(upper(i_product_id)) <> trim(upper('$i_product_id_old'))
                AND i_company = $this->i_company
        ", FALSE);
    }

    /** Update Data */
    public function update()
    {
        $raya = ucwords(strtolower($this->input->post('e_product_name', TRUE)));
        $mr = $this->input->post('i_product', TRUE);
        $table = array(
            'i_company'             => $this->i_company,
            'i_product_id'          => strtoupper($this->input->post('i_product_id', TRUE)),
            'e_product_name'        => $raya,
            'i_product_group'       => $this->input->post('i_product_group', TRUE),
            'i_product_category'    => $this->input->post('i_product_category', TRUE),
            'i_product_subcategory' => $this->input->post('i_product_subcategory', TRUE),
            'i_product_status'      => $this->input->post('i_product_status', TRUE),
            'i_product_series'      => $this->input->post('i_product_series', TRUE),
            'i_product_color'       => $this->input->post('i_product_color', TRUE),
            'i_product_motif'       => $this->input->post('i_product_motif', TRUE),
            'd_product_update'      => current_datetime(),
        );
        $this->db->where('i_product', $mr);
        $this->db->update('tr_product', $table);
        
        
        $this->db->query("UPDATE tm_ic SET e_product_name = '$raya' WHERE i_product = '$mr' ", FALSE);
    }
}

/* End of file Mmaster.php */
