<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mpromo extends CI_Model
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

        $i_promo_type = $this->input->post('i_promo_type', TRUE);
        if ($i_promo_type == '') {
            $i_promo_type = $this->uri->segment(5);
        }

        if ($i_promo_type != '0') {
            $p = "AND a.i_promo_type = '$i_promo_type' ";
        } else {
            $p = "";
        }

        $dfrom  = date('Y-m-d', strtotime($dfrom));
        $dto    = date('Y-m-d', strtotime($dto));

        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("SELECT distinct
                d_promo as tgl,
                a.i_promo AS id,
                to_char(d_promo, 'YYYY-MM-DD') AS d_promo,
                i_promo_id,
                to_char(d_promo, 'YYYYMM') as i_periode,
                e_promo_name,
                b.e_promo_type_name,
                to_char(d_promo_start , 'DD FMMonth YYYY') AS d_promo_start,
                to_char(d_promo_finish, 'DD FMMonth YYYY') AS d_promo_finish,
                f_promo_cancel AS f_status,
                '$dfrom' AS dfrom,
                '$dto' AS dto
            FROM
                tm_promo a
            INNER JOIN tr_promo_type b ON
                (b.i_promo_type = a.i_promo_type)
            left join tm_so p on (p.i_promo=a.i_promo)
            WHERE
                a.d_promo BETWEEN '$dfrom' AND '$dto'
                AND a.i_company = '$this->i_company'
                $p
        ", FALSE);

        $datatables->edit('f_status', function ($data) {
            if ($data['f_status'] == 't') {
                $status = $this->lang->line('Tidak Aktif');
                $color  = 'red';
            } else {
                $color  = 'teal';
                $status = $this->lang->line('Aktif');
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
            $i_periode  = $data['i_periode'];
            // $i_so       = $data['i_so'];
            $data       = '';
            $data      .= "<a href='" . base_url() . $this->folder . '/view/' . encrypt_url($id) . '/' . encrypt_url($dfrom) . '/' . encrypt_url($dto) . "' title='View Data'><i class='fa fa-eye fa-lg warning darken-4 mr-1'></i></a>";
            // if ($i_periode >= get_periode()) {
            if (check_role($this->id_menu, 3) && $f_status == 'f') {
                $data      .= "<a href='" . base_url() . $this->folder . '/edit/' . encrypt_url($id)  . '/' .  encrypt_url($dfrom) . '/' . encrypt_url($dto) . "' title='Edit Data'><i class='fa fa-pencil-square success darken-4 fa-lg mr-1'></i></a>";
            }
            // if (check_role($this->id_menu, 4) && $f_status == 'f' && ($i_so == null || $i_so == '')) {
            if (check_role($this->id_menu, 4) && $f_status == 'f') {
                $data      .= "<a href='#' onclick='sweetdeletev2link(\"" . $this->folder . "\",\"" . $id . "\",\"" . encrypt_url($dfrom)  . "\",\"" . encrypt_url($dto)  . "\");' title='Delete Data'><i class='fa fa-trash fa-lg red darken-4'></i></a>";
            }
            // }
            return $data;
        });
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('i_periode');
        $datatables->hide('id');
        // $datatables->hide('i_so');
        return $datatables->generate();
    }

    /** Running Number Dokumen*/
    public function running_number($thbl, $tahun)
    {
        $cek = $this->db->query("SELECT 
                substring(i_promo_id, 1, 2) AS kode 
            FROM tm_promo 
            WHERE i_company = '$this->i_company'
            ORDER BY i_promo DESC");
        if ($cek->num_rows() > 0) {
            $kode = $cek->row()->kode;
        } else {
            $kode = 'PR';
        }
        $query  = $this->db->query("SELECT
                max(substring(i_promo_id, 9, 6)) AS max
            FROM
                tm_promo
            WHERE to_char (d_promo, 'yyyy') >= '$tahun'
            AND i_company = '$this->i_company'
            AND substring(i_promo_id, 1, 2) = '$kode'
            AND substring(i_promo_id, 4, 2) = substring('$thbl',1,2)
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

    /** Get P */
    public function get_p($cari)
    {
        return $this->db->query("SELECT distinct
                xx.i_promo_type, i_promo_type_id , e_promo_type_name
            FROM 
                tr_promo_type xx
            WHERE 
                (e_promo_type_name ILIKE '%$cari%' OR i_promo_type_id ILIKE '%$cari%')
                AND xx.i_company = '$this->i_company' 
                AND f_promo_type_active = 'true' 
            ORDER BY 1 ASC
        ", FALSE);
    }

    /** Get Area */
    public function get_area($cari)
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

    /** Get Type */
    public function get_type($cari)
    {
        return $this->db->query("SELECT 
                DISTINCT
                i_promo_type, 
                i_promo_type_id, 
                e_promo_type_name 
            FROM 
                tr_promo_type
            WHERE 
                (e_promo_type_name ILIKE '%$cari%' OR i_promo_type_id ILIKE '%$cari%')
                AND i_company = '$this->i_company' 
                AND f_promo_type_active = true 
            ORDER BY 1 ASC
        ", FALSE);
    }

    /** Get Valid */
    public function get_valid($i_promo_type)
    {
        return $this->db->query("SELECT 
                n_valid,
                f_read_price
            FROM 
                tr_promo_type
            WHERE 
                i_promo_type = '$i_promo_type'
        ", FALSE);
    }

    /** Get Group */
    public function get_group($cari)
    {
        return $this->db->query("SELECT 
                DISTINCT
                i_price_group, 
                i_price_groupid, 
                e_price_groupname 
            FROM 
                tr_price_group
            WHERE 
                (e_price_groupname ILIKE '%$cari%' OR i_price_groupid ILIKE '%$cari%')
                AND i_company = '$this->i_company' 
                AND f_price_groupactive = true 
            ORDER BY 3 ASC
        ", FALSE);
    }

    /** Get Product */
    public function get_product($cari, $i_price_group)
    {
        return $this->db->query("SELECT
                a.i_product,
                a.i_product_id,
                initcap(a.e_product_name) || ' ' || initcap(c.e_product_motifname) || ' ' || initcap(d.e_product_gradename) AS e_product_name
            FROM
                tr_product a
            INNER JOIN tr_customer_price b ON
                (b.i_product = a.i_product)
            INNER JOIN tr_product_motif c ON
                (c.i_product_motif = a.i_product_motif)
            INNER JOIN tr_product_grade d ON
                (d.i_product_grade = b.i_product_grade
                    AND d.f_default = TRUE)
            WHERE
                a.i_company = '$this->i_company'
                AND b.i_price_group = '$i_price_group'
                AND (a.i_product_id ILIKE '%$cari%'
                    OR a.e_product_name ILIKE '%$cari%')
            ORDER BY
                3
        ", FALSE);
    }

    /** Get Product */
    public function get_detail_product($i_product, $i_price_group)
    {
        return $this->db->query("SELECT
                a.i_product_motif,
                b.i_product_grade,
                c.e_product_motifname,
                b.v_price AS v_unit_price
            FROM
                tr_product a
            INNER JOIN tr_customer_price b ON
                (b.i_product = a.i_product)
            INNER JOIN tr_product_motif c ON
                (c.i_product_motif = a.i_product_motif)
            INNER JOIN tr_product_grade d ON
                (d.i_product_grade = b.i_product_grade
                    AND d.f_default = TRUE)
            WHERE
                a.i_company = '$this->i_company'
                AND b.i_price_group = '$i_price_group'
                AND a.i_product = '$i_product'
            ORDER BY
                3
        ", FALSE);
    }

    /** Get Customer */
    public function get_customer($cari)
    {
        return $this->db->query("SELECT 
                i_customer, i_customer_id , e_customer_name||' - '||initcap(e_city_name) AS e_customer_name
            FROM 
                tr_customer a
            INNER JOIN tr_city b ON (b.i_city = a.i_city)
            INNER JOIN tm_user_area c ON (c.i_area = a.i_area)
            WHERE 
                (e_customer_name ILIKE '%$cari%' OR i_customer_id ILIKE '%$cari%')
                AND a.i_company = '$this->i_company' 
                AND f_customer_active = 'true'
                AND c.i_user = '$this->i_user'
            ORDER BY 3 ASC
        ", FALSE);
    }

    /** Get Nota Detail */
    public function get_detail_customer($id)
    {
        return $this->db->query("SELECT
                e_customer_address
            FROM
                tr_customer
            WHERE
                i_customer = '$id'
        ", FALSE);
    }

    /** Simpan Data */
    public function save()
    {
        $query = $this->db->query("SELECT max(i_promo)+1 AS id FROM tm_promo", TRUE);
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

        $f_all_product = ($this->input->post('f_all_product') == 'on') ? TRUE : FALSE;
        $f_all_customer = ($this->input->post('f_all_customer') == 'on') ? TRUE : FALSE;
        $f_all_area = ($this->input->post('f_all_area') == 'on') ? TRUE : FALSE;
        $f_customer_group = ($this->input->post('f_customer_group') == 'on') ? TRUE : FALSE;
        $f_product_group = ($this->input->post('f_product_group') == 'on') ? TRUE : FALSE;
        $n_promo_discount1 = ($this->input->post('n_promo_discount1') != '') ? str_replace(",", "", $this->input->post('n_promo_discount1')) : 0;
        $n_promo_discount2 = ($this->input->post('n_promo_discount2') != '') ? str_replace(",", "", $this->input->post('n_promo_discount2')) : 0;
        $table = array(
            'i_company'         => $this->i_company,
            'i_promo'           => $id,
            'i_promo_id'        => $this->input->post('i_document'),
            'i_promo_type'      => $this->input->post('i_promo_type'),
            'e_promo_name'      => ucwords(strtolower($this->input->post('e_promo_name'))),
            'd_promo'           => $this->input->post('d_document'),
            'd_promo_start'     => $this->input->post('d_promo_start'),
            'd_promo_finish'    => $this->input->post('d_promo_finish'),
            'i_price_group'     => $this->input->post('i_price_group'),
            'n_promo_discount1' => $n_promo_discount1,
            'n_promo_discount2' => $n_promo_discount2,
            'f_all_product'     => $f_all_product,
            'f_all_customer'    => $f_all_customer,
            'f_all_area'        => $f_all_area,
            'f_customer_group'  => $f_customer_group,
            'f_product_group'   => $f_product_group,
            'd_entry'           => current_datetime(),
        );
        $this->db->insert('tm_promo', $table);

        /* Simpan Promo Product */
        if ($f_all_product == FALSE) {
            if (is_array($this->input->post('i_product')) || is_object($this->input->post('i_product'))) {
                $i = 0;
                foreach ($this->input->post('i_product') as $i_product) {
                    if ($i_product != '' || $i_product != null) {
                        $item = array(
                            'i_promo'               => $id,
                            'i_product'             => $i_product,
                            'i_product_grade'       => $this->input->post('i_product_grade')[$i],
                            'i_product_motif'       => $this->input->post('i_product_motif')[$i],
                            'v_unit_price'          => str_replace(",", "", $this->input->post('v_unit_price')[$i]),
                            'n_quantity_min'        => $this->input->post('n_quantity_min')[$i],
                            'n_item_no'             => $i,
                        );
                        $this->db->insert('tm_promo_item', $item);
                    }
                    $i++;
                }
            } else {
                die;
            }
        }

        /* Simpan Promo Customer */
        if ($f_all_customer == FALSE) {
            if (is_array($this->input->post('i_customer')) || is_object($this->input->post('i_customer'))) {
                $i = 0;
                foreach ($this->input->post('i_customer') as $i_customer) {
                    if ($i_customer != '' || $i_customer != null) {
                        $item = array(
                            'i_promo'     => $id,
                            'i_customer'  => $i_customer,
                        );
                        $this->db->insert('tm_promo_customer', $item);
                    }
                    $i++;
                }
            } else {
                die;
            }
        }

        /* Simpan Promo Area */
        if ($f_all_area == FALSE) {
            if (is_array($this->input->post('i_area')) || is_object($this->input->post('i_area'))) {
                $i = 0;
                foreach ($this->input->post('i_area') as $i_area) {
                    if ($i_area != '' || $i_area != null) {
                        $item = array(
                            'i_promo'  => $id,
                            'i_area'   => $i_area,
                        );
                        $this->db->insert('tm_promo_area', $item);
                    }
                    $i++;
                }
            } else {
                die;
            }
        }
    }

    /** Get Data Untuk Edit */
    public function get_data($id)
    {
        return $this->db->query("SELECT
                a.*,
                to_char(a.d_promo,'DD FMMonth YYYY') AS dpromo,
                to_char(a.d_promo_start,'DD FMMonth YYYY') AS dstart,
                to_char(a.d_promo_finish,'DD FMMonth YYYY') AS dfinish,
                b.e_promo_type_name,
	            c.e_price_groupname,
                b.f_read_price
            FROM
                tm_promo a
            INNER JOIN tr_promo_type b ON
                (b.i_promo_type = a.i_promo_type)
            INNER JOIN tr_price_group c ON 
                (c.i_price_group = a.i_price_group)
            WHERE
                a.i_promo = '$id'
        ", FALSE);
    }

    /** Get Data Untuk Edit Detail */
    public function get_data_detail($id)
    {
        return $this->db->query("SELECT
                a.*,
                b.i_product_id,
                initcap(b.e_product_name) || ' ' || initcap(c.e_product_motifname) || ' ' || initcap(d.e_product_gradename) AS e_product_name,
                c.e_product_motifname
            FROM
                tm_promo_item a
            INNER JOIN tr_product b ON
                (b.i_product = a.i_product)
            INNER JOIN tr_product_motif c ON
                (c.i_product_motif = a.i_product_motif)
            INNER JOIN tr_product_grade d ON
                (d.i_product_grade = a.i_product_grade)
            WHERE
                a.i_promo = '$id'
        ", FALSE);
    }

    /** Get Data Untuk Edit Customer */
    public function get_data_customer($id)
    {
        return $this->db->query("SELECT
                a.*,
                b.i_customer_id,
                b.e_customer_name,
                b.e_customer_address
            FROM
                tm_promo_customer a
            INNER JOIN tr_customer b ON
                (b.i_customer = a.i_customer)
            WHERE
                a.i_promo = '$id'
        ", FALSE);
    }

    /** Get Data Untuk Edit Area */
    public function get_data_area($id)
    {
        return $this->db->query("SELECT
                a.*,
                b.i_area_id,
                b.e_area_name
            FROM
                tm_promo_area a
            INNER JOIN tr_area b ON
                (b.i_area = a.i_area)
            WHERE
                a.i_promo = '$id'
        ", FALSE);
    }

    /** Update Data */
    public function update()
    {
        $id = $this->input->post('id');
        $f_all_product = ($this->input->post('f_all_product') == 'on') ? TRUE : FALSE;
        $f_all_customer = ($this->input->post('f_all_customer') == 'on') ? TRUE : FALSE;
        $f_all_area = ($this->input->post('f_all_area') == 'on') ? TRUE : FALSE;
        $f_customer_group = ($this->input->post('f_customer_group') == 'on') ? TRUE : FALSE;
        $f_product_group = ($this->input->post('f_product_group') == 'on') ? TRUE : FALSE;
        $n_promo_discount1 = ($this->input->post('n_promo_discount1') != '') ? str_replace(",", "", $this->input->post('n_promo_discount1')) : 0;
        $n_promo_discount2 = ($this->input->post('n_promo_discount2') != '') ? str_replace(",", "", $this->input->post('n_promo_discount2')) : 0;
        $table = array(
            'i_company'         => $this->i_company,
            'i_promo_id'        => $this->input->post('i_document'),
            'i_promo_type'      => $this->input->post('i_promo_type'),
            'e_promo_name'      => ucwords(strtolower($this->input->post('e_promo_name'))),
            'd_promo'           => $this->input->post('d_document'),
            'd_promo_start'     => $this->input->post('d_promo_start'),
            'd_promo_finish'    => $this->input->post('d_promo_finish'),
            'i_price_group'     => $this->input->post('i_price_group'),
            'n_promo_discount1' => $n_promo_discount1,
            'n_promo_discount2' => $n_promo_discount2,
            'f_all_product'     => $f_all_product,
            'f_all_customer'    => $f_all_customer,
            'f_all_area'        => $f_all_area,
            'f_customer_group'  => $f_customer_group,
            'f_product_group'   => $f_product_group,
            'd_entry'           => current_datetime(),
        );
        $this->db->where('i_promo', $id);
        $this->db->update('tm_promo', $table);

        /* Simpan Promo Product */
        if ($f_all_product == FALSE) {
            if (is_array($this->input->post('i_product')) || is_object($this->input->post('i_product'))) {
                $this->db->where('i_promo', $id);
                $this->db->delete('tm_promo_item');
                $i = 0;
                foreach ($this->input->post('i_product') as $i_product) {
                    if ($i_product != '' || $i_product != null) {
                        $item = array(
                            'i_promo'               => $id,
                            'i_product'             => $i_product,
                            'i_product_grade'       => $this->input->post('i_product_grade')[$i],
                            'i_product_motif'       => $this->input->post('i_product_motif')[$i],
                            'v_unit_price'          => str_replace(",", "", $this->input->post('v_unit_price')[$i]),
                            'n_quantity_min'        => $this->input->post('n_quantity_min')[$i],
                            'n_item_no'             => $i,
                        );
                        $this->db->insert('tm_promo_item', $item);
                    }
                    $i++;
                }
            } else {
                die;
            }
        }

        /* Simpan Promo Customer */
        if ($f_all_customer == FALSE) {
            if (is_array($this->input->post('i_customer')) || is_object($this->input->post('i_customer'))) {
                $this->db->where('i_promo', $id);
                $this->db->delete('tm_promo_customer');
                $i = 0;
                foreach ($this->input->post('i_customer') as $i_customer) {
                    if ($i_customer != '' || $i_customer != null) {
                        $item = array(
                            'i_promo'     => $id,
                            'i_customer'  => $i_customer,
                        );
                        $this->db->insert('tm_promo_customer', $item);
                    }
                    $i++;
                }
            } else {
                die;
            }
        }

        /* Simpan Promo Area */
        if ($f_all_area == FALSE) {
            if (is_array($this->input->post('i_area')) || is_object($this->input->post('i_area'))) {
                $this->db->where('i_promo', $id);
                $this->db->delete('tm_promo_area');
                $i = 0;
                foreach ($this->input->post('i_area') as $i_area) {
                    if ($i_area != '' || $i_area != null) {
                        $item = array(
                            'i_promo'  => $id,
                            'i_area'   => $i_area,
                        );
                        $this->db->insert('tm_promo_area', $item);
                    }
                    $i++;
                }
            } else {
                die;
            }
        }
    }

    /** Cancel Data */
    public function cancel($id)
    {
        $table = array(
            'f_promo_cancel' => 't',
        );
        $this->db->where('i_promo', $id);
        $this->db->update('tm_promo', $table);
    }
}

/* End of file Mmaster.php */
