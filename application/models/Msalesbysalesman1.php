<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Msalesbysalesman extends CI_Model
{

     /** List Datatable */
     public function serverside($dfrom, $dto)
     {
     // $dfrom = $this->input->post('dfrom', TRUE);
     // if ($dfrom == '') {
     //      $dfrom = $this->uri->segment(3);
     // }

     // $dto = $this->input->post('dto', TRUE);
     // if ($dto == '') {
     //      $dto = $this->uri->segment(4);
     // }
     // var_dump($dfrom,$dto);
     // die;

     //    $i_area = $this->input->post('i_area', TRUE);
     //    if ($i_area == '') {
     //        $i_area = $this->uri->segment(5);
     //    }

     //    if ($i_area != 'NA') {
     //        $area = "AND a.i_area = '$i_area' ";
     //    } else {
     //        $area = "";
     //    }

     //    $i_customer = $this->input->post('i_customer', TRUE);
     //    if ($i_customer == '') {
     //        $i_customer = $this->uri->segment(6);
     //    }

     //    if ($i_customer != 'ALL') {
     //        $customer = "AND a.i_customer = '$i_customer' ";
     //    } else {
     //        $customer = "";
     //    }



     $dfrom0     = date('m-d', strtotime($dfrom));
     $dto0       = date('m-d', strtotime($dto));
     
     // Mendapatkan tahun saat ini
     $currentYear = date('Y');
     
     // Menghitung tahun sebelumnya
     $previousYear = $currentYear - 1;
     
     // Mengecek apakah tahun sebelumnya adalah tahun kabisat
     $isLeapYear = date('L', strtotime("$previousYear-01-01")) == 1;
     
     // Mengambil tanggal dengan mempertimbangkan tahun kabisat
     if ($isLeapYear) {
          // Jika tahun sebelumnya adalah tahun kabisat, ambil tanggal 29 Februari
          $dfrom1 = $previousYear . "-" . $dfrom0;
          $dto1   = $previousYear . '-02-29' ;
     } else {
          // Jika bukan tahun kabisat, ambil tanggal 28 Februari
          $dfrom1 = $previousYear . "-" . $dfrom0;
          $dto1   = $previousYear . "-" . $dto0;
     }


     $dfrom2        = date('Y', strtotime($dfrom));
     $dfrom3        = date('m', strtotime($dfrom));
     $i_periode     = $dfrom2 . $dfrom3;
     
     $dfrom         = date('Y-m-d', strtotime($dfrom));
     $dto           = date('Y-m-d', strtotime($dto));

     return $this->db->query("SELECT
          miu.*,
          io.ob,
          gw.i_area_id || ' - ' || gw.e_area_name as araa,
          ri.e_salesman_name
     from
          (
          select
               i_company,
               i_area,
               i_salesman,
               sum(v_target) as v_target,
               sum(v_realisasi) as v_realisasi,
               sum(targ) as targ,
               sum(reals) as reals,
               sum(oa) as oa,
               sum(oaa) as oaa,
               sum(krm) as krm,
               sum(krmm) as krmm,
               sum(yui) as yui,
               sum(yuii) as yuii
          from
               (
               select
                    i_company,
                    i_area,
                    i_salesman,
                    v_target,
                    v_realisasi,
                    0 as targ,
                    0 as reals,
                    0 as oa,
                    0 as oaa,
                    0 as krm,
                    0 as krmm,
                    0 as yui,
                    0 as yuii
               from
                    (
                    select
                         i_company,
                         i_area,
                         i_salesman,
                         sum(v_target) as v_target,
                         sum(v_realisasi) as v_realisasi
                    from
                         (
                         select
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
                              y.e_remark as e_remark
                         from
                              (
                              select
                                   com,
                                   nota,
                                   ara,
                                   sal,
                                   cus,
                                   sum(v_target) as v_target,
                                   sum(v_realisasi) as v_realisasi
                              from
                                   (
                                   select
                                        com,
                                        nota,
                                        ara,
                                        sal,
                                        cus,
                                        sum(v_target) as v_target,
                                        0 as v_realisasi
                                   from
                                        (
                                        select
                                             re.i_company as com,
                                             re.i_nota as nota,
                                             re.i_area as ara,
                                             ss.i_salesman as sal,
                                             re.i_customer as cus,
                                             case
                                                  when re.f_masalah = 't'
                                                  and re.v_sisa > 0 then 0.01
                                                  else re.v_sisa
                                             end as v_target
                                        from
                                             tm_nota re
                                        inner join tr_customer mu on
                                             (mu.i_customer = re.i_customer)
                                        inner join tr_city su on
                                             (su.i_city = mu.i_city)
                                        inner join tm_do sj on
                                             (sj.i_do_id = re.i_nota_id
                                                  and sj.i_company = re.i_company
                                                  and f_nota_cancel = 'f')
                                        inner join tr_salesman ss on
                                             (ss.i_salesman = sj.i_salesman)
                                        inner join tr_area mi on
                                             (mi.i_area = re.i_area)
                                        where
                                             f_nota_cancel = 'f'
                                             and re.i_company = '$this->i_company'
                                             and to_char((re.d_jatuh_tempo + su.n_toleransi), 'yyyymm') <= '$i_periode'
                                   union all
                                        select
                                             mi.i_company as com,
                                             mi.i_nota as nota,
                                             mi.i_area as ara,
                                             ss.i_salesman as sal,
                                             re.i_customer as cus,
                                             case
                                                  when re.f_masalah = 't'
                                                  and mi.v_jumlah > 0 then 0.01
                                                  else mi.v_jumlah
                                             end as v_target
                                        from
                                             tm_alokasi_item mi
                                        inner join tm_alokasi ru on
                                             (mi.i_alokasi = ru.i_alokasi)
                                        inner join tm_nota re on
                                             (re.i_nota = mi.i_nota)
                                        inner join tr_customer mu on
                                             (mu.i_customer = re.i_customer)
                                        inner join tr_city su on
                                             (su.i_city = mu.i_city)
                                        inner join tm_do sj on
                                             (sj.i_do_id = re.i_nota_id
                                                  and sj.i_company = re.i_company
                                                  and f_nota_cancel = 'f')
                                        inner join tr_salesman ss on
                                             (ss.i_salesman = sj.i_salesman)
                                        where
                                             ru.f_alokasi_cancel = 'f'
                                             and ru.i_company = '$this->i_company'
                                             and to_char((ru.d_alokasi), 'yyyymm') >= '$i_periode'
                                             and to_char((re.d_jatuh_tempo + su.n_toleransi), 'yyyymm') <= '$i_periode'
                                   union all
                                        select
                                             mi.i_company as com,
                                             mi.i_nota as nota,
                                             mi.i_area as ara,
                                             ss.i_salesman as sal,
                                             re.i_customer as cus,
                                             case
                                                  when re.f_masalah = 't'
                                                  and mi.v_jumlah > 0 then 0.01
                                                  else mi.v_jumlah
                                             end as v_target
                                        from
                                             tm_alokasi_kas_item mi
                                        inner join tm_alokasi_kas ru on
                                             (mi.i_alokasi = ru.i_alokasi)
                                        inner join tm_nota re on
                                             (re.i_nota = mi.i_nota)
                                        inner join tr_customer mu on
                                             (mu.i_customer = re.i_customer)
                                        inner join tr_city su on
                                             (su.i_city = mu.i_city)
                                        inner join tm_do sj on
                                             (sj.i_do_id = re.i_nota_id
                                                  and sj.i_company = re.i_company
                                                  and f_nota_cancel = 'f')
                                        inner join tr_salesman ss on
                                             (ss.i_salesman = sj.i_salesman)
                                        where
                                             ru.f_alokasi_cancel = 'f'
                                             and ru.i_company = '$this->i_company'
                                             and to_char((ru.d_alokasi), 'yyyymm') >= '$i_periode'
                                             and to_char((re.d_jatuh_tempo + su.n_toleransi), 'yyyymm') <= '$i_periode' ) as sq
                                   group by
                                        1,
                                        2,
                                        3,
                                        4,
                                        5
                              union all
                                   select
                                        com,
                                        nota,
                                        ara,
                                        sal,
                                        cus,
                                        0 as v_target,
                                        sum(v_realisasi) as v_realisasi
                                   from
                                        (
                                        select
                                             mi.i_company as com,
                                             mi.i_nota as nota,
                                             mi.i_area as ara,
                                             ss.i_salesman as sal,
                                             re.i_customer as cus,
                                             mi.v_jumlah as v_realisasi
                                        from
                                             tm_alokasi_item mi
                                        inner join tm_alokasi ru on
                                             (mi.i_alokasi = ru.i_alokasi)
                                        inner join tm_nota re on
                                             (re.i_nota = mi.i_nota)
                                        inner join tr_customer mu on
                                             (mu.i_customer = re.i_customer)
                                        inner join tm_do sj on
                                             (sj.i_do_id = re.i_nota_id
                                                  and sj.i_company = re.i_company
                                                  and f_nota_cancel = 'f')
                                        inner join tr_salesman ss on
                                             (ss.i_salesman = sj.i_salesman)
                                        where
                                             ru.f_alokasi_cancel = 'f'
                                             and ru.i_company = '$this->i_company'
                                             and to_char((ru.d_alokasi), 'yyyymm') = '$i_periode'
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
                                        inner join tm_alokasi_kas ru on
                                             (mi.i_alokasi = ru.i_alokasi)
                                        inner join tm_nota re on
                                             (re.i_nota = mi.i_nota)
                                        inner join tr_customer mu on
                                             (mu.i_customer = re.i_customer)
                                        inner join tm_do sj on
                                             (sj.i_do_id = re.i_nota_id
                                                  and sj.i_company = re.i_company
                                                  and f_nota_cancel = 'f')
                                        inner join tr_salesman ss on
                                             (ss.i_salesman = sj.i_salesman)
                                        where
                                             ru.f_alokasi_cancel = 'f'
                                             and ru.i_company = '$this->i_company'
                                             and to_char((ru.d_alokasi), 'yyyymm') = '$i_periode' ) as rt
                                   group by
                                        1,
                                        2,
                                        3,
                                        4,
                                        5 ) as ng
                              group by
                                   1,
                                   2,
                                   3,
                                   4,
                                   5 ) as cr
                         inner join tr_area u on
                              (u.i_area = cr.ara)
                         inner join tm_user_area uu on
                              (uu.i_area = u.i_area
                                   and uu.i_user = '1')
                         inner join tr_salesman p on
                              (p.i_salesman = cr.sal )
                         inner join tr_customer i on
                              (i.i_customer = cr.cus)
                         inner join tr_city ct on
                              (ct.i_city = i.i_city)
                         inner join tm_nota y on
                              (y.i_nota = cr.nota)
                         where
                              cr.v_target>0
                              or cr.v_realisasi>0) as re
                    group by
                         1,
                         2,
                         3) as mu
          union all
               select
                    i_company,
                    i_area,
                    i_salesman,
                    0 as v_target,
                    0 as v_realisasi,
                    targ,
                    reals,
                    0 as oa,
                    0 as oaa,
                    0 as krm,
                    0 as krmm,
                    0 as yui,
                    0 as yuii
               from
                    (
                    select
                         i_company,
                         i_area ,
                         i_salesman ,
                         sum(v_target) as targ ,
                         sum(reals) as reals
                    from
                         (
                         select
                              r.i_company,
                              r.i_area ,
                              r.i_salesman ,
                              r.v_target ,
                              0 as reals
                         from
                              tm_target_item r
                         where
                              r.i_company = '$this->i_company'
                              and r.i_periode = '$i_periode'
                    union all
                         select
                              e.i_company,
                              e.i_area ,
                              ee.i_salesman ,
                              0 as v_target ,
                              sum(e.v_nota_gross) as reals
                         from
                              tm_nota e
                         inner join tm_so ee on
                              (ee.i_so = e.i_so)
                         where
                              e.i_company = '$this->i_company'
                              and e.f_nota_cancel = 'f'
                              and ee.f_so_cancel = 'f'
                              and to_char((e.d_nota), 'yyyymm') = '$i_periode'
                         group by
                              1,
                              2,
                              3) as eee
                    group by
                         1,
                         2,
                         3) as yu
          union all
               select
                    i_company,
                    i_area,
                    i_salesman,
                    0 as v_target,
                    0 as v_realisasi,
                    0 as targ,
                    0 as reals,
                    oa,
                    oaa,
                    0 as krm,
                    0 as krmm,
                    0 as yui,
                    0 as yuii
               from
                    (
                    select
                         i_company,
                         i_area,
                         i_salesman,
                         sum(oa) as oa,
                         sum(oaa) as oaa
                    from
                         (
                         select
                              i_company,
                              i_area,
                              i_salesman,
                              count(i_customer) as oa,
                              0 as oaa
                         from
                              (
                              select
                                   k.i_company ,
                                   k.i_area ,
                                   y.i_salesman ,
                                   k.i_customer
                              from
                                   tm_nota k
                              inner join tm_so y on
                                   (y.i_so = k.i_so)
                              where
                                   k.i_company = '$this->i_company'
                                   and k.f_nota_cancel = 'f'
                                   and d_nota between '$dfrom1' and '$dto1') as kr
                         group by
                              1,
                              2,
                              3
                    union all
                         select
                              i_company,
                              i_area,
                              i_salesman,
                              0 as oa,
                              count(i_customer) as oaa
                         from
                              (
                              select
                                   k.i_company ,
                                   k.i_area ,
                                   y.i_salesman ,
                                   k.i_customer
                              from
                                   tm_nota k
                              inner join tm_so y on
                                   (y.i_so = k.i_so)
                              where
                                   k.i_company = '$this->i_company'
                                   and k.f_nota_cancel = 'f'
                                   and d_nota between '$dfrom' and '$dto') as yz
                         group by
                              1,
                              2,
                              3) as rh
                    group by
                         1,
                         2,
                         3) as zu
          union all
               select
                    i_company ,
                    i_area ,
                    i_salesman ,
                    0 as v_target,
                    0 as v_realisasi,
                    0 as targ,
                    0 as reals,
                    0 as oa,
                    0 as oaa,
                    krm,
                    krmm,
                    0 as yui,
                    0 as yuii
               from
                    (
                    select
                         i_company ,
                         i_area ,
                         i_salesman ,
                         sum(krm) as krm,
                         sum(krmm) as krmm
                    from
                         (
                         select
                              b.i_company ,
                              b.i_area ,
                              c.i_salesman ,
                              sum(a.n_deliver) as krm,
                              0 as krmm
                         from
                              tm_nota_item a
                         inner join tm_nota b on
                              (b.i_nota = a.i_nota)
                         inner join tm_so c on
                              (c.i_so = b.i_so)
                         where
                              b.i_company = '$this->i_company'
                              and b.f_nota_cancel = 'f'
                              and b.d_nota between '$dfrom1' and '$dto1'
                         group by
                              1,
                              2,
                              3
                    union all
                         select
                              b.i_company ,
                              b.i_area ,
                              c.i_salesman ,
                              0 as krm,
                              sum(a.n_deliver) as krmm
                         from
                              tm_nota_item a
                         inner join tm_nota b on
                              (b.i_nota = a.i_nota)
                         inner join tm_so c on
                              (c.i_so = b.i_so)
                         where
                              b.i_company = '$this->i_company'
                              and b.f_nota_cancel = 'f'
                              and b.d_nota between '$dfrom' and '$dto'
                         group by
                              1,
                              2,
                              3) as miru
                    group by
                         1,
                         2,
                         3) as ri
          union all
               select
                    i_company ,
                    i_area ,
                    i_salesman ,
                    0 as v_target,
                    0 as v_realisasi,
                    0 as targ,
                    0 as reals,
                    0 as oa,
                    0 as oaa,
                    0 as krm,
                    0 as krmm,
                    yui,
                    yuii
               from
                    (
                    select
                         i_company ,
                         i_area ,
                         i_salesman ,
                         sum(yui) as yui,
                         sum(yuii) as yuii
                    from
                         (
                         select
                              y.i_company ,
                              y.i_area ,
                              u.i_salesman ,
                              sum(y.v_nota_gross) as yui,
                              0 as yuii
                         from
                              tm_nota y
                         inner join tm_so u on
                              (u.i_so = y.i_so)
                         where
                              y.i_company = '$this->i_company'
                              and y.f_nota_cancel = 'f'
                              and y.d_nota between '$dfrom1' and '$dto1'
                         group by
                              1,
                              2,
                              3
                    union all
                         select
                              y.i_company ,
                              y.i_area ,
                              u.i_salesman ,
                              0 as yui,
                              sum(y.v_nota_gross) as yuii
                         from
                              tm_nota y
                         inner join tm_so u on
                              (u.i_so = y.i_so)
                         where
                              y.i_company = '$this->i_company'
                              and y.f_nota_cancel = 'f'
                              and y.d_nota between '$dfrom' and '$dto'
                         group by
                              1,
                              2,
                              3) as i
                    group by
                         1,
                         2,
                         3) as ha
               ) as yz
          group by
               1,
               2,
               3
          order by
               i_area,
               i_salesman) as miu
     inner join (
          select
               i_area ,
               count(i_customer) as ob
          from
               (
               select
                    s.i_customer ,
                    s.i_area
               from
                    tr_customer s
               where
                    s.f_customer_active = 't'
          ) as ss
          group by
               1) as io on
          (io.i_area = miu.i_area)
     inner join tr_area gw on
          (gw.i_area = miu.i_area)
     inner join tr_salesman ri on
          (ri.i_salesman = miu.i_salesman)     
        ", FALSE);
     }
}

/* End of file Mmaster.php */
