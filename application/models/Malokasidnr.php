<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Malokasidnr extends CI_Model
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
        $datatables->query("SELECT
                d_alokasi as tgl,
                i_alokasi AS id,
                d_alokasi,
                i_alokasi_id,
                to_char(d_alokasi, 'YYYYMM') as i_periode,
                b.e_supplier_name AS e_supplier_name,
                e_supplier_groupname,
                g.i_dn_id,
                v_jumlah::money AS v_jumlah,
                v_lebih::money AS v_lebih ,
                f_alokasi_cancel AS f_status,
                '$dfrom' AS dfrom,
                '$dto' AS dto,
                '$i_supplier' AS i_supplier
            FROM
                tm_alokasidn a
            INNER JOIN tr_supplier b ON (b.i_supplier = a.i_supplier)            
            inner join tr_supplier_group c on (b.i_supplier_group = c.i_supplier_group)
            inner join tm_dn g on (g.i_dn=a.i_dn)
            WHERE
                a.i_company = '$this->i_company'
                AND a.d_alokasi BETWEEN '$dfrom' AND '$dto'
                $supplier
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
            $dfrom      = $data['dfrom'];
            $dto        = $data['dto'];
            $i_supplier   = $data['i_supplier'];
            $v_jumlah   = $data['v_jumlah'];
            $i_periode  = $data['i_periode'];
            $data       = '';
            $data      .= "<a href='" . base_url() . $this->folder . '/view/' . encrypt_url($id) . '/' . encrypt_url($dfrom) . '/' . encrypt_url($dto) . '/' . encrypt_url($i_supplier) . "' title='View Data'><i class='fa fa-eye fa-lg warning darken-4 mr-1'></i></a>";
            if ($i_periode >= get_periode()) {
                if (check_role($this->id_menu, 3) && $f_status == 'f') {
                    $data      .= "<a href='" . base_url() . $this->folder . '/edit/' . encrypt_url($id)  . '/' .  encrypt_url($dfrom) . '/' . encrypt_url($dto) . '/' . encrypt_url($i_supplier) . "' title='Edit Data'><i class='fa fa-pencil success darken-4 fa-lg mr-1'></i></a>";
                }
                if (check_role($this->id_menu, 4) && $f_status == 'f') {
                    $data      .= "<a href='#' onclick='sweetdeletev333raya(\"" . $this->folder . "\",\"" . $id . "\",\"" . encrypt_url($dfrom)  . "\",\"" . encrypt_url($dto)  . '/' . encrypt_url($i_supplier) . "\");' title='Delete Data'><i class='fa fa-trash fa-lg red darken-4'></i></a>";
                }
            }
            return $data;
        });
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('i_supplier');
        $datatables->hide('i_periode');
        $datatables->hide('id');
        return $datatables->generate();
    }

    /** List Datatable */
    public function serversidex()
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
        $datatables->query("SELECT
                a.d_dn as tgl,
                to_char(a.d_dn, 'YYYYMM') as i_periode,
                a.i_dn AS id,
                a.d_dn,
                a.i_dn_id,
                e.e_supplier_name AS e_supplier_name,
                f.e_supplier_groupname,
                a.v_netto::money AS v_netto,
                a.v_sisa::money AS v_sisa,
                '$dfrom' AS dfrom,
                '$dto' AS dto,
                '$i_supplier' AS i_supplier
            FROM
                tm_dn a
            INNER JOIN tr_supplier e ON (e.i_supplier = a.i_supplier)            
            inner join tr_supplier_group f on (e.i_supplier_group = f.i_supplier_group)
            WHERE
                a.i_company = '$this->i_company'
                AND a.d_dn BETWEEN '$dfrom' AND '$dto'
                AND a.f_dn_cancel = 'f'
                AND a.v_sisa > 0
                $supplier
            ORDER BY
                1 ASC
        ", FALSE);

        /** Cek Hak Akses, Apakah User Bisa Edit */
        $datatables->add('action', function ($data) {
            $id         = trim($data['id']);
            $i_periode  = $data['i_periode'];
            $dfrom      = $data['dfrom'];
            $dto        = $data['dto'];
            $i_supplier     = $data['i_supplier'];
            $data       = '';
            // if ($i_periode >= get_periode()) {
            if (check_role($this->id_menu, 1)) {
                $data      .= "<a href='" . base_url() . $this->folder . '/add/' . encrypt_url($id) . '/' . encrypt_url($dfrom) . '/' . encrypt_url($dto) . '/' . encrypt_url($i_supplier) . "' title='Tambah Data'><i class='fa fa-check-circle-o success darken-4 fa-lg mr-1'></i></a>";
            }
            // }
            return $data;
        });
        $datatables->hide('id');
        $datatables->hide('i_periode');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('i_supplier');
        return $datatables->generate();
    }



    /** Get Supplier */
    public function get_supplier0($cari)
    {
        return $this->db->query("SELECT distinct
                xx.i_supplier, i_supplier_id , e_supplier_name AS e_supplier_name
            FROM 
                tr_supplier xx
            inner join tm_bbr yy on (xx.i_supplier =yy.i_supplier)
            WHERE 
                (e_supplier_name ILIKE '%$cari%' OR i_supplier_id ILIKE '%$cari%')
                AND xx.i_company = '$this->i_company' 
            ORDER BY 3 ASC
        ", FALSE);
    }

    /** Running Number Dokumen*/
    public function running_number($thbl, $tahun)
    {
        $cek = $this->db->query("SELECT 
                substring(i_alokasi_id, 1, 3) AS kode 
            FROM tm_alokasidn
            WHERE i_company = '$this->i_company'
            ORDER BY i_alokasi DESC");
        if ($cek->num_rows() > 0) {
            $kode = $cek->row()->kode;
        } else {
            $kode = 'ADR';
        }
        $query  = $this->db->query("SELECT
                max(substring(i_alokasi_id, 10, 6)) AS max
            FROM
                tm_alokasidn
            WHERE to_char (d_alokasi, 'yyyy') >= '$tahun'
            AND i_company = '$this->i_company'
            AND substring(i_alokasi_id, 1, 3) = '$kode'
            AND substring(i_alokasi_id, 5, 2) = substring('$thbl',1,2)
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

    /** Get Data KN */
    public function get_data_dn($id)
    {
        return $this->db->query("SELECT 
                a.*,
                b.e_supplier_name ,
                d.e_supplier_groupname ,
                a.d_dn as d_bukti
            FROM
                tm_dn a 
            inner join tr_supplier b on (b.i_supplier=a.i_supplier)
            inner join tr_supplier_group d on (b.i_supplier_group = d.i_supplier_group)
            WHERE a.i_dn = '$id'
        ", FALSE);
    }

    /** Get Nota */
    public function get_nota($cari, $i_supplier)
    {
        return $this->db->query("SELECT
                i_nota,
                i_nota_id,
                to_char(d_nota, 'DD FMMonth YYYY') AS d_nota
            FROM
                tm_nota_pembelian
            WHERE
                f_nota_cancel = 'f'
                AND (i_nota_id ILIKE '%$cari%')
                AND i_supplier = '$i_supplier'
                AND i_company = '$this->i_company'
                AND v_sisa > 0
            ORDER BY
                i_nota ASC 
        ", FALSE);
    }

    /** Get Nota Detail */
    public function get_detail_nota($i_nota)
    {
        return $this->db->query("SELECT
                to_char(d_nota, 'DD FMMonth YYYY') AS dnota,
                d_nota,
                v_nota_netto AS v_jumlah,
                v_sisa,
                i_supplier
            FROM
                tm_nota_pembelian 
            WHERE
                i_nota = '$i_nota'
        ", FALSE);
    }

    /** Simpan Data */
    public function save()
    {
        $query = $this->db->query("SELECT max(i_alokasi)+1 AS id FROM tm_alokasidn", TRUE);
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

        $ym = date('ym', strtotime($this->input->post('d_document')));
        $Y = date('Y', strtotime($this->input->post('d_document')));
        $i_alokasi_id = $this->running_number($ym, $Y);
        $i_dn = $this->input->post('i_dn');
        $table = array(
            'i_company'     => $this->i_company,
            'i_alokasi'     => $id,
            'i_alokasi_id'  => $i_alokasi_id,
            'd_alokasi'     => $this->input->post('d_document'),
            'i_dn'          => $i_dn,
            'i_supplier'    => $this->input->post('i_supplier'),
            'v_jumlah'      => str_replace(",", "", $this->input->post('v_jumlah')),
            'v_lebih'       => str_replace(",", "", $this->input->post('v_lebih')),
            'd_entry'       => current_datetime(),
        );
        $this->db->insert('tm_alokasidn', $table);
        if (is_array($this->input->post('i_nota')) || is_object($this->input->post('i_nota'))) {
            $i = 0;
            foreach ($this->input->post('i_nota') as $i_nota) {
                if ($i_nota != '' || $i_nota != null) {
                    $v_jumlah = str_replace(",", "", $this->input->post('vjumlah')[$i]);
                    $item = array(
                        'i_company'        => $this->i_company,
                        'i_alokasi'        => $id,
                        'i_supplier'       => $this->input->post('i_supp')[$i],
                        'i_nota'           => $i_nota,
                        'd_nota'           => $this->input->post('d_nota')[$i],
                        'v_jumlah'         => $v_jumlah,
                        'v_sisa'           => str_replace(",", "", $this->input->post('vsesa')[$i]),
                        'n_item_no'        => $i,
                        'e_remark'         => $this->input->post('eremark')[$i],
                    );
                    $this->db->insert('tm_alokasidn_item', $item);
                    $i++;

                    $this->db->query("UPDATE tm_nota_pembelian SET v_sisa = v_sisa - '$v_jumlah' WHERE i_nota = '$i_nota' ", FALSE);
                    $this->db->query("UPDATE tm_dn SET v_sisa = v_sisa - '$v_jumlah' WHERE i_dn = '$i_dn' ", FALSE);
                }
            }
        } else {
            die;
        }
    }

    /** Get Data Untuk Edit */
    public function get_data($id)
    {
        return $this->db->query("SELECT 
                a.*,
                b.e_supplier_name ,
                d.e_supplier_groupname ,
                a.d_alokasi as d_bukti,
                e.i_dn_id,
                e.d_dn
            FROM 
                tm_alokasidn a
            inner join tr_supplier b on (b.i_supplier=a.i_supplier)
            inner join tr_supplier_group d on (b.i_supplier_group = d.i_supplier_group)
            inner join tm_dn e on (e.i_dn=a.i_dn)
            WHERE i_alokasi = '$id'     
        ", FALSE);
    }

    /** Get Data Untuk Edit Detail */
    public function get_data_detail($id)
    {
        return $this->db->query("SELECT
                a.i_nota,
                b.i_nota_id,
                to_char(b.d_nota, 'DD FMMonth YYYY') AS dnota,
                b.d_nota,
                a.v_jumlah,
                a.v_sisa,
                a.e_remark,
                b.v_sisa + a.v_jumlah AS v_nota
            FROM
                tm_alokasidn_item a
            INNER JOIN tm_nota_pembelian b ON
                (b.i_nota = a.i_nota)
            WHERE
                i_alokasi = '$id'
            ORDER BY
                n_item_no ASC
        ", FALSE);
    }


    public function update_item($id)
    {
        $query = $this->db->query("SELECT i_nota, i_dn, a.v_jumlah FROM tm_alokasidn_item a, tm_alokasidn b WHERE a.i_alokasi = b.i_alokasi AND b.i_alokasi = '$id' ", FALSE);
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $key) {
                $this->db->query("UPDATE tm_nota_pembelian SET v_sisa = v_sisa + '$key->v_jumlah' WHERE i_nota = '$key->i_nota' ", FALSE);
                $this->db->query("UPDATE tm_dn SET v_sisa = v_sisa + '$key->v_jumlah' WHERE i_dn = '$key->i_dn' ", FALSE);
            }
        }
    }

    /** Cancel Data */
    public function cancel($id,$alasan,$i_alokasi_id)
    {
        $table = array(
            'f_alokasi_cancel' => 't',
        );
        $this->db->where('i_alokasi', $id);
        $this->db->update('tm_alokasidn', $table);
        $this->update_item($id);

        $this->db->query("INSERT INTO tx_del (i_company, i_user, e_user_name, e_dokumen, e_alasan)
        VALUES ('$this->i_company', '$this->i_user', '$this->e_user_name', 'No ALOKASI DEBET NOTA : $i_alokasi_id', '$alasan')");

    }
}

/* End of file Mmaster.php */
