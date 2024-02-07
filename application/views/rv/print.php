<html class="loading" lang="en" data-textdirection="ltr">
<!-- BEGIN: Head-->

<head>

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
        /* body {} */

        .table.table-xs th,
        .table td,
        .table.table-xs td {
            padding: 0.4rem 0.4rem;
        }

        .table>tfoot>tr>th,
        .table>tfoot>tr>td {
            border: none !important;
        }

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
                    <div class="card-body p-2 area-print">



                        <div class="invoice-logo-title row">
                            <div class="col-9 d-flex flex-column justify-content-center align-items-start">
                                <!-- <span>Software Development</span> -->
                                <p class="font-large-2 text-uppercase text-primary"><?= $company->e_company_name; ?>
                                    <input type="hidden" id="id" value="<?= $data->i_rv; ?>">
                                    <input type="hidden" id="path" value="<?= base_url($this->folder); ?>">
                                    <br><span class="font-medium-3 text-capitalize"><?= $company->e_company_address; ?></span><br>
                                </p>
                            </div>

                            <div class="col-3 d-flex justify-content-end invoice-logo">
                                <img src="<?= base_url(); ?>assets/images/<?= $company->e_company_logo; ?>" alt="company-logo" height="94" width="149">
                            </div>
                        </div>
                        <hr class="mt-0 mb-0">


                        <!-- invoice address and contacts -->
                        <div class="row mb-1">
                            <div class="col-12">
                                <div class="info-title text-center">
                                    <span class="text-bold-700 font-large-1 text-uppercase">Bukti Penerimaan <?= $data->e_coa_name; ?></span>
                                </div>
                            </div>
                        </div>

                        <!-- invoice address and contacts -->
                        <div class="row invoice-adress-info">
                            <div class="col-6 from-info">
                                <div class="info-title">
                                    <span class="font-medium-3">NO. </span><span class="font-medium-1"><?= $data->i_rv_id; ?></span>
                                </div>
                            </div>
                            <div class="col-6 to-info">
                                <div class="info-title text-right mb-1">
                                    <p class="font-medium-3 text-capitalize"><?= $data->e_area_name . ', ' . date('d', strtotime($data->d_rv)) . ' ' . bulan(date('m', strtotime($data->d_rv))) . ' ' . date('Y', strtotime($data->d_rv)); ?></p>
                                </div>
                            </div>
                        </div>
                        <!--product details table -->
                        <div class="product-details-table mt-0 mb-0">
                            <table class="table table-xs table-bordered mt-0 mb-0 text-capitalize font-medium-3">
                                <thead>
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th class="text-center"><?= $this->lang->line("Perkiraan"); ?></th>
                                        <th class="text-center"><?= $this->lang->line("Tgl Bukti"); ?></th>
                                        <th class="text-center"><?= $this->lang->line("Keterangan"); ?></th>
                                        <th class="text-right"><?= $this->lang->line("Jumlah"); ?></th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php $i = 0;
                                    $subtotal = 0;
                                    $saldo = 0;
                                    $saldoakhir = 0;
                                    if ($detail->num_rows() > 0) {
                                        foreach ($detail->result() as $key) {
                                            $i++; ?>
                                            <tr>
                                                <td class="text-center" valign="center"><?= $i; ?></td>
                                                <td><?= $key->i_coa_id; ?></td>
                                                <td><?= $key->date_bukti; ?></td>
                                                <td><?= $key->e_remark; ?></td>
                                                <td class="text-right"><?= number_format($key->v_rv); ?></td>
                                            </tr>
                                    <?php
                                            $subtotal += $key->v_rv;
                                        }
                                        $saldo = 0;
                                        $saldoakhir = $subtotal + $saldo;
                                    } ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="4" class="text-right"><?= $this->lang->line('Jumlah'); ?> Rp. </th>
                                        <th class="text-right"><?= number_format($subtotal); ?></th>
                                    </tr>
                                    <tr>
                                        <th colspan="5" class="text-right"><span class="text-capitalize"><em>(Terbilang : <?= terbilang($subtotal); ?> Rupiah)</em></span></th>
                                    </tr>
                                </tfoot>

                            </table>
                        </div>
                        <hr class="mt-0 mb-2">

                        <!-- invoice total -->
                        <div class="invoice-total">
                            <div class="row">
                                <div class="col-sm-12">
                                    <table class="table nowrap table-borderless table-xs">
                                        <tbody class="font-medium-3">
                                            <tr>
                                                <td class="text-center">Dibayar :</td>
                                                <td class="text-center">Diperiksa :</td>
                                                <td class="text-center">Diketahui :</td>
                                                <td class="text-center">Disetujui :</td>
                                                <td></td>
                                                <td width="160px" class="text-center">Diterima</td>
                                            </tr>
                                            <tr height="50">
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <?php if ($data->i_coa == '359' or $data->i_coa == '6') { ?>
                                                    <td class="text-center">(<u> Novi </u>)</td>
                                                <?php } else { ?>
                                                    <td class="text-center">(<u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u>)</td>
                                                <?php } ?>
                                                <td class="text-center">(<u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u>)</td>
                                                <td class="text-center">(<u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u>)</td>
                                                <td class="text-center">(<u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u>)</td>
                                                <td class="text-center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u></td>
                                                <td class="text-center">(<u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u>)</td>
                                            </tr>
                                        </tbody>
                                    </table>
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