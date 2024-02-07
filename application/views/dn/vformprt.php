<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=windows-1252" />
</head>

<body>
    <style type="text/css" media="all">
        /*
/*
@page land {size: landscape;}
@media print {
input.noPrint { display: none; }
}
@page
        {
            size: auto;   /* auto is the initial value 
            margin: 0mm;   this affects the margin in the printer settings 
        */
        * {
            size: landscape;
        }

        @page {
            /*size: Letter; */
            /*margin: 0mm;*/
            /* this affects the margin in the printer settings */
            margin: 0.03in 0.37in 0.07in 0.26in;
        }


        .huruf {
            FONT-FAMILY: Tahoma, Verdana, Arial, Helvetica, sans-serif;
        }

        .miring {
            font-style: italic;

        }

        .wrap {
            margin: 0 auto;
            text-align: left;
        }

        .ceKotak {
            background-color: #f0f0f0;
            border-bottom: #80c0e0 1px solid;
            border-top: #80c0e0 1px solid;
            border-left: #80c0e0 1px solid;
            border-right: #80c0e0 1px solid;
        }

        .garis {
            background-color: #000000;
            width: 100%;
            height: 50%;
            font-size: 100px;
            border-style: solid;
            border-width: 0.01px;
            border-collapse: collapse;
            spacing: 1px;
        }

        .garis td {
            background-color: #FFFFFF;
            border-style: solid;
            border-width: 0.01px;
            font-size: 10px;
            FONT-WEIGHT: normal;
            padding: 1px;
        }

        .garisy {
            background-color: #000000;
            width: 100%;
            height: 50%;
            border-style: solid;
            border-width: 0.01px;
            border-collapse: collapse;
            spacing: 1px;
        }

        .garisy td {
            background-color: #FFFFFF;
            border-style: solid;
            border-width: 0.01px;
            padding: 1px;
        }

        .garisx {
            background-color: #000000;
            width: 100%;
            height: 50%;
            border-style: none;
            border-collapse: collapse;
            spacing: 1px;
        }

        .garisx td {
            background-color: #FFFFFF;
            border-style: none;
            font-size: 10px;
            FONT-WEIGHT: normal;
            padding: 1px;
        }

        .judul {
            font-size: 18px;
            FONT-WEIGHT: normal;
        }

        .catatan {
            font-size: 14px;
            FONT-WEIGHT: normal;
        }

        .nmper {
            margin-top: 0;
            font-size: 11px;
            FONT-WEIGHT: normal;
        }

        .isi {
            font-size: 11px;
            font-weight: normal;
        }

        .eusinya {
            font-size: 8px;
            font-weight: normal;
        }

        .garisbawah {
            border-bottom: #000000 0.1px solid;
        }

        .garisatas {
            border-top: #000000 0.1px solid;
        }

        .gariskiri {
            border-left: #000000 0.1px solid;
        }

        .gariskanan {
            border-right: #000000 0.1px solid;
        }
    </style>
    <style type="text/css" media="print">
        .noDisplay {
            display: none;
        }

        .pagebreak {
            page-break-before: always;
        }
    </style>
    <table width="100%" class="nmper" border="0">
        <tr>
            <td colspan="3" style="width:50%"><?php echo $company->e_company_name; ?></td>
            <td>Tgl. :
                <?php
                $tmp = explode("-", $data->d_dn);
                $th = $tmp[0];
                $bl = $tmp[1];
                $hr = $tmp[2];
                $dkn = $hr . " " . substr(bulan($bl), 0, 3) . " " . $th;
                echo $dkn;
                ?>
            </td>
        </tr>
        <tr>
            <td colspan="3" class="huruf judul">D E B E T &nbsp; N O T A</td>
            <td>Kepada Yth.</td>
        </tr>
        <tr>
            <td colspan="3">No. : <?php echo $data->i_dn_id; ?></td>
            <td><?php echo $data->$data->e_supplier_name; ?></td>
        </tr>
        <tr>
            <td colspan="3">&nbsp;</td>
            <td><?php echo $data->e_supplier_groupname; ?></td>
        </tr>
        <tr>
            <td colspan="4">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="4">
                <table width="100%" class="nmper" border="0">
                    <tr>
                        <td class="gariskiri garisatas"><?php echo "Telah kami Kredit Rekening Saudara atas"; ?>
                        </td>
                        <td width="10px" class="gariskiri garisatas">&nbsp;</td>
                        <td class="gariskanan garisatas">&nbsp;</td>
                    </tr>
                    <tr>
                        <td class="gariskiri"><?php echo "Sesuai dengan BBM Nomor " . $data->i_bbm_id; ?>
                        </td>
                        <td width="10px" class="gariskiri">&nbsp;</td>
                        <td class="gariskanan ">&nbsp;</td>
                    </tr>
                    <tr>
                        <td class="gariskiri"><?php echo "Tgl. " . $dkn; ?>
                        </td>
                        <td width="10px" class="gariskiri">Rp.</td>
                        <td width="120px" align="right" class="gariskanan">
                            <?php echo number_format($data->v_netto); ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="gariskiri">&nbsp;</td>
                        <td width="10px" class="gariskiri">&nbsp;</td>
                        <td class="gariskanan">&nbsp;</td>
                    </tr>
                    <tr>
                        <td class="garisbawah gariskiri">&nbsp;</td>
                        <td class="gariskiri garisbawah" width="10px">&nbsp;</td>
                        <td class="gariskanan garisbawah">&nbsp;</td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td width="10px" class="gariskiri garisbawah">Rp.</td>
                        <td width="120px" align="right" class="gariskanan garisbawah">
                            <?php echo number_format($data->v_netto); ?>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="4">
                <table width="100%" class="nmper" border="0">
                    <tr>
                        <td colspan="3">Terbilang : <?php $kata = ucwords(terbilang($data->v_netto));
                                                    echo $kata . ' Rupiah' ?> </td>
                    </tr>
                    <tr>
                        <td colspan="3">&nbsp;</td>
                    </tr>
                    <tr>
                        <td align="center" style="width:50%">
                            Mengetahui
                        </td>

                        <td align="center" style="width:50%">
                            Hormat Kami,
                        </td>
                    </tr>
                    <tr align="center">
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr align="center">
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr align="center">
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td width="200px" align="center">(&nbsp;&nbsp;&nbsp;&nbsp; <?php echo ""; ?>
                            &nbsp;&nbsp;&nbsp;&nbsp;)</td>
                        <td align="center" width="200px" colspan="2">(&nbsp;&nbsp;&nbsp;&nbsp; <?php echo ""; ?>
                            &nbsp;&nbsp;&nbsp;&nbsp;)</td>
                    </tr>
                    <tr>
                        <td colspan="3">TANGGAL CETAK : <?php $tgl = date("d") . " " . bulan(date("m")) . " " . date("Y") . "  Jam : " . date("H:i:s");
                                                        echo $tgl; ?></td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="4">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="4">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="4">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="4">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="4">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="4">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="4">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="4">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="4">&nbsp;</td>
        </tr>
    </table>
    <?php
    $bbm  = $this->mymodel->get_data_referensi($data->i_refference);
    foreach ($bbm->result() as $rowbbm) { ?>
        <table width="100%" class="nmper" border="0">
            <tr>
                <td colspan="2"><?php echo $company->e_company_name; ?></td>
            </tr>
            <tr>
                <td style="width:64%">BUKTI BARANG MASUK</td>
                <td>No : <?php echo $data->i_bbm_id; ?></td>
            </tr>
            <tr>
                <td>( B B M ) - RETUR</td>
                <td>Tgl : <?php
                            $tmp = explode("-", $data->d_refference);
                            $th = $tmp[0];
                            $bl = $tmp[1];
                            $hr = $tmp[2];
                            $drefference = $hr . " " . substr(bulan($bl), 0, 3) . " " . $th;
                            echo $drefference; ?></td>
            </tr>
            <tr>
                <td colspan="2">Telah diterima dari : <?php echo $data->e_customer_name; ?></td>
            </tr>
            <tr>
                <td colspan="2">&nbsp;</td>
            </tr>
            <tr>
                <td colspan="2">Referensi &nbsp;&nbsp; : <?php echo $data->i_dn_id; ?></td>
            </tr>
            <tr>
                <td colspan="2">Keterangan : <?php echo $rowbbm->i_ttb_id; ?></td>
            </tr>
            <tr>
                <td colspan="2">&nbsp;</td>
            </tr>
            <tr>
                <td colspan="2">
                    <table width="100%" class="nmper" border="0">
                        <tr align="center">
                            <td width="33px" class="gariskiri garisatas garisbawah">
                                NO. URUT
                            </td>
                            <td width="75px" class="gariskiri garisatas garisbawah">
                                KODE BARANG
                            </td>
                            <td width="350px" class="gariskiri garisatas garisbawah">
                                NAMA BARANG
                            </td>
                            <td width="33px" class="gariskiri garisatas garisbawah">
                                Diskon1
                            </td>
                            <td width="33px" class="gariskiri garisatas garisbawah">
                                Diskon2
                            </td>
                            <td width="33px" class="gariskiri garisatas garisbawah">
                                Diskon3
                            </td>
                            <td width="75px" class="gariskiri garisatas garisbawah">
                                UNIT
                            </td>
                            <td width="75px" class="gariskiri garisatas garisbawah">
                                HARGA (PC)
                            </td>
                            <td colspan="2" width="100px" class="gariskiri gariskanan garisatas garisbawah">
                                NILAI
                            </td>
                        </tr>
                        <?php
                        $bbmi  = $this->mymodel->get_data_detail_referensi($data->i_refference);
                        $i = 0;
                        $totsub = 0;
                        $jdis = 0;
                        $jds = 0;
                        $dppp = 0;
                        $pppn = 0;
                        foreach ($bbmi->result() as $rowbbmi) {
                            $i++;
                            #			    $hrg	= $hrg+($rowi->n_order*$rowi->v_product_mill);
                        ?>
                            <tr>
                                <td width="25" class="gariskiri" align="center">
                                    <?php echo $i; ?>
                                </td>
                                <td class="gariskiri" align="center">
                                    <?php echo $rowbbmi->i_product_id; ?>
                                </td>
                                <td class="gariskiri" align="center">
                                    <?php
                                    if (strlen($rowbbmi->e_product_name) > 50) {
                                        $nam  = substr($rowbbmi->e_product_name, 0, 50);
                                    } else {
                                        $nam  = $rowbbmi->e_product_name . str_repeat(" ", 50 - strlen($rowbbmi->e_product_name));
                                    }
                                    echo $nam; ?>
                                </td>
                                <td class="gariskiri" align="center">
                                    <?php echo number_format($rowbbmi->n_ttb_discount1); ?>
                                </td>
                                <td class="gariskiri" align="center">
                                    <?php echo number_format($rowbbmi->n_ttb_discount2); ?>
                                </td>
                                <td class="gariskiri" align="center">
                                    <?php echo number_format($rowbbmi->n_ttb_discount3); ?>
                                </td>
                                <td class="gariskiri" align="center">
                                    <?php echo number_format($rowbbmi->n_quantity); ?>
                                </td>
                                <td class="gariskiri" width="75px" align="right">
                                    <?php echo number_format($rowbbmi->v_unit_price); ?>
                                </td>
                                <td align="right" colspan="2" class="gariskiri gariskanan">
                                    <?php
                                    $sub = $rowbbmi->n_quantity * $rowbbmi->v_unit_price;
                                    echo number_format($sub);
                                    $totsub = $totsub + $sub;
                                    $jds = $rowbbmi->n_ttb_discount1 + $rowbbmi->n_ttb_discount2 + $rowbbmi->n_ttb_discount3;
                                    $jdis += ($jds / 100) * $sub;
                                    $dppp = $totsub - $jdis;
                                    $pppn = $dppp * (($data->n_ppn_r) / 100);
                                    ?>
                                </td>
                            </tr>
                        <?php } ?>
                        <tr>
                            <td colspan="6" class="garisatas">&nbsp;</td>
                            <td colspan="2" class="garisatas">Jumlah</td>
                            <td width="10px" class="garisatas gariskiri">Rp.</td>
                            <td width="70px" class="garisatas gariskanan" align="right">
                                <?php echo number_format($totsub); ?></td>
                        </tr>
                        <tr>
                            <td colspan="6">&nbsp;</td>
                            <td colspan="2">Potongan</td>
                            <td width="10px" class="gariskiri">Rp.</td>
                            <td width="70px" class="gariskanan" align="right"><?php echo number_format($jdis); ?>
                            </td>
                        </tr>
                        <?php if ($data->f_ttb_plusppn == 't') { ?>
                            <tr>
                                <td colspan="6">&nbsp;</td>
                                <td colspan="2">DPP</td>
                                <td width="10px" class="gariskiri">Rp.</td>
                                <td width="70px" class="gariskanan" align="right"><?php echo number_format($dppp); ?>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="6">&nbsp;</td>
                                <td colspan="2">PPN (<?php echo number_format($data->n_ppn_r); ?> %)</td>
                                <td width="10px" class="gariskiri">Rp.</td>
                                <td width="70px" class="gariskanan" align="right"><?php echo number_format($pppn); ?>
                                </td>
                            </tr>
                        <?php } ?>
                        <tr>
                            <td colspan="6">&nbsp;</td>
                            <td colspan="2">Jumlah Bersih</td>
                            <td width="10px" class="gariskiri garisatas garisbawah">Rp.</td>
                            <td width="70px" align="right" class="garisatas gariskanan garisbawah">
                                <?php echo number_format($data->v_netto); ?></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <table width="100%" class="nmper" border="0">
                        <tr>
                            <td colspan="5">&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="5">&nbsp;</td>
                        </tr>
                        <tr>
                            <td align="center" style="width:20%">&nbsp;</td>
                            <td align="center" style="width:20%">
                                Mengetahui
                            </td>
                            <td align="center" style="width:20%">&nbsp;</td>
                            <td align="center" style="width:20%">
                                Hormat Kami,
                            </td>
                            <td align="center" style="width:20%">&nbsp;</td>
                        </tr>
                        <tr align="center">
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr align="center">
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr align="center">
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>

                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td width="200px" align="center">(&nbsp;&nbsp;___________________________&nbsp;&nbsp;)</td>
                            <td>&nbsp;</td>
                            <td width="200px" align="center">(&nbsp;&nbsp;___________________________&nbsp;&nbsp;)</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="5">&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="5">TANGGAL CETAK : <?php $tgl = date("d") . " " . bulan(date("m")) . " " . date("Y") . "  Jam : " . date("H:i:s");
                                                            echo $tgl; ?></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="4">&nbsp;</td>
            </tr>
            <tr>
                <td colspan="4">&nbsp;</td>
            </tr>
            <tr>
                <td colspan="4">&nbsp;</td>
            </tr>
        </table>
        <td>
            </tr>
            </table>
        <?php
    }
        ?>
        <div class="noDisplay">
            <center><b><a href="#" onClick="window.print()">Print</a></b></center>
        </div>