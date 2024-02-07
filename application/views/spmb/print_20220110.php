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
                                    <input type="hidden" id="id" value="<?= $data->i_sr; ?>">
                                    <input type="hidden" id="path" value="<?= base_url($this->folder); ?>">
                                    <br><span class="font-medium-3 text-capitalize"><?= $company->e_company_address; ?></span><br>
                                    <?php if ($company->e_company_npwp_code != null) { ?>
                                        <span class="font-medium-3"><?= $this->lang->line('NPWP'); ?> - <?= $company->e_company_npwp_code; ?></span><br>
                                    <?php } ?>
                                    <?php if ($company->e_company_phone != null) { ?>
                                        <span class="font-medium-3"><?= $this->lang->line('Telepon'); ?> - <?= $company->e_company_phone; ?></span>
                                </p>
                            </div>
                        <?php } ?>

                        <div class="col-3 d-flex justify-content-end invoice-logo">
                            <img src="<?= base_url(); ?>assets/images/<?= $company->e_company_logo; ?>" alt="company-logo" height="119" width="191">
                        </div>
                        </div>
                        <hr class="mt-0 mb-0">


                        <!-- invoice address and contacts -->
                        <div class="row invoice-adress-info mb-1">
                            <div class="col-6 mt-1 from-info">
                                <div class="company-name mb-1">
                                    <span class="font-medium-3">Kepada Yth,</span>
                                </div>
                                <div class="info-title mb-1">
                                    <span class="font-medium-3">Bagian Pembelian</span>
                                </div>
                                <div class="company-name mb-1">
                                    <span class="text-muted text-bold-700 font-medium-3"><?= $this->session->e_company_name; ?></span>
                                </div>
                            </div>
                            <div class="col-6 mt-1 to-info">
                                <div class="info-title text-center mb-1">
                                    <h2 class="text-uppercase text-bold-700"><?= $this->lang->line('Surat Permintaan Barang'); ?></h2>
                                </div>
                                <div class="company-name text-center mb-1">
                                    <input type="hidden" id="id" value="<?= $data->i_sr; ?>">
                                    <input type="hidden" id="path" value="<?= base_url($this->folder); ?>">
                                    <span class="font-medium-3">No. Dokumen : <strong><?= $data->i_sr_id . '</strong> / ' . $data->i_area_id . ' - ' . $data->e_area_name; ?></span>
                                </div>
                                <div class="company-address text-center mb-1">
                                    <span class="font-medium-3">Tanggal Dokumen : <?= date('d', strtotime($data->d_sr)) . ' ' . bulan(date('m', strtotime($data->d_sr))) . ' ' . date('Y', strtotime($data->d_sr)); ?></span>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <span class="font-medium-3">Dengan hormat,</span>
                                <p class="font-medium-3">Bersama surat ini kami mohon dikirimkan barang-barang sebagai berikut :</p>
                            </div>
                        </div>

                        <!--product details table -->
                        <div class="product-details-table table-responsive">
                            <div class="table-responsive">
                                <table class="table table-xs table-bordered mt-0 mb-0">
                                    <thead>
                                        <tr>
                                            <th scope="col" class="text-center" width="5%">No</th>
                                            <th scope="col">Kode</th>
                                            <th scope="col">Nama Barang</th>
                                            <th scope="col">Keterangan</th>
                                            <th scope="col" class="text-right">Jumlah</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $i = 0;
                                        if ($detail->num_rows() > 0) {
                                            foreach ($detail->result() as $key) {
                                                $i++; ?>
                                                <tr>
                                                    <td class="text-center"><?= $i; ?></td>
                                                    <td><?= $key->i_product_id; ?></td>
                                                    <td><?= $key->e_product_name; ?></td>
                                                    <td><?= $key->e_remark; ?></td>
                                                    <td class="text-right"><?= $key->n_order; ?></td>
                                                </tr>
                                        <?php }
                                        } ?>
                                    </tbody>
                                </table>
                            </div>

                        </div>
                        <div class="col-sm-12">
                            <br><span class="font-medium-3">Demikian surat ini kami sampaikan, atas perhatian dan kerjasamanya kami ucapkan terima kasih.</span><br>
                            <p>&nbsp;</p><br>
                        </div>



                        <div class="invoice-total mt-0 mb-0">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="table-responsive">
                                        <table class="table table-xs table-borderless" width="100%;">
                                            <tbody class="text-center">
                                                <tr>
                                                    <td width="25%;">
                                                        <p class="mb-3">Hormat Kami,</p>
                                                    </td>
                                                    <td width="25%;">
                                                        <p class="mb-3"></p>
                                                    </td>
                                                    <td width="25%;">
                                                        <p class="mb-3"></p>
                                                    </td>
                                                    <td width="25%;">
                                                        <p class="mb-3">Menyetujui,</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-muted">
                                                        <p class="mt-1">( Kepala Gudang )</p>
                                                    </td>
                                                    <td class="text-muted">
                                                        <p class="mt-1">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
                                                    </td>
                                                    <td class="text-muted">
                                                        <p class="mt-1">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
                                                    </td>
                                                    <td class="text-muted">
                                                        <p class="mt-1">( Supervisor Administrasi )</p>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <br><span class="font-small-3">*Tanggal Cetak : <?= date('d') . ' ' . bulan(date('m')) . ' ' . date('Y') . ' ' . date('H:i:s'); ?></span>
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