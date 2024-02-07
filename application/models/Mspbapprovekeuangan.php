<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mspbapprovekeuangan extends CI_Model
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

        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("SELECT
                a.i_so AS id,
                to_char(a.d_so_entry, 'YYYY-MM-DD HH24:MI') as d_entry,
                to_char(a.d_so_entry, 'YYYYMM') as i_periode,
                a.i_so_id,
                c.i_customer_id || ' ~ ' || initcap(c.e_customer_name) AS e_customer_name,
                initcap(d.e_area_name) AS e_area_name,
                initcap(b.e_salesman_name) AS e_salesman_name,
                case when a.f_so_stockdaerah = 't' then 'CABANG' else 'PUSAT' end as e_pemenuhan,
                a.f_so_cancel AS f_status,
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
                AND
                    CASE
                        WHEN f_so_stockdaerah = 'f' THEN a.d_approve2 ISNULL
                        AND a.d_approve1 NOTNULL
                        ELSE a.d_approve2 ISNULL
                        AND d_approve1 ISNULL
                    END
                AND a.i_status_so = '2'
                AND a.d_notapprove ISNULL
                AND f_so_cancel = 'f'
                AND c.d_approve notnull
                $area
            ORDER BY
                i_so DESC
        ", FALSE);

        /** Cek Hak Akses, Apakah User Bisa Edit */
        $datatables->add('action', function ($data) {
            $id         = trim($data['id']);
            $f_status   = $data['f_status'];
            $i_periode  = $data['i_periode'];
            $dfrom      = $data['dfrom'];
            $dto        = $data['dto'];
            $i_area     = $data['i_area'];
            $data       = '';
            if ($i_periode >= get_periode()) {
                if (check_role($this->id_menu, 3) && $f_status == 'f') {
                    $data      .= "<a href='" . base_url() . $this->folder . '/detail_approve/' . encrypt_url($id) . '/' . encrypt_url($dfrom) . '/' . encrypt_url($dto) .  '/' . encrypt_url($i_area) . "' title='Approve Data'><i class='fa fa-check-circle-o success darken-4 fa-lg mr-1'></i></a>";
                }
            }
            return $data;
        });
        $datatables->hide('f_status');
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

    /** Get Data Untuk Approve */
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
                END AS eppn,
                c.plafon as pla,
                h.e_customer_statusname as bl
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
            inner join tr_customer_status h on (h.i_customer_status=c.i_customer_status)
            WHERE i_so = '$id'
        ", FALSE);
    }

    /** Get Detail Untuk Approve */
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

    /** Approve Data */
    public function approve($id, $note)
    {
        $note = str_replace("'", "", $note);
        $e_user_name = $this->session->e_user_name;
        $this->db->query("UPDATE
                tm_so
            SET
                i_approve2 = '$e_user_name',
                d_approve2 = current_date,
                e_approve2 = '$note',
                i_status_so = CASE
                    WHEN f_so_stockdaerah = 'f' AND d_approve1 NOTNULL THEN 3
                    WHEN f_so_stockdaerah = 't' AND d_approve1 ISNULL THEN 1
                    ELSE i_status_so 
                END
            WHERE
                i_so = '$id'
        ", FALSE);
        /* $table = array(
            'i_approve1' => $this->session->e_user_name,
            'd_approve1' => current_date(),
            'e_approve1' => $note,
        );
        $this->db->where('i_so', $id);
        $this->db->update('tm_so', $table); */
    }

    /** Not Approve Data */
    public function notapprove($id, $note)
    {
        $table = array(
            'i_notapprove' => $this->session->e_user_name,
            'd_notapprove' => current_date(),
            'e_notapprove' => $note,
        );
        $this->db->where('i_so', $id);
        $this->db->update('tm_so', $table);
    }
}

/* End of file Mmaster.php */
