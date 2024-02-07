<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mhargajual extends CI_Model
{

    /** List Datatable */
    public function serverside()
    {
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("SELECT
                a.i_customer_price AS id,
                b.i_price_groupid || ' - ' || b.e_price_groupname AS price_group,
                c.i_product_id,
                c.e_product_name,
                d.e_product_gradename, 
                s.e_product_statusname,
                a.v_price
            FROM
                tr_customer_price a
            INNER JOIN tr_price_group b ON
                (b.i_price_group = a.i_price_group)
            INNER JOIN tr_product c ON
                (c.i_product = a.i_product)
            INNER JOIN tr_product_grade d ON
                (d.i_product_grade = a.i_product_grade)
            inner join tr_product_status s on
                (s.i_product_status = c.i_product_status)
            WHERE 
                a.i_company = '$this->i_company'
            ORDER BY
                c.e_product_name ASC 
        ", FALSE);

        /** Cek Hak Akses, Apakah User Bisa Edit */
        /* if (check_role($this->id_menu, 3)) {
            $datatables->add('action', function ($data) {
                $id         = trim($data['id']);
                $data       = '';
                $data      .= "<a href='" . base_url() . $this->folder . '/edit/' . encrypt_url($id) . "' title='Edit Data'><i class='icon-note'></i></a>";
                return $data;
            });
        } */
        //$datatables->hide();
        $datatables->edit('v_price', function ($data) {
            return format_rupiah($data['v_price']);
        });

        return $datatables->generate();
    }

    /** Cek Apakah Data Sudah Ada Pas Simpan */
    public function cek($i_product, $i_price_group)
    {
        return $this->db->query("
            SELECT 
                i_customer_price
            FROM 
                tr_customer_price
            WHERE 
                i_price_group = '$i_price_group'
                AND i_price_group = '$i_product'
                AND i_company = '$this->i_company'
        ", FALSE);
    }

    public function get_product($cari)
    {
        return $this->db->query("
            SELECT
                i_product AS id,
                i_product_id AS code,
                e_product_name AS name
            FROM
                tr_product
            WHERE
                i_company = '$this->i_company'
                AND f_product_active = 't'
                AND (i_product_id ILIKE '%$cari%'
		            OR e_product_name ILIKE '%$cari%')
            ORDER BY
                e_product_name ASC
        ", FALSE);
    }

    public function get_price_group($i_product, $i_product_grade)
    {
        return $this->db->query("
            SELECT
                a.i_price_group,
                i_price_groupid,
                e_price_groupname,
                COALESCE (b.v_price,
                0) AS v_price
            FROM
                tr_price_group a
            LEFT JOIN (
                SELECT
                    i_price_group,
                    v_price
                FROM
                    tr_customer_price
                WHERE
                    i_company = '$this->i_company'
                    AND i_product = '$i_product'
                    AND i_product_grade = '$i_product_grade') b ON
                (b.i_price_group = a.i_price_group)
            WHERE 
                i_company = '$this->i_company'
            ORDER BY
                a.i_price_group ASC
        ", FALSE);
    }

    /** Simpan Data */
    public function save($i_product, $i_product_grade)
    {
        $this->db->where('f_price_groupactive', TRUE);
        $this->db->where('i_company', $this->i_company);
        $this->db->order_by('i_price_group', 'ASC');
        $query = $this->db->get('tr_price_group');
        /* $query = $this->db->get_where('tr_price_group', ['f_price_groupactive' => true, 'i_company' => $this->i_company]); */
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $key) {
                $i_price_group  = $this->input->post('i_price_group_' . $key->i_price_group);
                $v_price        = str_replace(",", "", $this->input->post('v_price' . $key->i_price_group));
                $this->db->query("
                    INSERT INTO tr_customer_price (i_company, i_price_group, i_product_grade, i_product, v_price) 
                    VALUES ($this->i_company, '$i_price_group', '$i_product_grade', '$i_product', '$v_price')
                    ON CONFLICT (i_company, i_price_group, i_product_grade, i_product) DO UPDATE 
                    SET v_price = excluded.v_price,
                    d_customer_price_update = now()", false);
                /* $table = array(
                    'i_company'         => $this->i_company,
                    'i_price_group'     => ,
                    'i_product_grade'   => $i_product_grade,
                    'i_product'         => $i_product,
                    'v_price'           => $this->input->post('v_price'.$key->i_price_group),
                );
                $this->db->insert('tr_customer_price', $table); */
            }
        }
    }

    /** Get Data Untuk Edit */
    public function getdata($id)
    {
        return $this->db->query("
            SELECT 
                a.*, b.i_supplier_groupid, b.e_supplier_groupname
            FROM 
                tr_supplier a
                left join tr_supplier_group b on (a.i_supplier_group = b.i_supplier_group)
            WHERE
                a.i_supplier = '$id'
        ", FALSE);
    }

    /** Cek Apakah Data Sudah Ada Pas Edit */
    public function cek_edit($kode, $kodeold)
    {
        return $this->db->query("
            SELECT 
                i_supplier_id
            FROM 
                tr_supplier 
            WHERE 
                trim(upper(i_supplier_id)) <> trim(upper('$kodeold'))
                AND trim(upper(i_supplier_id)) = trim(upper('$kode'))
                AND i_company = '$this->i_company'
        ", FALSE);
    }

    /** Update Data */
    public function update($id, $isuppliergroup, $kode, $nama)
    {
        $table = array(
            'i_supplier_id' => $kode,
            'e_supplier_name' => $nama,
            'i_supplier_group' => $isuppliergroup,
            'd_supplier_update'  => date('Y-m-d H:i:s'),
        );
        $this->db->where('i_supplier', $id);
        $this->db->update('tr_supplier', $table);
    }
}

/* End of file Mmaster.php */
