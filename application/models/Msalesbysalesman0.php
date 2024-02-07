<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Msalesbysalesman extends CI_Model
{

     /** List Datatable */
     public function serverside($dfrom, $dto)
     {
          $tmp = explode("-", $dfrom);
          $hr = $tmp[0];
          $bl = $tmp[1];
          $th = $tmp[2] - 1;
          $thnow = $tmp[2];
          $thbl = $thnow . "-" . $bl;
          $thblfrom = $thnow . "-" . $bl;
          $dfromprev = $hr . "-" . $bl . "-" . $th;

          $tsasih = date('Y-m', strtotime('-24 month', strtotime($thbl))); //tambah tanggal sebanyak 6 bulan
          if ($tsasih != '') {
               $smn = explode("-", $tsasih);
               $thn = $smn[0];
               $bln = $smn[1];
          }
          $taunsasih = $thn . $bln;

          $tmp = explode("-", $dto);
          $hr = $tmp[0];
          $bl = $tmp[1];
          $th = $tmp[2] - 1;
          $thnya = $tmp[2];

          $thblto = $thnya . $bl;
          if ((intval($th) % 4 != 0) && ($bl == '02') && ($hr == '29')) $hr = '28';
          $dtoprev = $hr . "-" . $bl . "-" . $th;
          return $this->db->query("
            with raya as (
                 select distinct a.i_company, a.i_area, c.i_salesman , a.i_nota, a.v_sisa, a.v_nota_netto 
                 from tm_nota a
                 inner join tm_nota_item b on (a.i_nota = b.i_nota)
                 inner join tm_do c on (b.i_do = c.i_do)
                 where a.i_company = '$this->i_company' and to_char(a.d_jatuh_tempo , 'yyyymm') between '$thblfrom' and '$thblto' 
                 and a.f_nota_cancel = false and c.f_do_cancel = false and a.f_insentif = true
            )
            select x.*, a.e_salesman_name, b.e_area_name, b.i_area_id from (
                 select i_company , i_area, i_salesman, 
                 sum(v_target_tagihan) as  v_target_tagihan, sum(v_realisasi_tagihan) as v_realisasi_tagihan ,
                 case when sum(v_target_tagihan) = 0 then 0 else sum(v_realisasi_tagihan) / sum(v_target_tagihan) * 100 end as persen_tagihan ,
                 sum(v_target) as v_target , sum(v_realisasi) as v_realisasi , 
                 case when sum(v_target) = 0 then 0 else sum(v_realisasi) / sum(v_target) * 100 end as persen_realisasi, 
                 sum(ob) as ob , sum(oaprev) as oaprev, sum(oa) as oa , 
                 case when sum(oaprev) = 0 then 0 else (sum(oa) - sum(oaprev)) / sum(oaprev) * 100 end as persen_oa,
                 sum(qtyprev) as qtyprev , sum(qty) as qty , 
                 case when sum(qtyprev) = 0 then 0 else (sum(qty) - sum(qtyprev)) / sum(qtyprev) * 100 end as persen_qty,
                 sum(netsalesprev) as netsalesprev, sum(netsales) as netsales,
                 case when sum(netsalesprev) = 0 then 0 else (sum(netsales) - sum(netsalesprev)) / sum(netsalesprev) * 100 end as persen_sales
                 from (
                      /* target tagihan coll (oke) */
                      select c.i_company , c.i_area, c.i_salesman , coalesce(c.v_sisa,0) as v_target_tagihan, 0 as v_realisasi_tagihan, 
                      0 as v_target , 0 as v_realisasi, 0 as ob, 0 as oaprev,  0 as oa,
                      0 as qtyprev , 0 as qty, 0 as netsalesprev, 0 as netsales
                      from raya c
                      union all  /* target tagihan (oke) */
                      select c.i_company , c.i_area, c.i_salesman , coalesce(b.v_jumlah,0) as v_target_tagihan, 0 as v_realisasi_tagihan, 
                      0 as v_target, 0 as v_realisasi, 0 as ob, 0 as oaprev,  0 as oa,
                      0 as qtyprev , 0 as qty, 0 as netsalesprev, 0 as netsales
                      from raya c
                      left join tm_alokasi_item b on (b.i_nota = c.i_nota)
                      left join tm_alokasi a on (a.i_alokasi = b.i_alokasi) 
                      where a.i_company = '$this->i_company' and to_char(a.d_alokasi , 'yyyymm') between '$thblfrom' and '$thblto' and a.f_alokasi_cancel = false
                      union all  /* realisasi tagihan (oke) */
                      select i_company, i_area, i_salesman, 0 as v_target_tagihan, v_realisasi_tagihan as v_realisasi_tagihan ,
                      0 as v_target, 0 as v_realisasi, 0 as ob, 0 as oaprev,  0 as oa,
                      0 as qtyprev , 0 as qty, 0 as netsalesprev, 0 as netsales
                      from (
                           select distinct a.i_company, a.i_area, e.i_salesman, b.i_alokasi_item ,coalesce(b.v_jumlah,0) as v_realisasi_tagihan
                           from tm_alokasi a
                           inner join tm_alokasi_item b on (a.i_alokasi = b.i_alokasi)
                           inner join tm_nota c on (b.i_nota = c.i_nota)
                           inner join tm_nota_item d on (c.i_nota = d.i_nota)
                           inner join tm_do e on (d.i_do = e.i_do)
                           where a.i_company = '$this->i_company' and to_char(a.d_alokasi , 'yyyymm') between '$thblfrom' and '$thblto' 
                           and a.f_alokasi_cancel = false and c.f_nota_cancel =false
                      ) as x 
                      union all /* target sales (oke)*/
                      select i_company , i_area, i_salesman, 0 as v_target_tagihan, 0 as v_realisasi_tagihan, 
                      coalesce(v_target,0) as v_target, 0 as v_realisasi, 0 as ob, 0 as oaprev,  0 as oa,
                      0 as qtyprev , 0 as qty, 0 as netsalesprev, 0 as netsales
                      from tm_target_item where i_periode between '$thblfrom' and '$thblto'  and i_company = '$this->i_company'
                      union all /* realisasi selling (oke)*/
                      select i_company, i_area, i_salesman, 0 as v_target_tagihan, 0 as v_realisasi_tagihan ,
                      0 as v_target, v_realisasi, 0 as ob, 0 as oaprev,  0 as oa,
                      0 as qtyprev , 0 as qty, 0 as netsalesprev, 0 as netsales from (
                           select distinct a.i_company, a.i_area, c.i_salesman , a.i_nota, coalesce(v_nota_netto ,0) as v_realisasi
                           from tm_nota a
                           inner join tm_nota_item b on (a.i_nota = b.i_nota)
                           inner join tm_do c on (b.i_do = c.i_do)
                           where a.i_company = '$this->i_company' and to_char(a.d_nota , 'yyyymm') between '$thblfrom' and '$thblto' 
                           and a.f_nota_cancel = false and c.f_do_cancel = false
                      ) as x 
                      union all /* OB (oke)*/
                      select i_company, i_area, i_salesman, 0 as v_target_tagihan, 0 as v_realisasi_tagihan ,
                      0 as v_target, 0 as v_realisasi, count(i_customer) as ob, 0 as oaprev,  0 as oa,
                      0 as qtyprev , 0 as qty, 0 as netsalesprev, 0 as netsales from (
                           select distinct a.i_company , b.i_salesman , a.i_area, a.i_customer, 0 as i_nota from tr_customer a
                           inner join tr_salesman_areacover b on (a.i_area_cover = b.i_area_cover )
                           where a.i_company = '$this->i_company' and a.f_customer_active = true 
                           union all 
                           select distinct a.i_company, a.i_area, c.i_salesman ,  a.i_customer, a.i_nota
                           from tm_nota a
                           inner join tm_nota_item b on (a.i_nota = b.i_nota)
                           inner join tm_do c on (b.i_do = c.i_do)
                           where a.i_company = '$this->i_company' and to_char(a.d_nota, 'yyyymm')>= '$taunsasih' AND to_char(a.d_nota, 'yyyymm') <= '$thblto' 
                           and a.f_nota_cancel = false and c.f_do_cancel = false
                       ) as x
                       group by 1,2,3
                      union all /* OA Prev */
                      select i_company, i_area, i_salesman, 0 as v_target_tagihan, 0 as v_realisasi_tagihan ,
                      0 as v_target, 0 as v_realisasi, 0 as ob , count(i_customer) as oaprev,  0 as oa,
                      0 as qtyprev , 0 as qty, 0 as netsalesprev, 0 as netsales from (
                           select distinct a.i_company, a.i_area, c.i_salesman , a.i_nota, a.i_customer
                           from tm_nota a
                           inner join tm_nota_item b on (a.i_nota = b.i_nota)
                           inner join tm_do c on (b.i_do = c.i_do)
                           where a.i_company = '$this->i_company' and (a.d_nota >= to_date('$dfromprev', 'dd-mm-yyyy') AND a.d_nota <= to_date('$dtoprev', 'dd-mm-yyyy'))
                           and a.f_nota_cancel = false and c.f_do_cancel = false
                       ) as x
                       group by 1,2,3
                        union all /* OA NOW */
                      select i_company, i_area, i_salesman, 0 as v_target_tagihan, 0 as v_realisasi_tagihan ,
                      0 as v_target, 0 as v_realisasi, 0 as ob , 0 as oaprev , count(i_customer) as oa,
                      0 as qtyprev , 0 as qty, 0 as netsalesprev, 0 as netsales from (
                           select distinct a.i_company, a.i_area, c.i_salesman , a.i_nota, a.i_customer
                           from tm_nota a
                           inner join tm_nota_item b on (a.i_nota = b.i_nota)
                           inner join tm_do c on (b.i_do = c.i_do)
                           where a.i_company = '$this->i_company' and (a.d_nota >= to_date('$dfrom', 'dd-mm-yyyy') AND a.d_nota <= to_date('$dto', 'dd-mm-yyyy'))
                           and a.f_nota_cancel = false and c.f_do_cancel = false
                       ) as x
                       group by 1,2,3
                      union all /* QTY Prev */
                      select i_company, i_area, i_salesman, 0 as v_target_tagihan, 0 as v_realisasi_tagihan ,
                      0 as v_target, 0 as v_realisasi, 0 as ob , 0 as oaprev,  0 as oa, coalesce(qtyprev,0) as qtyprev , 0 as qty,
                      0 as netsalesprev, 0 as netsales from (
                           select a.i_company, a.i_area, c.i_salesman , sum(b.n_deliver) as qtyprev
                           from tm_nota a
                           inner join tm_nota_item b on (a.i_nota = b.i_nota)
                           inner join tm_do c on (b.i_do = c.i_do)
                           where a.i_company = '$this->i_company' and (a.d_nota >= to_date('$dfromprev', 'dd-mm-yyyy') AND a.d_nota <= to_date('$dtoprev', 'dd-mm-yyyy'))
                           and a.f_nota_cancel = false and c.f_do_cancel = false
                           group by 1,2,3
                       ) as x
                        union all /* QTY NOW */
                      select i_company, i_area, i_salesman, 0 as v_target_tagihan, 0 as v_realisasi_tagihan ,
                      0 as v_target, 0 as v_realisasi, 0 as ob , 0 as oaprev,  0 as oa, 0 as qtyprev, coalesce(qty,0) as qty,
                      0 as netsalesprev, 0 as netsales from (
                           select a.i_company, a.i_area, c.i_salesman , sum(b.n_deliver) as qty
                           from tm_nota a
                           inner join tm_nota_item b on (a.i_nota = b.i_nota)
                           inner join tm_do c on (b.i_do = c.i_do)
                           where a.i_company = '$this->i_company' and (a.d_nota >= to_date('$dfrom', 'dd-mm-yyyy') AND a.d_nota <= to_date('$dto', 'dd-mm-yyyy'))
                           and a.f_nota_cancel = false and c.f_do_cancel = false
                           group by 1,2,3
                       ) as x
                       union all /* Net Sales Prev */
                      select i_company, i_area, i_salesman, 0 as v_target_tagihan, 0 as v_realisasi_tagihan ,
                      0 as v_target, 0 as v_realisasi, 0 as ob , 0 as oaprev, 0 as oa,
                      0 as qtyprev , 0 as qty, sum(v_nota_netto) as netsalesprev, 0 as netsales  from (
                           select distinct a.i_company, a.i_area, c.i_salesman , a.i_nota, coalesce(a.v_nota_netto ,0) as v_nota_netto 
                           from tm_nota a
                           inner join tm_nota_item b on (a.i_nota = b.i_nota)
                           inner join tm_do c on (b.i_do = c.i_do)
                           where a.i_company = '$this->i_company' and (a.d_nota >= to_date('$dfromprev', 'dd-mm-yyyy') AND a.d_nota <= to_date('$dtoprev', 'dd-mm-yyyy'))
                           and a.f_nota_cancel = false and c.f_do_cancel = false
                       ) as x
                       group by 1,2,3
                        union all /* Net Sales NOW */
                      select i_company, i_area, i_salesman, 0 as v_target_tagihan, 0 as v_realisasi_tagihan ,
                      0 as v_target, 0 as v_realisasi, 0 as ob , 0 as oaprev , 0 as oa,
                      0 as qtyprev , 0 as qty, 0 as netsalesprev, sum(v_nota_netto) as netsales from (
                           select distinct a.i_company, a.i_area, c.i_salesman , a.i_nota, coalesce(a.v_nota_netto ,0) as v_nota_netto 
                           from tm_nota a
                           inner join tm_nota_item b on (a.i_nota = b.i_nota)
                           inner join tm_do c on (b.i_do = c.i_do)
                           where a.i_company = '$this->i_company' and (a.d_nota >= to_date('$dfrom', 'dd-mm-yyyy') AND a.d_nota <= to_date('$dto', 'dd-mm-yyyy'))
                           and a.f_nota_cancel = false and c.f_do_cancel = false
                       ) as x
                       group by 1,2,3
                 ) as x
                 group by 1,2,3
            ) as x 
            inner join tr_salesman a on (a.i_salesman = x.i_salesman)
            inner join tr_area b on (b.i_area = x.i_area)   
            order by b.i_area_id
        ", FALSE);
     }
}

/* End of file Mmaster.php */
