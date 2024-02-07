<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Raya extends CI_Controller
{
	public $id_menu = '109';

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
				'app-assets/vendors/css/forms/selects/select2.min.css',
				'app-assets/vendors/css/animate/animate.css',
				'app-assets/vendors/css/pickers/pickadate/pickadate.css',
			)
		);

		add_js(
			array(
				'app-assets/vendors/js/tables/datatable/datatables.min.js',
				'app-assets/vendors/js/tables/datatable/dataTables.buttons.min.js',
				'app-assets/vendors/js/tables/datatable/buttons.bootstrap4.min.js',
				'app-assets/vendors/js/extensions/sweetalert2.all.min.js',
				'app-assets/vendors/js/forms/select/select2.full.min.js',
				'app-assets/vendors/js/pickers/pickadate/picker.js',
				'app-assets/vendors/js/pickers/pickadate/picker.date.js',
				'assets/js/' . $this->folder . '/index.js?v=19',
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
				$i_store = 'ALL';
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

		if ($i_store != 'ALL') {
			$e_store_name = $this->db->get_where('tr_store', ['f_store_active' => true, 'i_company' => $this->i_company, 'i_store' => $i_store])->row()->e_store_name;
		} else {
			$e_store_name = '( Semua )';
		}

		$data = array(
			'dfrom'     => date('d-m-Y', strtotime($dfrom)),
			'dto'       => date('d-m-Y', strtotime($dto)),
			'i_store'           => $i_store,
			'e_store_name'      => ucwords(strtolower($e_store_name)),
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
	public function get_store()
	{
		$filter = [];
		$filter[] = array(
			'id'   => 'ALL',
			'text' => '( Semua )',
		);
		$data = $this->mymodel->get_store(str_replace("'", "", $this->input->get('q')));
		foreach ($data->result() as $row) {
			$filter[] = array(
				'id'   => $row->i_store,
				'text' => $row->e_store_name,
			);
		}
		echo json_encode($filter);
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
				'assets/js/' . $this->folder . '/add.js?v=19',
			)
		);

		$dfrom 		= decrypt_url($this->uri->segment(3));
		$dto 		= decrypt_url($this->uri->segment(4));
		$i_store	= decrypt_url($this->uri->segment(5));

		$data = array(
			'iproduk' 		=> $this->db->get_where('tr_product', ['f_product_active' => true, 'i_company' => $this->i_company]),
			'imotif' 		=> $this->db->get_where('tr_product_motif', ['f_product_motifactive' => true, 'i_company' => $this->i_company]),
			'igrade' 		=> $this->db->get_where('tr_product_grade', ['f_product_gradeactive' => true, 'i_company' => $this->i_company]),
			'igudang' 		=> $this->db->get_where('tr_store', ['f_store_active' => true, 'i_company' => $this->i_company]),
			'istoreloc' 	=> $this->db->get_where('tr_store_loc', ['f_store_loc_active' => true, 'i_company' => $this->i_company]),
			'dfrom'	 		=> $dfrom,
			'dto'	 		=> $dto,
			'i_store'		=> $i_store,
		);
		$this->logger->write('Membuka Form Tambah ' . $this->title);
		$this->template->load('main', $this->folder . '/add', $data);
	}

	public function get_produk()
	{
		$filter = [];
		$cari 	= $this->input->get('q');
		if ($cari != '') {
			$data = $this->mymodel->get_produk($cari);
			foreach ($data->result() as $row) {
				$filter[] = array(
					'id'   => $row->i_product,
					'text' => $row->i_product_id,
				);
			}
		} else {
			$filter[] = array(
				'id'   => null,
				'text' => 'Masukan Kode Barang',
			);
		}
		echo json_encode($filter);
	}

	public function get_namaproduk()
	{
		header("Content-Type: application/json", true);
		$ngaran = $this->input->post('namabarang', TRUE);
		$query  = array(
			'header' => $this->mymodel->get_namaproduk($ngaran)->result_array()
		);
		echo json_encode($query);
	}

	public function get_storloc()
	{
		$filter = [];
		$loc 	= $this->input->get('q');
		$gudang = $this->input->get('gudang');

		if ($gudang != '') {
			$data = $this->mymodel->get_storloc($gudang, $loc);
			foreach ($data->result() as $row) {
				$filter[] = array(
					'id'   => $row->i_store_loc,
					'text' => $row->e_store_loc_name,
				);
			}
		} else {
			$filter[] = array(
				'id'   => null,
				'text' => 'Pilih gudang !!!',
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

		$this->form_validation->set_rules('Stok', 'Stok', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('iproduct', 'iproduct', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('ebr', 'ebr', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('imotif', 'imotif', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('igrade', 'igrade', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('igudang', 'igudang', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('istorloc', 'istorloc', 'trim|required|min_length[0]');

		if ($this->form_validation->run() == false) {
			$data = array(
				'sukses' => false,
				'ada'	 => false,
			);
		} else {
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
				$this->logger->write('Tambah Stok' . $this->input->post('iproduct'));
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
				'assets/js/' . $this->folder . '/edit.js?v=19',
			)
		);

		$id = decrypt_url($this->uri->segment(3));
		$dfrom 			= decrypt_url($this->uri->segment(4));
		$dto 			= decrypt_url($this->uri->segment(5));
		$i_store		= decrypt_url($this->uri->segment(6));
		$data = array(
			'data' 	 		=> $this->mymodel->get_data_edit($id)->row(),
			'dfrom'	 		=> $dfrom,
			'dto'	 		=> $dto,
			'i_store'		=> $i_store,
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

		$this->form_validation->set_rules('iic', 'iic', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('produkname', 'produkname', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('stk', 'stk', 'trim|required|min_length[0]');

		if ($this->form_validation->run() == false) {
			$data = array(
				'sukses' => false,
				'ada'	 => false,
			);
		} else {
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
				$this->logger->write('Merubah Stok' . $this->input->post('b2'));
				$data = array(
					'sukses' => true,
					'ada'	 => false,
				);
			}
		}
		echo json_encode($data);
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
				$this->logger->write('Cancel Data ' . $this->title . ' Id : ' . $id);
				$data = array(
					'sukses' => true,
				);
			}
		}
		echo json_encode($data);
	}

}
