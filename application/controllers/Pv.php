<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pv extends CI_Controller
{
	public $id_menu = '50802';

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
		$this->e_user_name	= $this->session->e_user_name;

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
				'app-assets/vendors/js/forms/select/select2.full.min.js',
				'assets/js/' . $this->folder . '/index.js?v='.date('YmdHis'),
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
		$i_type = $this->input->post('i_pv_type', TRUE);
		if ($i_type == '') {
			$i_type = $this->uri->segment(5);
			if ($i_type == '') {
				$i_type = "0";
			}
		}

		$i_area = $this->input->post('i_area', TRUE);
		if ($i_area == '') {
			$i_area = $this->uri->segment(6);
			if ($i_area == '') {
				$i_area = '0';
			}
		}

		$i_coa = $this->input->post('i_coa', TRUE);
		if ($i_coa == '') {
			$i_coa = $this->uri->segment(7);
			if ($i_coa == '') {
				$i_coa = '0';
			}
		}



		if (strlen($dfrom) != 10) {
			$dfrom = decrypt_url($dfrom);
		}
		if (strlen($dto) != 10) {
			$dto = decrypt_url($dto);
		}
		if (strlen($i_type) > 10) {
			$i_type = decrypt_url($i_type);
		}
		if (strlen($i_area) > 10) {
			$i_area = decrypt_url($i_area);
		}
		if (strlen($i_coa) > 10) {
			$i_coa = decrypt_url($i_coa);
		}

		if ($i_area != '0') {
			$e_area_name = $this->db->get_where('tr_area', ['f_area_active' => true, 'i_company' => $this->i_company, 'i_area' => $i_area])->row()->e_area_name;
		} else {
			$e_area_name = 'Nasional';
		}

		if ($i_coa != '0') {
			$e_coa_name = $this->db->get_where('tr_coa', ['f_coa_status' => true, 'i_company' => $this->i_company, 'i_coa' => $i_coa])->row()->e_coa_name;
		} else {
			$e_coa_name = 'Semua';
		}


		$cari = '';
		$data = array(
			// 'dfrom'     => date('d-m-Y', strtotime('-1 month', strtotime($dfrom))),
			'dfrom'     		=> date('d-m-Y', strtotime($dfrom)),
			'dto'       		=> date('d-m-Y', strtotime($dto)),
			'i_type'			=> $i_type,
			'type'				=> $this->mymodel->get_type($cari),
			'i_area'            => $i_area,
			'e_area_name'       => ucwords(strtolower($e_area_name)),
			'i_coa'            	=> $i_coa,
			'e_coa_name'       	=> ucwords(strtolower($e_coa_name)),
		);

		$this->logger->write('Membuka Menu ' . $this->title);
		$this->template->load('main', $this->folder . '/index', $data);
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
				'app-assets/vendors/css/extensions/sweetalert2.min.css',
				'app-assets/vendors/css/animate/animate.css',
				'app-assets/vendors/css/forms/selects/select2.min.css',
				'app-assets/css/global.css',
			)
		);

		add_js(
			array(
				'app-assets/vendors/js/forms/validation/jqBootstrapValidation.js',
				'app-assets/vendors/js/extensions/sweetalert2.all.min.js',
				'app-assets/vendors/js/forms/select/select2.full.min.js',
				'assets/js/' . $this->folder . '/add.js?v='.date('YmdHis'),
			)
		);

		$dfrom = decrypt_url($this->uri->segment(3));
		$dto = decrypt_url($this->uri->segment(4));
		$htype 		= decrypt_url($this->uri->segment(5));
		$harea		= decrypt_url($this->uri->segment(6));
		$hcoa 		= decrypt_url($this->uri->segment(7));
		$data = array(
			'dfrom'	 => $dfrom,
			'dto'	 => $dto,
			'htype'		=> $htype,
			'harea'		=> $harea,
			'hcoa'	 	=> $hcoa,
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
		$i_area = $this->input->post('i_area', TRUE);
		$i_pv_type = $this->input->post('i_pv_type', TRUE);
		$i_coa = $this->input->post('i_coa', TRUE);
		$number = '';
		if ($tanggal != '' && $i_area != '' && $i_pv_type != '' && $i_coa != '') {
			$number = $this->mymodel->running_number(date('ym', strtotime($tanggal)), date('Y', strtotime($tanggal)), date('m', strtotime($tanggal)), $i_pv_type, $i_area,$i_coa);
		}
		echo json_encode($number);
	}

	public function suzu()
	{
		$tanggal = $this->input->post('tanggal', TRUE);
		$i_area = $this->input->post('i_area', TRUE);
		$i_pv_type = $this->input->post('i_pv_type', TRUE);
		$i_coa = $this->input->post('i_coa', TRUE);

		if ($tanggal != '' && $i_area != '' && $i_pv_type != '' && $i_coa != '') {
			$query  = array(
				'ori' => $this->mymodel->suzu(date('ym', strtotime($tanggal)), date('Y', strtotime($tanggal)), date('m', strtotime($tanggal)), $i_pv_type, $i_area,$i_coa)->result_array()
			);
		}

		echo json_encode($query);
	}

	/** Cek Number Edit*/
	public function cek_code_edit()
	{
		$data = $this->mymodel->cek_edit();
		if ($data->num_rows() > 0) {
			echo json_encode(1);
		} else {
			echo json_encode(0);
		}
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

	/** Get Data Type */
	public function get_pv_type()
	{
		$filter = [];
		$data = $this->mymodel->get_type(str_replace("'", "", $this->input->get('q')));
		foreach ($data->result() as $row) {
			$filter[] = array(
				'id'   => $row->i_pv_type,
				'text' => $row->i_pv_type_id . ' - ' . $row->e_pv_type_name,
			);
		}
		echo json_encode($filter);
	}

	/** Get Data Coa */
	public function get_coa()
	{
		$filter = [];
		$i_pv_type = $this->input->get('i_pv_type');
		if ($i_pv_type != '') {
			$data = $this->mymodel->get_coa(str_replace("'", "", $this->input->get('q')), $i_pv_type);
			foreach ($data->result() as $row) {
				$filter[] = array(
					'id'   => $row->i_coa,
					'text' => $row->i_coa_id . ' - ' . $row->e_coa_name,
				);
			}
		} else {
			$filter[] = array(
				'id'   => null,
				'text' => $this->lang->line("Pilih") . ' ' . $this->lang->line("Tipe Voucher"),
			);
		}
		echo json_encode($filter);
	}

	/** Get Data Coa */
	public function get_coa2()
	{

		$filter = [];
		$filter[] = array(
			'id'   => '0',
			'text' => 'Semua',
		);
		$cari   = str_replace("'", "", $this->input->get('q'));
		$i_pv_type = $this->input->get('i_pv_type');
		if ($i_pv_type != '') {
			$data = $this->mymodel->get_coa2($cari, $i_pv_type);
			foreach ($data->result() as $row) {
				$filter[] = array(
					'id'   => $row->i_coa,
					'text' => $row->i_coa_id . ' - ' . $row->e_coa_name,
				);
			}
		} else {
			$filter[] = array(
				'id'   => null,
				'text' => $this->lang->line('Pilih') . ' ' . $this->lang->line('Type'),
			);
		}
		echo json_encode($filter);
	}

	/** Get Data Coa */
	public function get_coa_type()
	{
		$filter = [];
		$i_pv_type = $this->input->get('i_pv_type');
		if ($i_pv_type != '') {
			$data = $this->mymodel->get_coa_type(str_replace("'", "", $this->input->get('q')), $i_pv_type);
			foreach ($data->result() as $row) {
				$filter[] = array(
					'id'   => $row->i_coa,
					'text' => $row->i_coa_id . ' - ' . $row->e_coa_name,
				);
			}
		} else {
			$filter[] = array(
				'id'   => null,
				'text' => $this->lang->line("Pilih") . ' ' . $this->lang->line("Tipe Voucher"),
			);
		}
		echo json_encode($filter);
	}



	/** Get Data Reference Type */
	public function get_reference_type()
	{
		$filter = [];
		$data = $this->mymodel->get_reference_type(str_replace("'", "", $this->input->get('q')));
		foreach ($data->result() as $row) {
			$filter[] = array(
				'id'   => $row->i_pv_refference_type,
				'text' => $row->e_pv_refference_type_name,
			);
		}
		echo json_encode($filter);
	}

	/** Get Data Reference */
	public function get_reference()
	{
		$filter = [];
		$cari = str_replace("'", "", $this->input->get('q'));
		$i_pv_refference_type = $this->input->get('i_pv_refference_type');
		if ($i_pv_refference_type != '' || $i_pv_refference_type != null) {
			$data = $this->mymodel->get_reference($cari, $i_pv_refference_type);
			foreach ($data->result() as $row) {
				$filter[] = array(
					'id'   => $row->id,
					'text' => $row->kode . ' #(' . $row->v_jumlah . ') ',
				);
			}
		} else {
			$filter[] = array(
				'id'   => null,
				'text' => $this->lang->line('Pilih') . ' ' . $this->lang->line('TF/GR/TN'),
			);
		}
		echo json_encode($filter);
	}

	/** Get Data Detail Reference */
	public function get_detail_referensi()
	{
		// $v_jumlah = 0;
		header("Content-Type: application/json", true);
		$id 					= $this->input->post('id', true);
		$i_pv_refference_type = $this->input->post('i_pv_refference_type');
		if (($id != '' || $id != null) && ($i_pv_refference_type != '' || $i_pv_refference_type != null)) {
			// $data = $this->mymodel->get_reference_detail($id, $i_rv_refference_type);
			// if ($data->num_rows() > 0) {
			// 	$v_jumlah = $data->row()->v_jumlah;
			// } else {
			// 	$v_jumlah = 0;
			// }
			$query  = array(
				'detail' => $this->mymodel->get_reference_detail($id, $i_pv_refference_type)->result_array()
			);
		}
		// echo json_encode($v_jumlah);
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

		$this->form_validation->set_rules('i_area', 'i_area', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('i_coa', 'i_coa', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('v_pv', 'v_pv', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('i_document', 'i_document', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('d_document', 'd_document', 'trim|required|min_length[0]');

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
					$this->logger->write('Simpan ' . $this->title . ' No Dokumen : ' . $this->input->post('i_document') . ' i_coa : ' . $this->input->post('i_coa') . ' v_pv : ' . $this->input->post('v_pv') . ' : ' . $this->session->e_company_name);
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
				'app-assets/vendors/css/extensions/sweetalert2.min.css',
				'app-assets/vendors/css/animate/animate.css',
				'app-assets/vendors/css/forms/selects/select2.min.css',
				'app-assets/css/global.css',
			)
		);

		add_js(
			array(
				'app-assets/vendors/js/forms/validation/jqBootstrapValidation.js',
				'app-assets/vendors/js/extensions/sweetalert2.all.min.js',
				'app-assets/vendors/js/forms/select/select2.full.min.js',
				'assets/js/' . $this->folder . '/edit.js?v='.date('YmdHis'),
			)
		);

		$id 		= decrypt_url($this->uri->segment(3));
		$dfrom 		= decrypt_url($this->uri->segment(4));
		$dto 		= decrypt_url($this->uri->segment(5));
		$htype 		= decrypt_url($this->uri->segment(6));
		$harea		= decrypt_url($this->uri->segment(7));
		$hcoa 		= decrypt_url($this->uri->segment(8));
		$data 		= array(
			'data' 	 	=> $this->mymodel->get_data($id)->row(),
			'detail' 	=> $this->mymodel->get_data_detail2($id),
			'dfrom'	 	=> $dfrom,
			'dto'	 	=> $dto,
			'htype'		=> $htype,
			'harea'		=> $harea,
			'hcoa'	 	=> $hcoa,
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
		$this->form_validation->set_rules('i_area', 'i_area', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('i_coa', 'i_coa', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('v_pv', 'v_pv', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('i_document', 'i_document', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('i_document_old', 'i_document_old', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('d_document', 'd_document', 'trim|required|min_length[0]');

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
				/** Jika Belum Ada Update Data */
				$this->db->trans_begin();
				$this->mymodel->update();
				$this->mymodel->miru();
				if ($this->db->trans_status() === FALSE) {
					$this->db->trans_rollback();
					$data = array(
						'sukses' => false,
						'ada'	 => false,
					);
				} else {
					$this->db->trans_commit();
					$this->logger->write('Edit ' . $this->title . ' No Dokumen : ' . $this->input->post('i_document') . ' i_coa : ' . $this->input->post('i_coa') . ' v_pv : ' . $this->input->post('v_pv') . ' : ' . $this->session->e_company_name);
					$data = array(
						'sukses' => true,
						'ada'	 => false,
					);
				}
			}
		}
		echo json_encode($data);
	}

	/** Cancel */
	public function delete2()
	{
		/** Cek Hak Akses, Apakah User Bisa Hapus */
		$data = check_role($this->id_menu, 4);
		if (!$data) {
			redirect(base_url(), 'refresh');
		}

		$this->form_validation->set_rules('id', 'id', 'trim|required|min_length[0]');
		$id = $this->input->post('id', TRUE);
		$alasan = $this->input->post('alasan', TRUE);

		$i_pv_id = $this->db->get_where('tm_pv', ['i_company' => $this->i_company, 'i_pv'  => $id])->row()->i_pv_id;
		if ($this->form_validation->run() == false) {
			$data = array(
				'sukses' => false,
			);
		} else {
			$this->db->trans_begin();
			$this->mymodel->cancel($id,$alasan,$i_pv_id);
			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				$data = array(
					'sukses' => false,
				);
			} else {
				$this->db->trans_commit();
				$this->logger->write('Batal ' . $this->title . ' Id : ' . $id . ' : ' . $i_pv_id . ' : ' . $this->session->e_company_name);
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

		$id 	= decrypt_url($this->uri->segment(3));
		$dfrom 	= decrypt_url($this->uri->segment(4));
		$dto 	= decrypt_url($this->uri->segment(5));
		$type 	= decrypt_url($this->uri->segment(6));
		$area 	= decrypt_url($this->uri->segment(7));
		$coa 	= decrypt_url($this->uri->segment(8));
		$data   = array(
			'data' 	 => $this->mymodel->get_data($id)->row(),
			'detail' => $this->mymodel->get_data_detail($id),
			'dfrom'	 => $dfrom,
			'dto'	 => $dto,
			'type'	 => $type,
			'area'	 => $area,
			'coa'	 => $coa,
		);
		$this->logger->write('Membuka Form View ' . $this->title);
		$this->template->load('main', $this->folder . '/view', $data);
	}

	/** Redirect ke Form Cetak */
	public function print()
	{
		/** Cek Hak Akses, Apakah User Bisa Print */
		$data = check_role($this->id_menu, 5);
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
				'assets/js/' . $this->folder . '/print.js?v='.date('YmdHis'),
			)
		);

		$id = decrypt_url($this->uri->segment(3));

		$data = array(
			'data' 	 => $this->mymodel->get_data($id)->row(),
			'detail' => $this->mymodel->get_data_detail($id),
			'company' 	=> $this->db->get_where('tr_company', ['f_company_active' => true, 'i_company' => $this->i_company])->row(),
		);
		$this->logger->write('Membuka Form Cetak ' . $this->title);
		$this->load->view($this->folder . '/print', $data);
		/* $this->template->load('main', $this->folder . '/print', $data); */
	}

	/** Get Saldo */
	public function get_saldo()
	{
		$i_coa = $this->input->post('i_coa');
		$d_document = $this->input->post('tanggal');
		if ($d_document != '') {
			$d_document = $this->input->post('tanggal');
		} else {
			$d_document = date('Y-m-d');
		}
		$i_periode = date('Ym', strtotime($d_document));
		$data = $this->mymodel->get_saldo($i_coa, $i_periode);
		if ($data->num_rows() > 0) {
			echo json_encode($data->row()->v_saldo_akhir);
		} else {
			echo json_encode(0);
		}
	}

	/** Get tex */
	public function get_tex()
	{
		$i_pv_type = $this->input->post('i_pv_type');

		$data = $this->mymodel->get_tex($i_pv_type);
		if ($data->num_rows() > 0) {
			echo json_encode($data->row()->i_pv_type_id);
		} else {
			echo json_encode(0);
		}
	}



	/** Update Print */
	public function update_print()
	{
		$this->form_validation->set_rules('id', 'id', 'trim|required|min_length[0]');
		$id = $this->input->post('id', TRUE);
		$i_pv_id = $this->db->get_where('tm_pv', ['i_company' => $this->i_company, 'i_pv'  => $id])->row()->i_pv_id;
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
				$this->logger->write('Print ' . $this->title . ' Id : ' . $id . ' : ' . $i_pv_id . ' : ' . $this->session->e_company_name);
				$data = array(
					'sukses' => true,
				);
			}
		}
		echo json_encode($data);
	}
}
