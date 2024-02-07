<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mpelangganapprove extends CI_Model
{

    /** List Datatable */
    public function serverside()
    {
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("SELECT
            a.i_customer, s.i_so, s.i_so_id, a.i_customer_id, a.e_customer_name,  initcap(b.e_area_name) as e_area_name , initcap(e_city_name) as e_city_name,
            coalesce(n_customer_discount1, 0) || '%' as n_customer_discount1, e_customer_owner, e_customer_phone
            from tr_customer a
            inner join tm_so s on (s.i_customer = a.i_customer and s.f_so_cancel = 'f')
            left join tr_area b on (a.i_area = b.i_area)
            left join tr_city c on (a.i_city = c.i_city)
            where a.i_company = '$this->i_company'  
            and a.d_approve isnull
            and f_customer_active = false
            and case when s.f_so_stockdaerah = 't' then s.d_approve1 isnull else s.d_approve1 notnull end 
            order by a.i_customer desc
        ", FALSE);

        /* $datatables->edit('f_customer_active', function ($data) {
            $id         = $data['i_customer'];
            if ($data['f_customer_active']=='t') {
                $status = $this->lang->line('Aktif');
                $color  = 'success';
            }else{
                $status = $this->lang->line('Tidak Aktif');
                $color  = 'danger';
            }
            $data = "<button class='btn btn-outline-".$color." btn-sm round' onclick='changestatus(\"".$this->folder."\",\"".$id."\");'>".$status."</button>";
            return $data;
        }); */

        /** Cek Hak Akses, Apakah User Bisa Edit */
        if (check_role($this->id_menu, 3)) {
            $datatables->add('action', function ($data) {
                $id         = $data['i_customer'];
                $i_so       = $data['i_so'];
                $data       = '';
                $data      .= "<a href='" . base_url() . $this->folder . '/detail_approve/' . encrypt_url($id) . '/' . encrypt_url($i_so) . "' title='Approve Data'><i class='fa fa-check-circle-o success darken-4 fa-lg mr-1'></i></a>";
                return $data;
            });
        }
        $datatables->hide('i_so');
        return $datatables->generate();
    }

    public function get_area($cari)
    {
        return $this->db->query("
             SELECT 
                i_area , i_area_id , e_area_name 
            FROM 
                tr_area tsg 
            WHERE 
                (e_area_name ILIKE '%$cari%' or i_area_id ILIKE '%$cari%')
                and i_company = '$this->i_company' and f_area_active = true
            ORDER BY 3 ASC
        ", FALSE);
    }


    public function get_city($cari, $param1)
    {
        return $this->db->query("
             SELECT 
                i_city , i_city_id , e_city_name 
            FROM 
                tr_city tsg 
            WHERE 
                (e_city_name ILIKE '%$cari%' or i_city_id ILIKE '%$cari%')
                and i_company = '$this->i_company' and f_city_active = true and i_area = '$param1'
            ORDER BY 3 ASC
        ", FALSE);
    }

    public function get_cover($cari, $param1, $param2)
    {
        return $this->db->query("
            SELECT 
                a.i_area_cover , i_area_cover_id , e_area_cover_name 
            FROM 
                tr_area_cover a
            INNER JOIN tr_area_cover_item b ON
                (b.i_area_cover = a.i_area_cover)
            WHERE 
                (e_area_cover_name ILIKE '%$cari%' or i_area_cover_id ILIKE '%$cari%')
                and i_company = '$this->i_company' and f_area_cover_active = true and i_area = '$param1' and i_city = '$param2'
            ORDER BY 3 ASC
        ", FALSE);
    }

    public function get_group($cari)
    {
        return $this->db->query("
             SELECT 
                i_customer_group , i_customer_groupid , e_customer_groupname 
            FROM 
                tr_customer_group tsg 
            WHERE 
                (e_customer_groupname ILIKE '%$cari%' or i_customer_groupid ILIKE '%$cari%')
                and i_company = '$this->i_company' and f_customer_groupactive = true
            ORDER BY 3 ASC
        ", FALSE);
    }

    public function get_type($cari)
    {
        return $this->db->query("
             SELECT 
                i_customer_type , i_customer_typeid , e_customer_typename 
            FROM 
                tr_customer_type tsg 
            WHERE 
                (e_customer_typename ILIKE '%$cari%' or i_customer_typeid ILIKE '%$cari%')
                and i_company = '$this->i_company' and f_customer_typeactive = true
            ORDER BY 3 ASC
        ", FALSE);
    }

    public function get_level($cari)
    {
        return $this->db->query("
             SELECT 
                i_customer_level , i_customer_levelid , e_customer_levelname 
            FROM 
                tr_customer_level tsg 
            WHERE 
                (e_customer_levelname ILIKE '%$cari%' or i_customer_levelid ILIKE '%$cari%')
                and i_company = '$this->i_company' and f_customer_levelactive = true
            ORDER BY 3 ASC
        ", FALSE);
    }

    public function get_status($cari)
    {
        return $this->db->query("
             SELECT 
                i_customer_status , i_customer_statusid , e_customer_statusname 
            FROM 
                tr_customer_status tsg 
            WHERE 
                (e_customer_statusname ILIKE '%$cari%' or i_customer_statusid ILIKE '%$cari%')
                and i_company = '$this->i_company' and f_customer_statusactive = true
            ORDER BY 3 ASC
        ", FALSE);
    }

    public function get_price($cari)
    {
        return $this->db->query("
             SELECT 
                i_price_group , i_price_groupid , e_price_groupname 
            FROM 
                tr_price_group tsg 
            WHERE 
                (e_price_groupname ILIKE '%$cari%' or i_price_groupid ILIKE '%$cari%')
                and i_company = '$this->i_company' and f_price_groupactive = true
            ORDER BY 3 ASC
        ", FALSE);
    }

    public function get_payment($cari)
    {
        return $this->db->query("
             SELECT 
                i_customer_payment , i_customer_paymentid , e_customer_paymentname 
            FROM 
                tr_customer_payment tsg 
            WHERE 
                (e_customer_paymentname ILIKE '%$cari%' or i_customer_paymentid ILIKE '%$cari%')
                and i_company = '$this->i_company' and f_customer_paymentactive = true
            ORDER BY 3 ASC
        ", FALSE);
    }

    public function get_paygroup($cari)
    {
        return $this->db->query("
             SELECT 
                i_customer_paygroup , i_customer_paygroupid , e_customer_paygroupname 
            FROM 
                tr_customer_paygroup tsg 
            WHERE 
                (e_customer_paygroupname ILIKE '%$cari%' or i_customer_paygroupid ILIKE '%$cari%')
                and i_company = '$this->i_company' and f_customer_paygroupactive = true
            ORDER BY 3 ASC
        ", FALSE);
    }

    /** Get Data Untuk Edit */
    public function getdata($id)
    {
        return $this->db->query("
            select a.*, b.e_area_name , c.e_city_name , d.e_area_cover_name , e.e_price_groupname, f.e_customer_groupname , 
            g.e_customer_paygroupname , h.e_customer_paymentname , i.e_customer_typename , j.e_customer_levelname , k.e_customer_statusname ,
            b.i_area_id , c.i_city_id , d.i_area_cover_id , e.i_price_groupid , f.i_customer_groupid , g.i_customer_paygroupid , 
            h.i_customer_paymentid , i.i_customer_typeid , j.i_customer_levelid , k.i_customer_statusid,
             n_customer_discount1 as disc1,
             n_customer_discount2 as disc2,
             n_customer_discount3 as disc3, 
             a.d_customer_register as d_customer_register_edit, 
             a.d_start as d_start_edit,
             e_ekspedisi_cus,
             e_ekspedisi_bayar
            from tr_customer a
            left join tr_area b on (a.i_area = b.i_area)
            left join tr_city c on (a.i_city = c.i_city)
            left join tr_area_cover d on (a.i_area_cover = d.i_area_cover)
            left join tr_price_group e on (a.i_price_group = e.i_price_group)
            left join tr_customer_group f on (a.i_customer_group = f.i_customer_group)
            left join tr_customer_paygroup g on (a.i_customer_paygroup = g.i_customer_paygroup)
            left join tr_customer_payment h on (a.i_customer_payment = h.i_customer_payment)
            left join tr_customer_type i on (a.i_customer_type = i.i_customer_type)
            left join tr_customer_level j on (a.i_customer_level = j.i_customer_level)
            left join tr_customer_status k on (a.i_customer_status = k.i_customer_status)
            where a.i_customer = '$id'
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
                c.e_customer_phone,
                c.i_price_group,
                c.f_customer_plusppn,
                c.n_customer_top,
                c.d_customer_register,
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
        ", FALSE);
    }

    /** Approve Data */
    public function approve($id, $note)
    {
        $table = array(
            'd_approve'         => current_date(),
            'e_approve'         => $this->session->e_user_name,
            'f_customer_active' => true
        );
        $this->db->where('i_customer', $id);
        $this->db->update('tr_customer', $table);
    }

    public function approve2($i_so, $note)
    {
        /* $table = array(
            'i_status_so' => 3,
            'i_approve2' => $this->session->e_user_name,
            'd_approve1' => current_date(),
            'd_approve2' => current_date(),
            'e_approve2' => $this->session->e_user_name,
        );
        $this->db->where('i_so', $i_so);
        $this->db->update('tm_so', $table); */
        $e_user_name = $this->session->e_user_name;
        $R = "PELANGGAN BARU";
        $this->db->query("UPDATE
                tm_so
            SET
                i_approve2 = '$e_user_name',
                d_approve2 = current_date,
                e_approve2 = '$R',
                i_status_so = CASE
                    WHEN f_so_stockdaerah = 'f' AND d_approve1 NOTNULL THEN 3
                    WHEN f_so_stockdaerah = 't' AND d_approve1 ISNULL THEN 1
                    ELSE i_status_so 
                END
            WHERE
                i_so = '$i_so'
        ", FALSE);
    }
}

/* End of file Mmaster.php */
