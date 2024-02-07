<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Msalesperformance extends CI_Model
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

          $dfrom  = date('Ym', strtotime($dfrom));
          $dto    = date('Ym', strtotime($dto));

          $datatables = new Datatables(new CodeigniterAdapter);
          $datatables->query("SELECT
                    id,
                    i_periode,
                    sum(v_target)::money as v_target,
                    sum(v_so)::money as v_so,
                    sum(v_do)::money as v_do,
                    sum(v_nota)::money as v_nota
                from
                    (
                    select
                        to_char((substring(i_periode, 1, 4)|| '-' || substring(i_periode, 5, 2)|| '-01')::date, 'mm') as id,
                        to_char((substring(i_periode, 1, 4)|| '-' || substring(i_periode, 5, 2)|| '-01')::date, 'FMMonth YYYY') as i_periode,
                        sum(v_target) as v_target,
                        0 as v_so,
                        0 as v_do,
                        0 as v_nota
                    from
                        tm_target_item
                    where
                        i_periode between '$dfrom' and '$dto'
                        and i_company = '$this->i_company'
                    group by
                        1,2
                union all
                    select
                        to_char(d_so, 'mm') as id,
                        to_char(d_so, 'FMMonth YYYY') as i_periode,
                        0 as v_target,
                        sum(v_so) as v_so,
                        0 as v_do,
                        0 as v_nota
                    from
                        tm_so
                    where
                        to_char(d_so, 'YYYYmm') >= '$dfrom'
                        and to_char(d_so, 'YYYYmm') <= '$dto'
                        and tm_so.i_company = '$this->i_company'
                        and f_so_cancel = 'f'
                    group by
                        1,2
                union all
                    select
                        to_char(d_do, 'mm') as id,
                        to_char(d_do, 'FMMonth YYYY') as i_periode,
                        0 as v_target,
                        0 as v_so,
                        round((v_total - (v_so_discounttotal + v_diskon1 + v_diskon2 + v_diskon3)) * ((x.n_so_ppn + 100)/ 100)) as v_do,
                        0 as v_nota
                    from
                        (
                        select
                            a.i_company,
                            a.d_do,
                            b.n_so_ppn,
                            b.v_so_discounttotal,
                            sum(d.n_deliver * e.v_unit_price) as v_total,
                            sum(d.n_deliver * e.v_unit_price * (e.n_so_discount1 / 100)) as v_diskon1,
                            sum((d.n_deliver * e.v_unit_price - d.n_deliver * e.v_unit_price * (e.n_so_discount1 / 100))*(e.n_so_discount2 / 100)) as v_diskon2,
                            sum(((d.n_deliver * e.v_unit_price)-(d.n_deliver * e.v_unit_price * (e.n_so_discount1 / 100))-((d.n_deliver * e.v_unit_price - d.n_deliver * e.v_unit_price * (e.n_so_discount1 / 100))*(e.n_so_discount2 / 100))) * (e.n_so_discount3 / 100)) as v_diskon3
                        from
                            tm_do a
                        inner join tm_so b on
                            (b.i_so = a.i_so)
                        inner join tm_do_item d on
                            (d.i_do = a.i_do)
                        inner join tm_so_item e on
                            (e.i_so = a.i_so
                                and d.i_product = e.i_product)
                        where
                            to_char(d_do, 'YYYYmm') >= '$dfrom'
                                and to_char(d_do, 'YYYYmm') <= '$dto'
                                    and a.i_company = '$this->i_company'
                                    and a.f_do_cancel = 'f'
                                group by
                                    1,
                                    2,
                                    3,
                                    4 ) as x
                    inner join tr_company co on
                        (co.i_company = x.i_company)
                union all
                    select
                        to_char(d_nota, 'mm') as id,
                        to_char(d_nota, 'FMMonth YYYY') as i_periode,
                        0 as v_target,
                        0 as v_so,
                        0 as v_do,
                        sum(v_nota_netto) as v_nota
                    from
                        tm_nota
                    where
                        to_char(d_nota, 'YYYYmm') >= '$dfrom'
                        and to_char(d_nota, 'YYYYmm') <= '$dto'
                        and tm_nota.i_company = '$this->i_company'
                        and f_nota_cancel = 'f'
                    group by
                        1,2 ) as x
                group by
                    1,2
                order by
                    1
                         ", FALSE);
        // $datatables->hide('id');
        return $datatables->generate();
    }

    /** Get Data Untuk Edit */
    public function get_data($dfrom, $dto)
    {
        $dfrom  = date('Ym', strtotime($dfrom));
        $dto    = date('Ym', strtotime($dto));

        return $this->db->query("SELECT
                        id,
                        i_periode,
                        sum(v_target) as v_target,
                        sum(v_so) as v_so,
                        sum(v_do) as v_do,
                        sum(v_nota) as v_nota
                    from
                        (
                        select
                            to_char((substring(i_periode, 1, 4)|| '-' || substring(i_periode, 5, 2)|| '-01')::date, 'mm') as id,
                            to_char((substring(i_periode, 1, 4)|| '-' || substring(i_periode, 5, 2)|| '-01')::date, 'FMMonth YYYY') as i_periode,
                            sum(v_target) as v_target,
                            0 as v_so,
                            0 as v_do,
                            0 as v_nota
                        from
                            tm_target_item
                        where
                            i_periode between '$dfrom' and '$dto'
                            and i_company = '$this->i_company'
                        group by
                            1,2
                    union all
                        select
                            to_char(d_so, 'mm') as id,
                            to_char(d_so, 'FMMonth YYYY') as i_periode,
                            0 as v_target,
                            sum(v_so) as v_so,
                            0 as v_do,
                            0 as v_nota
                        from
                            tm_so
                        where
                            to_char(d_so, 'YYYYmm') >= '$dfrom'
                            and to_char(d_so, 'YYYYmm') <= '$dto'
                            and tm_so.i_company = '$this->i_company'
                            and f_so_cancel = 'f'
                        group by
                            1,2
                    union all
                        select
                            to_char(d_do, 'mm') as id,
                            to_char(d_do, 'FMMonth YYYY') as i_periode,
                            0 as v_target,
                            0 as v_so,
                            round((v_total - (v_so_discounttotal + v_diskon1 + v_diskon2 + v_diskon3)) * ((x.n_so_ppn + 100)/ 100)) as v_do,
                            0 as v_nota
                        from
                            (
                            select
                                a.i_company,
                                a.d_do,
                                b.n_so_ppn,
                                b.v_so_discounttotal,
                                sum(d.n_deliver * e.v_unit_price) as v_total,
                                sum(d.n_deliver * e.v_unit_price * (e.n_so_discount1 / 100)) as v_diskon1,
                                sum((d.n_deliver * e.v_unit_price - d.n_deliver * e.v_unit_price * (e.n_so_discount1 / 100))*(e.n_so_discount2 / 100)) as v_diskon2,
                                sum(((d.n_deliver * e.v_unit_price)-(d.n_deliver * e.v_unit_price * (e.n_so_discount1 / 100))-((d.n_deliver * e.v_unit_price - d.n_deliver * e.v_unit_price * (e.n_so_discount1 / 100))*(e.n_so_discount2 / 100))) * (e.n_so_discount3 / 100)) as v_diskon3
                            from
                                tm_do a
                            inner join tm_so b on
                                (b.i_so = a.i_so)
                            inner join tm_do_item d on
                                (d.i_do = a.i_do)
                            inner join tm_so_item e on
                                (e.i_so = a.i_so
                                    and d.i_product = e.i_product)
                            where
                                to_char(d_do, 'YYYYmm') >= '$dfrom'
                                    and to_char(d_do, 'YYYYmm') <= '$dto'
                                        and a.i_company = '$this->i_company'
                                        and a.f_do_cancel = 'f'
                                    group by
                                        1,
                                        2,
                                        3,
                                        4 ) as x
                        inner join tr_company co on
                            (co.i_company = x.i_company)
                    union all
                        select
                            to_char(d_nota, 'mm') as id,
                            to_char(d_nota, 'FMMonth YYYY') as i_periode,
                            0 as v_target,
                            0 as v_so,
                            0 as v_do,
                            sum(v_nota_netto) as v_nota
                        from
                            tm_nota
                        where
                            to_char(d_nota, 'YYYYmm') >= '$dfrom'
                            and to_char(d_nota, 'YYYYmm') <= '$dto'
                            and tm_nota.i_company = '$this->i_company'
                            and f_nota_cancel = 'f'
                        group by
                            1,2 ) as x
                    group by
                        1,2
                    order by
                        1                       
               ", FALSE);
    }
}

/* End of file Mmaster.php */
