<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Spbapprovekeuangan extends CI_Controller
{
	public $id_menu = '501';

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
				'app-assets/vendors/css/extensions/sweetalert.css',
				'app-assets/vendors/css/pickers/pickadate/pickadate.css',
				'app-assets/vendors/css/forms/selects/select2.min.css',
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
				'assets/js/' . $this->folder . '/index.js?v=31917499',
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


		$i_area = $this->input->post('i_area', TRUE);
		if ($i_area == '') {
			$i_area = $this->uri->segment(5);
			if ($i_area == '') {
				$i_area = '0';
			}
		}


		if (strlen($dfrom) != 10) {
			$dfrom = decrypt_url($dfrom);
		}
		if (strlen($dto) != 10) {
			$dto = decrypt_url($dto);
		}
		if (strlen($i_area) > 10) {
			$i_area = decrypt_url($i_area);
		}

		if ($i_area != '0') {
			$e_area_name = $this->db->get_where('tr_area', ['f_area_active' => true, 'i_company' => $this->i_company, 'i_area' => $i_area])->row()->e_area_name;
		} else {
			$e_area_name = 'Nasional';
		}


		$data = array(
			// 'dfrom'     => date('d-m-Y', strtotime('-1 month', strtotime($dfrom))),
			'dfrom'    			=> date('d-m-Y', strtotime($dfrom)),
			'dto'       		=> date('d-m-Y', strtotime($dto)),
			'i_area'            => $i_area,
			'e_area_name'       => ucwords(strtolower($e_area_name)),
		);

		$this->logger->write('Membuka Menu ' . $this->title);
		$this->template->load('main', $this->folder . '/index', $data);
	}

	/** List Data */
	public function serverside()
	{
		echo $this->mymodel->serverside();
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

	/** Redirect ke Form Approve */
	public function detail_approve()
	{
		/** Cek Hak Akses, Apakah User Bisa Approve */
		$data = check_role($this->id_menu, 3);
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
		$harea		= decrypt_url($this->uri->segment(6));
		$data = array(
			'data' 	 => $this->mymodel->get_data($id)->row(),
			'detail' => $this->mymodel->get_data_detail($id),
			'dfrom'	 => $dfrom,
			'dto'	 => $dto,
			'harea'	 => $harea,
		);
		$this->logger->write('Membuka Form View ' . $this->title);
		$this->template->load('main', $this->folder . '/approve', $data);
	}

	/** Approve */
	public function approve()
	{
		/** Cek Hak Akses, Apakah User Bisa Approve */
		$data = check_role($this->id_menu, 3);
		if (!$data) {
			redirect(base_url(), 'refresh');
		}

		$this->form_validation->set_rules('id', 'id', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('note', 'note', 'trim|required|min_length[0]');
		$id = $this->input->post('id', TRUE);
		$note = $this->input->post('note', TRUE);
		$i_so_id = $this->db->get_where('tm_so', ['i_company' => $this->i_company, 'i_so'  => $id])->row()->i_so_id;
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
				$this->logger->write('Approve ' . $this->title . ' : ' . $id . ' : ' . $i_so_id . ' : ' . $this->session->e_company_name . ' Note : ' . $note);
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
		$data = check_role($this->id_menu, 3);
		if (!$data) {
			redirect(base_url(), 'refresh');
		}

		$this->form_validation->set_rules('id', 'id', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('note', 'note', 'trim|required|min_length[0]');
		$id = $this->input->post('id', TRUE);
		$note = $this->input->post('note', TRUE);
		$i_so_id = $this->db->get_where('tm_so', ['i_company' => $this->i_company, 'i_so'  => $id])->row()->i_so_id;
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
				$this->logger->write('Not Approve ' . $this->title . ' : ' . $id . ' : ' . $i_so_id . ' : ' . $this->session->e_company_name . ' Note : ' . $note);
				$data = array(
					'sukses' => true,
				);
			}
		}
		echo json_encode($data);
	}
}
