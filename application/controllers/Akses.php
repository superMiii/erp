<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Akses extends CI_Controller
{
	public $i_menu = '106';

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
		// $this->logger->write('Membuka Menu ' . $this->title);
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
				'app-assets/vendors/css/forms/listbox/bootstrap-duallistbox.min.css',
				'app-assets/css/plugins/forms/dual-listbox.css',
			)
		);

		add_js(
			array(
				'app-assets/vendors/js/forms/validation/jqBootstrapValidation.js',
				'app-assets/vendors/js/extensions/sweetalert.min.js',
				'app-assets/vendors/js/forms/select/select2.full.min.js',
				'app-assets/vendors/js/forms/listbox/jquery.bootstrap-duallistbox.min.js',
				'assets/js/' . $this->folder . '/add.js',
			)
		);

        $data = array(
            'department' => $this->db->get_where('tr_department',['f_status'=>true, 'i_department <>'=>1]), 
            'level'      => $this->db->get_where('tr_level',['f_status'=>true, 'i_level <>'=>1]), 
            'menu'       => $this->mymodel->get_menu(), 
            'submenu'    => $this->mymodel->get_sub_menu(), 
        );

		// $this->logger->write('Membuka Form Tambah ' . $this->title);
		$this->template->load('main', $this->folder . '/add', $data);
	}

	/** Simpan Data */
	public function save()
	{
		/** Cek Hak Akses, Apakah User Bisa Create */
		$data = check_role($this->i_menu, 1);
		if (!$data) {
			redirect(base_url(), 'refresh');
		}

		$this->form_validation->set_rules('isubmenu[]', 'isubmenu[]', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('idepartment', 'idepartment', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('ilevel', 'ilevel', 'trim|required|min_length[0]');
		if ($this->form_validation->run() == false) {
			$data = array(
				'sukses' => false,
				'ada'	 => false,
			);
		} else {
			/** Simpan Data */
			$this->db->trans_begin();
			$idepartment = $this->input->post('idepartment', TRUE);
			$ilevel 	 = $this->input->post('ilevel', TRUE);
			$imenu 		 = $this->input->post('imenu[]', TRUE);
			$isubmenu 	 = $this->input->post('isubmenu[]', TRUE);
			$this->mymodel->save($idepartment, $ilevel, $imenu, $isubmenu);
			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				$data = array(
					'sukses' => false,
					'ada'	 => false,
				);
			} else {
				$this->db->trans_commit();
				$this->logger->write('Simpan ' . $this->title . ' Level : ' . $ilevel.', Departemen : '.$idepartment. ' : ' . $this->session->e_company_name);
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
		$data = check_role($this->i_menu, 3);
		if (!$data) {
			redirect(base_url(), 'refresh');
		}

		add_css(
			array(
				'app-assets/css/plugins/forms/validation/form-validation.css',
				'app-assets/vendors/css/extensions/sweetalert.css',
				'app-assets/vendors/css/forms/selects/select2.min.css',
				'app-assets/vendors/css/forms/listbox/bootstrap-duallistbox.min.css',
				'app-assets/css/plugins/forms/dual-listbox.css',
			)
		);

		add_js(
			array(
				'app-assets/vendors/js/forms/validation/jqBootstrapValidation.js',
				'app-assets/vendors/js/extensions/sweetalert.min.js',
				'app-assets/vendors/js/forms/select/select2.full.min.js',
				'app-assets/vendors/js/forms/listbox/jquery.bootstrap-duallistbox.min.js',
				'assets/js/' . $this->folder . '/edit.js',
			)
		);

		$i_departement  = decrypt_url($this->uri->segment(3));
		$i_level 		= decrypt_url($this->uri->segment(4));

		$data = array(
			'department' => $this->db->get_where('tr_department',['f_status'=>true, 'i_department <>'=>1]), 
            'level'      => $this->db->get_where('tr_level',['f_status'=>true, 'i_level <>'=>1]), 
            'menu'       	=> $this->mymodel->get_menu_edit($i_departement, $i_level), 
            'submenu'    	=> $this->mymodel->get_sub_menu_edit($i_departement, $i_level), 
			'i_department' 	=> $i_departement,
			'i_level' 		=> $i_level
		);
		$this->logger->write('Membuka Form Edit ' . $this->title);
		$this->template->load('main', $this->folder . '/edit', $data);
	}

	/** Update Data */
	public function update()
	{
		/** Cek Hak Akses, Apakah User Bisa Create */
		$data = check_role($this->i_menu, 3);
		if (!$data) {
			redirect(base_url(), 'refresh');
		}

		$this->form_validation->set_rules('isubmenu[]', 'isubmenu[]', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('idepartment', 'idepartment', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('ilevel', 'ilevel', 'trim|required|min_length[0]');
		if ($this->form_validation->run() == false) {
			$data = array(
				'sukses' => false,
				'ada'	 => false,
			);
		} else {
			/** Simpan Data */
			$this->db->trans_begin();
			$idepartment = $this->input->post('idepartment', TRUE);
			$ilevel 	 = $this->input->post('ilevel', TRUE);
			$imenu 		 = $this->input->post('imenu[]', TRUE);
			$isubmenu 	 = $this->input->post('isubmenu[]', TRUE);
			$this->mymodel->save($idepartment, $ilevel, $imenu, $isubmenu);
			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				$data = array(
					'sukses' => false,
					'ada'	 => false,
				);
			} else {
				$this->db->trans_commit();
				$this->logger->write('Edit ' . $this->title . ' Level : ' . $ilevel.', Departemen : '.$idepartment. ' : ' . $this->session->e_company_name);
				$data = array(
					'sukses' => true,
					'ada'	 => false,
				);
			}
		}
		echo json_encode($data);
	}

	/** Hapus Data */
	public function delete()
	{
		/** Cek Hak Akses, Apakah User Bisa Hapus */
		$data = check_role($this->i_menu, 4);
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
			$this->mymodel->delete($id);
			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				$data = array(
					'sukses' => false,
				);
			} else {
				$this->db->trans_commit();
				$this->logger->write('Batal ' . $this->title . ' Id : ' . $id . ' : ' . $this->session->e_company_name);
				$data = array(
					'sukses' => true,
				);
			}
		}
		echo json_encode($data);
	}
}
