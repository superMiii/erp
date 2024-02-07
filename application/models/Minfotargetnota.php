<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Minfotargetnota extends CI_Model
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
            $area = "AND i_area = '$i_area' ";
        } else {
            $area = "";
        }

        // $i_salesman = $this->input->post('i_salesman', TRUE);
        // if ($i_salesman == '') {
        //     $i_salesman = $this->uri->segment(6);
        // }

        // if ($i_salesman != 'ALL') {
        //     $salesman = "AND i_salesman = '$i_salesman' ";
        // } else {
        //     $salesman = "";
        // }

        $i_periode = $year . $month;
        // $i_periode  = date('Ym');
        // $dfrom  = date('Y-m-d', strtotime($dfrom));
        // $dto    = date('Y-m-d', strtotime($dto));

        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("SELECT
                    i_company,
                    i_area,
                    e_area_name,
                    i_salesman,
                    e_salesman_name,
                    sum(v_target)::money as v_target,	
                    sum(v_spb)::money as v_spb,
                    case
                        when sum(v_target) > 0 then round((sum(v_spb) / sum(v_target))* 100) || '%'
                        else '0%'
                    end as p_spb,
                    sum(v_nota)::money as v_nota,
                    case
                        when sum(v_target) > 0 then round((sum(v_nota) / sum(v_target))* 100) || '%'
                        else '0%'
                    end as p_nota,  
                    sum(v_spb1)::money as v_spb1,
                    case
                        when sum(v_target) > 0 then round((sum(v_spb1) / sum(v_target))* 100) || '%'
                        else '0%'
                    end as p_spb1,                  
                    sum(v_nota1)::money as v_nota1,
                    case
                        when sum(v_target) > 0 then round((sum(v_nota1) / sum(v_target))* 100) || '%'
                        else '0%'
                    end as p_nota1
                from
                    (
                    
                    /***** GET DATA TARGET *******/
                    select distinct
                        a.i_target_item,
                        a.i_company,
                        b.i_area as i_area,    
                        b.i_area_id || ' - ' || initcap(b.e_area_name) as e_area_name,
                        c.i_salesman,
                        c.i_salesman_id || ' - ' || initcap(c.e_salesman_name) as e_salesman_name,
                        a.v_target,
                        0 as v_spb,
                        0 as v_nota,
                        0 as v_spb1,
                        0 as v_nota1
                    from
                        tm_target_item a
                    inner join tr_area b on
                        (b.i_area = a.i_area)
                    inner join tr_salesman c on
                        (c.i_salesman = a.i_salesman)
                    inner join tm_user_area u on
                        (u.i_area = b.i_area and u.i_user = '$this->i_user')
                    where
                        a.i_company = '$this->i_company'
                        and a.i_periode = '$i_periode'
                    /***** END GET DATA TARGET *******/
                union all

                    /***** GET DATA SPB *******/
                    select distinct
                        a.i_so,
                        a.i_company,
                        b.i_area as i_area,
                        b.i_area_id || ' - ' || initcap(b.e_area_name) as e_area_name,
                        c.i_salesman,
                        c.i_salesman_id || ' - ' || initcap(c.e_salesman_name) as e_salesman_name,
                        0 as v_target,
                        a.v_so as v_spb,
                        0 as v_nota,
                        0 as v_spb1,
                        0 as v_nota1
                    from
                        tm_so a
                    inner join tr_area b on
                        (b.i_area = a.i_area)
                    inner join tr_salesman c on
                        (c.i_salesman = a.i_salesman)
                    inner join tm_user_area u on
                        (u.i_area = b.i_area and u.i_user = '$this->i_user')
                    where
                        a.i_company = '$this->i_company'
                        and to_char(a.d_so, 'YYYYMM') = '$i_periode'
                        and a.f_so_cancel = 'f'
                    /***** GET DATA SPB *******/
                union all

                    /***** GET DATA NOTA *******/
                    select distinct
                        a.i_nota,
                        a.i_company,
                        b.i_area as i_area,
                        b.i_area_id || ' - ' || initcap(b.e_area_name) as e_area_name,
                        c.i_salesman,
                        c.i_salesman_id || ' - ' || initcap(c.e_salesman_name) as e_salesman_name,
                        0 as v_target,
                        0 as v_spb,
                        a.v_nota_netto as v_nota,
                        0 as v_spb1,
                        0 as v_nota1
                    from
                        tm_nota a
                    inner join tr_area b on
                        (b.i_area = a.i_area)
                    inner join tm_user_area u on
                        (u.i_area = b.i_area and u.i_user = '$this->i_user')
                    inner join (
                        select
                            distinct
                            a.i_nota,
                            a.i_do
                        from
                            tm_nota_item a,
                            tm_nota b
                        where
                            a.i_nota = b.i_nota
                            and b.f_nota_cancel = 'f') d on
                        (d.i_nota = a.i_nota)
                    inner join tm_do e on
                        (e.i_do = d.i_do
                            and e.f_do_cancel = 'f')
                    inner join tm_so f on
                        (f.i_so = e.i_so
                            and f.f_so_cancel = 'f')
                    inner join tr_salesman c on
                        (c.i_salesman = f.i_salesman)
                    where
                        a.i_company = '$this->i_company'
                        and to_char(a.d_nota, 'YYYYMM') = '$i_periode'
                        and a.f_nota_cancel = 'f'
                    /***** END GET DATA NOTA *******/
                    union all

                    /***** GET DATA SPB1 *******/
                    select distinct
                        a.i_so,
                        a.i_company,
                        b.i_area as i_area,
                        b.i_area_id || ' - ' || initcap(b.e_area_name) as e_area_name,
                        c.i_salesman,
                        c.i_salesman_id || ' - ' || initcap(c.e_salesman_name) as e_salesman_name,
                        0 as v_target,
                        0 as v_spb,
                        0 as v_nota,
                        ll.psnn as v_spb1,
                        0 as v_nota1
                    from
                        tm_so a
                    inner join tr_area b on
                        (b.i_area = a.i_area)
                    inner join tr_salesman c on
                        (c.i_salesman = a.i_salesman)
                    inner join (select	i_so,sum(psn) as psnn from	(select	a.i_so,	(v_unit_price * (1+(b.n_so_ppn / 100))) * n_order as psn from tm_so_item a inner join tm_so b on (b.i_so=a.i_so)) as rr group by 1) as ll on 
                        (ll.i_so=a.i_so)
                    inner join tm_user_area u on
                        (u.i_area = b.i_area and u.i_user = '$this->i_user')
                    where
                        a.i_company = '$this->i_company'
                        and to_char(a.d_so, 'YYYYMM') = '$i_periode'
                        and a.f_so_cancel = 'f'
                    /***** GET DATA SPB1 *******/
                union all

                    /***** GET DATA NOTA1 *******/
                    select distinct
                        a.i_nota,
                        a.i_company,
                        b.i_area as i_area,
                        b.i_area_id || ' - ' || initcap(b.e_area_name) as e_area_name,
                        c.i_salesman,
                        c.i_salesman_id || ' - ' || initcap(c.e_salesman_name) as e_salesman_name,
                        0 as v_target,
                        0 as v_spb,
                        0 as v_nota,
                        0 as v_spb1,
                        tot as v_nota1
                    from
                        tm_nota a
                    inner join tr_area b on	(b.i_area = a.i_area)
                    inner join tm_user_area u on (u.i_area = b.i_area and u.i_user = '$this->i_user')
                    inner join (select i_nota , sum(kaeda) as tot from (
                    select
                        k.i_nota,
                        (k.v_unit_price * (1+(h.n_so_ppn / 100))) * k.n_deliver as kaeda
                    from
                        tm_nota_item k 
                    inner join tm_do y on (y.i_do=k.i_do)
                    inner join tm_so h on (h.i_so=y.i_so) ) as krn group by 1) z on (z.i_nota=a.i_nota)
                    inner join tm_nota_item d on (d.i_nota=a.i_nota)
                    inner join tm_do e on (e.i_do = d.i_do and e.f_do_cancel = 'f')
                    inner join tm_so f on (f.i_so = e.i_so and f.f_so_cancel = 'f')
                    inner join tr_salesman c on (c.i_salesman = f.i_salesman)
                    where
                        a.i_company = '$this->i_company'
                        and to_char(a.d_nota, 'YYYYMM') = '$i_periode'
                        and a.f_nota_cancel = 'f'
                    /***** END GET DATA NOTA1 *******/
                        
                ) as x
            where 
                i_company = '$this->i_company'
                $area
            group by
                1,
                2,
                3,
                4,
                5
            order by
                3
        ", FALSE);
        $datatables->hide('i_company');
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

    /** Get Customer */
    public function get_salesman($cari, $i_area)
    {
        if ($i_area != '0') {
            $area = "AND i_area = '$i_area' ";
        } else {
            $area = "0";
        }
        return $this->db->query("SELECT 
                i_salesman, i_salesman_id , e_salesman_name
            FROM 
                tr_salesman
            WHERE 
                (e_salesman_name ILIKE '%$cari%' OR i_salesman_id ILIKE '%$cari%')
                AND i_company = '$this->i_company' 
                AND f_salesman_active = 'true' 
                $area
            ORDER BY 3 ASC
        ", FALSE);
    }

    /** Get Data Untuk Edit */
    public function get_data($month, $year, $i_area)
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
            $i_area = $this->uri->segment(3);
            if ($i_area == '') {
                $i_area = 0;
            }
        }

        if ($i_area != '0') {
            $area = "AND i_area = '$i_area' ";
        } else {
            $area = "";
        }

        $i_periode = $year . $month;

        return $this->db->query("SELECT
                    i_company,
                    i_area,
                    e_area_name,
                    i_salesman,
                    e_salesman_name,
                    sum(v_target) as v_target,	
                    sum(v_spb) as v_spb,
                    case
                        when sum(v_target) > 0 then round((sum(v_spb) / sum(v_target))* 100) || '%'
                        else '0%'
                    end as p_spb,
                    sum(v_nota) as v_nota,
                    case
                        when sum(v_target) > 0 then round((sum(v_nota) / sum(v_target))* 100) || '%'
                        else '0%'
                    end as p_nota,  
                    sum(v_spb1) as v_spb1,
                    case
                        when sum(v_target) > 0 then round((sum(v_spb1) / sum(v_target))* 100) || '%'
                        else '0%'
                    end as p_spb1,                  
                    sum(v_nota1) as v_nota1,
                    case
                        when sum(v_target) > 0 then round((sum(v_nota1) / sum(v_target))* 100) || '%'
                        else '0%'
                    end as p_nota1
                from
                    (
                    
                    /***** GET DATA TARGET *******/
                    select distinct
                        a.i_target_item,
                        a.i_company,
                        b.i_area as i_area,    
                        b.i_area_id || ' - ' || initcap(b.e_area_name) as e_area_name,
                        c.i_salesman,
                        c.i_salesman_id || ' - ' || initcap(c.e_salesman_name) as e_salesman_name,
                        a.v_target,
                        0 as v_spb,
                        0 as v_nota,
                        0 as v_spb1,
                        0 as v_nota1
                    from
                        tm_target_item a
                    inner join tr_area b on
                        (b.i_area = a.i_area)
                    inner join tr_salesman c on
                        (c.i_salesman = a.i_salesman)
                    inner join tm_user_area u on
                        (u.i_area = b.i_area and u.i_user = '$this->i_user')
                    where
                        a.i_company = '$this->i_company'
                        and a.i_periode = '$i_periode'
                    /***** END GET DATA TARGET *******/
                union all

                    /***** GET DATA SPB *******/
                    select distinct
                        a.i_so,
                        a.i_company,
                        b.i_area as i_area,
                        b.i_area_id || ' - ' || initcap(b.e_area_name) as e_area_name,
                        c.i_salesman,
                        c.i_salesman_id || ' - ' || initcap(c.e_salesman_name) as e_salesman_name,
                        0 as v_target,
                        a.v_so as v_spb,
                        0 as v_nota,
                        0 as v_spb1,
                        0 as v_nota1
                    from
                        tm_so a
                    inner join tr_area b on
                        (b.i_area = a.i_area)
                    inner join tr_salesman c on
                        (c.i_salesman = a.i_salesman)
                    inner join tm_user_area u on
                        (u.i_area = b.i_area and u.i_user = '$this->i_user')
                    where
                        a.i_company = '$this->i_company'
                        and to_char(a.d_so, 'YYYYMM') = '$i_periode'
                        and a.f_so_cancel = 'f'
                    /***** GET DATA SPB *******/
                union all

                    /***** GET DATA NOTA *******/
                    select distinct
                        a.i_nota,
                        a.i_company,
                        b.i_area as i_area,
                        b.i_area_id || ' - ' || initcap(b.e_area_name) as e_area_name,
                        c.i_salesman,
                        c.i_salesman_id || ' - ' || initcap(c.e_salesman_name) as e_salesman_name,
                        0 as v_target,
                        0 as v_spb,
                        a.v_nota_netto as v_nota,
                        0 as v_spb1,
                        0 as v_nota1
                    from
                        tm_nota a
                    inner join tr_area b on
                        (b.i_area = a.i_area)
                    inner join tm_user_area u on
                        (u.i_area = b.i_area and u.i_user = '$this->i_user')
                    inner join (
                        select
                            distinct
                            a.i_nota,
                            a.i_do
                        from
                            tm_nota_item a,
                            tm_nota b
                        where
                            a.i_nota = b.i_nota
                            and b.f_nota_cancel = 'f') d on
                        (d.i_nota = a.i_nota)
                    inner join tm_do e on
                        (e.i_do = d.i_do
                            and e.f_do_cancel = 'f')
                    inner join tm_so f on
                        (f.i_so = e.i_so
                            and f.f_so_cancel = 'f')
                    inner join tr_salesman c on
                        (c.i_salesman = f.i_salesman)
                    where
                        a.i_company = '$this->i_company'
                        and to_char(a.d_nota, 'YYYYMM') = '$i_periode'
                        and a.f_nota_cancel = 'f'
                    /***** END GET DATA NOTA *******/
                    union all

                    /***** GET DATA SPB1 *******/
                    select distinct
                        a.i_so,
                        a.i_company,
                        b.i_area as i_area,
                        b.i_area_id || ' - ' || initcap(b.e_area_name) as e_area_name,
                        c.i_salesman,
                        c.i_salesman_id || ' - ' || initcap(c.e_salesman_name) as e_salesman_name,
                        0 as v_target,
                        0 as v_spb,
                        0 as v_nota,
                        ll.psnn as v_spb1,
                        0 as v_nota1
                    from
                        tm_so a
                    inner join tr_area b on
                        (b.i_area = a.i_area)
                    inner join tr_salesman c on
                        (c.i_salesman = a.i_salesman)
                    inner join (select	i_so,sum(psn) as psnn from	(select	a.i_so,	(v_unit_price * (1+(b.n_so_ppn / 100))) * n_order as psn from tm_so_item a inner join tm_so b on (b.i_so=a.i_so)) as rr group by 1) as ll on 
                        (ll.i_so=a.i_so)
                    inner join tm_user_area u on
                        (u.i_area = b.i_area and u.i_user = '$this->i_user')
                    where
                        a.i_company = '$this->i_company'
                        and to_char(a.d_so, 'YYYYMM') = '$i_periode'
                        and a.f_so_cancel = 'f'
                    /***** GET DATA SPB1 *******/
                union all

                    /***** GET DATA NOTA1 *******/
                    select distinct
                        a.i_nota,
                        a.i_company,
                        b.i_area as i_area,
                        b.i_area_id || ' - ' || initcap(b.e_area_name) as e_area_name,
                        c.i_salesman,
                        c.i_salesman_id || ' - ' || initcap(c.e_salesman_name) as e_salesman_name,
                        0 as v_target,
                        0 as v_spb,
                        0 as v_nota,
                        0 as v_spb1,
                        tot as v_nota1
                    from
                        tm_nota a
                    inner join tr_area b on	(b.i_area = a.i_area)
                    inner join tm_user_area u on (u.i_area = b.i_area and u.i_user = '$this->i_user')
                    inner join (select i_nota , sum(kaeda) as tot from (
                    select
                        k.i_nota,
                        (k.v_unit_price * (1+(h.n_so_ppn / 100))) * k.n_deliver as kaeda
                    from
                        tm_nota_item k 
                    inner join tm_do y on (y.i_do=k.i_do)
                    inner join tm_so h on (h.i_so=y.i_so) ) as krn group by 1) z on (z.i_nota=a.i_nota)
                    inner join tm_nota_item d on (d.i_nota=a.i_nota)
                    inner join tm_do e on (e.i_do = d.i_do and e.f_do_cancel = 'f')
                    inner join tm_so f on (f.i_so = e.i_so and f.f_so_cancel = 'f')
                    inner join tr_salesman c on (c.i_salesman = f.i_salesman)
                    where
                        a.i_company = '$this->i_company'
                        and to_char(a.d_nota, 'YYYYMM') = '$i_periode'
                        and a.f_nota_cancel = 'f'
                    /***** END GET DATA NOTA1 *******/
                        
                ) as x
            where 
                i_company = '$this->i_company'
                $area
            group by
                1,
                2,
                3,
                4,
                5
            order by
                3
        ", FALSE);
    }
}

/* End of file Mmaster.php */
