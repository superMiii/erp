<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mkumasuk extends CI_Model
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

        $i_area = $this->input->post('i_area', TRUE);
        if ($i_area == '') {
            $i_area = $this->uri->segment(5);
        }

        if ($i_area != '0') {
            $area = "AND a.i_area = '$i_area' ";
        } else {
            $area = "";
        }

        $dfrom  = date('Y-m-d', strtotime($dfrom));
        $dto    = date('Y-m-d', strtotime($dto));

        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("SELECT distinct
                a.d_kum AS tgl,
                a.d_kum,
                a.i_kum_id,
                to_char(a.d_kum, 'YYYYMM') as i_periode,
                b.e_area_name,
                c.i_customer_id || ' ~ ' || c.e_customer_name AS e_customer,
                d.e_salesman_name,             
	            a.e_atasnama as e_atasnama,  
                v_jumlah::money AS v_jumlah,               
	            w.i_rv_id as f_referensi,
                w.alok as alok,
                f_kum_cancel AS f_status,
                '$dfrom' AS dfrom,
                '$dto' AS dto,
                a.i_kum as id,
                '$i_area' AS i_area
            FROM
                tm_kum a
            INNER JOIN tr_area b ON
                (b.i_area = a.i_area)
            INNER JOIN tr_customer c ON
                (c.i_customer = a.i_customer)
            INNER JOIN tr_salesman d ON
                (d.i_salesman = a.i_salesman)
            INNER JOIN tm_user_area e ON
                (e.i_area = a.i_area)
            left join (SELECT
                        y.i_company,
                        y.e_rv_refference_type_name,
                        z.i_rv,
                        z.i_rv_id,
                        i_rv_refference,
                        case when t.v_rv = t.v_rv_saldo then 0 else 1 end as alok
                    from
                        tm_rv_item t
                        inner join tr_rv_refference_type y on (y.i_rv_refference_type = t.i_rv_refference_type)
                        inner join tm_rv z on (z.i_rv=t.i_rv)
                        WHERE z.i_company = '$this->i_company' AND y.f_transfer = 't') w on 
            (w.i_rv_refference=a.i_kum)               
            WHERE
                a.i_company = '$this->i_company'
                AND e.i_user = '$this->i_user'
                AND a.d_kum BETWEEN '$dfrom' AND '$dto'
                $area
            ORDER BY
                1 DESC
        ", FALSE);

        // $datatables->edit('f_referensi', function ($data) {
        //     if ($data['f_referensi'] != '') {
        //         $status = $this->lang->line('Belum Digunakan');
        //         $color  = 'teal';
        //     } else {
        //         $color  = 'red';
        //         $status = $this->lang->line('Sudah Digunakan');
        //     }
        //     $data = "<span class='badge bg-" . $color . " badge-pill'>" . $status . "</span>";
        //     return $data;
        // });

        $datatables->edit('f_status', function ($data) {
            if ($data['f_status'] == 't') {
                $status = $this->lang->line('Tidak Aktif');
                $color  = 'red';
            } else {
                $color  = 'teal';
                $status = $this->lang->line('Aktif');
            }
            $data = "<span class='badge bg-" . $color . " badge-pill'>" . $status . "</span>";
            return $data;
        });

        /** Cek Hak Akses, Apakah User Bisa Edit */
        $datatables->add('action', function ($data) {
            $id         = trim($data['id']);
            $f_status   = $data['f_status'];
            $dfrom      = $data['dfrom'];
            $dto        = $data['dto'];
            $i_area     = $data['i_area'];
            $i_periode  = $data['i_periode'];
            $alok       = $data['alok'];
            $data       = '';
            $data      .= "<a href='" . base_url() . $this->folder . '/view/' . encrypt_url($id) . '/' . encrypt_url($dfrom) . '/' . encrypt_url($dto) . '/' . encrypt_url($i_area) . "' title='View Data'><i class='fa fa-eye fa-lg warning darken-4 mr-1'></i></a>";
            if ($i_periode >= get_periode()) {
                if (check_role($this->id_menu, 3) && $f_status == 'f' && ($alok == 0)) {
                    $data      .= "<a href='" . base_url() . $this->folder . '/edit/' . encrypt_url($id) . '/' . encrypt_url($dfrom) . '/' . encrypt_url($dto) . '/' . encrypt_url($i_area) . "' title='Edit Data'><i class='fa fa-pencil-square success darken-4 fa-lg mr-1'></i></a>";
                }
                if (check_role($this->id_menu, 5) && $f_status == 'f') {
                    $data      .= "<a href='#' onclick='openLink(\"" . $this->folder . "\",\"" . encrypt_url($id) . "\"); return false;' title='Print Data'><i class='fa fa-print fa-lg blue darken-4 mr-1'></i></a>";
                }
                if (check_role($this->id_menu, 4) && $f_status == 'f' && ($alok == 0)) {
                    $data      .= "<a href='#' onclick='sweetdeletev33raya3(\"" . $this->folder . "\",\"" . $id . "\",\"" . encrypt_url($dfrom)  . "\",\"" . encrypt_url($dto)  . '/' . encrypt_url($i_area) . "\");' title='Delete Data'><i class='fa fa-trash fa-lg red darken-4'></i></a>";
                }
            }
            return $data;
        });
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('i_periode');
        $datatables->hide('id');
        $datatables->hide('i_area');
        $datatables->hide('alok');
        return $datatables->generate();
    }


    /** Get Area */
    public function get_area($cari)
    {
        return $this->db->query("SELECT 
                DISTINCT
                a.i_area, 
                i_area_id, 
                initcap(e_area_name) AS e_area_name
            FROM 
                tr_area a
            INNER JOIN tm_user_area b 
                ON (b.i_area = a.i_area) 
            WHERE 
                (e_area_name ILIKE '%$cari%' OR i_area_id ILIKE '%$cari%')
                AND a.i_company = '$this->i_company' 
                AND f_area_active = true
                AND b.i_user = '$this->i_user' 
            ORDER BY 3 ASC
        ", FALSE);
    }

    /** Get Customer */
    public function get_customer($cari, $i_area)
    {
        return $this->db->query("
            SELECT 
                i_customer, i_customer_id , e_customer_name
            FROM 
                tr_customer
            WHERE 
                (e_customer_name ILIKE '%$cari%' OR i_customer_id ILIKE '%$cari%')
                AND i_company = '$this->i_company' 
                AND i_area = '$i_area'
            ORDER BY 3 ASC
        ", FALSE);
    }

    /** Get Salesman */
    public function get_salesman($cari, $i_customer)
    {
        return $this->db->query("
            SELECT
                a.i_salesman,
                a.i_salesman_id ,
                a.e_salesman_name
            FROM
                tr_salesman a
            INNER JOIN tr_salesman_areacover b ON
                (a.i_salesman = b.i_salesman)
            WHERE
                (a.e_salesman_name ILIKE '%$cari%'
                    OR a.i_salesman_id ILIKE '%$cari%')
                AND a.i_company = '$this->i_company'
                AND f_salesman_active = TRUE
                AND b.i_area_cover IN (
                SELECT
                    i_area_cover
                FROM
                    tr_customer
                WHERE
                    i_customer = '$i_customer' )
            GROUP BY
                1
            ORDER BY
                3 ASC
        ", FALSE);
    }

    /** Get DT */
    public function get_dt($cari, $i_area, $i_customer)
    {
        return $this->db->query("SELECT distinct
                a.i_dt, i_dt_id , to_char(d_dt, 'DD Month YYYY') AS d_dt
            FROM 
                tm_dt a 
                inner join tm_dt_item b on (b.i_dt=a.i_dt)
                inner join tm_nota c on (c.i_nota=b.i_nota)
            WHERE 
                (i_dt_id ILIKE '%$cari%')
                AND a.i_company = '$this->i_company' 
                AND f_dt_cancel = 'false' 
                AND a.i_area = '$i_area'
                and c.i_customer = '$i_customer'
            ORDER BY 1 deSC
        ", FALSE);
    }

    /** Get Bank */
    public function get_bank($cari)
    {
        return $this->db->query("
            SELECT 
                i_bank, i_bank_id, e_bank_name
            FROM 
                tr_bank
            WHERE 
                (i_bank_id ILIKE '%$cari%' OR e_bank_name ILIKE '%$cari%')
                AND i_company = '$this->i_company' 
                AND f_bank_active = 'true' 
            ORDER BY 3 ASC
        ", FALSE);
    }


    /** Running Number Dokumen*/
    public function running_number($thbl, $tahun, $bln)
    {
        $cek = $this->db->query("SELECT 
                substring(i_kum_id, 1, 2) AS kode 
            FROM tm_kum 
            WHERE i_company = '$this->i_company'
            ORDER BY i_kum DESC");
        if ($cek->num_rows() > 0) {
            $kode = $cek->row()->kode;
        } else {
            $kode = 'TM';
        }
        $query  = $this->db->query("SELECT
                max(substring(i_kum_id, 9, 6)) AS max
            FROM
                tm_kum
            WHERE to_char (d_kum, 'yyyy') >= '$tahun'
            and to_char (d_kum, 'MM') >= '$bln'
            AND i_company = '$this->i_company'
            AND substring(i_kum_id, 1, 2) = '$kode'
            AND substring(i_kum_id, 4, 2) = substring('$thbl',1,2)
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
        $i_document = str_replace("_", "", $this->input->post('i_document'));
        $d_document = $this->input->post('d_document');
        $i_area = $this->input->post('i_area');
        return $this->db->query("
            SELECT 
                i_kum_id
            FROM 
                tm_kum 
            WHERE 
                upper(trim(i_kum_id)) = upper(trim('$i_document'))
                AND d_kum = '$d_document'
                AND i_area = '$i_area'
                AND i_company = '$this->i_company'
        ", FALSE);
    }

    /** Simpan Data */
    public function save()
    {
        $table = array(
            'i_company'     => $this->i_company,
            'i_kum_id'      => strtoupper($this->input->post('i_document')),
            'i_area'        => $this->input->post('i_area'),
            'i_customer'    => $this->input->post('i_customer'),
            'i_salesman'    => $this->input->post('i_salesman'),
            'd_kum'         => $this->input->post('d_document'),
            'd_entry'       => current_datetime(),
            'e_remark'      => $this->input->post('e_remark'),
            'v_jumlah'      => str_replace(",", "", $this->input->post('v_jumlah')),
            'i_bank'        => $this->input->post('i_bank'),
            'i_dt'          => $this->input->post('i_dt'),
            'e_atasnama'    => $this->input->post('e_atasnama'),
        );
        $this->db->insert('tm_kum', $table);
    }

    /** Get Data Untuk Edit */
    public function get_data($id)
    {
        return $this->db->query("SELECT 
                a.*,
                b.i_area_id,
                b.e_area_name,
                c.i_customer_id,
                c.e_customer_name,
                e.i_salesman_id,
                e.e_salesman_name,
                f.i_bank_id,
                f.e_bank_name,
                d.i_dt_id,                
                w.i_rv as alok,
                to_char(d.d_dt, 'DD FMMonth YYYY') AS d_dt
                /* to_char(d.d_dt, 'DD')||' '||trim(to_char(d.d_dt, 'Month'))||' '||to_char(d.d_dt, 'YYYY') AS d_dt */
            FROM 
                tm_kum a
            INNER JOIN tr_area b ON 
                (b.i_area = a.i_area)
            INNER JOIN tr_customer c ON 
                (c.i_customer = a.i_customer)
            INNER JOIN tr_salesman e ON 
                (e.i_salesman = a.i_salesman)
            LEFT JOIN tm_dt d ON 
                (d.i_dt = a.i_dt)
            LEFT JOIN tr_bank f ON 
                (f.i_bank = a.i_bank)
            left join (SELECT
                    y.i_company,
                    y.e_rv_refference_type_name,
                    z.i_rv,
                    z.i_rv_id,
                    i_rv_refference,
                    case when t.v_rv = t.v_rv_saldo then 0 else 1 end as alok
                from
                    tm_rv_item t
                    inner join tr_rv_refference_type y on (y.i_rv_refference_type = t.i_rv_refference_type)
                    inner join tm_rv z on (z.i_rv=t.i_rv)
                    WHERE z.i_company = '$this->i_company' AND y.f_transfer = 't') w on 
            (w.i_rv_refference=a.i_kum)               
            WHERE i_kum = '$id'
        ", FALSE);
    }

    /** Cek Apakah Data Sudah Ada Pas Edit */
    public function cek_edit()
    {
        $i_document = str_replace("_", "", $this->input->post('i_document'));
        $i_document_old = str_replace("_", "", $this->input->post('i_document_old'));
        $d_document = $this->input->post('d_document');
        $i_area = $this->input->post('i_area');
        return $this->db->query("
            SELECT 
                i_kum_id
            FROM 
                tm_kum 
            WHERE 
                trim(upper(i_kum_id)) <> trim(upper('$i_document_old'))
                AND trim(upper(i_kum_id)) = trim(upper('$i_document'))
                AND d_kum = '$d_document'
                AND i_area = '$i_area'
                AND i_company = '$this->i_company'
         ", FALSE);
    }

    /** Update Data */
    public function update()
    {
        $id = $this->input->post('id');
        $arara = $this->input->post('i_area');
        $i_dt = ($this->input->post('i_dt') == '') ? null :  $this->input->post('i_dt');
        $table = array(
            'i_company'     => $this->i_company,
            'i_kum_id'      => strtoupper($this->input->post('i_document')),
            'i_area'        => $arara,
            'i_customer'    => $this->input->post('i_customer'),
            'i_salesman'    => $this->input->post('i_salesman'),
            'd_kum'         => $this->input->post('d_document'),
            'd_update'      => current_datetime(),
            'e_remark'      => $this->input->post('e_remark'),
            'v_jumlah'      => str_replace(",", "", $this->input->post('v_jumlah')),
            'i_bank'        => $this->input->post('i_bank'),
            'i_dt'          => $i_dt,
            'e_atasnama'    => $this->input->post('e_atasnama'),
        );
        $this->db->where('i_kum', $id);
        $this->db->update('tm_kum', $table);

        $miu = $this->db->query("SELECT t.i_rv_item,
                                t.ara,
                                case when t.v_rv = t.v_rv_saldo then 0 else 1 end as alok
                            from
                                tm_rv_item t
                                inner join tr_rv_refference_type y on (y.i_rv_refference_type = t.i_rv_refference_type)
                                inner join tm_rv z on (z.i_rv=t.i_rv)
                                inner join tm_kum m on (m.i_kum=t.i_rv_refference)
                            WHERE 
                                z.i_company = '$this->i_company' 
                                AND y.f_transfer = 't'
                                and m.i_kum = $id ", FALSE);
        if ($miu->num_rows() > 0) {
            foreach ($miu->result() as $key) {
                $this->db->query("UPDATE tm_rv_item SET ara = $arara WHERE i_rv_item = '$key->i_rv_item' ", FALSE);
            }
        }



    }

    /** Cancel Data */
    public function cancel($id,$alasan,$i_kum_id)
    {
        $table = array(
            'f_kum_cancel' => 't',
        );
        $this->db->where('i_kum', $id);
        $this->db->update('tm_kum', $table);

        $this->db->query("INSERT INTO tx_del (i_company, i_user, e_user_name, e_dokumen, e_alasan)
        VALUES ('$this->i_company', '$this->i_user', '$this->e_user_name', 'No KU Masuk : $i_kum_id', '$alasan')");
    }
}

/* End of file Mmaster.php */
