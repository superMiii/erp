<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mcloseop extends CI_Model
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
        $datatables->query("WITH cte as (
                    select ROW_NUMBER() OVER (ORDER BY a.i_po) AS i , a.i_po, a.i_po_id, to_char(a.d_po, 'dd-mm-yyyy') as d_po,
                    c.i_so_id, to_char(a.d_so , 'dd-mm-yyyy') as d_so,
                    d.i_sr_id, to_char(a.d_sr , 'dd-mm-yyyy') as d_sr,
                    e.e_supplier_name, f_po_close 
                    from tm_po a
                    left join tm_so c on (a.i_so = c.i_so)
                    left join tm_sr d on (a.i_sr = d.i_sr)
                    left join tr_supplier e on (a.i_supplier = e.i_supplier)
                    where f_po_cancel = 'f' and  a.i_po in (
                        select i_po from (select i_po, sum(n_order - n_delivery) as sisa from tm_po_item group by 1) as x
                        where sisa > 0
                    ) and a.d_po between '$dfrom' and '$dto' AND a.i_company = '$this->i_company' $supplier
                )
                select (select count(i) AS jml from cte) AS jml, i, i_po, i_po_id, d_po, coalesce(i_so_id, i_sr_id) as i_reff , coalesce(d_so, d_sr) as d_reff, e_supplier_name, f_po_close 
                from cte a 
        ", FALSE);

        $datatables->edit('f_po_close', function ($data) {
            if ($data['f_po_close'] == 't') {
                $status = $this->lang->line('Tutup');
                $color  = 'danger';
            } else {
                $color  = 'success';
                $status = $this->lang->line('Buka');
            }
            $data = "<span class='btn btn-outline-" . $color . " btn-sm round'>" . $status . "</span>";
            return $data;
        });

        /** Cek Hak Akses, Apakah User Bisa Edit */
        $datatables->add('action', function ($data) {
            $id         = trim($data['i_po']);
            $i        = trim($data['i']);
            $jml        = trim($data['jml']);
            $close   = $data['f_po_close'];
            $data       = '';
            if (check_role($this->id_menu, 3)) {
                // $data      .= "<a href='" . base_url() . $this->folder . '/detail_realisasi/' . encrypt_url($id) . "' title='Realisasi Data'><i class='feather icon-check-square mr-1'></i></a>";
                if ($close == 'f') {
                    $data  .= "
                    <input type=\"checkbox\" id=\"chk\" name=\"chk" . $i . "\">
                    <input name=\"id" . $i . "\" value=\"" . $id . "\" type=\"hidden\">
                    <input name=\"jml\" value=\"" . $jml . "\" type=\"hidden\">";
                } else {
                    //$data .= "<a href=\"#\" onclick='unclosing(\"$id\",\"$iop\"); return false;'><i class='ti-close'></i></a>";
                }
            }
            return $data;
        });
        $datatables->hide('i');
        $datatables->hide('jml');
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
}

/* End of file Mmaster.php */
