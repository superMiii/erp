<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mbarang_p extends CI_Model
{

    /** List Datatable */
    public function serverside()
    {
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("SELECT
                i_product as id,
	            f_pareto as f_pareto,
	            i_product_id,
	            e_product_name,
	            tr_product_motif.e_product_motifname,
                s.e_product_statusname,
	            f_product_active as f_status
            FROM
            tr_product
            inner join tr_product_motif on
	            tr_product.i_product_motif = tr_product_motif.i_product_motif
            inner join tr_product_status s on
                (s.i_product_status = tr_product.i_product_status)
            WHERE
                tr_product.i_company = '$this->i_company'
            ORDER BY 4 ASC ", FALSE);

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

        $datatables->edit('f_pareto', function ($data) {
            $id         = $data['id'];
            if ($data['f_pareto'] == 't') {
                $pareto = $this->lang->line('Pareto');
                $color  = 'amber';
            } else {
                $pareto = $this->lang->line('Nonpareto');
                $color  = 'cyan';
            }
            $data = "<button class='btn btn-outline-" . $color . " btn-sm round' onclick='changepareto(\"" . $this->folder . "\",\"" . $id . "\");'>" . $pareto . "</button>";
            return $data;
        });

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

    public function changepareto($id)
    {
        $this->db->select('f_pareto');
        $this->db->from('tr_product');
        $this->db->where('i_product', $id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $pareto = $query->row()->f_pareto;
        } else {
            $pareto = 'f';
        }
        if ($pareto == 'f') {
            $fpareto = 't';
        } else {
            $fpareto = 'f';
        }
        $table = array(
            'f_pareto' => $fpareto,
        );
        $this->db->where('i_product', $id);
        $this->db->update('tr_product', $table);
    }
}

/* End of file Mmaster.php */
