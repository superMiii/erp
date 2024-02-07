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
                <p class="header text-bold-700"><?= $this->lang->line('Nota Penjualan'); ?></p>
            </td>
            <td class="text-right" rowspan="3"><img src="<?= base_url(); ?>assets/images/<?= $company->e_company_logo; ?>" alt="company-logo" height="70" width="100"></td>
        </tr>
        <tr>
            <td><span class="judul">Nomor Nota : <?= $data->i_nota_id; ?></span></td>
        </tr>
        <tr>
            <td><span class="judul">Tanggal Nota : <?= date('d', strtotime($data->d_nota)) . ' ' . bulan(date('m', strtotime($data->d_nota))) . ' ' . date('Y', strtotime($data->d_nota)); ?></span></td>
        </tr>
        <tr>
            <td colspan="2">
                <hr class="mt-0 mb-0">
            </td>
        </tr>
        <tr>
            <td width="50%"><span class="header2 text-bold-700"><?= $company->e_company_name; ?></span></td>
            <td class="text-right"><span class="text-bold-700 header2"><?= '[' . $data->i_customer_id . '] - ' . $data->e_customer_name; ?></span></td>
        </tr>
        <tr>
            <td><span class="judul text-capitalize"><?= $company->e_company_address; ?></span></td>
            <?php if ($data->i_customer != 1554) { ?>
                <td class="text-right"><span class="judul"><?= $data->e_customer_address; ?></span></td>
            <?php } ?>
        </tr>
        <tr>
            <td><span class="judul"><?php if ($company->e_company_npwp_code != null) { ?><?= $this->lang->line('NPWP'); ?> - <?= $company->e_company_npwp_code; ?><?php } ?></span></td>
            <?php if ($data->i_customer != 1554) { ?>
                <td class="text-right"><span class="judul"><?= '[' . $data->i_area_id . '] ' . $data->e_area_name . ' - ' . $data->e_city_name; ?></span></td>
            <?php } ?>
        </tr>
        <tr>
            <td><span class="judul"><?php if ($company->e_company_phone != null) { ?><?= $this->lang->line('Telepon'); ?> - <?= $company->e_company_phone; ?><?php } ?></span></td>
            <td width="50%" class="text-right"><span class="font-14 text-bold-700"><?= $this->lang->line('Pramuniaga'); ?> - <?= '[' . $data->i_salesman_id . '] ' . $data->e_salesman_name; ?></span></td>
        </tr>
        <?php if ($data->f_top30 == 't') { ?>
            <tr>
                <td colspan="2" class="text-right font-14 text-bold-700"><span><?= $this->lang->line('Masa Bayar'); ?> : 30 Hari</span></td>
            </tr>
        <?php } else { ?>
            <tr>
                <td colspan="2" class="text-right font-14 text-bold-700"><span><?= $this->lang->line('Masa Bayar'); ?> : <?= $data->n_so_toplength; ?> Hari</span></td>
            </tr>
        <?php } ?>
    </table>
    <input type="hidden" id="id" value="<?= $data->i_nota; ?>">
    <input type="hidden" id="path" value="<?= base_url($this->folder); ?>">
    <table class="judul5" width="100%" border="1" cellspacing="0" cellpadding="2">
        <thead>
            <tr>
                <th scope="col" class="text-center" width="3%">No</th>
                <th class="text-left"><?= $this->lang->line('Kode'); ?></th>
                <th class="text-left"><?= $this->lang->line('Nama Barang'); ?></th>
                <th class="text-right" scope="col">Qty</th>
                <th class="text-right" scope="col"><?= $this->lang->line('Harga'); ?></th>
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
                    $i++;
                    $total = $key->v_unit_price * $key->n_deliver;
                    $v_diskon1 = $total * ($key->n_nota_discount1 / 100);
                    $v_diskon2 = ($total - $v_diskon1) * ($key->n_nota_discount2 / 100);
                    $v_diskon3 = ($total - $v_diskon1 - $v_diskon2) * ($key->n_nota_discount3 / 100);
                    $v_diskon4 = ($total - $v_diskon1 - $v_diskon2 - $v_diskon3) * ($key->n_nota_discount4 / 100);
                    $v_total_discount = $v_diskon1 + $v_diskon2 + $v_diskon3 + $v_diskon4;
            ?>
                    <tr>
                        <td class="text-center"><?= $i; ?></td>
                        <td><?= $key->i_product_id; ?></td>
                        <td><?= $key->e_product_name; ?></td>
                        <td class="text-right"><?= $key->n_deliver; ?></td>
                        <td class="text-right"><?= number_format($key->v_unit_price); ?></td>
                        <td class="text-right"><?= number_format($total); ?></td>
                    </tr>
            <?php
                    $subtotal += $total;
                    $distotal += $v_total_discount;
                }
                $dpp = $subtotal - $distotal - $data->v_so_discounttotal;
                $ppn = $dpp * ($data->n_so_ppn / 100);
                $grandtotal = $dpp + $ppn;
                $netto = $grandtotal + $data->v_meterai;
            } ?>

        </tbody>
        <?php if ($data->v_meterai > 0) { ?>
            <tfoot>
                <tr>
                    <th class="font-medium-2" colspan="4">
                    </th>
                    <th class="text-right"><?= $this->lang->line("Sub Total"); ?> Rp.</th>
                    <td class="text-right text-bold-700"><?= number_format($subtotal); ?></td>
                </tr>
                <tr>
                    <th rowspan="6" colspan="4">
                        <div class="card-content">
                            <div class="text-left">
                                <span class="mb-1"><strong>Catatan : </strong></span><br>
                                <span class="mb-0"><small><br>1. Barang-barang yang sudah dibeli tidak dapat ditukar/dikembalikan, <br>&nbsp;&nbsp;&nbsp; kecuali ada perjanjian terlebih dahulu.</small></span>
                                <span class="mb-0"><small><br>2. Faktur asli merupakan bukti pembayaran yang sah.</small></span>
                                <span class="mb-0"><small><br>3. Pembayaran dengan cek/giro, baru dianggap sah setelah diuangkan/cair.</small></span>
                                <span class="mb-1"><small><br>4. Pembayaran dapat ditransfer ke rekening berikut :</small></span>
                            </div>
                        </div>
                    </th>
                    <th class="text-right"><?= $this->lang->line("Diskon Per Item"); ?> Rp.</th>
                    <td class="text-right"><?= number_format($distotal); ?></td>
                </tr>
                <tr>
                    <th class="text-right"><?= $this->lang->line("Diskon Tambahan"); ?> Rp.</th>
                    <td class="text-right"><?= number_format($data->v_so_discounttotal); ?>
                        <!-- <li class="dropdown-divider mt-0 mb-0"></li> -->
                    </td>
                </tr>
                <?php if ($data->f_so_plusppn == 't') { ?>
                    <tr>
                        <th class="text-right"><?= $this->lang->line("DPP"); ?> Rp.</th>
                        <td class="text-right"><?= number_format($dpp); ?></td>
                    </tr>
                    <tr>
                        <th class="text-right"><?= $this->lang->line("PPN"); ?> (<?= $data->n_so_ppn; ?>%) Rp.</th>
                        <td class="text-right"><?= number_format($ppn); ?>
                            <!-- <li class="dropdown-divider mt-0 mb-0"></li> -->
                        </td>
                    </tr>
                    <tr>
                        <th class="text-right"><?= $this->lang->line("Bea Meterai"); ?> Rp.</th>
                        <td class="text-right"><?= number_format($data->v_meterai); ?></td>
                    </tr>
                    <tr>
                        <th class="text-right"><?= $this->lang->line("Nilai Bersih"); ?> Rp.</th>
                        <td class="text-right text-bold-700"><?= number_format($netto); ?></td>
                    </tr>
                <?php } else { ?>
                    <tr>
                        <th class="text-right"><?= $this->lang->line("Bea Meterai"); ?> Rp.</th>
                        <th class="text-right"><?= number_format($data->v_meterai); ?></th>
                    </tr>
                    <tr>
                        <th class="text-right"><?= $this->lang->line("Nilai Bersih"); ?> Rp.</th>
                        <th class="text-right text-bold-700"><?= number_format($netto); ?></th>
                    </tr>
                    <tr>
                        <th class="text-right" colspan="2">&nbsp;</th>
                    </tr>
                <?php } ?>
                <tr>
                    <th class="text-right" colspan="6"><span class="font-small-3 text-capitalize"><em>(<?= terbilang($netto); ?>)</em></span></th>
                </tr>
                <!-- <tr>
                                            <p class="mb-0 font-medium-3"><strong>Bank : <?= $company->e_company_account_bank; ?></strong></p>
                                            <p class="mb-0 font-medium-3"><strong>Atas Nama : <?= $company->e_company_account_name; ?></strong></p>
                                            <p class="mb-0 font-medium-3"><strong>Nomor Rekening : <?= $company->e_company_account_number; ?></strong></p>
                                        </tr> -->
            </tfoot>
        <?php } else { ?>
            <tfoot>
                <tr>
                    <th rowspan="6" colspan="4">
                        <div class="border-blue-grey">
                            <div class="card-content">
                                <div class="text-left">
                                    <span class="mb-1"><strong>Catatan : </strong></span><br>
                                    <span class="mb-0"><small><br>1. Barang-barang yang sudah dibeli tidak dapat ditukar/dikembalikan, <br>&nbsp;&nbsp;&nbsp; kecuali ada perjanjian terlebih dahulu.</small></span>
                                    <span class="mb-0"><small><br>2. Faktur asli merupakan bukti pembayaran yang sah.</small></span>
                                    <span class="mb-0"><small><br>3. Pembayaran dengan cek/giro, baru dianggap sah setelah diuangkan/cair.</small></span>
                                    <span class="mb-1"><small><br>4. Pembayaran dapat ditransfer ke rekening berikut :</small></span>
                                </div>
                            </div>
                        </div>
                    </th>
                    <th class="text-right"><?= $this->lang->line("Sub Total"); ?> Rp.</th>
                    <td class="text-right text-bold-700"><?= number_format($subtotal); ?></td>
                </tr>
                <tr>

                    <th class="text-right"><?= $this->lang->line("Disk"); ?> Rp.</th>
                    <td class="text-right"><?= number_format($distotal); ?></td>
                </tr>
                <tr>
                    <th class="text-right"><?= $this->lang->line("Diskon Tambahan"); ?> Rp.</th>
                    <td class="text-right"><?= number_format($data->v_so_discounttotal); ?>
                    </td>
                </tr>
                <?php if ($data->f_so_plusppn == 't') { ?>
                    <tr>
                        <th class="text-right"><?= $this->lang->line("DPP"); ?> Rp.</th>
                        <td class="text-right"><?= number_format($dpp); ?></td>
                    </tr>
                    <tr>
                        <th class="text-right"><?= $this->lang->line("PPN"); ?> (<?= $data->n_so_ppn; ?>%) Rp.</th>
                        <td class="text-right"><?= number_format($ppn); ?>
                            <!-- <li class="dropdown-divider mt-0 mb-0"></li> -->
                        </td>
                    </tr>
                    <tr>
                        <th class="text-right"><?= $this->lang->line("Nilai Bersih"); ?> Rp.</th>
                        <td class="text-right text-bold-700"><?= number_format($grandtotal); ?></td>
                    </tr>
                <?php } else { ?>
                    <tr>
                        <th class="text-right"><?= $this->lang->line("Nilai Bersih"); ?> Rp.</th>
                        <th class="text-right text-bold-700"><?= number_format($grandtotal); ?></th>
                    </tr>
                    <tr>
                        <th class="text-right" colspan="2" rowspan="2">&nbsp;</th>
                    </tr>
                    <tr>
                        <!-- <th class="text-right" colspan="2">&nbsp;</th> -->
                    </tr>
                <?php } ?>
                <tr>
                    <th class="text-right" colspan="6"><span class="font-14 text-capitalize"><em>(<?= ucwords(strtolower(terbilang($grandtotal))); ?>)</em></span></th>
                </tr>

            </tfoot>

        <?php } ?>
    </table>
    <hr class="mt-1 mb-0">
    <table width="100%;" class="font-14">
        <tr>
            <td class="mb-0">Bank : <strong><?= $company->e_company_account_bank; ?></strong></td>
            <td class="mb-0">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
            <td class="mb-0">Bank : <strong><?= $company->e_company_account_bank2; ?></strong></td>
            <td class="mb-0">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
            <td class="mb-0">Bank : <strong><?= $company->e_company_account_bank3; ?></strong></td>
        </tr>
        <tr>
            <td class="mb-0">Atas Nama : <strong><?= $company->e_company_account_name; ?></strong>
            </td>
            <td class="mb-0">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
            <td class="mb-0">Atas Nama : <strong><?= $company->e_company_account_name2; ?></strong>
            </td>
            <td class="mb-0">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
            <td class="mb-0">Atas Nama : <strong><?= $company->e_company_account_name3; ?></strong>
            </td>
        </tr>
        <tr>
            <td class="mb-0">Nomor Rekening : <strong><?= $company->e_company_account_number; ?></strong></td>
            <td class="mb-0">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
            <td class="mb-0">Nomor Rekening : <strong><?= $company->e_company_account_number2; ?></strong></td>
            <td class="mb-0">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
            <td class="mb-0">Nomor Rekening : <strong><?= $company->e_company_account_number3; ?></strong></td>
        </tr>
    </table>
    <hr class="mt-0 mb-1">
    <table width="100%;" class="font-13">
        <tbody class="text-center">
            <!-- <tr>
                <td colspan="6" class="text-right"><span><strong>Bandung, <?= date('d', strtotime($data->d_nota)) . ' ' . bulan(date('m', strtotime($data->d_nota))) . ' ' . date('Y', strtotime($data->d_nota)); ?></strong></span></td>
            </tr> -->
            <tr>
                <td>
                    Penerima
                </td>
                <td>
                    &nbsp;
                </td>
                <td>
                    S E & O
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
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                </td>
                <?php if ($company->i_company == '5' or $company->i_company == '8') { ?>
                    <td class="text-muted">
                        ( &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; )
                    </td>
                <?php } else { ?>
                    <td class="text-muted">
                        ( Kartika Suri D.Y. )
                    </td>
                    <!-- <td class="text-muted">
                    ( Arief Hariyono )
                </td> -->
                <?php } ?>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="6" class="text-center"><a href="#" onclick="window.print();" class="button button1 no-print"> <i class="feather icon-printer mr-25 common-size"></i> <?= $this->lang->line("Cetak"); ?></a></td>
            </tr>
        </tfoot>
    </table>
    <?= put_footer(); ?>
</body>
<!-- END: Body-->

</html>