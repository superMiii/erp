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

class Infocustomerlist extends CI_Controller
{
    public $id_menu = '90103';

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
                'assets/js/' . $this->folder . '/index.js?v=19107',
            )
        );

        $i_area = $this->input->post('i_area', TRUE);
        if ($i_area == '') {
            $i_area = $this->uri->segment(3);
            if ($i_area == '') {
                $i_area = 'NA';
            }
        }

        $i_price_group = $this->input->post('i_price_group', TRUE);
        if ($i_price_group == '') {
            $i_price_group = $this->uri->segment(4);
            if ($i_price_group == '') {
                $i_price_group = 'ALL';
            }
        }

        $i_customer_status = $this->input->post('i_customer_status', TRUE);
        if ($i_customer_status == '') {
            $i_customer_status = $this->uri->segment(5);
            if ($i_customer_status == '') {
                $i_customer_status = 'ALL';
            }
        }

        $i_customer_group = $this->input->post('i_customer_group', TRUE);
        if ($i_customer_group == '') {
            $i_customer_group = $this->uri->segment(6);
            if ($i_customer_group == '') {
                $i_customer_group = 'ALL';
            }
        }

        if ($i_area != 'NA') {
            $e_area_name = $this->db->get_where('tr_area', ['f_area_active' => true, 'i_company' => $this->i_company, 'i_area' => $i_area])->row()->e_area_name;
        } else {
            $e_area_name = 'Nasional';
        }

        if ($i_price_group != 'ALL') {
            $e_price_groupname = $this->db->get_where('tr_price_group', ['f_price_groupactive' => true, 'i_company' => $this->i_company, 'i_price_group' => $i_price_group])->row()->e_price_groupname;
        } else {
            $e_price_groupname = 'Semua Harga';
        }

        if ($i_customer_status != 'ALL') {
            $e_customer_statusname = $this->db->get_where('tr_customer_status', ['f_customer_statusactive' => true, 'i_company' => $this->i_company, 'i_customer_status' => $i_customer_status])->row()->e_customer_statusname;
        } else {
            $e_customer_statusname = '( Semua Kriteria )';
        }

        if ($i_customer_group != 'ALL') {
            $e_customer_groupname = $this->db->get_where('tr_customer_group', ['f_customer_groupactive' => true, 'i_company' => $this->i_company, 'i_customer_group' => $i_customer_group])->row()->e_customer_groupname;
        } else {
            $e_customer_groupname = 'Semua Group';
        }
        $data = array(
            // 'dfrom'             => date('d-m-Y', strtotime($dfrom)),
            // 'dto'               => date('d-m-Y', strtotime($dto)),
            'i_area'                => $i_area,
            'e_area_name'           => ucwords(strtolower($e_area_name)),
            'i_price_group'         => $i_price_group,
            'e_price_groupname'     => ucwords(strtolower($e_price_groupname)),
            'i_customer_status'     => $i_customer_status,
            'e_customer_statusname' => ucwords(strtolower($e_customer_statusname)),
            'i_customer_group'      => $i_customer_group,
            'e_customer_groupname'  => ucwords(strtolower($e_customer_groupname)),
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
    public function get_price_group()
    {
        $filter = [];
        $filter[] = array(
            'id'   => 'ALL',
            'text' => 'Semua Harga',
        );
        $cari   = str_replace("'", "", $this->input->get('q'));
        $i_area = $this->input->get('i_area');
        if ($i_area != '') {
            $data = $this->mymodel->get_price_group($cari, $i_area);
            foreach ($data->result() as $row) {
                $filter[] = array(
                    'id'   => $row->i_price_group,
                    'text' => $row->e_price_groupname,
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

    public function get_customer_status()
    {
        $filter = [];
        $filter[] = array(
            'id'   => 'ALL',
            'text' => '( Semua Kriteria )',
        );
        $data = $this->mymodel->get_customer_status(str_replace("'", "", $this->input->get('q')));
        foreach ($data->result() as $row) {
            $filter[] = array(
                'id'   => $row->i_customer_status,
                'text' => $row->e_customer_statusname,
            );
        }
        echo json_encode($filter);
    }


    public function get_customer_group()
    {
        $filter = [];
        $filter[] = array(
            'id'   => 'ALL',
            'text' => 'Semua Group',
        );
        $cari   = str_replace("'", "", $this->input->get('q'));
        $i_area = $this->input->get('i_area');
        if ($i_area != '') {
            $data = $this->mymodel->get_customer_group($cari, $i_area);
            foreach ($data->result() as $row) {
                $filter[] = array(
                    'id'   => $row->i_customer_group,
                    'text' => $row->e_customer_groupname,
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
        $i_area     = $this->input->post('i_area', TRUE);
        $i_price_group = $this->input->post('i_price_group', TRUE);
        $i_customer_status = $this->input->post('i_customer_status', TRUE);
        $i_customer_group = $this->input->post('i_customer_group', TRUE);


        if ($i_area != 'NA') {
            $e_area_name = $this->db->get_where('tr_area', ['f_area_active' => true, 'i_company' => $this->i_company, 'i_area' => $i_area])->row()->e_area_name;
        } else {
            $e_area_name = 'Nasional';
        }

        if ($i_price_group != 'ALL') {
            $e_price_groupname = $this->db->get_where('tr_price_group', ['i_company' => $this->i_company, 'i_price_group' => $i_price_group])->row()->e_price_groupname;
        } else {
            $e_price_groupname = 'Semua Harga';
        }

        if ($i_customer_status != 'ALL') {
            $e_customer_statusname = $this->db->get_where('tr_customer_status', ['f_customer_statusactive' => true, 'i_company' => $this->i_company, 'i_customer_status' => $i_customer_status])->row()->e_customer_statusname;
        } else {
            $e_customer_statusname = '( Semua Kriteria )';
        }

        if ($i_customer_group != 'ALL') {
            $e_customer_groupname = $this->db->get_where('tr_customer_group', ['f_customer_groupactive' => true, 'i_company' => $this->i_company, 'i_customer_group' => $i_customer_group])->row()->e_customer_groupname;
        } else {
            $e_customer_groupname = 'Semua Group';
        }

        $e_company_name = $this->db->get_where('tr_company', ['f_company_active' => true, 'i_company' => $this->i_company])->row()->e_company_name;

        /** Query Get Data */
        $query      = $this->mymodel->get_data($i_area, $i_price_group, $i_customer_group, $i_customer_status);

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
        $spreadsheet->getActiveSheet()->mergeCells("A1:T1");
        $spreadsheet->getActiveSheet()->mergeCells("A2:T2");
        $spreadsheet->getActiveSheet()->mergeCells("A3:T3");
        $spreadsheet->getActiveSheet()->mergeCells("A4:T4");
        $spreadsheet->getActiveSheet()->mergeCells("A5:T5");
        $h = 7;
        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A1', $this->title . " - " . $e_company_name)
            ->setCellValue('A2', "Area : " . ucwords(strtolower($e_area_name)))
            ->setCellValue('A3', "Harga : $e_price_groupname")
            ->setCellValue('A4', "Kriteria : $e_customer_statusname")
            ->setCellValue('A5', "Group : $e_customer_groupname")
            ->setCellValue('A' . $h, $this->lang->line('No'))
            ->setCellValue('B' . $h, $this->lang->line('Tanggal Terdaftar'))
            ->setCellValue('C' . $h, $this->lang->line('Nama Area Provinsi'))
            ->setCellValue('D' . $h, $this->lang->line('Kota'))
            ->setCellValue('E' . $h, $this->lang->line('Nama Pelanggan'))
            ->setCellValue('F' . $h, $this->lang->line('Alamat Pelanggan'))
            ->setCellValue('G' . $h, $this->lang->line('Kelompok Pelanggan'))
            ->setCellValue('H' . $h, $this->lang->line('PPN'))
            ->setCellValue('I' . $h, $this->lang->line('Kode NPWP'))
            ->setCellValue('J' . $h, $this->lang->line('Nama Pemilik'))
            ->setCellValue('K' . $h, $this->lang->line('Nomor KTP Pemilik'))
            ->setCellValue('L' . $h, $this->lang->line('Nomor Telepon'))
            ->setCellValue('M' . $h, $this->lang->line('Diskon1'))
            ->setCellValue('N' . $h, $this->lang->line('Diskon2'))
            ->setCellValue('O' . $h, $this->lang->line('Diskon3'))
            ->setCellValue('P' . $h, $this->lang->line('Tipe Pelanggan'))
            ->setCellValue('Q' . $h, $this->lang->line('Status Pelanggan'))
            ->setCellValue('R' . $h, $this->lang->line('Kelompok Harga'))
            ->setCellValue('S' . $h, $this->lang->line('TOP'))
            ->setCellValue('T' . $h, $this->lang->line('Plafon'));

        $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, 'A1:T5');
        $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle1, 'A' . $h . ':T' . $h);

        if ($query->num_rows() > 0) {
            $i = 8;
            $x = 8;
            $nomor = 1;
            foreach ($query->result() as $row) {
                $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $nomor)
                    ->setCellValue('B' . $i, $row->reg)
                    ->setCellValue('C' . $i, $row->e_area_name)
                    ->setCellValue('D' . $i, $row->e_city_name)
                    ->setCellValue('E' . $i, $row->e_customer_name)
                    ->setCellValue('F' . $i, $row->e_customer_address)
                    ->setCellValue('G' . $i, $row->e_customer_groupname)
                    ->setCellValue('H' . $i, $row->ppn)
                    ->setCellValue('I' . $i, $row->e_customer_npwpcode)
                    ->setCellValue('J' . $i, $row->e_customer_owner)
                    ->setCellValue('K' . $i, $row->e_ktp_owner)
                    ->setCellValue('L' . $i, $row->e_customer_phone)
                    ->setCellValue('M' . $i, $row->n_customer_discount1)
                    ->setCellValue('N' . $i, $row->n_customer_discount2)
                    ->setCellValue('O' . $i, $row->n_customer_discount3)
                    ->setCellValue('P' . $i, $row->e_customer_typename)
                    ->setCellValue('Q' . $i, $row->e_customer_statusname)
                    ->setCellValue('R' . $i, $row->e_price_groupname)
                    ->setCellValue('S' . $i, $row->n_customer_top)
                    ->setCellValue('T' . $i, $row->pla);
                $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle2, 'A' . $i . ':T' . $i);
                $i++;
                $nomor++;
            }
        }

        $writer = new Xls($spreadsheet);
        $nama_file = $this->title . " - " . $e_company_name . " - " . $e_price_groupname . " - " . $e_customer_statusname . " - " . $e_customer_groupname . ".xls";
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
