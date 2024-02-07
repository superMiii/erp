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

class Infospbsjnota extends CI_Controller
{
    public $id_menu = '90431';

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
                'assets/js/' . $this->folder . '/index.js?v=2',
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
                $i_area = '0';
            }
        }
        
        $rmu = $this->input->post('rmu', TRUE);
        if ($rmu == '') {
            $rmu = $this->uri->segment(6);
            if ($rmu == '') {
                $rmu = '1';
            }
        }

        if (strlen($dfrom) != 10) {
            $dfrom = decrypt_url($dfrom);
        }
        if (strlen($dto) != 10) {
            $dto = decrypt_url($dto);
        }
        if (strlen($i_area) > 10) {
            $i_area = decrypt_url($i_area);
        }
        if (strlen($rmu) > 10) {
            $rmu = decrypt_url($rmu);
        }

        if ($i_area != '0') {
            $e_area_name = $this->db->get_where('tr_area', ['f_area_active' => true, 'i_company' => $this->i_company, 'i_area' => $i_area])->row()->e_area_name;
        } else {
            $e_area_name = '( Semua )';
        }

        $data = array(
            'dfrom'             => date('d-m-Y', strtotime($dfrom)),
            'dto'               => date('d-m-Y', strtotime($dto)),
            'i_area'           => $i_area,
            'e_area_name'      => ucwords(strtolower($e_area_name)),
            'rmu'               => $rmu,
            'data' => $this->mymodel->get_data($dfrom, $dto, $i_area, $rmu),
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

    /** Redirect ke Form View */
    public function view()
    {
        /** Cek Hak Akses, Apakah User Bisa View */
        $data = check_role($this->id_menu, 2);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }
        add_js(
            array(
                'assets/js/' . $this->folder . '/index.js',
            )
        );

        $i_so           = decrypt_url($this->uri->segment(3));
        $i_company      = decrypt_url($this->uri->segment(4));
        $dfrom          = decrypt_url($this->uri->segment(5));
        $dto            = decrypt_url($this->uri->segment(6));
        $i_area         = decrypt_url($this->uri->segment(7));
        $rmu            = decrypt_url($this->uri->segment(8));

        if ($i_area != '0') {
            $e_area_name = $this->db->get_where('tr_area', ['f_area_active' => true, 'i_company' => $this->i_company, 'i_area' => $i_area])->row()->e_area_name;
        } else {
            $e_area_name = '( Semua )';
        }

        if ($rmu == '') {
            $rmu = $this->uri->segment(6);
            if ($rmu == '') {
                $rmu = '1';
            }
        }

        $i_so_id = $this->db->get_where('tm_so', ['i_so' => $i_so])->row()->i_so_id;
        $data = array(
            'detail'           => $this->mymodel->get_data_detail($i_so),
            'dfrom'            => $dfrom,
            'dto'              => $dto,
            'i_area'           => $i_area,
            'e_area_name'      => ucwords(strtolower($e_area_name)),
            'i_so_id'           => $i_so_id,
            'rmu'               => $rmu,
        );
        $this->logger->write('Membuka Form View ' . $this->title);
        $this->template->load('main', $this->folder . '/view', $data);
    }

    /** Export Data */
    public function export()
    {
        
        
        /** Parameter From Ajax Post */
        $dfrom      = $this->input->post('dfrom', TRUE);
        $dto        = $this->input->post('dto', TRUE);
        $i_area    = $this->input->post('i_area', TRUE);
        $rmu    = $this->input->post('rmu', TRUE);

        // var_dump($i_area,$dto ,$dfrom);
        // die;


        if ($i_area != '0') {
            $e_area_name = $this->db->get_where('tr_area', ['f_area_active' => true, 'i_company' => $this->i_company, 'i_area' => $i_area])->row()->e_area_name;
        } else {
            $e_area_name = '( Semua Area )';
        }
        $e_company_name = $this->db->get_where('tr_company', ['f_company_active' => true, 'i_company' => $this->i_company])->row()->e_company_name;


        // var_dump($dfrom, $dto, $i_area);
        // var_dump($i_area,$dto ,$dfrom,$e_company_name,$e_area_name);
        // die;


        /** Query Get Data */
        $query      = $this->mymodel->get_data($dfrom, $dto, $i_area, $rmu);

        // var_dump($i_area,$dto ,$dfrom,$e_company_name,$e_area_name, $query);
        // die;

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
        $spreadsheet->getActiveSheet()->mergeCells("A1:M1");
        $spreadsheet->getActiveSheet()->mergeCells("A2:M2");
        $spreadsheet->getActiveSheet()->mergeCells("A3:M3");
        $spreadsheet->getActiveSheet()->mergeCells("A4:M4");
        $h = 6;
        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A1', $this->title)
            ->setCellValue('A2', $e_company_name)
            ->setCellValue('A3', ucwords(strtolower($e_area_name)))
            ->setCellValue('A4', "Periode : $dfrom s/d $dto")
            ->setCellValue('A' . $h, $this->lang->line('No'))
            ->setCellValue('B' . $h, $this->lang->line('Nama Area Provinsi'))
            ->setCellValue('C' . $h, $this->lang->line('Kode Pelanggan'))
            ->setCellValue('D' . $h, $this->lang->line('Nama Pelanggan'))
            ->setCellValue('E' . $h, $this->lang->line('Pemenuhan'))
            ->setCellValue('F' . $h, $this->lang->line('No SPB'))
            ->setCellValue('G' . $h, $this->lang->line('Tgl SPB'))
            ->setCellValue('H' . $h, $this->lang->line('Jumlah SPB'))
            ->setCellValue('I' . $h, "Jumlah SPB ACC")
            ->setCellValue('J' . $h, $this->lang->line('No SJ'))
            ->setCellValue('K' . $h, $this->lang->line('Tgl SJ'))
            ->setCellValue('L' . $h, $this->lang->line('Jumlah SJ'))
            ->setCellValue('M' . $h, $this->lang->line('Jumlah Nota'));

        $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, 'A1:M4');
        $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle1, 'A' . $h . ':M' . $h);

        if ($query->num_rows() > 0) {
            $i = 7;
            $x = 7;
            $nomor = 1;
            foreach ($query->result() as $row) {
                $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $nomor)
                    ->setCellValue('B' . $i, $row->ara)
                    ->setCellValue('C' . $i, $row->i_customer_id)
                    ->setCellValue('D' . $i, $row->e_customer_name)
                    ->setCellValue('E' . $i, $row->pst)
                    ->setCellValue('F' . $i, $row->i_so_id)
                    ->setCellValue('G' . $i, Date::PHPToExcel($row->d_so))
                    ->setCellValue('H' . $i, $row->vso)
                    ->setCellValue('I' . $i, $row->vrmu)
                    ->setCellValue('J' . $i, $row->i_do_id)
                    ->setCellValue('K' . $i, Date::PHPToExcel($row->d_do))
                    ->setCellValue('L' . $i, $row->vdo)
                    ->setCellValue('M' . $i, $row->vnota);
                $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle2, 'A' . $i . ':M' . $i);
                $i++;
                $nomor++;
            }
            $spreadsheet->getActiveSheet()
                ->getStyle('G' . $x . ':G' . $i)
                ->getNumberFormat()
                ->setFormatCode(NumberFormat::FORMAT_DATE_DDMMYYYY);
                $spreadsheet->getActiveSheet()
                    ->getStyle('K' . $x . ':K' . $i)
                    ->getNumberFormat()
                    ->setFormatCode(NumberFormat::FORMAT_DATE_DDMMYYYY);
            $spreadsheet->getActiveSheet()->mergeCells('A' . $i . ':G' . $i);
            $spreadsheet->getActiveSheet()->mergeCells('J' . $i . ':K' . $i);
            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle1, 'A' . $i . ':M' . $i);
            $spreadsheet->getActiveSheet()->setCellValue('A' . $i, 'TOTAL');
            $spreadsheet->getActiveSheet()->setCellValue('H' . $i, "=SUM(H" . $x . ":H" . ($i - 1) . ")");
            $spreadsheet->getActiveSheet()->setCellValue('I' . $i, "=SUM(I" . $x . ":I" . ($i - 1) . ")");
            $spreadsheet->getActiveSheet()->setCellValue('L' . $i, "=SUM(L" . $x . ":L" . ($i - 1) . ")");
            $spreadsheet->getActiveSheet()->setCellValue('M' . $i, "=SUM(M" . $x . ":M" . ($i - 1) . ")");
            $spreadsheet->getActiveSheet()
                ->getStyle('H' . $x . ':I' . $i)
                ->getNumberFormat()
                ->setFormatCode(NumberFormat::FORMAT_CURRENCY_IDR);
                $spreadsheet->getActiveSheet()
                    ->getStyle('L' . $x . ':M' . $i)
                    ->getNumberFormat()
                    ->setFormatCode(NumberFormat::FORMAT_CURRENCY_IDR);
        }

        $writer = new Xls($spreadsheet);
        $nama_file = "SPB SJ NOTA" . " - " . $e_company_name . "_" . $dfrom . "_sd_" . $dto . ".xls";
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
