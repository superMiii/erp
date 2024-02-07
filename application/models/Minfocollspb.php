<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Minfocollspb extends CI_Model
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
            $area = "AND a.i_area = '$i_area' ";
        } else {
            $area = "";
        }

        $i_salesman = $this->input->post('i_salesman', TRUE);
        if ($i_salesman == '') {
            $i_salesman = $this->uri->segment(6);
        }

        if ($i_salesman != '0') {
            $salesman = "AND b.i_salesman = '$i_salesman' ";
        } else {
            $salesman = "";
        }

        $i_periode = $year;
        // $i_periode  = date('Ym');
        // $dfrom  = date('Y-m-d', strtotime($dfrom));
        // $dto    = date('Y-m-d', strtotime($dto));

        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("SELECT 
                    lb.i_company,
                    lb.periode,
                    a.i_area_id || ' - ' || a.e_area_name as e_area_name,
                    b.e_salesman_name,
                    target::money as target,
                    so::money as so,
                    retur::money as retur,
                    nota::money as nota,
                    case
                        when target = 0 then '0.00 %'
                        else round((nota / target)* 100, 2) || ' %'
                    end as persen1,
                    0 as insentif1,
                    '-' as pendingan,
                    '0.00 %' as persen2,
                    sisa::money as sisa,
                    realisasi::money as realisasi,
                    case
                        when sisa = 0 then '0.00 %'
                        else round((realisasi / sisa)* 100, 2) || ' %'
                    end as persen3,
                    0 as insentif2,
                    gohari::money as gohari,
                    case
                        when sisa = 0 then '0.00 %'
                        else round((gohari / sisa)* 100, 2) || ' %'
                    end as persen4,
                    0 as insentif3,
                    case
                        when gohari = 0 then 'TIDAK'
                        else 'YA'
                    end as stat
                from (
                    select i_company, periode, i_area,i_salesman,
                    sum(v_target) as target,sum(v_so) as so,  sum(v_kn) as retur, sum(v_nota) as nota,
                    sum(v_sisa) as sisa, sum(v_realisasi) as realisasi, sum(gohari) as gohari  
                    from 
                    (
                        select
                            a.i_company,
                            to_char(d_so , 'yyyymm') as periode,
                            a.i_area ,
                            b.i_salesman ,
                            sum(v_so) as v_so,
                            0 as v_target,
                            0 as v_kn ,
                            0 as v_nota,
                            0 as v_sisa,
                            0 as v_realisasi,
                            0 as gohari
                        from
                            tm_so a 
                        inner join tr_salesman b on (b.i_salesman=a.i_salesman)
                        where
                            f_so_cancel = 'f'
                            and a.i_company = '$this->i_company'
                            and to_char(d_so, 'yyyy') = '$i_periode'
                            $area
                            $salesman
                        group by 1,2,3,4
                        union All
                        select
                            a.i_company,
                            i_periode as periode,
                            a.i_area ,
                            b.i_salesman ,
                            0 as v_so,
                            sum(v_target) as v_target,
                            0 as v_kn,
                            0 as v_nota,
                            0 as v_sisa,
                            0 as v_realisasi,
                            0 as gohari
                        from
                            tm_target_item a
                        inner join tr_salesman b on (b.i_salesman=a.i_salesman)
                        where
                            a.i_company = '$this->i_company'
                            and left(i_periode,4) = '$i_periode'
                            $area
                            $salesman
                        group by 1,2,3,4
                        union all 
                        select
                            a.i_company,
                            to_char(d_kn, 'yyyymm') as periode,
                            a.i_area ,
                            b.i_salesman ,
                            0 as v_so ,
                            0 as v_target ,
                            sum(v_netto) as v_kn,
                            0 as v_nota,
                            0 as v_sisa,
                            0 as v_realisasi,
                            0 as gohari
                        from
                            tm_kn a
                        inner join tr_salesman b on (b.i_salesman=a.i_salesman)
                        where 
                            f_kn_retur = 't'
                            and f_kn_cancel = 'f'
                            and a.i_company = '$this->i_company'
                            and to_char(d_kn, 'yyyy') = '$i_periode'
                            $area
                            $salesman
                        group by 1,2,3,4
                        union all
                        select
                            a.i_company,
                            to_char(a.d_nota,'yyyymm') as periode,
                            a.i_area ,
                            b.i_salesman ,
                            0 as v_so ,
                            0 as v_target ,
                            0 as v_kn,
                            sum(a.v_nota_netto) as v_nota,
                            0 as v_sisa,
                            0 as v_realisasi,
                            0 as gohari
                        from
                            tm_nota a
                        inner join tm_do b on (b.i_do_id =a.i_nota_id and b.i_company=a.i_company)
                        where 
                            a.f_nota_cancel = 'f'
                            and a.i_company = '$this->i_company'
                            and to_char(a.d_nota, 'yyyy') = '$i_periode'
                            $area
                            $salesman
                        group by 1,2,3,4
                        union all 
                        select
                            a.i_company,
                            to_char(a.d_nota,'yyyymm') as periode,
                            a.i_area ,
                            b.i_salesman ,
                            0 as v_so ,
                            0 as v_target ,
                            0 as v_kn,
                            0 as v_nota,
                            sum(a.v_sisa) as v_sisa,
                            0 as v_realisasi,
                            0 as gohari
                        from
                            tm_nota a
                        inner join tm_do b on (b.i_do_id =a.i_nota_id and b.i_company=a.i_company)
                        where 
                            a.f_nota_cancel = 'f'
                            and a.i_company = '$this->i_company'
                            and to_char(a.d_nota, 'yyyy') = '$i_periode'
                            $area
                            $salesman
                        group by 1,2,3,4
                        union all 
                        select
                            a.i_company,
                            to_char(a.d_nota ,'yyyymm') as periode,
                            a.i_area ,
                            b.i_salesman ,
                            0 as v_so ,
                            0 as v_target ,
                            0 as v_kn,
                            0 as v_nota,
                            0 as v_sisa,
                            a.v_jumlah as v_realisasi,
                            0 as gohari
                        from
                            tm_alokasi_item a
                        inner join tm_nota n on (n.i_nota=a.i_nota)
                        inner join tm_do b on (b.i_do_id =n.i_nota_id and b.i_company=n.i_company)
                        inner join tm_alokasi l on (l.i_alokasi=a.i_alokasi)
                        where 
                            l.f_alokasi_cancel = 'f'
                            and a.i_company = '$this->i_company'
                            and to_char(a.d_nota, 'yyyy') = '$i_periode'
                            $area
                            $salesman
                        union all 
                        select
                            a.i_company,
                            to_char(a.d_nota,'yyyymm') as periode,
                            a.i_area ,
                            b.i_salesman ,
                            0 as v_so ,
                            0 as v_target ,
                            0 as v_kn,
                            0 as v_nota,
                            0 as v_sisa,
                            0 as v_realisasi,
                            sum(a.v_sisa) as gohari
                        from
                            tm_nota a
                        inner join tm_do b on (b.i_do_id =a.i_nota_id and b.i_company=a.i_company)
                        where 
                            a.f_nota_cancel = 'f'
                            and (a.d_jatuh_tempo + interval '90 day') <= now()
                            and a.i_company = '$this->i_company'
                            and to_char(a.d_nota, 'yyyy') = '$i_periode'
                            $area
                            $salesman
                        group by 1,2,3,4
                    ) as z
                    group by 1,2,3,4
                    order by 2,3
                ) as lb 
                inner join tr_area a on (a.i_area=lb.i_area)
                inner join tr_salesman b on (b.i_salesman=lb.i_salesman)
        ", FALSE);
        // $datatables->hide('i_company');
        // $datatables->hide('i_area');
        // $datatables->hide('i_salesman');
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
            $salesman = "AND b.i_salesman = '$i_salesman' ";
        } else {
            $salesman = "";
        }

        if ($i_area != '0') {
            $area = "AND a.i_area = '$i_area' ";
        } else {
            $area = "";
        }

        $i_periode = $year;

        return $this->db->query("SELECT 
                lb.i_company,
                lb.periode,
                a.i_area_id || ' - ' || a.e_area_name as e_area_name,
                b.e_salesman_name,
                target,
                so,
                retur,
                nota,
                case
                    when target = 0 then '0.00 %'
                    else round((nota / target)* 100, 2) || ' %'
                end as persen1,
                0 as insentif1,
                '-' as pendingan,
                '0.00 %' as persen2,
                sisa,
                realisasi,
                case
                    when sisa = 0 then '0.00 %'
                    else round((realisasi / sisa)* 100, 2) || ' %'
                end as persen3,
                0 as insentif2,
                gohari,
                case
                    when sisa = 0 then '0.00 %'
                    else round((gohari / sisa)* 100, 2) || ' %'
                end as persen4,
                0 as insentif3,
                case
                    when gohari = 0 then 'TIDAK'
                    else 'YA'
                end as stat
            from (
                select i_company, periode, i_area,i_salesman,
                sum(v_target) as target,sum(v_so) as so,  sum(v_kn) as retur, sum(v_nota) as nota,
                sum(v_sisa) as sisa, sum(v_realisasi) as realisasi, sum(gohari) as gohari  
                from 
                (
                    select
                        a.i_company,
                        to_char(d_so , 'yyyymm') as periode,
                        a.i_area ,
                        b.i_salesman ,
                        sum(v_so) as v_so,
                        0 as v_target,
                        0 as v_kn ,
                        0 as v_nota,
                        0 as v_sisa,
                        0 as v_realisasi,
                        0 as gohari
                    from
                        tm_so a 
                    inner join tr_salesman b on (b.i_salesman=a.i_salesman)
                    where
                        f_so_cancel = 'f'
                        and a.i_company = '$this->i_company'
                        and to_char(d_so, 'yyyy') = '$i_periode'
                        $area
                        $salesman
                    group by 1,2,3,4
                    union All
                    select
                        a.i_company,
                        i_periode as periode,
                        a.i_area ,
                        b.i_salesman ,
                        0 as v_so,
                        sum(v_target) as v_target,
                        0 as v_kn,
                        0 as v_nota,
                        0 as v_sisa,
                        0 as v_realisasi,
                        0 as gohari
                    from
                        tm_target_item a
                    inner join tr_salesman b on (b.i_salesman=a.i_salesman)
                    where
                        a.i_company = '$this->i_company'
                        and left(i_periode,4) = '$i_periode'
                        $area
                        $salesman
                    group by 1,2,3,4
                    union all 
                    select
                        a.i_company,
                        to_char(d_kn, 'yyyymm') as periode,
                        a.i_area ,
                        b.i_salesman ,
                        0 as v_so ,
                        0 as v_target ,
                        sum(v_netto) as v_kn,
                        0 as v_nota,
                        0 as v_sisa,
                        0 as v_realisasi,
                        0 as gohari
                    from
                        tm_kn a
                    inner join tr_salesman b on (b.i_salesman=a.i_salesman)
                    where 
                        f_kn_retur = 't'
                        and f_kn_cancel = 'f'
                        and a.i_company = '$this->i_company'
                        and to_char(d_kn, 'yyyy') = '$i_periode'
                        $area
                        $salesman
                    group by 1,2,3,4
                    union all
                    select
                        a.i_company,
                        to_char(a.d_nota,'yyyymm') as periode,
                        a.i_area ,
                        b.i_salesman ,
                        0 as v_so ,
                        0 as v_target ,
                        0 as v_kn,
                        sum(a.v_nota_netto) as v_nota,
                        0 as v_sisa,
                        0 as v_realisasi,
                        0 as gohari
                    from
                        tm_nota a
                    inner join tm_do b on (b.i_do_id =a.i_nota_id and b.i_company=a.i_company)
                    where 
                        a.f_nota_cancel = 'f'
                        and a.i_company = '$this->i_company'
                        and to_char(a.d_nota, 'yyyy') = '$i_periode'
                        $area
                        $salesman
                    group by 1,2,3,4
                    union all 
                    select
                        a.i_company,
                        to_char(a.d_nota,'yyyymm') as periode,
                        a.i_area ,
                        b.i_salesman ,
                        0 as v_so ,
                        0 as v_target ,
                        0 as v_kn,
                        0 as v_nota,
                        sum(a.v_sisa) as v_sisa,
                        0 as v_realisasi,
                        0 as gohari
                    from
                        tm_nota a
                    inner join tm_do b on (b.i_do_id =a.i_nota_id and b.i_company=a.i_company)
                    where 
                        a.f_nota_cancel = 'f'
                        and a.i_company = '$this->i_company'
                        and to_char(a.d_nota, 'yyyy') = '$i_periode'
                        $area
                        $salesman
                    group by 1,2,3,4
                    union all 
                    select
                        a.i_company,
                        to_char(a.d_nota ,'yyyymm') as periode,
                        a.i_area ,
                        b.i_salesman ,
                        0 as v_so ,
                        0 as v_target ,
                        0 as v_kn,
                        0 as v_nota,
                        0 as v_sisa,
                        a.v_jumlah as v_realisasi,
                        0 as gohari
                    from
                        tm_alokasi_item a
                    inner join tm_nota n on (n.i_nota=a.i_nota)
                    inner join tm_do b on (b.i_do_id =n.i_nota_id and b.i_company=n.i_company)
                    inner join tm_alokasi l on (l.i_alokasi=a.i_alokasi)
                    where 
                        l.f_alokasi_cancel = 'f'
                        and a.i_company = '$this->i_company'
                        and to_char(a.d_nota, 'yyyy') = '$i_periode'
                        $area
                        $salesman
                    union all 
                    select
                        a.i_company,
                        to_char(a.d_nota,'yyyymm') as periode,
                        a.i_area ,
                        b.i_salesman ,
                        0 as v_so ,
                        0 as v_target ,
                        0 as v_kn,
                        0 as v_nota,
                        0 as v_sisa,
                        0 as v_realisasi,
                        sum(a.v_sisa) as gohari
                    from
                        tm_nota a
                    inner join tm_do b on (b.i_do_id =a.i_nota_id and b.i_company=a.i_company)
                    where 
                        a.f_nota_cancel = 'f'
                        and (a.d_jatuh_tempo + interval '90 day') <= now()
                        and a.i_company = '$this->i_company'
                        and to_char(a.d_nota, 'yyyy') = '$i_periode'
                        $area
                        $salesman
                    group by 1,2,3,4
                ) as z
                group by 1,2,3,4
                order by 2,3
            ) as lb 
            inner join tr_area a on (a.i_area=lb.i_area)
            inner join tr_salesman b on (b.i_salesman=lb.i_salesman)
        ", FALSE);
    }
}
/* End of file Mmaster.php */
