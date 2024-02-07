<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Minfohutangpembelian extends CI_Model
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

        if ($i_supplier != 'ALL') {
            $supplier = "AND b.i_supplier = '$i_supplier' ";
        } else {
            $supplier = "";
        }

        $dfrom  = date('Y-m-d', strtotime($dfrom));
        $dto    = date('Y-m-d', strtotime($dto));

        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("SELECT 
            i_nota,
            i_nota_id,
            e_supplier_name,
            n_supplier_top ,
            d_nota ,
            d_jatuh_tempo ,
            kn::money as kn ,
            yr::money as yr
        from (
            select
                a.i_nota,
                a.i_nota_id,
                b.e_supplier_name,
                b.n_supplier_top ,
                a.d_nota ,
                a.d_jatuh_tempo ,
                a.v_nota_netto as kn ,
                (a.v_sisa + coalesce (ie.kana,0)) as yr
            from
                tm_nota_pembelian a
            inner join tr_supplier b on	(b.i_supplier = a.i_supplier)
            left join (
                select
                    sp.i_nota,
                    sum(sp.v_jumlah) as kana
                from
                    (
                    select
                        x.i_nota,
                        x.v_jumlah
                    from
                        tm_alokasi_bk_item x
                    inner join tm_alokasi_bk n on
                        (n.i_alokasi = x.i_alokasi)
                    where
                        n.d_alokasi > '$dto') sp
                group by 1) ie on (ie.i_nota = a.i_nota)
            where
                a.i_company = '$this->i_company'
                and a.f_nota_cancel = 'f'
                and d_nota <= '$dto'
                $supplier ) tt where yr > 0
            order by i_nota
        ", FALSE);
        return $datatables->generate();
    }

    public function get_supplier($cari)
    {
        return $this->db->query("SELECT 
                i_supplier, i_supplier_id , initcap(e_supplier_name) AS e_supplier_name
            FROM 
                tr_supplier
            WHERE 
                (e_supplier_name ILIKE '%$cari%' OR i_supplier_id ILIKE '%$cari%')
                AND i_company = '$this->i_company' 
                AND f_supplier_active = 'true' 
            ORDER BY 3 ASC
        ", FALSE);
    }

    /** Get Data Untuk Edit */
    public function get_data($dfrom, $dto, $i_supplier)
    {
        if ($i_supplier != 'ALL') {
            $supplier = "AND b.i_supplier = '$i_supplier' ";
        } else {
            $supplier = "";
        }
        $dfrom  = date('Y-m-d', strtotime($dfrom));
        $dto    = date('Y-m-d', strtotime($dto));
        return $this->db->query("SELECT
                i_nota,
                i_nota_id,
                e_supplier_name,
                n_supplier_top ,
                d_nota ,
                d_jatuh_tempo ,
                kn as kn ,
                yr as yr
            from (
                select
                    a.i_nota,
                    a.i_nota_id,
                    b.e_supplier_name,
                    b.n_supplier_top ,
                    a.d_nota ,
                    a.d_jatuh_tempo ,
                    a.v_nota_netto as kn ,
                    (a.v_sisa + coalesce (ie.kana,0)) as yr
                from
                    tm_nota_pembelian a
                inner join tr_supplier b on	(b.i_supplier = a.i_supplier)
                left join (
                    select
                        sp.i_nota,
                        sum(sp.v_jumlah) as kana
                    from
                        (
                        select
                            x.i_nota,
                            x.v_jumlah
                        from
                            tm_alokasi_bk_item x
                        inner join tm_alokasi_bk n on
                            (n.i_alokasi = x.i_alokasi)
                        where
                            n.d_alokasi > '$dto') sp
                    group by 1) ie on (ie.i_nota = a.i_nota)
                where
                    a.i_company = '$this->i_company'
                    and a.f_nota_cancel = 'f'
                    and d_nota <= '$dto'
                    $supplier ) tt where yr > 0
                order by i_nota
        ", FALSE);
    }
}

/* End of file Mmaster.php */
