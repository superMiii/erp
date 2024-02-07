<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{

    public function index()
    {
        cek_login();
        $this->form_validation->set_rules('username', 'Username', 'trim|required|min_length[0]');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[0]');

        if ($this->form_validation->run() == false) {
            $this->load->view('auth/auth');
        } else {

            $username = strtoupper(trim($this->input->post('username', true)));
            $password = encrypt_password(trim($this->input->post('password', true)));

            //$user = $this->db->get_where('tm_user', ['upper(i_user_id)' => $username, 'e_user_password' => $password, 'f_status' => 't'])->row_array();

            $user = $this->db->query("WITH cte as (
                    select
                        *
                    from
                        tm_user
                    where
                        upper(i_user_id ) = '$username'
                        and e_user_password = '$password'
                        and f_status = true 
                ) 
                select a.*, b.i_company, b.e_company_name, b.f_company_plusppn, b.n_ppn, b.v_meterai, b.v_meterai_limit, b.f_plus_meterai, b.e_color from cte a
                left join (
                    select
                        DISTINCT ON (x.i_user) x.i_user, y.e_company_name, x.i_company, y.f_company_plusppn, y.n_ppn, y.v_meterai, y.v_meterai_limit, y.f_plus_meterai, y.e_color
                    from tm_user_company x
                    inner join tr_company y on (x.i_company = y.i_company)
                    where x.i_user in (select i_user from cte) and y.f_company_active = true
                    order by x.i_user, x.i_company asc
                ) as b on a.i_user = b.i_user 
            ", FALSE)->row_array();

            if ($user) {
                $data = array(
                    'i_user'            => $user['i_user'],
                    'e_user_name'       => $user['e_user_name'],
                    'i_user_id'         => $user['i_user_id'],
                    'F_status'          => $user['f_status'],
                    'i_company'         => $user['i_company'],
                    'e_company_name'    => $user['e_company_name'],
                    'f_company_plusppn' => $user['f_company_plusppn'],
                    'n_ppn'             => $user['n_ppn'],
                    'v_meterai'         => $user['v_meterai'],
                    'v_meterai_limit'   => $user['v_meterai_limit'],
                    'f_plus_meterai'    => $user['f_plus_meterai'],
                    'language'          => 'indonesia',
                    'fix_menu'          => 'menu-expanded',
                    'e_color'           => $user['e_color'],
                    'f_pusat'           => $user['f_pusat'],
                    'ava'               => $user['ava'],
                );

                $this->session->set_userdata($data);
                $this->logger->write('Login');
                /* redirect('main', 'refresh'); */
                redirect(site_url());
            } else {
                $this->session->Set_flashdata('message', '<div class="alert alert-icon-right alert-secondary alert-dismissible mb-2" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
                <strong>Username</strong> atau
                <strong>Password</strong> Anda Salah :(
              </div>');
                redirect('auth', 'refresh');
            }
        }
    }

    public function logout()
    {
        $this->logger->write('Logout');
        $this->session->sess_destroy();
        redirect('auth', 'refresh');
    }


    public function set_company()
    {
        $data = array(
            'i_company' => $this->input->post('id'),
            'e_company_name' => $this->input->post('name'),
            'f_company_plusppn' => $this->input->post('ppn'),
            'n_ppn' => $this->input->post('n_ppn'),
            'f_plus_meterai' => $this->input->post('f_plus_meterai'),
            'v_meterai' => $this->input->post('v_meterai'),
            'v_meterai_limit' => $this->input->post('v_meterai_limit'),
            'e_color' => $this->input->post('e_color'),
        );
        $this->session->set_userdata($data);
    }

    public function set_department()
    {
        $data = array(
            'i_department' => $this->input->post('id'),
            'e_department_name' => $this->input->post('name'),
        );
        $this->session->set_userdata($data);
    }

    public function set_level()
    {
        $data = array(
            'i_level' => $this->input->post('id'),
            'e_level_name' => $this->input->post('name'),
        );
        $this->session->set_userdata($data);
    }

    public function set_activemenu()
    {
        $data = array(
            'idmenu_1' => $this->input->post('idmenu_1'),
            'idmenu_2' => $this->input->post('idmenu_2'),
            'idmenu_3' => $this->input->post('idmenu_3'),
            'current_link' => $this->input->post('folder'),
        );
        $this->session->set_userdata($data);
    }

    public function switch_language()
    {
        //$language = "indonesia";
        $this->session->set_userdata('language', $this->input->post('language'));
    }

    public function set_collapse()
    {
        $this->session->set_userdata('fix_menu', $this->input->post('menucolex'));
    }
}
