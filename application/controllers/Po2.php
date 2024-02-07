<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Po2 extends CI_Controller
{
	public $i_menu = '605';

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
		$this->folder 		= $data->e_folder;
		$this->title		= $data->e_menu;
		$this->icon			= $data->icon;
		$this->i_company 	= $this->session->i_company;
		$this->i_user 		= $this->session->i_user;

		/** Load Model, Nama model harus sama dengan nama folder */
		$this->load->model('m' . $this->folder, 'mymodel');
	}


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
				'assets/js/' . $this->folder . '/index.js?v=197',
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
			'dfrom'     		=> date('d-m-Y', strtotime($dfrom)),
			'dto'       		=> date('d-m-Y', strtotime($dto)),
			'i_supplier'        => $i_supplier,
			'e_supplier_name'   => ucwords(strtolower($e_supplier_name)),
		);

		$this->logger->write('Membuka Menu ' . $this->title);
		$this->template->load('main', $this->folder . '/index', $data);
	}

	
	public function serverside()
	{
		echo $this->mymodel->serverside();
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
				// 'app-assets/vendors/css/extensions/sweetalert.css',
				'app-assets/vendors/css/extensions/sweetalert2.min.css',
				'app-assets/vendors/css/forms/selects/select2.min.css',
				'app-assets/vendors/css/pickers/pickadate/pickadate.css',
				'app-assets/css/global.css',
			)
		);

		add_js(
			array(
				'app-assets/vendors/js/forms/validation/jqBootstrapValidation.js',
				// 'app-assets/vendors/js/extensions/sweetalert.min.js',
				'app-assets/vendors/js/extensions/sweetalert2_new.min.js',
				'app-assets/vendors/js/forms/select/select2.full.min.js',
				'app-assets/vendors/js/forms/extended/inputmask/jquery.inputmask.bundle.min.js',
				'app-assets/vendors/js/pickers/pickadate/picker.js',
				'app-assets/vendors/js/pickers/pickadate/picker.date.js',
				'assets/js/' . $this->folder . '/add.js?v=197',
			)
		);

		$dfrom 		= decrypt_url($this->uri->segment(3));
		$dto 		= decrypt_url($this->uri->segment(4));
		$i_supplier	= decrypt_url($this->uri->segment(5));
		$data = array(
			'dfrom'	 		=> $dfrom,
			'dto'	 		=> $dto,
			'i_supplier'	=> $i_supplier,
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

	/** Get Data Product Group */
	public function get_supplier()
	{
		$filter = [];
		$cari 	= str_replace("'", "", $this->input->get('q'));
		if ($cari != '') {
			$data = $this->mymodel->get_supplier($cari);
			foreach ($data->result() as $row) {
				$filter[] = array(
					'id'   => $row->i_supplier,
					'text' => $row->i_supplier_id . ' - ' . $row->e_supplier_name,
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
				'text' => $row->i_area_id . " - " . $row->e_area_name,
			);
		}
		echo json_encode($filter);
	}

	/** Get Product */
	public function get_product()
	{
		$filter = [];
		$cari   = str_replace("'", "", $this->input->get('q'));
		$supp   = $this->input->get('i_supplier');
		$data   = $this->mymodel->get_product($cari, $supp);
		foreach ($data->result() as $row) {
			$filter[] = array(
				'id'   => $row->i_product,
				'text' => $row->i_product_id . ' - ' . $row->e_product_name,
			);
		}
		echo json_encode($filter);
	}

	/** Get Product Detail */
	public function get_product_detail()
	{
		header("Content-Type: application/json", true);
		$i_product 	= $this->input->post('i_product', TRUE);
		$supp   	= $this->input->post('i_supplier', TRUE);
		$query     	= array(
			'detail' => $this->mymodel->get_product_detail($i_product, $supp)->result()
		);
		echo json_encode($query);
	}

	/** Simpan Data */
	public function save()
	{
		/** Cek Hak Akses, Apakah User Bisa Create */
		$data = check_role($this->i_menu, 1);
		if (!$data) {
			redirect(base_url(), 'refresh');
		}

		$this->form_validation->set_rules('i_supplier', 'i_supplier', 'trim|required|min_length[0]');
		// $this->form_validation->set_rules('i_store', 'i_store', 'trim|required|min_length[0]');
		// $this->form_validation->set_rules('i_store_location', 'i_store_location', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('i_document', 'i_document', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('d_document', 'd_document', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('jml', 'jml', 'trim|required|min_length[0]');

		if ($this->form_validation->run() == false) {
			$data = array(
				'sukses' => false,
				'ada'	 => false,
			);
		} else {
			/** Cek Jika Nama Sudah Ada */
			$cek = $this->mymodel->cek_code();
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
					$this->logger->write('Simpan ' . $this->title . ' No Document : ' . $this->input->post('i_document') . ' i_supplier : ' . $this->input->post('i_supplier') . ' : ' . $this->session->e_company_name);
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
				// 'app-assets/vendors/css/extensions/sweetalert.css',
				'app-assets/vendors/css/extensions/sweetalert2.min.css',
				'app-assets/vendors/css/forms/selects/select2.min.css',
				'app-assets/vendors/css/pickers/pickadate/pickadate.css',
				'app-assets/css/global.css',
			)
		);

		add_js(
			array(
				'app-assets/vendors/js/forms/validation/jqBootstrapValidation.js',
				// 'app-assets/vendors/js/extensions/sweetalert.min.js',
				'app-assets/vendors/js/extensions/sweetalert2_new.min.js',
				'app-assets/vendors/js/forms/select/select2.full.min.js',
				'app-assets/vendors/js/forms/extended/inputmask/jquery.inputmask.bundle.min.js',
				'app-assets/vendors/js/pickers/pickadate/picker.js',
				'app-assets/vendors/js/pickers/pickadate/picker.date.js',
				'assets/js/' . $this->folder . '/edit.js?v=197',
			)
		);


		$dfrom 			= decrypt_url($this->uri->segment(4));
		$dto 			= decrypt_url($this->uri->segment(5));
		$i_supplier		= decrypt_url($this->uri->segment(6));

		$data = array(
			'data' 			=> $this->mymodel->getdata(decrypt_url($this->uri->segment(3)))->row(),
			'detail' 		=> $this->mymodel->getdetail(decrypt_url($this->uri->segment(3))),
			'dfrom'	 		=> $dfrom,
			'dto'	 		=> $dto,
			'i_supplier'	=> $i_supplier,
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

		$this->form_validation->set_rules('id', 'id', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('i_supplier', 'i_supplier', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('i_document', 'i_document', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('i_document_old', 'i_document_old', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('d_document', 'd_document', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('jml', 'jml', 'trim|required|min_length[0]');

		if ($this->form_validation->run() == false) {
			$data = array(
				'sukses' => false,
				'ada'	 => false,
			);
		} else {
			/** Cek Jika Kode Sudah Ada */
			// $cek = $this->mymodel->cek_edit();
			/** Jika Sudah Ada Jangan Disimpan */
			// if ($cek->num_rows() > 0) {
			// 	$data = array(
			// 		'sukses' => false,
			// 		'ada'	 => true,
			// 	);
			// } else {
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
					$this->logger->write('Edit ' . $this->title . ' No Document : ' . $this->input->post('i_document') . ' i_supplier : ' . $this->input->post('i_supplier') . ' : ' . $this->session->e_company_name);
					$data = array(
						'sukses' => true,
						'ada'	 => false,
					);
				}
			// }
		}
		echo json_encode($data);
	}

	/** Redirect ke Form View */
	public function view()
	{
		/** Cek Hak Akses, Apakah User Bisa Edit */
		$data = check_role($this->i_menu, 2);
		if (!$data) {
			redirect(base_url(), 'refresh');
		}
		add_css(
			array(
				'app-assets/css/plugins/forms/validation/form-validation.css',
				// 'app-assets/vendors/css/extensions/sweetalert.css',
				'app-assets/vendors/css/extensions/sweetalert2.min.css',
				'app-assets/vendors/css/forms/selects/select2.min.css',
				'app-assets/vendors/css/pickers/pickadate/pickadate.css',
				'app-assets/css/global.css',
			)
		);

		add_js(
			array(
				'app-assets/vendors/js/forms/validation/jqBootstrapValidation.js',
				// 'app-assets/vendors/js/extensions/sweetalert.min.js',
				'app-assets/vendors/js/extensions/sweetalert2_new.min.js',
				'app-assets/vendors/js/forms/select/select2.full.min.js',
				'app-assets/vendors/js/forms/extended/inputmask/jquery.inputmask.bundle.min.js',
				'app-assets/vendors/js/pickers/pickadate/picker.js',
				'app-assets/vendors/js/pickers/pickadate/picker.date.js',
				'assets/js/' . $this->folder . '/view.js?v=197',
			)
		);

		$dfrom 			= decrypt_url($this->uri->segment(4));
		$dto 			= decrypt_url($this->uri->segment(5));
		$i_supplier		= decrypt_url($this->uri->segment(6));

		$data = array(
			'data' => $this->mymodel->getdata(decrypt_url($this->uri->segment(3)))->row(),
			'detail' => $this->mymodel->getdetail(decrypt_url($this->uri->segment(3))),
			'dfrom'	 		=> $dfrom,
			'dto'	 		=> $dto,
			'i_supplier'	=> $i_supplier,
		);
		$this->logger->write('Membuka Form Edit ' . $this->title);
		$this->template->load('main', $this->folder . '/view', $data);
	}

	/** Cancel */
	public function delete()
	{
		/** Cek Hak Akses, Apakah User Bisa Hapus */
		$data = check_role($this->i_menu, 4);
		if (!$data) {
			redirect(base_url(), 'refresh');
		}

		$this->form_validation->set_rules('id', 'id', 'trim|required|min_length[0]');
		$id = $this->input->post('id', TRUE);
		$i_po_id = $this->db->get_where('tm_po', ['i_company' => $this->i_company, 'i_po'  => $id])->row()->i_po_id;
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
				$this->logger->write('Batal ' . $this->title . ' Id : ' . $id . ' : ' . $i_po_id . ' : ' . $this->session->e_company_name);
				$data = array(
					'sukses' => true,
				);
			}
		}
		echo json_encode($data);
	}

	/** Redirect ke Form Cetak */
	public function print()
	{
		/** Cek Hak Akses, Apakah User Bisa Print */
		$data = check_role($this->i_menu, 5);
		if (!$data) {
			redirect(base_url(), 'refresh');
		}

		add_css(
			array(
				/* 'app-assets/css/pages/app-invoice.css', */
				'app-assets/css/bootstrap.min.css',
				'app-assets/css/bootstrap-extended.min.css',
				'app-assets/css/colors.min.css',
				'app-assets/css/components.min.css',
			)
		);

		add_js(
			array(
				'app-assets/vendors/js/vendors.min.js',
				'assets/js/' . $this->folder . '/print.js',
			)
		);

		$id = decrypt_url($this->uri->segment(3));

		$data = array(
			'data' => $this->mymodel->getdata(decrypt_url($this->uri->segment(3)))->row(),
			'detail' => $this->mymodel->getdetail(decrypt_url($this->uri->segment(3))),
			'company' 	=> $this->db->get_where('tr_company', ['f_company_active' => true, 'i_company' => $this->i_company])->row(),
		);
		$this->logger->write('Membuka Form Cetak ' . $this->title);
		$this->load->view($this->folder . '/print', $data);
	}

	/** Update Print */
	public function update_print()
	{
		$this->form_validation->set_rules('id', 'id', 'trim|required|min_length[0]');
		$id = $this->input->post('id', TRUE);
		$i_po_id = $this->db->get_where('tm_po', ['i_company' => $this->i_company, 'i_po'  => $id])->row()->i_po_id;
		if ($this->form_validation->run() == false) {
			$data = array(
				'sukses' => false,
			);
		} else {
			$this->db->trans_begin();
			$this->mymodel->update_print($id);
			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				$data = array(
					'sukses' => false,
				);
			} else {
				$this->db->trans_commit();
				$this->logger->write('Print ' . $this->title . ' Id : ' . $id . ' : ' . $i_po_id . ' : ' . $this->session->e_company_name);
				$data = array(
					'sukses' => true,
				);
			}
		}
		echo json_encode($data);
	}
}
