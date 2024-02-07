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

class Cek1 extends CI_Controller
{
    public $id_menu = '110';

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



        if (strlen($dfrom) != 10) {
            $dfrom = decrypt_url($dfrom);
        }
        if (strlen($dto) != 10) {
            $dto = decrypt_url($dto);
        }

        $data = array(
            'dfrom'             => date('d-m-Y', strtotime($dfrom)),
            'dto'               => date('d-m-Y', strtotime($dto)),
        );

        $this->logger->write('Membuka Menu ' . $this->title);
        $this->template->load('main', $this->folder . '/index', $data);
    }

    /** List Data */
    public function serverside()
    {
        echo $this->mymodel->serverside();
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

        $i_product         = decrypt_url($this->uri->segment(3));
        $i_company         = decrypt_url($this->uri->segment(4));
        $dfrom             = decrypt_url($this->uri->segment(5));
        $dto             = decrypt_url($this->uri->segment(6));
        $data = array(
            'detail'            => $this->mymodel->get_data_detail($i_product, $i_company, $dfrom, $dto),
            'dfrom'                => $dfrom,
            'dto'                => $dto,
            'e_product_name'    => $this->logger->get_product($i_product),
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
        $i_periode  = date('Ym', strtotime($dfrom));
        // $i_store    = $this->input->post('i_store', TRUE);
        // $i_product  = $this->input->post('i_product', TRUE);
        // if ($i_store != 'NA') {
        //     $e_store_name = $this->db->get_where('tr_store', ['f_store_active' => true, 'i_company' => $this->i_company, 'i_store' => $i_store])->row()->e_store_name;
        // } else {
        //     $e_store_name = '( SEMUA GUDANG )';
        // }
        $e_company_name = $this->db->get_where('tr_company', ['f_company_active' => true, 'i_company' => $this->i_company])->row()->e_company_name;

        /** Query Get Data */
        $query      = $this->mymodel->get_print($dfrom, $dto);

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

        $spreadsheet->getActiveSheet()->mergeCells("A1:J1");
        $spreadsheet->getActiveSheet()->mergeCells("A2:J2");
        $spreadsheet->getActiveSheet()->mergeCells("A3:J3");
        $spreadsheet->getActiveSheet()->mergeCells("A4:J4");
        $h = 6;
        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A1', $this->title)
            ->setCellValue('A2', $e_company_name)
            ->setCellValue('A3', "PUSAT")
            ->setCellValue('A4', "Periode : $dfrom s/d $dto")
            ->setCellValue('A' . $h, 'i_product')
            ->setCellValue('B' . $h, $this->lang->line('Kode Barang'))
            ->setCellValue('C' . $h, $this->lang->line('Nama Barang'))
            ->setCellValue('D' . $h, 'n_saldo_akhir')
            ->setCellValue('E' . $h, 'i_mutasi_saldoawal')
            ->setCellValue('F' . $h, 'n_saldo_awal')
            ->setCellValue('G' . $h, 'n_saldo_awal-n_saldo_akhir')
            ->setCellValue('H' . $h, 'i_ic')
            ->setCellValue('I' . $h, 'n_quantity_stock')
            ->setCellValue('J' . $h, 'n_quantity_stock-n_saldo_akhir');

        $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, 'A1:J5');
        $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle1, 'A' . $h . ':J' . $h);

        if ($query->num_rows() > 0) {
            $i = 7;
            $x = 7;
            $nomor = 1;
            foreach ($query->result() as $row) {
                $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $row->i_product)
                    ->setCellValue('B' . $i, $row->i_product_id)
                    ->setCellValue('C' . $i, $row->e_product_name)
                    ->setCellValue('D' . $i, $row->n_saldo_akhir)
                    ->setCellValue('E' . $i, $row->i_mutasi_saldoawal)
                    ->setCellValue('F' . $i, $row->n_saldo_awal)
                    ->setCellValue('G' . $i, $row->selisih3)
                    ->setCellValue('H' . $i, $row->i_ic)
                    ->setCellValue('I' . $i, $row->n_quantity_stock)
                    ->setCellValue('J' . $i, $row->selisih);
                $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle2, 'A' . $i . ':J' . $i);
                $i++;
                $nomor++;
            }
            $spreadsheet->getActiveSheet()->mergeCells('A' . $i . ':F' . $i);
            $spreadsheet->getActiveSheet()->mergeCells('H' . $i . ':I' . $i);
            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle1, 'A' . $i . ':J' . $i);
            $spreadsheet->getActiveSheet()->setCellValue('A' . $i, 'TOTAL');
            $spreadsheet->getActiveSheet()->setCellValue('G' . $i, "=SUM(G" . $x . ":G" . ($i - 1) . ")");
            $spreadsheet->getActiveSheet()->setCellValue('J' . $i, "=SUM(J" . $x . ":J" . ($i - 1) . ")");
        }
        
        $writer = new Xls($spreadsheet);
        $nama_file = $this->title . "_" . $e_company_name . "_" . $i_periode . ".xls";
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


    /** Simpan Data */
    public function save()
    {
        /** Cek Hak Akses, Apakah User Bisa Create */
        // $data = check_role($this->id_menu, 2);
        // if (!$data) {
        //     redirect(base_url(), 'refresh');
        // }
        $dfrom = $this->input->post('d_from2', TRUE);

        $this->form_validation->set_rules('d_from2', 'd_from2', 'trim|required|min_length[0]');
        // $this->form_validation->set_rules('i_store', 'i_store', 'trim|required|min_length[0]');
        // $this->form_validation->set_rules('i_periode', 'i_periode', 'trim|required|min_length[0]');
        // $i_mp_id = strtoupper($this->input->post('i_mp_id', TRUE));
        // $i_store = strtoupper($this->input->post('i_store', TRUE));
        // $i_periode = strtoupper($this->input->post('i_periode', TRUE));
        if ($this->form_validation->run() == false) {
            $data = array(
                'sukses' => false,
                'ada'     => false,
            );
        } else {
            /** Cek Jika Kode Sudah Ada */
            // $cek = $this->mymodel->cek($i_mp_id);
            /** Jika Sudah Ada Jangan Disimpan */
            // if ($cek->num_rows() > 0) {
            //     $data = array(
            //         'sukses' => false,
            //         'ada'     => true,
            //     );
            // } else {
            /** Jika Belum Ada Simpan Data */
            $this->db->trans_begin();
            $this->mymodel->save();
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $data = array(
                    'sukses' => false,
                    'ada'     => false,
                );
            } else {
                $this->db->trans_commit();
                $this->logger->write('Simpan Data ' . $this->title);
                $data = array(
                    'sukses' => true,
                    'ada'     => false,
                );
            }
            // }
        }
        echo json_encode($data);
    }



    /** Get Number */
    public function number()
    {
        $tanggal = $this->input->post('tanggal', TRUE);
        if ($tanggal != '') {
            $number = $this->mymodel->running_number(date('ym', strtotime($tanggal)), date('Y', strtotime($tanggal)));
        } else {
            $number = $this->mymodel->running_number(date('ym'), date('Y'));
        }
        echo json_encode($number);
    }
}
