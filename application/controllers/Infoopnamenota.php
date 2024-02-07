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

class Infoopnamenota extends CI_Controller
{
    public $id_menu = '90402';

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
                'assets/js/' . $this->folder . '/index.js?v=1',
            )
        );

        $dto = $this->input->post('dto', TRUE);
        if ($dto == '') {
            $dto = $this->uri->segment(3);
            if ($dto == '') {
                $dto = date('d-m-Y');
            }
        }
        $f_jatuh = $this->input->post('f_jatuh', TRUE);
        if ($f_jatuh == '') {
            $f_jatuh = $this->uri->segment(4);
            if ($f_jatuh == '') {
                $f_jatuh = 'f';
            }
        }
        $i_area = $this->input->post('i_area', TRUE);
        if ($i_area == '') {
            $i_area = $this->uri->segment(5);
            if ($i_area == '') {
                $i_area = 'NA';
            }
        }
        if ($i_area != 'NA') {
            $e_area_name = $this->db->get_where('tr_area', ['f_area_active' => true, 'i_company' => $this->i_company, 'i_area' => $i_area])->row()->e_area_name;
        } else {
            $e_area_name = 'Nasional';
        }
        $data = array(
            'dto'           => date('d-m-Y', strtotime($dto)),
            'f_jatuh'       => $f_jatuh,
            'i_area'        => $i_area,
            'e_area_name'   => ucwords(strtolower($e_area_name)),
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

    /** Export Data */
    public function export()
    {
        /** Parameter From Ajax Post */
        $dto        = $this->input->post('dto', TRUE);
        $f_jatuh    = $this->input->post('f_jatuh', TRUE);
        $i_area     = $this->input->post('i_area', TRUE);
        if ($f_jatuh == 'f') {
            $judul = "Berdasarkan Tanggal Nota : ";
        } else {
            $judul = "Berdasarkan Tanggal Jatuh Tempo : ";
        }
        if ($i_area != 'NA') {
            $e_area_name = $this->db->get_where('tr_area', ['f_area_active' => true, 'i_company' => $this->i_company, 'i_area' => $i_area])->row()->e_area_name;
        } else {
            $e_area_name = 'Nasional';
        }

        $e_company_name = $this->db->get_where('tr_company', ['f_company_active' => true, 'i_company' => $this->i_company])->row()->e_company_name;

        /** Query Get Data */
        $query      = $this->mymodel->get_data($dto, $f_jatuh, $i_area);

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
        $spreadsheet->getActiveSheet()->mergeCells("A1:V1");
        $spreadsheet->getActiveSheet()->mergeCells("A2:V2");
        $spreadsheet->getActiveSheet()->mergeCells("A3:V3");
        $h = 5;
        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A1', $this->title . " - " . $e_company_name)
            ->setCellValue('A2', "Area : " . ucwords(strtolower($e_area_name)))
            ->setCellValue('A3', $judul . " " . $dto)
            ->setCellValue('A' . $h, $this->lang->line('No'))
            ->setCellValue('B' . $h, $this->lang->line('Nama Area Provinsi'))
            ->setCellValue('C' . $h, $this->lang->line('No SJ'))
            ->setCellValue('D' . $h, $this->lang->line('Tgl SJ'))
            ->setCellValue('E' . $h, $this->lang->line('No Nota'))
            ->setCellValue('F' . $h, $this->lang->line('Tgl Nota'))
            ->setCellValue('G' . $h, $this->lang->line('TOP'))
            ->setCellValue('H' . $h, $this->lang->line('Toleransi'))
            ->setCellValue('I' . $h, $this->lang->line('Tgl Jatuh Tempo'))
            ->setCellValue('J' . $h, $this->lang->line('Nama Salesman'))
            ->setCellValue('K' . $h, $this->lang->line('Kode Pelanggan'))
            ->setCellValue('L' . $h, $this->lang->line('Nama Pelanggan'))
            ->setCellValue('M' . $h, $this->lang->line('Nama Kota / Kabupaten'))
            ->setCellValue('N' . $h, $this->lang->line('Nama Pelanggan'))
            ->setCellValue('O' . $h, $this->lang->line('Nama Pelanggan'))
            ->setCellValue('P' . $h, $this->lang->line('PKP'))
            ->setCellValue('Q' . $h, $this->lang->line('Bersih'))
            ->setCellValue('R' . $h, $this->lang->line('Nilai Sisa'))
            ->setCellValue('S' . $h, $this->lang->line('Info'))
            ->setCellValue('T' . $h, $this->lang->line('Status'))
            ->setCellValue('U' . $h, $this->lang->line('Lama'))
            ->setCellValue('V' . $h, $this->lang->line('Keterangan'));

        $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, 'A1:V4');
        $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle1, 'A' . $h . ':V' . $h);

        if ($query->num_rows() > 0) {
            $i              = 6;
            $x              = 6;
            $nomor          = 1;
            $lama           = 0;
            $tot            = 0;
            $saldonya0      = 0;
            $saldonya30     = 0;
            $saldonya60     = 0;
            $saldonya90     = 0;
            $totalsaldonya  = 0;
            $saldonyajtp    = 0;
            $saldojalan     = 0;
            $pecah1 = explode("-", $dto);
            $date1  = $pecah1[0];
            $month1 = $pecah1[1];
            $year1  = $pecah1[2];
            $jd1    = GregorianToJD($month1, $date1, $year1);
            foreach ($query->result() as $row) {
                $pecah2 = explode('-', $row->d_jatuh_tempo_top);
                $date2  = $pecah2[2];
                $month2 = $pecah2[1];
                $year2  = $pecah2[0];
                $jd2    = GregorianToJD($month2, $date2, $year2);
                $lama   = $jd1 - $jd2;
                $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $nomor)
                    ->setCellValue('B' . $i, $row->e_area_name)
                    ->setCellValue('C' . $i, $row->i_do_id)
                    ->setCellValue('D' . $i, Date::PHPToExcel($row->d_do))
                    ->setCellValue('E' . $i, $row->i_nota_id)
                    ->setCellValue('F' . $i, Date::PHPToExcel($row->d_nota))
                    ->setCellValue('G' . $i, $row->n_customer_top)
                    ->setCellValue('H' . $i, $row->n_toleransi)
                    ->setCellValue('I' . $i, Date::PHPToExcel($row->d_jatuh_tempo_top))
                    ->setCellValue('J' . $i, $row->e_salesman_name)
                    ->setCellValue('K' . $i, $row->codee)
                    ->setCellValue('L' . $i, $row->e_customer_name)
                    ->setCellValue('M' . $i, $row->e_city_name)
                    ->setCellValue('N' . $i, $row->e_customer_address)
                    ->setCellValue('O' . $i, $row->e_customer_phone)
                    ->setCellValue('P' . $i, $row->pkp)
                    ->setCellValue('Q' . $i, $row->v_nota_netto)
                    ->setCellValue('R' . $i, $row->v_sisa)
                    ->setCellValue('S' . $i, $row->ket)
                    ->setCellValue('T' . $i, $row->status)
                    ->setCellValue('U' . $i, $lama)
                    ->setCellValue('V' . $i, $row->e_remark);
                $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle2, 'A' . $i . ':V' . $i);
                if ($lama > 0 && $lama <= 7) {
                    $spreadsheet->getActiveSheet()->getStyle('A' . $i . ':V' . $i)->getFill()
                        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                        ->getStartColor()->setRGB('ADFF2F');
                } elseif ($lama >= 8 && $lama <= 15) {
                    $spreadsheet->getActiveSheet()->getStyle('A' . $i . ':V' . $i)->getFill()
                        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                        ->getStartColor()->setRGB('F0E68C');
                } elseif ($lama > 15) {
                    $spreadsheet->getActiveSheet()->getStyle('A' . $i . ':V' . $i)->getFill()
                        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                        ->getStartColor()->setRGB('FF69B4');
                }
                $totalsaldonya += $row->v_sisa;

                if ($lama > 90) {
                    $saldonya90 += $row->v_sisa;
                } elseif ($lama >= 61 && $lama <= 90) {
                    $saldonya60 += $row->v_sisa;
                } elseif ($lama >= 31 && $lama <= 60) {
                    $saldonya30 += $row->v_sisa;
                } elseif ($lama >= 0 && $lama <= 30) {
                    $saldonya0 += $row->v_sisa;
                } elseif ($lama < 0) {
                    $saldojalan += $row->v_sisa;
                }

                $saldonyajtp  = $totalsaldonya - $saldojalan;
                $p90          = $saldonya90 / $totalsaldonya;
                $p60          = $saldonya60 / $totalsaldonya;
                $p30          = $saldonya30 / $totalsaldonya;
                $p0           = $saldonya0 / $totalsaldonya;
                $pjtp         = $saldonyajtp / $totalsaldonya;
                $pj           = $saldojalan / $totalsaldonya;
                $ptot         = $totalsaldonya / $totalsaldonya;

                $tot = $tot + $row->v_sisa;
                $i++;
                $nomor++;
            }
            $spreadsheet->getActiveSheet()
                ->getStyle('D' . $x . ':D' . $i)
                ->getNumberFormat()
                ->setFormatCode(NumberFormat::FORMAT_DATE_DDMMYYYY);
            $spreadsheet->getActiveSheet()
                ->getStyle('F' . $x . ':F' . $i)
                ->getNumberFormat()
                ->setFormatCode(NumberFormat::FORMAT_DATE_DDMMYYYY);
            $spreadsheet->getActiveSheet()
                ->getStyle('I' . $x . ':I' . $i)
                ->getNumberFormat()
                ->setFormatCode(NumberFormat::FORMAT_DATE_DDMMYYYY);
            $spreadsheet->getActiveSheet()
                ->getStyle('Q' . $x . ':R' . $i)
                ->getNumberFormat()
                ->setFormatCode(NumberFormat::FORMAT_CURRENCY_IDR);
            $i = $i + 2;
            $y = $i;
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('B' . $i, $this->lang->line('Kategori Keterlambatan'))
                ->setCellValue('C' . $i, $this->lang->line('Nilai Piutang'))
                ->setCellValue('D' . $i, $this->lang->line('Persentase'));
            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle1, 'B' . $i . ':D' . $i);

            $i = $i + 1;
            $z = $i;
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('B' . $i, "> 90 Hari")
                ->setCellValue('C' . $i, $saldonya90)
                ->setCellValue('D' . $i, $p90);

            $i = $i + 1;
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('B' . $i, "61-90 Hari")
                ->setCellValue('C' . $i, $saldonya60)
                ->setCellValue('D' . $i, $p60);

            $i = $i + 1;
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('B' . $i, "31-60 Hari")
                ->setCellValue('C' . $i, $saldonya30)
                ->setCellValue('D' . $i, $p30);

            $i = $i + 1;
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('B' . $i, "0-30 Hari	")
                ->setCellValue('C' . $i, $saldonya0)
                ->setCellValue('D' . $i, $p0);

            $i = $i + 1;
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('B' . $i, $this->lang->line('Total Piutang Jatuh Tempo'))
                ->setCellValue('C' . $i, $saldonyajtp)
                ->setCellValue('D' . $i, $pjtp);

            $i = $i + 1;
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('B' . $i, $this->lang->line('Piutang Berjalan'))
                ->setCellValue('C' . $i, $saldojalan)
                ->setCellValue('D' . $i, $pj);

            $i = $i + 1;
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('B' . $i, $this->lang->line('Total Piutang Keseluruhan'))
                ->setCellValue('C' . $i, $tot)
                ->setCellValue('D' . $i, $ptot);


            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle2, 'B' . $z . ':D' . $i);
            $spreadsheet->getActiveSheet()
                ->getStyle('C' . $z . ':C' . $i)
                ->getNumberFormat()
                ->setFormatCode(NumberFormat::FORMAT_CURRENCY_IDR);
            $spreadsheet->getActiveSheet()
                ->getStyle('D' . $z . ':D' . $i)
                ->getNumberFormat()
                ->setFormatCode(NumberFormat::FORMAT_PERCENTAGE_00);
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
