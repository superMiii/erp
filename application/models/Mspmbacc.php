<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mspmbacc extends CI_Model
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
                d_sr as tgl,
                a.i_sr AS id,
                d_sr,
                i_sr_id,
                to_char(d_sr, 'YYYYMM') as i_periode,
                b.e_area_name,
                a.e_remark,
                f_sr_cancel AS f_status,
                a.d_approve1,
                a.d_approve2,
                '$dfrom' as dfrom,
                '$dto' as dto,
                '$i_area' AS i_area
            FROM
                tm_sr a
            INNER JOIN tr_area b ON
                (b.i_area = a.i_area)
            INNER JOIN tm_user_area c ON
                (c.i_area = b.i_area)
            WHERE
                c.i_user = '$this->i_user'
                AND a.i_company = '$this->i_company'
                AND a.d_sr BETWEEN '$dfrom' AND '$dto'
                AND f_sr_acc = 'f'
                AND f_sr_cancel = 'f'
                $area
            ORDER BY
                1 DESC 
        ", FALSE);

        $datatables->edit('f_status', function ($data) {
            if ($data['f_status'] == 't') {
                $status = $this->lang->line('Tidak Aktif');
                $color  = 'red';
            } else {
                $color  = 'teal';
                $status = $this->lang->line('Aktif');
            }
            $data = "<span class='badge bg-" . $color . " badge-pill'>" . $status . "</span>";
            return $data;
        });

        /** Cek Hak Akses, Apakah User Bisa Edit */
        $datatables->add('action', function ($data) {
            $id         = trim($data['id']);
            $f_status   = $data['f_status'];
            $d_approve1 = $data['d_approve1'];
            $d_approve2 = $data['d_approve2'];
            $i_periode  = $data['i_periode'];
            $dfrom      = $data['dfrom'];
            $dto        = $data['dto'];
            $i_area     = $data['i_area'];
            $data       = '';
            if ($i_periode >= get_periode()) {
                if (check_role($this->i_menu, 3) && $f_status == 'f'  && ($d_approve1 == null || $d_approve1 == '') && ($d_approve2 == null || $d_approve2 == '')) {
                    $data      .= "<a href='" . base_url() . $this->folder . '/edit/' . encrypt_url($id)  . '/' .  encrypt_url($dfrom) . '/' . encrypt_url($dto) . '/' . encrypt_url($i_area) . "' title='Edit Data'><i class='fa fa-check-circle-o success darken-4 fa-lg mr-1'></i></a>";
                }
            }
            return $data;
        });
        $datatables->hide('d_approve1');
        $datatables->hide('d_approve2');
        $datatables->hide('i_periode');
        $datatables->hide('id');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('i_area');
        return $datatables->generate();
    }

    /** Get Area */
    public function get_area0($cari)
    {
        return $this->db->query("SELECT
                a.i_area ,
                a.e_store_loc_name AS e_area_name
            FROM
                tr_store_loc a 
            inner join tm_user_store b on (b.i_store=a.i_store AND b.i_user = '$this->i_user' )
            inner join tr_store c on (c.i_store=a.i_store)
            WHERE 
                (a.e_store_loc_name ILIKE '%$cari%')
                AND a.i_company = '$this->i_company' 
                AND f_store_loc_active = true  
                and c.f_store_pusat ='f'              
            ORDER BY 1 ASC
        ", FALSE);
    }
    
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

    /** Get Data Product */
    public function get_product($cari)
    {
        return $this->db->query("SELECT
                a.i_product,
                case
                    when s.i_product_statusid = 'STP1' then a.i_product_id || ' (*STP)'
                    when s.i_product_statusid = 'STP2' then a.i_product_id || ' (#STP)'
                    else a.i_product_id
                end as i_product_id,
                initcap(e_product_name) AS e_product_name
            FROM
                tr_product a 
            inner join tr_product_status s on
                (s.i_product_status = a.i_product_status)
            WHERE
                (a.i_product_id ILIKE '%$cari%'
                    OR e_product_name ILIKE '%$cari%')
                AND a.i_company = '$this->i_company'
            ORDER BY
                e_product_name ASC
        ", FALSE);
    }

    /** Get Data Detail Product */
    public function get_product_detail($i_product,$i_area)
    {
        return $this->db->query("SELECT
                    k.i_product ,
                    n.i_product_grade ,
                    k.i_product_motif ,
                    u.v_price ,
                    initcap(k.e_product_name) AS e_product_name,
                    n.e_product_gradename
                from
                    tr_area m
                inner join tr_store i on (i.i_store=m.i_store)
                inner join tr_price_group r on (r.i_price_group=i.i_price_group)
                inner join tr_customer_price u on (r.i_price_group=u.i_price_group)
                inner join tr_product k on (k.i_product=u.i_product)
                INNER JOIN tr_product_motif e on (e.i_product_motif = k.i_product_motif)
                INNER JOIN tr_product_grade n on (n.i_product_grade = u.i_product_grade)
                WHERE
                    k.i_product = '$i_product'
                    AND k.i_company = '$this->i_company'
                    and m.i_area = '$i_area'
                order by k.e_product_name
        ", FALSE);
    }

    /** Get Data Untuk Edit */
    public function getdata($id)
    {
        return $this->db->query("SELECT 
                a.*,
                b.e_store_name,
                c.i_area_id,
                c.e_area_name
            FROM 
                tm_sr a
            LEFT JOIN tr_store b ON 
                (b.i_store = a.i_store)
            LEFT JOIN tr_store_loc d ON 
                (d.i_store_loc = a.i_store_location)
            INNER JOIN tr_area c ON 
                (c.i_area = a.i_area)
            WHERE
                i_sr = '$id'
        ", FALSE);
    }

    /** Get Data Untuk Edit */
    public function getdetail($id)
    {
        return $this->db->query("SELECT
                a.*,
                COALESCE(n_acc,0) AS acc,
                case
                    when s.i_product_statusid = 'STP1' then b.i_product_id || ' (*STP)'
                    when s.i_product_statusid = 'STP2' then b.i_product_id || ' (#STP)'
                    else b.i_product_id
                end as i_product_id,
                b.e_product_name,
                g.e_product_gradename as e_product_motifname
            FROM
                tm_sr_item a
            INNER JOIN tr_product b ON
                (b.i_product = a.i_product)
            INNER JOIN tr_product_motif c ON
                (c.i_product_motif = a.i_product_motif)
            inner join tr_product_status s on (s.i_product_status = b.i_product_status)
            inner join tr_product_grade g on (g.i_product_grade=a.i_product_grade)
            WHERE
                a.i_sr = '$id'
            order by b.e_product_name                
        ", FALSE);
    }

    /** Cek Apakah Data Sudah Ada Pas Edit */
    public function cek_edit()
    {
        $i_document = $this->input->post('i_document', TRUE);
        $i_document_old = $this->input->post('i_document_old', TRUE);
        return $this->db->query("
            SELECT 
                i_sr_id
            FROM 
                tm_sr 
            WHERE 
                trim(upper(i_sr_id)) <> trim(upper('$i_document_old'))
                AND trim(upper(i_sr_id)) = trim(upper('$i_document'))
                AND i_company = '$this->i_company'
        ", FALSE);
    }

    /** Update Data */
    public function update()
    {
        $id = $this->input->post('id');
        $i_area = $this->input->post('i_area');
        $header = array(
            'f_sr_acc' => 't',
        );
        $this->db->where('i_sr', $id);
        $this->db->update('tm_sr', $header);
        if (is_array($this->input->post('i_product')) || is_object($this->input->post('i_product'))) {
            $this->db->where('i_sr', $id);
            $this->db->delete('tm_sr_item');
            $i = 0;
            foreach ($this->input->post('i_product') as $i_product) {
                $item = array(
                    'i_sr'              => $id,
                    'i_product'         => $i_product,
                    'i_product_grade'   => $this->input->post('i_product_grade')[$i],
                    'i_product_motif'   => $this->input->post('i_product_motif')[$i],
                    'n_order'           => $this->input->post('n_order')[$i],
                    /* 'n_stock'           => $this->input->post('n_stock')[$i], */
                    'v_unit_price'      => $this->input->post('v_unit_price')[$i],
                    'e_remark'          => $this->input->post('e_remarkitem')[$i],
                    'i_area'            => $i_area,
                    'n_item_no'         => $i,
                    'n_acc'             => $this->input->post('n_acc')[$i],
                    /* 'n_saldo'           => $this->input->post('n_saldo')[$i], */
                );
                $this->db->insert('tm_sr_item', $item);
                $i++;
            }
        } else {
            die;
        }
    }
}

/* End of file Mmaster.php */
