<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mnotapembelian extends CI_Model
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

        $dfrom  = date('Y-m-d', strtotime($dfrom));
        $dto    = date('Y-m-d', strtotime($dto));

        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("SELECT distinct
            d_nota as tgl,
            a.i_nota as id,
            d_nota, 
            a.i_nota_id,
            to_char(d_nota, 'YYYYMM') as i_periode,
            b.e_supplier_name,
            a.i_nota_supplier,
            a.v_nota_netto::money as v_jumlah ,
            a.v_sisa::money as v_sisa,
            f_nota_cancel as f_status,
            '$dfrom' as dfrom,
            '$dto' as dto,
            '$i_supplier' AS i_supplier
        from
            tm_nota_pembelian a
        inner join tr_supplier b on
            (a.i_supplier = b.i_supplier)
        inner join tm_nota_pembelian_item c on (c.i_nota=a.i_nota)
        where
            d_nota between '$dfrom' and '$dto'
            and a.i_company = '$this->i_company'
                $supplier
        order by
            1 desc
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
            $i_supplier = $data['i_supplier'];
            $v_jumlah   = $data['v_jumlah'];
            $v_sisa     = $data['v_sisa'];
            $i_periode  = $data['i_periode'];
            $data       = '';
            $data      .= "<a href='" . base_url() . $this->folder . '/view/' . encrypt_url($id) . '/' . encrypt_url($dfrom) . '/' . encrypt_url($dto) . '/' . encrypt_url($i_supplier) . "' title='View Data'><i class='fa fa-eye fa-lg warning darken-4 mr-1'></i></a>";
            if ($i_periode >= get_periode()) {
                if ($v_jumlah == $v_sisa) {
                    if (check_role($this->id_menu, 3) && $f_status == 'f' && $v_jumlah == $v_sisa) {
                        $data      .= "<a href='" . base_url() . $this->folder . '/edit/' . encrypt_url($id)  . '/' .  encrypt_url($dfrom) . '/' . encrypt_url($dto) . '/' . encrypt_url($i_supplier) . "' title='Edit Data'><i class='fa fa-pencil-square success darken-4 fa-lg mr-1'></i></a>";
                    }

                    if (check_role($this->id_menu, 5) && $f_status == 'f') {
                        $data      .= "<a href='#' onclick='openLink(\"" . $this->folder . "\",\"" . encrypt_url($id) . "\"); return false;' title='Print Data'><i class='fa fa-print fa-lg blue darken-4 mr-1'></i></a>";
                    }
                    if (check_role($this->id_menu, 4) && $f_status == 'f' && $v_jumlah == $v_sisa) {
                        // $data      .= "<a href='#' onclick='sweetdeletev33(\"" . $this->folder . "\",\"" . $id . "\",\"" . encrypt_url($dfrom)  . "\",\"" . encrypt_url($dto)  . '/' . encrypt_url($i_supplier) . "\");' title='Delete Data'><i class='fa fa-trash fa-lg red darken-4'></i></a>";
                        $data      .= "<a href='#' onclick='sweetdeletev333raya(\"" . $this->folder . "\",\"" . $id . "\");' title='Delete Data'><i class='fa fa-trash fa-lg red darken-4'></i></a>";
                    }
                }
            }
            return $data;
        });
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('i_supplier');
        $datatables->hide('i_periode');
        $datatables->hide('id');
        return $datatables->generate();
    }

    /** Get Supplier */
    public function get_supplier0($cari)
    {
        return $this->db->query("SELECT distinct
                xx.i_supplier, i_supplier_id , e_supplier_name AS e_supplier_name
            FROM 
                tr_supplier xx
                inner join tm_nota_pembelian yy on (yy.i_supplier=xx.i_supplier)
            WHERE 
                (e_supplier_name ILIKE '%$cari%' OR i_supplier_id ILIKE '%$cari%')
                AND xx.i_company = '$this->i_company' 
                AND f_supplier_active = 'true' 
            ORDER BY 3 ASC
        ", FALSE);
    }


    /** Get Area */
    public function get_supplier($cari)
    {
        return $this->db->query("
            SELECT b.i_supplier, b.i_supplier_id, b.e_supplier_name 
            from (select distinct i_supplier from tm_gr a where f_gr_cancel = 'f' and i_faktur is null) as a 
            inner join tr_supplier b on (a.i_supplier = b.i_supplier)
            where (i_supplier_id ilike '%$cari%' or e_supplier_name ilike '%$cari%')
            and b.i_company = '$this->i_company'
        ", FALSE);
    }

    public function get_detail_supplier($id)
    {
        return $this->db->query("
            SELECT f_supplier_pkp, n_supplier_top, coalesce((select 0 as n_ppn from tr_company tc where i_company = '$this->i_company'),0) as n_ppn  from tr_supplier ts where i_supplier = '$id'
        ", FALSE);
    }

    /** Running Number Dokumen*/
    public function running_number($thbl, $tahun)
    {
        $cek = $this->db->query("SELECT 
                 substring(i_nota_id, 1, 2) AS kode 
             FROM tm_nota_pembelian 
             WHERE i_company = '$this->i_company'
             ORDER BY i_nota DESC");
        if ($cek->num_rows() > 0) {
            $kode = $cek->row()->kode;
        } else {
            $kode = 'AP';
        }
        $query  = $this->db->query("SELECT
                 max(substring(i_nota_id, 9, 6)) AS max
             FROM
                 tm_nota_pembelian
             WHERE to_char (d_nota, 'yyyy') >= '$tahun'
             AND i_company = '$this->i_company'
             AND substring(i_nota_id, 1, 2) = '$kode'
             AND substring(i_nota_id, 4, 2) = substring('$thbl',1,2)
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

    /** Get Nota */
    public function get_gr($cari, $i_supplier)
    {
        return $this->db->query("SELECT distinct 
                a.i_gr,
                i_gr_id,
                i_po_id
            from
                tm_gr a
            inner join tm_gr_item b on (b.i_gr=a.i_gr)
            inner join tm_po c on (c.i_po=b.i_po)
            where
                (i_gr_id ILIKE '%$cari%' OR i_po_id ILIKE '%$cari%')
                AND a.i_company = '$this->i_company' 
                AND a.i_supplier = '$i_supplier'
                and a.i_faktur is null
                and a.f_gr_cancel = 'f'
            order by 1
        ", FALSE);
    }

    /** Get Nota Detail */
    public function get_detail_gr($i_gr)
    {
        return $this->db->query("SELECT
                to_char(d_gr, 'dd-mm-yyyy') as d_refference,
                v_gr_netto AS v_jumlah
            FROM
                tm_gr 
            WHERE
                i_gr = '$i_gr'
        ", FALSE);
    }

    public function get_rincian_gr($i_gr)
    {
        return $this->db->query("SELECT
                i_gr_item,
                a.i_gr,
                a.i_product,
                a.i_product_grade,
                a.i_product_motif,
                i_gr_id as r_id,
                e_product_name as r_name,
                v_product_mill as r_harga,
                n_deliver as r_qty,
	            n_gr_discount as r_dis,
	            a.v_gr_discount as r_dc,
                (v_product_mill*n_deliver)-a.v_gr_discount as r_tot 
            from
                tm_gr_item a
                inner join tm_gr b on (b.i_gr=a.i_gr)
            WHERE
                a.i_gr IN ($i_gr)
            order by 1 asc
        ", FALSE);
    }


    /** Simpan Data */
    public function save()
    {
        $query = $this->db->query("SELECT max(i_nota)+1 AS id FROM tm_nota_pembelian", TRUE);
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


        $table = array(
            'i_company'         => $this->i_company,
            'i_nota'            => $id,
            'i_nota_id'         => strtoupper($this->input->post('i_document')),
            'd_nota'            => date('Y-m-d', strtotime($this->input->post('d_document'))),
            'i_supplier'        => $this->input->post('i_supplier'),
            'i_nota_supplier'   => $this->input->post('i_nota_supplier'),
            'f_nota_pkp'        => $this->input->post('f_supplier_pkp'),
            'd_jatuh_tempo'     => date('Y-m-d', strtotime($this->input->post('d_jatuh_tempo'))),
            'v_nota_gross'      => str_replace(",", "", $this->input->post('tfoot_subtotal')),
            'n_nota_discount'   => str_replace(",", "", $this->input->post('tfoot_n_diskon')),
            'v_nota_discount'   => str_replace(",", "", $this->input->post('tfoot_v_diskon')),
            'v_nota_netto'      => str_replace(",", "", $this->input->post('tfoot_total')),
            'v_sisa'            => str_replace(",", "", $this->input->post('tfoot_total')),
            'e_remark'          => $this->input->post('e_remark'),
        );
        $this->db->insert('tm_nota_pembelian', $table);

        if ($this->input->post('jml') > 0) {
            if (is_array($this->input->post('i_nota')) || is_object($this->input->post('i_nota'))) {
                $i = 0;
                foreach ($this->input->post('i_nota') as $i_nota) {
                    $item = array(
                        'i_nota'           => $id,
                        'i_gr'             => $i_nota,
                        'v_jumlah'         => str_replace(",", "", $this->input->post('v_jumlah_item')[$i]),
                        'n_item_no'        => $i,
                    );
                    $this->db->insert('tm_nota_pembelian_item', $item);
                    $this->db->query(" UPDATE tm_gr set i_faktur = '$id' where i_gr = '$i_nota' ", FALSE);
                    $i++;
                }
            } else {
                die;
            }
        } else {
            die;
        }

        if ($this->input->post('jml2') > 0) {
            if (is_array($this->input->post('i_gr')) || is_object($this->input->post('i_gr'))) {
                $i = 0;
                foreach ($this->input->post('i_gr') as $i_gr) {
                    $item = array(
                        'i_nota'           => $id,
                        'i_gr'             => $i_gr,
                        'i_product'        => str_replace(",", "", $this->input->post('i_product')[$i]),
                        'i_product_grade'  => str_replace(",", "", $this->input->post('i_product_grade')[$i]),
                        'i_product_motif'  => str_replace(",", "", $this->input->post('i_product_motif')[$i]),
                        'e_product_name'   => str_replace(",", "", $this->input->post('r_name')[$i]),
                        'n_deliver'        => str_replace(",", "", $this->input->post('r_qty')[$i]),
                        'v_product_mill'   => str_replace(",", "", $this->input->post('r_harga')[$i]),
                        'n_gr_discount'    => str_replace(",", "", $this->input->post('r_dis')[$i]),
                        'v_gr_discount'    => str_replace(",", "", $this->input->post('r_dc')[$i]),
                        'n_item_no'        => $i,
                    );
                    $this->db->insert('tm_nota_pembelian_det', $item);
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
        return $this->db->query("SELECT a.i_nota, a.i_nota_id, 
            to_char(d_nota, 'dd-mm-yyyy') as d_nota, 
            d_nota as dnota, 
            b.i_supplier, b.i_supplier_id, b.e_supplier_name, 
            a.i_nota_supplier, f_nota_pkp ,
            to_char(d_jatuh_tempo , 'dd-mm-yyyy') as d_jatuh_tempo,
            a.d_jatuh_tempo - d_nota  as n_supplier_top,
            v_nota_gross, n_nota_discount, v_nota_discount , v_nota_gross- v_nota_discount as dpp, v_nota_ppn , v_nota_netto ,
            n_nota_discount, v_nota_discount , e_remark , case when f_nota_pkp = true then 'Ya' else 'Tidak' end as status_name
            from tm_nota_pembelian a
            inner join tr_supplier b on (a.i_supplier = b.i_supplier)
            WHERE i_nota = '$id'
        ", FALSE);
    }

    /** Get Data Untuk Edit Detail */
    public function get_data_detail($id)
    {
        return $this->db->query("SELECT distinct 
                a.i_gr,
                b.i_gr_id || ' / ' || d.i_po_id as i_gr_id,
                to_char(b.d_gr, 'dd-mm-yyyy') AS d_nota,
                a.v_jumlah,
                a.n_item_no
            FROM
                tm_nota_pembelian_item a
            INNER JOIN tm_gr b ON (b.i_gr = a.i_gr)
            inner join tm_gr_item c on (b.i_gr = c.i_gr)
            inner join tm_po d on (c.i_po = d.i_po)
            WHERE a.i_nota = '$id' 
            ORDER BY a.n_item_no ASC
        ", FALSE);
    }

    public function get_rincian_gr0($rayae)
    {
        return $this->db->query("SELECT 
                a.*,
                B.i_gr_id as r_id,
                a.e_product_name as r_name,
                a.v_product_mill as r_harga,
                a.n_deliver as r_qty,
	            a.n_gr_discount as r_dis,
	            a.v_gr_discount as r_dc,
                (a.v_product_mill*a.n_deliver)-a.v_gr_discount as r_tot 
            from
                tm_nota_pembelian_det a
                inner join tm_gr b on (b.i_gr=a.i_gr)
            WHERE
                i_nota = ($rayae)
            order by 1 DESC
        ", FALSE);
    }


    /** Update Data */
    public function update()
    {
        // $ym = date('ym', strtotime($this->input->post('d_document')));
        // $Y = date('Y', strtotime($this->input->post('d_document')));
        // $i_nota_id = $this->running_number($ym,$Y);
        $d_document = date('Y-m-d', strtotime($this->input->post('d_document_submit')));
        //$i_nota_id = $this->running_number(date('ym', strtotime($d_document)), date('Y', strtotime($d_document)));
        $id = $this->input->post('id');
        $table = array(
            'i_company'         => $this->i_company,
            'i_nota_id'         => $this->input->post('i_document'),
            'd_nota'            => date('Y-m-d', strtotime($this->input->post('d_document'))),
            'i_supplier'        => $this->input->post('i_supplier'),
            'i_nota_supplier'   => $this->input->post('i_nota_supplier'),
            'f_nota_pkp'        => $this->input->post('f_supplier_pkp'),
            'd_jatuh_tempo'     => date('Y-m-d', strtotime($this->input->post('d_jatuh_tempo'))),
            'v_nota_gross'      => str_replace(",", "", $this->input->post('tfoot_subtotal')),
            'n_nota_discount'   => str_replace(",", "", $this->input->post('tfoot_n_diskon')),
            'v_nota_discount'   => str_replace(",", "", $this->input->post('tfoot_v_diskon')),
            'v_nota_netto'      => str_replace(",", "", $this->input->post('tfoot_total')),
            'v_sisa'            => str_replace(",", "", $this->input->post('tfoot_total')),
            'e_remark'          => $this->input->post('e_remark'),
        );
        $this->db->where('i_nota', $id);
        $this->db->update('tm_nota_pembelian', $table);

        if ($this->input->post('jml') > 0) {
            if (is_array($this->input->post('i_nota')) || is_object($this->input->post('i_nota'))) {
                $i = 0;
                $this->db->query(" 
                    UPDATE tm_gr set i_faktur = null where i_gr IN (select i_gr from tm_nota_pembelian_item where i_nota = '$id' ) ;
                    DELETE from tm_nota_pembelian_item where i_nota = '$id';
                ", FALSE);
                foreach ($this->input->post('i_nota') as $i_nota) {
                    $item = array(
                        'i_nota'           => $id,
                        'i_gr'             => $i_nota,
                        'v_jumlah'         => str_replace(",", "", $this->input->post('v_jumlah_item')[$i]),
                        'n_item_no'        => $i,
                    );
                    $this->db->insert('tm_nota_pembelian_item', $item);
                    $this->db->query(" UPDATE tm_gr set i_faktur = '$id' where i_gr = '$i_nota' ", FALSE);
                    $i++;
                }
            } else {
                die;
            }
        } else {
            die;
        }

        if ($this->input->post('jml2') > 0) {
            if (is_array($this->input->post('i_gr')) || is_object($this->input->post('i_gr'))) {
                $i = 0;
                $this->db->query("DELETE from tm_nota_pembelian_det where i_nota = '$id';", FALSE);
                foreach ($this->input->post('i_gr') as $i_gr) {
                    $item = array(
                        'i_nota'           => $id,
                        'i_gr'             => $i_gr,
                        'i_product'        => str_replace(",", "", $this->input->post('i_product')[$i]),
                        'i_product_grade'  => str_replace(",", "", $this->input->post('i_product_grade')[$i]),
                        'i_product_motif'  => str_replace(",", "", $this->input->post('i_product_motif')[$i]),
                        'e_product_name'   => str_replace(",", "", $this->input->post('r_name')[$i]),
                        'n_deliver'        => str_replace(",", "", $this->input->post('r_qty')[$i]),
                        'v_product_mill'   => str_replace(",", "", $this->input->post('r_harga')[$i]),
                        'n_gr_discount'    => str_replace(",", "", $this->input->post('r_dis')[$i]),
                        'v_gr_discount'    => str_replace(",", "", $this->input->post('r_dc')[$i]),
                        'n_item_no'        => $i,
                    );
                    $this->db->insert('tm_nota_pembelian_det', $item);
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
    public function cancel($id,$alasan,$i_nota_id)
    {
        $this->db->query(" 
            UPDATE tm_nota_pembelian set f_nota_cancel = TRUE where i_nota = '$id';
            UPDATE tm_gr set i_faktur = null where i_gr IN (select i_gr from tm_nota_pembelian_item where i_nota = '$id' ) 
        ", FALSE);

        $this->db->query("INSERT INTO tx_del (i_company, i_user, e_user_name, e_dokumen, e_alasan)
        VALUES ('$this->i_company', '$this->i_user', '$this->e_user_name', 'No NOTA PEMBELIAN : $i_nota_id', '$alasan')");
    }







































    /** Cek Apakah Data Sudah Ada */
    public function cek_code()
    {
        $i_document = str_replace("_", "", $this->input->post('i_document'));
        return $this->db->query("SELECT 
                i_nota_id
            FROM 
                tm_nota_pembelian 
            WHERE 
                upper(trim(i_nota_id)) = upper(trim('$i_document'))
                AND i_company = '$this->i_company'
        ", FALSE);
    }

    /** Get Area */
    public function get_area($cari)
    {
        return $this->db->query("SELECT 
                DISTINCT
                a.i_area, 
                i_area_id, 
                e_area_name 
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
    public function get_dt($cari, $i_area)
    {
        return $this->db->query("SELECT 
                i_dt, i_dt_id , to_char(d_dt, 'DD Month YYYY') AS d_dt
            FROM 
                tm_dt
            WHERE 
                (i_dt_id ILIKE '%$cari%')
                AND i_company = '$this->i_company' 
                AND f_dt_cancel = 'false' 
                AND i_area = '$i_area'
            ORDER BY 1 ASC
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
                i_nota_id
            FROM 
                tm_nota_pembelian 
            WHERE 
                trim(upper(i_nota_id)) <> trim(upper('$i_document_old'))
                AND trim(upper(i_nota_id)) = trim(upper('$i_document'))
                AND i_area = '$i_area'
                AND i_company = '$this->i_company'
         ", FALSE);
    }

    /** Get Data Untuk Edit */
    public function get_data_edit($id)
    {
        return $this->db->query("SELECT
                DISTINCT a.*,
                a.i_supplier,
                c.e_supplier_name,
                a.v_nota_gross,
                a.v_nota_discount,
                a.v_nota_netto,
                case
		            when c.f_supplier_pkp = 't' then 'PKP'
		            else 'NonPKP'
	            end as f_supplier_pkp,
                COALESCE(c.n_supplier_top, 0) AS n_supplier_top
            FROM
                tm_nota_pembelian a
            INNER JOIN tm_nota_pembelian_item b ON
                (b.i_nota = a.i_nota)
            INNER JOIN tr_supplier c ON
                (a.i_supplier = c.i_supplier)
            WHERE
                a.i_nota = '$id'
        ", FALSE);
    }

    /** Get Detail Untuk Edit */
    public function get_data_detail_edit($id)
    {
        return $this->db->query("SELECT 
                a.*,
                b.i_gr_id,
                b.d_gr
            FROM 
                tm_nota_pembelian_item a
            INNER JOIN tm_gr b ON 
                (b.i_gr = a.i_gr)
            WHERE a.i_nota = '$id'
        ", FALSE);
    }
}

/* End of file Mmaster.php */
