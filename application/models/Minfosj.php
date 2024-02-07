<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Minfosj extends CI_Model
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

        if ($i_area != 'ALL') {
            $area = "AND a.i_area = '$i_area' ";
        } else {
            $area = "";
        }

        $dfrom  = date('Y-m-d', strtotime($dfrom));
        $dto    = date('Y-m-d', strtotime($dto));

        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("SELECT DISTINCT
                    a.i_company,
                    a.i_do as i_do,
                    i_do_id,
                    el.raya::money as r,
                    a.d_do,
                    b.i_customer_id AS codee,
                    b.e_customer_name AS customer,
                    d.i_area_id || ' - ' || initcap(d.e_area_name) as e_area_name,
                    case when c.f_so_stockdaerah = 't' then 'CABANG' else 'PUSAT' end as e_pemenuhan,
                    c.i_so_id,
                    c.v_so::money as v_so,
                    c.d_so,
                    h.i_sl_id,
                    h.v_sl::money AS v_sl,
                    h.d_sl,
                    '$dfrom' AS dfrom,
                    '$dto' AS dto,
                    '$i_area' AS i_area
                FROM
                    tm_do a
                INNER JOIN tr_customer b ON
                    (b.i_customer = a.i_customer)
                INNER JOIN tm_so c ON
                    (c.i_so = a.i_so)
                INNER JOIN tr_area d ON
                    (d.i_area = a.i_area)
                INNER JOIN tm_user_area g ON
                    (g.i_area = d.i_area  and g.i_user = '$this->i_user')
                LEFT JOIN (SELECT DISTINCT i_do, i_sl_id, a.d_sl, a.v_sl  FROM tm_sl a, tm_sl_item b WHERE a.i_sl = b.i_sl AND a.f_sl_batal = 'f') h ON (h.i_do = a.i_do and a.f_do_cancel = 'f')
                inner join (select i_do,
                            (raya-(d1+d2+d3+d4))+(n_so_ppn/100)*(raya-(d1+d2+d3+d4)) as raya
                        from (
                        select i_do,
                            rr.i_so,
                            raya,
                            raya*(n_so_discount1/100) as d1,
                            (raya- (raya * (n_so_discount1/100)) )*(n_so_discount2/100) as d2,
                            (raya- (raya*(n_so_discount1/100)) - ((raya- (raya * (n_so_discount1/100)) )*(n_so_discount2/100)))*(n_so_discount3/100) as d3,
                            (raya- (raya*(n_so_discount1/100)) - (raya- (raya * (n_so_discount1/100)) )*(n_so_discount2/100) - (raya- (raya*(n_so_discount1/100)) - ((raya- (raya * (n_so_discount1/100)) )*(n_so_discount2/100)))*(n_so_discount3/100)) * (n_so_discount4/100) as d4
                        from (
                        select
                                i_do,r.i_so,sum(tot) as raya
                        from(
                            select
                                    a.i_do,c.i_so,b.n_deliver * c.v_unit_price as tot
                            from
                                    tm_do a
                            inner join tm_do_item b on (b.i_do = a.i_do)
                            inner join tm_so_item c on (c.i_so = a.i_so and c.i_product = b.i_product)
                                ) as r group by	1,2) as rr
                        inner join (select distinct i_so,n_so_discount1,n_so_discount2,n_so_discount3,n_so_discount4 from tm_so_item) as ss on (ss.i_so=rr.i_so)
                        ) as yy
                        inner join (select distinct i_so,n_so_ppn from tm_so) as se on (se.i_so=yy.i_so)
                        ) el on (el.i_do=a.i_do)
                WHERE
                a.f_do_cancel = 'f'
                AND a.i_company = '$this->i_company'
                AND a.d_do BETWEEN '$dfrom' AND '$dto'
                $area
                ORDER BY
                    a.d_do ASC
                    ", FALSE);

        /** Cek Hak Akses, Apakah User Bisa Edit */
        $datatables->add('action', function ($data) {
            $i_company  = $data['i_company'];
            $dfrom      = $data['dfrom'];
            $dto        = $data['dto'];
            $i_area     = $data['i_area'];
            $i_do       = $data['i_do'];
            $data       = '';
            $data      .= "<a href='" . base_url() . $this->folder . '/view/' . encrypt_url($i_do) . '/' . encrypt_url($i_company) . '/' . encrypt_url($dfrom) . '/' . encrypt_url($dto) . '/' . encrypt_url($i_area) . "' title='View Mutasi'><i class='fa fa-eye fa-lg warning darken-4 mr-1'></i></a>";
            return $data;
        });
        $datatables->hide('i_company');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('i_area');
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
    inner join tm_do c on (c.i_area = a.i_area)
    WHERE 
        (e_area_name ILIKE '%$cari%' OR i_area_id ILIKE '%$cari%')
        AND a.i_company = '$this->i_company' 
        AND f_area_active = true
        AND b.i_user = '$this->i_user' 
        ", FALSE);
    }

    // /** Get Customer */
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

    /**** List Mutasi ***/
    public function get_data_detail($i_do, $i_company, $dfrom, $dto, $i_area)
    {

        $dfrom  = date('Y-m-d', strtotime($dfrom));
        $dto    = date('Y-m-d', strtotime($dto));

        return $this->db->query("SELECT
	            b.i_do_id ,
                c.i_area_id || ' - ' || initcap(c.e_area_name) as e_area_name,	
                d.i_customer_id as codee,
                initcap(d.e_customer_name) as e_customer_name,
                e.i_product_id ,
                e.e_product_name ,
                f.e_product_motifname ,
                a.n_deliver 
            from
                tm_do_item a
            inner join tm_do b on (b.i_do=a.i_do)
            inner join tr_area c on (c.i_area=b.i_area)
            inner join tr_customer d on (d.i_customer=b.i_customer)
            inner join tr_product e on (e.i_product=a.i_product)
            inner join tr_product_motif f on (f.i_product_motif=a.i_product_motif)
            WHERE
                a.i_do = '$i_do'
            order by n_item_no desc 
        ", FALSE);
    }

    /** Get Data Untuk Edit */
    public function get_data($dfrom, $dto, $i_area)
    {
        if ($i_area != 'ALL') {
            $area = "AND a.i_area = '$i_area' ";
        } else {
            $area = "";
        }
        $dfrom  = date('Y-m-d', strtotime($dfrom));
        $dto    = date('Y-m-d', strtotime($dto));
        return $this->db->query("SELECT DISTINCT
                    a.i_company,
                    a.i_do as i_do,
                    i_do_id,
                    a.d_do,
                    b.i_customer_id AS codee,
                    b.e_customer_name AS customer,
                    d.i_area_id || ' - ' || initcap(d.e_area_name) as e_area_name,
                    case when c.f_so_stockdaerah = 't' then 'CABANG' else 'PUSAT' end as e_pemenuhan,
                    c.i_so_id,
                    el.raya as r,
                    c.v_so,
                    c.d_so,
                    h.i_sl_id,
                    h.v_sl AS v_sl,
                    h.d_sl,
                    '$dfrom' AS dfrom,
                    '$dto' AS dto,
                    '$i_area' AS i_area
                FROM
                    tm_do a
                INNER JOIN tr_customer b ON
                    (b.i_customer = a.i_customer)
                INNER JOIN tm_so c ON
                    (c.i_so = a.i_so)
                INNER JOIN tr_area d ON
                    (d.i_area = a.i_area)
                INNER JOIN tm_user_area g ON
                    (g.i_area = d.i_area  and g.i_user = '$this->i_user')
                LEFT JOIN (SELECT DISTINCT i_do, i_sl_id, a.d_sl, a.v_sl  FROM tm_sl a, tm_sl_item b WHERE a.i_sl = b.i_sl AND a.f_sl_batal = 'f') h ON (h.i_do = a.i_do and a.f_do_cancel = 'f')
                inner join (select i_do,
                            (raya-(d1+d2+d3+d4))+(n_so_ppn/100)*(raya-(d1+d2+d3+d4)) as raya
                        from (
                        select i_do,
                            rr.i_so,
                            raya,
                            raya*(n_so_discount1/100) as d1,
                            (raya- (raya * (n_so_discount1/100)) )*(n_so_discount2/100) as d2,
                            (raya- (raya*(n_so_discount1/100)) - ((raya- (raya * (n_so_discount1/100)) )*(n_so_discount2/100)))*(n_so_discount3/100) as d3,
                            (raya- (raya*(n_so_discount1/100)) - (raya- (raya * (n_so_discount1/100)) )*(n_so_discount2/100) - (raya- (raya*(n_so_discount1/100)) - ((raya- (raya * (n_so_discount1/100)) )*(n_so_discount2/100)))*(n_so_discount3/100)) * (n_so_discount4/100) as d4
                        from (
                        select
                                i_do,r.i_so,sum(tot) as raya
                        from(
                            select
                                    a.i_do,c.i_so,b.n_deliver * c.v_unit_price as tot
                            from
                                    tm_do a
                            inner join tm_do_item b on (b.i_do = a.i_do)
                            inner join tm_so_item c on (c.i_so = a.i_so and c.i_product = b.i_product)
                                ) as r group by	1,2) as rr
                        inner join (select distinct i_so,n_so_discount1,n_so_discount2,n_so_discount3,n_so_discount4 from tm_so_item) as ss on (ss.i_so=rr.i_so)
                        ) as yy
                        inner join (select distinct i_so,n_so_ppn from tm_so) as se on (se.i_so=yy.i_so)
                        ) el on (el.i_do=a.i_do)
                WHERE
                a.f_do_cancel = 'f'
                AND a.i_company = '$this->i_company'
                AND a.d_do BETWEEN '$dfrom' AND '$dto'
                $area
                ORDER BY
                    a.d_do ASC
        ", FALSE);
    }
}

/* End of file Mmaster.php */
