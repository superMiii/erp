<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mrrkhapprove extends CI_Model
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
                a.i_rrkh AS id,
                a.d_rrkh,
                to_char(a.d_rrkh, 'YYYYMM') as i_periode,
                b.e_area_name,
                c.e_salesman_name,
                a.f_rrkh_cancel AS f_status,
                '$dfrom' AS dfrom,
                '$dto' AS dto,
                '$i_area' AS i_area
            FROM
                tm_rrkh a
            INNER JOIN tr_area b ON
                (b.i_area = a.i_area)
            INNER JOIN tr_salesman c ON
                (c.i_salesman = a.i_salesman)
            INNER JOIN tm_user_area d ON
                (d.i_area = a.i_area)
            WHERE
                a.d_rrkh BETWEEN '$dfrom' AND '$dto'
                AND a.i_company = '$this->i_company'
                AND d.i_user = '$this->i_user'
                AND f_rrkh_cancel = 'f'
                AND d_approve ISNULL
                $area
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
            $data       = '';
            // $data      .= "<a href='" . base_url() . $this->folder . '/view/' . encrypt_url($id) . '/' . encrypt_url($dfrom) . '/' . encrypt_url($dto) . "' title='View Data'><i class='fa fa-eye fa-lg warning darken-4 mr-1'></i></a>";
            if ($i_periode >= get_periode()) {
                if (check_role($this->id_menu, 3) && $f_status == 'f') {
                    $data      .= "<a href='" . base_url() . $this->folder . '/detail_approve/' . encrypt_url($id) . '/' . encrypt_url($dfrom) . '/' . encrypt_url($dto) . '/' . encrypt_url($i_area) . "' title='Approve Data'><i class='fa fa-check-circle-o success darken-4 fa-lg mr-1'></i></a>";
                }
            }
            return $data;
        });
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('i_area');
        $datatables->hide('i_periode');
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
    public function get_data($id)
    {
        return $this->db->query("SELECT 
                a.*,
                b.i_area_id,
                b.e_area_name,
                e.i_salesman_id,
                e.e_salesman_name
            FROM 
                tm_rrkh a
            INNER JOIN tr_area b ON 
                (b.i_area = a.i_area)
            INNER JOIN tr_salesman e ON 
                (e.i_salesman = a.i_salesman)
            WHERE i_rrkh = '$id'
        ", FALSE);
    }

    /** Get Data Untuk Edit Detail */
    public function get_data_detail($id)
    {
        return $this->db->query("SELECT
                a.*,
                b.i_customer_id,
                b.e_customer_name,
                c.e_city_name,
                d.e_kunjungan_type_name
            FROM
                tm_rrkh_item a
            INNER JOIN tr_customer b ON
                (b.i_customer = a.i_customer)
            INNER JOIN tr_city c ON
                (c.i_city = a.i_city)
            INNER JOIN tr_kunjungan_type d ON
                (d.i_kunjungan_type = a.i_kunjungan_type)
            WHERE
                a.i_rrkh = '$id'
            ORDER BY
                a.n_item_no ASC
        ", FALSE);
    }

    /** Approve Data */
    public function approve($id)
    {
        $table = array(
            'i_approve' => $this->i_user,
            'd_approve' => current_date(),
        );
        $this->db->where('i_rrkh', $id);
        $this->db->update('tm_rrkh', $table);
    }
}

/* End of file Mmaster.php */
