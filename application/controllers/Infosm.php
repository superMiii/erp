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

class Infosm extends CI_Controller
{
    public $id_menu = '90311';

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
                'app-assets/css/plugins/forms/validation/form-validation.css',
                'app-assets/css/global.css',
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
                'app-assets/vendors/js/forms/validation/jqBootstrapValidation.js',
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
        $i_store = $this->input->post('i_store', TRUE);
        if ($i_store == '') {
            $i_store = $this->uri->segment(5);
            if ($i_store == '') {
                $i_store = '0';
            }
        }

        $i_store2 = $this->input->post('i_store2', TRUE);
        if ($i_store2 == '') {
            $i_store2 = $this->uri->segment(6);
            if ($i_store2 == '') {
                $i_store2 = '0';
            }
        }

        $i_store3 = $this->input->post('i_store3', TRUE);
        if ($i_store3 == '') {
            $i_store3 = $this->uri->segment(7);
            if ($i_store3 == '') {
                $i_store3 = '0';
            }
        }

        $i_store4 = $this->input->post('i_store4', TRUE);
        if ($i_store4 == '') {
            $i_store4 = $this->uri->segment(8);
            if ($i_store4 == '') {
                $i_store4 = '0';
            }
        }

        $i_store5 = $this->input->post('i_store5', TRUE);
        if ($i_store5 == '') {
            $i_store5 = $this->uri->segment(9);
            if ($i_store5 == '') {
                $i_store5 = '0';
            }
        }

        $i_store6 = $this->input->post('i_store6', TRUE);
        if ($i_store6 == '') {
            $i_store6 = $this->uri->segment(10);
            if ($i_store6 == '') {
                $i_store6 = '0';
            }
        }

        $i_store7 = $this->input->post('i_store7', TRUE);
        if ($i_store7 == '') {
            $i_store7 = $this->uri->segment(11);
            if ($i_store7 == '') {
                $i_store7 = '0';
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

        if ($i_store != '0') {
            $e_store_name = $this->db->get_where('tr_store', ['i_company' => $this->i_company, 'i_store' => $i_store])->row()->e_store_name;
        } else {
            $e_store_name = 'Pilih Gudang';
        }

        if ($i_store2 != '0') {
            $e_store_name2 = $this->db->get_where('tr_store', ['i_company' => $this->i_company, 'i_store' => $i_store2])->row()->e_store_name;
        } else {
            $e_store_name2 = 'Pilih Gudang';
        }

        if ($i_store3 != '0') {
            $e_store_name3 = $this->db->get_where('tr_store', ['i_company' => $this->i_company, 'i_store' => $i_store3])->row()->e_store_name;
        } else {
            $e_store_name3 = 'Pilih Gudang';
        }

        if ($i_store4 != '0') {
            $e_store_name4 = $this->db->get_where('tr_store', ['i_company' => $this->i_company, 'i_store' => $i_store4])->row()->e_store_name;
        } else {
            $e_store_name4 = 'Pilih Gudang';
        }

        if ($i_store5 != '0') {
            $e_store_name5 = $this->db->get_where('tr_store', ['i_company' => $this->i_company, 'i_store' => $i_store5])->row()->e_store_name;
        } else {
            $e_store_name5 = 'Pilih Gudang';
        }

        if ($i_store6 != '0') {
            $e_store_name6 = $this->db->get_where('tr_store', ['i_company' => $this->i_company, 'i_store' => $i_store6])->row()->e_store_name;
        } else {
            $e_store_name6 = 'Pilih Gudang';
        }

        if ($i_store7 != '0') {
            $e_store_name7 = $this->db->get_where('tr_store', ['i_company' => $this->i_company, 'i_store' => $i_store7])->row()->e_store_name;
        } else {
            $e_store_name7 = 'Pilih Gudang';
        }

        $data = array(
            'dfrom'             => date('d-m-Y', strtotime($dfrom)),
            'dto'               => date('d-m-Y', strtotime($dto)),
            'i_store'           => $i_store,
            'e_store_name'      => ucwords(strtolower($e_store_name)),
            'i_store2'           => $i_store2,
            'e_store_name2'      => ucwords(strtolower($e_store_name2)),
            'i_store3'           => $i_store3,
            'e_store_name3'      => ucwords(strtolower($e_store_name3)),
            'i_store4'           => $i_store4,
            'e_store_name4'      => ucwords(strtolower($e_store_name4)),
            'i_store5'           => $i_store5,
            'e_store_name5'      => ucwords(strtolower($e_store_name5)),
            'i_store6'           => $i_store6,
            'e_store_name6'      => ucwords(strtolower($e_store_name6)),
            'i_store7'           => $i_store7,
            'e_store_name7'      => ucwords(strtolower($e_store_name7)),
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
        $data = $this->mymodel->get_store(str_replace("'", "", $this->input->get('q')));
        foreach ($data->result() as $row) {
            $filter[] = array(
                'id'   => $row->i_store,
                'text' => $row->e_store_name,
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
        $i_store    = $this->input->post('i_store', TRUE);
        $i_store2   = $this->input->post('i_store2', TRUE);
        $i_store3   = $this->input->post('i_store3', TRUE);
        $i_store4   = $this->input->post('i_store4', TRUE);
        $i_store5   = $this->input->post('i_store5', TRUE);
        $i_store6   = $this->input->post('i_store6', TRUE);
        $i_store7   = $this->input->post('i_store7', TRUE);


        if ($i_store != '0') {
            $e_store_name = $this->db->get_where('tr_store', ['i_company' => $this->i_company, 'i_store' => $i_store])->row()->e_store_name;
        } else {
            $e_store_name = 'Pilih Gudang';
        }

        if ($i_store2 != '0') {
            $e_store_name2 = $this->db->get_where('tr_store', ['i_company' => $this->i_company, 'i_store' => $i_store2])->row()->e_store_name;
        } else {
            $e_store_name2 = 'Pilih Gudang';
        }

        if ($i_store3 != '0') {
            $e_store_name3 = $this->db->get_where('tr_store', ['i_company' => $this->i_company, 'i_store' => $i_store3])->row()->e_store_name;
        } else {
            $e_store_name3 = 'Pilih Gudang';
        }

        if ($i_store4 != '0') {
            $e_store_name4 = $this->db->get_where('tr_store', ['i_company' => $this->i_company, 'i_store' => $i_store4])->row()->e_store_name;
        } else {
            $e_store_name4 = 'Pilih Gudang';
        }

        if ($i_store5 != '0') {
            $e_store_name5 = $this->db->get_where('tr_store', ['i_company' => $this->i_company, 'i_store' => $i_store5])->row()->e_store_name;
        } else {
            $e_store_name5 = 'Pilih Gudang';
        }

        if ($i_store6 != '0') {
            $e_store_name6 = $this->db->get_where('tr_store', ['i_company' => $this->i_company, 'i_store' => $i_store6])->row()->e_store_name;
        } else {
            $e_store_name6 = 'Pilih Gudang';
        }

        if ($i_store7 != '0') {
            $e_store_name7 = $this->db->get_where('tr_store', ['i_company' => $this->i_company, 'i_store' => $i_store7])->row()->e_store_name;
        } else {
            $e_store_name7 = 'Pilih Gudang';
        }

        $e_company_name = $this->db->get_where('tr_company', ['f_company_active' => true, 'i_company' => $this->i_company])->row()->e_company_name;

        /** Query Get Data */
        $query      = $this->mymodel->get_data($dfrom, $dto, $i_store, $i_store2, $i_store3, $i_store4, $i_store5, $i_store6, $i_store7);

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
        $spreadsheet->getActiveSheet()->mergeCells("A1:L1");
        $spreadsheet->getActiveSheet()->mergeCells("A2:L2");
        $spreadsheet->getActiveSheet()->mergeCells("A3:L3");
        $h = 5;
        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A1', $this->title)
            ->setCellValue('A2', $e_company_name)
            ->setCellValue('A3', "Periode : $dfrom s/d $dto")
            ->setCellValue('A' . $h, $this->lang->line('No'))
            ->setCellValue('B' . $h, $this->lang->line('Kode Barang'))
            ->setCellValue('C' . $h, $this->lang->line('Nama Barang'))
            ->setCellValue('D' . $h, $this->lang->line('Harga Beli'))
            ->setCellValue('E' . $h, $this->lang->line('Pusat'))
            ->setCellValue('F' . $h, $e_store_name)
            ->setCellValue('G' . $h, $e_store_name2)
            ->setCellValue('H' . $h, $e_store_name3)
            ->setCellValue('I' . $h, $e_store_name4)
            ->setCellValue('J' . $h, $e_store_name5)
            ->setCellValue('K' . $h, $e_store_name6)
            ->setCellValue('L' . $h, $e_store_name7);

        $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, 'A1:L3');
        $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle1, 'A' . $h . ':L' . $h);

        if ($query->num_rows() > 0) {
            $i = 6;
            $x = 6;
            $nomor = 1;
            foreach ($query->result() as $row) {
                $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $nomor)
                    ->setCellValue('B' . $i, $row->i_product_id)
                    ->setCellValue('C' . $i, $row->e_product_name)
                    ->setCellValue('D' . $i, $row->hb)
                    ->setCellValue('E' . $i, $row->pusat)
                    ->setCellValue('F' . $i, $row->gudang1)
                    ->setCellValue('G' . $i, $row->gudang2)
                    ->setCellValue('H' . $i, $row->gudang3)
                    ->setCellValue('I' . $i, $row->gudang4)
                    ->setCellValue('J' . $i, $row->gudang5)
                    ->setCellValue('K' . $i, $row->gudang6)
                    ->setCellValue('L' . $i, $row->gudang7);
                $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle2, 'A' . $i . ':L' . $i);
                $i++;
                $nomor++;
            }
            // $spreadsheet->getActiveSheet()
            //     ->getStyle('E' . $x . ':E' . $i)
            //     ->getNumberFormat()
            //     ->setFormatCode(NumberFormat::FORMAT_DATE_DDMMYYYY);
            $spreadsheet->getActiveSheet()
                ->getStyle('D' . $x . ':D' . $i)
                ->getNumberFormat()
                ->setFormatCode(NumberFormat::FORMAT_CURRENCY_IDR);
            // $spreadsheet->getActiveSheet()->mergeCells('A' . $i . ':C' . $i);
            // $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle2, 'A' . $i . ':K' . $i);
            // $spreadsheet->getActiveSheet()->setCellValue('A' . $i, 'TOTAL');
            // $spreadsheet->getActiveSheet()->setCellValue('D' . $i, "=SUM(D" . $x . ":D" . ($i - 1) . ")");
            // $spreadsheet->getActiveSheet()->setCellValue('E' . $i, "=SUM(E" . $x . ":E" . ($i - 1) . ")");
            // $spreadsheet->getActiveSheet()->setCellValue('F' . $i, "=SUM(F" . $x . ":F" . ($i - 1) . ")");
            // $spreadsheet->getActiveSheet()->setCellValue('G' . $i, "=SUM(G" . $x . ":G" . ($i - 1) . ")");
            // $spreadsheet->getActiveSheet()->setCellValue('H' . $i, "=SUM(H" . $x . ":H" . ($i - 1) . ")");
            // $spreadsheet->getActiveSheet()->setCellValue('I' . $i, "=SUM(I" . $x . ":I" . ($i - 1) . ")");
            // $spreadsheet->getActiveSheet()->setCellValue('J' . $i, "=SUM(J" . $x . ":J" . ($i - 1) . ")");
            // $spreadsheet->getActiveSheet()->setCellValue('K' . $i, "=SUM(K" . $x . ":K" . ($i - 1) . ")");
        }

        $writer = new Xls($spreadsheet);
        $nama_file = str_replace(" ", "_", $this->title) . "_" . str_replace(" ", "_", $e_store_name) . ".xls";
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
