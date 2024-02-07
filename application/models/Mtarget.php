<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mtarget extends CI_Model
{

    /** List Datatable */
    public function serverside()
    {
        $month = $this->input->post('month', TRUE);
        if ($month == '') {
            $month = $this->uri->segment(3);
        }

        $year = $this->input->post('year', TRUE);
        if ($year == '') {
            $year = $this->uri->segment(4);
        }
        $i_periode = $year . $month;
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("SELECT
                a.i_area AS id,
                a.i_periode,
                b.e_area_name,
                sum(a.v_target)::money AS v_target
            FROM
                tm_target_item a
            INNER JOIN tr_area b ON
                (b.i_area = a.i_area)
            INNER JOIN tm_user_area c ON
                (c.i_area = a.i_area)
            WHERE
                a.i_company = '$this->i_company'
                AND a.i_periode = '$i_periode'
                AND c.i_user = '$this->i_user'
            GROUP BY 
                1,2,3
            ORDER BY
                a.i_periode ASC
        ", FALSE);

        /** Cek Hak Akses, Apakah User Bisa Edit */
        $datatables->add('action', function ($data) {
            $id         = trim($data['id']);
            $i_periode  = trim($data['i_periode']);
            $data       = '';
            $data      .= "<a href='" . base_url() . $this->folder . '/view/' . encrypt_url($id) . '/' . encrypt_url($i_periode) . "' title='View Data'><i class='fa fa-eye fa-lg warning darken-4 mr-1'></i></a>";
            if ($i_periode >= get_periode()) {
                if (check_role($this->i_menu, 3)) {
                    $data      .= "<a href='" . base_url() . $this->folder . '/edit/' . encrypt_url($id) . '/' . encrypt_url($i_periode) . "' title='Edit Data'><i class='fa fa-pencil-square  success darken-4 fa-lg mr-1'></i></a>";
                }
            }
            return $data;
        });
        return $datatables->generate();
    }

    /** Get Area */
    public function get_area_user($cari)
    {
        return $this->db->query("SELECT 
                DISTINCT
                a.i_area, 
                i_area_id, 
                e_area_name 
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

    /** Get Data City */
    public function get_city($cari, $i_area)
    {
        return $this->db->query("SELECT 
                i_city,
                i_city_id,
                e_city_name
            FROM 
                tr_city
            WHERE 
                (e_city_name ILIKE '%$cari%')
                AND f_city_active = 't'
                AND i_area = '$i_area'
            ORDER BY 
                e_city_name
        ", FALSE);
    }

    /** Get Data Salesman */
    public function get_salesman($cari)
    {
        return $this->db->query("SELECT
                i_salesman,
                e_salesman_name
            FROM
                tr_salesman
            WHERE
                f_salesman_active = 't'
                AND (e_salesman_name ILIKE '%$cari%')
                AND i_company = '$this->i_company'
            ORDER BY
                e_salesman_name ASC
        ", FALSE);
    }

    /** Simpan Data */
    public function save()
    {
        if (is_array($this->input->post('i_city')) || is_object($this->input->post('i_city'))) {
            $i_area = $this->input->post('i_area');
            $i_periode = $this->input->post('year') . $this->input->post('month');
            $i = 0;
            $this->db->query("DELETE FROM tm_target_item WHERE i_company = '$this->i_company' AND i_periode = '$i_periode' AND i_area = '$i_area' ", FALSE);
            foreach ($this->input->post('i_city') as $i_city) {
                $i_salesman = $this->input->post('i_salesman')[$i];
                $v_target = str_replace(",", "", $this->input->post('v_target_city')[$i]);
                $this->db->query("INSERT INTO tm_target_item (i_company, i_periode, i_area, i_city, i_salesman, v_target, d_entry) 
                    VALUES ($this->i_company, '$i_periode', '$i_area', $i_city, $i_salesman, $v_target, now())
                    ON CONFLICT (i_company, i_periode, i_area, i_salesman, i_city) DO UPDATE 
                    SET v_target = excluded.v_target;
                ");
                $i++;
            }
        } else {
            die;
        }
    }

    /** Get Data Untuk Edit */
    public function getdata($id)
    {
        return $this->db->query("SELECT i_area, i_area_id, e_area_name FROM tr_area WHERE i_area = '$id'", FALSE);
    }

    /** Get Data Untuk Edit */
    public function getdetail($id, $i_periode)
    {
        return $this->db->query("SELECT
                *,
                b.e_city_name,
                c.e_salesman_name
            FROM
                tm_target_item a
            INNER JOIN tr_city b ON
                (b.i_city = a.i_city)
            INNER JOIN tr_salesman c ON
                (c.i_salesman = a.i_salesman)
            WHERE
                a.i_area = '$id'
                AND i_periode = '$i_periode'
                AND a.i_company = '$this->i_company'
        ", FALSE);
    }

    /** Simpan Data */
    public function update()
    {
        if (is_array($this->input->post('i_city')) || is_object($this->input->post('i_city'))) {
            $i_area = $this->input->post('i_area');
            $i_area_old = $this->input->post('i_area_old');
            $i_periode = $this->input->post('year') . $this->input->post('month');
            $i = 0;
            $this->db->query("DELETE FROM tm_target_item WHERE i_company = '$this->i_company' AND i_periode = '$i_periode' AND i_area in( '$i_area_old','$i_area') ", FALSE);
            foreach ($this->input->post('i_city') as $i_city) {
                $i_salesman = $this->input->post('i_salesman')[$i];
                $v_target = str_replace(",", "", $this->input->post('v_target_city')[$i]);
                $this->db->query("INSERT INTO tm_target_item (i_company, i_periode, i_area, i_city, i_salesman, v_target, d_entry) 
                    VALUES ($this->i_company, '$i_periode', '$i_area', $i_city, $i_salesman, $v_target, now())
                    ON CONFLICT (i_company, i_periode, i_area, i_salesman, i_city) DO UPDATE 
                    SET v_target = excluded.v_target;
                ");
                $i++;
            }
        } else {
            die;
        }
    }
}

/* End of file Mmaster.php */
