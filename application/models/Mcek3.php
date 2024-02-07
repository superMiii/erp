<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mcek3 extends CI_Model
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

        $i_status_so = $this->input->post('i_status_so', TRUE);
        if ($i_status_so == '') {
            $i_status_so = $this->uri->segment(5);
        }

        if ($i_status_so != 'ALL') {
            $status_so = "AND a.i_status_so = '$i_status_so' ";
        } else {
            $status_so = "";
        }


        $dfrom  = date('Y-m-d', strtotime($dfrom));
        $dto    = date('Y-m-d', strtotime($dto));

        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("SELECT 
                    b.i_status_so as id,
                    a.f_so_cancel AS f_status,
                    b.e_status_so_name ,
                    a.i_so_id,
                    c.i_do_id,
                    d.i_nota_id,
                    a.v_so::money as vso,
                    d.v_nota_netto::money as vnota 
                from
                    tm_so a
                inner join tr_status_so b on (b.i_status_so=a.i_status_so)
                left join tm_do c on (c.i_so=a.i_so and c.f_do_cancel ='f')
                left join tm_nota d on (d.i_so=a.i_so and d.f_nota_cancel ='f')
                where 
                    a.f_so_cancel ='f'
                    AND a.i_company = '$this->i_company'
                    AND a.d_so BETWEEN '$dfrom' AND '$dto'
                    $status_so
                ORDER BY
                    1 ASC
                    ", FALSE);

        $datatables->edit('f_status', function ($data) {
            if ($data['f_status'] == 't') {
                $status = $this->lang->line('Batal');
                $color  = 'red';
            } else {
                $color  = 'teal';
                $status = $data['e_status_so_name'];
            }
            $data = "<span class='badge bg-" . $color . " badge-pill'>" . $status . "</span>";
            return $data;
        });

        $datatables->hide('e_status_so_name');
        return $datatables->generate();
    }

    /** Get Area */
    public function get_status_so($cari)
    {
        return $this->db->query("select distinct 
                a.*
            from
                tr_status_so a
            inner join tm_so b on
                (b.i_status_so = a.i_status_so)
                order by 1
        ", FALSE);
    }


    /** Get Data Untuk Edit */
    public function get_data($dfrom, $dto, $i_status_so)
    {
        if ($i_status_so != 'ALL') {
            $status_so = "AND a.i_status_so = '$i_status_so' ";
        } else {
            $status_so = "";
        }

        $dfrom  = date('Y-m-d', strtotime($dfrom));
        $dto    = date('Y-m-d', strtotime($dto));
        return $this->db->query("SELECT 
                    b.i_status_so as id,
                    a.f_so_cancel AS f_status,
                    b.e_status_so_name ,
                    a.i_so_id,
                    c.i_do_id,
                    d.i_nota_id,
                    a.v_so as vso,
                    d.v_nota_netto as vnota 
                from
                    tm_so a
                inner join tr_status_so b on (b.i_status_so=a.i_status_so)
                left join tm_do c on (c.i_so=a.i_so and c.f_do_cancel ='f')
                left join tm_nota d on (d.i_so=a.i_so and d.f_nota_cancel ='f')
                where 
                    a.f_so_cancel ='f'
                    AND a.i_company = '$this->i_company'
                    AND a.d_so BETWEEN '$dfrom' AND '$dto'
                    $status_so
                ORDER BY
                    1 ASC
                    ", FALSE);
    }
}

/* End of file Mmaster.php */
