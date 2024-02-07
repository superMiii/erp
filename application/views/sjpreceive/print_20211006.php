<html class="loading" lang="en" data-textdirection="ltr">
<!-- BEGIN: Head-->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
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
        @import url('http://fonts.cdnfonts.com/css/fake-receipt');
        /* @import url('https://fonts.googleapis.com/css2?family=Baumans&display=swap'); */

        body {
            /* font-family: 'Baumans', cursive; */
            font-family: 'Fake Receipt', sans-serif;
            /* font-family: 'Merchant Copy Doublesize', sans-serif; */
            /* font-family: 'Merchant Copy Wide', sans-serif; */
            /* font-family: 'Merchant Copy', sans-serif; */
        }

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
                        <!-- card-header -->
                        <!-- <div class="card-header px-0">
                            <div class="row">
                                <div class="col-md-12 col-lg-7 col-xl-4 mb-50">
                                    <span class="invoice-id font-weight-bold">Invoice# </span>
                                    <span>948372</span>
                                </div>
                                <div class="col-md-12 col-lg-5 col-xl-8">
                                    <div class="d-flex align-items-center justify-content-end justify-content-xs-start">
                                        <div class="issue-date pr-2">
                                            <span class="font-weight-bold no-wrap">Issue Date: </span>
                                            <span>07/02/2019</span>
                                        </div>
                                        <div class="due-date">
                                            <span class="font-weight-bold no-wrap">DueDate: </span>
                                            <span>06/04/2019</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> -->


                        <!-- invoice address and contacts -->
                        <div class="row invoice-adress-info mb-1">
                            <div class="col-5 from-info">
                                <div class="company-name mb-1">
                                    <span class="text-bold-600 font-medium-3"><?= strtoupper($this->session->e_company_name); ?></span>
                                </div>
                                <div class="info-title mb-1">
                                    <span><?= $company->e_company_address; ?></span>
                                </div>
                                <?php if ($company->e_company_npwp_code != null) { ?>
                                    <div class="info-title mb-1">
                                        <span class="text-muted"><?= $this->lang->line('NPWP'); ?> - <?= $company->e_company_npwp_code; ?></span>
                                    </div>
                                <?php } ?>
                                <?php if ($company->e_company_phone != null) { ?>
                                    <div class="info-title mb-1">
                                        <span class="text-muted"><?= $this->lang->line('Telepon'); ?> - <?= $company->e_company_phone; ?></span>
                                    </div>
                                <?php } ?>
                                <div class="info-title mb-1">
                                    <span class="text-muted"><?= $this->lang->line('Nomor Dokumen'); ?> : </span><span class="font-medium-2"><?= $data->i_do_id; ?></span>
                                </div>
                                <div class="info-title mb-1">
                                    <span class="text-muted"><?= $this->lang->line('Nomor Pesanan'); ?> : <?= $data->i_so_id; ?></span>
                                </div>
                                <?php if ($data->i_po_reff != null) { ?>
                                    <div class="info-title mb-1">
                                        <span class="text-muted"><?= $this->lang->line('Nomor Referensi'); ?> - <?= $data->i_po_reff; ?></span>
                                    </div>
                                <?php } ?>
                            </div>
                            <div class="col-7 to-info">
                                <div class="info-title text-right mb-1">
                                    <p class="font-large-1 text-capitalize"><?= $this->lang->line('Surat Jalan'); ?></p>
                                    <!-- <h2 class="text-uppercase text-bold-600">surat permintaan barang</h2> -->
                                </div>
                                <div class="company-name text-right mb-1">
                                    <input type="hidden" id="id" value="<?= $data->i_so; ?>">
                                    <input type="hidden" id="path" value="<?= base_url($this->folder); ?>">
                                    <span class="text-bold-600 font-medium-2"><?= '[' . $data->i_customer_id . '] - ' . $data->e_customer_name; ?></span>
                                </div>
                                <div class="company-address text-right mb-1">
                                    <span class="text-muted"><?= $data->e_customer_address; ?></span>
                                </div>
                                <div class="company-name text-right mb-1">
                                    <span class="text-muted"><?= '[' . $data->i_area_id . '] ' . $data->e_area_name . ' - ' . $data->e_city_name; ?></span>
                                </div>
                                <div class="company-name text-right">
                                    <span class="text-muted"><?= $this->lang->line('Telepon') . ' - ' . $data->e_customer_phone; ?></span>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <p class="text-muted font-small-2">Harap diterima barang-barang berikut ini :</p>
                            </div>
                        </div>

                        <!--product details table -->
                        <div class="product-details-table table-responsive">
                            <table class="table table-xs table-borderless">
                                <thead>
                                    <tr>
                                        <th scope="col" class="text-center" width="3%">No</th>
                                        <th scope="col"><?= $this->lang->line('Nama Barang'); ?></th>
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
                                            $total = $key->v_unit_price * $key->n_do;
                                            $v_diskon1 = $total * ($key->n_so_discount1 / 100);
                                            $v_diskon2 = ($total - $v_diskon1) * ($key->n_so_discount2 / 100);
                                            $v_diskon3 = ($total - $v_diskon1 - $v_diskon2) * ($key->n_so_discount3 / 100);
                                            $v_total_discount = $v_diskon1 + $v_diskon2 + $v_diskon3;
                                    ?>
                                            <tr>
                                                <td class="text-center"><?= $i; ?></td>
                                                <td><?= $key->i_product_id . ' - ' . $key->e_product_name; ?></td>
                                                <td class="text-right"><?= $key->n_do; ?></td>
                                                <td class="text-right"><?= number_format($key->v_unit_price); ?></td>
                                                <td class="text-right"><?= number_format($total); ?></td>
                                            </tr>
                                    <?php
                                            $subtotal += $total;
                                            $distotal += $v_total_discount;
                                        }
                                        $dpp = $subtotal - $distotal - $data->v_so_discounttotal;
                                        $ppn = $dpp * 0.1;
                                        $grandtotal = $dpp + $ppn;
                                    } ?>
                                    <tr>
                                        <td colspan="5">
                                            <li class="dropdown-divider"></li>
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <?php if ($grandtotal >= 10000000) { ?>
                                        <tr>
                                            <th class="font-small-2" colspan="3"><?= $this->lang->line('Catatan'); ?> : Nilai Belum Termasuk Bea Meterai Rp. 10.000,-</th>
                                            <th class="text-right">Sub Total</th>
                                            <td class="text-right"><?= number_format($subtotal); ?></td>
                                        </tr>
                                    <?php } else { ?>
                                        <tr>
                                            <th class="text-right" colspan="4">Sub Total</th>
                                            <td class="text-right"><?= number_format($subtotal); ?></td>
                                        </tr>
                                    <?php } ?>
                                    <tr>
                                        <th class="text-right" colspan="4">Diskon Per Item</th>
                                        <td class="text-right"><?= number_format($distotal); ?></td>
                                    </tr>
                                    <tr>
                                        <th class="text-right" colspan="4">Diskon Tambahan</th>
                                        <td class="text-right"><?= number_format($data->v_so_discounttotal); ?><li class="dropdown-divider"></li>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-right" colspan="4">Nilai Kotor</th>
                                        <td class="text-right"><?= number_format($dpp); ?></td>
                                    </tr>
                                    <tr>
                                        <th class="text-right" colspan="4">Pajak (10%)</th>
                                        <td class="text-right"><?= number_format($ppn); ?><li class="dropdown-divider"></li>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-right" colspan="4">Nilai Bersih</th>
                                        <td class="text-right"><?= number_format($grandtotal); ?></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <hr>

                        <!-- invoice total -->
                        <div class="invoice-total">
                            <div class="row">
                                <div class="col-sm-12">
                                    <span>Cimahi, <?= date('d', strtotime($data->d_so)) . ' ' . bulan(date('m', strtotime($data->d_so))) . ' ' . date('Y', strtotime($data->d_so)); ?></span>
                                </div>
                                <div class="col-sm-12">
                                    <div class="table-responsive">
                                        <table class="table table-xs table-borderless" width="100%;">
                                            <tbody class="text-center">
                                                <tr>
                                                    <td>
                                                        <p class="mt-2 mb-5">Penerima</p>
                                                    </td>
                                                    <td>
                                                        <p class="mt-2 mb-5">Mengetahui</p>
                                                    </td>
                                                    <td>
                                                        <p class="mt-2 mb-5">Pengirim</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-muted">
                                                        <p class="mt-2">( &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; )</p>
                                                    </td>
                                                    <td class="text-muted">
                                                        <p class="mt-2">( &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; )</p>
                                                    </td>
                                                    <td class="text-muted">
                                                        <p class="mt-2">( &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; )</p>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <span class="font-small-2">Tanggal Cetak : <?= date('d') . ' ' . bulan(date('m')) . ' ' . date('Y') . ' ' . date('H:i:s'); ?></span>
                                </div>
                                <!-- <div class="col-4 col-sm-12 mt-75">
                                    <p>Demikian surat ini kami sampaikan, atas perhatian dan kerjasamanya kami ucapkan terima kasih.</p>
                                </div>
                                <div class="col-8 col-sm-12 d-flex text-center justify-content-end mt-75">
                                    <ul class="list-group cost-list">
                                        <li class="list-group-item each-cost border-0 p-50 d-flex justify-content-between">
                                            <span class="cost-title mb-5 mr-2">Menyetujui, </span>
                                        </li>
                                        <li class="list-group-item each-cost border-0 p-50 d-flex justify-content-between">
                                            <span class="cost-title mr-2">( Supervisor Administrasi )</span>
                                        </li>
                                        <li class="dropdown-divider"></li>
                                    </ul>
                                </div> -->
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