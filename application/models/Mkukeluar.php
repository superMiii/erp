<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mkukeluar extends CI_Model
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
        $datatables->query("SELECT distinct
                a.d_kuk as tgl,
                a.d_kuk,
                a.i_kuk_id,
                to_char(a.d_kuk, 'YYYYMM') as i_periode,
                c.e_supplier_name AS e_supplier,
                v_jumlah::money AS v_jumlah,                    
	            w.i_pv_id as f_referensi,
                w.alok as alok,
                f_kuk_cancel AS f_status,
                '$dfrom' AS dfrom ,
                '$dto' AS dto ,
                a.i_kuk AS id,
                '$i_supplier' AS i_supplier
            FROM
                tm_kuk a
            left JOIN tr_supplier c ON
                (c.i_supplier = a.i_supplier)
            left join (SELECT
                        y.i_company,
                        y.e_pv_refference_type_name,
                        z.i_pv,
                        z.i_pv_id,
                        i_pv_refference,
                        case when t.v_pv = t.v_pv_saldo then 0 else 1 end as alok
                    from
                        tm_pv_item t
                        inner join tr_pv_refference_type y on (y.i_pv_refference_type = t.i_pv_refference_type)
                        inner join tm_pv z on (z.i_pv=t.i_pv)
                        WHERE z.i_company = '$this->i_company' AND y.f_transfer = 't') w on 
            (w.i_pv_refference=a.i_kuk)   
            WHERE
                a.i_company = '$this->i_company'
                AND a.d_kuk BETWEEN '$dfrom' AND '$dto'
                $supplier
            ORDER BY
                a.i_kuk DESC
        ", FALSE);

        // $datatables->edit('f_referensi', function ($data) {
        //     if ($data['f_referensi'] == 'NOTNULL') {
        //         $status = $this->lang->line('Belum Digunakan');
        //         $color  = 'teal';
        //     } else {
        //         $color  = 'red';
        //         $status = $this->lang->line('Sudah Digunakan');
        //     }
        //     $data = "<span class='badge bg-" . $color . " badge-pill'>" . $status . "</span>";
        //     return $data;
        // });


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
            $i_supplier = $data['i_supplier'];
            $i_periode  = $data['i_periode'];
            $alok       = $data['alok'];
            $data       = '';
            $data      .= "<a href='" . base_url() . $this->folder . '/view/' . encrypt_url($id) . '/' . encrypt_url($dfrom) . '/' . encrypt_url($dto) . '/' . encrypt_url($i_supplier) . "' title='View Data'><i class='fa fa-eye fa-lg warning darken-4 mr-1'></i></a>";
            if ($i_periode >= get_periode()) {
                if (check_role($this->id_menu, 3) && $f_status == 'f' && ($alok == 0)) {
                    $data      .= "<a href='" . base_url() . $this->folder . '/edit/' . encrypt_url($id) . '/' . encrypt_url($dfrom) . '/' . encrypt_url($dto) . '/' . encrypt_url($i_supplier) . "' title='Edit Data'><i class='fa fa-pencil-square success darken-4 fa-lg mr-1'></i></a>";
                }
                if (check_role($this->id_menu, 5) && $f_status == 'f') {
                    $data      .= "<a href='#' onclick='openLink(\"" . $this->folder . "\",\"" . encrypt_url($id) . "\"); return false;' title='Print Data'><i class='fa fa-print fa-lg blue darken-4 mr-1'></i></a>";
                }
                if (check_role($this->id_menu, 4) && $f_status == 'f' && ($alok == 0)) {
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
        $datatables->hide('alok');
        return $datatables->generate();
    }

    /** Get Supplier */
    public function get_supplier0($cari)
    {
        return $this->db->query("SELECT distinct
                xx.i_supplier, i_supplier_id , e_supplier_name AS e_supplier_name
            FROM 
                tr_supplier xx
                inner join tm_kuk yy on (yy.i_supplier=xx.i_supplier)
            WHERE 
                (e_supplier_name ILIKE '%$cari%' OR i_supplier_id ILIKE '%$cari%')
                AND xx.i_company = '$this->i_company' 
                AND f_supplier_active = 'true' 
            ORDER BY 3 ASC
        ", FALSE);
    }

    /** Get Supplier */
    public function get_supplier($cari)
    {
        return $this->db->query("SELECT 
                i_supplier, i_supplier_id , e_supplier_name
            FROM 
                tr_supplier
            WHERE 
                (e_supplier_name ILIKE '%$cari%' OR i_supplier_id ILIKE '%$cari%')
                AND i_company = '$this->i_company' 
                AND f_supplier_active = 'true' 
            ORDER BY 3 ASC
        ", FALSE);
    }

    /** Get Bank */
    public function get_bank($cari)
    {
        return $this->db->query("SELECT 
                i_bank, i_bank_id, e_bank_name
            FROM 
                tr_bank
            WHERE 
                (i_bank_id ILIKE '%$cari%' OR e_bank_name ILIKE '%$cari%')
                AND i_company = '$this->i_company' 
                AND f_bank_active = 'true' 
            ORDER BY 3 ASC
        ", FALSE);
    }



    /** Running Number Dokumen*/
    public function running_number($thbl, $tahun, $bln)
    {
        $cek = $this->db->query("SELECT 
                substring(i_kuk_id, 1, 2) AS kode 
            FROM tm_kuk 
            WHERE i_company = '$this->i_company'
            ORDER BY i_kuk DESC");
        if ($cek->num_rows() > 0) {
            $kode = $cek->row()->kode;
        } else {
            $kode = 'TK';
        }
        $query  = $this->db->query("SELECT
                max(substring(i_kuk_id, 9, 6)) AS max
            FROM
                tm_kuk
            WHERE to_char (d_kuk, 'yyyy') >= '$tahun'
            and to_char (d_kuk, 'MM') >= '$bln'
            AND i_company = '$this->i_company'
            AND substring(i_kuk_id, 1, 2) = '$kode'
            AND substring(i_kuk_id, 4, 2) = substring('$thbl',1,2)
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
        $d_document = $this->input->post('d_document');
        return $this->db->query("SELECT 
                i_kuk_id
            FROM 
                tm_kuk 
            WHERE 
                upper(trim(i_kuk_id)) = upper(trim('$i_document'))
                AND d_kuk = '$d_document'
                AND i_company = '$this->i_company'
        ", FALSE);
    }

    /** Simpan Data */
    public function save()
    {
        $supp = ($this->input->post('i_supplier') == '') ? null :  $this->input->post('i_supplier');
        $table = array(
            'i_company'     => $this->i_company,
            'i_kuk_id'      => strtoupper($this->input->post('i_document')),
            'i_supplier'    => $supp,
            'd_kuk'         => $this->input->post('d_document'),
            'd_entry'       => current_datetime(),
            'e_remark'      => $this->input->post('e_remark'),
            'v_jumlah'      => str_replace(",", "", $this->input->post('v_jumlah')),
            'i_bank'        => $this->input->post('i_bank'),
        );
        $this->db->insert('tm_kuk', $table);
    }

    /** Get Data Untuk Edit */
    public function get_data($id)
    {
        return $this->db->query("SELECT 
                a.*,
                c.i_supplier_id,
                c.e_supplier_name,
                f.i_bank_id,
                f.e_bank_name
            FROM 
                tm_kuk a
            left JOIN tr_supplier c ON 
                (c.i_supplier = a.i_supplier)
            LEFT JOIN tr_bank f ON 
                (f.i_bank = a.i_bank)
            WHERE i_kuk = '$id'
        ", FALSE);
    }

    /** Cek Apakah Data Sudah Ada Pas Edit */
    public function cek_edit()
    {
        $i_document = str_replace("_", "", $this->input->post('i_document'));
        $i_document_old = str_replace("_", "", $this->input->post('i_document_old'));
        $d_document = $this->input->post('d_document');
        return $this->db->query("SELECT 
                i_kuk_id
            FROM 
                tm_kuk 
            WHERE 
                trim(upper(i_kuk_id)) <> trim(upper('$i_document_old'))
                AND trim(upper(i_kuk_id)) = trim(upper('$i_document'))
                AND d_kuk = '$d_document'
                AND i_company = '$this->i_company'
         ", FALSE);
    }

    /** Update Data */
    public function update()
    {
        $id = $this->input->post('id');
        $supp = ($this->input->post('i_supplier') == '') ? null :  $this->input->post('i_supplier');
        $table = array(
            'i_company'     => $this->i_company,
            'i_kuk_id'      => strtoupper($this->input->post('i_document')),
            'i_supplier'    => $supp,
            'd_kuk'         => $this->input->post('d_document'),
            'd_update'      => current_datetime(),
            'e_remark'      => $this->input->post('e_remark'),
            'v_jumlah'      => str_replace(",", "", $this->input->post('v_jumlah')),
            'i_bank'        => $this->input->post('i_bank'),
        );
        $this->db->where('i_kuk', $id);
        $this->db->update('tm_kuk', $table);
    }

    /** Cancel Data */
    public function cancel($id,$alasan,$i_kuk_id)
    {
        $table = array(
            'f_kuk_cancel' => 't',
        );
        $this->db->where('i_kuk', $id);
        $this->db->update('tm_kuk', $table);

        $this->db->query("INSERT INTO tx_del (i_company, i_user, e_user_name, e_dokumen, e_alasan)
        VALUES ('$this->i_company', '$this->i_user', '$this->e_user_name', 'No KU Keluar : $i_kuk_id', '$alasan')");
    }
}

/* End of file Mmaster.php */
