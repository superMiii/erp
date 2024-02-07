<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mspmb extends CI_Model
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
                a.d_sr as tgl,
                a.i_sr AS id,
                a.d_sr,
                i_sr_id,
                to_char(a.d_sr, 'YYYYMM') as i_periode,
                b.e_area_name,
                a.e_remark,
                f_sr_cancel AS f_status,
                a.d_approve1,
                a.d_approve2,
	            d.i_gs_id,
                a.n_print,
                a.f_sr_acc,
                '$dfrom' AS dfrom,
                '$dto' AS dto,
                '$i_area' AS i_area
            FROM
                tm_sr a
            INNER JOIN tr_area b ON
                (b.i_area = a.i_area)
            INNER JOIN tm_user_area c ON
                (c.i_area = b.i_area)
            left join tm_gs d on (d.i_sr=a.i_sr and a.i_company=d.i_company)
            WHERE
                c.i_user = '$this->i_user'
                AND a.i_company = '$this->i_company'
                AND a.d_sr BETWEEN '$dfrom' AND '$dto'
                $area
            ORDER BY
                1 DESC 
        ", FALSE);

        $datatables->edit('n_print', function ($data) {
            if ($data['n_print'] == '0') {
                $data = "<span class='badge bg-yellow bg-darken-3 badge-pill'>" . $this->lang->line('Belum') . "</span>";
            } else {
                $data = "<span class='badge bg-blue bg-darken-1 badge-pill'>" . $this->lang->line('Sudah') . ' ' . $data['n_print'] . ' x' . "</span>";
            }
            return $data;
        });

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
            $dfrom      = $data['dfrom'];
            $dto        = $data['dto'];
            $i_area     = $data['i_area'];
            $i_periode  = $data['i_periode'];
            $f_sr_acc   = $data['f_sr_acc'];
            $data       = '';
            $data      .= "<a href='" . base_url() . $this->folder . '/view/' . encrypt_url($id) . '/' . encrypt_url($dfrom) . '/' . encrypt_url($dto) . '/' . encrypt_url($i_area) . "' title='View Data'><i class='fa fa-eye fa-lg warning darken-4 mr-1'></i></a>";
            if ($i_periode >= get_periode()) {
                if (check_role($this->i_menu, 3) && $f_status == 'f'  && ($d_approve1 == null || $d_approve1 == '') && ($d_approve2 == null || $d_approve2 == '') && ($f_sr_acc == 'f')) {
                    $data      .= "<a href='" . base_url() . $this->folder . '/edit/' . encrypt_url($id)  . '/' .  encrypt_url($dfrom) . '/' . encrypt_url($dto) . '/' . encrypt_url($i_area) . "' title='Edit Data'><i class='fa fa-pencil-square success darken-4 fa-lg mr-1'></i></a>";
                }
                if (check_role($this->i_menu, 5) && $f_status == 'f') {
                    $data      .= "<a href='#' onclick='openLink(\"" . $this->folder . "\",\"" . encrypt_url($id) . "\"); return false;' title='Print Data'><i class='fa fa-print fa-lg blue darken-4 mr-1'></i></a>";
                }
                if (check_role($this->i_menu, 4) && $f_status == 'f' && ($d_approve1 == null || $d_approve1 == '') && ($d_approve2 == null || $d_approve2 == '') && ($f_sr_acc == 'f')) {
                    $data      .= "<a href='#' onclick='sweetdeletev33(\"" . $this->folder . "\",\"" . $id . "\",\"" . encrypt_url($dfrom)  . "\",\"" . encrypt_url($dto)  . '/' . encrypt_url($i_area) . "\");' title='Delete Data'><i class='fa fa-trash fa-lg red darken-4'></i></a>";
                }
            }
            return $data;
        });
        $datatables->hide('d_approve1');
        $datatables->hide('d_approve2');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('i_area');
        $datatables->hide('i_periode');
        $datatables->hide('id');
        $datatables->hide('f_sr_acc');
        return $datatables->generate();
    }

    /** Running Number Dokumen*/
    public function running_number($thbl, $tahun)
    {
        $cek = $this->db->query("
            SELECT 
                substring(i_sr_id, 1, 2) AS kode 
            FROM tm_sr 
            WHERE i_company = '$this->i_company'
            ORDER BY i_sr DESC");
        if ($cek->num_rows() > 0) {
            $kode = $cek->row()->kode;
        } else {
            $kode = 'SR';
        }
        $query  = $this->db->query("
            SELECT
                max(substring(i_sr_id, 9, 6)) AS max
            FROM
                tm_sr
            WHERE to_char (d_sr, 'yyyy') >= '$tahun'
            AND i_company = '$this->i_company'
            AND substring(i_sr_id, 1, 2) = '$kode'
            AND substring(i_sr_id, 4, 2) = substring('$thbl',1,2)
        ", false);
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $no = $row->max;
            }
            $number = $no + 1;
            settype($number, "string");
            $n = strlen($number);
            while ($n < 6) {
                $number = "0" . $number;
                $n = strlen($number);
            }
            $number = $kode . "-" . $thbl . "-" . $number;
            return $number;
        } else {
            $number = "000001";
            $nomer  = $kode . "-" . $thbl . "-" . $number;
            return $nomer;
        }
    }

    /** Cek Apakah Data Sudah Ada */
    public function cek_code()
    {
        $i_document = $this->input->post('i_document', TRUE);
        return $this->db->query("
            SELECT 
                i_sr_id
            FROM 
                tm_sr 
            WHERE 
                upper(trim(i_sr_id)) = upper(trim('$i_document'))
                AND i_company = '$this->i_company'
        ", FALSE);
    }

    /** Get Data Gudang */
    public function get_store($cari)
    {
        return $this->db->query("
            SELECT
                b.i_store_loc AS id,
                a.i_store_id AS code,
                b.e_store_loc_name AS name,
                e_store_name AS e_name
            FROM
                tr_store a
            INNER JOIN tr_store_loc b ON
                (b.i_store = a.i_store)
            INNER JOIN tr_area c ON
                (c.i_store = a.i_store)
            INNER JOIN tm_user_area d ON
                (d.i_area = c.i_area
                    AND d.i_user = '1')
            WHERE
                (e_store_name ILIKE '%$cari%'
                    OR i_store_id ILIKE '%$cari%'
                    OR i_store_loc_id ILIKE '%$cari%'
                    OR e_store_loc_name ILIKE '%$cari%')
                AND f_store_active = 't'
                AND b.f_store_loc_active = 't'
                AND a.i_company = '$this->i_company'
            ORDER BY
                e_store_name;
        ", FALSE);
        /* return $this->db->query("
            SELECT
                DISTINCT 	
                a.i_store,
                i_store_id,
                e_store_name
            FROM
                tr_store a
            INNER JOIN tr_area b ON
                (b.i_store = a.i_store)
            WHERE
                (e_store_name ILIKE '%$cari%'
                OR i_store_id ILIKE '%$cari%')
                AND f_store_active = 't'
                AND a.i_company = '$this->i_company'
                AND b.i_area IN (
                SELECT
                    i_area
                FROM
                    tm_user_area
                WHERE
                    i_user = '$this->i_user'
                    AND i_company = '$this->i_company')
            ORDER BY
                e_store_name
        ", FALSE); */
    }



    /** Get Area */
    public function get_area($cari)
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
            INNER JOIN tr_customer_price b ON
                (b.i_product = a.i_product)
            INNER JOIN tr_product_grade c ON
                (c.i_product_grade = b.i_product_grade)
            INNER JOIN tr_price_group d ON
                (d.i_price_group = b.i_price_group)
            INNER JOIN tr_product_motif e ON
                (e.i_product_motif = a.i_product_motif)
            inner join tr_product_status s on (s.i_product_status = a.i_product_status)
            WHERE
                (i_product_id ILIKE '%$cari%'
                    OR e_product_name ILIKE '%$cari%')
                AND a.i_company = '$this->i_company'
                AND c.f_default = 't'
                AND c.f_product_gradeactive = 't'
                AND d.f_default2 = 't'
                and a.f_product_active = 't'
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

    /** Simpan Data */
    public function save()
    {
        $query = $this->db->query("SELECT max(i_sr)+1 AS id FROM tm_sr", TRUE);
        if ($query->num_rows() > 0) {
            $id = $query->row()->id;
            if ($id == null) {
                $id = 1;
            } else {
                $id = $id;
            }
        } else {
            $id = 1;
        }

        $header = array(
            'i_company'         => $this->session->i_company,
            'i_sr'              => $id,
            'i_area'            => $this->input->post('i_area'),
            'i_sr_id'           => strtoupper($this->input->post('i_document')),
            'd_sr'              => $this->input->post('d_document'),
            'd_sr_entry'        => current_datetime(),
            'e_remark'          => $this->input->post('e_remark'),
            /* 'i_store'           => $this->input->post('i_store'),
            'i_store_location'  => $this->input->post('i_store'), */
            'n_print'           => 0,
        );
        $this->db->insert('tm_sr', $header);
        if ($this->input->post('jml') > 0) {
            $i = 0;
            if (is_array($this->input->post('i_product')) || is_object($this->input->post('i_product'))) {
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
                        'i_area'            => $this->input->post('i_area'),
                        'n_item_no'         => $i,
                        /* 'n_acc'             => $this->input->post('n_acc')[$i], */
                        /* 'n_saldo'           => $this->input->post('n_saldo')[$i], */
                    );
                    $this->db->insert('tm_sr_item', $item);
                    $i++;
                }
            }
        } else {
            die;
        }
    }

    /** Get Data Untuk Edit */
    public function getdata($id)
    {
        return $this->db->query("
            SELECT 
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
        return $this->db->query("WITH cte AS (
            select a.i_store , i_store_loc from tr_store_loc a 
            inner join tr_store b on (a.i_store = b.i_store) 
            where b.f_store_pusat = true and b.i_company = '$this->i_company' 
            order by b.d_store_update, b.d_store_entry desc nulls last limit 1  
        )
        SELECT
            a.*,
            COALESCE(n_acc,0) AS acc,
            case
                    when s.i_product_statusid = 'STP1' then b.i_product_id || ' (*STP)'
                    when s.i_product_statusid = 'STP2' then b.i_product_id || ' (#STP)'
                    else b.i_product_id
                end as i_product_id,
            b.e_product_name,
            gg.e_product_gradename as e_product_motifname,
            cte.i_store,
            cte.i_store_loc,
            COALESCE(i.n_quantity_stock , 0) as stok
        FROM
            tm_sr_item a
        INNER JOIN tr_product b ON (b.i_product = a.i_product)
        inner join tr_product_status s on
                (s.i_product_status = b.i_product_status)
        INNER JOIN tr_product_motif c ON (c.i_product_motif = a.i_product_motif)
        inner join tr_product_grade gg on (gg.i_product_grade=a.i_product_grade)
        full join cte on (cte.i_store is not null)
        left join tm_ic i on (i.i_store = cte.i_store and i.i_store_location = cte.i_store_loc
        and i.i_product = a.i_product and i.i_product_grade = a.i_product_grade and i.i_product_motif = a.i_product_motif)
        WHERE a.i_sr = '$id' ORDER BY b.e_product_name asc
        ", FALSE);
    }

    /** Cek Apakah Data Sudah Ada Pas Edit */
    public function cek_edit()
    {
        $i_document = $this->input->post('i_document', TRUE);
        $i_document_old = $this->input->post('i_document_old', TRUE);
        return $this->db->query("SELECT 
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
        $header = array(
            'i_company'         => $this->session->i_company,
            'i_area'            => $this->input->post('i_area'),
            'i_sr_id'           => $this->input->post('i_document'),
            'd_sr'              => $this->input->post('d_document'),
            'd_sr_update'       => current_datetime(),
            'e_remark'          => $this->input->post('e_remark'),
            /* 'i_store'           => $this->input->post('i_store'),
            'i_store_location'  => $this->input->post('i_store_location'), */
            'n_print'           => 0,
        );
        $this->db->where('i_sr', $id);
        $this->db->update('tm_sr', $header);
        $jml = $this->input->post('jml');
        if ($this->input->post('jml') > 0) {
            $i = 0;
            if (is_array($this->input->post('i_product')) || is_object($this->input->post('i_product'))) {
                $this->db->where('i_sr', $id);
                $this->db->delete('tm_sr_item');
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
                        'i_area'            => $this->input->post('i_area'),
                        'n_item_no'         => $i,
                        /* 'n_acc'             => $this->input->post('n_acc')[$i], */
                        /* 'n_saldo'           => $this->input->post('n_saldo')[$i], */
                    );
                    $this->db->insert('tm_sr_item', $item);
                    $i++;
                }
            }
        } else {
            die;
        }
    }

    /** Cancel Data */
    public function cancel($id)
    {
        $table = array(
            'f_sr_cancel' => 't',
        );
        $this->db->where('i_sr', $id);
        $this->db->update('tm_sr', $table);
    }

    public function update_print($id)
    {
        $this->db->query("UPDATE tm_sr SET n_print = n_print + 1 WHERE i_sr = '$id' ", FALSE);
    }
}

/* End of file Mmaster.php */
