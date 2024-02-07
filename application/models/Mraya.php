<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mraya extends CI_Model
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

        $i_store = $this->input->post('i_store', TRUE);
        if ($i_store == '') {
            $i_store = $this->uri->segment(5);
        }

        if ($i_store != 'ALL') {
            $store = "AND a.i_store = '$i_store' ";
        } else {
            $store = "";
        }

        $dfrom  = date('Y-m-d', strtotime($dfrom));
        $dto    = date('Y-m-d', strtotime($dto));
        
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("SELECT
                i_ic as id,
                A.n_quantity_stock,
                b.i_product_id,
	            b.e_product_name,
	            c.e_product_motifname,
	            d.e_product_gradename,
	            e.e_store_name,            
                '$dfrom' AS dfrom,
                '$dto' AS dto,
                '$i_store' AS i_store
            from
                tm_ic as a
            inner join
            tr_product as b on
                a.i_product = b.i_product
            inner join 
            tr_product_motif as c on
                a.i_product_motif = c.i_product_motif
            inner join
            tr_product_grade as d on
                a.i_product_grade = d.i_product_grade
            inner join
            tr_store as e on
                a.i_store = e.i_store
                WHERE
                a.i_company = '$this->i_company'   
                $store    
            ORDER BY
            a.n_quantity_stock DESC
        ", FALSE);

        /** Cek Hak Akses, Apakah User Bisa Edit */
        $datatables->add('action', function ($data) {
            $id         = trim($data['id']);
            $dfrom      = $data['dfrom'];
            $dto        = $data['dto'];
            $i_store    = $data['i_store'];
            $data       = '';
            // $data      .= "<a href='" . base_url() . $this->folder . '/view/' . encrypt_url($id) . "' title='View Data'><i class='fa fa-eye fa-lg warning darken-4 mr-1'></i></a>";
            if (check_role($this->id_menu, 3)) {
                $data      .= "<a href='" . base_url() . $this->folder . '/edit/' . encrypt_url($id)  . '/' .  encrypt_url($dfrom) . '/' . encrypt_url($dto) . '/' . encrypt_url($i_store) . "' title='Edit Data'><i class='fa fa-check-circle-o success darken-4 fa-lg mr-1'></i></a>";
            }

            return $data;
        });

        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('i_store');
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
            and f_store_active = 't'
            and i_company = '$this->i_company'
                order by 2
        ", FALSE);
    }

    /** Cari produkid */
    public function get_produk($cari)
    {
        return $this->db->query("
              SELECT 
                 i_product, i_product_id ,e_product_name 
             FROM 
                 tr_product
             WHERE 
                 (e_product_name ILIKE '%$cari%' or i_product_id ILIKE '%$cari%')
                 AND i_company = '$this->i_company' AND f_product_active = true
             ORDER BY 3 ASC
         ", FALSE);
    }

    public function get_namaproduk($rayaa)
    {
        return $this->db->query("
              SELECT 
                 e_product_name 
             FROM 
                 tr_product
             WHERE 
                 i_product = $rayaa
         ", FALSE);
    }

    public function get_storloc($gudang, $loc)
    {
        return $this->db->query("
              SELECT 
                 i_store_loc ,e_store_loc_name 
             FROM 
                 tr_store_loc
             WHERE 
                i_store = $gudang AND
                (e_store_loc_name ILIKE '%$loc%')
                 AND i_company = '$this->i_company' AND f_store_loc_active = true
         ", FALSE);
    }


    /** Simpan Data */
    public function save()
    {
        $table = array(
            'i_company'             => $this->i_company,
            'n_quantity_stock'      => $this->input->post('Stok', TRUE),
            'i_product'             => $this->input->post('iproduct', TRUE),
            'i_product_motif'       => $this->input->post('imotif', TRUE),
            'i_product_grade'       => $this->input->post('igrade', TRUE),
            'i_store'               => $this->input->post('igudang', TRUE),
            'i_store_location'      => $this->input->post('istorloc', TRUE),
            'e_product_name'        => $this->input->post('ebr', TRUE),
        );
        $this->db->insert('tm_ic', $table);
    }


    /** Get Data Untuk Edit */
    public function get_data_edit($id)
    {
        return $this->db->query("SELECT
                i_ic,
                b.i_product as b1,
                b.i_product_id as b2,
                b.e_product_name,
                c.i_store,
                c.i_store_id as c2,
                c.e_store_name as c3,
                n_quantity_stock
            from
                tm_ic a
            inner join tr_product b on (b.i_product=a.i_product)
            inner join tr_store c on (c.i_store=a.i_store)
            WHERE
                a.i_ic = '$id'
        ", FALSE);
    }

    /** Update Data */
    public function update()
    {
        $iic = $this->input->post('iic');
        $header = array(
            'n_quantity_stock'                    => $this->input->post('stk')
        );
        $this->db->where('i_ic', $iic);
        $this->db->update('tm_ic', $header);
    }

    /** Cancel Data */
    public function cancel($id)
    {
        $table = array(
            'f_nota_cancel' => 't',
        );
        $this->db->where('i_nota', $id);
        $this->db->update('tm_nota', $table);
    }

    /** Update Print */
    public function update_print($id)
    {
        $this->db->query("UPDATE tm_nota SET n_print = n_print + 1 WHERE i_nota = '$id' ", FALSE);
    }
}

/* End of file Mmaster.php */
