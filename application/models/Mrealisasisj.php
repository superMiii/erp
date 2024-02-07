<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mrealisasisj extends CI_Model
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
                a.d_do as tgl,
                a.i_do AS id,
                a.d_do,
                i_do_id,
                to_char(a.d_do, 'YYYYMM') as i_periode,
                ' [ ' || b.i_customer_id || ' ] ' || b.e_customer_name AS customer,
                d.e_area_name,
                case when c.f_so_stockdaerah = 't' then 'CABANG' else 'PUSAT' end as e_pemenuhan,
                c.i_so_id,
                c.d_so,
                h.i_sl_id,
                h.d_sl,
                a.f_do_cancel AS f_status,
                a.n_do_print AS n_print,
                '$dfrom' AS dfrom,
                '$dto' AS dto,
                '$i_area' AS i_area
            FROM
                tm_do a
            INNER JOIN tr_customer b ON
                (b.i_customer = a.i_customer)
            INNER JOIN tm_so c ON
                (c.i_so = a.i_so)
            INNER JOIN tr_area d ON
                (d.i_area = a.i_area)
            INNER JOIN tm_user_area g ON
                (g.i_area = a.i_area)
            LEFT JOIN (SELECT DISTINCT i_do, i_sl_id, b.d_sl  FROM tm_sl a, tm_sl_item b WHERE a.i_sl = b.i_sl AND a.f_sl_batal = 'f') h ON (h.i_do = a.i_do and a.f_do_cancel = 'f') 
            WHERE
                a.i_company = '$this->i_company'
                AND g.i_user = '$this->i_user'
                AND a.d_do BETWEEN '$dfrom' AND '$dto'
            ORDER BY
                1 ASC
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
            $i_sl_id    = $data['i_sl_id'];
            $i_periode  = $data['i_periode'];
            $dfrom      = $data['dfrom'];
            $dto        = $data['dto'];
            $i_area     = $data['i_area'];
            $data       = '';
            $data      .= "<a href='" . base_url() . $this->folder . '/view/' . encrypt_url($id) . "' title='View Data'><i class='fa fa-eye fa-lg warning darken-4 mr-1'></i></a>";
            if ($i_periode >= get_periode()) {
                if (check_role($this->id_menu, 3) && $f_status == 'f' && ($i_sl_id == '' || $i_sl_id == null)) {
                    $data      .= "<a href='" . base_url() . $this->folder . '/edit/' . encrypt_url($id)  . '/' .  encrypt_url($dfrom) . '/' . encrypt_url($dto) . '/' . encrypt_url($i_area) . "' title='Edit Data'><i class='fa fa-pencil success darken-4 fa-lg mr-1'></i></a>";
                }
                if (check_role($this->id_menu, 5) && $f_status == 'f') {
                    $data      .= "<a href='" . base_url() . $this->folder . '/edit/' . encrypt_url($id)  . '/' .  encrypt_url($dfrom) . '/' . encrypt_url($dto) . '/' . encrypt_url($i_area) . "' title='Edit Data'><i class='fa fa-pencil success darken-4 fa-lg mr-1'></i></a>";
                }
                if (check_role($this->id_menu, 4) && $f_status == 'f' && ($i_sl_id == '' || $i_sl_id == null)) {
                    $data      .= "<a href='#' onclick='sweetdeletev2link(\"" . $this->folder . "\",\"" . $id . "\");' title='Delete Data'><i class='fa fa-trash fa-lg red darken-4'></i></a>";
                }
            }
            return $data;
        });
        $datatables->hide('i_periode');
        $datatables->hide('id');
        /* $datatables->hide('e_status_so_name'); */
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('i_area');
        return $datatables->generate();
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
                a.d_so as tgl,
                to_char(a.d_so, 'YYYYMM') as i_periode,
                a.i_so AS id,                
                a.d_so,
                a.i_so_id,
                initcap(c.e_customer_name) AS e_customer_name,
                initcap(d.e_area_name) AS e_area_name,
                case when a.f_so_stockdaerah = 't' then 'CABANG' else 'PUSAT' end as e_pemenuhan,
                sum(g.n_order) AS n_order,
                sum(g.n_order - g.n_deliver) AS n_kirim,
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
            INNER JOIN tm_so_item g ON 
                (g.i_so = a.i_so)
            WHERE
                f.i_user = '$this->i_user'
                AND a.i_company = '$this->i_company'
                AND a.d_so BETWEEN '$dfrom' AND '$dto'
                AND a.f_so_cancel = 'f'
                AND a.i_status_so = 5
                AND a.d_approve1 NOTNULL 
                AND a.d_approve2 NOTNULL
                $area
            GROUP BY
                1,2,3,4,5,6,7
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
            if ($i_periode >= get_periode()) {
                if (check_role($this->id_menu, 3)) {
                    $data      .= "<a href='" . base_url() . $this->folder . '/add/' . encrypt_url($id) . '/' . encrypt_url($dfrom) . '/' . encrypt_url($dto) . '/' . encrypt_url($i_area) . "' title='Tambah Data'><i class='fa fa-check-circle-o success darken-4 fa-lg mr-1'></i></a>";
                }
            }
            return $data;
        });
        $datatables->hide('id');
        $datatables->hide('i_periode');
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

    /** Running Number Dokumen*/
    public function running_number($thbl, $tahun)
    {
        /* $cek = $this->db->query("
            SELECT 
                substring(i_do_id, 1, 2) AS kode 
            FROM tm_do 
            WHERE i_company = '$this->i_company'
            ORDER BY i_do DESC");
        if ($cek->num_rows() > 0) {
            $kode = $cek->row()->kode;
        } else {
            $kode = 'DO';
        } */
        /* $query  = $this->db->query("
            SELECT
                max(substring(i_do_id, 9, 6)) AS max
            FROM
                tm_do
            WHERE to_char (d_do, 'yyyy') >= '$tahun'
            AND i_company = '$this->i_company'
            AND substring(i_do_id, 4, 2) = substring('$thbl',1,2)
        ", false); */
        $query  = $this->db->query("
            SELECT
                max(substring(i_do_id, 6, 6)) AS max
            FROM
                tm_do
            WHERE to_char (d_do, 'yyyy') >= '$tahun'
            AND i_company = '$this->i_company'
            AND substring(i_do_id, 1, 2) = substring('$thbl',1,2)
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
            $number = $thbl . "-" . $number;
            return $number;
        } else {
            $number = "000001";
            $nomer  = $thbl . "-" . $number;
            return $nomer;
        }
    }

    /** Cek Apakah Data Sudah Ada */
    public function cek_code()
    {
        $i_document = str_replace("_", "", $this->input->post('i_document'));
        return $this->db->query("
            SELECT 
                i_do_id
            FROM 
                tm_do 
            WHERE 
                upper(trim(i_do_id)) = upper(trim('$i_document'))
                AND i_company = '$this->i_company'
        ", FALSE);
    }

    /** Simpan Data */
    public function save()
    {
        $query = $this->db->query("SELECT max(i_do)+1 AS id FROM tm_do", TRUE);
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
            'i_do'          => $id,
            'i_do_id'       => $this->input->post('i_document'),
            'i_area'        => $this->input->post('i_area'),
            'i_so'          => $this->input->post('i_so'),
            'i_customer'    => $this->input->post('i_customer'),
            'i_salesman'    => $this->input->post('i_salesman'),
            'd_do'          => date('Y-m-d', strtotime($this->input->post('d_document'))),
            'd_do_entry'    => current_datetime(),
            'e_remark'      => $this->input->post('e_remark'),
            'n_do_print'    => 0,
        );
        $this->db->insert('tm_do', $header);

        if ($this->input->post('jml') > 0) {
            $i = 0;
            foreach ($this->input->post('i_product') as $i_product) {
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
                $this->db->query(
                    "UPDATE tm_so_item 
                    SET n_deliver = n_deliver + " . str_replace(",", "", $this->input->post('n_deliver')[$i]) . " 
                    WHERE i_so = '" . $this->input->post('i_so') . "'
                    AND i_product = '" . $i_product . "'"
                );
                $i++;
            }
        } else {
            die;
        }
        $table = array(
            'i_status_so' => '6',
        );
        $this->db->where('i_so', $this->input->post('i_so'));
        $this->db->update('tm_so', $table);
    }

    /** Simpan Data */
    public function save_turunan()
    {
        $query = $this->db->query("SELECT max(i_do)+1 AS id FROM tm_do", TRUE);
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
            'i_do'          => $id,
            'i_do_id'       => $this->input->post('i_document'),
            'i_area'        => $this->input->post('i_area'),
            'i_so'          => $this->input->post('i_so'),
            'i_customer'    => $this->input->post('i_customer'),
            'i_salesman'    => $this->input->post('i_salesman'),
            'd_do'          => date('Y-m-d', strtotime($this->input->post('d_document'))),
            'd_do_entry'    => current_datetime(),
            'e_remark'      => $this->input->post('e_remark'),
            'n_do_print'    => 0,
        );
        $this->db->insert('tm_do', $header);

        if ($this->input->post('jml') > 0) {
            $i = 0;

            $number = $this->so->running_number(date('ym', strtotime($this->input->post('d_document'))), date('Y', strtotime($this->input->post('d_document'))), $this->input->post('i_area'));
            $query = $this->db->query("SELECT max(i_so)+1 AS id_so FROM tm_so", TRUE);
            if ($query->num_rows() > 0) {
                $id_so = $query->row()->id_so;
                if ($id_so == null) {
                    $id_so = 1;
                } else {
                    $id_so = $id_so;
                }
            } else {
                $id_so = 1;
            }
            $d_so = date('Y-m-d', strtotime($this->input->post('d_document')));
            $i_so = $this->input->post('i_so');

            $this->db->query("
                insert into tm_so (i_company, i_so, i_so_id, i_area, i_customer,i_salesman, i_price_group, 
				i_store, i_store_location, i_status_so, i_product_group, d_so, d_so_entry, e_customer_pkpnpwp , 
				f_so_plusppn , f_so_stockdaerah , f_so_consigment , f_so_cancel, n_so_toplength , v_so_discounttotal , 
				v_so , i_so_refference, n_so_ppn  )
				select i_company, '$id_so' as i_so, '$number' as i_so_id, i_area, i_customer, i_salesman, i_price_group, null as i_store, null as i_store_location, '1' as i_status_so, i_product_group, '$d_so' as d_so, now() as d_so_entry, 
				e_customer_pkpnpwp , f_so_plusppn , f_so_stockdaerah , f_so_consigment , 'f' as f_so_cancel, n_so_toplength , '0' as v_so_discounttotal , '0' as v_so , $i_so as i_so_refference, n_so_ppn
				                from tm_so where i_so = $i_so
            ", TRUE);

            foreach ($this->input->post('i_product') as $i_product) {
                $n_order = str_replace(",", "", $this->input->post('n_order')[$i]);
                $n_deliver = str_replace(",", "", $this->input->post('n_deliver')[$i]);

                $n_turunan = abs($n_order - $n_deliver);
                $i_product_grade = $this->input->post('i_product_grade')[$i];
                $i_product_motif = $this->input->post('i_product_motif')[$i];
                $item = array(
                    'i_do'              => $id,
                    'i_product'         => $i_product,
                    'i_product_grade'   => $i_product_grade,
                    'i_product_motif'   => $i_product_motif,
                    'n_deliver'         => $n_deliver,
                    'e_product_name'    => $this->input->post('e_product_name')[$i],
                    'n_item_no'         => $i,
                );
                $this->db->insert('tm_do_item', $item);
                $this->db->query(
                    "UPDATE tm_so_item 
                    SET n_deliver = n_deliver + " . str_replace(",", "", $this->input->post('n_deliver')[$i]) . " 
                    WHERE i_so = '" . $this->input->post('i_so') . "'
                    AND i_product = '" . $i_product . "'"
                );

                if ($n_turunan > 0) {
                    $j = 0;
                    $this->db->query("
                        INSERT INTO tm_so_item (i_so,i_product,i_product_grade,i_product_motif,i_product_status,i_po,e_product_name,e_remark,n_order,n_deliver,n_item_no,
						 v_unit_price,n_so_discount1,n_so_discount2,n_so_discount3, v_so_discount1,v_so_discount2,v_so_discount3)
						
						select '$id_so' as i_so, i_product ,i_product_grade,i_product_motif,i_product_status,i_po,e_product_name,e_remark, '$n_turunan' as n_order, 0 as n_deliver, '$j' as n_item_no , 
						v_unit_price,n_so_discount1,n_so_discount2,n_so_discount3 , (((v_unit_price *  '$n_turunan')/100) * n_so_discount1) as v_so_discount1, ((v_unit_price *  '$n_turunan') - ((v_unit_price *  '$n_turunan')/100) * n_so_discount1)/100 * n_so_discount2 as v_so_discount2, 
						((v_unit_price *  '$n_turunan') - (((v_unit_price *  '$n_turunan')/100) * n_so_discount1) - (((v_unit_price *  '$n_turunan') - ((v_unit_price *  '$n_turunan')/100) * n_so_discount1)/100 * n_so_discount2))/100 * n_so_discount3 as v_so_discount3
						from tm_so_item
						where i_so = '$i_so' and i_product = '$i_product' and i_product_grade = '$i_product_grade' and i_product_motif = '$i_product_motif'
                    ");

                    $j++;
                }

                $i++;
            }


            $this->db->query("
                update tm_so set v_so =
				(select gross + ((gross / 100) * n_so_ppn) as total from (
					select a.i_so, b.n_so_ppn, sum((a.n_order * a.v_unit_price) - a.v_so_discount1 - a.v_so_discount2 - a.v_so_discount3) as gross 
					from tm_so_item a
					inner join tm_so b on (a.i_so = b.i_so)
					where a.i_so = '$id_so'
					group by 1,2
				) as x) where i_so = '$id_so'
            ", TRUE);
        } else {
            die;
        }
        $table = array(
            'i_status_so' => '6',
        );
        $this->db->where('i_so', $this->input->post('i_so'));
        $this->db->update('tm_so', $table);
    }

    /** Get Data Untuk Tambah */
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
                c.e_customer_address
            FROM 
                tm_so a
            INNER JOIN tr_area b ON 
                (b.i_area = a.i_area)
            INNER JOIN tr_customer c ON 
                (c.i_customer = a.i_customer)
            INNER JOIN tr_salesman e ON 
                (e.i_salesman = a.i_salesman)
            WHERE i_so = '$id'
        ", FALSE);
    }

    /** Get Detail Untuk Tambah */
    public function get_data_detail($id)
    {
        return $this->db->query("SELECT 
			    a.*,
			    b.i_product_id,
			    g.e_product_gradename as e_product_motifname,
			    coalesce (e.n_quantity_stock, 0) as stok
			FROM 
			    tm_so_item a
			INNER JOIN tr_product b ON (b.i_product = a.i_product)
			INNER JOIN tr_product_motif c ON (c.i_product_motif = a.i_product_motif)
			inner join tm_so d on (a.i_so = d.i_so)
			left join tm_ic e on (a.i_product = e.i_product and a.i_product_grade = e.i_product_grade and a.i_product_motif = e.i_product_motif and d.i_store = e.i_store and d.i_store_location = e.i_store_location)
            inner join tr_product_grade g on (g.i_product_grade=a.i_product_grade)
			WHERE a.i_so = '$id'
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
                c.e_customer_phone
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
                a.n_deliver AS n_do
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
            WHERE a.i_do = '$id'
        ", FALSE);
    }

    /** Cek Apakah Data Sudah Ada Pas Edit */
    public function cek_edit()
    {
        $i_document = str_replace("_", "", $this->input->post('i_document'));
        $i_document_old = str_replace("_", "", $this->input->post('i_document_old'));
        return $this->db->query("
             SELECT 
                i_do_id
             FROM 
                tm_do 
             WHERE 
                trim(upper(i_do_id)) <> trim(upper('$i_document_old'))
                AND trim(upper(i_do_id)) = trim(upper('$i_document'))
                AND i_company = '$this->i_company'
         ", FALSE);
    }

    /** Update Data */
    public function update()
    {
        $id = $this->input->post('id');
        $header = array(
            'i_company'     => $this->session->i_company,
            'i_do'          => $id,
            'i_do_id'       => $this->input->post('i_document'),
            'i_area'        => $this->input->post('i_area'),
            'i_so'          => $this->input->post('i_so'),
            'i_customer'    => $this->input->post('i_customer'),
            'i_salesman'    => $this->input->post('i_salesman'),
            'd_do'          => date('Y-m-d', strtotime($this->input->post('d_document'))),
            'd_do_update'   => current_datetime(),
            'e_remark'      => $this->input->post('e_remark'),
        );
        $this->db->where('i_do', $id);
        $this->db->update('tm_do', $header);

        if ($this->input->post('jml') > 0) {
            $this->db->where('i_do', $id);
            $this->db->delete('tm_do_item');
            $i = 0;
            foreach ($this->input->post('i_product') as $i_product) {
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
                /** Update SO Item */
                $n_deliver = str_replace(",", "", $this->input->post('n_deliver')[$i]);
                $n_deliver_old = str_replace(",", "", $this->input->post('n_deliver_old')[$i]);
                $this->db->query(
                    "UPDATE tm_so_item 
                    SET n_deliver = (n_deliver - $n_deliver_old) + $n_deliver
                    WHERE i_so = '" . $this->input->post('i_so') . "'
                    AND i_product = '" . $i_product . "'"
                );
                /* $this->db->query(
                    "UPDATE tm_so_item 
                    SET n_deliver = n_deliver + " . str_replace(",", "", $this->input->post('n_deliver')[$i]) . " 
                    WHERE i_so = '" . $this->input->post('i_so') . "'
                    AND i_product = '" . $i_product . "'"
                ); */
                $i++;
            }
        } else {
            die;
        }

        $table = array(
            'i_status_so' => '6',
        );
        $this->db->where('i_so', $this->input->post('i_so'));
        $this->db->update('tm_so', $table);
    }

    /** Cancel Data */
    public function cancel($id)
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

    /** Update Print */
    public function update_print($id)
    {
        $this->db->query("UPDATE tm_do SET n_do_print = n_do_print + 1 WHERE i_do = '$id' ", FALSE);
    }
}

/* End of file Mmaster.php */
