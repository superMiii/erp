<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mgroupbayar extends CI_Model
{

    /** List Datatable */
    public function serverside()
    {
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
            select i_customer_paygroup , i_customer_paygroupid , 
			e_customer_paygroupname ,  v_flafon, v_credit, f_customer_paygroupactive 
			from tr_customer_paygroup where i_company = '$this->i_company'
            order by i_customer_paygroup desc
        ", FALSE);

        $datatables->edit('f_customer_paygroupactive', function ($data) {
            $id         = $data['i_customer_paygroup'];
            if ($data['f_customer_paygroupactive'] == 't') {
                $status = $this->lang->line('Aktif');
                $color  = 'success';
            } else {
                $status = $this->lang->line('Tidak Aktif');
                $color  = 'danger';
            }
            $data = "<button class='btn btn-outline-" . $color . " btn-sm round' onclick='changestatus(\"" . $this->folder . "\",\"" . $id . "\");'>" . $status . "</button>";
            return $data;
        });

        $datatables->edit('v_flafon', function ($data) {
            return format_rupiah($data['v_flafon']);
        });

        $datatables->edit('v_credit', function ($data) {
            return format_rupiah($data['v_credit']);
        });

        /** Cek Hak Akses, Apakah User Bisa Edit */
        if (check_role($this->id_menu, 3)) {
            $datatables->add('action', function ($data) {
                $id         = trim($data['i_customer_paygroup']);
                $data       = '';
                $data      .= "<a href='" . base_url() . $this->folder . '/edit/' . encrypt_url($id) . "' title='Edit Data'><i class='fa fa-pencil-square success darken-4 fa-lg mr-1'></i></a>";
                return $data;
            });
        }
        return $datatables->generate();
    }

    public function changestatus($id)
    {
        $this->db->select('f_customer_paygroupactive');
        $this->db->from('tr_customer_paygroup');
        $this->db->where('i_customer_paygroup', $id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $status = $query->row()->f_customer_paygroupactive;
        } else {
            $status = 'f';
        }
        if ($status == 'f') {
            $fstatus = 't';
        } else {
            $fstatus = 'f';
        }
        $table = array(
            'f_customer_paygroupactive' => $fstatus,
        );
        $this->db->where('i_customer_paygroup', $id);
        $this->db->update('tr_customer_paygroup', $table);
    }

    /** Cek Apakah Data Sudah Ada Pas Simpan */
    public function cek($kode)
    {
        return $this->db->query("
            SELECT 
                i_customer_paygroupid
            FROM 
                tr_customer_paygroup
            WHERE 
                trim(upper(i_customer_paygroupid)) = trim(upper('$kode'))
                AND i_company = '$this->i_company'
        ", FALSE);
    }

    /** Simpan Data */
    public function save($kode, $nama, $v_flafon)
    {
        $i_customer_paygroup = $this->db->query("
            SELECT coalesce(max(i_customer_paygroup),0)::int + 1 as i_customer_paygroup FROM tr_customer_paygroup
        ", FALSE)->row()->i_customer_paygroup;

        $table = array(
            'i_customer_paygroup' => $i_customer_paygroup,
            'i_company'    => $this->i_company,
            'i_customer_paygroupid' => $kode,
            'e_customer_paygroupname' => $nama,
            'v_flafon' => $v_flafon,
            'd_customer_paygroupentry'  => date('Y-m-d H:i:s'),
        );
        $this->db->insert('tr_customer_paygroup', $table);
    }

    /** Get Data Untuk Edit */
    public function getdata($id)
    {
        return $this->db->query("
            SELECT 
                *
            FROM 
                tr_customer_paygroup 
            WHERE
                i_customer_paygroup = '$id'
        ", FALSE);
    }

    /** Cek Apakah Data Sudah Ada Pas Edit */
    public function cek_edit($kode, $kodeold)
    {
        return $this->db->query("
            SELECT 
                i_customer_paygroupid
            FROM 
                tr_customer_paygroup 
            WHERE 
                trim(upper(i_customer_paygroupid)) <> trim(upper('$kodeold'))
                AND trim(upper(i_customer_paygroupid)) = trim(upper('$kode'))
                AND i_company = '$this->i_company'
        ", FALSE);
    }

    /** Update Data */
    public function update($id, $kode, $nama, $v_flafon)
    {
        $table = array(
            'i_customer_paygroupid' => $kode,
            'e_customer_paygroupname' => $nama,
            'v_flafon' => $v_flafon,
            'd_customer_paygroupupdate'  => date('Y-m-d H:i:s'),
        );
        $this->db->where('i_customer_paygroup', $id);
        $this->db->update('tr_customer_paygroup', $table);
    }
}

/* End of file Mmaster.php */
