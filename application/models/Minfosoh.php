<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Minfosoh extends CI_Model
{

    /** List Datatable */
    public function serverside()
    {

        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("SELECT DISTINCT
                    a.i_product ,
                    a.i_company,
                    case
                        when s.i_product_statusid = 'STP1' then b.i_product_id || ' (*STP)'
                        when s.i_product_statusid = 'STP2' then b.i_product_id || ' (#STP)'
                        else b.i_product_id
                    end as i_product_id,
                    b.e_product_name ,
                    c.e_product_motifname ,
                    d.e_product_gradename ,
                    cc.ac
                from
                    tm_ic a
                inner join tr_product b on (b.i_product = a.i_product)
                inner join tr_product_motif c on (c.i_product_motif = a.i_product_motif)
                inner join tr_product_grade d on (d.i_product_grade = a.i_product_grade)
                inner join tr_product_status s on (s.i_product_status = b.i_product_status)
                inner join (select i_product, sum(ab) as ac	from
                        (select	i_product, n_quantity_stock as ab from tm_ic
                        where i_company = '$this->i_company' ) as bb group by 1) cc on (cc.i_product = a.i_product)
                where
                    a.i_company = '$this->i_company'
                order by
                    4
                    ", FALSE);

        /** Cek Hak Akses, Apakah User Bisa Edit */
        $datatables->add('action', function ($data) {
            $i_company  = $data['i_company'];
            $i_product  = $data['i_product'];
            $data       = '';
            $data      .= "<a href='" . base_url() . $this->folder . '/view/' . encrypt_url($i_product) . '/' . encrypt_url($i_company) . "' title='View Mutasi'><i class='fa fa-eye fa-lg warning darken-4 mr-1'></i></a>";
            return $data;
        });
        $datatables->hide('i_company');
        return $datatables->generate();
    }

    /**** List Detail ***/
    public function get_data_detail($i_product, $i_company)
    {

        return $this->db->query("SELECT
	                a.i_product ,
                    a.i_company,
                    case
                        when s.i_product_statusid = 'STP1' then b.i_product_id || ' (*STP)'
                        when s.i_product_statusid = 'STP2' then b.i_product_id || ' (#STP)'
                        else b.i_product_id
                    end as i_product_id,
                    b.e_product_name ,
                    c.e_product_motifname ,
                    d.e_product_gradename ,
                    a.n_quantity_stock,
                	e.e_store_name  
                from
                    tm_ic a
                inner join tr_product b on (b.i_product = a.i_product)
                inner join tr_product_motif c on (c.i_product_motif = a.i_product_motif)
                inner join tr_product_grade d on (d.i_product_grade = a.i_product_grade)
            	inner join tr_store e on (e.i_store = a.i_store)
                inner join tr_product_status s on (s.i_product_status = b.i_product_status)
                where
                    a.i_company = '$i_company'
                    and a.i_product = '$i_product'
                order by
                    4 
        ", FALSE);
    }

    /** Get Data Untuk Edit */
    public function get_data()
    {
        return $this->db->query("SELECT DISTINCT
           a.i_product ,
                    a.i_company,
                    case
                        when s.i_product_statusid = 'STP1' then b.i_product_id || ' (*STP)'
                        when s.i_product_statusid = 'STP2' then b.i_product_id || ' (#STP)'
                        else b.i_product_id
                    end as i_product_id,
                    b.e_product_name ,
                    c.e_product_motifname ,
                    d.e_product_gradename ,
                    cc.ac
                from
                    tm_ic a
                inner join tr_product b on (b.i_product = a.i_product)
                inner join tr_product_motif c on (c.i_product_motif = a.i_product_motif)
                inner join tr_product_grade d on (d.i_product_grade = a.i_product_grade)
                inner join tr_product_status s on (s.i_product_status = b.i_product_status)
                inner join (select i_product, sum(ab) as ac	from
                        (select	i_product, n_quantity_stock as ab from tm_ic
                        where i_company = '$this->i_company' ) as bb group by 1) cc on (cc.i_product = a.i_product)
                where
                    a.i_company = '$this->i_company'
                order by
                    4
        ", FALSE);
    }
}

/* End of file Mmaster.php */
