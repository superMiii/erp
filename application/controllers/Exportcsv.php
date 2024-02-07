<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Exportcsv extends CI_Controller
{
    public $id_menu = '801';

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
                'app-assets/vendors/css/animate/animate.css',
                'app-assets/vendors/css/pickers/pickadate/pickadate.css',	
				'app-assets/vendors/css/forms/selects/select2.min.css',
            )
        );

        add_js(
            array(
                'app-assets/vendors/js/tables/datatable/datatables.min.js',
                'app-assets/vendors/js/tables/datatable/dataTables.buttons.min.js',
                'app-assets/vendors/js/tables/datatable/buttons.bootstrap4.min.js',
                'app-assets/vendors/js/extensions/sweetalert2.all.min.js',
                'app-assets/vendors/js/pickers/pickadate/picker.js',
                'app-assets/vendors/js/pickers/pickadate/picker.date.js',
				'app-assets/vendors/js/forms/select/select2.full.min.js',
                'assets/js/' . $this->folder . '/index.js',
            )
        );

        $i_nota_mulai = $this->input->post('i_nota_mulai', TRUE);
        if ($i_nota_mulai == '') {
            $i_nota_mulai = $this->uri->segment(3);
            if ($i_nota_mulai == '') {
                $i_nota_mulai = 0;
            }
        }
        $i_nota_akhir = $this->input->post('i_nota_akhir', TRUE);
        if ($i_nota_akhir == '') {
            $i_nota_akhir = $this->uri->segment(4);
            if ($i_nota_akhir == '') {
                $i_nota_akhir = 0;
            }
        }

        if ($i_nota_mulai != 0) {
            $i_nota_id_mulai = $this->db->query("SELECT i_nota_id||' ( '||to_char(d_nota, 'dd-mm-yyyy')||' )' AS i_nota_id  FROM tm_nota WHERE i_nota_id = '$i_nota_mulai' AND f_nota_cancel = 'f' AND i_company = '$this->i_company' ")->row()->i_nota_id;
        } else {
            $i_nota_id_mulai = '( Pilih Nota )';
        }

        if ($i_nota_akhir != 0) {
            $i_nota_id_akhir = $this->db->query("SELECT i_nota_id||' ( '||to_char(d_nota, 'dd-mm-yyyy')||' )' AS i_nota_id FROM tm_nota WHERE i_nota_id = '$i_nota_akhir' AND f_nota_cancel = 'f' AND i_company = '$this->i_company' ")->row()->i_nota_id;
        } else {
            $i_nota_id_akhir = '( Pilih Nota )';
        }

        $data = array(
            'i_nota_mulai'      => $i_nota_mulai,
            'i_nota_akhir'      => $i_nota_akhir,
            'i_nota_id_mulai'   => $i_nota_id_mulai,
            'i_nota_id_akhir'   => $i_nota_id_akhir
        );

        $this->logger->write('Membuka Menu ' . $this->title);
        $this->template->load('main', $this->folder . '/index', $data);
    }

    /** List Data */
    public function serverside()
    {
        echo $this->mymodel->serverside();
    }

    public function get_nota_mulai() {
		$filter = [];
		$cari 	= str_replace("'", "", $this->input->get('q'));
		// if ($cari != '') {
		$data = $this->mymodel->get_nota_mulai($cari);
		foreach ($data->result() as $row) {
			$filter[] = array(
				'id'   => $row->i_nota_id,
				'text' => $row->i_nota_id . ' ( ' . $row->d_nota. ' )',
			);
		}
		// } else {
		// 	$filter[] = array(
		// 		'id'   => null,
		// 		'text' => $this->lang->line('Ketik Untuk Cari Data'),
		// 	);
		// }

		echo json_encode($filter);
	}

	public function get_nota_akhir() {
		$filter = [];
		$cari 	= str_replace("'", "", $this->input->get('q'));
		if ($this->input->get('i_nota_mulai') != '') {
			$data = $this->mymodel->get_nota_akhir($cari, $this->input->get('i_nota_mulai'));
			foreach ($data->result() as $row) {
				$filter[] = array(
					'id'   => $row->i_nota_id,
					'text' => $row->i_nota_id . ' ( ' . $row->d_nota. ' )',
				);
			}
		} else {
			$filter[] = array(
				'id'   => null,
				'text' => $this->lang->line('Nomor Awal Harus Di Isi'),
			);
		}

		echo json_encode($filter);
	}

    /** Export CSV */

    public function export_csv()
    {
        $data = check_role($this->id_menu, 2);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $i_nota_mulai = ($this->input->post('i_nota_mulai', TRUE) != '' ? $this->input->post('i_nota_mulai', TRUE) : $this->uri->segment(3));
        $i_nota_akhir = ($this->input->post('i_nota_akhir', TRUE) != '' ? $this->input->post('i_nota_akhir', TRUE) : $this->uri->segment(4));
        $now = gmdate("D, d M Y H:i:s");
        $filename = "E-faktur_" . $i_nota_mulai . "_" . $i_nota_akhir . ".csv";
        header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
        header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");
        header("Last-Modified: {$now} GMT");

        // force download  
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");

        // disposition / encoding on response body
        header("Content-Disposition: attachment;filename={$filename}");
        header("Content-Transfer-Encoding: binary");

        //$out = fopen('php://output', 'w');

        $out = fopen('php://output', 'w');
        //$out    = fopen('export/pajakkeluaran/'.$filename,'w');
        fputcsv($out, array("FK", "KD_JENIS_TRANSAKSI", "FG_PENGGANTI", "NOMOR_FAKTUR", "MASA_PAJAK", "TAHUN_PAJAK", "TANGGAL_FAKTUR", "NPWP", "NAMA", "ALAMAT_LENGKAP", "JUMLAH_DPP", "JUMLAH_PPN", "JUMLAH_PPNMBM", "ID_KETERANGAN_TAMBAHAN", "FG_UANG_MUKA", "UANG_MUKA_DPP", "UANG_MUKA_PPN", "UANG_MUKA_PPNBM", "REFERENSI"));
        fputcsv($out, array("LT", "NPWP", "NAMA", "JALAN", "BLOK", "NOMOR", "RT", "RW", "KECAMATAN", "KELURAHAN", "KABUPATEN", "PROPINSI", "KODE_POS", "NOMOR_TELEPON"));
        fputcsv($out, array("OF", "KODE_OBJEK", "NAMA", "HARGA_SATUAN", "JUMLAH_BARANG", "HARGA_TOTAL", "DISKON", "DPP", "PPN", "TARIF_PPNBM", "PPNBM"));
        $header = $this->mymodel->data_header($i_nota_mulai, $i_nota_akhir);
        if ($header->num_rows() > 0) {
            foreach ($header->result() as $key) {
                fputcsv($out, array("FK", "01", 0, "$key->nomor_faktur", "$key->masa_pajak", "$key->tahun_pajak", "$key->tanggal_faktur", "$key->npwp", "$key->nama", "$key->alamat", "$key->dpp", "$key->ppn", 0, "", 0, 0, 0, 0, "$key->i_nota_id"));
                $detail = $this->mymodel->data_detail($key->i_nota);
                if ($detail->num_rows() > 0) {
                    foreach ($detail->result() as $row) {
                        $dpp = $row->harga_total - $row->diskon;
                        $ppn = round($dpp * ($this->session->n_ppn/100));
                        fputcsv($out, array("OF", $row->kode_objek, $row->nama, $row->harga_satuan, $row->jumlah_barang, $row->harga_total, $row->diskon, $dpp, $ppn, "0", "0"));
                    }
                }
            }
        }
        fclose($out);
        die();
    }
}
