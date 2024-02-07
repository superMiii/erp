<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pelanggan extends CI_Controller
{
	public $id_menu = '20407';

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
		// $this->i_user = $this->session->userdata('i_user');
		$this->e_user_name 		= $this->session->e_user_name;

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
				'app-assets/vendors/css/forms/icheck/icheck.css',
				'app-assets/vendors/css/pickers/pickadate/pickadate.css',
				'app-assets/css/plugins/forms/switch.css',
			)
		);

		add_js(
			array(
				'app-assets/vendors/js/forms/validation/jqBootstrapValidation.js',
				'app-assets/vendors/js/extensions/sweetalert.min.js',
				'app-assets/vendors/js/forms/select/select2.full.min.js',
				'app-assets/vendors/js/forms/extended/inputmask/jquery.inputmask.bundle.min.js',
				'app-assets/vendors/js/forms/icheck/icheck.min.js',
				'app-assets/vendors/js/pickers/pickadate/picker.js',
				'app-assets/vendors/js/pickers/pickadate/picker.date.js',
				'app-assets/vendors/js/forms/toggle/bootstrap-checkbox.min.js',
				'assets/js/' . $this->folder . '/add.js',
			)
		);
		$this->logger->write('Membuka Form Tambah ' . $this->title);
		$this->template->load('main', $this->folder . '/add');
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

	/** Simpan Data */
	public function save()
	{
		/** Cek Hak Akses, Apakah User Bisa Create */
		$data = check_role($this->id_menu, 1);
		if (!$data) {
			redirect(base_url(), 'refresh');
		}


		$this->form_validation->set_rules('iarea', 'iarea', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('kode', 'kode', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('nama', 'nama', 'trim|required|min_length[0]');


		if ($this->form_validation->run() == false) {
			$data = array(
				'sukses' => false,
				'ada'	 => false,
			);
		} else {
			/** Cek Jika Nama Sudah Ada */
			$cek = $this->mymodel->cek($this->input->post('kode', TRUE));
			/** Jika Sudah Ada Jangan Disimpan */
			if ($cek->num_rows() > 0) {
				$data = array(
					'sukses' => false,
					'ada'	 => true,
				);
			} else {
				/** Jika Belum Ada Simpan Data */
				$this->db->trans_begin();

				$iarea  = ucwords($this->input->post('iarea', TRUE));
				$icity  = ucwords($this->input->post('icity', TRUE));
				$icover  = ucwords($this->input->post('icover', TRUE));

				$kode  = ucwords($this->input->post('kode', TRUE));
				$nama  = ucwords($this->input->post('nama', TRUE));
				$alamat  = ucwords($this->input->post('alamat', TRUE));

				$pemilik  = ucwords($this->input->post('pemilik', TRUE));
				$telepon  = ucwords($this->input->post('telepon', TRUE));
				$diskon  = str_replace('_', '', str_replace('%', '', $this->input->post('diskon', TRUE)));
				$diskon2  = str_replace('_', '', str_replace('%', '', $this->input->post('diskon2', TRUE)));
				$diskon3  = str_replace('_', '', str_replace('%', '', $this->input->post('diskon3', TRUE)));

				if ($diskon == '') $diskon = 0;
				if ($diskon2 == '') $diskon2 = 0;
				if ($diskon3 == '') $diskon3 = 0;
				$npwp_kode  = ucwords($this->input->post('kode_npwp', TRUE));
				$npwp_nama  = ucwords($this->input->post('nama_npwp', TRUE));
				$npwp_alamat  = ucwords($this->input->post('alamat_npwp', TRUE));

				$igroup  = ucwords($this->input->post('igroup', TRUE));
				$itype  = ucwords($this->input->post('itype', TRUE));
				$ilevel  = ucwords($this->input->post('ilevel', TRUE));
				$istatus  = ucwords($this->input->post('istatus', TRUE));


				$iprice  = ucwords($this->input->post('iprice', TRUE));
				$ipayment  = ucwords($this->input->post('ipayment', TRUE));
				// $ipaygroup  = ucwords($this->input->post('ipaygroup', TRUE));

				$ppn = 'f';
				if ($this->input->post('chk-ppn', TRUE) != null) {
					$ppn = 't';
				}
				$tanggal = $this->input->post('tanggal_submit', TRUE);
				$top = $this->input->post('top', TRUE);


				// if ($this->input->post('chk-flafon', TRUE) != null) {
				// 	$ipaygroup = $this->mymodel->insert_groupbayar($kode, $nama);
				// }

				$e_pic_name = $this->input->post('e_pic_name', TRUE);
				$e_pic_phone = $this->input->post('e_pic_phone', TRUE);
				$e_ktp_owner = $this->input->post('e_ktp_owner', TRUE);
				$e_shipment_address = $this->input->post('e_shipment_address', TRUE);
				$n_building_m2 = $this->input->post('n_building_m2', TRUE);
				$e_competitor = $this->input->post('e_competitor', TRUE);
				$d_start = $this->input->post('d_start_submit', TRUE);
				$f_pareto = ($this->input->post('f_pareto') == 'on') ? 't' : 'f';

				$query = $this->db->query("SELECT max(i_customer)+1 AS id FROM tr_customer", TRUE);
				if ($query->num_rows() > 0) {
					$id = $query->row()->id;
					if ($id == null) {
						$id = 1;
					} else {
						$id = $id;
					}
				} else {
					$id = 1;
				}

				$e_ekspedisi_cus  = ucwords($this->input->post('e_ekspedisi_cus', TRUE));
				$e_ekspedisi_bayar  = ucwords($this->input->post('e_ekspedisi_bayar', TRUE));


				$this->mymodel->save($iarea, $icity, $icover, $kode, $nama, $alamat, $pemilik, $telepon, $diskon, $npwp_kode, $npwp_nama, $npwp_alamat, $igroup, $itype, $ilevel, $istatus, $iprice, $ipayment, $diskon2, $diskon3, $ppn, $tanggal, $top, $e_pic_name, $e_pic_phone, $e_ktp_owner, $e_shipment_address, $n_building_m2, $e_competitor, $d_start, $id, $e_ekspedisi_cus, $e_ekspedisi_bayar, $f_pareto);
				// $this->mymodel->save($iarea, $icity, $icover, $kode, $nama, $alamat, $pemilik, $telepon, $diskon, $npwp_kode, $npwp_nama, $npwp_alamat, $igroup, $itype, $ilevel, $istatus, $iprice, $ipayment, $ipaygroup, $diskon2, $diskon3, $ppn, $tanggal, $top, $e_pic_name, $e_pic_phone, $e_ktp_owner, $e_shipment_address, $n_building_m2, $e_competitor, $d_start, $id, $e_ekspedisi_cus, $e_ekspedisi_bayar, $f_pareto);
				if ($this->db->trans_status() === FALSE) {
					$this->db->trans_rollback();
					$data = array(
						'sukses' => false,
						'ada'	 => false,
					);
				} else {
					$this->db->trans_commit();
					$this->logger->write('Simpan ' . $this->title . ' : ' . $kode . ' : ' . $this->session->e_company_name);
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
				'app-assets/vendors/css/forms/icheck/icheck.css',
				'app-assets/vendors/css/pickers/pickadate/pickadate.css',
				'app-assets/css/plugins/forms/switch.css',
			)
		);

		add_js(
			array(
				'app-assets/vendors/js/forms/validation/jqBootstrapValidation.js',
				'app-assets/vendors/js/extensions/sweetalert.min.js',
				'app-assets/vendors/js/forms/select/select2.full.min.js',
				'app-assets/vendors/js/forms/extended/inputmask/jquery.inputmask.bundle.min.js',
				'app-assets/vendors/js/forms/icheck/icheck.min.js',
				'app-assets/vendors/js/pickers/pickadate/picker.js',
				'app-assets/vendors/js/pickers/pickadate/picker.date.js',
				'app-assets/vendors/js/forms/toggle/bootstrap-checkbox.min.js',
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
		$this->form_validation->set_rules('nama', 'nama', 'trim|required|min_length[0]');

		$iarea  = ucwords($this->input->post('iarea', TRUE));
		$icity  = ucwords($this->input->post('icity', TRUE));
		$icover  = ucwords($this->input->post('icover', TRUE));

		$id 	= $this->input->post('id', TRUE);
		$kode  = ucwords($this->input->post('kode', TRUE));
		$kodeold 	= $this->input->post('kodeold', TRUE);
		$nama  = ucwords($this->input->post('nama', TRUE));
		$alamat  = ucwords($this->input->post('alamat', TRUE));

		$pemilik  = ucwords($this->input->post('pemilik', TRUE));
		$telepon  = ucwords($this->input->post('telepon', TRUE));
		$diskon  = str_replace('_', '', str_replace('%', '', $this->input->post('diskon', TRUE)));
		$diskon2  = str_replace('_', '', str_replace('%', '', $this->input->post('diskon2', TRUE)));
		$diskon3  = str_replace('_', '', str_replace('%', '', $this->input->post('diskon3', TRUE)));
		if ($diskon == '') $diskon = 0;
		if ($diskon2 == '') $diskon2 = 0;
		if ($diskon3 == '') $diskon3 = 0;

		$npwp_kode  = ucwords($this->input->post('kode_npwp', TRUE));
		$npwp_nama  = ucwords($this->input->post('nama_npwp', TRUE));
		$npwp_alamat  = ucwords($this->input->post('alamat_npwp', TRUE));

		$igroup  = ucwords($this->input->post('igroup', TRUE));
		$itype  = ucwords($this->input->post('itype', TRUE));
		$ilevel  = ucwords($this->input->post('ilevel', TRUE));

		// $istatus  = ucwords($this->input->post('istatus', TRUE));


		$iprice  = ucwords($this->input->post('iprice', TRUE));
		$ipayment  = ucwords($this->input->post('ipayment', TRUE));
		// $ipaygroup  = ucwords($this->input->post('ipaygroup', TRUE));

		$ppn = 'f';
		if ($this->input->post('chk-ppn', TRUE) != null) {
			$ppn = 't';
		}
		$top = $this->input->post('top', TRUE);

		// if ($this->input->post('chk-flafon', TRUE) != null) {
		// 	$ipaygroup = $this->mymodel->insert_groupbayar($kode, $nama);
		// }

		$tanggal = $this->input->post('tanggal_submit', TRUE);
		$e_pic_name = $this->input->post('e_pic_name', TRUE);
		$e_pic_phone = $this->input->post('e_pic_phone', TRUE);
		$e_ktp_owner = $this->input->post('e_ktp_owner', TRUE);
		$e_shipment_address = $this->input->post('e_shipment_address', TRUE);
		$n_building_m2 = $this->input->post('n_building_m2', TRUE);
		$e_competitor = $this->input->post('e_competitor', TRUE);
		$d_start = $this->input->post('d_start_submit', TRUE);
		$f_pareto = ($this->input->post('f_pareto') == 'on') ? 't' : 'f';

		$e_ekspedisi_cus  = ucwords($this->input->post('e_ekspedisi_cus', TRUE));
		$e_ekspedisi_bayar  = ucwords($this->input->post('e_ekspedisi_bayar', TRUE));


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
				$this->mymodel->update($id, $iarea, $icity, $icover, $kode, $nama, $alamat, $pemilik, $telepon, $diskon, $npwp_kode, $npwp_nama, $npwp_alamat, $igroup, $itype, $ilevel, $iprice, $ipayment, $diskon2, $diskon3, $ppn, $tanggal, $top, $e_pic_name, $e_pic_phone, $e_ktp_owner, $e_shipment_address, $n_building_m2, $e_competitor, $d_start, $e_ekspedisi_cus, $e_ekspedisi_bayar, $f_pareto);
				// $this->mymodel->update($id, $iarea, $icity, $icover, $kode, $nama, $alamat, $pemilik, $telepon, $diskon, $npwp_kode, $npwp_nama, $npwp_alamat, $igroup, $itype, $ilevel, $istatus, $iprice, $ipayment, $ipaygroup, $diskon2, $diskon3, $ppn, $tanggal, $top, $e_pic_name, $e_pic_phone, $e_ktp_owner, $e_shipment_address, $n_building_m2, $e_competitor, $d_start, $e_ekspedisi_cus, $e_ekspedisi_bayar, $f_pareto);
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
		$i_customer_id = $this->db->get_where('tr_customer', ['i_company' => $this->i_company, 'i_customer'  => $id])->row()->i_customer_id;
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
				$this->logger->write('Update Status ' . $this->title . ' Id : ' . $id . ' : ' . $i_customer_id . ' : ' . $this->session->e_company_name);
				$data = array(
					'sukses' => true,
				);
			}
		}
		echo json_encode($data);
	}
}
