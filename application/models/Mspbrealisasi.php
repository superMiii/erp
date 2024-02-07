<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mspbrealisasi extends CI_Model
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

        if ($i_area != '0') {
            $area = "AND a.i_area = '$i_area' ";
        } else {
            $area = "";
        }

        $dfrom  = date('Y-m-d', strtotime($dfrom));
        $dto    = date('Y-m-d', strtotime($dto));
        $f_pusat = $this->session->f_pusat;

        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("SELECT
                a.i_so AS id,
                to_char(a.d_so_entry,  'YYYY-MM-DD') as d_entry,
                to_char(a.d_so_entry, 'YYYYMM') as i_periode,
                a.i_so_id,
                c.i_customer_id || ' ~ ' || initcap(c.e_customer_name) AS e_customer_name,
                initcap(d.e_area_name) AS e_area_name,
                initcap(b.e_salesman_name) AS e_salesman_name,
                case when a.f_so_stockdaerah = 't' then 'CABANG' else 'PUSAT' end as e_pemenuhan,
                e.e_status_so_name,
                '$dfrom' as dfrom,
                '$dto' as dto,
                '$i_area' AS i_area
            FROM
                tm_so a
            INNER JOIN tr_salesman b ON
                (b.i_salesman = a.i_salesman)
            INNER JOIN tr_customer c ON
                (c.i_customer = a.i_customer)
            INNER JOIN tr_area d ON	
                (d.i_area = a.i_area)
            INNER JOIN tr_status_so e ON
                (e.i_status_so = a.i_status_so)
            INNER JOIN tm_user_area f ON
                (f.i_area = a.i_area)
            WHERE
                f.i_user = '$this->i_user'
                AND a.i_company = '$this->i_company'
                AND a.d_so BETWEEN '$dfrom' AND '$dto'
                AND a.i_status_so = '3'
                AND a.f_so_cancel = 'f'
                AND c.d_approve notnull
                -- And case when '$f_pusat' = 'f' then a.f_so_stockdaerah='t' else a.f_so_stockdaerah='f' end
                $area
            ORDER BY
                i_so DESC
        ", FALSE);

        /* $datatables->edit('f_status', function ($data) {
            if ($data['f_status'] == 't') {
                $status = $this->lang->line('Batal');
                $color  = 'danger';
            } else {
                $color  = 'success';
                $status = $data['e_status_so_name'];
            }
            $data = "<span class='btn btn-outline-" . $color . " btn-sm round'>" . $status . "</span>";
            return $data;
        }); */

        /** Cek Hak Akses, Apakah User Bisa Edit */
        $datatables->add('action', function ($data) {
            $id         = trim($data['id']);
            $i_periode  = $data['i_periode'];
            $dfrom          = $data['dfrom'];
            $dto            = $data['dto'];
            $i_area     = $data['i_area'];
            $data       = '';
            if ($i_periode >= get_periode()) {
                if (check_role($this->id_menu, 3)) {
                    $data      .= "<a href='" . base_url() . $this->folder . '/detail_realisasi/' . encrypt_url($id) . '/' . encrypt_url($dfrom) . '/' . encrypt_url($dto) . '/' . encrypt_url($i_area) .  "' title='Realisasi Data'><i class='fa fa-check-circle-o success darken-4 fa-lg mr-1'></i></a>";
                }
            }
            return $data;
        });
        $datatables->hide('e_status_so_name');
        $datatables->hide('i_periode');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('i_area');
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

    /** Get Store */
    public function get_store($cari, $f_so_stockdaerah, $i_area)
    {
        if ($f_so_stockdaerah == 'f') {
            return $this->db->query("
                SELECT
                    a.i_store,
                    a.i_store_id,
                    a.e_store_name,
                    b.i_store_loc,
                    b.e_store_loc_name 
                FROM
                    tr_store a
                inner join tr_store_loc b on (a.i_store = b.i_store)
                WHERE
                    a.f_store_pusat = 't'
                    AND a.f_store_active = 't'
                    AND a.i_company = '$this->i_company'
                    AND (a.i_store_id ilike '%$cari%' or a.e_store_name ilike '%$cari%')
                ORDER BY e_store_name ASC
                ", FALSE);
        } else {
            return $this->db->query("
                SELECT b.i_store, b.i_store_id, b.e_store_name, c.i_store_loc, c.e_store_loc_name  from tr_area a
                inner join tr_store b on (a.i_store = b.i_store)
                inner join tr_store_loc c on (b.i_store = c.i_store)
                where a.i_area = '$i_area'
                AND f_store_active = 't'
                AND b.i_company = '$this->i_company'
                AND (i_store_id ilike '%$cari%' or e_store_name ilike '%$cari%')
                order by 3 asc
            ", FALSE);
        }
    }

    //ambil data realisasi
    public function get_store_utama($i_store_loc)
    {
        return $this->db->query("
            select i_store from tr_store_loc where i_store_loc = '$i_store_loc'
        ", FALSE);
    }

    /** Get Data Untuk Edit */
    public function get_data($id)
    {
        return $this->db->query("SELECT 
                a.*,
                i_po_reff AS e_po_reff, 
                b.i_area_id,
                b.e_area_name,
                e.i_salesman_id,
                e.e_salesman_name,
                f.i_product_groupid,
                f.e_product_groupname,
                c.i_customer_id,
                c.e_customer_name,
                c.e_customer_address,
                c.i_price_group,
                c.f_customer_plusppn,
                c.n_customer_top,
                c.n_customer_discount1,
                c.n_customer_discount2,
                c.n_customer_discount3,
                c.e_customer_npwpcode,
                d.e_price_groupname,
                CASE
                    WHEN c.f_customer_plusppn = TRUE THEN 'Ya'
                    ELSE 'Tidak'
                END AS eppn
            FROM 
                tm_so a
            INNER JOIN tr_area b ON 
                (b.i_area = a.i_area)
            INNER JOIN tr_customer c ON 
                (c.i_customer = a.i_customer)
            INNER JOIN tr_price_group d ON
                (a.i_price_group = d.i_price_group)
            INNER JOIN tr_salesman e ON 
                (e.i_salesman = a.i_salesman)
            INNER JOIN tr_product_group f ON
                (a.i_product_group = f.i_product_group)
            WHERE i_so = '$id'
        ", FALSE);
    }

    /** Get Detail Untuk Edit */
    public function get_data_detail($id)
    {
        return $this->db->query("SELECT 
                a.*,
                b.i_product_id 
            FROM 
                tm_so_item a
            INNER JOIN tr_product b ON 
                (b.i_product = a.i_product)
            WHERE i_so = '$id'
            order by b.e_product_name
        ", FALSE);
    }

    //ambil data realisasi
    public function get_data_realisasi($i_store, $i_so, $i_periode, $i_store_loc)
    {
        // i_product_id, e_product_name, e_product_motifname, i_product, i_product_motif, i_product_grade, n_order, n_stock, n_op
        if ($i_store == "" || $i_store == null) {
            return $this->db->query("
                select b.i_product_id, b.e_product_name, c.e_product_motifname, a.i_product, a.i_product_motif, a.i_product_grade , 
                d.e_product_gradename, a.n_order, 0 as n_stock, a.n_order as n_op, a.i_so_item from tm_so_item a
                inner join tr_product b on (a.i_product = b.i_product)
                inner join tr_product_motif c on (a.i_product_motif = c.i_product_motif)
                inner join tr_product_grade d on (a.i_product_grade = d.i_product_grade)
                where a.i_so = $i_so
                order by b.e_product_name
            ", FALSE);
        } else {
            return $this->db->query("
                with cte as (
                    select b.i_product_id, b.e_product_name, c.e_product_motifname, a.i_product, a.i_product_motif, a.i_product_grade , 
                    d.e_product_gradename, a.n_order, coalesce(e.n_quantity_stock,0) as ic_stock, a.i_so_item from tm_so_item a
                    inner join tr_product b on (a.i_product = b.i_product)
                    inner join tr_product_motif c on (a.i_product_motif = c.i_product_motif)
                    inner join tr_product_grade d on (a.i_product_grade = d.i_product_grade)
                    left join tm_ic e on ( 
                        a.i_product = e.i_product and a.i_product_motif = e.i_product_motif and 
                        a.i_product_grade = e.i_product_grade and e.i_company = '$this->i_company' and e.i_store = '$i_store' and e.i_store_location = '$i_store_loc'
                    )
                    where a.i_so = $i_so
                    order by b.e_product_name
                )
                select x.*, case when n_stock > n_order then 0 when n_stock <= n_order and n_stock >= 0 then n_order - n_stock else n_order end as n_op /*case when (x.n_order - x.n_stock) <= 0 then x.n_order else x.n_order - x.n_stock end as n_op */ from (
                    select a.*, 
                    case when a.ic_stock - coalesce(b.n_op,0) < 0 then 0 else a.ic_stock - coalesce(b.n_op,0) end as n_stock  
                    from cte a
                    left join (
                        select b.i_product, b.i_product_motif, b.i_product_grade, sum(n_op) as n_op from tm_so a
                        inner join tm_so_item b on (a.i_so = b.i_so)
                        where to_char(a.d_so, 'yyyymm') = '$i_periode'
                        and a.i_company = '$this->i_company' 
                        and a.f_so_cancel = false and a.f_request_op = true and a.i_sj is null
                        group by 1,2,3
                    ) as b on (a.i_product = b.i_product and a.i_product_motif = b.i_product_motif and a.i_product_grade = b.i_product_grade )
                ) as x
            ", FALSE);
        }
    }
    /** Update Data */
    public function update()
    {
        $id = $this->input->post('id');
        $f_so_stockdaerah = ($this->input->post('f_so_stockdaerah') == 'on') ? 't' : 'f';
        $f_request_op = ($this->input->post('f_request_op') == 'on') ? 't' : 'f';
        $header = array(
            'f_request_op'     => $f_request_op,
            'i_store'          => $this->input->post('i_store'),
            'i_store_location' => $this->input->post('i_store_loc'),
            /* 'f_so_stockdaerah' => $f_so_stockdaerah, */
            'i_status_so'      => '5',
        );
        $this->db->where('i_so', $id);
        $this->db->update('tm_so', $header);

        $jml = $this->input->post('jml');
        if ($jml > 0) {

            $i = 0;
            foreach ($this->input->post('i_so_item') as $i_so_item) {
                $n_op = str_replace(",", "", $this->input->post('n_op')[$i]);
                $i_so_item = str_replace(",", "", $this->input->post('i_so_item')[$i]);
                $item = array(
                    'n_op'          => $n_op
                );
                $this->db->where('i_so_item', $i_so_item);
                $this->db->update('tm_so_item', $item);
                $i++;
            }
        } else {
            die;
        }
    }
}

/* End of file Mmaster.php */
