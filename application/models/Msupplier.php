<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Msupplier extends CI_Model
{

    /** List Datatable */
    public function serverside()
    {
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
        select
        a.i_supplier,
        a.i_supplier_id,
        a.e_supplier_name,
        b.e_supplier_groupname,
        a.n_supplier_top,
        case
            when f_supplier_pkp = 't' then 'PKP'
            else 'NONPKP'
        end as pkp,	
        a.f_supplier_active
    from
        tr_supplier a
    left join tr_supplier_group b on
        (a.i_supplier_group = b.i_supplier_group)
            where a.i_company = '$this->i_company'  order by a.i_supplier desc
        ", FALSE);

        $datatables->edit('f_supplier_active', function ($data) {
            $id         = $data['i_supplier'];
            if ($data['f_supplier_active'] == 't') {
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
                $id         = trim($data['i_supplier']);
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
        $this->db->select('f_supplier_active');
        $this->db->from('tr_supplier');
        $this->db->where('i_supplier', $id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $status = $query->row()->f_supplier_active;
        } else {
            $status = 'f';
        }
        if ($status == 'f') {
            $fstatus = 't';
        } else {
            $fstatus = 'f';
        }
        $table = array(
            'f_supplier_active' => $fstatus,
        );
        $this->db->where('i_supplier', $id);
        $this->db->update('tr_supplier', $table);
    }

    /** Cek Apakah Data Sudah Ada Pas Simpan */
    public function cek($kode)
    {
        return $this->db->query("
            SELECT 

                i_supplier_id
            FROM 
                tr_supplier
            WHERE 
                trim(upper(i_supplier_id)) = trim(upper('$kode'))
                AND i_company = '$this->i_company'
        ", FALSE);
    }

    /** Cari group supplier */
    public function get_group_supplier($cari)
    {
        return $this->db->query("
             SELECT 
                i_supplier_group , i_supplier_groupid , e_supplier_groupname 
            FROM 
                tr_supplier_group tsg 
            WHERE 
                (e_supplier_groupname ILIKE '%$cari%' or i_supplier_groupid ILIKE '%$cari%')
                and i_company = '$this->i_company' and f_supplier_groupactive = true
            ORDER BY 3 ASC
        ", FALSE);
    }
    /** Simpan Data */
    public function save($kode, $nama, $isuppliergroup, $top, $ppn)
    {
        $table = array(
            'i_company'    => $this->i_company,
            'i_supplier_id' => $kode,
            'e_supplier_name' => $nama,
            'i_supplier_group' => $isuppliergroup,
            'd_supplier_entry'  => date('Y-m-d H:i:s'),
            'n_supplier_top' => $top,
            'f_supplier_pkp' => $ppn,
        );
        $this->db->insert('tr_supplier', $table);
    }

    /** Get Data Untuk Edit */
    public function getdata($id)
    {
        return $this->db->query("
            SELECT 
                a.*, b.i_supplier_groupid, b.e_supplier_groupname
            FROM 
                tr_supplier a
                left join tr_supplier_group b on (a.i_supplier_group = b.i_supplier_group)
            WHERE
                a.i_supplier = '$id'
        ", FALSE);
    }

    /** Cek Apakah Data Sudah Ada Pas Edit */
    public function cek_edit($kode, $kodeold)
    {
        return $this->db->query("
            SELECT 
                i_supplier_id
            FROM 
                tr_supplier 
            WHERE 
                trim(upper(i_supplier_id)) <> trim(upper('$kodeold'))
                AND trim(upper(i_supplier_id)) = trim(upper('$kode'))
                AND i_company = '$this->i_company'
        ", FALSE);
    }

    /** Update Data */
    public function update($id, $isuppliergroup, $kode, $nama, $ppn, $top)
    {
        $table = array(
            'i_supplier_id' => $kode,
            'e_supplier_name' => $nama,
            'i_supplier_group' => $isuppliergroup,
            'd_supplier_update'  => date('Y-m-d H:i:s'),
            'n_supplier_top' => $top,
            'f_supplier_pkp' => $ppn,

        );
        $this->db->where('i_supplier', $id);
        $this->db->update('tr_supplier', $table);
    }
}

/* End of file Mmaster.php */
