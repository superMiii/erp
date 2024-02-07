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

class Infonotaak extends CI_Controller
{
    public $id_menu = '90412';

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
        $i_area = $this->input->post('i_area', TRUE);
        if ($i_area == '') {
            $i_area = $this->uri->segment(5);
            if ($i_area == '') {
                $i_area = 'NA';
            }
        }
        // $i_customer = $this->input->post('i_customer', TRUE);
        // if ($i_customer == '') {
        //     $i_customer = $this->uri->segment(6);
        //     if ($i_customer == '') {
        //         $i_customer = 'ALL';
        //     }
        // }
        if ($i_area != 'NA') {
            $e_area_name = $this->db->get_where('tr_area', ['f_area_active' => true, 'i_company' => $this->i_company, 'i_area' => $i_area])->row()->e_area_name;
        } else {
            $e_area_name = 'Nasional';
        }
        // if ($i_customer != 'ALL') {
        //     $e_customer_name = $this->db->get_where('tr_customer', ['f_customer_active' => true, 'i_company' => $this->i_company, 'i_customer' => $i_customer])->row()->e_customer_name;
        // } else {
        //     $e_customer_name = 'Semua';
        // }
        $data = array(
            'dfrom'             => date('d-m-Y', strtotime($dfrom)),
            'dto'               => date('d-m-Y', strtotime($dto)),
            'i_area'            => $i_area,
            'e_area_name'       => ucwords(strtolower($e_area_name)),
            'data' => $this->mymodel->get_data($dfrom, $dto, $i_area),
            // 'i_customer'        => $i_customer,
            // 'e_customer_name'   => ucwords(strtolower($e_customer_name)),
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
            'id'   => 'NA',
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
    // public function get_customer()
    // {
    //     $filter = [];
    //     $filter[] = array(
    //         'id'   => 'ALL',
    //         'text' => 'Semua',
    //     );
    //     $cari   = str_replace("'", "", $this->input->get('q'));
    //     $i_area = $this->input->get('i_area');
    //     if ($i_area != '') {
    //         $data = $this->mymodel->get_customer($cari, $i_area);
    //         foreach ($data->result() as $row) {
    //             $filter[] = array(
    //                 'id'   => $row->i_customer,
    //                 'text' => $row->i_customer_id . ' - ' . $row->e_customer_name,
    //             );
    //         }
    //     } else {
    //         $filter[] = array(
    //             'id'   => null,
    //             'text' => $this->lang->line('Pilih') . ' ' . $this->lang->line('Area'),
    //         );
    //     }
    //     echo json_encode($filter);
    // }

    /** Export Data */
    public function export()
    {
        /** Parameter From Ajax Post */
        $dfrom      = $this->input->post('dfrom', TRUE);
        $dto        = $this->input->post('dto', TRUE);
        $i_area     = $this->input->post('i_area', TRUE);
        // $i_customer = $this->input->post('i_customer', TRUE);
        if ($i_area != 'NA') {
            $e_area_name = $this->db->get_where('tr_area', ['f_area_active' => true, 'i_company' => $this->i_company, 'i_area' => $i_area])->row()->e_area_name;
        } else {
            $e_area_name = 'Nasional';
        }

        // if ($i_customer != 'ALL') {
        //     $e_customer_name = $this->db->get_where('tr_customer', ['f_customer_active' => true, 'i_company' => $this->i_company, 'i_customer' => $i_customer])->row()->e_customer_name;
        // } else {
        //     $e_customer_name = 'Semua';
        // }

        $e_company_name = $this->db->get_where('tr_company', ['f_company_active' => true, 'i_company' => $this->i_company])->row()->e_company_name;

        /** Query Get Data */
        $query      = $this->mymodel->get_data($dfrom, $dto, $i_area);

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
        $spreadsheet->getActiveSheet()->mergeCells("A1:R1");
        $spreadsheet->getActiveSheet()->mergeCells("A2:R2");
        $spreadsheet->getActiveSheet()->mergeCells("A3:R3");
        $h = 5;
        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A1', $this->title . " - " . $e_company_name)
            ->setCellValue('A2', "Area : " . ucwords(strtolower($e_area_name)))
            ->setCellValue('A3', "Periode : $dfrom s/d $dto")
            ->setCellValue('A' . $h, $this->lang->line('No'))
            ->setCellValue('B' . $h, $this->lang->line('No Nota'))
            ->setCellValue('C' . $h, $this->lang->line('Tgl Nota'))
            ->setCellValue('D' . $h, $this->lang->line('TOP'))
            ->setCellValue('E' . $h, $this->lang->line('Toleransi'))
            ->setCellValue('F' . $h, $this->lang->line('Jatuh Tempo'))
            ->setCellValue('G' . $h, $this->lang->line('Kode Pelanggan'))
            ->setCellValue('H' . $h, $this->lang->line('Nama Pelanggan'))
            ->setCellValue('I' . $h, $this->lang->line('Nama Pramuniaga'))
            ->setCellValue('J' . $h, $this->lang->line('Faktur Komersial'))
            ->setCellValue('K' . $h, $this->lang->line('No Seri Pajak'))
            ->setCellValue('L' . $h, $this->lang->line('Tanggal Pajak'))
            ->setCellValue('M' . $h, $this->lang->line('Nilai Kotor'))
            ->setCellValue('N' . $h, $this->lang->line('PPN'))
            ->setCellValue('O' . $h, $this->lang->line('Diskon Rp'))
            ->setCellValue('P' . $h, $this->lang->line('Nilai Bersih'))
            ->setCellValue('Q' . $h, $this->lang->line('Kode Area'))
            ->setCellValue('R' . $h, $this->lang->line('Nama Area Provinsi'));

        $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, 'A1:R4');
        $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle1, 'A' . $h . ':R' . $h);

        if ($query->num_rows() > 0) {
            $i = 6;
            $x = 6;
            $nomor = 1;
            foreach ($query->result() as $row) {
                $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $nomor)
                    ->setCellValue('B' . $i, $row->i_nota_id)
                    ->setCellValue('C' . $i, Date::PHPToExcel($row->d_nota))
                    ->setCellValue('D' . $i, $row->n_customer_top)
                    ->setCellValue('E' . $i, $row->n_toleransi)
                    ->setCellValue('F' . $i, Date::PHPToExcel($row->d_jatuh_tempo))
                    ->setCellValue('G' . $i, $row->i_customer_id)
                    ->setCellValue('H' . $i, $row->e_customer_name)
                    ->setCellValue('I' . $i, $row->e_salesman_name)
                    ->setCellValue('J' . $i, $row->i_faktur_komersial)
                    ->setCellValue('K' . $i, $row->i_seri_pajak)
                    ->setCellValue('L' . $i, Date::PHPToExcel($row->d_pajak))
                    ->setCellValue('M' . $i, $row->v_nota_gross)
                    ->setCellValue('N' . $i, $row->v_nota_ppn)
                    ->setCellValue('O' . $i, $row->v_nota_discount)
                    ->setCellValue('P' . $i, $row->v_nota_netto)
                    ->setCellValue('Q' . $i, $row->i_area_id)
                    ->setCellValue('R' . $i, $row->e_area_name);
                $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle2, 'A' . $i . ':R' . $i);
                $i++;
                $nomor++;
            }
            $spreadsheet->getActiveSheet()
                ->getStyle('C' . $x . ':C' . $i)
                ->getNumberFormat()
                ->setFormatCode(NumberFormat::FORMAT_DATE_DDMMYYYY);
            $spreadsheet->getActiveSheet()
                ->getStyle('F' . $x . ':F' . $i)
                ->getNumberFormat()
                ->setFormatCode(NumberFormat::FORMAT_DATE_DDMMYYYY);
            $spreadsheet->getActiveSheet()
                ->getStyle('L' . $x . ':L' . $i)
                ->getNumberFormat()
                ->setFormatCode(NumberFormat::FORMAT_DATE_DDMMYYYY);
            $spreadsheet->getActiveSheet()->mergeCells('A' . $i . ':K' . $i);
            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle2, 'A' . $i . ':O' . $i);
            $spreadsheet->getActiveSheet()->setCellValue('A' . $i, 'TOTAL');
            $spreadsheet->getActiveSheet()->setCellValue('M' . $i, "=SUM(M" . $x . ":M" . ($i - 1) . ")");
            $spreadsheet->getActiveSheet()->setCellValue('N' . $i, "=SUM(N" . $x . ":N" . ($i - 1) . ")");
            $spreadsheet->getActiveSheet()->setCellValue('O' . $i, "=SUM(O" . $x . ":O" . ($i - 1) . ")");
            $spreadsheet->getActiveSheet()->setCellValue('P' . $i, "=SUM(P" . $x . ":P" . ($i - 1) . ")");
            $spreadsheet->getActiveSheet()
                ->getStyle('M' . $x . ':P' . $i)
                ->getNumberFormat()
                ->setFormatCode(NumberFormat::FORMAT_CURRENCY_IDR);
        }

        $writer = new Xls($spreadsheet);
        $nama_file = $this->title . " - " . $e_company_name  . "_" . $dfrom . "_sd_" . $dto . ".xls";
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
