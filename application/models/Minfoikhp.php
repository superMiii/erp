<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Minfoikhp extends CI_Model
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

        $i_area = $this->input->post('i_area', TRUE);
        if ($i_area == '') {
            $i_area = $this->uri->segment(5);
            if ($i_area == '') {
                $i_area = 0;
            }
        }

        $tanggal_saldo  = date('Y-m-01', strtotime($dfrom));
        $i_periode  = date('Ym', strtotime($dfrom));
        $dfrom  = date('Y-m-d', strtotime($dfrom));
        $dto    = date('Y-m-d', strtotime($dto));

        $dtod = date('Y-m-t', strtotime($dto));
        $dtotod = date('Y-m-01', strtotime('+1 month', strtotime(date('Y-m', strtotime($dtod)))));


        
        $ka         = date('m', strtotime($dfrom));
        $de         = date('m', strtotime($dto));


        // if ($ka == $de){

        if ($dto == $dtod) {
            $this->db->query(
                "INSERT INTO public.tm_saldo_ikhp
                (i_company, d_ikhp, i_area, v_saldo_tunai, v_saldo_giro, d_entry)
                SELECT $this->i_company, '$dtotod', $i_area, saldo_akhir_tunai, saldo_akhir_giro, now() from	(
                    select
                    ROW_NUMBER () OVER (ORDER BY tanggal),
                    *
                    from  ikhp5 ($this->i_company,$i_area,'$dfrom','$dto')order by 1 desc limit 1 ) t	  
                ON CONFLICT (i_company, d_ikhp, i_area) DO UPDATE 
                SET v_saldo_tunai = excluded.v_saldo_tunai, v_saldo_giro = excluded.v_saldo_giro, d_update = now()                        
                "
            );
        }

        // }

        return $this->db->query("SELECT * from ikhp5 ($this->i_company,$i_area,'$dfrom','$dto');            
        ", FALSE);
    }

    
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

    /** Get Data Untuk Export */
    public function serverside2($dfrom, $dto, $i_area)
    {
        if ($dfrom == '') {
            $dfrom = $this->uri->segment(3);
        }

        if ($dto == '') {
            $dto = $this->uri->segment(4);
        }

        if ($i_area == '') {
            $i_area = $this->uri->segment(5);
        }

        $tanggal_saldo  = date('Y-m-01', strtotime($dfrom));
        $i_periode  = date('Ym', strtotime($dfrom));
        $dfrom  = date('Y-m-d', strtotime($dfrom));
        $dto    = date('Y-m-d', strtotime($dto));
        return $this->db->query("SELECT * from ikhp2 ($this->i_company,$i_area,'$dfrom','$dto');            
        ", FALSE);
                }
}

/* End of file Mmaster.php */
