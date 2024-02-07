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

class Infospmbacc extends CI_Controller
{
	public $id_menu = '90310';

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
		$this->folder         = $data->e_folder;
		$this->title        = $data->e_menu;
		$this->icon            = $data->icon;
		$this->i_company     = $this->session->i_company;
		$this->i_user         = $this->session->i_user;

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
				$dfrom = date('Y-m') . '-01';
			}
		}
		$dto = $this->input->post('dto', TRUE);
		if ($dto == '') {
			$dto = $this->uri->segment(4);
			if ($dto == '') {
				$dto = date('Y-m-d');
			}
		}
		$i_store = $this->input->post('i_store', TRUE);
		if ($i_store == '') {
			$i_store = $this->uri->segment(5);
			if ($i_store == '') {
				$i_store = 'ALL';
			}
		}
        $i_product = $this->input->post('i_product', TRUE);
        if ($i_product == '') {
            $i_product = $this->uri->segment(6);
            if ($i_product == '') {
                $i_product = 'NA';
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
        if (strlen($i_product) > 10) {
            $i_product = decrypt_url($i_product);
        }

		if ($i_store != 'ALL') {
			$e_store_name = $this->db->get_where('tr_store', ['f_store_active' => true, 'f_store_pusat' => false, 'i_company' => $this->i_company, 'i_store' => $i_store])->row()->e_store_name;
		} else {
			$e_store_name = '( Semua )';
		}

        if ($i_product != 'NA') {
            $e_product_name = $this->db->get_where('tr_product', ['f_product_active' => true, 'i_company' => $this->i_company, 'i_product' => $i_product])->row()->e_product_name;
        } else {
            $e_product_name = 'Semua Barang';
        }

		$data = array(
			'dfrom'             => date('Y-m-d', strtotime($dfrom)),
			'dto'               => date('Y-m-d', strtotime($dto)),
			'i_store'           => $i_store,
			'e_store_name'      => ucwords(strtolower($e_store_name)),
            'i_product'         => $i_product,
            'e_product_name'    => ucwords(strtolower($e_product_name)),
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
    
    public function get_prod()
    {
        $filter = [];
        $filter[] = array(
            'id'   => 'NA',
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


	/** Export Data */
	public function export()
	{
		/** Parameter From Ajax Post */
		$dfrom      = $this->input->post('dfrom', TRUE);
		$dto        = $this->input->post('dto', TRUE);
        $i_product     = $this->input->post('i_product', TRUE);
		$i_store    = $this->input->post('i_store', TRUE);

		if ($i_store != 'ALL') {
			$e_store_name = $this->db->get_where('tr_store', ['f_store_active' => true, 'f_store_pusat' => false, 'i_company' => $this->i_company, 'i_store' => $i_store])->row()->e_store_name;
		} else {
			$e_store_name = '( Semua Gudang )';
		}

        if ($i_product != 'NA') {
            $e_product_name = $this->db->get_where('tr_product', ['f_product_active' => true, 'i_company' => $this->i_company, 'i_product' => $i_product])->row()->e_product_name;
        } else {
            $e_product_name = 'Semua Barang';
        }

		$e_company_name = $this->db->get_where('tr_company', ['f_company_active' => true, 'i_company' => $this->i_company])->row()->e_company_name;

		/** Query Get Data */
		$query      = $this->mymodel->get_data($dfrom, $dto, $i_store, $i_product);

		/** Style And Create New Spreedsheet */
		$spreadsheet  = new Spreadsheet;
		$sharedStyle1 = new Style();
		$sharedStyle2 = new Style();
		$sharedStyle3 = new Style();

		$spreadsheet->getActiveSheet()->getStyle('B2')->getAlignment()->applyFromArray(
			[
				'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
				'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER, 'textRotation' => 0, 'wrapText' => TRUE
			]
		);

		$sharedStyle1->applyFromArray(
			[
				'fill' => [
					'fillType' => Fill::FILL_SOLID,
					'color' => ['rgb' => '00FFFF'],
				],
				'font' => [
					'name'  => 'Arial',
					'bold'  => true,
					'italic' => false,
					'size'  => 10
				],
				'alignment' => [
					'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
					'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
				],
				'borders' => [
					'top'    => ['borderStyle' => Border::BORDER_THIN],
					'bottom' => ['borderStyle' => Border::BORDER_THIN],
					'left'   => ['borderStyle' => Border::BORDER_THIN],
					'right'  => ['borderStyle' => Border::BORDER_THIN]
				],
			]
		);

		$sharedStyle2->applyFromArray(
			[
				'font' => [
					'name'  => 'Arial',
					'bold'  => false,
					'italic' => false,
					'size'  => 10
				],
				'borders' => [
					'top'    => ['borderStyle' => Border::BORDER_THIN],
					'bottom' => ['borderStyle' => Border::BORDER_THIN],
					'left'   => ['borderStyle' => Border::BORDER_THIN],
					'right'  => ['borderStyle' => Border::BORDER_THIN]
				],
				'alignment' => [
					'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
				],
			]
		);

		$sharedStyle3->applyFromArray(
			[
				'font' => [
					'name'  => 'Times New Roman',
					'bold'  => true,
					'italic' => false,
					'size'  => 12
				],
				'alignment' => [
					'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
					'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
				],
			]
		);
		$spreadsheet->getDefaultStyle()
			->getFont()
			->setName('Calibri')
			->setSize(9);
		/* foreach (range('A', 'K') as $columnID) {
            $spreadsheet->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
        } */
		$spreadsheet->getActiveSheet()->mergeCells("A1:J1");
		$spreadsheet->getActiveSheet()->mergeCells("A2:J2");
		$spreadsheet->getActiveSheet()->mergeCells("A3:J3");
		$spreadsheet->getActiveSheet()->mergeCells("A4:J4");
		$h = 6;
		$spreadsheet->setActiveSheetIndex(0)
			->setCellValue('A1', $this->title)
			->setCellValue('A2', $e_company_name)
			->setCellValue('A3', ucwords(strtolower($e_store_name)))
			->setCellValue('A4', "Periode : $dfrom s/d $dto")
			->setCellValue('A' . $h, $this->lang->line('No'))
			->setCellValue('B' . $h, $this->lang->line('No SPMB'))
			->setCellValue('C' . $h, $this->lang->line('Tgl SPMB'))
			->setCellValue('D' . $h, $this->lang->line('Nama Area Provinsi'))
			->setCellValue('E' . $h, $this->lang->line('Kode Barang'))
			->setCellValue('F' . $h, $this->lang->line('Nama Barang'))
			->setCellValue('G' . $h, $this->lang->line('Jml Pesan'))
			->setCellValue('H' . $h, $this->lang->line('Jml Acc'))
			->setCellValue('I' . $h, $this->lang->line('Realisasi'))
			->setCellValue('J' . $h, $this->lang->line('Harga'));

		$spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, 'A1:J4');
		$spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle1, 'A' . $h . ':J' . $h);

		if ($query->num_rows() > 0) {
			$i = 7;
			$x = 7;
			$nomor = 1;
			foreach ($query->result() as $row) {
				$spreadsheet->setActiveSheetIndex(0)
					->setCellValue('A' . $i, $nomor)
					->setCellValue('B' . $i, $row->i_sr_id)
					->setCellValue('C' . $i, $row->d_sr)
					->setCellValue('D' . $i, $row->ara)
					->setCellValue('E' . $i, $row->i_product_id)
					->setCellValue('F' . $i, $row->e_product_name)
					->setCellValue('G' . $i, $row->n_order)
					->setCellValue('H' . $i, $row->n_acc)
					->setCellValue('I' . $i, $row->n_deliver)
					->setCellValue('J' . $i, $row->v_unit_price);
				$spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle2, 'A' . $i . ':J' . $i);
				$i++;
				$nomor++;
			}
			$spreadsheet->getActiveSheet()
				->getStyle('C' . $x . ':C' . $i)
				->getNumberFormat()
				->setFormatCode(NumberFormat::FORMAT_DATE_DDMMYYYY);
			$spreadsheet->getActiveSheet()->mergeCells('A' . $i . ':F' . $i);
			$spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle1, 'A' . $i . ':J' . $i);
			$spreadsheet->getActiveSheet()->setCellValue('A' . $i, 'TOTAL');
			$spreadsheet->getActiveSheet()->setCellValue('G' . $i, "=SUM(G" . $x . ":G" . ($i - 1) . ")");
			$spreadsheet->getActiveSheet()->setCellValue('H' . $i, "=SUM(H" . $x . ":H" . ($i - 1) . ")");
			$spreadsheet->getActiveSheet()->setCellValue('I' . $i, "=SUM(I" . $x . ":I" . ($i - 1) . ")");
			$spreadsheet->getActiveSheet()->setCellValue('J' . $i, "=SUM(J" . $x . ":J" . ($i - 1) . ")");
			// $spreadsheet->getActiveSheet()
			// 	->getStyle('I' . $x . ':J' . $i)
			// 	->getNumberFormat()
			// 	->setFormatCode(NumberFormat::FORMAT_CURRENCY_IDR);
		}

		$writer = new Xls($spreadsheet);
		$nama_file = $this->title . " - " . $e_company_name . "_" . $dfrom . "_sd_" . $dto . ".xls";
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename=' . $nama_file . '');
		header('Cache-Control: max-age=0');
		ob_start();
		$writer->save('php://output');
		$exceldata = ob_get_contents();
		ob_end_clean();
		$response =  array(
			'file'      => "data:application/vnd.ms-excel;base64," . base64_encode($exceldata),
			'nama_file' => $nama_file,
		);

		die(json_encode($response));
	}
}
