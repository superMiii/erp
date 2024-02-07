<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Style;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Conditional;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\IOFactory;

class Infoikhp extends CI_Controller
{
    public $id_menu = '90408';

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
                'app-assets/vendors/css/forms/selects/select2.min.css',
                'app-assets/vendors/css/pickers/pickadate/pickadate.css',
            )
        );

        add_js(
            array(
                'app-assets/vendors/js/forms/select/select2.full.min.js',
                'app-assets/vendors/js/pickers/pickadate/picker.js',
                'app-assets/vendors/js/pickers/pickadate/picker.date.js',
                'assets/js/' . $this->folder . '/index.js?v=1',
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
        }
        if ($i_area != '') {
            $e_area_name = $this->db->get_where('tr_area', ['f_area_active' => true, 'i_company' => $this->i_company, 'i_area' => $i_area])->row()->e_area_name;
            $i_area_id = $this->db->get_where('tr_area', ['f_area_active' => true, 'i_company' => $this->i_company, 'i_area' => $i_area])->row()->i_area_id;
        } else {
            $e_area_name = '';
            $i_area_id = '';
        }
        
        $ka         = date('m', strtotime($dfrom));
        $de         = date('m', strtotime($dto));
// var_dump($ka , $de);
// die;

        if ($ka == $de){
        }
        $data = array(
            'dfrom'             => date('d-m-Y', strtotime($dfrom)),
            'dto'               => date('d-m-Y', strtotime($dto)),
            'i_area'            => $i_area,
            'i_area_id'         => $i_area_id,
            'e_area_name'       => ucwords(strtolower($e_area_name)),
            'data'              => $this->mymodel->serverside(),
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
        $data = $this->mymodel->get_area(str_replace("'", "", $this->input->get('q')));
        foreach ($data->result() as $row) {
            $filter[] = array(
                'id'   => $row->i_area,
                'text' => $row->i_area_id . ' - ' . $row->e_area_name,
            );
        }
        echo json_encode($filter);
    }

    /** Export Data */
    public function export()
    {
        /** Parameter From Ajax Post */
        $dfrom      = $this->input->post('dfrom', TRUE);
        $dto        = $this->input->post('dto', TRUE);
        $i_area     = $this->input->post('i_area', TRUE);
        if ($dfrom == '') {
            $dfrom = $this->uri->segment(3);
        }

        if ($dto == '') {
            $dto = $this->uri->segment(4);
        }

        if ($i_area == '') {
            $i_area = $this->uri->segment(5);
        }
        if ($i_area != 'NA') {
            $e_area_name = $this->db->get_where('tr_area', ['f_area_active' => true, 'i_company' => $this->i_company, 'i_area' => $i_area])->row()->e_area_name;
        } else {
            $e_area_name = 'Nasional';
        }


        $e_company_name = $this->db->get_where('tr_company', ['f_company_active' => true, 'i_company' => $this->i_company])->row()->e_company_name;

        /** Query Get Data */
        $data      = $this->mymodel->serverside2($dfrom, $dto, $i_area);

        $html_data = '
        <table>
        <thead>
			<tr>
				<th class="text-center" colspan="10">&nbsp;</th>
			</tr>
			<tr>
				<th class="text-center" colspan="10">IKHP</th>
			</tr>
			<tr>
				<th class="text-center" colspan="10">' . $e_company_name . '</th>
			</tr>
			<tr>
				<th class="text-center" colspan="10">' . $e_area_name . '</th>
			</tr>
			<tr>
				<th class="text-center" colspan="10">' . $dfrom . ' Sampai ' . $dto . '</th>
			</tr>
			<tr>
				<th class="text-center" colspan="10">&nbsp;</th>
			</tr>
		</thead>
        </table>
        <table class="table table-xs display nowrap table-striped table-bordered" id="serverside" width="100%;">
        <thead>
			<tr>
				<th rowspan="2" class="text-center" width="5%;">No</th>
				<th class="text-center" rowspan="2">' . $this->lang->line('Tanggal') . '</th>
				<th class="text-center" rowspan="2">' . $this->lang->line('No Referensi') . '</th>
				<th class="text-center" rowspan="2">' . $this->lang->line('Uraian') . '</th>
				<th class="text-center" colspan="2">' . $this->lang->line('Penerimaan') . '</th>
				<th class="text-center" colspan="2">' . $this->lang->line('Pengeluaran') . '</th>
				<th class="text-center" colspan="2">' . $this->lang->line('Saldo Akhir') . '</th>
			</tr>
			<tr>
				<th class="text-right">' . $this->lang->line('Tunai') . '</th>
				<th class="text-right">' . $this->lang->line('Giro') . '</th>
				<th class="text-right">' . $this->lang->line('Tunai') . '</th>
				<th class="text-right">' . $this->lang->line('Giro') . '</th>
				<th class="text-right">' . $this->lang->line('Tunai') . '</th>
				<th class="text-right">' . $this->lang->line('Giro') . '</th>
            </tr>
		</thead>
		<tbody>';
        $i = 0;
        $debet_tunai = 0;
        $credit_tunai = 0;
        $saldo_akhir_tunai = 0;
        $debet_giro = 0;
        $credit_giro = 0;
        $saldo_akhir_giro = 0;
        if ($data->num_rows() > 0) {
            foreach ($data->result() as $key) {
                $debet_tunai += $key->debet_tunai;
                $credit_tunai += $key->credit_tunai;
                $saldo_akhir_tunai = $key->saldo_akhir_tunai;

                $debet_giro += $key->debet_giro;
                $credit_giro += $key->credit_giro;
                $saldo_akhir_giro = $key->saldo_akhir_giro;

                $i++;
                $html_data .= '<tr>
						<td class="text-center">' . $i . '</td>
						<td>' . $key->tanggal . '</td>
						<td>' . $key->kode . '</td>
						<td>' . $key->e_remark . '</td>
						<td>' . $key->debet_tunai . '</td>
						<td>' . $key->debet_giro . '</td>
						<td>' . $key->credit_tunai . '</td>
						<td>' . $key->credit_giro . '</td>
						<td>' . $key->saldo_akhir_tunai . '</td>
						<td>' . $key->saldo_akhir_giro . '</td>					
					</tr>';
            }
        }
        $html_data .= '</tbody>        
	    </table>
        <table>
        <thead>
			<tr>
				<th class="text-center" colspan="4">TOTAL</th>
				<th class="text-center">' . $debet_tunai . '</th>
				<th class="text-center">' . $debet_giro . '</th>
				<th class="text-center">' . $credit_tunai . '</th>
				<th class="text-center">' . $credit_giro . '</th>
				<th class="text-center">' . $saldo_akhir_tunai . '</th>
				<th class="text-center">' . $saldo_akhir_giro . '</th>
			</tr>
		</thead>
        </table>';


        $nama_file = "IKHP . $e_area_name . $e_company_name ";
        $nama_file .= ".xls";
        $export_excel1 = 'ABC';
        /*if ($export_excel1 != 'f')
				   $nama_file.= ".xls";
			   else
				   $nama_file.= ".ods";*/
        $data = $html_data;
        $this->logger->write('Export Data ' . $this->title);
        $dir = getcwd();
        include($dir . "/application/libraries/GenerateExcelFile.php");
        return true;
    }


    public function print()
	{
		/** Cek Hak Akses, Apakah User Bisa Print */
		// $data = check_role($this->id_menu, 1);
		// if (!$data) {
		// 	redirect(base_url(), 'refresh');
		// }

		add_css(
			array(
				/* 'app-assets/css/pages/app-invoice.css', */
				'app-assets/css/bootstrap.min.css',
				'app-assets/css/bootstrap-extended.min.css',
				'app-assets/css/colors.min.css',
				'app-assets/css/components.min.css',
			)
		);

		add_js(
			array(
				'app-assets/vendors/js/vendors.min.js',
				// 'assets/js/' . $this->folder . '/print.js',
			)
		);


		$dfrom = $this->input->get('d_from', TRUE);
		$dto = $this->input->get('d_to', TRUE);
		$i_area = $this->input->get('i_area', TRUE);
		if (strlen($dfrom) != 10) {
			$dfrom = decrypt_url($dfrom);
		}
		if (strlen($dto) != 10) {
			$dto = decrypt_url($dto);
		}
		if (strlen($i_area) > 10) {
			$i_area = decrypt_url($i_area);
		}

        // var_dump($dfrom, $dto, $i_area);
		if ($i_area != '0') {
			$e_area_name = $this->db->get_where('tr_area', ['f_area_active' => true, 'i_company' => $this->i_company, 'i_area' => $i_area])->row()->e_area_name;
		} else {
			$e_area_name = 'Nasional';
		}
		$data = array(
			'dfrom'     		=> date('d-m-Y', strtotime($dfrom)),
			'dto'       		=> date('d-m-Y', strtotime($dto)),
			'i_area'            => $i_area,
			'e_area_name'       => ucwords(strtolower($e_area_name)),
			'data'              => $this->mymodel->serverside2($dfrom, $dto, $i_area),
			'company' 	        => $this->db->get_where('tr_company', ['f_company_active' => true, 'i_company' => $this->i_company])->row(),
		);
		$this->logger->write('Membuka Form Cetak ' . $this->title);
		$this->load->view($this->folder . '/print', $data);
		/* $this->template->load('main', $this->folder . '/print', $data); */
	}
}
