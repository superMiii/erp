<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mpbr extends CI_Model
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
                d_gs as tgl,
                i_gs AS id,
                d_gs,
                i_gs_id,
                to_char(d_gs, 'YYYYMM') as i_periode,
                to_char(d_gs_receive , 'DD FMMonth YYYY') AS d_gs_receive,
                c.e_area_name,
                f_gs_cancel AS f_status,
                '$dfrom' AS dfrom,
                '$dto' AS dto,
                '$i_area' AS i_area
            FROM
                tm_gs2 a
            INNER JOIN tr_area c ON
                (c.i_area = a.i_area)
            INNER JOIN tm_user_area d ON (d.i_area = a.i_area)
            WHERE
                a.d_gs BETWEEN '$dfrom' AND '$dto'
                AND a.i_company = '$this->i_company'
                AND d.i_user = '$this->i_user'
	            and d_acc notnull 
                $area
            order by d_gs desc, d_gs_receive nulls first
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
            $d_gs_receive   = $data['d_gs_receive'];
            $i_periode  = $data['i_periode'];
            $dfrom      = $data['dfrom'];
            $dto        = $data['dto'];
            $i_area     = $data['i_area'];
            $data       = '';
            $data      .= "<a href='" . base_url() . $this->folder . '/view/' . encrypt_url($id) . '/' . encrypt_url($dfrom) . '/' . encrypt_url($dto) . '/' . encrypt_url($i_area) . "' title='View Data'><i class='fa fa-eye fa-lg warning darken-4 mr-1'></i></a>";
            if ($i_periode >= get_periode3()) {
                if (check_role($this->id_menu, 3) && $f_status == 'f' && $d_gs_receive == '') {
                    $data      .= "<a href='" . base_url() . $this->folder . '/edit/' . encrypt_url($id)  . '/' .  encrypt_url($dfrom) . '/' . encrypt_url($dto) . '/' . encrypt_url($i_area) . "' title='Edit Data'><i class='fa fa-check-circle-o success darken-4 fa-lg mr-1'></i></a>";
                }
            }
            return $data;
        });

        // $datatables->hide('n_print');
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
                max(substring(i_gs_id, 6, 6)) AS max
            FROM
                tm_gs2
            WHERE to_char (d_gs , 'yyyy') >= '$tahun'
            AND i_company = '$this->i_company'
            AND substring(i_gs_id, 1, 2) = substring('$thbl',1,2)
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


    /** Get Data Untuk Edit */
    public function get_data_edit($id)
    {
        return $this->db->query("SELECT d.i_gs, d.i_gs_id, d.d_gs , d.i_area, c.e_area_name , d.e_remark,
            d.i_store, d.i_store_loc, b.e_store_name , a.e_store_loc_name , d.v_gs,  d.d_gs_receive, d.v_gs_receive
            from tr_store_loc a 
            inner join tr_store b on (a.i_store = b.i_store)
            inner join tr_area c on (a.i_store = c.i_store)
            inner join tm_gs2 d on (d.i_area = c.i_area)
            where d.i_gs = '$id' limit 1; 
        ", FALSE);
    }

    /** Get Detail Untuk Edit */
    public function get_data_detail_edit($id)
    {
        return $this->db->query("SELECT 
                a.*,
                b.i_product_id,
                b.e_product_name,
                g.e_product_gradename as e_product_gradename,
                coalesce (e.n_quantity_stock, 0) as stok
            FROM 
                tm_gs_item2 a
            INNER JOIN tr_product b ON (b.i_product = a.i_product)
            INNER JOIN tr_product_motif c ON (c.i_product_motif = a.i_product_motif)
            inner join tm_gs2 d on (a.i_gs = d.i_gs)
            inner join tr_product_grade g on (g.i_product_grade=a.i_product_grade)
            left join tm_ic e on (a.i_product = e.i_product and a.i_product_grade = e.i_product_grade and a.i_product_motif = e.i_product_motif and d.i_store = e.i_store and d.i_store_loc = e.i_store_location)
            WHERE a.i_gs = '$id' order by a.n_item_no
        ", FALSE);
    }

    /** Cek Apakah Data Sudah Ada Pas Edit */
    public function cek_edit()
    {
        $i_document = str_replace("_", "", $this->input->post('i_document'));
        $i_document_old = str_replace("_", "", $this->input->post('i_document_old'));
        return $this->db->query("SELECT 
                i_gs_id
             FROM 
                tm_gs2
             WHERE 
                trim(upper(i_gs_id)) <> trim(upper('$i_document_old'))
                AND trim(upper(i_gs_id)) = trim(upper('$i_document'))
                AND i_company = '$this->i_company'
         ", FALSE);
    }

    /** Update Data */
    public function update()
    {
        $id = $this->input->post('id');

        $header = array(
            'd_gs_receive'          => date('Y-m-d', strtotime($this->input->post('d_document'))),
            'v_gs_receive'          => str_replace(",", "", $this->input->post('v_total')),
            'i_store'               => $this->input->post('i_store'),
            'i_store_loc'           => $this->input->post('i_store_loc'),
        );
        $this->db->where('i_gs', $id);
        $this->db->update('tm_gs2', $header);

        if ($this->input->post('jml') > 0) {
            $i = 0;
            $x = 0;
            foreach ($this->input->post('i_product') as $i_product) {
                $n_receive = str_replace(",", "", $this->input->post('n_receive')[$i]);
                if ($n_receive > 0) {
                    $item = array(
                        'n_quantity_receive' => $n_receive,
                    );
                    $this->db->where('i_gs', $id);
                    $this->db->where('i_product', $i_product);
                    $this->db->where('i_product_grade', $this->input->post('i_product_grade')[$i]);
                    $this->db->where('i_product_motif', $this->input->post('i_product_motif')[$i]);
                    $this->db->update('tm_gs_item2', $item);
                    $x++;
                }

                $i++;
            }
        } else {
            die;
        }
    }
}

/* End of file Mmaster.php */
