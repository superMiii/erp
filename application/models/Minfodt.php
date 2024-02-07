<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Minfodt extends CI_Model
{

    /**** List Datatable ***/
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

        if ($i_area != 'ALL') {
            $area = "AND a.i_area = '$i_area' ";
        } else {
            $area = "";
        }

        $dfrom  = date('Y-m-d', strtotime($dfrom));
        $dto    = date('Y-m-d', strtotime($dto));

        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("SELECT
                a.i_company ,
                a.i_dt ,
                a.d_dt ,
                a.i_dt_id ,
                b.i_area_id || ' - ' || b.e_area_name as e_area_name ,
                a.v_jumlah::money as v_jumlah ,
                '$dfrom' AS dfrom,
                '$dto' AS dto,
                '$i_area' AS i_area
            from
                tm_dt a
                inner join tr_area b on (b.i_area=a.i_area)
                inner join tm_user_area u on (u.i_area = b.i_area and u.i_user = '$this->i_user')
            where 
                a.d_dt BETWEEN '$dfrom' AND '$dto'
	            and a.f_dt_cancel = 'f'
                AND a.i_company = '$this->i_company'
                $area
            order by 3  desc 
        ", FALSE);

        /** Cek Hak Akses, Apakah User Bisa Edit */
        $datatables->add('action', function ($data) {
            $i_company  = $data['i_company'];
            $dfrom      = $data['dfrom'];
            $dto        = $data['dto'];
            $i_area    = $data['i_area'];
            $i_so      = $data['i_dt'];
            $data       = '';
            $data      .= "<a href='" . base_url() . $this->folder . '/view/' . encrypt_url($i_so) . '/' . encrypt_url($i_company) . '/' . encrypt_url($dfrom) . '/' . encrypt_url($dto) . '/' . encrypt_url($i_area) . "' title='View Mutasi'><i class='fa fa-eye fa-lg warning darken-4 mr-1'></i></a>";
            return $data;
        });
        $datatables->hide('i_company');
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

    /**** List Mutasi ***/
    public function get_data_detail($i_dt)
    {
        return $this->db->query("SELECT
                b.i_company ,
                a.i_dt_item ,
                a.i_dt ,
	            b.i_dt_id ,
                c.i_nota_id ,
                c.d_nota ,
                c.d_jatuh_tempo ,
                d.i_customer_id || ' ~ ' || d.e_customer_name as nm,
                c.v_nota_netto,
                c.v_sisa,
                e.v_jumlah,
                i.e_rv_refference_type_name,
                e.e_remark
            from
                tm_dt_item a 
            inner join tm_dt b on (b.i_dt=a.i_dt)
            inner join tm_nota c on (c.i_nota=a.i_nota)
            inner join tr_customer d on (d.i_customer=c.i_customer)
            left join tm_alokasi_item e on (e.i_nota=c.i_nota)
            left join tm_alokasi f on (f.i_alokasi=e.i_alokasi)
            left join tm_rv_item h on (h.i_rv_item=f.i_rv_item)
            left join tr_rv_refference_type i on (i.i_rv_refference_type=h.i_rv_refference_type)
            WHERE
                a.i_dt = '$i_dt'
        ", FALSE);
    }

    /** Get Data Untuk Export */
    public function get_data($dfrom, $dto, $i_area)
    {

        $dfrom  = date('Y-m-d', strtotime($dfrom));
        $dto    = date('Y-m-d', strtotime($dto));
        if ($i_area != 'ALL') {
            $area = "AND a.i_area = '$i_area' ";
        } else {
            $area = "";
        }
        return $this->db->query("SELECT
               a.i_company ,
                a.i_dt ,
                a.d_dt ,
                a.i_dt_id ,
                b.i_area_id || ' - ' || b.e_area_name as e_area_name ,
                a.v_jumlah as v_jumlah ,
                '$dfrom' AS dfrom,
                '$dto' AS dto,
                '$i_area' AS i_area
            from
                tm_dt a
                inner join tr_area b on (b.i_area=a.i_area)
                inner join tm_user_area u on (u.i_area = b.i_area and u.i_user = '$this->i_user')
            where 
                a.d_dt BETWEEN '$dfrom' AND '$dto'
	            and a.f_dt_cancel = 'f'
                AND a.i_company = '$this->i_company'
                $area
            order by 3  desc 
        ", FALSE);
    }
}

/* End of file Mmaster.php */
