<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mtgl extends CI_Model
{

    /** List Datatable */
    public function serverside()
    {
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("SELECT
                i_t,
                d_tgl,
                f_tx AS f_status
            FROM
                tx_tgl
                ", FALSE);

        $datatables->edit('f_status', function ($data) {
            $id         = $data['i_t'];
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
                $id         = trim($data['i_t']);
                $data       = '';
                $data      .= "<a href='" . base_url() . $this->folder . '/edit/' . encrypt_url($id) . "' title='Edit Data'><i class='fa fa-pencil-square success darken-4 fa-lg mr-1'></i></a>";
                return $data;
            });
        }
        return $datatables->generate();
    }

    public function changestatus($id)
    {
        $this->db->select('f_tx');
        $this->db->from('tx_tgl');
        $this->db->where('i_t', $id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $status = $query->row()->f_tx;
        } else {
            $status = 'f';
        }
        if ($status == 'f') {
            $fstatus = 't';
        } else {
            $fstatus = 'f';
        }
        $table = array(
            'f_tx' => $fstatus,
        );
        $this->db->where('i_t', $id);
        $this->db->update('tx_tgl', $table);
    }


    /** Get Data Untuk Edit */
    public function getdata($id)
    {
        return $this->db->query("SELECT 
                a.* 
            FROM 
                tx_tgl a
            ", FALSE);
    }

    /** Update Data */
    public function update($id, $d_tgl, $f_kunci)
    {
        $table = array(
            'd_tgl'   => $d_tgl,
            'f_tx'    => $f_kunci,
        );
        $this->db->where('i_t', $id);
        $this->db->update('tx_tgl', $table);
    }
}

/* End of file Mmaster.php */
