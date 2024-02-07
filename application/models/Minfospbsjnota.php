<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Minfospbsjnota extends CI_Model
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

        if ($i_area != '0') {
            $area = "AND a.i_area = '$i_area' ";
        } else {
            $area = "";
        }

        $rmu = $this->input->post('rmu', TRUE);
        if ($rmu == '') {
            $rmu = $this->uri->segment(7);
        }

        if ($rmu == '3') {
            $ermu = "AND a.f_so_stockdaerah ='t'";
        } elseif ($rmu == '2') {
            $ermu = "AND a.f_so_stockdaerah ='f'";
        } else {
            $ermu = "";
        }

        $dfrom  = date('Y-m-d', strtotime($dfrom));
        $dto    = date('Y-m-d', strtotime($dto));

        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("SELECT
                a.i_so,
                a.i_company,
                b.i_area_id || ' - ' || b.e_area_name as ara,
                c.i_customer_id ,
                c.e_customer_name ,
                case when a.f_so_stockdaerah ='t' then 'CABANG' else 'PUSAT' end as pst,
                a.i_so_id ,
                a.d_so ,
                a.v_so::money as vso,
                icz.v_so::money as vrmu,
                d.i_do_id ,
                d.d_do ,
                el.raya::money as vdo,
                g.v_nota_netto::money as vnota ,
                '$dfrom' AS dfrom,
                '$dto' AS dto,
                '$i_area' AS i_area,
                '$rmu' AS rmu
            from
                tm_so a 
            inner join tr_area b on (b.i_area=a.i_area)
            inner join tr_customer c on (c.i_customer=a.i_customer)
            left join tm_do d on (d.i_so=a.i_so and d.f_do_cancel = 'f')
            left join (select i_do,
                (raya-(d1+d2+d3+d4))+(n_so_ppn/100)*(raya-(d1+d2+d3+d4)) as raya
            from (
            select i_do,
                rr.i_so,
                raya,
                raya*(n_so_discount1/100) as d1,
                (raya- (raya * (n_so_discount1/100)) )*(n_so_discount2/100) as d2,
                (raya- (raya*(n_so_discount1/100)) - ((raya- (raya * (n_so_discount1/100)) )*(n_so_discount2/100)))*(n_so_discount3/100) as d3,
                (raya- (raya*(n_so_discount1/100)) - (raya- (raya * (n_so_discount1/100)) )*(n_so_discount2/100) - (raya- (raya*(n_so_discount1/100)) - ((raya- (raya * (n_so_discount1/100)) )*(n_so_discount2/100)))*(n_so_discount3/100)) * (n_so_discount4/100) as d4
            from (
            select
                    i_do,r.i_so,sum(tot) as raya
            from(
                select
                        a.i_do,c.i_so,b.n_deliver * c.v_unit_price as tot
                from
                        tm_do a
                inner join tm_do_item b on (b.i_do = a.i_do)
                inner join tm_so_item c on (c.i_so = a.i_so and c.i_product = b.i_product)
                where a.f_do_cancel = 'f'
                    ) as r group by	1,2) as rr
            inner join (select distinct i_so,n_so_discount1,n_so_discount2,n_so_discount3,n_so_discount4 from tm_so_item) as ss on (ss.i_so=rr.i_so)
            ) as yy
            inner join (select distinct i_so,n_so_ppn from tm_so) as se on (se.i_so=yy.i_so)
            ) el on (el.i_do=d.i_do)
            left join tm_nota g on (g.i_so =a.i_so and g.f_nota_cancel='f')
            inner join tm_user_area u on (u.i_area = b.i_area and u.i_user = '$this->i_user')
            left join tm_so icz on (icz.i_so=a.i_so and icz.d_approve1 notnull and icz.d_approve2 notnull)
            WHERE
                a.f_so_cancel = 'f'
                AND a.i_company = '$this->i_company'
                AND a.d_so BETWEEN '$dfrom' AND '$dto'
                $area
                $ermu
            order by 3
        ", FALSE);

        /** Cek Hak Akses, Apakah User Bisa Edit */
        $datatables->add('action', function ($data) {
            $i_company  = $data['i_company'];
            $dfrom      = $data['dfrom'];
            $dto        = $data['dto'];
            $i_area    = $data['i_area'];
            $i_so      = $data['i_so'];
            $rmu      = $data['rmu'];
            $data       = '';
            $data      .= "<a href='" . base_url() . $this->folder . '/view/' . encrypt_url($i_so) . '/' . encrypt_url($i_company) . '/' . encrypt_url($dfrom) . '/' . encrypt_url($dto) . '/' . encrypt_url($i_area) . '/' . encrypt_url($rmu) . "' title='View Mutasi'><i class='fa fa-eye fa-lg warning darken-4 mr-1'></i></a>";
            return $data;
        });
        $datatables->hide('i_company');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('i_area');
        $datatables->hide('rmu');
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
            inner join tm_ttbretur b on
                (b.i_area = a.i_area)
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

    /**** List Mutasi ***/
    public function get_data_detail($i_so)
    {
        return $this->db->query("SELECT
                a.i_so,
                a.i_product,
                b.i_product_id ,
                b.e_product_name ,
                c.e_product_gradename ,
                a.n_so_discount1 ,
                a.n_so_discount2 ,
                a.n_so_discount3 ,
                a.v_unit_price ,
                gi.s,
                gi.j,
                gi.n
            from
                tm_so_item a
            inner join tr_product b on (b.i_product=a.i_product)
            inner join tr_product_grade c on (c.i_product_grade=a.i_product_grade)
            inner join (
            select soo,pro,sum(spb) as s,sum(sj) as j, sum(nota) as n from (
            select
                a.i_so as soo,
                a.i_product as pro,
                a.n_order as spb,
                0 as sj,
                0 as nota
            from
                tm_so_item a
            union all
            select
                a.i_so as soo,
                b.i_product as pro,
                0 as spb,
                b.n_deliver as sj,
                0 as nota
            from
                tm_do a
            inner join tm_do_item b on (b.i_do =a.i_do )
            union all
            select
                a.i_so as soo,
                b.i_product as pro,
                0 as spb,
                0 as sj,
                b.n_deliver as nota 
            from
                tm_nota a
            inner join tm_nota_item b on (b.i_nota=a.i_nota)
            ) as el 
            group by 1,2 ) gi on (gi.soo=a.i_so and gi.pro = a.i_product)
            WHERE
                a.i_so = '$i_so'
            order by a.n_item_no desc 
        ", FALSE);
    }

    /** Get Data Untuk Export */
    public function get_data($dfrom, $dto, $i_area, $rmu)
    {
        // var_dump($i_area,$dto ,$dfrom);
        // die;

        if ($i_area != '0') {
            $area = "AND a.i_area = '$i_area' ";
        } else {
            $area = "";
        }

        if ($rmu == '3') {
            $ermu = "AND a.f_so_stockdaerah ='t'";
        } elseif ($rmu == '2') {
            $ermu = "AND a.f_so_stockdaerah ='f'";
        } else {
            $ermu = "";
        }

        $dfrom  = date('Y-m-d', strtotime($dfrom));
        $dto    = date('Y-m-d', strtotime($dto));
        return $this->db->query("SELECT
                a.i_so,
                a.i_company,
                b.i_area_id || ' - ' || b.e_area_name as ara,
                c.i_customer_id ,
                c.e_customer_name ,
                case when a.f_so_stockdaerah ='t' then 'CABANG' else 'PUSAT' end as pst,
                a.i_so_id ,
                a.d_so ,
                a.v_so as vso,
                icz.v_so as vrmu,
                d.i_do_id ,
                d.d_do ,
                el.raya as vdo,
                g.v_nota_netto as vnota ,
                '$dfrom' AS dfrom,
                '$dto' AS dto,
                '$i_area' AS i_area
            from
                tm_so a 
            inner join tr_area b on (b.i_area=a.i_area)
            inner join tr_customer c on (c.i_customer=a.i_customer)
            left join tm_do d on (d.i_so=a.i_so and d.f_do_cancel = 'f')
            left join (select i_do,
                (raya-(d1+d2+d3+d4))+(n_so_ppn/100)*(raya-(d1+d2+d3+d4)) as raya
            from (
            select i_do,
                rr.i_so,
                raya,
                raya*(n_so_discount1/100) as d1,
                (raya- (raya * (n_so_discount1/100)) )*(n_so_discount2/100) as d2,
                (raya- (raya*(n_so_discount1/100)) - ((raya- (raya * (n_so_discount1/100)) )*(n_so_discount2/100)))*(n_so_discount3/100) as d3,
                (raya- (raya*(n_so_discount1/100)) - (raya- (raya * (n_so_discount1/100)) )*(n_so_discount2/100) - (raya- (raya*(n_so_discount1/100)) - ((raya- (raya * (n_so_discount1/100)) )*(n_so_discount2/100)))*(n_so_discount3/100)) * (n_so_discount4/100) as d4
            from (
            select
                    i_do,r.i_so,sum(tot) as raya
            from(
                select
                        a.i_do,c.i_so,b.n_deliver * c.v_unit_price as tot
                from
                        tm_do a
                inner join tm_do_item b on (b.i_do = a.i_do)
                inner join tm_so_item c on (c.i_so = a.i_so and c.i_product = b.i_product)
                where a.f_do_cancel = 'f'
                    ) as r group by	1,2) as rr
            inner join (select distinct i_so,n_so_discount1,n_so_discount2,n_so_discount3,n_so_discount4 from tm_so_item) as ss on (ss.i_so=rr.i_so)
            ) as yy
            inner join (select distinct i_so,n_so_ppn from tm_so) as se on (se.i_so=yy.i_so)
            ) el on (el.i_do=d.i_do)
            left join tm_nota g on (g.i_so =a.i_so and g.f_nota_cancel='f')
            inner join tm_user_area u on (u.i_area = b.i_area and u.i_user = '$this->i_user')
            left join tm_so icz on (icz.i_so=a.i_so and icz.d_approve1 notnull and icz.d_approve2 notnull)
            WHERE
                a.f_so_cancel = 'f'
                AND a.i_company = '$this->i_company'
                AND a.d_so BETWEEN '$dfrom' AND '$dto'
                $area
                $ermu
            order by 3
        ", FALSE);
    }
}

/* End of file Mmaster.php */
