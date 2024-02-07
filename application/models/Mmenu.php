<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmenu extends CI_Model
{

    /** List Datatable */
    public function serverside()
    {
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("SELECT 0 AS no, 
        i_menu,  e_menu, i_parent, n_urut, e_folder 
        FROM tr_menu ORDER BY n_urut", FALSE);

        /** Cek Hak Akses, Apakah User Bisa Edit */
        if (check_role($this->i_menu, 3)) {
            $datatables->add('action', function ($data) {
                $id         = trim($data['i_menu']);
                $data       = '';
                $data      .= "<a href='" . base_url() . $this->folder . '/edit/' . encrypt_url($id) . "' title='Edit Data'><i class='fa fa-check-circle-o success darken-4 fa-lg mr-1'></i></a>";
                return $data;
            });
        }
        return $datatables->generate();
    }

    /** Cek Apakah Data Sudah Ada Pas Simpan */
    public function get_menu($cari)
    {
        return $this->db->query("SELECT 
                i_menu,
                e_menu
            FROM 
                tr_menu 
            WHERE 
                (e_menu ILIKE '%$cari%')
                AND e_folder = '#'
            ORDER BY i_menu
        ", FALSE);
    }

    /** Cek Apakah Data Sudah Ada Pas Simpan */
    public function get_user_power($cari)
    {
        return $this->db->query("
            SELECT 
                i_power,
                e_power_name
            FROM 
                tr_user_power 
            WHERE 
                (e_power_name ILIKE '%$cari%')
            ORDER BY i_power
        ", FALSE);
    }

    /** Cek Apakah Data Sudah Ada Pas Simpan */
    public function cek($id)
    {
        return $this->db->query("
            SELECT 
                i_menu
            FROM 
                tr_menu 
            WHERE 
                i_menu = $id
        ", FALSE);
    }

    /** Simpan Data */
    public function save($iparent, $idmenu, $emenu, $nurut, $efolder, $icon, $ipower)
    {
        $table = array(
            'i_menu'    => $idmenu,
            'e_menu'    => $emenu,
            'i_parent'  => $iparent,
            'n_urut'    => $nurut,
            'e_folder'  => $efolder,
            'icon'      => $icon,
        );
        $this->db->insert('tr_menu', $table);

        if (is_array($ipower) || is_object($ipower)) {
            foreach ($ipower as $i_power) {
                $detail = array(
                    'i_menu'        => $idmenu,
                    'i_power'       => $i_power,
                    'i_department'  => 1,
                    'i_level'       => 1,
                );
                $this->db->insert('tm_user_role', $detail);
            }
        }
    }

    /** Get Data Untuk Edit */
    public function getdata($id)
    {
        return $this->db->query("
            SELECT 
                a.*,
                b.e_menu AS menu_parent
            FROM 
                tr_menu a
            LEFT JOIN tr_menu b ON (b.i_menu = a.i_parent)
            WHERE
                a.i_menu = '$id'
        ", FALSE);
    }

    /** Get Data Power Edit */
    public function get_power($id)
    {
        return $this->db->query("
            SELECT
                a.*,
                b.selek
            FROM
                tr_user_power a
            LEFT JOIN (
                    SELECT
                        i_power,
                        'selected' AS selek
                    FROM
                        tm_user_role
                    WHERE
                        i_menu = '$id'
                        AND i_level = '1'
                        AND i_department = '1'
                ) b ON
                (
                    b.i_power = a.i_power
                )
        ", FALSE);
    }

    /** Cek Apakah Data Sudah Ada Pas Edit */
    public function cek_edit($idmenu, $idmenuold)
    {
        return $this->db->query("
            SELECT 
                i_menu
            FROM 
                tr_menu 
            WHERE 
                i_menu <> $idmenuold
                AND i_menu = $idmenu
        ", FALSE);
    }

    /** Update Data */
    public function update($iparent, $idmenu, $emenu, $nurut, $efolder, $icon, $idmenuold, $ipower)
    {
        $table = array(
            'i_menu'    => $idmenu,
            'e_menu'    => $emenu,
            'i_parent'  => $iparent,
            'n_urut'    => $nurut,
            'e_folder'  => $efolder,
            'icon'      => $icon,
        );
        $this->db->where('i_menu', $idmenuold);
        $this->db->update('tr_menu', $table);
        if (is_array($ipower) || is_object($ipower)) {
            $this->db->where('i_menu', $idmenuold);
            $this->db->where('i_level', 1);
            $this->db->where('i_department', 1);
            $this->db->delete('tm_user_role');
            foreach ($ipower as $i_power) {
                $detail = array(
                    'i_menu'        => $idmenu,
                    'i_power'       => $i_power,
                    'i_department'  => 1,
                    'i_level'       => 1,
                );
                $this->db->insert('tm_user_role', $detail);
            }
        }
    }
}

/* End of file Mmaster.php */
