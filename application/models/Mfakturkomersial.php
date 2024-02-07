<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mfakturkomersial extends CI_Model {

    /** List Datatable */
    public function serverside(){
        $dfrom = $this->input->post('dfrom', TRUE);
        if ($dfrom == '') {
            $dfrom = $this->uri->segment(3);
        }

        $dto = $this->input->post('dto', TRUE);
        if ($dto == '') {
            $dto = $this->uri->segment(4);
        }

        // var_dump($dfrom . $dto);

        $dfrom  = date('Y-m-d', strtotime($dfrom));
        $dto    = date('Y-m-d', strtotime($dto));

        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
            select i_nota, i_nota_id , to_char(d_nota, 'dd-mm-yyyy') as d_nota, i_seri_pajak from tm_nota 
                where  f_nota_cancel = 'f' and f_pajak_cancel = 'f' and i_seri_pajak is not null and i_company = '$this->i_company'
                and d_nota between '$dfrom' and '$dto'
                order by i_nota_id asc
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
        // if (check_role($this->id_menu, 3)) {
        //     $datatables->add('action', function ($data) {
        //         $id         = trim($data['i_nota']);
        //         $data       = '';
        //         $data      .= "<a href='".base_url().$this->folder.'/edit/'.encrypt_url($id)."' title='Edit Data'><i class='icon-note'></i></a>";
        //         return $data;
        //     });
        // }        
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

     /** Get Data Untuk Tambah */
    public function getnomorpajak()
    {
        return $this->db->query("
            SELECT i_pajak_id ||n_year|| n_start as nomor_mulai, i_pajak_id||n_year || n_end as nomor_akhir,  
            n_start, n_end, i_pajak_id||n_year AS i_pajak_id
            FROM tr_pajak 
            WHERE i_company = '$this->i_company'
        ", FALSE);
    }

    public function get_nota_mulai($cari) {
        return $this->db->query("
            select i_nota_id, to_char(d_nota, 'dd-mm-yyyy') as d_nota from tm_nota where f_nota_cancel = 'f' and f_pajak_cancel = 'f' 
            and i_seri_pajak is null and i_company = '$this->i_company' and i_nota_id ilike '%$cari%'
            order by i_nota_id asc
        ", FALSE);
    }

    public function get_nota_akhir($cari ,$i_nota)
    {
        return $this->db->query("
            select i_nota_id, to_char(d_nota, 'dd-mm-yyyy') as d_nota from tm_nota where f_nota_cancel = 'f' and f_pajak_cancel = 'f' 
            and i_seri_pajak is null and i_company = '$this->i_company' and i_nota_id ilike '%$cari%' and i_nota_id > '$i_nota'
            order by i_nota_id asc
        ", FALSE);
    }

    public function get_looping_nota($i_nota_mulai, $i_nota_akhir)
    {
        return $this->db->query("
            select i_nota_id from tm_nota where f_nota_cancel = 'f' and f_pajak_cancel = 'f' 
            and i_seri_pajak is null and i_company = '$this->i_company'
            and i_nota_id between '$i_nota_mulai' and '$i_nota_akhir'
            order by i_nota_id asc
        ", FALSE);
    }

    /*fuction check len*/
    function checklen($param) {
        $len = strlen($param);
        $zero = "";
        for ($i = 8; $i > $len; $i--) {
            $zero = $zero."0";
        }
        return $zero.$param;
    }

    /** Update Data */
    public function update($i_nota_id, $nomor_segmen, $n_start)
    {
        $seri = $nomor_segmen.$n_start;
        $this->db->query("
            UPDATE tm_nota set i_seri_pajak = '$seri' , d_pajak = d_nota  where i_company = '$this->i_company' and i_nota_id = '$i_nota_id'
        ", FALSE);
    }

    /** Update Data */
    public function updatemaster($n_start)
    {
        $this->db->query("
            UPDATE tr_pajak set n_start = '$n_start' where i_company = '$this->i_company'
        ", FALSE);
    }








    /** Simpan Data */
    public function save($kode, $nama, $norek, $namarek)
    {
        $table = array(
        	'i_company'	=> $this->i_company,
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
    public function cek_edit($kode,$kodeold)
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
    // public function update($id, $kode, $n_start, $n_end)
    // {
    //     $table = array(
    //         'i_pajak_id' => $kode, 
    //         'n_start' => $n_start,  
    //         'n_end' => $n_end, 
    //         'd_update'  => date('Y-m-d H:i:s'), 
    //     );
    //     $this->db->where('i_pajak', $id);
    //     $this->db->update('tr_pajak', $table);
    // }
}

/* End of file Mmaster.php */
