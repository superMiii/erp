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

class Cek3 extends CI_Controller
{
    public $id_menu = '112';

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
                'assets/js/' . $this->folder . '/index.js',
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
        $i_status_so = $this->input->post('i_status_so', TRUE);
        if ($i_status_so == '') {
            $i_status_so = $this->uri->segment(5);
            if ($i_status_so == '') {
                $i_status_so = 'ALL';
            }
        }
        if ($i_status_so != 'ALL') {
            $e_status_so_name = $this->db->get_where('tr_status_so', ['f_status_so_active' => true, 'i_status_so' => $i_status_so])->row()->e_status_so_name;
        } else {
            $e_status_so_name = '( Semua )';
        }
        $data = array(
            'dfrom'             => date('d-m-Y', strtotime($dfrom)),
            'dto'               => date('d-m-Y', strtotime($dto)),
            'i_status_so'            => $i_status_so,
            'e_status_so_name'       => ucwords(strtolower($e_status_so_name)),
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
    public function get_status_so()
    {
        $filter = [];
        $filter[] = array(
            'id'   => 'ALL',
            'text' => '( Semua )',
        );
        $data = $this->mymodel->get_status_so(str_replace("'", "", $this->input->get('q')));
        foreach ($data->result() as $row) {
            $filter[] = array(
                'id'   => $row->i_status_so,
                'text' => $row->e_status_so_name,
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
        $i_status_so     = $this->input->post('i_status_so', TRUE);
        // $i_customer = $this->input->post('i_customer', TRUE);
        if ($i_status_so != 'ALL') {
            $e_status_so_name = $this->db->get_where('tr_status_so', ['f_status_so_active' => true, 'i_status_so' => $i_status_so])->row()->e_status_so_name;
        } else {
            $e_status_so_name = '( Semua )';
        }
        // if ($i_customer != 'ALL') {
        //     $e_customer_name = $this->db->get_where('tr_customer', ['f_customer_active' => true, 'i_company' => $this->i_company, 'i_customer' => $i_customer])->row()->e_customer_name;
        // } else {
        //     $e_customer_name = 'Semua';
        // }

        $e_company_name = $this->db->get_where('tr_company', ['f_company_active' => true, 'i_company' => $this->i_company])->row()->e_company_name;

        /** Query Get Data */
        $query      = $this->mymodel->get_data($dfrom, $dto, $i_status_so);

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
        $spreadsheet->getActiveSheet()->mergeCells("A1:G1");
        $spreadsheet->getActiveSheet()->mergeCells("A2:G2");
        $spreadsheet->getActiveSheet()->mergeCells("A3:G3");
        $spreadsheet->getActiveSheet()->mergeCells("A4:G4");
        $h = 6;
        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A1', $this->title)
            ->setCellValue('A4', "$e_company_name")
            ->setCellValue('A2', "Status : " . ucwords(strtolower($e_status_so_name)))
            ->setCellValue('A3', "$dfrom s/d $dto")
            ->setCellValue('A' . $h, $this->lang->line('No'))
            ->setCellValue('B' . $h, $this->lang->line('Status'))
            ->setCellValue('C' . $h, $this->lang->line('SPB'))
            ->setCellValue('D' . $h, $this->lang->line('SJ'))
            ->setCellValue('E' . $h, $this->lang->line('Nota'))
            ->setCellValue('F' . $h, $this->lang->line('SPB'))
            ->setCellValue('G' . $h, $this->lang->line('Nota'));

        $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, 'A1:G4');
        $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle1, 'A' . $h . ':G' . $h);

        if ($query->num_rows() > 0) {
            $i = 7;
            $x = 7;
            $nomor = 1;
            foreach ($query->result() as $row) {
                $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $nomor)
                    ->setCellValue('B' . $i, $row->e_status_so_name)
                    ->setCellValue('C' . $i, $row->i_so_id)
                    ->setCellValue('D' . $i, $row->i_do_id)
                    ->setCellValue('E' . $i, $row->i_nota_id)
                    ->setCellValue('F' . $i, $row->vso)
                    ->setCellValue('G' . $i, $row->vnota);
                $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle2, 'A' . $i . ':G' . $i);
                $i++;
                $nomor++;
            }
            $spreadsheet->getActiveSheet()->mergeCells('A' . $i . ':E' . $i);
            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle2, 'A' . $i . ':G' . $i);
            $spreadsheet->getActiveSheet()->setCellValue('A' . $i, 'TOTAL');
            $spreadsheet->getActiveSheet()->setCellValue('F' . $i, "=SUM(F" . $x . ":F" . ($i - 1) . ")");
            $spreadsheet->getActiveSheet()->setCellValue('G' . $i, "=SUM(G" . $x . ":G" . ($i - 1) . ")");
            $spreadsheet->getActiveSheet()
                ->getStyle('F' . $x . ':G' . $i)
                ->getNumberFormat()
                ->setFormatCode(NumberFormat::FORMAT_CURRENCY_IDR);
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