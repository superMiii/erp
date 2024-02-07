<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Minfocollc2 extends CI_Model
{

    /** List Datatable */
    public function serverside()
    {
        $month = $this->input->post('month', TRUE);
        if ($month == '') {
            $month = $this->uri->segment(3);
        }

        $year = $this->input->post('year', TRUE);
        if ($year == '') {
            $year = $this->uri->segment(4);
        }

        $i_area = $this->input->post('i_area', TRUE);
        if ($i_area == '') {
            $i_area = $this->uri->segment(3);
        }

        if ($i_area != '0') {
            $area = "AND y.i_area = '$i_area' ";
        } else {
            $area = "";
        }

        $i_salesman = $this->input->post('i_salesman', TRUE);
        if ($i_salesman == '') {
            $i_salesman = $this->uri->segment(6);
        }

        if ($i_salesman != '0') {
            $salesman = "AND p.i_salesman = '$i_salesman' ";
        } else {
            $salesman = "";
        }

        $i_periode = $year . $month;
        // $i_periode  = date('Ym');
        // $dfrom  = date('Y-m-d', strtotime($dfrom));
        // $dto    = date('Y-m-d', strtotime($dto));

        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("SELECT
                    t.i_nota ,
                        u.i_area_id || ' - ' || u.e_area_name as areaaa,
                        p.e_salesman_name as salesss ,
                        y.i_nota_id,
                        y.d_nota,
                        y.d_jatuh_tempo,
                        i.i_customer_id as i_customer_id ,
                        i.e_customer_name as e_customer_name ,
                        i.n_customer_top ,
                        y.v_nota_netto::money as v_nota_netto ,
                        t.v_target::money as v_target ,
                        t.v_realisasi::money as v_realisasi ,
                        case 
                            when t.v_target = 0 then '0.00 %'
                            else round((t.v_realisasi /t.v_target)* 100, 2) || ' %' end as persen
                    from ( select
                            i_nota ,
                            sum(v_target) as v_target ,
                            sum(v_realisasi) as v_realisasi
                        from ( select
                                i_nota,
                                v_sisa as v_target,
                                0 as v_realisasi
                            from
                                tm_nota
                            where
                                f_nota_cancel = 'f'
                                and to_char(d_jatuh_tempo, 'yyyymm') <= '$i_periode'
                        union all
                            select
                                i_nota,
                                a.v_jumlah as v_target,
                                0 as v_realisasi
                            from
                                tm_alokasi_item a,
                                tm_alokasi b
                            where
                                a.i_alokasi = b.i_alokasi
                                and b.f_alokasi_cancel = 'f'
                                and to_char(b.d_alokasi, 'yyyymm') >= '$i_periode'
                        union all
                            select
                                i_nota,
                                0 as v_target,
                                a.v_jumlah as v_realisasi
                            from
                                tm_alokasi_item a,
                                tm_alokasi b
                            where
                                a.i_alokasi = b.i_alokasi
                                and b.f_alokasi_cancel = 'f'
                                and to_char(b.d_alokasi, 'yyyymm') = '$i_periode' 
                    ) as r group by 1) as t
                    inner join tm_nota y on	(y.i_nota = t.i_nota)
                    inner join tr_area u on	(u.i_area = y.i_area)
                    inner join tr_customer i on	(i.i_customer = y.i_customer)
                    left join tm_do o on (o.i_do_id = y.i_nota_id)
                    inner join tr_salesman p on	(p.i_salesman = o.i_salesman)
                    where
                        y.i_company = '$this->i_company'
                        $area
                        $salesman
                    order by y.d_jatuh_tempo 
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

    public function get_salesman($cari, $i_area)
    {
        if ($i_area != '0') {
            $area = "AND d.i_area = '$i_area' ";
        } else {
            $area = "";
        }
        return $this->db->query("SELECT distinct 
                a.i_salesman,
                a.i_salesman_id ,
                a.e_salesman_name
            from
                tr_salesman a
                inner join tr_salesman_areacover b on (b.i_salesman=a.i_salesman)
                inner join tr_area_cover_item c on (c.i_area_cover =b.i_area_cover)	
                inner join tr_area d on (d.i_area =c.i_area)
            WHERE 
                (a.e_salesman_name ILIKE '%$cari%')
                AND a.i_company = '$this->i_company' 
                AND a.f_salesman_active = 'true' 
                $area
            ORDER BY 3 ASC
        ", FALSE);
    }

    /** Get Data Untuk Edit */
    public function get_data($month, $year, $i_area, $i_salesman)
    {
        $year = $this->input->post('year', TRUE);
        if ($year == '') {
            $year = $this->uri->segment(3);
            if ($year == '') {
                $year = date('Y');
            }
        }
        $month = $this->input->post('month', TRUE);
        if ($month == '') {
            $month = $this->uri->segment(4);
            if ($month == '') {
                $month = date('m');
            }
        }

        $i_area = $this->input->post('i_area', TRUE);
        if ($i_area == '') {
            $i_area = $this->uri->segment(5);
            if ($i_area == '') {
                $i_area = 0;
            }
        }


        $i_salesman = $this->input->post('i_salesman', TRUE);
        if ($i_salesman == '') {
            $i_salesman = $this->uri->segment(6);
            if ($i_salesman == '') {
                $i_salesman = 0;
            }
        }

        if ($i_salesman != '0') {
            $salesman = "AND p.i_salesman = '$i_salesman' ";
        } else {
            $salesman = "";
        }

        if ($i_area != '0') {
            $area = "AND y.i_area = '$i_area' ";
        } else {
            $area = "";
        }

        $i_periode = $year . $month;

        return $this->db->query("SELECT
                        t.i_nota ,
                        u.i_area_id || ' - ' || u.e_area_name as areaaa,
                        p.e_salesman_name as salesss ,
                        y.i_nota_id,
                        y.d_nota,
                        y.d_jatuh_tempo,
                        i.i_customer_id as i_customer_id ,
                        i.e_customer_name as e_customer_name ,
                        i.n_customer_top ,
                        y.v_nota_netto as v_nota_netto ,
                        t.v_target as v_target ,
                        t.v_realisasi as v_realisasi ,
                        case 
                            when t.v_target = 0 then '0.00 %'
                            else round((t.v_realisasi /t.v_target)* 100, 2) || ' %' end as persen
                    from ( select
                            i_nota ,
                            sum(v_target) as v_target ,
                            sum(v_realisasi) as v_realisasi
                        from ( select
                                i_nota,
                                v_sisa as v_target,
                                0 as v_realisasi
                            from
                                tm_nota
                            where
                                f_nota_cancel = 'f'
                                and to_char(d_jatuh_tempo, 'yyyymm') <= '$i_periode'
                        union all
                            select
                                i_nota,
                                a.v_jumlah as v_target,
                                0 as v_realisasi
                            from
                                tm_alokasi_item a,
                                tm_alokasi b
                            where
                                a.i_alokasi = b.i_alokasi
                                and b.f_alokasi_cancel = 'f'
                                and to_char(b.d_alokasi, 'yyyymm') >= '$i_periode'
                        union all
                            select
                                i_nota,
                                0 as v_target,
                                a.v_jumlah as v_realisasi
                            from
                                tm_alokasi_item a,
                                tm_alokasi b
                            where
                                a.i_alokasi = b.i_alokasi
                                and b.f_alokasi_cancel = 'f'
                                and to_char(b.d_alokasi, 'yyyymm') = '$i_periode' 
                    ) as r group by 1) as t
                    inner join tm_nota y on	(y.i_nota = t.i_nota)
                    inner join tr_area u on	(u.i_area = y.i_area)
                    inner join tr_customer i on	(i.i_customer = y.i_customer)
                    left join tm_do o on (o.i_do_id = y.i_nota_id)
                    inner join tr_salesman p on	(p.i_salesman = o.i_salesman)
                    where
                        y.i_company = '$this->i_company'
                        $area
                        $salesman
                    order by y.d_jatuh_tempo 
        ", FALSE);
    }
}

/* End of file Mmaster.php */
