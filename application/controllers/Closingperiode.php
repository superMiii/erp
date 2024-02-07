<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Closingperiode extends CI_Controller
{
    public $id_menu = '514';

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
                'app-assets/vendors/css/extensions/sweetalert2.min.css',
                'app-assets/vendors/css/forms/selects/select2.min.css',
                'app-assets/vendors/css/animate/animate.css',
            )
        );

        add_js(
            array(
                'app-assets/vendors/js/extensions/sweetalert2.all.min.js',
                'app-assets/vendors/js/forms/select/select2.full.min.js',
                'assets/js/' . $this->folder . '/index.js',
            )
        );

        $this->logger->write('Membuka Menu ' . $this->title);
        $this->template->load('main', $this->folder . '/index');
    }

    /** Closing Periode */
    public function closing()
    {
        $data = check_role($this->id_menu, 3);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        /** Parameter From Ajax Post */
        $this->form_validation->set_rules('year', 'year', 'trim|required|min_length[0]');
        $this->form_validation->set_rules('month', 'month', 'trim|required|min_length[0]');
        $year  = $this->input->post('year', TRUE);
        $month = $this->input->post('month', TRUE);
        if ($this->form_validation->run() == false) {
            $data = array(
                'sukses' => false,
                'periode' => $year.$month,
            );
        } else {
            $this->db->trans_begin();
            $this->mymodel->update_periode($year, $month);
            $this->mymodel->update_log($year, $month);
            $this->mymodel->update_coa_saldo($year, $month);
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $data = array(
                    'sukses' => false,
                    'periode' => $year.$month,
                );
            } else {
                $this->db->trans_commit();
                $this->logger->write('Closing All Transaksi Periode : ' . $year . $month . ' : ' . $this->session->e_company_name);
                $data = array(
                    'sukses' => true,
                    'periode' => $year.$month,
                );
            }
        }
        echo json_encode($data);
    }
}
