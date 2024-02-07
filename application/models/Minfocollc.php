<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Minfocollc extends CI_Model
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
            $area = "AND mi.i_area = '$i_area' ";
        } else {
            $area = "";
        }

        $i_salesman = $this->input->post('i_salesman', TRUE);
        if ($i_salesman == '') {
            $i_salesman = $this->uri->segment(6);
        }

        if ($i_salesman != '0') {
            $salesman = "AND ss.i_salesman = '$i_salesman' ";
        } else {
            $salesman = "";
        }

        $i_periode = $year . $month;
        // $i_periode  = date('Ym');
        // $dfrom  = date('Y-m-d', strtotime($dfrom));
        // $dto    = date('Y-m-d', strtotime($dto));

        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("SELECT
                    cr.nota as i_nota,
                    cr.com as i_company,
                    u.i_area as i_area,
                    u.i_area_id || ' - ' || u.e_area_name as areaaa,
                    p.i_salesman as i_salesman ,
                    p.e_salesman_name as salesss ,
                    y.i_nota_id as i_nota_id,
                    y.d_nota as d_nota,
                    i.i_customer_id as i_customer_id ,
                    i.e_customer_name as e_customer_name ,
                    i.n_customer_top as n_customer_top,
                    ct.n_toleransi as n_toleransi,
                    y.d_jatuh_tempo + ct.n_toleransi as d_jatuh_tempo,
                    (current_date - (y.d_jatuh_tempo + ct.n_toleransi)) as lama,
                    y.v_nota_netto::money as v_nota_netto,
                    cr.v_target::money as v_target,
                    cr.v_realisasi::money as v_realisasi,
                    case
                        when cr.v_target = 0 then '0.00 %'
                        else round((cr.v_realisasi / cr.v_target)* 100, 2) || ' %'
                    end as persen,
                    y.e_remark as e_remark
                from (
                    select
                        com,
                        nota,
                        ara,
                        sal,
                        cus,
                        sum(v_target) as v_target,
                        sum(v_realisasi) as v_realisasi
                    from (
                        select
                            com,
                            nota,
                            ara,
                            sal,
                            cus,
                            sum(v_target) as v_target,
                            0 as v_realisasi
                        from (
                            select  
                                re.i_company as com,
                                re.i_nota as nota,
                                re.i_area as ara,
                                ss.i_salesman as sal,
                                re.i_customer as cus,
                                case
                                    when re.f_masalah = 't' and re.v_sisa > 0 then 0.01
                                    else 
                                    re.v_sisa
                                end as v_target
                            from
                                tm_nota re 
                                inner join tr_customer mu on (mu.i_customer=re.i_customer)
                                inner join tr_city su on (su.i_city=mu.i_city)
                                inner join tm_do sj on (sj.i_do_id = re.i_nota_id and sj.i_company = re.i_company and f_nota_cancel ='f')
                                inner join tr_salesman ss on (ss.i_salesman = sj.i_salesman)
                                inner join tr_area mi on (mi.i_area=re.i_area)
                            where 
                                f_nota_cancel ='f'
                                and re.i_company = '$this->i_company'
                                and to_char((re.d_jatuh_tempo + su.n_toleransi), 'yyyymm') <= '$i_periode'
                                $area
                                $salesman
                            union all	
                            select
                                mi.i_company as com,	
                                mi.i_nota as nota,
                                mi.i_area as ara,
                                ss.i_salesman as sal,
                                re.i_customer as cus,
                                case
                                    when re.f_masalah = 't' and mi.v_jumlah > 0 then 0.01
                                    else 
                                    mi.v_jumlah
                                end as v_target
                            from
                                tm_alokasi_item mi
                                inner join tm_alokasi ru on (mi.i_alokasi=ru.i_alokasi)
                                inner join tm_nota re on (re.i_nota=mi.i_nota)
                                inner join tr_customer mu on (mu.i_customer=re.i_customer)
                                inner join tr_city su on (su.i_city=mu.i_city)
                                inner join tm_do sj on (sj.i_do_id = re.i_nota_id and sj.i_company = re.i_company and f_nota_cancel ='f')
                                inner join tr_salesman ss on (ss.i_salesman = sj.i_salesman)
                            where 
                                ru.f_alokasi_cancel ='f'
                                and ru.i_company = '$this->i_company'
                                and to_char((ru.d_alokasi), 'yyyymm') >= '$i_periode'
                                and to_char((re.d_jatuh_tempo + su.n_toleransi), 'yyyymm') <= '$i_periode'
                                $area
                                $salesman
                            union all	
                            select
                                mi.i_company as com,	
                                mi.i_nota as nota,
                                mi.i_area as ara,
                                ss.i_salesman as sal,
                                re.i_customer as cus,
                                case
                                    when re.f_masalah = 't' and mi.v_jumlah > 0 then 0.01
                                    else 
                                    mi.v_jumlah
                                end as v_target
                            from
                                tm_alokasi_kas_item mi
                                inner join tm_alokasi_kas ru on (mi.i_alokasi=ru.i_alokasi)
                                inner join tm_nota re on (re.i_nota=mi.i_nota)
                                inner join tr_customer mu on (mu.i_customer=re.i_customer)
                                inner join tr_city su on (su.i_city=mu.i_city)
                                inner join tm_do sj on (sj.i_do_id = re.i_nota_id and sj.i_company = re.i_company and f_nota_cancel ='f')
                                inner join tr_salesman ss on (ss.i_salesman = sj.i_salesman)
                            where 
                                ru.f_alokasi_cancel ='f'
                                and ru.i_company = '$this->i_company'
                                and to_char((ru.d_alokasi), 'yyyymm') >= '$i_periode'
                                and to_char((re.d_jatuh_tempo + su.n_toleransi), 'yyyymm') <= '$i_periode'
                                $area
                                $salesman
                        ) as sq group by 1,2,3,4,5
                        union all
                        select
                            com,
                            nota,
                            ara,
                            sal,
                            cus,
                            0 as v_target,
                            sum(v_realisasi) as v_realisasi
                        from (
                            select
                                mi.i_company as com,
                                mi.i_nota as nota,
                                mi.i_area as ara,
                                ss.i_salesman as sal,
                                re.i_customer as cus,
                                mi.v_jumlah as v_realisasi
                            from
                                tm_alokasi_item mi
                                inner join tm_alokasi ru on (mi.i_alokasi=ru.i_alokasi)
                                inner join tm_nota re on (re.i_nota=mi.i_nota)
                                inner join tr_customer mu on (mu.i_customer=re.i_customer)
                                inner join tm_do sj on (sj.i_do_id = re.i_nota_id and sj.i_company = re.i_company and f_nota_cancel ='f')
                                inner join tr_salesman ss on (ss.i_salesman = sj.i_salesman)
                            where 
                                ru.f_alokasi_cancel ='f'
                                and ru.i_company = '$this->i_company'
                                and to_char((ru.d_alokasi), 'yyyymm') = '$i_periode'
                                $area
                                $salesman
                            union all
                            select
                                mi.i_company as com,	
                                mi.i_nota as nota,
                                mi.i_area as ara,
                                ss.i_salesman as sal,
                                re.i_customer as cus,
                                mi.v_jumlah as v_realisasi
                            from
                                tm_alokasi_kas_item mi
                                inner join tm_alokasi_kas ru on (mi.i_alokasi=ru.i_alokasi)
                                inner join tm_nota re on (re.i_nota=mi.i_nota)
                                inner join tr_customer mu on (mu.i_customer=re.i_customer)
                                inner join tm_do sj on (sj.i_do_id = re.i_nota_id and sj.i_company = re.i_company and f_nota_cancel ='f')
                                inner join tr_salesman ss on (ss.i_salesman = sj.i_salesman)
                            where 
                                ru.f_alokasi_cancel ='f'
                                and ru.i_company = '$this->i_company'
                                and to_char((ru.d_alokasi), 'yyyymm') = '$i_periode'
                                $area
                                $salesman
                        ) as rt group by 1,2,3,4,5
                    ) as ng group by 1,2,3,4,5
                ) as cr 
                inner join tr_area u on (u.i_area=cr.ara)
                inner join tm_user_area uu on (uu.i_area = u.i_area and uu.i_user = '$this->i_user')
                inner join tr_salesman p on (p.i_salesman = cr.sal )
                inner join tr_customer i on (i.i_customer = cr.cus)
                inner join tr_city ct on (ct.i_city =i.i_city)
                inner join tm_nota y on (y.i_nota = cr.nota) 
                where cr.v_target>0 or cr.v_realisasi>0
        ", FALSE);
        $datatables->hide('i_company');
        $datatables->hide('i_area');
        $datatables->hide('i_salesman');
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
                inner join tm_user_area uu on (uu.i_area = d.i_area and uu.i_user = '$this->i_user')
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
            $salesman = "AND ss.i_salesman = '$i_salesman' ";
        } else {
            $salesman = "";
        }

        if ($i_area != '0') {
            $area = "AND mi.i_area = '$i_area' ";
        } else {
            $area = "";
        }

        $i_periode = $year . $month;

        return $this->db->query("SELECT
                        cr.nota as i_nota,
                        cr.com as i_company,
                        u.i_area as i_area,
                        u.i_area_id || ' - ' || u.e_area_name as areaaa,
                        p.i_salesman as i_salesman ,
                        p.e_salesman_name as salesss ,
                        y.i_nota_id as i_nota_id,
                        y.d_nota as d_nota,
                        i.i_customer_id as i_customer_id ,
                        i.e_customer_name as e_customer_name ,
                        i.n_customer_top as n_customer_top,
                        ct.n_toleransi as n_toleransi,
                        y.d_jatuh_tempo + ct.n_toleransi as d_jatuh_tempo,
                        (current_date - (y.d_jatuh_tempo + ct.n_toleransi)) as lama,
                        y.v_nota_netto as v_nota_netto,
                        cr.v_target as v_target,
                        cr.v_realisasi as v_realisasi,
                        case
                            when cr.v_target = 0 then '0.00 %'
                            else round((cr.v_realisasi / cr.v_target)* 100, 2) || ' %'
                        end as persen,
                        y.e_remark as e_remark
                    from (
                        select
                            com,
                            nota,
                            ara,
                            sal,
                            cus,
                            sum(v_target) as v_target,
                            sum(v_realisasi) as v_realisasi
                        from (
                            select
                                com,
                                nota,
                                ara,
                                sal,
                                cus,
                                sum(v_target) as v_target,
                                0 as v_realisasi
                            from (
                                select  
                                    re.i_company as com,
                                    re.i_nota as nota,
                                    re.i_area as ara,
                                    ss.i_salesman as sal,
                                    re.i_customer as cus,
                                    case
                                        when re.f_masalah = 't' and re.v_sisa > 0 then 0.01
                                        else 
                                        re.v_sisa
                                    end as v_target
                                from
                                    tm_nota re 
                                    inner join tr_customer mu on (mu.i_customer=re.i_customer)
                                    inner join tr_city su on (su.i_city=mu.i_city)
                                    inner join tm_do sj on (sj.i_do_id = re.i_nota_id and sj.i_company = re.i_company and f_nota_cancel ='f')
                                    inner join tr_salesman ss on (ss.i_salesman = sj.i_salesman)
                                    inner join tr_area mi on (mi.i_area=re.i_area)
                                where 
                                    f_nota_cancel ='f'
                                    and re.i_company = '$this->i_company'
                                    and to_char((re.d_jatuh_tempo + su.n_toleransi), 'yyyymm') <= '$i_periode'
                                    $area
                                    $salesman
                                union all	
                                select
                                    mi.i_company as com,	
                                    mi.i_nota as nota,
                                    mi.i_area as ara,
                                    ss.i_salesman as sal,
                                    re.i_customer as cus,
                                    case
                                        when re.f_masalah = 't' and mi.v_jumlah > 0 then 0.01
                                        else 
                                        mi.v_jumlah
                                    end as v_target
                                from
                                    tm_alokasi_item mi
                                    inner join tm_alokasi ru on (mi.i_alokasi=ru.i_alokasi)
                                    inner join tm_nota re on (re.i_nota=mi.i_nota)
                                    inner join tr_customer mu on (mu.i_customer=re.i_customer)
                                    inner join tr_city su on (su.i_city=mu.i_city)
                                    inner join tm_do sj on (sj.i_do_id = re.i_nota_id and sj.i_company = re.i_company and f_nota_cancel ='f')
                                    inner join tr_salesman ss on (ss.i_salesman = sj.i_salesman)
                                where 
                                    ru.f_alokasi_cancel ='f'
                                    and ru.i_company = '$this->i_company'
                                    and to_char((ru.d_alokasi), 'yyyymm') >= '$i_periode'
                                    and to_char((re.d_jatuh_tempo + su.n_toleransi), 'yyyymm') <= '$i_periode'
                                    $area
                                    $salesman
                                union all	
                                select
                                    mi.i_company as com,	
                                    mi.i_nota as nota,
                                    mi.i_area as ara,
                                    ss.i_salesman as sal,
                                    re.i_customer as cus,
                                    case
                                        when re.f_masalah = 't' and mi.v_jumlah > 0 then 0.01
                                        else 
                                        mi.v_jumlah
                                    end as v_target
                                from
                                    tm_alokasi_kas_item mi
                                    inner join tm_alokasi_kas ru on (mi.i_alokasi=ru.i_alokasi)
                                    inner join tm_nota re on (re.i_nota=mi.i_nota)
                                    inner join tr_customer mu on (mu.i_customer=re.i_customer)
                                    inner join tr_city su on (su.i_city=mu.i_city)
                                    inner join tm_do sj on (sj.i_do_id = re.i_nota_id and sj.i_company = re.i_company and f_nota_cancel ='f')
                                    inner join tr_salesman ss on (ss.i_salesman = sj.i_salesman)
                                where 
                                    ru.f_alokasi_cancel ='f'
                                    and ru.i_company = '$this->i_company'
                                    and to_char((ru.d_alokasi), 'yyyymm') >= '$i_periode'
                                    and to_char((re.d_jatuh_tempo + su.n_toleransi), 'yyyymm') <= '$i_periode'
                                    $area
                                    $salesman
                            ) as sq group by 1,2,3,4,5
                            union all
                            select
                                com,
                                nota,
                                ara,
                                sal,
                                cus,
                                0 as v_target,
                                sum(v_realisasi) as v_realisasi
                            from (
                                select
                                    mi.i_company as com,
                                    mi.i_nota as nota,
                                    mi.i_area as ara,
                                    ss.i_salesman as sal,
                                    re.i_customer as cus,
                                    mi.v_jumlah as v_realisasi
                                from
                                    tm_alokasi_item mi
                                    inner join tm_alokasi ru on (mi.i_alokasi=ru.i_alokasi)
                                    inner join tm_nota re on (re.i_nota=mi.i_nota)
                                    inner join tr_customer mu on (mu.i_customer=re.i_customer)
                                    inner join tm_do sj on (sj.i_do_id = re.i_nota_id and sj.i_company = re.i_company and f_nota_cancel ='f')
                                    inner join tr_salesman ss on (ss.i_salesman = sj.i_salesman)
                                where 
                                    ru.f_alokasi_cancel ='f'
                                    and ru.i_company = '$this->i_company'
                                    and to_char((ru.d_alokasi), 'yyyymm') = '$i_periode'
                                    $area
                                    $salesman
                                union all
                                select
                                    mi.i_company as com,	
                                    mi.i_nota as nota,
                                    mi.i_area as ara,
                                    ss.i_salesman as sal,
                                    re.i_customer as cus,
                                    mi.v_jumlah as v_realisasi
                                from
                                    tm_alokasi_kas_item mi
                                    inner join tm_alokasi_kas ru on (mi.i_alokasi=ru.i_alokasi)
                                    inner join tm_nota re on (re.i_nota=mi.i_nota)
                                    inner join tr_customer mu on (mu.i_customer=re.i_customer)
                                    inner join tm_do sj on (sj.i_do_id = re.i_nota_id and sj.i_company = re.i_company and f_nota_cancel ='f')
                                    inner join tr_salesman ss on (ss.i_salesman = sj.i_salesman)
                                where 
                                    ru.f_alokasi_cancel ='f'
                                    and ru.i_company = '$this->i_company'
                                    and to_char((ru.d_alokasi), 'yyyymm') = '$i_periode'
                                    $area
                                    $salesman
                            ) as rt group by 1,2,3,4,5
                        ) as ng group by 1,2,3,4,5
                    ) as cr 
                    inner join tr_area u on (u.i_area=cr.ara)
                    inner join tm_user_area uu on (uu.i_area = u.i_area and uu.i_user = '$this->i_user')
                    inner join tr_salesman p on (p.i_salesman = cr.sal )
                    inner join tr_customer i on (i.i_customer = cr.cus)
                    inner join tr_city ct on (ct.i_city =i.i_city)
                    inner join tm_nota y on (y.i_nota = cr.nota) 
                    where cr.v_target>0 or cr.v_realisasi>0
                        ", FALSE);
    }
}
/* End of file Mmaster.php */
