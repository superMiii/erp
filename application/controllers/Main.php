<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Main extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		cek_session();
		$this->folder = 'dashboard';
	}

	/** Default Controllers */
	public function index()
	{
		add_css(
			array(
				'app-assets/css/core/colors/palette-climacon.css',
				'app-assets/vendors/css/charts/jquery-jvectormap-2.0.3.css',
				'app-assets/vendors/css/charts/morris.css',
				'app-assets/vendors/css/extensions/unslider.css',
				'app-assets/vendors/css/weather-icons/climacons.min.css',
			)
		);

		add_js(
			array(
				'app-assets/vendors/js/extensions/jquery.knob.min.js',
				'app-assets/js/scripts/extensions/knob.js',
				'app-assets/vendors/js/charts/raphael-min.js',
				'app-assets/vendors/js/charts/morris.min.js',
				'app-assets/vendors/js/charts/jvector/jquery-jvectormap-2.0.3.min.js',
				'app-assets/vendors/js/charts/jvector/jquery-jvectormap-world-mill.js',
				'app-assets/data/jvector/visitor-data.js',
				'app-assets/vendors/js/charts/chart.min.js',
				'app-assets/vendors/js/charts/jquery.sparkline.min.js',
				'app-assets/vendors/js/extensions/unslider-min.js',
				'assets/js/' . $this->folder . '/index.js',
			)
		);
		$this->template->load('main', $this->folder . '/index');
	}

	public function change_password()
	{
		add_css(
			array(
				'app-assets/css/plugins/forms/wizard.min.css',
				'app-assets/vendors/css/extensions/sweetalert.css',
			)
		);

		add_js(
			array(
				'app-assets/vendors/js/extensions/jquery.steps.min.js',
				'app-assets/vendors/js/forms/validation/jquery.validate.min.js',
				'app-assets/vendors/js/extensions/sweetalert.min.js',
				'assets/js/password/index.js',
			)
		);
		$data = array(
			'data' => $this->db->get_where('tm_user', ['i_user' => $this->session->userdata('i_user'), 'f_status' => true])->row(),
		);
		$this->template->load('main', 'auth/password', $data);
	}

	public function user()
	{
		add_css(
			array(
				'app-assets/css/plugins/forms/validation/form-validation.css',
				'app-assets/vendors/css/extensions/sweetalert.css',
				'app-assets/vendors/css/forms/selects/select2.min.css',
				'app-assets/css/plugins/forms/switch.css',
			)
		);

		add_js(
			array(
				'app-assets/vendors/js/forms/validation/jqBootstrapValidation.js',
				'app-assets/vendors/js/extensions/sweetalert.min.js',
				'app-assets/vendors/js/forms/select/select2.full.min.js',
				'app-assets/vendors/js/forms/toggle/bootstrap-checkbox.min.js',
				'assets/js/password/index1.js',
			)
		);
		$data = array(
			'data' => $this->db->get_where('tm_user', ['i_user' => $this->session->userdata('i_user'), 'f_status' => true])->row(),
		);
		$this->template->load('main', 'auth/user', $data);
	}

	public function update()
	{
		$this->form_validation->set_rules('user_id', 'user_id', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('i_user_id', 'i_user_id', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('repeat_password', 'repeat_password', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('e_user_password', 'e_user_password', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('repeat_e_user_password', 'repeat_e_user_password', 'trim|required|min_length[0]');
		$user_id 	= $this->input->post('user_id', TRUE);
		$e_user_password 	= encrypt_password($this->input->post('e_user_password', TRUE));
		if ($this->form_validation->run() == false) {
			$data = array(
				'sukses' => false,
				'ada'	 => false,
			);
		} else {
			/** Update Data */
			$this->db->trans_begin();
			$data = array(
				'e_user_password' => $e_user_password,
			);
			$this->db->where('i_user', $this->session->i_user);
			$this->db->update('tm_user', $data);
			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				$data = array(
					'sukses' => false,
					'ada'	 => false,
				);
			} else {
				$this->db->trans_commit();
				$this->logger->write('User [' . $user_id . '] Update Password');
				$data = array(
					'sukses' => true,
					'ada'	 => false,
				);
			}
		}
		echo json_encode($data);
	}

	public function update1()
	{
		
		$this->form_validation->set_rules('i_user', 'i_user', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('e_name', 'e_name', 'trim|required|min_length[0]');
		$i_user = $this->input->post('i_user', TRUE);
		$e_name = $this->input->post('e_name', TRUE);
		$fotoold = $this->input->post('fotoold', TRUE);

		$fotoup		= $_FILES['fotonew'];
		if ($fotoup = '') {
			$fotoup = $fotoold;
		} else {
			$config['upload_path']          = './assets/images/avatar/';
			$config['allowed_types']        = 'gif|jpg|jpeg|png';
			$config['overwrite']            = true;
			// $config['max_size']             = 1024; // 1MB
			// $config['max_width']            = 1080;
			// $config['max_height']           = 1080;

			$this->load->library('upload', $config);

			if (!$this->upload->do_upload('fotonew')) {
				$fotoup = $fotoold;
			} else {
				$fotoup = $this->upload->data('file_name');
				chmod("./assets/images/avatar/$fotoup", 0777);
				if ($fotoold != "default.jpg") {
					unlink("./assets/images/avatar/$fotoold");
				}
			}
		}

		if ($this->form_validation->run() == false) {
			$data = array(
				'sukses' => false,
				'ada'	 => false,
			);
		} else {
			// $cek = $this->mymodel->cek_edit($i_user_id, $i_user_id_old);
			// if ($cek->num_rows() > 0) {
			// 	$data = array(
			// 		'sukses' => false,
			// 		'ada'	 => true,
			// 	);
			// } else {
				
				$table = array(
					'ava'             => $fotoup,
				);
				$this->db->where('i_user', $i_user);
				$this->db->update('tm_user', $table);
				$this->logger->write('Upload foto ' . $fotoup . ' : ' . $e_name);

				// $this->db->trans_begin();
				// $this->mymodel->update($i_user, $i_user_id, $e_user_name, $password, $f_pusat, $fotoup);
				if ($this->db->trans_status() === FALSE) {
					$this->db->trans_rollback();
					$data = array(
						'sukses' => false,
						'ada'	 => false,
					);
				} else {
					$this->db->trans_commit();
					$this->logger->write('Upload foto ' . $fotoup . ' : ' . $e_name);
					$data = array(
						'sukses' => true,
						'ada'	 => false,
					);
				}
			// }
		}
		echo json_encode($data);

		

		
	}
}
