<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Msalesbyklasifikasi extends CI_Model
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
                         miu.i_customer_type,
                         ri.e_customer_typename ,
                         io.ob,
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
                              i_customer_type ,
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
                                   i_customer_type ,
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
                                        i_customer_type ,
                                        sum(oa) as oa,
                                        sum(oaa) as oaa
                                   from
                                        (
                                        select
                                             i_company,
                                             i_customer_type ,
                                             count(i_customer) as oa,
                                             0 as oaa
                                        from
                                             (
                                             select distinct
                                                  k.i_company ,
                                                  h.i_customer_type ,
                                                  k.i_customer
                                             from
                                                  tm_nota k
                                             inner join tr_customer h on
                                                  (h.i_customer = k.i_customer)
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
                                             i_customer_type ,
                                             0 as oa,
                                             count(i_customer) as oaa
                                        from
                                             (
                                             select distinct
                                                  k.i_company ,
                                                  h.i_customer_type ,
                                                  k.i_customer
                                             from
                                                  tm_nota k
                                             inner join tr_customer h on
                                                  (h.i_customer = k.i_customer)
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
                                   i_customer_type ,
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
                                        i_customer_type ,
                                        sum(krm) as krm,
                                        sum(krmm) as krmm
                                   from
                                        (
                                        select
                                             b.i_company ,
                                             h.i_customer_type ,
                                             sum(a.n_deliver) as krm,
                                             0 as krmm
                                        from
                                             tm_nota_item a
                                        inner join tm_nota b on
                                             (b.i_nota = a.i_nota)
                                        inner join tr_customer h on
                                             (h.i_customer = b.i_customer)
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
                                             h.i_customer_type ,
                                             0 as krm,
                                             sum(a.n_deliver) as krmm
                                        from
                                             tm_nota_item a
                                        inner join tm_nota b on
                                             (b.i_nota = a.i_nota)
                                        inner join tr_customer h on
                                             (h.i_customer = b.i_customer)
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
                                   i_customer_type ,
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
                                        i_customer_type ,
                                        sum(yui) as yui,
                                        sum(yuii) as yuii
                                   from
                                        (
                                        select
                                             y.i_company ,
                                             h.i_customer_type ,
                                             sum(y.v_nota_gross) as yui,
                                             0 as yuii
                                        from
                                             tm_nota y
                                        inner join tr_customer h on
                                             (h.i_customer = y.i_customer)
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
                                             h.i_customer_type ,
                                             0 as yui,
                                             sum(y.v_nota_gross) as yuii
                                        from
                                             tm_nota y
                                        inner join tr_customer h on
                                             (h.i_customer = y.i_customer)
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
                                   i_customer_type ,
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
                                        h.i_customer_type ,
                                        sum(y.v_nota_gross) as yuii
                                   from
                                        tm_nota y
                                   inner join tr_customer h on
                                        (h.i_customer = y.i_customer)
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
                                             h.i_customer_type ,
                                             0 as yui,
                                             sum(y.v_nota_gross) as yuii
                                        from
                                             tm_nota y
                                        inner join tr_customer h on
                                             (h.i_customer = y.i_customer)
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
                    inner join (
                         select
                              i_customer_type ,
                              count(i_customer) as ob
                         from
                              (
                              select
                                   s.i_customer ,
                                   s.i_customer_type
                              from
                                   tr_customer s
                              where
                                   s.f_customer_active = 't'
                                   and s.i_company = '$this->i_company'
                              ) as ss
                         group by
                              1
                         ) as io on
                         (miu.i_customer_type = io.i_customer_type)
                         inner join tr_customer_type ri on (miu.i_customer_type=ri.i_customer_type)
                         order by miu.i_customer_type
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

    public function get_salesman($cari)
    {
        return $this->db->query("SELECT 
                i_salesman, i_salesman_id , e_salesman_name
            FROM 
                tr_salesman
            WHERE 
                (e_salesman_name ILIKE '%$cari%' OR i_salesman_id ILIKE '%$cari%')
                AND i_company = '$this->i_company' 
                AND f_salesman_active = 'true' 
            ORDER BY 3 ASC
        ", FALSE);
    }

    /** Get Data Untuk Edit */
    public function get_data($dfrom, $dto)
    {
        

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
                         miu.i_customer_type,
                         ri.e_customer_typename ,
                         io.ob,
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
                              i_customer_type ,
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
                                   i_customer_type ,
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
                                        i_customer_type ,
                                        sum(oa) as oa,
                                        sum(oaa) as oaa
                                   from
                                        (
                                        select
                                             i_company,
                                             i_customer_type ,
                                             count(i_customer) as oa,
                                             0 as oaa
                                        from
                                             (
                                             select distinct
                                                  k.i_company ,
                                                  h.i_customer_type ,
                                                  k.i_customer
                                             from
                                                  tm_nota k
                                             inner join tr_customer h on
                                                  (h.i_customer = k.i_customer)
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
                                             i_customer_type ,
                                             0 as oa,
                                             count(i_customer) as oaa
                                        from
                                             (
                                             select distinct
                                                  k.i_company ,
                                                  h.i_customer_type ,
                                                  k.i_customer
                                             from
                                                  tm_nota k
                                             inner join tr_customer h on
                                                  (h.i_customer = k.i_customer)
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
                                   i_customer_type ,
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
                                        i_customer_type ,
                                        sum(krm) as krm,
                                        sum(krmm) as krmm
                                   from
                                        (
                                        select
                                             b.i_company ,
                                             h.i_customer_type ,
                                             sum(a.n_deliver) as krm,
                                             0 as krmm
                                        from
                                             tm_nota_item a
                                        inner join tm_nota b on
                                             (b.i_nota = a.i_nota)
                                        inner join tr_customer h on
                                             (h.i_customer = b.i_customer)
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
                                             h.i_customer_type ,
                                             0 as krm,
                                             sum(a.n_deliver) as krmm
                                        from
                                             tm_nota_item a
                                        inner join tm_nota b on
                                             (b.i_nota = a.i_nota)
                                        inner join tr_customer h on
                                             (h.i_customer = b.i_customer)
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
                                   i_customer_type ,
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
                                        i_customer_type ,
                                        sum(yui) as yui,
                                        sum(yuii) as yuii
                                   from
                                        (
                                        select
                                             y.i_company ,
                                             h.i_customer_type ,
                                             sum(y.v_nota_gross) as yui,
                                             0 as yuii
                                        from
                                             tm_nota y
                                        inner join tr_customer h on
                                             (h.i_customer = y.i_customer)
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
                                             h.i_customer_type ,
                                             0 as yui,
                                             sum(y.v_nota_gross) as yuii
                                        from
                                             tm_nota y
                                        inner join tr_customer h on
                                             (h.i_customer = y.i_customer)
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
                                   i_customer_type ,
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
                                        h.i_customer_type ,
                                        sum(y.v_nota_gross) as yuii
                                   from
                                        tm_nota y
                                   inner join tr_customer h on
                                        (h.i_customer = y.i_customer)
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
                                             h.i_customer_type ,
                                             0 as yui,
                                             sum(y.v_nota_gross) as yuii
                                        from
                                             tm_nota y
                                        inner join tr_customer h on
                                             (h.i_customer = y.i_customer)
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
                    inner join (
                         select
                              i_customer_type ,
                              count(i_customer) as ob
                         from
                              (
                              select
                                   s.i_customer ,
                                   s.i_customer_type
                              from
                                   tr_customer s
                              where
                                   s.f_customer_active = 't'
                                   and s.i_company = '$this->i_company'
                              ) as ss
                         group by
                              1
                         ) as io on
                         (miu.i_customer_type = io.i_customer_type)
                         inner join tr_customer_type ri on (miu.i_customer_type=ri.i_customer_type)
                         order by miu.i_customer_type            
               ", FALSE);
    }
}

/* End of file Mmaster.php */
