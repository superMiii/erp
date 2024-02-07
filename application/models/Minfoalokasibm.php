<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Minfoalokasibm extends CI_Model
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
            $area = "AND a.i_area = '$i_area' ";
        } else {
            $area = "";
        }

        $i_customer = $this->input->post('i_customer', TRUE);
        if ($i_customer == '') {
            $i_customer = $this->uri->segment(6);
        }

        if ($i_customer != 'ALL') {
            $customer = "AND a.i_customer = '$i_customer' ";
        } else {
            $customer = "";
        }

        $dfrom  = date('Y-m-d', strtotime($dfrom));
        $dto    = date('Y-m-d', strtotime($dto));

        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("SELECT
                c.i_alokasi,
                g.i_area_id || ' - ' || initcap(g.e_area_name) as e_area_name,
                h.i_customer_id as codee,
                initcap(h.e_customer_name) as e_customer_name,
                a.i_nota,
                a.i_nota_id,
                a.d_nota,
                f.e_rv_refference_type_name,
                case when f.i_rv_refference_type_id ='TF' then tf.i_kum_id else '' end as tf,
                case when f.i_rv_refference_type_id ='TN' then tn.i_tunai_id else '' end as tn,
                case when f.i_rv_refference_type_id ='GR' then gr.i_giro_id  else '' end as gr,
                e.i_rv_id,
                e.d_rv,
                c.i_alokasi_id,
                c.d_alokasi,
                e.v_rv::money as jumlahbm,
                c.v_jumlah::money as jumlahalokasi,
                c.v_lebih::money as v_lebih,
                a.v_nota_netto::money as jumlah_nota,
                a.v_sisa::money as sisa_nota,
                b.e_remark,
                case when f.i_rv_refference_type_id ='TF' then d1.i_dt_id when f.i_rv_refference_type_id ='TN' then d2.i_dt_id else d2.i_dt_id end as i_dt_id
            from
                tm_nota a 
                inner join tm_alokasi_item b on (b.i_nota=a.i_nota)
                inner join tm_alokasi c on (c.i_alokasi=b.i_alokasi)
                inner join tm_rv_item d on (d.i_rv_item =c.i_rv_item)
                inner join tm_rv e on (e.i_rv=c.i_rv)
                inner join tr_rv_refference_type f on (f.i_rv_refference_type=d.i_rv_refference_type)
                inner join tr_area g on	(g.i_area = a.i_area)
                inner join tr_customer h on	(h.i_customer = a.i_customer)
                left join tm_kum tf on (tf.i_kum = d.i_rv_refference)
                left join tm_tunai tn on (tn.i_tunai = d.i_rv_refference)
                left join tm_giro gr on (gr.i_giro = d.i_rv_refference)
                left join tm_dt d1 on (d1.i_dt=tf.i_dt)
                left join tm_dt d2 on (d2.i_dt=tn.i_dt)
                left join tm_dt d3 on (d3.i_dt=gr.i_dt)
                inner join tm_user_area u on (u.i_area = a.i_area and u.i_user = '$this->i_user')
            WHERE
                c.f_alokasi_cancel = 'f'
                AND c.i_company = '$this->i_company'
                AND c.d_alokasi BETWEEN '$dfrom' AND '$dto'
                $area
                $customer
            ORDER BY
                c.d_alokasi ASC
        ", FALSE);
        $datatables->hide('i_nota');
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

    /** Get Customer */
    public function get_customer($cari, $i_area)
    {
        if ($i_area != 'NA') {
            $area = "AND i_area = '$i_area' ";
        } else {
            $area = "";
        }
        return $this->db->query("SELECT 
                i_customer, i_customer_id , e_customer_name
            FROM 
                tr_customer
            WHERE 
                (e_customer_name ILIKE '%$cari%' OR i_customer_id ILIKE '%$cari%')
                AND i_company = '$this->i_company' 
                AND f_customer_active = 'true' 
                $area
            ORDER BY 3 ASC
        ", FALSE);
    }

    /** Get Data Untuk Edit */
    public function get_data($dfrom, $dto, $i_area, $i_customer)
    {
        if ($i_area != 'NA') {
            $area = "AND a.i_area = '$i_area' ";
        } else {
            $area = "";
        }
        if ($i_customer != 'ALL') {
            $customer = "AND a.i_customer = '$i_customer' ";
        } else {
            $customer = "";
        }
        $dfrom  = date('Y-m-d', strtotime($dfrom));
        $dto    = date('Y-m-d', strtotime($dto));
        return $this->db->query("SELECT
                c.i_alokasi,
                g.i_area_id || ' - ' || initcap(g.e_area_name) as e_area_name,
                h.i_customer_id as codee,
                initcap(h.e_customer_name) as e_customer_name,
                a.i_nota,
                a.i_nota_id,
                a.d_nota,
                f.e_rv_refference_type_name,
                case when f.i_rv_refference_type_id ='TF' then tf.i_kum_id else '' end as tf,
                case when f.i_rv_refference_type_id ='TN' then tn.i_tunai_id else '' end as tn,
                case when f.i_rv_refference_type_id ='GR' then gr.i_giro_id  else '' end as gr,
                e.i_rv_id,
                e.d_rv,
                c.i_alokasi_id,
                c.d_alokasi,
                e.v_rv as jumlahbm,
                c.v_jumlah as jumlahalokasi,
                c.v_lebih as v_lebih,
                a.v_nota_netto as jumlah_nota,
                a.v_sisa as sisa_nota,
                b.e_remark,
                case when f.i_rv_refference_type_id ='TF' then d1.i_dt_id when f.i_rv_refference_type_id ='TN' then d2.i_dt_id else d2.i_dt_id end as i_dt_id
            from
                tm_nota a 
                inner join tm_alokasi_item b on (b.i_nota=a.i_nota)
                inner join tm_alokasi c on (c.i_alokasi=b.i_alokasi)
                inner join tm_rv_item d on (d.i_rv_item =c.i_rv_item)
                inner join tm_rv e on (e.i_rv=c.i_rv)
                inner join tr_rv_refference_type f on (f.i_rv_refference_type=d.i_rv_refference_type)
                inner join tr_area g on	(g.i_area = a.i_area)
                inner join tr_customer h on	(h.i_customer = a.i_customer)
                left join tm_kum tf on (tf.i_kum = d.i_rv_refference)
                left join tm_tunai tn on (tn.i_tunai = d.i_rv_refference)
                left join tm_giro gr on (gr.i_giro = d.i_rv_refference)
                left join tm_dt d1 on (d1.i_dt=tf.i_dt)
                left join tm_dt d2 on (d2.i_dt=tn.i_dt)
                left join tm_dt d3 on (d3.i_dt=gr.i_dt)
                inner join tm_user_area u on (u.i_area = a.i_area and u.i_user = '$this->i_user')
            WHERE
                c.f_alokasi_cancel = 'f'
                AND c.i_company = '$this->i_company'
                AND c.d_alokasi BETWEEN '$dfrom' AND '$dto'
                $area
                $customer
            ORDER BY
                c.d_alokasi ASC
        ", FALSE);
    }
}

/* End of file Mmaster.php */
