<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Malokasiknr extends CI_Model
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
        $datatables->query("SELECT
                d_alokasi as tgl,
                i_alokasi AS id,
                d_alokasi,
                i_alokasi_id,
                to_char(d_alokasi, 'YYYYMM') as i_periode,
                b.i_customer_id || ' - ' || initcap(b.e_customer_name) AS e_customer_name,
                '[' || f.i_area_id || '] - ' || f.e_area_name AS e_area,
                g.i_kn_id,
                v_jumlah::money AS v_jumlah,
                v_lebih::money AS v_lebih ,
                f_alokasi_cancel AS f_status,
                '$dfrom' AS dfrom,
                '$dto' AS dto,
                '$i_area' AS i_area
            FROM
                tm_alokasiknr a
            INNER JOIN tr_customer b ON
                (b.i_customer = a.i_customer)
            INNER JOIN tm_user_area e ON
                (e.i_area = a.i_area)
            INNER JOIN tr_area f ON
                (f.i_area = a.i_area)
            inner join tm_kn g on (g.i_kn=a.i_kn)
            WHERE
                a.i_company = '$this->i_company'
                AND e.i_user = '$this->i_user'
                AND a.d_alokasi BETWEEN '$dfrom' AND '$dto'
                $area
            ORDER BY
                1 DESC
        ", FALSE);

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
            $v_jumlah   = $data['v_jumlah'];
            $i_periode  = $data['i_periode'];
            $data       = '';
            $data      .= "<a href='" . base_url() . $this->folder . '/view/' . encrypt_url($id) . '/' . encrypt_url($dfrom) . '/' . encrypt_url($dto) . '/' . encrypt_url($i_area) . "' title='View Data'><i class='fa fa-eye fa-lg warning darken-4 mr-1'></i></a>";
            if ($i_periode >= get_periode()) {
                if (check_role($this->id_menu, 3) && $f_status == 'f') {
                    $data      .= "<a href='" . base_url() . $this->folder . '/edit/' . encrypt_url($id)  . '/' .  encrypt_url($dfrom) . '/' . encrypt_url($dto) . '/' . encrypt_url($i_area) . "' title='Edit Data'><i class='fa fa-pencil success darken-4 fa-lg mr-1'></i></a>";
                }
                if (check_role($this->id_menu, 4) && $f_status == 'f') {
                    $data      .= "<a href='#' onclick='sweetdeletev33raya3(\"" . $this->folder . "\",\"" . $id . "\",\"" . encrypt_url($dfrom)  . "\",\"" . encrypt_url($dto)  . '/' . encrypt_url($i_area) . "\");' title='Delete Data'><i class='fa fa-trash fa-lg red darken-4'></i></a>";
                }
            }
            return $data;
        });
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('i_area');
        $datatables->hide('i_periode');
        $datatables->hide('id');
        return $datatables->generate();
    }

    /** List Datatable */
    public function serversidex()
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
        $datatables->query("SELECT
                a.d_kn as tgl,
                to_char(a.d_kn, 'YYYYMM') as i_periode,
                a.i_kn AS id,
                a.d_kn,
                a.i_kn_id,
                d.e_area_name,
                '[ ' || e.i_customer_id || ' ] - ' || e.e_customer_name AS customer,
                f.e_salesman_name,
                a.v_netto::money AS v_netto,
                a.v_sisa::money AS v_sisa,
                '$dfrom' AS dfrom,
                '$dto' AS dto,
                '$i_area' AS i_area
            FROM
                tm_kn a
            INNER JOIN tr_area d ON
                (d.i_area = a.i_area)
            INNER JOIN tr_customer e ON
                (e.i_customer = a.i_customer)
            INNER JOIN tr_salesman f ON
                (f.i_salesman = a.i_salesman)
            INNER JOIN tm_user_area g ON
                (g.i_area = a.i_area)
            WHERE
                a.i_company = '$this->i_company'
                AND a.d_kn BETWEEN '$dfrom' AND '$dto'
                AND g.i_user = '$this->i_user'
                AND a.f_kn_retur = 't'
                AND a.f_kn_cancel = 'f'
                AND a.v_sisa > 0
                $area
            ORDER BY
                1 ASC
        ", FALSE);

        /** Cek Hak Akses, Apakah User Bisa Edit */
        $datatables->add('action', function ($data) {
            $id         = trim($data['id']);
            $i_periode  = $data['i_periode'];
            $dfrom      = $data['dfrom'];
            $dto        = $data['dto'];
            $i_area     = $data['i_area'];
            $data       = '';
            // if ($i_periode >= get_periode()) {
            if (check_role($this->id_menu, 1)) {
                $data      .= "<a href='" . base_url() . $this->folder . '/add/' . encrypt_url($id) . '/' . encrypt_url($dfrom) . '/' . encrypt_url($dto) . '/' . encrypt_url($i_area) . "' title='Tambah Data'><i class='fa fa-check-circle-o success darken-4 fa-lg mr-1'></i></a>";
            }
            // }
            return $data;
        });
        $datatables->hide('id');
        $datatables->hide('i_periode');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('i_area');
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

    /** Running Number Dokumen*/
    public function running_number($thbl, $tahun)
    {
        $cek = $this->db->query("SELECT 
                substring(i_alokasi_id, 1, 3) AS kode 
            FROM tm_alokasiknr
            WHERE i_company = '$this->i_company'
            ORDER BY i_alokasi DESC");
        if ($cek->num_rows() > 0) {
            $kode = $cek->row()->kode;
        } else {
            $kode = 'AKR';
        }
        $query  = $this->db->query("SELECT
                max(substring(i_alokasi_id, 10, 6)) AS max
            FROM
                tm_alokasiknr
            WHERE to_char (d_alokasi, 'yyyy') >= '$tahun'
            AND i_company = '$this->i_company'
            AND substring(i_alokasi_id, 1, 3) = '$kode'
            AND substring(i_alokasi_id, 5, 2) = substring('$thbl',1,2)
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

    /** Get Data KN */
    public function get_data_kn($id)
    {
        return $this->db->query("SELECT 
                a.i_kn_id,
                a.i_kn,
                a.d_kn,
                b.e_customer_name,
                b.i_customer_id,
                a.i_customer,
                a.i_area,
                c.e_area_name,
                a.i_salesman,
                d.e_salesman_name,
                a.v_netto,
                a.v_sisa
            FROM 
                tm_kn a
            INNER JOIN tr_customer b ON 
                (b.i_customer = a.i_customer)
            INNER JOIN tr_area c ON 
                (c.i_area = a.i_area)
            INNER JOIN tr_salesman d ON 
                (d.i_salesman = a.i_salesman)
            WHERE a.i_kn = '$id'
        ", FALSE);
    }

    /** Get Customer */
    public function get_customer($cari, $i_area)
    {
        return $this->db->query("SELECT DISTINCT
                a.i_customer, i_customer_id , initcap(e_customer_name) AS e_customer_name
            FROM 
                tr_customer a
            INNER JOIN tm_nota b ON (b.i_customer = a.i_customer)
            WHERE 
                (e_customer_name ILIKE '%$cari%' OR i_customer_id ILIKE '%$cari%')
                AND a.i_company = '$this->i_company' 
                AND a.i_area = '$i_area'
                AND b.v_sisa > 0
            ORDER BY 3 ASC
        ", FALSE);
    }

    /** Get Salesman */
    public function get_salesman($cari, $i_customer)
    {
        return $this->db->query("SELECT
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
    public function get_dt($cari, $i_area)
    {
        return $this->db->query("SELECT 
                i_dt, i_dt_id , to_char(d_dt, 'DD Month YYYY') AS d_dt
            FROM 
                tm_dt
            WHERE 
                (i_dt_id ILIKE '%$cari%')
                AND i_company = '$this->i_company' 
                AND f_dt_cancel = 'false' 
                AND i_area = '$i_area'
            ORDER BY 1 ASC
        ", FALSE);
    }

    /** Get Nota */
    public function get_nota($cari, $i_area, $i_customer)
    {
        return $this->db->query("SELECT
                i_nota,
                i_nota_id,
                to_char(d_nota, 'DD FMMonth YYYY') AS d_nota
            FROM
                tm_nota 
            WHERE
                f_nota_cancel = 'f'
                AND (i_nota_id ILIKE '%$cari%')
                AND i_area = '$i_area'
                AND i_customer = '$i_customer'
                AND i_company = '$this->i_company'
                AND v_sisa > 0
            ORDER BY
                i_nota ASC 
        ", FALSE);
    }

    /** Get Nota Detail */
    public function get_detail_nota($i_nota)
    {
        return $this->db->query("SELECT
                to_char(d_nota, 'DD FMMonth YYYY') AS dnota,
                d_nota,
                v_nota_netto AS v_jumlah,
                v_sisa
            FROM
                tm_nota 
            WHERE
                i_nota = '$i_nota'
        ", FALSE);
    }

    /** Simpan Data */
    public function save()
    {
        $query = $this->db->query("SELECT max(i_alokasi)+1 AS id FROM tm_alokasiknr", TRUE);
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

        $ym = date('ym', strtotime($this->input->post('d_document')));
        $Y = date('Y', strtotime($this->input->post('d_document')));
        $i_alokasi_id = $this->running_number($ym, $Y);
        $i_kn = $this->input->post('i_kn');
        $table = array(
            'i_company'     => $this->i_company,
            'i_alokasi'     => $id,
            'i_alokasi_id'  => $i_alokasi_id,
            'i_kn'          => $i_kn,
            'i_area'        => $this->input->post('i_area'),
            'i_customer'    => $this->input->post('i_customer'),
            'd_alokasi'     => $this->input->post('d_document'),
            'v_jumlah'      => str_replace(",", "", $this->input->post('v_jumlah')),
            'v_lebih'       => str_replace(",", "", $this->input->post('v_lebih')),
            'd_entry'       => current_datetime(),
        );
        $this->db->insert('tm_alokasiknr', $table);
        if (is_array($this->input->post('i_nota')) || is_object($this->input->post('i_nota'))) {
            $i = 0;
            foreach ($this->input->post('i_nota') as $i_nota) {
                if ($i_nota != '' || $i_nota != null) {
                    $v_jumlah = str_replace(",", "", $this->input->post('vjumlah')[$i]);
                    $item = array(
                        'i_company'        => $this->i_company,
                        'i_alokasi'        => $id,
                        'i_area'           => $this->input->post('i_area'),
                        'i_nota'           => $i_nota,
                        'd_nota'           => $this->input->post('d_nota')[$i],
                        'v_jumlah'         => $v_jumlah,
                        'v_sisa'           => str_replace(",", "", $this->input->post('vsesa')[$i]),
                        'n_item_no'        => $i,
                        'e_remark'         => $this->input->post('eremark')[$i],
                    );
                    $this->db->insert('tm_alokasiknr_item', $item);
                    $i++;

                    $this->db->query("UPDATE tm_nota SET v_sisa = v_sisa - '$v_jumlah' WHERE i_nota = '$i_nota' ", FALSE);
                    $this->db->query("UPDATE tm_kn SET v_sisa = v_sisa - '$v_jumlah' WHERE i_kn = '$i_kn' ", FALSE);
                }
            }
        } else {
            die;
        }
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
                d.i_salesman,
                e.i_salesman_id,
                e.e_salesman_name,
                d.i_kn_id,
                d.d_kn,
                d.v_netto,
                d.v_sisa
            FROM 
                tm_alokasiknr a
            INNER JOIN tr_area b ON 
                (b.i_area = a.i_area)
            INNER JOIN tr_customer c ON 
                (c.i_customer = a.i_customer)
            INNER JOIN tm_kn d ON 
                (d.i_kn = a.i_kn)
            inner join tr_salesman e on (e.i_salesman=d.i_salesman)
            WHERE i_alokasi = '$id'            
            AND d.f_kn_retur = 't'
        ", FALSE);
    }

    /** Get Data Untuk Edit Detail */
    public function get_data_detail($id)
    {
        return $this->db->query("SELECT
                a.i_nota,
                b.i_nota_id,
                to_char(b.d_nota, 'DD FMMonth YYYY') AS dnota,
                b.d_nota,
                a.v_jumlah,
                a.v_sisa,
                a.e_remark,
                b.v_sisa + a.v_jumlah AS v_nota
            FROM
                tm_alokasiknr_item a
            INNER JOIN tm_nota b ON
                (b.i_nota = a.i_nota)
            WHERE
                i_alokasi = '$id'
            ORDER BY
                n_item_no ASC
        ", FALSE);
    }

    /** Update Data */
    public function update()
    {
        $id = $this->input->post('id');
        $i_area = $this->input->post('i_area');
        $i_rv_item = $this->input->post('i_rv_item');
        $table = array(
            'i_company'     => $this->i_company,
            'i_alokasi_id'  => strtoupper($this->input->post('i_document')),
            'i_rv'          => $this->input->post('i_rv'),
            'i_rv_item'     => $i_rv_item,
            'i_area'        => $i_area,
            'i_customer'    => $this->input->post('i_customer'),
            'd_alokasi'     => $this->input->post('d_document'),
            'e_bank_name'   => $this->input->post('e_bank_name'),
            'v_jumlah'      => str_replace(",", "", $this->input->post('v_jumlah')),
            'v_lebih'       => str_replace(",", "", $this->input->post('v_lebih')),
            'd_update'      => current_datetime(),
        );
        $this->db->where('i_alokasi', $id);
        $this->db->update('tm_alokasi', $table);
        if (is_array($this->input->post('i_nota')) || is_object($this->input->post('i_nota'))) {
            $this->update_item($id);
            $this->db->where('i_alokasi', $id);
            $this->db->delete('tm_alokasi_item');
            $i = 0;
            foreach ($this->input->post('i_nota') as $i_nota) {
                $v_jumlah = str_replace(",", "", $this->input->post('vjumlah')[$i]);
                $item = array(
                    'i_company'        => $this->i_company,
                    'i_alokasi'        => $id,
                    'i_rv_item'        => $i_rv_item,
                    'i_area'           => $i_area,
                    'i_nota'           => $i_nota,
                    'd_nota'           => $this->input->post('d_nota')[$i],
                    'v_jumlah'         => $v_jumlah,
                    'v_sisa'           => str_replace(",", "", $this->input->post('vsesa')[$i]),
                    'n_item_no'        => $i,
                    'e_remark'         => $this->input->post('eremark')[$i],
                );
                $this->db->insert('tm_alokasi_item', $item);
                $i++;

                $this->db->query("UPDATE tm_nota SET v_sisa = v_sisa - '$v_jumlah' WHERE i_nota = '$i_nota' ", FALSE);
                $this->db->query("UPDATE tm_rv_item SET v_rv_saldo = v_rv_saldo - '$v_jumlah' WHERE i_rv_item = '$i_rv_item' ", FALSE);
            }
        } else {
            die;
        }
    }

    public function update_item($id)
    {
        $query = $this->db->query("SELECT i_nota, i_kn, a.v_jumlah FROM tm_alokasiknr_item a, tm_alokasiknr b WHERE a.i_alokasi = b.i_alokasi AND b.i_alokasi = '$id' ", FALSE);
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $key) {
                $this->db->query("UPDATE tm_nota SET v_sisa = v_sisa + '$key->v_jumlah' WHERE i_nota = '$key->i_nota' ", FALSE);
                $this->db->query("UPDATE tm_kn SET v_sisa = v_sisa + '$key->v_jumlah' WHERE i_kn = '$key->i_kn' ", FALSE);
            }
        }
    }

    /** Cancel Data */
    public function cancel($id,$alasan,$i_alokasi_id)
    {
        $table = array(
            'f_alokasi_cancel' => 't',
        );
        $this->db->where('i_alokasi', $id);
        $this->db->update('tm_alokasiknr', $table);
        $this->update_item($id);

        $this->db->query("INSERT INTO tx_del (i_company, i_user, e_user_name, e_dokumen, e_alasan)
        VALUES ('$this->i_company', '$this->i_user', '$this->e_user_name', 'No ALOKASI KN RETUR : $i_alokasi_id', '$alasan')");
    }
}

/* End of file Mmaster.php */
