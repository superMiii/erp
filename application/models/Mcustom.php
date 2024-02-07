<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/* use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter; */

class Mcustom extends CI_Model {

    public function get_company($i_user)
    {
        return $this->db->query("
            select b.i_company , b.e_company_name, b.f_company_plusppn, b.n_ppn, b.v_meterai, b.v_meterai_limit, b.f_plus_meterai, b.e_color from tm_user_company a 
            inner join tr_company b on (a.i_company = b.i_company)
            where i_user = $i_user and b.f_company_active  = true
            order by 2 ASC
        ", FALSE);
    }

    public function get_department($i_user)
    {
        return $this->db->query("
            with cte as (
                select distinct i_department from tm_user_cover where i_user = '$i_user'
            )
            select i_department, e_department_name from tr_department where i_department in (select * from cte) and f_status = 't'
            ORDER BY 2  ASC
        ", FALSE);
    }

     public function get_level($i_user, $i_department)
    {
        return $this->db->query("
            with cte as (
                select distinct i_level from tm_user_cover where i_user = '$i_user' and i_department = '$i_department'
            )
            select i_level , e_level_name from tr_level where i_level in (select * from cte) and f_status = 't'
            ORDER BY 2  ASC
        ", FALSE);
    }
    
    public function get_menu($i_user, $i_department, $i_level)
    {
        return $this->db->query("
            SELECT
                DISTINCT a.*
            FROM
                tr_menu a
            INNER JOIN tm_user_role b on (a.i_menu = b.i_menu)
            WHERE b.i_department = '$i_department' 
                AND b.i_level = '$i_level'
                AND a.i_parent = '0'
            ORDER BY
                4,
                1
        ", FALSE);
    }

    public function get_sub_menu($i_department, $i_level,$i_menu)
    {
        return $this->db->query("
            SELECT
                DISTINCT a.*
            FROM
                tr_menu a
            INNER JOIN tm_user_role b on (a.i_menu = b.i_menu)
            WHERE b.i_department = '$i_department' 
                AND b.i_level = '$i_level' 
                AND a.i_parent = '$i_menu'
            ORDER BY
                4,
                1
        ", FALSE);
    }

    public function cek_role($i_user,$i_menu,$id)
    {
        $i_level = $this->session->userdata('i_level');
        $i_department = $this->session->userdata('i_department');
        return $this->db->query("
            SELECT
                DISTINCT a.*
            FROM
                tr_menu a
            INNER JOIN tm_user_role b ON
                (a.i_menu = b.i_menu)
            WHERE
                b.i_level = '$i_level'
                AND b.i_department = '$i_department'
                AND a.i_menu = '$i_menu'
                AND b.i_power = '$id'
            ORDER BY
                4,
                1
        ", FALSE);
    }
}

/* End of file Mmaster.php */
