<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Infobrgsj extends CI_Controller
{
	public $id_menu = '90318';

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
				'assets/js/' . $this->folder . '/index.js?v='.date('YmdHis'),
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
        $i_product = $this->input->post('i_product', TRUE);
        if ($i_product == '') {
            $i_product = $this->uri->segment(5);
            if ($i_product == '') {
                $i_product = '0';
            }
        }

        if ($i_product != '0') {
            $e_product_name = $this->db->get_where('tr_product', ['f_product_active' => true, 'i_company' => $this->i_company, 'i_product' => $i_product])->row()->e_product_name;
        } else {
            $e_product_name = 'Semua Barang';
        }

		$data = array(
			'hari'		=> $this->mymodel->get_group(),
			'dfrom'     => date('d-m-Y', strtotime($dfrom)),
			'dto'       => date('d-m-Y', strtotime($dto)),
            'i_product'         => $i_product,
            'e_product_name'    => ucwords(strtolower($e_product_name)),
			'data'      => $this->mymodel->serverside(),
		);

		$this->logger->write('Membuka Menu ' . $this->title);
		$this->template->load('main', $this->folder . '/index', $data);
	}

	
    public function get_prod()
    {
        $filter = [];
        $filter[] = array(
            'id'   => '0',
            'text' => 'Semua Barang',
        );
        $data = $this->mymodel->get_prod(str_replace("'", "", $this->input->get('q')));
        foreach ($data->result() as $row) {
            $filter[] = array(
                'id'   => $row->i_product,
                'text' => $row->i_product_id . ' - ' . $row->e_product_name,
            );
        }
        echo json_encode($filter);
    }

	function export()
	{
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
        $i_product = $this->input->post('i_product', TRUE);
        if ($i_product == '') {
            $i_product = $this->uri->segment(5);
            if ($i_product == '') {
                $i_product = '0';
            }
        }

        if ($i_product != '0') {
            $e_product_name = $this->db->get_where('tr_product', ['f_product_active' => true, 'i_company' => $this->i_company, 'i_product' => $i_product])->row()->e_product_name;
        } else {
            $e_product_name = 'Semua Barang';
        }
		// var_dump($i_product);
		// die;

		$data = $this->mymodel->serverside2($dfrom, $dto, $i_product);
		$hari = $this->mymodel->get_group();

		$html_data = '<table class="table table-xs display nowrap table-striped table-bordered" id="serverside" width="100%;">
		<thead>
			<tr>
				<th rowspan="2" class="text-center" width="5%;">No</th>
				<th class="text-center" rowspan="2">' . $this->lang->line('Kode') . '</th>
				<th class="text-center" rowspan="2">' . $this->lang->line('Nama Barang') . '</th>
				<th class="text-center" rowspan="2">' . $this->lang->line('Status') . '</th>
				<th class="text-center" colspan="' . $hari->num_rows() . '"> ' . $this->lang->line('Tanggal') . '</th>
				<th class="text-center" rowspan="2">' . $this->lang->line('Total') . '</th>
			</tr>
			<tr>';
		if ($hari->num_rows() > 0) {
			foreach ($hari->result() as $key) {
				$html_data .= '
				<th class="text-right">' . $key->hari . '</th>
				';
			}
		}
		$html_data .= '</tr>
		</thead>
		<tbody>';
		if ($data->num_rows() > 0) {
			$i = 1;
			foreach ($data->result() as $key) {
				$html_data .= '<tr>
						<td class="text-center">' . $i . '</td>
						<td>' . explode("|", $key->e_prod)[0] . '</td>
						<td>' . explode("|", $key->e_prod)[1] . '</td>
						<td>' . explode("|", $key->e_prod)[2] . '</td>';
				$x = 0;
				foreach (json_decode($key->krm) as $krm) {
					$x += $krm;
					if ($krm > 0) {
						$html_data .= '<td class="text-right"><strong>' . $krm . '</strong></td>';
					} else {
						$html_data .= '<td class="text-right">' . $krm . '</td>';
					}
				}
				$html_data .= '<td class="text-right"><strong>' . $x . '</strong></td>						
					</tr>';
				$i++;
			}
		}
		$html_data .= '</tbody>
	</table>';


		$nama_file = "SJ Penjualan Barang (PUSAT)";
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
}
