<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mpelanggan extends CI_Model
{

    /** List Datatable */
    public function serverside()
    {
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("SELECT 
            a.i_customer, a.i_customer_id, a.e_customer_name,  b.e_area_name , e_city_name, e_customer_address,
            coalesce(n_customer_discount1, 0) || '%' as n_customer_discount1, 
            coalesce(n_customer_discount2, 0) || '%' as n_customer_discount2, 
            e_customer_owner, e_customer_phone,e_customer_npwpcode,e_ktp_owner,
            e.e_customer_statusname,
            d.e_price_groupname,
            n_customer_top,
            case
                when a.f_top30 = 't' then '30 Hari'
                else n_customer_top || ' Hari'
            end as top30,
            plafon::money as pla , a.f_customer_active,
            case
                when a.e_approve notnull then 'Disetujui : ' || e_approve
                else 'Belum Disetujui'
            end as acc
            from tr_customer a
            left join tr_area b on (a.i_area = b.i_area)
            left join tr_city c on (a.i_city = c.i_city)
            inner join tr_price_group d on (d.i_price_group = a.i_price_group)
            inner join tr_customer_status e on (e.i_customer_status=a.i_customer_status)
            where a.i_company = '$this->i_company' order by a.i_customer desc
        ", FALSE);

        $datatables->edit('f_customer_active', function ($data) {
            $id         = $data['i_customer'];
            if ($data['f_customer_active'] == 't') {
                $status = $this->lang->line('Aktif');
                $color  = 'success';
            } else {
                $status = $this->lang->line('Tidak Aktif');
                $color  = 'danger';
            }
            $data = "<button class='btn btn-outline-" . $color . " btn-sm round' onclick='changestatus(\"" . $this->folder . "\",\"" . $id . "\");'>" . $status . "</button>";
            return $data;
        });

        /** Cek Hak Akses, Apakah User Bisa Edit */
        if (check_role($this->id_menu, 3)) {
            $datatables->add('action', function ($data) {
                $id         = trim($data['i_customer']);
                $data       = '';
                $data      .= "<a href='" . base_url() . $this->folder . '/edit/' . encrypt_url($id) . "' title='Edit Data'><i class='fa fa-pencil-square success darken-4 fa-lg mr-1'></i></a>";
                return $data;
            });
        }
        //$datatables->hide();
        return $datatables->generate();
    }

    public function changestatus($id)
    {
        $this->db->select('f_customer_active');
        $this->db->from('tr_customer');
        $this->db->where('i_customer', $id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $status = $query->row()->f_customer_active;
        } else {
            $status = 'f';
        }
        if ($status == 'f') {
            $fstatus = 't';
        } else {
            $fstatus = 'f';
        }
        $table = array(
            'f_customer_active' => $fstatus,
        );
        $this->db->where('i_customer', $id);
        $this->db->update('tr_customer', $table);
    }

    /** Cek Apakah Data Sudah Ada Pas Simpan */
    public function cek($kode)
    {
        return $this->db->query("SELECT 
                i_customer_id
            FROM 
                tr_customer
            WHERE 
                trim(upper(i_customer_id)) = trim(upper('$kode'))
                AND i_company = '$this->i_company'
        ", FALSE);
    }

    public function get_area($cari)
    {
        return $this->db->query("SELECT 
                i_area , i_area_id , e_area_name 
            FROM 
                tr_area tsg 
            WHERE 
                (e_area_name ILIKE '%$cari%' or i_area_id ILIKE '%$cari%')
                and i_company = '$this->i_company' and f_area_active = true
            ORDER BY 3 ASC
        ", FALSE);
    }


    public function get_city($cari, $param1)
    {
        return $this->db->query("SELECT 
                i_city , i_city_id , e_city_name 
            FROM 
                tr_city tsg 
            WHERE 
                (e_city_name ILIKE '%$cari%' or i_city_id ILIKE '%$cari%')
                and i_company = '$this->i_company' and f_city_active = true and i_area = '$param1'
            ORDER BY 3 ASC
        ", FALSE);
    }

    public function get_cover($cari, $param1, $param2)
    {
        return $this->db->query("SELECT 
                a.i_area_cover , i_area_cover_id , e_area_cover_name 
            FROM 
                tr_area_cover a
            INNER JOIN tr_area_cover_item b ON
                (b.i_area_cover = a.i_area_cover)
            WHERE 
                (e_area_cover_name ILIKE '%$cari%' or i_area_cover_id ILIKE '%$cari%')
                and i_company = '$this->i_company' and f_area_cover_active = true and i_area = '$param1' and i_city = '$param2'
            ORDER BY 3 ASC
        ", FALSE);
    }

    public function get_group($cari)
    {
        return $this->db->query("SELECT 
                i_customer_group , i_customer_groupid , e_customer_groupname 
            FROM 
                tr_customer_group tsg 
            WHERE 
                (e_customer_groupname ILIKE '%$cari%' or i_customer_groupid ILIKE '%$cari%')
                and i_company = '$this->i_company' and f_customer_groupactive = true
            ORDER BY 3 ASC
        ", FALSE);
    }

    public function get_type($cari)
    {
        return $this->db->query("SELECT 
                i_customer_type , i_customer_typeid , e_customer_typename 
            FROM 
                tr_customer_type tsg 
            WHERE 
                (e_customer_typename ILIKE '%$cari%' or i_customer_typeid ILIKE '%$cari%')
                and i_company = '$this->i_company' and f_customer_typeactive = true
            ORDER BY 3 ASC
        ", FALSE);
    }

    public function get_level($cari)
    {
        return $this->db->query("SELECT 
                i_customer_level , i_customer_levelid , e_customer_levelname 
            FROM 
                tr_customer_level tsg 
            WHERE 
                (e_customer_levelname ILIKE '%$cari%' or i_customer_levelid ILIKE '%$cari%')
                and i_company = '$this->i_company' and f_customer_levelactive = true
            ORDER BY 3 ASC
        ", FALSE);
    }

    public function get_status($cari)
    {
        return $this->db->query("SELECT 
                i_customer_status , i_customer_statusid , e_customer_statusname 
            FROM 
                tr_customer_status tsg 
            WHERE 
                (e_customer_statusname ILIKE '%$cari%' or i_customer_statusid ILIKE '%$cari%')
                and i_company = '$this->i_company' and f_customer_statusactive = true
                and f_status_awal = true
            ORDER BY 3 ASC
        ", FALSE);
    }

    public function get_price($cari)
    {
        return $this->db->query("SELECT 
                i_price_group , i_price_groupid , e_price_groupname 
            FROM 
                tr_price_group tsg 
            WHERE 
                (e_price_groupname ILIKE '%$cari%' or i_price_groupid ILIKE '%$cari%')
                and i_company = '$this->i_company' and f_price_groupactive = true
            ORDER BY 3 ASC
        ", FALSE);
    }

    public function get_payment($cari)
    {
        return $this->db->query("SELECT 
                i_customer_payment , i_customer_paymentid , e_customer_paymentname 
            FROM 
                tr_customer_payment tsg 
            WHERE 
                (e_customer_paymentname ILIKE '%$cari%' or i_customer_paymentid ILIKE '%$cari%')
                and i_company = '$this->i_company' and f_customer_paymentactive = true
            ORDER BY 3 ASC
        ", FALSE);
    }

    public function get_paygroup($cari)
    {
        return $this->db->query("SELECT 
                i_customer_paygroup , i_customer_paygroupid , e_customer_paygroupname 
            FROM 
                tr_customer_paygroup tsg 
            WHERE 
                (e_customer_paygroupname ILIKE '%$cari%' or i_customer_paygroupid ILIKE '%$cari%')
                and i_company = '$this->i_company' and f_customer_paygroupactive = true
            ORDER BY 3 ASC
        ", FALSE);
    }

    public function insert_groupbayar($kode, $nama)
    {
        $cek = $this->db->query("SELECT i_customer_paygroup FROM tr_customer_paygroup where i_customer_paygroupid = '$kode' and i_company = '$this->i_company'
        ", FALSE);

        if ($cek->num_rows() > 0) {
            $i_customer_paygroup = $cek->row()->i_customer_paygroup;
        } else {
            $i_customer_paygroup = $this->db->query("
               SELECT coalesce(max(i_customer_paygroup),0)::int + 1 as i_customer_paygroup FROM tr_customer_paygroup
            ", FALSE)->row()->i_customer_paygroup;

            $this->db->query("
                INSERT INTO tr_customer_paygroup (i_customer_paygroup, i_company,i_customer_paygroupid,e_customer_paygroupname,v_flafon,v_credit,f_customer_paygroupactive,d_customer_paygroupentry) VALUES
                ('$i_customer_paygroup','$this->i_company','$kode','$nama',0.00,0.00,true,now());
            ", FALSE);
        }
        return $i_customer_paygroup;
    }

    /** Simpan Data */
    public function save(
        $iarea,
        $icity,
        $icover,
        $kode,
        $nama,
        $alamat,
        $pemilik,
        $telepon,
        $diskon,
        $npwp_kode,
        $npwp_nama,
        $npwp_alamat,
        $igroup,
        $itype,
        $ilevel,
        $istatus,
        $iprice,
        $ipayment,
        $diskon2,
        $diskon3,
        $ppn,
        $tanggal,
        $top,
        $e_pic_name,
        $e_pic_phone,
        $e_ktp_owner,
        $e_shipment_address,
        $n_building_m2,
        $e_competitor,
        $d_start,
        $id,
        $e_ekspedisi_cus,
        $e_ekspedisi_bayar,
        $f_pareto
    ) {
        $n_building_m2 = ($n_building_m2 == '') ? null : $n_building_m2;
        $d_start = ($d_start == '') ? null : $d_start;
        $table = array(
            'i_company' => $this->i_company,
            'i_customer' => $id,
            'i_customer_id' => $kode,
            'e_customer_name' => $nama,
            'e_customer_address' => $alamat,
            'n_customer_discount1' => $diskon,
            'n_customer_discount2' => $diskon2,
            'n_customer_discount3' => $diskon3,
            'e_customer_phone' => $telepon,
            'e_customer_owner' => $pemilik,
            'e_customer_npwpcode' => $npwp_kode,
            'e_customer_npwpname' => $npwp_nama,
            'e_customer_npwpaddress' => $npwp_alamat,
            'i_area' => $iarea,
            'i_city' => $icity,
            'i_area_cover' => $icover,
            'i_price_group' => $iprice,
            'i_customer_group' => $igroup,
            'i_customer_payment' => $ipayment,
            'i_customer_type' => $itype,
            'i_customer_level' => $ilevel,
            'i_customer_status' => $istatus,
            'n_customer_top' => $top,
            'f_customer_plusppn' => $ppn,
            'd_customer_register' => $tanggal,
            'e_pic_name' => $e_pic_name,
            'e_pic_phone' => $e_pic_phone,
            'e_ktp_owner' => $e_ktp_owner,
            'e_shipment_address' => $e_shipment_address,
            'n_building_m2' => $n_building_m2,
            'e_competitor' => $e_competitor,
            'd_start' => $d_start,
            'd_customer_entry'  => date('Y-m-d H:i:s'),
            'd_approve'  => date('Y-m-d H:i:s'),
            'e_approve' => $this->e_user_name,
            'e_ekspedisi_cus' => $e_ekspedisi_cus,
            'e_ekspedisi_bayar' => $e_ekspedisi_bayar,
            'f_pareto' => $f_pareto,
        );
        $this->db->insert('tr_customer', $table);
    }

    /** Get Data Untuk Edit */
    public function getdata($id)
    {
        return $this->db->query("
            select a.*, b.e_area_name , c.e_city_name , d.e_area_cover_name , e.e_price_groupname, f.e_customer_groupname , 
            g.e_customer_paygroupname , h.e_customer_paymentname , i.e_customer_typename , j.e_customer_levelname , k.e_customer_statusname ,
            b.i_area_id , c.i_city_id , d.i_area_cover_id , e.i_price_groupid , f.i_customer_groupid , g.i_customer_paygroupid , 
            h.i_customer_paymentid , i.i_customer_typeid , j.i_customer_levelid , k.i_customer_statusid,
             n_customer_discount1  as disc1,
             n_customer_discount2  as disc2,
             n_customer_discount3  as disc3, to_char(a.d_customer_register, 'dd-mm-yyyy') as d_customer_register_edit, to_char(a.d_start, 'dd-mm-yyyy') as d_start_edit
            from tr_customer a
            left join tr_area b on (a.i_area = b.i_area)
            left join tr_city c on (a.i_city = c.i_city)
            left join tr_area_cover d on (a.i_area_cover = d.i_area_cover)
            left join tr_price_group e on (a.i_price_group = e.i_price_group)
            left join tr_customer_group f on (a.i_customer_group = f.i_customer_group)
            left join tr_customer_paygroup g on (a.i_customer_paygroup = g.i_customer_paygroup)
            left join tr_customer_payment h on (a.i_customer_payment = h.i_customer_payment)
            left join tr_customer_type i on (a.i_customer_type = i.i_customer_type)
            left join tr_customer_level j on (a.i_customer_level = j.i_customer_level)
            left join tr_customer_status k on (a.i_customer_status = k.i_customer_status)
            where a.i_customer = '$id'
        ", FALSE);
    }

    /** Cek Apakah Data Sudah Ada Pas Edit */
    public function cek_edit($kode, $kodeold)
    {
        return $this->db->query("
            SELECT 
                i_customer_id
            FROM 
                tr_customer 
            WHERE 
                trim(upper(i_customer_id)) <> trim(upper('$kodeold'))
                AND trim(upper(i_customer_id)) = trim(upper('$kode'))
                AND i_company = '$this->i_company'
        ", FALSE);
    }

    /** Update Data */
    public function update(
        $id,
        $iarea,
        $icity,
        $icover,
        $kode,
        $nama,
        $alamat,
        $pemilik,
        $telepon,
        $diskon,
        $npwp_kode,
        $npwp_nama,
        $npwp_alamat,
        $igroup,
        $itype,
        $ilevel,
        $iprice,
        $ipayment,
        $diskon2,
        $diskon3,
        $ppn,
        $tanggal,
        $top,
        $e_pic_name,
        $e_pic_phone,
        $e_ktp_owner,
        $e_shipment_address,
        $n_building_m2,
        $e_competitor,
        $d_start,
        $e_ekspedisi_cus,
        $e_ekspedisi_bayar,
        $f_pareto
    ) {
        $n_building_m2 = ($n_building_m2 == '') ? null : $n_building_m2;
        $d_start = ($d_start == '') ? null : $d_start;
        $table = array(
            'i_customer_id' => $kode,
            'e_customer_name' => $nama,
            'e_customer_address' => $alamat,
            'n_customer_discount1' => $diskon,
            'n_customer_discount2' => $diskon2,
            'n_customer_discount3' => $diskon3,
            'e_customer_phone' => $telepon,
            'e_customer_owner' => $pemilik,
            'e_customer_npwpcode' => $npwp_kode,
            'e_customer_npwpname' => $npwp_nama,
            'e_customer_npwpaddress' => $npwp_alamat,
            'i_area' => $iarea,
            'i_city' => $icity,
            'i_area_cover' => $icover,
            'i_price_group' => $iprice,
            'i_customer_group' => $igroup,
            'i_customer_payment' => $ipayment,
            'i_customer_type' => $itype,
            'i_customer_level' => $ilevel,
            'n_customer_top' => $top,
            'f_customer_plusppn' => $ppn,
            'd_customer_register' => $tanggal,
            'e_pic_name' => $e_pic_name,
            'e_pic_phone' => $e_pic_phone,
            'e_ktp_owner' => $e_ktp_owner,
            'e_shipment_address' => $e_shipment_address,
            'n_building_m2' => $n_building_m2,
            'e_competitor' => $e_competitor,
            'd_start' => $d_start,
            'd_customer_update'  => date('Y-m-d H:i:s'),
            'e_ekspedisi_cus' => $e_ekspedisi_cus,
            'e_ekspedisi_bayar' => $e_ekspedisi_bayar,
            'f_pareto' => $f_pareto,
        );
        $this->db->where('i_customer', $id);
        $this->db->update('tr_customer', $table);
    }
}

/* End of file Mmaster.php */
