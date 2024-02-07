<html class="loading" lang="en" data-textdirection="ltr">
<!-- BEGIN: Head-->

<head>
    <title>Print <?= $this->title; ?></title>

    <!-- <link rel="stylesheet" type="text/css" href="<?= base_url(); ?>app-assets/vendors/css/vendors.min.css">
    <?= put_headers(); ?> -->
    <!-- END: Vendor CSS-->

    <!-- BEGIN: Theme CSS-->
    <!-- <link rel="stylesheet" type="text/css" href="<?= base_url(); ?>app-assets/css/bootstrap.css"> -->
    <link rel="stylesheet" type="text/css" href="<?= base_url(); ?>app-assets/css/bootstrap-extended.css">
    <!--<link rel="stylesheet" type="text/css" href="<?= base_url(); ?>app-assets/css/colors.css">
    <link rel="stylesheet" type="text/css" href="<?= base_url(); ?>app-assets/css/components.css"> -->
    <!-- END: Theme CSS-->

    <!-- BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="<?= base_url(); ?>assets/css/style.css">
    <link rel="stylesheet" type="text/css" href="<?= base_url(); ?>assets/css/print.css">
    <!-- END: Custom CSS-->
</head>
<!-- END: Head-->

<!-- BEGIN: Body-->

<!-- <body oncontextmenu='return false;' onkeydown='return false;' onmousedown='return false;'> -->

<body>
    <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
            <td>
                <p class="header text-uppercase text-bold-700 text-primary"><?= $this->lang->line('Surat Jalan'); ?></p>
            </td>
            <td class="text-right" rowspan="4"><img src="<?= base_url(); ?>assets/images/<?= $company->e_company_logo; ?>" alt="company-logo" height="90" width="125"></td>
        </tr>
        <tr>
            <td><span class="judul4 font-weight-bold">Nomor Surat Jalan : <?= $data->i_do_id; ?></span></td>
        </tr>
        <tr>
            <td><span class="judul4 font-weight-bold">Nomor SPB : <?= $data->i_so_id; ?></span></td>
        </tr>
        <!-- <tr>
            <td><span class="judul4 font-weight-bold">Nomor Referensi : <?= $data->i_po_reff; ?></span></td>
            <hr class="mt-0 mb-0">
        </tr> -->
        <tr>
            <td><span class="judul4 font-weight-bold">Tanggal Surat Jalan : <?= date('d', strtotime($data->d_do)) . ' ' . bulan(date('m', strtotime($data->d_do))) . ' ' . date('Y', strtotime($data->d_do)); ?></span></td>
        </tr>
        <tr>
            <td colspan="2">
                <hr class="mt-0 mb-0">
            </td>
        </tr>
        <tr>
            <td width="50%"><span class="header text-bold-700"><?= $company->e_company_name; ?></span></td>
            <td width="50%" class="text-right"><span class="judul4"><?= $this->lang->line('Pramuniaga'); ?> - <?= '[' . $data->i_salesman_id . '] ' . $data->e_salesman_name; ?></span></td>
        </tr>
        <tr>
            <td><span class="judul4 text-capitalize"><?= $company->e_company_address; ?></span></td>
            <td class="text-right"><span class="text-bold-700 header"><?= '[' . $data->i_customer_id . '] - ' . $data->e_customer_name; ?></span></td>
        </tr>
        <tr>
            <td><span class="judul4"><?php if ($company->e_company_npwp_code != null) { ?><?= $this->lang->line('NPWP'); ?> - <?= $company->e_company_npwp_code; ?><?php } ?></span></td>
            <td class="text-right"><span class="judul4"><?= $data->e_customer_address; ?></span></td>
        </tr>
        <tr>
            <td><span class="judul4"><?php if ($company->e_company_phone != null) { ?><?= $this->lang->line('Telepon'); ?> - <?= $company->e_company_phone; ?><?php } ?></span></td>
            <td class="text-right"><span class="judul4"><?= $data->e_city_name . ' - ' . $data->e_area_name; ?></span></td>
        </tr>
        <tr>
            <td colspan="2" class="text-right judul4"><span><?= $data->e_customer_phone; ?></span></td>
        </tr>
        <tr>
            <td class="judul4"><span>Harap diterima barang-barang berikut ini :</span></td>
            <td class="text-right judul4"><span>Ekspedisi : <?= $data->e_ekspedisi_cus; ?></span></td>
            <!-- <td class="text-right judul4"><span>&nbsp;</span></td> -->
        </tr>
    </table>
    <input type="hidden" id="id" value="<?= $data->i_do; ?>">
    <input type="hidden" id="path" value="<?= base_url($this->folder); ?>">



    <?php if ($data->e_customer_name == 'Sample' or $company->i_company == '5') { ?>
        <table class="judul4" width="100%" border="1" cellspacing="0" cellpadding="2">
            <thead>
                <tr>
                    <th scope="col" class="text-center" width="3%">No</th>
                    <th class="text-left"><?= $this->lang->line('Kode'); ?></th>
                    <th class="text-left"><?= $this->lang->line('Nama Barang'); ?></th>
                    <th class="text-right" scope="col">Qty</th>
                    <!-- <th class="text-right" scope="col"><?= $this->lang->line('Harga'); ?></th>
                    <th class="text-left"><?= $this->lang->line('Disk1'); ?></th>
                    <th class="text-left"><?= $this->lang->line('Disk2'); ?></th>
                    <th class="text-left"><?= $this->lang->line('Disk3'); ?></th>
                    <th class="text-right" scope="col"><?= $this->lang->line('Total'); ?></th> -->
                </tr>
            </thead>
            <tbody>
                <?php $i = 0;
                $subtotal = 0;
                $distotal = 0;
                $dpp = 0;
                $ppn = 0;
                $grandtotal = 0;
                if ($detail->num_rows() > 0) {
                    foreach ($detail->result() as $key) {
                        if ($key->n_do > 0) {
                            $i++;
                            $total = $key->v_unit_price * $key->n_do;
                            $v_diskon1 = $total * ($key->n_so_discount1 / 100);
                            $v_diskon2 = ($total - $v_diskon1) * ($key->n_so_discount2 / 100);
                            $v_diskon3 = ($total - $v_diskon1 - $v_diskon2) * ($key->n_so_discount3 / 100);
                            $v_diskon4 = ($total - $v_diskon1 - $v_diskon2 - $v_diskon3) * ($key->n_so_discount4 / 100);
                            $v_total_discount = $v_diskon1 + $v_diskon2 + $v_diskon3 + $v_diskon4;
                ?>
                            <tr>
                                <td class="text-center"><?= $i; ?></td>
                                <td><?= $key->i_product_id; ?></td>
                                <td><?= $key->e_product_name; ?></td>
                                <td class="text-right"><?= $key->n_do; ?></td>
                                <!-- <td class="text-right"><?= number_format($key->v_unit_price); ?></td>
                                <td><?= $key->n_so_discount1; ?></td>
                                <td><?= $key->n_so_discount2; ?></td>
                                <td><?= $key->n_so_discount3; ?></td>
                                <td class="text-right"><?= number_format($total); ?></td> -->
                            </tr>
                <?php
                            $subtotal += $total;
                            $distotal += $v_total_discount;
                        }
                    }
                    $dpp = $subtotal - $distotal - $data->v_so_discounttotal;
                    $ppn = $dpp * ($data->n_so_ppn / 100);
                    $grandtotal = $dpp + $ppn;
                } ?>

            </tbody>
            <tfoot>

                <?php if ($grandtotal >= $this->session->v_meterai_limit) { ?>
                    <!-- <tr>
                        <th class="font-small-2" colspan="5"><?= $this->lang->line('Catatan'); ?> : Nilai Belum Termasuk Bea Meterai Rp. 10.000,-</th>
                        
                        <th class="text-right" colspan="3">Sub Total</th>
                        <td class="text-right"><strong><?= number_format($subtotal); ?></strong></td>
                    </tr> -->
                    <tr>
                        <th <?php if ($data->f_so_plusppn == 't') { ?> rowspan="4" <?php } else { ?> rowspan="4" <?php } ?> colspan="5">
                            <div class="border-blue-grey">
                                <div class="card-content">
                                    <div class="text-center">
                                        <span class="mb-1"><strong>P E N T I N G</strong></span><br>
                                        <span class="mb-0"><small>PENERIMA WAJIB TTD DAN ATAU CAP TOKO,</small></span><br>
                                        <span class="mb-0"><small>SURAT JALN INI MERUPAKAN BUKTI RESMI PENERIMAAN BARANG,</small></span><br>
                                        <span class="mb-0"><small>SURAT JALAN INI BUKAN MERUPAKAN BUKTI TAGIHAN,</small></span><br>
                                        <span class="mb-0"><small>TERIMA KOMPLEN BARANG PALING LAMA 3 HARI SETELAH BARANG DITERIMA</small></span>
                                    </div>
                                </div>
                            </div>
                        </th>
                    </tr>
                <?php } else { ?>
                    <tr>
                        <th <?php if ($data->f_so_plusppn == 't') { ?> rowspan="7" <?php } else { ?> rowspan="4" <?php } ?> colspan="5">
                            <div class="border-blue-grey">
                                <div class="card-content">
                                    <div class="text-center">
                                        <span class="mb-1"><strong>P E N T I N G</strong></span><br>
                                        <span class="mb-0"><small>PENERIMA WAJIB TTD DAN ATAU CAP TOKO,</small></span><br>
                                        <span class="mb-0"><small>SURAT JALN INI MERUPAKAN BUKTI RESMI PENERIMAAN BARANG,</small></span><br>
                                        <span class="mb-0"><small>SURAT JALAN INI BUKAN MERUPAKAN BUKTI TAGIHAN,</small></span><br>
                                        <span class="mb-0"><small>TERIMA KOMPLEN BARANG PALING LAMA 3 HARI SETELAH BARANG DITERIMA</small></span>
                                    </div>
                                </div>
                            </div>
                        </th>
                        <!-- <th class="text-right" colspan="3">Sub Total</th>
                        <td class="text-right"><strong><?= number_format($subtotal); ?></strong></td> -->
                    </tr>
                <?php } ?>
                <!-- <tr>
                    <th class="text-right" colspan="3">Diskon Per Item</th>
                    <td class="text-right"><?= number_format($distotal); ?></td>
                </tr>
                <tr>
                    <th class="text-right" colspan="3">Diskon Tambahan</th>
                    <td class="text-right"><?= number_format($data->v_so_discounttotal); ?></li>
                    </td>
                </tr>
                <?php if ($data->f_so_plusppn == 't') { ?>
                    <tr>
                        <th class="text-right" colspan="3">Nilai Kotor</th>
                        <td class="text-right"><?= number_format($dpp); ?></td>
                    </tr>
                    <tr>
                        <th class="text-right" colspan="3">Pajak (<?= $data->n_so_ppn; ?>%)</th>
                        <td class="text-right"><?= number_format($ppn); ?></td>
                    </tr>
                <?php } ?>
                <tr>
                    <th class="text-right" colspan="3">Nilai Bersih</th>
                    <td class="text-right"><strong><?= number_format($grandtotal); ?></strong></td>
                </tr> -->
            </tfoot>
        </table>





    <?php } else { ?>










        <table class="judul4" width="100%" border="1" cellspacing="0" cellpadding="2">
            <thead>
                <tr>
                    <th scope="col" class="text-center" width="3%">No</th>
                    <th class="text-left"><?= $this->lang->line('Kode'); ?></th>
                    <th class="text-left"><?= $this->lang->line('Nama Barang'); ?></th>
                    <th class="text-right" scope="col">Qty</th>
                    <th class="text-right" scope="col"><?= $this->lang->line('Harga'); ?></th>
                    <th class="text-left"><?= $this->lang->line('Disk1'); ?></th>
                    <th class="text-left"><?= $this->lang->line('Disk2'); ?></th>
                    <th class="text-left"><?= $this->lang->line('Disk3'); ?></th>
                    <th class="text-right" scope="col"><?= $this->lang->line('Total'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php $i = 0;
                $subtotal = 0;
                $distotal = 0;
                $dpp = 0;
                $ppn = 0;
                $grandtotal = 0;
                if ($detail->num_rows() > 0) {
                    foreach ($detail->result() as $key) {
                        if ($key->n_do > 0) {
                            $i++;
                            $total = $key->v_unit_price * $key->n_do;
                            $v_diskon1 = $total * ($key->n_so_discount1 / 100);
                            $v_diskon2 = ($total - $v_diskon1) * ($key->n_so_discount2 / 100);
                            $v_diskon3 = ($total - $v_diskon1 - $v_diskon2) * ($key->n_so_discount3 / 100);
                            $v_diskon4 = ($total - $v_diskon1 - $v_diskon2 - $v_diskon3) * ($key->n_so_discount4 / 100);
                            $v_total_discount = $v_diskon1 + $v_diskon2 + $v_diskon3 + $v_diskon4;
                ?>
                            <tr>
                                <td class="text-center"><?= $i; ?></td>
                                <td><?= $key->i_product_id; ?></td>
                                <td><?= $key->e_product_name; ?></td>
                                <td class="text-right"><?= $key->n_do; ?></td>
                                <td class="text-right"><?= number_format($key->v_unit_price); ?></td>
                                <td><?= $key->n_so_discount1; ?></td>
                                <td><?= $key->n_so_discount2; ?></td>
                                <td><?= $key->n_so_discount3; ?></td>
                                <td class="text-right"><?= number_format($total); ?></td>
                            </tr>
                <?php
                            $subtotal += $total;
                            $distotal += $v_total_discount;
                        }
                    }
                    $dpp = $subtotal - $distotal - $data->v_so_discounttotal;
                    $ppn = $dpp * ($data->n_so_ppn / 100);
                    $grandtotal = $dpp + $ppn;
                } ?>

            </tbody>
            <tfoot>

                <?php if ($grandtotal >= $this->session->v_meterai_limit) { ?>
                    <tr>
                        <th class="font-small-2" colspan="5"><?= $this->lang->line('Catatan'); ?> : Nilai Belum Termasuk Bea Meterai Rp. 10.000,-</th>
                        <th class="text-right" colspan="3">Sub Total</th>
                        <td class="text-right"><strong><?= number_format($subtotal); ?></strong></td>
                        <!-- <td class="text-right"><?= number_format($subtotal); ?></td> -->
                    </tr>
                    <tr>
                        <th <?php if ($data->f_so_plusppn == 't') { ?> rowspan="6" <?php } else { ?> rowspan="4" <?php } ?> colspan="5">
                            <div class="border-blue-grey">
                                <div class="card-content">
                                    <div class="text-center">
                                        <span class="mb-1"><strong>P E N T I N G</strong></span><br>
                                        <span class="mb-0"><small>PENERIMA WAJIB TTD DAN ATAU CAP TOKO,</small></span><br>
                                        <span class="mb-0"><small>SURAT JALAN INI MERUPAKAN BUKTI RESMI PENERIMAAN BARANG,</small></span><br>
                                        <span class="mb-0"><small>SURAT JALAN INI BUKAN MERUPAKAN BUKTI TAGIHAN,</small></span><br>
                                        <span class="mb-0"><small>TERIMA KOMPLEN BARANG PALING LAMA 3 HARI SETELAH BARANG DITERIMA</small></span>
                                    </div>
                                </div>
                            </div>
                        </th>
                    </tr>
                <?php } else { ?>
                    <tr>
                        <th <?php if ($data->f_so_plusppn == 't') { ?> rowspan="7" <?php } else { ?> rowspan="4" <?php } ?> colspan="5">
                            <div class="border-blue-grey">
                                <div class="card-content">
                                    <div class="text-center">
                                        <span class="mb-1"><strong>P E N T I N G</strong></span><br>
                                        <span class="mb-0"><small>PENERIMA WAJIB TTD DAN ATAU CAP TOKO,</small></span><br>
                                        <span class="mb-0"><small>SURAT JALAN INI MERUPAKAN BUKTI RESMI PENERIMAAN BARANG,</small></span><br>
                                        <span class="mb-0"><small>SURAT JALAN INI BUKAN MERUPAKAN BUKTI TAGIHAN,</small></span><br>
                                        <span class="mb-0"><small>TERIMA KOMPLEN BARANG PALING LAMA 3 HARI SETELAH BARANG DITERIMA</small></span>
                                    </div>
                                </div>
                            </div>
                        </th>
                        <th class="text-right" colspan="3">Sub Total</th>
                        <td class="text-right"><strong><?= number_format($subtotal); ?></strong></td>
                        <!-- <td class="text-right"><?= number_format($subtotal); ?></td> -->
                    </tr>
                <?php } ?>
                <tr>
                    <td class="text-right" colspan="3">Diskon Per Item</td>
                    <td class="text-right"><?= number_format($distotal); ?></td>
                </tr>
                <tr>
                    <td class="text-right" colspan="3">Diskon Tambahan</td>
                    <td class="text-right"><?= number_format($data->v_so_discounttotal); ?></li>
                    </td>
                </tr>
                <?php if ($data->f_so_plusppn == 't') { ?>
                    <tr>
                        <td class="text-right" colspan="3">Nilai Kotor</td>
                        <td class="text-right"><?= number_format($dpp); ?></td>
                    </tr>
                    <tr>
                        <td class="text-right" colspan="3">Pajak (<?= $data->n_so_ppn; ?>%)</td>
                        <td class="text-right"><?= number_format($ppn); ?></td>
                    </tr>
                <?php } ?>
                <tr>
                    <th class="text-right" colspan="3">Nilai Bersih</th>
                    <td class="text-right"><strong><?= number_format($grandtotal); ?><s /trong></td>
                </tr>
            </tfoot>
        </table>
    <?php } ?>
    <hr>
    <table class="judul4" width="100%" border="1" cellspacing="0" cellpadding="2">
        <tr>
            <td class="mb-0">Bank : <strong><?= $company->e_company_account_bank; ?></strong></td>
            <td class="mb-0">Bank : <strong><?= $company->e_company_account_bank2; ?></strong></td>
            <td class="mb-0">Bank : <strong><?= $company->e_company_account_bank3; ?></strong></td>
        </tr>
        <tr>
            <td class="mb-0">Atas Nama : <strong><?= $company->e_company_account_name; ?></strong>
            </td>
            <td class="mb-0">Atas Nama : <strong><?= $company->e_company_account_name2; ?></strong>
            </td>
            <td class="mb-0">Atas Nama : <strong><?= $company->e_company_account_name3; ?></strong>
            </td>
        </tr>
        <tr>
            <td class="mb-0">Nomor Rekening : <strong><?= $company->e_company_account_number; ?></strong></td>
            <td class="mb-0">Nomor Rekening : <strong><?= $company->e_company_account_number2; ?></strong></td>
            <td class="mb-0">Nomor Rekening : <strong><?= $company->e_company_account_number3; ?></strong></td>
        </tr>
    </table>

    <table width="100%;" class="judul4">
        <tbody class="text-center">
            <!-- <th class="text-left" colspan="5"><?= $this->lang->line('Keterangan :'); ?> <?= $data->e_remark; ?> </th> -->
            <!-- <th class="text-left" colspan="5">&nbsp; </th> -->
            <!-- <tr>
                <td colspan="6" class="text-right"><span>Bandung, <?= date('d', strtotime($data->d_do)) . ' ' . bulan(date('m', strtotime($data->d_do))) . ' ' . date('Y', strtotime($data->d_do)); ?></span></td>
            </tr> -->
            <tr>
                <td>
                    Penerima
                </td>
                <td>
                    Mengetahui
                </td>
                <td>
                    Pengirim
                </td>
            </tr>
            <tr height="40px">
                <td colspan="6"></td>
            </tr>
            <tr>
                <td class="text-muted">
                    ( &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; )
                </td>
                <td class="text-muted">
                    ( &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; )
                </td>
                <td class="text-muted">
                    ( &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; )
                </td>
            </tr>
        </tbody>
        <tfoot>
            <!-- <tr>
                <td colspan="6"><span class="judul4">*Tanggal Cetak : <?= date('d') . ' ' . bulan(date('m')) . ' ' . date('Y') . ' ' . date('H:i:s'); ?></span></td>
            </tr> -->
            <tr>
                <td colspan="6" class="text-center"><a href="#" onclick="window.print();" class="button button1 no-print"> <i class="feather icon-printer mr-25 common-size"></i> <?= $this->lang->line("Cetak"); ?></a></td>
            </tr>
        </tfoot>
    </table>
    <?= put_footer(); ?>
</body>
<!-- END: Body-->

</html>