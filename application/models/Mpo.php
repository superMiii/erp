<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mpo extends CI_Model
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

        $i_supplier = $this->input->post('i_supplier', TRUE);
        if ($i_supplier == '') {
            $i_supplier = $this->uri->segment(5);
        }

        if ($i_supplier != '0') {
            $supplier = "AND a.i_supplier = '$i_supplier' ";
        } else {
            $supplier = "";
        }

        // var_dump($dfrom . $dto);

        $dfrom  = date('Y-m-d', strtotime($dfrom));
        $dto    = date('Y-m-d', strtotime($dto));


        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("WITH cte as (
            select
                a.d_po as tgl,
                a.i_po,
                a.d_po,
                a.i_po_id,
                to_char(a.d_po, 'YYYYMM') as i_periode,
                case
                    when a.i_so is not null then b.i_so_id
                    else c.i_sr_id
                end as i_reff,
                to_char(case when a.i_so is not null then a.d_so else a.d_sr end, 'dd-mm-yyyy') as d_reff,
                d.e_supplier_name,
                a.f_po_cancel,
                e.e_status_po_name
            from
                tm_po a
            left join tm_so b on
                (a.i_so = b.i_so)
            left join tm_sr c on
                (a.i_sr = c.i_sr)
            left join tr_supplier d on
                (a.i_supplier = d.i_supplier)
            left join tr_status_po e on
                (a.i_status_po = e.i_status_po)
            where
                a.d_po between '$dfrom' and '$dto'
                and a.i_company = '$this->i_company'
                $supplier
                            )
                            select
                a.d_po as tgl,
                a.i_po as id,
                a.d_po,
                a.i_po_id,
                a.i_periode,
                a.i_reff,
                a.d_reff,
                a.e_supplier_name,
                a.e_status_po_name,
                a.f_po_cancel,
                case
                    when a.f_po_cancel = true then false
                    when b.i_po is not null then false
                    else true
                end as editable,
                '$dfrom' AS dfrom,
                '$dto' AS dto  
            from
                cte a
            left join (
                select
                    distinct i_po
                from
                    tm_gr_item
                where
                    i_po in (
                    select
                        i_po
                    from
                        cte)
                            ) as b on
                (a.i_po = b.i_po)
            left join (
                select
                    i_po,
                    case
                        when sum(n_delivery - n_order) < 0 then 1
                        else 2
                    end as i_status_po
                from
                    tm_po_item tpi
                where
                    i_po in (
                    select
                        i_po
                    from
                        cte)
                group by
                    1
                            ) as c on
                (a.i_po = c.i_po)
            left join tr_status_po e on
                (c.i_status_po = e.i_status_po)
            order by
                1 asc
        ", FALSE);

        $datatables->edit('f_po_cancel', function ($data) {
            if ($data['f_po_cancel'] == 't') {
                $status = $this->lang->line('Batal');
                $color  = 'danger';
            } else {
                $color  = 'success';
                $status = $data['e_status_po_name'];
            }
            $data = "<span class='btn btn-outline-" . $color . " btn-sm round'>" . $status . "</span>";
            return $data;
        });

        /** Cek Hak Akses, Apakah User Bisa Edit */
        $datatables->add('action', function ($data) {
            $id         = trim($data['id']);
            $editable   = $data['editable'];
            $i_periode  = $data['i_periode'];
            $dfrom      = $data['dfrom'];
            $dto        = $data['dto'];
            $data       = '';
            $data      .= "<a href='" . base_url() . $this->folder . '/view/' . encrypt_url($id) . '/' . encrypt_url($dfrom) . '/' . encrypt_url($dto) . "' title='View Data'><i class='fa fa-eye fa-lg warning darken-4 mr-1'></i></a>";
            // if ($i_periode >= get_periode()) {
            if (check_role($this->id_menu, 3) && $editable == 't') {
                $data      .= "<a href='" . base_url() . $this->folder . '/edit/' . encrypt_url($id)  . '/' .  encrypt_url($dfrom) . '/' . encrypt_url($dto) . "' title='Edit Data'><i class='fa fa-pencil-square success darken-4 fa-lg mr-1'></i></a>";
            }

            if (check_role($this->id_menu, 5)) {
                $data      .= "<a href='#' onclick='openLink(\"" . $this->folder . "\",\"" . encrypt_url($id) . "\"); return false;' title='Print Data'><i class='fa fa-print fa-lg blue darken-4 mr-1'></i></a>";
            }

            if (check_role($this->id_menu, 4) && $editable == 't') {
                $data      .= "<a href='#' onclick='sweetdeletev2link(\"" . $this->folder . "\",\"" . $id . "\",\"" . encrypt_url($dfrom)  . "\",\"" . encrypt_url($dto)  . "\");' title='Delete Data'><i class='fa fa-trash fa-lg red darken-4'></i></a>";
            }
            return $data;
            // }
        });
        $datatables->hide('e_status_po_name');
        $datatables->hide('editable');
        $datatables->hide('i_periode');
        $datatables->hide('id');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        return $datatables->generate();
    }

    /** Get Supplier */
    public function get_supplier0($cari)
    {
        return $this->db->query("SELECT distinct
                xx.i_supplier, i_supplier_id , e_supplier_name AS e_supplier_name
            FROM 
                tr_supplier xx
            inner join tm_po yy on (xx.i_supplier =yy.i_supplier)
            WHERE 
                (e_supplier_name ILIKE '%$cari%' OR i_supplier_id ILIKE '%$cari%')
                AND xx.i_company = '$this->i_company' 
                AND f_supplier_active = 'true' 
            ORDER BY 3 ASC
        ", FALSE);
    }


    public function serverside2()
    {
        $dfrom = $this->input->post('dfrom', TRUE);
        if ($dfrom == '') {
            $dfrom = $this->uri->segment(3);
        }

        $dto = $this->input->post('dto', TRUE);
        if ($dto == '') {
            $dto = $this->uri->segment(4);
        }

        $dfrom  = date('Y-m-d', strtotime($dfrom));
        $dto    = date('Y-m-d', strtotime($dto));

        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("SELECT i_so, i_so_id, d_so, b.e_customer_name, c.e_area_name, 'SO' as jenis,
        '$dfrom' AS dfrom,
        '$dto' AS dto
         from (
                select a.i_so, a.i_so_id, to_char(d_so, 'dd-mm-yyyy') as d_so,  a.i_area, a.i_customer from tm_so a
                inner join tm_so_item b on (a.i_so = b.i_so)
                where a.f_request_op = true and f_so_cancel = false 
                and b.i_po is null and b.n_op > 0 and a.d_so between '$dfrom' and '$dto' AND a.i_company = '$this->i_company'
                group by 1,2,3,4
            ) as a
            inner join tr_customer b on (a.i_customer = b.i_customer)
            inner join tr_area c on (a.i_area = c.i_area)
            union all
            select i_sr, i_sr_id, d_sr, b.e_store_name, c.e_area_name, 'SR' as jenis,
        '$dfrom' AS dfrom,
        '$dto' AS dto from (
                select a.i_sr , a.i_sr_id, to_char(d_sr, 'dd-mm-yyyy') as d_sr,  a.i_area , a.i_store from tm_sr a
                inner join tm_sr_item b on (a.i_sr = b.i_sr)
                where a.f_po = true and a.f_sr_cancel = false 
                and b.i_po is null and b.n_op > 0 and a.d_sr between '$dfrom' and '$dto' AND a.i_company = '$this->i_company'
                group by 1,2,3,4
            ) as a
            inner join tr_store b on (a.i_store = b.i_store)
            inner join tr_area c on (a.i_area = c.i_area)
            order by 1
        ", FALSE);

        // $datatables->edit('f_status', function ($data) {
        //     if ($data['f_status'] == 't') {
        //         $status = $this->lang->line('Batal');
        //         $color  = 'danger';
        //     } else {
        //         $color  = 'success';
        //         $status = $data['e_status_so_name'];
        //     }
        //     $data = "<span class='btn btn-outline-" . $color . " btn-sm round'>" . $status . "</span>";
        //     return $data;
        // });

        /** Cek Hak Akses, Apakah User Bisa Edit */
        $datatables->add('action', function ($data) {
            $id         = trim($data['i_so']);
            $jenis         = $data['jenis'];
            $dfrom      = $data['dfrom'];
            $dto        = $data['dto'];
            $data       = '';

            if (check_role($this->id_menu, 1)) {
                $data      .= "<a href='" . base_url() . $this->folder . '/detail_pembelian/' . encrypt_url($id) . '/' . $jenis . '/' . encrypt_url($dfrom) . '/' . encrypt_url($dto) . "'><i class='fa fa-check-circle-o success darken-4 fa-lg mr-1'></i></a>";
            }
            return $data;
        });
        $datatables->hide('jenis');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        return $datatables->generate();
    }

    /** Get Data Untuk add header */
    public function get_data($id, $jenis)
    {

        if ($jenis == 'SO') {
            return $this->db->query(" 
                select 'SO' as jenis, a.i_area, b.i_area_id , b.e_area_name, 
                a.i_so, a.i_so_id, to_char(d_so, 'dd-mm-yyyy') as d_so,
                null as i_sr, null as i_sr_id, null as d_sr
                from tm_so a
                inner join tr_area b on (a.i_area = b.i_area)
                where a.i_so = $id
            ", FALSE);
        } else {
            return $this->db->query(" 
                select 'SR' as jenis, a.i_area, b.i_area_id , b.e_area_name, 
                null as i_so, null as i_so_id, null as d_so,
                i_sr, i_sr_id, to_char(d_sr, 'dd-mm-yyyy') as d_sr
                from tm_sr a
                inner join tr_area b on (a.i_area = b.i_area)
                where a.i_sr = $id
            ", FALSE);
        }
    }



    /** Get Detail Untuk SO Item */
    public function get_so_item($i_supplier, $id)
    {
        if ($i_supplier == "") {
            return $this->db->query("SELECT a.i_product, a2.i_product_id, a.e_product_name, a.i_product_grade , a.i_product_motif, b.e_product_motifname , a.n_op, 0 as v_price from tm_so_item a
                inner join tr_product a2 on (a.i_product = a2.i_product)
                inner join tr_product_motif b on (a.i_product_motif = b.i_product_motif)
                where a.i_so = '$id' and a.n_op > 0
                order by a2.e_product_name
            ", FALSE);
        } else {
            return $this->db->query("SELECT a.i_product, a2.i_product_id, a.e_product_name, a.i_product_grade , a.i_product_motif, b.e_product_motifname , a.n_op, c.v_price from tm_so_item a
                inner join tr_product a2 on (a.i_product = a2.i_product)
                inner join tr_product_motif b on (a.i_product_motif = b.i_product_motif)
                inner join tr_supplier_price c on (a.i_product = c.i_product)
                where a.i_so = '$id' and c.i_supplier = '$i_supplier' and a.n_op > 0
                order by a2.e_product_name
            ", FALSE);
        }
    }

    /** Get Detail Untuk SO Item */
    public function get_sr_item($i_supplier, $id)
    {
        if ($i_supplier == "") {
            return $this->db->query("
                select a.i_product, a2.i_product_id, a2.e_product_name, a.i_product_grade , a.i_product_motif, b.e_product_motifname , a.n_op, 0 as v_price from tm_sr_item a
                inner join tr_product a2 on (a.i_product = a2.i_product)
                inner join tr_product_motif b on (a.i_product_motif = b.i_product_motif)
                where a.i_sr = '$id' 
                order by a2.e_product_name
            ", FALSE);
        } else {
            return $this->db->query("
                select a.i_product, a2.i_product_id, a2.e_product_name, a.i_product_grade , a.i_product_motif, b.e_product_motifname , a.n_op, c.v_price from tm_sr_item a
                inner join tr_product a2 on (a.i_product = a2.i_product)
                inner join tr_product_motif b on (a.i_product_motif = b.i_product_motif)
                inner join tr_supplier_price c on (a.i_product = c.i_product)
                where a.i_sr = '$id' and c.i_supplier = '$i_supplier'
                order by a2.e_product_name
            ", FALSE);
        }
    }

    /** Running Number Dokumen*/
    public function running_number($thbl, $tahun)
    {
        $cek = $this->db->query("
            SELECT 
                substring(i_po_id, 1, 2) AS kode 
            FROM tm_po 
            WHERE i_company = '$this->i_company'
            ORDER BY i_po DESC");
        if ($cek->num_rows() > 0) {
            $kode = $cek->row()->kode;
        } else {
            $kode = 'PO';
        }
        $query  = $this->db->query("
            SELECT
                max(substring(i_po_id, 9, 6)) AS max
            FROM
                tm_po
            WHERE to_char (d_po, 'yyyy') >= '$tahun'
            AND i_company = '$this->i_company'
            AND substring(i_po_id, 1, 2) = '$kode'
            AND substring(i_po_id, 4, 2) = substring('$thbl',1,2)
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

    public function get_supplier($cari)
    {
        return $this->db->query("
            SELECT
                i_supplier ,
                i_supplier_id ,
                e_supplier_name 
            FROM
                tr_supplier 
            WHERE
                (e_supplier_name ILIKE '%$cari%' OR i_supplier_id ILIKE '%$cari%')
                AND f_supplier_active = 't'
                AND i_company = '$this->i_company'
            ORDER BY 3 ASC 
        ", FALSE);
    }

    /** Cek Apakah Data Sudah Ada */
    public function cek_code()
    {
        $i_document = str_replace("_", "", $this->input->post('i_document'));
        return $this->db->query("
            SELECT 
                i_po_id
            FROM 
                tm_po 
            WHERE 
                upper(trim(i_po_id)) = upper(trim('$i_document'))
                AND i_company = '$this->i_company'
        ", FALSE);
    }

    public function save()
    {
        $query = $this->db->query("SELECT max(i_po)+1 AS id FROM tm_po", TRUE);
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

        $d_po = date('Y-m-d', strtotime($this->input->post('d_document_submit')));
        $i_document = $this->running_number(date('ym', strtotime($d_po)), date('Y', strtotime($d_po)));
        $jenis =  $this->input->post('jenis');
        $i_so =  $this->input->post('i_so');
        if ($i_so == "") $i_so = null;

        $i_sr =  $this->input->post('i_sr');
        if ($i_sr == "") $i_sr = null;

        $d_estimation = $this->input->post('d_estimation_submit');
        if ($d_estimation == "") $d_estimation = null;

        $d_so = $this->input->post('d_so');
        if ($d_so == "") {
            $d_so = null;
        } else {
            $d_so = date('Y-m-d', strtotime($d_so));
        }

        $d_sr = $this->input->post('d_sr');
        if ($d_sr == "") {
            $d_sr = null;
        } else {
            $d_sr = date('Y-m-d', strtotime($d_sr));
        }

        $header = array(
            'i_company'             => $this->session->i_company,
            'i_po'                  => $id,
            'i_po_id'               => $i_document,
            'd_po'                  => $d_po,
            'd_estimation'          => $d_estimation,
            'i_supplier'            => $this->input->post('i_supplier'),
            'i_area'                => $this->input->post('i_area'),
            'i_status_po'           => 1,
            'i_so'                  => $i_so,
            'd_so'                  => $d_so,
            'i_sr'                  => $i_sr,
            'd_sr'                  => $d_sr,
            'e_po_remark'           => $this->input->post('e_po_remark'),
            'd_entry'               => current_datetime(),
        );
        $this->db->insert('tm_po', $header);

        if ($this->input->post('jml') > 0) {
            $i = 0;
            $x = 0;
            foreach ($this->input->post('i_product') as $i_product) {
                $n_order = str_replace(",", "", $this->input->post('n_order')[$i]);

                $i_product_grade = $this->input->post('i_product_grade')[$i];
                $i_product_motif = $this->input->post('i_product_motif')[$i];
                if ($n_order > 0) {
                    $item = array(
                        'i_po'             => $id,
                        'i_product'        => $i_product,
                        'i_product_grade'  => $i_product_grade,
                        'i_product_motif'  => $i_product_motif,
                        'e_product_name'   => $this->input->post('e_product_name')[$i],
                        'n_order'          => $n_order,
                        'n_delivery'       => 0,
                        'n_item_no'        => $x,
                        'v_product_mill'   => str_replace(",", "", $this->input->post('v_price')[$i]),
                        'n_po_discount'    => str_replace(",", "", $this->input->post('n_disc')[$i]),
                        'v_po_discount'    => str_replace(",", "", $this->input->post('v_disc')[$i]),
                    );
                    $this->db->insert('tm_po_item', $item);
                    $x++;
                    if ($this->input->post('jenis') !== "ee") {
                        if ($jenis == 'SO') {
                            $this->db->query(" 
                            UPDATE tm_so_item set i_po = $id where i_so = $i_so and i_product = $i_product and i_product_grade = '$i_product_grade' and i_product_motif = '$i_product_motif'
                        ", FALSE);
                        } else {
                            $this->db->query(" 
                            UPDATE tm_sr_item set i_po = $id where i_sr = $i_sr and i_product = $i_product and i_product_grade = '$i_product_grade' and i_product_motif = '$i_product_motif'
                        ", FALSE);
                        }
                    }
                }
                $i++;
            }
        } else {
            die;
        }
    }


    public function get_data_edit($id)
    {
        return $this->db->query("SELECT
        case
            when a.i_so is not null then 'SO'
            else 'SR'
        end as jenis,
        a.i_po,
        i_po_id,
        to_char(a.d_po, 'dd-mm-yyyy') as d_po,
        a.d_po as d_po2,
        to_char(d_estimation, 'dd-mm-yyyy') as d_estimation,
        a.i_area,
        b.i_area_id,
        b.e_area_name,
        c.i_supplier,
        c.i_supplier_id,
        c.e_supplier_name,
        a.e_po_remark,
        a.i_so,
        d.i_so_id,
        to_char(a.d_so, 'dd-mm-yyyy') as d_so,
        a.i_sr,
        e.i_sr_id,
        to_char(a.d_sr, 'dd-mm-yyyy') as d_sr,
        case
                        when c.f_supplier_pkp = 't' then 'PKP'
            else 'NonPKP'
        end as f_supplier_pkp,
        coalesce(c.n_supplier_top, 0) as n_supplier_top
    from
        tm_po a
    inner join tr_area b on
        (a.i_area = b.i_area)
    inner join tr_supplier c on
        (a.i_supplier = c.i_supplier)
    left join tm_so d on
        (a.i_so = d.i_so)
    left join tm_sr e on
        (a.i_sr = e.i_sr)
            where a.i_po = $id
        ", FALSE);
    }


    /** Get Detail Untuk Edit */
    public function get_data_detail_edit($id)
    {
        return $this->db->query("SELECT 
                a.*,
	            b.i_product_id idp,
	            b.e_product_name namap,
	            v_product_mill * n_order as tot
            from
	            tm_po_item a
            inner join tr_product b on
                a.i_product = b.i_product
            WHERE a.i_po = '$id'
                    order by b.e_product_name
        ", FALSE);
    }



    /** Get Detail EDIT Untuk PO Item */
    public function get_po_item($id)
    {
        return $this->db->query("
            select a.i_product, a2.i_product_id, a.e_product_name, a.i_product_grade , a.i_product_motif, b.e_product_motifname , a.n_order as n_op, a.v_product_mill as v_price,
            a.n_po_discount, a.v_po_discount from tm_po_item a
            inner join tr_product a2 on (a.i_product = a2.i_product)
            inner join tr_product_motif b on (a.i_product_motif = b.i_product_motif)
            where a.i_po = '$id' 
            order by a2.e_product_name
        ", FALSE);
    }



    /** Cek Apakah Data Sudah Ada Pas Edit */
    public function cek_edit()
    {
        $i_document = str_replace("_", "", $this->input->post('i_document'));
        $i_document_old = str_replace("_", "", $this->input->post('i_document_old'));
        return $this->db->query("
             SELECT 
                 i_po_id
             FROM 
                 tm_po
             WHERE 
                 trim(upper(i_po_id)) <> trim(upper('$i_document_old'))
                 AND trim(upper(i_po_id)) = trim(upper('$i_document'))
                 AND i_company = '$this->i_company'
         ", FALSE);
    }



    /** Update Data */
    public function update()
    {
        $id =  $this->input->post('id');
        $jenis =  $this->input->post('jenis');
        $i_so =  $this->input->post('i_so');
        if ($i_so == "") $i_so = null;

        $i_sr =  $this->input->post('i_sr');
        if ($i_sr == "") $i_sr = null;

        $d_estimation = $this->input->post('d_estimation_submit');
        if ($d_estimation == "") $d_estimation = null;

        $d_so = $this->input->post('d_so');
        if ($d_so == "") {
            $d_so = null;
        } else {
            $d_so = date('Y-m-d', strtotime($d_so));
        }

        $d_sr = $this->input->post('d_sr');
        if ($d_sr == "") {
            $d_sr = null;
        } else {
            $d_sr = date('Y-m-d', strtotime($d_sr));
        }

        $header = array(
            'i_company'             => $this->session->i_company,
            'i_po_id'               => str_replace("_", "", strtoupper($this->input->post('i_document'))),
            'd_po'                  => date('Y-m-d', strtotime($this->input->post('d_document_submit'))),
            'd_estimation'          => $d_estimation,
            'i_supplier'            => $this->input->post('i_supplier'),
            'i_area'                => $this->input->post('i_area'),
            'i_status_po'           => 1,
            'i_so'                  => $i_so,
            'd_so'                  => $d_so,
            'i_sr'                  => $i_sr,
            'd_sr'                  => $d_sr,
            'e_po_remark'           => $this->input->post('e_po_remark'),
            'd_update'               => current_datetime(),
        );
        $this->db->where('i_po', $id);
        $this->db->update('tm_po', $header);

        if ($this->input->post('jml') > 0) {
            $this->db->where('i_po', $id);
            $this->db->delete('tm_po_item');
            $i = 0;
            $x = 0;
            foreach ($this->input->post('i_product') as $i_product) {
                $n_order = str_replace(",", "", $this->input->post('n_order')[$i]);
                $i_product_grade = $this->input->post('i_product_grade')[$i];
                $i_product_motif = $this->input->post('i_product_motif')[$i];
                if ($n_order > 0) {
                    $item = array(
                        'i_po'             => $id,
                        'i_product'        => $i_product,
                        'i_product_grade'  => $i_product_grade,
                        'i_product_motif'  => $i_product_motif,
                        'e_product_name'   => $this->input->post('e_product_name')[$i],
                        'n_order'          => $n_order,
                        'n_delivery'       => 0,
                        'n_item_no'        => $x,
                        'v_product_mill'   => str_replace(",", "", $this->input->post('v_price')[$i]),
                        'n_po_discount'    => str_replace(",", "", $this->input->post('n_disc')[$i]),
                        'v_po_discount'    => str_replace(",", "", $this->input->post('v_disc')[$i]),
                    );
                    $this->db->insert('tm_po_item', $item);
                    $x++;
                    if ($this->input->post('jenis') !== "ee") {
                        if ($jenis == 'SO') {
                            $this->db->query(" 
                            UPDATE tm_so_item set i_po = $id where i_so = $i_so and i_product = $i_product and i_product_grade = '$i_product_grade' and i_product_motif = '$i_product_motif'
                        ", FALSE);
                        } else {
                            $this->db->query(" 
                            UPDATE tm_sr_item set i_po = $id where i_sr = $i_sr and i_product = $i_product and i_product_grade = '$i_product_grade' and i_product_motif = '$i_product_motif'
                        ", FALSE);
                        }
                    }
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
            'f_po_cancel' => 't',
        );
        $this->db->where('i_po', $id);
        $this->db->update('tm_po', $table);

        $this->db->query(" 
            UPDATE tm_so_item set i_po = null where i_po = $id;
            UPDATE tm_sr_item set i_po = null where i_po = $id;
        ", FALSE);
    }
}

/* End of file Mmaster.php */
