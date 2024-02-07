<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Msetortunai extends CI_Model
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
        $datatables->query("SELECT DISTINCT
                a.d_st as tgl,
                i_st AS id,
                a.d_st,
                i_st_id,
                to_char(a.d_st, 'YYYYMM') as i_periode,
                b.e_area_name,
                a.v_jumlah :: money AS v_jumlah,
                k.e_bank_name,            
	            w.i_rv_id as f_referensi,
                a.f_st_cancel AS f_status,
                a.n_print,
                '$dfrom' AS dfrom,
                '$dto' AS dto,
                '$i_area' AS i_area
            FROM
                tm_st a
            INNER JOIN tr_area b ON
                (b.i_area = a.i_area)
            INNER JOIN tm_user_area c ON
                (c.i_area = a.i_area)
            inner join tr_bank k on (k.i_bank=a.i_bank)
            left join (SELECT
                        y.i_company,
                        y.e_rv_refference_type_name,
                        z.i_rv,
                        z.i_rv_id,
                        i_rv_refference
                    from
                        tm_rv_item t
                        inner join tr_rv_refference_type y on (y.i_rv_refference_type = t.i_rv_refference_type)
                        inner join tm_rv z on (z.i_rv=t.i_rv)
                        WHERE z.i_company = '$this->i_company' AND y.f_tunai = 't') w on 
            (w.i_rv_refference=a.i_st)             
            WHERE
                a.i_company = '$this->i_company'
                AND c.i_user = '$this->i_user'
                AND a.d_st BETWEEN '$dfrom' AND '$dto'
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

        $datatables->edit('n_print', function ($data) {
            if ($data['n_print'] == '0') {
                $data = "<span class='badge bg-yellow bg-darken-3 badge-pill'>" . $this->lang->line('Belum') . "</span>";
            } else {
                $data = "<span class='badge bg-blue bg-darken-1 badge-pill'>" . $this->lang->line('Sudah') . ' ' . $data['n_print'] . ' x' . "</span>";
            }
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
            $f_referensi  = $data['f_referensi'];
            $data       = '';
            $data      .= "<a href='" . base_url() . $this->folder . '/view/' . encrypt_url($id) . '/' . encrypt_url($dfrom) . '/' . encrypt_url($dto) . '/' . encrypt_url($i_area) . "' title='View Data'><i class='fa fa-eye fa-lg warning darken-4 mr-1'></i></a>";
            if ($i_periode >= get_periode()) {
                if (check_role($this->id_menu, 3) && $f_status == 'f' && ($f_referensi == null || $f_referensi == '')) {
                    $data      .= "<a href='" . base_url() . $this->folder . '/edit/' . encrypt_url($id)  . '/' .  encrypt_url($dfrom) . '/' . encrypt_url($dto) . '/' . encrypt_url($i_area) . "' title='Edit Data'><i class='fa fa-pencil-square success darken-4 fa-lg mr-1'></i></a>";
                }
                if (check_role($this->id_menu, 5) && $f_status == 'f') {
                    $data      .= "<a href='#' onclick='openLink(\"" . $this->folder . "\",\"" . encrypt_url($id) . "\"); return false;' title='Print Data'><i class='fa fa-print fa-lg blue darken-4 mr-1'></i></a>";
                }
                if (check_role($this->id_menu, 4) && $f_status == 'f') {
                    $data      .= "<a href='#' onclick='sweetdeletev33raya3(\"" . $this->folder . "\",\"" . $id . "\",\"" . encrypt_url($dfrom)  . "\",\"" . encrypt_url($dto)  . '/' . encrypt_url($i_area) . "\");' title='Delete Data'><i class='fa fa-trash fa-lg red darken-4'></i></a>";
                }
                return $data;
            }
        });
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('i_area');
        $datatables->hide('i_periode');
        $datatables->hide('id');
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
    public function running_number($thbl, $tahun, $bln)
    {
        $cek = $this->db->query("SELECT 
                 substring(i_st_id, 1, 3) AS kode 
             FROM tm_st 
             WHERE i_company = '$this->i_company'
             ORDER BY i_st DESC");
        if ($cek->num_rows() > 0) {
            $kode = $cek->row()->kode;
        } else {
            $kode = 'ZTN';
        }
        $query  = $this->db->query("SELECT
                 max(substring(i_st_id, 10, 6)) AS max
             FROM
                 tm_st
             WHERE to_char (d_st, 'yyyy') >= '$tahun'
            and to_char (d_st, 'MM') >= '$bln'
             AND i_company = '$this->i_company'
             AND substring(i_st_id, 1, 3) = '$kode'
             AND substring(i_st_id, 5, 2) = substring('$thbl',1,2)
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

    /** Get Bank */
    public function get_bank($cari)
    {
        return $this->db->query("SELECT 
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

    /** Get Tunai */
    public function get_tunai($cari, $i_area)
    {
        return $this->db->query("SELECT
                i_tunai,
                i_tunai_id,
                to_char(d_tunai, 'DD FMMonth YYYY') AS d_tunai,
                b.i_customer_id  || ' - ' || b.e_customer_name as cus
            FROM
                tm_tunai a
            inner join tr_customer b on (b.i_customer=a.i_customer)
            WHERE
                f_tunai_cancel = 'f'
                AND (i_tunai_id ILIKE '%$cari%')
                AND a.i_area = '$i_area'
                AND a.i_company = '$this->i_company'
                AND v_sisa > 0
            ORDER BY
                i_tunai ASC 
        ", FALSE);
    }

    /** Get Tunai Detail */
    public function get_detail_tunai($i_tunai)
    {
        return $this->db->query("SELECT
                to_char(d_tunai, 'DD FMMonth YYYY') AS d_tunai,
                v_jumlah,
                b.i_customer_id  || ' - ' || b.e_customer_name as cus
            FROM
                tm_tunai a
            inner join tr_customer b on (b.i_customer=a.i_customer)
            WHERE
                i_tunai = '$i_tunai'
        ", FALSE);
    }

    /** Cek Apakah Data Sudah Ada */
    public function cek_code()
    {
        $i_document = str_replace("_", "", $this->input->post('i_document'));
        return $this->db->query("
            SELECT 
                i_st_id
            FROM 
                tm_st 
            WHERE 
                upper(trim(i_st_id)) = upper(trim('$i_document'))
                AND i_company = '$this->i_company'
        ", FALSE);
    }

    /** Simpan Data */
    public function save()
    {
        $query = $this->db->query("SELECT max(i_st)+1 AS id FROM tm_st", TRUE);
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
        $bln = date('m', strtotime($this->input->post('d_document')));

        $i_st_id = $this->running_number($ym, $Y, $bln);

        $table = array(
            'i_company'     => $this->i_company,
            'i_st'          => $id,
            'i_st_id'       => $i_st_id,
            'i_area'        => $this->input->post('i_area'),
            'd_st'          => $this->input->post('d_document'),
            'd_entry'       => current_datetime(),
            'e_remark'      => $this->input->post('e_remark'),
            'v_jumlah'      => str_replace(",", "", $this->input->post('v_jumlah')),
            'i_bank'        => $this->input->post('i_bank'),
        );
        $this->db->insert('tm_st', $table);
        if ($this->input->post('jml') > 0) {
            if (is_array($this->input->post('i_tunai')) || is_object($this->input->post('i_tunai'))) {
                $i = 0;
                foreach ($this->input->post('i_tunai') as $i_tunai) {
                    $v_jumlah = str_replace(",", "", $this->input->post('v_jumlah_item')[$i]);
                    $item = array(
                        'i_st'      => $id,
                        'i_tunai'   => $i_tunai,
                        'v_jumlah'  => $v_jumlah,
                        'n_item_no' => $i,
                    );
                    $this->db->insert('tm_st_item', $item);
                    $i++;

                    $this->db->query("UPDATE tm_tunai SET v_sisa = v_sisa - $v_jumlah WHERE i_tunai = $i_tunai ", FALSE);
                }
            } else {
                die;
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
                c.i_bank_id,
                c.e_bank_name
            FROM 
                tm_st a
            INNER JOIN tr_area b ON 
                (b.i_area = a.i_area)
            INNER JOIN tr_bank c ON 
                (c.i_bank = a.i_bank)
            WHERE i_st = '$id'
        ", FALSE);
    }

    /** Get Data Untuk Edit Detail */
    public function get_data_detail($id)
    {
        return $this->db->query("SELECT
                a.i_tunai,
                b.i_tunai_id,
                to_char(b.d_tunai, 'DD FMMonth YYYY') AS d_tunai,
                a.v_jumlah,
                c.i_customer_id  || ' - ' || c.e_customer_name as cus
            FROM
                tm_st_item a
            INNER JOIN tm_tunai b ON (b.i_tunai = a.i_tunai)
            inner join tr_customer c on (b.i_customer=c.i_customer)
            WHERE
                i_st = '$id'
            ORDER BY
                n_item_no ASC
        ", FALSE);
    }

    /** Cek Apakah Data Sudah Ada Pas Edit */
    public function cek_edit()
    {
        $i_document = str_replace("_", "", $this->input->post('i_document'));
        $i_document_old = str_replace("_", "", $this->input->post('i_document_old'));
        $i_area = $this->input->post('i_area');
        return $this->db->query("
            SELECT 
                i_tunai_id
            FROM 
                tm_tunai 
            WHERE 
                trim(upper(i_tunai_id)) <> trim(upper('$i_document_old'))
                AND trim(upper(i_tunai_id)) = trim(upper('$i_document'))
                AND i_area = '$i_area'
                AND i_company = '$this->i_company'
         ", FALSE);
    }

    /** Update Data */
    public function update()
    {
        $id = $this->input->post('id');
        $table = array(
            'i_company'     => $this->i_company,
            'i_st_id'       => strtoupper($this->input->post('i_document')),
            'i_area'        => $this->input->post('i_area'),
            'd_st'          => $this->input->post('d_document'),
            'd_update'      => current_datetime(),
            'e_remark'      => $this->input->post('e_remark'),
            'v_jumlah'      => str_replace(",", "", $this->input->post('v_jumlah')),
            'i_bank'        => $this->input->post('i_bank'),
        );
        $this->db->where('i_st', $id);
        $this->db->update('tm_st', $table);
        if ($this->input->post('jml') > 0) {
            if (is_array($this->input->post('i_tunai')) || is_object($this->input->post('i_tunai'))) {
                $i = 0;
                $this->update_item($id);
                $this->db->where('i_st', $id);
                $this->db->delete('tm_st_item');
                foreach ($this->input->post('i_tunai') as $i_tunai) {
                    $v_jumlah = str_replace(",", "", $this->input->post('v_jumlah_item')[$i]);
                    $item = array(
                        'i_st'      => $id,
                        'i_tunai'   => $i_tunai,
                        'v_jumlah'  => $v_jumlah,
                        'n_item_no' => $i,
                    );
                    $this->db->insert('tm_st_item', $item);
                    $i++;
                    $this->db->query("UPDATE tm_tunai SET v_sisa = v_sisa - $v_jumlah WHERE i_tunai = $i_tunai ", FALSE);
                }
            } else {
                die;
            }
        } else {
            die;
        }
    }

    /** Cancel Data */
    public function cancel($id,$alasan,$i_st_id)
    {
        $table = array(
            'f_st_cancel' => 't',
        );
        $this->db->where('i_st', $id);
        $this->db->update('tm_st', $table);

        $this->update_item($id);

        /* $query = $this->db->query("SELECT i_tunai, v_jumlah FROM tm_st_item WHERE i_st = '$id' ", FALSE);
        if ($query->num_rows()>0) {
            foreach ($query->result() as $key) {
                $this->db->query("UPDATE tm_tunai SET v_sisa = v_sisa + $key->v_jumlah WHERE i_tunai = '$key->i_tunai' ", FALSE);
            }
        } */

        $this->db->query("INSERT INTO tx_del (i_company, i_user, e_user_name, e_dokumen, e_alasan)
        VALUES ('$this->i_company', '$this->i_user', '$this->e_user_name', 'No SETOR TUNAI : $i_st_id', '$alasan')");
    }

    public function update_item($id)
    {
        $query = $this->db->query("SELECT i_tunai, v_jumlah FROM tm_st_item WHERE i_st = '$id' ", FALSE);
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $key) {
                $this->db->query("UPDATE tm_tunai SET v_sisa = v_sisa + $key->v_jumlah WHERE i_tunai = '$key->i_tunai' ", FALSE);
            }
        }
    }


    /** Update Print */
    public function update_print($id)
    {
        $this->db->query("UPDATE tm_st SET n_print = n_print + 1 WHERE i_st = '$id' ", FALSE);
    }
}

/* End of file Mmaster.php */
