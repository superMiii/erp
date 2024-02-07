<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mtunaiitem extends CI_Model
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
                a.d_tunai as tgl,
                a.i_tunai AS id,
                to_char(a.d_tunai, 'DD FMMonth YYYY') AS d_tunai,
                a.i_tunai_id,
                to_char(a.d_tunai, 'YYYYMM') as i_periode,
                b.e_area_name,
                c.i_customer_id || ' ~ ' || c.e_customer_name AS e_customer,
                d.e_salesman_name,
                v_jumlah::money AS v_jumlah,
                v_sisa::money AS v_sisa,
                f_tunai_cancel AS f_status,
                '$dfrom' AS dfrom,
                '$dto' AS dto,
                '$i_area' AS i_area
            FROM
                tm_tunai a
            INNER JOIN tr_area b ON
                (b.i_area = a.i_area)
            INNER JOIN tr_customer c ON
                (c.i_customer = a.i_customer)
            INNER JOIN tr_salesman d ON
                (d.i_salesman = a.i_salesman)
            INNER JOIN tm_user_area e ON
                (e.i_area = a.i_area)
            WHERE
                a.i_company = '$this->i_company'
                AND e.i_user = '$this->i_user'
                AND a.d_tunai BETWEEN '$dfrom' AND '$dto'
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
            $v_sisa     = $data['v_sisa'];
            $i_periode  = $data['i_periode'];
            $data       = '';
            $data      .= "<a href='" . base_url() . $this->folder . '/view/' . encrypt_url($id) . '/' . encrypt_url($dfrom) . '/' . encrypt_url($dto) . '/' . encrypt_url($i_area) . "' title='View Data'><i class='fa fa-eye fa-lg warning darken-4 mr-1'></i></a>";
            if ($i_periode >= get_periode()) {
                if ($v_jumlah == $v_sisa) {
                    if (check_role($this->id_menu, 3) && $f_status == 'f') {
                        $data      .= "<a href='" . base_url() . $this->folder . '/edit/' . encrypt_url($id) . '/' .  encrypt_url($dfrom) . '/' . encrypt_url($dto) . '/' . encrypt_url($i_area) . "' title='Edit Data'><i class='fa fa-pencil-square success darken-4 fa-lg mr-1'></i></a>";
                    }
                    if (check_role($this->id_menu, 4) && $f_status == 'f') {
                        $data      .= "<a href='#' onclick='sweetdeletev33raya3(\"" . $this->folder . "\",\"" . $id . "\",\"" . encrypt_url($dfrom)  . "\",\"" . encrypt_url($dto)  . '/' . encrypt_url($i_area) . "\");' title='Delete Data'><i class='fa fa-trash fa-lg red darken-4'></i></a>";
                    }
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

    /** Running Number Dokumen*/
    public function running_number($thbl, $tahun, $bln)
    {
        $cek = $this->db->query("SELECT 
                substring(i_tunai_id, 1, 2) AS kode 
            FROM tm_tunai 
            WHERE i_company = '$this->i_company'
            ORDER BY i_tunai DESC");
        if ($cek->num_rows() > 0) {
            $kode = $cek->row()->kode;
        } else {
            $kode = 'TN';
        }
        $query  = $this->db->query("SELECT
                max(substring(i_tunai_id, 9, 6)) AS max
            FROM
                tm_tunai
            WHERE to_char (d_tunai, 'yyyy') >= '$tahun'
            and to_char (d_tunai, 'MM') >= '$bln'
            AND i_company = '$this->i_company'
            AND substring(i_tunai_id, 1, 2) = '$kode'
            AND substring(i_tunai_id, 4, 2) = substring('$thbl',1,2)
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


    /** Get Customer */
    public function get_customer($cari, $i_area)
    {
        return $this->db->query("SELECT 
                i_customer, i_customer_id , e_customer_name
            FROM 
                tr_customer
            WHERE 
                (e_customer_name ILIKE '%$cari%' OR i_customer_id ILIKE '%$cari%')
                AND i_company = '$this->i_company' 
                AND f_customer_active = 'true' 
                AND i_area = '$i_area'
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
            ORDER BY 1 DesC
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
                to_char(d_nota, 'DD FMMonth YYYY') AS d_nota,
                v_sisa AS v_jumlah
            FROM
                tm_nota 
            WHERE
                i_nota = '$i_nota'
        ", FALSE);
    }

    /** Cek Apakah Data Sudah Ada */
    public function cek_code()
    {
        $i_document = str_replace("_", "", $this->input->post('i_document'));
        $i_area = $this->input->post('i_area');
        return $this->db->query("
            SELECT 
                i_kum_id
            FROM 
                tm_kum 
            WHERE 
                upper(trim(i_kum_id)) = upper(trim('$i_document'))
                AND i_area = '$i_area'
                AND i_company = '$this->i_company'
        ", FALSE);
    }

    /** Simpan Data */
    public function save()
    {
        $query = $this->db->query("SELECT max(i_tunai)+1 AS id FROM tm_tunai", TRUE);
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

        $i_tunai_id = $this->running_number($ym, $Y, $bln);

        $table = array(
            'i_company'     => $this->i_company,
            'i_tunai'       => $id,
            'i_tunai_id'    => $i_tunai_id,
            'i_area'        => $this->input->post('i_area'),
            'i_customer'    => $this->input->post('i_customer'),
            'i_salesman'    => $this->input->post('i_salesman'),
            'd_tunai'       => $this->input->post('d_document'),
            'd_entry'       => current_datetime(),
            'e_remark'      => $this->input->post('e_remark'),
            'v_jumlah'      => str_replace(",", "", $this->input->post('v_jumlah')),
            'v_sisa'        => str_replace(",", "", $this->input->post('v_jumlah')),
            'i_dt'          => $this->input->post('i_dt'),
        );
        $this->db->insert('tm_tunai', $table);
        if ($this->input->post('jml') > 0) {
            if (is_array($this->input->post('i_nota')) || is_object($this->input->post('i_nota'))) {
                $i = 0;
                foreach ($this->input->post('i_nota') as $i_nota) {
                    $item = array(
                        'i_company'        => $this->i_company,
                        'i_tunai'          => $id,
                        'i_area'           => $this->input->post('i_area'),
                        'i_nota'           => $i_nota,
                        'v_jumlah'         => str_replace(",", "", $this->input->post('v_jumlah_item')[$i]),
                        'n_item_no'        => $i,
                    );
                    $this->db->insert('tm_tunai_item', $item);
                    $i++;
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
                c.i_customer_id,
                c.e_customer_name,
                e.i_salesman_id,
                e.e_salesman_name,
                d.i_dt_id,
                to_char(d.d_dt, 'DD FMMonth YYYY') AS d_dt
            FROM 
                tm_tunai a
            INNER JOIN tr_area b ON 
                (b.i_area = a.i_area)
            INNER JOIN tr_customer c ON 
                (c.i_customer = a.i_customer)
            INNER JOIN tr_salesman e ON 
                (e.i_salesman = a.i_salesman)
            LEFT JOIN tm_dt d ON 
                (d.i_dt = a.i_dt)
            WHERE i_tunai = '$id'
        ", FALSE);
    }

    /** Get Data Untuk Edit Detail */
    public function get_data_detail($id)
    {
        return $this->db->query("SELECT
                a.i_nota,
                b.i_nota_id,
                to_char(d_nota, 'DD FMMonth YYYY') AS d_nota,
                a.v_jumlah,
                b.v_nota_netto
            FROM
                tm_tunai_item a
            INNER JOIN tm_nota b ON
                (b.i_nota = a.i_nota)
            WHERE
                i_tunai = '$id'
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
            'i_tunai_id'    => strtoupper($this->input->post('i_document')),
            'i_area'        => $this->input->post('i_area'),
            'i_customer'    => $this->input->post('i_customer'),
            'i_salesman'    => $this->input->post('i_salesman'),
            'd_tunai'       => $this->input->post('d_document'),
            'd_update'      => current_datetime(),
            'e_remark'      => $this->input->post('e_remark'),
            'v_jumlah'      => str_replace(",", "", $this->input->post('v_jumlah')),
            'v_sisa'        => str_replace(",", "", $this->input->post('v_jumlah')),
            'i_dt'          => $this->input->post('i_dt'),
        );
        $this->db->where('i_tunai', $id);
        $this->db->update('tm_tunai', $table);
        if ($this->input->post('jml') > 0) {
            if (is_array($this->input->post('i_nota')) || is_object($this->input->post('i_nota'))) {
                $this->db->where('i_tunai', $id);
                $this->db->delete('tm_tunai_item');
                $i = 0;
                foreach ($this->input->post('i_nota') as $i_nota) {
                    $item = array(
                        'i_company'        => $this->i_company,
                        'i_tunai'          => $id,
                        'i_area'           => $this->input->post('i_area'),
                        'i_nota'           => $i_nota,
                        'v_jumlah'         => str_replace(",", "", $this->input->post('v_jumlah_item')[$i]),
                        'n_item_no'        => $i,
                    );
                    $this->db->insert('tm_tunai_item', $item);
                    $i++;
                }
            } else {
                die;
            }
        } else {
            die;
        }
    }

    /** Cancel Data */
    public function cancel($id,$alasan,$i_tunai_id)
    {
        $table = array(
            'f_tunai_cancel' => 't',
        );
        $this->db->where('i_tunai', $id);
        $this->db->update('tm_tunai', $table);

        $this->db->query("INSERT INTO tx_del (i_company, i_user, e_user_name, e_dokumen, e_alasan)
        VALUES ('$this->i_company', '$this->i_user', '$this->e_user_name', 'No TUNAI ITEM : $i_tunai_id', '$alasan')");
    }
}

/* End of file Mmaster.php */
