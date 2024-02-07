<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Menu extends CI_Controller
{
	public $i_menu = '103';

	public function __construct()
	{
		parent::__construct();
		cek_session();

		/** Cek Hak Akses, Apakah User Bisa Read */
		$data = check_role($this->i_menu, 2);
		if (!$data) {
			redirect(base_url(), 'refresh');
		}

		/** Deklarasi Nama Folder, Title dan Icon */
		$this->folder 	= $data->e_folder;
		$this->title	= $data->e_menu;
		$this->icon		= $data->icon;

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
			)
		);

		add_js(
			array(
				'app-assets/vendors/js/tables/datatable/datatables.min.js',
				'app-assets/vendors/js/tables/datatable/dataTables.buttons.min.js',
				'app-assets/vendors/js/tables/datatable/buttons.bootstrap4.min.js',
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
		$data = check_role($this->i_menu, 1);
		if (!$data) {
			redirect(base_url(), 'refresh');
		}

		add_css(
			array(
				'app-assets/css/plugins/forms/validation/form-validation.css',
				'app-assets/vendors/css/extensions/sweetalert2.min.css',				
				'app-assets/vendors/css/animate/animate.css',			
				'app-assets/vendors/css/forms/selects/select2.min.css',
			)
		);

		add_js(
			array(
				'app-assets/vendors/js/forms/validation/jqBootstrapValidation.js',
				'app-assets/vendors/js/extensions/sweetalert2.all.min.js',
				'app-assets/vendors/js/forms/select/select2.full.min.js',
				'assets/js/' . $this->folder . '/add.js',
			)
		);

		$this->logger->write('Membuka Form Tambah ' . $this->title);
		$this->template->load('main', $this->folder . '/add');
	}

	/** Get Menu */
	public function get_menu()
	{
		$filter = [];
		$filter[] = array(
			'id'   => 0,
			'text' => "# - Default",
		);
		$data = $this->mymodel->get_menu($this->input->get('q'));
		foreach ($data->result() as $row) {
			$filter[] = array(
				'id'   => $row->i_menu,
				'text' => $row->i_menu . ' - ' . $row->e_menu,
			);
		}
		echo json_encode($filter);
	}

	/** Get Power */
	public function get_power()
	{
		$filter = [];
		$data = $this->mymodel->get_user_power($this->input->get('q'));
		foreach ($data->result() as $row) {
			$filter[] = array(
				'id'   => $row->i_power,
				'text' => $row->i_power . ' - ' . $row->e_power_name,
			);
		}
		echo json_encode($filter);
	}

	/** Simpan Data */
	public function save()
	{
		/** Cek Hak Akses, Apakah User Bisa Create */
		$data = check_role($this->i_menu, 1);
		if (!$data) {
			redirect(base_url(), 'refresh');
		}

		$this->form_validation->set_rules('imenu', 'imenu', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('iparent', 'iparent', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('emenu', 'emenu', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('nurut', 'nurut', 'trim|required|min_length[0]');
		if ($this->form_validation->run() == false) {
			$data = array(
				'sukses' => false,
				'ada'	 => false,
			);
		} else {
			/** Cek Jika Nama Sudah Ada */
			$cek = $this->mymodel->cek($this->input->post('imenu', TRUE));
			/** Jika Sudah Ada Jangan Disimpan */
			if ($cek->num_rows() > 0) {
				$data = array(
					'sukses' => false,
					'ada'	 => true,
				);
			} else {
				/** Jika Belum Ada Simpan Data */
				$this->db->trans_begin();
				$iparent = $this->input->post('iparent', TRUE);
				$imenu = $this->input->post('imenu', TRUE);
				$emenu = ucwords($this->input->post('emenu', TRUE));
				$nurut = $this->input->post('nurut', TRUE);
				$efolder = $this->input->post('efolder', TRUE);
				$icon = $this->input->post('icon', TRUE);
				$ipower = $this->input->post('ipower', TRUE);
				$this->mymodel->save($iparent, $imenu, $emenu, $nurut, $efolder, $icon, $ipower);
				if ($this->db->trans_status() === FALSE) {
					$this->db->trans_rollback();
					$data = array(
						'sukses' => false,
						'ada'	 => false,
					);
				} else {
					$this->db->trans_commit();
					$this->logger->write('Simpan Data ' . $this->title . ' : ' . $imenu);
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
		$data = check_role($this->i_menu, 3);
		if (!$data) {
			redirect(base_url(), 'refresh');
		}

		add_css(
			array(
				'app-assets/css/plugins/forms/validation/form-validation.css',
				'app-assets/vendors/css/extensions/sweetalert2.min.css',				
				'app-assets/vendors/css/animate/animate.css',			
				'app-assets/vendors/css/forms/selects/select2.min.css',
			)
		);

		add_js(
			array(
				'app-assets/vendors/js/forms/validation/jqBootstrapValidation.js',
				'app-assets/vendors/js/extensions/sweetalert2.all.min.js',
				'app-assets/vendors/js/forms/select/select2.full.min.js',
				'assets/js/' . $this->folder . '/edit.js',
			)
		);

		$data = array(
			'data' => $this->mymodel->getdata(decrypt_url($this->uri->segment(3)))->row(),
			'power'=> $this->mymodel->get_power(decrypt_url($this->uri->segment(3))),
		);
		$this->logger->write('Membuka Form Edit ' . $this->title);
		$this->template->load('main', $this->folder . '/edit', $data);
	}

	/** Update Data */
	public function update()
	{
		/** Cek Hak Akses, Apakah User Bisa Edit */
		$data = check_role($this->i_menu, 3);
		if (!$data) {
			redirect(base_url(), 'refresh');
		}

		$this->form_validation->set_rules('imenuold', 'imenuold', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('imenu', 'imenu', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('iparent', 'iparent', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('emenu', 'emenu', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('nurut', 'nurut', 'trim|required|min_length[0]');
		$iparent = $this->input->post('iparent', TRUE);
		$imenu = $this->input->post('imenu', TRUE);
		$imenuold = $this->input->post('imenuold', TRUE);
		$emenu = ucwords($this->input->post('emenu', TRUE));
		$nurut = $this->input->post('nurut', TRUE);
		$efolder = $this->input->post('efolder', TRUE);
		$icon = $this->input->post('icon', TRUE);
		$ipower = $this->input->post('ipower', TRUE);
		if ($this->form_validation->run() == false) {
			$data = array(
				'sukses' => false,
				'ada'	 => false,
			);
		} else {
			/** Cek Jika Nama Sudah Ada */
			$cek = $this->mymodel->cek_edit($imenu, $imenuold);
			/** Jika Sudah Ada Jangan Disimpan */
			if ($cek->num_rows() > 0) {
				$data = array(
					'sukses' => false,
					'ada'	 => true,
				);
			} else {
				/** Jika Belum Ada Update Data */
				$this->db->trans_begin();
				$this->mymodel->update($iparent, $imenu, $emenu, $nurut, $efolder, $icon, $imenuold, $ipower);
				if ($this->db->trans_status() === FALSE) {
					$this->db->trans_rollback();
					$data = array(
						'sukses' => false,
						'ada'	 => false,
					);
				} else {
					$this->db->trans_commit();
					$this->logger->write('Update Data ' . $this->title . ' : ' . $imenu);
					$data = array(
						'sukses' => true,
						'ada'	 => false,
					);
				}
			}
		}
		echo json_encode($data);
	}
}
