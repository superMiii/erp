<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mpo2 extends CI_Model
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
                i_po as id,
                d_po ,
                i_po_id,
                b.e_supplier_name ,
                c.e_status_po_name,
                a.i_status_po,
                a.i_supplier,  
                f_po_cancel,  
                case
                    when a.f_po_cancel = true then false
                    when a.i_status_po = '2' then false
                    else true
                end as editable,            
                '$dfrom' AS dfrom,
                '$dto' AS dto
            from
                tm_po a
                inner join tr_supplier b on (b.i_supplier=a.i_supplier)
                inner join tr_status_po c on (c.i_status_po=a.i_status_po)
            WHERE
                a.d_po between '$dfrom' and '$dto'
                and a.i_company = '$this->i_company'
                $supplier
        ", FALSE);

        $datatables->edit('e_status_po_name', function ($data) {
            if ($data['f_po_cancel'] == 't') {
                $status = $this->lang->line('Batal');
                $color  = 'danger';
            } elseif  ($data['i_status_po'] == '1'){
                $color  = 'warning';
                $status = $data['e_status_po_name'];
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
            $dfrom      = $data['dfrom'];
            $dto        = $data['dto'];
            $i_supplier = $data['i_supplier'];
            $f_po_cancel= $data['f_po_cancel'];
            $data       = '';
            $data      .= "<a href='" . base_url() . $this->folder . '/view/' . encrypt_url($id) . '/' . encrypt_url($dfrom) . '/' . encrypt_url($dto) . '/' . encrypt_url($i_supplier) . "' title='View Data'><i class='fa fa-eye fa-lg warning darken-4 mr-1'></i></a>";
            if (check_role($this->i_menu, 3) && $editable == 't') {
                $data      .= "<a href='" . base_url() . $this->folder . '/edit/' . encrypt_url($id)  . '/' .  encrypt_url($dfrom) . '/' . encrypt_url($dto) . '/' . encrypt_url($i_supplier) . "' title='Edit Data'><i class='fa fa-pencil-square success darken-4 fa-lg mr-1'></i></a>";
            }
            if (check_role($this->i_menu, 5)) {
                $data      .= "<a href='#' onclick='openLink(\"" . $this->folder . "\",\"" . encrypt_url($id) . "\"); return false;' title='Print Data'><i class='fa fa-print fa-lg blue darken-4 mr-1'></i></a>";
            }
            if (check_role($this->i_menu, 4) && $editable == 't') {
                $data      .= "<a href='#' onclick='sweetdeletev333(\"" . $this->folder . "\",\"" . $id . "\",\"" . encrypt_url($dfrom)  . "\",\"" . encrypt_url($dto)  . "\",\"" . encrypt_url($i_supplier)  . "\");' title='Delete Data'><i class='fa fa-trash fa-lg red darken-4'></i></a>";
            }
            return $data;
        });
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('i_supplier');
        $datatables->hide('i_status_po');
        $datatables->hide('f_po_cancel');
        $datatables->hide('editable');
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


    /** Running Number Dokumen*/
    public function running_number($thbl, $tahun)
    {
        $cek = $this->db->query("SELECT 
                substring(i_po_id, 1, 2) AS kode 
            FROM tm_po 
            WHERE i_company = '$this->i_company'
            ORDER BY i_po DESC");
        if ($cek->num_rows() > 0) {
            $kode = $cek->row()->kode;
        } else {
            $kode = 'PO';
        }
        $query  = $this->db->query("SELECT
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

    /** Cek Apakah Data Sudah Ada */
    public function cek_code()
    {
        $i_document = $this->input->post('i_document', TRUE);
        return $this->db->query("SELECT 
                i_sr_id
            FROM 
                tm_sr 
            WHERE 
                upper(trim(i_sr_id)) = upper(trim('$i_document'))
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
                initcap(e_product_name) AS e_product_name,
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

        $d_po = date('Y-m-d', strtotime($this->input->post('d_document')));
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
            'i_area'                => 66,
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
                        'v_product_mill'   => str_replace(",", "", $this->input->post('v_unit_price')[$i]),
                    );
                    $x++;
                    $this->db->insert('tm_po_item', $item);
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
                i_po,
                d_po ,
                i_po_id,
                b.e_supplier_name ,
                a.i_supplier, 
                e_po_remark,                
                case
                                when b.f_supplier_pkp = 't' then 'PKP'
                    else 'NonPKP'
                end as f_supplier_pkp,
                coalesce(b.n_supplier_top, 0) as n_supplier_top
            from
                tm_po a
                inner join tr_supplier b on (b.i_supplier=a.i_supplier)
            WHERE
                a.i_po = $id
        ", FALSE);
    }


    /** Get Detail Untuk Edit */
    public function getdetail($id)
    {
        return $this->db->query("SELECT distinct
                i_po,
                i_po_item,
                zz.i_product,
                a.i_product_id as i_product_id,
                a.e_product_name as e_product_name,
                e.e_product_motifname ,
                zz.v_product_mill,
                zz.n_order ,
                b.i_product_grade,
                zz.i_product_motif,
	            zz.n_item_no ,
	            zz.v_product_mill * zz.n_order as tot,
	            a.i_product_id idp,
	            a.e_product_name namap,
                zz.n_po_discount,
                zz.v_po_discount
            from
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
            inner join tm_po_item zz on (zz.i_product=a.i_product)
            WHERE zz.i_po = '$id'
            order by zz.n_item_no 
        ", FALSE);
    }

    
    public function update()
    {
        $id = $this->input->post('id');

        $header = array(
            'i_company'             => $this->session->i_company,
            'i_po_id'               => str_replace("_", "", strtoupper($this->input->post('i_document'))),
            'd_po'                  => date('Y-m-d', strtotime($this->input->post('d_document'))),
            'i_supplier'            => $this->input->post('i_supplier'),
            'i_status_po'           => 1,
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
                        'v_product_mill'   => str_replace(",", "", $this->input->post('v_product_mill')[$i]),
                    );
                    $x++;
                    $this->db->insert('tm_po_item', $item);
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
    }


}

/* End of file Mmaster.php */
