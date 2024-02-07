<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Infospbcus2 extends CI_Controller
{
	public $id_menu = '90115';

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
				'app-assets/vendors/css/tables/datatable/buttons.bootstrap4.min.css',
				'app-assets/vendors/css/extensions/sweetalert2.min.css',
				'app-assets/vendors/css/forms/selects/select2.min.css',
				'app-assets/vendors/css/animate/animate.css',
				'app-assets/vendors/css/pickers/pickadate/pickadate.css',
			)
		);

		add_js(
			array(
				'app-assets/vendors/js/tables/datatable/buttons.bootstrap4.min.js',
				'app-assets/vendors/js/extensions/sweetalert2.all.min.js',
				'app-assets/vendors/js/forms/select/select2.full.min.js',
				'app-assets/vendors/js/pickers/pickadate/picker.js',
				'app-assets/vendors/js/pickers/pickadate/picker.date.js',
				'assets/js/' . $this->folder . '/index.js?v='.date('YmdHis'),
			)
		);

		$year = $this->input->post('year', TRUE);
		if ($year == '') {
			$year = $this->uri->segment(3);
			if ($year == '') {
				$year = date('Y');
			}
		}
		$i_area = $this->input->post('i_area', TRUE);
		if ($i_area == '') {
			$i_area = $this->uri->segment(4);
			if ($i_area == '') {
				$i_area = '0';
			}
		}
		$i_customer = $this->input->post('i_customer', TRUE);
		if ($i_customer == '') {
			$i_customer = $this->uri->segment(5);
			if ($i_customer == '') {
				$i_customer = '0';
			}
		}

		if ($i_area != '0') {
			$e_area_name = $this->db->get_where('tr_area', ['f_area_active' => true, 'i_company' => $this->i_company, 'i_area' => $i_area])->row()->e_area_name;
		} else {
			$e_area_name = 'Nasional';
		}
		if ($i_customer != '0') {
			$e_customer_name = $this->db->get_where('tr_customer', ['f_customer_active' => true, 'i_company' => $this->i_company, 'i_customer' => $i_customer])->row()->e_customer_name;
		} else {
			$e_customer_name = 'Semua';
		}
		$data = array(
			'bulan'		=> $this->mymodel->get_group(),
			'year'               => $year,
			'i_area'            => $i_area,
			'e_area_name'       => ucwords(strtolower($e_area_name)),
			'i_customer'        => $i_customer,
			'e_customer_name'   => ucwords(strtolower($e_customer_name)),
			'data'      => $this->mymodel->serverside(),
		);

		$this->logger->write('Membuka Menu ' . $this->title);
		$this->template->load('main', $this->folder . '/index', $data);
	}

	/** Get Data Area */
	public function get_area()
	{
		$filter = [];
		$filter[] = array(
			'id'   => '0',
			'text' => 'Nasional',
		);
		$data = $this->mymodel->get_area(str_replace("'", "", $this->input->get('q')));
		foreach ($data->result() as $row) {
			$filter[] = array(
				'id'   => $row->i_area,
				'text' => $row->i_area_id . ' - ' . $row->e_area_name,
			);
		}
		echo json_encode($filter);
	}

	/** Get Data Customer */
	public function get_customer()
	{
		$filter = [];
		$filter[] = array(
			'id'   => '0',
			'text' => 'Semua',
		);
		$cari   = str_replace("'", "", $this->input->get('q'));
		$i_area = $this->input->get('i_area');
		if ($i_area != '') {
			$data = $this->mymodel->get_customer($cari, $i_area);
			foreach ($data->result() as $row) {
				$filter[] = array(
					'id'   => $row->i_customer,
					'text' => $row->i_customer_id . ' - ' . $row->e_customer_name,
				);
			}
		} else {
			$filter[] = array(
				'id'   => null,
				'text' => $this->lang->line('Pilih') . ' ' . $this->lang->line('Area'),
			);
		}
		echo json_encode($filter);
	}

	function export()
	{
		$year = $this->input->post('year', TRUE);
		if ($year == '') {
			$year = $this->uri->segment(3);
		}
		$i_area = $this->input->post('i_area', TRUE);
		if ($i_area == '') {
			$i_area = $this->uri->segment(4);
		}
		$i_customer = $this->input->post('i_customer', TRUE);
		if ($i_customer == '') {
			$i_customer = $this->uri->segment(5);
		}
		$data = $this->mymodel->serverside2($year, $i_area, $i_customer);
		$bulan = $this->mymodel->get_group();

		$html_data = '<table class="table table-xs display nowrap table-striped table-bordered" id="serverside" width="100%;">
		<thead>
			<tr>
				<th rowspan="2" class="text-center" width="5%;">No</th>
				<th class="text-center" rowspan="2">' . $this->lang->line('Nama Area Provinsi') . '</th>
				<th class="text-center" rowspan="2">' . $this->lang->line('Nama Pelanggan') . '</th>
				<th class="text-center" rowspan="2">' . $this->lang->line('Kode Barang') . '</th>
				<th class="text-center" rowspan="2">' . $this->lang->line('Nama Barang') . '</th>
				<th class="text-center" rowspan="2">' . $this->lang->line('Harga Terakhir') . '</th>
				<th class="text-center" colspan="' . $bulan->num_rows() . '"> ' . $this->lang->line('Bulan') . '</th>
				<th class="text-center" rowspan="2">' . $this->lang->line('Total') . '</th>
			</tr>
			<tr>';
		if ($bulan->num_rows() > 0) {
			foreach ($bulan->result() as $key) {
				$html_data .= '
				<th class="text-right">' . $key->bulan . '</th>
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
						<td>' . $key->an . '</td>
						<td>' . $key->cn . '</td>
						<td>' . $key->mr . '</td>
						<td>' . $key->pn . '</td>
						<td>' . $key->latest_v_unit_price . '</td>';
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


		$nama_file = "Customer Card";
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
