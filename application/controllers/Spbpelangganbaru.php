<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Spbpelangganbaru extends CI_Controller
{
	public $id_menu = '309';

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
				'app-assets/css/plugins/forms/wizard.min.css',
				'app-assets/vendors/css/forms/icheck/icheck.css',
				'app-assets/css/plugins/forms/switch.css',
				'app-assets/css/global.css',
			)
		);

		add_js(
			array(
				'app-assets/vendors/js/forms/validation/jqBootstrapValidation.js',
				'app-assets/vendors/js/extensions/sweetalert2.all.min.js',
				'app-assets/vendors/js/forms/select/select2.full.min.js',
				'app-assets/vendors/js/extensions/jquery.steps.min.js',
				'app-assets/vendors/js/forms/validation/jquery.validate.min.js',
				'app-assets/vendors/js/forms/extended/inputmask/jquery.inputmask.bundle.min.js',
				'app-assets/vendors/js/forms/toggle/bootstrap-checkbox.min.js',
				'app-assets/vendors/js/forms/icheck/icheck.min.js',
				'assets/js/' . $this->folder . '/add.js?v='.date('YmdHis'),
			)
		);
		$this->logger->write('Membuka Form Tambah ' . $this->title);
		$this->template->load('main', $this->folder . '/add');
	}

	/** Get Number */
	public function number()
	{
		$tanggal = $this->input->post('tanggal', TRUE);
		$i_area = $this->input->post('i_area', TRUE);
		if ($tanggal != '') {
			$number = $this->mymodel->running_number(date('ym', strtotime($tanggal)), date('Y', strtotime($tanggal)), $i_area);
		} else {
			$number = $this->mymodel->running_number(date('ym'), date('Y'), $i_area);
		}
		echo json_encode($number);
	}

	/** Get Data Alasan */
	public function get_alasan_retur()
	{
		$filter = [];
		$data = $this->mymodel->get_alasan_retur(str_replace("'", "", $this->input->get('q')));
		foreach ($data->result() as $row) {
			$filter[] = array(
				'id'   => $row->i_alasan_retur,
				'text' => $row->e_alasan_retur_name,
			);
		}
		echo json_encode($filter);
	}

	/** Get Data Area */
	public function get_area()
	{
		$filter = [];
		$data = $this->mymodel->get_area(str_replace("'", "", $this->input->get('q')));
		foreach ($data->result() as $row) {
			$filter[] = array(
				'id'   => $row->i_area,
				'text' => $row->i_area_id . ' - ' . $row->e_area_name,
			);
		}
		echo json_encode($filter);
	}

	/** Get Data Customer */
	public function get_customer()
	{
		$filter = [];
		$cari 	= str_replace("'", "", $this->input->get('q'));
		$i_area = $this->input->get('i_area');
		if ($i_area != '') {
			$data = $this->mymodel->get_customer($cari, $i_area);
			foreach ($data->result() as $row) {
				$filter[] = array(
					'id'   => $row->i_customer,
					'text' => $row->i_customer_id . ' - ' . $row->e_customer_name,
				);
			}
		} else {
			$filter[] = array(
				'id'   => null,
				'text' => $this->lang->line('Pilih') . ' ' . $this->lang->line('Area'),
			);
		}
		echo json_encode($filter);
	}

	/** Get Detail Customer */
	public function get_customer_detail()
	{
		header("Content-Type: application/json", true);
		$icustomer = $this->input->post('i_customer', TRUE);
		$query  = array(
			'header' => $this->mymodel->get_customer_detail($icustomer)->result_array()
		);
		echo json_encode($query);
	}

	/** Get Data Salesman */
	public function get_salesman()
	{
		$filter = [];
		$i_area = $this->input->get('iarea');
		if ($i_area != '') {
			$data = $this->mymodel->get_salesman(str_replace("'", "", $this->input->get('q')), $i_area);
			foreach ($data->result() as $row) {
				$filter[] = array(
					'id'   => $row->i_salesman,
					'text' => $row->i_salesman_id . ' - ' . $row->e_salesman_name,
				);
			}
		} else {
			$filter[] = array(
				'id'   => null,
				'text' => $this->lang->line('Pilih') . ' ' . $this->lang->line('Area'),
			);
		}
		echo json_encode($filter);
	}

	/** Get Product */
	public function get_product()
	{
		$filter = [];
		$cari = str_replace("'", "", $this->input->get('q'));
		$i_price_group = $this->input->get('i_price_group');
		$i_product_group = $this->input->get('i_product_group');
		if ($i_price_group != '' && $i_product_group != '') {
			if ($cari != '') {
				$data = $this->mymodel->get_product($cari, $i_product_group, $i_price_group);
				foreach ($data->result() as $row) {
					$filter[] = array(
						'id'   => $row->i_product,
						'text' => $row->i_product_id . ' - ' . $row->e_product_name . ' ( ' . $row->e_product_motifname . " " . $row->e_product_colorname . ' )',
					);
				}
			} else {
				$filter[] = array(
					'id'   => null,
					'text' => $this->lang->line('Ketik Untuk Cari Data'),
				);
			}
		} else {
			$filter[] = array(
				'id'   => null,
				'text' => $this->lang->line('Pilih') . ' ' . $this->lang->line('Kelompok Barang') . ' / ' . $this->lang->line('Pelanggan'),
			);
		}
		echo json_encode($filter);
	}

	/** Get Detail Product */
	public function get_product_price()
	{
		header("Content-Type: application/json", true);
		$i_price_group = $this->input->post('i_price_group', TRUE);
		$i_product_group = $this->input->post('i_product_group', TRUE);
		$i_product = $this->input->post('i_product', TRUE);
		$query  = array(
			'detail' => $this->mymodel->get_product_price($i_price_group, $i_product_group, $i_product)->result_array()
		);
		echo json_encode($query);
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
		$data = $this->mymodel->get_group($this->input->get('q'), $this->input->get('iareax'));
		foreach ($data->result() as $row) {
			$filter[] = array(
				'id'   => $row->i_customer_group,
				'text' => $row->e_customer_groupname,
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

	/** Get Data Product Group */
	public function get_product_group()
	{
		$filter = [];
		$data = $this->mymodel->get_product_group(str_replace("'", "", $this->input->get('q')));
		foreach ($data->result() as $row) {
			$filter[] = array(
				'id'   => $row->i_product_group,
				'text' => $row->i_product_groupid . ' - ' . $row->e_product_groupname,
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

		/* Validasi Pelanggan */
		$this->form_validation->set_rules('iarea', 'iarea', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('kode', 'kode', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('nama', 'nama', 'trim|required|min_length[0]');

		/* Validasi SPB */
		/* $this->form_validation->set_rules('i_area', 'i_area', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('i_customer', 'i_customer', 'trim|required|min_length[0]'); */
		$this->form_validation->set_rules('i_salesman', 'i_salesman', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('iprice', 'iprice', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('i_product_group', 'i_product_group', 'trim|required|min_length[0]');
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
				$iarea  = ucwords($this->input->post('iarea', TRUE));
				$icity  = ucwords($this->input->post('icity', TRUE));
				$icover  = ucwords($this->input->post('icover', TRUE));

				$kode  = ucwords($this->input->post('kode', TRUE));
				$nama  = ucwords($this->input->post('nama', TRUE));
				$alamat  = ucwords($this->input->post('alamat', TRUE));

				$pemilik  = ucwords($this->input->post('pemilik', TRUE));
				$telepon  = ucwords($this->input->post('telepon', TRUE));
				// $diskon  = str_replace('_', '', str_replace('%', '', $this->input->post('diskon', TRUE)));
				// $diskon2  = str_replace('_', '', str_replace('%', '', $this->input->post('diskon2', TRUE)));
				// $diskon3  = str_replace('_', '', str_replace('%', '', $this->input->post('diskon3', TRUE)));

				// if ($diskon == '') $diskon = 0;
				// if ($diskon2 == '') $diskon2 = 0;
				// if ($diskon3 == '') $diskon3 = 0;

				if ($this->input->post('chk-flx', TRUE) != null) {
					$diskon = 0;
					$diskon2 = 0;
					$diskon3 = 0;
				} else {
					$diskon  = str_replace('_', '', str_replace('%', '', $this->input->post('diskon', TRUE)));
					$diskon2  = str_replace('_', '', str_replace('%', '', $this->input->post('diskon2', TRUE)));
					$diskon3  = str_replace('_', '', str_replace('%', '', $this->input->post('diskon3', TRUE)));

					if ($diskon == '') $diskon = 0;
					if ($diskon2 == '') $diskon2 = 0;
					if ($diskon3 == '') $diskon3 = 0;
				}


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
				$tanggal = $this->input->post('tanggal', TRUE);
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
				$d_start = $this->input->post('d_start', TRUE);

				$e_ekspedisi_cus  = ucwords($this->input->post('e_ekspedisi_cus', TRUE));
				$e_ekspedisi_bayar  = ucwords($this->input->post('e_ekspedisi_bayar', TRUE));

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

				$this->mymodel->save($iarea, $icity, $icover, $kode, $nama, $alamat, $pemilik, $telepon, $diskon, $npwp_kode, $npwp_nama, $npwp_alamat, $igroup, $itype, $ilevel, $istatus, $iprice, $ipayment, $diskon2, $diskon3, $ppn, $tanggal, $top, $e_pic_name, $e_pic_phone, $e_ktp_owner, $e_shipment_address, $n_building_m2, $e_competitor, $d_start, $id, $e_ekspedisi_cus, $e_ekspedisi_bayar);
				// $this->mymodel->save($iarea, $icity, $icover, $kode, $nama, $alamat, $pemilik, $telepon, $diskon, $npwp_kode, $npwp_nama, $npwp_alamat, $igroup, $itype, $ilevel, $istatus, $iprice, $ipayment, $ipaygroup, $diskon2, $diskon3, $ppn, $tanggal, $top, $e_pic_name, $e_pic_phone, $e_ktp_owner, $e_shipment_address, $n_building_m2, $e_competitor, $d_start, $id, $e_ekspedisi_cus, $e_ekspedisi_bayar);
				// $this->mymodel->save();
				if ($this->db->trans_status() === FALSE) {
					$this->db->trans_rollback();
					$data = array(
						'sukses' => false,
						'ada'	 => false,
					);
				} else {
					$this->db->trans_commit();
					$this->logger->write('Simpan ' . $this->title . ' : ' . $this->input->post('i_document') . ' I_Area : ' . $this->input->post('i_area'). ' : ' . $this->session->e_company_name);
					$data = array(
						'sukses' => true,
						'ada'	 => false,
					);
				}
			}
		}
		echo json_encode($data);
	}

	public function cek_kode()
	{
		$query = $this->mymodel->cek_code();
		if ($query->num_rows() > 0) {
			echo json_encode(1);
		} else {
			echo json_encode(0);
		}
	}
}
