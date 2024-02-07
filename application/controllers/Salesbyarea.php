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

class Salesbyarea extends CI_Controller
{
    public $id_menu = '70103';

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
        $pro = $this->input->post('pro', TRUE);
        if ($pro == '') {
            $pro = $this->uri->segment(5);
            if ($pro == '') {
                $pro = '1';
            }
        }
        // $i_area = $this->input->post('i_area', TRUE);
        // if ($i_area == '') {
        //     $i_area = $this->uri->segment(5);
        //     if ($i_area == '') {
        //         $i_area = 'NA';
        //     }
        // }
        // $i_salesman = $this->input->post('i_salesman', TRUE);
        // if ($i_salesman == '') {
        //     $i_salesman = $this->uri->segment(6);
        //     if ($i_salesman == '') {
        //         $i_salesman = 'ALL';
        //     }
        // }
        // if ($i_area != 'NA') {
        //     $e_area_name = $this->db->get_where('tr_area', ['f_area_active' => true, 'i_company' => $this->i_company, 'i_area' => $i_area])->row()->e_area_name;
        // } else {
        //     $e_area_name = 'Nasional';
        // }
        // if ($i_salesman != 'ALL') {
        //     $e_salesman_name = $this->db->get_where('tr_salesman', ['f_salesman_active' => true, 'i_company' => $this->i_company, 'i_salesman' => $i_salesman])->row()->e_salesman_name;
        // } else {
        //     $e_salesman_name = 'SEMUA SALES';
        // }
        $data = array(
            'dfrom'             => date('d-m-Y', strtotime($dfrom)),
            'dto'               => date('d-m-Y', strtotime($dto)),
            'pro'               => $pro,
            // 'i_area'            => $i_area,
            // 'e_area_name'       => ucwords(strtolower($e_area_name)),
            // 'i_salesman'        => $i_salesman,
            // 'e_salesman_name'   => ucwords(strtolower($e_salesman_name)),
			'tahun' 			=> date('Y', strtotime($dfrom)),
            'data'              => $this->mymodel->get_data($dfrom, $dto, $pro),
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
    // public function get_area()
    // {
    //     $filter = [];
    //     $filter[] = array(
    //         'id'   => 'NA',
    //         'text' => 'Nasional',
    //     );
    //     $data = $this->mymodel->get_area(str_replace("'", "", $this->input->get('q')));
    //     foreach ($data->result() as $row) {
    //         $filter[] = array(
    //             'id'   => $row->i_area,
    //             'text' => $row->i_area_id . ' - ' . $row->e_area_name,
    //         );
    //     }
    //     echo json_encode($filter);
    // }

    // /** Get Data salesman */
    // public function get_salesman()
    // {
    //     $filter = [];
    //     $filter[] = array(
    //         'id'   => 'ALL',
    //         'text' => 'SEMUA SALES',
    //     );
	// 	$data = $this->mymodel->get_salesman(str_replace("'", "", $this->input->get('q')));
	// 	foreach ($data->result() as $row) {
	// 		$filter[] = array(
	// 			'id'   => $row->i_salesman,
	// 			'text' => $row->e_salesman_name,
	// 		);
	// 	}
       
    //     echo json_encode($filter);
    // }

    /** Export Data */
    public function export()
    {
        /** Parameter From Ajax Post */
        $dfrom      = $this->input->post('dfrom', TRUE);
        $dto        = $this->input->post('dto', TRUE);
        $pro        = $this->input->post('pro', TRUE);
        // $i_area     = $this->input->post('i_area', TRUE);
        // $i_salesman = $this->input->post('i_salesman', TRUE);
		$tahun 		= date('Y', strtotime($dfrom));
		$prevth 	= $tahun - 1;

        // if ($i_area != 'NA') {
        //     $e_area_name = $this->db->get_where('tr_area', ['f_area_active' => true, 'i_company' => $this->i_company, 'i_area' => $i_area])->row()->e_area_name;
        // } else {
        //     $e_area_name = 'Nasional';
        // }

        // if ($i_salesman != 'ALL') {
        //     $e_salesman_name = $this->db->get_where('tr_salesman', ['f_salesman_active' => true, 'i_company' => $this->i_company, 'i_salesman' => $i_salesman])->row()->e_salesman_name;
        // } else {
        //     $e_salesman_name = 'SEMUA SALES';
        // }

        $e_company_name = $this->db->get_where('tr_company', ['f_company_active' => true, 'i_company' => $this->i_company])->row()->e_company_name;

        if ($pro == '2') {
            $epro = "PULAU BALI";
        } elseif ($pro == '3') {
            $epro = "PULAU JAWA";
        } elseif ($pro == '4') {
            $epro = "PULAU KALIMANTAN";
        } elseif ($pro == '5') {
            $epro = "PULAU NUSA TENGGARA";
        } elseif ($pro == '6') {
            $epro = "PULAU PAPUA";
        } elseif ($pro == '7') {
            $epro = "PULAU SULAWESI";
        } elseif ($pro == '8') {
            $epro = "PULAU SUMATERA";
        } else {
            $epro = "Semua Pulau";
        }
        /** Query Get Data */
        $query      = $this->mymodel->get_data($dfrom, $dto, $pro);

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
        $spreadsheet->getActiveSheet()->mergeCells("A1:N1");
        $spreadsheet->getActiveSheet()->mergeCells("A2:N2");
        $spreadsheet->getActiveSheet()->mergeCells("A3:N3");
        $h = 5;
        $j = 6;
        $spreadsheet->getActiveSheet()->mergeCells("A$h:A$j");
        $spreadsheet->getActiveSheet()->mergeCells("B$h:B$j");
        $spreadsheet->getActiveSheet()->mergeCells("C$h:C$j");
        $spreadsheet->getActiveSheet()->mergeCells("D$h:D$j");
        $spreadsheet->getActiveSheet()->mergeCells("E$h:G$h");
        $spreadsheet->getActiveSheet()->mergeCells("H$h:J$h");
        $spreadsheet->getActiveSheet()->mergeCells("K$h:M$h");
        $spreadsheet->getActiveSheet()->mergeCells("N$h:N$j");
        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A1', $this->title . " - " . $e_company_name)
            ->setCellValue('A2', $epro)
            ->setCellValue('A3', "Periode : $dfrom s/d $dto")
            ->setCellValue('A' . $h, $this->lang->line('No'))
            ->setCellValue('B' . $h, $this->lang->line('Nama Area Provinsi'))
            ->setCellValue('C' . $h, $this->lang->line('Pulau'))
            ->setCellValue('D' . $h, "OB")
            ->setCellValue('E' . $h, "OA")
            ->setCellValue('E' . $j, "$prevth")
            ->setCellValue('F' . $j, "$tahun")
            ->setCellValue('G' . $j, "%")
            ->setCellValue('H' . $h, "Sales Qty(Unit)")
            ->setCellValue('H' . $j, "$prevth")
            ->setCellValue('I' . $j, "$tahun")
            ->setCellValue('J' . $j, "%")
            ->setCellValue('K' . $h, "Net Sales(Rp.)")
            ->setCellValue('K' . $j, "$prevth")
            ->setCellValue('L' . $j, "$tahun")
            ->setCellValue('M' . $j, "%")
            ->setCellValue('N' . $h, "%Ctr Net Sales(Rp.)");

        $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, 'A1:N4');
        $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle1, 'A' . $h . ':N' . $j);

        if ($query->num_rows() > 0) {
            $i = 7;
            $x = 7;
            $nomor = 1;
            foreach ($query->result() as $row) {
                $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $nomor)
                    ->setCellValue('B' . $i, $row->araa)
                    ->setCellValue('C' . $i, $row->e_area_island)
                    ->setCellValue('D' . $i, $row->ob)
                    ->setCellValue('E' . $i, $row->oa)
                    ->setCellValue('F' . $i, $row->oaa)
                    ->setCellValue('G' . $i, $row->p3)
                    ->setCellValue('H' . $i, $row->krm)
                    ->setCellValue('I' . $i, $row->krmm)
                    ->setCellValue('J' . $i, $row->p4)
                    ->setCellValue('K' . $i, $row->yui)
                    ->setCellValue('L' . $i, $row->yuii)
                    ->setCellValue('M' . $i, $row->p5)
                    ->setCellValue('N' . $i, $row->p6);
                $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle2, 'A' . $i . ':N' . $i);
                $i++;
                $nomor++;
            }
            // $spreadsheet->getActiveSheet()
            //     ->getStyle('F' . $x . ':F' . $i)
            //     ->getNumberFormat()
            //     ->setFormatCode(NumberFormat::FORMAT_DATE_DDMMYYYY);
            $spreadsheet->getActiveSheet()->mergeCells('A' . $i . ':C' . $i);
            $spreadsheet->getActiveSheet()->mergeCells('G' . $i . ':G' . $i);
            $spreadsheet->getActiveSheet()->mergeCells('J' . $i . ':J' . $i);
            $spreadsheet->getActiveSheet()->mergeCells('M' . $i . ':N' . $i);
            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle1, 'A' . $i . ':N' . $i);
            $spreadsheet->getActiveSheet()->setCellValue('A' . $i, 'TOTAL');
            $spreadsheet->getActiveSheet()->setCellValue('D' . $i, "=SUM(D" . $x . ":D" . ($i - 1) . ")");
            $spreadsheet->getActiveSheet()->setCellValue('E' . $i, "=SUM(E" . $x . ":E" . ($i - 1) . ")");
            $spreadsheet->getActiveSheet()->setCellValue('F' . $i, "=SUM(F" . $x . ":F" . ($i - 1) . ")");
            $spreadsheet->getActiveSheet()->setCellValue('H' . $i, "=SUM(H" . $x . ":H" . ($i - 1) . ")");
            $spreadsheet->getActiveSheet()->setCellValue('I' . $i, "=SUM(I" . $x . ":I" . ($i - 1) . ")");
            $spreadsheet->getActiveSheet()->setCellValue('K' . $i, "=SUM(K" . $x . ":K" . ($i - 1) . ")");
            $spreadsheet->getActiveSheet()->setCellValue('L' . $i, "=SUM(L" . $x . ":L" . ($i - 1) . ")");
            $spreadsheet->getActiveSheet()
                ->getStyle('K' . $x . ':L' . $i)
                ->getNumberFormat()
                ->setFormatCode(NumberFormat::FORMAT_CURRENCY_IDR);
            // $spreadsheet->getActiveSheet()
            //     ->getStyle('G' . $x . ':H' . $i)
            //     ->getNumberFormat()
            //     ->setFormatCode(NumberFormat::FORMAT_CURRENCY_IDR);
			// $spreadsheet->getActiveSheet()
			// 	->getStyle('Q' . $x . ':R' . $i)
			// 	->getNumberFormat()
			// 	->setFormatCode(NumberFormat::FORMAT_CURRENCY_IDR);
        }

        $writer = new Xls($spreadsheet);
        $nama_file = "Performa Penjualan Berdasarkan Area " . " - " . $e_company_name . " - " . $epro . ".xls";
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