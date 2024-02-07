<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mspb extends CI_Model
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
                a.i_so AS id,
                to_char(a.d_so, 'YYYY-MM-DD') as d_entry,
                to_char(a.d_so, 'YYYYMM') as i_periode,
                a.i_so_id,
                c.i_customer_id || ' ~ ' || initcap(c.e_customer_name) AS e_customer_name,
                a.v_so::money AS v_so,
                initcap(b.e_salesman_name) AS e_salesman_name,
                a.f_so_cancel AS f_status,
                to_char(a.d_approve1, 'DD FMMonth YYYY') AS d_approve1,
                to_char(a.d_approve2, 'DD FMMonth YYYY') AS d_approve2,
                initcap(d.e_area_name) AS e_area_name,
                to_char(a.d_so_entry, 'YYYY-MM-DD HH24:MI') as d_entryso,             
                case when a.i_so_refference is not null then 'SPB TURUNAN' when c.d_approve is null  then 'PELANGGAN BARU' else ' - ' end as turunan,
                case when a.f_so_stockdaerah = 't' then 'CABANG' else 'PUSAT' end as e_pemenuhan,   
                a.n_print,
                case when a.d_notapprove is not null then 'TIDAK DISETUJUI : ' || a.i_notapprove  else e.e_status_so_name end as e_status_so_name,
                i_so_refference,
	            i_do,
                '$dfrom' AS dfrom,
                '$dto' AS dto,
                '$i_area' AS i_area
            FROM
                tm_so a
            INNER JOIN tr_salesman b ON
                (b.i_salesman = a.i_salesman)
            INNER JOIN tr_customer c ON
                (c.i_customer = a.i_customer)
            INNER JOIN tr_area d ON	
                (d.i_area = a.i_area)
            INNER JOIN tr_status_so e ON
                (e.i_status_so = a.i_status_so)
            INNER JOIN tm_user_area f ON
                (f.i_area = a.i_area)
            LEFT JOIN (SELECT DISTINCT i_so, string_agg(i_do::varchar,', ') AS i_do FROM tm_do WHERE f_do_cancel = 'f' GROUP BY 1) g ON
	            (g.i_so = a.i_so)
            WHERE
                f.i_user = '$this->i_user'
                AND a.i_company = '$this->i_company'
                AND a.d_so BETWEEN '$dfrom' AND '$dto'
                AND a.i_promo ISNULL
                $area
            ORDER BY
                a.i_so DESC
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
            } elseif ($data['e_status_so_name'] == "Sudah Dinotakan") {
                $color  = 'black';
                $status = "Sudah Dinotakan";
            } elseif ($data['e_status_so_name'] == "Siap Nota") {
                $color  = 'amber';
                $status = "Siap Nota";
            } elseif ($data['e_status_so_name'] == "Siap DKB") {
                $color  = 'pink';
                $status = "Siap DKB";
            } elseif ($data['e_status_so_name'] == "Siap SJ") {
                $color  = 'blue';
                $status = "Siap SJ";
            } elseif ($data['e_status_so_name'] == "Realisasi ke Gudang") {
                $color  = 'purple';
                $status = "Realisasi ke Gudang";
            } elseif ($data['e_status_so_name'] == "Menunggu Persetujuan Keuangan") {
                $color  = 'teal';
                $status = "Menunggu Persetujuan Keuangan";
            } elseif ($data['e_status_so_name'] == "Menunggu Persetujuan Admin Sales") {
                $color  = 'success';
                $status = "Menunggu Persetujuan Admin Sales";
            } else {
                $color  = 'red';
                $status = $data['e_status_so_name'];
            }
            $data = "<span class='badge bg-" . $color . " badge-pill'>" . $status . "</span>";
            return $data;
        });

        /** Cek Hak Akses, Apakah User Bisa Edit */
        $datatables->add('action', function ($data) {
            $id         = trim($data['id']);
            $f_status   = $data['f_status'];
            $d_approve1 = $data['d_approve1'];
            $d_approve2 = $data['d_approve2'];
            $dfrom      = $data['dfrom'];
            $dto        = $data['dto'];
            $i_so_refference        = $data['i_so_refference'];
            $i_do       = $data['i_do'];
            $i_periode  = $data['i_periode'];
            $i_area     = $data['i_area'];
            $data       = '';
            $data      .= "<a href='" . base_url() . $this->folder . '/view/' . encrypt_url($id) . '/' . encrypt_url($dfrom) . '/' . encrypt_url($dto) . '/' . encrypt_url($i_area) . "' title='View Data'><i class='fa fa-eye fa-lg warning darken-4 mr-1'></i></a>";
            if ($i_periode >= get_periode()) {
                if (check_role($this->id_menu, 3) && $f_status == 'f'  && ($d_approve1 == null || $d_approve1 == '') && ($d_approve2 == null || $d_approve2 == '') && ($i_so_refference == '' || $i_so_refference == null)) {
                    $data      .= "<a href='" . base_url() . $this->folder . '/edit/' . encrypt_url($id)  . '/' .  encrypt_url($dfrom) . '/' . encrypt_url($dto) . '/' . encrypt_url($i_area) . "' title='Edit Data'><i class='fa fa-pencil-square success darken-4 fa-lg mr-1'></i></a>";
                }
                if (check_role($this->id_menu, 5) && $f_status == 'f') {
                    $data      .= "<a href='#' onclick='openLink(\"" . $this->folder . "\",\"" . encrypt_url($id) . "\"); return false;' title='Print Data'><i class='fa fa-print fa-lg blue darken-4 mr-1'></i></a>";
                }
                if (check_role($this->id_menu, 4) && $f_status == 'f' && ($d_approve1 == null || $d_approve1 == '') && ($d_approve2 == null || $d_approve2 == '')) {
                    $data      .= "<a href='#' onclick='sweetdeletev33(\"" . $this->folder . "\",\"" . $id . "\",\"" . encrypt_url($dfrom)  . "\",\"" . encrypt_url($dto)  . '/' . encrypt_url($i_area) . "\");' title='Delete Data'><i class='fa fa-trash fa-lg red darken-4'></i></a>";
                } elseif (($i_so_refference == '' || $i_so_refference == null) && ($i_do == '' || $i_do == null)) {
                    $data      .= "<a href='#' onclick='sweetdeletev33(\"" . $this->folder . "\",\"" . $id . "\",\"" . encrypt_url($dfrom)  . "\",\"" . encrypt_url($dto)  . '/' . encrypt_url($i_area) . "\");' title='Delete Data'><i class='fa fa-trash fa-lg red darken-4'></i></a>";
                }
            }
            return $data;
        });
        $datatables->hide('e_status_so_name');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('i_area');
        $datatables->hide('i_so_refference');
        $datatables->hide('i_do');
        $datatables->hide('i_periode');
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

    /** Get Product Group */
    public function get_product_group($cari)
    {
        return $this->db->query("
            SELECT
                i_product_group,
                i_product_groupid,
                e_product_groupname
            FROM
                tr_product_group
            WHERE
                (e_product_groupname ILIKE '%$cari%' OR i_product_groupid ILIKE '%$cari%')
                AND f_product_groupactive = 't'
                AND i_company = '$this->i_company'
            ORDER BY
                e_product_groupname ASC 
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
                AND f_customer_active = 'true' 
                AND i_area = '$i_area'
                AND d_approve NOTNULL
            ORDER BY 3 ASC
        ", FALSE);
    }

    /** Get Detail Customer */
    public function get_customer_detail($icustomer)
    {
        return $this->db->query("
            SELECT
                a.i_customer,
                a.e_customer_address,
                a.i_price_group,
                a.f_customer_plusppn,
                a.n_customer_top,
                a.n_customer_discount1,
                a.n_customer_discount2,
                a.n_customer_discount3,
                a.e_customer_npwpcode,
                b.e_price_groupname,
                CASE
                    WHEN a.f_customer_plusppn = TRUE THEN 'Ya'
                    ELSE 'Tidak'
                END AS eppn
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

    /** Get Product */
    public function get_product($cari, $i_product_group, $i_price_group)
    {
        return $this->db->query("
            SELECT
                a.i_product,
                case
                    when s.i_product_statusid = 'STP1' then a.i_product_id || ' (*STP)'
                    when s.i_product_statusid = 'STP2' then a.i_product_id || ' (#STP)'
                    else a.i_product_id
                end as i_product_id,
                a.e_product_name,
                b.e_product_motifname,
                c.e_product_colorname
            FROM
                tr_product a
            INNER JOIN tr_product_motif b ON
                (a.i_product_motif = b.i_product_motif)
            INNER JOIN tr_product_color c ON
                (a.i_product_color = c.i_product_color)
            INNER JOIN tr_customer_price d ON
                (d.i_product = a.i_product)
            INNER JOIN tr_product_grade e ON
                (e.i_product_grade = d.i_product_grade)
            inner join tr_product_status s on
                (s.i_product_status = a.i_product_status)
            WHERE
                (a.e_product_name ILIKE '%$cari%'
                    OR a.i_product_id ILIKE '%$cari%')
                AND a.i_company = '$this->i_company'
                AND a.f_product_active = TRUE
                AND d.i_price_group = '$i_price_group'
                AND e.f_default = 't'
                AND a.i_product_group = '$i_product_group'
            ORDER BY
                e_product_name ASC
        ", FALSE);
    }


    public function get_product_price($i_price_group, $i_product_group, $i_product)
    {
        return $this->db->query("
            SELECT
                d.v_price,
                e.i_product_grade,
                a.i_product_status,
                a.i_product_motif,
                a.e_product_name
            FROM
                tr_product a
            INNER JOIN tr_product_motif b ON
                (a.i_product_motif = b.i_product_motif)
            INNER JOIN tr_product_color c ON
                (a.i_product_color = c.i_product_color)
            INNER JOIN tr_customer_price d ON
                (d.i_product = a.i_product)
            INNER JOIN tr_product_grade e ON
                (e.i_product_grade = d.i_product_grade)
            WHERE
                a.i_product = '$i_product'
                AND a.i_company = '$this->i_company'
                AND a.f_product_active = TRUE
                AND d.i_price_group = '$i_price_group'
                AND e.f_default = 't'
                AND a.i_product_group = '$i_product_group'
        ", FALSE);
    }

    /** Running Number Dokumen*/
    public function running_number($thbl, $tahun, $i_area)
    {
        /* $cek = $this->db->query("
            SELECT 
                substring(i_so_id, 1, 2) AS kode 
            FROM tm_so 
            WHERE i_company = '$this->i_company'
            AND i_area = '$i_area'
            ORDER BY i_so DESC");
        if ($cek->num_rows() > 0) {
            $kode = $cek->row()->kode;
        } else {
            $kode = 'SO';
        } */
        $cek = $this->db->query("
            SELECT 
                CASE
                    WHEN length(i_area_id)= 1 THEN '0' || i_area_id
                    ELSE i_area_id
                END AS kode
            FROM tr_area
            WHERE i_company = '$this->i_company'
            AND i_area = '$i_area'");
        if ($cek->num_rows() > 0) {
            $kode = $cek->row()->kode;
        } else {
            $kode = $i_area;
        }
        $query  = $this->db->query("
            SELECT
                max(substring(i_so_id, 9, 6)) AS max
            FROM
                tm_so
            WHERE to_char (d_so, 'yyyy') >= '$tahun'
            AND i_company = '$this->i_company'
            AND i_area = '$i_area'
            AND substring(i_so_id, 1, 2) = '$kode'
            AND substring(i_so_id, 4, 2) = substring('$thbl',1,2)
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
        $i_area = $this->input->post('i_area');
        return $this->db->query("
            SELECT 
                i_so_id
            FROM 
                tm_so 
            WHERE 
                upper(trim(i_so_id)) = upper(trim('$i_document'))
                AND i_area = '$i_area'
                AND i_company = '$this->i_company'
        ", FALSE);
    }

    /** Simpan Data */
    public function save()
    {
        $query = $this->db->query("SELECT max(i_so)+1 AS id FROM tm_so", TRUE);
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

        $i_so_id = $this->running_number($ym, $Y, $this->input->post('i_area'));

        $d_po = ($this->input->post('d_po') == '' || $this->input->post('d_po' == null)) ? null : $this->input->post('d_po');
        $v_so_discounttotal = ($this->input->post('tfoot_v_diskon') == '' || $this->input->post('tfoot_v_diskon') == null) ? 0 : str_replace(",", "", $this->input->post('tfoot_v_diskon'));
        $f_so_stockdaerah = ($this->input->post('f_so_stockdaerah') == 'on') ? 't' : 'f';
        $i_status_so = ($f_so_stockdaerah == 't') ? 2 : 1;
        $header = array(
            'i_company'             => $this->session->i_company,
            'i_so'                  => $id,
            'i_so_id'               => $i_so_id,
            'i_area'                => $this->input->post('i_area'),
            'i_customer'            => $this->input->post('i_customer'),
            'i_salesman'            => $this->input->post('i_salesman'),
            'i_price_group'         => $this->input->post('i_price_group'),
            'i_po_reff'             => $this->input->post('i_so_po'),
            'i_status_so'           => $i_status_so,
            'i_product_group'       => $this->input->post('i_product_group'),
            'd_so'                  => $this->input->post('d_document'),
            'd_so_entry'            => current_datetime(),
            'd_po_reff'             => $d_po,
            'e_customer_pkpnpwp'    => $this->input->post('e_customer_pkpnpwp'),
            'f_so_plusppn'          => $this->input->post('ppn'),
            'e_remark'              => $this->input->post('e_remarkh'),
            'f_so_stockdaerah'      => $f_so_stockdaerah,
            'n_so_toplength'        => $this->input->post('n_so_toplength'),
            'v_so_discounttotal'    => $v_so_discounttotal,
            'v_so'                  => str_replace(",", "", $this->input->post('tfoot_total')),
            'n_so_ppn'              => str_replace(",", "", $this->input->post('nppn')),
            'e_user_name'           => $this->input->post('e_user_name'),
        );
        $this->db->insert('tm_so', $header);

        if ($this->input->post('jml') > 0) {
            $i = 0;
            foreach ($this->input->post('i_product') as $i_product) {
                $item = array(
                    'i_so'             => $id,
                    'i_product'        => $i_product,
                    'i_product_grade'  => $this->input->post('i_product_grade')[$i],
                    'i_product_motif'  => $this->input->post('i_product_motif')[$i],
                    'i_product_status' => $this->input->post('i_product_status')[$i],
                    'e_product_name'   => $this->input->post('e_product_name')[$i],
                    'e_remark'         => $this->input->post('e_remark')[$i],
                    'n_order'          => str_replace(",", "", $this->input->post('n_order')[$i]),
                    'n_deliver'        => 0,
                    'n_item_no'        => $i,
                    'v_unit_price'     => str_replace(",", "", $this->input->post('v_price')[$i]),
                    'n_so_discount1'   => str_replace(",", "", $this->input->post('n_disc')[$i]),
                    'n_so_discount2'   => str_replace(",", "", $this->input->post('n_disc2')[$i]),
                    'n_so_discount3'   => str_replace(",", "", $this->input->post('n_disc3')[$i]),
                    'n_so_discount4'   => 0,
                    'v_so_discount1'   => str_replace(",", "", $this->input->post('v_disc')[$i]),
                    'v_so_discount2'   => str_replace(",", "", $this->input->post('v_disc2')[$i]),
                    'v_so_discount3'   => str_replace(",", "", $this->input->post('v_disc3')[$i]),
                    'v_so_discount4'   => 0,
                );
                $this->db->insert('tm_so_item', $item);
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
                i_po_reff AS e_po_reff,
                b.i_area_id,
                b.e_area_name,
	            r.e_city_name,
                e.i_salesman_id,
                e.e_salesman_name,
                f.i_product_groupid,
                f.e_product_groupname,
                c.i_customer_id,
                c.e_customer_name,
                c.e_customer_address,
                c.e_customer_phone,
                c.i_price_group,
                c.f_customer_plusppn,
                c.n_customer_top,
                c.d_customer_register,
                c.n_customer_discount1,
                c.n_customer_discount2,
                c.n_customer_discount3,
                c.e_customer_npwpcode,
                c.e_ekspedisi_cus,
                c.e_ekspedisi_bayar,
                c.d_approve,
                d.e_price_groupname,
                CASE
                    WHEN c.f_customer_plusppn = TRUE THEN 'Ya'
                    ELSE 'Tidak'
                END AS eppn,
                c.plafon::money as pla,
                h.e_customer_statusname as bl,
                case when a.f_so_stockdaerah = 't' then 'CABANG' else 'PUSAT' end as e_pemenuhan,
	            mm.sissa:: money as sissa
            FROM 
                tm_so a
            INNER JOIN tr_area b ON 
                (b.i_area = a.i_area)
            INNER JOIN tr_customer c ON 
                (c.i_customer = a.i_customer)
            INNER JOIN tr_price_group d ON
                (a.i_price_group = d.i_price_group)
            INNER JOIN tr_salesman e ON 
                (e.i_salesman = a.i_salesman)
            INNER JOIN tr_product_group f ON
                (a.i_product_group = f.i_product_group)
            inner join tr_city r on
                (r.i_city = c.i_city)
            inner join tr_customer_status h on (h.i_customer_status=c.i_customer_status)
            left join (select i_customer, sum(v_sisa) as sissa  from (select nn.i_customer, nn.v_sisa from tm_nota nn where nn.f_nota_cancel = 'f') as nm group by 1) as mm on (mm.i_customer=a.i_customer)
            WHERE i_so = '$id'
        ", FALSE);
    }

    /** Get Detail Untuk Edit */
    public function get_data_detail($id)
    {
        return $this->db->query("SELECT 
                a.*,
                case
                    when c.i_product_statusid = 'STP1' then b.i_product_id || ' (*STP)'
                    when c.i_product_statusid = 'STP2' then b.i_product_id || ' (#STP)'
                    else b.i_product_id
                end as i_product_id
            from
                tm_so_item a
            inner join tr_product b on
                (b.i_product = a.i_product)
            inner join tr_product_status c on
                (c.i_product_status = a.i_product_status)
            WHERE i_so = '$id'
            order by b.e_product_name
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
                 i_so_id
             FROM 
                 tm_so 
             WHERE 
                 trim(upper(i_so_id)) <> trim(upper('$i_document_old'))
                 AND trim(upper(i_so_id)) = trim(upper('$i_document'))
                 AND i_area = '$i_area'
                 AND i_company = '$this->i_company'
         ", FALSE);
    }

    /** Update Data */
    public function update()
    {
        $id = $this->input->post('id');
        $d_po = ($this->input->post('d_po') == '' || $this->input->post('d_po' == null)) ? null : $this->input->post('d_po');
        $v_so_discounttotal = ($this->input->post('tfoot_v_diskon') == '' || $this->input->post('tfoot_v_diskon') == null) ? 0 : str_replace(",", "", $this->input->post('tfoot_v_diskon'));
        $f_so_stockdaerah = ($this->input->post('f_so_stockdaerah') == 'on') ? 't' : 'f';
        $i_status_so = ($f_so_stockdaerah == 't') ? 2 : 1;
        $header = array(
            'i_company'             => $this->session->i_company,
            'i_so_id'               => str_replace("_", "", strtoupper($this->input->post('i_document'))),
            'i_area'                => $this->input->post('i_area'),
            'i_customer'            => $this->input->post('i_customer'),
            'i_salesman'            => $this->input->post('i_salesman'),
            'i_price_group'         => $this->input->post('i_price_group'),
            'i_po_reff'             => $this->input->post('i_so_po'),
            'i_status_so'           => $i_status_so,
            'i_product_group'       => $this->input->post('i_product_group'),
            'd_so'                  => $this->input->post('d_document'),
            'd_so_update'           => current_datetime(),
            'd_po_reff'             => $d_po,
            'e_customer_pkpnpwp'    => $this->input->post('e_customer_pkpnpwp'),
            'f_so_plusppn'          => $this->input->post('ppn'),
            'e_remark'              => $this->input->post('e_remarkh'),
            'f_so_stockdaerah'      => $f_so_stockdaerah,
            'n_so_toplength'        => $this->input->post('n_so_toplength'),
            'v_so_discounttotal'    => $v_so_discounttotal,
            'v_so'                  => str_replace(",", "", $this->input->post('tfoot_total')),
            'n_so_ppn'              => str_replace(",", "", $this->input->post('nppn')),
        );
        $this->db->where('i_so', $id);
        $this->db->update('tm_so', $header);

        if ($this->input->post('jml') > 0) {
            $this->db->where('i_so', $id);
            $this->db->delete('tm_so_item');
            $i = 0;
            foreach ($this->input->post('i_product') as $i_product) {
                $item = array(
                    'i_so'             => $id,
                    'i_product'        => $i_product,
                    'i_product_grade'  => $this->input->post('i_product_grade')[$i],
                    'i_product_motif'  => $this->input->post('i_product_motif')[$i],
                    'i_product_status' => $this->input->post('i_product_status')[$i],
                    'e_product_name'   => $this->input->post('e_product_name')[$i],
                    'e_remark'         => $this->input->post('e_remark')[$i],
                    'n_order'          => str_replace(",", "", $this->input->post('n_order')[$i]),
                    'n_deliver'        => 0,
                    'n_item_no'        => $i,
                    'v_unit_price'     => str_replace(",", "", $this->input->post('v_price')[$i]),
                    'n_so_discount1'   => str_replace(",", "", $this->input->post('n_disc')[$i]),
                    'n_so_discount2'   => str_replace(",", "", $this->input->post('n_disc2')[$i]),
                    'n_so_discount3'   => str_replace(",", "", $this->input->post('n_disc3')[$i]),
                    'n_so_discount4'   => 0,
                    'v_so_discount1'   => str_replace(",", "", $this->input->post('v_disc')[$i]),
                    'v_so_discount2'   => str_replace(",", "", $this->input->post('v_disc2')[$i]),
                    'v_so_discount3'   => str_replace(",", "", $this->input->post('v_disc3')[$i]),
                    'v_so_discount4'   => 0,
                );
                $this->db->insert('tm_so_item', $item);
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
            'f_so_cancel' => 't',
        );
        $this->db->where('i_so', $id);
        $this->db->update('tm_so', $table);
    }

    /** Update Print */
    public function update_print($id)
    {
        $this->db->query("UPDATE tm_so SET n_print = n_print + 1 WHERE i_so = '$id' ", FALSE);
    }
}

/* End of file Mmaster.php */
