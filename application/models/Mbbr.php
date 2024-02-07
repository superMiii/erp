<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mbbr extends CI_Model
{
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
        $datatables->query("SELECT
                a.i_bbr as id,
                to_char(d_bbr, 'YYYYMM') as i_periode,
                d_bbr ,
                i_bbr_id,
                b.e_supplier_name ,
                f_dn,        
                e.i_dn_id,
                f_bbr_cancel AS f_status, 
                '$dfrom' AS dfrom,
                '$dto' AS dto,
                '$i_supplier' as i_supplier
            from
                tm_bbr a
                inner join tr_supplier b on (b.i_supplier=a.i_supplier)
                left join tm_dn e on (e.i_bbr = a.i_bbr and f_dn_cancel='f')
            WHERE
                a.d_bbr between '$dfrom' and '$dto'
                and a.i_company = '$this->i_company'
                $supplier
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
            $dfrom      = $data['dfrom'];
            $dto        = $data['dto'];
            $i_supplier = $data['i_supplier'];
            $f_status   = $data['f_status'];
            $f_dn       = $data['f_dn'];
            $i_periode  = $data['i_periode'];
            $data       = '';
            $data      .= "<a href='" . base_url() . $this->folder . '/view/' . encrypt_url($id) . '/' . encrypt_url($dfrom) . '/' . encrypt_url($dto) . '/' . encrypt_url($i_supplier) . "' title='View Data'><i class='fa fa-eye fa-lg warning darken-4 mr-1'></i></a>";
            if ($i_periode >= get_periode3()) {
                if (check_role($this->i_menu, 3) && $f_dn == 'f') {
                    $data      .= "<a href='" . base_url() . $this->folder . '/edit/' . encrypt_url($id)  . '/' .  encrypt_url($dfrom) . '/' . encrypt_url($dto) . '/' . encrypt_url($i_supplier) . "' title='Edit Data'><i class='fa fa-pencil-square success darken-4 fa-lg mr-1'></i></a>";
                }
                if (check_role($this->i_menu, 5)) {
                    $data      .= "<a href='#' onclick='openLink(\"" . $this->folder . "\",\"" . encrypt_url($id) . "\"); return false;' title='Print Data'><i class='fa fa-print fa-lg blue darken-4 mr-1'></i></a>";
                }
                if (check_role($this->i_menu, 4) && $f_dn == 'f') {
                    $data      .= "<a href='#' onclick='sweetdeletev333(\"" . $this->folder . "\",\"" . $id . "\",\"" . encrypt_url($dfrom)  . "\",\"" . encrypt_url($dto)  . "\",\"" . encrypt_url($i_supplier)  . "\");' title='Delete Data'><i class='fa fa-trash fa-lg red darken-4'></i></a>";
                }            
            }
            return $data;
        });
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('i_supplier');
        $datatables->hide('f_dn');
        $datatables->hide('i_periode');
        return $datatables->generate();
    }

    

    /** Get Supplier */
    public function get_supplier0($cari)
    {
        return $this->db->query("SELECT distinct
                xx.i_supplier, i_supplier_id , e_supplier_name AS e_supplier_name
            FROM 
                tr_supplier xx
            inner join tm_bbr yy on (xx.i_supplier =yy.i_supplier)
            WHERE 
                (e_supplier_name ILIKE '%$cari%' OR i_supplier_id ILIKE '%$cari%')
                AND xx.i_company = '$this->i_company' 
                AND f_supplier_active = 'true' 
            ORDER BY 3 ASC
        ", FALSE);
    }


    /** Running Number Dokumen*/
    public function running_number($thbl, $tahun)
    {
        $cek = $this->db->query("SELECT 
                substring(i_bbr_id, 1, 2) AS kode 
            FROM tm_bbr 
            WHERE i_company = '$this->i_company'
            ORDER BY i_bbr DESC");
        if ($cek->num_rows() > 0) {
            $kode = $cek->row()->kode;
        } else {
            $kode = 'BR';
        }
        $query  = $this->db->query("SELECT
                max(substring(i_bbr_id, 9, 6)) AS max
            FROM
                tm_bbr
            WHERE to_char (d_bbr, 'yyyy') >= '$tahun'
            AND i_company = '$this->i_company'
            AND substring(i_bbr_id, 1, 2) = '$kode'
            AND substring(i_bbr_id, 4, 2) = substring('$thbl',1,2)
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
        $i_document = $this->input->post('i_document', TRUE);
        return $this->db->query("SELECT 
                i_bbr_id
            FROM 
                tm_bbr
            WHERE 
                upper(trim(i_bbr_id)) = upper(trim('$i_document'))
                AND i_company = '$this->i_company'
        ", FALSE);
    }


    public function get_supplier($cari)
    {
        return $this->db->query("SELECT
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


    /** Get Data Product */
    public function get_product($cari, $supp)
    {
        return $this->db->query("SELECT
                a.i_product,
                a.i_product_id,
                initcap(e_product_name) AS e_product_name,
                s.v_price ,
                e.e_product_motifname 
            FROM
                tr_product a
            INNER JOIN tr_customer_price b ON
                (b.i_product = a.i_product)
            INNER JOIN tr_product_grade c ON
                (c.i_product_grade = b.i_product_grade)
            INNER JOIN tr_price_group d ON
                (d.i_price_group = b.i_price_group)
            INNER JOIN tr_product_motif e ON
                (e.i_product_motif = a.i_product_motif)
            inner join tr_supplier_price s on (s.i_product=a.i_product)
            inner join tr_supplier t on (t.i_supplier=s.i_supplier)
            WHERE
                (i_product_id ILIKE '%$cari%'
                    OR e_product_name ILIKE '%$cari%')
                AND a.i_company = '$this->i_company'
                AND c.f_default = 't'
                AND c.f_product_gradeactive = 't'
                AND d.f_default2 = 't'
                AND t.i_supplier = '$supp'
            ORDER BY
                e_product_name ASC
        ", FALSE);
    }

    /** Get Data Detail Product */
    public function get_product_detail($i_product, $supp)
    {
        return $this->db->query("SELECT
                a.i_product,
                b.i_product_grade,
                a.i_product_motif,
                a.e_product_name,
                s.v_price,
                initcap(a.e_product_name) AS e_product_name,
                e.e_product_motifname,
                ss.n_quantity_stock as st
            FROM
                tr_product a
            INNER JOIN tr_customer_price b ON
                (b.i_product = a.i_product)
            INNER JOIN tr_product_grade c ON
                (c.i_product_grade = b.i_product_grade)
            INNER JOIN tr_price_group d ON
                (d.i_price_group = b.i_price_group)
            INNER JOIN tr_product_motif e ON
                (e.i_product_motif = a.i_product_motif)
            inner join tr_supplier_price s on (s.i_product=a.i_product)
            inner join tm_ic ss on (ss.i_product=a.i_product and ss.i_company=a.i_company and e.i_product_motif=ss.i_product_motif and c.i_product_grade=ss.i_product_grade)
            inner join tr_store sx on (sx.i_store=ss.i_store and sx.f_store_pusat='t')
            WHERE
                a.i_product = '$i_product'
                AND c.f_default = 't'
                AND c.f_product_gradeactive = 't'
                AND d.f_default2 = 't'
                AND s.i_supplier = '$supp'
                AND a.i_company = '$this->i_company'
        ", FALSE);
    }

    /** Simpan Data */
    public function save()
    {
        $query = $this->db->query("SELECT max(i_bbr)+1 AS id FROM tm_bbr", TRUE);
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

        $d_bbr = date('Y-m-d', strtotime($this->input->post('d_document')));
        $i_document = $this->running_number(date('ym', strtotime($d_bbr)), date('Y', strtotime($d_bbr)));

        $header = array(
            'i_company'             => $this->session->i_company,
            'i_bbr'                 => $id,
            'i_bbr_id'              => $i_document,
            'i_supplier'            => $this->input->post('i_supplier'),
            'd_bbr'                 => $d_bbr,
            'v_bbr'                 => str_replace(",", "",$this->input->post('tfoot_total')),
            'e_remark'              => $this->input->post('e_remark'),
            'f_dn'                  => 'f',
            'd_entry'               => current_datetime(),
        );
        $this->db->insert('tm_bbr', $header);

        if ($this->input->post('jml') > 0) {
            $i = 0;
            $x = 0;
            foreach ($this->input->post('i_product') as $i_product) {
                $n_quantity = str_replace(",", "", $this->input->post('n_order')[$i]);

                $i_product_grade = $this->input->post('i_product_grade')[$i];
                $i_product_motif = $this->input->post('i_product_motif')[$i];
                if ($n_quantity > 0) {
                    $item = array(
                        'i_bbr'             => $id,
                        'i_product'         => $i_product,
                        'i_product_grade'   => $i_product_grade,
                        'i_product_motif'   => $i_product_motif,
                        'n_quantity'        => $n_quantity,
                        'v_unit_price'      => str_replace(",", "", $this->input->post('v_unit_price')[$i]),
                        'e_remark'          => $this->input->post('ket')[$i],
                        'n_item_no'         => $x,
                        'n_stock'           => $this->input->post('st')[$i],
                    );
                    $x++;
                    $this->db->insert('tm_bbr_item', $item);
                }
                $i++;
            }
        } else {
            die;
        }
    }


    
    public function getdata($id)
    {
        return $this->db->query("SELECT
                i_bbr,
                d_bbr ,
                i_bbr_id,
                b.e_supplier_name ,
                a.i_supplier, 
                e_remark as e_po_remark
            from
                tm_bbr a
                inner join tr_supplier b on (b.i_supplier=a.i_supplier)
            WHERE
                a.i_bbr = '$id'
        ", FALSE);
    }


    /** Get Detail Untuk Edit */
    public function getdetail($id)
    {
        return $this->db->query("SELECT
                a.*,
                a.e_remark as ket,
                a.n_stock as st,
                b.i_product_id,
                b.e_product_name,
                e.e_product_motifname
            from
                tm_bbr_item a
            inner join tr_product b on (b.i_product=a.i_product)
            INNER JOIN tr_product_motif e ON (e.i_product_motif = a.i_product_motif)
            WHERE a.i_bbr = '$id'
            order by a.n_item_no 
        ", FALSE);
    }

    
    public function update()
    {
        $id = $this->input->post('id');

        $d_bbr = date('Y-m-d', strtotime($this->input->post('d_document')));
        $i_document = $this->running_number(date('ym', strtotime($d_bbr)), date('Y', strtotime($d_bbr)));

        $header = array(
            'i_company'             => $this->session->i_company,
            'i_bbr'                 => $id,
            'i_bbr_id'              => $i_document,
            'i_supplier'            => $this->input->post('i_supplier'),
            'd_bbr'                 => $d_bbr,
            'v_bbr'                 => str_replace(",", "",$this->input->post('tfoot_total')),
            'e_remark'              => $this->input->post('e_po_remark'),
            'f_dn'                  => 'f',
            'd_update'               => current_datetime(),
        );
        $this->db->where('i_bbr', $id);
        $this->db->update('tm_bbr', $header);

        if ($this->input->post('jml') > 0) {
            $this->db->where('i_bbr', $id);
            $this->db->delete('tm_bbr_item');

            $i = 0;
            $x = 0;
            foreach ($this->input->post('i_product') as $i_product) {
                $n_quantity = str_replace(",", "", $this->input->post('n_order')[$i]);

                $i_product_grade = $this->input->post('i_product_grade')[$i];
                $i_product_motif = $this->input->post('i_product_motif')[$i];
                if ($n_quantity > 0) {
                    $item = array(
                        'i_bbr'             => $id,
                        'i_product'         => $i_product,
                        'i_product_grade'   => $i_product_grade,
                        'i_product_motif'   => $i_product_motif,
                        'n_quantity'        => $n_quantity,
                        'v_unit_price'      => str_replace(",", "", $this->input->post('v_unit_price')[$i]),
                        'e_remark'          => $this->input->post('ket')[$i],
                        'n_item_no'         => $x,
                        'n_stock'           => $this->input->post('st')[$i],
                    );
                    $x++;
                    $this->db->insert('tm_bbr_item', $item);
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
            'f_bbr_cancel' => 't',
        );
        $this->db->where('i_bbr', $id);
        $this->db->update('tm_bbr', $table);
    }


}

/* End of file Mmaster.php */
