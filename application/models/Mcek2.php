<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mcek2 extends CI_Model
{

    /**** List Datatable ***/
    public function serverside()
    {
        $i_store = $this->input->post('i_store', TRUE);
        if ($i_store == '') {
            $i_store = $this->uri->segment(5);
        }

        $dfrom = $this->input->post('dfrom', TRUE);
        if ($dfrom == '') {
            $dfrom = $this->uri->segment(3);
        }

        $dto = $this->input->post('dto', TRUE);
        if ($dto == '') {
            $dto = $this->uri->segment(4);
        }
        $i_periode = date('Ym', strtotime($dfrom));
        $datefrom = date('Y-m-d', strtotime($dfrom));
        $dateto = date('Y-m-d', strtotime($dto));
        $datejangkafrom = date('Y-m-01', strtotime($dfrom));
        $datejangkato = date('Y-m-d', strtotime('-1 days', strtotime($dfrom)));
        if ($datejangkafrom == $datefrom) {
            $datejangkafrom = '9999-09-09';
            $datejangkato = '9999-09-29';
        }

        $i_periode2 = date('Ym', strtotime('+1 month', strtotime($dfrom)));

        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("SELECT
                a.e_product_name as id,
                a.i_company,
                a.i_product,
                a.i_product_id,
                a.e_product_name,
                n_saldo_akhir,
                i_mutasi_saldoawal,
                n_saldo_awal,
                case when n_saldo_awal - n_saldo_akhir <> 0 then 'SALDO BEDA' ELSE 'OK' end as selisih2,
                n_saldo_awal - n_saldo_akhir as selisih3,
	            i_ic,
                n_quantity_stock,
                n_quantity_stock - n_saldo_akhir as selisih,
                '$dfrom' AS dfrom,
                '$dto' AS dto,
                '$i_store' AS i_store
            from
                tr_product a
            left join (SELECT
                i_product,
                n_saldo_akhir
            FROM f_cab_mut_stok('$i_store',$this->i_company,'$i_periode','$datefrom','$dateto','$datejangkafrom','$datejangkato')) as b on (b.i_product=a.i_product)
            left join (SELECT i_ic, i_product, n_quantity_stock from tm_ic ic inner join tr_store st on (st.i_store=ic.i_store)
            where ic.i_company = $this->i_company and st.i_store ='$i_store') c on (c.i_product=a.i_product)
            left join (SELECT
                i_mutasi_saldoawal,
                i_product,
                n_saldo_awal 
            from
                tm_mutasi_saldoawal 
            where i_periode = '$i_periode2' and i_company = $this->i_company and i_store='$i_store') as cc on (cc.i_product=a.i_product)
            where a.i_company = $this->i_company
            order by 13 desc
        ", FALSE);

        /** Cek Hak Akses, Apakah User Bisa Edit */
        $datatables->add('action', function ($data) {
            $i_company  = $data['i_company'];
            $dfrom      = $data['dfrom'];
            $dto        = $data['dto'];
            $i_store    = $data['i_store'];
            $i_product  = $data['i_product'];
            $data       = '';
            $data      .= "<a href='" . base_url() . $this->folder . '/view/' . encrypt_url($i_product) . '/' . encrypt_url($i_company) . '/' . encrypt_url($dfrom) . '/' . encrypt_url($dto) . '/' . encrypt_url($i_store) . "' title='View Mutasi'><i class='fa fa-eye fa-lg warning darken-4 mr-1'></i></a>";
            return $data;
        });
        $datatables->hide('i_company');
        $datatables->hide('i_product');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('i_store');
        $datatables->hide('i_mutasi_saldoawal');
        $datatables->hide('i_ic');
        $datatables->hide('selisih3');
        return $datatables->generate();
    }

    /**** List Mutasi ***/
    public function get_data_detail($i_product, $i_company, $dfrom, $dto, $i_store)
    {
        $i_periode = date('Ym', strtotime($dfrom));
        $datefrom = date('Y-m-d', strtotime($dfrom));
        $dateto = date('Y-m-d', strtotime($dto));
        $datejangkafrom = date('Y-m-01', strtotime($dfrom));
        $datejangkato = date('Y-m-d', strtotime('-1 days', strtotime($dfrom)));
        if ($datejangkafrom != $datefrom) {
            $tanggalsadldo = $datejangkafrom;
        } else {
            $tanggalsadldo = $datefrom;
        }
        if ($datejangkafrom == $datefrom) {
            $datejangkafrom = '9999-09-09';
            $datejangkato = '9999-09-29';
        }

        return $this->db->query("SELECT
                    i_refference_id,
                    tanggal,
                    e_customer_name,
                    debet,
                    credit,
                    belance
                from f_cab_mut_stok_det('$i_product',$this->i_company,'$i_periode','$datefrom','$dateto','$datejangkafrom','$datejangkato','$tanggalsadldo','$i_store')
        ", FALSE);
    }

    /** Get Data Untuk Export */
    public function get_data($dfrom, $dto, $i_store)
    {
        $i_periode = date('Ym', strtotime($dfrom));
        $datefrom = date('Y-m-d', strtotime($dfrom));
        $dateto = date('Y-m-d', strtotime($dto));
        $datejangkafrom = date('Y-m-01', strtotime($dfrom));
        $datejangkato = date('Y-m-d', strtotime('-1 days', strtotime($dfrom)));
        if ($datejangkafrom == $datefrom) {
            $datejangkafrom = '9999-09-09';
            $datejangkato = '9999-09-29';
        }

        $i_periode2 = date('Ym', strtotime('+1 month', strtotime($dfrom)));

        return $this->db->query("SELECT
                    a.e_product_name as id,
                    a.i_company,
                    a.i_product,
                    a.i_product_id,
                    a.e_product_name,
                    n_saldo_akhir,
                    i_mutasi_saldoawal,
                    n_saldo_awal,
                    case when n_saldo_awal - n_saldo_akhir <> 0 then 'SALDO BEDA' ELSE 'OK' end as selisih2,
                    n_saldo_awal - n_saldo_akhir as selisih3,
                    i_ic,
                    n_quantity_stock,
                    n_quantity_stock - n_saldo_akhir as selisih,
                    '$dfrom' AS dfrom,
                    '$dto' AS dto,
                    '$i_store' AS i_store
                from
                    tr_product a
                left join (SELECT
                    i_product,
                    n_saldo_akhir
                FROM f_cab_mut_stok('$i_store',$this->i_company,'$i_periode','$datefrom','$dateto','$datejangkafrom','$datejangkato')) as b on (b.i_product=a.i_product)
                left join (SELECT i_ic, i_product, n_quantity_stock from tm_ic ic inner join tr_store st on (st.i_store=ic.i_store)
                where ic.i_company = $this->i_company and st.i_store ='$i_store') c on (c.i_product=a.i_product)
                left join (SELECT
                    i_mutasi_saldoawal,
                    i_product,
                    n_saldo_awal 
                from
                    tm_mutasi_saldoawal 
                where i_periode = '$i_periode2' and i_company = $this->i_company and i_store='$i_store') as cc on (cc.i_product=a.i_product)
                where a.i_company = $this->i_company
                order by 13 desc
        ", FALSE);
    }


    public function get_store($cari)
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
        AND f_store_pusat = 'f'
    ORDER BY 3 ASC
        ", FALSE);
    }


    /** Simpan Data */
    public function save()
    {
        $dfrom = $this->input->post('d_from2');
        $dto = $this->input->post('d_to2');
        $i_periode = formatYM($dfrom);
        $datefrom = date('Y-m-d', strtotime($dfrom));
        $dateto = date('Y-m-d', strtotime($dto));
        $datejangkafrom = date('Y-m-01', strtotime($dfrom));
        $datejangkato = date('Y-m-d', strtotime('-1 days', strtotime($dfrom)));
        if ($datejangkafrom != $datefrom) {
            $tanggalsadldo = $datejangkafrom;
        } else {
            $tanggalsadldo = $datefrom;
        }
        if ($datejangkafrom == $datefrom) {
            $datejangkafrom = '9999-09-09';
            $datejangkato = '9999-09-29';
        }

        $i_store = $this->input->post('i_store2', TRUE);

        $query = $this->db->query("SELECT max(i_mp)+1 AS id FROM tz_mp", TRUE);
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

        $i_document = $this->running_number(formatym2($dfrom), formatY($dfrom));


        $table = array(
            'i_company'         => $this->i_company,
            'i_mp'              => $id,
            'i_mp_id'           => $i_document,
            'd_mp'              => current_datetime(),
            'i_store'           => $i_store,
            'i_periode'         => $i_periode,
        );
        $this->db->insert('tz_mp', $table);


        $SQL = $this->db->query("SELECT
                i_company,
                i_product,
                i_product_id,
                e_product_name,
                n_saldo_awal,
                n_pinjaman,
                n_penjualan,
                n_q,
                n_adj,
                n_saldo_akhir,
                n_stockopname,
                n_selisi
            from
                f_cab_mut_stok('$i_store',$this->i_company,'$i_periode','$datefrom','$dateto','$datejangkafrom','$datejangkato')
            order by
                3");

        if ($SQL->num_rows() > 0) {
            foreach ($SQL->result() as $key) {
                $query1 = $this->db->query("SELECT max(i_mp_item)+1 AS id FROM tz_mp_item", TRUE);
                if ($query1->num_rows() > 0) {
                    $ids = $query1->row()->id;
                    if ($ids == null) {
                        $ids = 1;
                    } else {
                        $ids = $ids;
                    }
                } else {
                    $ids = 1;
                }
                $table = array(
                    'i_mp_item'         => $ids,
                    'i_mp'              => $id,
                    'i_product'         => $key->i_product,
                    'e_product_name'    => $key->e_product_name,
                    'n_saldo_awal'      => $key->n_saldo_awal,
                    'n_beli'            => $key->n_pinjaman,
                    // 'n_r_beli'          => 0,
                    // 'n_r_cab'           => 0,
                    // 'n_kon_in'          => 0,
                    'n_jual'            => $key->n_penjualan,
                    'n_sjp'             => $key->n_q,
                    // 'n_bbk'             => 0,
                    // 'n_kon_out'         => 0,
                    'n_adj'             => $key->n_adj,
                    'n_saldo_akhir'     => $key->n_saldo_akhir,
                    'n_so'              => $key->n_stockopname,
                    'n_selisih'         => $key->n_selisi,
                    // 'n_git'             => 0,
                );
                $this->db->insert('tz_mp_item', $table);

                $SQL2 = $this->db->query("SELECT
                        i_refference_id,
                        tanggal,
                        e_customer_name,
                        debet,
                        credit,
                        belance
                    from
                        f_cab_mut_stok_det('$key->i_product',$this->i_company,'$i_periode','$datefrom','$dateto','$datejangkafrom','$datejangkato','$tanggalsadldo','$i_store')
                    order by
                        3");

                if ($SQL2->num_rows() > 0) {
                    foreach ($SQL2->result() as $key2) {
                        $table = array(
                            'i_mp_item'         => $ids,
                            'i_mp'              => $id,
                            'e_ref_1'           => $key2->i_refference_id,
                            // 'e_ref_2'           => $key2->i_refference_id2,
                            'd_ref'             => $key2->tanggal,
                            'e_remark'          => $key2->e_customer_name,
                            'n_in'              => $key2->debet,
                            'n_out'             => $key2->credit,
                            'n_s'               => $key2->belance,
                            // 'n_git_d'           => $key2->git,
                        );
                        $this->db->insert('tz_mp_item_det', $table);
                    }
                }
            }
        }
    }


    /** Running Number Dokumen*/
    public function running_number($thbl, $tahun)
    {
        $cek = $this->db->query("SELECT 
                substring(i_mp_id, 1, 2) AS kode 
            FROM tz_mp 
            WHERE i_company = '$this->i_company'
            and substring(i_mp_id, 1, 2) like '%MC%'
            ORDER BY i_mp_id DESC");
        if ($cek->num_rows() > 0) {
            $kode = $cek->row()->kode;
        } else {
            $kode = 'MC';
        }
        $query  = $this->db->query("SELECT
                max(substring(i_mp_id, 9, 6)) AS max
            FROM
                tz_mp
            WHERE to_char (d_mp, 'yyyy') >= '$tahun'
            AND i_company = '$this->i_company'
            AND substring(i_mp_id, 1, 2) = '$kode'
            AND substring(i_mp_id, 4, 2) = substring('$thbl',1,2)
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
}

/* End of file Mmaster.php */
