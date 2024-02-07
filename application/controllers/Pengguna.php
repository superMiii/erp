<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pengguna extends CI_Controller
{
	public $i_menu = '107';

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

		$this->i_company = $this->session->i_company;

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
				'assets/js/' . $this->folder . '/index.js?v='.date('YmdHis'),
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
				'app-assets/vendors/css/extensions/sweetalert.css',
				'app-assets/vendors/css/forms/selects/select2.min.css',
				'app-assets/css/plugins/forms/switch.css',
			)
		);

		add_js(
			array(
				'app-assets/vendors/js/forms/validation/jqBootstrapValidation.js',
				'app-assets/vendors/js/extensions/sweetalert.min.js',
				'app-assets/vendors/js/forms/select/select2.full.min.js',
				'app-assets/vendors/js/forms/toggle/bootstrap-checkbox.min.js',
				'assets/js/' . $this->folder . '/add.js?v='.date('YmdHis'),
				'app-assets/pswd.js',
			)
		);

		$this->logger->write('Membuka Form Tambah ' . $this->title);
		$this->template->load('main', $this->folder . '/add');
	}

	/** Get Company */
	public function get_company()
	{
		$filter = [];
		$cari = str_replace("'", "", $this->input->get('q'));
		$data = $this->mymodel->get_company($cari);
		foreach ($data->result() as $row) {
			$filter[] = array(
				'id'   => $row->i_company,
				'text' => $row->e_company_name,
			);
		}
		echo json_encode($filter);
	}

	/** Get Area */
	public function get_area()
	{
		$filter = [];
		$cari = str_replace("'", "", $this->input->get('q'));
		$i_company = $this->input->get('i_company');
		$data = $this->mymodel->get_area($cari, $i_company);
		foreach ($data->result() as $row) {
			$filter[] = array(
				'id'   => $row->i_area,
				'text' => $row->e_area_name,
			);
		}
		echo json_encode($filter);
	}

	/** Get RV Type */
	public function get_rv_type()
	{
		$filter = [];
		$cari = str_replace("'", "", $this->input->get('q'));
		$i_company = $this->input->get('i_company');
		$data = $this->mymodel->get_rv_type($cari, $i_company);
		foreach ($data->result() as $row) {
			$filter[] = array(
				'id'   => $row->i_rv_type,
				'text' => $row->e_rv_type_name,
			);
		}
		echo json_encode($filter);
	}

	/** Get PV Type */
	public function get_pv_type()
	{
		$filter = [];
		$cari = str_replace("'", "", $this->input->get('q'));
		$i_company = $this->input->get('i_company');
		$data = $this->mymodel->get_pv_type($cari, $i_company);
		foreach ($data->result() as $row) {
			$filter[] = array(
				'id'   => $row->i_pv_type,
				'text' => $row->e_pv_type_name,
			);
		}
		echo json_encode($filter);
	}
	
	public function get_store()
	{
		$filter = [];
		$cari = str_replace("'", "", $this->input->get('q'));
		$i_company = $this->input->get('i_company');
		$data = $this->mymodel->get_store($cari, $i_company);
		foreach ($data->result() as $row) {
			$filter[] = array(
				'id'   => $row->i_store,
				'text' => $row->e_store_name,
			);
		}
		echo json_encode($filter);
	}

	/** Get Department */
	public function get_department()
	{
		$filter = [];
		$cari = str_replace("'", "", $this->input->get('q'));
		$data = $this->mymodel->get_department($cari);
		foreach ($data->result() as $row) {
			$filter[] = array(
				'id'   => $row->i_department,
				'text' => $row->e_department_name,
			);
		}
		echo json_encode($filter);
	}

	/** Get Level */
	public function get_level()
	{
		$filter = [];
		$cari = str_replace("'", "", $this->input->get('q'));
		$data = $this->mymodel->get_level($cari);
		foreach ($data->result() as $row) {
			$filter[] = array(
				'id'   => $row->i_level,
				'text' => $row->e_level_name,
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

		$this->form_validation->set_rules('i_user_id', 'i_user_id', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('e_user_name', 'e_user_name', 'trim|required|min_length[0]');
		/* $this->form_validation->set_rules('i_company[]', 'i_company[]', 'trim|required|min_length[0]'); */
		$this->form_validation->set_rules('password', 'password', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('password2', 'password2', 'trim|required|min_length[0]');
		$i_user_id = $this->input->post('i_user_id', TRUE);
		$e_user_name = $this->input->post('e_user_name', TRUE);
		/* $i_company = $this->input->post('i_company[]', TRUE); */
		$f_pusat = ($this->input->post('f_pusat') == 'on') ? 'f' : 't';
		$password  = encrypt_password(trim($this->input->post('password', TRUE)));



		$foto		= $_FILES['foto'];
		if ($foto = '') {
			$foto = $this->input->post('fotoku', TRUE);
		} else {
			$config['upload_path']          = './assets/images/avatar/';
			$config['allowed_types']        = 'gif|jpg|jpeg|png';
			$config['overwrite']            = true;
			// $config['max_size']             = 1024; // 1MB
			// $config['max_width']            = 1080;
			// $config['max_height']           = 1080;

			$this->load->library('upload', $config);
			if (!$this->upload->do_upload('foto')) {
				$foto = $this->input->post('fotoku', TRUE);
			} else {
				$foto = $this->upload->data('file_name');
				chmod("./assets/images/avatar/$foto", 0777);
			}
		}

		// $file_name = str_replace('.', '', $data['current_user']->id);
		// $config['upload_path']          = FCPATH . '/assets/avatar/';
		// $config['allowed_types']        = 'gif|jpg|jpeg|png';
		// $config['file_name']            = $file_name;
		// $config['overwrite']            = true;
		// $config['max_size']             = 1024; // 1MB
		// $config['max_width']            = 1080;
		// $config['max_height']           = 1080;



		if ($this->form_validation->run() == false) {
			$data = array(
				'sukses' => false,
				'ada'	 => false,
			);
		} else {
			/** Cek Jika Nama Sudah Ada */
			$cek = $this->mymodel->cek($i_user_id);
			/** Jika Sudah Ada Jangan Disimpan */
			if ($cek->num_rows() > 0) {
				$data = array(
					'sukses' => false,
					'ada'	 => true,
				);
			} else {
				/** Jika Belum Ada Simpan Data */
				$this->db->trans_begin();
				$this->mymodel->save($i_user_id, $e_user_name, /* $i_company,  */ $password, $f_pusat, $foto);
				if ($this->db->trans_status() === FALSE) {
					$this->db->trans_rollback();
					$data = array(
						'sukses' => false,
						'ada'	 => false,
					);
				} else {
					$this->db->trans_commit();
					$this->logger->write('Simpan Data ' . $this->title . ' : ' . $i_user_id . ' : ' . $this->session->e_company_name);
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
				'app-assets/vendors/css/extensions/sweetalert.css',
				'app-assets/vendors/css/forms/selects/select2.min.css',
				'app-assets/css/plugins/forms/switch.css',
			)
		);

		add_js(
			array(
				'app-assets/vendors/js/forms/validation/jqBootstrapValidation.js',
				'app-assets/vendors/js/extensions/sweetalert.min.js',
				'app-assets/vendors/js/forms/select/select2.full.min.js',
				'app-assets/vendors/js/forms/toggle/bootstrap-checkbox.min.js',
				'assets/js/' . $this->folder . '/edit.js?v='.date('YmdHis'),
				'app-assets/pswd.js',
			)
		);

		$id = decrypt_url($this->uri->segment(3));

		$data = array(
			'data' 		=> $this->mymodel->getdata($id)->row(),
			'company'	=> $this->mymodel->getcompany($id),
			'detail' 	=> $this->mymodel->getdetail($id),
			'detaill' 	=> $this->mymodel->getdetaill($id),
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

		$this->form_validation->set_rules('i_user', 'i_user', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('i_user_id_old', 'i_user_id_old', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('i_user_id', 'i_user_id', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('e_user_name', 'e_user_name', 'trim|required|min_length[0]');
		/* $this->form_validation->set_rules('i_company[]', 'i_company[]', 'trim|required|min_length[0]'); */
		$this->form_validation->set_rules('password', 'password', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('password2', 'password2', 'trim|required|min_length[0]');
		$i_user = $this->input->post('i_user', TRUE);
		$i_user_id_old = $this->input->post('i_user_id_old', TRUE);
		$i_user_id = $this->input->post('i_user_id', TRUE);
		$e_user_name = $this->input->post('e_user_name', TRUE);
		/* $i_company = $this->input->post('i_company[]', TRUE); */
		$f_pusat = ($this->input->post('f_pusat') == 'on') ? 'f' : 't';
		$password  = encrypt_password(trim($this->input->post('password', TRUE)));



		$fotoold = $this->input->post('fotoold', TRUE);



		// chmod("./assets/images/avatar/", 0770);
		// exec("chmod -R 0777 " . base_url('assets/images/avatar/tes'));

		$fotoup		= $_FILES['fotonew'];
		if ($fotoup = '') {
			$fotoup = $fotoold;
		} else {
			$config['upload_path']          = './assets/images/avatar/';
			$config['allowed_types']        = 'gif|jpg|jpeg|png';
			$config['overwrite']            = true;
			// $config['max_size']             = 1024; // 1MB
			// $config['max_width']            = 1080;
			// $config['max_height']           = 1080;

			$this->load->library('upload', $config);

			if (!$this->upload->do_upload('fotonew')) {
				$fotoup = $fotoold;
			} else {
				$fotoup = $this->upload->data('file_name');
				chmod("./assets/images/avatar/$fotoup", 0777);
				if ($fotoold != "default.jpg") {
					unlink("./assets/images/avatar/$fotoold");
				}
			}
		}



		if ($this->form_validation->run() == false) {
			$data = array(
				'sukses' => false,
				'ada'	 => false,
			);
		} else {
			/** Cek Jika Nama Sudah Ada */
			$cek = $this->mymodel->cek_edit($i_user_id, $i_user_id_old);
			/** Jika Sudah Ada Jangan Disimpan */
			if ($cek->num_rows() > 0) {
				$data = array(
					'sukses' => false,
					'ada'	 => true,
				);
			} else {
				/** Jika Belum Ada Simpan Data */
				$this->db->trans_begin();
				$this->mymodel->update($i_user, $i_user_id, $e_user_name, $password, $f_pusat, $fotoup);
				if ($this->db->trans_status() === FALSE) {
					$this->db->trans_rollback();
					$data = array(
						'sukses' => false,
						'ada'	 => false,
					);
				} else {
					$this->db->trans_commit();
					$this->logger->write('Edit ' . $this->title . ' : ' . $i_user_id . ' : ' . $this->session->e_company_name);
					$data = array(
						'sukses' => true,
						'ada'	 => false,
					);
				}
			}
		}
		echo json_encode($data);
	}

	/** Redirect ke Form View */
	public function view()
	{
		/** Cek Hak Akses, Apakah User Bisa View */
		$data = check_role($this->i_menu, 2);
		if (!$data) {
			redirect(base_url(), 'refresh');
		}

		$id = decrypt_url($this->uri->segment(3));

		$data = array(
			'data' 		=> $this->mymodel->getdata($id)->row(),
			'company'	=> $this->mymodel->getcompany($id),
			'detail' 	=> $this->mymodel->getdetail($id),
			'detaill' 	=> $this->mymodel->getdetaill($id),
		);
		$this->logger->write('Membuka Form View ' . $this->title);
		$this->template->load('main', $this->folder . '/view', $data);
	}

	/** Update Status */
	public function changestatus()
	{
		/** Cek Hak Akses, Apakah User Bisa Edit */
		$data = check_role($this->i_menu, 3);
		if (!$data) {
			redirect(base_url(), 'refresh');
		}

		$this->form_validation->set_rules('id', 'id', 'trim|required|min_length[0]');
		$id = $this->input->post('id', TRUE);
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
				$this->logger->write('Update Status ' . $this->title . ' Id : ' . $id . ' : ' . $this->session->e_company_name);
				$data = array(
					'sukses' => true,
				);
			}
		}
		echo json_encode($data);
	}



	// public function upload_avatar()
	// {
	// 	$this->load->model('profile_model');
	// 	$data['current_user'] = $this->auth_model->current_user();

	// 	if ($this->input->method() === 'post') {
	// 		// the user id contain dot, so we must remove it
	// 		$file_name = str_replace('.', '', $data['current_user']->id);
	// 		$config['upload_path']          = FCPATH . '/assets/avatar/';
	// 		$config['allowed_types']        = 'gif|jpg|jpeg|png';
	// 		$config['file_name']            = $file_name;
	// 		$config['overwrite']            = true;
	// 		$config['max_size']             = 1024; // 1MB
	// 		$config['max_width']            = 1080;
	// 		$config['max_height']           = 1080;

	// 		$this->load->library('upload', $config);

	// 		if (!$this->upload->do_upload('avatar')) {
	// 			$data['error'] = $this->upload->display_errors();
	// 		} else {
	// 			$uploaded_data = $this->upload->data();

	// 			$new_data = [
	// 				'id' => $data['current_user']->id,
	// 				'avatar' => $uploaded_data['file_name'],
	// 			];

	// 			if ($this->profile_model->update($new_data)) {
	// 				$this->session->set_flashdata('message', 'Avatar updated!');
	// 				redirect(site_url('admin/setting'));
	// 			}
	// 		}
	// 	}

	// 	$this->load->view('admin/setting_upload_avatar.php', $data);
	// }
}
