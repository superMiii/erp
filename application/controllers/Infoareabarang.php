<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Infoareabarang extends CI_Controller
{
	public $id_menu = '90106';

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
				'assets/js/' . $this->folder . '/index.js?v=1910',
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
			'area'		=> $this->mymodel->get_group(),
			'data'      => $this->mymodel->serverside(),
			'dfrom'     => date('d-m-Y', strtotime($dfrom)),
			'dto'       => date('d-m-Y', strtotime($dto)),
		);

		$this->logger->write('Membuka Menu ' . $this->title);
		$this->template->load('main', $this->folder . '/index', $data);
	}


	function export()
	{
		$dfrom = $this->input->post('dfrom', TRUE);
		if ($dfrom == '') {
			$dfrom = $this->uri->segment(3);
		}
		$dto = $this->input->post('dto', TRUE);
		if ($dto == '') {
			$dto = $this->uri->segment(4);
		}
		$data = $this->mymodel->serverside2($dfrom, $dto);
		$bulan = $this->mymodel->get_group();

		$html_data = '<table class="table table-xs display nowrap table-striped table-bordered" id="serverside" width="100%;">
		<thead>
			<tr>
				<th rowspan="2" class="text-center" width="5%;">No</th>
				<th class="text-center" rowspan="2">' . $this->lang->line('Kategori') . '</th>
				<th class="text-center" rowspan="2">' . $this->lang->line('Kode') . '</th>
				<th class="text-center" rowspan="2">' . $this->lang->line('Nama Barang') . '</th>
				<th class="text-center" colspan="' . $bulan->num_rows() . '"> ' . $this->lang->line('Area') . '</th>
				<th class="text-center" rowspan="2">' . $this->lang->line('Total') . '</th>
			</tr>
			<tr>';
		if ($bulan->num_rows() > 0) {
			foreach ($bulan->result() as $key) {
				$html_data .= '
				<th class="text-right">' . $key->i_area_id . '</th>
				';
			}
		}
		$html_data .= '</tr>
		</thead>
		<tbody>';
		if ($data->num_rows() > 0) {
			$i = 1;
			foreach ($data->result() as $key) {
				$html_data .= '<tr>
						<td class="text-center">' . $i . '</td>
						<td>' . $key->e_product_categoryname . '</td>
						<td>' . $key->i_product_id . '</td>
						<td>' . $key->e_product_name . '</td>';
				$x = 0;
				foreach (json_decode($key->n_deliver) as $n_deliver) {
					$x += $n_deliver;
					if ($n_deliver > 0) {
						$html_data .= '<td class="text-right"><strong>' . $n_deliver . '</strong></td>';
					} else {
						$html_data .= '<td class="text-right">' . $n_deliver . '</td>';
					}
				}
				$html_data .= '<td class="text-right"><strong>' . $x . '</strong></td>						
					</tr>';
				$i++;
			}
		}
		$html_data .= '</tbody>
	</table>';


		$nama_file = "Penjualan_Barang_Perarea_NOTA";
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
