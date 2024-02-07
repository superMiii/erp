<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Fakturkomersial extends CI_Controller
{
	public $id_menu = '802';

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
		$this->folder 	= $data->e_folder;
		$this->title	= $data->e_menu;
		$this->icon		= $data->icon;
		$this->i_company = $this->session->userdata('i_company');

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
				'app-assets/vendors/css/extensions/sweetalert.css',
				'app-assets/vendors/css/pickers/pickadate/pickadate.css',
			)
		);

		add_js(
			array(
				'app-assets/vendors/js/tables/datatable/datatables.min.js',
				'app-assets/vendors/js/tables/datatable/dataTables.buttons.min.js',
				'app-assets/vendors/js/tables/datatable/buttons.bootstrap4.min.js',
				'app-assets/vendors/js/extensions/sweetalert.min.js',
				'app-assets/vendors/js/pickers/pickadate/picker.js',
				'app-assets/vendors/js/pickers/pickadate/picker.date.js',
				'assets/js/' . $this->folder . '/index.js',
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
		$data = array(
			'dfrom'     => date('d-m-Y', strtotime($dfrom)),
			'dto'       => date('d-m-Y', strtotime($dto)),
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
				'app-assets/vendors/css/pickers/pickadate/pickadate.css',
				'app-assets/css/global.css',
			)
		);

		add_js(
			array(
				'app-assets/vendors/js/forms/validation/jqBootstrapValidation.js',
				'app-assets/vendors/js/extensions/sweetalert2.all.min.js',
				'app-assets/vendors/js/forms/select/select2.full.min.js',
				'app-assets/vendors/js/pickers/pickadate/picker.js',
				'app-assets/vendors/js/pickers/pickadate/picker.date.js',
				'assets/js/' . $this->folder . '/add.js',
			)
		);

		$data = array(
			'data' => $this->mymodel->getnomorpajak()->row(),
		);
		$this->logger->write('Membuka Form Tambah ' . $this->title);
		$this->template->load('main', $this->folder . '/add', $data);
	}

	public function get_nota_mulai() {
		$filter = [];
		$cari 	= str_replace("'", "", $this->input->get('q'));
		// if ($cari != '') {
		$data = $this->mymodel->get_nota_mulai($cari);
		foreach ($data->result() as $row) {
			$filter[] = array(
				'id'   => $row->i_nota_id,
				'text' => $row->i_nota_id . ' ( ' . $row->d_nota. ' )',
			);
		}
		// } else {
		// 	$filter[] = array(
		// 		'id'   => null,
		// 		'text' => $this->lang->line('Ketik Untuk Cari Data'),
		// 	);
		// }

		echo json_encode($filter);
	}

	public function get_nota_akhir() {
		$filter = [];
		$cari 	= str_replace("'", "", $this->input->get('q'));
		if ($this->input->get('i_nota_mulai') != '') {
			$data = $this->mymodel->get_nota_akhir($cari, $this->input->get('i_nota_mulai'));
			foreach ($data->result() as $row) {
				$filter[] = array(
					'id'   => $row->i_nota_id,
					'text' => $row->i_nota_id . ' ( ' . $row->d_nota. ' )',
				);
			}
		} else {
			$filter[] = array(
				'id'   => null,
				'text' => $this->lang->line('Nomor Awal Harus Di Isi'),
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


		$this->form_validation->set_rules('nomor_segmen', 'nomor_segmen', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('n_start', 'n_start', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('n_end', 'n_end', 'trim|required|min_length[0]');
		if ($this->form_validation->run() == false) {
			$data = array(
				'sukses' => false,
				'ada'	 => false,
			);
		} else {
			/** Jika Belum Ada Simpan Data */
			$this->db->trans_begin();
			$nomor_segmen   = ucwords($this->input->post('nomor_segmen', TRUE));
			$n_start   = ucwords($this->input->post('n_start', TRUE));
			$n_end   = ucwords($this->input->post('n_end', TRUE));
			$i_nota_mulai  = ucwords($this->input->post('i_nota_mulai', TRUE));
			$i_nota_akhir  = ucwords($this->input->post('i_nota_akhir', TRUE));

			$i_nota_akhir_asli = "";

			$data = $this->mymodel->get_looping_nota($i_nota_mulai, $i_nota_akhir);
			foreach ($data->result() as $row) {
				if ($n_end-$n_start >= 0) {
					$n_start_len = $this->mymodel->checklen($n_start);
					$this->mymodel->update($row->i_nota_id, $nomor_segmen, $n_start_len);
					$i_nota_akhir_asli = $row->i_nota_id;
					$n_start++;
				} else {
					break;
				}		
			}
			$n_start_len = $this->mymodel->checklen($n_start);
			$this->mymodel->updatemaster($n_start_len);
			// 
			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				$data = array(
					'sukses' => false,
					'ada'	 => false,
				);
			} else {
				$this->db->trans_commit();
				$this->logger->write('Simpan ' . $this->title . ' : ' . $i_nota_mulai . ' s/d '.$i_nota_akhir_asli . ' : ' . $this->session->e_company_name);
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
			)
		);

		add_js(
			array(
				'app-assets/vendors/js/forms/validation/jqBootstrapValidation.js',
				'app-assets/vendors/js/extensions/sweetalert.min.js',
				'assets/js/' . $this->folder . '/edit.js',
			)
		);

		$data = array(
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

		$this->form_validation->set_rules('kode', 'kode', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('n_start', 'n_start', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('n_end', 'n_end', 'trim|required|min_length[0]');



		$id 	= $this->input->post('id', TRUE);
		$kode 	= $this->input->post('kode', TRUE);
		$kodeold 	= $this->input->post('kodeold', TRUE);
		$n_start 	= str_replace(",", "", $this->input->post('n_start', TRUE));
		$n_end 	= str_replace(",", "", $this->input->post('n_end', TRUE));


		if ($this->form_validation->run() == false) {
			$data = array(
				'sukses' => false,
				'ada'	 => false,
			);
		} else {
			/** Cek Jika Nama Sudah Ada */
			$cek = $this->mymodel->cek_edit($kode, $kodeold);
			/** Jika Sudah Ada Jangan Disimpan */
			if ($cek->num_rows() > 0) {
				$data = array(
					'sukses' => false,
					'ada'	 => true,
				);
			} else {
				/** Jika Belum Ada Update Data */
				$this->db->trans_begin();
				$this->mymodel->update($id, $kode, $n_start, $n_end);
				if ($this->db->trans_status() === FALSE) {
					$this->db->trans_rollback();
					$data = array(
						'sukses' => false,
						'ada'	 => false,
					);
				} else {
					$this->db->trans_commit();
					$this->logger->write('Edit ' . $this->title . ' : ' . $kode . ' : ' . $this->session->e_company_name);
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
		$i_nota_id = $this->db->get_where('tm_nota', ['i_company' => $this->i_company, 'i_nota'  => $id])->row()->i_nota_id;
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
				$this->logger->write('Update Status ' . $this->title . ' Id : ' . $id . ' : ' . $i_nota_id . ' : ' . $this->session->e_company_name);
				$data = array(
					'sukses' => true,
				);
			}
		}
		echo json_encode($data);
	}
}
