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
                    <div class="card-body area-print">


                        <div class="invoice-logo-title row">
                            <div class="col-9 d-flex flex-column justify-content-center align-items-start">
                                <!-- <span>Software Development</span> -->
                                <p class="font-large-2 text-uppercase text-primary"><?= $this->lang->line('Surat Pemesanan Barang'); ?>
                                    <input type="hidden" id="id" value="<?= $data->i_so; ?>">
                                    <input type="hidden" id="path" value="<?= base_url($this->folder); ?>">
                                    <br><span class="font-medium-3 font-weight-bold">Nomor SPB : <?= $data->i_so_id; ?></span>
                                    <br><span class="font-medium-3 font-weight-bold">Tanggal SPB : <?= date('d', strtotime($data->d_so)) . ' ' . bulan(date('m', strtotime($data->d_so))) . ' ' . date('Y', strtotime($data->d_so)); ?></span>
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
                                    <span class="text-muted text-bold-700 font-large-1"><?= $company->e_company_name; ?></span><br>
                                    <span class="font-medium-3 text-capitalize"><?= $company->e_company_address; ?></span><br>
                                    <?php if ($company->e_company_npwp_code != null) { ?>
                                        <span class="font-medium-3"><?= $this->lang->line('NPWP'); ?> - <?= $company->e_company_npwp_code; ?></span><br>
                                    <?php } ?>
                                    <?php if ($company->e_company_phone != null) { ?>
                                        <span class="font-medium-3"><?= $this->lang->line('Telepon'); ?> - <?= $company->e_company_phone; ?></span>
                                </div>
                            <?php } ?>
                            <span class="text-muted text-bold-700 font-large-1"><?= $this->lang->line('Pramuniaga'); ?> - <?= '[' . $data->i_salesman_id . '] ' . $data->e_salesman_name; ?></span><br>

                            </div>
                            <div class="col-6 mt-1 to-info text-right">
                                <!-- <div class="info-title mb-1">
                                    <span font-large-1>Bill To</span>
                                </div> -->
                                <div class="company-name mb-1">
                                    <span class="text-muted text-bold-700 font-large-1"><?= '[' . $data->i_customer_id . '] - ' . $data->e_customer_name; ?></span><br>
                                    <span class="font-medium-3"><?= $data->e_customer_address; ?></span><br>
                                    <!-- <span class="font-medium-3"><?= '[' . $data->i_area_id . '] ' . $data->e_area_name . ' - ' . $data->e_city_name; ?></span><br> -->
                                    <span class="font-medium-3"><?= $this->lang->line('Area'); ?> <?= $data->i_area_id . ' - ' . $data->e_area_name; ?></span><br>
                                    <span class="font-medium-3"><?= $data->e_customer_phone; ?></span><br>
                                    <span class="font-medium-3" colspan="4"><?= $this->lang->line('Masa Bayar'); ?> :
                                        <?= $data->n_so_toplength; ?> Hari</span>
                                </div>
                            </div>
                        </div>


                        <!--product details table -->
                        <div class="product-details-table table-responsive">
                            <div class="table-responsive">
                                <table class="table table-xs table-bordered mt-0 mb-0">
                                    <thead>
                                        <tr>
                                            <th scope="col" class="text-center" width="3%">No</th>
                                            <th scope="col"><?= $this->lang->line('Nama Barang'); ?></th>
                                            <th scope="col"><?= $this->lang->line('Keterangan'); ?></th>
                                            <th class="text-right" scope="col">Qty <br> Pesanan</th>
                                            <th class="text-right" scope="col">Qty <br> Pemenuhan</th>
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
                                                $total = $key->v_unit_price * $key->n_order;
                                                $v_diskon1 = $total * ($key->n_so_discount1 / 100);
                                                $v_diskon2 = ($total - $v_diskon1) * ($key->n_so_discount2 / 100);
                                                $v_diskon3 = ($total - $v_diskon1 - $v_diskon2) * ($key->n_so_discount3 / 100);
                                                $v_total_discount = $v_diskon1 + $v_diskon2 + $v_diskon3;
                                        ?>
                                                <tr>
                                                    <td class="text-center"><?= $i; ?></td>
                                                    <td><?= $key->i_product_id . ' - ' . $key->e_product_name; ?></td>
                                                    <td><?= $key->e_remark; ?></td>
                                                    <td class="text-right"><?= $key->n_order; ?></td>
                                                    <td></td>
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
                                        } ?>
                                        <!-- <tr>
                                        <td colspan="6"><li class="dropdown-divider mt-0 mb-0"></li></td>
                                    </tr> -->
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="4"><strong>KETERANGAN : <?= $data->e_remark; ?></strong></td>
                                            <th class="text-right" colspan="2"><?= $this->lang->line('Sub Total'); ?> Rp. </th>
                                            <td class="text-right"><strong><?= number_format($subtotal); ?></strong></td>
                                        </tr>
                                        <tr>
                                            <td colspan="4">Plafond : Rp. 0,-</td>
                                            <th class="text-right" colspan="2"><?= $this->lang->line('Diskon Per Item'); ?> Rp. </th>
                                            <td class="text-right"><?= number_format($distotal); ?></td>
                                        </tr>
                                        <tr>
                                            <td colspan="4">Rata-rata Keterlambatan : 0 Hari</td>
                                            <th class="text-right" colspan="2"><?= $this->lang->line('Diskon Tambahan'); ?> Rp. </th>
                                            <td class="text-right"><?= number_format($data->v_so_discounttotal); ?></li>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="4">Saldo Piutang : Rp. 0,-</td>
                                            <?php if ($data->f_so_plusppn == 't') { ?>
                                                <th class="text-right" colspan="2"><?= $this->lang->line('DPP'); ?> Rp. </th>
                                                <td class="text-right"><?= number_format($dpp); ?></td>
                                            <?php } else { ?>
                                                <th class="text-right" colspan="2"><?= $this->lang->line('Nilai Bersih'); ?> Rp. </th>
                                                <th class="text-right"><?= number_format($grandtotal); ?></th>
                                            <?php } ?>
                                        </tr>
                                        <tr>
                                            <td colspan="4">Jumlah SPB : 0</td>
                                            <?php if ($data->f_so_plusppn == 't') { ?>
                                                <th class="text-right" colspan="2"><?= $this->lang->line('PPN'); ?> (<?= $data->n_so_ppn; ?>%) Rp. </th>
                                                <td class="text-right"><?= number_format($ppn); ?></li>
                                                </td>
                                            <?php } ?>
                                        </tr>
                                        <tr>
                                            <td colspan="4">Riwayat Penjualan : 0</td>
                                            <?php if ($data->f_so_plusppn == 't') { ?>
                                                <th class="text-right" colspan="2"><?= $this->lang->line('Nilai Bersih'); ?> Rp. </th>
                                                <th class="text-right font-medium-3"><?= number_format($grandtotal); ?></th>
                                            <?php } ?>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        <!-- invoice total -->
                        <div class="invoice-total">
                            <div class="row">
                                <!-- <div class="col-sm-12">
                                    <span>Demikian surat ini kami sampaikan, atas perhatian dan kerjasamanya kami ucapkan terima kasih.</span>
                                </div> -->
                                <div class="col-sm-12">
                                    <div class="table-responsive">
                                        <table class="table table-xs table-borderless mt-0 mb-0" width="100%;">
                                            <tbody class="text-center">
                                                <tr>
                                                    <td>
                                                        <p>&nbsp;</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <span class="mb-2">Hormat kami</span>
                                                    </td>
                                                    <td>
                                                        <span class="mb-2">Disetujui</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-muted">
                                                        <p class="mt-3">( &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; )</p>
                                                    </td>
                                                    <td class="text-muted">
                                                        <p class="mt-3">( &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; )</p>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-sm-12 mt-0 mb-0">
                                    <span>*Tanggal Cetak : <?= date('d') . ' ' . bulan(date('m')) . ' ' . date('Y') . ' ' . date('H:i:s'); ?></span>
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