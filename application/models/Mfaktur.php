<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mfaktur extends CI_Model
{

    /** List Datatable */
    public function serverside()
    {
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
            select i_pajak , i_pajak_id ,n_year, n_start, n_end
			from tr_pajak where i_company = '$this->i_company'
            order by i_pajak desc
        ", FALSE);

        // $datatables->edit('f_pajak_active', function ($data) {
        //     $id         = $data['i_pajak'];
        //     if ($data['f_pajak_active']=='t') {
        //          $status = $this->lang->line('Aktif');
        //         $color  = 'success';
        //     }else{
        //         $status = $this->lang->line('Tidak Aktif');
        //         $color  = 'danger';
        //     }
        //     $data = "<button class='btn btn-outline-".$color." btn-sm round' onclick='changestatus(\"".$this->folder."\",\"".$id."\");'>".$status."</button>";
        //     return $data;
        // });

        /** Cek Hak Akses, Apakah User Bisa Edit */
        if (check_role($this->id_menu, 3)) {
            $datatables->add('action', function ($data) {
                $id         = trim($data['i_pajak']);
                $data       = '';
                $data      .= "<a href='" . base_url() . $this->folder . '/edit/' . encrypt_url($id) . "' title='Edit Data'><i class='fa fa-pencil-square success darken-4 fa-lg mr-1'></i></a>";
                return $data;
            });
        }
        return $datatables->generate();
    }

    /** Cek Apakah Data Sudah Ada Pas Simpan */
    public function cek($kode)
    {
        return $this->db->query("
            SELECT 
                i_pajak_id
            FROM 
                tr_pajak
            WHERE 
                trim(upper(i_pajak_id)) = trim(upper('$kode'))
                AND i_company = '$this->i_company'
        ", FALSE);
    }

    /** Simpan Data */
    public function save($kode, $nama, $norek, $namarek)
    {
        $table = array(
            'i_company'    => $this->i_company,
            'i_pajak_id' => $kode,
            'e_pajak_name' => $nama,
            'e_rekening_no' => $norek,
            'e_rekening_name' => $namarek,
            'd_pajak_entry'  => date('Y-m-d H:i:s'),
        );
        $this->db->insert('tr_pajak', $table);
    }

    /** Get Data Untuk Edit */
    public function getdata($id)
    {
        return $this->db->query("
            SELECT 
                *
            FROM 
                tr_pajak 
            WHERE
                i_pajak = '$id'
        ", FALSE);
    }

    /** Cek Apakah Data Sudah Ada Pas Edit */
    public function cek_edit($kode, $kodeold)
    {
        return $this->db->query("
            SELECT 
                i_pajak_id
            FROM 
                tr_pajak 
            WHERE 
                trim(upper(i_pajak_id)) <> trim(upper('$kodeold'))
                AND trim(upper(i_pajak_id)) = trim(upper('$kode'))
                AND i_company = '$this->i_company'
        ", FALSE);
    }

    /** Update Data */
    public function update($id, $kode, $n_year, $n_start, $n_end)
    {
        $table = array(
            'i_pajak_id' => $kode,
            'n_year' => $n_year,
            'n_start' => $n_start,
            'n_end' => $n_end,
            'd_update'  => date('Y-m-d H:i:s'),
        );
        $this->db->where('i_pajak', $id);
        $this->db->update('tr_pajak', $table);
    }
}

/* End of file Mmaster.php */
