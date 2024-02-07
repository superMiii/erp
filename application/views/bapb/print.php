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

        .table-note td {
            padding: 0.0rem 0.1rem;
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

<body oncontextmenu='return false;' onkeydown='return false;' onmousedown='return false;'>

<!-- <body> -->
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
                        <div class="row invoice-adress-info">
                            <div class="col-6 from-info">
                                <div class="info-title">
                                    <span class="text-muted font-small-2"><?= $this->lang->line('Nomor Dokumen'); ?> : </span><span class="font-medium-1"><?= $data->i_sl_id; ?></span>
                                </div>
                                <div class="info-title">
                                    <span class="text-muted font-small-2"><?= $this->lang->line('Kirim Ke') . ' : ' . $data->e_sl_kirim_name . ' - [' . $data->i_area_id . '] ' . $data->e_area_name; ?></span>
                                </div>
                                <div class="info-title">
                                    <span class="text-muted font-small-2"><?= $this->lang->line('Kirim Via') . ' : ' . $data->e_sl_via_name; ?></span>
                                </div>
                                <div class="info-title">
                                    <span class="text-muted font-small-2"><?= $this->lang->line('Nama Supir') . ' : ' . $data->e_sopir_name; ?></span>
                                </div>
                            </div>
                            <div class="col-6 to-info">
                                <div class="company-name text-right mb-1">
                                    <input type="hidden" id="id" value="<?= $data->i_sl; ?>">
                                    <input type="hidden" id="path" value="<?= base_url($this->folder); ?>">
                                    <span class="text-bold-600 font-medium-3"><?= $this->session->e_company_name; ?></span>
                                </div>
                                <div class="info-title text-right mb-1">
                                    <p class="font-medium-3 text-capitalize"><?= $this->lang->line('Daftar Kiriman Barang'); ?></p>
                                </div>
                            </div>
                        </div>
                        <!--product details table -->
                        <div class="product-details-table table-responsive mt-0 mb-0">
                            <table class="table table-xs table-bordered mt-0 mb-0">
                                <thead class="font-small-2">
                                    <tr>
                                        <th scope="col" rowspan="2" class="text-center" width="3%">No</th>
                                        <th scope="col" colspan="3" class="text-center"><?= $this->lang->line('Debitur'); ?></th>
                                        <th scope="col" colspan="4" class="text-center"><?= $this->lang->line('Surat Jalan'); ?></th>
                                        <th scope="col" rowspan="2" class="text-center"><?= $this->lang->line('Keterangan'); ?></th>
                                    </tr>
                                    <tr>
                                        <th scope="col" class="text-center"><?= $this->lang->line('Kode'); ?></th>
                                        <th scope="col" class="text-center"><?= $this->lang->line('Nama'); ?></th>
                                        <th scope="col" class="text-center"><?= $this->lang->line('Kota'); ?></th>
                                        <th scope="col" class="text-center"><?= $this->lang->line('No SJ'); ?></th>
                                        <th scope="col" class="text-center"><?= $this->lang->line('Tgl SJ'); ?></th>
                                        <th scope="col" class="text-center"><?= $this->lang->line('Tgl SO'); ?></th>
                                        <th scope="col" class="text-center"><?= $this->lang->line('Nilai'); ?></th>
                                    </tr>
                                </thead>
                                <tbody class="font-small-1">
                                    <?php $i = 0;
                                    $total = 0;
                                    if ($detail->num_rows() > 0) {
                                        foreach ($detail->result() as $key) {
                                            $i++;
                                    ?>
                                            <tr>
                                                <td class="text-center"><?= $i; ?></td>
                                                <td><?= $key->i_customer_id; ?></td>
                                                <td><?= $key->e_customer; ?></td>
                                                <td><?= $key->e_city_name; ?></td>
                                                <td><?= $key->i_do_id; ?></td>
                                                <td><?= date('d-m-Y', strtotime($key->d_do)); ?></td>
                                                <td><?= date('d-m-Y', strtotime($key->d_so)); ?></td>
                                                <td class="text-right"><?= number_format($key->v_jumlah); ?></td>
                                                <td><?= $key->e_remark; ?></td>
                                            </tr>
                                    <?php
                                        $total += $key->v_jumlah;
                                        }
                                    } ?>
                                </tbody>
                                <tfoot class="font-small-2">
                                    <tr>
                                        <th colspan="7" class="text-right"><?= $this->lang->line('Jumlah'); ?> Rp. </th>
                                        <td class="text-right"><?= number_format($total); ?></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <hr class="mt-0 mb-1">

                        <!-- invoice total -->
                        <div class="invoice-total">
                            <div class="row">
                                <div class="col-sm-12 text-right">
                                    <span class="font-small-2">Cimahi, <?= date('d', strtotime($data->d_sl)) . ' ' . bulan(date('m', strtotime($data->d_sl))) . ' ' . date('Y', strtotime($data->d_sl)); ?></span>
                                </div>
                                <div class="col-sm-12">
                                    <table class="table nowrap table-borderless table-xs">
                                        <tbody class="font-small-2">
                                            <tr>
                                                <td class="text-center" colspan="2">Berangkat</td>
                                                <td class="text-center" colspan="2">Kembali</td>
                                                <td class="text-center" colspan="2">Diterima Oleh,</td>
                                            </tr>
                                            <tr height="80">
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td class="text-center">(Kep.Gudang)</td>
                                                <td class="text-center">(Ekspeditur)</td>
                                                <td class="text-center">(Kep.Gudang)</td>
                                                <td class="text-center">(Ekspeditur)</td>
                                                <td class="text-center">(&nbsp;&nbsp;&nbsp;Kasir&nbsp;&nbsp;&nbsp;)</td>
                                                <td class="text-center">(Adm Piutang)</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-sm-12">
                                    <table class="table nowrap table-borderless table-note" style="width:50%">
                                        <tbody class="font-small-2">
                                            <tr>
                                                <td>Putih</td>
                                                <td>:</td>
                                                <td>Pusat</td>
                                            </tr>
                                            <tr>
                                                <td>Merah</td>
                                                <td>:</td>
                                                <td>Admin Piutang</td>
                                            </tr>
                                            <tr>
                                                <td>Kuning</td>
                                                <td>:</td>
                                                <td>Gudang</td>
                                            </tr>
                                            <tr>
                                                <td style="width:30%;">Tanggal Cetak</td>
                                                <td>:</td>
                                                <td><?= date('d') . ' ' . bulan(date('m')) . ' ' . date('Y') . ' ' . date('H:i:s'); ?></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <!-- <div class="col-sm-12">
                                    <span class="font-small-2">Merah : Admin Piutang</span>
                                </div>
                                <div class="col-sm-12">
                                    <span class="font-small-2">Kuning : Gudang</span>
                                </div>
                                <div class="col-sm-12">
                                    <span class="font-small-2">Tanggal Cetak : <?= date('d') . ' ' . bulan(date('m')) . ' ' . date('Y') . ' ' . date('H:i:s'); ?></span>
                                </div> -->
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