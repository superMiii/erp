<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Minfobrgsj extends CI_Model
{

    public function get_group()
    {
       
        $dfrom = $this->input->post('dfrom', TRUE);
        if ($dfrom == '') {
            $dfrom = $this->uri->segment(3);
            if ($dfrom == '') {
                $dfrom = '01-' . date('m-Y');
            }
        }
        $dto = $this->input->post('dto', TRUE);
        if ($dto == '') {
            $dto = $this->uri->segment(4);
            if ($dto == '') {
                $dto = date('d-m-Y');
            }
        }

        $dfrom  = date('Y-m-d', strtotime($dfrom));
        $dto    = date('Y-m-d', strtotime($dto));

        $kr = date('m', strtotime($dfrom));
        $yz = date('m', strtotime($dto));

        if ($kr != $yz) {
            $ui = '1995-10-01';
            $ux = '1995-10-31';
       } else {
            $ui = $dfrom;
            $ux = $dto;
       }

        return $this->db->query("SELECT EXTRACT(DAY FROM tanggal) AS hari
        FROM generate_series(
            '$ui'::DATE, -- Tanggal 1 Maret 2020
            '$ux'::DATE, -- Tanggal terakhir Maret 2020
            INTERVAL '1 day' -- Dengan interval sehari
        ) AS tanggal ", FALSE);
    }

    /** List Datatable */
    /* public function serverside($year, $area) */
    public function serverside()
    {
       
        $dfrom = $this->input->post('dfrom', TRUE);
        if ($dfrom == '') {
            $dfrom = $this->uri->segment(3);
            if ($dfrom == '') {
                $dfrom = '01-' . date('m-Y');
            }
        }
        $dto = $this->input->post('dto', TRUE);
        if ($dto == '') {
            $dto = $this->uri->segment(4);
            if ($dto == '') {
                $dto = date('d-m-Y');
            }
        }
        $i_product = $this->input->post('i_product', TRUE);
        if ($i_product == '') {
            $i_product = $this->uri->segment(5);
        }

        if ($i_product != '0' && $i_product != null) {
            $prod = "AND m.i_product = '$i_product' ";
        } else {
            $prod = "";
        }

        $dfrom  = date('Y-m-d', strtotime($dfrom));
        $dto    = date('Y-m-d', strtotime($dto));

        // var_dump($i_product);
        // die;

        return $this->db->query("WITH raya as (
                    select distinct 
                        m.i_product,
                        i.i_product_id || '|' || i.e_product_name || '|' || u.e_product_statusname as e_prod,
                        miu.hari
                    from
                        tm_do_item m
                    inner join tr_product i on (m.i_product=i.i_product)
                    inner join tr_product_status u on (i.i_product_status=u.i_product_status)
                    inner join tm_do mm on (mm.i_do=m.i_do)
                    cross join (
                    SELECT EXTRACT(DAY FROM tanggal) AS hari
                    FROM generate_series(
                        '$dfrom'::DATE, -- Tanggal 1 Maret 2020
                        '$dto'::DATE, -- Tanggal terakhir Maret 2020
                        INTERVAL '1 day' -- Dengan interval sehari
                    ) AS tanggal
                    ) miu
                    where
                        mm.i_company = '$this->i_company' 
                        and mm.f_do_cancel = 'f'
                        and mm.f_so_stockdaerah = 'f'
                        and m.n_deliver > 0
                        AND mm.d_do BETWEEN '$dfrom' AND '$dto'
                        $prod	
                        )	
                    select
                        y.e_prod,
                        jsonb_agg(y.hari order by y.hari) as yz,
                        jsonb_agg(coalesce(x.krn, 0) order by y.hari) as krm
                    from
                        (	
                        select
                            m.i_product ,
                            EXTRACT(DAY FROM k.d_do::DATE) as hari,
                            sum(m.n_deliver) as krn
                        from
                            tm_do k 
                        inner join tm_do_item m on (k.i_do=m.i_do)
                        where
                            k.i_company = '$this->i_company' 
                            and k.f_do_cancel = 'f'
                            and k.f_so_stockdaerah = 'f'
                            and m.n_deliver > 0
                            AND k.d_do BETWEEN '$dfrom' AND '$dto'
                            $prod
                        group by
                                1,
                                2			
                                ) as x
                    right join raya y on (x.i_product = y.i_product and x.hari = y.hari)
                    group by
                        1
                    order by
                        1
        ", FALSE);
    }

    public function get_prod($cari)
    {
        return $this->db->query("SELECT 
                i_product, 
                i_product_id, 
                e_product_name
            FROM 
                tr_product
            WHERE 
                (e_product_name ILIKE '%$cari%' OR i_product_id ILIKE '%$cari%')
                AND i_company = '$this->i_company' 
                AND f_product_active = true
            ORDER BY 3 ASC
        ", FALSE);
    }




    public function serverside2($dfrom, $dto, $i_product)
    // public function serverside()
    {
        if ($dfrom == '') {
            $dfrom = $this->uri->segment(3);
            if ($dfrom == '') {
                $dfrom = '01-' . date('m-Y');
            }
        }

        if ($dto == '') {
            $dto = $this->uri->segment(4);
            if ($dto == '') {
                $dto = date('d-m-Y');
            }
        }


        if ($i_product != '0' && $i_product != null) {
            $prod = "AND m.i_product = '$i_product' ";
        } else {
            $prod = "";
        }

        $dfrom  = date('Y-m-d', strtotime($dfrom));
        $dto    = date('Y-m-d', strtotime($dto));

        return $this->db->query("WITH raya as (
                    select distinct 
                        m.i_product,
                        i.i_product_id || '|' || i.e_product_name || '|' || u.e_product_statusname as e_prod,
                        miu.hari
                    from
                        tm_do_item m
                    inner join tr_product i on (m.i_product=i.i_product)
                    inner join tr_product_status u on (i.i_product_status=u.i_product_status)
                    inner join tm_do mm on (mm.i_do=m.i_do)
                    cross join (
                    SELECT EXTRACT(DAY FROM tanggal) AS hari
                    FROM generate_series(
                        '$dfrom'::DATE, -- Tanggal 1 Maret 2020
                        '$dto'::DATE, -- Tanggal terakhir Maret 2020
                        INTERVAL '1 day' -- Dengan interval sehari
                    ) AS tanggal
                    ) miu
                    where
                        mm.i_company = '$this->i_company' 
                        and mm.f_do_cancel = 'f'
                        and mm.f_so_stockdaerah = 'f'
                        and m.n_deliver > 0
                        AND mm.d_do BETWEEN '$dfrom' AND '$dto'
                        $prod	
                        )	
                    select
                        y.e_prod,
                        jsonb_agg(y.hari order by y.hari) as yz,
                        jsonb_agg(coalesce(x.krn, 0) order by y.hari) as krm
                    from
                        (	
                        select
                            m.i_product ,
                            EXTRACT(DAY FROM k.d_do::DATE) as hari,
                            sum(m.n_deliver) as krn
                        from
                            tm_do k 
                        inner join tm_do_item m on (k.i_do=m.i_do)
                        where
                            k.i_company = '$this->i_company' 
                            and k.f_do_cancel = 'f'
                            and k.f_so_stockdaerah = 'f'
                            and m.n_deliver > 0
                            AND k.d_do BETWEEN '$dfrom' AND '$dto'
                            $prod
                        group by
                                1,
                                2			
                                ) as x
                    right join raya y on (x.i_product = y.i_product and x.hari = y.hari)
                    group by
                        1
                    order by
                        1
        ", FALSE);
    }
}

/* End of file Mmaster.php */
