<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mfcop extends CI_Model
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
            $supplier = "AND tfp.i_supplier = '$i_supplier' ";
        } else {
            $supplier = "";
        }

        //var_dump($dfrom . $dto);

        $dfrom  = date('Ym', strtotime($dfrom));
        $dto    = date('Ym', strtotime($dto));


        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("SELECT x.i_forecast as id, d_forecast, d_validasi, a.e_supplier_name from (
                    select i_company, i_supplier, to_char(d_forecast, 'FMMonth yyyy') as d_forecast,  to_char(d_forecast, 'yyyymm') as d_validasi , max(i_forecast) as i_forecast 
                    from tm_forecast_pembelian tfp
                    where i_company = '$this->i_company' and to_char(d_forecast, 'yyyymm') between '$dfrom' and '$dto' and f_forecast_cancel = 'f' $supplier
                    group by 1,2, to_char(d_forecast, 'FMMonth yyyy'), to_char(d_forecast, 'yyyymm')
                ) as x
                inner join tr_supplier a on (x.i_supplier = a.i_supplier)
        ", FALSE);

        /** Cek Hak Akses, Apakah User Bisa Edit */

        $datatables->add('action', function ($data) {
            $id         = trim($data['id']);
            $today = date('Ym');
            $d_validasi   = trim($data['d_validasi']);
            $data       = '';

            $data      .= "<a href='" . base_url() . $this->folder . '/view/' . encrypt_url($id) . "' title='View Data'><i class='icon-eye text-warning mr-1'></i></a>";

            if ($d_validasi >= $today) {
                if (check_role($this->id_menu, 3)) {
                    $data      .= "<a href='" . base_url() . $this->folder . '/edit/' . encrypt_url($id) . "' title='" . $this->lang->line('Ubah') . "'><i class='feather icon-check-square mr-1'></i></a>";
                }

                if (check_role($this->id_menu, 4)) {
                    $data      .= "<a href='#' onclick='sweetdelete(\"" . $this->folder . "\",\"" . $id . "\");' title='Delete Data'><i class='fa fa-times text-danger'></i></a>";
                }
            }

            return $data;
        });
        $datatables->hide('d_validasi');
        return $datatables->generate();
    }

    /** Get Supplier */
    public function get_supplier0($cari)
    {
        return $this->db->query("SELECT distinct
                xx.i_supplier, i_supplier_id , e_supplier_name AS e_supplier_name
            FROM 
                tr_supplier xx
            WHERE 
                (e_supplier_name ILIKE '%$cari%' OR i_supplier_id ILIKE '%$cari%')
                AND xx.i_company = '$this->i_company' 
                AND f_supplier_active = 'true' 
            ORDER BY 3 ASC
        ", FALSE);
    }

    /** Get Data Product */
    public function get_product($cari)
    {
        return $this->db->query("SELECT
                i_product,
                i_product_id,
                initcap(e_product_name) AS e_product_name
            FROM
                tr_product
            WHERE
                (i_product_id ILIKE '%$cari%'
                    OR e_product_name ILIKE '%$cari%')
                AND i_company = '$this->i_company'
            ORDER BY
                e_product_name ASC
        ", FALSE);
    }

    /** Get Data Detail Product */
    public function get_product_detail($i_product)
    {
        return $this->db->query("
            SELECT
                i_product,
                a.i_product_motif,
                i_product_id,
                initcap(e_product_name) AS e_product_name,
                b.e_product_motifname
            FROM
                tr_product a
            INNER JOIN tr_product_motif b ON
                (b.i_product_motif = a.i_product_motif)
            WHERE 
                a.i_product = '$i_product'
        ", FALSE);
    }


    public function save()
    {
        $query = $this->db->query("SELECT max(i_forecast)+1 AS id FROM tm_forecast_pembelian", TRUE);
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

        $d_fc = date('Y-m-d', strtotime($this->input->post('d_document_submit')));
        $header = array(
            'i_company'             => $this->i_company,
            'i_forecast'            => $id,
            'i_supplier'            => $this->input->post('i_supplier'),
            'd_forecast'            => $d_fc,
            'e_remark'              => $this->input->post('e_remark'),
        );
        $this->db->insert('tm_forecast_pembelian', $header);

        $i = 0;
        foreach ($this->input->post('i_product') as $i_product) {
            $item = array(
                'i_forecast'              => $id,
                'i_product'         => $i_product,
                'i_product_motif'   => $this->input->post('i_product_motif')[$i],
                'n_forecast'        => $this->input->post('n_quantity')[$i],
                'e_product_name'    => $this->input->post('e_product_name')[$i],
                'e_remark'          => $this->input->post('e_remarkitem')[$i],
                'n_item_no'         => $i,
            );
            $this->db->insert('tm_forecast_pembelian_item', $item);
            $i++;
        }
    }

    public function get_data_edit($id)
    {
        return $this->db->query(" 
            select i_forecast, to_char(d_forecast, 'dd-FMMonth-yyyy') as d_forecast, x.i_supplier , a.i_supplier_id, a.e_supplier_name , x.e_remark 
            from tm_forecast_pembelian x
            inner join tr_supplier a on (x.i_supplier = a.i_supplier)
            where i_forecast = '$id'
        ", FALSE);
    }

    /** Get Detail EDIT Untuk PO Item */
    public function get_data_detail($id)
    {
        return $this->db->query("
            with cte as (
                select a.i_product, b.i_product_id, b.e_product_name, a.i_product_motif, 
                c.e_product_motifname, a.n_forecast, a.e_remark, d.i_supplier, d_forecast from tm_forecast_pembelian_item a
                inner join tr_product b on (a.i_product = b.i_product)
                inner join tr_product_motif c on (a.i_product_motif = c.i_product_motif)
                inner join tm_forecast_pembelian d on (a.i_forecast = d.i_forecast)
                where a.i_forecast = '$id' 
            )
            select x.*, coalesce(y.diterima,0) as diterima from cte x
            left join (
                select a.i_supplier, i_product, i_product_motif, sum(n_delivery) as diterima from tm_po a
                inner join tm_po_item b on (a.i_po = b.i_po)
                where to_char(a.d_po, 'yyyymm') = (select to_char(d_forecast, 'yyyymm') from cte limit 1)
                group by 1,2,3
            ) as y on (x.i_supplier = y.i_supplier and x.i_product = y.i_product and x.i_product_motif = y.i_product_motif )
            order by diterima desc, e_product_name asc
        ", FALSE);
    }

    /** Update Data */
    public function update()
    {
        $id = $this->input->post('id');
        $d_fc = date('Y-m-d', strtotime($this->input->post('d_document_submit')));

        $header = array(
            'i_company'             => $this->i_company,
            'i_supplier'            => $this->input->post('i_supplier'),
            'd_forecast'            => $d_fc,
            'e_remark'              => $this->input->post('e_remark'),
        );
        $this->db->where('i_forecast', $id);
        $this->db->update('tm_forecast_pembelian', $header);

        $i = 0;
        $this->db->query(" DELETE from tm_forecast_pembelian_item where i_forecast = '$id';", FALSE);
        foreach ($this->input->post('i_product') as $i_product) {
            $item = array(
                'i_forecast'        => $id,
                'i_product'         => $i_product,
                'i_product_motif'   => $this->input->post('i_product_motif')[$i],
                'n_forecast'        => $this->input->post('n_quantity')[$i],
                'e_product_name'    => $this->input->post('e_product_name')[$i],
                'e_remark'          => $this->input->post('e_remarkitem')[$i],
                'n_item_no'         => $i,
            );
            $this->db->insert('tm_forecast_pembelian_item', $item);
            $i++;
        }
    }


    /** Cancel Data */
    public function cancel($id)
    {
        $table = array(
            'f_forecast_cancel' => 't',
        );
        $this->db->where('i_forecast', $id);
        $this->db->update('tm_forecast_pembelian', $table);
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
}

/* End of file Mmaster.php */
