<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mspmbrealisasi extends CI_Model
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
                d_sr as tgl,
                a.i_sr AS id,
                d_sr,
                i_sr_id,
                a.d_approve1,
                to_char(d_sr, 'YYYYMM') as i_periode,
                b.e_area_name,
                a.e_remark,
                f_sr_cancel AS f_status,
                a.d_approve2,
                '$dfrom' as dfrom,
                '$dto' as dto,
                '$i_area' AS i_area
            FROM
                tm_sr a
            INNER JOIN tr_area b ON
                (b.i_area = a.i_area)
            INNER JOIN tm_user_area c ON
                (c.i_area = b.i_area)
            WHERE
                c.i_user = '$this->i_user'
                AND a.i_company = '$this->i_company'
                AND f_sr_acc = 't'
                AND d_approve1 NOTNULL
                AND f_sr_cancel = 'f'
                AND a.i_store is null
                AND a.d_sr BETWEEN '$dfrom' AND '$dto'
                $area
            ORDER BY
                1 DESC 
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
            $d_approve1 = $data['d_approve1'];
            $d_approve2 = $data['d_approve2'];
            $i_periode  = $data['i_periode'];
            $dfrom          = $data['dfrom'];
            $dto            = $data['dto'];
            $i_area     = $data['i_area'];
            $data       = '';
            $data      .= "<a href='" . base_url() . $this->folder . '/view/' . encrypt_url($id) . '/' . encrypt_url($dfrom) . '/' . encrypt_url($dto) . '/' . encrypt_url($i_area) . "' title='View Data'><i class='fa fa-eye fa-lg warning darken-4 mr-1'></i></a>";
            if ($i_periode >= get_periode()) {
                if (check_role($this->i_menu, 3) && $f_status == 'f'  && ($d_approve1 != null || $d_approve1 != '')) {
                    $data      .= "<a href='" . base_url() . $this->folder . '/edit/' . encrypt_url($id)  . '/' .  encrypt_url($dfrom) . '/' . encrypt_url($dto) . '/' . encrypt_url($i_area) . "' title='Edit Data'><i class='fa fa-pencil-square success darken-4 fa-lg mr-1'></i></a>";
                }
                if (check_role($this->i_menu, 5) && $f_status == 'f') {
                    $data      .= "<a href='#' onclick='openLink(\"" . $this->folder . "\",\"" . encrypt_url($id) . "\"); return false;' title='Print Data'><i class='fa fa-print fa-lg blue darken-4 mr-1'></i></a>";
                }
            }
            return $data;
        });
        $datatables->hide('d_approve2');
        $datatables->hide('i_periode');
        $datatables->hide('id');
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

    /** Get Data Untuk Edit */
    public function getdata($id)
    {
        return $this->db->query("SELECT 
                a.*,
                b.e_store_name,
                c.i_area_id,
                c.e_area_name
            FROM 
                tm_sr a
            LEFT JOIN tr_store b ON 
                (b.i_store = a.i_store)
            LEFT JOIN tr_store_loc d ON 
                (d.i_store_loc = a.i_store_location)
            INNER JOIN tr_area c ON 
                (c.i_area = a.i_area)
            WHERE
                i_sr = '$id'
        ", FALSE);
    }

    /** Get Data Untuk Edit */
    public function getdetail($id)
    {
        return $this->db->query("WITH cte AS (
                select a.i_store , i_store_loc from tr_store_loc a 
                inner join tr_store b on (a.i_store = b.i_store) 
                where b.f_store_pusat = true and b.i_company = '$this->i_company' 
                order by b.d_store_update, b.d_store_entry desc nulls last limit 1  
            )
            SELECT
                a.*,
                COALESCE(n_acc,0) AS acc,
                case
                    when s.i_product_statusid = 'STP1' then b.i_product_id || ' (*STP)'
                    when s.i_product_statusid = 'STP2' then b.i_product_id || ' (#STP)'
                    else b.i_product_id
                end as i_product_id,
                b.e_product_name,
                g.e_product_gradename as e_product_motifname,
                cte.i_store,
                cte.i_store_loc,
                COALESCE(i.n_quantity_stock , 0) as stok
            FROM
                tm_sr_item a
            INNER JOIN tr_product b ON (b.i_product = a.i_product)
            INNER JOIN tr_product_motif c ON (c.i_product_motif = a.i_product_motif)
            inner join tr_product_status s on (s.i_product_status = b.i_product_status)
            inner join tr_product_grade g on (g.i_product_grade=a.i_product_grade)
            full join cte on (cte.i_store is not null)
            left join tm_ic i on (i.i_store = cte.i_store and i.i_store_location = cte.i_store_loc
            and i.i_product = a.i_product and i.i_product_grade = a.i_product_grade and i.i_product_motif = a.i_product_motif)
            WHERE a.i_sr = '$id' 
            order by b.e_product_name
        ", FALSE);
    }

    /** Cek Apakah Data Sudah Ada Pas Edit */
    public function cek_edit()
    {
        $i_document = $this->input->post('i_document', TRUE);
        $i_document_old = $this->input->post('i_document_old', TRUE);
        return $this->db->query("SELECT 
                i_sr_id
            FROM 
                tm_sr 
            WHERE 
                trim(upper(i_sr_id)) <> trim(upper('$i_document_old'))
                AND trim(upper(i_sr_id)) = trim(upper('$i_document'))
                AND i_company = '$this->i_company'
        ", FALSE);
    }

    /** Update Data */
    public function update()
    {
        $id = $this->input->post('id');
        $i_area = $this->input->post('i_area');
        $header = array(
            'e_remark'          => $this->input->post('e_remark'),
        );
        $this->db->where('i_sr', $id);
        $this->db->update('tm_sr', $header);


        if (is_array($this->input->post('i_sr_item')) || is_object($this->input->post('i_sr_item'))) {
            $i = 0;
            $i_store = '';
            $i_store_loc = '';
            $n_op = '';
            $totalop = 0;
            $f_op = 'f';
            foreach ($this->input->post('i_sr_item') as $i_sr_item) {
                $i_store = $this->input->post('i_store')[$i];
                $i_store_loc = $this->input->post('i_store_loc')[$i];
                $n_op = $this->input->post('n_op')[$i];

                $totalop += $n_op;
                $item = array(
                    'n_stock' => $this->input->post('n_stock')[$i],
                    'n_op'    => $n_op,
                    'e_remark' => $this->input->post('e_remarkitem')[$i],
                );
                $this->db->where('i_sr_item', $i_sr_item);
                $this->db->update('tm_sr_item', $item);
                $i++;
            }

            if ($totalop > 0) {
                $f_op = 't';
            }

            $this->db->query("
                update tm_sr set f_po = '$f_op', i_store = '$i_store', i_store_location = '$i_store_loc' where i_sr = '$id' 
            ", FALSE);
        } else {
            die;
        }
    }


    public function update_print($id)
    {
        $this->db->query("UPDATE tm_sr SET n_print = n_print + 1 WHERE i_sr = '$id' ", FALSE);
    }
}

/* End of file Mmaster.php */
