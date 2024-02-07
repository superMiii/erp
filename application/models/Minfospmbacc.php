<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Minfospmbacc extends CI_Model
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

        if ($i_store != 'ALL') {
            $store = "AND e.i_store = '$i_store' ";
        } else {
            $store = "";
        }

        $i_product = $this->input->post('i_product', TRUE);
        if ($i_product == '') {
            $i_product = $this->uri->segment(6);
        }

        if ($i_product != 'NA') {
            $prod = "AND a.i_product = '$i_product' ";
        } else {
            $prod = "";
        }


        $dfrom  = date('Y-m-d', strtotime($dfrom));
        $dto    = date('Y-m-d', strtotime($dto));

        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("SELECT
                a.i_sr_item,
                b.i_sr as isr,
                b.i_sr_id,
                b.d_sr,
	            c.i_area_id || ' - ' || c.e_area_name as ara,
                case
                    when s.i_product_statusid = 'STP1' then d.i_product_id || ' (*STP)'
                    when s.i_product_statusid = 'STP2' then d.i_product_id || ' (#STP)'
                    else d.i_product_id
                end as i_product_id,
                d.e_product_name,
                a.n_order,
                a.n_acc,
	            a.n_deliver,
                e.e_store_name,
                a.v_unit_price::money as v_unit_price
            from
                tm_sr_item a
                inner join tm_sr b on (b.i_sr=a.i_sr)
                inner join tr_area c on (c.i_area =b.i_area)
                inner join tr_product d on (d.i_product=a.i_product)
                inner join tr_product_status s on (s.i_product_status = d.i_product_status)
                inner join tr_store e on (e.i_store=c.i_store)                                                 
            where 
                b.d_sr BETWEEN '$dfrom' AND '$dto'
                AND b.i_company = '$this->i_company'
                and b.f_sr_cancel = 'f'
                $store
                $prod
            order by d.e_product_name
                ", FALSE);

        $datatables->hide('isr');
        $datatables->hide('e_store_name');
        return $datatables->generate();
    }

    /** Get Area */
    public function get_store($cari)
    {
        return $this->db->query("SELECT distinct 
                a.*
            from
                tr_store a
            where 
            (e_store_name ILIKE '%$cari%')
            and f_store_active = 't' and f_store_pusat = 'f'
            and i_company = '$this->i_company'
                order by 2
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


    /** Get Data Untuk Export */
    public function get_data($dfrom, $dto, $i_store, $i_product)
    {
        $dfrom  = date('Y-m-d', strtotime($dfrom));
        $dto    = date('Y-m-d', strtotime($dto));

        if ($i_store != 'ALL') {
            $store = "AND e.i_store = '$i_store' ";
        } else {
            $store = "";
        }
        if ($i_product != 'NA') {
            $prod = "AND a.i_product = '$i_product' ";
        } else {
            $prod = "";
        }

        return $this->db->query("SELECT
                    a.i_sr_item,
                    b.i_sr as isr,
                    b.i_sr_id,
                    b.d_sr,
                    c.i_area_id || ' - ' || c.e_area_name as ara,
                    case
                        when s.i_product_statusid = 'STP1' then d.i_product_id || ' (*STP)'
                        when s.i_product_statusid = 'STP2' then d.i_product_id || ' (#STP)'
                        else d.i_product_id
                    end as i_product_id,
                    d.e_product_name,
                    a.n_order,
                    a.n_acc,
                    a.n_deliver,
                    e.e_store_name,
                    a.v_unit_price as v_unit_price
                from
                    tm_sr_item a
                    inner join tm_sr b on (b.i_sr=a.i_sr)
                    inner join tr_area c on (c.i_area =b.i_area)
                    inner join tr_product d on (d.i_product=a.i_product)
                    inner join tr_product_status s on (s.i_product_status = d.i_product_status)  
                    inner join tr_store e on (e.i_store=c.i_store)                                                 
                where 
                    b.d_sr BETWEEN '$dfrom' AND '$dto'
                    AND b.i_company = '$this->i_company'
                    and b.f_sr_cancel = 'f'
                    $store
                    $prod
                order by d.e_product_name
                ", FALSE);
    }
}

/* End of file Mmaster.php */
