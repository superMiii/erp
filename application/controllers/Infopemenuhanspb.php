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

class Infopemenuhanspb extends CI_Controller
{
    public $id_menu = '90102';

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

        $i_salesman = $this->input->post('i_salesman', TRUE);
        if ($i_salesman == '') {
            $i_salesman = $this->uri->segment(8);
            if ($i_salesman == '') {
                $i_salesman = 'ALL';
            }
        }

        $i_product = $this->input->post('i_product', TRUE);
        if ($i_product == '') {
            $i_product = $this->uri->segment(9);
            if ($i_product == '') {
                $i_product = 'NA';
            }
        }

        $miu = $this->input->post('miu', TRUE);
        if ($miu == '') {
            $miu = $this->uri->segment(10);
            if ($miu == '') {
                $miu = '1';
            }
        }

        if ($i_area != 'NA') {
            $e_area_name = $this->db->get_where('tr_area', ['f_area_active' => true, 'i_company' => $this->i_company, 'i_area' => $i_area])->row()->e_area_name;
        } else {
            $e_area_name = 'Nasional';
        }
        if ($i_customer != 'ALL') {
            $e_customer_name = $this->db->get_where('tr_customer', ['f_customer_active' => true, 'i_company' => $this->i_company, 'i_customer' => $i_customer])->row()->e_customer_name;
        } else {
            $e_customer_name = 'Semua Pelanggan';
        }
        if ($i_salesman != 'ALL') {
            $e_salesman_name = $this->db->get_where('tr_salesman', ['f_salesman_active' => true, 'i_company' => $this->i_company, 'i_salesman' => $i_salesman])->row()->e_salesman_name;
        } else {
            $e_salesman_name = 'Semua Sales';
        }

        if ($i_product != 'NA') {
            $e_product_name = $this->db->get_where('tr_product', ['f_product_active' => true, 'i_company' => $this->i_company, 'i_product' => $i_product])->row()->e_product_name;
        } else {
            $e_product_name = 'Semua Barang';
        }

        $data = array(
            'dfrom'             => date('d-m-Y', strtotime($dfrom)),
            'dto'               => date('d-m-Y', strtotime($dto)),
            'i_area'            => $i_area,
            'e_area_name'       => ucwords(strtolower($e_area_name)),
            'i_customer'        => $i_customer,
            'e_customer_name'   => ucwords(strtolower($e_customer_name)),
            'pro'               => $pro,
            'i_salesman'        => $i_salesman,
            'e_salesman_name'   => ucwords(strtolower($e_salesman_name)),
            'i_product'         => $i_product,
            'e_product_name'    => ucwords(strtolower($e_product_name)),         
            'miu'               => $miu,
            'data'              => $this->mymodel->get_data($dfrom, $dto, $i_area, $i_customer, $pro, $i_salesman, $i_product, $miu),
        );

        $this->logger->write('Membuka Menu ' . $this->title);
        $this->template->load('main', $this->folder . '/index', $data);
    }

    /** List Data */
    public function serverside()
    {
        echo $this->mymodel->serverside();
    }

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
            'text' => 'Semua Pelanggan',
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

    /** Get Data Customer */
    public function get_salesman()
    {
        $filter = [];
        $filter[] = array(
            'id'   => 'ALL',
            'text' => 'Semua Sales',
        );
        $cari   = str_replace("'", "", $this->input->get('q'));
        $i_area = $this->input->get('i_area');
        if ($i_area != '') {
            $data = $this->mymodel->get_salesman($cari, $i_area);
            foreach ($data->result() as $row) {
                $filter[] = array(
                    'id'   => $row->i_salesman,
                    'text' => $row->e_salesman_name,
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
        $i_area     = $this->input->post('i_area', TRUE);
        $i_customer = $this->input->post('i_customer', TRUE);
        $pro        = $this->input->post('pro', TRUE);
        $i_salesman = $this->input->post('i_salesman', TRUE);
        $i_product     = $this->input->post('i_product', TRUE);
        $miu        = $this->input->post('miu', TRUE);


        if ($i_area != 'NA') {
            $e_area_name = $this->db->get_where('tr_area', ['f_area_active' => true, 'i_company' => $this->i_company, 'i_area' => $i_area])->row()->e_area_name;
        } else {
            $e_area_name = 'Nasional';
        }

        if ($i_customer != 'ALL') {
            $e_customer_name = $this->db->get_where('tr_customer', ['f_customer_active' => true, 'i_company' => $this->i_company, 'i_customer' => $i_customer])->row()->e_customer_name;
        } else {
            $e_customer_name = 'Semua Pelanggan';
        }
        
        if ($i_salesman != 'ALL') {
            $e_salesman_name = $this->db->get_where('tr_salesman', ['f_salesman_active' => true, 'i_company' => $this->i_company, 'i_salesman' => $i_salesman])->row()->e_salesman_name;
        } else {
            $e_salesman_name = 'Semua Sales';
        }

        if ($i_product != 'NA') {
            $e_product_name = $this->db->get_where('tr_product', ['f_product_active' => true, 'i_company' => $this->i_company, 'i_product' => $i_product])->row()->e_product_name;
        } else {
            $e_product_name = 'Semua Barang';
        }

        $e_company_name = $this->db->get_where('tr_company', ['f_company_active' => true, 'i_company' => $this->i_company])->row()->e_company_name;

        /** Query Get Data */
        $query      = $this->mymodel->get_data($dfrom, $dto, $i_area, $i_customer, $pro, $i_salesman, $i_product, $miu);

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
        $spreadsheet->getActiveSheet()->mergeCells("A1:Y1");
        $spreadsheet->getActiveSheet()->mergeCells("A2:Y2");
        $spreadsheet->getActiveSheet()->mergeCells("A3:Y3");
        $spreadsheet->getActiveSheet()->mergeCells("A4:Y4");
        $h = 6;
        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A1', $this->title . " - " . $e_company_name)
            ->setCellValue('A2', "Area : " . ucwords(strtolower($e_area_name)))
            ->setCellValue('A3', "Periode : $dfrom s/d $dto")
            ->setCellValue('A4', "Pelanggan : $e_customer_name & Sales : $e_salesman_name")
            ->setCellValue('A' . $h, $this->lang->line('No'))
            ->setCellValue('B' . $h, $this->lang->line('Nama Area Provinsi'))
            ->setCellValue('C' . $h, $this->lang->line('Kota'))
            ->setCellValue('D' . $h, $this->lang->line('Pulau'))
            ->setCellValue('E' . $h, $this->lang->line('Pemenuhan'))
            ->setCellValue('F' . $h, $this->lang->line('No SPB'))
            ->setCellValue('G' . $h, $this->lang->line('Bulan'))
            ->setCellValue('H' . $h, $this->lang->line('Tahun'))
            ->setCellValue('I' . $h, $this->lang->line('Tgl SPB'))
            ->setCellValue('J' . $h, $this->lang->line('Nama Pelanggan'))
            ->setCellValue('K' . $h, $this->lang->line('Jenis Pelanggan'))
            ->setCellValue('L' . $h, $this->lang->line('Nama Salesman'))
            ->setCellValue('M' . $h, $this->lang->line('Company'))
            ->setCellValue('N' . $h, $this->lang->line('Kode Barang'))
            ->setCellValue('O' . $h, $this->lang->line('Nama Barang'))
            ->setCellValue('P' . $h, $this->lang->line('Sub Kategori Barang'))
            ->setCellValue('Q' . $h, $this->lang->line('Kategori Barang'))
            ->setCellValue('R' . $h, $this->lang->line('Qty SPB'))
            ->setCellValue('S' . $h, $this->lang->line('Qty SJ'))
            ->setCellValue('T' . $h, $this->lang->line('Qty Nota'))
            ->setCellValue('U' . $h, $this->lang->line('Harga/Pcs'))
            ->setCellValue('V' . $h, $this->lang->line('Diskon/Pcs'))
            ->setCellValue('W' . $h, $this->lang->line('No Nota'))
            ->setCellValue('X' . $h, $this->lang->line('Tgl Nota'))
            ->setCellValue('Y' . $h, $this->lang->line('Promo'));

        $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, 'A1:Y4');
        $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle1, 'A' . $h . ':Y' . $h);

        if ($query->num_rows() > 0) {
            $i = 7;
            $x = 7;
            $nomor = 1;
            foreach ($query->result() as $row) {
                $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $nomor)
                    ->setCellValue('B' . $i, $row->e_area_name)
                    ->setCellValue('C' . $i, $row->e_city_name)
                    ->setCellValue('D' . $i, $row->e_area_island)
                    ->setCellValue('E' . $i, $row->pinuh)
                    ->setCellValue('F' . $i, $row->i_so_id)
                    ->setCellValue('G' . $i, $row->bulan)
                    ->setCellValue('H' . $i, $row->tahun)
                    ->setCellValue('I' . $i, Date::PHPToExcel($row->d_so))
                    ->setCellValue('J' . $i, $row->e_customer_name)
                    ->setCellValue('K' . $i, $row->e_customer_typename)
                    ->setCellValue('L' . $i, $row->e_salesman_name)
                    ->setCellValue('M' . $i, $row->e_company_name)
                    ->setCellValue('N' . $i, $row->i_product_id)
                    ->setCellValue('O' . $i, $row->e_product_name)
                    ->setCellValue('P' . $i, $row->e_product_subcategoryname)
                    ->setCellValue('Q' . $i, $row->e_product_categoryname)
                    ->setCellValue('R' . $i, $row->n_order)
                    ->setCellValue('S' . $i, $row->n_sj)
                    ->setCellValue('T' . $i, $row->n_deliver)
                    ->setCellValue('U' . $i, $row->v_unit_price)
                    ->setCellValue('V' . $i, $row->v_discount)
                    ->setCellValue('W' . $i, $row->i_nota_id)
                    ->setCellValue('X' . $i, Date::PHPToExcel($row->d_nota))
                    ->setCellValue('Y' . $i, $row->pro);
                $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle2, 'A' . $i . ':Y' . $i);
                $i++;
                $nomor++;
            }
            $spreadsheet->getActiveSheet()
                ->getStyle('I' . $x . ':I' . $i)
                ->getNumberFormat()
                ->setFormatCode(NumberFormat::FORMAT_DATE_DDMMYYYY);
            $spreadsheet->getActiveSheet()
                ->getStyle('X' . $x . ':X' . $i)
                ->getNumberFormat()
                ->setFormatCode(NumberFormat::FORMAT_DATE_DDMMYYYY);
            $spreadsheet->getActiveSheet()->mergeCells('A' . $i . ':Q' . $i);
            $spreadsheet->getActiveSheet()->mergeCells('W' . $i . ':Y' . $i);
            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle1, 'A' . $i . ':Y' . $i);
            $spreadsheet->getActiveSheet()->setCellValue('A' . $i, 'TOTAL');
            $spreadsheet->getActiveSheet()->setCellValue('R' . $i, "=SUM(R" . $x . ":R" . ($i - 1) . ")");
            $spreadsheet->getActiveSheet()->setCellValue('S' . $i, "=SUM(S" . $x . ":S" . ($i - 1) . ")");
            $spreadsheet->getActiveSheet()->setCellValue('T' . $i, "=SUM(T" . $x . ":T" . ($i - 1) . ")");
            $spreadsheet->getActiveSheet()->setCellValue('U' . $i, "=SUM(U" . $x . ":U" . ($i - 1) . ")");
            $spreadsheet->getActiveSheet()->setCellValue('V' . $i, "=SUM(U" . $x . ":V" . ($i - 1) . ")");
            $spreadsheet->getActiveSheet()
                ->getStyle('U' . $x . ':V' . $i)
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
