<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mdaftardinas extends CI_Model
{

    /** List Datatable */
    public function serverside()
    {
        $dfrom = $this->input->post('dfrom', TRUE);
        if ($dfrom == '') {
            $dfrom = $this->uri->segment(3);
        }

        $dto = $this->input->post('dto', TRUE);
        if ($dto == '') {
            $dto = $this->uri->segment(4);
        }

        $i_store = $this->input->post('i_store', TRUE);
        if ($i_store == '') {
            $i_store = $this->uri->segment(5);
        }

        if ($i_store != '0') {
            $store = "AND a.i_store = '$i_store' ";
        } else {
            $store = "";
        }

        $dfrom  = date('Y-m-d', strtotime($dfrom));
        $dto    = date('Y-m-d', strtotime($dto));

        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("SELECT distinct
                d_dinas as tgl,
                i_dinas AS id,
                d_dinas,
                i_dinas_id,
                e_staff_name,
                e_area,
                e_kota,
                n_lama_dinas || '  Hari' as n_lama_dinas,
                to_char(d_berangkat, 'DD FMMonth YYYY') AS d_berangkat,
                to_char(d_kembali, 'DD FMMonth YYYY') AS d_kembali,
                v_anggaran_biaya::money as v_anggaran_biaya,
                v_spb_target::money as v_spb_target,
                f_dinas_cancel AS f_status,
                case when a.d_dcc is not null then 'TIDAK DISETUJUI : ' || a.i_dcc || ' : ' || a.e_dcc  else d.e_status_dn_name end as e_status_dn_name,
                d_acc1,  
                d_acc2,  
                d_acc3,  
                d_acc4,  
                d_acc5,  
                i_acc1,
                i_acc2,
                i_acc3,
                i_acc4,
                i_acc5,  
                d_dcc, 
                v_transfer::money as v_transfer,
                v_transfer as v_transfer0,
                to_char(d_dinas, 'YYYYMM') as i_periode,        
                '$dfrom' AS dfrom,
                '$dto' AS dto,
                '$i_store' AS i_store
            FROM
                tm_dinas a
            INNER JOIN tr_status_dn d ON (d.i_status_dn = a.i_status_dn)
            INNER JOIN tm_user_store su ON (su.i_store = a.i_store)
            WHERE
                a.i_company = '$this->i_company'
                AND d_dinas BETWEEN '$dfrom' AND '$dto'
                AND su.i_user = '$this->i_user'
                $store
            ORDER BY
                1 DESC 
        ", FALSE);

        $datatables->edit('f_status', function ($data) {
            if ($data['f_status'] == 't') {
                $status = $this->lang->line('Batal');
                $color  = 'red';
            } elseif ($data['e_status_dn_name'] == "SELESAI") {
                $color  = 'black';
                $status = "SELESAI";
            } elseif ($data['e_status_dn_name'] == "SUDAH DISETUJUI") {
                $color  = 'black';
                $status = "SUDAH DISETUJUI";
            } elseif ($data['e_status_dn_name'] == "Menunggu Approve GM") {
                $color  = 'amber';
                $status = "Menunggu Approve GM";
            } elseif ($data['e_status_dn_name'] == "Menunggu Approve FADH") {
                $color  = 'purple';
                $status = "Menunggu Approve FADH";
            } elseif ($data['e_status_dn_name'] == "Menunggu Approve SDH") {
                $color  = 'blue';
                $status = "Menunggu Approve SDH";
            } elseif ($data['e_status_dn_name'] == "Menunggu Approve Supervisor") {
                $color  = 'teal';
                $status = "Menunggu Approve Supervisor";
            } elseif ($data['e_status_dn_name'] == "Menunggu Approve Admin Sales") {
                $color  = 'success';
                $status = "Menunggu Approve Admin Sales";
            } else {
                $color  = 'red';
                $status = $data['e_status_dn_name'];
            }
            $data = "<span class='badge bg-" . $color . " badge-pill'>" . $status . "</span>";
            return $data;
        });

        /** Cek Hak Akses, Apakah User Bisa Edit */
        $datatables->add('action', function ($data) {
            $id         = trim($data['id']);
            $f_status   = $data['f_status'];
            $d_acc1     = $data['d_acc1'];
            $d_acc2     = $data['d_acc2'];
            $d_acc3     = $data['d_acc3'];
            $d_acc4     = $data['d_acc4'];
            $d_acc5     = $data['d_acc5'];
            $d_dcc      = $data['d_dcc'];
            $v_transfer = $data['v_transfer0'];
            $dfrom      = $data['dfrom'];
            $dto        = $data['dto'];
            $i_store     = $data['i_store'];
            $i_periode  = $data['i_periode'];
            $data       = '';
            $data      .= "<a href='" . base_url() . $this->folder . '/view/' . encrypt_url($id) . '/' . encrypt_url($dfrom) . '/' . encrypt_url($dto) . '/' . encrypt_url($i_store) . "' title='View Data'><i class='fa fa-eye fa-lg warning darken-4 mr-1'></i></a>";
            // if ($i_periode >= get_periode()) {
                if (check_role($this->i_menu, 3) && ($d_dcc == null || $d_dcc == '') && ($d_acc1 == null || $d_acc1 == '') && ($d_acc2 == null || $d_acc2 == '') && ($d_acc3 == null || $d_acc3 == '') && ($d_acc4 == null || $d_acc4 == '') && ($d_acc5 == null || $d_acc5 == '')) {
                    $data      .= "<a href='" . base_url() . $this->folder . '/edit/' . encrypt_url($id)  . '/' .  encrypt_url($dfrom) . '/' . encrypt_url($dto) . '/' . encrypt_url($i_store) . "' title='Edit Data'><i class='fa fa-pencil-square success darken-4 fa-lg mr-1'></i></a>";
                }
                if (check_role($this->i_menu, 3) && ($v_transfer != 0 )){
                    $data      .= "<a href='" . base_url() . $this->folder . '/edit2/' . encrypt_url($id)  . '/' .  encrypt_url($dfrom) . '/' . encrypt_url($dto) . '/' . encrypt_url($i_store) . "' title='Edit Data'><i class='fa fa-pencil-square success darken-4 fa-lg mr-1'></i></a>";
                }
                // if (check_role($this->i_menu, 5) && ($d_dcc == null || $d_dcc == '')) {
                //     $data      .= "<a href='#' onclick='openLink(\"" . $this->folder . "\",\"" . encrypt_url($id) . "\"); return false;' title='Print Data'><i class='fa fa-print fa-lg blue darken-4 mr-1'></i></a>";
                // }
                if (check_role($this->i_menu, 4)) {
                    $data      .= "<a href='#' onclick='sweetdeletev33a(\"" . $this->folder . "\",\"" . $id . "\",\"" . encrypt_url($dfrom)  . "\",\"" . encrypt_url($dto)  . '/' . encrypt_url($i_store) . "\");' title='Delete Data'><i class='fa fa-trash fa-lg red darken-4'></i></a>";
                }
            // }
            return $data;
        });
        $datatables->hide('i_periode');
        $datatables->hide('id');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('i_store');
        $datatables->hide('e_status_dn_name');
        $datatables->hide('d_dcc');
        $datatables->hide('v_transfer0');
        $datatables->hide('d_acc1');
        $datatables->hide('d_acc2');
        $datatables->hide('d_acc3');
        $datatables->hide('d_acc4');
        $datatables->hide('d_acc5');
        return $datatables->generate();
    }

    /** Running Number Dokumen*/
    public function running_number($thbl, $tahun)
    {
        $cek = $this->db->query("SELECT 
                 substring(i_dinas_id, 1, 3) AS kode 
             FROM tm_dinas 
             WHERE i_company = '$this->i_company'
             ORDER BY i_dinas DESC");
        if ($cek->num_rows() > 0) {
            $kode = $cek->row()->kode;
        } else {
            $kode = 'DNS';
        }
        $query  = $this->db->query("SELECT
                 max(substring(i_dinas_id, 10, 6)) AS max
             FROM
                tm_dinas
             WHERE to_char (d_dinas, 'yyyy') >= '$tahun'
             AND i_company = '$this->i_company'
             AND substring(i_dinas_id, 1, 3) = '$kode'
             AND substring(i_dinas_id, 5, 2) = substring('$thbl',1,2)
         ", false);
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $no = $row->max;
            }
            $number = $no + 1;
            settype($number, "string");
            $n = strlen($number);
            while ($n < 6) {
                $number = "0" . $number;
                $n = strlen($number);
            }
            $number = $kode . "-" . $thbl . "-" . $number;
            return $number;
        } else {
            $number = "000001";
            $nomer  = $kode . "-" . $thbl . "-" . $number;
            return $nomer;
        }
    }

    /** Cek Apakah Data Sudah Ada */
    public function cek_code()
    {
        $i_document = $this->input->post('i_document', TRUE);
        return $this->db->query("SELECT 
                i_gs_id
            FROM 
                tm_gs2
            WHERE 
                upper(trim(i_gs_id)) = upper(trim('$i_document'))
                AND i_company = '$this->i_company'
        ", FALSE);
    }
    
    public function get_store0($cari)
    {
        return $this->db->query("SELECT 
                a.i_store, 
                d.e_store_loc_name AS e_name
            FROM 
                tm_user_store a 
            INNER JOIN tr_store_loc d 
                ON (a.i_store = d.i_store) 
            WHERE 
                (e_store_loc_name ILIKE '%$cari%')
                AND a.i_company = '$this->i_company' 
                AND f_store_loc_active = true
                AND a.i_user = '$this->i_user' 
            ORDER BY 1 ASC
                ", FALSE);
    }

    public function get_store7($cari)
    {
        return $this->db->query("SELECT
                a.i_store AS id,
                a.e_store_loc_name AS e_name
            FROM
                tr_store_loc a
            INNER JOIN tm_user_store d on (d.i_store = a.i_store AND d.i_user = '$this->i_user')
            WHERE
                (e_store_loc_name ILIKE '%$cari%')
                AND a.f_store_loc_active = 't'
                AND a.i_company = '$this->i_company'
            ORDER BY
                1;
        ", FALSE);
    }
    
    /** Get Data Gudang */
    public function get_dn_atasan($cari)
    {
        return $this->db->query("SELECT 
            *
        FROM 
            tr_dn_atasan        
        WHERE 
            (e_dn_atasan_name ILIKE '%$cari%')
            AND f_dn_atasan_active = true
        ORDER BY 1 ASC
        ", FALSE);
    }
    public function get_dn_departement($cari)
    {
        return $this->db->query("SELECT 
            *
        FROM 
            tr_dn_departement       
        WHERE 
            (e_dn_departement_name ILIKE '%$cari%')
            AND f_dn_departement_active = true
        ORDER BY 1 ASC
        ", FALSE);
    }
    public function get_dn_jabatan($cari)
    {
        return $this->db->query("SELECT 
            *
        FROM 
            tr_dn_jabatan        
        WHERE 
            (e_dn_jabatan_name ILIKE '%$cari%')
            AND f_dn_jabatan_active = true
        ORDER BY 1 ASC
        ", FALSE);
    }


    /** Simpan Data */
    public function save()
    {
        $query = $this->db->query("SELECT max(i_dinas)+1 AS id FROM tm_dinas", TRUE);
        if ($query->num_rows() > 0) {
            $id = $query->row()->id;
            if ($id == null) {
                $id = 1;
            } else {
                $id = $id;
            }
        } else {
            $id = 1;
        }

        $f_divisi       = ($this->input->post('f_divisi') == 'on') ? 'f' : 't';
        $i_stat         = ($this->input->post('i_dn_jabatan') == '1') ? '1' : '2';
        $i_dn           = ($this->input->post('i_dn_jabatan'));
        switch($i_dn){
            case '1':
                $dn = '1';
                break;
            case '2':
                $dn = '2';
                break;
            case '3':
                $dn = '2';
                break;
            case '4':
                $dn = '2';
                break;
            case '5':
                $dn = '5';
                break;
            case '6':
                $dn = '5';
                break;
        }
        $v_tiket1       = str_replace(",", "", $this->input->post('v_tiket1'));
        $v_tiket2       = str_replace(",", "", $this->input->post('v_tiket2'));
        $v_tol          = str_replace(",", "", $this->input->post('v_tol'));
        $v_bbm          = str_replace(",", "", $this->input->post('v_bbm'));
        $v_parkir       = str_replace(",", "", $this->input->post('v_parkir'));
        $v_penginapan   = str_replace(",", "", $this->input->post('v_penginapan'));
        $v_laundry      = str_replace(",", "", $this->input->post('v_laundry'));
        $v_uangmakan    = str_replace(",", "", $this->input->post('v_uangmakan'));
        $v_lainlain     = str_replace(",", "", $this->input->post('v_lainlain'));
        $v_spb_realisasi= str_replace(",", "", $this->input->post('v_spb_realisasi'));
        $n_biaya_vs_spb = str_replace(",", "", $this->input->post('n_biaya_vs_spb'));
        $v_nota_tertagih= str_replace(",", "", $this->input->post('v_nota_tertagih'));
        $v_realisasi_biaya= str_replace(",", "", $this->input->post('v_realisasi_biaya'));
        $v_tiket1       = (strlen($v_tiket1)>0) ? $v_tiket1 : 0 ;
        $v_tiket2       = (strlen($v_tiket2)>0) ? $v_tiket2 : 0 ;
        $v_tol          = (strlen($v_tol)>0) ? $v_tol : 0 ;
        $v_bbm          = (strlen($v_bbm)>0) ? $v_bbm : 0 ;
        $v_parkir       = (strlen($v_parkir)>0) ? $v_parkir : 0 ;
        $v_penginapan   = (strlen($v_penginapan)>0) ? $v_penginapan : 0 ;
        $v_laundry      = (strlen($v_laundry)>0) ? $v_laundry : 0 ;
        $v_uangmakan    = (strlen($v_uangmakan)>0) ? $v_uangmakan : 0 ;
        $v_lainlain     = (strlen($v_lainlain)>0) ? $v_lainlain : 0 ;
        $v_spb_realisasi= (strlen($v_spb_realisasi)>0) ? $v_spb_realisasi : 0 ;
        $n_biaya_vs_spb = (strlen($n_biaya_vs_spb)>0) ? $n_biaya_vs_spb : 0 ;
        $v_nota_tertagih= (strlen($v_nota_tertagih)>0) ? $v_nota_tertagih : 0 ;
        $v_realisasi_biaya= (strlen($v_realisasi_biaya)>0) ? $v_realisasi_biaya : 0 ;

        $header = array(
            'i_company'         => $this->session->i_company,
            'i_dinas'           => $id,
            'i_dinas_id'        => strtoupper($this->input->post('i_document')),
            'e_staff_name'      => $this->input->post('e_staff_name'),
            'f_pusat'           => $f_divisi,
            'd_dinas'           => $this->input->post('d_document'),
            'd_dinas_entry'     => current_datetime(),
            'i_store'           => $this->input->post('i_store7'),
            'i_dn_atasan'       => $this->input->post('i_dn_atasan'),
            'i_dn_departement'  => $this->input->post('i_dn_departement'),
            'i_dn_jabatan'      => $this->input->post('i_dn_jabatan'),
            'e_area'            => $this->input->post('e_area'),
            'e_kota'            => $this->input->post('e_kota'),
            'n_lama_dinas'      => str_replace(",", "", $this->input->post('n_lama_dinas')),
            'd_berangkat'       => $this->input->post('d_berangkat'),
            'd_kembali'         => $this->input->post('d_kembali'),
            'e_remark'          => $this->input->post('e_remark'),
            'v_anggaran_biaya'  => str_replace(",", "", $this->input->post('v_anggaran_biaya')),
            'v_tiket1'          => $v_tiket1,
            'v_tiket2'          => $v_tiket2,
            'v_tol'             => $v_tol,
            'v_bbm'             => $v_bbm,
            'v_parkir'          => $v_parkir,
            'v_penginapan'      => $v_penginapan,
            'v_laundry'         => $v_laundry,
            'v_uangmakan'       => $v_uangmakan,
            'v_lainlain'        => $v_lainlain,
            'v_spb_target'      => str_replace(",", "", $this->input->post('v_spb_target')),
            'v_spb_realisasi'   => $v_spb_realisasi,
            'n_biaya_vs_spb'    => $n_biaya_vs_spb,
            'v_nota_tertagih'   => $v_nota_tertagih,
            'v_realisasi_biaya' => $v_realisasi_biaya,
            'i_user'            => $this->input->post('i_user'),
            'e_user_name'       => $this->input->post('e_user_name'),
            'i_status_dn'       => $dn,
        );
        $this->db->insert('tm_dinas', $header);
        
    }

    /** Get Data Untuk Edit */
    public function getdata($id)
    {
        return $this->db->query("SELECT 
                a.*,
                b.e_dn_atasan_name ,
                c.e_dn_departement_name ,
                d.e_dn_jabatan_name ,
                e.e_store_loc_name 
            FROM 
                tm_dinas a
                inner join tr_dn_atasan b on (b.i_dn_atasan=a.i_dn_atasan)
                inner join tr_dn_departement c on (c.i_dn_departement=a.i_dn_departement)
                inner join tr_dn_jabatan d on (d.i_dn_jabatan=a.i_dn_jabatan)
                inner join tr_store_loc e on (e.i_store=a.i_store)
            WHERE
                a.i_dinas = '$id'
        ", FALSE);
    }

    /** Cek Apakah Data Sudah Ada Pas Edit */
    public function cek_edit()
    {
        $i_document = $this->input->post('i_document', TRUE);
        $i_document_old = $this->input->post('i_document_old', TRUE);
        return $this->db->query("SELECT 
                i_dinas_id
            FROM 
                tm_dinas
            WHERE 
                trim(upper(i_dinas_id)) <> trim(upper('$i_document_old'))
                AND trim(upper(i_dinas_id)) = trim(upper('$i_document'))
                AND i_company = '$this->i_company'
        ", FALSE);
    }

    /** Update Data */
    public function update()
    {
        $id = $this->input->post('id');
        $f_divisi = ($this->input->post('f_divisi') == 'on') ? 't' : 'f';
        $i_stat = ($this->input->post('i_dn_jabatan') == '1') ? '1' : '2';
        $i_dn           = ($this->input->post('i_dn_jabatan'));
        switch($i_dn){
            case '1':
                $dn = '1';
                break;
            case '2':
                $dn = '2';
                break;
            case '3':
                $dn = '2';
                break;
            case '4':
                $dn = '2';
                break;
            case '5':
                $dn = '5';
                break;
            case '6':
                $dn = '5';
                break;
        }
        $v_tiket1       = str_replace(",", "", $this->input->post('v_tiket1'));
        $v_tiket2       = str_replace(",", "", $this->input->post('v_tiket2'));
        $v_tol          = str_replace(",", "", $this->input->post('v_tol'));
        $v_bbm          = str_replace(",", "", $this->input->post('v_bbm'));
        $v_parkir       = str_replace(",", "", $this->input->post('v_parkir'));
        $v_penginapan   = str_replace(",", "", $this->input->post('v_penginapan'));
        $v_laundry      = str_replace(",", "", $this->input->post('v_laundry'));
        $v_uangmakan    = str_replace(",", "", $this->input->post('v_uangmakan'));
        $v_lainlain     = str_replace(",", "", $this->input->post('v_lainlain'));
        $v_spb_realisasi= str_replace(",", "", $this->input->post('v_spb_realisasi'));
        $n_biaya_vs_spb = str_replace(",", "", $this->input->post('n_biaya_vs_spb'));
        $v_nota_tertagih= str_replace(",", "", $this->input->post('v_nota_tertagih'));
        $v_realisasi_biaya= str_replace(",", "", $this->input->post('v_realisasi_biaya'));
        $v_tiket1       = (strlen($v_tiket1)>0) ? $v_tiket1 : 0 ;
        $v_tiket2       = (strlen($v_tiket2)>0) ? $v_tiket2 : 0 ;
        $v_tol          = (strlen($v_tol)>0) ? $v_tol : 0 ;
        $v_bbm          = (strlen($v_bbm)>0) ? $v_bbm : 0 ;
        $v_parkir       = (strlen($v_parkir)>0) ? $v_parkir : 0 ;
        $v_penginapan   = (strlen($v_penginapan)>0) ? $v_penginapan : 0 ;
        $v_laundry      = (strlen($v_laundry)>0) ? $v_laundry : 0 ;
        $v_uangmakan    = (strlen($v_uangmakan)>0) ? $v_uangmakan : 0 ;
        $v_lainlain     = (strlen($v_lainlain)>0) ? $v_lainlain : 0 ;
        $v_spb_realisasi= (strlen($v_spb_realisasi)>0) ? $v_spb_realisasi : 0 ;
        $n_biaya_vs_spb = (strlen($n_biaya_vs_spb)>0) ? $n_biaya_vs_spb : 0 ;
        $v_nota_tertagih= (strlen($v_nota_tertagih)>0) ? $v_nota_tertagih : 0 ;
        $v_realisasi_biaya= (strlen($v_realisasi_biaya)>0) ? $v_realisasi_biaya : 0 ;

        $header = array(
            'i_company'         => $this->session->i_company,
            'i_dinas'           => $id,
            'i_dinas_id'        => strtoupper($this->input->post('i_document')),
            'e_staff_name'      => $this->input->post('e_staff_name'),
            'f_pusat'           => $f_divisi,
            'd_dinas'           => $this->input->post('d_document'),
            'd_dinas_update'    => current_datetime(),
            'i_store'           => $this->input->post('i_store7'),
            'i_dn_atasan'       => $this->input->post('i_dn_atasan'),
            'i_dn_departement'  => $this->input->post('i_dn_departement'),
            'i_dn_jabatan'      => $this->input->post('i_dn_jabatan'),
            'e_area'            => $this->input->post('e_area'),
            'e_kota'            => $this->input->post('e_kota'),
            'n_lama_dinas'      => str_replace(",", "", $this->input->post('n_lama_dinas')),
            'd_berangkat'       => $this->input->post('d_berangkat'),
            'd_kembali'         => $this->input->post('d_kembali'),
            'e_remark'          => $this->input->post('e_remark'),
            'v_anggaran_biaya'  => str_replace(",", "", $this->input->post('v_anggaran_biaya')),
            'v_tiket1'          => $v_tiket1,
            'v_tiket2'          => $v_tiket2,
            'v_tol'             => $v_tol,
            'v_bbm'             => $v_bbm,
            'v_parkir'          => $v_parkir,
            'v_penginapan'      => $v_penginapan,
            'v_laundry'         => $v_laundry,
            'v_uangmakan'       => $v_uangmakan,
            'v_lainlain'        => $v_lainlain,
            'v_spb_target'      => str_replace(",", "", $this->input->post('v_spb_target')),
            'v_spb_realisasi'   => $v_spb_realisasi,
            'n_biaya_vs_spb'    => $n_biaya_vs_spb,
            'v_nota_tertagih'   => $v_nota_tertagih,
            'v_realisasi_biaya'   => $v_realisasi_biaya,
            'i_user'            => $this->input->post('i_user'),
            'e_user_name'       => $this->input->post('e_user_name'),
            'i_status_dn'       => $dn,
        );
        $this->db->where('i_dinas', $id);
        $this->db->update('tm_dinas', $header);        
    }

    public function update2()
    {
        $id             = $this->input->post('id');
        $f_selesai      = ($this->input->post('f_selesai') == 'on') ? 't' : 'f';
        switch($f_selesai){
            case 'f':
                $dn2 = '6';
                break;
            case 't':
                $dn2 = '7';
                break;
        }

        $v_spb_realisasi= str_replace(",", "", $this->input->post('v_spb_realisasi'));
        $n_biaya_vs_spb = str_replace(",", "", $this->input->post('n_biaya_vs_spb'));
        $v_nota_tertagih= str_replace(",", "", $this->input->post('v_nota_tertagih'));
        $v_realisasi_biaya= str_replace(",", "", $this->input->post('v_realisasi_biaya'));
        $v_spb_realisasi= (strlen($v_spb_realisasi)>0) ? $v_spb_realisasi : 0 ;
        $n_biaya_vs_spb = (strlen($n_biaya_vs_spb)>0) ? $n_biaya_vs_spb : 0 ;
        $v_nota_tertagih= (strlen($v_nota_tertagih)>0) ? $v_nota_tertagih : 0 ;
        $v_realisasi_biaya= (strlen($v_realisasi_biaya)>0) ? $v_realisasi_biaya : 0 ;

        $header = array(
            'i_company'         => $this->session->i_company,
            'i_dinas'           => $id,
            'v_spb_realisasi'   => $v_spb_realisasi,
            'n_biaya_vs_spb'    => $n_biaya_vs_spb,
            'v_nota_tertagih'   => $v_nota_tertagih,
            'v_realisasi_biaya' => $v_realisasi_biaya,
            'i_status_dn'       => $dn2,
            'f_selesai'         => $f_selesai,
        );
        $this->db->where('i_dinas', $id);
        $this->db->update('tm_dinas', $header);        
    }

    /** Cancel Data */
    public function cancel($id)
    {
        $table = array(
            'f_dinas_cancel' => 't',
        );
        $this->db->where('i_dinas', $id);
        $this->db->update('tm_dinas', $table);
    }

}

/* End of file Mmaster.php */
