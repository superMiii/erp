<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Minfokn extends CI_Model
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

        $i_customer = $this->input->post('i_customer', TRUE);
        if ($i_customer == '') {
            $i_customer = $this->uri->segment(6);
        }

        if ($i_customer != 'ALL') {
            $customer = "AND a.i_customer = '$i_customer' ";
        } else {
            $customer = "";
        }

        $dfrom  = date('Y-m-d', strtotime($dfrom));
        $dto    = date('Y-m-d', strtotime($dto));

        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("SELECT
                a.i_kn AS ids,
                a.i_kn_id,
                to_char(a.d_kn, 'DD FMMonth YYYY') AS d_kn,
                b.i_bbm_id,
                to_char(b.d_bbm, 'DD FMMonth YYYY') AS d_bbm,
                d.e_area_name,
                e.i_customer_id,
                e.e_customer_name,
                f.e_salesman_name,
                a.v_netto::money AS v_netto,
                a.v_sisa::money AS v_sisa,
                b.i_bbm as bbm,
                e.i_customer as cus,
                    '$dfrom' AS dfrom,
                    '$dto' AS dto,
                    '$i_area' AS i_area,
                    '$i_customer' AS i_customer
            FROM
                tm_kn a
            INNER JOIN tm_bbm b ON
                (b.i_bbm = a.i_refference)
            INNER JOIN tm_ttbretur c ON
                (c.i_ttb = b.i_ttb)
            INNER JOIN tr_area d ON
                (d.i_area = a.i_area)
            INNER JOIN tr_customer e ON
                (e.i_customer = a.i_customer)
            INNER JOIN tr_salesman f ON
                (f.i_salesman = a.i_salesman)
            INNER JOIN tm_user_area g ON
                (g.i_area = a.i_area)
            WHERE
                a.i_company = '$this->i_company'
                AND a.d_kn BETWEEN '$dfrom' AND '$dto'
                AND g.i_user = '$this->i_user'
                AND a.f_kn_retur = 't'
                $area
                $customer
            ORDER BY
                3 ASC
        ", FALSE);

        /** Cek Hak Akses, Apakah User Bisa Edit */
        $datatables->add('action', function ($data) {
            $bbm        = $data['bbm'];
            $cus        = $data['cus'];
            $dfrom      = $data['dfrom'];
            $dto        = $data['dto'];
            $i_area     = $data['i_area'];
            $i_customer = $data['i_customer'];
            $data       = '';
            $data      .= "<a href='" . base_url() . $this->folder . '/view/' . encrypt_url($bbm) . '/' . encrypt_url($cus) . '/' . encrypt_url($dfrom) . '/' . encrypt_url($dto) . '/' . encrypt_url($i_area) . '/' . encrypt_url($i_customer) . "' title='View Mutasi'><i class='fa fa-eye fa-lg warning darken-4 mr-1'></i></a>";
            return $data;
        });

        $datatables->hide('bbm');
        $datatables->hide('cus');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('i_area');
        $datatables->hide('i_customer');
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
            inner join tm_kn bb on (bb.i_area=a.i_area AND bb.f_kn_retur = 't')
            WHERE 
                (e_area_name ILIKE '%$cari%' OR i_area_id ILIKE '%$cari%')
                AND a.i_company = '$this->i_company' 
                AND f_area_active = true
                AND b.i_user = '$this->i_user' 
            ORDER BY 3 ASC
        ", FALSE);
    }

    /** Get Customer */
    public function get_customer($cari, $i_area)
    {
        if ($i_area != 'NA') {
            $area = "AND a.i_area = '$i_area' ";
        } else {
            $area = "";
        }
        return $this->db->query("SELECT DISTINCT
                a.i_customer, i_customer_id , e_customer_name
            FROM 
                tr_customer a
                inner join tm_kn b on (b.i_customer=a.i_customer AND b.f_kn_retur = 't')
            WHERE 
                (e_customer_name ILIKE '%$cari%' OR i_customer_id ILIKE '%$cari%')
                AND a.i_company = '$this->i_company' 
                AND f_customer_active = 'true' 
                $area
            ORDER BY 3 ASC
        ", FALSE);
    }


    public function get_data_detail($bbm, $cus)
    {
        return $this->db->query("SELECT
                        d.i_ttb_item,
                c.i_product_id,
                c.e_product_name,
                e.e_product_motifname,
                a.*,
                d.n_ttb_discount1,
                d.n_ttb_discount2,
                d.n_ttb_discount3,
                d.i_product1,
                d.i_product1_grade,
                d.i_product1_motif,
                f.i_product_id AS i_product_id1,
                f.e_product_name AS e_product_name1,
                g.e_product_motifname AS e_product_motifname1,
                d.n_quantity AS n_quantity1,
                n.i_seri_pajak,
                n.d_pajak 
            FROM
                tm_bbm_item a
            INNER JOIN tm_bbm b ON
                (b.i_bbm = a.i_bbm)
            INNER JOIN tr_product c ON
                (c.i_product = a.i_product)
            INNER JOIN tm_ttbretur_item d ON
                (d.i_ttb = b.i_ttb
                    AND a.i_product = d.i_product2)
            INNER JOIN tr_product_motif e ON
                (e.i_product_motif = a.i_product_motif)
            INNER JOIN tr_product f ON
                (f.i_product = d.i_product1)
            INNER JOIN tr_product_motif g ON
                (g.i_product_motif = d.i_product1_motif)
            inner join tm_nota n on (n.i_nota=d.i_nota)
            WHERE
                a.i_bbm = '$bbm'
            ORDER BY
                a.n_item_no ASC
        ", FALSE);
    }


    /** Get Data Untuk Edit */
    public function get_data($dfrom, $dto, $i_area, $i_customer)
    {
        if ($i_area != 'NA') {
            $area = "AND a.i_area = '$i_area' ";
        } else {
            $area = "";
        }
        if ($i_customer != 'ALL') {
            $customer = "AND a.i_customer = '$i_customer' ";
        } else {
            $customer = "";
        }
        $dfrom  = date('Y-m-d', strtotime($dfrom));
        $dto    = date('Y-m-d', strtotime($dto));
        return $this->db->query("SELECT
                a.i_kn AS id,
                a.i_kn_id,
                to_char(a.d_kn, 'DD FMMonth YYYY') AS d_kn,
                b.i_bbm_id,
                to_char(b.d_bbm, 'DD FMMonth YYYY') AS d_bbm,
                d.e_area_name,
                e.i_customer_id,
                e.e_customer_name,
                f.e_salesman_name,
                a.v_netto AS v_netto,
                a.v_sisa AS v_sisa
            FROM
                tm_kn a
            INNER JOIN tm_bbm b ON
                (b.i_bbm = a.i_refference)
            INNER JOIN tm_ttbretur c ON
                (c.i_ttb = b.i_ttb)
            INNER JOIN tr_area d ON
                (d.i_area = a.i_area)
            INNER JOIN tr_customer e ON
                (e.i_customer = a.i_customer)
            INNER JOIN tr_salesman f ON
                (f.i_salesman = a.i_salesman)
            INNER JOIN tm_user_area g ON
                (g.i_area = a.i_area)
            WHERE
                a.i_company = '$this->i_company'
                AND a.d_kn BETWEEN '$dfrom' AND '$dto'
                AND g.i_user = '$this->i_user'
                AND a.f_kn_retur = 't'
                $area
                $customer
            ORDER BY
                3 ASC
        ", FALSE);
    }
}

/* End of file Mmaster.php */
