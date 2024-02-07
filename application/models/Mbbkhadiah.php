<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mbbkhadiah extends CI_Model
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
            $area = "AND b.i_area = '$i_area' ";
        } else {
            $area = "";
        }

        $dfrom  = date('Y-m-d', strtotime($dfrom));
        $dto    = date('Y-m-d', strtotime($dto));

        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("SELECT
                d_bbk as tgl,
                a.i_bbk AS id,
                d_bbk,
                i_bbk_id,
                to_char(d_bbk, 'YYYYMM') as i_periode,
                b.i_customer_id || ' ~ ' || b.e_customer_name AS e_customer_name,
                r.i_area_id || ' - ' || r.e_area_name as are,
                f_bbk_cancel AS f_status,
                '$dfrom' AS dfrom,
                '$dto' AS dto,
                '$i_area' AS i_area
            FROM
                tm_bbk a
            INNER JOIN tr_customer b ON
                (b.i_customer = a.i_customer)
            INNER JOIN tm_user_area c ON
                (c.i_area = b.i_area)
            inner join tr_area r on (r.i_area=b.i_area)
            WHERE
                c.i_user = '$this->i_user'
                AND a.i_company = '$this->i_company'
                AND d_bbk BETWEEN '$dfrom' AND '$dto'
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
            $dfrom      = $data['dfrom'];
            $dto        = $data['dto'];
            $i_area     = $data['i_area'];
            $i_periode  = $data['i_periode'];
            $data       = '';
            $data      .= "<a href='" . base_url() . $this->folder . '/view/' . encrypt_url($id) . '/' . encrypt_url($dfrom) . '/' . encrypt_url($dto) . '/' . encrypt_url($i_area) . "' title='View Data'><i class='fa fa-eye fa-lg warning darken-4 mr-1'></i></a>";
            if ($i_periode >= get_periode3()) {
                if (check_role($this->i_menu, 3) && $f_status == 'f') {
                    $data      .= "<a href='" . base_url() . $this->folder . '/edit/' . encrypt_url($id)  . '/' .  encrypt_url($dfrom) . '/' . encrypt_url($dto) . '/' . encrypt_url($i_area) . "' title='Edit Data'><i class='fa fa-pencil-square success darken-4 fa-lg mr-1'></i></a>";
                }
                if (check_role($this->i_menu, 5) && $f_status == 'f') {
                    $data      .= "<a href='#' onclick='openLink(\"" . $this->folder . "\",\"" . encrypt_url($id) . "\"); return false;' title='Print Data'><i class='fa fa-print fa-lg blue darken-4 mr-1'></i></a>";
                }
                if (check_role($this->i_menu, 4) && $f_status == 'f') {
                    $data      .= "<a href='#' onclick='sweetdeletev33(\"" . $this->folder . "\",\"" . $id . "\",\"" . encrypt_url($dfrom)  . "\",\"" . encrypt_url($dto)  . '/' . encrypt_url($i_area) . "\");' title='Delete Data'><i class='fa fa-trash fa-lg red darken-4'></i></a>";
                }
            }
            return $data;
        });
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('i_area');
        $datatables->hide('i_periode');
        $datatables->hide('id');
        return $datatables->generate();
    }

    /** Running Number Dokumen*/
    public function running_number($thbl, $tahun)
    {
        $cek = $this->db->query("SELECT 
                substring(i_bbk_id, 1, 3) AS kode 
            FROM tm_bbk 
            WHERE i_company = '$this->i_company'
            ORDER BY i_bbk DESC");
        if ($cek->num_rows() > 0) {
            $kode = $cek->row()->kode;
        } else {
            $kode = 'BBK';
        }
        $query  = $this->db->query("SELECT
                max(substring(i_bbk_id, 10, 6)) AS max
            FROM
                tm_bbk
            WHERE to_char (d_bbk, 'yyyy') >= '$tahun'
            AND i_company = '$this->i_company'
            AND substring(i_bbk_id, 1, 3) = '$kode'
            AND substring(i_bbk_id, 5, 2) = substring('$thbl',1,2)
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
        return $this->db->query("SELECT 
                i_bbk_id
            FROM 
                tm_bbk 
            WHERE 
                upper(trim(i_bbk_id)) = upper(trim('$i_document'))
                AND i_company = '$this->i_company'
        ", FALSE);
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

    /** Get Data Customer */
    public function get_customer($cari)
    {
        return $this->db->query("SELECT
                i_customer AS id,
                i_customer_id AS code,
                initcap(e_customer_name) AS name
            FROM
                tr_customer
            WHERE
                (e_customer_name ILIKE '%$cari%'
                    OR i_customer_id ILIKE '%$cari%')
                AND f_customer_active = 't'
                AND i_company = '$this->i_company'
            ORDER BY 3
        ", FALSE);
    }

    /** Get Data Product */
    public function get_product($cari)
    {
        return $this->db->query("SELECT
                i_product,
                i_product_id,
                initcap(e_product_name) AS e_product_name
            FROM
                tr_product
            WHERE
                (i_product_id ILIKE '%$cari%'
                    OR e_product_name ILIKE '%$cari%')
                AND i_company = '$this->i_company'
            ORDER BY
                e_product_name ASC
        ", FALSE);
    }

    /** Get Data Detail Product */
    public function get_product_detail($i_product)
    {
        return $this->db->query("SELECT
                a.i_product,
                b.i_product_grade,
                a.i_product_motif,
                b.v_price,
                initcap(a.e_product_name) AS e_product_name,
                c.e_product_gradename as e_product_motifname,
                p.n_quantity_stock as n_stk
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
            inner join tm_ic p on (p.i_product=a.i_product)
            inner join tr_store q on (q.i_store =p.i_store)
            WHERE
                a.i_product = '$i_product'
                AND c.f_default = 't'
                AND c.f_product_gradeactive = 't'
                AND d.f_default = 't'
                AND a.i_company = '$this->i_company'
                and q.f_store_pusat ='t'
        ", FALSE);
    }

    /** Simpan Data */
    public function save()
    {
        $query = $this->db->query("SELECT max(i_bbk)+1 AS id FROM tm_bbk", TRUE);
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
            'i_company'  => $this->session->i_company,
            'i_bbk'      => $id,
            'i_area'     => $this->db->query("SELECT i_area FROM tr_area WHERE i_company = '$this->i_company' AND f_area_pusat = 't' ")->row()->i_area,
            'i_customer' => $this->input->post('i_customer'),
            'i_bbk_id'   => strtoupper($this->input->post('i_document')),
            'd_bbk'      => $this->input->post('d_document'),
            'e_remark'   => $this->input->post('e_remark'),
        );
        $this->db->insert('tm_bbk', $header);
        if (is_array($this->input->post('i_product')) || is_object($this->input->post('i_product'))) {
            $i = 0;
            foreach ($this->input->post('i_product') as $i_product) {
                if ($i_product != '') {
                    $item = array(
                        'i_bbk'             => $id,
                        'i_product'         => $i_product,
                        'i_product_grade'   => $this->input->post('i_product_grade')[$i],
                        'i_product_motif'   => $this->input->post('i_product_motif')[$i],
                        'n_quantity'        => $this->input->post('n_order')[$i],
                        'v_unit_price'      => $this->input->post('v_unit_price')[$i],
                        'e_remark'          => $this->input->post('e_remarkitem')[$i],
                        'n_item_no'         => $i,
                    );
                    $this->db->insert('tm_bbk_item', $item);
                }
                $i++;
            }
        } else {
            die;
        }
    }

    /** Get Data Untuk Edit */
    public function getdata($id)
    {
        return $this->db->query("SELECT 
                a.*,
                c.i_customer_id,
                c.e_customer_name,
                c.e_customer_address,
                c.e_customer_phone,
                c.i_price_group,
                c.f_customer_plusppn,
                c.n_customer_top,
                c.d_customer_register,
                d.i_area_id,
                d.e_area_name,
	            r.e_city_name
            FROM 
                tm_bbk a
            INNER JOIN tr_customer c ON 
                (c.i_customer = a.i_customer)
            INNER JOIN tr_area d ON 
                (d.i_area = d.i_area)
            inner join tr_city r on
                (r.i_city = c.i_city)
            WHERE
                i_bbk = '$id'
        ", FALSE);
    }

    /** Get Data Untuk Edit */
    public function getdetail($id)
    {
        return $this->db->query("SELECT
                a.*,
                b.i_product_id,
                b.e_product_name,
                g.e_product_gradename as e_product_motifname,
                p.n_quantity_stock as n_stk
            FROM
                tm_bbk_item a
            INNER JOIN tr_product b ON
                (b.i_product = a.i_product)
            INNER JOIN tr_product_motif c ON
                (c.i_product_motif = a.i_product_motif)
            inner join tm_ic p on (p.i_product=a.i_product)
            inner join tr_store q on (q.i_store =p.i_store)
            inner join tr_product_grade g on (g.i_product_grade=a.i_product_grade)
            WHERE
                a.i_bbk = '$id'
                and q.f_store_pusat ='t'
            ORDER BY
                a.n_item_no ASC
        ", FALSE);
    }

    /** Cek Apakah Data Sudah Ada Pas Edit */
    public function cek_edit()
    {
        $i_document = $this->input->post('i_document', TRUE);
        $i_document_old = $this->input->post('i_document_old', TRUE);
        return $this->db->query("SELECT 
                i_bbk_id
            FROM 
                tm_bbk 
            WHERE 
                trim(upper(i_bbk_id)) <> trim(upper('$i_document_old'))
                AND trim(upper(i_bbk_id)) = trim(upper('$i_document'))
                AND i_company = '$this->i_company'
        ", FALSE);
    }

    /** Update Data */
    public function update()
    {
        $id = $this->input->post('id');
        $header = array(
            'i_company'    => $this->session->i_company,
            'i_customer'   => $this->input->post('i_customer'),
            'i_bbk_id'     => $this->input->post('i_document'),
            'd_bbk'        => $this->input->post('d_document'),
            'd_update'     => current_datetime(),
            'e_remark'     => $this->input->post('e_remark'),
        );
        $this->db->where('i_bbk', $id);
        $this->db->update('tm_bbk', $header);
        if (is_array($this->input->post('i_product')) || is_object($this->input->post('i_product'))) {
            $this->db->where('i_bbk', $id);
            $this->db->delete('tm_bbk_item');
            $i = 0;
            foreach ($this->input->post('i_product') as $i_product) {
                if ($i_product != '') {
                    $item = array(
                        'i_bbk'             => $id,
                        'i_product'         => $i_product,
                        'i_product_grade'   => $this->input->post('i_product_grade')[$i],
                        'i_product_motif'   => $this->input->post('i_product_motif')[$i],
                        'n_quantity'        => $this->input->post('n_order')[$i],
                        'v_unit_price'      => $this->input->post('v_unit_price')[$i],
                        'e_remark'          => $this->input->post('e_remarkitem')[$i],
                        'n_item_no'         => $i,
                    );
                    $this->db->insert('tm_bbk_item', $item);
                }
                $i++;
            }
        } else {
            die;
        }
    }

    /** Cancel Data */
    public function cancel($id)
    {
        $table = array(
            'f_bbk_cancel' => 't',
        );
        $this->db->where('i_bbk', $id);
        $this->db->update('tm_bbk', $table);
    }
}

/* End of file Mmaster.php */
