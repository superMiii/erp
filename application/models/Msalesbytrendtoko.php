<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Msalesbytrendtoko extends CI_Model
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
               $area = "AND ri.i_area = '$i_area' ";
          } else {
               $area = "";
          }
          

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

          $dfrom  = date('Y-m-d', strtotime($dfrom));
          $dto    = date('Y-m-d', strtotime($dto));

          $datatables = new Datatables(new CodeigniterAdapter);
          $datatables->query("SELECT
                    io.i_area,
                    ri.i_area_id || ' - ' || ri.e_area_name as araa,
                    io.i_customer_id ,
                    io.e_customer_name ,
                    miu.oa,
                    miu.oaa,
                    case
                         when miu.oa = 0 then '0.00 %'
                         else round(((miu.oaa-miu.oa)/ miu.oa)* 100, 2) || ' %'
                    end as p3,
                    miu.krm,
                    miu.krmm,
                    case
                         when miu.krm = 0 then '0.00 %'
                         else round(((miu.krmm-miu.krm)/ miu.krm)* 100, 2) || ' %'
                    end as p4,
                    miu.yui::money as yui,
                    miu.yuii::money as yuii,
                    case
                         when miu.yui = 0 then '0.00 %'
                         else round(((miu.yuii-miu.yui)/ miu.yui)* 100, 2) || ' %'
                    end as p5,
                    miu.ctr || ' %' as p6
               from
                    (
                    select
                         i_company,
                         i_customer ,
                         sum(oa) as oa,
                         sum(oaa) as oaa,
                         sum(krm) as krm,
                         sum(krmm) as krmm,
                         sum(yui) as yui,
                         sum(yuii) as yuii,
                         sum(ctr) as ctr
                    from
                         (
                         select
                              i_company,
                              i_customer ,
                              oa,
                              oaa,
                              0 as krm,
                              0 as krmm,
                              0 as yui,
                              0 as yuii,
                              0 as ctr
                         from
                              (
                              select
                                   i_company,
                                   i_customer ,
                                   sum(oa) as oa,
                                   sum(oaa) as oaa
                              from
                                   (
                                   select
                                        i_company,
                                        i_customer ,
                                        count(i_customer) as oa,
                                        0 as oaa
                                   from
                                        (
                                        select
                                             k.i_company ,
                                             k.i_customer
                                        from
                                             tm_nota k
                                        where
                                             k.i_company = '$this->i_company'
                                             and k.f_nota_cancel = 'f'
                                             and d_nota between '$dfrom1' and '$dto1') as kr
                                   group by
                                        1,
                                        2
                              union all
                                   select
                                        i_company,
                                        i_customer ,
                                        0 as oa,
                                        count(i_customer) as oaa
                                   from
                                        (
                                        select
                                             k.i_company ,
                                             k.i_customer
                                        from
                                             tm_nota k
                                        where
                                             k.i_company = '$this->i_company'
                                             and k.f_nota_cancel = 'f'
                                             and d_nota between '$dfrom' and '$dto') as yz
                                   group by
                                        1,
                                        2) as rh
                              group by
                                   1,
                                   2) as ko
                    union all
                         select
                              i_company ,
                              i_customer ,
                              0 as oa,
                              0 as oaa,
                              krm,
                              krmm,
                              0 as yui,
                              0 as yuii,
                              0 as ctr
                         from
                              (
                              select
                                   i_company ,
                                   i_customer ,
                                   sum(krm) as krm,
                                   sum(krmm) as krmm
                              from
                                   (
                                   select
                                        b.i_company ,
                                        b.i_customer ,
                                        sum(a.n_deliver) as krm,
                                        0 as krmm
                                   from
                                        tm_nota_item a
                                   inner join tm_nota b on
                                        (b.i_nota = a.i_nota)
                                   where
                                        b.i_company = '$this->i_company'
                                        and b.f_nota_cancel = 'f'
                                        and b.d_nota between '$dfrom1' and '$dto1'
                                   group by
                                        1,
                                        2
                              union all
                                   select
                                        b.i_company ,
                                        b.i_customer ,
                                        0 as krm,
                                        sum(a.n_deliver) as krmm
                                   from
                                        tm_nota_item a
                                   inner join tm_nota b on
                                        (b.i_nota = a.i_nota)
                                   where
                                        b.i_company = '$this->i_company'
                                        and b.f_nota_cancel = 'f'
                                        and b.d_nota between '$dfrom' and '$dto'
                                   group by
                                        1,
                                        2) as miru
                              group by
                                   1,
                                   2) as ga
                    union all
                         select
                              i_company ,
                              i_customer ,
                              0 as oa,
                              0 as oaa,
                              0 as krm,
                              0 as krmm,
                              yui,
                              yuii,
                              0 as ctr
                         from
                              (
                              select
                                   i_company ,
                                   i_customer ,
                                   sum(yui) as yui,
                                   sum(yuii) as yuii
                              from
                                   (
                                   select
                                        y.i_company ,
                                        y.i_customer ,
                                        sum(y.v_nota_gross) as yui,
                                        0 as yuii
                                   from
                                        tm_nota y
                                   where
                                        y.i_company = '$this->i_company'
                                        and y.f_nota_cancel = 'f'
                                        and y.d_nota between '$dfrom1' and '$dto1'
                                   group by
                                        1,
                                        2
                              union all
                                   select
                                        y.i_company ,
                                        y.i_customer ,
                                        0 as yui,
                                        sum(y.v_nota_gross) as yuii
                                   from
                                        tm_nota y
                                   where
                                        y.i_company = '$this->i_company'
                                        and y.f_nota_cancel = 'f'
                                        and y.d_nota between '$dfrom' and '$dto'
                                   group by
                                        1,
                                        2) as i
                              group by
                                   1,
                                   2) as wa
                    union all
                         select
                              kk.i_company,
                              i_customer ,
                              0 as oa,
                              0 as oaa,
                              0 as krm,
                              0 as krmm,
                              0 as yui,
                              0 as yuii,
                              ROUND((kk.yuii / nn.mik)* 100, 2) as ctr
                         from
                              (
                              select
                                   y.i_company ,
                                   y.i_customer ,
                                   sum(y.v_nota_gross) as yuii
                              from
                                   tm_nota y
                              where
                                   y.i_company = '$this->i_company'
                                   and y.f_nota_cancel = 'f'
                                   and y.d_nota between '$dfrom' and '$dto'
                              group by
                                   1,
                                   2) as kk
                         inner join (
                              select
                                   i_company,
                                   sum(yuii) as mik
                              from
                                   (
                                   select
                                        y.i_company ,
                                        y.i_customer ,
                                        0 as yui,
                                        sum(y.v_nota_gross) as yuii
                                   from
                                        tm_nota y
                                   where
                                        y.i_company = '$this->i_company'
                                        and y.f_nota_cancel = 'f'
                                        and y.d_nota between '$dfrom' and '$dto'
                                   group by
                                        1,
                                        2) as mm
                              group by
                                   1) as nn on
                              (nn.i_company = kk.i_company) ) as miru
                    group by
                         1,
                         2 ) as miu
               inner join tr_customer io on
                    (io.i_customer = miu.i_customer)
               inner join tr_area ri on
                    (ri.i_area = io.i_area)
               where 
                    miu.i_company = '$this->i_company'
                    $area
               order by ri.i_area, io.e_customer_name 
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

    /** Get Data Untuk Edit */
    public function get_data($dfrom, $dto, $i_area)
    {
     
        if ($i_area != 'NA') {
            $area = "AND ri.i_area = '$i_area' ";
        } else {
            $area = "";
        }

        

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

        $dfrom  = date('Y-m-d', strtotime($dfrom));
        $dto    = date('Y-m-d', strtotime($dto));
        return $this->db->query("SELECT 
                         io.i_area,
                         ri.i_area_id || ' - ' || ri.e_area_name as araa,
                         io.i_customer_id ,
                         io.e_customer_name ,
                         miu.oa,
                         miu.oaa,
                         case
                              when miu.oa = 0 then '0.00 %'
                              else round(((miu.oaa-miu.oa)/ miu.oa)* 100, 2) || ' %'
                         end as p3,
                         miu.krm,
                         miu.krmm,
                         case
                              when miu.krm = 0 then '0.00 %'
                              else round(((miu.krmm-miu.krm)/ miu.krm)* 100, 2) || ' %'
                         end as p4,
                         miu.yui as yui,
                         miu.yuii as yuii,
                         case
                              when miu.yui = 0 then '0.00 %'
                              else round(((miu.yuii-miu.yui)/ miu.yui)* 100, 2) || ' %'
                         end as p5,
                         miu.ctr || ' %' as p6
                    from
                         (
                         select
                              i_company,
                              i_customer ,
                              sum(oa) as oa,
                              sum(oaa) as oaa,
                              sum(krm) as krm,
                              sum(krmm) as krmm,
                              sum(yui) as yui,
                              sum(yuii) as yuii,
                              sum(ctr) as ctr
                         from
                              (
                              select
                                   i_company,
                                   i_customer ,
                                   oa,
                                   oaa,
                                   0 as krm,
                                   0 as krmm,
                                   0 as yui,
                                   0 as yuii,
                                   0 as ctr
                              from
                                   (
                                   select
                                        i_company,
                                        i_customer ,
                                        sum(oa) as oa,
                                        sum(oaa) as oaa
                                   from
                                        (
                                        select
                                             i_company,
                                             i_customer ,
                                             count(i_customer) as oa,
                                             0 as oaa
                                        from
                                             (
                                             select
                                                  k.i_company ,
                                                  k.i_customer
                                             from
                                                  tm_nota k
                                             where
                                                  k.i_company = '$this->i_company'
                                                  and k.f_nota_cancel = 'f'
                                                  and d_nota between '$dfrom1' and '$dto1') as kr
                                        group by
                                             1,
                                             2
                                   union all
                                        select
                                             i_company,
                                             i_customer ,
                                             0 as oa,
                                             count(i_customer) as oaa
                                        from
                                             (
                                             select
                                                  k.i_company ,
                                                  k.i_customer
                                             from
                                                  tm_nota k
                                             where
                                                  k.i_company = '$this->i_company'
                                                  and k.f_nota_cancel = 'f'
                                                  and d_nota between '$dfrom' and '$dto') as yz
                                        group by
                                             1,
                                             2) as rh
                                   group by
                                        1,
                                        2) as ko
                         union all
                              select
                                   i_company ,
                                   i_customer ,
                                   0 as oa,
                                   0 as oaa,
                                   krm,
                                   krmm,
                                   0 as yui,
                                   0 as yuii,
                                   0 as ctr
                              from
                                   (
                                   select
                                        i_company ,
                                        i_customer ,
                                        sum(krm) as krm,
                                        sum(krmm) as krmm
                                   from
                                        (
                                        select
                                             b.i_company ,
                                             b.i_customer ,
                                             sum(a.n_deliver) as krm,
                                             0 as krmm
                                        from
                                             tm_nota_item a
                                        inner join tm_nota b on
                                             (b.i_nota = a.i_nota)
                                        where
                                             b.i_company = '$this->i_company'
                                             and b.f_nota_cancel = 'f'
                                             and b.d_nota between '$dfrom1' and '$dto1'
                                        group by
                                             1,
                                             2
                                   union all
                                        select
                                             b.i_company ,
                                             b.i_customer ,
                                             0 as krm,
                                             sum(a.n_deliver) as krmm
                                        from
                                             tm_nota_item a
                                        inner join tm_nota b on
                                             (b.i_nota = a.i_nota)
                                        where
                                             b.i_company = '$this->i_company'
                                             and b.f_nota_cancel = 'f'
                                             and b.d_nota between '$dfrom' and '$dto'
                                        group by
                                             1,
                                             2) as miru
                                   group by
                                        1,
                                        2) as ga
                         union all
                              select
                                   i_company ,
                                   i_customer ,
                                   0 as oa,
                                   0 as oaa,
                                   0 as krm,
                                   0 as krmm,
                                   yui,
                                   yuii,
                                   0 as ctr
                              from
                                   (
                                   select
                                        i_company ,
                                        i_customer ,
                                        sum(yui) as yui,
                                        sum(yuii) as yuii
                                   from
                                        (
                                        select
                                             y.i_company ,
                                             y.i_customer ,
                                             sum(y.v_nota_gross) as yui,
                                             0 as yuii
                                        from
                                             tm_nota y
                                        where
                                             y.i_company = '$this->i_company'
                                             and y.f_nota_cancel = 'f'
                                             and y.d_nota between '$dfrom1' and '$dto1'
                                        group by
                                             1,
                                             2
                                   union all
                                        select
                                             y.i_company ,
                                             y.i_customer ,
                                             0 as yui,
                                             sum(y.v_nota_gross) as yuii
                                        from
                                             tm_nota y
                                        where
                                             y.i_company = '$this->i_company'
                                             and y.f_nota_cancel = 'f'
                                             and y.d_nota between '$dfrom' and '$dto'
                                        group by
                                             1,
                                             2) as i
                                   group by
                                        1,
                                        2) as wa
                         union all
                              select
                                   kk.i_company,
                                   i_customer ,
                                   0 as oa,
                                   0 as oaa,
                                   0 as krm,
                                   0 as krmm,
                                   0 as yui,
                                   0 as yuii,
                                   ROUND((kk.yuii / nn.mik)* 100, 2) as ctr
                              from
                                   (
                                   select
                                        y.i_company ,
                                        y.i_customer ,
                                        sum(y.v_nota_gross) as yuii
                                   from
                                        tm_nota y
                                   where
                                        y.i_company = '$this->i_company'
                                        and y.f_nota_cancel = 'f'
                                        and y.d_nota between '$dfrom' and '$dto'
                                   group by
                                        1,
                                        2) as kk
                              inner join (
                                   select
                                        i_company,
                                        sum(yuii) as mik
                                   from
                                        (
                                        select
                                             y.i_company ,
                                             y.i_customer ,
                                             0 as yui,
                                             sum(y.v_nota_gross) as yuii
                                        from
                                             tm_nota y
                                        where
                                             y.i_company = '$this->i_company'
                                             and y.f_nota_cancel = 'f'
                                             and y.d_nota between '$dfrom' and '$dto'
                                        group by
                                             1,
                                             2) as mm
                                   group by
                                        1) as nn on
                                   (nn.i_company = kk.i_company) ) as miru
                         group by
                              1,
                              2 ) as miu
                    inner join tr_customer io on
                         (io.i_customer = miu.i_customer)
                    inner join tr_area ri on
                         (ri.i_area = io.i_area)
                    where 
                         miu.i_company = '$this->i_company'
                         $area
                    order by ri.i_area, io.e_customer_name
               ", FALSE);
    }
}

/* End of file Mmaster.php */
