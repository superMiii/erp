<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Minfogiro extends CI_Model
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

        if ($i_area != 'NA') {
            $area = "AND a.i_area = '$i_area' ";
        } else {
            $area = "";
        }

        $dfrom  = date('Y-m-d', strtotime($dfrom));
        $dto    = date('Y-m-d', strtotime($dto));

        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("SELECT
                a.i_giro as id,
                a.i_giro_id,
                to_char(a.d_giro, 'DD Month YYYY') as d_giro,
                b.i_area_id || ' - ' || initcap(b.e_area_name) as e_area_name,
                c.i_customer_id as codee,
                c.e_customer_name as e_customer,
                a.d_giro_terima as terima,
                a.d_giro_cair as cair,
                a.d_giro_tolak as tolak,
                a.d_giro_duedate as tempo,
                a.e_giro_bank as bank,
                a.v_jumlah::money as jml,
                a.e_giro_description,
                '$dfrom' AS dfrom,
                '$dto' AS dto
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
                and f_giro_batal = 'f'
                AND a.d_giro BETWEEN '$dfrom' AND '$dto'
                $area
            ORDER BY
                a.i_giro DESC 
        ", FALSE);

        // $datatables->edit('f_status', function ($data) {
        //     if ($data['f_status'] == 't') {
        //         $status = $this->lang->line('Tidak Aktif');
        //         $color  = 'red';
        //     } else {
        //         $color  = 'teal';
        //         $status = $this->lang->line('Aktif');
        //     }
        //     $data = "<span class='badge bg-" . $color . " badge-pill'>" . $status . "</span>";
        //     return $data;
        // });

        // /** Cek Hak Akses, Apakah User Bisa Edit */
        // $datatables->add('action', function ($data) {
        //     $id         = trim($data['id']);
        //     $f_status   = $data['f_status'];
        //     $dfrom      = $data['dfrom'];
        //     $dto        = $data['dto'];
        //     $data       = '';
        //     $data      .= "<a href='" . base_url() . $this->folder . '/view/' . encrypt_url($id) . '/' . encrypt_url($dfrom) . '/' . encrypt_url($dto) . "' title='View Data'><i class='fa fa-eye fa-lg warning darken-4 mr-1'></i></a>";
        //     if (check_role($this->id_menu, 3) && $f_status == 'f') {
        //         $data      .= "<a href='" . base_url() . $this->folder . '/edit/' . encrypt_url($id) . "' title='Edit Data'><i class='fa fa-pencil success darken-4 fa-lg mr-1'></i></a>";
        //     }
        //     if (check_role($this->id_menu, 5) && $f_status == 'f') {
        //         $data      .= "<a href='#' onclick='openLink(\"" . $this->folder . "\",\"" . encrypt_url($id) . "\"); return false;' title='Print Data'><i class='fa fa-print fa-lg blue darken-4 mr-1'></i></a>";
        //     }
        //     if (check_role($this->id_menu, 4) && $f_status == 'f') {
        //         $data      .= "<a href='#' onclick='sweetdeletev2(\"" . $this->folder . "\",\"" . $id . "\");' title='Delete Data'><i class='fa fa-trash fa-lg red darken-4'></i></a>";
        //     }
        //     return $data;
        // });
        $datatables->hide('dfrom');
        $datatables->hide('dto');
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

    /** Get Data Untuk Edit */
    public function get_data($dfrom, $dto, $i_area)
    {
        if ($i_area != 'NA') {
            $area = "AND a.i_area = '$i_area' ";
        } else {
            $area = "";
        }
        $dfrom  = date('Y-m-d', strtotime($dfrom));
        $dto    = date('Y-m-d', strtotime($dto));
        return $this->db->query("SELECT 
                a.i_giro as id,
                a.i_giro_id,
                to_char(a.d_giro, 'DD Month YYYY') as d_giro,
                b.i_area_id || ' - ' || initcap(b.e_area_name) as e_area_name,
                c.i_customer_id as codee,
                c.e_customer_name as e_customer,
                a.d_giro_terima as terima,
                a.d_giro_cair as cair,
                a.d_giro_tolak as tolak,
                a.d_giro_duedate as tempo,
                a.e_giro_bank as bank,
                a.v_jumlah as jml,
                a.e_giro_description,
                '$dfrom' AS dfrom,
                '$dto' AS dto
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
                and f_giro_batal = 'f'
                AND a.d_giro BETWEEN '$dfrom' AND '$dto'
                $area
            ORDER BY
                a.i_giro DESC 
        ", FALSE);
    }
}

/* End of file Mmaster.php */
