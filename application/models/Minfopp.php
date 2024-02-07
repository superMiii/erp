<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Minfopp extends CI_Model
{

    /**** List Datatable ***/
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

        if ($i_area != 'ALL') {
            $area = "AND a.i_area = '$i_area' ";
        } else {
            $area = "";
        }

        $dfrom  = date('Y-m-d', strtotime($dfrom));
        $dto    = date('Y-m-d', strtotime($dto));

        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("SELECT
                    a.d_so_entry as id,
                    a.i_company,
                    b.i_area as i_area,
                    a.d_so,
                    a.i_so_id,
                    b.i_area_id || ' - ' || initcap(b.e_area_name) as e_area_name,
                    c.i_salesman,
                    c.i_salesman_id || ' - ' || initcap(c.e_salesman_name) as e_salesman_name,
                    ll.psnn::money as v_spb1,
                    case when ma.tot is null then 0::money else ma.tot::money end as v_nota1
                from
                    tm_so a
                inner join tr_area b on (b.i_area = a.i_area)
                inner join tr_salesman c on (c.i_salesman = a.i_salesman)
                inner join (select	i_so,sum(psn) as psnn from	(select	a.i_so,	(v_unit_price * (1+(b.n_so_ppn / 100))) * n_order as psn from tm_so_item a inner join tm_so b on (b.i_so=a.i_so)) as rr group by 1) as ll on 
                    (ll.i_so=a.i_so)
                inner join tm_user_area u on (u.i_area = b.i_area and u.i_user = '1')
                left join (select
                    i_nota ,
                    i_so ,
                    sum(kaeda) as tot
                from
                    (
                    select
                        k.i_nota,
                        h.i_so ,
                        (k.v_unit_price * (1 +(h.n_so_ppn / 100))) * k.n_deliver as kaeda
                    from
                        tm_nota_item k
                    inner join tm_do y on (y.i_do = k.i_do)
                    inner join tm_so h on (h.i_so = y.i_so) 
                    inner join tm_nota a on (a.i_nota=k.i_nota)
                    where
                        a.i_company = '$this->i_company'
                        and a.f_nota_cancel = 'f'
                        and a.d_nota between '$dfrom' AND '$dto'
                        $area
                        ) as krn
                group by
                    1,2
                ) as ma on (ma.i_so=a.i_so)
                where
                    a.i_company = '$this->i_company'
                    and a.f_so_cancel = 'f'
                    and a.d_so BETWEEN '$dfrom' AND '$dto'
                    $area
                order by
                    1
        ", FALSE);
        $datatables->hide('i_company');
        $datatables->hide('i_salesman');
        $datatables->hide('i_area');
        return $datatables->generate();
    }

    /** Get Area */
    public function get_area($cari)
    {
        return $this->db->query("SELECT
                distinct 
                a.i_area,
                a.i_area_id,
                a.e_area_name
            from
                tr_area a
            INNER JOIN tm_user_area u 
                ON (u.i_area = a.i_area AND u.i_user = '$this->i_user' ) 
            WHERE 
                (e_area_name ILIKE '%$cari%' OR i_area_id ILIKE '%$cari%')
                AND a.i_company = '$this->i_company' 
                AND f_area_active = true
            order by
                3
        ", FALSE);
    }

    /** Get Data Untuk Export */
    public function get_data($dfrom, $dto, $i_area)
    {

        $dfrom  = date('Y-m-d', strtotime($dfrom));
        $dto    = date('Y-m-d', strtotime($dto));
        if ($i_area != 'ALL') {
            $area = "AND a.i_area = '$i_area' ";
        } else {
            $area = "";
        }
        return $this->db->query("SELECT
                    a.d_so_entry as id,
                    a.i_company,
                    b.i_area as i_area,
                    b.i_area_id || ' - ' || initcap(b.e_area_name) as e_area_name,
                    c.i_salesman,
                    c.i_salesman_id || ' - ' || initcap(c.e_salesman_name) as e_salesman_name,
                    a.i_so_id,
                    a.d_so,
                    ll.psnn as v_spb1,
                    case when ma.tot is null then 0 else ma.tot end as v_nota1
                from
                    tm_so a
                inner join tr_area b on (b.i_area = a.i_area)
                inner join tr_salesman c on (c.i_salesman = a.i_salesman)
                inner join (select	i_so,sum(psn) as psnn from	(select	a.i_so,	(v_unit_price * (1+(b.n_so_ppn / 100))) * n_order as psn from tm_so_item a inner join tm_so b on (b.i_so=a.i_so)) as rr group by 1) as ll on 
                    (ll.i_so=a.i_so)
                inner join tm_user_area u on (u.i_area = b.i_area and u.i_user = '1')
                left join (select
                    i_nota ,
                    i_so ,
                    sum(kaeda) as tot
                from
                    (
                    select
                        k.i_nota,
                        h.i_so ,
                        (k.v_unit_price * (1 +(h.n_so_ppn / 100))) * k.n_deliver as kaeda
                    from
                        tm_nota_item k
                    inner join tm_do y on (y.i_do = k.i_do)
                    inner join tm_so h on (h.i_so = y.i_so) 
                    inner join tm_nota a on (a.i_nota=k.i_nota)
                    where
                        a.i_company = '$this->i_company'
                        and a.f_nota_cancel = 'f'
                        and a.d_nota between '$dfrom' AND '$dto'
                        $area
                        ) as krn
                group by
                    1,2
                ) as ma on (ma.i_so=a.i_so)
                where
                    a.i_company = '$this->i_company'
                    and a.f_so_cancel = 'f'
                    and a.d_so BETWEEN '$dfrom' AND '$dto'
                    $area
                order by
                    1,8,3
        ", FALSE);
    }
}

/* End of file Mmaster.php */
