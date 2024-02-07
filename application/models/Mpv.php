<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mpv extends CI_Model
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

        $i_type = $this->input->post('i_type', TRUE);
        if ($i_type == '') {
            $i_type = $this->uri->segment(5);
        }

        if ($i_type != '0' && $i_type != '') {
            $type = "AND a.i_pv_type = '$i_type' ";
        } else {
            $type = "";
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

        $i_coa = $this->input->post('i_coa', TRUE);
        if ($i_coa == '') {
            $i_coa = $this->uri->segment(6);
        }

        if ($i_coa != '0') {
            $coa = "AND a.i_coa = '$i_coa' ";
        } else {
            $coa = "";
        }

        $dfrom  = date('Y-m-d', strtotime($dfrom));
        $dto    = date('Y-m-d', strtotime($dto));

        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("SELECT
                d_pv as tgl,
                a.i_pv AS id,
                d_pv,
                i_area_id||' - '||i_pv_id as i_pv_id,
                to_char(d_pv, 'YYYYMM') as i_periode,
                ' [ ' || c.i_coa_id || ' ] ' || initcap(c.e_coa_name) AS e_coa_name,
                b.e_area_name,
                a.v_pv::money AS v_pv,
                case when f.io = f.miu then 'Utuh' when f.io != f.miu then 'Sudah Alokasi' else 'Kosong' end as alo,
                f_pv_cancel AS f_status,
                a.n_print,
                f.io,
                f.miu,
                '$dfrom' AS dfrom,
                '$dto' AS dto,
                '$i_type' as i_type,
                '$i_area' as i_area,
                '$i_coa' as i_coa
            FROM
                tm_pv a
            INNER JOIN tr_area b ON
                (b.i_area = a.i_area)
            INNER JOIN tr_coa c ON
                (c.i_coa = a.i_coa)
            INNER JOIN tm_user_area d ON
                (d.i_area = a.i_area 
                AND d.i_user = '$this->i_user')
            INNER JOIN tm_user_kas_pv e ON
                (e.i_pv_type = a.i_pv_type 
                AND e.i_user = '$this->i_user')
            left join (select i_pv,	sum(v_pv) as io, sum(v_pv_saldo) as miu from tm_pv_item group by 1) as f on (f.i_pv=a.i_pv)
            WHERE
                a.i_company = '$this->i_company'
                AND a.d_pv BETWEEN '$dfrom' AND '$dto'
                $type
                $area
                $coa
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

        $datatables->edit('n_print', function ($data) {
            if ($data['n_print'] == '0') {
                $data = "<span class='badge bg-yellow bg-darken-3 badge-pill'>" . $this->lang->line('Belum') . "</span>";
            } else {
                $data = "<span class='badge bg-blue bg-darken-1 badge-pill'>" . $this->lang->line('Sudah') . ' ' . $data['n_print'] . ' x' . "</span>";
            }
            return $data;
        });

        $datatables->edit('alo', function ($data) {
            if ($data['alo'] == "Utuh") {
                $status = "Utuh";
                $color  = 'cyan';
            } elseif ($data['alo'] == "Sudah Alokasi") {
                $color  = 'pink';
                $status = "Sudah Alokasi";
            } else {
                $color  = 'red';
                $status = "TIDAK ADA DATA";
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
            $type       = $data['i_type'];
            $area       = $data['i_area'];
            $coa        = $data['i_coa'];
            $io         = $data['io'];
            $miu        = $data['miu'];
            $data       = '';
            $data      .= "<a href='" . base_url() . $this->folder . '/view/' . encrypt_url($id) . '/' . encrypt_url($dfrom) . '/' . encrypt_url($dto) . '/' . encrypt_url($type) . '/' . encrypt_url($area) . '/' . encrypt_url($coa) . "' title='View Data'><i class='fa fa-eye fa-lg warning darken-4 mr-1'></i></a>";
            if (check_role($this->id_menu, 5) && $f_status == 'f') {
                $data      .= "<a href='#' onclick='openLink(\"" . $this->folder . "\",\"" . encrypt_url($id) . "\"); return false;' title='Print Data'><i class='fa fa-print fa-lg blue darken-4 mr-1'></i></a>";
            }
            if ($i_periode >= get_periode2()) {
                if (check_role($this->id_menu, 3) && $f_status == 'f') {
                    $data      .= "<a href='" . base_url() . $this->folder . '/edit/' . encrypt_url($id) . '/' .  encrypt_url($dfrom) . '/' . encrypt_url($dto) . '/' . encrypt_url($type) . '/' . encrypt_url($area) . '/' . encrypt_url($coa) . "' title='Edit Data'><i class='fa fa-pencil-square success darken-4 fa-lg mr-1'></i></a>";
                }
                if (check_role($this->id_menu, 4) && $f_status == 'f' && $io == $miu) {
                    $data      .= "<a href='#' onclick='sweetdeletev5raya(\"" . $this->folder . "\",\"" . $id . "\",\"" . encrypt_url($dfrom)  . "\",\"" . encrypt_url($dto)  . "\",\"" . encrypt_url($type)  . "\",\"" . encrypt_url($area)  . "\",\"" . encrypt_url($coa)  . "\");' title='Delete Data'><i class='fa fa-trash fa-lg red darken-4'></i></a>";
                }
            }
            return $data;
        });
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('i_periode');
        $datatables->hide('id');
        $datatables->hide('i_type');
        $datatables->hide('i_area');
        $datatables->hide('i_coa');
        $datatables->hide('io');
        $datatables->hide('miu');
        return $datatables->generate();
    }

    /** Running Number Dokumen*/
    public function running_number($thbl, $tahun, $bln, $i_pv_type, $i_area, $i_coa)
    {
        $cek = $this->db->query("SELECT 
                 substring(i_pv_id, 1, 2) AS kode 
            FROM tm_pv 
            WHERE i_company = '$this->i_company'
            AND i_area = '$i_area'
            AND i_pv_type = '$i_pv_type'
            AND i_coa = '$i_coa'
            ORDER BY i_pv DESC");
        if ($cek->num_rows() > 0) {
            $kode = $cek->row()->kode;
        } else {
            $kode = 'PV';
        }
        $query  = $this->db->query("SELECT
                 max(substring(i_pv_id, 9, 6)) AS max
            FROM
                tm_pv
            WHERE to_char (d_pv, 'yyyy') >= '$tahun'
            and to_char (d_pv, 'MM') >= '$bln'
            AND i_company = '$this->i_company'
            AND i_area = '$i_area'
            AND i_pv_type = '$i_pv_type'
            AND i_coa = '$i_coa'
            AND f_pv_cancel = 'f'
            AND substring(i_pv_id, 1, 2) = '$kode'
            AND substring(i_pv_id, 4, 2) = substring('$thbl',1,2)
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

    public function suzu($thbl, $tahun, $bln, $i_pv_type, $i_area, $i_coa)
    {
        return $this->db->query("SELECT
                    i_pv_id as kod,
	                d_pv as tgl
                FROM
                    tm_pv
                WHERE to_char (d_pv, 'yyyy') >= '$tahun'
                and to_char (d_pv, 'MM') >= '$bln'
                AND i_company = '$this->i_company'
                AND i_area = '$i_area'
                AND i_pv_type = '$i_pv_type'
                AND i_coa = '$i_coa'
                AND f_pv_cancel = 'f'
                AND substring(i_pv_id, 4, 2) = substring('$thbl',1,2)
                order by d_pv desc
        ", FALSE);
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

    /** Get Type */
    public function get_type($cari)
    {
        return $this->db->query("SELECT 
                i_pv_type, i_pv_type_id , initcap(e_pv_type_name) AS e_pv_type_name
            FROM 
                tr_pv_type
            WHERE 
                (e_pv_type_name ILIKE '%$cari%' OR i_pv_type_id ILIKE '%$cari%')
                AND i_company = '$this->i_company' 
                AND f_pv_type_active = 'true' 
                AND i_pv_type IN (SELECT i_pv_type FROM tm_user_kas_pv WHERE i_user = '$this->i_user' AND i_company = '$this->i_company')
            ORDER BY 3 ASC
        ", FALSE);
    }

    /** Get CoA Type */
    public function get_coa_type($cari, $i_pv_type)
    {
        return $this->db->query("SELECT 
                i_coa, i_coa_id , e_coa_name
            FROM 
                tr_coa a
            INNER JOIN tr_pv_type b ON
                (b.i_coa_group = a.i_coa_group
                    AND a.i_company = b.i_company 
                    AND b.i_pv_type = '$i_pv_type')
            WHERE 
                (e_coa_name ILIKE '%$cari%' OR i_coa_id ILIKE '%$cari%')
                AND a.i_company = '$this->i_company' 
                AND f_coa_status = 'true'
            ORDER BY 2 ASC
        ", FALSE);
    }

    /** Get CoA */
    public function get_coa($cari, $i_pv_type)
    {
        $query = $this->db->query("SELECT i_pv_type_id FROM tr_pv_type WHERE i_pv_type = '$i_pv_type'", FALSE);
        if ($query->num_rows() > 0) {
            $i_pv_type_id = $query->row()->i_pv_type_id;
            return $this->db->query("SELECT 
                    i_coa, i_coa_id , e_coa_name
                FROM 
                    tr_coa
                WHERE 
                    (e_coa_name ILIKE '%$cari%' OR i_coa_id ILIKE '%$cari%')
                    AND i_company = '$this->i_company' 
                    AND f_coa_status = 'true' 
                    AND 
                        CASE
                            WHEN '$i_pv_type_id' = 'KK' THEN f_kas_kecil = 't'
                            WHEN '$i_pv_type_id' = 'KB' THEN f_kas_besar = 't'
                            WHEN '$i_pv_type_id' = 'BK' THEN f_kas_bank = 't'
                        END
                ORDER BY 3 ASC
            ", FALSE);
        } else {
            return $this->db->query("SELECT 
                    i_coa, i_coa_id , e_coa_name
                FROM 
                    tr_coa
                WHERE 
                    (e_coa_name ILIKE '%$cari%' OR i_coa_id ILIKE '%$cari%')
                    AND i_company = '$this->i_company' 
                    AND f_coa_status = 'true' 
                ORDER BY 3 ASC
            ", FALSE);
        }
    }

    /** Get Area */
    public function get_coa2($cari, $i_pv_type)
    {
        if ($i_pv_type != '0') {
            $pv = "AND c.i_pv_type = '$i_pv_type' ";
        } else {
            $pv = "";
        }
        return $this->db->query("SELECT  distinct
                    a.i_coa, i_coa_id , e_coa_name
                FROM 
                    tr_coa a
                inner join tm_pv b on (b.i_coa=a.i_coa)
                inner join tm_user_kas_pv c on (c.i_pv_type = b.i_pv_type AND c.i_user = '$this->i_user')
                WHERE 
                    (e_coa_name ILIKE '%$cari%' OR i_coa_id ILIKE '%$cari%')
                    AND a.i_company = '$this->i_company' 
                    AND f_coa_status = 'true' 
                    $pv
                ORDER BY 3 ASC
        ", FALSE);
    }

    /** Get Type Reference */
    public function get_reference_type($cari)
    {
        return $this->db->query("SELECT 
                i_pv_refference_type, e_pv_refference_type_name
            FROM 
                tr_pv_refference_type
            WHERE 
                (e_pv_refference_type_name ILIKE '%$cari%')
                AND i_company = '$this->i_company' 
                AND f_pv_refference_type_active = 'true' 
            ORDER BY 2 ASC
        ", FALSE);
    }

    /** Get Type Reference */
    public function get_reference($cari, $i_pv_refference_type)
    {
        $query = $this->db->query("SELECT f_giro, f_transfer FROM tr_pv_refference_type WHERE i_pv_refference_type = '$i_pv_refference_type'", FALSE);
        if ($query->num_rows() > 0) {
            $f_giro = $query->row()->f_giro;
            $f_transfer = $query->row()->f_transfer;
            if ($f_giro == 't' && $f_transfer == 'f') {
                return $this->db->query("SELECT
                        i_giro AS id,
                        i_giro_id AS kode,
                        v_jumlah,
                        'GR' AS referensi
                    FROM
                        tm_giro_company
                    WHERE
                        f_giro_batal = 'f'
                        AND i_company = '$this->i_company'
                        AND i_giro_id ILIKE '%$cari%'
                    AND i_giro NOT IN (SELECT
                            i_pv_refference AS i_gr
                        FROM
                            tm_pv_item a,
                            tm_pv b
                        WHERE
                            b.i_pv = a.i_pv
                            AND b.f_pv_cancel = 'f'
                            AND i_pv_refference_type = '$i_pv_refference_type'
                            AND b.i_company = '$this->i_company'
                            AND i_pv_refference NOTNULL )
		                order by 1 desc
                ", FALSE);
            } elseif ($f_giro == 'f' && $f_transfer == 't') {
                return $this->db->query("SELECT
                        i_kuk AS id,
                        i_kuk_id AS kode,
                        v_jumlah,
                        'TF' AS referensi
                    FROM
                        tm_kuk
                    WHERE
                        f_kuk_cancel = 'f'
                        AND i_company = '$this->i_company'
                        AND i_kuk_id ILIKE '%$cari%'
                        AND i_kuk NOT IN (SELECT
                                i_pv_refference AS i_kuk
                            FROM
                                tm_pv_item a,
                                tm_pv b
                            WHERE
                                b.i_pv = a.i_pv
                                AND b.f_pv_cancel = 'f'
                                AND i_pv_refference_type = '$i_pv_refference_type'
                                AND b.i_company = '$this->i_company'
                                AND i_pv_refference NOTNULL )
		                order by 1 desc
                ", FALSE);
            } else {
                return $this->db->query("SELECT
                        *
                    FROM
                        (
                        SELECT
                            i_giro AS id,
                            i_giro_id AS kode,
                            v_jumlah,
                            'GR' AS referensi
                        FROM
                            tm_giro_company
                        WHERE
                            f_giro_batal = 'f'
                            AND i_company = '$this->i_company'
                            AND f_giro_cair = 't'
                            AND i_giro_id ILIKE '%$cari%'                    
                    UNION ALL
                        SELECT
                            i_kuk AS id,
                            i_kuk_id AS kode,
                            v_jumlah,
                            'TF' AS referensi
                        FROM
                            tm_kuk
                        WHERE
                            f_kuk_cancel = 'f'
                            AND i_company = '$this->i_company'
                            AND i_kuk_id ILIKE '%$cari%'
                    ) AS x
                    WHERE
                        referensi = (
                        SELECT
                            i_pv_refference_type_id
                        FROM
                            tr_pv_refference_type
                        WHERE
                            i_pv_refference_type = '$i_pv_refference_type')
		                order by 1 desc
                ", FALSE);
            }
        } else {
            return $this->db->query("SELECT
                    *
                FROM
                    (
                    SELECT
                        i_giro AS id,
                        i_giro_id AS kode,
                        v_jumlah,
                        'GR' AS referensi
                    FROM
                        tm_giro_company
                    WHERE
                        f_giro_batal = 'f'
                        AND i_company = '$this->i_company'
                        AND f_giro_cair = 't'
                        AND i_giro_id ILIKE '%$cari%'                
                UNION ALL
                    SELECT
                        i_kuk AS id,
                        i_kuk_id AS kode,
                        v_jumlah,
                        'TF' AS referensi
                    FROM
                        tm_kuk
                    WHERE
                        f_kuk_cancel = 'f'
                        AND i_company = '$this->i_company'
                        AND i_kuk_id ILIKE '%$cari%'
                ) AS x
                WHERE
                    referensi = (
                    SELECT
                        i_pv_refference_type_id
                    FROM
                        tr_pv_refference_type
                    WHERE
                        i_pv_refference_type = '$i_pv_refference_type')
		                order by 1 desc
            ", FALSE);
        }
    }

    /** Get Type Reference Detail */
    public function get_reference_detail($id, $i_pv_refference_type)
    {
        return $this->db->query("SELECT
               *
            from
                (
                select
                    i_giro as id,
                    i_giro_id as kode,
                    v_jumlah,
                    'GR' as referensi
                from
                    tm_giro_company
                where
                    f_giro_batal = 'f'
                    and i_company = '$this->i_company'         
            union all
                select
                    i_kuk as id,
                    i_kuk_id as kode,
                    v_jumlah,
                    'TF' as referensi
                from
                    tm_kuk
                where
                    f_kuk_cancel = 'f'
                    and i_company = '$this->i_company'
            ) as x
            WHERE
                id = '$id'
                AND referensi = (
                SELECT
                    i_pv_refference_type_id
                FROM
                    tr_pv_refference_type
                WHERE
                    i_pv_refference_type = '$i_pv_refference_type')
		                order by 1 desc
        ", FALSE);
    }

    /** Cek Apakah Data Sudah Ada */
    public function cek_code()
    {
        $i_document = str_replace("_", "", $this->input->post('i_document'));
        $i_area = $this->input->post('i_area');
        $i_pv_type = $this->input->post('i_pv_type');
        $i_coa = $this->input->post('i_coa');
        return $this->db->query("SELECT 
                i_pv_id
            FROM 
                tm_pv 
            WHERE 
                upper(trim(i_pv_id)) = upper(trim('$i_document'))
                AND i_area = '$i_area'
                AND i_pv_type = '$i_pv_type'
                AND i_company = '$this->i_company'
                AND i_coa = '$i_coa'
                AND f_pv_cancel = 'f'
        ", FALSE);
    }

    /** Simpan Data */
    public function save()
    {
        $query = $this->db->query("SELECT max(i_pv)+1 AS id FROM tm_pv", TRUE);
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

        $ym = date('ym', strtotime($this->input->post('d_document')));
        $Y = date('Y', strtotime($this->input->post('d_document')));
        $bln = date('m', strtotime($this->input->post('d_document')));
        $i_area = $this->input->post('i_area');
        $i_pv_type = $this->input->post('i_pv_type');
        $i_coa = $this->input->post('i_coa');
        $i_pv_id = $this->running_number($ym, $Y, $bln, $i_pv_type, $i_area, $i_coa);

        $table = array(
            'i_company'  => $this->i_company,
            'i_pv'       => $id,
            'i_pv_id'    => $i_pv_id,
            'i_area'     => $i_area,
            'i_coa'      => $this->input->post('i_coa'),
            'i_pv_type'  => $i_pv_type,
            'd_pv'       => $this->input->post('d_document'),
            'v_pv'       => str_replace(",", "", $this->input->post('v_pv')),
            'e_remark'   => $this->input->post('e_remark'),
            'd_entry'    => current_datetime(),
        );
        $this->db->insert('tm_pv', $table);
        if (is_array($this->input->post('i_coa_item')) || is_object($this->input->post('i_coa_item'))) {
            $i = 0;
            foreach ($this->input->post('i_coa_item') as $i_coa) {
                $i_pv_refference_type = ($this->input->post('i_pv_refference_type')[$i] == '') ? null :  $this->input->post('i_pv_refference_type')[$i];
                $i_pv_refference = ($this->input->post('i_pv_refference')[$i] == '') ? null :  $this->input->post('i_pv_refference')[$i];
                $item = array(
                    'i_pv'                  => $id,
                    'i_coa'                 => $i_coa,
                    'e_coa_name'            => $this->db->query("SELECT e_coa_name FROM tr_coa WHERE i_coa = '$i_coa'", FALSE)->row()->e_coa_name,
                    'v_pv'                  => str_replace(",", "", $this->input->post('v_pv_item')[$i]),
                    'v_pv_saldo'            => str_replace(",", "", $this->input->post('v_pv_item')[$i]),
                    'e_remark'              => $this->input->post('e_remark_item')[$i],
                    'd_bukti'               => $this->input->post('d_bukti')[$i],
                    'n_item_no'             => $i,
                    'i_pv_refference_type'  => $i_pv_refference_type,
                    'i_pv_refference'       => $i_pv_refference,
                );
                $this->db->insert('tm_pv_item', $item);
                $i++;
            }
        } else {
            die;
        }
    }

    /** Get Data Untuk Edit */
    public function get_data($id)
    {
        return $this->db->query("SELECT 
                a.*,
                to_char(d_pv, 'DD FMMonth YYYY') AS date_pv,
                b.i_area_id,
                b.e_area_name,
                c.i_coa_id,
                c.e_coa_name,
                d.i_pv_type_id,
                d.e_pv_type_name
            FROM 
                tm_pv a
            INNER JOIN tr_area b ON 
                (b.i_area = a.i_area)
            INNER JOIN tr_coa c ON 
                (c.i_coa = a.i_coa)
            INNER JOIN tr_pv_type d ON 
                (d.i_pv_type = a.i_pv_type)
            WHERE i_pv = '$id'
        ", FALSE);
    }

    /** Get Data Untuk Edit Detail */
    public function get_data_detail($id)
    {
        return $this->db->query("SELECT
                a.*,
                to_char(d_bukti, 'DD FMMonth YYYY') AS date_bukti,
                d.i_coa_id,
                d.e_coa_name,
                b.e_pv_refference_type_name,
                CASE 
                    WHEN b.f_giro = 't' AND b.f_transfer = 'f' THEN f.i_giro_id|| ' #( '||f.v_jumlah||')'
                    WHEN b.f_giro = 'f' AND b.f_transfer = 't' THEN g.i_kuk_id|| ' #( '||g.v_jumlah||')'
                END AS i_referensi
            FROM
                tm_pv_item a
            INNER JOIN tr_coa d ON 
                (d.i_coa = a.i_coa)
            LEFT JOIN tr_pv_refference_type b ON 
                (b.i_pv_refference_type = a.i_pv_refference_type)
            LEFT JOIN tm_giro_company f ON 
                (f.i_giro = a.i_pv_refference)
            LEFT JOIN tm_kuk g ON 
                (g.i_kuk = a.i_pv_refference) 
            WHERE
                i_pv = '$id'
            ORDER BY
                n_item_no ASC
        ", FALSE);
    }

    public function get_data_detail2($id)
    {
        return $this->db->query("SELECT
                a.*,
                to_char(d_bukti, 'DD FMMonth YYYY') AS date_bukti,
                d.i_coa_id,
                d.e_coa_name,
                b.e_pv_refference_type_name,
                CASE 
                    WHEN b.f_giro = 't' AND b.f_transfer = 'f' THEN f.i_giro_id|| ' #( '||f.v_jumlah||')'
                    WHEN b.f_giro = 'f' AND b.f_transfer = 't' THEN g.i_kuk_id|| ' #( '||g.v_jumlah||')'
                END AS i_referensi
            FROM
                tm_pv_item a
            INNER JOIN tr_coa d ON 
                (d.i_coa = a.i_coa)
            LEFT JOIN tr_pv_refference_type b ON 
                (b.i_pv_refference_type = a.i_pv_refference_type)
            LEFT JOIN tm_giro_company f ON 
                (f.i_giro = a.i_pv_refference)
            LEFT JOIN tm_kuk g ON 
                (g.i_kuk = a.i_pv_refference) 
            WHERE
                i_pv = '$id'      
	            and v_pv = v_pv_saldo
            ORDER BY
                n_item_no ASC
        ", FALSE);
    }

    /** Cek Apakah Data Sudah Ada Pas Edit */
    public function cek_edit()
    {
        $i_document = str_replace("_", "", $this->input->post('i_document'));
        $i_document_old = str_replace("_", "", $this->input->post('i_document_old'));
        $i_area = $this->input->post('i_area');
        $i_pv_type = $this->input->post('i_pv_type');
        $i_coa = $this->input->post('i_coa');
        return $this->db->query("SELECT 
                i_pv_id
            FROM 
                tm_pv 
            WHERE 
                trim(upper(i_pv_id)) <> trim(upper('$i_document_old'))
                AND trim(upper(i_pv_id)) = trim(upper('$i_document'))
                AND i_area = '$i_area'
                AND i_pv_type = '$i_pv_type'
                AND i_company = '$this->i_company'
                AND i_coa = '$i_coa'
         ", FALSE);
    }

    /** Update Data */
    public function update()
    {
        $id = $this->input->post('id');

        $table = array(
            'i_company'  => $this->i_company,
            'i_pv_id'    => strtoupper($this->input->post('i_document')),
            'i_area'     => $this->input->post('i_area'),
            'i_coa'      => $this->input->post('i_coa'),
            'i_pv_type'  => $this->input->post('i_pv_type'),
            'd_pv'       => $this->input->post('d_document'),
            // 'v_pv'       => str_replace(",", "", $this->input->post('v_pv')),
            'e_remark'   => $this->input->post('e_remark'),
            'd_update'   => current_datetime(),
        );
        $this->db->where('i_pv', $id);
        $this->db->update('tm_pv', $table);
        if (is_array($this->input->post('i_coa_item')) || is_object($this->input->post('i_coa_item'))) {

            $noo = $this->db->query("SELECT max(n_item_no) as mak FROM tm_pv_item where i_pv=$id ", FALSE)->row()->mak;

            $this->db->where('i_pv', $id);
            $this->db->where("v_pv=v_pv_saldo");
            $this->db->delete('tm_pv_item');

            $nono = $noo+1;
            $i = 0;
            foreach ($this->input->post('i_coa_item') as $i_coa) {
                $i_pv_refference_type = ($this->input->post('i_pv_refference_type')[$i] == '') ? null :  $this->input->post('i_pv_refference_type')[$i];
                $i_pv_refference = ($this->input->post('i_pv_refference')[$i] == '') ? null :  $this->input->post('i_pv_refference')[$i];
                $item = array(
                    'i_pv'                  => $id,
                    'i_coa'                 => $i_coa,
                    'e_coa_name'            => $this->db->query("SELECT e_coa_name FROM tr_coa WHERE i_coa = '$i_coa'", FALSE)->row()->e_coa_name,
                    'v_pv'                  => str_replace(",", "", $this->input->post('v_pv_item')[$i]),
                    'v_pv_saldo'            => str_replace(",", "", $this->input->post('v_pv_item')[$i]),
                    'e_remark'              => $this->input->post('e_remark_item')[$i],
                    'd_bukti'               => $this->input->post('d_bukti')[$i],
                    'n_item_no'             => $i,
                    'i_pv_refference_type'  => $i_pv_refference_type,
                    'i_pv_refference'       => $i_pv_refference,
                );
                $this->db->insert('tm_pv_item', $item);
                $i++;
                $nono++;
            }
        } else {
            die;
        }
    }

    public function miru()
    {
        $id = $this->input->post('id');
        $this->db->query("UPDATE tm_pv set 
        v_pv=miru.v_pv2 from (
        select i_pv, sum(v_pv) as v_pv2 from (
        select i_pv, v_pv from tm_pv_item where
        i_pv = '$id' )as krn group by 1
        )as miru where tm_pv.i_pv =miru.i_pv ", FALSE);
    }


    /** Cancel Data */
    public function cancel($id,$alasan,$i_pv_id)
    {
        $table = array(
            'f_pv_cancel' => 't',
        );
        $this->db->where('i_pv', $id);
        $this->db->update('tm_pv', $table);

        $this->db->query("INSERT INTO tx_del (i_company, i_user, e_user_name, e_dokumen, e_alasan)
        VALUES ('$this->i_company', '$this->i_user', '$this->e_user_name', 'No Voucher : $i_pv_id', '$alasan')");
    }

    /** Get Saldo Saat Ini */
    public function get_saldo($i_coa, $i_periode)
    {
        // return $this->db->query("SELECT v_saldo_akhir FROM tm_coa_saldo WHERE i_coa = '$i_coa' AND i_company = '$this->i_company' AND i_periode = '$i_periode' ", FALSE);

        return $this->db->query("SELECT
        i_coa,
        sum(v_saldo_awal) AS v_saldo_awal,
        sum(v_mutasi_debet) AS v_mutasi_debet,
        sum(v_mutasi_kredit) AS v_mutasi_kredit,
        (sum(v_saldo_awal) + sum(v_mutasi_debet) - abs(sum(v_mutasi_kredit))) AS v_saldo_akhir
    FROM
        (
        SELECT
            i_coa,
            v_saldo_awal,
            0 AS v_mutasi_debet,
            0 AS v_mutasi_kredit
        FROM
            tm_coa_saldo
        WHERE
            i_company = '$this->i_company'
            AND i_periode = '$i_periode'
            AND i_coa = '$i_coa'
    UNION ALL
        SELECT
            i_coa,
            0 AS v_saldo_awal,
            sum(v_rv) AS v_mutasi_debet,
            0 AS v_mutasi_kredit
        FROM
            tm_rv
        WHERE
            i_company = '$this->i_company'
            AND to_char(d_rv, 'YYYYMM') = '$i_periode'
            AND f_rv_cancel = 'f'
            AND i_coa = '$i_coa'
        GROUP BY
            1
    UNION ALL
        SELECT
            i_coa,
            0 AS v_saldo_awal,
            0 AS v_mutasi_debet,
            sum(v_pv) AS v_mutasi_kredit
        FROM
            tm_pv
        WHERE
            i_company = '$this->i_company'
            AND to_char(d_pv, 'YYYYMM') = '$i_periode'
            AND f_pv_cancel = 'f'
            AND i_coa = '$i_coa'
        GROUP BY
            1
    ) AS x
    GROUP BY
        1 ", FALSE);
    }

    public function get_tex($i_pv_type)
    {
        return $this->db->query("SELECT i_pv_type_id FROM tr_pv_type WHERE i_pv_type = '$i_pv_type' ", FALSE);
    }

    /** Update Print */
    public function update_print($id)
    {
        $this->db->query("UPDATE tm_pv SET n_print = n_print + 1 WHERE i_pv = '$id' ", FALSE);
    }
}

/* End of file Mmaster.php */
