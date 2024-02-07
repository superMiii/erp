<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mpengguna extends CI_Model
{

    /** List Datatable */
    public function serverside()
    {
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("SELECT
                distinct a.i_user,
                i_user_id,
                e_user_name,
                e.e_department_name,
                f.e_level_name,
                case when a.f_pusat='t' then 'PUSAT' else 'DAERAH' end as f_pusat,
                a.f_status,
                case
                    when d_user_update isnull then d_user_entry
                    else d_user_update
                end as d_update,
                a.ava
            from
                tm_user a
            left join tm_user_company b on
                (b.i_user = a.i_user)
            left join tr_company c on
                (c.i_company = b.i_company)
            left join tm_user_cover d on
                (a.i_user = d.i_user)
            left join tr_department e on
                (e.i_department = d.i_department)
            left join tr_level f on
                (f.i_level = d.i_level)
                    WHERE
                        b.i_company = '$this->i_company'
                        AND a.i_user <> 1
                    ORDER BY
                        e_user_name
        ", FALSE);

        $datatables->edit('f_status', function ($data) {
            $id         = $data['i_user'];
            if ($data['f_status'] == 't') {
                $status = $this->lang->line('Aktif');
                $color  = 'success';
            } else {
                $status = $this->lang->line('Tidak Aktif');
                $color  = 'danger';
            }
            $data = "<button class='btn btn-outline-" . $color . " btn-sm round' onclick='changestatus(\"" . $this->folder . "\",\"" . $id . "\");'>" . $status . "</button>";
            return $data;
        });

        /** Cek Hak Akses, Apakah User Bisa Edit */
        $datatables->add('action', function ($data) {
            $id         = trim($data['i_user']);
            $data       = '';
            $data      .= "<a href='" . base_url() . $this->folder . '/view/' . encrypt_url($id) . "' title='View Data'><i class='fa fa-eye fa-lg warning darken-4 mr-1'></i></a>";
            if (check_role($this->i_menu, 3)) {
                $data      .= "<a href='" . base_url() . $this->folder . '/edit/' . encrypt_url($id) . "' title='Edit Data'><i class='fa fa-check-circle-o success darken-4 fa-lg mr-1'></i></a>";
            }
            return $data;
        });
        $datatables->hide('d_update');
        return $datatables->generate();
    }

    public function changestatus($id)
    {
        $this->db->select('f_status');
        $this->db->from('tm_user');
        $this->db->where('i_user', $id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $status = $query->row()->f_status;
        } else {
            $status = 'f';
        }
        if ($status == 'f') {
            $fstatus = 't';
        } else {
            $fstatus = 'f';
        }
        $table = array(
            'f_status' => $fstatus,
        );
        $this->db->where('i_user', $id);
        $this->db->update('tm_user', $table);
    }

    /** Get Data Company */
    public function get_company($cari)
    {
        return $this->db->query("SELECT 
                i_company,
                i_company_id,
                initcap(e_company_name) AS e_company_name
            FROM 
                tr_company
            WHERE 
                (e_company_name ILIKE '%$cari%')
                AND f_company_active = 't'
            ORDER BY 
                e_company_name
        ", FALSE);
    }

    /** Get Data Area */
    public function get_area($cari, $i_company)
    {
        return $this->db->query("SELECT 
                i_area,
                i_area_id,
                initcap(e_area_name) AS e_area_name
            FROM 
                tr_area
            WHERE 
                (e_area_name ILIKE '%$cari%')
                AND f_area_active = 't'
                AND i_company = '$i_company'
            ORDER BY 
                e_area_name
        ", FALSE);
    }

    /** Get Data RV */
    public function get_rv_type($cari, $i_company)
    {
        return $this->db->query("SELECT 
                i_rv_type,
                i_rv_type_id,
                initcap(e_rv_type_name) AS e_rv_type_name
            FROM 
                tr_rv_type
            WHERE 
                (e_rv_type_name ILIKE '%$cari%')
                AND f_rv_type_active = 't'
                AND i_company = '$i_company'
            ORDER BY 
                e_rv_type_name
        ", FALSE);
    }

    /** Get Data PV */
    public function get_pv_type($cari, $i_company)
    {
        return $this->db->query("SELECT 
                i_pv_type,
                i_pv_type_id,
                initcap(e_pv_type_name) AS e_pv_type_name
            FROM 
                tr_pv_type
            WHERE 
                (e_pv_type_name ILIKE '%$cari%')
                AND f_pv_type_active = 't'
                AND i_company = '$i_company'
            ORDER BY 
                e_pv_type_name
        ", FALSE);
    }

    public function get_store($cari, $i_company)
    {
        return $this->db->query("SELECT 
                i_store,
                i_store_id,
                initcap(e_store_name) AS e_store_name
            FROM 
                tr_store
            WHERE 
                (e_store_name ILIKE '%$cari%')
                AND f_store_active = 't'
                AND i_company = '$i_company'
            ORDER BY 
                1
        ", FALSE);
    }

    /** Get Data Department */
    public function get_department($cari)
    {
        return $this->db->query("SELECT
                i_department,
                i_department_id,
                e_department_name
            FROM
                tr_department
            WHERE
                f_status = 't'
                AND (e_department_name ILIKE '%$cari%'
                OR i_department_id ILIKE '%$cari%')
            ORDER BY
                e_department_name ASC
        ", FALSE);
    }

    /** Get Data Level */
    public function get_level($cari)
    {
        return $this->db->query("SELECT
                i_level,
                e_level_name
            FROM
                tr_level
            WHERE
                f_status = 't'
                AND (e_level_name ILIKE '%$cari%')
                AND i_level <> 1
            ORDER BY
                e_level_name ASC
        ", FALSE);
    }

    /** Cek Apakah Data Sudah Ada Pas Simpan */
    public function cek($id)
    {
        return $this->db->query("SELECT 
                i_user_id
            FROM 
                tm_user 
            WHERE 
                lower(trim(i_user_id)) = lower(trim('$id'))
        ", FALSE);
    }

    /** Simpan Data */
    public function save($i_user_id, $e_user_name, /* $i_company,  */ $password, $f_pusat, $foto)
    {
        $query = $this->db->query("SELECT max(i_user)+1 AS id FROM tm_user", TRUE);
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

        $table = array(
            'i_user'          => $id,
            'i_user_id'       => strtolower($i_user_id),
            'e_user_password' => $password,
            'e_user_name'     => ucwords(strtolower($e_user_name)),
            'f_pusat'         => $f_pusat,
            'ava'             => $foto,
        );
        $this->db->insert('tm_user', $table);

        $jml = $this->input->post('jml', TRUE);
        if ($jml > 0) {
            for ($i = 1; $i <= $jml; $i++) {
                $department = $this->input->post('i_departemen' . $i, TRUE);
                if ($department != '' || $department != null) {
                    $level = $this->input->post('i_level' . $i . '[]', TRUE);
                    if (is_array($level) || is_object($level)) {
                        $this->db->where('i_user', $id);
                        $this->db->where('i_department', $department);
                        $this->db->delete('tm_user_cover');
                        foreach ($level as $ilevel) {
                            if ($ilevel != '') {
                                $tabledetail = array(
                                    'i_user'        => $id,
                                    'i_department'  => $department,
                                    'i_level'       => $ilevel,
                                );
                                $this->db->insert('tm_user_cover', $tabledetail);
                            }
                        }
                    }
                }
            }
        }

        $jmlx = $this->input->post('jmlx', TRUE);
        if ($jmlx > 0) {
            $this->db->where('i_user', $id);
            $this->db->delete('tm_user_company');
            for ($x = 1; $x <= $jmlx; $x++) {
                $company = $this->input->post('i_company' . $x, TRUE);
                if ($company != '' || $company != null) {
                    $tablecompany = array(
                        'i_company' => $company,
                        'i_user'    => $id,
                    );
                    $this->db->insert('tm_user_company', $tablecompany);

                    $this->db->where('i_user', $id);
                    $this->db->where('i_company', $company);
                    $this->db->delete('tm_user_area');
                    $f_checked = $this->input->post('f_checked' . $x, TRUE);
                    if ($f_checked == 'f') {
                        $area = $this->input->post('i_area' . $x . '[]', TRUE);
                        if (is_array($area) || is_object($area)) {
                            foreach ($area as $iarea) {
                                if ($iarea != '') {
                                    $tablearea = array(
                                        'i_user'     => $id,
                                        'i_company'  => $company,
                                        'i_area'     => $iarea,
                                    );
                                    $this->db->insert('tm_user_area', $tablearea);
                                }
                            }
                        }
                    } else {
                        $sql = $this->db->get_where('tr_area', ['f_area_active' => true, 'i_company' => $company]);
                        if ($sql->num_rows() > 0) {
                            foreach ($sql->result() as $key) {
                                $tablearea = array(
                                    'i_user'     => $id,
                                    'i_company'  => $company,
                                    'i_area'     => $key->i_area,
                                );
                                $this->db->insert('tm_user_area', $tablearea);
                            }
                        }
                    }

                    $this->db->where('i_user', $id);
                    $this->db->where('i_company', $company);
                    $this->db->delete('tm_user_kas_rv');
                    $i_rv_type = $this->input->post('i_rv_type' . $x . '[]', TRUE);
                    if (is_array($i_rv_type) || is_object($i_rv_type)) {
                        foreach ($i_rv_type as $rv_type) {
                            if ($rv_type != '') {
                                $tablearea = array(
                                    'i_rv_type'  => $rv_type,
                                    'i_company'  => $company,
                                    'i_user'     => $id,
                                );
                                $this->db->insert('tm_user_kas_rv', $tablearea);
                            }
                        }
                    }

                    $this->db->where('i_user', $id);
                    $this->db->where('i_company', $company);
                    $this->db->delete('tm_user_kas_pv');
                    $i_pv_type = $this->input->post('i_pv_type' . $x . '[]', TRUE);
                    if (is_array($i_pv_type) || is_object($i_pv_type)) {
                        foreach ($i_pv_type as $pv_type) {
                            if ($pv_type != '') {
                                $tablearea = array(
                                    'i_pv_type'  => $pv_type,
                                    'i_company'  => $company,
                                    'i_user'     => $id,
                                );
                                $this->db->insert('tm_user_kas_pv', $tablearea);
                            }
                        }
                    }

                    $this->db->where('i_user', $id);
                    $this->db->where('i_company', $company);
                    $this->db->delete('tm_user_store');
                    $i_store = $this->input->post('i_store' . $x . '[]', TRUE);
                    if (is_array($i_store) || is_object($i_store)) {
                        foreach ($i_store as $store) {
                            if ($store != '') {
                                $tablearea = array(
                                    'i_store'  => $store,
                                    'i_company'  => $company,
                                    'i_user'     => $id,
                                );
                                $this->db->insert('tm_user_store', $tablearea);
                            }
                        }
                    }
                }
            }
        }
    }

    /** Get Data Untuk Edit */
    public function getdata($id)
    {
        return $this->db->query("SELECT                 
                i_user ,
                i_user_id ,
                e_user_password ,
                e_user_name ,
                f_status ,
                d_user_entry ,
                d_user_update ,
                case when f_pusat ='t' then 'PUSAT' else 'DAERAH' end as f_pusat,
                ava
            FROM 
                tm_user
            WHERE
                i_user = '$id'
        ", FALSE);
    }

    /** Get Data Untuk Edit */
    public function getcompany($id)
    {
        return $this->db->query("
            SELECT
                a.i_company,
                b.i_company_id,
                b.e_company_name
            FROM
                tm_user_company a
            INNER JOIN tr_company b ON
                (b.i_company = a.i_company)
            WHERE
                i_user = '$id'
        ", FALSE);
    }

    /** Get Data Untuk Edit */
    public function getdetail($id)
    {
        return $this->db->query("
            SELECT
                a.i_department,
                b.e_department_name, 
                jsonb_agg(a.i_level||'|'||c.e_level_name) AS level
            FROM
                tm_user_cover a
            INNER JOIN tr_department b ON
                (b.i_department = a.i_department)
            INNER JOIN tr_level c ON
                (c.i_level = a.i_level)
            WHERE
                i_user = '$id'
            GROUP BY
                1,
                2
        ", FALSE);
    }

    /** Get Data Untuk Edit */
    public function getdetaill($id)
    {
        /* return $this->db->query("
            SELECT
                a.i_company,
                b.e_company_name,
                jsonb_agg(a.i_area || '|' || c.e_area_name) AS area
            FROM
                tm_user_area a
            RIGHT JOIN tr_company b ON
                (b.i_company = a.i_company)
            INNER JOIN tr_area c ON
                (c.i_area = a.i_area)
            WHERE a.i_user = '$id'
            GROUP BY
                1,
                2
        ", FALSE); */
        return $this->db->query("SELECT
                a.i_company,
                b.e_company_name,
                area,
                rv,
                pv,
                st
            FROM
                tm_user_company a
            INNER JOIN tr_company b ON
                (b.i_company = a.i_company)
            LEFT JOIN (
                SELECT
                    a.i_company,
                    a.i_user,
                    jsonb_agg(a.i_area || '|' || initcap(e_area_name)) AS area
                FROM
                    tm_user_area a
                INNER JOIN tr_area b ON
                    (b.i_area = a.i_area
                        AND a.i_company = b.i_company
                        AND a.i_user = $id)
                GROUP BY
                        1,
                        2) c ON
                (c.i_company = a.i_company
                    AND a.i_user = c.i_user)
            LEFT JOIN (
                SELECT
                    a.i_company,
                    a.i_user,
                    jsonb_agg(a.i_rv_type || '|' || initcap(e_rv_type_name)) AS rv
                FROM
                    tm_user_kas_rv a
                INNER JOIN tr_rv_type b ON
                    (b.i_rv_type = a.i_rv_type
                        AND a.i_company = b.i_company
                        AND i_user = $id)
                GROUP BY
                    1,
                    2) d ON
                (d.i_company = a.i_company
                    AND a.i_user = d.i_user)
            LEFT JOIN (
                SELECT
                    a.i_company,
                    a.i_user,
                    jsonb_agg(a.i_pv_type || '|' || initcap(e_pv_type_name)) AS pv
                FROM
                    tm_user_kas_pv a
                INNER JOIN tr_pv_type b ON
                    (b.i_pv_type = a.i_pv_type
                        AND a.i_company = b.i_company
                        AND i_user = $id)
                GROUP BY
                    1,
                    2) e ON
                (e.i_company = a.i_company
                    AND a.i_user = e.i_user)
            LEFT JOIN (
                SELECT
                    a.i_company,
                    a.i_user,
                    jsonb_agg(a.i_store || '|' || initcap(e_store_name)) AS st
                FROM
                    tm_user_store a
                INNER JOIN tr_store b ON
                    (b.i_store = a.i_store
                        AND a.i_company = b.i_company
                        AND i_user = $id)
                GROUP BY
                    1,
                    2) f ON
                (f.i_company = a.i_company
                    AND a.i_user = f.i_user)
            WHERE
                a.i_user = $id
        ", FALSE);
    }

    /** Cek Apakah Data Sudah Ada Pas Edit */
    public function cek_edit($i_user_id, $i_user_id_old)
    {
        return $this->db->query("SELECT 
                i_user_id
            FROM 
                tm_user 
            WHERE 
                trim(lower(i_user_id)) <> trim(lower('$i_user_id_old'))
                AND trim(lower(i_user_id)) = trim(lower('$i_user_id'))
        ", FALSE);
    }

    /** Update Data */
    public function update($i_user, $i_user_id, $e_user_name, $password, $f_pusat, $fotoup)
    {
        $table = array(
            'i_user_id'       => strtolower($i_user_id),
            'e_user_password' => $password,
            'e_user_name'     => ucwords(strtolower($e_user_name)),
            'd_user_update'   => current_datetime(),
            'f_pusat'         => $f_pusat,
            'ava'             => $fotoup,
        );
        $this->db->where('i_user', $i_user);
        $this->db->update('tm_user', $table);
        $jml = $this->input->post('jml', TRUE);
        if ($jml > 0) {
            $this->db->where('i_user', $i_user);
            /* $this->db->where('i_department', $department); */
            $this->db->delete('tm_user_cover');
            for ($i = 1; $i <= $jml; $i++) {
                $department = $this->input->post('i_departemen' . $i, TRUE);
                if ($department != '' || $department != null) {
                    $level = $this->input->post('i_level' . $i . '[]', TRUE);
                    if (is_array($level) || is_object($level)) {
                        foreach ($level as $ilevel) {
                            $tabledetail = array(
                                'i_user'        => $i_user,
                                'i_department'  => $department,
                                'i_level'       => $ilevel,
                            );
                            $this->db->insert('tm_user_cover', $tabledetail);
                        }
                    }
                }
            }
        }

        $jmlx = $this->input->post('jmlx', TRUE);
        if ($jmlx > 0) {
            $this->db->where('i_user', $i_user);
            $this->db->delete('tm_user_company');
            $this->db->where('i_user', $i_user);
            $this->db->delete('tm_user_area');
            $this->db->where('i_user', $i_user);
            $this->db->delete('tm_user_kas_rv');
            $this->db->where('i_user', $i_user);
            $this->db->delete('tm_user_kas_pv');
            $this->db->where('i_user', $i_user);
            $this->db->delete('tm_user_store');
            for ($x = 1; $x <= $jmlx; $x++) {
                $company = $this->input->post('i_company' . $x, TRUE);
                if ($company != '' || $company != null) {
                    $tablecompany = array(
                        'i_company' => $company,
                        'i_user'    => $i_user,
                    );
                    $this->db->insert('tm_user_company', $tablecompany);

                    $f_checked = $this->input->post('f_checked' . $x, TRUE);
                    if ($f_checked == 'f') {
                        $area = $this->input->post('i_area' . $x . '[]', TRUE);
                        if (is_array($area) || is_object($area)) {
                            foreach ($area as $iarea) {
                                $tablearea = array(
                                    'i_user'     => $i_user,
                                    'i_company'  => $company,
                                    'i_area'     => $iarea,
                                );
                                $this->db->insert('tm_user_area', $tablearea);
                            }
                        }
                    } else {
                        $sql = $this->db->get_where('tr_area', ['f_area_active' => true, 'i_company' => $company]);
                        if ($sql->num_rows() > 0) {
                            foreach ($sql->result() as $key) {
                                $tablearea = array(
                                    'i_user'     => $i_user,
                                    'i_company'  => $company,
                                    'i_area'     => $key->i_area,
                                );
                                $this->db->insert('tm_user_area', $tablearea);
                            }
                        }
                    }

                    $i_rv_type = $this->input->post('i_rv_type' . $x . '[]', TRUE);
                    if (is_array($i_rv_type) || is_object($i_rv_type)) {
                        foreach ($i_rv_type as $rv_type) {
                            if ($rv_type != '') {
                                $tablearea = array(
                                    'i_rv_type'  => $rv_type,
                                    'i_company'  => $company,
                                    'i_user'     => $i_user,
                                );
                                $this->db->insert('tm_user_kas_rv', $tablearea);
                            }
                        }
                    }

                    $i_pv_type = $this->input->post('i_pv_type' . $x . '[]', TRUE);
                    if (is_array($i_pv_type) || is_object($i_pv_type)) {
                        foreach ($i_pv_type as $pv_type) {
                            if ($pv_type != '') {
                                $tablearea = array(
                                    'i_pv_type'  => $pv_type,
                                    'i_company'  => $company,
                                    'i_user'     => $i_user,
                                );
                                $this->db->insert('tm_user_kas_pv', $tablearea);
                            }
                        }
                    }

                    $i_store = $this->input->post('i_store' . $x . '[]', TRUE);
                    if (is_array($i_store) || is_object($i_store)) {
                        foreach ($i_store as $store) {
                            if ($store != '') {
                                $tablearea = array(
                                    'i_store'  => $store,
                                    'i_company'  => $company,
                                    'i_user'     => $i_user,
                                );
                                $this->db->insert('tm_user_store', $tablearea);
                            }
                        }
                    }
                }
            }
        }
    }
}

/* End of file Mmaster.php */
