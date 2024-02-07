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

class Inforinciansj extends CI_Controller
{
    public $id_menu = '90317';

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
                'assets/js/' . $this->folder . '/index.js?v=4',
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
                $i_product = 'NA';
            }
        }
        $i_customer = $this->input->post('i_customer', TRUE);
        if ($i_customer == '') {
            $i_customer = $this->uri->segment(6);
            if ($i_customer == '') {
                $i_customer = 'ALL';
            }
        }

        $pro = $this->input->post('pro', TRUE);
        if ($pro == '') {
            $pro = $this->uri->segment(7);
            if ($pro == '') {
                $pro = '1';
            }
        }


        if ($i_product != 'NA') {
            $e_product_name = $this->db->get_where('tr_product', ['f_product_active' => true, 'i_company' => $this->i_company, 'i_product' => $i_product])->row()->e_product_name;
        } else {
            $e_product_name = 'Semua Barang';
        }
        if ($i_customer != 'ALL') {
            $e_customer_name = $this->db->get_where('tr_customer', ['f_customer_active' => true, 'i_company' => $this->i_company, 'i_customer' => $i_customer])->row()->e_customer_name;
        } else {
            $e_customer_name = 'Semua Pelanggan';
        }

        $data = array(
            'dfrom'             => date('d-m-Y', strtotime($dfrom)),
            'dto'               => date('d-m-Y', strtotime($dto)),
            'i_product'         => $i_product,
            'e_product_name'    => ucwords(strtolower($e_product_name)),
            'i_customer'        => $i_customer,
            'e_customer_name'   => ucwords(strtolower($e_customer_name)),            
            'pro'               => $pro,
            'data'              => $this->mymodel->get_data($dfrom, $dto, $i_product, $i_customer, $pro),
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

    /** Get Data Customer */
    public function get_customer()
    {
        $filter = [];
        $filter[] = array(
            'id'   => 'ALL',
            'text' => 'Semua Pelanggan',
        );
        $cari   = str_replace("'", "", $this->input->get('q'));
        $i_product = $this->input->get('i_product');
        if ($i_product != '') {
            $data = $this->mymodel->get_customer($cari, $i_product);
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
        $i_product     = $this->input->post('i_product', TRUE);
        $i_customer = $this->input->post('i_customer', TRUE);
        $pro        = $this->input->post('pro', TRUE);
        

        if ($i_product != 'NA') {
            $e_product_name = $this->db->get_where('tr_product', ['f_product_active' => true, 'i_company' => $this->i_company, 'i_product' => $i_product])->row()->e_product_name;
        } else {
            $e_product_name = 'Semua Barang';
        }

        if ($i_customer != 'ALL') {
            $e_customer_name = $this->db->get_where('tr_customer', ['f_customer_active' => true, 'i_company' => $this->i_company, 'i_customer' => $i_customer])->row()->e_customer_name;
        } else {
            $e_customer_name = 'Semua Pelanggan';
        }
        

        $e_company_name = $this->db->get_where('tr_company', ['f_company_active' => true, 'i_company' => $this->i_company])->row()->e_company_name;

        /** Query Get Data */
        $query      = $this->mymodel->get_data($dfrom, $dto, $i_product, $i_customer);

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
        $spreadsheet->getActiveSheet()->mergeCells("A1:H1");
        $spreadsheet->getActiveSheet()->mergeCells("A2:H2");
        $spreadsheet->getActiveSheet()->mergeCells("A3:H3");
        $spreadsheet->getActiveSheet()->mergeCells("A4:H4");
        $h = 6;
        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A1', $this->title . " - " . $e_company_name)
            ->setCellValue('A2', "Periode : $dfrom s/d $dto")
            ->setCellValue('A3', "Pelanggan : $e_customer_name")
            ->setCellValue('A4', "Barang : " . ucwords(strtolower($e_product_name)))
            ->setCellValue('A' . $h, $this->lang->line('No'))
            ->setCellValue('B' . $h, $this->lang->line('No SJ'))
            ->setCellValue('C' . $h, $this->lang->line('Tgl SJ'))
            ->setCellValue('D' . $h, $this->lang->line('Pemenuhan'))
            ->setCellValue('E' . $h, $this->lang->line('Nama Pelanggan'))
            ->setCellValue('F' . $h, $this->lang->line('Kode Barang'))
            ->setCellValue('G' . $h, $this->lang->line('Nama Barang'))
            ->setCellValue('H' . $h, $this->lang->line('Jml Kirim'));

        $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, 'A1:H4');
        $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle1, 'A' . $h . ':H' . $h);

        if ($query->num_rows() > 0) {
            $i = 7;
            $x = 7;
            $nomor = 1;
            foreach ($query->result() as $row) {
                $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $nomor)
                    ->setCellValue('B' . $i, $row->i_do_id)
                    ->setCellValue('C' . $i, $row->tgl)
                    ->setCellValue('D' . $i, $row->pemenuhan)
                    ->setCellValue('E' . $i, $row->namee)
                    ->setCellValue('F' . $i, $row->i_product_id)
                    ->setCellValue('G' . $i, $row->e_product_name)
                    ->setCellValue('H' . $i, $row->n_deliver);
                $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle2, 'A' . $i . ':H' . $i);
                $i++;
                $nomor++;
            }
            // $spreadsheet->getActiveSheet()
            //     ->getStyle('I' . $x . ':I' . $i)
            //     ->getNumberFormat()
            //     ->setFormatCode(NumberFormat::FORMAT_DATE_DDMMYYYY);
            $spreadsheet->getActiveSheet()->mergeCells('A' . $i . ':G' . $i);
            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle1, 'A' . $i . ':H' . $i);
            $spreadsheet->getActiveSheet()->setCellValue('A' . $i, 'TOTAL');
            $spreadsheet->getActiveSheet()->setCellValue('H' . $i, "=SUM(H" . $x . ":H" . ($i - 1) . ")");
            // $spreadsheet->getActiveSheet()
            //     ->getStyle('U' . $x . ':V' . $i)
            //     ->getNumberFormat()
            //     ->setFormatCode(NumberFormat::FORMAT_CURRENCY_IDR);
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
