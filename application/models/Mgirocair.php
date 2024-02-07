<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mgirocair extends CI_Model
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
                AND (a.d_giro_cair NOTNULL OR a.d_giro_tolak NOTNULL)
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
            $i_periode  = $data['i_periode'];
            $f_referensi  = $data['f_referensi'];
            $data       = '';
            $data      .= "<a href='" . base_url() . $this->folder . '/view/' . encrypt_url($id) . '/' . encrypt_url($dfrom) . '/' . encrypt_url($dto) . '/' . encrypt_url($i_area) . "' title='View Data'><i class='fa fa-eye fa-lg warning darken-4 mr-1'></i></a>";
            if ($i_periode >= get_periode()) {
                if (check_role($this->id_menu, 3) && $f_status == 'f' && ($f_referensi == null || $f_referensi == '')) {
                    $data      .= "<a href='" . base_url() . $this->folder . '/edit/' . encrypt_url($id) . '/' .  encrypt_url($dfrom) . '/' . encrypt_url($dto) . '/' . encrypt_url($i_area) . "' title='Edit Data'><i class='fa fa-pencil-square success darken-4 fa-lg mr-1'></i></a>";
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
                to_char(a.d_giro, 'YYYYMM') as i_periode,   
                a.i_giro_id, 
                initcap(b.e_area_name) AS e_area_name,
                a.v_jumlah::money AS v_jumlah,
                initcap('[' || c.i_customer_id || '] ' || c.e_customer_name) AS e_customer,
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
            WHERE
                a.i_company = '$this->i_company'
                AND a.d_giro BETWEEN '$dfrom' AND '$dto'
                AND a.f_giro_batal = 'f'
	            and a.d_giro_cair isnull
	            and a.d_giro_tolak isnull
                AND (a.d_giro_cair ISNULL OR a.d_giro_tolak ISNULL)
                $area
            ORDER BY
                1 DESC 
        ", FALSE);

        /** Cek Hak Akses, Apakah User Bisa Edit */
        $datatables->add('action', function ($data) {
            $id         = trim($data['id']);
            $dfrom      = $data['dfrom'];
            $dto        = $data['dto'];
            $i_area     = $data['i_area'];
            $i_periode  = $data['i_periode'];
            $data       = '';
            // if ($i_periode >= get_periode()) {
                // if (check_role($this->id_menu, 1)) {
                    $data      .= "<a href='" . base_url() . $this->folder . '/add/' . encrypt_url($id) . '/' . encrypt_url($dfrom) . '/' . encrypt_url($dto) . '/' . encrypt_url($i_area) .  "' title='Add Data'><i class='fa fa-check-circle-o success darken-4 fa-lg mr-1'></i></a>";
                // }
            // }
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

    /** Simpan Data */
    public function save()
    {
        $id             = $this->input->post('id');
        $d_giro_terima  = $this->input->post('d_giro_terima');
        $d_giro_duedate = $this->input->post('d_giro_duedate');
        $f_giro_tolak   = $this->input->post('f_giro_tolak');
        $d_giro_cair    = $this->input->post('d_giro_cair');
        $d_giro_cair    = ($d_giro_cair == '' || $d_giro_cair == null) ? null : $d_giro_cair;
        $f_cair         = ($d_giro_cair == '' || $d_giro_cair == null) ? FALSE : TRUE;
        $d_giro_tolak   = ($f_giro_tolak == 'on') ? $this->input->post('d_giro_tolak') : null;
        $f_tolak        = ($f_giro_tolak == 'on') ? TRUE : FALSE;
        $table = array(
            'd_giro_terima'     => $d_giro_terima,
            'd_giro_duedate'    => $d_giro_duedate,
            'd_giro_cair'       => $d_giro_cair,
            'd_giro_tolak'      => $d_giro_tolak,
            'f_giro_tolak'      => $f_tolak,
            'f_giro_cair'       => $f_cair,
            'd_entry_cair'      => current_datetime(),
        );
        $this->db->where('i_giro', $id);
        $this->db->update('tm_giro', $table);
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

    /** Update Data */
    public function update()
    {
        $id             = $this->input->post('id');
        $d_giro_terima  = $this->input->post('d_giro_terima');
        $d_giro_duedate = $this->input->post('d_giro_duedate');
        $f_giro_tolak   = $this->input->post('f_giro_tolak');
        $d_giro_cair    = $this->input->post('d_giro_cair');
        $d_giro_cair    = ($d_giro_cair == '' || $d_giro_cair == null) ? null : $d_giro_cair;
        $f_cair         = ($d_giro_cair == '' || $d_giro_cair == null) ? FALSE : TRUE;
        $d_giro_tolak   = ($f_giro_tolak == 'on') ? $this->input->post('d_giro_tolak') : null;
        $f_tolak        = ($f_giro_tolak == 'on') ? TRUE : FALSE;
        $table = array(
            'd_giro_terima'     => $d_giro_terima,
            'd_giro_duedate'    => $d_giro_duedate,
            'd_giro_cair'       => $d_giro_cair,
            'd_giro_tolak'      => $d_giro_tolak,
            'f_giro_tolak'      => $f_tolak,
            'f_giro_cair'       => $f_cair,
            'd_entry_cair'      => current_datetime(),
        );
        $this->db->where('i_giro', $id);
        $this->db->update('tm_giro', $table);
    }

    /** Cancel Data */
    public function cancel($id,$alasan,$i_giro_id)
    {
        $table = array(
            'd_giro_cair'  => null,
            'd_giro_tolak' => null,
            'f_giro_tolak' => false,
            'f_giro_cair'  => false,
            'd_entry_cair' => null,
        );
        $this->db->where('i_giro', $id);
        $this->db->update('tm_giro', $table);

        $this->db->query("INSERT INTO tx_del (i_company, i_user, e_user_name, e_dokumen, e_alasan)
        VALUES ('$this->i_company', '$this->i_user', '$this->e_user_name', 'No GIRO Cair/Tolak : $i_giro_id', '$alasan')");
    }
}

/* End of file Mmaster.php */
