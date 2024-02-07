<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Minfottdnota extends CI_Model
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

        // $i_customer = $this->input->post('i_customer', TRUE);
        // if ($i_customer == '') {
        //     $i_customer = $this->uri->segment(6);
        // }

        // if ($i_customer != 'ALL') {
        //     $customer = "AND a.i_customer = '$i_customer' ";
        // } else {
        //     $customer = "";
        // }

        $dfrom  = date('Y-m-d', strtotime($dfrom));
        $dto    = date('Y-m-d', strtotime($dto));

        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("SELECT 
            distinct 
                a.i_company ,
                b.i_area_id || ' - ' || initcap(b.e_area_name) as e_area_name,
                c.i_customer_id as codee,
                initcap(c.e_customer_name) as e_customer_name,
                initcap(f.e_salesman_name) as e_salesman_name,
                e.i_do_id,
                e.d_do,
                i_nota_id,
                d_nota,
                v_nota_netto::money as v_nota_netto,
                v_sisa::money as v_sisa,
                case when a.f_so_stockdaerah = 't' then 'CABANG' else 'PUSAT' end as pnh,
                case when a.n_print = 0 then 'Belum' else 'Sudah' end as nprint
            from
                tm_nota a
            inner join tr_area b on
                (a.i_area = b.i_area)
            inner join tr_customer c on
                (c.i_customer = a.i_customer)
            inner join tm_nota_item d on
                (d.i_nota = a.i_nota)
            inner join tm_do e on
                (e.i_do = d.i_do)
            inner join tr_salesman f on
                (f.i_salesman = e.i_salesman )
                inner join tm_user_area u on
                    (u.i_area = b.i_area and u.i_user = '$this->i_user')
            where
                a.f_nota_cancel = 'f'
                and a.i_company = '$this->i_company'
                and a.d_nota between '$dfrom' and '$dto'
                $area
            order by
                4 asc
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
    // public function get_customer($cari, $i_area)
    // {
    //     if ($i_area != 'NA') {
    //         $area = "AND i_area = '$i_area' ";
    //     } else {
    //         $area = "";
    //     }
    //     return $this->db->query("SELECT 
    //             i_customer, i_customer_id , e_customer_name
    //         FROM 
    //             tr_customer
    //         WHERE 
    //             (e_customer_name ILIKE '%$cari%' OR i_customer_id ILIKE '%$cari%')
    //             AND i_company = '$this->i_company' 
    //             AND f_customer_active = 'true' 
    //             $area
    //         ORDER BY 3 ASC
    //     ", FALSE);
    // }

    /** Get Data Untuk Edit */
    public function get_data($dfrom, $dto, $i_area)
    {
        if ($i_area != 'NA') {
            $area = "AND a.i_area = '$i_area' ";
        } else {
            $area = "";
        }
        // if ($i_customer != 'ALL') {
        //     $customer = "AND a.i_customer = '$i_customer' ";
        // } else {
        //     $customer = "";
        // }
        $dfrom  = date('Y-m-d', strtotime($dfrom));
        $dto    = date('Y-m-d', strtotime($dto));
        return $this->db->query("SELECT
            distinct 
                a.i_company ,
                b.i_area_id || ' - ' || initcap(b.e_area_name) as e_area_name,
                c.i_customer_id as codee,
                initcap(c.e_customer_name) as e_customer_name,
                initcap(f.e_salesman_name) as e_salesman_name,
                e.i_do_id,
                e.d_do,
                i_nota_id,
                d_nota,
                v_nota_netto as v_nota_netto,
                v_sisa as v_sisa,
                case when a.f_so_stockdaerah = 't' then 'CABANG' else 'PUSAT' end as pnh,
                case when a.n_print = 0 then 'Belum' else 'Sudah' end as nprint
            from
                tm_nota a
            inner join tr_area b on
                (a.i_area = b.i_area)
            inner join tr_customer c on
                (c.i_customer = a.i_customer)
            inner join tm_nota_item d on
                (d.i_nota = a.i_nota)
            inner join tm_do e on
                (e.i_do = d.i_do)
            inner join tr_salesman f on
                (f.i_salesman = e.i_salesman )
                    inner join tm_user_area u on
                        (u.i_area = b.i_area and u.i_user = '$this->i_user')
            where
                a.f_nota_cancel = 'f'
                and a.i_company = '$this->i_company'
                and a.d_nota between '$dfrom' and '$dto'
                $area
            order by
                4 asc
        ", FALSE);
    }
}

/* End of file Mmaster.php */
