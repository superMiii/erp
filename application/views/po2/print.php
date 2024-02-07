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
                    <div class="card-body">
                        <!-- card-header -->
                        <input type="hidden" id="id" value="<?= $data->i_po; ?>">

                        <!-- invoice logo and title -->
                        <div class="invoice-logo-title row">
                            <div class="col-9 d-flex flex-column justify-content-center align-items-start">
                                <!-- <span>Software Development</span> -->
                                <p class="font-large-2 text-uppercase text-primary"><?= $this->lang->line('Pesanan Pembelian'); ?><br><span class="font-medium-3 font-weight-bold">NOMOR NOTA : <?= $data->i_po_id; ?></span></p>
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
                                    <span class="text-muted text-bold-700 font-large-1"><?= $company->e_company_name; ?></span><br>
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
                                <div class="company-name mb-1">
                                    <span class="font-medium-3">Tanggal Pesanan : <?= $data->d_po; ?></span><br>
                                    <span class="text-muted text-bold-700 font-large-1"><?= $data->e_supplier_name; ?></span><br>
                                    <span class="font-medium-3">PPN : <?= $data->f_supplier_pkp; ?></span><br>
                                    <span class="font-medium-3" colspan="4"><?= $this->lang->line('Masa Bayar'); ?> :
                                        <?= $data->n_supplier_top; ?> Hari</span><br>
                                </div>
                            </div>
                        </div>


                        <!--product details table -->
                        <input type="hidden" id="id" value="<?= $data->i_po; ?>">
                        <input type="hidden" id="path" value="<?= base_url($this->folder); ?>">
                        <div class="product-details-table table-responsive">
                            <table class="table table-xs table-bordered mt-0 mb-0">
                                <thead>
                                    <tr>
                                        <th rowspan="2" class="text-center" width="5%" valign="center">No</th>
                                        <th rowspan="2" class="text-center" width="30%" valign="center"><?= $this->lang->line("Nama Barang"); ?></th>
                                        <th rowspan="2" class="text-center" width="12%" valign="center"><?= $this->lang->line("Harga"); ?></th>
                                        <th rowspan="2" class="text-center" width="8%" valign="center"><?= $this->lang->line("Qty"); ?></th>
                                        <th rowspan="1" class="text-center" colspan="2" width="14%"><?= $this->lang->line("Diskon1"); ?></th>
                                        <th rowspan="2 " class="text-center" width="14%" valign="center"><?= $this->lang->line("Total"); ?></th>
                                    </tr>
                                    <tr>
                                        <th class="text-center" rowspan="1" colspan="1" width="6%">%</th>
                                        <th class="text-center" rowspan="1" colspan="1" width="8%">Rp</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $i = 0;
                                    $totalll = 0;
                                    if ($detail->num_rows() > 0) {
                                        foreach ($detail->result() as $key) {
                                            $i++;
                                    ?>
                                            <tr>
                                                <td class="text-center" width="5%" valign="center"><?= $i; ?></td>
                                                <td class="text-center" width="30%" valign="center"><?= $key->idp . ' - ' . $key->namap; ?></td>
                                                <td class="text-center" width="12%" valign="center"><?= number_format($key->v_product_mill, 2); ?></td>
                                                <td class="text-center" width="8%" valign="center"><?= $key->n_order; ?></td>
                                                <td class="text-center" colspan="1" width="6%"><?= $key->n_po_discount; ?></td>
                                                <td class="text-center" colspan="1" width="8%"><?= $key->v_po_discount; ?></td>
                                                <td class="text-center" width="14%" valign="center"><?= number_format($key->tot, 2); ?></td>
                                            </tr>
                                    <?php
                                            $totalll += $key->tot;
                                        }
                                    } ?>
                                </tbody>

                                <tfoot>
                                    <tr>
                                        <th colspan="5">&nbsp;</th>
                                        <th class="text-right"><?= $this->lang->line("Total"); ?> Rp.</th>
                                        <td class="text-right text-bold-700"><?= number_format($totalll, 2); ?></td>
                                    </tr>

                                    <tr>
                                        <th class="text-right" colspan="7"><span class="font-medium-3 text-capitalize"><em>(<?= terbilang($totalll); ?>)</em></span></th>
                                    </tr>

                                </tfoot>
                            </table>


                            <!-- invoice total -->
                            <hr class="mt-0 mb-1">
                            <p>&nbsp;</p>
                            <div class="invoice-total mt-0 mb-0">
                                <div class="row">
                                    <!-- <div class="col-sm-12 text-right">
                                    <span class="font-small-3 text-capitalize"><em>(<?= terbilang($grandtotal); ?>)</em></span>
                                </div> -->
                                    <div class="col-sm-12 text-right">
                                        <span class="font-medium-3"><strong>Bandung, <?= date('d', strtotime($data->d_po)) . ' ' . bulan(date('m', strtotime($data->d_po))) . ' ' . date('Y', strtotime($data->d_po)); ?></strong></span>
                                    </div><br>
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
                                                            <p class="mb-3">Disetujui</p>
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