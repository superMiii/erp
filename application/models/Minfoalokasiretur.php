<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Minfoalokasiretur extends CI_Model
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
                DISTINCT 
                a.i_alokasi,
                b.i_area_id || ' - ' || initcap(b.e_area_name) as e_area_name,
                c.i_customer_id as codee,
                initcap(c.e_customer_name) as e_customer_name,
                a.i_alokasi_id as i_alokasi_id,
                a.d_alokasi as d_alokasi,
                d.i_kn_id as i_kn_id,
                a.v_jumlah::money as v_jumlah,
                a.v_lebih::money as v_lebih,
                f.i_nota_id as i_nota_id ,
                f.d_nota as d_nota,
                f.v_nota_netto::money as v_nota_netto,
                mmx.tt::money as alok,
                f.v_sisa::money as v_sisa
            from
                tm_alokasiknr a
            inner join tr_area b on
                a.i_area = b.i_area
            inner join tr_customer c on
                c.i_customer = a.i_customer
            inner join tm_kn d on
                d.i_kn = a.i_kn
            inner join tm_alokasiknr_item e on
                e.i_alokasi = a.i_alokasi
            inner join tm_nota f on
                e.i_nota = f.i_nota
                    inner join tm_user_area u on
                        (u.i_area = b.i_area and u.i_user = '$this->i_user')
            left join (select nott,sum(tot) as tt from(
                        select
                            bb.i_nota as nott,
                            (bb.v_jumlah) as tot
                        from
                            tm_alokasi aa
                        inner join tm_alokasi_item bb on (bb.i_alokasi = aa.i_alokasi)
                        where aa.f_alokasi_cancel ='f')
                        as mmk group by 1) mmx on ( mmx.nott = f.i_nota)
            WHERE
                a.f_alokasi_cancel = 'f'
                AND a.i_company = '$this->i_company'
                AND a.d_alokasi BETWEEN '$dfrom' AND '$dto'
                $area
                $customer
            ORDER BY
                a.d_alokasi ASC
        ", FALSE);
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

    /** Get Customer */
    public function get_customer($cari, $i_area)
    {
        if ($i_area != 'NA') {
            $area = "AND i_area = '$i_area' ";
        } else {
            $area = "";
        }
        return $this->db->query("SELECT 
                i_customer, i_customer_id , e_customer_name
            FROM 
                tr_customer
            WHERE 
                (e_customer_name ILIKE '%$cari%' OR i_customer_id ILIKE '%$cari%')
                AND i_company = '$this->i_company' 
                AND f_customer_active = 'true' 
                $area
            ORDER BY 3 ASC
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
                DISTINCT 
                a.i_alokasi,
                b.i_area_id || ' - ' || initcap(b.e_area_name) as e_area_name,
                c.i_customer_id as codee,
                initcap(c.e_customer_name) as e_customer_name,
                a.i_alokasi_id as i_alokasi_id,
                a.d_alokasi as d_alokasi,
                d.i_kn_id as i_kn_id,
                a.v_jumlah as v_jumlah,
                a.v_lebih as v_lebih,
                f.i_nota_id as i_nota_id ,
                f.d_nota as d_nota,
                f.v_nota_netto as v_nota_netto,
                mmx.tt as alok,
                f.v_sisa as v_sisa
            from
                tm_alokasiknr a
            inner join tr_area b on
                a.i_area = b.i_area
            inner join tr_customer c on
                c.i_customer = a.i_customer
            inner join tm_kn d on
                d.i_kn = a.i_kn
            inner join tm_alokasiknr_item e on
                e.i_alokasi = a.i_alokasi
            inner join tm_nota f on
                e.i_nota = f.i_nota
                    inner join tm_user_area u on
                        (u.i_area = b.i_area and u.i_user = '$this->i_user')
            left join (select nott,sum(tot) as tt from(
                        select
                            bb.i_nota as nott,
                            (bb.v_jumlah) as tot
                        from
                            tm_alokasi aa
                        inner join tm_alokasi_item bb on (bb.i_alokasi = aa.i_alokasi)
                        where aa.f_alokasi_cancel ='f')
                        as mmk group by 1) mmx on ( mmx.nott = f.i_nota)
            WHERE
                a.f_alokasi_cancel = 'f'
                AND a.i_company = '$this->i_company'
                AND a.d_alokasi BETWEEN '$dfrom' AND '$dto'
                $area
                $customer
            ORDER BY
                a.d_alokasi ASC
        ", FALSE);
    }
}

/* End of file Mmaster.php */
