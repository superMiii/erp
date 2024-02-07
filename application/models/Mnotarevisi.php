<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mnotarevisi extends CI_Model
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
                a.d_nota as tgl,
                a.i_nota AS id,
                a.d_nota, 
                a.i_nota_id,
                to_char(a.d_nota, 'YYYYMM') AS i_periode,
                c.i_do,
                to_char(c.d_do, 'DD Month YYYY') AS d_do,
                c.i_do_id,
                to_char(e.d_so, 'DD Month YYYY') AS d_so,
                e.i_so_id,
                f.i_customer_id || ' ~ ' || f.e_customer_name AS customer,
                g.e_area_name,                
                case when e.f_so_stockdaerah = 't' then 'CABANG' else 'PUSAT' end as e_pemenuhan,
                a.v_nota_netto as v_nota_netto,
                a.v_sisa,
                a.n_print
            FROM
                tm_nota a
            INNER JOIN (SELECT DISTINCT i_nota, i_do FROM tm_nota_item) b ON
                (b.i_nota = a.i_nota)
            INNER JOIN tm_do c ON
                (c.i_do = b.i_do AND c.f_do_cancel = 'f')
            INNER JOIN tm_so e ON
                (e.i_so = c.i_so)
            INNER JOIN tr_customer f ON
                (f.i_customer = a.i_customer)
            INNER JOIN tr_area g ON
                (g.i_area = a.i_area)
            INNER JOIN tm_user_area h ON
                (h.i_area = g.i_area)
            WHERE
                a.i_company = '$this->i_company'
                AND h.i_user = '$this->i_user'
                AND a.d_nota BETWEEN '$dfrom' AND '$dto'
                AND a.f_nota_cancel = 'f'
                $area
            ORDER BY
                1 ASC
        ", FALSE);

        $datatables->edit('v_nota_netto', function ($data) {
            return format_rupiah($data['v_nota_netto']);
        });

        $datatables->edit('v_sisa', function ($data) {
            return format_rupiah($data['v_sisa']);
        });

        /** Cek Hak Akses, Apakah User Bisa Edit */
        $datatables->add('action', function ($data) {
            $id             = $data['id'];
            $i_do           = $data['i_do'];
            $n_print        = $data['n_print'];
            $v_nota_netto   = $data['v_nota_netto'];
            $v_sisa         = $data['v_sisa'];
            $i_periode      = $data['i_periode'];
            $data           = '';
            $data      .= "<a href='" . base_url() . $this->folder . '/view/' . encrypt_url($id) . "' title='View Data'><i class='fa fa-eye fa-lg warning darken-4 mr-1'></i></a>";
            if ($i_periode >= get_periode3()) {
                if (check_role($this->id_menu, 3) && $v_nota_netto == $v_sisa) {
                    $data      .= "<a href='" . base_url() . $this->folder . '/edit/' . encrypt_url($i_do) . '/' . encrypt_url($id) . "' title='Edit Data'><i class='fa fa-pencil-square success darken-4 fa-lg mr-1'></i></a>";
                }
                if (check_role($this->id_menu, 5) && $n_print == '0') {
                    $data      .= "<a href='#' onclick='openLink(\"" . $this->folder . "\",\"" . encrypt_url($id) . "\"); return false;' title='Print Data'><i class='fa fa-print fa-lg blue darken-4 mr-1'></i></a>";
                }
            }
            return $data;
        });
        $datatables->hide('i_do');
        $datatables->hide('i_periode');
        $datatables->hide('n_print');
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

    /** Get Data Untuk Edit */
    public function get_data_nota($id)
    {
        return $this->db->query("SELECT
                DISTINCT a.*,
                a.i_customer,
                h.i_salesman,
                c.i_do,
                c.i_do_id,
                c.d_do,
                d.i_so,
                d.d_so,
                d.i_so_id,
                d.n_so_ppn,
                d.f_so_plusppn,
                d.v_so_discounttotal,
                g.i_area_id,
                g.e_area_name,
                h.i_salesman_id,
                h.e_salesman_name,
                e.i_customer_id,
                e.e_customer_name,
                e.e_customer_address,
                e.e_customer_phone,
                i.e_city_name,
                f.e_price_groupname,
                d.e_customer_pkpnpwp,
                COALESCE(d.n_so_toplength, 0) AS n_so_toplength
            FROM
                tm_nota a
            INNER JOIN tm_nota_item b ON
                (b.i_nota = a.i_nota)
            INNER JOIN tm_do c ON
                (c.i_do = b.i_do)
            INNER JOIN tm_so d ON
                (d.i_so = c.i_so)
            INNER JOIN tr_customer e ON
                (e.i_customer = a.i_customer)
            INNER JOIN tr_price_group f ON
                (f.i_price_group = d.i_price_group)
            INNER JOIN tr_area g ON
                (g.i_area = a.i_area)
            INNER JOIN tr_salesman h ON
                (h.i_salesman = d.i_salesman)
            INNER JOIN tr_city i ON
                (i.i_city = e.i_city)
            WHERE
                a.i_nota = '$id'
        ", FALSE);
    }

    /** Get Detail Untuk Edit */
    public function get_data_nota_detail($id)
    {
        return $this->db->query("SELECT 
                a.*,
                b.i_product_id,
                b.e_product_name,
                c.e_product_motifname
            FROM 
                tm_nota_item a
            INNER JOIN tr_product b ON 
                (b.i_product = a.i_product)
            INNER JOIN tr_product_motif c ON 
                (c.i_product_motif = a.i_product_motif)
            WHERE a.i_nota = '$id'
        ", FALSE);
    }

    /** Get Data Untuk Edit */
    public function get_data_edit($id)
    {
        return $this->db->query("SELECT 
                a.*,
                aa.*,
                a.e_remark as remark2,
                b.i_area_id,
                b.e_area_name,
                f.e_city_name,
                e.i_salesman_id,
                e.e_salesman_name,
                c.i_customer_id,
                c.e_customer_name,
                c.e_customer_address,
                c.e_customer_phone,
                n_customer_discount1, 
                n_customer_discount2, 
                n_customer_discount3
            FROM 
                tm_do a
            INNER JOIN tm_so aa ON 
                (aa.i_so = a.i_so)
            INNER JOIN tr_area b ON 
                (b.i_area = a.i_area)
            INNER JOIN tr_customer c ON 
                (c.i_customer = a.i_customer)
            INNER JOIN tr_salesman e ON 
                (e.i_salesman = a.i_salesman)
            INNER JOIN tr_city f ON
                (f.i_city = c.i_city)
            WHERE i_do = '$id'
        ", FALSE);
    }

    /** Get Detail Untuk Edit */
    public function get_data_detail_edit($id)
    {
        return $this->db->query("SELECT 
                a.*,
                aa.*,
                b.i_product_id,
                c.e_product_motifname,
                a.n_deliver AS n_do,
                aa.n_deliver + a.n_deliver  AS n_op,
                COALESCE(n_quantity_stock + a.n_deliver,0) AS n_stock
            FROM 
                tm_do_item a
            INNER JOIN tm_do ab ON 
                (ab.i_do = a.i_do)
            INNER JOIN tm_so_item aa ON 
                (ab.i_so = aa.i_so AND aa.i_product = a.i_product)
            INNER JOIN tr_product b ON 
                (b.i_product = a.i_product)
            INNER JOIN tr_product_motif c ON 
                (c.i_product_motif = a.i_product_motif)
            INNER JOIN tm_so d ON 
                (d.i_so = aa.i_so)
            LEFT JOIN tm_ic e ON 
                (e.i_product = a.i_product AND 
                a.i_product_motif = e.i_product_motif AND 
                e.i_product_grade = a.i_product_grade AND
                ab.i_company = e.i_company AND 
                e.i_store = d.i_store AND
                d.i_store_location = e.i_store_location)
            WHERE a.i_do = '$id'
        ", FALSE);
    }

    /** Update Data */
    public function update()
    {
        $id = $this->input->post('id');
        $d_do = $this->input->post('d_document');
        $i_nota = $this->input->post('i_nota');
        $i_so = $this->input->post('i_so');
        $n_customer_discount1 = $this->input->post('n_customer_discount1');
        $n_customer_discount2 = $this->input->post('n_customer_discount2');
        $n_customer_discount3 = $this->input->post('n_customer_discount3');
        if (is_array($this->input->post('i_product')) || is_object($this->input->post('i_product'))) {
            $i = 0;
            foreach ($this->input->post('i_product') as $i_product) {
                if ($i_product != '' || $i_product != null) {
                    $i_product_old = $this->input->post('i_product_old')[$i];
                    if ($i_product_old == '') {
                        $item = array(
                            'i_do'              => $id,
                            'i_product'         => $i_product,
                            'i_product_grade'   => $this->input->post('i_product_grade')[$i],
                            'i_product_motif'   => $this->input->post('i_product_motif')[$i],
                            'n_deliver'         => str_replace(",", "", $this->input->post('n_deliver')[$i]),
                            'e_product_name'    => $this->input->post('e_product_name')[$i],
                            'n_item_no'         => $i,
                        );
                        $this->db->insert('tm_do_item', $item);

                        $detail = array(
                            'i_so'             => $i_so,
                            'i_product'        => $i_product,
                            'i_product_grade'  => $this->input->post('i_product_grade')[$i],
                            'i_product_motif'  => $this->input->post('i_product_motif')[$i],
                            'i_product_status' => $this->input->post('i_product_status')[$i],
                            'e_product_name'   => $this->input->post('e_product_name')[$i],
                            'e_remark'         => "Penambahan Item Dari SJ",
                            'n_order'          => str_replace(",", "", $this->input->post('n_deliver')[$i]),
                            'n_deliver'        => str_replace(",", "", $this->input->post('n_deliver')[$i]),
                            'n_item_no'        => $i,
                            'v_unit_price'     => str_replace(",", "", $this->input->post('v_price')[$i]),
                            'n_so_discount1'   => str_replace(",", "", $n_customer_discount1),
                            'n_so_discount2'   => str_replace(",", "", $n_customer_discount2),
                            'n_so_discount3'   => str_replace(",", "", $n_customer_discount3),
                            'n_so_discount4'   => 0
                        );
                        $this->db->insert('tm_so_item', $detail);
                    } elseif ($i_product != $i_product_old) {
                        $i_do_item = $this->input->post('i_do_item')[$i];
                        $item = array(
                            'i_do'              => $id,
                            'i_product'         => $i_product,
                            'i_product_grade'   => $this->input->post('i_product_grade')[$i],
                            'i_product_motif'   => $this->input->post('i_product_motif')[$i],
                            'n_deliver'         => str_replace(",", "", $this->input->post('n_deliver')[$i]),
                            'e_product_name'    => $this->input->post('e_product_name')[$i],
                            'n_item_no'         => $i,
                        );
                        $this->db->where('i_do_item', $i_do_item);
                        $this->db->update('tm_do_item', $item);
                        $i_so_item = $this->input->post('i_so_item')[$i];
                        $detail = array(
                            'i_so'             => $i_so,
                            'i_product'        => $i_product,
                            'i_product_grade'  => $this->input->post('i_product_grade')[$i],
                            'i_product_motif'  => $this->input->post('i_product_motif')[$i],
                            'i_product_status' => $this->input->post('i_product_status')[$i],
                            'e_product_name'   => $this->input->post('e_product_name')[$i],
                            'e_remark'         => "Perubahan Item Dari SJ",
                            'n_order'          => str_replace(",", "", $this->input->post('n_deliver')[$i]),
                            'n_deliver'        => str_replace(",", "", $this->input->post('n_deliver')[$i]),
                            'n_item_no'        => $i,
                            'v_unit_price'     => str_replace(",", "", $this->input->post('v_price')[$i]),
                            'n_so_discount1'   => str_replace(",", "", $this->input->post('n_so_discount1')[$i]),
                            'n_so_discount2'   => str_replace(",", "", $this->input->post('n_so_discount2')[$i]),
                            'n_so_discount3'   => str_replace(",", "", $this->input->post('n_so_discount3')[$i]),
                            'n_so_discount4'   => 0
                        );
                        $this->db->where('i_so_item', $i_so_item);
                        $this->db->update('tm_so_item', $detail);
                    } else {
                        $i_do_item = $this->input->post('i_do_item')[$i];
                        $item = array(
                            'i_do'              => $id,
                            'i_product'         => $i_product,
                            'i_product_grade'   => $this->input->post('i_product_grade')[$i],
                            'i_product_motif'   => $this->input->post('i_product_motif')[$i],
                            'n_deliver'         => str_replace(",", "", $this->input->post('n_deliver')[$i]),
                            'e_product_name'    => $this->input->post('e_product_name')[$i],
                            'n_item_no'         => $i,
                        );
                        $this->db->where('i_do_item', $i_do_item);
                        $this->db->update('tm_do_item', $item);

                        $n_deliver = str_replace(",", "", $this->input->post('n_deliver')[$i]);
                        $n_deliver_old = str_replace(",", "", $this->input->post('n_deliver_old')[$i]);
                        $n_order = str_replace(",", "", $this->input->post('n_order')[$i]);
                        if ($n_deliver > $n_order) {
                            $this->db->query("UPDATE tm_so_item SET n_deliver = $n_deliver, n_order = $n_deliver WHERE i_so = '$i_so' AND i_product = '$i_product'");
                        } elseif ($n_deliver_old > 0 && $n_deliver == 0) {
                            $this->db->query("UPDATE tm_so_item SET n_deliver = $n_deliver, n_order = $n_deliver WHERE i_so = '$i_so' AND i_product = '$i_product'");
                        } elseif (($n_deliver_old > 0 || $n_deliver_old == 0) && $n_deliver > 0 && $n_order == 0) {
                            $this->db->query("UPDATE tm_so_item SET n_order = $n_deliver, n_deliver = $n_deliver WHERE i_so = '$i_so' AND i_product = '$i_product'");
                        } else {
                            $this->db->query("UPDATE tm_so_item SET n_deliver = (n_deliver - $n_deliver_old) +  $n_deliver WHERE i_so = '$i_so' AND i_product = '$i_product'");
                        }
                        /** Update SO Item */
                    }
                }
                $i++;
            }
            $this->hitung_so($i_so);
            $this->hitung_nota($id, $i_nota, $i_so, $d_do);
        } else {
            die;
        }

        /* $table = array(
            'i_status_so' => '6',
        );
        $this->db->where('i_so', $this->input->post('i_so'));
        $this->db->update('tm_so', $table); */
    }

    /** Cancel Data */
    public function delete($id)
    {
        $table = array(
            'f_do_cancel' => 't',
        );
        $this->db->where('i_do', $id);
        $this->db->update('tm_do', $table);

        $query = $this->db->query("SELECT i_so FROM tm_do WHERE i_do ='$id' ", FALSE);
        if ($query->num_rows() > 0) {
            $i_so = $query->row()->i_so;
            $this->db->query("UPDATE tm_so SET i_status_so = '5' WHERE i_so = '$i_so' ", FALSE);
            $detail = $this->db->query("SELECT * FROM tm_do_item WHERE i_do = '$id' ", FALSE);
            if ($detail->num_rows() > 0) {
                foreach ($detail->result() as $key) {
                    $this->db->query("UPDATE tm_so_item SET n_deliver = n_deliver - $key->n_deliver WHERE i_so = '$i_so' AND i_product = '$key->i_product' ", FALSE);
                }
            }
        }
    }

    /** Get Product */
    public function get_product($cari, $i_product_group, $i_price_group)
    {
        return $this->db->query("SELECT
                a.i_product,
                a.i_product_id ,
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
            WHERE
                (a.e_product_name ILIKE '%$cari%'
                    OR a.i_product_id ILIKE '%$cari%')
                AND a.i_company = '$this->i_company'
                AND d.i_price_group = '$i_price_group'
                AND e.f_default = 't'
                AND a.i_product_group = '$i_product_group'
            ORDER BY
                e_product_name ASC
        ", FALSE);
    }


    public function get_product_price($i_price_group, $i_product_group, $i_product, $i_store, $i_store_location)
    {
        return $this->db->query("SELECT
                d.v_price,
                e.i_product_grade,
                a.i_product_status,
                a.i_product_motif,
                a.e_product_name,
                b.e_product_motifname,
                COALESCE(f.n_quantity_stock) AS n_stock
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
            LEFT JOIN tm_ic f ON 
                (f.i_product = a.i_product AND 
                a.i_product_motif = f.i_product_motif AND 
                f.i_product_grade = d.i_product_grade AND
                a.i_company = f.i_company AND 
                f.i_store = '$i_store' AND
                f.i_store_location = '$i_store_location')
            WHERE
                a.i_product = '$i_product'
                AND a.i_company = '$this->i_company'
                AND d.i_price_group = '$i_price_group'
                AND e.f_default = 't'
                AND a.i_product_group = '$i_product_group'
        ", FALSE);
    }

    /*** Hitung dan Update SO ***/
    public function hitung_so($i_so)
    {
        $header = $this->db->query("SELECT f_so_plusppn, v_so_discounttotal, n_so_ppn FROM tm_so WHERE i_so = '$i_so'", FALSE);
        if ($header->num_rows() > 0) {
            $row = $header->row();
            $f_ppn = $row->f_so_plusppn;
            $v_so_discounttotal = $row->v_so_discounttotal;
            $n_so_ppn = $row->n_so_ppn;
        } else {
            $f_ppn = $this->session->f_company_plusppn;
            $v_so_discounttotal = 0;
            $n_so_ppn = $this->session->n_ppn;
        }

        $item = $this->db->query("SELECT * FROM tm_so_item WHERE i_so = '$i_so' ", FALSE);
        if ($item->num_rows() > 0) {
            $subtotal = 0;
            $distotal = 0;
            foreach ($item->result() as $key) {
                $total = $key->n_order * $key->v_unit_price;
                $v_diskon1 = $total * ($key->n_so_discount1 / 100);
                $v_diskon2 = ($total - $v_diskon1) * ($key->n_so_discount2 / 100);
                $v_diskon3 = ($total - $v_diskon1 - $v_diskon2) * ($key->n_so_discount3 / 100);
                $v_diskon4 = ($total - $v_diskon1 - $v_diskon2 - $v_diskon3) * ($key->n_so_discount4 / 100);
                $v_total_discount = $v_diskon1 + $v_diskon2 + $v_diskon3 + $v_diskon4;
                $distotal += $v_total_discount;
                $subtotal += $total;
                $this->db->query("UPDATE tm_so_item SET v_so_discount1 = '$v_diskon1', v_so_discount2 = '$v_diskon2', v_so_discount3 = '$v_diskon3', v_so_discount4 = '$v_diskon4' WHERE i_so_item = '$key->i_so_item' ");
            }
            $dpp = $subtotal - $distotal - $v_so_discounttotal;
            $nppn = ($f_ppn == 't') ? $n_so_ppn : 0;
            $ppn = $dpp * ($nppn / 100);
            $grandtotal = round($dpp + $ppn);
            $this->db->query("UPDATE tm_so SET v_so = '$grandtotal' WHERE i_so = '$i_so' ");
        }
    }

    /*** Hitung dan Update Nota ***/
    public function hitung_nota($i_do, $i_nota, $i_so, $d_do)
    {
        $header = $this->db->query("SELECT f_so_plusppn, v_so_discounttotal, n_so_ppn FROM tm_so WHERE i_so = '$i_so'", FALSE);
        if ($header->num_rows() > 0) {
            $row = $header->row();
            $f_ppn = $row->f_so_plusppn;
            $v_so_discounttotal = $row->v_so_discounttotal;
            $n_so_ppn = $row->n_so_ppn;
        } else {
            $f_ppn = $this->session->f_company_plusppn;
            $v_so_discounttotal = 0;
            $n_so_ppn = $this->session->n_ppn;
        }

        $item = $this->db->query("SELECT a.i_product, a.i_product_grade, a.i_product_motif, a.n_deliver, c.v_unit_price, a.e_product_name, c.n_so_discount1, c.n_so_discount2, c.n_so_discount3, c.n_so_discount4 
            FROM tm_do_item a
            INNER JOIN tm_do b ON (b.i_do = a.i_do AND b.f_do_cancel = 'f')
            INNER JOIN tm_so_item c ON (c.i_so = b.i_so AND a.i_product = c.i_product)
            WHERE a.i_do = '$i_do' AND a.n_deliver > 0; ", FALSE);
        if ($item->num_rows() > 0) {
            $this->db->where('i_nota', $i_nota);
            $this->db->delete('tm_nota_item');
            $i = 0;
            $subtotal = 0;
            $distotal = 0;
            foreach ($item->result() as $key) {
                $i++;
                $total = $key->n_deliver * $key->v_unit_price;
                $v_diskon1 = $total * ($key->n_so_discount1 / 100);
                $v_diskon2 = ($total - $v_diskon1) * ($key->n_so_discount2 / 100);
                $v_diskon3 = ($total - $v_diskon1 - $v_diskon2) * ($key->n_so_discount3 / 100);
                $v_diskon4 = ($total - $v_diskon1 - $v_diskon2 - $v_diskon3) * ($key->n_so_discount4 / 100);
                $v_total_discount = $v_diskon1 + $v_diskon2 + $v_diskon3 + $v_diskon4;
                $distotal += $v_total_discount;
                $subtotal += $total;
                $item = array(
                    'i_nota'           => $i_nota,
                    'i_do'             => $i_do,
                    'd_do'             => $d_do,
                    'i_product'        => $key->i_product,
                    'i_product_grade'  => $key->i_product_grade,
                    'i_product_motif'  => $key->i_product_motif,
                    'n_deliver'        => $key->n_deliver,
                    'v_unit_price'     => $key->v_unit_price,
                    'e_product_name'   => $key->e_product_name,
                    'n_item_no'        => $i,
                    'n_nota_discount1' => $key->n_so_discount1,
                    'v_nota_discount1' => $v_diskon1,
                    'n_nota_discount2' => $key->n_so_discount2,
                    'v_nota_discount2' => $v_diskon2,
                    'n_nota_discount3' => $key->n_so_discount3,
                    'v_nota_discount3' => $v_diskon3,
                    'n_nota_discount4' => $key->n_so_discount4,
                    'v_nota_discount4' => $v_diskon4,
                );
                $this->db->insert('tm_nota_item', $item);
            }
            $dpp = $subtotal - $distotal - $v_so_discounttotal;
            $v_nota_discount = $distotal + $v_so_discounttotal;
            $nppn = ($f_ppn == 't') ? $n_so_ppn : 0;
            $ppn = $dpp * ($nppn / 100);
            $grandtotal = round($dpp + $ppn);
            $f_plus_meterai  = $this->session->f_plus_meterai;
            $v_meterai_limit = (float)$this->session->v_meterai_limit;
            if ($f_plus_meterai == 't' && $grandtotal >= $v_meterai_limit) {
                $v_meterai   = (float)$this->session->v_meterai;
            } else {
                $v_meterai   = 0;
            };

            $header = array(
                'd_nota_update'             => current_datetime(),
                'v_nota_gross'              => $subtotal,
                'v_nota_ppn'                => $ppn,
                'v_nota_discount'           => $v_nota_discount,
                'v_nota_netto'              => $grandtotal,
                'v_sisa'                    => $grandtotal,
                'n_print'                   => 0,
                'n_faktur_komersialprint'   => 0,
                'n_pajak_print'             => 0,
                'v_meterai'                 => $v_meterai,
                'v_meterai_sisa'            => $v_meterai,
            );
            $this->db->where('i_nota', $i_nota);
            $this->db->update('tm_nota', $header);
        }
    }
}

/* End of file Mmaster.php */
