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

class Infocollspb extends CI_Controller
{
    public $id_menu = '90418';

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

        $year = $this->input->post('year', TRUE);
        if ($year == '') {
            $year = $this->uri->segment(3);
            if ($year == '') {
                $year = date('Y');
            }
        }
        $month = $this->input->post('month', TRUE);
        if ($month == '') {
            $month = $this->uri->segment(4);
            if ($month == '') {
                $month = date('m');
            }
        }
        $i_area = $this->input->post('i_area', TRUE);
        if ($i_area == '') {
            $i_area = $this->uri->segment(5);
            if ($i_area == '') {
                $i_area = '0';
            }
        }

        $i_salesman = $this->input->post('i_salesman', TRUE);
        if ($i_salesman == '') {
            $i_salesman = $this->uri->segment(6);
            if ($i_salesman == '') {
                $i_salesman = '0';
            }
        }

        if ($i_area != '0') {
            $e_area_name = $this->db->get_where('tr_area', ['f_area_active' => true, 'i_company' => $this->i_company, 'i_area' => $i_area])->row()->e_area_name;
        } else {
            $e_area_name = 'Nasional';
        }
        if ($i_salesman != '0') {
            $e_salesman_name = $this->db->get_where('tr_salesman', ['f_salesman_active' => true, 'i_company' => $this->i_company, 'i_salesman' => $i_salesman])->row()->e_salesman_name;
        } else {
            $e_salesman_name = 'Semua';
        }

        $data = array(
            'month'             => $month,
            'year'               => $year,
            'i_area'            => $i_area,
            'e_area_name'       => ucwords(strtolower($e_area_name)),
            'i_salesman'        => $i_salesman,
            'e_salesman_name'   => ucwords(strtolower($e_salesman_name)),
            'data' => $this->mymodel->get_data($month, $year, $i_area, $i_salesman),
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
        $filter[] = array(
            'id'   => '0',
            'text' => 'Nasional',
        );
        $data = $this->mymodel->get_area(str_replace("'", "", $this->input->get('q')));
        foreach ($data->result() as $row) {
            $filter[] = array(
                'id'   => $row->i_area,
                'text' => $row->i_area_id . ' - ' . $row->e_area_name,
            );
        }
        echo json_encode($filter);
    }

    /** Get Data Customer */
    public function get_salesman()
    {
        $filter = [];
        $filter[] = array(
            'id'   => '0',
            'text' => 'Semua',
        );
        $cari   = str_replace("'", "", $this->input->get('q'));
        $i_area = $this->input->get('i_area');
        if ($i_area != '') {
            $data = $this->mymodel->get_salesman($cari, $i_area);
            foreach ($data->result() as $row) {
                $filter[] = array(
                    'id'   => $row->i_salesman,
                    'text' => $row->i_salesman_id . ' - ' . $row->e_salesman_name,
                );
            }
        } else {
            $filter[] = array(
                'id'   => null,
                'text' => $this->lang->line('Pilih') . ' ' . $this->lang->line('Area'),
            );
        }
        echo json_encode($filter);
    }

    /** Export Data */
    public function export()
    {
        /** Parameter From Ajax Post */
        $month      = $this->input->post('month', TRUE);
        $year        = $this->input->post('year', TRUE);
        $i_area     = $this->input->post('i_area', TRUE);
        $i_salesman = $this->input->post('i_salesman', TRUE);

        if ($i_area != '0') {
            $e_area_name = $this->db->get_where('tr_area', ['f_area_active' => true, 'i_company' => $this->i_company, 'i_area' => $i_area])->row()->e_area_name;
        } else {
            $e_area_name = 'Nasional';
        }
        if ($i_salesman != '0') {
            $e_salesman_name = $this->db->get_where('tr_salesman', ['f_salesman_active' => true, 'i_company' => $this->i_company, 'i_salesman' => $i_salesman])->row()->e_salesman_name;
        } else {
            $e_salesman_name = 'Semua';
        }


        $i_periode = $year . $month;

        // if ($i_salesman != 'ALL') {
        //     $e_salesman_name = $this->db->get_where('tr_salesman', ['f_salesman_active' => true, 'i_company' => $this->i_company, 'i_salesman' => $i_salesman])->row()->e_customer_name;
        // } else {
        //     $e_salesman_name = 'Semua';
        // }

        $e_company_name = $this->db->get_where('tr_company', ['f_company_active' => true, 'i_company' => $this->i_company])->row()->e_company_name;

        /** Query Get Data */
        $query      = $this->mymodel->get_data($month, $year, $i_area, $i_salesman);

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
                    'color' => ['rgb' => 'DFF1D0'],
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
        $spreadsheet->getActiveSheet()->mergeCells("A1:T1");
        $spreadsheet->getActiveSheet()->mergeCells("A2:T2");
        $spreadsheet->getActiveSheet()->mergeCells("A3:T3");
        $spreadsheet->getActiveSheet()->mergeCells("A4:T4");
        $h = 6;
        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A1', $this->title . ' - ' . $e_company_name)
            ->setCellValue('A2', "Area : " . ucwords(strtolower($e_area_name)))
            ->setCellValue('A3', "Sales : " . ucwords(strtolower($e_salesman_name)))
            ->setCellValue('A4', "Tahun : $year")
            ->setCellValue('A' . $h, $this->lang->line('No'))
            ->setCellValue('B' . $h, $this->lang->line('Periode'))
            ->setCellValue('C' . $h, $this->lang->line('Nama Area Provinsi'))
            ->setCellValue('D' . $h, $this->lang->line('Nama Pramuniaga'))
            ->setCellValue('E' . $h, $this->lang->line('Target Penjualan'))
            ->setCellValue('F' . $h, $this->lang->line('SPB'))
            ->setCellValue('G' . $h, $this->lang->line('Retur'))
            ->setCellValue('H' . $h, $this->lang->line('Jumlah Nota'))
            ->setCellValue('I' . $h, $this->lang->line('%'))
            ->setCellValue('J' . $h, $this->lang->line('Insentif'))
            ->setCellValue('K' . $h, $this->lang->line('Pendingan'))
            ->setCellValue('L' . $h, $this->lang->line('%'))
            ->setCellValue('M' . $h, $this->lang->line('Target Tagihan'))
            ->setCellValue('N' . $h, $this->lang->line('Pelunasan Toko'))
            ->setCellValue('O' . $h, $this->lang->line('%'))
            ->setCellValue('P' . $h, $this->lang->line('Insentif'))
            ->setCellValue('Q' . $h, $this->lang->line('Nota>90 Hari'))
            ->setCellValue('R' . $h, $this->lang->line('%'))
            ->setCellValue('S' . $h, $this->lang->line('Total Insentif'))
            ->setCellValue('T' . $h, $this->lang->line('Status'));

        $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, 'A1:T4');
        $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle1, 'A' . $h . ':T' . $h);

        if ($query->num_rows() > 0) {
            $i = 7;
            $x = 7;
            $nomor = 1;
            foreach ($query->result() as $row) {
                $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $nomor)
                    ->setCellValue('B' . $i, $row->periode)
                    ->setCellValue('C' . $i, $row->e_area_name)
                    ->setCellValue('D' . $i, $row->e_salesman_name)
                    ->setCellValue('E' . $i, $row->target)
                    ->setCellValue('F' . $i, $row->so)
                    ->setCellValue('G' . $i, $row->retur)
                    ->setCellValue('H' . $i, $row->nota)
                    ->setCellValue('I' . $i, $row->persen1)
                    ->setCellValue('J' . $i, $row->insentif1)
                    ->setCellValue('K' . $i, $row->pendingan)
                    ->setCellValue('L' . $i, $row->persen2)
                    ->setCellValue('M' . $i, $row->sisa)
                    ->setCellValue('N' . $i, $row->realisasi)
                    ->setCellValue('O' . $i, $row->persen3)
                    ->setCellValue('P' . $i, $row->insentif2)
                    ->setCellValue('Q' . $i, $row->gohari)
                    ->setCellValue('R' . $i, $row->persen4)
                    ->setCellValue('S' . $i, $row->insentif3)
                    ->setCellValue('T' . $i, $row->stat);
                $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle2, 'A' . $i . ':T' . $i);
                $i++;
                $nomor++;
            }
            // $spreadsheet->getActiveSheet()
            //     ->getStyle('E' . $x . ':F' . $i)
            //     ->getNumberFormat()
            //     ->setFormatCode(NumberFormat::FORMAT_DATE_DDMMYYYY);
            $spreadsheet->getActiveSheet()->mergeCells('A' . $i . ':D' . $i);
            $spreadsheet->getActiveSheet()->mergeCells('I' . $i . ':L' . $i);
            $spreadsheet->getActiveSheet()->mergeCells('O' . $i . ':P' . $i);
            $spreadsheet->getActiveSheet()->mergeCells('R' . $i . ':T' . $i);
            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle2, 'A' . $i . ':T' . $i);
            $spreadsheet->getActiveSheet()->setCellValue('A' . $i, 'TOTAL');
            $spreadsheet->getActiveSheet()->setCellValue('E' . $i, "=SUM(E" . $x . ":E" . ($i - 1) . ")");
            $spreadsheet->getActiveSheet()->setCellValue('F' . $i, "=SUM(F" . $x . ":F" . ($i - 1) . ")");
            $spreadsheet->getActiveSheet()->setCellValue('G' . $i, "=SUM(G" . $x . ":G" . ($i - 1) . ")");
            $spreadsheet->getActiveSheet()->setCellValue('H' . $i, "=SUM(H" . $x . ":H" . ($i - 1) . ")");
            $spreadsheet->getActiveSheet()->setCellValue('M' . $i, "=SUM(M" . $x . ":M" . ($i - 1) . ")");
            $spreadsheet->getActiveSheet()->setCellValue('N' . $i, "=SUM(N" . $x . ":N" . ($i - 1) . ")");
            $spreadsheet->getActiveSheet()->setCellValue('Q' . $i, "=SUM(Q" . $x . ":Q" . ($i - 1) . ")");
            $spreadsheet->getActiveSheet()
                ->getStyle('E' . $x . ':H' . $i)
                ->getNumberFormat()
                ->setFormatCode(NumberFormat::FORMAT_CURRENCY_IDR);
            $spreadsheet->getActiveSheet()
                ->getStyle('M' . $x . ':N' . $i)
                ->getNumberFormat()
                ->setFormatCode(NumberFormat::FORMAT_CURRENCY_IDR);
            $spreadsheet->getActiveSheet()
                ->getStyle('Q' . $x . ':Q' . $i)
                ->getNumberFormat()
                ->setFormatCode(NumberFormat::FORMAT_CURRENCY_IDR);
        }

        $writer = new Xls($spreadsheet);
        $nama_file = $this->title . " - " . $e_company_name . "_" . $i_periode . ".xls";
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
