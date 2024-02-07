<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mtgl extends CI_Model
{
    public function getdata()
    {
        return $this->db->query("SELECT 
                a.* 
            FROM 
                tx_tgl a
        ", FALSE);
    }

    /** Update Periode */
    public function update($d_tgl, $f_kunci)
    {
        $id = $this->input->post('id');
        $f_kunci = ($this->input->post('f_kunci') == 'on') ? 't' : 'f';
        $header = array(
            'i_t'               => $id,
            'd_tgl'             => $this->input->post('d_document'),
            'f_tx'              => $f_divisi,
        );
        $this->db->where('i_t', $id);
        $this->db->update('tx_tgl', $header);        
    }

    
}
