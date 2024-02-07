<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pelangganapprove extends CI_Controller
{
	public $id_menu = '513';

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
				'assets/js/' . $this->folder . '/index.js?v=19',
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

	public function get_area()
	{
		$filter = [];
		$data = $this->mymodel->get_area($this->input->get('q'));
		foreach ($data->result() as $row) {
			$filter[] = array(
				'id'   => $row->i_area,
				'text' => $row->i_area_id . ' - ' . $row->e_area_name,
			);
		}
		echo json_encode($filter);
	}

	public function get_city()
	{
		$filter = [];
		$data = $this->mymodel->get_city($this->input->get('q'), $this->input->get('param1'));
		foreach ($data->result() as $row) {
			$filter[] = array(
				'id'   => $row->i_city,
				'text' => $row->i_city_id . ' - ' . $row->e_city_name,
			);
		}
		echo json_encode($filter);
	}

	public function get_cover()
	{
		$filter = [];
		$data = $this->mymodel->get_cover($this->input->get('q'), $this->input->get('param1'), $this->input->get('param2'));
		foreach ($data->result() as $row) {
			$filter[] = array(
				'id'   => $row->i_area_cover,
				'text' => $row->i_area_cover_id . ' - ' . $row->e_area_cover_name,
			);
		}
		echo json_encode($filter);
	}

	public function get_group()
	{
		$filter = [];
		$data = $this->mymodel->get_group($this->input->get('q'));
		foreach ($data->result() as $row) {
			$filter[] = array(
				'id'   => $row->i_customer_group,
				'text' => $row->i_customer_groupid . ' - ' . $row->e_customer_groupname,
			);
		}
		echo json_encode($filter);
	}

	public function get_type()
	{
		$filter = [];
		$data = $this->mymodel->get_type($this->input->get('q'));
		foreach ($data->result() as $row) {
			$filter[] = array(
				'id'   => $row->i_customer_type,
				'text' => $row->i_customer_typeid . ' - ' . $row->e_customer_typename,
			);
		}
		echo json_encode($filter);
	}

	public function get_level()
	{
		$filter = [];
		$data = $this->mymodel->get_level($this->input->get('q'));
		foreach ($data->result() as $row) {
			$filter[] = array(
				'id'   => $row->i_customer_level,
				'text' => $row->i_customer_levelid . ' - ' . $row->e_customer_levelname,
			);
		}
		echo json_encode($filter);
	}

	public function get_status()
	{
		$filter = [];
		$data = $this->mymodel->get_status($this->input->get('q'));
		foreach ($data->result() as $row) {
			$filter[] = array(
				'id'   => $row->i_customer_status,
				'text' => $row->i_customer_statusid . ' - ' . $row->e_customer_statusname,
			);
		}
		echo json_encode($filter);
	}

	public function get_price()
	{
		$filter = [];
		$data = $this->mymodel->get_price($this->input->get('q'));
		foreach ($data->result() as $row) {
			$filter[] = array(
				'id'   => $row->i_price_group,
				'text' => $row->i_price_groupid . ' - ' . $row->e_price_groupname,
			);
		}
		echo json_encode($filter);
	}

	public function get_payment()
	{
		$filter = [];
		$data = $this->mymodel->get_payment($this->input->get('q'));
		foreach ($data->result() as $row) {
			$filter[] = array(
				'id'   => $row->i_customer_payment,
				'text' => $row->i_customer_paymentid . ' - ' . $row->e_customer_paymentname,
			);
		}
		echo json_encode($filter);
	}

	public function get_paygroup()
	{
		$filter = [];
		$data = $this->mymodel->get_paygroup($this->input->get('q'));
		foreach ($data->result() as $row) {
			$filter[] = array(
				'id'   => $row->i_customer_paygroup,
				'text' => $row->i_customer_paygroupid . ' - ' . $row->e_customer_paygroupname,
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
				'app-assets/vendors/css/forms/selects/select2.min.css',
				'app-assets/vendors/css/forms/icheck/icheck.css',
				'app-assets/vendors/css/extensions/sweetalert2.min.css',
				'app-assets/vendors/css/animate/animate.css',
				'app-assets/css/plugins/forms/wizard.min.css',
				'app-assets/css/plugins/forms/switch.css',
			)
		);

		add_js(
			array(
				'app-assets/vendors/js/forms/select/select2.full.min.js',
				'app-assets/vendors/js/forms/icheck/icheck.min.js',
				'app-assets/vendors/js/forms/validation/jquery.validate.min.js',
				'app-assets/vendors/js/extensions/jquery.steps.min.js',
				'app-assets/vendors/js/extensions/sweetalert2.all.min.js',
				'app-assets/vendors/js/forms/toggle/bootstrap-checkbox.min.js',
				'assets/js/' . $this->folder . '/approve.js?v=19',
			)
		);

		$i_customer = decrypt_url($this->uri->segment(3));
		$i_so = decrypt_url($this->uri->segment(4));

		$data = array(
			'data' 	 => $this->mymodel->getdata($i_customer)->row(),
			'dataso' => $this->mymodel->get_data($i_so)->row(),
			'detail' => $this->mymodel->get_data_detail($i_so),
		);
		$this->logger->write('Membuka Form Approve ' . $this->title);
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
		// $this->form_validation->set_rules('note', 'note', 'trim|required|min_length[0]');
		$id = $this->input->post('id', TRUE);
		$note = $this->input->post('note', TRUE);
		$i_customer = explode('|', $id)[0];
		$i_so = explode('|', $id)[1];
		// $i_so = $this->input->post('i_so', TRUE);
		if ($this->form_validation->run() == false) {
			$data = array(
				'sukses' => false,
			);
		} else {
			$this->db->trans_begin();
			$this->mymodel->approve($i_customer, $note);
			$this->mymodel->approve2($i_so, $note);
			// $i_customer_id = $this->db->get_where('tr_customer', ['i_company' => $this->i_company, 'i_customer'  => $id])->row()->i_customer_id;
			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				$data = array(
					'sukses' => false,
				);
			} else {
				$this->db->trans_commit();
				// $this->logger->write('Approve Data ' . $this->title . ' Id : ' . $id . ' Note : ' . $note . ' : ' . $i_customer_id . ' : ' . $this->session->e_company_name);
				$this->logger->write('Approve Data ' . $this->title . ' Id : ' . $id . ' Note : ' . $note . ' : ' . $this->session->e_company_name);
				$data = array(
					'sukses' => true,
				);
			}
		}
		echo json_encode($data);
	}
}
