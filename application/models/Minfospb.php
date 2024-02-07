<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Minfospb extends CI_Model
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

        $i_status_so = $this->input->post('i_status_so', TRUE);
        if ($i_status_so == '') {
            $i_status_so = $this->uri->segment(5);
        }

        if ($i_status_so != 'ALL') {
            $status_so = "AND a.i_status_so = '$i_status_so' ";
        } else {
            $status_so = "";
        }

        $i_area = $this->input->post('i_area', TRUE);
        if ($i_area == '') {
             $i_area = $this->uri->segment(6);
        }

        if ($i_area != 'NA') {
             $area = "AND a.i_area = '$i_area' ";
        } else {
             $area = "";
        }

        $dfrom  = date('Y-m-d', strtotime($dfrom));
        $dto    = date('Y-m-d', strtotime($dto));

        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("SELECT DISTINCT
                    a.i_so AS id,
                    e.e_status_so_name,
                    to_char(a.d_so, 'YYYYMM') as i_periode,
                    a.i_so_id,
                    c.i_customer_id || ' - ' || initcap(c.e_customer_name) AS e_customer_name,
                    to_char(a.d_so, 'YYYY-MM-DD') as d_entry,
                    initcap(b.e_salesman_name) AS e_salesman_name,
                    to_char(a.d_approve1, 'DD FMMonth YYYY') AS d_approve1,
                    to_char(a.d_approve2, 'DD FMMonth YYYY') AS d_approve2,
                    d.i_area_id || ' ~ ' || d.e_area_name as e_area_name,
                    initcap(cc.e_city_name) AS e_city_name,
                    case when a.f_so_stockdaerah = 't' then 'CABANG' else 'PUSAT' end as e_pemenuhan,
	                i_do,
                    a.v_so::money AS v_so,
	                nn.v_nota_netto ::money as v_nota
                FROM
                    tm_so a
                INNER JOIN tr_salesman b ON
                    (b.i_salesman = a.i_salesman)
                INNER JOIN tr_customer c ON
                    (c.i_customer = a.i_customer)
                INNER JOIN tr_city cc ON
                    (cc.i_city = c.i_city)
                INNER JOIN tr_area d ON	
                    (d.i_area = a.i_area)
                INNER JOIN tr_status_so e ON
                    (e.i_status_so = a.i_status_so)
                INNER JOIN tm_user_area f ON
                    (f.i_area = a.i_area)
                LEFT JOIN (SELECT DISTINCT i_so, string_agg(i_do::varchar,', ') AS i_do FROM tm_do WHERE f_do_cancel = 'f' GROUP BY 1) g ON
                    (g.i_so = a.i_so)
                inner join tm_user_area u on
                    (u.i_area = f.i_area and u.i_user = '$this->i_user')
                left join tm_nota nn on (nn.i_so=a.i_so)
                WHERE
                    a.f_so_cancel = 'f'
                    AND a.i_company = '$this->i_company'
                    AND a.d_so BETWEEN '$dfrom' AND '$dto'
                    $status_so
                    $area
                ORDER BY
                    2 ASC
                    ", FALSE);

        $datatables->edit('e_status_so_name', function ($data) {
            if ($data['e_status_so_name'] == "Sudah Dinotakan") {
                $color  = 'black';
                $status = "Sudah Dinotakan";
            } elseif ($data['e_status_so_name'] == "Siap Nota") {
                $color  = 'amber';
                $status = "Siap Nota";
            } elseif ($data['e_status_so_name'] == "Siap DKB") {
                $color  = 'pink';
                $status = "Siap DKB";
            } elseif ($data['e_status_so_name'] == "Siap SJ") {
                $color  = 'blue';
                $status = "Siap SJ";
            } elseif ($data['e_status_so_name'] == "Realisasi ke Gudang") {
                $color  = 'purple';
                $status = "Realisasi ke Gudang";
            } elseif ($data['e_status_so_name'] == "Menunggu Persetujuan Keuangan") {
                $color  = 'teal';
                $status = "Menunggu Persetujuan Keuangan";
            } elseif ($data['e_status_so_name'] == "Menunggu Persetujuan Admin Sales") {
                $color  = 'success';
                $status = "Menunggu Persetujuan Admin Sales";
            } else {
                $color  = 'red';
                $status = $data['e_status_so_name'];
            }
            $data = "<span class='badge bg-" . $color . " badge-pill'>" . $status . "</span>";
            return $data;
        });
        $datatables->hide('i_do');
        return $datatables->generate();
    }

    /** Get Area */
    public function get_status_so($cari)
    {
        return $this->db->query("select distinct 
                a.*
            from
                tr_status_so a
            inner join tm_so b on
                (b.i_status_so = a.i_status_so)
                order by 1
        ", FALSE);
    }

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
    public function get_data($dfrom, $dto, $i_status_so, $i_area)
    {
        if ($i_status_so != 'ALL') {
            $status_so = "AND a.i_status_so = '$i_status_so' ";
        } else {
            $status_so = "";
        }

        if ($i_area != 'NA') {
            $area = "AND a.i_area = '$i_area' ";
        } else {
            $area = "";
        }

        $dfrom  = date('Y-m-d', strtotime($dfrom));
        $dto    = date('Y-m-d', strtotime($dto));
        return $this->db->query("SELECT DISTINCT
                    a.i_so AS id,
                    a.f_so_cancel AS f_status0,
                    to_char(a.d_so, 'YYYYMM') as i_periode,
                    a.i_so_id,
                    c.i_customer_id || ' - ' || initcap(c.e_customer_name) AS e_customer_name,
                    to_char(a.d_so, 'YYYY-MM-DD') as d_entry,
                    initcap(b.e_salesman_name) AS e_salesman_name,
                    to_char(a.d_approve1, 'DD FMMonth YYYY') AS d_approve1,
                    to_char(a.d_approve2, 'DD FMMonth YYYY') AS d_approve2,
                    d.i_area_id || ' ~ ' || d.e_area_name as e_area_name,
                    initcap(cc.e_city_name) AS e_city_name,
                    case when a.f_so_stockdaerah = 't' then 'CABANG' else 'PUSAT' end as e_pemenuhan,
                    i_do,
                    e.e_status_so_name as f_status,
                    a.v_so AS v_so,
                    nn.v_nota_netto  as v_nota
                FROM
                    tm_so a
                INNER JOIN tr_salesman b ON
                    (b.i_salesman = a.i_salesman)
                INNER JOIN tr_customer c ON
                    (c.i_customer = a.i_customer)
                INNER JOIN tr_city cc ON
                    (cc.i_city = c.i_city)
                INNER JOIN tr_area d ON	
                    (d.i_area = a.i_area)
                INNER JOIN tr_status_so e ON
                    (e.i_status_so = a.i_status_so)
                INNER JOIN tm_user_area f ON
                    (f.i_area = a.i_area)
                LEFT JOIN (SELECT DISTINCT i_so, string_agg(i_do::varchar,', ') AS i_do FROM tm_do WHERE f_do_cancel = 'f' GROUP BY 1) g ON
                    (g.i_so = a.i_so)
                inner join tm_user_area u on
                    (u.i_area = f.i_area and u.i_user = '$this->i_user')
                left join tm_nota nn on (nn.i_so=a.i_so)
                WHERE
                    a.f_so_cancel = 'f'
                    AND a.i_company = '$this->i_company'
                    AND a.d_so BETWEEN '$dfrom' AND '$dto'
                    $status_so
                    $area
                ORDER BY
                    2 ASC
                    ", FALSE);
    }
}

/* End of file Mmaster.php */
