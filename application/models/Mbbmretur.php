<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mbbmretur extends CI_Model
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
                d_bbm as tgl,
                i_bbm AS id,
                d_bbm,
                i_bbm_id,
                to_char(d_bbm, 'YYYYMM') as i_periode,
                to_char(a.d_ttb, 'DD FMMonth YYYY') AS d_ttb,
                i_ttb_id,
                c.e_area_name,
                e.i_customer_id || ' ~ ' || e.e_customer_name AS customer,
                k.i_kn_id as ikin,
                a.f_bbm_cancel AS f_status,
                '$dfrom' AS dfrom,
                '$dto' AS dto,
                '$i_area' AS i_area
            FROM
                tm_bbm a
            INNER JOIN tm_ttbretur b ON
                (b.i_ttb = a.i_ttb)
            INNER JOIN tr_area c ON
                (c.i_area = a.i_area)
            INNER JOIN tm_user_area d ON
                (d.i_area = a.i_area)
            INNER JOIN tr_customer e ON
                (e.i_customer = a.i_customer)
            left join tm_kn k on (k.i_refference = a.i_bbm and k.f_kn_cancel ='f' and k.f_kn_retur='t')
            WHERE
                a.i_company = '$this->i_company'
                AND d.i_user = '$this->i_user'
                AND a.d_bbm BETWEEN '$dfrom' AND '$dto'
                $area
            ORDER BY
                1 DESC
        ", FALSE);

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
            $i_periode  = $data['i_periode'];
            $dfrom      = $data['dfrom'];
            $dto        = $data['dto'];
            $i_area     = $data['i_area'];
            $ikin        = $data['ikin'];
            $data       = '';
            $data      .= "<a href='" . base_url() . $this->folder . '/view/' . encrypt_url($id) . '/' . encrypt_url($dfrom) . '/' . encrypt_url($dto) . '/' . encrypt_url($i_area) . "' title='View Data'><i class='fa fa-eye fa-lg warning darken-4 mr-1'></i></a>";
            if ($i_periode >= get_periode3()) {
                if (check_role($this->id_menu, 3) && $f_status == 'f' && ($ikin == null || $ikin == '')) {
                    $data      .= "<a href='" . base_url() . $this->folder . '/edit/' . encrypt_url($id)  . '/' .  encrypt_url($dfrom) . '/' . encrypt_url($dto) . '/' . encrypt_url($i_area) . "' title='Edit Data'><i class='fa fa-pencil-square success darken-4 fa-lg mr-1'></i></a>";
                }
                if (check_role($this->id_menu, 5) && $f_status == 'f') {
                    $data      .= "<a href='#' onclick='openLink(\"" . $this->folder . "\",\"" . encrypt_url($id) . "\"); return false;' title='Print Data'><i class='fa fa-print fa-lg blue darken-4 mr-1'></i></a>";
                }
                if (check_role($this->id_menu, 4) && $f_status == 'f' && ($ikin == null || $ikin == '')) {
                    $data      .= "<a href='#' onclick='sweetdeletev33(\"" . $this->folder . "\",\"" . $id . "\",\"" . encrypt_url($dfrom)  . "\",\"" . encrypt_url($dto)  . '/' . encrypt_url($i_area) . "\");' title='Delete Data'><i class='fa fa-trash fa-lg red darken-4'></i></a>";
                }
            }
            return $data;
        });
        $datatables->hide('i_periode');
        $datatables->hide('id');
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
        $datatables->query("SELECT DISTINCT
                a.d_ttb as tgl,
                to_char(a.d_ttb, 'YYYYMM') as i_periode,
                a.i_ttb AS id,
                a.d_ttb,
                a.i_ttb_id,
                b.e_area_name,
                c.i_customer_id || ' ~ ' || c.e_customer_name AS e_customer,
                d.e_alasan_retur_name,
                '$dfrom' AS dfrom,
                '$dto' AS dto,
                '$i_area' AS i_area
            FROM
                tm_ttbretur a
            INNER JOIN tr_area b ON
                (b.i_area = a.i_area)
            INNER JOIN tr_customer c ON
                (c.i_customer = a.i_customer)
            INNER JOIN tr_alasan_retur d ON
                (d.i_alasan_retur = a.i_alasan_retur)
            INNER JOIN tm_user_area f ON
                (f.i_area = a.i_area)
            INNER JOIN tm_ttbretur_item g ON 
                (g.i_ttb = a.i_ttb)
            WHERE
                f.i_user = '$this->i_user'
                AND a.i_company = '$this->i_company'
                AND a.d_ttb BETWEEN '$dfrom' AND '$dto'
                AND a.f_ttb_cancel = 'f'
                AND g.n_quantity_receive <= 0
                $area
            ORDER BY
                1 DESC
        ", FALSE);

        /** Cek Hak Akses, Apakah User Bisa Edit */
        $datatables->add('action', function ($data) {
            $id         = trim($data['id']);
            $i_periode  = $data['i_periode'];
            $dfrom      = $data['dfrom'];
            $dto        = $data['dto'];
            $i_area     = $data['i_area'];
            $data       = '';
            // if ($i_periode >= get_periode3()) {
            if (check_role($this->id_menu, 3)) {
                $data      .= "<a href='" . base_url() . $this->folder . '/add/' . encrypt_url($id) . '/' . encrypt_url($dfrom) . '/' . encrypt_url($dto) . '/' . encrypt_url($i_area) .   "' title='Tambah Data'><i class='fa fa-check-circle-o success darken-4 fa-lg mr-1'></i></a>";
            }
            // }
            return $data;
        });
        $datatables->hide('i_periode');
        $datatables->hide('id');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('i_area');
        return $datatables->generate();
    }

    /** Running Number Dokumen*/
    public function running_number($thbl, $tahun)
    {
        $cek = $this->db->query("SELECT 
                substring(i_bbm_id, 1, 3) AS kode 
            FROM tm_bbm 
            WHERE i_company = '$this->i_company'
            ORDER BY i_bbm DESC");
        if ($cek->num_rows() > 0) {
            $kode = $cek->row()->kode;
        } else {
            $kode = 'BBM';
        }
        $query  = $this->db->query("SELECT
                max(substring(i_bbm_id, 10, 6)) AS max
            FROM
                tm_bbm
            WHERE to_char (d_bbm, 'yyyy') >= '$tahun'
            AND i_company = '$this->i_company'
            AND substring(i_bbm_id, 5, 2) = substring('$thbl',1,2)
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
            $number = $kode . '-' . $thbl . "-" . $number;
            return $number;
        } else {
            $number = "000001";
            $nomer  = $kode . '-' . $thbl . "-" . $number;
            return $nomer;
        }
    }

    /** Cek Apakah Data Sudah Ada */
    public function cek_code()
    {
        $i_document = str_replace("_", "", $this->input->post('i_document'));
        return $this->db->query("SELECT 
                i_bbm_id
            FROM 
                tm_bbm 
            WHERE 
                upper(trim(i_bbm_id)) = upper(trim('$i_document'))
                AND i_company = '$this->i_company'
        ", FALSE);
    }

    /** Get Product */
    public function get_product($cari, $i_customer)
    {
        return $this->db->query("SELECT
                a.i_nota_item,
                b.i_product_id,
                b.e_product_name,
                ab.i_nota_id
            FROM
                tm_nota_item a
            INNER JOIN tm_nota ab ON
                (ab.i_nota = a.i_nota)
            INNER JOIN tr_product b ON
                (b.i_product = a.i_product)
            WHERE
                ab.i_company = '$this->i_company'
                AND ab.f_nota_cancel = 'f'
                AND ab.i_customer = '$i_customer'
                AND (b.e_product_name ILIKE '%$cari%'
                    OR b.i_product_id ILIKE '%$cari%')
            ORDER BY 2,3
        ", FALSE);
    }

    /** Get Product Detail */
    public function get_product_detail($i_product)
    {
        return $this->db->query("SELECT
                b.e_product_name,
                a.i_product_grade,
                a.i_product_motif,
                c.e_product_motifname
            FROM
                tm_ic a
            INNER JOIN tr_product b ON
                (b.i_product = a.i_product)
            INNER JOIN tr_product_motif c ON
                (c.i_product_motif = a.i_product_motif)
            WHERE
                a.i_product = '$i_product'", FALSE);
    }

    /** Simpan Data */
    public function save()
    {
        $query = $this->db->query("SELECT max(i_bbm)+1 AS id FROM tm_bbm", TRUE);
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

        $header = array(
            'i_company'     => $this->session->i_company,
            'i_bbm'         => $id,
            'i_bbm_id'      => $this->input->post('i_document'),
            'i_ttb'         => $this->input->post('i_ttb'),
            'd_ttb'         => $this->input->post('d_ttb'),
            'i_area'        => $this->input->post('i_area'),
            'i_salesman'    => $this->input->post('i_salesman'),
            'i_customer'    => $this->input->post('i_customer'),
            'd_bbm'         => $this->input->post('d_document'),
            'e_remark'      => $this->input->post('e_remarkh'),
            'd_entry'       => current_datetime(),
        );
        $this->db->insert('tm_bbm', $header);

        if (is_array($this->input->post('i_product')) || is_object($this->input->post('i_product'))) {
            $i = 0;
            foreach ($this->input->post('i_product') as $i_product) {
                $item = array(
                    'i_bbm'             => $id,
                    'i_product'         => $i_product,
                    'i_product_motif'   => $this->input->post('i_product_motif')[$i],
                    'i_product_grade'   => $this->input->post('i_product_grade')[$i],
                    'n_quantity'        => str_replace(",", "", $this->input->post('n_bbm')[$i]),
                    'v_unit_price'      => str_replace(",", "", $this->input->post('v_unit_price')[$i]),
                    'e_remark'          => $this->input->post('e_remark')[$i],
                    'n_item_no'         => $i,
                );
                $this->db->insert('tm_bbm_item', $item);
                $i_ttb_item = $this->input->post('i_ttb_item')[$i];
                if ($i_ttb_item != '' || $i_ttb_item != null) {
                    $ttb = array(
                        'i_product2'         => $i_product,
                        'i_product2_grade'   => $this->input->post('i_product_grade')[$i],
                        'i_product2_motif'   => $this->input->post('i_product_motif')[$i],
                        'n_quantity_receive' => str_replace(",", "", $this->input->post('n_bbm')[$i]),
                    );
                    $this->db->where('i_ttb_item', $i_ttb_item);
                    $this->db->update('tm_ttbretur_item', $ttb);
                }
                $i++;
                // $i_ttb = $this->input->post('i_ttb')[$i];
                // $query = $this->db->query("SELECT d_receive1 FROM tm_ttbretur WHERE i_ttb = '$i_ttb' ", FALSE);
                // if ($query->num_rows() > 0) {
                //     foreach ($query->result() as $key) {
                //         $this->db->query("UPDATE tm_ttbretur SET d_receive1 = '2022-02-24' WHERE i_ttb = '$key->i_ttb' ", FALSE);
                //     }
                // }
                // $i++;
            }
        } else {
            die;
        }
    }

    /** Get Data Untuk Tambah */
    public function get_data($id)
    {
        return $this->db->query("SELECT
                a.i_ttb,
                a.i_ttb_id,
                a.d_ttb,
                to_char(a.d_ttb, 'DD FMMonth YYYY') AS dttb,
                to_char(current_date, 'DD FMMonth YYYY') AS date_now,
                a.i_area,
                b.i_area_id,
                b.e_area_name,
                a.i_salesman,
                c.i_salesman_id,
                c.e_salesman_name,
                a.i_customer,
                d.i_customer_id,
                d.e_customer_name,
                d.e_customer_address,
                d.e_customer_phone,
                d.i_price_group,
                d.f_customer_plusppn,
                d.n_customer_top,
                d.d_customer_register
            FROM
                tm_ttbretur a
            INNER JOIN tr_area b ON
                (b.i_area = a.i_area)
            INNER JOIN tr_salesman c ON
                (c.i_salesman = a.i_salesman)
            INNER JOIN tr_customer d ON
                (d.i_customer = a.i_customer)
            WHERE
                a.i_ttb = '$id'
        ", FALSE);
    }

    /** Get Detail Untuk Tambah */
    public function get_data_detail($id)
    {
        return $this->db->query("SELECT
                a.i_ttb_item,
                a.i_product1,
                b.i_product_id,
                b.e_product_name,
                a.i_product1_motif,
                c.e_product_motifname,
                a.i_product1_grade,
                d.e_product_gradename,
                a.n_quantity,
                a.v_unit_price
            FROM
                tm_ttbretur_item a
            INNER JOIN tr_product b ON
                (b.i_product = a.i_product1)
            INNER JOIN tr_product_motif c ON
                (c.i_product_motif = a.i_product1_motif)
            INNER JOIN tr_product_grade d ON
                (d.i_product_grade = a.i_product1_grade)
            WHERE
                a.i_ttb = '$id'
            ORDER BY
                a.n_item_no ASC
        ", FALSE);
    }

    /** Get Data Untuk Edit */
    public function get_data_edit($id)
    {
        return $this->db->query("SELECT
                e.i_bbm,
                e.i_bbm_id,
                a.i_ttb,
                a.i_ttb_id,
                a.d_ttb,
                to_char(a.d_ttb, 'DD FMMonth YYYY') AS dttb,
                to_char(e.d_bbm, 'DD FMMonth YYYY') AS date_now,
                e.d_bbm,
                a.i_area,
                b.i_area_id,
                b.e_area_name,
                a.i_salesman,
                c.i_salesman_id,
                c.e_salesman_name,
                a.i_customer,
                d.i_customer_id,
                d.e_customer_name,
                d.e_customer_address,
                d.e_customer_phone,
                d.i_price_group,
                d.f_customer_plusppn,
                d.n_customer_top,
                d.d_customer_register,
                e.e_remark,
	            r.e_city_name
            FROM
                tm_ttbretur a
            INNER JOIN tm_bbm e ON 
                (e.i_ttb = a.i_ttb)
            INNER JOIN tr_area b ON
                (b.i_area = e.i_area)
            INNER JOIN tr_salesman c ON
                (c.i_salesman = e.i_salesman)
            INNER JOIN tr_customer d ON
                (d.i_customer = e.i_customer)
            inner join tr_city r on
                (r.i_city = d.i_city)
            WHERE
                e.i_bbm = '$id'
        ", FALSE);
    }

    /** Get Detail Untuk Edit */
    public function get_data_detail_edit($id)
    {
        return $this->db->query("SELECT
                d.i_ttb_item,
                c.i_product_id,
                c.e_product_name,
                e.e_product_motifname,
                a.*,
                d.i_product1,
                d.i_product1_grade,
                d.i_product1_motif,
                f.i_product_id AS i_product_id1,
                f.e_product_name AS e_product_name1,
                g.e_product_motifname AS e_product_motifname1,
                d.n_quantity AS n_quantity1
            FROM
                tm_bbm_item a
            INNER JOIN tm_bbm b ON
                (b.i_bbm = a.i_bbm)
            INNER JOIN tr_product c ON
                (c.i_product = a.i_product)
            INNER JOIN tm_ttbretur_item d ON
                (d.i_ttb = b.i_ttb
                    AND a.i_product = d.i_product2)
            INNER JOIN tr_product_motif e ON
                (e.i_product_motif = a.i_product_motif)
            INNER JOIN tr_product f ON
                (f.i_product = d.i_product1)
            INNER JOIN tr_product_motif g ON
                (g.i_product_motif = d.i_product1_motif)
            WHERE
                a.i_bbm = '$id'
            ORDER BY
                a.n_item_no ASC
        ", FALSE);
    }

    /** Cek Apakah Data Sudah Ada Pas Edit */
    public function cek_edit()
    {
        $i_document = str_replace("_", "", $this->input->post('i_document'));
        $i_document_old = str_replace("_", "", $this->input->post('i_document_old'));
        return $this->db->query("SELECT 
                i_bbm_id
             FROM 
                tm_bbm 
             WHERE 
                trim(upper(i_bbm_id)) <> trim(upper('$i_document_old'))
                AND trim(upper(i_bbm_id)) = trim(upper('$i_document'))
                AND i_company = '$this->i_company'
         ", FALSE);
    }

    /** Update Data */
    public function update()
    {
        $id = $this->input->post('id');
        $header = array(
            'i_company'     => $this->session->i_company,
            'i_bbm_id'      => $this->input->post('i_document'),
            'i_ttb'         => $this->input->post('i_ttb'),
            'd_ttb'         => $this->input->post('d_ttb'),
            'i_area'        => $this->input->post('i_area'),
            'i_salesman'    => $this->input->post('i_salesman'),
            'i_customer'    => $this->input->post('i_customer'),
            'd_bbm'         => $this->input->post('d_document'),
            'e_remark'      => $this->input->post('e_remarkh'),
            'd_update'      => current_datetime(),
        );
        $this->db->where('i_bbm', $id);
        $this->db->update('tm_bbm', $header);

        if (is_array($this->input->post('i_bbm_item')) || is_object($this->input->post('i_bbm_item'))) {
            $i = 0;
            foreach ($this->input->post('i_bbm_item') as $i_bbm_item) {
                $item = array(
                    'i_bbm'             => $id,
                    'i_product'         => $this->input->post('i_product')[$i],
                    'i_product_motif'   => $this->input->post('i_product_motif')[$i],
                    'i_product_grade'   => $this->input->post('i_product_grade')[$i],
                    'n_quantity'        => str_replace(",", "", $this->input->post('n_bbm')[$i]),
                    'v_unit_price'      => str_replace(",", "", $this->input->post('v_unit_price')[$i]),
                    'e_remark'          => $this->input->post('e_remark')[$i],
                    'n_item_no'         => $i,
                );
                $this->db->where('i_bbm_item', $i_bbm_item);
                $this->db->update('tm_bbm_item', $item);
                $i_ttb_item = $this->input->post('i_ttb_item')[$i];
                if ($i_ttb_item != '' || $i_ttb_item != null) {
                    $ttb = array(
                        'i_product2'         => $this->input->post('i_product')[$i],
                        'i_product2_grade'   => $this->input->post('i_product_grade')[$i],
                        'i_product2_motif'   => $this->input->post('i_product_motif')[$i],
                        'n_quantity_receive' => str_replace(",", "", $this->input->post('n_bbm')[$i]),
                    );
                    $this->db->where('i_ttb_item', $i_ttb_item);
                    $this->db->update('tm_ttbretur_item', $ttb);
                }
                $i++;
            }
        } else {
            die;
        }
    }

    /** Cancel Data */
    public function cancel($id)
    {
        $table = array(
            'f_bbm_cancel' => 't',
        );
        $this->db->where('i_bbm', $id);
        $this->db->update('tm_bbm', $table);

        $query = $this->db->query("SELECT
                a.i_ttb,
                b.i_product,
                b.n_quantity
            FROM
                tm_bbm a
            INNER JOIN tm_bbm_item b ON
                (b.i_bbm = a.i_bbm)
            WHERE
                a.i_bbm = '$id'
            ORDER BY
                b.n_item_no ASC ", FALSE);
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $key) {
                $this->db->query("UPDATE 
                    tm_ttbretur_item SET 
                    n_quantity_receive = n_quantity_receive - $key->n_quantity,
                    i_product2 = null,
                    i_product2_grade = null,
                    i_product2_motif = null
                    WHERE i_ttb = '$key->i_ttb' 
                    AND i_product2 = '$key->i_product' 
                ", FALSE);
            }
        }
    }
}

/* End of file Mmaster.php */
