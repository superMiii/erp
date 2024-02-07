<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dinas5 extends CI_Controller
{
	public $i_menu = '1206';

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

	public function get_store()
	{
		$filter = [];
		$cari   = str_replace("'", "", $this->input->get('q'));
		$data   = $this->mymodel->get_store($cari);
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

	/** Redirect ke Form Approve */
	public function detail_approve()
	{
		/** Cek Hak Akses, Apakah User Bisa Approve */
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
				'assets/js/' . $this->folder . '/approve.js?v=3191749917',
				'app-assets/vendors/js/extensions/sweetalert2.all.min.js',
			)
		);

		$id 		= decrypt_url($this->uri->segment(3));
		$dfrom 		= decrypt_url($this->uri->segment(4));
		$dto 		= decrypt_url($this->uri->segment(5));
		$hstore		= decrypt_url($this->uri->segment(6));
		$data = array(
			'data' 	 => $this->mymodel->getdata($id)->row(),
			'dfrom'	 => $dfrom,
			'dto'	 => $dto,
			'hstore'	 => $hstore,
		);
		$this->logger->write('Membuka Form View ' . $this->title);
		$this->template->load('main', $this->folder . '/approve', $data);
	}

	/** Approve */
	public function approve()
	{
		/** Cek Hak Akses, Apakah User Bisa Approve */
		$data = check_role($this->i_menu, 3);
		if (!$data) {
			redirect(base_url(), 'refresh');
		}

		$this->form_validation->set_rules('id', 'id', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('note', 'note', 'trim|required|min_length[0]');
		$id = $this->input->post('id', TRUE);
		$note = $this->input->post('note', TRUE);
		$i_dinas_id = $this->db->get_where('tm_dinas', ['i_company' => $this->i_company, 'i_dinas'  => $id])->row()->i_dinas_id;
		if ($this->form_validation->run() == false) {
			$data = array(
				'sukses' => false,
			);
		} else {
			$this->db->trans_begin();
			$this->mymodel->approve($id, $note);
			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				$data = array(
					'sukses' => false,
				);
			} else {
				$this->db->trans_commit();
				$this->logger->write('Approve ' . $this->title . ' : ' . $id . ' : ' . $i_dinas_id . ' : ' . $this->session->e_company_name);
				$data = array(
					'sukses' => true,
				);
			}
		}
		echo json_encode($data);
	}

	/** Not Approve */
	public function notapprove()
	{
		/** Cek Hak Akses, Apakah User Bisa Approve */
		$data = check_role($this->i_menu, 3);
		if (!$data) {
			redirect(base_url(), 'refresh');
		}

		$this->form_validation->set_rules('id', 'id', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('note', 'note', 'trim|required|min_length[0]');
		$id = $this->input->post('id', TRUE);
		$note = $this->input->post('note', TRUE);
		$i_dinas_id = $this->db->get_where('tm_dinas', ['i_company' => $this->i_company, 'i_dinas'  => $id])->row()->i_dinas_id;
		if ($this->form_validation->run() == false) {
			$data = array(
				'sukses' => false,
			);
		} else {
			$this->db->trans_begin();
			$this->mymodel->notapprove($id, $note);
			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				$data = array(
					'sukses' => false,
				);
			} else {
				$this->db->trans_commit();
				$this->logger->write('Not Approve ' . $this->title . ' : ' . $id . ' : ' . $i_dinas_id . ' : ' . $this->session->e_company_name);
				$data = array(
					'sukses' => true,
				);
			}
		}
		echo json_encode($data);
	}
}
