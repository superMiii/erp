<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Alokasikb extends CI_Controller
{
	public $id_menu = '51202';

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
		$this->e_user_name	= $this->session->e_user_name;

		/** Load Model, Nama model harus sama dengan nama folder */
		$this->load->model('m' . $this->folder, 'mymodel');
	}

	/** Default Controllers */
	public function index()
	{
		add_css(
			array(
				'app-assets/vendors/css/tables/datatable/datatables.min.css',
				'app-assets/vendors/css/tables/extensions/buttons.dataTables.min.css',
				'app-assets/vendors/css/tables/datatable/buttons.bootstrap4.min.css',
				'app-assets/vendors/css/extensions/sweetalert2.min.css',
				'app-assets/vendors/css/animate/animate.css',
				'app-assets/vendors/css/pickers/pickadate/pickadate.css',
				'app-assets/vendors/css/forms/selects/select2.min.css',
			)
		);

		add_js(
			array(
				'app-assets/vendors/js/tables/datatable/datatables.min.js',
				'app-assets/vendors/js/tables/datatable/dataTables.buttons.min.js',
				'app-assets/vendors/js/tables/datatable/buttons.bootstrap4.min.js',
				'app-assets/vendors/js/extensions/sweetalert2.all.min.js',
				'app-assets/vendors/js/pickers/pickadate/picker.js',
				'app-assets/vendors/js/pickers/pickadate/picker.date.js',
				'assets/js/' . $this->folder . '/index.js?v='.date('YmdHis'),
				'app-assets/vendors/js/forms/select/select2.full.min.js',
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

		$i_supplier = $this->input->post('i_supplier', TRUE);
		if ($i_supplier == '') {
			$i_supplier = $this->uri->segment(5);
			if ($i_supplier == '') {
				$i_supplier = '0';
			}
		}

		if (strlen($dfrom) != 10) {
			$dfrom = decrypt_url($dfrom);
		}
		if (strlen($dto) != 10) {
			$dto = decrypt_url($dto);
		}
		if (strlen($i_supplier) > 10) {
			$i_supplier = decrypt_url($i_supplier);
		}

		if ($i_supplier != '0') {
			$e_supplier_name = $this->db->get_where('tr_supplier', ['f_supplier_active' => true, 'i_company' => $this->i_company, 'i_supplier' => $i_supplier])->row()->e_supplier_name;
		} else {
			$e_supplier_name = '( Semua Pemasok )';
		}

		$data = array(
			// 'dfrom'     => date('d-m-Y', strtotime('-1 month', strtotime($dfrom))),
			'dfrom'     => date('d-m-Y', strtotime($dfrom)),
			'dto'       => date('d-m-Y', strtotime($dto)),
			'i_supplier'        => $i_supplier,
			'e_supplier_name'   => ucwords(strtolower($e_supplier_name)),
		);

		$this->logger->write('Membuka Menu ' . $this->title);
		$this->template->load('main', $this->folder . '/index', $data);
	}

	/** List Data */
	public function serverside()
	{
		echo $this->mymodel->serverside();
	}

	/** Referensi */
	public function indexx()
	{
		add_css(
			array(
				'app-assets/vendors/css/tables/datatable/datatables.min.css',
				'app-assets/vendors/css/tables/extensions/buttons.dataTables.min.css',
				'app-assets/vendors/css/tables/datatable/buttons.bootstrap4.min.css',
				'app-assets/vendors/css/extensions/sweetalert2.min.css',
				'app-assets/vendors/css/animate/animate.css',
				'app-assets/vendors/css/pickers/pickadate/pickadate.css',
			)
		);

		add_js(
			array(
				'app-assets/vendors/js/tables/datatable/datatables.min.js',
				'app-assets/vendors/js/tables/datatable/dataTables.buttons.min.js',
				'app-assets/vendors/js/tables/datatable/buttons.bootstrap4.min.js',
				'app-assets/vendors/js/extensions/sweetalert2.all.min.js',
				'app-assets/vendors/js/pickers/pickadate/picker.js',
				'app-assets/vendors/js/pickers/pickadate/picker.date.js',
				'assets/js/' . $this->folder . '/indexx.js?v='.date('YmdHis'),
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

		$i_supplier = $this->input->post('i_supplier', TRUE);
		if ($i_supplier == '') {
			$i_supplier = $this->uri->segment(5);
			if ($i_supplier == '') {
				$i_supplier = '0';
			}
		}

		if (strlen($dfrom) != 10) {
			$dfrom = decrypt_url($dfrom);
		}
		if (strlen($dto) != 10) {
			$dto = decrypt_url($dto);
		}
		if (strlen($i_supplier) > 10) {
			$i_supplier = decrypt_url($i_supplier);
		}

		if ($i_supplier != '0') {
			$e_supplier_name = $this->db->get_where('tr_supplier', ['f_supplier_active' => true, 'i_company' => $this->i_company, 'i_supplier' => $i_supplier])->row()->e_supplier_name;
		} else {
			$e_supplier_name = '( Semua Pemasok )';
		}

		$data = array(
			// 'dfrom'     => date('d-m-Y', strtotime('-1 month', strtotime($dfrom))),
			'dfrom'     => date('d-m-Y', strtotime($dfrom)),
			'dto'       => date('d-m-Y', strtotime($dto)),
			'i_supplier'        => $i_supplier,
			'e_supplier_name'   => ucwords(strtolower($e_supplier_name)),
		);

		$this->logger->write('Membuka Menu Daftar Bank Masuk' . $this->title);
		$this->template->load('main', $this->folder . '/indexx', $data);
	}

	/** List Data Referensi */
	public function serversidex()
	{
		echo $this->mymodel->serversidex();
	}

	/** Get Data Area */
	public function get_supplier0()
	{
		$filter = [];
		$filter[] = array(
			'id'   => '0',
			'text' => '( Semua Pemasok )',
		);
		$data = $this->mymodel->get_supplier0(str_replace("'", "", $this->input->get('q')));
		foreach ($data->result() as $row) {
			$filter[] = array(
				'id'   => $row->i_supplier,
				'text' => $row->e_supplier_name,
			);
		}
		echo json_encode($filter);
	}

	/** Redirect ke Form Tambah */
	public function add()
	{
		/** Cek Hak Akses, Apakah User Bisa Create */
		$data = check_role($this->id_menu, 1);
		if (!$data) {
			redirect(base_url(), 'refresh');
		}

		add_css(
			array(
				'app-assets/css/plugins/forms/validation/form-validation.css',
				'app-assets/vendors/css/extensions/sweetalert2.min.css',
				'app-assets/vendors/css/animate/animate.css',
				'app-assets/vendors/css/forms/selects/select2.min.css',
				'app-assets/css/global.css',
			)
		);

		add_js(
			array(
				'app-assets/vendors/js/forms/validation/jqBootstrapValidation.js',
				'app-assets/vendors/js/extensions/sweetalert2.all.min.js',
				'app-assets/vendors/js/forms/select/select2.full.min.js',
				'assets/js/' . $this->folder . '/add.js?v='.date('YmdHis'),
			)
		);

		$id = decrypt_url($this->uri->segment(3));
		$dfrom = decrypt_url($this->uri->segment(4));
		$dto = decrypt_url($this->uri->segment(5));
		$data = array(
			'data' 	 => $this->mymodel->get_data_pv($id)->row(),
			'dfrom'	 => $dfrom,
			'dto'	 => $dto,
		);
		$this->logger->write('Membuka Form Tambah ' . $this->title);
		$this->template->load('main', $this->folder . '/add', $data);
	}

	/** Cek Number */
	public function cek_code()
	{
		$data = $this->mymodel->cek_code();
		if ($data->num_rows() > 0) {
			echo json_encode(1);
		} else {
			echo json_encode(0);
		}
	}

	/** Get Number */
	public function number()
	{
		$tanggal = $this->input->post('tanggal', TRUE);
		if ($tanggal != '') {
			$number = $this->mymodel->running_number(date('ym', strtotime($tanggal)), date('Y', strtotime($tanggal)));
		} else {
			$number = $this->mymodel->running_number(date('ym'), date('Y'));
		}
		echo json_encode($number);
	}

	/** Cek Number Edit*/
	public function cek_code_edit()
	{
		$data = $this->mymodel->cek_edit();
		if ($data->num_rows() > 0) {
			echo json_encode(1);
		} else {
			echo json_encode(0);
		}
	}

	/** Get Data supplier */
	public function get_supplier()
	{
		$filter = [];
		$cari 	= str_replace("'", "", $this->input->get('q'));
		$data = $this->mymodel->get_supplier($cari);
		foreach ($data->result() as $row) {
			$filter[] = array(
				'id'   => $row->i_supplier,
				'text' => $row->i_supplier_id . ' - ' . $row->e_supplier_name,
			);
		}
		echo json_encode($filter);
	}

	/** Get Data Nota */
	public function get_nota()
	{
		$filter = [];
		$cari   = str_replace("'", "", $this->input->get('q'));
		$i_supplier = $this->input->get('i_supplier');
		if ($i_supplier != '' || $i_supplier != null) {
			$data = $this->mymodel->get_nota($cari, $i_supplier);
			foreach ($data->result() as $row) {
				$filter[] = array(
					'id'   => $row->i_nota,
					'text' => $row->i_nota_id,
				);
			}
		} else {
			$filter[] = array(
				'id'   => null,
				'text' => $this->lang->line('Pilih') . ' ' . $this->lang->line('Pemasok'),
			);
		}
		echo json_encode($filter);
	}

	/** Get Detail Nota */
	public function get_detail_nota()
	{
		header("Content-Type: application/json", true);
		$query = '';
		$i_nota = $this->input->post('i_nota', TRUE);
		if ($i_nota != '' || $i_nota != null) {
			$query  = array(
				'detail' => $this->mymodel->get_detail_nota($i_nota)->result_array()
			);
		}
		echo json_encode($query);
	}

	/** Simpan Data */
	public function save()
	{
		/** Cek Hak Akses, Apakah User Bisa Create */
		$data = check_role($this->id_menu, 1);
		if (!$data) {
			redirect(base_url(), 'refresh');
		}

		$this->form_validation->set_rules('i_supplier', 'i_supplier', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('i_document', 'i_document', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('d_document', 'd_document', 'trim|required|min_length[0]');

		if ($this->form_validation->run() == false) {
			$data = array(
				'sukses' => false,
				'ada'	 => false,
			);
		} else {
			/** Simpan Data */
			$this->db->trans_begin();
			$this->mymodel->save();
			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				$data = array(
					'sukses' => false,
					'ada'	 => false,
				);
			} else {
				$this->db->trans_commit();
				$this->logger->write('Simpan ' . $this->title . '  : ' . $this->input->post('i_document') . ' I_Area : ' . $this->input->post('i_area'). ' : ' . $this->session->e_company_name);
				$data = array(
					'sukses' => true,
					'ada'	 => false,
				);
			}
		}
		echo json_encode($data);
	}


	/** Cancel */
	public function delete2()
	{
		/** Cek Hak Akses, Apakah User Bisa Hapus */
		$data = check_role($this->id_menu, 4);
		if (!$data) {
			redirect(base_url(), 'refresh');
		}

		$this->form_validation->set_rules('id', 'id', 'trim|required|min_length[0]');
		$id = $this->input->post('id', TRUE);
		$alasan = $this->input->post('alasan', TRUE);

		$i_alokasi_id = $this->db->get_where('tm_alokasi_kb', ['i_company' => $this->i_company, 'i_alokasi'  => $id])->row()->i_alokasi_id;
		if ($this->form_validation->run() == false) {
			$data = array(
				'sukses' => false,
			);
		} else {
			$this->db->trans_begin();
			$this->mymodel->cancel($id,$alasan,$i_alokasi_id);
			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				$data = array(
					'sukses' => false,
				);
			} else {
				$this->db->trans_commit();
				$this->logger->write('Batal ' . $this->title . ' Id : ' . $id . ' : ' . $i_alokasi_id. ' : ' . $this->session->e_company_name);
				$data = array(
					'sukses' => true,
				);
			}
		}
		echo json_encode($data);
	}

	/** Redirect ke Form View */
	public function view()
	{
		/** Cek Hak Akses, Apakah User Bisa View */
		$data = check_role($this->id_menu, 2);
		if (!$data) {
			redirect(base_url(), 'refresh');
		}

		$id 	= decrypt_url($this->uri->segment(3));
		$dfrom 	= decrypt_url($this->uri->segment(4));
		$dto 	= decrypt_url($this->uri->segment(5));
		$harea		= decrypt_url($this->uri->segment(6));
		$data = array(
			'data' 	 => $this->mymodel->get_data($id)->row(),
			'detail' => $this->mymodel->get_data_detail($id),
			'dfrom'	 => $dfrom,
			'dto'	 => $dto,
			'harea'	 => $harea,
		);
		$this->logger->write('Membuka Form View ' . $this->title);
		$this->template->load('main', $this->folder . '/view', $data);
	}



	/** Update Print */
	public function update_print()
	{
		// $this->form_validation->set_rules('id', 'id', 'trim|required|min_length[0]');
		// $id = $this->input->post('id', TRUE);
		// if ($this->form_validation->run() == false) {
		// 	$data = array(
		// 		'sukses' => false,
		// 	);
		// } else {
		// 	$this->db->trans_begin();
		// 	$this->mymodel->update_print($id);
		// 	if ($this->db->trans_status() === FALSE) {
		// 		$this->db->trans_rollback();
		// 		$data = array(
		// 			'sukses' => false,
		// 		);
		// 	} else {
		// 		$this->db->trans_commit();
		// 		$this->logger->write('Print Data ' . $this->title . ' Id : ' . $id);
		// 		$data = array(
		// 			'sukses' => true,
		// 		);
		// 	}
		// }
		// echo json_encode($data);
	}
}