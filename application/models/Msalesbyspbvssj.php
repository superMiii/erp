<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Msalesbyspbvssj extends CI_Model
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
            $status_so = "AND rm.i_status_so = '$i_status_so' ";
        } else {
            $status_so = "";
        }

        $i_area = $this->input->post('i_area', TRUE);
        if ($i_area == '') {
             $i_area = $this->uri->segment(6);
        }

        if ($i_area != 'NA') {
             $area = "AND rm.i_area = '$i_area' ";
        } else {
             $area = "";
        }

        $dfrom  = date('Y-m-d', strtotime($dfrom));
        $dto    = date('Y-m-d', strtotime($dto));

        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("SELECT 
                    rm.i_area as id ,
                    io.i_customer_id || ' - ' || io.e_customer_name as cus,
                    mr.e_status_so_name ,
                    krn.i_area_id || ' ~ ' || krn.e_area_name as araa,
                    yz.e_city_name ,
	                rm.i_so_id ,
                    to_char(rm.d_so, 'DD FMMonth YYYY') AS d_so ,
                    to_char(rm.d_approve1, 'DD FMMonth YYYY') AS d_approve1 ,
                    to_char(rm.d_approve2, 'DD FMMonth YYYY') AS d_approve2 ,
                    case when rm.d_approve1 notnull then rm.d_approve1-rm.d_so else null end as d1,
                    case when rm.d_approve2 notnull then rm.d_approve2-rm.d_so else null end as d2,
                    miu.i_do_id ,
                    to_char(miu.d_do, 'DD FMMonth YYYY') AS d_do ,
                    case when miu.d_do notnull then miu.d_do-rm.d_so else null end as d3,
                    ich.i_nota_id ,
                    to_char(ich.d_nota, 'DD FMMonth YYYY') AS d_nota ,
                    case when ich.d_nota_entry notnull then ich.d_nota-rm.d_so else null end as d4,
                    rm.v_so::money as v_so ,
                    ich.v_nota_netto::money as v_nota_netto
                from
                    tm_so rm
                inner join tr_customer io on (io.i_customer=rm.i_customer)
                inner join tr_status_so mr on (mr.i_status_so=rm.i_status_so)
                inner join  tr_area krn on (krn.i_area=rm.i_area)
                inner join tr_city yz on (yz.i_city=io.i_city)
                left join tm_do miu on (miu.i_so = rm.i_so and miu.i_company = '2' and miu.f_do_cancel = 'f' and miu.d_do  between '2023-08-01' and '2023-08-31')
                left join tm_nota ich on (ich.i_so = rm.i_so and ich.i_company = '2' and ich.f_nota_cancel = 'f' and ich.d_nota  between '2023-08-01' and '2023-08-31')
                where 
                    rm.i_company = '$this->i_company'
                    and rm.f_so_cancel ='f'
                    and rm.d_so between '$dfrom' AND '$dto'
                    $status_so
                    $area
                ORDER BY
                    1 ASC
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
            $status_so = "AND rm.i_status_so = '$i_status_so' ";
        } else {
            $status_so = "";
        }

        if ($i_area != 'NA') {
            $area = "AND rm.i_area = '$i_area' ";
        } else {
            $area = "";
        }

        $dfrom  = date('Y-m-d', strtotime($dfrom));
        $dto    = date('Y-m-d', strtotime($dto));
        return $this->db->query("SELECT 
                    rm.i_area as id ,
                    io.i_customer_id || ' - ' || io.e_customer_name as cus,
                    mr.e_status_so_name ,
                    krn.i_area_id || ' ~ ' || krn.e_area_name as araa,
                    yz.e_city_name ,                    
	                rm.i_so_id ,
                    to_char(rm.d_so, 'DD FMMonth YYYY') AS d_so ,
                    to_char(rm.d_approve1, 'DD FMMonth YYYY') AS d_approve1 ,
                    to_char(rm.d_approve2, 'DD FMMonth YYYY') AS d_approve2 ,
                    case when rm.d_approve1 notnull then rm.d_approve1-rm.d_so else null end as d1,
                    case when rm.d_approve2 notnull then rm.d_approve2-rm.d_so else null end as d2,
                    miu.i_do_id ,
                    to_char(miu.d_do, 'DD FMMonth YYYY') AS d_do ,
                    case when miu.d_do notnull then miu.d_do-rm.d_so else null end as d3,
                    ich.i_nota_id ,
                    to_char(ich.d_nota, 'DD FMMonth YYYY') AS d_nota ,
                    case when ich.d_nota_entry notnull then ich.d_nota-rm.d_so else null end as d4,
                    rm.v_so ,
                    ich.v_nota_netto 
                from
                    tm_so rm
                inner join tr_customer io on (io.i_customer=rm.i_customer)
                inner join tr_status_so mr on (mr.i_status_so=rm.i_status_so)
                inner join  tr_area krn on (krn.i_area=rm.i_area)
                inner join tr_city yz on (yz.i_city=io.i_city)
                left join tm_do miu on (miu.i_so = rm.i_so and miu.i_company = '2' and miu.f_do_cancel = 'f' and miu.d_do  between '2023-08-01' and '2023-08-31')
                left join tm_nota ich on (ich.i_so = rm.i_so and ich.i_company = '2' and ich.f_nota_cancel = 'f' and ich.d_nota  between '2023-08-01' and '2023-08-31')
                where 
                    rm.i_company = '$this->i_company'
                    and rm.f_so_cancel ='f'
                    and rm.d_so between '$dfrom' AND '$dto'
                    $status_so
                    $area
                ORDER BY
                    1 ASC
                    ", FALSE);
    }
}

/* End of file Mmaster.php */
