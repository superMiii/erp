<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mreturpenjualan extends CI_Model
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
                a.d_ttb as tgl,
                a.i_ttb AS id,
                a.d_ttb, 
                a.i_ttb_id,
                to_char(a.d_ttb, 'YYYYMM') as i_periode,
                b.e_area_name,
                c.i_customer_id || ' ~ ' || c.e_customer_name AS e_customer,
                a.v_ttb_netto::money AS v_ttb,
                e.e_salesman_name,
                d.e_alasan_retur_name,
	            p.i_bbm_id as i_bbm_id,
                a.f_ttb_cancel AS f_status,
                a.d_receive1,
                a.d_receive2,
                '$dfrom' AS dfrom,
                '$dto' AS dto,
                '$i_area' AS i_area
            FROM
                tm_ttbretur a
            INNER JOIN tr_area b ON
                (b.i_area = a.i_area)
            INNER JOIN tr_customer c ON
                (c.i_customer = a.i_customer)
            INNER JOIN tr_salesman e ON
                (e.i_salesman = a.i_salesman)
            INNER JOIN tr_alasan_retur d ON
                (d.i_alasan_retur = a.i_alasan_retur)
            INNER JOIN tm_user_area f ON
                (f.i_area = a.i_area)
            left join tm_bbm p on (p.i_ttb = a.i_ttb and p.f_bbm_cancel='f')
            WHERE
                f.i_user = '$this->i_user'
                AND a.i_company = '$this->i_company'
                AND a.d_ttb BETWEEN '$dfrom' AND '$dto'
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
            $d_receive1 = $data['d_receive1'];
            $d_receive2 = $data['d_receive2'];
            $dfrom      = $data['dfrom'];
            $dto        = $data['dto'];
            $i_area     = $data['i_area'];
            $i_periode  = $data['i_periode'];
            $i_bbm_id  = $data['i_bbm_id'];
            $data       = '';
            $data      .= "<a href='" . base_url() . $this->folder . '/view/' . encrypt_url($id) . '/' . encrypt_url($dfrom) . '/' . encrypt_url($dto) . '/' . encrypt_url($i_area) . "' title='View Data'><i class='fa fa-eye fa-lg warning darken-4 mr-1'></i></a>";
            if ($i_periode >= get_periode()) {
                if (check_role($this->id_menu, 3) && $f_status == 'f'  && ($d_receive1 == null || $d_receive1 == '') && ($d_receive2 == null || $d_receive2 == '') && ($i_bbm_id == null || $i_bbm_id == '')) {
                    $data      .= "<a href='" . base_url() . $this->folder . '/edit/' . encrypt_url($id)  . '/' .  encrypt_url($dfrom) . '/' . encrypt_url($dto) . '/' . encrypt_url($i_area) . "' title='Edit Data'><i class='fa fa-pencil-square success darken-4 fa-lg mr-1'></i></a>";
                }
                if (check_role($this->id_menu, 5) && $f_status == 'f') {
                    $data      .= "<a href='#' onclick='openLink(\"" . $this->folder . "\",\"" . encrypt_url($id) . "\"); return false;' title='Print Data'><i class='fa fa-print fa-lg blue darken-4 mr-1'></i></a>";
                }
                if (check_role($this->id_menu, 4) && $f_status == 'f' && ($d_receive1 == null || $d_receive1 == '') && ($d_receive2 == null || $d_receive2 == '') && ($i_bbm_id == null || $i_bbm_id == '')) {
                    $data      .= "<a href='#' onclick='sweetdeletev33(\"" . $this->folder . "\",\"" . $id . "\",\"" . encrypt_url($dfrom)  . "\",\"" . encrypt_url($dto)  . '/' . encrypt_url($i_area) . "\");' title='Delete Data'><i class='fa fa-trash fa-lg red darken-4'></i></a>";
                }
            }
            return $data;
        });
        $datatables->hide('d_receive1');
        $datatables->hide('d_receive2');
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

    /** Get Alasan Retur*/
    public function get_alasan_retur($cari)
    {
        return $this->db->query("SELECT
                i_alasan_retur,
                e_alasan_retur_name
            FROM
                tr_alasan_retur
            WHERE
                f_status_alasan_retur_active = 't'
                AND (e_alasan_retur_name ILIKE '%$cari%')
            ORDER BY
                i_alasan_retur ASC 
        ", FALSE);
    }

    /** Get Customer */
    public function get_customer($cari, $i_area)
    {
        return $this->db->query("SELECT 
                a.i_customer, i_customer_id , e_customer_name
            FROM 
                tr_customer a
            INNER JOIN (SELECT DISTINCT i_customer FROM tm_nota WHERE f_nota_cancel = 'f' AND i_company = '$this->i_company') c 
                ON (c.i_customer = a.i_customer)
            WHERE 
                (e_customer_name ILIKE '%$cari%' OR i_customer_id ILIKE '%$cari%')
                AND i_company = '$this->i_company' 
                AND f_customer_active = 'true' 
                AND i_area = '$i_area'
            ORDER BY 3 ASC
        ", FALSE);
    }

    /** Get Detail Customer */
    public function get_customer_detail($icustomer)
    {
        return $this->db->query("SELECT
                a.e_customer_address,
                a.i_price_group,
                a.f_customer_plusppn,
                a.e_customer_npwpcode,
                a.f_customer_pkp,
                b.e_price_groupname,
                CASE
                    WHEN a.f_customer_plusppn = TRUE THEN 'Ya'
                    ELSE 'Tidak'
                END AS eppn,
                CASE
                    WHEN a.f_customer_pkp = TRUE THEN 'Ya'
                    ELSE 'Tidak'
                END AS pkp
            FROM
                tr_customer a
            INNER JOIN tr_price_group b ON
                (a.i_price_group = b.i_price_group)
            WHERE
                i_customer = $icustomer
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
            GROUP BY 1
            ORDER BY 3 ASC
        ", FALSE);
    }

    /** Get Product */
    public function get_product($cari, $i_customer)
    {
        return $this->db->query("SELECT DISTINCT        
	            i_nota_item,
                a.i_product,
	            ab.i_nota_id,
                b.i_product_id,
                b.e_product_name
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
            ORDER BY 3 desc
        ", FALSE);
    }


    public function get_product_price($i_nota_item, $i_customer)
    {
        return $this->db->query("SELECT
        (
                SELECT
                    sum(n_deliver) AS qty_nota
                FROM
                    tm_nota_item
                WHERE
                    i_nota IN (
                    SELECT
                        i_nota
                    FROM
                        tm_nota
                    WHERE
                        i_customer = '$i_customer'
                        AND f_nota_cancel='f')
                    AND i_product = (
                    SELECT
                        i_product
                    FROM
                        tm_nota_item
                    WHERE
                        i_nota_item = '$i_nota_item')) AS n_nota,
                a.*,
                b.d_nota,
                b.i_nota_id
            FROM
                tm_nota_item a
            INNER JOIN tm_nota b ON 
                (b.i_nota = a.i_nota)
            WHERE
                i_nota_item = '$i_nota_item'", FALSE);
    }

    /** Running Number Dokumen*/
    public function running_number($thbl, $tahun)
    {
        $cek = $this->db->query("SELECT 
                substring(i_ttb_id, 1, 3) AS kode 
            FROM tm_ttbretur 
            WHERE i_company = '$this->i_company'
            ORDER BY i_ttb_id DESC");
        if ($cek->num_rows() > 0) {
            $kode = $cek->row()->kode;
        } else {
            $kode = 'TTB';
        }
        $query  = $this->db->query("SELECT
                max(substring(i_ttb_id, 10, 6)) AS max
            FROM
                tm_ttbretur
            WHERE to_char (d_ttb, 'yyyy') >= '$tahun'
            AND i_company = '$this->i_company'
            AND substring(i_ttb_id, 1, 3) = '$kode'
            AND substring(i_ttb_id, 5, 2) = substring('$thbl',1,2)
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
        return $this->db->query("SELECT 
                i_ttb_id
            FROM 
                tm_ttbretur 
            WHERE 
                upper(trim(i_ttb_id)) = upper(trim('$i_document'))
                AND i_company = '$this->i_company'
        ", FALSE);
    }

    /** Simpan Data */
    public function save()
    {
        $query = $this->db->query("SELECT max(i_ttb)+1 AS id FROM tm_ttbretur", TRUE);
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
        $i_ttb_id = $this->running_number($ym, $Y);
        $v_ttb_discounttotal = str_replace(",", "", $this->input->post('v_ttb_discounttotal'));
        $f_ttb_plusdiscount = ($v_ttb_discounttotal > 0) ? TRUE : FALSE;
        $v_ttb_ppn = ($this->input->post('v_ttb_ppn') == '') ? 0 : str_replace(",", "", $this->input->post('v_ttb_ppn'));
        $header = array(
            'i_company'             => $this->session->i_company,
            'i_ttb'                 => $id,
            'i_ttb_id'              => $i_ttb_id,
            'd_ttb'                 => $this->input->post('d_document'),
            'i_area'                => $this->input->post('i_area'),
            'i_customer'            => $this->input->post('i_customer'),
            'i_salesman'            => $this->input->post('i_salesman'),
            /* 'n_ttb_discount1'       => str_replace(",", "", $this->input->post('n_ttb_discount1')),
            'n_ttb_discount2'       => str_replace(",", "", $this->input->post('n_ttb_discount2')),
            'n_ttb_discount3'       => str_replace(",", "", $this->input->post('n_ttb_discount3')),
            'v_ttb_discount1'       => str_replace(",", "", $this->input->post('v_ttb_discount1')),
            'v_ttb_discount2'       => str_replace(",", "", $this->input->post('v_ttb_discount2')),
            'v_ttb_discount3'       => str_replace(",", "", $this->input->post('v_ttb_discount3')), */
            'f_ttb_pkp'             => $this->input->post('f_ttb_pkp'),
            'f_ttb_plusppn'         => $this->input->post('f_ttb_plusppn'),
            'f_ttb_plusdiscount'    => $f_ttb_plusdiscount,
            'v_ttb_gross'           => str_replace(",", "", $this->input->post('v_ttb_gross')),
            'v_ttb_ppn'             => $v_ttb_ppn,
            'v_ttb_discounttotal'   => $v_ttb_discounttotal,
            'v_ttb_netto'           => str_replace(",", "", $this->input->post('v_ttb_netto')),
            'e_ttb_remark'          => $this->input->post('e_remark'),
            'd_entry'               => current_datetime(),
            'v_ttb_sisa'            => str_replace(",", "", $this->input->post('v_ttb_netto')),
            'i_price_group'         => $this->input->post('i_price_group'),
            'i_alasan_retur'        => $this->input->post('i_alasan_retur'),
            'n_ppn_r'               => $this->input->post('n_ppn'),
        );
        $this->db->insert('tm_ttbretur', $header);
        if (is_array($this->input->post('i_nota')) || is_object($this->input->post('i_nota'))) {
            $i = 0;
            foreach ($this->input->post('i_nota') as $i_nota) {
                $item = array(
                    'i_ttb'             => $id,
                    'i_nota'            => $i_nota,
                    'd_nota'            => $this->input->post('d_nota')[$i],
                    'i_product1'        => $this->input->post('i_product1')[$i],
                    'i_product1_grade'  => $this->input->post('i_product1_grade')[$i],
                    'i_product1_motif'  => $this->input->post('i_product1_motif')[$i],
                    'n_quantity'        => $this->input->post('n_quantity')[$i],
                    'v_unit_price'      => str_replace(",", "", $this->input->post('v_unit_price')[$i]),
                    'n_ttb_discount1'   => str_replace(",", "", $this->input->post('n_ttb_discount1')[$i]),
                    'n_ttb_discount2'   => str_replace(",", "", $this->input->post('n_ttb_discount2')[$i]),
                    'n_ttb_discount3'   => str_replace(",", "", $this->input->post('n_ttb_discount3')[$i]),
                    'v_ttb_discount1'   => str_replace(",", "", $this->input->post('v_ttb_discount1')[$i]),
                    'v_ttb_discount2'   => str_replace(",", "", $this->input->post('v_ttb_discount2')[$i]),
                    'v_ttb_discount3'   => str_replace(",", "", $this->input->post('v_ttb_discount3')[$i]),
                    'e_ttb_remark'      => $this->input->post('e_ttb_remark')[$i],
                    'n_item_no'         => $i,
                );
                $this->db->insert('tm_ttbretur_item', $item);
                $i++;
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
                e.i_salesman_id,
                e.e_salesman_name,
                c.i_customer_id,
                c.e_customer_name,
                c.e_customer_address,
                c.e_customer_phone,
                c.i_price_group,
                c.f_customer_plusppn,
                c.n_customer_top,
                c.d_customer_register,
                c.e_customer_npwpcode,
                d.e_price_groupname,
                g.e_alasan_retur_name,
                CASE
                    WHEN a.f_ttb_plusppn = TRUE THEN 'Ya'
                    ELSE 'Tidak'
                END AS eppn,
                CASE
                    WHEN a.f_ttb_pkp = TRUE THEN 'Ya'
                    ELSE 'Tidak'
                END AS pkp,
	            r.e_city_name
            FROM 
                tm_ttbretur a
            INNER JOIN tr_area b ON 
                (b.i_area = a.i_area)
            INNER JOIN tr_customer c ON 
                (c.i_customer = a.i_customer)
            INNER JOIN tr_price_group d ON
                (a.i_price_group = d.i_price_group)
            INNER JOIN tr_salesman e ON 
                (e.i_salesman = a.i_salesman)
            INNER JOIN tr_alasan_retur g ON
                (a.i_alasan_retur = g.i_alasan_retur)
            inner join tr_city r on
                (r.i_city = c.i_city)
            WHERE i_ttb = '$id'
        ", FALSE);
    }

    /** Get Detail Untuk Edit */
    public function get_data_detail($id)
    {
        return $this->db->query("SELECT 
                a.*,
                b.i_product_id,
                b.e_product_name,
                c.i_nota_item,
                c.n_deliver,
                d.i_nota_id
            FROM 
                tm_ttbretur_item a
            INNER JOIN tr_product b ON 
                (b.i_product = a.i_product1)
            INNER JOIN tm_nota_item c ON
                (c.i_nota = a.i_nota AND a.i_product1 = c.i_product)
            inner join tm_nota d on (d.i_nota = c.i_nota)
            WHERE i_ttb = '$id'
        ", FALSE);
    }

    /** Cek Apakah Data Sudah Ada Pas Edit */
    public function cek_edit()
    {
        $i_document = str_replace("_", "", $this->input->post('i_document'));
        $i_document_old = str_replace("_", "", $this->input->post('i_document_old'));
        return $this->db->query("SELECT 
                i_ttb_id
            FROM 
                tm_ttbretur 
            WHERE 
                trim(upper(i_ttb_id)) <> trim(upper('$i_document_old'))
                AND trim(upper(i_ttb_id)) = trim(upper('$i_document'))
                AND i_company = '$this->i_company'
         ", FALSE);
    }

    /** Update Data */
    public function update()
    {
        $id = $this->input->post('id');
        $v_ttb_discounttotal = str_replace(",", "", $this->input->post('v_ttb_discounttotal'));
        $f_ttb_plusdiscount = ($v_ttb_discounttotal > 0) ? TRUE : FALSE;
        $v_ttb_ppn = ($this->input->post('v_ttb_ppn') == '') ? 0 : str_replace(",", "", $this->input->post('v_ttb_ppn'));
        $header = array(
            'i_ttb_id'              => $this->input->post('i_document'),
            'd_ttb'                 => $this->input->post('d_document'),
            'i_area'                => $this->input->post('i_area'),
            'i_customer'            => $this->input->post('i_customer'),
            'i_salesman'            => $this->input->post('i_salesman'),
            /* 'n_ttb_discount1'       => str_replace(",", "", $this->input->post('n_ttb_discount1')),
            'n_ttb_discount2'       => str_replace(",", "", $this->input->post('n_ttb_discount2')),
            'n_ttb_discount3'       => str_replace(",", "", $this->input->post('n_ttb_discount3')),
            'v_ttb_discount1'       => str_replace(",", "", $this->input->post('v_ttb_discount1')),
            'v_ttb_discount2'       => str_replace(",", "", $this->input->post('v_ttb_discount2')),
            'v_ttb_discount3'       => str_replace(",", "", $this->input->post('v_ttb_discount3')), */
            'f_ttb_pkp'             => $this->input->post('f_ttb_pkp'),
            'f_ttb_plusppn'         => $this->input->post('f_ttb_plusppn'),
            'f_ttb_plusdiscount'    => $f_ttb_plusdiscount,
            'v_ttb_gross'           => str_replace(",", "", $this->input->post('v_ttb_gross')),
            'v_ttb_ppn'             => $v_ttb_ppn,
            'v_ttb_discounttotal'   => str_replace(",", "", $this->input->post('v_ttb_discounttotal')),
            'v_ttb_netto'           => str_replace(",", "", $this->input->post('v_ttb_netto')),
            'e_ttb_remark'          => $this->input->post('e_remark'),
            'd_update'              => current_datetime(),
            'v_ttb_sisa'            => str_replace(",", "", $this->input->post('v_ttb_netto')),
            'i_price_group'         => $this->input->post('i_price_group'),
            'i_alasan_retur'        => $this->input->post('i_alasan_retur'),
            'n_ppn_r'               => $this->input->post('n_ppn'),
        );
        $this->db->where('i_ttb', $id);
        $this->db->update('tm_ttbretur', $header);
        if (is_array($this->input->post('i_nota')) || is_object($this->input->post('i_nota'))) {
            $this->db->where('i_ttb', $id);
            $this->db->delete('tm_ttbretur_item');
            $i = 0;
            foreach ($this->input->post('i_nota') as $i_nota) {
                $item = array(
                    'i_ttb'             => $id,
                    'i_nota'            => $i_nota,
                    'd_nota'            => $this->input->post('d_nota')[$i],
                    'i_product1'        => $this->input->post('i_product1')[$i],
                    'i_product1_grade'  => $this->input->post('i_product1_grade')[$i],
                    'i_product1_motif'  => $this->input->post('i_product1_motif')[$i],
                    'n_quantity'        => $this->input->post('n_quantity')[$i],
                    'v_unit_price'      => str_replace(",", "", $this->input->post('v_unit_price')[$i]),
                    'n_ttb_discount1'   => str_replace(",", "", $this->input->post('n_ttb_discount1')[$i]),
                    'n_ttb_discount2'   => str_replace(",", "", $this->input->post('n_ttb_discount2')[$i]),
                    'n_ttb_discount3'   => str_replace(",", "", $this->input->post('n_ttb_discount3')[$i]),
                    'v_ttb_discount1'   => str_replace(",", "", $this->input->post('v_ttb_discount1')[$i]),
                    'v_ttb_discount2'   => str_replace(",", "", $this->input->post('v_ttb_discount2')[$i]),
                    'v_ttb_discount3'   => str_replace(",", "", $this->input->post('v_ttb_discount3')[$i]),
                    'e_ttb_remark'      => $this->input->post('e_ttb_remark')[$i],
                    'n_item_no'         => $i,
                );
                $this->db->insert('tm_ttbretur_item', $item);
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
            'f_ttb_cancel' => 't',
        );
        $this->db->where('i_ttb', $id);
        $this->db->update('tm_ttbretur', $table);
    }
}

/* End of file Mmaster.php */
