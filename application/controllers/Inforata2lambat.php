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

class Inforata2lambat extends CI_Controller
{
    public $id_menu = '90421';

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
                'app-assets/vendors/css/forms/icheck/icheck.css',
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
                'app-assets/vendors/js/forms/icheck/icheck.min.js',
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
                $i_area = 'ALL';
            }
        }

        $i_customer = $this->input->post('i_customer', TRUE);
        if ($i_customer == '') {
            $i_customer = $this->uri->segment(6);
            if ($i_customer == '') {
                $i_customer = 'ALL';
            }
        }

        if ($i_area != 'ALL') {
            $e_area_name = $this->db->get_where('tr_area', ['f_area_active' => true, 'i_company' => $this->i_company, 'i_area' => $i_area])->row()->e_area_name;
        } else {
            $e_area_name = '( Semua )';
        }

        if ($i_customer != 'ALL') {
            $e_customer_name = $this->db->get_where('tr_customer', ['i_company' => $this->i_company, 'i_customer' => $i_customer])->row()->e_customer_name;
        } else {
            $e_customer_name = '( Semua Pelaggan )';
        }

        $data = array(
            'dfrom'             => date('d-m-Y', strtotime($dfrom)),
            'dto'               => date('d-m-Y', strtotime($dto)),
            'i_area'           => $i_area,
            'e_area_name'      => ucwords(strtolower($e_area_name)),
            'i_customer'        => $i_customer,
            'e_customer_name'   => ucwords(strtolower($e_customer_name)),
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
    public function get_customer()
    {
        $filter = [];
        $filter[] = array(
            'id'   => 'ALL',
            'text' => '( Semua Pelaggan )',
        );
        $cari   = str_replace("'", "", $this->input->get('q'));
        $i_area = $this->input->get('i_area');
        if ($i_area != '') {
            $data = $this->mymodel->get_customer($cari, $i_area);
            foreach ($data->result() as $row) {
                $filter[] = array(
                    'id'   => $row->i_customer,
                    'text' => $row->i_customer_id . ' - ' . $row->e_customer_name,
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
        $dfrom      = $this->input->post('dfrom', TRUE);
        $dto        = $this->input->post('dto', TRUE);
        $i_area     = $this->input->post('i_area', TRUE);
        $i_customer = $this->input->post('i_customer', TRUE);
        if ($i_area != 'ALL') {
            $e_area_name = $this->db->get_where('tr_area', ['f_area_active' => true, 'i_company' => $this->i_company, 'i_area' => $i_area])->row()->e_area_name;
        } else {
            $e_area_name = '( Nasional )';
        }

        if ($i_customer != 'ALL') {
            $e_customer_name = $this->db->get_where('tr_customer', ['i_company' => $this->i_company, 'i_customer' => $i_customer])->row()->e_customer_name;
        } else {
            $e_customer_name = '( Semua Pelanggan )';
        }

        $e_company_name = $this->db->get_where('tr_company', ['f_company_active' => true, 'i_company' => $this->i_company])->row()->e_company_name;

        /** Query Get Data */
        $query      = $this->mymodel->get_data($dfrom, $dto, $i_area, $i_customer);

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
        $spreadsheet->getActiveSheet()->mergeCells("A1:O1");
        $spreadsheet->getActiveSheet()->mergeCells("A2:O2");
        $spreadsheet->getActiveSheet()->mergeCells("A3:O3");
        $spreadsheet->getActiveSheet()->mergeCells("A4:O4");
        $h = 6;
        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A1', $this->title . " - " . $e_company_name)
            ->setCellValue('A2', "Area : " . ucwords(strtolower($e_area_name)))
            ->setCellValue('A3', "Periode : $dfrom s/d $dto")
            ->setCellValue('A4', "Pelanggan : $e_customer_name")
            ->setCellValue('A' . $h, $this->lang->line('No'))
            ->setCellValue('B' . $h, $this->lang->line('Nama Area Provinsi'))
            ->setCellValue('C' . $h, $this->lang->line('Kode Pelanggan'))
            ->setCellValue('D' . $h, $this->lang->line('Nama Pelanggan'))
            ->setCellValue('E' . $h, $this->lang->line('No Nota'))
            ->setCellValue('F' . $h, $this->lang->line('Jumlah Nota'))
            ->setCellValue('G' . $h, $this->lang->line('Tgl Nota'))
            ->setCellValue('H' . $h, $this->lang->line('TOP'))
            ->setCellValue('I' . $h, $this->lang->line('Toleransi'))
            ->setCellValue('J' . $h, $this->lang->line('Tgl Jatuh Tempo + Toleransi'))
            ->setCellValue('K' . $h, $this->lang->line('Tgl Alokasi'))
            ->setCellValue('L' . $h, $this->lang->line('Jumlah Alokasi'))
            ->setCellValue('M' . $h, $this->lang->line('Jenis Pembayaran'))
            ->setCellValue('N' . $h, $this->lang->line('Keterangan'))
            ->setCellValue('O' . $h, $this->lang->line('Keterlambatan'));

        $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, 'A1:O5');
        $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle1, 'A' . $h . ':O' . $h);

        if ($query->num_rows() > 0) {
            $i = 7;
            $x = 7;
            $nomor = 1;
            $lama           = 0;
            foreach ($query->result() as $row) {
                $pecah1 = explode('-', $row->tempo_tol);
                $date1  = $pecah1[2];
                $month1 = $pecah1[1];
                $year1  = $pecah1[0];
                $jd1    = GregorianToJD($month1, $date1, $year1);
                $pecah2 = explode('-', $row->alo);
                $date2  = $pecah2[2];
                $month2 = $pecah2[1];
                $year2  = $pecah2[0];
                $jd2    = GregorianToJD($month2, $date2, $year2);
                $lama   = $jd2 - $jd1;
                $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $nomor)
                    ->setCellValue('B' . $i, $row->areaa)
                    ->setCellValue('C' . $i, $row->kodee)
                    ->setCellValue('D' . $i, $row->namee)
                    ->setCellValue('E' . $i, $row->i_nota_id)
                    ->setCellValue('F' . $i, $row->v_nota_netto)
                    ->setCellValue('G' . $i, Date::PHPToExcel($row->d_nota))
                    ->setCellValue('H' . $i, $row->n_customer_top)
                    ->setCellValue('I' . $i, $row->n_toleransi)
                    ->setCellValue('J' . $i, $row->tempo_tol)
                    ->setCellValue('K' . $i, $row->alo)
                    ->setCellValue('L' . $i, $row->v_jumlah)
                    ->setCellValue('M' . $i, $row->e_rv_refference_type_name)
                    ->setCellValue('N' . $i, $row->e_remark)
                    ->setCellValue('O' . $i, $lama);
                $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle2, 'A' . $i . ':O' . $i);
                if ($lama >= 0) {
                    $spreadsheet->getActiveSheet()->getStyle('A' . $i . ':O' . $i)->getFill()
                        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                        ->getStartColor()->setRGB('98FB98');
                } elseif ($lama < 0) {
                    $spreadsheet->getActiveSheet()->getStyle('A' . $i . ':O' . $i)->getFill()
                        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                        ->getStartColor()->setRGB('F0E68C');
                }
                $i++;
                $nomor++;
            }
            $spreadsheet->getActiveSheet()
                ->getStyle('G' . $x . ':G' . $i)
                ->getNumberFormat()
                ->setFormatCode(NumberFormat::FORMAT_DATE_DDMMYYYY);
            // $spreadsheet->getActiveSheet()->mergeCells('A' . $i . ':H' . $i);
            // $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle1, 'A' . $i . ':N' . $i);
            // $spreadsheet->getActiveSheet()->setCellValue('A' . $i, 'TOTAL');
            // $spreadsheet->getActiveSheet()->setCellValue('I' . $i, "=SUM(I" . $x . ":I" . ($i - 1) . ")");
            // $spreadsheet->getActiveSheet()->setCellValue('J' . $i, "=SUM(J" . $x . ":J" . ($i - 1) . ")");
            // $spreadsheet->getActiveSheet()->setCellValue('K' . $i, "=SUM(K" . $x . ":K" . ($i - 1) . ")");
            // $spreadsheet->getActiveSheet()->setCellValue('L' . $i, "=SUM(L" . $x . ":L" . ($i - 1) . ")");
            // $spreadsheet->getActiveSheet()->setCellValue('M' . $i, "=SUM(M" . $x . ":M" . ($i - 1) . ")");
            // $spreadsheet->getActiveSheet()->setCellValue('N' . $i, "=SUM(N" . $x . ":N" . ($i - 1) . ")");
            $spreadsheet->getActiveSheet()
                ->getStyle('F' . $x . ':F' . $i)
                ->getNumberFormat()
                ->setFormatCode(NumberFormat::FORMAT_CURRENCY_IDR);
                $spreadsheet->getActiveSheet()
                    ->getStyle('L' . $x . ':L' . $i)
                    ->getNumberFormat()
                    ->setFormatCode(NumberFormat::FORMAT_CURRENCY_IDR);
        }



        $writer = new Xls($spreadsheet);
        $nama_file = $this->title . " - " . $e_company_name . "_" . $e_area_name . "_" . $dto . ".xls";
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
