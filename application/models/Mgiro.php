<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mgiro extends CI_Model
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
                a.d_giro as tgl,
                a.i_giro AS id,
                a.d_giro,
                a.i_giro_id,   
                to_char(a.d_giro, 'YYYYMM') as i_periode,
                initcap(b.e_area_name) AS e_area_name,
                initcap(c.i_customer_id || ' ~ ' || c.e_customer_name) AS e_customer,   
                a.v_jumlah::money AS v_jumlah,     
	            w.i_rv_id as f_referensi,
	            case when a.d_giro_cair notnull then 'CAIR' when a.d_giro_tolak notnull then 'TOLAK' else '' end as raya,
                a.f_giro_batal AS f_status,
                a.d_giro_cair as cair,
                a.d_giro_tolak tol,
                '$dfrom' AS dfrom,
                '$dto' AS dto,
                '$i_area' AS i_area
            FROM
                tm_giro a
            INNER JOIN tr_area b ON
                (b.i_area = a.i_area)
            INNER JOIN tr_customer c ON
                (c.i_customer = a.i_customer)
            INNER JOIN tm_user_area d ON
                (d.i_area = a.i_area
                    AND d.i_user = '$this->i_user')            
            left join (SELECT
                        y.i_company,
                        y.e_rv_refference_type_name,
                        z.i_rv,
                        z.i_rv_id,
                        i_rv_refference
                    from
                        tm_rv_item t
                        inner join tr_rv_refference_type y on (y.i_rv_refference_type = t.i_rv_refference_type)
                        inner join tm_rv z on (z.i_rv=t.i_rv)
                        WHERE z.i_company = '$this->i_company' AND y.f_giro = 't') w on 
            (w.i_rv_refference=a.i_giro)
            WHERE
                a.i_company = '$this->i_company'
                AND a.d_giro BETWEEN '$dfrom' AND '$dto'
                $area
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
            $i_area     = $data['i_area'];
            $cair       = $data['cair'];
            $tol        = $data['tol'];
            $i_periode  = $data['i_periode'];
            $f_referensi  = $data['f_referensi'];
            $data       = '';
            $data      .= "<a href='" . base_url() . $this->folder . '/view/' . encrypt_url($id) . '/' . encrypt_url($dfrom) . '/' . encrypt_url($dto) . '/' . encrypt_url($i_area) . "' title='View Data'><i class='fa fa-eye fa-lg warning darken-4 mr-1'></i></a>";
            if ($i_periode >= get_periode()) {
                if (check_role($this->id_menu, 3) && $f_status == 'f' && ($f_referensi == null || $f_referensi == '') && ($cair == null || $cair == '') && ($tol == null || $tol == '')) {
                    $data      .= "<a href='" . base_url() . $this->folder . '/edit/' . encrypt_url($id) . '/' .  encrypt_url($dfrom) . '/' . encrypt_url($dto) . '/' . encrypt_url($i_area) .  "' title='Edit Data'><i class='fa fa-pencil-square success darken-4 fa-lg mr-1'></i></a>";
                }
                if (check_role($this->id_menu, 5) && $f_status == 'f') {
                    $data      .= "<a href='#' onclick='openLink(\"" . $this->folder . "\",\"" . encrypt_url($id) . "\"); return false;' title='Print Data'><i class='fa fa-print fa-lg blue darken-4 mr-1'></i></a>";
                }
                if (check_role($this->id_menu, 4) && $f_status == 'f') {
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
        $datatables->hide('cair');
        $datatables->hide('tol');
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

    /** Get Customer */
    public function get_customer($cari, $i_area)
    {
        return $this->db->query("
            SELECT 
                i_customer, i_customer_id , e_customer_name
            FROM 
                tr_customer
            WHERE 
                (e_customer_name ILIKE '%$cari%' OR i_customer_id ILIKE '%$cari%')
                AND i_company = '$this->i_company' 
                AND f_customer_active = 'true' 
                AND i_area = '$i_area'
            ORDER BY 3 ASC
        ", FALSE);
    }

    /** Get DT */
    public function get_dt($cari, $i_area, $i_customer)
    {
        return $this->db->query("SELECT distinct
                a.i_dt, i_dt_id , to_char(d_dt, 'DD Month YYYY') AS d_dt
            FROM 
                tm_dt a 
                inner join tm_dt_item b on (b.i_dt=a.i_dt)
                inner join tm_nota c on (c.i_nota=b.i_nota)
            WHERE 
                (i_dt_id ILIKE '%$cari%')
                AND a.i_company = '$this->i_company' 
                AND f_dt_cancel = 'false' 
                AND a.i_area = '$i_area'
                and c.i_customer = '$i_customer'
            ORDER BY 1 deSC
        ", FALSE);
    }

    /** Cek Apakah Data Sudah Ada */
    public function cek_code()
    {
        $i_document = str_replace("_", "", $this->input->post('i_document'));
        $i_area = $this->input->post('i_area');
        return $this->db->query("
            SELECT 
                i_giro_id
            FROM 
                tm_giro 
            WHERE 
                upper(trim(i_giro_id)) = upper(trim('$i_document'))
                AND i_area = '$i_area'
                AND i_company = '$this->i_company'
        ", FALSE);
    }

    /** Simpan Data */
    public function save()
    {
        $table = array(
            'i_company'          => $this->i_company,
            'i_giro_id'          => strtoupper($this->input->post('i_document')),
            'i_area'             => $this->input->post('i_area'),
            'i_customer'         => $this->input->post('i_customer'),
            'd_giro'             => $this->input->post('d_document'),
            'd_giro_duedate'     => $this->input->post('d_giro_duedate'),
            'd_giro_terima'      => $this->input->post('d_giro_terima'),
            'd_entry'            => current_datetime(),
            'e_giro_description' => $this->input->post('e_giro_description'),
            'e_giro_bank'        => $this->input->post('e_giro_bank'),
            'v_jumlah'           => str_replace(",", "", $this->input->post('v_jumlah')),
            'i_dt'               => $this->input->post('i_dt'),
        );
        $this->db->insert('tm_giro', $table);
    }

    /** Get Data Untuk Edit */
    public function get_data($id)
    {
        return $this->db->query("SELECT 
                a.*,
                b.i_area_id,
                b.e_area_name,
                c.i_customer_id,
                c.e_customer_name,
                d.i_dt_id,
                to_char(d.d_dt, 'DD')||' '||trim(to_char(d.d_dt, 'Month'))||' '||to_char(d.d_dt, 'YYYY') AS d_dt
            FROM 
                tm_giro a
            INNER JOIN tr_area b ON 
                (b.i_area = a.i_area)
            INNER JOIN tr_customer c ON 
                (c.i_customer = a.i_customer)
            LEFT JOIN tm_dt d ON 
                (d.i_dt = a.i_dt)
            WHERE i_giro = '$id'
        ", FALSE);
    }

    /** Cek Apakah Data Sudah Ada Pas Edit */
    public function cek_edit()
    {
        $i_document = str_replace("_", "", $this->input->post('i_document'));
        $i_document_old = str_replace("_", "", $this->input->post('i_document_old'));
        $i_area = $this->input->post('i_area');
        return $this->db->query("
             SELECT 
                 i_giro_id
             FROM 
                 tm_giro 
             WHERE 
                 trim(upper(i_giro_id)) <> trim(upper('$i_document_old'))
                 AND trim(upper(i_giro_id)) = trim(upper('$i_document'))
                 AND i_area = '$i_area'
                 AND i_company = '$this->i_company'
         ", FALSE);
    }

    /** Update Data */
    public function update()
    {
        $id = $this->input->post('id');
        $i_dt = ($this->input->post('i_dt') == '') ? null :  $this->input->post('i_dt');
        $table = array(
            'i_company'          => $this->i_company,
            'i_giro_id'          => strtoupper($this->input->post('i_document')),
            'i_area'             => $this->input->post('i_area'),
            'i_customer'         => $this->input->post('i_customer'),
            'd_giro'             => $this->input->post('d_document'),
            'd_giro_duedate'     => $this->input->post('d_giro_duedate'),
            'd_giro_terima'      => $this->input->post('d_giro_terima'),
            'd_update'           => current_datetime(),
            'e_giro_description' => $this->input->post('e_giro_description'),
            'e_giro_bank'        => $this->input->post('e_giro_bank'),
            'v_jumlah'           => str_replace(",", "", $this->input->post('v_jumlah')),
            'i_dt'               => $i_dt,
        );
        $this->db->where('i_giro', $id);
        $this->db->update('tm_giro', $table);
    }

    /** Cancel Data */
    public function cancel($id,$alasan,$i_giro_id)
    {
        $table = array(
            'f_giro_batal' => 't',
        );
        $this->db->where('i_giro', $id);
        $this->db->update('tm_giro', $table);

        $this->db->query("INSERT INTO tx_del (i_company, i_user, e_user_name, e_dokumen, e_alasan)
        VALUES ('$this->i_company', '$this->i_user', '$this->e_user_name', 'No GIRO Pelanggan : $i_giro_id', '$alasan')");
    }
}

/* End of file Mmaster.php */
