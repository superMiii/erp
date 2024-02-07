<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mdt extends CI_Model
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
                d_dt as tgl,
                i_dt AS id,
                d_dt,
                a.i_dt_id,
                to_char(d_dt, 'YYYYMM') as i_periode,
                '[' || b.i_area_id || '] - ' || b.e_area_name AS e_area,
                v_jumlah::money AS v_jumlah,
                f_dt_cancel AS f_status,
                n_print,
                '$dfrom' AS dfrom,
                '$dto' AS dto,
                '$i_area' AS i_area
            FROM
                tm_dt a
            INNER JOIN tr_area b ON
                (b.i_area = a.i_area)
            INNER JOIN tm_user_area c ON 
                (c.i_area = a.i_area)
            WHERE
                c.i_user = '$this->i_user'
                AND a.i_company = '$this->i_company'
                AND a.d_dt BETWEEN '$dfrom' AND '$dto'
                $area
            ORDER BY
                1 DESC
        ", FALSE);

        $datatables->edit('n_print', function ($data) {
            if ($data['n_print'] == '0') {
                $data = "<span class='badge bg-yellow bg-darken-3 badge-pill'>" . $this->lang->line('Belum') . "</span>";
            } else {
                $data = "<span class='badge bg-blue bg-darken-1 badge-pill'>" . $this->lang->line('Sudah') . ' ' . $data['n_print'] . ' x' . "</span>";
            }
            return $data;
        });

        $datatables->edit('f_status', function ($data) {
            if ($data['f_status'] == 't') {
                $status = $this->lang->line('Batal');
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
            $data       = '';
            $data      .= "<a href='" . base_url() . $this->folder . '/view/' . encrypt_url($id) . '/' . encrypt_url($dfrom) . '/' . encrypt_url($dto) . '/' . encrypt_url($i_area) . "' title='View Data'><i class='fa fa-eye fa-lg warning darken-4 mr-1'></i></a>";
            if ($i_periode >= get_periode()) {
                if (check_role($this->id_menu, 3) && $f_status == 'f') {
                    $data      .= "<a href='" . base_url() . $this->folder . '/edit/' . encrypt_url($id) . '/' . encrypt_url($dfrom) . '/' . encrypt_url($dto) . '/' . encrypt_url($i_area) . "' title='Edit Data'><i class='fa fa-pencil-square success darken-4 fa-lg mr-1'></i></a>";
                }
                if (check_role($this->id_menu, 5) && $f_status == 'f') {
                    $data      .= "<a href='#' onclick='openLink(\"" . $this->folder . "\",\"" . encrypt_url($id) . "\"); return false;' title='Print Data'><i class='fa fa-print fa-lg blue darken-4 mr-1'></i></a>";
                }
                if (check_role($this->id_menu, 4) && $f_status == 'f') {
                    $data      .= "<a href='#' onclick='sweetdeletev33(\"" . $this->folder . "\",\"" . $id . "\");' title='Delete Data'><i class='fa fa-trash fa-lg red darken-4'></i></a>";
                }
            }
            return $data;
        });
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('i_periode');
        $datatables->hide('id');
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

    /** Get Nota */
    public function get_nota($cari, $i_area)
    {
        return $this->db->query("SELECT
                a.i_nota,
                a.i_nota_id,
                a.d_nota,
                c.e_customer_name AS e_customer,
                '[ ' || c.i_customer_id || ' ] - ' || c.e_customer_name AS e_customer_name
            FROM
                tm_nota a
            INNER JOIN tr_customer c ON
                (c.i_customer = a.i_customer)
            WHERE
                a.f_nota_cancel = 'f'
                AND a.v_sisa > 0
                AND (a.i_nota_id ILIKE '%$cari%' or c.e_customer_name ilike '%$cari%')
                AND a.i_area = '$i_area'
                AND a.i_company = '$this->i_company'
            ORDER BY
                1 ASC 
        ", FALSE);
    }

    /** Get Nota Detail */
    public function get_detail_nota($id)
    {
        return $this->db->query("SELECT
                a.i_nota,
                a.i_nota_id,
                a.d_nota,
                to_char(a.d_nota, 'DD Month YYYY') AS date_nota,
                to_char(a.d_jatuh_tempo, 'DD Month YYYY') AS due_date,
                '[' || b.i_customer_id || '] - ' || b.e_customer_name AS e_customer,
                a.v_nota_netto,
                v_sisa
            FROM
                tm_nota a
            INNER JOIN tr_customer b ON
                (b.i_customer = a.i_customer)
            WHERE
                a.f_nota_cancel = 'f'
                AND v_sisa > 0
                AND i_nota = '$id'
        ", FALSE);
    }

    /** Running Number Dokumen*/
    public function running_number($thbl, $tahun, $i_area)
    {
        $cek0 = $this->db->query("SELECT 
                CASE
                    WHEN length(i_area_id)= 1 THEN '0' || i_area_id
                    ELSE i_area_id
                END AS kode0
            FROM tr_area
            WHERE i_company = '$this->i_company'
            AND i_area = '$i_area'");
        if ($cek0->num_rows() > 0) {
            $kode0 = $cek0->row()->kode0;
        } else {
            $kode0 = $i_area;
        }
        $cek = $this->db->query("SELECT 
                substring(i_dt_id, 4, 2) AS kode 
            FROM tm_dt 
            WHERE i_company = '$this->i_company'
            ORDER BY i_dt DESC");
        if ($cek->num_rows() > 0) {
            $kode = $cek->row()->kode;
        } else {
            $kode = 'DT';
        }
        $query  = $this->db->query("SELECT
                max(substring(i_dt_id, 12, 6)) AS max
            FROM
                tm_dt
            WHERE to_char (d_dt, 'yyyy') >= '$tahun'
            AND i_company = '$this->i_company'
            AND substring(i_dt_id, 1, 2) = '$kode0'
            AND substring(i_dt_id, 4, 2) = '$kode'
            AND substring(i_dt_id, 7, 2) = substring('$thbl',1,2)
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
            $number = $kode0 . "-" .$kode . "-" . $thbl . "-" . $number;
            return $number;
        } else {
            $number = "000001";
            $nomer  = $kode0 . "-" .$kode . "-" . $thbl . "-" . $number;
            return $nomer;
        }
    }

    /** Cek Apakah Data Sudah Ada */
    public function cek_code()
    {
        $i_document = str_replace("_", "", $this->input->post('i_document'));
        return $this->db->query("SELECT 
                i_dt_id
            FROM 
                tm_dt 
            WHERE 
                upper(trim(i_dt_id)) = upper(trim('$i_document'))
                AND i_company = '$this->i_company'
        ", FALSE);
    }

    /** Simpan Data */
    public function save()
    {
        $query = $this->db->query("SELECT max(i_dt)+1 AS id FROM tm_dt", TRUE);
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
        for ($i = 0; $i < 100000; $i++) {
            # code...
        }

        $header = array(
            'i_company'             => $this->session->i_company,
            'i_dt'                  => $id,
            'i_dt_id'               => str_replace("_", "", strtoupper($this->input->post('i_document'))),
            'i_area'                => $this->input->post('i_area'),
            'd_dt'                  => $this->input->post('d_document'),
            'v_jumlah'              => str_replace(",", "", $this->input->post('v_jumlah')),
            'd_entry'               => current_datetime(),
        );
        $this->db->insert('tm_dt', $header);

        if ($this->input->post('jml') > 0) {
            $i = 0;
            if (is_array($this->input->post('i_nota')) || is_object($this->input->post('i_nota'))) {
                foreach ($this->input->post('i_nota') as $i_nota) {
                    $item = array(
                        'i_dt'             => $id,
                        'i_nota'           => $i_nota,
                        'd_nota'           => $this->input->post('d_nota')[$i],
                        'v_sisa'           => str_replace(",", "", $this->input->post('v_sisa')[$i]),
                        'v_bayar'          => str_replace(",", "", $this->input->post('v_bayar')[$i]),
                        'n_item_no'        => $i,
                    );
                    $this->db->insert('tm_dt_item', $item);
                    $i++;
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
                to_char(a.d_dt, 'DD')||' '||trim(to_char(a.d_dt, 'Month'))||' '||to_char(a.d_dt, 'YYYY')  AS date_dt,
                b.i_area_id,
                b.e_area_name
            FROM 
                tm_dt a
            INNER JOIN tr_area b ON 
                (b.i_area = a.i_area)
            WHERE i_dt = '$id'
        ", FALSE);
    }

    /** Get Detail Untuk Edit */
    public function get_data_detail($id)
    {
        return $this->db->query("SELECT
                a.i_nota,
                aa.i_nota_id,
                aa.d_nota,
                aa.d_jatuh_tempo,
                to_char(aa.d_nota, 'DD Month YYYY') AS date_nota,
                to_char(aa.d_jatuh_tempo, 'DD Month YYYY') AS due_date,
                '[' || b.i_customer_id || '] - ' || b.e_customer_name AS e_customer,
                b.i_customer_id,
                b.e_customer_name,
                c.e_city_name,
                a.v_bayar,
                aa.v_sisa
            FROM
                tm_dt_item a
            INNER JOIN tm_nota aa ON 
                (aa.i_nota = a.i_nota)
            INNER JOIN tr_customer b ON
                (b.i_customer = aa.i_customer)
            INNER JOIN tr_city c ON
                (c.i_city = b.i_city)
            WHERE
                a.i_dt = '$id'
            order by i_dt_item 
        ", FALSE);
    }

    /** Cek Apakah Data Sudah Ada Pas Edit */
    public function cek_edit()
    {
        $i_document = str_replace("_", "", $this->input->post('i_document'));
        $i_document_old = str_replace("_", "", $this->input->post('i_document_old'));
        return $this->db->query("
            SELECT 
                i_dt_id
            FROM 
                tm_dt 
            WHERE 
                trim(upper(i_dt_id)) <> trim(upper('$i_document_old'))
                AND trim(upper(i_dt_id)) = trim(upper('$i_document'))
                AND i_company = '$this->i_company'
         ", FALSE);
    }

    /** Update Data */
    public function update()
    {
        $id = $this->input->post('id');
        $header = array(
            'i_company'             => $this->session->i_company,
            'i_dt'                  => $id,
            'i_dt_id'               => str_replace("_", "", strtoupper($this->input->post('i_document'))),
            'i_area'                => $this->input->post('i_area'),
            'd_dt'                  => $this->input->post('d_document'),
            'v_jumlah'              => str_replace(",", "", $this->input->post('v_jumlah')),
            'd_update'              => current_datetime(),
        );
        $this->db->where('i_dt', $id);
        $this->db->update('tm_dt', $header);

        if ($this->input->post('jml') > 0) {
            $i = 0;
            if (is_array($this->input->post('i_nota')) || is_object($this->input->post('i_nota'))) {
                $this->db->where('i_dt', $id);
                $this->db->delete('tm_dt_item');
                foreach ($this->input->post('i_nota') as $i_nota) {
                    $item = array(
                        'i_dt'             => $id,
                        'i_nota'           => $i_nota,
                        'd_nota'           => $this->input->post('d_nota')[$i],
                        'v_sisa'           => str_replace(",", "", $this->input->post('v_sisa')[$i]),
                        'v_bayar'          => str_replace(",", "", $this->input->post('v_bayar')[$i]),
                        'n_item_no'        => $i,
                    );
                    $this->db->insert('tm_dt_item', $item);
                    $i++;
                }
            }
        } else {
            die;
        }
    }

    /** Cancel Data */
    public function cancel($id)
    {
        $table = array(
            'f_dt_cancel' => 't',
        );
        $this->db->where('i_dt', $id);
        $this->db->update('tm_dt', $table);
    }

    /** Update Print */
    public function update_print($id)
    {
        $this->db->query("UPDATE tm_dt SET n_print = n_print + 1 WHERE i_dt = '$id' ", FALSE);
    }
}

/* End of file Mmaster.php */
