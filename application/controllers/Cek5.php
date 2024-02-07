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

class Cek5 extends CI_Controller
{
    public $id_menu = '114';

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

        $year = $this->input->post('year', TRUE);
        if ($year == '') {
            $year = $this->uri->segment(3);
            if ($year == '') {
                $year = substr(get_periode2(),0,4);
            }
        }
        $month = $this->input->post('month', TRUE);
        if ($month == '') {
            $month = $this->uri->segment(4);
            if ($month == '') {
                $month = substr(get_periode2(),4,2);
            }
        }
        $data = array(
            'month'             => $month,
            'year'               => $year,
            'data' => $this->mymodel->get_data($month, $year),
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
    public function get_so()
    {
        $filter = [];
        $filter[] = array(
            'id'   => '0',
            'text' => 'Pilih Periode',
        );
        $data = $this->mymodel->get_so(str_replace("'", "", $this->input->get('q')));
        foreach ($data->result() as $row) {
            $filter[] = array(
                'id'   => $row->i_periode,
                'text' => $row->i_periode,
            );
        }
        echo json_encode($filter);
    }

    
    /** Closing Periode */
    public function closing()
    {
        $data = check_role($this->id_menu, 3);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        /** Parameter From Ajax Post */
        $this->form_validation->set_rules('year', 'year', 'trim|required|min_length[0]');
        $this->form_validation->set_rules('month', 'month', 'trim|required|min_length[0]');
        $year  = $this->input->post('year', TRUE);
        $month = $this->input->post('month', TRUE);


        if ($this->form_validation->run() == false) {
            $data = array(
                'sukses' => false,
                'periode' => $year.$month,
            );
        } else {
			$cek = $this->mymodel->miru($year, $month);
			if ($cek->num_rows() > 0) {
				$this->db->trans_begin();
                    // $this->mymodel->update_periode($year, $month);
                    // $this->mymodel->update_log($year, $month);
                    $this->mymodel->update_coa_saldo2($year, $month);
                    if ($this->db->trans_status() === FALSE) {
                        $this->db->trans_rollback();
                        $data = array(
                            'sukses' => false,
                            'periode' => $year.$month,
                        );
                    } else {
                        $this->db->trans_commit();
                        $this->logger->write('Update COA SALDO : ' . $year . $month);
                        $data = array(
                            'sukses' => true,
                            'periode' => $year.$month,
                        );
                    }
			} else {
                    $this->db->trans_begin();
                    // $this->mymodel->update_periode($year, $month);
                    // $this->mymodel->update_log($year, $month);
                    $this->mymodel->update_coa_saldo($year, $month);
                    $this->mymodel->karen($year, $month);
                    if ($this->db->trans_status() === FALSE) {
                        $this->db->trans_rollback();
                        $data = array(
                            'sukses' => false,
                            'periode' => $year.$month,
                        );
                    } else {
                        $this->db->trans_commit();
                        $this->logger->write('Insert COA SALDO : ' . $year . $month);
                        $data = array(
                            'sukses' => true,
                            'periode' => $year.$month,
                        );
                    }
            }
        }
        echo json_encode($data);
    }



    public function closing2()
    {
        $data = check_role($this->id_menu, 3);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        /** Parameter From Ajax Post */
        $this->form_validation->set_rules('year', 'year', 'trim|required|min_length[0]');
        $this->form_validation->set_rules('month', 'month', 'trim|required|min_length[0]');
        $year  = $this->input->post('year', TRUE);
        $month = $this->input->post('month', TRUE);


        if ($this->form_validation->run() == false) {
            $data = array(
                'sukses' => false,
                'periode' => $year.$month,
            );
        } else {
                    $this->db->trans_begin();
                    // $this->mymodel->update_periode($year, $month);
                    // $this->mymodel->update_log($year, $month);
                    $this->mymodel->update_coa_saldo3($year, $month); 
                    if ($this->db->trans_status() === FALSE) {
                        $this->db->trans_rollback();
                        $data = array(
                            'sukses' => false,
                            'periode' => $year.$month,
                        );
                    } else {
                        $this->db->trans_commit();
                        $this->logger->write('Perbarui Saldo Awal : ' . $year . $month);
                        $data = array(
                            'sukses' => true,
                            'periode' => $year.$month,
                        );
                    }
        }
        echo json_encode($data);
    }



    /** Export Data */
    public function export()
    {        
        $month      = $this->input->post('month', TRUE);
        $year        = $this->input->post('year', TRUE);
        $i_periode = $year . $month;
        

        $e_company_name = $this->db->get_where('tr_company', ['f_company_active' => true, 'i_company' => $this->i_company])->row()->e_company_name;

        /** Query Get Data */
        $query      = $this->mymodel->get_data($year, $month);

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
        
        $spreadsheet->getActiveSheet()->mergeCells("A1:H1");
        $spreadsheet->getActiveSheet()->mergeCells("A2:H2");
        $spreadsheet->getActiveSheet()->mergeCells("A3:H3");
        $h = 5;
        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A1', $this->title)
            ->setCellValue('A2', ucwords(strtoupper($e_company_name)))
            ->setCellValue('A3', "PERIODE : " . $i_periode)
            ->setCellValue('A' . $h, "i_coa_saldo")
            ->setCellValue('B' . $h, "COA")
            ->setCellValue('C' . $h, "periode")
            ->setCellValue('D' . $h, "coa_name")
            ->setCellValue('E' . $h, "saldo_awal")
            ->setCellValue('F' . $h, "mutasi_debet")
            ->setCellValue('G' . $h, "mutasi_kredit")
            ->setCellValue('H' . $h, "saldo_akhir");

        $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, 'A1:H4');
        $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle1, 'A' . $h . ':H' . $h);

        if ($query->num_rows() > 0) {
            $i = 6;
            $x = 6;
            $nomor = 1;
            foreach ($query->result() as $row) {
                $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $row->i_coa_saldo)
                    ->setCellValue('B' . $i, $row->i_coa)
                    ->setCellValue('C' . $i, $row->i_periode)
                    ->setCellValue('D' . $i, $row->e_coa_name)
                    ->setCellValue('E' . $i, $row->v_saldo_awal)
                    ->setCellValue('F' . $i, $row->v_mutasi_debet)
                    ->setCellValue('G' . $i, $row->v_mutasi_kredit)
                    ->setCellValue('H' . $i, $row->v_saldo_akhir);
                $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle2, 'A' . $i . ':H' . $i);
                $i++;
                $nomor++;
            }
            // $spreadsheet->getActiveSheet()
            //     ->getStyle('F' . $x . ':F' . $i)
            //     ->getNumberFormat()
            //     ->setFormatCode(NumberFormat::FORMAT_DATE_DDMMYYYY);
            // $spreadsheet->getActiveSheet()->mergeCells('A' . $i . ':E' . $i);
            // $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle1, 'A' . $i . ':F' . $i);
            // $spreadsheet->getActiveSheet()->setCellValue('A' . $i, 'TOTAL');
            // $spreadsheet->getActiveSheet()->setCellValue('F' . $i, "=SUM(F" . $x . ":F" . ($i - 1) . ")");
            $spreadsheet->getActiveSheet()
                ->getStyle('E' . $x . ':H' . $i)
                ->getNumberFormat()
                ->setFormatCode(NumberFormat::FORMAT_CURRENCY_IDR);
        }

        $writer = new Xls($spreadsheet);
        $nama_file = $this->title . " - " . $e_company_name . " - " . $i_periode  . ".xls";
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
