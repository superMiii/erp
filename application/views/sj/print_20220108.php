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

        @page {
			size: 21cm 29.7cm;
			margin: 0.25in 0.25in 0.25in 0.25in
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
                                <p class="font-large-2 text-uppercase text-primary"><?= $this->lang->line('Surat Jalan'); ?>
                                    <input type="hidden" id="id" value="<?= $data->i_do; ?>">
                                    <input type="hidden" id="path" value="<?= base_url($this->folder); ?>">
                                    <br><span class="font-medium-3 font-weight-bold">Nomor Surat Jalan : <?= $data->i_do_id; ?></span>
                                    <br><span class="font-medium-3 font-weight-bold">Nomor SPB : <?= $data->i_so_id; ?></span>
                                    <?php if ($data->i_po_reff != null) { ?>
                                        <br><span class="font-medium-3 font-weight-bold">Nomor Referensi : <?= $data->i_po_reff; ?></span>
                                    <?php } ?>
                                </p>
                            </div>
                            <div class="col-3 d-flex justify-content-end invoice-logo">
                                <img src="<?= base_url(); ?>assets/images/<?= $company->e_company_logo; ?>" alt="company-logo" height="119" width="191">
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
                                <span class="font-medium-3"><u>Harap diterima barang-barang berikut ini :</u></span>
                            <?php } ?>

                            </div>
                            <div class="col-6 mt-1 to-info text-right">
                                <!-- <div class="info-title mb-1">
                                    <span font-large-1>Bill To</span>
                                </div> -->
                                <div class="company-name mb-1">
                                    <span class="text-muted font-medium-3"><?= $this->lang->line('Pramuniaga'); ?> - <?= '[' . $data->i_salesman_id . '] ' . $data->e_salesman_name; ?></span><br>
                                    <span class="text-muted text-bold-700 font-medium-3"><?= '[' . $data->i_customer_id . '] - ' . $data->e_customer_name; ?></span><br>
                                    <span class="font-medium-3"><?= $data->e_customer_address; ?></span><br>
                                    <span class="font-medium-3"><?= '[' . $data->i_area_id . '] ' . $data->e_area_name . ' - ' . $data->e_city_name; ?></span><br>
                                    <span class="font-medium-3"><?= $data->e_customer_phone; ?></span>
                                </div>
                            </div>
                        </div>


                        <!--product details table -->
                        <div class="product-details-table table-responsive">
                            <table class="table table-xs table-bordered mt-0 mb-0">
                                <thead>
                                    <tr>
                                        <th scope="col" class="text-center" width="3%">No</th>
                                        <th scope="col"><?= $this->lang->line('Nama Barang'); ?></th>
                                        <th class="text-right" scope="col">Qty</th>
                                        <th class="text-right" scope="col"><?= $this->lang->line('Harga'); ?></th>
                                        <th class="text-right" scope="col"><?= $this->lang->line('Total'); ?></th>
                                    </tr>
                                    <!-- <tr>
                                        <td colspan="5">
                                            <li class="dropdown-divider mt-0 mb-0"></li>
                                        </td>
                                    </tr> -->
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
                                            $ppn = $dpp * ($data->n_so_ppn/100);
                                            $grandtotal = $dpp + $ppn;
                                        }
                                    } ?>


                                    <?php if ($grandtotal >= 10000000) { ?>
                                        <tr>
                                            <th class="font-small-2" colspan="3"><?= $this->lang->line('Catatan'); ?> : Nilai Belum Termasuk Bea Meterai Rp. 10.000,-</th>
                                            <th class="text-right">Sub Total</th>
                                            <td class="text-right"><strong><?= number_format($subtotal); ?></strong></td>
                                        </tr>
                                    <?php } else { ?>
                                        <tr>
                                            <th <?php if ($data->f_so_plusppn=='t') {?> rowspan="6" <?php }else{?> rowspan="4" <?php } ?> colspan="3">
                                                <p>&nbsp;</p>
                                                <div class="card border-blue-grey">
                                                    <div class="card-content">
                                                        <div class="text-center p-1">
                                                            <p class="mb-1"><strong>P E N T I N G</strong></p>
                                                            <p class="mb-0"><small>PENERIMA WAJIB TTD DAN ATAU CAP TOKO,</small></p>
                                                            <p class="mb-0"><small>SURAT JALN INI MERUPAKAN BUKTI RESMI PENERIMAAN BARANG,</small></p>
                                                            <p class="mb-0"><small>SURAT JALAN INI BUKAN MERUPAKAN BUKTI TAGIHAN,</small></p>
                                                            <p class="mb-0"><small>TERIMA KOMPLEN BARANG PALING LAMA 3 HARI SETELAH BARANG DITERIMA</small></p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </th>
                                            <th class="text-right">Sub Total</th>
                                            <td class="text-right"><strong><?= number_format($subtotal); ?></strong></td>
                                        </tr>
                                    <?php } ?>
                                    <tr>
                                        <th class="text-right">Diskon Per Item</th>
                                        <td class="text-right"><?= number_format($distotal); ?></td>
                                    </tr>
                                    <tr>
                                        <th class="text-right">Diskon Tambahan</th>
                                        <td class="text-right"><?= number_format($data->v_so_discounttotal); ?></li>
                                        </td>
                                    </tr>
                                    <?php if ($data->f_so_plusppn=='t') {?>
                                        <tr>
                                            <th class="text-right">Nilai Kotor</th>
                                            <td class="text-right"><?= number_format($dpp); ?></td>
                                        </tr>
                                        <tr>
                                            <th class="text-right">Pajak (10%)</th>
                                            <td class="text-right"><?= number_format($ppn); ?></td>
                                        </tr>
                                    <?php } ?>
                                    <tr>
                                        <th class="text-right">Nilai Bersih</th>
                                        <td class="text-right"><strong><?= number_format($grandtotal); ?></strong></td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                </tfoot>
                            </table>
                        </div>
                        <hr class="mt-0 mb-1">

                        <!-- invoice total -->
                        <div class="invoice-total">
                            <div class="row">
                                <div class="col-sm-12 text-right">
                                    <span>Bandung, <?= date('d', strtotime($data->d_so)) . ' ' . bulan(date('m', strtotime($data->d_so))) . ' ' . date('Y', strtotime($data->d_so)); ?>&nbsp;&nbsp;&nbsp;&nbsp;</span>
                                </div>
                                <div class="col-sm-12">
                                    <div class="table-responsive">
                                        <table class="table table-xs table-borderless" width="100%;">
                                            <tbody class="text-center">
                                                <tr>
                                                    <td>
                                                        <p class="mt-0 mb-5">Penerima</p>
                                                    </td>
                                                    <td>
                                                        <p class="mt-0 mb-5">Mengetahui</p>
                                                    </td>
                                                    <td>
                                                        <p class="mt-0 mb-5">Pengirim</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-muted">
                                                        <p class="mt-2">( &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; )</p>
                                                    </td>
                                                    <td class="text-muted">
                                                        <p class="mt-2">( &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; )</p>
                                                    </td>
                                                    <td class="text-muted">
                                                        <p class="mt-2">( &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; )</p>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <br><span class="font-small-3">*Tanggal Cetak : <?= date('d') . ' ' . bulan(date('m')) . ' ' . date('Y') . ' ' . date('H:i:s'); ?></span>
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