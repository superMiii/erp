<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mgr extends CI_Model
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
        $datatables->query("SELECT DISTINCT
                a.i_gr as id,
                d_gr ,
                i_gr_id ,	
                to_char(d_gr, 'YYYYMM') as i_periode,
                c.i_po,
                c.i_po_id,
                c.d_po ,
                d.e_supplier_name,
                a.i_refference,
                a.f_gr_cancel,
                a.i_faktur,
                e.i_nota_id,
            '$dfrom' as dfrom,
            '$dto' as dto,
            '$i_supplier' AS i_supplier
            from
                tm_gr a
                inner join tm_gr_item b on (b.i_gr=a.i_gr)
                inner join tm_po c on (c.i_po=b.i_po)
                inner join tr_supplier d on (d.i_supplier=c.i_supplier)
                left join tm_nota_pembelian e on (a.i_faktur=e.i_nota and e.i_company=a.i_company)
            where 
                a.d_gr between '$dfrom' and '$dto'
                and a.i_company = '$this->i_company'
                $supplier
        ", FALSE);

        $datatables->edit('f_gr_cancel', function ($data) {
            if ($data['f_gr_cancel'] == 't') {
                $status = $this->lang->line('Batal');
                $color  = 'danger';
            } else {
                $color  = 'success';
                // $status = $data['e_status_po_name'];
                $status = $this->lang->line('Aktif');
            }
            $data = "<span class='btn btn-outline-" . $color . " btn-sm round'>" . $status . "</span>";
            return $data;
        });

        /** Cek Hak Akses, Apakah User Bisa Edit */
        $datatables->add('action', function ($data) {
            $id         = trim($data['id']);
            $i_po         = trim($data['i_po']);
            $i_faktur   = $data['i_faktur'];
            $f_gr_cancel = $data['f_gr_cancel'];
            $i_periode  = $data['i_periode'];
            $dfrom      = $data['dfrom'];
            $dto        = $data['dto'];
            $i_supplier = $data['i_supplier'];
            $data       = '';
            $data      .= "<a href='" . base_url() . $this->folder . '/view/' . encrypt_url($id) . '/' . encrypt_url($i_po) . '/' . encrypt_url($dfrom) . '/' . encrypt_url($dto) . '/' . encrypt_url($i_supplier) . "' title='View Data'><i class='fa fa-eye fa-lg warning darken-4 mr-1'></i></a>";
            if ($i_periode >= get_periode3()) {
                if (check_role($this->id_menu, 3) && $i_faktur == '' && $f_gr_cancel == 'f') {
                    $data      .= "<a href='" . base_url() . $this->folder . '/edit/' . encrypt_url($id)  . '/' . encrypt_url($i_po) . '/' .  encrypt_url($dfrom) . '/' . encrypt_url($dto) . '/' . encrypt_url($i_supplier) . "' title='Edit Data'><i class='fa fa-pencil-square success darken-4 fa-lg mr-1'></i></a>";
                }

                if (check_role($this->id_menu, 4) && $i_faktur == '' && $f_gr_cancel == 'f') {
                    $data      .= "<a href='#' onclick='sweetdeletev333(\"" . $this->folder . "\",\"" . $id . "\");' title='Delete Data'><i class='fa fa-trash fa-lg red darken-4'></i></a>";
                }
            }
            return $data;
        });
        $datatables->hide('i_po');
        $datatables->hide('i_faktur');
        $datatables->hide('i_periode');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('i_supplier');
        return $datatables->generate();
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

        $i_supplier = $this->input->post('i_supplier', TRUE);
        if ($i_supplier == '') {
            $i_supplier = $this->uri->segment(5);
        }

        if ($i_supplier != '0') {
            $supplier = "AND a.i_supplier = '$i_supplier' ";
        } else {
            $supplier = "";
        }

        $dfrom  = date('Y-m-d', strtotime($dfrom));
        $dto    = date('Y-m-d', strtotime($dto));

        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("SELECT a.i_po,d_po, a.i_po_id,  c.e_supplier_name, to_char(d_estimation, 'dd-mm-yyyy') as d_estimation,
        '$dfrom' as dfrom,
        '$dto' as dto,
        '$i_supplier' AS i_supplier
         from tm_po a
            inner join tm_po_item b on (a.i_po = b.i_po)
            inner join tr_supplier c on (a.i_supplier = c.i_supplier)
            where a.f_po_cancel = false 
            and (b.n_order - b.n_delivery > 0) 
            and a.d_po between '$dfrom' and '$dto' 
            AND a.i_company = '$this->i_company' 
            and a.f_po_close = false
            and a.f_terima = false
            $supplier
            group by 1,2,3,4
            order by d_po asc
        ", FALSE);

        /** Cek Hak Akses, Apakah User Bisa Edit */
        $datatables->add('action', function ($data) {
            $id         = trim($data['i_po']);
            $dfrom      = $data['dfrom'];
            $dto        = $data['dto'];
            $i_supplier = $data['i_supplier'];
            $data       = '';

            if (check_role($this->id_menu, 1)) {
                $data      .= "<a href='" . base_url() . $this->folder . '/add_reffrence/' . encrypt_url($id) . '/' . encrypt_url($dfrom) . '/' . encrypt_url($dto) . '/' . encrypt_url($i_supplier) .  "'><i class='fa fa-check-circle-o success darken-4 fa-lg mr-1'></i></a>";
            }
            return $data;
        });
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('i_supplier');
        return $datatables->generate();
    }

    /** Get Supplier */
    public function get_supplier0($cari)
    {
        return $this->db->query("SELECT distinct
                xx.i_supplier, i_supplier_id , e_supplier_name AS e_supplier_name
            FROM 
                tr_supplier xx
                inner join tm_gr yy on (yy.i_supplier=xx.i_supplier)
            WHERE 
                (e_supplier_name ILIKE '%$cari%' OR i_supplier_id ILIKE '%$cari%')
                AND xx.i_company = '$this->i_company' 
                AND f_supplier_active = 'true' 
            ORDER BY 3 ASC
        ", FALSE);
    }

    /** Get Data Untuk add header */
    public function get_data($id)
    {
        return $this->db->query("SELECT 
            a.i_po, a.i_po_id, d_po, d_estimation,
            a.i_area, b.i_area_id , b.e_area_name,
            c.i_supplier, c.i_supplier_id , c.e_supplier_name
            from tm_po a
            inner join tr_area b on (a.i_area = b.i_area)
            inner join tr_supplier c on (a.i_supplier = c.i_supplier)
            where i_po = '$id'
        ", FALSE);
    }


    /** Get Detail Untuk SO Item */
    public function get_data_refference($i_po)
    {
        return $this->db->query("SELECT b.i_product_id, b.e_product_name, c.e_product_motifname, a.v_product_mill, a.i_po_item, 
            a.i_product , a.i_product_motif , a.i_product_grade , a.n_order as n_op , 
            a.n_po_discount , a.v_po_discount from tm_po_item a
            inner join tr_product b on (a.i_product = b.i_product)
            inner join tr_product_motif c on (a.i_product_motif = c.i_product_motif)
            where a.i_po = $i_po
            order by b.e_product_name
        ", FALSE);
    }

    /** Running Number Dokumen*/
    public function running_number($thbl, $tahun)
    {
        $cek = $this->db->query("
            SELECT 
                substring(i_gr_id, 1, 2) AS kode 
            FROM tm_gr 
            WHERE i_company = '$this->i_company'
            ORDER BY i_gr DESC");
        if ($cek->num_rows() > 0) {
            $kode = $cek->row()->kode;
        } else {
            $kode = 'GR';
        }
        $query  = $this->db->query("
            SELECT
                max(substring(i_gr_id, 9, 6)) AS max
            FROM
                tm_gr
            WHERE to_char (d_gr, 'yyyy') >= '$tahun'
            AND i_company = '$this->i_company'
            AND substring(i_gr_id, 1, 2) = '$kode'
            AND substring(i_gr_id, 4, 2) = substring('$thbl',1,2)
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
        $d_document = date('Y-m-d', strtotime($this->input->post('d_document')));
        return $this->db->query("
            SELECT 
                i_gr_id
            FROM 
                tm_gr 
            WHERE 
                upper(trim(i_gr_id)) = upper(trim('$i_document'))
                and d_gr = '$d_document'
                AND i_company = '$this->i_company'
        ", FALSE);
    }

    /** Cek Apakah Data Sudah Ada Pas Edit */
    public function cek_edit()
    {
        $i_document = str_replace("_", "", $this->input->post('i_document'));
        $d_document = date('Y-m-d', strtotime($this->input->post('d_document')));
        $i_document_old = str_replace("_", "", $this->input->post('i_document_old'));
        return $this->db->query("
             SELECT 
                 i_gr_id
             FROM 
                 tm_gr
             WHERE 
                 trim(upper(i_gr_id)) <> trim(upper('$i_document_old'))
                 AND trim(upper(i_gr_id)) = trim(upper('$i_document'))
                 AND d_gr = '$d_document' 
                 AND i_company = '$this->i_company'
         ", FALSE);
    }

    public function save()
    {
        $query = $this->db->query("SELECT max(i_gr)+1 AS id FROM tm_gr", TRUE);
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


        $i_po = $this->input->post('i_po');
        $this->db->query("UPDATE tm_po set f_terima = 'true', i_status_po = '2' where i_po = $i_po", FALSE);

        $d_gr = $this->input->post('d_document');
        $i_document = $this->running_number(date('ym', strtotime($d_gr)), date('Y', strtotime($d_gr)));

        $v_gr_discount = str_replace(",", "", $this->input->post('tfoot_v_diskon'));
        if ($v_gr_discount == "") $v_gr_discount = 0;
        $header = array(
            'i_company'             => $this->session->i_company,
            'i_gr'                  => $id,
            'i_gr_id'               => $i_document,
            'd_gr'                  => $d_gr,
            'i_supplier'            => $this->input->post('i_supplier'),
            'i_area'                => $this->input->post('i_area'),
            'v_gr_gross'            => str_replace(",", "", $this->input->post('tfoot_subtotal')),
            'v_gr_discount'         => $v_gr_discount,
            'v_gr_netto'            => str_replace(",", "", $this->input->post('tfoot_total')),
            'i_refference'          => $this->input->post('i_refference')
        );
        $this->db->insert('tm_gr', $header);

        if ($this->input->post('jml') > 0) {
            $i = 0;
            $x = 0;
            //i_po_item   n_op n_delivery n_disc v_disc
            foreach ($this->input->post('i_po_item') as $i_po_item) {

                $i_product = $this->input->post('i_product')[$i];
                $i_product_grade = $this->input->post('i_product_grade')[$i];
                $i_product_motif = $this->input->post('i_product_motif')[$i];
                $bonus = $this->input->post('bonus')[$i];

                $n_delivery = str_replace(",", "", $this->input->post('n_delivery')[$i]);

                if ($n_delivery > 0) {
                    $item = array(
                        'i_gr'             => $id,
                        'i_product'        => $i_product,
                        'i_product_grade'  => $i_product_grade,
                        'i_product_motif'  => $i_product_motif,
                        'e_product_name'   => $this->input->post('e_product_name')[$i],
                        'n_deliver'        => $n_delivery + $bonus,
                        'v_product_mill'   => str_replace(",", "", $this->input->post('v_price')[$i]),
                        'i_po'             => $this->input->post('i_po'),
                        'e_remark'         => $this->input->post('e_remark')[$i],
                        'n_item_no'        => $x,
                        'n_gr_discount'    => str_replace(",", "", $this->input->post('n_disc')[$i]),
                        'v_gr_discount'    => str_replace(",", "", $this->input->post('v_disc')[$i]),
                        'n_dis_sup'        => $bonus,
                    );
                    $this->db->insert('tm_gr_item', $item);
                    $x++;

                    $this->db->query(" 
                        UPDATE tm_po_item set n_delivery = '$n_delivery' where i_po_item = $i_po_item
                    ", FALSE);
                }
                $i++;
            }
        } else {
            die;
        }
    }

    /** Get Data Untuk edit header */
    public function get_data_edit($id, $i_po)
    {
        return $this->db->query(" 
            SELECT 
            a.i_po, a.i_po_id, to_char(d_po, 'dd-mm-yyyy') as d_po, to_char(d_estimation, 'dd-mm-yyyy') as d_estimation,
            a.i_area, b.i_area_id , b.e_area_name,
            c.i_supplier, c.i_supplier_id , c.e_supplier_name,
            d.i_gr , d.i_gr_id, to_char(d.d_gr, 'dd-mm-yyyy') as d_gr , d.v_gr_discount, d.i_refference
            from tm_po a
            inner join tr_area b on (a.i_area = b.i_area)
            inner join tr_supplier c on (a.i_supplier = c.i_supplier)
            inner join tm_gr d on (d.i_gr = $id)
            where i_po = $i_po
        ", FALSE);
    }

    /** Get Detail EDIT Untuk GR Item */
    public function get_data_detail($id)
    {
        return $this->db->query("SELECT
        b.i_product_id,
        b.e_product_name,
        c.e_product_motifname,
        a.v_product_mill,
        d.i_po_item,
        a.i_product ,
        a.i_product_motif ,
        a.i_product_grade ,
        d.n_order as n_op ,
        a.n_deliver,	
        case
        when (a.n_deliver - d.n_order) < 0 then 0
        else a.n_deliver - d.n_order end as x_bonus,	
        a.n_gr_discount ,
        a.v_gr_discount,
        a.e_remark
    from
        tm_gr_item a
    inner join tr_product b on
        (a.i_product = b.i_product)
    inner join tr_product_motif c on
        (a.i_product_motif = c.i_product_motif)
    inner join tm_po_item d on
        (a.i_po = d.i_po
            and a.i_product = d.i_product
            and a.i_product_motif = d.i_product_motif
            and a.i_product_grade = d.i_product_grade)
    where a.i_gr = $id
    order by b.e_product_name
        ", FALSE);
    }

    /** Update Data */
    public function update()
    {
        $id =  $this->input->post('id');
        $v_gr_discount = str_replace(",", "", $this->input->post('tfoot_v_diskon'));
        if ($v_gr_discount == "") $v_gr_discount = 0;
        $header = array(
            'i_company'             => $this->session->i_company,
            'i_gr_id'               => strtoupper($this->input->post('i_document')),
            'd_gr'                  => $this->input->post('d_document'),
            'i_supplier'            => $this->input->post('i_supplier'),
            'i_area'                => $this->input->post('i_area'),
            'v_gr_gross'            => str_replace(",", "", $this->input->post('tfoot_subtotal')),
            'v_gr_discount'         => $v_gr_discount,
            'v_gr_netto'            => str_replace(",", "", $this->input->post('tfoot_total')),
            'i_refference'          => $this->input->post('i_refference')
        );
        $this->db->where('i_gr', $id);
        $this->db->update('tm_gr', $header);

        if ($this->input->post('jml') > 0) {
            $this->db->where('i_gr', $id);
            $this->db->delete('tm_gr_item');
            $i = 0;
            $x = 0;
            //i_po_item   n_op n_delivery n_disc v_disc
            foreach ($this->input->post('i_po_item') as $i_po_item) {

                $i_product = $this->input->post('i_product')[$i];
                $i_product_grade = $this->input->post('i_product_grade')[$i];
                $i_product_motif = $this->input->post('i_product_motif')[$i];
                $bonus = $this->input->post('bonus')[$i];

                $n_delivery = str_replace(",", "", $this->input->post('n_delivery')[$i]);
                $n_delivery_old = str_replace(",", "", $this->input->post('n_delivery_old')[$i]);
                $n_bonus = $n_delivery + $bonus;
                $har = str_replace(",", "", $this->input->post('v_price')[$i]);

                if ($n_bonus > 0) {
                    $item = array(
                        'i_gr'             => $id,
                        'i_product'        => $i_product,
                        'i_product_grade'  => $i_product_grade,
                        'i_product_motif'  => $i_product_motif,
                        'e_product_name'   => $this->input->post('e_product_name')[$i],
                        'n_deliver'        => $n_bonus,
                        'v_product_mill'   => str_replace(",", "", $this->input->post('v_price')[$i]),
                        'i_po'             => $this->input->post('i_po'),
                        'e_remark'         => $this->input->post('e_remark')[$i],
                        'n_item_no'        => $x,
                        'n_gr_discount'    => str_replace(",", "", $this->input->post('n_disc')[$i]),
                        'v_gr_discount'    => str_replace(",", "", $this->input->post('v_disc')[$i]),
                        'n_dis_sup'        => $bonus,
                    );
                    $this->db->insert('tm_gr_item', $item);
                    $x++;

                    $this->db->query(" 
                        UPDATE tm_po_item set n_delivery = ((n_delivery) - ($n_delivery_old)) + ($n_delivery), v_product_mill = $har where i_po_item = $i_po_item
                    ", FALSE);
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
            'f_gr_cancel' => 't',
        );
        $this->db->where('i_gr', $id);
        $this->db->update('tm_gr', $table);

        $this->db->query(" 
            UPDATE tm_po_item
            SET n_delivery = n_delivery - subquery.n_deliver
            FROM (
                select d.i_po_item, a.n_deliver from tm_gr_item a
                inner join tm_po_item d on (a.i_po = d.i_po and a.i_product = d.i_product and a.i_product_motif = d.i_product_motif and a.i_product_grade = d.i_product_grade)
                where a.i_gr = $id
            ) AS subquery
            WHERE tm_po_item.i_po_item =subquery.i_po_item;
        ", FALSE);
    }
}

/* End of file Mmaster.php */
