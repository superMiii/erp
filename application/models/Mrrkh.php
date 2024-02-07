<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mrrkh extends CI_Model
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
                a.d_approve,
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
                $area
        ", FALSE);

        $datatables->edit('f_status', function ($data) {
            $dapprove = $data['d_approve'];
            if ($data['f_status'] == 't') {
                $status = $this->lang->line('Tidak Aktif');
                $color  = 'red';
            } else {
                if ($dapprove == null) {
                    $color  = 'warning';
                    $status = $this->lang->line('Menunggu Persetujuan');
                } else {
                    $color  = 'teal';
                    $status = $this->lang->line('Sudah Disetujui');
                }
            }
            $data = "<span class='badge bg-" . $color . " bg-darken-3 badge-pill'>" . $status . "</span>";
            return $data;
        });

        /** Cek Hak Akses, Apakah User Bisa Edit */
        $datatables->add('action', function ($data) {
            $id         = trim($data['id']);
            $f_status   = $data['f_status'];
            $dfrom      = $data['dfrom'];
            $dto        = $data['dto'];
            $i_area     = $data['i_area'];
            $dapprove   = $data['d_approve'];
            $i_periode  = $data['i_periode'];
            $data       = '';
            $data      .= "<a href='" . base_url() . $this->folder . '/view/' . encrypt_url($id) . '/' . encrypt_url($dfrom) . '/' . encrypt_url($dto) . '/' . encrypt_url($i_area) . "' title='View Data'><i class='fa fa-eye fa-lg warning darken-4 mr-1'></i></a>";
            if ($i_periode >= get_periode()) {
                if (check_role($this->id_menu, 3) && $f_status == 'f' && $dapprove == null) {
                    $data      .= "<a href='" . base_url() . $this->folder . '/edit/' . encrypt_url($id)  . '/' .  encrypt_url($dfrom) . '/' . encrypt_url($dto) . '/' . encrypt_url($i_area) . "' title='Edit Data'><i class='fa fa-pencil-square success darken-4 fa-lg mr-1'></i></a>";
                }
                if (check_role($this->id_menu, 4) && $f_status == 'f' && $dapprove == null) {
                    $data      .= "<a href='#' onclick='sweetdeletev33(\"" . $this->folder . "\",\"" . $id . "\",\"" . encrypt_url($dfrom)  . "\",\"" . encrypt_url($dto)  . '/' . encrypt_url($i_area) . "\");' title='Delete Data'><i class='fa fa-trash fa-lg red darken-4'></i></a>";
                }
            }
            return $data;
        });
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('i_area');
        $datatables->hide('d_approve');
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

    /** Get Salesman */
    public function get_salesman($cari)
    {
        return $this->db->query("SELECT
                a.i_salesman,
                a.i_salesman_id ,
                a.e_salesman_name
            FROM
                tr_salesman a
            INNER JOIN tr_salesman_areacover b ON
                (a.i_salesman = b.i_salesman)
            WHERE
                (a.e_salesman_name ILIKE '%$cari%'
                    OR a.i_salesman_id ILIKE '%$cari%')
                AND a.i_company = '$this->i_company'
                AND f_salesman_active = TRUE
            GROUP BY
                1
            ORDER BY
                3 ASC
        ", FALSE);
    }

    /** Get Customer */
    public function get_customer($cari, $i_area)
    {
        return $this->db->query("SELECT 
                i_customer, i_customer_id , e_customer_name
            FROM 
                tr_customer
            WHERE 
                (e_customer_name ILIKE '%$cari%' OR i_customer_id ILIKE '%$cari%')
                AND i_company = '$this->i_company' 
                AND f_customer_active = 'true' 
                AND i_area = '$i_area'
            ORDER BY 3 ASC
        ", FALSE);
    }

    /** Get Nota Detail */
    public function get_detail_customer($id)
    {
        return $this->db->query("SELECT
                a.i_city,
                e_city_name
            FROM
                tr_customer a
            INNER JOIN tr_city b ON 
                (b.i_city = a.i_city)
            WHERE
                i_customer = '$id'
        ", FALSE);
    }

    /** Get Type Kunjungan */
    public function get_kunjungan($cari)
    {
        return $this->db->query("SELECT
                i_kunjungan_type,
                e_kunjungan_type_name
            FROM
                tr_kunjungan_type
            WHERE
                f_kunjungan_type_active = 't'
                AND e_kunjungan_type_name ILIKE '%$cari%'
            ORDER BY
                2
        ", FALSE);
    }

    /** Cek Apakah Data Sudah Ada */
    public function cek_code()
    {
        $d_document = $this->input->post('d_document');
        $i_area = $this->input->post('i_area');
        $i_salesman = $this->input->post('i_salesman');
        return $this->db->query("SELECT 
                d_rrkh
            FROM 
                tm_rrkh 
            WHERE 
                d_rrkh = '$d_document'
                AND i_area = '$i_area'
                AND i_salesman = '$i_salesman'
                AND i_company = '$this->i_company'
        ", FALSE);
    }

    /** Simpan Data */
    public function save()
    {
        $query = $this->db->query("SELECT max(i_rrkh)+1 AS id FROM tm_rrkh", TRUE);
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
            'i_company'     => $this->i_company,
            'i_rrkh'        => $id,
            'i_area'        => $this->input->post('i_area'),
            'i_salesman'    => $this->input->post('i_salesman'),
            'd_rrkh'        => $this->input->post('d_document'),
            'd_receive'     => $this->input->post('d_receive'),
            'd_rrkh_entry'  => current_datetime(),
        );
        $this->db->insert('tm_rrkh', $table);
        if (is_array($this->input->post('i_customer')) || is_object($this->input->post('i_customer'))) {
            $i = 0;
            foreach ($this->input->post('i_customer') as $i_customer) {
                if ($i_customer != '' || $i_customer != null) {
                    if (!empty($this->input->post('f_kunjungan_realisasi')[$i])) {
                        $f_kunjungan_realisasi = ($this->input->post('f_kunjungan_realisasi')[$i] == 'on') ? TRUE : FALSE;
                    } else {
                        $f_kunjungan_realisasi  = FALSE;
                    }
                    if (!empty($this->input->post('f_kunjungan_valid')[$i])) {
                        $f_kunjungan_valid = ($this->input->post('f_kunjungan_valid')[$i] == 'on') ? TRUE : FALSE;
                    } else {
                        $f_kunjungan_valid  = FALSE;
                    }
                    $item = array(
                        'i_rrkh'                => $id,
                        'i_customer'            => $i_customer,
                        'i_kunjungan_type'      => $this->input->post('i_kunjungan_type')[$i],
                        'i_city'                => $this->input->post('i_city')[$i],
                        'f_kunjungan_realisasi' => $f_kunjungan_realisasi,
                        'f_kunjungan_valid'     => $f_kunjungan_valid,
                        'e_remark'              => $this->input->post('e_remark')[$i],
                        'd_entry'               => current_datetime(),
                        'n_item_no'             => $i,
                    );
                    $this->db->insert('tm_rrkh_item', $item);
                }
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

    /** Cek Apakah Data Sudah Ada Pas Edit */
    public function cek_edit()
    {
        $d_document_old = $this->input->post('d_document_old');
        $d_document = $this->input->post('d_document');
        $i_area = $this->input->post('i_area');
        $i_salesman = $this->input->post('i_salesman');
        return $this->db->query("SELECT 
                d_rrkh
            FROM 
                tm_rrkh 
            WHERE 
                d_rrkh = '$d_document'
                AND d_rrkh <> '$d_document_old'
                AND i_area = '$i_area'
                AND i_salesman = '$i_salesman'
                AND i_company = '$this->i_company'
        ", FALSE);
    }

    /** Update Data */
    public function update()
    {
        $id = $this->input->post('id');
        $table = array(
            'i_company'     => $this->i_company,
            'i_area'        => $this->input->post('i_area'),
            'i_salesman'    => $this->input->post('i_salesman'),
            'd_rrkh'        => $this->input->post('d_document'),
            'd_rrkh_update' => current_datetime(),
            'd_receive'     => $this->input->post('d_receive'),
        );
        $this->db->where('i_rrkh', $id);
        $this->db->update('tm_rrkh', $table);
        if (is_array($this->input->post('i_customer')) || is_object($this->input->post('i_customer'))) {
            $this->db->where('i_rrkh', $id);
            $this->db->delete('tm_rrkh_item');
            $i = 0;
            foreach ($this->input->post('i_customer') as $i_customer) {
                if ($i_customer != '' || $i_customer != null) {
                    if (!empty($this->input->post('f_kunjungan_realisasi')[$i])) {
                        $f_kunjungan_realisasi = ($this->input->post('f_kunjungan_realisasi')[$i] == 'on') ? TRUE : FALSE;
                    } else {
                        $f_kunjungan_realisasi  = FALSE;
                    }
                    if (!empty($this->input->post('f_kunjungan_valid')[$i])) {
                        $f_kunjungan_valid = ($this->input->post('f_kunjungan_valid')[$i] == 'on') ? TRUE : FALSE;
                    } else {
                        $f_kunjungan_valid  = FALSE;
                    }
                    $item = array(
                        'i_rrkh'                => $id,
                        'i_customer'            => $i_customer,
                        'i_kunjungan_type'      => $this->input->post('i_kunjungan_type')[$i],
                        'i_city'                => $this->input->post('i_city')[$i],
                        'f_kunjungan_realisasi' => $f_kunjungan_realisasi,
                        'f_kunjungan_valid'     => $f_kunjungan_valid,
                        'e_remark'              => $this->input->post('e_remark')[$i],
                        'd_entry'               => current_datetime(),
                        'd_update'              => current_datetime(),
                        'n_item_no'             => $i,
                    );
                    $this->db->insert('tm_rrkh_item', $item);
                }
                $i++;
            }
        } else {
            die;
        }
    }

    /** Cancel Data */
    public function cancel($id)
    {
        $table = array(
            'f_rrkh_cancel' => 't',
        );
        $this->db->where('i_rrkh', $id);
        $this->db->update('tm_rrkh', $table);
    }
}

/* End of file Mmaster.php */
