<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Tgl extends CI_Controller
{
    public $id_menu = '108';

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
        $this->folder     = $data->e_folder;
        $this->title      = $data->e_menu;
        $this->icon       = $data->icon;
        $this->i_company  = $this->session->i_company;
        $this->i_user     = $this->session->i_user;

        /** Load Model, Nama model harus sama dengan nama folder */
        $this->load->model('m' . $this->folder, 'mymodel');
    }

    /** Default Controllers */
    public function index()
    {
        add_css(
            array(
				'app-assets/css/plugins/forms/validation/form-validation.css',
				/* 'app-assets/vendors/css/extensions/sweetalert.css', */
				'app-assets/vendors/css/extensions/sweetalert2.min.css',
				'app-assets/vendors/css/animate/animate.css',
				'app-assets/vendors/css/forms/selects/select2.min.css',
				'app-assets/css/plugins/forms/switch.css',
				'app-assets/vendors/css/pickers/pickadate/pickadate.css',
				'app-assets/css/global.css',
            )
        );

        add_js(
            array(
				'app-assets/vendors/js/forms/validation/jqBootstrapValidation.js',
				/* 'app-assets/vendors/js/extensions/sweetalert.min.js', */
				'app-assets/vendors/js/extensions/sweetalert2.all.min.js',
				'app-assets/vendors/js/forms/select/select2.full.min.js',
				'app-assets/vendors/js/forms/extended/inputmask/jquery.inputmask.bundle.min.js',
				'app-assets/vendors/js/pickers/pickadate/picker.js',
				'app-assets/vendors/js/pickers/pickadate/picker.date.js',
				'app-assets/vendors/js/forms/toggle/bootstrap-checkbox.min.js',
                'assets/js/' . $this->folder . '/index.js?v='.date('YmdHis'),
            )
        );
		$data = array(
			'data' => $this->mymodel->getdata()->row(),
		);

        $this->logger->write('Membuka Menu ' . $this->title);
        $this->template->load('main', $this->folder . '/index', $data);
    }

    /** Closing Periode */
    public function update()
	{
		/** Cek Hak Akses, Apakah User Bisa Edit */
		$data = check_role($this->i_menu, 3);
		if (!$data) {
			redirect(base_url(), 'refresh');
		}

		$this->form_validation->set_rules('id', 'id', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('d_tgl', 'd_tgl', 'trim|required|min_length[0]');

		if ($this->form_validation->run() == false) {
			$data = array(
				'sukses' => false,
				'ada'	 => false,
			);
		} else {
			/** Cek Jika Kode Sudah Ada */
			// $cek = $this->mymodel->cek_edit();
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
					$this->logger->write('Kunci tgl ' . $this->title . '  : ' . $this->input->post('d_tgl') . ' : ' . $this->session->e_company_name);
					$data = array(
						'sukses' => true,
						'ada'	 => false,
					);
				}
			}
		}
		echo json_encode($data);
	}
}
