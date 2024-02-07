<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mexportcsv extends CI_Model
{

    /** List Datatable */
    public function serverside()
    {
        $i_nota_mulai = $this->input->post('i_nota_mulai', TRUE);
        if ($i_nota_mulai == '') {
            $i_nota_mulai = $this->uri->segment(3);
        }

        $i_nota_akhir = $this->input->post('i_nota_akhir', TRUE);
        if ($i_nota_akhir == '') {
            $i_nota_akhir = $this->uri->segment(4);
        }

        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("SELECT DISTINCT
                a.i_nota AS id,
                a.i_seri_pajak,
                a.i_nota_id,
                to_char(a.d_nota, 'DD FMMonth YYYY') AS d_nota,
                c.i_do_id,
                to_char(c.d_do, 'DD FMMonth YYYY') AS d_do,
                ' [ ' || f.i_customer_id || ' ] ' || f.e_customer_name AS customer,
                g.e_area_name
            FROM
                tm_nota a
            INNER JOIN (SELECT DISTINCT i_nota, i_do FROM tm_nota_item) b ON
                (b.i_nota = a.i_nota)
            INNER JOIN tm_do c ON
                (c.i_do = b.i_do)
            INNER JOIN tr_customer f ON
                (f.i_customer = a.i_customer)
            INNER JOIN tr_area g ON
                (g.i_area = a.i_area)
            INNER JOIN tm_user_area h ON
                (h.i_area = g.i_area)
            WHERE
                a.i_company = '$this->i_company'
                AND h.i_user = '$this->i_user'
                AND a.i_nota_id BETWEEN '$i_nota_mulai' AND '$i_nota_akhir'
                /* AND d.i_status_so = '7' */
                AND a.f_nota_cancel = 'f'
                AND a.i_seri_pajak NOTNULL
            ORDER BY
                a.i_nota ASC
        ", FALSE);
        return $datatables->generate();
    }

    public function data_header($i_nota_mulai, $i_nota_akhir)
    {
        return $this->db->query("SELECT
                a.i_nota,
                /* regexp_replace(trim(a.i_seri_pajak), '[^\w]+', '', 'g') AS nomor_faktur, */
                substring(regexp_replace(trim(a.i_seri_pajak), '[^\w]+', '', 'g'),4,13) AS nomor_faktur,
                to_char(d_pajak, 'MM') AS masa_pajak,
                to_char(d_pajak, 'YYYY') AS tahun_pajak,
                to_char(d_nota, 'DD/MM/YYYY') AS tanggal_faktur,
                CASE
                    WHEN (trim(b.e_customer_npwpcode) ISNULL
                    OR trim(b.e_customer_npwpcode) = '') THEN '000000000000000'
                    ELSE regexp_replace(trim(b.e_customer_npwpcode), '[^\w]+', '', 'g')
                END AS npwp,
                CASE
                    WHEN (trim(b.e_customer_npwpname) ISNULL
                    OR trim(b.e_customer_npwpname) = '') THEN upper(trim(b.e_customer_name))
                    ELSE upper(trim(b.e_customer_npwpname))
                END AS nama,
                CASE
                    WHEN (trim(b.e_customer_npwpaddress) ISNULL
                    OR trim(b.e_customer_npwpaddress) = '') THEN upper(trim(b.e_customer_address))
                    ELSE upper(trim(b.e_customer_npwpaddress))
                END AS alamat,
                a.v_nota_gross - a.v_nota_discount AS dpp,
                a.v_nota_ppn AS ppn,
                i_nota_id
            FROM
                tm_nota a
            INNER JOIN tr_customer b ON
                (b.i_customer = a.i_customer)
            WHERE
                a.f_nota_cancel = 'f'
                AND a.i_nota_id BETWEEN '$i_nota_mulai' AND '$i_nota_akhir'
                AND a.i_company = '$this->i_company'
                AND a.i_seri_pajak NOTNULL
            ORDER BY
                a.i_nota_id ASC
        ", FALSE);
    }

    public function data_detail($id)
    {
        return $this->db->query("SELECT
                b.i_product_id AS kode_objek,
                upper(b.e_product_name) AS nama,
                a.v_unit_price AS harga_satuan,
                a.n_deliver AS jumlah_barang,
                a.n_deliver * a.v_unit_price AS harga_total,
                a.v_nota_discount1 + a.v_nota_discount2 + a.v_nota_discount3 + a.v_nota_discount4 AS diskon
            FROM
                tm_nota_item a
            INNER JOIN tr_product b ON
                (b.i_product = a.i_product)
            WHERE
                a.i_nota = '$id'
            ORDER BY
                a.n_item_no ASC
        ", FALSE);
    }

    public function get_nota_mulai($cari) {
        return $this->db->query("
            select i_nota, i_nota_id, to_char(d_nota, 'dd-mm-yyyy') as d_nota from tm_nota where f_nota_cancel = 'f' and f_pajak_cancel = 'f' 
            and i_seri_pajak notnull and i_company = '$this->i_company' and i_nota_id ilike '%$cari%'
            order by i_nota_id asc
        ", FALSE);
    }

    public function get_nota_akhir($cari ,$i_nota)
    {
        return $this->db->query("
            select i_nota, i_nota_id, to_char(d_nota, 'dd-mm-yyyy') as d_nota from tm_nota where f_nota_cancel = 'f' and f_pajak_cancel = 'f' 
            and i_seri_pajak notnull and i_company = '$this->i_company' and i_nota_id ilike '%$cari%' and i_nota_id >= '$i_nota'
            order by i_nota_id asc
        ", FALSE);
    }
}

/* End of file Mmaster.php */
