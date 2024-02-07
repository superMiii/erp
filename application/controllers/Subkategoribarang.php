<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Subkategoribarang extends CI_Controller
{
	public $id_menu = '20103';

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
		$this->folder 	 = $data->e_folder;
		$this->title	 = $data->e_menu;
		$this->icon		 = $data->icon;
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
		$data = check_role($this->id_menu, 1);
		if (!$data) {
			redirect(base_url(), 'refresh');
		}

		add_css(
			array(
				'app-assets/css/plugins/forms/validation/form-validation.css',
				'app-assets/vendors/css/extensions/sweetalert.css',
				'app-assets/vendors/css/forms/selects/select2.min.css',
			)
		);

		add_js(
			array(
				'app-assets/vendors/js/forms/validation/jqBootstrapValidation.js',
				'app-assets/vendors/js/extensions/sweetalert.min.js',
				'app-assets/vendors/js/forms/select/select2.full.min.js',
				'assets/js/' . $this->folder . '/add.js',
			)
		);

		$data = array(
			'category' => $this->db->get_where('tr_product_category',['f_product_categoryactive'=>true, 'i_company'=> $this->i_company]), 
		);
		$this->logger->write('Membuka Form Tambah ' . $this->title);
		$this->template->load('main', $this->folder . '/add', $data);
	}

	/** Simpan Data */
	public function save()
	{
		/** Cek Hak Akses, Apakah User Bisa Create */
		$data = check_role($this->id_menu, 1);
		if (!$data) {
			redirect(base_url(), 'refresh');
		}

		$this->form_validation->set_rules('i_product_category', 'i_product_category', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('i_product_subcategoryid', 'i_product_subcategoryid', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('e_product_subcategoryname', 'e_product_subcategoryname', 'trim|required|min_length[0]');
		$i_product_category = strtoupper($this->input->post('i_product_category', TRUE));
		$i_product_subcategoryid = strtoupper($this->input->post('i_product_subcategoryid', TRUE));
		$e_product_subcategoryname = ucwords(strtolower($this->input->post('e_product_subcategoryname', TRUE)));
		if ($this->form_validation->run() == false) {
			$data = array(
				'sukses' => false,
				'ada'	 => false,
			);
		} else {
			/** Cek Jika Kode Sudah Ada */
			$cek = $this->mymodel->cek($i_product_category,$i_product_subcategoryid);
			/** Jika Sudah Ada Jangan Disimpan */
			if ($cek->num_rows() > 0) {
				$data = array(
					'sukses' => false,
					'ada'	 => true,
				);
			} else {
				/** Jika Belum Ada Simpan Data */
				$this->db->trans_begin();
				$this->mymodel->save($i_product_category, $i_product_subcategoryid, $e_product_subcategoryname);
				if ($this->db->trans_status() === FALSE) {
					$this->db->trans_rollback();
					$data = array(
						'sukses' => false,
						'ada'	 => false,
					);
				} else {
					$this->db->trans_commit();
					$this->logger->write('Simpan ' . $this->title . ' : ' . $e_product_subcategoryname . ' : ' . $this->session->e_company_name);
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
			)
		);

		add_js(
			array(
				'app-assets/vendors/js/forms/validation/jqBootstrapValidation.js',
				'app-assets/vendors/js/extensions/sweetalert.min.js',
				'app-assets/vendors/js/forms/select/select2.full.min.js',
				'assets/js/' . $this->folder . '/edit.js',
			)
		);

		$data = array(
			'category' => $this->db->get_where('tr_product_category',['f_product_categoryactive'=>true, 'i_company'=> $this->i_company]), 
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

		$this->form_validation->set_rules('i_company_old', 'i_company_old', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('i_product_category', 'i_product_category', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('i_product_subcategory', 'i_product_subcategory', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('i_product_subcategoryid', 'i_product_subcategoryid', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('i_product_subcategoryid_old', 'i_product_subcategoryid_old', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('e_product_subcategoryname', 'e_product_subcategoryname', 'trim|required|min_length[0]');
		$i_company_old = $this->input->post('i_company_old', TRUE);
		$i_product_category = $this->input->post('i_product_category', TRUE);
		$i_product_subcategory = $this->input->post('i_product_subcategory', TRUE);
		$i_product_subcategoryid = strtoupper($this->input->post('i_product_subcategoryid', TRUE));
		$e_product_subcategoryname = ucwords(strtolower($this->input->post('e_product_subcategoryname', TRUE)));
		$i_product_subcategoryid_old = strtoupper($this->input->post('i_product_subcategoryid_old', TRUE));
		if ($this->form_validation->run() == false) {
			$data = array(
				'sukses' => false,
				'ada'	 => false,
			);
		} else {
			/** Cek Jika Nama Sudah Ada */
			$cek = $this->mymodel->cek_edit($i_company_old, $i_product_category, $i_product_subcategoryid, $i_product_subcategoryid_old);
			/** Jika Sudah Ada Jangan Disimpan */
			if ($cek->num_rows() > 0) {
				$data = array(
					'sukses' => false,
					'ada'	 => true,
				);
			} else {
				/** Jika Belum Ada Update Data */
				$this->db->trans_begin();
				$this->mymodel->update($i_product_category, $i_product_subcategory, $i_product_subcategoryid, $e_product_subcategoryname);
				if ($this->db->trans_status() === FALSE) {
					$this->db->trans_rollback();
					$data = array(
						'sukses' => false,
						'ada'	 => false,
					);
				} else {
					$this->db->trans_commit();
					$this->logger->write('Edit ' . $this->title . ' : ' . $e_product_subcategoryname . ' : ' . $this->session->e_company_name);
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
		$i_product_subcategoryid = $this->db->get_where('tr_product_subcategory', ['i_company' => $this->i_company, 'i_product_subcategory'  => $id])->row()->i_product_subcategoryid;
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
				$this->logger->write('Update Status ' . $this->title . ' Id : ' . $id . ' : ' . $i_product_subcategoryid . ' : ' . $this->session->e_company_name);
				$data = array(
					'sukses' => true,
				);
			}
		}
		echo json_encode($data);
	}
}
