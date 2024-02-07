<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Daftardinas extends CI_Controller
{
	public $i_menu = '1201';

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


		$i_store = $this->input->post('i_store', TRUE);
		if ($i_store == '') {
			$i_store = $this->uri->segment(5);
			if ($i_store == '') {
				$i_store = '0';
			}
		}

		if (strlen($dfrom) != 10) {
			$dfrom = decrypt_url($dfrom);
		}
		if (strlen($dto) != 10) {
			$dto = decrypt_url($dto);
		}
		if (strlen($i_store) > 10) {
			$i_store = decrypt_url($i_store);
		}

		if ($i_store != '0') {
			$e_store_name = $this->db->get_where('tr_store', ['f_store_active' => true, 'i_company' => $this->i_company, 'i_store' => $i_store])->row()->e_store_name;
		} else {
			$e_store_name = '( Semua Divisi Area )';
		}

		$data = array(
			'dfrom'     => date('d-m-Y', strtotime($dfrom)),
			'dto'       => date('d-m-Y', strtotime($dto)),
			'i_store'            => $i_store,
			'e_store_name'       => ucwords(strtolower($e_store_name)),
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
		$data = check_role($this->i_menu, 1);
		if (!$data) {
			redirect(base_url(), 'refresh');
		}

		add_css(
			array(
				'app-assets/css/plugins/forms/validation/form-validation.css',
				'app-assets/css/plugins/forms/switch.css',
				'app-assets/css/global.css',
				'app-assets/vendors/css/forms/selects/select2.min.css',
				'app-assets/vendors/css/extensions/sweetalert2.min.css',
			)
		);

		add_js(
			array(
				'app-assets/vendors/js/forms/validation/jqBootstrapValidation.js',
				'app-assets/vendors/js/forms/select/select2.full.min.js',
				'app-assets/vendors/js/forms/toggle/bootstrap-checkbox.min.js',
				'app-assets/vendors/js/extensions/sweetalert2.all.min.js',
				'assets/js/' . $this->folder . '/add.js?v='.date('YmdHis'),
			)
		);

		$dfrom = decrypt_url($this->uri->segment(3));
		$dto = decrypt_url($this->uri->segment(4));
		$hstore		= decrypt_url($this->uri->segment(5));
		$data = array(
			'dfrom'	 => $dfrom,
			'dto'	 => $dto,
			'hstore'	 => $hstore,
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

	/** Get Store */	
	public function get_store0()
	{
		$filter = [];
		$filter[] = array(
			'id'   => '0',
			'text' => '( Semua Divisi Area )',
		);
		$data = $this->mymodel->get_store0(str_replace("'", "", $this->input->get('q')));
		foreach ($data->result() as $row) {
			$filter[] = array(
				'id'   => $row->i_store,
				'text' => $row->e_name,
			);
		}
		echo json_encode($filter);
	}

	public function get_store7()
	{
		$filter = [];
		$cari   = str_replace("'", "", $this->input->get('q'));
		$data   = $this->mymodel->get_store7($cari);
		foreach ($data->result() as $row) {
			$filter[] = array(
				'id'   => $row->id,
				'text' => $row->e_name,
			);
		}
		echo json_encode($filter);
	}

	public function get_dn_atasan()
	{
		$filter = [];
		$data = $this->mymodel->get_dn_atasan(str_replace("'", "", $this->input->get('q')));
		foreach ($data->result() as $row) {
			$filter[] = array(
				'id'   => $row->i_dn_atasan,
				'text' => $row->e_dn_atasan_name,
			);
		}
		echo json_encode($filter);
	}
	public function get_dn_departement()
	{
		$filter = [];
		$data = $this->mymodel->get_dn_departement(str_replace("'", "", $this->input->get('q')));
		foreach ($data->result() as $row) {
			$filter[] = array(
				'id'   => $row->i_dn_departement,
				'text' => $row->e_dn_departement_name,
			);
		}
		echo json_encode($filter);
	}
	public function get_dn_jabatan()
	{
		$filter = [];
		$data = $this->mymodel->get_dn_jabatan(str_replace("'", "", $this->input->get('q')));
		foreach ($data->result() as $row) {
			$filter[] = array(
				'id'   => $row->i_dn_jabatan,
				'text' => $row->e_dn_jabatan_name,
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

		$this->form_validation->set_rules('i_document', 'i_document', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('d_document', 'd_document', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('i_store7', 'i_store7', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('e_staff_name', 'e_staff_name', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('i_dn_atasan', 'i_dn_atasan', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('i_dn_departement', 'i_dn_departement', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('i_dn_jabatan', 'i_dn_jabatan', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('e_area', 'e_area', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('e_kota', 'e_kota', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('n_lama_dinas', 'n_lama_dinas', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('d_berangkat', 'd_berangkat', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('d_kembali', 'd_kembali', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('v_anggaran_biaya', 'v_anggaran_biaya', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('v_spb_target', 'v_spb_target', 'trim|required|min_length[0]');

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
					$this->logger->write('Simpan ' . $this->title . '  : ' . $this->input->post('i_document') . ' e_staff_name : ' . $this->input->post('e_staff_name') .' i_store : ' . $this->input->post('i_store7'). ' : ' . $this->session->e_company_name);
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
				'app-assets/css/plugins/forms/switch.css',
				'app-assets/css/global.css',
				'app-assets/vendors/css/forms/selects/select2.min.css',
				'app-assets/vendors/css/extensions/sweetalert2.min.css',
			)
		);

		add_js(
			array(
				'app-assets/vendors/js/forms/validation/jqBootstrapValidation.js',
				'app-assets/vendors/js/forms/select/select2.full.min.js',
				'app-assets/vendors/js/forms/toggle/bootstrap-checkbox.min.js',
				'app-assets/vendors/js/extensions/sweetalert2.all.min.js',
				'assets/js/' . $this->folder . '/edit.js?v='.date('YmdHis'),
			)
		);

		$id = decrypt_url($this->uri->segment(3));
		$dfrom = decrypt_url($this->uri->segment(4));
		$dto = decrypt_url($this->uri->segment(5));
		$hstore		= decrypt_url($this->uri->segment(6));
		$data = array(
			'data' => $this->mymodel->getdata($id)->row(),
			'dfrom'	 => $dfrom,
			'dto'	 => $dto,
			'hstore'	 => $hstore,
		);
		$this->logger->write('Membuka Form Edit ' . $this->title);
		$this->template->load('main', $this->folder . '/edit', $data);
	}

	public function edit2()
	{
		/** Cek Hak Akses, Apakah User Bisa Edit */
		$data = check_role($this->i_menu, 3);
		if (!$data) {
			redirect(base_url(), 'refresh');
		}

		add_css(
			array(
				'app-assets/css/plugins/forms/validation/form-validation.css',
				'app-assets/css/plugins/forms/switch.css',
				'app-assets/css/global.css',
				'app-assets/vendors/css/forms/selects/select2.min.css',
				'app-assets/vendors/css/extensions/sweetalert2.min.css',
			)
		);

		add_js(
			array(
				'app-assets/vendors/js/forms/validation/jqBootstrapValidation.js',
				'app-assets/vendors/js/forms/select/select2.full.min.js',
				'app-assets/vendors/js/forms/toggle/bootstrap-checkbox.min.js',
				'app-assets/vendors/js/extensions/sweetalert2.all.min.js',
				'assets/js/' . $this->folder . '/edit2.js?v='.date('YmdHis'),
			)
		);

		$id = decrypt_url($this->uri->segment(3));
		$dfrom = decrypt_url($this->uri->segment(4));
		$dto = decrypt_url($this->uri->segment(5));
		$hstore		= decrypt_url($this->uri->segment(6));
		$data = array(
			'data' => $this->mymodel->getdata($id)->row(),
			'dfrom'	 => $dfrom,
			'dto'	 => $dto,
			'hstore'	 => $hstore,
		);
		$this->logger->write('Membuka Form Edit2 ' . $this->title);
		$this->template->load('main', $this->folder . '/edit2', $data);
	}


	/** Update Data */
	public function update()
	{
		/** Cek Hak Akses, Apakah User Bisa Edit */
		$data = check_role($this->i_menu, 3);
		if (!$data) {
			redirect(base_url(), 'refresh');
		}

		$this->form_validation->set_rules('i_document', 'i_document', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('d_document', 'd_document', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('i_store7', 'i_store7', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('e_staff_name', 'e_staff_name', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('i_dn_atasan', 'i_dn_atasan', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('i_dn_departement', 'i_dn_departement', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('i_dn_jabatan', 'i_dn_jabatan', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('e_area', 'e_area', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('e_kota', 'e_kota', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('n_lama_dinas', 'n_lama_dinas', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('d_berangkat', 'd_berangkat', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('d_kembali', 'd_kembali', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('v_anggaran_biaya', 'v_anggaran_biaya', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('v_spb_target', 'v_spb_target', 'trim|required|min_length[0]');

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
				if ($this->db->trans_status() === FALSE) {
					$this->db->trans_rollback();
					$data = array(
						'sukses' => false,
						'ada'	 => false,
					);
				} else {
					$this->db->trans_commit();
					$this->logger->write('Edit ' . $this->title . '  : ' . $this->input->post('i_document') . ' e_staff_name : ' . $this->input->post('e_staff_name') .' i_store : ' . $this->input->post('i_store7'). ' : ' . $this->session->e_company_name);
					$data = array(
						'sukses' => true,
						'ada'	 => false,
					);
				}
			}
		}
		echo json_encode($data);
	}

	public function update2()
	{
		/** Cek Hak Akses, Apakah User Bisa Edit */
		$data = check_role($this->i_menu, 3);
		if (!$data) {
			redirect(base_url(), 'refresh');
		}

		$this->form_validation->set_rules('i_document', 'i_document', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('v_realisasi_biaya', 'v_realisasi_biaya', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('v_spb_realisasi', 'v_spb_realisasi', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('v_nota_tertagih', 'v_nota_tertagih', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('n_biaya_vs_spb', 'n_biaya_vs_spb', 'trim|required|min_length[0]');

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
				$this->mymodel->update2();
				if ($this->db->trans_status() === FALSE) {
					$this->db->trans_rollback();
					$data = array(
						'sukses' => false,
						'ada'	 => false,
					);
				} else {
					$this->db->trans_commit();
					$this->logger->write('Edit2 ' . $this->title . '  : ' . $this->input->post('i_document') . ' e_staff_name : ' . $this->input->post('e_staff_name') .' i_store : ' . $this->input->post('i_store7'). ' : ' . $this->session->e_company_name);
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

		add_css(
			array(
				'app-assets/css/plugins/forms/validation/form-validation.css',
				'app-assets/css/plugins/forms/switch.css',
				'app-assets/css/global.css',
				'app-assets/vendors/css/forms/selects/select2.min.css',
				'app-assets/vendors/css/extensions/sweetalert2.min.css',
			)
		);

		add_js(
			array(
				'app-assets/vendors/js/forms/validation/jqBootstrapValidation.js',
				'app-assets/vendors/js/forms/select/select2.full.min.js',
				'app-assets/vendors/js/forms/toggle/bootstrap-checkbox.min.js',
				'app-assets/vendors/js/extensions/sweetalert2.all.min.js',
				'assets/js/' . $this->folder . '/view.js?v='.date('YmdHis'),
			)
		);

		$id 	= decrypt_url($this->uri->segment(3));
		$dfrom 	= decrypt_url($this->uri->segment(4));
		$dto 	= decrypt_url($this->uri->segment(5));
		$hstore		= decrypt_url($this->uri->segment(6));
		$data = array(
			'data' => $this->mymodel->getdata($id)->row(),
			'dfrom'	 => $dfrom,
			'dto'	 => $dto,
			'hstore'	 => $hstore,
		);
		$this->logger->write('Membuka Form view ' . $this->title);
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
		$i_dinas_id = $this->db->get_where('tm_dinas', ['i_company' => $this->i_company, 'i_dinas'  => $id])->row()->i_dinas_id;
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
				$this->logger->write('Batal ' . $this->title . ' Id : ' . $id . ' : ' . $i_dinas_id . ' : ' . $this->session->e_company_name);
				$data = array(
					'sukses' => true,
				);
			}
		}
		echo json_encode($data);
	}


}
