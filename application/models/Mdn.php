<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mdn extends CI_Model
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

        $i_supplier = $this->input->post('i_supplier', TRUE);
        if ($i_supplier == '') {
            $i_supplier = $this->uri->segment(5);
        }

        if ($i_supplier != '0') {
            $supplier = "AND a.i_supplier = '$i_supplier' ";
        } else {
            $supplier = "";
        }

        $dfrom  = date('Y-m-d', strtotime($dfrom));
        $dto    = date('Y-m-d', strtotime($dto));

        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("SELECT
                a.d_dn as tgl,
                a.i_dn as id,
                a.d_dn as d_dn,
                a.i_dn_id,
                to_char(a.d_dn, 'YYYYMM') as i_periode,
                b.i_bbr_id,
                to_char(b.d_bbr, 'DD FMMonth YYYY') as d_bbr,
                e.e_supplier_name as supp,
                a.v_netto::money as v_netto,
                a.v_sisa::money as v_sisa,
                i_alokasi_id as f_referensi,
                a.f_dn_cancel as f_status,
                '$dfrom' AS dfrom,
                '$dto' AS dto,
                '$i_supplier' AS i_supplier
            FROM
                tm_dn a
            inner join tm_bbr b on
                (b.i_bbr = a.i_bbr)
            inner join tr_supplier e on
                (e.i_supplier = a.i_supplier)
            left join tm_alokasidn ex on (ex.i_dn = a.i_dn and f_alokasi_cancel='f')
            WHERE
                a.i_company = '$this->i_company'
                AND a.d_dn BETWEEN '$dfrom' AND '$dto'
                $supplier
            ORDER BY
                1 ASC
        ", FALSE);

        $datatables->edit('f_status', function ($data) {
            if ($data['f_status'] == 't') {
                $status = $this->lang->line('Batal');
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
            $i_supplier = $data['i_supplier'];
            $v_netto    = $data['v_netto'];
            $v_sisa     = $data['v_sisa'];
            $i_periode  = $data['i_periode'];
            $data       = '';
            $data      .= "<a href='" . base_url() . $this->folder . '/view/' . encrypt_url($id) . '/' . encrypt_url($dfrom) . '/' . encrypt_url($dto) . '/' . encrypt_url($i_supplier) . "' title='View Data'><i class='fa fa-eye fa-lg warning darken-4 mr-1'></i></a>";
            if ($i_periode >= get_periode()) {
                if (check_role($this->id_menu, 3) && $f_status == 'f'  && ($v_netto == $v_sisa)) {
                    $data      .= "<a href='" . base_url() . $this->folder . '/edit/' . encrypt_url($id)  . '/' .  encrypt_url($dfrom) . '/' . encrypt_url($dto) . '/' . encrypt_url($i_supplier) . "' title='Edit Data'><i class='fa fa-pencil-square success darken-4 fa-lg mr-1'></i></a>";
                }
                // if (check_role($this->id_menu, 5) && $f_status == 'f') {
                //     $data      .= "<a href='#' onclick='openLink(\"" . $this->folder . "\",\"" . encrypt_url($id . '|t') . "\"); return false;' title='Print KN'><i class='fa fa-print fa-lg blue darken-4 mr-1'></i></a>";
                // }
                // if (check_role($this->id_menu, 5) && $f_status == 'f') {
                //     $data      .= "<a href='#' onclick='openLink(\"" . $this->folder . "\",\"" . encrypt_url($id . '|f') . "\"); return false;' title='Print Nota Retur'><i class='fa fa-print fa-lg purple darken-4 mr-1'></i></a>";
                // }
                if (check_role($this->id_menu, 4) && $f_status == 'f'  && ($v_netto == $v_sisa)) {
                    $data      .= "<a href='#' onclick='sweetdeletev333raya(\"" . $this->folder . "\",\"" . $id . "\",\"" . encrypt_url($dfrom)  . "\",\"" . encrypt_url($dto)  . '/' . encrypt_url($i_supplier) . "\");' title='Delete Data'><i class='fa fa-trash fa-lg red darken-4'></i></a>";
                }
            }
            return $data;
        });
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('i_supplier');
        $datatables->hide('i_periode');
        $datatables->hide('id');
        return $datatables->generate();
    }

    /** List Datatable */
    public function serversidex()
    {
        $dfrom = $this->input->post('dfrom', TRUE);
        if ($dfrom == '') {
            $dfrom = $this->uri->segment(3);
        }

        $dto = $this->input->post('dto', TRUE);
        if ($dto == '') {
            $dto = $this->uri->segment(4);
        }

        $i_supplier = $this->input->post('i_supplier', TRUE);
        if ($i_supplier == '') {
            $i_supplier = $this->uri->segment(5);
        }

        if ($i_supplier != '0') {
            $supplier = "AND a.i_supplier = '$i_supplier' ";
        } else {
            $supplier = "";
        }

        $dfrom  = date('Y-m-d', strtotime($dfrom));
        $dto    = date('Y-m-d', strtotime($dto));

        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("SELECT
                d_bbr as tgl,
                to_char(d_bbr, 'YYYYMM') as i_periode,
                i_bbr as id,
                d_bbr,
                i_bbr_id,
                e.e_supplier_name as supp,
                a.f_bbr_cancel as f_status,
                '$dfrom' AS dfrom,
                '$dto' AS dto,
                '$i_supplier' AS i_supplier
            FROM
                tm_bbr a
            inner join tr_supplier e on
                (e.i_supplier = a.i_supplier)
            WHERE
                a.i_company = '$this->i_company'
                AND a.d_bbr BETWEEN '$dfrom' AND '$dto'
                AND a.f_bbr_cancel = 'f' 
                AND a.f_dn = 'f'
                $supplier
            ORDER BY
                1 DESC
        ", FALSE);

        $datatables->edit('f_status', function ($data) {
            if ($data['f_status'] == 't') {
                $status = $this->lang->line('Batal');
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
            $i_periode  = $data['i_periode'];
            $dfrom      = $data['dfrom'];
            $dto        = $data['dto'];
            $i_supplier = $data['i_supplier'];
            $data       = '';
            if ($i_periode >= get_periode()) {
                if (check_role($this->id_menu, 3)) {
                    $data      .= "<a href='" . base_url() . $this->folder . '/add/' . encrypt_url($id) . '/' . encrypt_url($dfrom) . '/' . encrypt_url($dto) . '/' . encrypt_url($i_supplier) . "' title='Tambah Data'><i class='fa fa-check-circle-o success darken-4 fa-lg mr-1'></i></a>";
                }
            }
            return $data;
        });
        $datatables->hide('id');
        $datatables->hide('i_periode');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('i_supplier');
        return $datatables->generate();
    }




    /** Get Supplier */
    public function get_supplier0($cari)
    {
        return $this->db->query("SELECT distinct
                xx.i_supplier, i_supplier_id , e_supplier_name AS e_supplier_name
            FROM 
                tr_supplier xx
            inner join tm_bbr yy on (xx.i_supplier =yy.i_supplier)
            WHERE 
                (e_supplier_name ILIKE '%$cari%' OR i_supplier_id ILIKE '%$cari%')
                AND xx.i_company = '$this->i_company' 
                AND f_supplier_active = 'true' 
            ORDER BY 3 ASC
        ", FALSE);
    }

    /** Get Data Untuk Tambah */
    public function get_data_referensi($id)
    {
        return $this->db->query("SELECT
                a.i_bbr,
                a.i_bbr_id,
                to_char(a.d_bbr, 'DD FMMonth YYYY') as date_now,
                a.d_bbr,
                a.i_supplier,
                b.e_supplier_name,
                c.e_supplier_groupname ,
                a.e_remark
            FROM
                tm_bbr a
            inner join tr_supplier b on
                (b.i_supplier = a.i_supplier)
            inner join tr_supplier_group c on
                (b.i_supplier_group = c.i_supplier_group)
            WHERE
                a.i_bbr = '$id'
        ", FALSE);
    }

    /** Get Detail Untuk Tambah */
    public function get_data_detail_referensi($id)
    {
        return $this->db->query("SELECT
                a.*,
                b.i_product_id ,
                b.e_product_name ,
                c.e_product_motifname ,
                d.e_product_gradename ,
                a.e_remark as ket
            from
                tm_bbr_item a
            inner join tr_product b on (b.i_product = a.i_product)
            inner join tr_product_motif c on (c.i_product_motif = a.i_product_motif)
            inner join tr_product_grade d on (d.i_product_grade = a.i_product_grade)
            WHERE
                a.i_bbr = '$id'
            ORDER BY
                a.n_item_no ASC
        ", FALSE);
    }


    /** Running Number Dokumen*/
    public function running_number($thbl, $tahun)
    {
        $cek = $this->db->query("SELECT 
                substring(i_dn_id, 1, 3) AS kode 
            FROM tm_dn 
            WHERE i_company = '$this->i_company'
            ORDER BY i_dn_id DESC");
        if ($cek->num_rows() > 0) {
            $kode = $cek->row()->kode;
        } else {
            $kode = 'DNR';
        }
        $query  = $this->db->query("SELECT
                max(substring(i_dn_id, 10, 6)) AS max
            FROM
                tm_dn
            WHERE to_char (d_dn, 'yyyy') >= '$tahun'
            AND i_company = '$this->i_company'
            AND substring(i_dn_id, 1, 3) = '$kode'
            AND substring(i_dn_id, 5, 2) = substring('$thbl',1,2)
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
        $i_document = str_replace("_", "", $this->input->post('i_document'));
        return $this->db->query("SELECT 
                i_dn_id
            FROM 
                tm_dn 
            WHERE 
                upper(trim(i_dn_id)) = upper(trim('$i_document'))
                AND i_company = '$this->i_company'
        ", FALSE);
    }

    /** Simpan Data */
    public function save()
    {
        $ym = date('ym', strtotime($this->input->post('d_document')));
        $Y  = date('Y', strtotime($this->input->post('d_document')));
        $f_masalah  = ($this->input->post('f_masalah') == 'on') ? TRUE : FALSE;
        $f_insentif = ($this->input->post('f_insentif') == 'on') ? TRUE : FALSE;
        $i_dn_id = $this->running_number($ym, $Y);
        $i_bbr = $this->input->post('i_refference');
        $header = array(
            'i_company'             => $this->i_company,
            'i_dn_id'               => $i_dn_id,
            'd_dn'                  => $this->input->post('d_document'),
            'i_supplier'            => $this->input->post('i_supplier'),
            'i_bbr'                 => $i_bbr,
            'd_entry'               => current_datetime(),
            'e_remark'              => $this->input->post('e_remark'),
            'f_masalah'             => $f_masalah,
            'f_insentif'            => $f_insentif,
            'v_netto'               => str_replace(",", "", $this->input->post('tfoot_total')),
            'v_sisa'                => str_replace(",", "", $this->input->post('tfoot_total')),
        );
        $this->db->insert('tm_dn', $header);
        $this->db->query("UPDATE tm_bbr SET f_dn = 't' WHERE i_bbr = '$i_bbr'", FALSE);
    }

    /** Get Data Untuk Edit */
    public function get_data($id)
    {
        return $this->db->query("SELECT
                a.*,
                b.e_supplier_name ,
                c.i_bbr_id,
                c.d_bbr,
                d.e_supplier_groupname 
            FROM
                tm_dn a 
            inner join tr_supplier b on (b.i_supplier=a.i_supplier)
            inner join tm_bbr c on (c.i_bbr=a.i_bbr)
            inner join tr_supplier_group d on (b.i_supplier_group = d.i_supplier_group)
            WHERE
                a.i_dn = '$id'
        ", FALSE);
    }

    /** Get Detail Untuk Edit */
    public function get_data_detail($id)
    {
        return $this->db->query("SELECT
                a.*,
                b.i_product_id ,
                b.e_product_name ,
                c.e_product_motifname ,
                d.e_product_gradename ,
                a.e_remark as ket
            FROM
                tm_dn r
            inner join tm_bbr rb on (rb.i_bbr=r.i_bbr)
            inner join tm_bbr_item a on (a.i_bbr=rb.i_bbr)
            inner join tr_product b on (b.i_product = a.i_product)
            inner join tr_product_motif c on (c.i_product_motif = a.i_product_motif)
            inner join tr_product_grade d on (d.i_product_grade = a.i_product_grade)
            WHERE
                r.i_dn = '$id'
            ORDER BY
                a.n_item_no ASC
        ", FALSE);
    }

    /** Cek Apakah Data Sudah Ada Pas Edit */
    public function cek_edit()
    {
        $i_document = str_replace("_", "", $this->input->post('i_document'));
        $i_document_old = str_replace("_", "", $this->input->post('i_document_old'));
        return $this->db->query("SELECT 
                i_dn_id
            FROM 
                tm_dn 
            WHERE 
                trim(upper(i_dn_id)) <> trim(upper('$i_document_old'))
                AND trim(upper(i_dn_id)) = trim(upper('$i_document'))
                AND i_company = '$this->i_company'
         ", FALSE);
    }

    /** Update Data */
    public function update()
    {
        $id = $this->input->post('id');
        $f_masalah  = ($this->input->post('f_masalah') == 'on') ? TRUE : FALSE;
        $f_insentif = ($this->input->post('f_insentif') == 'on') ? TRUE : FALSE;
        $i_bbr = $this->input->post('i_refference');
        $i_dn_id = $this->input->post('i_document');
        $header = array(
            'i_company'             => $this->i_company,
            'i_dn_id'               => $i_dn_id,
            'd_dn'                  => $this->input->post('d_document'),
            'i_supplier'            => $this->input->post('i_supplier'),
            'i_bbr'                 => $i_bbr,
            'd_entry'               => current_datetime(),
            'e_remark'              => $this->input->post('e_remark'),
            'f_masalah'             => $f_masalah,
            'f_insentif'            => $f_insentif,
            'v_netto'               => str_replace(",", "", $this->input->post('tfoot_total')),
            'v_sisa'                => str_replace(",", "", $this->input->post('tfoot_total')),
        );
        $this->db->where('i_dn', $id);
        $this->db->update('tm_dn', $header);
        $this->db->query("UPDATE tm_bbr SET f_dn = 't' WHERE i_bbr = '$i_bbr'", FALSE);
    }

    /** Cancel Data */
    public function cancel($id,$alasan,$i_dn_id)
    {
        $table = array(
            'f_dn_cancel' => 't',
        );
        $this->db->where('i_dn', $id);
        $this->db->update('tm_dn', $table);

        $this->db->query("UPDATE tm_bbr SET f_dn = 'f' WHERE i_bbr = (SELECT i_bbr FROM tm_dn WHERE i_dn = '$id')", FALSE);

        $this->db->query("INSERT INTO tx_del (i_company, i_user, e_user_name, e_dokumen, e_alasan)
        VALUES ('$this->i_company', '$this->i_user', '$this->e_user_name', 'No DEBET NOTA : $i_dn_id', '$alasan')");
    }
}

/* End of file Mmaster.php */
