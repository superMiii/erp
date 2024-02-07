<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mrv extends CI_Model
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
            $type = "AND a.i_rv_type = '$i_type' ";
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
                d_rv as tgl,
                a.i_rv AS id,
                d_rv,
                i_area_id||' - '||i_rv_id as i_rv_id,
                to_char(d_rv, 'YYYYMM') as i_periode,
                ' [ ' || c.i_coa_id || ' ] ' || initcap(c.e_coa_name) AS e_coa_name,
                b.e_area_name,
                v_rv::money AS v_rv,
                case when f.io = f.miu then 'Utuh' when f.io != f.miu then 'Sudah Alokasi' else 'Kosong' end as alo,
                f_rv_cancel AS f_status,
                a.n_print,
                f.io,
                f.miu,
                '$dfrom' AS dfrom,
                '$dto' AS dto,
                '$i_type' as i_type,
                '$i_area' as i_area,
                '$i_coa' as i_coa
            FROM
                tm_rv a
            INNER JOIN tr_area b ON
                (b.i_area = a.i_area)
            INNER JOIN tr_coa c ON
                (c.i_coa = a.i_coa)
            INNER JOIN tm_user_area d ON
                (d.i_area = a.i_area 
                AND d.i_user = '$this->i_user')
            INNER JOIN tm_user_kas_rv e ON
                (e.i_rv_type = a.i_rv_type 
                AND e.i_user = '$this->i_user')
            left join (select i_rv,	sum(v_rv) as io, sum(v_rv_saldo) as miu from tm_rv_item group by 1) as f on (f.i_rv=a.i_rv)
            WHERE
                a.i_company = '$this->i_company'
                AND a.d_rv BETWEEN '$dfrom' AND '$dto'
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
    public function running_number($thbl, $tahun, $bln, $i_rv_type, $i_area, $i_coa)
    {
        $cek = $this->db->query("SELECT 
                 substring(i_rv_id, 1, 2) AS kode 
            FROM tm_rv 
            WHERE i_company = '$this->i_company'
            AND i_area = '$i_area'
            AND i_rv_type = '$i_rv_type'
            AND i_coa = '$i_coa'
            ORDER BY i_rv DESC");
        if ($cek->num_rows() > 0) {
            $kode = $cek->row()->kode;
        } else {
            $kode = 'RV';
        }
        $query  = $this->db->query("SELECT
                 max(substring(i_rv_id, 9, 6)) AS max
            FROM
                tm_rv
            WHERE to_char (d_rv, 'yyyy') >= '$tahun'
            and to_char (d_rv, 'MM') >= '$bln'
            AND i_company = '$this->i_company'
            AND i_area = '$i_area'
            AND i_rv_type = '$i_rv_type'
            AND i_coa = '$i_coa'
            AND f_rv_cancel = 'f'
            AND substring(i_rv_id, 1, 2) = '$kode'
            AND substring(i_rv_id, 4, 2) = substring('$thbl',1,2)
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


    public function suzu($thbl, $tahun, $bln, $i_rv_type, $i_area, $i_coa)
    {
        return $this->db->query("SELECT
                    i_rv_id as kod,
	                d_rv as tgl
                FROM
                    tm_rv
                WHERE to_char (d_rv, 'yyyy') >= '$tahun'
                and to_char (d_rv, 'MM') >= '$bln'
                AND i_company = '$this->i_company'
                AND i_area = '$i_area'
                AND i_rv_type = '$i_rv_type'
                AND i_coa = '$i_coa'
                AND f_rv_cancel = 'f'
                AND substring(i_rv_id, 4, 2) = substring('$thbl',1,2)
                order by d_rv desc
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
                i_rv_type, i_rv_type_id , initcap(e_rv_type_name) AS e_rv_type_name
            FROM 
                tr_rv_type
            WHERE 
                (e_rv_type_name ILIKE '%$cari%' OR i_rv_type_id ILIKE '%$cari%')
                AND i_company = '$this->i_company' 
                AND f_rv_type_active = 'true' 
                AND i_rv_type IN (SELECT i_rv_type FROM tm_user_kas_rv WHERE i_user = '$this->i_user' AND i_company = '$this->i_company')
            ORDER BY 3 ASC
        ", FALSE);
    }

    /** Get CoA Type */
    public function get_coa_type($cari, $i_rv_type)
    {
        return $this->db->query("SELECT 
                i_coa, i_coa_id , e_coa_name
            FROM 
                tr_coa a
            INNER JOIN tr_rv_type b ON
                (b.i_coa_group = a.i_coa_group
                    AND a.i_company = b.i_company 
                    AND b.i_rv_type = '$i_rv_type')
            WHERE 
                (e_coa_name ILIKE '%$cari%' OR i_coa_id ILIKE '%$cari%')
                AND a.i_company = '$this->i_company' 
                AND f_coa_status = 'true'
            ORDER BY 2 ASC
        ", FALSE);
    }

    /** Get CoA */
    public function get_coa($cari, $i_rv_type)
    {
        $query = $this->db->query("SELECT f_kas_kecil, f_kas_besar, f_kas_bank FROM tr_rv_type WHERE i_rv_type = '$i_rv_type'", FALSE);
        if ($query->num_rows() > 0) {
            $f_kas_kecil = $query->row()->f_kas_kecil;
            $f_kas_besar = $query->row()->f_kas_besar;
            $f_kas_bank = $query->row()->f_kas_bank;
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
                            WHEN '$f_kas_kecil' = 't' AND '$f_kas_besar' = 'f' AND '$f_kas_bank' = 'f' THEN f_kas_kecil = 't'
                            WHEN '$f_kas_kecil' = 'f' AND '$f_kas_besar' = 't' AND '$f_kas_bank' = 'f' THEN f_kas_besar = 't'
                            WHEN '$f_kas_kecil' = 'f' AND '$f_kas_besar' = 'f' AND '$f_kas_bank' = 't' THEN f_kas_bank = 't'
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
    public function get_coa2($cari, $i_rv_type)
    {
        if ($i_rv_type != '0') {
            $rv = "AND c.i_rv_type = '$i_rv_type' ";
        } else {
            $rv = "";
        }
        return $this->db->query("SELECT  distinct
                    a.i_coa, i_coa_id , e_coa_name
                FROM 
                    tr_coa a
                inner join tm_rv b on (b.i_coa=a.i_coa)
                inner join tm_user_kas_rv c on (c.i_rv_type = b.i_rv_type AND c.i_user = '$this->i_user')
                WHERE 
                    (e_coa_name ILIKE '%$cari%' OR i_coa_id ILIKE '%$cari%')
                    AND a.i_company = '$this->i_company' 
                    AND f_coa_status = 'true' 
                    $rv
                ORDER BY 3 ASC
        ", FALSE);
    }

    /** Get Type Reference */
    public function get_reference_type($cari)
    {
        return $this->db->query("SELECT 
                i_rv_refference_type, e_rv_refference_type_name
            FROM 
                tr_rv_refference_type
            WHERE 
                (e_rv_refference_type_name ILIKE '%$cari%')
                AND i_company = '$this->i_company' 
                AND f_rv_refference_type_active = 'true' 
            ORDER BY 2 ASC
        ", FALSE);
    }

    /** Get Type Reference */
    public function get_reference($cari, $i_rv_refference_type)
    {
        $query = $this->db->query("SELECT f_tunai, f_giro, f_transfer FROM tr_rv_refference_type WHERE i_rv_refference_type = '$i_rv_refference_type'", FALSE);
        if ($query->num_rows() > 0) {
            $f_tunai = $query->row()->f_tunai;
            $f_giro = $query->row()->f_giro;
            $f_transfer = $query->row()->f_transfer;
            if ($f_tunai == 't' && $f_giro == 'f' && $f_transfer == 'f') {
                return $this->db->query("SELECT
                        i_st AS id,
                        i_st_id AS kode,
                        rr.e_area_name AS ara,
                        v_jumlah,
                        'TN' AS referensi
                    FROM
                        tm_st
                        inner join tr_area rr on (tm_st.i_area=rr.i_area)
                    WHERE
                        f_st_cancel = 'f'
                        AND tm_st.i_company = '$this->i_company'
                        AND i_st_id ILIKE '%$cari%'
                        AND i_st NOT IN (SELECT
                                i_rv_refference AS i_st
                            FROM
                                tm_rv_item a,
                                tm_rv b
                            WHERE
                                b.i_rv = a.i_rv
                                AND b.f_rv_cancel = 'f'
                                AND i_rv_refference_type = '$i_rv_refference_type'
                                AND b.i_company = '$this->i_company'
                                AND i_rv_refference NOTNULL )
		                    order by 1 desc
                ", FALSE);
            } elseif ($f_tunai == 'f' && $f_giro == 't' && $f_transfer == 'f') {
                return $this->db->query("SELECT
                        i_giro AS id,
                        i_giro_id AS kode,
                        rr.e_area_name AS ara,
                        v_jumlah,
                        'GR' AS referensi
                    FROM
                        tm_giro
                        inner join tr_area rr on (tm_giro.i_area=rr.i_area)
                    WHERE
                        f_giro_batal = 'f'
                        AND tm_giro.i_company = '$this->i_company'
                        AND f_giro_cair = 't'
                        AND i_giro_id ILIKE '%$cari%'
                    AND i_giro NOT IN (SELECT
                            i_rv_refference AS i_gr
                        FROM
                            tm_rv_item a,
                            tm_rv b
                        WHERE
                            b.i_rv = a.i_rv
                            AND b.f_rv_cancel = 'f'
                            AND i_rv_refference_type = '$i_rv_refference_type'
                            AND b.i_company = '$this->i_company'
                            AND i_rv_refference NOTNULL )
		                order by 1 desc
                ", FALSE);
            } elseif ($f_tunai == 'f' && $f_giro == 'f' && $f_transfer == 't') {
                return $this->db->query("SELECT
                        i_kum AS id,
                        i_kum_id AS kode,
                        rr.e_area_name AS ara,
                        v_jumlah,
                        'TF' AS referensi
                    FROM
                        tm_kum
                        inner join tr_area rr on (tm_kum.i_area=rr.i_area)
                    WHERE
                        f_kum_cancel = 'f'
                        AND tm_kum.i_company = '$this->i_company'
                        AND i_kum_id ILIKE '%$cari%'
                        AND i_kum NOT IN (SELECT
                                i_rv_refference AS i_kum
                            FROM
                                tm_rv_item a,
                                tm_rv b
                            WHERE
                                b.i_rv = a.i_rv
                                AND b.f_rv_cancel = 'f'
                                AND i_rv_refference_type = '$i_rv_refference_type'
                                AND b.i_company = '$this->i_company'
                                AND i_rv_refference NOTNULL )
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
                            rr.e_area_name AS ara,
                            v_jumlah,
                            'GR' AS referensi
                        FROM
                            tm_giro
                        inner join tr_area rr on (tm_giro.i_area=rr.i_area)
                        WHERE
                            f_giro_batal = 'f'
                            AND tm_giro.i_company = '$this->i_company'
                            AND f_giro_cair = 't'
                            AND i_giro_id ILIKE '%$cari%'
                    UNION ALL
                        SELECT
                            i_st AS id,
                            i_st_id AS kode,
                            rr.e_area_name AS ara,
                            v_jumlah,
                            'TN' AS referensi
                        FROM
                            tm_st
                        inner join tr_area rr on (tm_st.i_area=rr.i_area)
                        WHERE
                            f_st_cancel = 'f'
                            AND tm_st.i_company = '$this->i_company'
                            AND i_st_id ILIKE '%$cari%'
                    UNION ALL
                        SELECT
                            i_kum AS id,
                            i_kum_id AS kode,
                            rr.e_area_name AS ara,
                            v_jumlah,
                            'TF' AS referensi
                        FROM
                            tm_kum
                        inner join tr_area rr on (tm_kum.i_area=rr.i_area)
                        WHERE
                            f_kum_cancel = 'f'
                            AND tm_kum.i_company = '$this->i_company'
                            AND i_kum_id ILIKE '%$cari%'
                    ) AS x
                    WHERE
                        referensi = (
                        SELECT
                            i_rv_refference_type_id
                        FROM
                            tr_rv_refference_type
                        WHERE
                            i_rv_refference_type = '$i_rv_refference_type')
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
                        tm_giro
                    WHERE
                        f_giro_batal = 'f'
                        AND i_company = '$this->i_company'
                        AND f_giro_cair = 't'
                        AND i_giro_id ILIKE '%$cari%'
                UNION ALL
                    SELECT
                        i_st AS id,
                        i_st_id AS kode,
                        v_jumlah,
                        'TN' AS referensi
                    FROM
                        tm_st
                    WHERE
                        f_st_cancel = 'f'
                        AND i_company = '$this->i_company'
                        AND i_st_id ILIKE '%$cari%'
                UNION ALL
                    SELECT
                        i_kum AS id,
                        i_kum_id AS kode,
                        v_jumlah,
                        'TF' AS referensi
                    FROM
                        tm_kum
                    WHERE
                        f_kum_cancel = 'f'
                        AND i_company = '$this->i_company'
                        AND i_kum_id ILIKE '%$cari%'
                ) AS x
                WHERE
                    referensi = (
                    SELECT
                        i_rv_refference_type_id
                    FROM
                        tr_rv_refference_type
                    WHERE
                        i_rv_refference_type = '$i_rv_refference_type')
		                order by 1 desc
            ", FALSE);
        }
    }

    /** Get Type Reference Detail */
    public function get_reference_detail($id, $i_rv_refference_type)
    {
        return $this->db->query("SELECT
                *
            FROM
                (
                SELECT
                    i_giro AS id,
                    i_giro_id AS kode,
                    i_area as araa,
                    v_jumlah,
                    'GR' AS referensi
                FROM
                    tm_giro
                WHERE
                    f_giro_batal = 'f'
                    AND i_company = '$this->i_company'
                    AND f_giro_cair = 't'
            UNION ALL
                SELECT
                    i_st AS id,
                    i_st_id AS kode,
                    i_area as araa,
                    v_jumlah,
                    'TN' AS referensi
                FROM
                    tm_st
                WHERE
                    f_st_cancel = 'f'
                    AND i_company = '$this->i_company'
            UNION ALL
                SELECT
                    i_kum AS id,
                    i_kum_id AS kode,
                    i_area as araa,
                    v_jumlah,
                    'TF' AS referensi
                FROM
                    tm_kum
                WHERE
                    f_kum_cancel = 'f'
                    AND i_company = '$this->i_company'
            ) AS x
            WHERE
                id = '$id'
                AND referensi = (
                SELECT
                    i_rv_refference_type_id
                FROM
                    tr_rv_refference_type
                WHERE
                    i_rv_refference_type = '$i_rv_refference_type')
		                order by 1 desc
        ", FALSE);
    }

    /** Cek Apakah Data Sudah Ada */
    public function cek_code()
    {
        $i_document = str_replace("_", "", $this->input->post('i_document'));
        $i_area = $this->input->post('i_area');
        $i_rv_type = $this->input->post('i_rv_type');
        $i_coa = $this->input->post('i_coa');
        return $this->db->query("SELECT 
                i_rv_id
            FROM 
                tm_rv 
            WHERE 
                upper(trim(i_rv_id)) = upper(trim('$i_document'))
                AND i_area = '$i_area'
                AND i_rv_type = '$i_rv_type'
                AND i_company = '$this->i_company'
                AND i_coa = '$i_coa'
                AND f_rv_cancel = 'f'
        ", FALSE);
    }

    /** Simpan Data */
    public function save()
    {
        $query = $this->db->query("SELECT max(i_rv)+1 AS id FROM tm_rv", TRUE);
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
        $i_rv_type = $this->input->post('i_rv_type');
        $i_coa = $this->input->post('i_coa');
        $i_rv_id = $this->running_number($ym, $Y, $bln, $i_rv_type, $i_area, $i_coa);

        $table = array(
            'i_company'  => $this->i_company,
            'i_rv'       => $id,
            'i_rv_id'    => $i_rv_id,
            'i_area'     => $i_area,
            'i_coa'      => $this->input->post('i_coa'),
            'i_rv_type'  => $i_rv_type,
            'd_rv'       => $this->input->post('d_document'),
            'v_rv'       => str_replace(",", "", $this->input->post('v_rv')),
            'e_remark'   => $this->input->post('e_remark'),
            'd_entry'    => current_datetime(),
        );
        $this->db->insert('tm_rv', $table);
        if (is_array($this->input->post('i_coa_item')) || is_object($this->input->post('i_coa_item'))) {
            $i = 0;
            foreach ($this->input->post('i_coa_item') as $i_coa) {
                $i_rv_refference_type   = ($this->input->post('i_rv_refference_type')[$i] == '') ? null :  $this->input->post('i_rv_refference_type')[$i];
                $i_rv_refference        = ($this->input->post('i_rv_refference')[$i] == '') ? null :  $this->input->post('i_rv_refference')[$i];
                $araa                   = ($this->input->post('araa')[$i] == '') ? null :  $this->input->post('araa')[$i];
                $item = array(
                    'i_rv'                  => $id,
                    'i_coa'                 => $i_coa,
                    'e_coa_name'            => $this->db->query("SELECT e_coa_name FROM tr_coa WHERE i_coa = '$i_coa'", FALSE)->row()->e_coa_name,
                    'v_rv'                  => str_replace(",", "", $this->input->post('v_rv_item')[$i]),
                    'v_rv_saldo'            => str_replace(",", "", $this->input->post('v_rv_item')[$i]),
                    'e_remark'              => $this->input->post('e_remark_item')[$i],
                    'd_bukti'               => $this->input->post('d_bukti')[$i],
                    'n_item_no'             => $i,
                    'i_rv_refference_type'  => $i_rv_refference_type,
                    'i_rv_refference'       => $i_rv_refference,
                    'ara'                   => $araa,
                );
                $this->db->insert('tm_rv_item', $item);
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
                to_char(d_rv, 'DD FMMonth YYYY') AS date_rv,
                b.i_area_id,
                b.e_area_name,
                c.i_coa_id,
                c.e_coa_name,
                d.i_rv_type_id,
                d.e_rv_type_name
            FROM 
                tm_rv a
            INNER JOIN tr_area b ON 
                (b.i_area = a.i_area)
            INNER JOIN tr_coa c ON 
                (c.i_coa = a.i_coa)
            INNER JOIN tr_rv_type d ON 
                (d.i_rv_type = a.i_rv_type)
            WHERE i_rv = '$id'
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
                b.e_rv_refference_type_name,
                CASE 
                    WHEN b.f_tunai = 't' AND b.f_giro = 'f' AND b.f_transfer = 'f' THEN e.i_st_id|| ' #( '||e.v_jumlah||')' || ' # '||rr.e_area_name 
                    WHEN b.f_tunai = 'f' AND b.f_giro = 't' AND b.f_transfer = 'f' THEN f.i_giro_id|| ' #( '||f.v_jumlah||')'|| ' # '||rr.e_area_name
                    WHEN b.f_tunai = 'f' AND b.f_giro = 'f' AND b.f_transfer = 't' THEN g.i_kum_id|| ' #( '||g.v_jumlah||')'|| ' # '||rr.e_area_name
                END AS i_referensi
            FROM
                tm_rv_item a
            INNER JOIN tr_coa d ON
                (d.i_coa = a.i_coa)
            LEFT JOIN tr_rv_refference_type b ON
                (b.i_rv_refference_type = a.i_rv_refference_type)
            LEFT JOIN tm_st e ON 
                (e.i_st = a.i_rv_refference)
            LEFT JOIN tm_giro f ON 
                (f.i_giro = a.i_rv_refference)
            LEFT JOIN tm_kum g ON 
                (g.i_kum = a.i_rv_refference) 
            left join tr_area rr on (rr.i_area = a.ara)
            WHERE
                i_rv = '$id'
            ORDER BY
                n_item_no ASC
        ", FALSE);
    }

    

    /** Get Data Untuk Edit Detail */
    public function get_data_detail2($id)
    {
        return $this->db->query("SELECT
                a.*,
                to_char(d_bukti, 'DD FMMonth YYYY') AS date_bukti,
                d.i_coa_id,
                d.e_coa_name,
                b.e_rv_refference_type_name,
                CASE 
                    WHEN b.f_tunai = 't' AND b.f_giro = 'f' AND b.f_transfer = 'f' THEN e.i_st_id|| ' #( '||e.v_jumlah||')' || ' # '||rr.e_area_name 
                    WHEN b.f_tunai = 'f' AND b.f_giro = 't' AND b.f_transfer = 'f' THEN f.i_giro_id|| ' #( '||f.v_jumlah||')'|| ' # '||rr.e_area_name
                    WHEN b.f_tunai = 'f' AND b.f_giro = 'f' AND b.f_transfer = 't' THEN g.i_kum_id|| ' #( '||g.v_jumlah||')'|| ' # '||rr.e_area_name
                END AS i_referensi
            FROM
                tm_rv_item a
            INNER JOIN tr_coa d ON
                (d.i_coa = a.i_coa)
            LEFT JOIN tr_rv_refference_type b ON
                (b.i_rv_refference_type = a.i_rv_refference_type)
            LEFT JOIN tm_st e ON 
                (e.i_st = a.i_rv_refference)
            LEFT JOIN tm_giro f ON 
                (f.i_giro = a.i_rv_refference)
            LEFT JOIN tm_kum g ON 
                (g.i_kum = a.i_rv_refference) 
            left join tr_area rr on (rr.i_area = a.ara)
            WHERE
                i_rv = '$id'                
	            and v_rv = v_rv_saldo
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
        $i_rv_type = $this->input->post('i_rv_type');
        $i_coa = $this->input->post('i_coa');
        return $this->db->query("SELECT
                i_rv_id
            FROM 
                tm_rv 
            WHERE 
                trim(upper(i_rv_id)) <> trim(upper('$i_document_old'))
                AND trim(upper(i_rv_id)) = trim(upper('$i_document'))
                AND i_area = '$i_area'
                AND i_rv_type = '$i_rv_type'
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
            'i_rv_id'    => strtoupper($this->input->post('i_document')),
            'i_area'     => $this->input->post('i_area'),
            'i_coa'      => $this->input->post('i_coa'),
            'i_rv_type'  => $this->input->post('i_rv_type'),
            'd_rv'       => $this->input->post('d_document'),
            // 'v_rv'       => str_replace(",", "", $this->input->post('v_rv')),
            'e_remark'   => $this->input->post('e_remark'),
            'd_update'   => current_datetime(),
        );
        $this->db->where('i_rv', $id);
        $this->db->update('tm_rv', $table);
        if (is_array($this->input->post('i_coa_item')) || is_object($this->input->post('i_coa_item'))) {

            $noo = $this->db->query("SELECT max(n_item_no) as mak FROM tm_rv_item where i_rv=$id ", FALSE)->row()->mak;

            $this->db->where('i_rv', $id);
            $this->db->where("v_rv=v_rv_saldo");
            $this->db->delete('tm_rv_item'); 

            $nono = $noo+1;
            $i = 0;
            foreach ($this->input->post('i_coa_item') as $i_coa) {
                $i_rv_refference_type   = ($this->input->post('i_rv_refference_type')[$i] == '') ? null :  $this->input->post('i_rv_refference_type')[$i];
                $i_rv_refference        = ($this->input->post('i_rv_refference')[$i] == '') ? null :  $this->input->post('i_rv_refference')[$i];
                $araa                   = ($this->input->post('araa')[$i] == '') ? null :  $this->input->post('araa')[$i];
                $item = array(
                    'i_rv'                  => $id,
                    'i_coa'                 => $i_coa,
                    'e_coa_name'            => $this->db->query("SELECT e_coa_name FROM tr_coa WHERE i_coa = '$i_coa'", FALSE)->row()->e_coa_name,
                    'v_rv'                  => str_replace(",", "", $this->input->post('v_rv_item')[$i]),
                    'v_rv_saldo'            => str_replace(",", "", $this->input->post('v_rv_item')[$i]),
                    'e_remark'              => $this->input->post('e_remark_item')[$i],
                    'd_bukti'               => $this->input->post('d_bukti')[$i],
                    'n_item_no'             => $nono,
                    'i_rv_refference_type'  => $i_rv_refference_type,
                    'i_rv_refference'       => $i_rv_refference,
                    'ara'                   => $araa,
                );
                $this->db->insert('tm_rv_item', $item);
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
        $this->db->query("UPDATE tm_rv set 
        v_rv=miru.v_rv2 from (
        select i_rv, sum(v_rv) as v_rv2 from (
        select i_rv, v_rv from tm_rv_item where
        i_rv = '$id' )as krn group by 1
        )as miru where tm_rv.i_rv =miru.i_rv ", FALSE);
    }

    /** Cancel Data */
    public function cancel($id,$alasan,$i_rv_id)
    {
        $table = array(
            'f_rv_cancel' => 't',
        );
        $this->db->where('i_rv', $id);
        $this->db->update('tm_rv', $table);

        $this->db->query("INSERT INTO tx_del (i_company, i_user, e_user_name, e_dokumen, e_alasan)
        VALUES ('$this->i_company', '$this->i_user', '$this->e_user_name', 'No Voucher : $i_rv_id', '$alasan')");
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

    public function get_tex($i_rv_type)
    {
        return $this->db->query("SELECT i_rv_type_id FROM tr_rv_type WHERE i_rv_type = '$i_rv_type' ", FALSE);
    }

    /** Update Print */
    public function update_print($id)
    {
        $this->db->query("UPDATE tm_rv SET n_print = n_print + 1 WHERE i_rv = '$id' ", FALSE);
    }
}

/* End of file Mmaster.php */
