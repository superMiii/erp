<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Viewstok extends CI_Controller
{
	public $id_menu = '604';

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
		// add_css(
		// 	array(
		// 		'app-assets/vendors/css/pickers/pickadate/pickadate.css',
		// 	)
		// );

		// add_js(
		// 	array(
		// 		'app-assets/vendors/js/pickers/pickadate/picker.js',
		// 		'app-assets/vendors/js/pickers/pickadate/picker.date.js',
		// 		'assets/js/' . $this->folder . '/index.js',
		// 	)
		// );

		$teangan = $this->input->post('teangan', TRUE);
		if ($teangan == '') {
			$teangan = $this->uri->segment(3);
			// if ($teangan == '') {
			// 	$teangan = '01-' . date('m-Y');
			// }
		}
		// $dto = $this->input->post('dto', TRUE);
		// if ($dto == '') {
		// 	$dto = $this->uri->segment(4);
		// 	if ($dto == '') {
		// 		$dto = date('d-m-Y');
		// 	}
		// }
		$data = array(
			'teangan'     => $teangan,
			// 'dto'       => date('d-m-Y', strtotime($dto)),
			'data'      => $this->mymodel->serverside($teangan),
		);

		$this->logger->write('Membuka Menu ' . $this->title);
		$this->template->load('main', $this->folder . '/index', $data);
	}
}
