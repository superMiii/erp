<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mbonmasuk extends CI_Model
{

    /** List Datatable */
    public function serverside()
    {
        $dfrom = $this->input->post('dfrom', TRUE);
        if ($dfrom == '') {
            $dfrom = $this->uri->segment(3);
        }

        $dto = $this->input->post('dto', TRUE);
        if ($dto == '') {
            $dto = $this->uri->segment(4);
        }

        $dfrom  = date('Y-m-d', strtotime($dfrom));
        $dto    = date('Y-m-d', strtotime($dto));

        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("SELECT
                a.i_gi AS id,
                i_gi_id,
                d_gi,
                a.e_remark,
                f_gi_cancel AS f_status
            FROM
                tm_gi a
            WHERE
                a.i_company = '$this->i_company'
                AND d_gi BETWEEN '$dfrom' AND '$dto'
            ORDER BY
                d_gi DESC 
        ", FALSE);

        $datatables->edit('f_status', function ($data) {
            $id         = $data['id'];
            if ($data['f_status'] == 't') {
                $status = $this->lang->line('Tidak Aktif');
                $color  = 'danger';
            } else {
                $status = $this->lang->line('Aktif');
                $color  = 'success';
            }
            $data = "<button class='btn btn-outline-" . $color . " btn-sm round'>" . $status . "</button>";
            return $data;
        });

        /** Cek Hak Akses, Apakah User Bisa Edit */
        $datatables->add('action', function ($data) {
            $id         = trim($data['id']);
            $f_status   = $data['f_status'];
            $data       = '';
            $data      .= "<a href='" . base_url() . $this->folder . '/view/' . encrypt_url($id) . "' title='View Data'><i class='icon-eye text-warning mr-1'></i></a>";
            if (check_role($this->i_menu, 3) && $f_status == 'f') {
                $data      .= "<a href='" . base_url() . $this->folder . '/edit/' . encrypt_url($id) . "' title='Edit Data'><i class='icon-note mr-1'></i></a>";
            }
            if (check_role($this->i_menu, 5) && $f_status == 'f') {
                $data      .= "<a href='" . base_url() . $this->folder . '/print/' . encrypt_url($id) . "' target='_blank' title='Print Data'><i class='icon-printer success success-darken-4 mr-1'></i></a>";
            }
            if (check_role($this->i_menu, 4) && $f_status == 'f') {
                $data      .= "<a href='#' onclick='sweetdelete(\"" . $this->folder . "\",\"" . $id . "\");' title='Delete Data'><i class='fa fa-times text-danger'></i></a>";
            }
            return $data;
        });
        return $datatables->generate();
    }

    /** Running Number Dokumen*/
    public function running_number($thbl, $tahun)
    {
        $cek = $this->db->query("
            SELECT 
                substring(i_gi_id, 1, 2) AS kode 
            FROM tm_gi 
            WHERE i_company = '$this->i_company'
            ORDER BY i_gi DESC");
        if ($cek->num_rows() > 0) {
            $kode = $cek->row()->kode;
        } else {
            $kode = 'GI';
        }
        $query  = $this->db->query("
            SELECT
                max(substring(i_gi_id, 9, 6)) AS max
            FROM
                tm_gi
            WHERE to_char (d_gi, 'yyyy') >= '$tahun'
            AND i_company = '$this->i_company'
            AND substring(i_gi_id, 1, 2) = '$kode'
            AND substring(i_gi_id, 4, 2) = substring('$thbl',1,2)
        ", false);
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $no = $row->max;
            }
            $number = $no + 1;
            settype($number, "string");
            $n = strlen($number);
            while ($n < 6) {
                $number = "0" . $number;
                $n = strlen($number);
            }
            $number = $kode . "-" . $thbl . "-" . $number;
            return $number;
        } else {
            $number = "000001";
            $nomer  = $kode . "-" . $thbl . "-" . $number;
            return $nomer;
        }
    }

    /** Cek Apakah Data Sudah Ada */
    public function cek_code()
    {
        $i_document = $this->input->post('i_document', TRUE);
        return $this->db->query("
            SELECT 
                i_gi_id
            FROM 
                tm_gi 
            WHERE 
                upper(trim(i_gi_id)) = upper(trim('$i_document'))
                AND i_company = '$this->i_company'
        ", FALSE);
    }

    /** Get Data Gudang */
    public function get_store($cari)
    {
        return $this->db->query("
            SELECT
                DISTINCT 	
                a.i_store,
                i_store_id,
                e_store_name
            FROM
                tr_store a
            INNER JOIN tr_area b ON
                (b.i_store = a.i_store)
            WHERE
                (e_store_name ILIKE '%$cari%'
                OR i_store_id ILIKE '%$cari%')
                AND f_store_active = 't'
                AND a.i_company = '$this->i_company'
                AND b.i_area IN (
                SELECT
                    i_area
                FROM
                    tm_user_area
                WHERE
                    i_user = '$this->i_user'
                    AND i_company = '$this->i_company')
            ORDER BY
                e_store_name
        ", FALSE);
    }

    /** Get Data Area */
    public function get_area($i_store)
    {
        return $this->db->query("
            SELECT
                i_area
            FROM
                tr_area
            WHERE
                i_store = '$i_store'
        ", FALSE);
    }

    /** Get Data Product */
    public function get_product($cari)
    {
        return $this->db->query("
            SELECT
                i_product,
                i_product_id,
                initcap(e_product_name) AS e_product_name
            FROM
                tr_product
            WHERE
                (i_product_id ILIKE '%$cari%'
                    OR e_product_name ILIKE '%$cari%')
                AND i_company = '$this->i_company'
            ORDER BY
                e_product_name ASC
        ", FALSE);
    }

    /** Get Data Detail Product */
    public function get_product_detail($i_product)
    {
        return $this->db->query("
            SELECT
                i_product,
                a.i_product_motif,
                i_product_id,
                initcap(e_product_name) AS e_product_name,
                b.e_product_motifname
            FROM
                tr_product a
            INNER JOIN tr_product_motif b ON
                (b.i_product_motif = a.i_product_motif)
            WHERE 
                a.i_product = '$i_product'
        ", FALSE);
    }

    /** Simpan Data */
    public function save()
    {
        $query = $this->db->query("SELECT max(i_gi)+1 AS id FROM tm_gi", TRUE);
        if ($query->num_rows() > 0) {
            $id = $query->row()->id;
            if ($id == null) {
                $id = 1;
            } else {
                $id = $id;
            }
        } else {
            $id = 1;
        }

        $this->db->trans_start();
        $header = array(
            'i_company'         => $this->session->i_company,
            'i_gi'              => $id,
            'i_gi_id'           => strtoupper($this->input->post('i_document')),
            'd_gi'              => $this->input->post('d_document_submit'),
            'd_entry'        => current_datetime(),
            'e_remark'          => $this->input->post('e_remark'),
        );
        $this->db->insert('tm_gi', $header);
        $this->db->trans_complete();

        if ($this->db->trans_status() === TRUE) {
            if ($this->input->post('jml') > 0) {
                $i = 0;
                foreach ($this->input->post('i_product') as $i_product) {
                    $item = array(
                        'i_gi'              => $id,
                        'i_product'         => $i_product,
                        'i_product_grade'   => 1,
                        'i_product_motif'   => $this->input->post('i_product_motif')[$i],
                        'n_quantity'        => $this->input->post('n_quantity')[$i],
                        'e_remark'          => $this->input->post('e_remarkitem')[$i],
                        'n_item_no'         => $i,
                    );
                    $this->db->insert('tm_gi_item', $item);
                    $i++;
                }
            } else {
                die;
            }
        } else {
            die;
        }
    }

    /** Get Data Untuk Edit */
    public function getdata($id)
    {
        return $this->db->query("
            SELECT 
                a.*
            FROM 
                tm_gi a
            WHERE
                i_gi = '$id'
        ", FALSE);
    }

    /** Get Data Untuk Edit */
    public function getdetail($id)
    {
        return $this->db->query("
            SELECT
                a.*,
                b.i_product_id,
                b.e_product_name,
                c.e_product_motifname
            FROM
                tm_gi_item a
            INNER JOIN tr_product b ON
                (b.i_product = a.i_product)
            INNER JOIN tr_product_motif c ON
                (c.i_product_motif = a.i_product_motif)
            WHERE
                a.i_gi = '$id'
            ORDER BY
                a.n_item_no ASC
        ", FALSE);
    }

    /** Cek Apakah Data Sudah Ada Pas Edit */
    public function cek_edit()
    {
        $i_document = $this->input->post('i_document', TRUE);
        $i_document_old = $this->input->post('i_document_old', TRUE);
        return $this->db->query("
            SELECT 
                i_gi_id
            FROM 
                tm_gi 
            WHERE 
                trim(upper(i_gi_id)) <> trim(upper('$i_document_old'))
                AND trim(upper(i_gi_id)) = trim(upper('$i_document'))
                AND i_company = '$this->i_company'
        ", FALSE);
    }

    /** Update Data */
    public function update()
    {
        $id = $this->input->post('id');
        $this->db->trans_start();
        $header = array(
            'i_company'         => $this->session->i_company,
            'i_gi_id'           => $this->input->post('i_document'),
            'd_gi'              => $this->input->post('d_document_submit'),
            'd_update'          => current_datetime(),
            'e_remark'          => $this->input->post('e_remark'),
        );
        $this->db->where('i_gi', $id);
        $this->db->update('tm_gi', $header);
        $this->db->trans_complete();

        if ($this->db->trans_status() === TRUE) {
            $jml = $this->input->post('jml');
            if ($jml > 0) {
                $this->db->where('i_gi', $id);
                $this->db->delete('tm_gi_item');
                $i = 0;
                foreach ($this->input->post('i_product') as $i_product) {
                    $item = array(
                        'i_gi'              => $id,
                        'i_product'         => $i_product,
                        'i_product_grade'   => 1,
                        'i_product_motif'   => $this->input->post('i_product_motif')[$i],
                        'n_quantity'           => $this->input->post('n_quantity')[$i],
                        'e_remark'          => $this->input->post('e_remarkitem')[$i],
                        'n_item_no'         => $i,
                    );
                    $this->db->insert('tm_gi_item', $item);
                    $i++;
                }
            } else {
                die;
            }
        } else {
            die;
        }
    }

    /** Cancel Data */
    public function cancel($id)
    {
        $table = array(
            'f_gi_cancel' => 't',
        );
        $this->db->where('i_gi', $id);
        $this->db->update('tm_gi', $table);
    }
}

/* End of file Mmaster.php */
