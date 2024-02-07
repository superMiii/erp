<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Makses extends CI_Model
{

    /** List Datatable */
    public function serverside()
    {
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("SELECT
                DISTINCT 
                a.i_department,
                a.i_level,
                b.e_department_name,
                c.e_level_name
            FROM
                tm_user_role a
            JOIN tr_department b ON
                (b.i_department = a.i_department)
            JOIN tr_level c ON
                (c.i_level = a.i_level)
            WHERE a.i_department <> 1 AND a.i_level <> 1
            ORDER BY
                3,
                4
        ", FALSE);

        /** Cek Hak Akses, Apakah User Bisa Edit */
        if (check_role($this->i_menu, 3) || check_role($this->i_menu, 4)) {
            $datatables->add('action', function ($data) {
                $i_department = trim($data['i_department']);
                $i_level      = trim($data['i_level']);
                $id           = $i_department . '|' . $i_level;
                $data       = '';
                if (check_role($this->i_menu, 3)) {
                    $data      .= "<a href='" . base_url() . $this->folder . '/edit/' . encrypt_url($i_department) . '/' . encrypt_url($i_level) . "' title='Edit Data'><i class='fa fa-check-circle-o success darken-4 fa-lg mr-1'></i></a>";
                }
                if (check_role($this->i_menu, 4)) {
                    $data      .= "<a href='#' onclick='sweetdelete(\"" . $this->folder . "\",\"" . $id . "\");' title='Hapus Data'><i class='fa fa-trash fa-lg red darken-4'></i></a>";
                }
                return $data;
            });
        }
        $datatables->hide('i_level');
        return $datatables->generate();
    }

    /** Get Data Menu */
    public function get_menu()
    {
        return $this->db->query("SELECT
                a.i_menu,
                a.e_menu,
                b.i_power,
                b.e_power_name
            FROM
                tr_menu a
            INNER JOIN tm_user_role c ON (c.i_menu = a.i_menu)
            INNER JOIN tr_user_power b ON (c.i_power = b.i_power)
            WHERE
                e_folder = '#'
                AND b.i_power = '2'
                AND c.i_level = 1
                AND c.i_department = 1
            ORDER BY
                a.i_menu,
                n_urut                
        ", FALSE);
    }

    /** Get Data Sub Menu */
    public function get_sub_menu()
    {
        return $this->db->query("SELECT
                a.i_menu,
                a.e_menu,
                b.i_power,
                b.e_power_name
            FROM
                tr_menu a
            INNER JOIN tm_user_role c ON (c.i_menu = a.i_menu)
            INNER JOIN tr_user_power b ON (c.i_power = b.i_power)
            WHERE
                e_folder <> '#'
                AND c.i_level = 1
                AND c.i_department = 1
            ORDER BY
                a.i_menu,
                n_urut                
        ", FALSE);
    }

    /** Simpan Data */
    public function save($idepartment, $ilevel, $imenu, $isubmenu)
    {
        if ((is_array($imenu) || is_object($imenu)) || (is_array($isubmenu) || is_object($isubmenu))) {
            $this->db->where('i_level', $ilevel);
            $this->db->where('i_department', $idepartment);
            $this->db->delete('tm_user_role');
        }

        if (is_array($imenu) || is_object($imenu)) {
            foreach ($imenu as $menu) {
                $i_menu  = explode("|", $menu)[0];
                $i_power = explode("|", $menu)[1];
                $this->db->query("INSERT INTO tm_user_role (i_menu, i_power, i_department, i_level) 
                    VALUES ($i_menu, $i_power, $idepartment, $ilevel)
                    ON CONFLICT (i_menu, i_department, i_level, i_power) DO UPDATE 
                    SET i_menu = excluded.i_menu, 
                        i_department = excluded.i_department, 
                        i_level = excluded.i_level, 
                        i_power = excluded.i_power
                ", FALSE);
            }
        }

        if (is_array($isubmenu) || is_object($isubmenu)) {
            foreach ($isubmenu as $submenu) {
                $i_menu  = explode("|", $submenu)[0];
                $i_power = explode("|", $submenu)[1];
                $this->db->query("INSERT INTO tm_user_role (i_menu, i_power, i_department, i_level) 
                    VALUES ($i_menu, $i_power, $idepartment, $ilevel)
                    ON CONFLICT (i_menu, i_department, i_level, i_power) DO UPDATE 
                    SET i_menu = excluded.i_menu, 
                        i_department = excluded.i_department, 
                        i_level = excluded.i_level, 
                        i_power = excluded.i_power
                ", FALSE);
            }
        }
    }

    /** Get Data Menu */
    public function get_menu_edit($i_department, $i_level)
    {
        return $this->db->query("SELECT
                a.i_menu,
                a.e_menu,
                b.i_power,
                b.e_power_name,
                CASE
                    WHEN c.i_menu = a.i_menu
                    AND b.i_power = c.i_power THEN 'selected'
                    ELSE ''
                END AS selected
            FROM
                tr_menu a
            INNER JOIN tm_user_role d ON (d.i_menu = a.i_menu)
            INNER JOIN tr_user_power b ON (b.i_power = d.i_power)
            LEFT JOIN (
                SELECT
                    i_menu,
                    i_power
                FROM
                    tm_user_role
                WHERE
                    i_department = $i_department
                    AND i_level = $i_level
                ) c ON
                (
                    c.i_menu = a.i_menu
                    AND b.i_power = c.i_power
                )
            WHERE
                e_folder = '#'
                AND b.i_power = '2'
                AND d.i_level = 1
                AND d.i_department = 1
            ORDER BY
                a.i_menu,
                n_urut              
        ", FALSE);
    }

    /** Get Data Sub Menu */
    public function get_sub_menu_edit($i_department, $i_level)
    {
        return $this->db->query("SELECT
                a.i_menu,
                a.e_menu,
                b.i_power,
                b.e_power_name,
                CASE
                    WHEN c.i_menu = a.i_menu
                    AND b.i_power = c.i_power THEN 'selected'
                    ELSE ''
                END AS selected
            FROM
                tr_menu a
            INNER JOIN tm_user_role d ON (d.i_menu = a.i_menu)
            INNER JOIN tr_user_power b ON (b.i_power = d.i_power)
            LEFT JOIN (
                SELECT
                    i_menu,
                    i_power
                FROM
                    tm_user_role
                WHERE
                    i_department = $i_department
                    AND i_level = $i_level
                ) c ON
                (
                    c.i_menu = a.i_menu
                    AND b.i_power = c.i_power
                )
            WHERE
                e_folder <> '#'
                AND d.i_level = 1
                AND d.i_department = 1
            ORDER BY
                a.i_menu,
                n_urut               
        ", FALSE);
    }

    public function delete($id)
    {
        $i_department   = explode("|", $id)[0];
        $i_level        = explode("|", $id)[1];
        $this->db->where('i_department', $i_department);
        $this->db->where('i_level', $i_level);
        $this->db->delete('tm_user_role');
    }
}

/* End of file Mmaster.php */
