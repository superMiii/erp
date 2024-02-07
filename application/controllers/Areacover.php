<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Areacover extends CI_Controller
{
	public $i_menu = '20203';

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
				'assets/js/' . $this->folder . '/add.js',
			)
		);

		$this->logger->write('Membuka Form Tambah ' . $this->title);
		$this->template->load('main', $this->folder . '/add');
	}

	/** Get Area */
	public function get_area()
	{
		$filter = [];
		$cari = str_replace("'","", $this->input->get('q'));
		$data = $this->mymodel->get_area($cari);
		foreach ($data->result() as $row) {
			$filter[] = array(
				'id'   => $row->i_area,
				'text' => '(' . $row->i_area_id . ') - ' . $row->e_area_name,
			);
		}
		echo json_encode($filter);
	}

	/** Get City */
	public function get_city()
	{
		$filter = [];
		$cari = str_replace("'","", $this->input->get('q'));
		$i_area = $this->input->get('i_area');
		$data = $this->mymodel->get_city($cari, $i_area);
		foreach ($data->result() as $row) {
			$filter[] = array(
				'id'   => $row->i_city,
				'text' => '(' . $row->i_city_id . ') - ' . $row->e_city_name,
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

		$this->form_validation->set_rules('i_area_cover_id', 'i_area_cover_id', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('e_area_cover_name', 'e_area_cover_name', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('jmlx', 'jmlx', 'trim|required|min_length[0]');
		$i_area_cover_id = strtoupper($this->input->post('i_area_cover_id', TRUE));
		if ($this->form_validation->run() == false) {
			$data = array(
				'sukses' => false,
				'ada'	 => false,
			);
		} else {
			/** Cek Jika Nama Sudah Ada */
			$cek = $this->mymodel->cek($i_area_cover_id);
			/** Jika Sudah Ada Jangan Disimpan */
			if ($cek->num_rows() > 0) {
				$data = array(
					'sukses' => false,
					'ada'	 => true,
				);
			} else {
				/** Jika Belum Ada Simpan Data */
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
					$this->logger->write('Simpan Data ' . $this->title . ' : ' . $i_area_cover_id. ' : ' . $this->session->e_company_name);
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
				'assets/js/' . $this->folder . '/edit.js',
			)
		);

		$id = decrypt_url($this->uri->segment(3));

		$data = array(
			'data' 		=> $this->mymodel->getdata($id)->row(),
			'detail' 	=> $this->mymodel->getdetail($id),
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

		$this->form_validation->set_rules('i_area_cover', 'i_area_cover', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('i_area_cover_id', 'i_area_cover_id', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('i_area_cover_id_old', 'i_area_cover_id_old', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('e_area_cover_name', 'e_area_cover_name', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('jmlx', 'jmlx', 'trim|required|min_length[0]');
		$i_area_cover_id = strtoupper($this->input->post('i_area_cover_id', TRUE));
		$i_area_cover_id_old = strtoupper($this->input->post('i_area_cover_id_old', TRUE));
		if ($this->form_validation->run() == false) {
			$data = array(
				'sukses' => false,
				'ada'	 => false,
			);
		} else {
			/** Cek Jika Nama Sudah Ada */
			$cek = $this->mymodel->cek_edit($i_area_cover_id, $i_area_cover_id_old);
			/** Jika Sudah Ada Jangan Disimpan */
			if ($cek->num_rows() > 0) {
				$data = array(
					'sukses' => false,
					'ada'	 => true,
				);
			} else {
				/** Jika Belum Ada Simpan Data */
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
					$this->logger->write('Update Data ' . $this->title . ' : ' . $i_area_cover_id. ' : ' . $this->session->e_company_name);
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
			'detail' 	=> $this->mymodel->getdetail($id),
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
		$i_area_cover_id = $this->db->get_where('tr_area_cover', ['i_company' => $this->i_company, 'i_area_cover'  => $id])->row()->i_area_cover_id;
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
				$this->logger->write('Update Status ' . $this->title . ' Id : ' . $id . ' : ' . $i_area_cover_id . ' : ' . $this->session->e_company_name);
				$data = array(
					'sukses' => true,
				);
			}
		}
		echo json_encode($data);
	}
}
