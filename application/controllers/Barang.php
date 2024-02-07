<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Barang extends CI_Controller
{
	public $id_menu = '20109';

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
			'group' 		=> $this->db->get_where('tr_product_group',['f_product_groupactive'=>true, 'i_company'=> $this->i_company]), 
			'category' 		=> $this->db->get_where('tr_product_category',['f_product_categoryactive'=>true, 'i_company'=> $this->i_company]), 
			'subcategory' 	=> $this->db->get_where('tr_product_subcategory',['f_product_subcategoryactive'=>true, 'i_company'=> $this->i_company]), 
			'status' 		=> $this->db->get_where('tr_product_status',['f_product_statusactive'=>true, 'i_company'=> $this->i_company]), 
			'series' 		=> $this->db->get_where('tr_product_series',['f_product_seriesactive'=>true, 'i_company'=> $this->i_company]), 
			'color' 		=> $this->db->get_where('tr_product_color',['f_product_coloractive'=>true, 'i_company'=> $this->i_company]), 
			'motif' 		=> $this->db->get_where('tr_product_motif',['f_product_motifactive'=>true, 'i_company'=> $this->i_company]), 
		);
		$this->logger->write('Membuka Form Tambah ' . $this->title);
		$this->template->load('main', $this->folder . '/add', $data);
	}

	/** Get Sub Categoru */
	public function get_subcategory()
	{
		$filter = [];
		$cari 	= $this->input->get('q');
		$i_product_category = $this->input->get('i_product_category');
		if ($i_product_category != '') {
			$data = $this->mymodel->get_subcategory($i_product_category, $cari);
			foreach ($data->result() as $row) {
				$filter[] = array(
					'id'   => $row->i_product_subcategory,
					'text' => $row->i_product_subcategoryid . ' - ' . $row->e_product_subcategoryname,
				);
			}
		} else {
			$filter[] = array(
				'id'   => null,
				'text' => 'Select Category',
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

		$this->form_validation->set_rules('i_product_id', 'i_product_id', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('e_product_name', 'e_product_name', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('i_product_category', 'i_product_category', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('i_product_subcategory', 'i_product_subcategory', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('i_product_group', 'i_product_group', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('i_product_status', 'i_product_status', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('i_product_series', 'i_product_series', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('i_product_color', 'i_product_color', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('i_product_motif', 'i_product_motif', 'trim|required|min_length[0]');
		$i_product_id = strtoupper($this->input->post('i_product_id', TRUE));
		if ($this->form_validation->run() == false) {
			$data = array(
				'sukses' => false,
				'ada'	 => false,
			);
		} else {
			/** Cek Jika Kode Sudah Ada */
			$cek = $this->mymodel->cek($i_product_id);
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
					$this->logger->write('Simpan ' . $this->title . ' : ' . $i_product_id . ' : ' . $this->session->e_company_name);
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
			'group' 		=> $this->db->get_where('tr_product_group',['f_product_groupactive'=>true, 'i_company'=> $this->i_company]), 
			'category' 		=> $this->db->get_where('tr_product_category',['f_product_categoryactive'=>true, 'i_company'=> $this->i_company]), 
			'subcategory' 	=> $this->db->get_where('tr_product_subcategory',['f_product_subcategoryactive'=>true, 'i_company'=> $this->i_company]), 
			'status' 		=> $this->db->get_where('tr_product_status',['f_product_statusactive'=>true, 'i_company'=> $this->i_company]), 
			'series' 		=> $this->db->get_where('tr_product_series',['f_product_seriesactive'=>true, 'i_company'=> $this->i_company]), 
			'color' 		=> $this->db->get_where('tr_product_color',['f_product_coloractive'=>true, 'i_company'=> $this->i_company]), 
			'motif' 		=> $this->db->get_where('tr_product_motif',['f_product_motifactive'=>true, 'i_company'=> $this->i_company]), 
			'data' 			=> $this->mymodel->getdata(decrypt_url($this->uri->segment(3)))->row(),
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

		$this->form_validation->set_rules('i_product', 'i_product', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('i_product_id_old', 'i_product_id_old', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('i_product_id', 'i_product_id', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('e_product_name', 'e_product_name', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('i_product_category', 'i_product_category', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('i_product_subcategory', 'i_product_subcategory', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('i_product_group', 'i_product_group', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('i_product_status', 'i_product_status', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('i_product_series', 'i_product_series', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('i_product_color', 'i_product_color', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('i_product_motif', 'i_product_motif', 'trim|required|min_length[0]');
		$i_product_id_old = strtoupper($this->input->post('i_product_id_old', TRUE));
		$i_product_id = strtoupper($this->input->post('i_product_id', TRUE));
		if ($this->form_validation->run() == false) {
			$data = array(
				'sukses' => false,
				'ada'	 => false,
			);
		} else {
			/** Cek Jika Nama Sudah Ada */
			$cek = $this->mymodel->cek_edit($i_product_id_old, $i_product_id);
			/** Jika Sudah Ada Jangan Disimpan */
			if ($cek->num_rows() > 0) {
				$data = array(
					'sukses' => false,
					'ada'	 => true,
				);
			} else {
				/** Jika Belum Ada Update Data */
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
					$this->logger->write('Update ' . $this->title . ' : ' . $i_product_id . ' : ' . $this->session->e_company_name);
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
		$i_product_id = $this->db->get_where('tr_product', ['i_company' => $this->i_company, 'i_product'  => $id])->row()->i_product_id;
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
				$this->logger->write('Update Status ' . $this->title . ' Id : ' . $id . ' : ' . $i_product_id. ' : ' . $this->session->e_company_name);
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
		/** Cek Hak Akses, Apakah User Bisa Edit */
		$data = check_role($this->id_menu, 2);
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
			'group' 		=> $this->db->get_where('tr_product_group',['f_product_groupactive'=>true, 'i_company'=> $this->i_company]), 
			'category' 		=> $this->db->get_where('tr_product_category',['f_product_categoryactive'=>true, 'i_company'=> $this->i_company]), 
			'subcategory' 	=> $this->db->get_where('tr_product_subcategory',['f_product_subcategoryactive'=>true, 'i_company'=> $this->i_company]), 
			'status' 		=> $this->db->get_where('tr_product_status',['f_product_statusactive'=>true, 'i_company'=> $this->i_company]), 
			'series' 		=> $this->db->get_where('tr_product_series',['f_product_seriesactive'=>true, 'i_company'=> $this->i_company]), 
			'color' 		=> $this->db->get_where('tr_product_color',['f_product_coloractive'=>true, 'i_company'=> $this->i_company]), 
			'motif' 		=> $this->db->get_where('tr_product_motif',['f_product_motifactive'=>true, 'i_company'=> $this->i_company]), 
			'data' 			=> $this->mymodel->getdata(decrypt_url($this->uri->segment(3)))->row(),
		);
		$this->logger->write('Membuka Form Detail ' . $this->title);
		$this->template->load('main', $this->folder . '/view', $data);
	}
		
}
