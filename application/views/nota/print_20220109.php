<html class="loading" lang="en" data-textdirection="ltr">
<!-- BEGIN: Head-->

<head>
    <!-- <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <title>Print | <?= $this->title; ?></title>
    <link rel="apple-touch-icon" href="<?= base_url(); ?>app-assets/images/ico/apple-icon-120.png">
    <link rel="shortcut icon" type="image/x-icon" href="<?= base_url(); ?>app-assets/images/ico/favicon.ico">
    <!-- <link href="https://fonts.googleapis.com/css?family=Montserrat:300,300i,400,400i,500,500i%7COpen+Sans:300,300i,400,400i,600,600i,700,700i" rel="stylesheet"> -->
    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="<?= base_url(); ?>app-assets/vendors/css/vendors.min.css">
    <?= put_headers(); ?>
    <!-- END: Vendor CSS-->

    <!-- BEGIN: Theme CSS-->
    <link rel="stylesheet" type="text/css" href="<?= base_url(); ?>app-assets/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="<?= base_url(); ?>app-assets/css/bootstrap-extended.css">
    <link rel="stylesheet" type="text/css" href="<?= base_url(); ?>app-assets/css/colors.css">
    <link rel="stylesheet" type="text/css" href="<?= base_url(); ?>app-assets/css/components.css">
    <!-- END: Theme CSS-->

    <!-- BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="<?= base_url(); ?>assets/css/style.css">
    <!-- END: Custom CSS-->
    <style>
        /* @import url('http://fonts.cdnfonts.com/css/merchant-copy-doublesize'); */
        /* @import url('http://fonts.cdnfonts.com/css/fake-receipt'); */
        /* @import url('https://fonts.googleapis.com/css2?family=Baumans&display=swap'); */

        /* body { */
        /* font-family: 'Baumans', cursive; */
        /* font-family: 'Fake Receipt', sans-serif; */
        /* font-family: 'Merchant Copy Doublesize', sans-serif; */
        /* font-family: 'Merchant Copy Wide', sans-serif; */
        /* font-family: 'Merchant Copy', sans-serif; */
        /* } */

        .table.table-xs th,
        .table td,
        .table th,
        .table.table-xs td {
            padding: 0.4rem 0.4rem;
        }

        /* .table>tfoot>tr>th,
        .table>tfoot>tr>td {
            border: none !important;
        } */

        @media print {

            .no-print,
            .no-print * {
                display: none !important;
            }
        }
    </style>
</head>
<!-- END: Head-->

<!-- BEGIN: Body-->

<!-- <body oncontextmenu='return false;' onkeydown='return false;' onmousedown='return false;'> -->

<body>
    <section class="app-invoice-wrapper">
        <div class="row">
            <div class="col-md-12 col-12 printable-content">
                <!-- using a bootstrap card -->
                <div class="card">
                    <!-- card body -->
                    <div class="card-body">
                        <!-- card-header -->
                        <input type="hidden" id="id" value="<?= $data->i_nota; ?>">
                        <input type="hidden" id="path" value="<?= base_url($this->folder); ?>">
                        <!-- <div class="col-sm-4">
                                    <span class="invoice-id font-weight-bold">#<?= $this->lang->line('Nota'); ?>:</span>
                                    <span class="font-medium-3"><?= $data->i_nota_id; ?></span>
                                </div> -->
                        <!-- <div class="col-sm-8">
                                    <div class="d-flex align-items-center justify-content-end justify-content-xs-start">
                                        <div class="issue-date pr-2">
                                            <span class="font-weight-bold no-wrap"><?= $this->lang->line('Tgl Nota'); ?>: </span>
                                            <span><?= date('d/m/Y', strtotime($data->d_nota)); ?></span>
                                        </div>
                                        <div class="due-date">
                                            <span class="font-weight-bold no-wrap"><?= $this->lang->line('Tgl Jatuh Tempo'); ?>: </span>
                                            <span><?= date('d/m/Y', strtotime($data->d_jatuh_tempo)); ?></span>
                                        </div>
                                    </div>
                                </div> -->

                        <!-- invoice logo and title -->
                        <div class="invoice-logo-title row">
                            <div class="col-9 d-flex flex-column justify-content-center align-items-start">
                                <!-- <span>Software Development</span> -->
                                <p class="font-large-2 text-uppercase text-primary"><?= $this->lang->line('Nota Penjualan'); ?><br><span class="font-medium-3 font-weight-bold">NOMOR NOTA : <?= $data->i_nota_id; ?></span></p>
                            </div>
                            <div class="col-3 d-flex justify-content-end invoice-logo">
                                <img src="<?= base_url(); ?>assets/images/<?= $company->e_company_logo; ?>" alt="company-logo" height="94" width="150">
                            </div>
                        </div>
                        <hr class="mt-0 mb-0">

                        <!-- invoice address and contacts -->
                        <div class="row invoice-adress-info px-0">
                            <div class="col-6 mt-1 from-info">
                                <!-- <div class="info-title mb-1">
                                    <span>Bill From</span>
                                </div> -->
                                <div class="company-name mb-1">
                                    <span class="text-muted text-bold-700 font-medium-3"><?= $company->e_company_name; ?></span><br>
                                    <span class="font-medium-3 text-capitalize"><?= $company->e_company_address; ?></span><br>
                                    <?php if ($company->e_company_npwp_code != null) { ?>
                                        <span class="font-medium-3"><?= $this->lang->line('NPWP'); ?> - <?= $company->e_company_npwp_code; ?></span><br>
                                    <?php } ?>
                                    <?php if ($company->e_company_phone != null) { ?>
                                        <span class="font-medium-3"><?= $this->lang->line('Telepon'); ?> - <?= $company->e_company_phone; ?></span>
                                </div>
                            <?php } ?>

                            </div>
                            <div class="col-6 mt-1 to-info text-right">
                                <!-- <div class="info-title mb-1">
                                    <span font-large-1>Bill To</span>
                                </div> -->
                                <div class="company-name mb-1">
                                    <span class="text-muted text-bold-700 font-medium-3"><?= '[' . $data->i_customer_id . '] - ' . $data->e_customer_name; ?></span><br>
                                    <span class="font-medium-3"><?= $data->e_customer_address; ?></span><br>
                                    <span class="font-medium-3"><?= '[' . $data->i_area_id . '] ' . $data->e_area_name . ' - ' . $data->e_city_name; ?></span><br>
                                    <span class="text-muted text-bold-700 font-medium-3"><?= $this->lang->line('Pramuniaga'); ?> - <?= '[' . $data->i_salesman_id . '] ' . $data->e_salesman_name; ?></span><br>
                                    <span class="text-muted text-bold-700 font-medium-3" colspan="4"><?= $this->lang->line('Masa Bayar'); ?> :
                                        <?= $data->n_so_toplength; ?> Hari</span>
                                </div>
                            </div>
                        </div>

                        <!--product details table -->
                        <div class="product-details-table table-responsive">
                            <table class="table table-xs table-bordered mt-0 mb-0">
                                <thead>
                                    <tr>
                                        <th scope="col" class="text-center" width="3%">No</th>
                                        <th scope="col"><?= $this->lang->line('Kode'); ?></th>
                                        <th scope="col"><?= $this->lang->line('Nama Barang'); ?></th>
                                        <th class="text-right" scope="col">Qty</th>
                                        <th class="text-right" scope="col"><?= $this->lang->line('Harga'); ?></th>
                                        <th class="text-right" scope="col"><?= $this->lang->line('Total'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $i = 0;
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
                                        $nppn = ($data->f_so_plusppn == 't') ? $data->n_so_ppn : 0;
                                        $ppn = $dpp * ($nppn / 100);
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
                                                    <div class="p-1">
                                                        <p class="mb-1 font-medium-3"><strong>Catatan : </strong></p>
                                                        <p class="mb-0 font-medium-2"><small>1. Barang-barang yang sudah dibeli tidak dapat ditukar/dikembalikan, <br>&nbsp;&nbsp;&nbsp; kecuali ada perjanjian terlebih dahulu.</small></p>
                                                        <p class="mb-0 font-medium-2"><small>2. Faktur asli merupakan bukti pembayaran yang sah.</small></p>
                                                        <p class="mb-0 font-medium-2"><small>3. Pembayaran dengan cek/giro, baru dianggap sah setelah diuangkan/cair.</small></p>
                                                        <p class="mb-1 font-medium-2"><small>4. Pembayaran dapat ditransfer ke rekening berikut :</small></p>
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
                                                <div class="card-content">
                                                    <div class="p-1">
                                                        <p class="mb-1 font-medium-3"><strong>Catatan : </strong></p>
                                                        <p class="mb-0 font-medium-3"><small>1. Barang-barang yang sudah dibeli tidak dapat ditukar/dikembalikan, <br>&nbsp;&nbsp;&nbsp; kecuali ada perjanjian terlebih dahulu.</small></p>
                                                        <p class="mb-0 font-medium-3"><small>2. Faktur asli merupakan bukti pembayaran yang sah.</small></p>
                                                        <p class="mb-0 font-medium-3"><small>3. Pembayaran dengan cek/giro, baru dianggap sah setelah diuangkan/cair.</small></p>
                                                        <p class="mb-1 font-medium-3"><small>4. Pembayaran dapat ditransfer ke rekening berikut :</small></p>
                                                    </div>
                                                </div>
                                            </th>
                                            <th class="text-right"><?= $this->lang->line("Sub Total"); ?> Rp.</th>
                                            <td class="text-right text-bold-700"><?= number_format($subtotal); ?></td>
                                        </tr>
                                        <tr>

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
                                            <th class="text-right" colspan="6"><span class="font-medium-3 text-capitalize"><em>(<?= terbilang($grandtotal); ?>)</em></span></th>
                                        </tr>

                                    </tfoot>
                            </table>
                            <div class="table-responsive">
                                <hr class="mt-1 mb-0">
                                <table>
                                    <tr>
                                        <td class="mb-0 font-medium-2">Bank : <strong><?= $company->e_company_account_bank; ?></strong>
                                        </td>
                                        <td class="mb-0 font-medium-2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                        <td class="mb-0 font-medium-2">Bank : <strong><?= $company->e_company_account_bank2; ?></strong>
                                        </td>
                                        <td class="mb-0 font-medium-2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                        <td class="mb-0 font-medium-2">Bank : <strong><?= $company->e_company_account_bank3; ?></strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="mb-0 font-medium-2">Atas Nama : <strong><?= $company->e_company_account_name; ?></strong>
                                        </td>
                                        <td class="mb-0 font-medium-2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                        <td class="mb-0 font-medium-2">Atas Nama : <strong><?= $company->e_company_account_name2; ?></strong>
                                        </td>
                                        <td class="mb-0 font-medium-2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                        <td class="mb-0 font-medium-2">Atas Nama : <strong><?= $company->e_company_account_name3; ?></strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="mb-0 font-medium-2">Nomor Rekening : <strong><?= $company->e_company_account_number; ?></strong>
                                        </td>
                                        <td class="mb-0 font-medium-2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                        <td class="mb-0 font-medium-2">Nomor Rekening : <strong><?= $company->e_company_account_number2; ?></strong>
                                        </td>
                                        <td class="mb-0 font-medium-2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                        <td class="mb-0 font-medium-2">Nomor Rekening : <strong><?= $company->e_company_account_number3; ?></strong>
                                        </td>
                                    </tr>
                                </table>
                                <hr class="mt-0 mb-1">
                            </div>
                        <?php } ?>
                        </div>
                        <!-- <hr class="mt-0 mb-0"> -->

                        <!-- invoice total -->
                        <div class="invoice-total mt-0 mb-0">
                            <div class="row">
                                <!-- <div class="col-sm-12 text-right">
                                    <span class="font-small-3 text-capitalize"><em>(<?= terbilang($grandtotal); ?>)</em></span>
                                </div> -->
                                <div class="col-sm-12 text-right">
                                    <span class="font-medium-3"><strong>Bandung, <?= date('d', strtotime($data->d_nota)) . ' ' . bulan(date('m', strtotime($data->d_nota))) . ' ' . date('Y', strtotime($data->d_nota)); ?></strong></span>
                                </div>
                                <div class="col-sm-12">
                                    <div class="table-responsive">
                                        <table class="table table-xs table-borderless" width="100%;">
                                            <tbody class="text-center">
                                                <tr>
                                                    <td width="25%;">
                                                        <p class="mb-3">Penerima</p>
                                                    </td>
                                                    <td width="25%;">
                                                        <p class="mb-3"></p>
                                                    </td>
                                                    <td width="25%;">
                                                        <p class="mb-3"></p>
                                                    </td>
                                                    <td width="25%;">
                                                        <p class="mb-3">S E & O</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-muted">
                                                        <p class="mt-1">( &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; )</p>
                                                    </td>
                                                    <td class="text-muted">
                                                        <p class="mt-1">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
                                                    </td>
                                                    <td class="text-muted">
                                                        <p class="mt-1">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
                                                    </td>
                                                    <td class="text-muted">
                                                        <p class="mt-1">( &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; )</p>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- buttons section -->
            <div class="col-md-4 col-6 action-btns m-auto no-print">
                <div class="card">
                    <div class="card-body">
                        <a href="#" onclick="window.print();" class="btn btn-secondary btn-block print-invoice"> <i class="feather icon-printer mr-25 common-size"></i> <?= $this->lang->line("Cetak"); ?></a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?= put_footer(); ?>
</body>
<!-- END: Body-->

</html>