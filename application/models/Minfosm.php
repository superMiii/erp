<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Minfosm extends CI_Model
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

        $i_store = $this->input->post('i_store', TRUE);
        if ($i_store == '') {
            $i_store = $this->uri->segment(5);
        }

        $i_store2 = $this->input->post('i_store2', TRUE);
        if ($i_store2 == '') {
            $i_store2 = $this->uri->segment(6);
        }

        $i_store3 = $this->input->post('i_store3', TRUE);
        if ($i_store3 == '') {
            $i_store3 = $this->uri->segment(7);
        }

        $i_store4 = $this->input->post('i_store4', TRUE);
        if ($i_store4 == '') {
            $i_store4 = $this->uri->segment(8);
        }

        $i_store5 = $this->input->post('i_store5', TRUE);
        if ($i_store5 == '') {
            $i_store5 = $this->uri->segment(9);
        }

        $i_store6 = $this->input->post('i_store6', TRUE);
        if ($i_store6 == '') {
            $i_store6 = $this->uri->segment(10);
        }

        $i_store7 = $this->input->post('i_store7', TRUE);
        if ($i_store7 == '') {
            $i_store7 = $this->uri->segment(11);
        }



        $i_periode = date('Ym', strtotime($dfrom));
        $datefrom = date('Y-m-d', strtotime($dfrom));
        $dateto = date('Y-m-d', strtotime($dto));
        $datejangkafrom = date('Y-m-01', strtotime($dfrom));
        $datejangkato = date('Y-m-d', strtotime('-1 days', strtotime($dfrom)));
        if ($datejangkafrom == $datefrom) {
            $datejangkafrom = '9999-09-09';
            $datejangkato = '9999-09-29';
        }

        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("SELECT
                    a.i_product as no,
                    a.i_product_id,
                    a.e_product_name,
                    CAST(COALESCE (hh.hg,0) AS int)::money as hb,
                    coalesce (b.n_saldo_akhir, 0) as pusat,
                    coalesce (c.n_saldo_akhir, 0) as gudang1,
                    coalesce (d.n_saldo_akhir, 0) as gudang2,
                    coalesce (e.n_saldo_akhir, 0) as gudang3,
                    coalesce (f.n_saldo_akhir, 0) as gudang4,
                    coalesce (g.n_saldo_akhir, 0) as gudang5,
                    coalesce (h.n_saldo_akhir, 0) as gudang6,
                    coalesce (i.n_saldo_akhir, 0) as gudang7
                from
                    tr_product a
                left join (
                select
                    i_product,
                    n_saldo_akhir
                FROM f_mutasi_stock2($this->i_company,'$i_periode','$datefrom','$dateto','$datejangkafrom','$datejangkato')
                )as b on (b.i_product=a.i_product)
                left join (select
                    i_product,
                    n_saldo_akhir
                FROM f_mut_cab('$i_store',$this->i_company,'$i_periode','$datefrom','$dateto','$datejangkafrom','$datejangkato')
                )as c on (c.i_product=a.i_product)
                left join (select
                    i_product,
                    n_saldo_akhir
                FROM f_mut_cab('$i_store2',$this->i_company,'$i_periode','$datefrom','$dateto','$datejangkafrom','$datejangkato')
                )as d on (d.i_product=a.i_product)
                left join (select
                    i_product,
                    n_saldo_akhir
                FROM f_mut_cab('$i_store3',$this->i_company,'$i_periode','$datefrom','$dateto','$datejangkafrom','$datejangkato')
                )as e on (e.i_product=a.i_product)
                left join (select
                    i_product,
                    n_saldo_akhir
                FROM f_mut_cab('$i_store4',$this->i_company,'$i_periode','$datefrom','$dateto','$datejangkafrom','$datejangkato')
                )as f on (f.i_product=a.i_product)
                left join (select
                    i_product,
                    n_saldo_akhir
                FROM f_mut_cab('$i_store5',$this->i_company,'$i_periode','$datefrom','$dateto','$datejangkafrom','$datejangkato')
                )as g on (g.i_product=a.i_product)
                left join (select
                    i_product,
                    n_saldo_akhir
                FROM f_mut_cab('$i_store6',$this->i_company,'$i_periode','$datefrom','$dateto','$datejangkafrom','$datejangkato')
                )as h on (h.i_product=a.i_product)
                left join (select
                    i_product,
                    n_saldo_akhir
                FROM f_mut_cab('$i_store7',$this->i_company,'$i_periode','$datefrom','$dateto','$datejangkafrom','$datejangkato')
                )as i on (i.i_product=a.i_product)
                left join (select i_product, max(v_price) as hg from tr_supplier_price where i_company = 2 group by 1) hh on (hh.i_product=a.i_product)
                where i_company = $this->i_company
                order by 3
                ", FALSE);

        // $datatables->hide('i_company');
        return $datatables->generate();
    }


    /** Get Data Untuk Export */
    public function get_data($dfrom, $dto, $i_store, $i_store2, $i_store3, $i_store4, $i_store5, $i_store6, $i_store7)
    {
        $i_periode = date('Ym', strtotime($dfrom));
        $datefrom = date('Y-m-d', strtotime($dfrom));
        $dateto = date('Y-m-d', strtotime($dto));
        $datejangkafrom = date('Y-m-01', strtotime($dfrom));
        $datejangkato = date('Y-m-d', strtotime('-1 days', strtotime($dfrom)));
        if ($datejangkafrom == $datefrom) {
            $datejangkafrom = '9999-09-09';
            $datejangkato = '9999-09-29';
        }
        return $this->db->query("SELECT
                        a.i_product as no,
                        a.i_product_id,
                        a.e_product_name,
                        CAST(COALESCE (hh.hg,0) AS int) as hb,
                        coalesce (b.n_saldo_akhir, 0) as pusat,
                        coalesce (c.n_saldo_akhir, 0) as gudang1,
                        coalesce (d.n_saldo_akhir, 0) as gudang2,
                        coalesce (e.n_saldo_akhir, 0) as gudang3,
                        coalesce (f.n_saldo_akhir, 0) as gudang4,
                        coalesce (g.n_saldo_akhir, 0) as gudang5,
                        coalesce (h.n_saldo_akhir, 0) as gudang6,
                        coalesce (i.n_saldo_akhir, 0) as gudang7
                    from
                        tr_product a
                    left join (
                    select
                        i_product,
                        n_saldo_akhir
                    FROM f_mutasi_stock2($this->i_company,'$i_periode','$datefrom','$dateto','$datejangkafrom','$datejangkato')
                    )as b on (b.i_product=a.i_product)
                    left join (select
                        i_product,
                        n_saldo_akhir
                    FROM f_mut_cab('$i_store',$this->i_company,'$i_periode','$datefrom','$dateto','$datejangkafrom','$datejangkato')
                    )as c on (c.i_product=a.i_product)
                    left join (select
                        i_product,
                        n_saldo_akhir
                    FROM f_mut_cab('$i_store2',$this->i_company,'$i_periode','$datefrom','$dateto','$datejangkafrom','$datejangkato')
                    )as d on (d.i_product=a.i_product)
                    left join (select
                        i_product,
                        n_saldo_akhir
                    FROM f_mut_cab('$i_store3',$this->i_company,'$i_periode','$datefrom','$dateto','$datejangkafrom','$datejangkato')
                    )as e on (e.i_product=a.i_product)
                    left join (select
                        i_product,
                        n_saldo_akhir
                    FROM f_mut_cab('$i_store4',$this->i_company,'$i_periode','$datefrom','$dateto','$datejangkafrom','$datejangkato')
                    )as f on (f.i_product=a.i_product)
                    left join (select
                        i_product,
                        n_saldo_akhir
                    FROM f_mut_cab('$i_store5',$this->i_company,'$i_periode','$datefrom','$dateto','$datejangkafrom','$datejangkato')
                    )as g on (g.i_product=a.i_product)
                    left join (select
                        i_product,
                        n_saldo_akhir
                    FROM f_mut_cab('$i_store6',$this->i_company,'$i_periode','$datefrom','$dateto','$datejangkafrom','$datejangkato')
                    )as h on (h.i_product=a.i_product)
                    left join (select
                        i_product,
                        n_saldo_akhir
                    FROM f_mut_cab('$i_store7',$this->i_company,'$i_periode','$datefrom','$dateto','$datejangkafrom','$datejangkato')
                    )as i on (i.i_product=a.i_product)
                    left join (select i_product, max(v_price) as hg from tr_supplier_price where i_company = 2 group by 1) hh on (hh.i_product=a.i_product)
                    where i_company = $this->i_company
                    order by 3
                    ", FALSE);
    }


    public function get_store($cari)
    {
        return $this->db->query("SELECT 
        DISTINCT
        a.i_store, 
        i_store_id, 
        initcap(e_store_name) AS e_store_name
    FROM 
        tr_store a
    WHERE 
        (e_store_name ILIKE '%$cari%' OR i_store_id ILIKE '%$cari%')
        AND a.i_company = '$this->i_company' 
        AND f_store_pusat = 'f'
    ORDER BY 3 ASC
        ", FALSE);
    }
}

/* End of file Mmaster.php */
