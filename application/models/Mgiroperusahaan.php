<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mgiroperusahaan extends CI_Model
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
                a.d_giro as tgl,
                a.i_giro AS id,
                a.d_giro,
                a.i_giro_id,
                to_char(a.d_giro, 'YYYYMM') as i_periode,
                c.e_supplier_name AS e_supplier,
                a.v_jumlah::money AS v_jumlah,                
	            w.i_pv_id as f_referensi,
                a.f_giro_batal AS f_status,
                '$dfrom' AS dfrom,
                '$dto' AS dto,
                '$i_supplier' AS i_supplier
            FROM
                tm_giro_company a
            INNER JOIN tr_supplier c ON
                (c.i_supplier = a.i_supplier)
            left join (SELECT
                        y.i_company,
                        y.e_pv_refference_type_name,
                        z.i_pv,
                        z.i_pv_id,
                        i_pv_refference
                    from
                        tm_pv_item t
                        inner join tr_pv_refference_type y on (y.i_pv_refference_type = t.i_pv_refference_type)
                        inner join tm_pv z on (z.i_pv=t.i_pv)
                        WHERE z.i_company = '$this->i_company' AND y.f_giro = 't') w on 
            (w.i_pv_refference=a.i_giro)  
            WHERE
                a.i_company = '$this->i_company'
                AND a.d_giro BETWEEN '$dfrom' AND '$dto'
                $supplier
            ORDER BY
                1 DESC 
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
            $i_supplier = $data['i_supplier'];
            $i_periode  = $data['i_periode'];
            $f_referensi  = $data['f_referensi'];
            $data       = '';
            $data      .= "<a href='" . base_url() . $this->folder . '/view/' . encrypt_url($id) . '/' . encrypt_url($dfrom) . '/' . encrypt_url($dto) . '/' . encrypt_url($i_supplier) . "' title='View Data'><i class='fa fa-eye fa-lg warning darken-4 mr-1'></i></a>";
            if ($i_periode >= get_periode()) {
                if (check_role($this->id_menu, 3) && $f_status == 'f' && ($f_referensi == null || $f_referensi == '')) {
                    $data      .= "<a href='" . base_url() . $this->folder . '/edit/' . encrypt_url($id) . '/' .  encrypt_url($dfrom) . '/' . encrypt_url($dto) . '/' . encrypt_url($i_supplier) .  "' title='Edit Data'><i class='fa fa-pencil-square success darken-4 fa-lg mr-1'></i></a>";
                }
                if (check_role($this->id_menu, 5) && $f_status == 'f') {
                    $data      .= "<a href='#' onclick='openLink(\"" . $this->folder . "\",\"" . encrypt_url($id) . "\"); return false;' title='Print Data'><i class='fa fa-print fa-lg blue darken-4 mr-1'></i></a>";
                }
                if (check_role($this->id_menu, 4) && $f_status == 'f') {
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

    /** Get Supplier */
    public function get_supplier0($cari)
    {
        return $this->db->query("SELECT distinct
                xx.i_supplier, i_supplier_id , e_supplier_name AS e_supplier_name
            FROM 
                tr_supplier xx
                inner join tm_giro_company yy on (yy.i_supplier=xx.i_supplier)
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

    /** Cek Apakah Data Sudah Ada */
    public function cek_code()
    {
        $i_document = str_replace("_", "", $this->input->post('i_document'));
        return $this->db->query("SELECT 
                i_giro_id
            FROM 
                tm_giro_company 
            WHERE 
                upper(trim(i_giro_id)) = upper(trim('$i_document'))
                AND i_company = '$this->i_company'
        ", FALSE);
    }

    /** Simpan Data */
    public function save()
    {
        $table = array(
            'i_company'          => $this->i_company,
            'i_giro_id'          => strtoupper($this->input->post('i_document')),
            'i_supplier'         => $this->input->post('i_supplier'),
            'd_giro'             => $this->input->post('d_document'),
            'd_giro_duedate'     => $this->input->post('d_giro_duedate'),
            'd_giro_kirim'       => $this->input->post('d_giro_terima'),
            'd_entry'            => current_datetime(),
            'e_giro_description' => $this->input->post('e_giro_description'),
            'e_giro_bank'        => $this->input->post('e_giro_bank'),
            'v_jumlah'           => str_replace(",", "", $this->input->post('v_jumlah')),
        );
        $this->db->insert('tm_giro_company', $table);
    }

    /** Get Data Untuk Edit */
    public function get_data($id)
    {
        return $this->db->query("SELECT 
                a.*,
                c.i_supplier_id,
                c.e_supplier_name
            FROM 
                tm_giro_company a
            INNER JOIN tr_supplier c ON 
                (c.i_supplier = a.i_supplier)
            WHERE i_giro = '$id'
        ", FALSE);
    }

    /** Cek Apakah Data Sudah Ada Pas Edit */
    public function cek_edit()
    {
        $i_document = str_replace("_", "", $this->input->post('i_document'));
        $i_document_old = str_replace("_", "", $this->input->post('i_document_old'));
        return $this->db->query("SELECT 
                 i_giro_id
             FROM 
                 tm_giro_company 
             WHERE 
                 trim(upper(i_giro_id)) <> trim(upper('$i_document_old'))
                 AND trim(upper(i_giro_id)) = trim(upper('$i_document'))
                 AND i_company = '$this->i_company'
         ", FALSE);
    }

    /** Update Data */
    public function update()
    {
        $id = $this->input->post('id');
        $table = array(
            'i_company'          => $this->i_company,
            'i_giro_id'          => strtoupper($this->input->post('i_document')),
            'i_supplier'         => $this->input->post('i_supplier'),
            'd_giro'             => $this->input->post('d_document'),
            'd_giro_duedate'     => $this->input->post('d_giro_duedate'),
            'd_giro_kirim'       => $this->input->post('d_giro_terima'),
            'd_update'           => current_datetime(),
            'e_giro_description' => $this->input->post('e_giro_description'),
            'e_giro_bank'        => $this->input->post('e_giro_bank'),
            'v_jumlah'           => str_replace(",", "", $this->input->post('v_jumlah')),
        );
        $this->db->where('i_giro', $id);
        $this->db->update('tm_giro_company', $table);
    }

    /** Cancel Data */
    public function cancel($id,$alasan,$i_giro_id)
    {
        $table = array(
            'f_giro_batal' => 't',
        );
        $this->db->where('i_giro', $id);
        $this->db->update('tm_giro_company', $table);

        $this->db->query("INSERT INTO tx_del (i_company, i_user, e_user_name, e_dokumen, e_alasan)
        VALUES ('$this->i_company', '$this->i_user', '$this->e_user_name', 'No GIRO Perrusahaan : $i_giro_id', '$alasan')");
    }
}

/* End of file Mmaster.php */
