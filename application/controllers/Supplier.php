<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Supplier extends CI_Controller
{
	public $id_menu = '20302';

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
		$this->folder 	= $data->e_folder;
		$this->title	= $data->e_menu;
		$this->icon		= $data->icon;
		$this->i_company = $this->session->userdata('i_company');

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
				'app-assets/vendors/css/extensions/sweetalert.css',
			)
		);

		add_js(
			array(
				'app-assets/vendors/js/tables/datatable/datatables.min.js',
				'app-assets/vendors/js/tables/datatable/dataTables.buttons.min.js',
				'app-assets/vendors/js/tables/datatable/buttons.bootstrap4.min.js',
				'app-assets/vendors/js/extensions/sweetalert.min.js',
				'assets/js/' . $this->folder . '/index.js',
			)
		);
		$this->logger->write('Membuka Menu ' . $this->title);
		$this->template->load('main', $this->folder . '/index');
	}

	/** List Data */
	public function serverside()
	{
		echo $this->mymodel->serverside();
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
				'app-assets/vendors/css/extensions/sweetalert.css',
				'app-assets/vendors/css/forms/selects/select2.min.css',
				'app-assets/vendors/css/forms/icheck/icheck.css',
				'app-assets/vendors/css/pickers/pickadate/pickadate.css',
			)
		);

		add_js(
			array(
				'app-assets/vendors/js/forms/validation/jqBootstrapValidation.js',
				'app-assets/vendors/js/extensions/sweetalert.min.js',
				'app-assets/vendors/js/forms/select/select2.full.min.js',
				'app-assets/vendors/js/forms/extended/inputmask/jquery.inputmask.bundle.min.js',
				'app-assets/vendors/js/forms/icheck/icheck.min.js',
				'app-assets/vendors/js/pickers/pickadate/picker.js',
				'app-assets/vendors/js/pickers/pickadate/picker.date.js',
				'assets/js/' . $this->folder . '/add.js',
				'app-assets/js/scripts/forms/checkbox-radio.min.js',
			)
		);
		$this->logger->write('Membuka Form Tambah ' . $this->title);
		$this->template->load('main', $this->folder . '/add');
	}

	/** Get Group Supplier */
	public function get_group_supplier()
	{
		$filter = [];
		// $filter[] = array(
		// 	'id'   => 0,
		// 	'text' => "# - Default",
		// );
		$data = $this->mymodel->get_group_supplier($this->input->get('q'));
		foreach ($data->result() as $row) {
			$filter[] = array(
				'id'   => $row->i_supplier_group,
				'text' => $row->i_supplier_groupid . ' - ' . $row->e_supplier_groupname,
			);
		}
		echo json_encode($filter);
	}

	/** Simpan Data */
	public function save()
	{
		/** Cek Hak Akses, Apakah User Bisa Create */
		$data = check_role($this->id_menu, 1);
		if (!$data) {
			redirect(base_url(), 'refresh');
		}


		$this->form_validation->set_rules('isuppliergroup', 'isuppliergroup', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('kode', 'kode', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('nama', 'nama', 'trim|required|min_length[0]');
		if ($this->form_validation->run() == false) {
			$data = array(
				'sukses' => false,
				'ada'	 => false,
			);
		} else {
			/** Cek Jika Nama Sudah Ada */
			$cek = $this->mymodel->cek($this->input->post('kode', TRUE));
			/** Jika Sudah Ada Jangan Disimpan */
			if ($cek->num_rows() > 0) {
				$data = array(
					'sukses' => false,
					'ada'	 => true,
				);
			} else {
				/** Jika Belum Ada Simpan Data */
				$this->db->trans_begin();
				$kode  = ucwords($this->input->post('kode', TRUE));
				$nama  = ucwords($this->input->post('nama', TRUE));
				$isuppliergroup  = ucwords($this->input->post('isuppliergroup', TRUE));
				$ppn = 'f';
				if ($this->input->post('chk-ppn', TRUE) != null) {
					$ppn = 't';
				}
				$top = $this->input->post('top', TRUE);

				$this->mymodel->save($kode, $nama, $isuppliergroup, $top, $ppn);
				if ($this->db->trans_status() === FALSE) {
					$this->db->trans_rollback();
					$data = array(
						'sukses' => false,
						'ada'	 => false,
					);
				} else {
					$this->db->trans_commit();
					$this->logger->write('Simpan ' . $this->title . ' : ' . $nama . ' : ' . $this->session->e_company_name);
					$data = array(
						'sukses' => true,
						'ada'	 => false,
					);
				}
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
				'app-assets/vendors/css/extensions/sweetalert.css',
				'app-assets/vendors/css/forms/selects/select2.min.css',
				'app-assets/vendors/css/forms/icheck/icheck.css',
				'app-assets/vendors/css/pickers/pickadate/pickadate.css',
			)
		);

		add_js(
			array(
				'app-assets/vendors/js/forms/validation/jqBootstrapValidation.js',
				'app-assets/vendors/js/extensions/sweetalert.min.js',
				'app-assets/vendors/js/forms/select/select2.full.min.js',
				'app-assets/vendors/js/forms/extended/inputmask/jquery.inputmask.bundle.min.js',
				'app-assets/vendors/js/forms/icheck/icheck.min.js',
				'app-assets/vendors/js/pickers/pickadate/picker.js',
				'app-assets/vendors/js/pickers/pickadate/picker.date.js',
				'assets/js/' . $this->folder . '/edit.js?v=5',
				'app-assets/js/scripts/forms/checkbox-radio.min.js',
			)
		);

		$data = array(
			'data' => $this->mymodel->getdata(decrypt_url($this->uri->segment(3)))->row(),
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

		$this->form_validation->set_rules('isuppliergroup', 'isuppliergroup', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('kode', 'kode', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('nama', 'nama', 'trim|required|min_length[0]');

		$id 	= $this->input->post('id', TRUE);
		$isuppliergroup  = ucwords($this->input->post('isuppliergroup', TRUE));
		$kode 	= $this->input->post('kode', TRUE);
		$kodeold 	= $this->input->post('kodeold', TRUE);
		$nama 	= ucwords($this->input->post('nama', TRUE));
		$ppn = 'f';
		if ($this->input->post('chk-ppn', TRUE) != null) {
			$ppn = 't';
		}
		$top = $this->input->post('top', TRUE);

		if ($this->form_validation->run() == false) {
			$data = array(
				'sukses' => false,
				'ada'	 => false,
			);
		} else {
			/** Cek Jika Nama Sudah Ada */
			$cek = $this->mymodel->cek_edit($kode, $kodeold);
			/** Jika Sudah Ada Jangan Disimpan */
			if ($cek->num_rows() > 0) {
				$data = array(
					'sukses' => false,
					'ada'	 => true,
				);
			} else {
				/** Jika Belum Ada Update Data */
				$this->db->trans_begin();
				$this->mymodel->update($id, $isuppliergroup, $kode, $nama, $ppn, $top);
				if ($this->db->trans_status() === FALSE) {
					$this->db->trans_rollback();
					$data = array(
						'sukses' => false,
						'ada'	 => false,
					);
				} else {
					$this->db->trans_commit();
					$this->logger->write('Edit ' . $this->title . ' : ' . $nama . ' : ' . $this->session->e_company_name);
					$data = array(
						'sukses' => true,
						'ada'	 => false,
					);
				}
			}
		}
		echo json_encode($data);
	}

	/** Update Status */
	public function changestatus()
	{
		/** Cek Hak Akses, Apakah User Bisa Edit */
		$data = check_role($this->id_menu, 3);
		if (!$data) {
			redirect(base_url(), 'refresh');
		}

		$this->form_validation->set_rules('id', 'id', 'trim|required|min_length[0]');
		$id = $this->input->post('id', TRUE);
		$e_supplier_name = $this->db->get_where('tr_supplier', ['i_company' => $this->i_company, 'i_supplier'  => $id])->row()->e_supplier_name;
		if ($this->form_validation->run() == false) {
			$data = array(
				'sukses' => false,
			);
		} else {
			/** Jika Belum Ada Update Data */
			$this->db->trans_begin();
			$this->mymodel->changestatus($id);
			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				$data = array(
					'sukses' => false,
				);
			} else {
				$this->db->trans_commit();
				$this->logger->write('Update Status ' . $this->title . ' Id : ' . $id . ' : ' . $e_supplier_name . ' : ' . $this->session->e_company_name);
				$data = array(
					'sukses' => true,
				);
			}
		}
		echo json_encode($data);
	}
}