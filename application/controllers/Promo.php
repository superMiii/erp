<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Promo extends CI_Controller
{
	public $id_menu = '30701';

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
				'assets/js/' . $this->folder . '/index.js?v=1910',
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

		$i_promo_type = $this->input->post('i_promo_type', TRUE);
		if ($i_promo_type == '') {
			$i_promo_type = $this->uri->segment(5);
			if ($i_promo_type == '') {
				$i_promo_type = '0';
			}
		}

		if ($i_promo_type != '0') {
			$e_promo_type_name = $this->db->get_where('tr_promo_type', ['f_promo_type_active' => true, 'i_company' => $this->i_company, 'i_promo_type' => $i_promo_type])->row()->e_promo_type_name;
		} else {
			$e_promo_type_name = '( Semua Promo )';
		}

		if (strlen($dfrom) != 10) {
			$dfrom = decrypt_url($dfrom);
		}
		if (strlen($dto) != 10) {
			$dto = decrypt_url($dto);
		}

		$data = array(
			'dfrom'     => date('d-m-Y', strtotime($dfrom)),
			'dto'       => date('d-m-Y', strtotime($dto)),
			'i_promo_type'        => $i_promo_type,
			'e_promo_type_name'   => ucwords(strtolower($e_promo_type_name)),
		);

		$this->logger->write('Membuka Menu ' . $this->title);
		$this->template->load('main', $this->folder . '/index', $data);
	}

	/** List Data */
	public function serverside()
	{
		echo $this->mymodel->serverside();
	}

	/** Get Number */
	public function number()
	{
		$tanggal = $this->input->post('tanggal', TRUE);
		$i_area = $this->input->post('i_area', TRUE);
		if ($tanggal != '') {
			$number = $this->mymodel->running_number(date('ym', strtotime($tanggal)), date('Y', strtotime($tanggal)), $i_area);
		} else {
			$number = $this->mymodel->running_number(date('ym'), date('Y'), $i_area);
		}
		echo json_encode($number);
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
				'app-assets/vendors/css/forms/icheck/icheck.css',
				'app-assets/css/global.css',
			)
		);

		add_js(
			array(
				'app-assets/vendors/js/forms/validation/jqBootstrapValidation.js',
				'app-assets/vendors/js/extensions/sweetalert2.all.min.js',
				'app-assets/vendors/js/forms/select/select2.full.min.js',
				'app-assets/vendors/js/forms/icheck/icheck.min.js',
				'assets/js/' . $this->folder . '/add.js?v=19',
			)
		);

		$dfrom = decrypt_url($this->uri->segment(3));
		$dto = decrypt_url($this->uri->segment(4));
		$data = array(
			'dfrom'	 => $dfrom,
			'dto'	 => $dto,
		);

		$this->logger->write('Membuka Form Tambah ' . $this->title);
		$this->template->load('main', $this->folder . '/add', $data);
	}

	/** Get Data Area */
	public function get_p()
	{
		$filter = [];
		$filter[] = array(
			'id'   => '0',
			'text' => '( Semua Promo )',
		);
		$data = $this->mymodel->get_p(str_replace("'", "", $this->input->get('q')));
		foreach ($data->result() as $row) {
			$filter[] = array(
				'id'   => $row->i_promo_type,
				'text' => $row->e_promo_type_name,
			);
		}
		echo json_encode($filter);
	}

	/** Get Data Type */
	public function get_type()
	{
		$filter = [];
		$data = $this->mymodel->get_type(str_replace("'", "", $this->input->get('q')));
		foreach ($data->result() as $row) {
			$filter[] = array(
				'id'   => $row->i_promo_type,
				'text' => $row->e_promo_type_name,
			);
		}
		echo json_encode($filter);
	}

	/** Get Data Group */
	public function get_group()
	{
		$filter = [];
		$data = $this->mymodel->get_group(str_replace("'", "", $this->input->get('q')));
		foreach ($data->result() as $row) {
			$filter[] = array(
				'id'   => $row->i_price_group,
				'text' => $row->e_price_groupname,
			);
		}
		echo json_encode($filter);
	}

	/** Get Data Product */
	public function get_product()
	{
		$filter = [];
		$cari 	= str_replace("'", "", $this->input->get('q'));
		$i_price_group = $this->input->get('i_price_group');
		if ($i_price_group != '') {
			if ($cari != '') {
				$data = $this->mymodel->get_product($cari, $i_price_group);
				foreach ($data->result() as $row) {
					$filter[] = array(
						'id'   => $row->i_product,
						'text' => $row->i_product_id . ' - ' . $row->e_product_name,
					);
				}
			} else {
				$filter[] = array(
					'id'   => null,
					'text' => $this->lang->line('Ketik Untuk Cari Data'),
				);
			}
		} else {
			$filter[] = array(
				'id'   => null,
				'text' => $this->lang->line('Pilih') . ' ' . $this->lang->line('Kelompok Barang'),
			);
		}
		echo json_encode($filter);
	}

	/** Get Detail product */
	public function get_detail_product()
	{
		header("Content-Type: application/json", true);
		$i_product = $this->input->post('i_product', TRUE);
		$i_price_group = $this->input->post('i_price_group', TRUE);
		$query  = array(
			'detail' => $this->mymodel->get_detail_product($i_product, $i_price_group)->result_array()
		);
		echo json_encode($query);
	}

	/** Get Data Area */
	public function get_area()
	{
		$filter = [];
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
		$cari 	= str_replace("'", "", $this->input->get('q'));
		if ($cari != '') {
			$data = $this->mymodel->get_customer($cari);
			foreach ($data->result() as $row) {
				$filter[] = array(
					'id'   => $row->i_customer,
					'text' => $row->i_customer_id . ' - ' . $row->e_customer_name,
				);
			}
		} else {
			$filter[] = array(
				'id'   => null,
				'text' => $this->lang->line('Ketik Untuk Cari Data'),
			);
		}
		echo json_encode($filter);
	}

	/** Get Detail Customer */
	public function get_detail_customer()
	{
		header("Content-Type: application/json", true);
		$i_customer = $this->input->post('i_customer', TRUE);
		$query  = array(
			'detail' => $this->mymodel->get_detail_customer($i_customer)->result_array()
		);
		echo json_encode($query);
	}

	/** Get Validasi */
	public function get_valid()
	{
		header("Content-Type: application/json", true);
		$i_promo_type = $this->input->post('i_promo_type', TRUE);
		$query  = array(
			'valid' => $this->mymodel->get_valid($i_promo_type)->result()
		);
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

		$this->form_validation->set_rules('i_document', 'i_document', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('d_document', 'd_document', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('i_promo_type', 'i_promo_type', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('e_promo_name', 'e_promo_name', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('d_promo_start', 'd_promo_start', 'trim|required|min_length[0]');

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
				$this->logger->write('Simpan ' . $this->title . ' Tgl Dokumen : ' . $this->input->post('d_document') . ' No Document : ' . $this->input->post('i_document')  . $this->session->e_company_name);
				$data = array(
					'sukses' => true,
					'ada'	 => false,
				);
			}
		}
		echo json_encode($data);
	}

	/** Redirect ke Form Edit */
	public function edit()
	{
		/** Cek Hak Akses, Apakah User Bisa Edit */
		$data = check_role($this->id_menu, 3);
		if (!$data) {
			redirect(base_url(), 'refresh');
		}

		add_css(
			array(
				'app-assets/css/plugins/forms/validation/form-validation.css',
				'app-assets/vendors/css/extensions/sweetalert2.min.css',
				'app-assets/vendors/css/animate/animate.css',
				'app-assets/vendors/css/forms/selects/select2.min.css',
				'app-assets/vendors/css/forms/icheck/icheck.css',
				'app-assets/css/global.css',
			)
		);

		add_js(
			array(
				'app-assets/vendors/js/forms/validation/jqBootstrapValidation.js',
				'app-assets/vendors/js/extensions/sweetalert2.all.min.js',
				'app-assets/vendors/js/forms/select/select2.full.min.js',
				'app-assets/vendors/js/forms/icheck/icheck.min.js',
				'assets/js/' . $this->folder . '/edit.js?v=19',
			)
		);

		$id = decrypt_url($this->uri->segment(3));
		$dfrom = decrypt_url($this->uri->segment(4));
		$dto = decrypt_url($this->uri->segment(5));
		$data = array(
			'data' 	 	=> $this->mymodel->get_data($id)->row(),
			'detail' 	=> $this->mymodel->get_data_detail($id),
			'customer' 	=> $this->mymodel->get_data_customer($id),
			'area' 		=> $this->mymodel->get_data_area($id),
			'dfrom'	 => $dfrom,
			'dto'	 => $dto,
		);
		$this->logger->write('Membuka Form Edit ' . $this->title);
		$this->template->load('main', $this->folder . '/edit', $data);
	}

	/** Update Data */
	public function update()
	{
		/** Cek Hak Akses, Apakah User Bisa Edit */
		$data = check_role($this->id_menu, 3);
		if (!$data) {
			redirect(base_url(), 'refresh');
		}

		$this->form_validation->set_rules('id', 'id', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('i_document', 'i_document', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('d_document', 'd_document', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('i_promo_type', 'i_promo_type', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('e_promo_name', 'e_promo_name', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('d_promo_start', 'd_promo_start', 'trim|required|min_length[0]');

		if ($this->form_validation->run() == false) {
			$data = array(
				'sukses' => false,
				'ada'	 => false,
			);
		} else {
			/** Update Data */
			$this->db->trans_begin();
			$this->mymodel->update();
			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				$data = array(
					'sukses' => false,
					'ada'	 => false,
				);
			} else {
				$this->db->trans_commit();
				$this->logger->write('Edit ' . $this->title . ' Tgl Dokumen : ' . $this->input->post('d_document') . ' No Document : ' . $this->input->post('i_document') . $id . $this->session->e_company_name);
				$data = array(
					'sukses' => true,
					'ada'	 => false,
				);
			}
		}
		echo json_encode($data);
	}

	/** Cancel */
	public function delete()
	{
		/** Cek Hak Akses, Apakah User Bisa Hapus */
		$data = check_role($this->id_menu, 4);
		if (!$data) {
			redirect(base_url(), 'refresh');
		}

		$this->form_validation->set_rules('id', 'id', 'trim|required|min_length[0]');
		$id = $this->input->post('id', TRUE);
		$i_promo_id = $this->db->get_where('tm_promo', ['i_company' => $this->i_company, 'i_promo'  => $id])->row()->i_promo_id;
		if ($this->form_validation->run() == false) {
			$data = array(
				'sukses' => false,
			);
		} else {
			$this->db->trans_begin();
			$this->mymodel->cancel($id);
			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				$data = array(
					'sukses' => false,
				);
			} else {
				$this->db->trans_commit();
				$this->logger->write('Cancel Data ' . $this->title . ' Id : ' . $id . ' : ' . $i_promo_id . ' : ' . $this->session->e_company_name);
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

		add_css(
			array(
				'app-assets/vendors/css/forms/icheck/icheck.css',
			)
		);

		add_js(
			array(
				'app-assets/vendors/js/forms/icheck/icheck.min.js',
				'assets/js/' . $this->folder . '/view.js',
			)
		);

		$id 	= decrypt_url($this->uri->segment(3));
		$dfrom 	= decrypt_url($this->uri->segment(4));
		$dto 	= decrypt_url($this->uri->segment(5));
		$data = array(
			'data' 	 	=> $this->mymodel->get_data($id)->row(),
			'detail' 	=> $this->mymodel->get_data_detail($id),
			'customer' 	=> $this->mymodel->get_data_customer($id),
			'area' 		=> $this->mymodel->get_data_area($id),
			'dfrom'	 => $dfrom,
			'dto'	 => $dto,
		);
		$this->logger->write('Membuka Form View ' . $this->title);
		$this->template->load('main', $this->folder . '/view', $data);
	}
}
