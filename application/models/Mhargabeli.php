<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mhargabeli extends CI_Model
{

    /** List Datatable */
    public function serverside()
    {
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("SELECT
                a.i_supplier_price AS id,
                b.e_supplier_name,
                c.i_product_id,
                c.e_product_name,
                s.e_product_statusname,
                a.v_price,
	            a.f_sup_aktif as f_status
            FROM
                tr_supplier_price a
            INNER JOIN tr_supplier b ON
                (b.i_supplier = a.i_supplier)
            INNER JOIN tr_product c ON
                (c.i_product = a.i_product)
            inner join tr_product_status s on
                (s.i_product_status = c.i_product_status)
            WHERE 
                a.i_company = '$this->i_company'
            ORDER BY
                c.e_product_name ASC", FALSE);

        $datatables->edit('f_status', function ($data) {
            $id         = $data['id'];
            if ($data['f_status'] == 't') {
                $status = $this->lang->line('Aktif');
                $color  = 'success';
            } else {
                $status = $this->lang->line('Tidak Aktif');
                $color  = 'danger';
            }
            $data = "<button class='btn btn-outline-" . $color . " btn-sm round' onclick='changestatus(\"" . $this->folder . "\",\"" . $id . "\");'>" . $status . "</button>";
            return $data;
        });

        $datatables->edit('v_price', function ($data) {
            return format_rupiah($data['v_price']);
        });


        /** Cek Hak Akses, Apakah User Bisa Edit */
        if (check_role($this->id_menu, 3)) {
            $datatables->add('action', function ($data) {
                $id         = trim($data['id']);
                $data       = '';
                $data      .= "<a href='" . base_url() . $this->folder . '/edit/' . encrypt_url($id) . "' title='Edit Data'><i class='fa fa-pencil-square success darken-4 fa-lg mr-1'></i></a>";
                return $data;
            });
        }
        return $datatables->generate();
    }

    public function changestatus($id)
    {
        $this->db->select('f_sup_aktif');
        $this->db->from('tr_supplier_price');
        $this->db->where('i_supplier_price', $id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $status = $query->row()->f_sup_aktif;
        } else {
            $status = 'f';
        }
        if ($status == 'f') {
            $fstatus = 't';
        } else {
            $fstatus = 'f';
        }
        $table = array(
            'f_sup_aktif' => $fstatus,
        );
        $this->db->where('i_supplier_price', $id);
        $this->db->update('tr_supplier_price', $table);
    }

    public function get_supplier($cari)
    {
        return $this->db->query("
            SELECT
                i_supplier AS id,
                i_supplier_id AS code,
                e_supplier_name AS name
            FROM
                tr_supplier
            WHERE
                i_company = '$this->i_company'
                AND f_supplier_active = 't'
                AND (i_supplier_id ILIKE '%$cari%'
		            OR e_supplier_name ILIKE '%$cari%')
            ORDER BY
                e_supplier_name ASC
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

    /** Cek Apakah Data Sudah Ada Pas Simpan */
    public function cek($isupplier, $iproduct)
    {
        return $this->db->query("
            SELECT 
                i_supplier
            FROM 
                tr_supplier_price 
            WHERE 
                i_supplier = '$isupplier' 
                AND i_product = '$iproduct'
                AND i_company = $this->i_company
     
        ", FALSE);
    }

    /** Simpan Data */
    public function save($isupplier, $iproduct, $v_price)
    {
        $table = array(
            'i_company'                   => $this->i_company,
            'i_supplier'          => $isupplier,
            'i_product'     => $iproduct,
            'v_price'     => $v_price,
            'd_supplier_price_entry'  => current_datetime(),
        );
        $this->db->insert('tr_supplier_price', $table);
    }

    /** Get Data Untuk Edit */
    public function getdata($id)
    {
        return $this->db->query("
          SELECT 
               a.i_supplier_price, b.i_supplier , b.i_supplier_id , b.e_supplier_name ,
               c.i_product , c.i_product_id , c.e_product_name , a.v_price
            FROM 
                tr_supplier_price a
            inner join tr_supplier b on (a.i_supplier = b.i_supplier)
            inner join tr_product c on (a.i_product = c.i_product)
            WHERE
                a.i_supplier_price = '$id'
        ", FALSE);
    }

    /** Cek Apakah Data Sudah Ada Pas Edit */
    public function cek_edit($isupplier, $isupplierold, $iproduct, $iproductold)
    {
        return $this->db->query("
            SELECT 
                i_supplier_price
            FROM 
                tr_supplier_price 
            WHERE 
                ((i_supplier = '$isupplier'
                AND i_supplier <> '$isupplierold') 
                AND 
                (i_product = '$iproduct'
                AND i_product <> '$iproductold'))
                AND i_company = $this->i_company
        ", FALSE);
    }

    /** Update Data */
    public function update($i_supplier_price, $isupplier, $iproduct, $v_price)
    {
        $table = array(
            'i_company'                   => $this->i_company,
            'i_supplier'          => $isupplier,
            'i_product'     => $iproduct,
            'v_price'   => $v_price,
            'd_supplier_price_update' => current_datetime(),
        );
        $this->db->where('i_supplier_price', $i_supplier_price);
        $this->db->update('tr_supplier_price', $table);
    }
}

/* End of file Mmaster.php */
