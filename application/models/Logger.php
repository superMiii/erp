<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Logger extends CI_Model {

	public function __construct()
    {
        $this->i_company = $this->session->i_company;
        $this->i_user    = $this->session->i_user;
    }

    public function write($pesan)   
	{
		$i_user = $this->session->userdata('i_user');
		if ($i_user!=null || $i_user!='') {
			$ip_address = $_SERVER['REMOTE_ADDR'];
			$data = array(
				'i_user' 	 => $i_user,
				'ip_address' => $ip_address,
				'waktu' 	 => current_datetime(),
				'activity' 	 => $pesan
			);
			$this->db->insert('tm_log', $data);
		}
	}

	public function get_product($i_product)
	{
		$product = '';
		$query = $this->db->query("SELECT i_product_id||' - '||e_product_name AS e_product_name FROM tr_product WHERE i_product = '$i_product' AND f_product_active = 't' AND i_company = '$this->i_company' ", FALSE);
		if ($query->num_rows()>0) {
			$product = $query->row()->e_product_name;
		}
		return $product;
	}

	public function get_store($i_store)
	{
		$store = '';
		$query = $this->db->query("SELECT '[ '||i_store_id||' ]  '||e_store_name AS e_store_name FROM tr_store WHERE i_store = '$i_store' AND f_store_active = 't' AND i_company = '$this->i_company' ", FALSE);
		if ($query->num_rows()>0) {
			$store = $query->row()->e_store_name;
		}
		return $store;
	}

	public function get_supplier($i_supplier)
	{
		$supplier = '';
		$query = $this->db->query("SELECT i_supplier_id||' - '||e_supplier_name AS e_supplier_name FROM tr_supplier WHERE i_supplier = '$i_supplier' AND f_supplier_active = 't' AND i_company = '$this->i_company' ", FALSE);
		if ($query->num_rows()>0) {
			$supplier = $query->row()->e_supplier_name;
		}
		return $supplier;
	}

	public function get_customer($i_customer)
	{
		$customer = '';
		$query = $this->db->query("SELECT i_customer_id||' - '||e_customer_name AS e_customer_name FROM tr_customer WHERE i_customer = '$i_customer' AND f_customer_active = 't' AND i_company = '$this->i_company' ", FALSE);
		if ($query->num_rows()>0) {
			$customer = $query->row()->e_customer_name;
		}
		return $customer;
	}



    /** Get Store */
    public function get_master_store($cari)
    {
        return $this->db->query("SELECT 
                DISTINCT
                a.i_store, 
                i_store_id, 
                initcap(e_store_name) AS e_store_name
            FROM 
                tr_store a
            INNER JOIN tr_area b 
                ON (b.i_store = a.i_store) 
            INNER JOIN tm_user_area c 
                ON (b.i_area = c.i_area) 
            WHERE 
                (e_store_name ILIKE '%$cari%' OR i_store_id ILIKE '%$cari%')
                AND a.i_company = '$this->i_company' 
                AND f_store_active = true
                AND c.i_user = '$this->i_user' 
            ORDER BY 3 ASC
        ", FALSE);
    }
}

/* End of file Logger.php */
