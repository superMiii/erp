<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mkonversi extends CI_Model
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
                a.d_convertion as tgl, 
                a.i_convertion AS id,
                a.d_convertion, 
                a.i_convertion_id,
                to_char(a.d_convertion, 'YYYYMM') AS i_periode,
                b.i_product_id,
                b.e_product_name,
                a.n_convertion,
                a.f_convertion_cancel AS f_status,
                '$dfrom' AS dfrom,
                '$dto' AS dto
            FROM
                tm_convertion a
            INNER JOIN tr_product b ON
                (b.i_product = a.i_product)
            WHERE
                a.i_company = '$this->i_company'
                AND a.d_convertion BETWEEN '$dfrom' AND '$dto'
        ", FALSE);

        $datatables->edit('f_status', function ($data) {
            if ($data['f_status'] == 't') {
                $status = $this->lang->line('Tidak Aktif');
                $color  = 'red';
            } else {
                $color  = 'teal';
                $status = $this->lang->line('Aktif');
            }
            $data = "<span class='badge bg-" . $color . " badge-pill'>" . $status . "</span>";
            return $data;
        });

        /** Cek Hak Akses, Apakah User Bisa Edit */
        $datatables->add('action', function ($data) {
            $id         = trim($data['id']);
            $f_status   = $data['f_status'];
            $dfrom      = $data['dfrom'];
            $dto        = $data['dto'];
            $i_periode  = $data['i_periode'];
            $data       = '';
            $data      .= "<a href='" . base_url() . $this->folder . '/view/' . encrypt_url($id) . '/' . encrypt_url($dfrom) . '/' . encrypt_url($dto) . "' title='View Data'><i class='fa fa-eye fa-lg warning darken-4 mr-1'></i></a>";
            if ($i_periode >= get_periode3()) {
                if (check_role($this->i_menu, 3) && $f_status == 'f') {
                    $data      .= "<a href='" . base_url() . $this->folder . '/edit/' . encrypt_url($id)  . '/' .  encrypt_url($dfrom) . '/' . encrypt_url($dto) . "' title='Edit Data'><i class='fa fa-pencil-square success darken-4 fa-lg mr-1'></i></a>";
                }
                if (check_role($this->i_menu, 5) && $f_status == 'f') {
                    $data      .= "<a href='#' onclick='openLink(\"" . $this->folder . "\",\"" . encrypt_url($id) . "\"); return false;' title='Print Data'><i class='fa fa-print fa-lg blue darken-4 mr-1'></i></a>";
                }
                if (check_role($this->i_menu, 4) && $f_status == 'f') {
                    $data      .= "<a href='#' onclick='sweetdeletev2link(\"" . $this->folder . "\",\"" . $id . "\",\"" . encrypt_url($dfrom)  . "\",\"" . encrypt_url($dto)  . "\");' title='Delete Data'><i class='fa fa-trash fa-lg red darken-4'></i></a>";
                }
            }
            return $data;
        });
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('i_periode');
        $datatables->hide('id');
        return $datatables->generate();
    }

    /** Running Number Dokumen*/
    public function running_number($thbl, $tahun)
    {
        $cek = $this->db->query("SELECT 
                substring(i_convertion_id, 1, 3) AS kode 
            FROM tm_convertion 
            WHERE i_company = '$this->i_company'
            ORDER BY i_convertion DESC");
        if ($cek->num_rows() > 0) {
            $kode = $cek->row()->kode;
        } else {
            $kode = 'KVR';
        }
        $query  = $this->db->query("SELECT
                max(substring(i_convertion_id, 10, 6)) AS max
            FROM
                tm_convertion
            WHERE to_char (d_convertion, 'yyyy') >= '$tahun'
            AND i_company = '$this->i_company'
            AND substring(i_convertion_id, 1, 3) = '$kode'
            AND substring(i_convertion_id, 5, 2) = substring('$thbl',1,2)
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
        return $this->db->query("SELECT 
                i_convertion_id
            FROM 
                tm_convertion 
            WHERE 
                upper(trim(i_convertion_id)) = upper(trim('$i_document'))
                AND i_company = '$this->i_company'
        ", FALSE);
    }

    /** Get Data Product */
    public function get_product($cari)
    {
        return $this->db->query("SELECT
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

    public function get_product2($cari,$i_product_asal)
    {
        return $this->db->query("SELECT
                i_product,
                i_product_id,
                initcap(e_product_name) AS e_product_name
            FROM
                tr_product
            WHERE
                (i_product_id ILIKE '%$cari%'
                    OR e_product_name ILIKE '%$cari%')
                AND i_company = '$this->i_company'
                and i_product != '$i_product_asal'
            ORDER BY
                e_product_name ASC
        ", FALSE);
    }

    /** Get Data Detail Product */
    public function get_product_detail($i_product)
    {
        return $this->db->query("SELECT DISTINCT
                a.i_product,
                b.i_product_grade,
                a.i_product_motif,
                initcap(a.e_product_name) AS e_product_name,
                initcap(e_product_motifname) AS e_product_motifname,
                initcap(e_product_gradename) AS e_product_gradename,
                b.n_quantity_stock as n_stk
            FROM
                tr_product a
            INNER JOIN tm_ic b ON 
                (b.i_product = a.i_product)
            INNER JOIN tr_product_grade c ON
                (c.i_product_grade = b.i_product_grade)
            INNER JOIN tr_product_motif e ON
                (e.i_product_motif = a.i_product_motif)
            inner join tr_store s on (s.i_store=b.i_store)
            WHERE
                a.i_product = '$i_product'
                AND c.f_default = 't'
                AND c.f_product_gradeactive = 't'
                AND a.i_company = '$this->i_company'
                and s.f_store_pusat = 't'
        ", FALSE);
    }

    /** Simpan Data */
    public function save()
    {
        $query = $this->db->query("SELECT max(i_convertion)+1 AS id FROM tm_convertion", TRUE);
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

        $n_convertion = ($this->input->post('n_convertion') == '') ? 0 : $this->input->post('n_convertion');

        $header = array(
            'i_company'         => $this->session->i_company,
            'i_convertion'      => $id,
            'i_convertion_id'   => strtoupper($this->input->post('i_document')),
            'd_convertion'      => $this->input->post('d_document'),
            'i_product'         => $this->input->post('i_product_asal'),
            'i_product_motif'   => $this->input->post('i_product_motif_asal'),
            'i_product_grade'   => $this->input->post('i_product_grade_asal'),
            'e_product_name'    => $this->input->post('e_product_name_asal'),
            'n_convertion'      => $n_convertion,
            'd_entry'           => current_datetime(),
        );
        $this->db->insert('tm_convertion', $header);
        if (is_array($this->input->post('i_product')) || is_object($this->input->post('i_product'))) {
            $i = 0;
            foreach ($this->input->post('i_product') as $i_product) {
                $convertion = ($this->input->post('convertion')[$i] == '') ? 0 : $this->input->post('convertion')[$i];
                if ($i_product != '' && $convertion > 0) {
                    $item = array(
                        'i_convertion'      => $id,
                        'i_product'         => $i_product,
                        'i_product_motif'   => $this->input->post('i_product_motif')[$i],
                        'i_product_grade'   => $this->input->post('i_product_grade')[$i],
                        'e_product_name'    => $this->input->post('e_product_name')[$i],
                        'n_convertion'      => $convertion,
                    );
                    $this->db->insert('tm_convertion_item', $item);
                }
                $i++;
            }
        } else {
            die;
        }
    }

    /** Get Data Untuk Edit */
    public function getdata($id)
    {
        return $this->db->query("SELECT
                a.*,
                b.i_product_id,
                b.e_product_name,
                c.e_product_motifname,
                d.e_product_gradename,
                r.n_quantity_stock as n_stk
            FROM
                tm_convertion a
            INNER JOIN tr_product b ON
                (b.i_product = a.i_product)
            INNER JOIN tr_product_motif c ON
                (c.i_product_motif = a.i_product_motif)
            INNER JOIN tr_product_grade d ON 
                (d.i_product_grade = a.i_product_grade)
            INNER JOIN tm_ic r ON (r.i_product = b.i_product)
            inner join tr_store s on (s.i_store=r.i_store)
            WHERE
                a.i_convertion = '$id'
	            and s.f_store_pusat = 't'
        ", FALSE);
    }

    /** Get Data Untuk Edit */
    public function getdetail($id)
    {
        return $this->db->query("SELECT
                a.*,
                b.i_product_id,
                b.e_product_name,
                c.e_product_motifname,
                d.e_product_gradename
            FROM
                tm_convertion_item a
            INNER JOIN tr_product b ON
                (b.i_product = a.i_product)
            INNER JOIN tr_product_motif c ON
                (c.i_product_motif = a.i_product_motif)
            INNER JOIN tr_product_grade d ON 
                (d.i_product_grade = a.i_product_grade)
            WHERE
                a.i_convertion = '$id'
            ORDER BY
                a.i_convertion_item ASC
        ", FALSE);
    }

    /** Cek Apakah Data Sudah Ada Pas Edit */
    public function cek_edit()
    {
        $i_document = $this->input->post('i_document', TRUE);
        $i_document_old = $this->input->post('i_document_old', TRUE);
        return $this->db->query("SELECT 
                i_convertion_id
            FROM 
                tm_convertion 
            WHERE 
                trim(upper(i_convertion_id)) <> trim(upper('$i_document_old'))
                AND trim(upper(i_convertion_id)) = trim(upper('$i_document'))
                AND i_company = '$this->i_company'
        ", FALSE);
    }

    /** Update Data */
    public function update()
    {
        $id = $this->input->post('id');
        $n_convertion = ($this->input->post('n_convertion') == '') ? 0 : $this->input->post('n_convertion');
        $header = array(
            'i_convertion_id'   => strtoupper($this->input->post('i_document')),
            'd_convertion'      => $this->input->post('d_document'),
            'i_product'         => $this->input->post('i_product_asal'),
            'i_product_motif'   => $this->input->post('i_product_motif_asal'),
            'i_product_grade'   => $this->input->post('i_product_grade_asal'),
            'e_product_name'    => $this->input->post('e_product_name_asal'),
            'n_convertion'      => $n_convertion,
            'd_update'          => current_datetime(),
        );
        $this->db->where('i_convertion', $id);
        $this->db->update('tm_convertion', $header);
        if (is_array($this->input->post('i_product')) || is_object($this->input->post('i_product'))) {
            $this->db->where('i_convertion', $id);
            $this->db->delete('tm_convertion_item');
            $i = 0;
            foreach ($this->input->post('i_product') as $i_product) {
                $convertion = ($this->input->post('convertion')[$i] == '') ? 0 : $this->input->post('convertion')[$i];
                if ($i_product != '' && $convertion > 0) {
                    $item = array(
                        'i_convertion'      => $id,
                        'i_product'         => $i_product,
                        'i_product_motif'   => $this->input->post('i_product_motif')[$i],
                        'i_product_grade'   => $this->input->post('i_product_grade')[$i],
                        'e_product_name'    => $this->input->post('e_product_name')[$i],
                        'n_convertion'      => $convertion,
                    );
                    $this->db->insert('tm_convertion_item', $item);
                }
                $i++;
            }
        } else {
            die;
        }
    }

    /** Cancel Data */
    public function cancel($id)
    {
        $table = array(
            'f_convertion_cancel' => 't',
        );
        $this->db->where('i_convertion', $id);
        $this->db->update('tm_convertion', $table);
    }
}

/* End of file Mmaster.php */
