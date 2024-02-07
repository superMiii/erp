<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Gr extends CI_Controller
{
	public $id_menu = '406';

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
				'assets/js/' . $this->folder . '/index.js?v=3191744',
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


	/** Default Controllers */
	public function add()
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
				'assets/js/' . $this->folder . '/list_po.js?v=3191744',
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

		$this->logger->write('Membuka Menu List SO ' . $this->title);
		$this->template->load('main', $this->folder . '/list_po', $data);
	}

	/** List Data */
	public function serverside2()
	{
		echo $this->mymodel->serverside2();
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

	/** Redirect ke Form Edit */
	public function add_reffrence()
	{
		/** Cek Hak Akses, Apakah User Bisa ADD */
		$data = check_role($this->id_menu, 1);
		if (!$data) {
			redirect(base_url(), 'refresh');
		}

		add_css(
			array(
				'app-assets/css/plugins/forms/validation/form-validation.css',
				'app-assets/vendors/css/extensions/sweetalert.css',
				'app-assets/vendors/css/forms/selects/select2.min.css',
				'app-assets/css/plugins/forms/switch.css',
				'app-assets/vendors/css/pickers/pickadate/pickadate.css',
				'app-assets/css/global.css',
				'app-assets/vendors/css/extensions/sweetalert2.min.css',
			)
		);

		add_js(
			array(
				'app-assets/vendors/js/forms/validation/jqBootstrapValidation.js',
				'app-assets/vendors/js/extensions/sweetalert.min.js',
				'app-assets/vendors/js/forms/select/select2.full.min.js',
				'app-assets/vendors/js/forms/extended/inputmask/jquery.inputmask.bundle.min.js',
				'app-assets/vendors/js/pickers/pickadate/picker.js',
				'app-assets/vendors/js/pickers/pickadate/picker.date.js',
				'app-assets/vendors/js/forms/toggle/bootstrap-checkbox.min.js',
				'assets/js/' . $this->folder . '/add.js?v=3191744',
				'app-assets/vendors/js/extensions/sweetalert2.all.min.js',
			)
		);

		$id = decrypt_url($this->uri->segment(3));
		$dfrom = decrypt_url($this->uri->segment(4));
		$dto = decrypt_url($this->uri->segment(5));
		$hsup = decrypt_url($this->uri->segment(6));

		$data = array(
			'data' 	 => $this->mymodel->get_data($id)->row(),
			'dfrom'	 => $dfrom,
			'dto'	 => $dto,
			'hsup'	 => $hsup,
		);
		$this->logger->write('Membuka Form Penerimaan Pesanan Pembelian ' . $this->title);
		$this->template->load('main', $this->folder . '/add', $data);
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

	public function get_data_refference()
	{
		$i_po    = $this->input->post('i_po');

		$data['data'] = $this->mymodel->get_data_refference($i_po)->result();

		echo json_encode($data);
	}

	public function save()
	{
		/** Cek Hak Akses, Apakah User Bisa Simpan */
		$data = check_role($this->id_menu, 1);
		if (!$data) {
			redirect(base_url(), 'refresh');
		}

		$this->form_validation->set_rules('i_document', 'i_document', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('d_document', 'd_document', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('jml', 'jml', 'trim|required|min_length[0]');

		if ($this->form_validation->run() == false) {
			$data = array(
				'sukses' => false,
				'ada'	 => false,
			);
		} else {
			/** Realisasi */
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
				$this->logger->write('Simpan ' . $this->title . ' No Document : ' . $this->input->post('i_document') . ' : ' . $this->session->e_company_name);
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
				'app-assets/vendors/css/extensions/sweetalert.css',
				'app-assets/vendors/css/forms/selects/select2.min.css',
				'app-assets/css/plugins/forms/switch.css',
				'app-assets/vendors/css/pickers/pickadate/pickadate.css',
				'app-assets/css/global.css',
				'app-assets/vendors/css/extensions/sweetalert2.min.css',
			)
		);

		add_js(
			array(
				'app-assets/vendors/js/forms/validation/jqBootstrapValidation.js',
				'app-assets/vendors/js/extensions/sweetalert.min.js',
				'app-assets/vendors/js/forms/select/select2.full.min.js',
				'app-assets/vendors/js/forms/extended/inputmask/jquery.inputmask.bundle.min.js',
				'app-assets/vendors/js/pickers/pickadate/picker.js',
				'app-assets/vendors/js/pickers/pickadate/picker.date.js',
				'app-assets/vendors/js/forms/toggle/bootstrap-checkbox.min.js',
				'assets/js/' . $this->folder . '/edit.js?v=3191744',
				'app-assets/vendors/js/extensions/sweetalert2.all.min.js',
			)
		);

		$id = decrypt_url($this->uri->segment(3));
		$i_po = decrypt_url($this->uri->segment(4));
		$dfrom = decrypt_url($this->uri->segment(5));
		$dto = decrypt_url($this->uri->segment(6));
		$hsup = decrypt_url($this->uri->segment(7));

		$data = array(
			'data' 	 => $this->mymodel->get_data_edit($id, $i_po)->row(),
			'dfrom'	 => $dfrom,
			'dto'	 => $dto,
			'hsup'	 => $hsup,
		);
		$this->logger->write('Membuka Form Edit ' . $this->title);
		$this->template->load('main', $this->folder . '/edit', $data);
	}

	// ambil data item untuk dokumen edit
	public function get_data_detail()
	{

		$id    = $this->input->post('id');
		$data['data'] = $this->mymodel->get_data_detail($id)->result();

		echo json_encode($data);
	}


	/** Cek Number */
	public function cek_edit()
	{
		$data = $this->mymodel->cek_edit();
		if ($data->num_rows() > 0) {
			echo json_encode(1);
		} else {
			echo json_encode(0);
		}
	}

	// update data
	public function update()
	{
		/** Cek Hak Akses, Apakah User Bisa Update */
		$data = check_role($this->id_menu, 3);
		if (!$data) {
			redirect(base_url(), 'refresh');
		}

		$this->form_validation->set_rules('i_document', 'i_document', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('d_document', 'd_document', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('jml', 'jml', 'trim|required|min_length[0]');

		if ($this->form_validation->run() == false) {
			$data = array(
				'sukses' => false,
				'ada'	 => false,
			);
		} else {
			/** Cek Jika Kode Sudah Ada */
			$cek = $this->mymodel->cek_edit();
			/** Jika Sudah Ada Jangan Disimpan */
			if ($cek->num_rows() > 0) {
				$data = array(
					'sukses' => false,
					'ada'	 => true,
				);
			} else {
				/** Update */
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
					$this->logger->write('Mengubah Data ' . $this->title . ' No Document : ' . $this->input->post('i_document') . ' : ' . $this->session->e_company_name);
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
				'app-assets/css/plugins/forms/switch.css',
				'app-assets/vendors/css/pickers/pickadate/pickadate.css',
				'app-assets/css/global.css',
				'app-assets/vendors/css/extensions/sweetalert2.min.css',
			)
		);

		add_js(
			array(
				'app-assets/vendors/js/forms/validation/jqBootstrapValidation.js',
				'app-assets/vendors/js/extensions/sweetalert.min.js',
				'app-assets/vendors/js/forms/select/select2.full.min.js',
				'app-assets/vendors/js/forms/extended/inputmask/jquery.inputmask.bundle.min.js',
				'app-assets/vendors/js/pickers/pickadate/picker.js',
				'app-assets/vendors/js/pickers/pickadate/picker.date.js',
				'app-assets/vendors/js/forms/toggle/bootstrap-checkbox.min.js',
				'assets/js/' . $this->folder . '/view.js?v=3191744',
				'app-assets/vendors/js/extensions/sweetalert2.all.min.js',
			)
		);

		$id = decrypt_url($this->uri->segment(3));
		$i_po = decrypt_url($this->uri->segment(4));
		$dfrom = decrypt_url($this->uri->segment(5));
		$dto = decrypt_url($this->uri->segment(6));
		$hsup = decrypt_url($this->uri->segment(7));

		$data = array(
			'data' 	 => $this->mymodel->get_data_edit($id, $i_po)->row(),
			'dfrom'	 => $dfrom,
			'dto'	 => $dto,
			'hsup'	 => $hsup,
		);
		$this->logger->write('Membuka Form View ' . $this->title);
		$this->template->load('main', $this->folder . '/view', $data);
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
		$i_gr_id = $this->db->get_where('tm_gr', ['i_company' => $this->i_company, 'i_gr'  => $id])->row()->i_gr_id;
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
				$this->logger->write('Batal ' . $this->title . ' Id : ' . $id . ' : ' . $i_gr_id . ' : ' . $this->session->e_company_name);
				$data = array(
					'sukses' => true,
				);
			}
		}
		echo json_encode($data);
	}
}
