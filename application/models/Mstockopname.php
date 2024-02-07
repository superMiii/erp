<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mstockopname extends CI_Model
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

        $i_store = $this->input->post('i_store', TRUE);
        if ($i_store == '') {
            $i_store = $this->uri->segment(5);
        }

        if ($i_store != '0') {
            $store = "AND a.i_store = '$i_store' ";
        } else {
            $store = "";
        }

        $dfrom  = date('Y-m-d', strtotime($dfrom));
        $dto    = date('Y-m-d', strtotime($dto));

        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("SELECT
                d_stockopname as tgl,
                i_stockopname AS id,
                d_stockopname,
                i_stockopname_id,
                to_char(d_stockopname, 'YYYYMM') as i_periode,
                b.e_area_name,
                c.e_store_name,
                d.e_store_loc_name,
                a.f_stockopname_cancel AS f_status,
                '$dfrom' AS dfrom,
                '$dto' AS dto
            FROM
                tm_stockopname a
            INNER JOIN tr_area b ON
                (b.i_area = a.i_area)
            INNER JOIN tr_store c ON
                (c.i_store = a.i_store)
            INNER JOIN tr_store_loc d ON
                (d.i_store_loc = a.i_store_loc)
            INNER JOIN tm_user_area e ON
                (e.i_area = b.i_area)
            WHERE
                a.i_company = $this->i_company
                AND e.i_user = $this->i_user
                AND d_stockopname BETWEEN '$dfrom' AND '$dto'
                $store
            ORDER BY 1 DESC 
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
            $i_periode  = $data['i_periode'];
            $data       = '';
            $data      .= "<a href='" . base_url() . $this->folder . '/view/' . encrypt_url($id) . '/' . encrypt_url($dfrom) . '/' . encrypt_url($dto) . "' title='View Data'><i class='fa fa-eye fa-lg warning darken-4 mr-1'></i></a>";
            if ($i_periode >= get_periode()) {
                if (check_role($this->id_menu, 3) && $f_status == 'f') {
                    $data      .= "<a href='" . base_url() . $this->folder . '/edit/' . encrypt_url($id)  . '/' .  encrypt_url($dfrom) . '/' . encrypt_url($dto) . "' title='Edit Data'><i class='fa fa-pencil-square success darken-4 fa-lg mr-1'></i></a>";
                }
                if (check_role($this->id_menu, 4) && $f_status == 'f') {
                    $data      .= "<a href='#' onclick='sweetdeletev2link(\"" . $this->folder . "\",\"" . $id . "\",\"" . encrypt_url($dfrom)  . "\",\"" . encrypt_url($dto)  . "\");' title='Delete Data'><i class='fa fa-trash fa-lg red darken-4'></i></a>";
                }
            }
            return $data;
        });
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('i_periode');
        $datatables->hide('id');
        return $datatables->generate();
    }

    /** Get Area */
    public function get_store0($cari)
    {
        return $this->db->query("SELECT 
        DISTINCT
        a.i_store, 
        i_store_id, 
        initcap(e_store_name) AS e_store_name
    FROM 
        tr_store a
    INNER JOIN tr_area b 
        ON (b.i_store = a.i_store) 
    INNER JOIN tm_user_area c 
        ON (b.i_area = c.i_area) 
    WHERE 
        (e_store_name ILIKE '%$cari%' OR i_store_id ILIKE '%$cari%')
        AND a.i_company = '$this->i_company' 
        AND f_store_active = true
        AND c.i_user = '$this->i_user' 
    ORDER BY 3 ASC
        ", FALSE);
    }


    /** Running Number Dokumen*/
    public function running_number($thbl, $tahun)
    {
        $cek = $this->db->query("SELECT 
                substring(i_stockopname_id, 1, 2) AS kode 
            FROM tm_stockopname 
            WHERE i_company = '$this->i_company'
            ORDER BY i_stockopname DESC");
        if ($cek->num_rows() > 0) {
            $kode = $cek->row()->kode;
        } else {
            $kode = 'SO';
        }
        $query  = $this->db->query("SELECT
                max(substring(i_stockopname_id, 9, 6)) AS max
            FROM
                tm_stockopname
            WHERE to_char (d_stockopname, 'yyyy') >= '$tahun'
            AND i_company = '$this->i_company'
            AND substring(i_stockopname_id, 1, 2) = '$kode'
            AND substring(i_stockopname_id, 4, 2) = substring('$thbl',1,2)
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
                i_stockopname_id
            FROM 
                tm_stockopname 
            WHERE 
                upper(trim(i_stockopname_id)) = upper(trim('$i_document'))
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

    /** Get Data Gudang */
    public function get_store($cari, $i_area)
    {
        return $this->db->query("SELECT DISTINCT
            b.i_store_loc AS id,
            b.i_store_loc_id AS code,
            b.e_store_loc_name AS name,
            e_store_name AS e_name
        FROM
            tr_store a
        INNER JOIN tr_store_loc b ON
            (b.i_store = a.i_store)
        INNER JOIN tr_area c ON
            (c.i_store = a.i_store)
        INNER JOIN tm_user_area d ON
            (d.i_area = c.i_area
                AND d.i_user = '$this->i_user')
        WHERE
            (e_store_name ILIKE '%$cari%'
                OR i_store_id ILIKE '%$cari%'
                OR i_store_loc_id ILIKE '%$cari%'
                OR e_store_loc_name ILIKE '%$cari%')
            AND f_store_active = 't'
            AND b.f_store_loc_active = 't'
            AND a.i_company = '$this->i_company'
            AND c.i_area = '$i_area'
        ORDER BY
            e_store_name;
    ", FALSE);
    }

    /** Simpan Data */
    public function save()
    {
        $query = $this->db->query("SELECT max(i_stockopname)+1 AS id FROM tm_stockopname", TRUE);
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
            'i_company'             => $this->i_company,
            'i_stockopname'         => $id,
            'i_stockopname_id'      => $this->input->post('i_document'),
            'i_area'                => $this->input->post('i_area'),
            'i_store'               => $this->input->post('i_store_loc'),
            'i_store_loc'           => $this->input->post('i_store_loc'),
            'd_stockopname'         => $this->input->post('d_document'),
            'd_stockopname_entry'   => current_datetime(),
        );
        $this->db->insert('tm_stockopname', $header);

        $i = 0;
        foreach ($this->input->post('i_product') as $i_product) {
            $item = array(
                'i_stockopname'     => $id,
                'i_product'         => $i_product,
                'i_product_grade'   => $this->input->post('i_product_grade')[$i],
                'i_product_motif'   => $this->input->post('i_product_motif')[$i],
                'n_stockopname'     => $this->input->post('n_stockopname')[$i],
                'e_product_name'    => $this->input->post('e_product_name')[$i],
                'n_item_no'         => $i,
            );
            $this->db->insert('tm_stockopname_item', $item);
            $i++;
        }
    }

    public function get_product()
    {
        return $this->db->query("SELECT distinct 
                c.i_product,
                c.i_product_id,
                c.e_product_name as e_product_name,
                d.i_product_grade,
                d.e_product_gradename,
                c.i_product_motif,
                e.e_product_motifname,                
                case when c.f_product_active = 't' then 'AKTIF' else 'TIDAK AKTIF' end as akt
            from
                tr_product c
            inner join tm_ic a on
                (c.i_product = a.i_product)
            inner join tr_product_grade d on
                (d.i_product_grade = a.i_product_grade)
            inner join tr_product_motif e on
                (e.i_product_motif = c.i_product_motif)
            WHERE
                c.i_company = '$this->i_company'
            order by e_product_name
            ", FALSE);
    }

    /** Get Data Untuk Edit */
    public function getdata($id)
    {
        return $this->db->query("SELECT
                a.*,
                c.e_store_name,
                b.i_store_loc_id,
                b.e_store_loc_name,
                d.i_area_id,
                d.e_area_name
            from
                tm_stockopname a
            inner join tr_store_loc b on
                (b.i_store_loc = a.i_store_loc)
            inner join tr_store c on
                (c.i_store = a.i_store)
            inner join tr_area d on
                (d.i_area = a.i_area)
            WHERE
                a.i_stockopname = '$id'
        ", FALSE);
    }

    /** Get Data Untuk Edit */
    public function getdetail($id)
    {
        return $this->db->query("SELECT
                a.*,
                b.e_product_motifname,
                c.e_product_gradename,
                d.i_product_id,
                case when d.f_product_active = 't' then 'AKTIF' else 'TIDAK AKTIF' end as akt
            FROM
                tm_stockopname_item a
            INNER JOIN tr_product_motif b ON
                (b.i_product_motif = a.i_product_motif)
            INNER JOIN tr_product_grade c ON
                (c.i_product_grade = a.i_product_grade)
            INNER JOIN tr_product d ON
                (d.i_product = a.i_product)
            WHERE
                a.i_stockopname = '$id'
            order by d.e_product_name
        ", FALSE);
    }


    /** Cek Apakah Data Sudah Ada Pas Edit */
    public function cek_edit()
    {
        $i_document = $this->input->post('i_document', TRUE);
        $i_document_old = $this->input->post('i_document_old', TRUE);
        return $this->db->query("SELECT 
                i_stockopname_id
            FROM 
                tm_stockopname 
            WHERE 
                trim(upper(i_stockopname_id)) <> trim(upper('$i_document_old'))
                AND trim(upper(i_stockopname_id)) = trim(upper('$i_document'))
                AND i_company = '$this->i_company'
        ", FALSE);
    }


    /** Update Data */
    public function update()
    {
        $id = $this->input->post('id');
        $header = array(
            'i_company'             => $this->i_company,
            'i_stockopname_id'      => $this->input->post('i_document'),
            'i_area'                => $this->input->post('i_area'),
            'i_store'               => $this->input->post('i_store_loc'),
            'i_store_loc'           => $this->input->post('i_store_loc'),
            'd_stockopname'         => $this->input->post('d_document'),
            'd_stockopname_update'  => current_datetime(),
        );
        $this->db->where('i_stockopname', $id);
        $this->db->update('tm_stockopname', $header);


        if (is_array($this->input->post('i_product')) || is_object($this->input->post('i_product'))) {
            $this->db->where('i_stockopname', $id);
            $this->db->delete('tm_stockopname_item');
            $i = 0;
            foreach ($this->input->post('i_product') as $i_product) {
                $item = array(
                    'i_stockopname'     => $id,
                    'i_product'         => $i_product,
                    'i_product_grade'   => $this->input->post('i_product_grade')[$i],
                    'i_product_motif'   => $this->input->post('i_product_motif')[$i],
                    'n_stockopname'     => $this->input->post('n_stockopname')[$i],
                    'e_product_name'    => $this->input->post('e_product_name')[$i],
                    'n_item_no'         => $i,
                );
                $this->db->insert('tm_stockopname_item', $item);
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
            'f_stockopname_cancel' => 't',
        );
        $this->db->where('i_stockopname', $id);
        $this->db->update('tm_stockopname', $table);
    }


    /* last end */
}

/* End of file Mmaster.php */
