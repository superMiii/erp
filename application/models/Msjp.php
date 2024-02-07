<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Msjp extends CI_Model
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
                b.e_remark,
                b.i_sr_id,
                to_char(b.d_sr , 'DD FMMonth YYYY') AS d_sr,
                f_gs_cancel AS f_status,
                a.n_print,
                '$dfrom' AS dfrom,
                '$dto' AS dto,
                '$i_area' AS i_area
            FROM
                tm_gs a
            INNER JOIN tm_sr b ON
                (b.i_sr = a.i_sr)
            INNER JOIN tr_area c ON
                (c.i_area = a.i_area)
            INNER JOIN tm_user_area d ON
                (d.i_area = a.i_area)
            WHERE
                a.d_gs BETWEEN '$dfrom' AND '$dto'
                AND a.i_company = '$this->i_company'
                AND d.i_user = '$this->i_user'
                $area
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
            $d_gs_receive   = $data['d_gs_receive'];
            $i_periode  = $data['i_periode'];
            $dfrom      = $data['dfrom'];
            $dto        = $data['dto'];
            $i_area     = $data['i_area'];
            $data       = '';
            $data      .= "<a href='" . base_url() . $this->folder . '/view/' . encrypt_url($id) . '/' . encrypt_url($dfrom) . '/' . encrypt_url($dto) . '/' . encrypt_url($i_area) . "' title='View Data'><i class='fa fa-eye fa-lg warning darken-4 mr-1'></i></a>";

            if ($i_periode >= get_periode3()) {
                if (check_role($this->id_menu, 3) && $f_status == 'f' && $d_gs_receive == '') {
                    $data      .= "<a href='" . base_url() . $this->folder . '/edit/' . encrypt_url($id)  . '/' .  encrypt_url($dfrom) . '/' . encrypt_url($dto) . '/' . encrypt_url($i_area) . "' title='Edit Data'><i class='fa fa-pencil-square success darken-4 fa-lg mr-1'></i></a>";
                }
                if (check_role($this->id_menu, 5) && $f_status == 'f') {
                    $data      .= "<a href='#' onclick='openLink(\"" . $this->folder . "\",\"" . encrypt_url($id) . "\"); return false;' title='Print Data'><i class='fa fa-print fa-lg blue darken-4 mr-1'></i></a>";
                }
                // if (check_role($this->id_menu, 4) && $f_status == 'f' && $d_gs_receive == '') {
                //     $data      .= "<a href='#' onclick='sweetdelete(\"" . $this->folder . "\",\"" . $id . "\");' title='Delete Data'><i class='fa fa-trash fa-lg red darken-4'></i></a>";
                // }
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
        $datatables->query("SELECT        
        d_sr as tgl,
        to_char(d_sr, 'YYYYMM') as i_periode,
        id,
        d_sr,
        i_sr_id ,
        e_area_name,
        n_order,
        n_sisa,
        '$dfrom' AS dfrom,
        '$dto' AS dto,
        '$i_area' AS i_area
    from
        (
        select
            a.i_sr as id,
            a.i_sr_id,
            a.d_sr,
            initcap(c.e_area_name) as e_area_name,
            sum(n_acc) as n_order,
            sum(n_acc - coalesce(n_deliver, 0)) as n_sisa
        from
            tm_sr a
        inner join tm_sr_item b on
            (a.i_sr = b.i_sr)
        inner join tr_area c on
            (a.i_area = c.i_area)
        inner join tm_user_area d on
            (d.i_area = a.i_area)
        where
            d.i_user = '$this->i_user'
            and a.i_company = '$this->i_company'
            and a.d_sr between '$dfrom' and '$dto'
            and a.f_sr_cancel = 'f'
            and c.f_area_pusat = 'f'
            and n_deliver is null
            and a.d_approve1 notnull
            and a.i_store is not null
            and a.f_sr_close = false
            $area
        group by
            1,
            2,
            3,
            4
        order by
            3 desc
                ) as x
    where
        n_sisa > 0
        ", FALSE);

        /** Cek Hak Akses, Apakah User Bisa Edit */
        $datatables->add('action', function ($data) {
            $id         = trim($data['id']);
            $i_periode  = $data['i_periode'];
            $dfrom      = $data['dfrom'];
            $dto        = $data['dto'];
            $i_area     = $data['i_area'];
            $data       = '';
            if ($i_periode >= get_periode3()) {
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
    public function get_area0($cari)
    {
        return $this->db->query("SELECT
                a.i_area ,
                a.e_store_loc_name AS e_area_name
            FROM
                tr_store_loc a 
            inner join tm_user_store b on (b.i_store=a.i_store AND b.i_user = '$this->i_user' )
            inner join tr_store c on (c.i_store=a.i_store)
            WHERE 
                (a.e_store_loc_name ILIKE '%$cari%')
                AND a.i_company = '$this->i_company' 
                AND f_store_loc_active = true  
                and c.f_store_pusat ='f'              
            ORDER BY 1 ASC
        ", FALSE);
    }
    
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
                substring(i_gs_id, 1, 2) AS kode 
            FROM tm_gs 
            WHERE i_company = '$this->i_company'
            ORDER BY i_do DESC");
        if ($cek->num_rows() > 0) {
            $kode = $cek->row()->kode;
        } else {
            $kode = 'DO';
        } */
        /* $query  = $this->db->query("
            SELECT
                max(substring(i_gs_id, 9, 6)) AS max
            FROM
                tm_gs
            WHERE to_char (d_do, 'yyyy') >= '$tahun'
            AND i_company = '$this->i_company'
            AND substring(i_gs_id, 4, 2) = substring('$thbl',1,2)
        ", false); */
        $query  = $this->db->query("
            SELECT
                max(substring(i_gs_id, 6, 6)) AS max
            FROM
                tm_gs 
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
                i_gs_id
            FROM 
                tm_gs 
            WHERE 
                upper(trim(i_gs_id)) = upper(trim('$i_document'))
                AND i_company = '$this->i_company'
        ", FALSE);
    }

    /** Simpan Data */
    public function save($tgl, $i_document)
    {
        $query = $this->db->query("SELECT max(i_gs)+1 AS id FROM tm_gs", TRUE);
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
            'i_gs'          => $id,
            'i_gs_id'       => $i_document,
            'd_gs'          => formatYMD($tgl),
            'i_sr'          => $this->input->post('i_sr'),
            'i_area'        => $this->input->post('i_area'),
            'd_sr'          => date('Y-m-d', strtotime($this->input->post('d_sr'))),
            'v_gs'          => str_replace(",", "", $this->input->post('v_total')),
            'd_gs_entry'    => current_datetime(),
            'i_store'       => $this->input->post('i_store'),
            'i_store_loc'   => $this->input->post('i_store_loc'),
        );
        $this->db->insert('tm_gs', $header);

        if ($this->input->post('jml') > 0) {
            $i = 0;
            $x = 0;
            foreach ($this->input->post('i_product') as $i_product) {
                // $del = ($this->input->post('n_deliver')[$i] == null) ? 0 :  $this->input->post('n_deliver')[$i];
                $del = ($this->input->post('n_deliver')[$i] == '') ? null :  $this->input->post('n_deliver')[$i];
                $deliver = str_replace(",", "", $del);

                // if ($deliver > 0) {
                $item = array(
                    'i_gs'              => $id,
                    'i_gs_item_id'      => $this->input->post('i_document'),
                    'i_product'         => $i_product,
                    'i_product_grade'   => $this->input->post('i_product_grade')[$i],
                    'i_product_motif'   => $this->input->post('i_product_motif')[$i],
                    'e_product_name'    => $this->input->post('e_product_name')[$i],
                    'v_unit_price'      => str_replace(",", "", $this->input->post('v_unit_price')[$i]),
                    'e_remark'          => $this->input->post('e_remark')[$i],
                    'n_quantity_order'  => str_replace(",", "", $this->input->post('n_order')[$i]),
                    // 'n_quantity_deliver' => $deliver,
                    'n_quantity_deliver' => $this->input->post('n_deliver')[$i],
                    'n_item_no'         => $x,
                );
                $this->db->insert('tm_gs_item', $item);
                $this->db->query(
                    "UPDATE tm_sr_item 
                        SET n_deliver = coalesce(n_deliver,0) + " . $this->input->post('n_deliver')[$i] . " 
                        WHERE i_sr = '" . $this->input->post('i_sr') . "'
                        AND i_product = '" . $i_product . "'  AND i_product_grade = '" . $this->input->post('i_product_grade')[$i] . "'  AND i_product_motif = '" . $this->input->post('i_product_motif')[$i] . "'"
                );
                $x++;
                // }

                $i++;
            }
        } else {
            die;
        }
        // $table = array(
        //     'i_status_so' => '6',
        // );
        // $this->db->where('i_so', $this->input->post('i_so'));
        // $this->db->update('tm_so', $table);
    }

    /** Simpan Data */

    /** Get Data Untuk Tambah */
    public function get_data($id)
    {
        return $this->db->query("SELECT d.i_sr, d.i_sr_id , d_sr, d.i_area, c.e_area_name , 
            a.i_store, a.i_store_loc, b.e_store_name , a.e_store_loc_name,e_remark
            from tr_store_loc a 
            inner join tr_store b on (a.i_store = b.i_store)
            inner join tr_area c on (a.i_store = c.i_store)
            inner join tm_sr d on (d.i_area = c.i_area)
            where d.i_sr = '$id' limit 1;
        ", FALSE);
    }

    /** Get Detail Untuk Tambah */
    public function get_data_detail($id)
    {
        return $this->db->query("SELECT 
                a.*,
                case
                    when s.i_product_statusid = 'STP1' then b.i_product_id || ' (*STP)'
                    when s.i_product_statusid = 'STP2' then b.i_product_id || ' (#STP)'
                    else b.i_product_id
                end as i_product_id,
                b.e_product_name,
                g.e_product_gradename as e_product_motifname,
                coalesce (e.n_quantity_stock, 0) as stok
            FROM 
                tm_sr_item a
            INNER JOIN tr_product b ON (b.i_product = a.i_product)
            INNER JOIN tr_product_motif c ON (c.i_product_motif = a.i_product_motif)
            inner join tr_product_status s on (s.i_product_status = b.i_product_status)
            inner join tm_sr d on (a.i_sr = d.i_sr)
            inner join tr_product_grade g on (g.i_product_grade=a.i_product_grade)
            left join tm_ic e on (a.i_product = e.i_product and a.i_product_grade = e.i_product_grade and a.i_product_motif = e.i_product_motif and d.i_store = e.i_store and d.i_store_location = e.i_store_location)
            WHERE a.i_sr = '$id'
            order by b.e_product_name
        ", FALSE);
    }

    /** Get Data Untuk Edit */
    public function get_data_edit($id)
    {
        return $this->db->query("SELECT d.i_gs, d.i_gs_id, d.d_gs , d.i_sr, e.i_sr_id , e.d_sr, d.i_area, c.e_area_name , 
            d.i_store, d.i_store_loc, b.e_store_name , a.e_store_loc_name , d.v_gs ,e_remark
            from tr_store_loc a 
            inner join tr_store b on (a.i_store = b.i_store)
            inner join tr_area c on (a.i_store = c.i_store)
            inner join tm_gs d on (d.i_area = c.i_area)
            inner join tm_sr e on (d.i_sr = e.i_sr)
            where d.i_gs = '$id' limit 1;
        ", FALSE);
    }

    /** Get Detail Untuk Edit */
    public function get_data_detail_edit($id)
    {
        return $this->db->query("SELECT 
                a.*,
                case
                    when s.i_product_statusid = 'STP1' then b.i_product_id || ' (*STP)'
                    when s.i_product_statusid = 'STP2' then b.i_product_id || ' (#STP)'
                    else b.i_product_id
                end as i_product_id,
                b.e_product_name,
                g.e_product_gradename as e_product_motifname,
                coalesce (e.n_quantity_stock, 0) as stok
            FROM 
                tm_gs_item a
            INNER JOIN tr_product b ON (b.i_product = a.i_product)
            INNER JOIN tr_product_motif c ON (c.i_product_motif = a.i_product_motif)
            inner join tr_product_status s on (s.i_product_status = b.i_product_status)
            inner join tm_gs d on (a.i_gs = d.i_gs)
            inner join tr_product_grade g on (g.i_product_grade=a.i_product_grade)
            inner join tm_sr f on d.i_sr = f.i_sr 
            left join tm_ic e on (a.i_product = e.i_product and a.i_product_grade = e.i_product_grade and a.i_product_motif = e.i_product_motif and f.i_store = e.i_store and f.i_store_location = e.i_store_location)
            WHERE a.i_gs = '$id'
            order by b.e_product_name
        ", FALSE);
    }

    /** Cek Apakah Data Sudah Ada Pas Edit */
    public function cek_edit()
    {
        $i_document = str_replace("_", "", $this->input->post('i_document'));
        $i_document_old = str_replace("_", "", $this->input->post('i_document_old'));
        return $this->db->query("
             SELECT 
                i_gs_id
             FROM 
                tm_gs 
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
            'i_company'     => $this->session->i_company,
            'i_gs'          => $id,
            'i_gs_id'       => $this->input->post('i_document'),
            'd_gs'          => date('Y-m-d', strtotime($this->input->post('d_document'))),
            'i_sr'          => $this->input->post('i_sr'),
            'i_area'        => $this->input->post('i_area'),
            'd_sr'          => date('Y-m-d', strtotime($this->input->post('d_sr'))),
            'v_gs'          => str_replace(",", "", $this->input->post('v_total')),
            'd_gs_entry'    => current_datetime(),
            'i_store'       => $this->input->post('i_store'),
            'i_store_loc'   => $this->input->post('i_store_loc'),
        );
        $this->db->where('i_gs', $id);
        $this->db->update('tm_gs', $header);

        $query = $this->db->query("SELECT i_sr FROM tm_gs WHERE i_gs ='$id' ", FALSE);
        if ($query->num_rows() > 0) {
            $i_sr = $query->row()->i_sr;
            // $this->db->query("UPDATE tm_so SET i_status_so = '5' WHERE i_so = '$i_so' ", FALSE);
            $detail = $this->db->query("SELECT * FROM tm_gs_item WHERE i_gs = '$id' ", FALSE);
            if ($detail->num_rows() > 0) {
                foreach ($detail->result() as $key) {
                    $this->db->query("
                        UPDATE tm_sr_item SET n_deliver = $key->n_quantity_deliver 
                        WHERE i_sr = '$i_sr' AND i_product = '$key->i_product' AND i_product_grade = '$key->i_product_grade' AND i_product_motif = '$key->i_product_motif'  
                    ", FALSE);
                }
            }
        }

        $this->db->query("
            DELETE FROM tm_gs_item where i_gs = '$id';
        ");

        if ($this->input->post('jml') > 0) {
            $i = 0;
            $x = 0;
            foreach ($this->input->post('i_product') as $i_product) {
                $deliver = str_replace(",", "", $this->input->post('n_deliver')[$i]);
                // if ($deliver > 0) {
                $item = array(
                    'i_gs'              => $id,
                    'i_gs_item_id'      => $this->input->post('i_document'),
                    'i_product'         => $i_product,
                    'i_product_grade'   => $this->input->post('i_product_grade')[$i],
                    'i_product_motif'   => $this->input->post('i_product_motif')[$i],
                    'e_product_name'    => $this->input->post('e_product_name')[$i],
                    'v_unit_price'      => str_replace(",", "", $this->input->post('v_unit_price')[$i]),
                    'e_remark'          => $this->input->post('e_remark')[$i],
                    'n_quantity_order'  => str_replace(",", "", $this->input->post('n_order')[$i]),
                    'n_quantity_deliver' => $deliver,
                    'n_item_no'         => $x,
                );
                $this->db->insert('tm_gs_item', $item);
                $this->db->query("
                        UPDATE tm_sr_item 
                        SET n_deliver = coalesce(n_deliver,0) + " . $deliver . " 
                        WHERE i_sr = '" . $this->input->post('i_sr') . "'
                        AND i_product = '" . $i_product . "'  AND i_product_grade = '" . $this->input->post('i_product_grade')[$i] . "'  AND i_product_motif = '" . $this->input->post('i_product_grade')[$i] . "'
                    ");
                $x++;
                // }

                $i++;
            }
        } else {
            die;
        }
        // $table = array(
        //     'i_status_so' => '6',
        // );
        // $this->db->where('i_so', $this->input->post('i_so'));
        // $this->db->update('tm_so', $table);
    }

    /** Cancel Data */
    public function cancel($id)
    {
        $table = array(
            'f_gs_cancel' => 't',
        );
        $this->db->where('i_gs', $id);
        $this->db->update('tm_gs', $table);

        $query = $this->db->query("SELECT i_sr FROM tm_gs WHERE i_gs ='$id' ", FALSE);
        if ($query->num_rows() > 0) {
            $i_sr = $query->row()->i_sr;
            // $this->db->query("UPDATE tm_so SET i_status_so = '5' WHERE i_so = '$i_so' ", FALSE);
            $detail = $this->db->query("SELECT * FROM tm_gs_item WHERE i_gs = '$id' ", FALSE);
            if ($detail->num_rows() > 0) {
                foreach ($detail->result() as $key) {
                    $this->db->query("
                        UPDATE tm_sr_item SET n_deliver = coalesce(n_deliver,0) - $key->n_quantity_deliver 
                        WHERE i_sr = '$i_sr' AND i_product = '$key->i_product' AND i_product_grade = '$key->i_product_grade' AND i_product_motif = '$key->i_product_motif'  
                    ", FALSE);
                }
            }
        }
    }

    /** Update Print */
    public function update_print($id)
    {
        $this->db->query("UPDATE tm_gs SET n_print = n_print + 1 WHERE i_gs = '$id' ", FALSE);
    }
}

/* End of file Mmaster.php */
