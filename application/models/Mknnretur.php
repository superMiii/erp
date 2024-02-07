<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mknnretur extends CI_Model
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

        $i_area = $this->input->post('i_area', TRUE);
        if ($i_area == '') {
            $i_area = $this->uri->segment(5);
        }

        if ($i_area != '0') {
            $area = "AND a.i_area = '$i_area' ";
        } else {
            $area = "";
        }

        $dfrom  = date('Y-m-d', strtotime($dfrom));
        $dto    = date('Y-m-d', strtotime($dto));

        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("SELECT
                a.d_kn as tgl,
                a.i_kn AS id,
                a.d_kn,
                a.i_kn_id,
                to_char(a.d_kn, 'YYYYMM') as i_periode,
                d.e_area_name,
                e.i_customer_id || ' ~ ' || e.e_customer_name AS customer,
                f.e_salesman_name,
                a.v_netto::money AS v_netto,
                a.v_sisa::money AS v_sisa,    
	            mm.i_alokasi_id as f_referensi,
                a.f_kn_cancel AS f_status,        
                '$dfrom' AS dfrom,
                '$dto' AS dto,
                '$i_area' AS i_area
            FROM
                tm_kn a
            INNER JOIN tr_area d ON
                (d.i_area = a.i_area)
            INNER JOIN tr_customer e ON
                (e.i_customer = a.i_customer)
            INNER JOIN tr_salesman f ON
                (f.i_salesman = a.i_salesman)
            INNER JOIN tm_user_area g ON
                (g.i_area = a.i_area)
            left join tm_alokasikn mm on (mm.i_kn = a.i_kn)
            WHERE
                a.i_company = '$this->i_company'
                AND a.d_kn BETWEEN '$dfrom' AND '$dto'
                AND g.i_user = '$this->i_user'
                AND a.f_kn_retur = 'f'
                $area
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
            $i_area     = $data['i_area'];
            $v_netto    = $data['v_netto'];
            $v_sisa     = $data['v_sisa'];
            $i_periode  = $data['i_periode'];
            $data       = '';
            $data      .= "<a href='" . base_url() . $this->folder . '/view/' . encrypt_url($id) . '/' . encrypt_url($dfrom) . '/' . encrypt_url($dto) . '/' . encrypt_url($i_area) . "' title='View Data'><i class='fa fa-eye fa-lg warning darken-4 mr-1'></i></a>";
            if ($i_periode >= get_periode()) {
                if (check_role($this->id_menu, 3) && $f_status == 'f'  && ($v_netto == $v_sisa)) {
                    $data      .= "<a href='" . base_url() . $this->folder . '/edit/' . encrypt_url($id)  . '/' .  encrypt_url($dfrom) . '/' . encrypt_url($dto) . '/' . encrypt_url($i_area) . "' title='Edit Data'><i class='fa fa-pencil-square success darken-4 fa-lg mr-1'></i></a>";
                }
                if (check_role($this->id_menu, 5) && $f_status == 'f') {
                    $data      .= "<a href='#' onclick='openLink(\"" . $this->folder . "\",\"" . encrypt_url($id) . "\"); return false;' title='Print Data'><i class='fa fa-print fa-lg blue darken-4 mr-1'></i></a>";
                }
                if (check_role($this->id_menu, 4) && $f_status == 'f'  && ($v_netto == $v_sisa)) {
                    $data      .= "<a href='#' onclick='sweetdeletev33raya3(\"" . $this->folder . "\",\"" . $id . "\",\"" . encrypt_url($dfrom)  . "\",\"" . encrypt_url($dto)  . '/' . encrypt_url($i_area) . "\");' title='Delete Data'><i class='fa fa-trash fa-lg red darken-4'></i></a>";
                }
            }
            return $data;
        });
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('i_area');
        $datatables->hide('i_periode');
        $datatables->hide('id');
        return $datatables->generate();
    }

    /** Get Area */
    public function get_area($cari)
    {
        return $this->db->query("SELECT 
                DISTINCT
                a.i_area, 
                i_area_id, 
                initcap(e_area_name) AS e_area_name
            FROM 
                tr_area a
            INNER JOIN tm_user_area b 
                ON (b.i_area = a.i_area) 
            WHERE 
                (e_area_name ILIKE '%$cari%' OR i_area_id ILIKE '%$cari%')
                AND a.i_company = '$this->i_company' 
                AND f_area_active = true
                AND b.i_user = '$this->i_user' 
            ORDER BY 3 ASC
        ", FALSE);
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

        $dfrom  = date('Y-m-d', strtotime($dfrom));
        $dto    = date('Y-m-d', strtotime($dto));

        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("SELECT
                d_bbm as tgl,
                i_bbm AS id,
                to_char(d_bbm, 'YYYYMM') as i_periode,
                d_bbm,
                i_bbm_id,
                i_ttb_id,
                a.d_ttb, 
                c.e_area_name,
                e.i_customer_id || ' ~ ' || e.e_customer_name AS customer,
                a.f_bbm_cancel AS f_status
            FROM
                tm_bbm a
            INNER JOIN tm_ttbretur b ON
                (b.i_ttb = a.i_ttb)
            INNER JOIN tr_area c ON
                (c.i_area = a.i_area)
            INNER JOIN tm_user_area d ON
                (d.i_area = a.i_area)
            INNER JOIN tr_customer e ON
                (e.i_customer = a.i_customer)
            WHERE
                a.i_company = '$this->i_company'
                AND d.i_user = '$this->i_user'
                AND a.d_bbm BETWEEN '$dfrom' AND '$dto'
                AND a.f_bbm_cancel = 'f' 
                AND a.f_kn = 'f'
            ORDER BY
                a.i_bbm DESC
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
            $data       = '';
            if ($i_periode >= get_periode()) {
                if (check_role($this->id_menu, 3)) {
                    $data      .= "<a href='" . base_url() . $this->folder . '/add/' . encrypt_url($id) . "' title='Tambah Data'><i class='fa fa-check-circle-o success darken-4 fa-lg mr-1'></i></a>";
                }
            }
            return $data;
        });
        $datatables->hide('id');
        $datatables->hide('i_periode');
        return $datatables->generate();
    }

    /** Get Data Untuk Tambah */
    public function get_data_referensi($id)
    {
        return $this->db->query("SELECT
                e.i_bbm,
                e.i_bbm_id,
                a.i_ttb,
                a.i_ttb_id,
                a.d_ttb,
                to_char(a.d_ttb, 'DD FMMonth YYYY') AS dttb,
                to_char(e.d_bbm, 'DD FMMonth YYYY') AS date_now,
                e.d_bbm,
                a.i_area,
                b.i_area_id,
                b.e_area_name,
                a.i_salesman,
                c.e_salesman_name,
                a.i_customer,
                d.i_customer_id,
                d.e_customer_name,
                e.e_remark
            FROM
                tm_ttbretur a
            INNER JOIN tm_bbm e ON 
                (e.i_ttb = a.i_ttb)
            INNER JOIN tr_area b ON
                (b.i_area = e.i_area)
            INNER JOIN tr_salesman c ON
                (c.i_salesman = e.i_salesman)
            INNER JOIN tr_customer d ON
                (d.i_customer = e.i_customer)
            WHERE
                e.i_bbm = '$id'
        ", FALSE);
    }

    /** Get Detail Untuk Tambah */
    public function get_data_detail_referensi($id)
    {
        return $this->db->query("SELECT
                d.i_ttb_item,
                c.i_product_id,
                c.e_product_name,
                e.e_product_motifname,
                a.*,
                d.i_product1,
                d.i_product1_grade,
                d.i_product1_motif,
                f.i_product_id AS i_product_id1,
                f.e_product_name AS e_product_name1,
                g.e_product_motifname AS e_product_motifname1,
                d.n_quantity AS n_quantity1
            FROM
                tm_bbm_item a
            INNER JOIN tm_bbm b ON
                (b.i_bbm = a.i_bbm)
            INNER JOIN tr_product c ON
                (c.i_product = a.i_product)
            INNER JOIN tm_ttbretur_item d ON
                (d.i_ttb = b.i_ttb
                    AND a.i_product = d.i_product2)
            INNER JOIN tr_product_motif e ON
                (e.i_product_motif = a.i_product_motif)
            INNER JOIN tr_product f ON
                (f.i_product = d.i_product1)
            INNER JOIN tr_product_motif g ON
                (g.i_product_motif = d.i_product1_motif)
            WHERE
                a.i_bbm = '$id'
            ORDER BY
                a.n_item_no ASC
        ", FALSE);
    }

    /** Running Number Dokumen*/
    public function running_number($thbl, $tahun)
    {
        $cek = $this->db->query("SELECT 
                substring(i_kn_id, 1, 2) AS kode 
            FROM tm_kn 
            WHERE i_company = '$this->i_company'
            AND f_kn_retur = 'f'
            ORDER BY i_kn_id DESC");
        if ($cek->num_rows() > 0) {
            $kode = $cek->row()->kode;
        } else {
            $kode = 'KN';
        }
        $query  = $this->db->query("SELECT
                max(substring(i_kn_id, 9, 6)) AS max
            FROM
                tm_kn
            WHERE to_char (d_kn, 'yyyy') >= '$tahun'
            AND i_company = '$this->i_company'
            AND f_kn_retur = 'f'
            AND substring(i_kn_id, 1, 2) = '$kode'
            AND substring(i_kn_id, 4, 2) = substring('$thbl',1,2)
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

    /** Get Customer */
    public function get_customer($cari, $i_area)
    {
        return $this->db->query("SELECT 
                a.i_customer, i_customer_id , e_customer_name
            FROM 
                tr_customer a
            INNER JOIN (SELECT DISTINCT i_customer FROM tm_nota WHERE f_nota_cancel = 'f' AND i_company = '$this->i_company') c 
                ON (c.i_customer = a.i_customer)
            WHERE 
                (e_customer_name ILIKE '%$cari%' OR i_customer_id ILIKE '%$cari%')
                AND i_company = '$this->i_company' 
                AND f_customer_active = 'true' 
                AND i_area = '$i_area'
            ORDER BY 3 ASC
        ", FALSE);
    }

    /** Get Detail Customer */
    public function get_customer_detail($icustomer)
    {
        return $this->db->query("SELECT
                a.e_customer_address,
                a.i_price_group,
                a.f_customer_plusppn,
                a.e_customer_npwpcode,
                a.f_customer_pkp,
                b.e_price_groupname,
                CASE
                    WHEN a.f_customer_plusppn = TRUE THEN 'Ya'
                    ELSE 'Tidak'
                END AS eppn,
                CASE
                    WHEN a.f_customer_pkp = TRUE THEN 'Ya'
                    ELSE 'Tidak'
                END AS pkp
            FROM
                tr_customer a
            INNER JOIN tr_price_group b ON
                (a.i_price_group = b.i_price_group)
            WHERE
                i_customer = $icustomer
        ", FALSE);
    }

    /** Get Salesman */
    public function get_salesman($cari, $i_customer)
    {
        return $this->db->query("SELECT
                a.i_salesman,
                a.i_salesman_id ,
                a.e_salesman_name
            FROM
                tr_salesman a
            INNER JOIN tr_salesman_areacover b ON
                (a.i_salesman = b.i_salesman)
            WHERE
                (a.e_salesman_name ILIKE '%$cari%'
                    OR a.i_salesman_id ILIKE '%$cari%')
                AND a.i_company = '$this->i_company'
                AND f_salesman_active = TRUE
                AND b.i_area_cover IN (
                SELECT
                    i_area_cover
                FROM
                    tr_customer
                WHERE
                    i_customer = '$i_customer' )
            GROUP BY 1
            ORDER BY 3 ASC
        ", FALSE);
    }

    /** Cek Apakah Data Sudah Ada */
    public function cek_code()
    {
        $i_document = str_replace("_", "", $this->input->post('i_document'));
        return $this->db->query("SELECT 
                i_kn_id
            FROM 
                tm_kn 
            WHERE 
                upper(trim(i_kn_id)) = upper(trim('$i_document'))
                AND i_company = '$this->i_company'
                AND f_kn_retur = 'f'
        ", FALSE);
    }

    /** Simpan Data */
    public function save()
    {
        $ym = date('ym', strtotime($this->input->post('d_document')));
        $Y  = date('Y', strtotime($this->input->post('d_document')));
        $f_masalah  = ($this->input->post('f_masalah') == 'on') ? TRUE : FALSE;
        $f_insentif = ($this->input->post('f_insentif') == 'on') ? TRUE : FALSE;
        $i_kn_id = $this->running_number($ym, $Y);
        $header = array(
            'i_company'             => $this->i_company,
            'i_area'                => $this->input->post('i_area'),
            'i_kn_id'               => $i_kn_id,
            'i_customer'            => $this->input->post('i_customer'),
            'i_refference'          => $this->input->post('i_refference'),
            'i_customer_paygroup'   => $this->input->post('i_customer_paygroup'),
            'i_salesman'            => $this->input->post('i_salesman'),
            'f_kn_retur'            => FALSE,
            'i_pajak'               => $this->input->post('i_pajak'),
            'd_kn'                  => $this->input->post('d_document'),
            'd_refference'          => $this->input->post('d_refference'),
            'd_pajak'               => $this->input->post('d_pajak'),
            'd_entry'               => current_datetime(),
            'e_remark'              => $this->input->post('e_remark'),
            'f_masalah'             => $f_masalah,
            'f_insentif'            => $f_insentif,
            'v_netto'               => str_replace(",", "", $this->input->post('v_netto')),
            'v_gross'               => str_replace(",", "", $this->input->post('v_gross')),
            'v_discount'            => str_replace(",", "", $this->input->post('v_discount')),
            'v_sisa'                => str_replace(",", "", $this->input->post('v_sisa')),
        );
        $this->db->insert('tm_kn', $header);
    }

    /** Get Data Untuk Edit */
    public function get_data($id)
    {
        return $this->db->query("SELECT
                a.*,
                c.i_area_id,
                c.e_area_name,
                d.i_customer_id,
                d.e_customer_name,
                d.e_customer_address,
                d.e_customer_phone,
                d.i_price_group,
                d.f_customer_plusppn,
                d.n_customer_top,
                d.d_customer_register,
                e.i_salesman_id,
                e.e_salesman_name
            FROM
                tm_kn a
            INNER JOIN tr_area c ON
                (c.i_area = a.i_area)
            INNER JOIN tr_customer d ON
                (d.i_customer = a.i_customer)
            INNER JOIN tr_salesman e ON
                (e.i_salesman = a.i_salesman)
            WHERE
                a.i_kn = '$id'
        ", FALSE);
    }

    /** Cek Apakah Data Sudah Ada Pas Edit */
    public function cek_edit()
    {
        $i_document = str_replace("_", "", $this->input->post('i_document'));
        $i_document_old = str_replace("_", "", $this->input->post('i_document_old'));
        return $this->db->query("SELECT 
                i_kn_id
            FROM 
                tm_kn 
            WHERE 
                trim(upper(i_kn_id)) <> trim(upper('$i_document_old'))
                AND trim(upper(i_kn_id)) = trim(upper('$i_document'))
                AND i_company = '$this->i_company'
                AND f_kn_retur = 'f'
         ", FALSE);
    }

    /** Update Data */
    public function update()
    {
        $id = $this->input->post('id');
        $f_masalah  = ($this->input->post('f_masalah') == 'on') ? TRUE : FALSE;
        $f_insentif = ($this->input->post('f_insentif') == 'on') ? TRUE : FALSE;
        $header = array(
            'i_company'             => $this->i_company,
            'i_area'                => $this->input->post('i_area'),
            'i_kn_id'               => $this->input->post('i_document'),
            'i_customer'            => $this->input->post('i_customer'),
            'i_refference'          => $this->input->post('i_refference'),
            'i_customer_paygroup'   => $this->input->post('i_customer_paygroup'),
            'i_salesman'            => $this->input->post('i_salesman'),
            'f_kn_retur'            => FALSE,
            'i_pajak'               => $this->input->post('i_pajak'),
            'd_kn'                  => $this->input->post('d_document'),
            'd_refference'          => $this->input->post('d_refference'),
            'd_pajak'               => $this->input->post('d_pajak'),
            'd_update'              => current_datetime(),
            'e_remark'              => $this->input->post('e_remark'),
            'f_masalah'             => $f_masalah,
            'f_insentif'            => $f_insentif,
            'v_netto'               => str_replace(",", "", $this->input->post('v_netto')),
            'v_gross'               => str_replace(",", "", $this->input->post('v_gross')),
            'v_discount'            => str_replace(",", "", $this->input->post('v_discount')),
            'v_sisa'                => str_replace(",", "", $this->input->post('v_sisa')),
        );
        $this->db->where('i_kn', $id);
        $this->db->update('tm_kn', $header);
    }

    /** Cancel Data */
    public function cancel($id,$alasan,$i_kn_id)
    {
        $table = array(
            'f_kn_cancel' => 't',
        );
        $this->db->where('i_kn', $id);
        $this->db->update('tm_kn', $table);

        $this->db->query("INSERT INTO tx_del (i_company, i_user, e_user_name, e_dokumen, e_alasan)
        VALUES ('$this->i_company', '$this->i_user', '$this->e_user_name', 'No KN NONRETUR : $i_kn_id', '$alasan')");
    }
}

/* End of file Mmaster.php */
