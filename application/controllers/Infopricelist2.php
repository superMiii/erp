<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Infopricelist2 extends CI_Controller
{
	public $id_menu = '90204';

	public function __construct()
	{
		parent::__construct();
		cek_session();

		/** Cek Hak Akses, Apakah User Bisa Read */
		$data = check_role($this->id_menu, 2);
		if (!$data) {
			redirect(base_url(), 'refresh');
		}

		/** Deklarasi Nama Folder, Title dan Icon */
		$this->folder 		= $data->e_folder;
		$this->title		= $data->e_menu;
		$this->icon			= $data->icon;
		$this->i_company 	= $this->session->i_company;
		$this->i_user 		= $this->session->i_user;

		/** Load Model, Nama model harus sama dengan nama folder */
		$this->load->model('m' . $this->folder, 'mymodel');
	}

	/** Default Controllers */
	public function index()
	{
		add_css(
			array(
				'app-assets/vendors/css/pickers/pickadate/pickadate.css',
			)
		);

		add_js(
			array(
				'app-assets/vendors/js/pickers/pickadate/picker.js',
				'app-assets/vendors/js/pickers/pickadate/picker.date.js',
				'assets/js/' . $this->folder . '/index.js?v=9',
			)
		);

		$dfrom = $this->input->post('dfrom', TRUE);
		if ($dfrom == '') {
			$dfrom = $this->uri->segment(3);
			if ($dfrom == '') {
				$dfrom = '01-' . date('m-Y');
			}
		}
		$dto = $this->input->post('dto', TRUE);
		if ($dto == '') {
			$dto = $this->uri->segment(4);
			if ($dto == '') {
				$dto = date('d-m-Y');
			}
		}
		$data = array(
			'price'		=> $this->mymodel->get_group(),
			'data'      => $this->mymodel->serverside(),
		);

		$this->logger->write('Membuka Menu ' . $this->title);
		$this->template->load('main', $this->folder . '/index', $data);
	}





	function export()
	{
		// $year = $this->input->post('year', TRUE);
		// if ($year == '') {
		// 	$year = $this->uri->segment(3);
		// }
		// $i_area = $this->input->post('i_area', TRUE);
		// if ($i_area == '') {
		// 	$i_area = $this->uri->segment(4);
		// }
		$data = $this->mymodel->serverside();
		$price = $this->mymodel->get_group();

		$html_data = '<table class="table table-xs display nowrap table-striped table-bordered" id="serverside" width="100%;">
		<thead>
			<tr>
				<th  class="text-center" width="5%;">No</th>
				<th >' . $this->lang->line('Kode') . '</th>
				<th >' . $this->lang->line('Nama Barang') . '</th>';
		if ($price->num_rows() > 0) {
			foreach ($price->result() as $key) {
				$html_data .= '
						<th class="text-right">' . $key->e_price_groupname . '</th>
						';
			}
		}
		$html_data .= '
				<th class="text-right" >' . $this->lang->line('Harga Beli') . '</th>
				<th >' . $this->lang->line('Kelompok Barang') . '</th>
				<th >' . $this->lang->line('Kategori Barang') . '</th>
				<th >' . $this->lang->line('Sub Kategori Barang') . '</th>
				<th >' . $this->lang->line('Tgl Daftar') . '</th>
				<th >' . $this->lang->line('Grade Barang') . '</th>
				<th class="text-center" >' . $this->lang->line('Status') . '</th>
				<th  >' . $this->lang->line('Status Barang') . '</th>
				<th  >' . $this->lang->line('Kode Pemasok') . '</th>
				<th  >' . $this->lang->line('Nama Pemasok') . '</th>
				<th class="text-center" >' . $this->lang->line('Stok') . '</th>
				<th class="text-center" >' . $this->lang->line('Kelompok Pemasok') . '</th>
			</tr>
		</thead>
		<tbody>';
		if ($data->num_rows() > 0) {
			$i = 1;
			foreach ($data->result() as $key) {
				$html_data .= '<tr>
						<td class="text-center">' . $i . '</td>
						<td>' . $key->i_product_id . '</td>
						<td>' . $key->e_product_name . '</td>';
				foreach (json_decode($key->v_price) as $v_price) {
					$html_data .= '<td class="text-right"><strong>' . $v_price . '</strong></td>';
				}
				$html_data .= '
				<td class="text-right">' . $key->v_price2 . '</td>
				<td>' . $key->e_product_groupname . '</td>
				<td>' . $key->e_product_categoryname . '</td>
				<td>' . $key->e_product_subcategoryname . '</td>
				<td>' . $key->d_product_entry . '</td>
				<td>' . $key->e_product_gradename . '</td>
				<td>' . $key->f_product_active . '</td>
				<td>' . $key->e_product_statusname . '</td>
				<td>' . $key->i_supplier_id . '</td>
				<td>' . $key->e_supplier_name . '</td>
				<td>' . $key->n_quantity_stock . '</td>
				<td>' . $key->e_supplier_groupname . '</td></tr>';
				$i++;
			}
		}
		$html_data .= '</tbody>
	</table>';


		$nama_file = "Harga Beli";
		$nama_file .= ".xls";
		$export_excel1 = 'ABC';
		/*if ($export_excel1 != 'f')
				   $nama_file.= ".xls";
			   else
				   $nama_file.= ".ods";*/
		$data = $html_data;
		$this->logger->write('Export Data ' . $this->title);
		$dir = getcwd();
		include($dir . "/application/libraries/GenerateExcelFile.php");
		return true;
	}
}
