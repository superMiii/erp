<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mspbpelangganbaru extends CI_Model
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

        $dfrom  = date('Y-m-d', strtotime($dfrom));
        $dto    = date('Y-m-d', strtotime($dto));

        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("SELECT                
                a.d_ttb as tgl,
                a.i_ttb AS id,
                a.d_ttb,
                a.i_ttb_id,
                to_char(a.d_ttb, 'YYYYMM') as i_periode,
                b.e_area_name,
                c.i_customer_id || ' ~ ' || c.e_customer_name AS e_customer,
                e.e_salesman_name,
                d.e_alasan_retur_name,
                a.f_ttb_cancel AS f_status,
                a.d_receive1,
                a.d_receive2,
                '$dfrom' AS dfrom,
                '$dto' AS dto
            FROM
                tm_ttbretur a
            INNER JOIN tr_area b ON
                (b.i_area = a.i_area)
            INNER JOIN tr_customer c ON
                (c.i_customer = a.i_customer)
            INNER JOIN tr_salesman e ON
                (e.i_salesman = a.i_salesman)
            INNER JOIN tr_alasan_retur d ON
                (d.i_alasan_retur = a.i_alasan_retur)
            INNER JOIN tm_user_area f ON
                (f.i_area = a.i_area)
            WHERE
                f.i_user = '$this->i_user'
                AND a.i_company = '$this->i_company'
                AND a.d_ttb BETWEEN '$dfrom' AND '$dto'
            ORDER BY
                1 DESC
        ", FALSE);

        $datatables->edit('f_status', function ($data) {
            if ($data['f_status'] == 't') {
                $status = $this->lang->line('Batal');
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
            $d_receive1 = $data['d_receive1'];
            $d_receive2 = $data['d_receive2'];
            $dfrom      = $data['dfrom'];
            $dto        = $data['dto'];
            $i_periode  = $data['i_periode'];
            $data       = '';
            $data      .= "<a href='" . base_url() . $this->folder . '/view/' . encrypt_url($id) . '/' . encrypt_url($dfrom) . '/' . encrypt_url($dto) . "' title='View Data'><i class='fa fa-eye fa-lg warning darken-4 mr-1'></i></a>";
            if ($i_periode >= get_periode()) {
                if (check_role($this->id_menu, 3) && $f_status == 'f'  && ($d_receive1 == null || $d_receive1 == '') && ($d_receive2 == null || $d_receive2 == '')) {
                    $data      .= "<a href='" . base_url() . $this->folder . '/edit/' . encrypt_url($id) . "' title='Edit Data'><i class='fa fa-pencil success darken-4 fa-lg mr-1'></i></a>";
                }
                if (check_role($this->id_menu, 5) && $f_status == 'f') {
                    $data      .= "<a href='#' onclick='openLink(\"" . $this->folder . "\",\"" . encrypt_url($id) . "\"); return false;' title='Print Data'><i class='fa fa-print fa-lg blue darken-4 mr-1'></i></a>";
                }
                if (check_role($this->id_menu, 4) && $f_status == 'f' && ($d_receive1 == null || $d_receive1 == '') && ($d_receive2 == null || $d_receive2 == '')) {
                    $data      .= "<a href='#' onclick='sweetdeletev2(\"" . $this->folder . "\",\"" . $id . "\");' title='Delete Data'><i class='fa fa-trash fa-lg red darken-4'></i></a>";
                }
            }
            return $data;
        });
        $datatables->hide('d_receive1');
        $datatables->hide('d_receive2');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('i_periode');
        $datatables->hide('id');
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
            /* INNER JOIN (SELECT DISTINCT i_area FROM tm_nota WHERE f_nota_cancel = 'f' AND i_company = '$this->i_company') c 
                ON (c.i_area = a.i_area) */
            WHERE 
                (e_area_name ILIKE '%$cari%' OR i_area_id ILIKE '%$cari%')
                AND a.i_company = '$this->i_company' 
                AND f_area_active = true
                AND b.i_user = '$this->i_user' 
            ORDER BY 3 ASC
        ", FALSE);
    }

    public function get_city($cari, $param1)
    {
        return $this->db->query("SELECT 
                i_city , i_city_id , initcap(e_city_name) AS e_city_name 
            FROM 
                tr_city 
            WHERE 
                (e_city_name ILIKE '%$cari%' or i_city_id ILIKE '%$cari%')
                and i_company = '$this->i_company' and f_city_active = true and i_area = '$param1'
            ORDER BY 3 ASC
        ", FALSE);
    }

    public function get_cover($cari, $param1, $param2)
    {
        return $this->db->query("SELECT 
                a.i_area_cover , i_area_cover_id , e_area_cover_name 
            FROM 
                tr_area_cover a
            INNER JOIN tr_area_cover_item b ON
                (b.i_area_cover = a.i_area_cover)
            WHERE 
                (e_area_cover_name ILIKE '%$cari%' or i_area_cover_id ILIKE '%$cari%')
                and i_company = '$this->i_company' and f_area_cover_active = true and i_area = '$param1' and i_city = '$param2'
            ORDER BY 3 ASC
        ", FALSE);
    }

    public function get_group($cari, $iareax)
    {
        return $this->db->query("SELECT distinct
                a.i_customer_group, e_customer_groupname
            FROM 
                tr_customer_group a
            inner join tr_customer b on (b.i_customer_group=a.i_customer_group)
            WHERE 
                (e_customer_groupname ILIKE '%$cari%')
                AND a.i_company = '$this->i_company' 
                AND f_customer_groupactive = 'true' 
            ORDER BY 2 ASC
        ", FALSE);
    }

    public function get_type($cari)
    {
        return $this->db->query("SELECT 
                i_customer_type , i_customer_typeid , e_customer_typename 
            FROM 
                tr_customer_type tsg 
            WHERE 
                (e_customer_typename ILIKE '%$cari%' or i_customer_typeid ILIKE '%$cari%')
                and i_company = '$this->i_company' and f_customer_typeactive = true
            ORDER BY 3 ASC
        ", FALSE);
    }

    public function get_level($cari)
    {
        return $this->db->query("SELECT 
                i_customer_level , i_customer_levelid , e_customer_levelname 
            FROM 
                tr_customer_level tsg 
            WHERE 
                (e_customer_levelname ILIKE '%$cari%' or i_customer_levelid ILIKE '%$cari%')
                and i_company = '$this->i_company' and f_customer_levelactive = true
            ORDER BY 3 ASC
        ", FALSE);
    }

    public function get_status($cari)
    {
        return $this->db->query("SELECT 
                i_customer_status , i_customer_statusid , e_customer_statusname 
            FROM 
                tr_customer_status 
            WHERE 
                (e_customer_statusname ILIKE '%$cari%' or i_customer_statusid ILIKE '%$cari%')
                and i_company = '$this->i_company' and f_customer_statusactive = true
                and f_status_awal = true
            ORDER BY 3 ASC
        ", FALSE);
    }

    public function get_price($cari)
    {
        return $this->db->query("SELECT 
                i_price_group , i_price_groupid , e_price_groupname 
            FROM 
                tr_price_group 
            WHERE 
                (e_price_groupname ILIKE '%$cari%' or i_price_groupid ILIKE '%$cari%')
                and i_company = '$this->i_company' and f_price_groupactive = true
            ORDER BY 3 ASC
        ", FALSE);
    }

    public function get_payment($cari)
    {
        return $this->db->query("SELECT 
                i_customer_payment , i_customer_paymentid , e_customer_paymentname 
            FROM 
                tr_customer_payment tsg 
            WHERE 
                (e_customer_paymentname ILIKE '%$cari%' or i_customer_paymentid ILIKE '%$cari%')
                and i_company = '$this->i_company' and f_customer_paymentactive = true
            ORDER BY 3 ASC
        ", FALSE);
    }

    public function get_paygroup($cari)
    {
        return $this->db->query("SELECT 
                i_customer_paygroup , i_customer_paygroupid , e_customer_paygroupname 
            FROM 
                tr_customer_paygroup tsg 
            WHERE 
                (e_customer_paygroupname ILIKE '%$cari%' or i_customer_paygroupid ILIKE '%$cari%')
                and i_company = '$this->i_company' and f_customer_paygroupactive = true
            ORDER BY 3 ASC
        ", FALSE);
    }

    /** Get Alasan Retur*/
    public function get_alasan_retur($cari)
    {
        return $this->db->query("SELECT
                i_alasan_retur,
                e_alasan_retur_name
            FROM
                tr_alasan_retur
            WHERE
                f_status_alasan_retur_active = 't'
                AND (e_alasan_retur_name ILIKE '%$cari%')
            ORDER BY
                i_alasan_retur ASC 
        ", FALSE);
    }

    /** Get Customer */
    public function get_customer($cari, $i_area)
    {
        return $this->db->query("SELECT 
                a.i_customer, i_customer_id , e_customer_name
            FROM 
                tr_customer a
            INNER JOIN (SELECT DISTINCT i_customer FROM tm_nota WHERE f_nota_cancel = 'f' AND i_company = '$this->i_company') c 
                ON (c.i_customer = a.i_customer)
            WHERE 
                (e_customer_name ILIKE '%$cari%' OR i_customer_id ILIKE '%$cari%')
                AND i_company = '$this->i_company' 
                AND f_customer_active = 'true' 
                AND i_area = '$i_area'
            ORDER BY 3 ASC
        ", FALSE);
    }

    /** Get Detail Customer */
    public function get_customer_detail($icustomer)
    {
        return $this->db->query("SELECT
                a.e_customer_address,
                a.i_price_group,
                a.f_customer_plusppn,
                a.e_customer_npwpcode,
                a.f_customer_pkp,
                b.e_price_groupname,
                CASE
                    WHEN a.f_customer_plusppn = TRUE THEN 'Ya'
                    ELSE 'Tidak'
                END AS eppn,
                CASE
                    WHEN a.f_customer_pkp = TRUE THEN 'Ya'
                    ELSE 'Tidak'
                END AS pkp
            FROM
                tr_customer a
            INNER JOIN tr_price_group b ON
                (a.i_price_group = b.i_price_group)
            WHERE
                i_customer = $icustomer
        ", FALSE);
    }

    /** Get Salesman */
    public function get_salesman($cari, $i_area)
    {
        /* return $this->db->query("SELECT
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
                AND b.i_area_cover IN (
                SELECT
                    i_area_cover
                FROM
                    tr_customer
                WHERE
                    i_customer = '$i_customer' )
            GROUP BY 1
            ORDER BY 3 ASC
        ", FALSE); */
        return $this->db->query("SELECT
                DISTINCT c.i_salesman,
                c.e_salesman_name,
                c.i_salesman_id
            FROM
                tr_area_cover_item a
            INNER JOIN tr_salesman_areacover b ON
                (b.i_area_cover = a.i_area_cover)
            INNER JOIN tr_salesman c ON
                (c.i_salesman = b.i_salesman)
            WHERE
                c.f_salesman_active = 't'
                AND a.i_area = '$i_area'
                AND (c.e_salesman_name ILIKE '%$cari%'
                    OR c.i_salesman_id ILIKE '%$cari%')
            ORDER BY 2
        ", FALSE);
    }

    /** Get Product */
    public function get_product($cari, $i_product_group, $i_price_group)
    {
        return $this->db->query("SELECT
                a.i_product,
                a.i_product_id ,
                a.e_product_name,
                b.e_product_motifname,
                c.e_product_colorname
            FROM
                tr_product a
            INNER JOIN tr_product_motif b ON
                (a.i_product_motif = b.i_product_motif)
            INNER JOIN tr_product_color c ON
                (a.i_product_color = c.i_product_color)
            INNER JOIN tr_customer_price d ON
                (d.i_product = a.i_product)
            INNER JOIN tr_product_grade e ON
                (e.i_product_grade = d.i_product_grade)
            WHERE
                (a.e_product_name ILIKE '%$cari%'
                    OR a.i_product_id ILIKE '%$cari%')
                AND a.i_company = '$this->i_company'
                AND a.f_product_active = TRUE
                AND d.i_price_group = '$i_price_group'
                AND e.f_default = 't'
                AND a.i_product_group = '$i_product_group'
            ORDER BY
                e_product_name ASC
        ", FALSE);
    }


    public function get_product_price($i_price_group, $i_product_group, $i_product)
    {
        return $this->db->query("SELECT
                d.v_price,
                e.i_product_grade,
                a.i_product_status,
                a.i_product_motif,
                a.e_product_name
            FROM
                tr_product a
            INNER JOIN tr_product_motif b ON
                (a.i_product_motif = b.i_product_motif)
            INNER JOIN tr_product_color c ON
                (a.i_product_color = c.i_product_color)
            INNER JOIN tr_customer_price d ON
                (d.i_product = a.i_product)
            INNER JOIN tr_product_grade e ON
                (e.i_product_grade = d.i_product_grade)
            WHERE
                a.i_product = '$i_product'
                AND a.i_company = '$this->i_company'
                AND a.f_product_active = TRUE
                AND d.i_price_group = '$i_price_group'
                AND e.f_default = 't'
                AND a.i_product_group = '$i_product_group'
        ", FALSE);
    }

    /** Get Product Group */
    public function get_product_group($cari)
    {
        return $this->db->query("SELECT
                i_product_group,
                i_product_groupid,
                e_product_groupname
            FROM
                tr_product_group
            WHERE
                (e_product_groupname ILIKE '%$cari%' OR i_product_groupid ILIKE '%$cari%')
                AND f_product_groupactive = 't'
                AND i_company = '$this->i_company'
            ORDER BY
                e_product_groupname ASC 
        ", FALSE);
    }

    /** Running Number Dokumen*/
    public function running_number($thbl, $tahun, $i_area)
    {
        /* $cek = $this->db->query("
            SELECT 
                substring(i_so_id, 1, 2) AS kode 
            FROM tm_so 
            WHERE i_company = '$this->i_company'
            AND i_area = '$i_area'
            ORDER BY i_so DESC");
        if ($cek->num_rows() > 0) {
            $kode = $cek->row()->kode;
        } else {
            $kode = 'SO';
        } */
        $cek = $this->db->query("SELECT 
                CASE
                    WHEN length(i_area_id)= 1 THEN '0' || i_area_id
                    ELSE i_area_id
                END AS kode
            FROM tr_area
            WHERE i_company = '$this->i_company'
            AND i_area = '$i_area'");
        if ($cek->num_rows() > 0) {
            $kode = $cek->row()->kode;
        } else {
            $kode = $i_area;
        }
        $query  = $this->db->query("SELECT
                max(substring(i_so_id, 9, 6)) AS max
            FROM
                tm_so
            WHERE to_char (d_so, 'yyyy') >= '$tahun'
            AND i_company = '$this->i_company'
            AND i_area = '$i_area'
            AND substring(i_so_id, 1, 2) = '$kode'
            AND substring(i_so_id, 4, 2) = substring('$thbl',1,2)
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

    /** Cek Apakah Data Sudah Ada */
    public function cek_code()
    {
        $i_document = str_replace("'", "", $this->input->post('i_document'));
        return $this->db->query("SELECT 
                i_customer_id
            FROM 
                tr_customer
            WHERE 
                upper(trim(i_customer_id)) = upper(trim('$i_document'))
                AND i_company = '$this->i_company'
        ", FALSE);
    }

    public function insert_groupbayar($kode, $nama)
    {
        $cek = $this->db->query("
             SELECT i_customer_paygroup FROM tr_customer_paygroup where i_customer_paygroupid = '$kode' and i_company = '$this->i_company'
        ", FALSE);

        if ($cek->num_rows() > 0) {
            $i_customer_paygroup = $cek->row()->i_customer_paygroup;
        } else {
            $i_customer_paygroup = $this->db->query("
               SELECT coalesce(max(i_customer_paygroup),0)::int + 1 as i_customer_paygroup FROM tr_customer_paygroup
            ", FALSE)->row()->i_customer_paygroup;

            $this->db->query("
                INSERT INTO tr_customer_paygroup (i_customer_paygroup, i_company,i_customer_paygroupid,e_customer_paygroupname,v_flafon,v_credit,f_customer_paygroupactive,d_customer_paygroupentry) VALUES
                ('$i_customer_paygroup','$this->i_company','$kode','$nama',0.00,0.00,true,now());
            ", FALSE);
        }
        return $i_customer_paygroup;
    }

    public function save(
        $iarea,
        $icity,
        $icover,
        $kode,
        $nama,
        $alamat,
        $pemilik,
        $telepon,
        $diskon,
        $npwp_kode,
        $npwp_nama,
        $npwp_alamat,
        $igroup,
        $itype,
        $ilevel,
        $istatus,
        $iprice,
        $ipayment,
        $diskon2,
        $diskon3,
        $ppn,
        $tanggal,
        $top,
        $e_pic_name,
        $e_pic_phone,
        $e_ktp_owner,
        $e_shipment_address,
        $n_building_m2,
        $e_competitor,
        $d_start,
        $id,
        $e_ekspedisi_cus,
        $e_ekspedisi_bayar
    ) {
        $n_building_m2 = ($n_building_m2 == '') ? null : $n_building_m2;
        $d_start = ($d_start == '') ? null : $d_start;
        $table = array(
            'i_company' => $this->i_company,
            'i_customer' => $id,
            'i_customer_id' => $kode,
            'e_customer_name' => $nama,
            'e_customer_address' => $alamat,
            'n_customer_discount1' => $diskon,
            'n_customer_discount2' => $diskon2,
            'n_customer_discount3' => $diskon3,
            'e_customer_phone' => $telepon,
            'e_customer_owner' => $pemilik,
            'e_customer_npwpcode' => $npwp_kode,
            'e_customer_npwpname' => $npwp_nama,
            'e_customer_npwpaddress' => $npwp_alamat,
            'i_area' => $iarea,
            'i_city' => $icity,
            'i_area_cover' => $icover,
            'i_price_group' => $iprice,
            'i_customer_group' => $igroup,
            'i_customer_payment' => $ipayment,
            'i_customer_type' => $itype,
            'i_customer_level' => $ilevel,
            'i_customer_status' => $istatus,
            'n_customer_top' => $top,
            'f_customer_plusppn' => $ppn,
            'd_customer_register' => $tanggal,
            'e_pic_name' => $e_pic_name,
            'e_pic_phone' => $e_pic_phone,
            'e_ktp_owner' => $e_ktp_owner,
            'e_shipment_address' => $e_shipment_address,
            'n_building_m2' => $n_building_m2,
            'e_competitor' => $e_competitor,
            'd_start' => $d_start,
            'd_customer_entry'  => date('Y-m-d H:i:s'),
            'f_customer_active' => 'f',
            'e_ekspedisi_cus' => $e_ekspedisi_cus,
            'e_ekspedisi_bayar' => $e_ekspedisi_bayar,
        );
        $this->db->insert('tr_customer', $table);

        $query = $this->db->query("SELECT max(i_so)+1 AS i_so FROM tm_so", TRUE);
        if ($query->num_rows() > 0) {
            $i_so = $query->row()->i_so;
            if ($i_so == null) {
                $i_so = 1;
            } else {
                $i_so = $i_so;
            }
        } else {
            $i_so = 1;
        }

        $ym = date('ym', strtotime($this->input->post('d_document')));
        $Y = date('Y', strtotime($this->input->post('d_document')));

        $i_so_id = $this->running_number($ym, $Y, $iarea);

        $d_po = ($this->input->post('d_po') == '' || $this->input->post('d_po' == null)) ? null : $this->input->post('d_po');
        $v_so_discounttotal = ($this->input->post('tfoot_v_diskon') == '' || $this->input->post('tfoot_v_diskon') == null) ? 0 : str_replace(",", "", $this->input->post('tfoot_v_diskon'));
        $f_so_stockdaerah = ($this->input->post('f_so_stockdaerah') == 'on') ? 't' : 'f';
        $i_status_so = ($f_so_stockdaerah == 't') ? 2 : 1;
        $header = array(
            'i_company'             => $this->session->i_company,
            'i_so'                  => $i_so,
            'i_so_id'               => $i_so_id,
            'i_area'                => $iarea,
            'i_customer'            => $id,
            'i_salesman'            => $this->input->post('i_salesman'),
            'i_price_group'         => $iprice,
            'i_po_reff'             => $this->input->post('i_so_po'),
            'i_status_so'           => $i_status_so,
            'i_product_group'       => $this->input->post('i_product_group'),
            'd_so'                  => $this->input->post('d_document'),
            'd_so_entry'            => current_datetime(),
            'd_po_reff'             => $d_po,
            'e_customer_pkpnpwp'    => $npwp_kode,
            'f_so_plusppn'          => $this->input->post('ppn'),
            'e_remark'              => $this->input->post('e_remarkh'),
            'f_so_stockdaerah'      => $f_so_stockdaerah,
            'n_so_toplength'        => $top,
            'v_so_discounttotal'    => $v_so_discounttotal,
            'v_so'                  => str_replace(",", "", $this->input->post('tfoot_total')),
            'n_so_ppn'              => str_replace(",", "", $this->input->post('nppn')),
        );
        $this->db->insert('tm_so', $header);

        if (is_array($this->input->post('i_product')) || is_object($this->input->post('i_product'))) {
            $i = 0;
            foreach ($this->input->post('i_product') as $i_product) {
                $item = array(
                    'i_so'             => $i_so,
                    'i_product'        => $i_product,
                    'i_product_grade'  => $this->input->post('i_product_grade')[$i],
                    'i_product_motif'  => $this->input->post('i_product_motif')[$i],
                    'i_product_status' => $this->input->post('i_product_status')[$i],
                    'e_product_name'   => $this->input->post('e_product_name')[$i],
                    'e_remark'         => $this->input->post('e_remark')[$i],
                    'n_order'          => str_replace(",", "", $this->input->post('n_order')[$i]),
                    'n_deliver'        => 0,
                    'n_item_no'        => $i,
                    'v_unit_price'     => str_replace(",", "", $this->input->post('v_price')[$i]),
                    'n_so_discount1'   => str_replace(",", "", $this->input->post('n_disc')[$i]),
                    'n_so_discount2'   => str_replace(",", "", $this->input->post('n_disc2')[$i]),
                    'n_so_discount3'   => str_replace(",", "", $this->input->post('n_disc3')[$i]),
                    'n_so_discount4'   => 0,
                    'v_so_discount1'   => str_replace(",", "", $this->input->post('v_disc')[$i]),
                    'v_so_discount2'   => str_replace(",", "", $this->input->post('v_disc2')[$i]),
                    'v_so_discount3'   => str_replace(",", "", $this->input->post('v_disc3')[$i]),
                    'v_so_discount4'   => 0,
                );
                $this->db->insert('tm_so_item', $item);
                $i++;
            }
        } else {
            die;
        }
    }

    /** Simpan Data */
    public function save_spb()
    {
        $query = $this->db->query("SELECT max(i_so)+1 AS id FROM tm_so", TRUE);
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

        $i_so_id = $this->running_number($ym, $Y, $this->input->post('i_area'));

        $d_po = ($this->input->post('d_po') == '' || $this->input->post('d_po' == null)) ? null : $this->input->post('d_po');
        $v_so_discounttotal = ($this->input->post('tfoot_v_diskon') == '' || $this->input->post('tfoot_v_diskon') == null) ? 0 : str_replace(",", "", $this->input->post('tfoot_v_diskon'));
        $f_so_stockdaerah = ($this->input->post('f_so_stockdaerah') == 'on') ? 't' : 'f';
        $i_status_so = ($f_so_stockdaerah == 't') ? 2 : 1;
        $header = array(
            'i_company'             => $this->session->i_company,
            'i_so'                  => $id,
            'i_so_id'               => $i_so_id,
            'i_area'                => $this->input->post('i_area'),
            'i_customer'            => $this->input->post('i_customer'),
            'i_salesman'            => $this->input->post('i_salesman'),
            'i_price_group'         => $this->input->post('i_price_group'),
            'i_po_reff'             => $this->input->post('i_so_po'),
            'i_status_so'           => $i_status_so,
            'i_product_group'       => $this->input->post('i_product_group'),
            'd_so'                  => $this->input->post('d_document'),
            'd_so_entry'            => current_datetime(),
            'd_po_reff'             => $d_po,
            'e_customer_pkpnpwp'    => $this->input->post('e_customer_pkpnpwp'),
            'f_so_plusppn'          => $this->input->post('ppn'),
            'e_remark'              => $this->input->post('e_remarkh'),
            'f_so_stockdaerah'      => $f_so_stockdaerah,
            'n_so_toplength'        => $this->input->post('n_so_toplength'),
            'v_so_discounttotal'    => $v_so_discounttotal,
            'v_so'                  => str_replace(",", "", $this->input->post('tfoot_total')),
            'n_so_ppn'              => str_replace(",", "", $this->input->post('nppn')),
        );
        $this->db->insert('tm_so', $header);

        if ($this->input->post('jml') > 0) {
            $i = 0;
            foreach ($this->input->post('i_product') as $i_product) {
                $item = array(
                    'i_so'             => $id,
                    'i_product'        => $i_product,
                    'i_product_grade'  => $this->input->post('i_product_grade')[$i],
                    'i_product_motif'  => $this->input->post('i_product_motif')[$i],
                    'i_product_status' => $this->input->post('i_product_status')[$i],
                    'e_product_name'   => $this->input->post('e_product_name')[$i],
                    'e_remark'         => $this->input->post('e_remark')[$i],
                    'n_order'          => str_replace(",", "", $this->input->post('n_order')[$i]),
                    'n_deliver'        => 0,
                    'n_item_no'        => $i,
                    'v_unit_price'     => str_replace(",", "", $this->input->post('v_price')[$i]),
                    'n_so_discount1'   => str_replace(",", "", $this->input->post('n_disc')[$i]),
                    'n_so_discount2'   => str_replace(",", "", $this->input->post('n_disc2')[$i]),
                    'n_so_discount3'   => str_replace(",", "", $this->input->post('n_disc3')[$i]),
                    'n_so_discount4'   => 0,
                    'v_so_discount1'   => str_replace(",", "", $this->input->post('v_disc')[$i]),
                    'v_so_discount2'   => str_replace(",", "", $this->input->post('v_disc2')[$i]),
                    'v_so_discount3'   => str_replace(",", "", $this->input->post('v_disc3')[$i]),
                    'v_so_discount4'   => 0,
                );
                $this->db->insert('tm_so_item', $item);
                $i++;
            }
        } else {
            die;
        }
    }
}

/* End of file Mmaster.php */
