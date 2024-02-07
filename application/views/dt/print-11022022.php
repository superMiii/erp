<html class="loading" lang="en" data-textdirection="ltr">
<!-- BEGIN: Head-->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <title>Print | <?= $this->title; ?></title>
    <link rel="apple-touch-icon" href="<?= base_url(); ?>app-assets/images/ico/apple-icon-120.png">
    <link rel="shortcut icon" type="image/x-icon" href="<?= base_url(); ?>app-assets/images/ico/favicon.ico">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,300i,400,400i,500,500i%7COpen+Sans:300,300i,400,400i,600,600i,700,700i" rel="stylesheet">
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

        body {
            /* font-family: 'Baumans', cursive; */
            /* font-family: 'Fake Receipt', sans-serif; */
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

        @page {
            size: landscape;
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
                        <div class="invoice-logo-title row">
                            <div class="col-8 d-flex flex-column justify-content-center align-items-start">
                                <div class="row">
                                    <input type="hidden" id="id" value="<?= $data->i_dt; ?>">
                                    <input type="hidden" id="path" value="<?= base_url($this->folder); ?>">
                                    <div class="col-sm-2">
                                        <img src="<?= base_url(); ?>assets/images/<?= $company->e_company_logo; ?>" alt="company-logo" height="60" width="100">
                                    </div>
                                    <div class="col-sm-10">
                                        <span class="font-large-1 ml-1"><?= strtoupper($this->session->e_company_name); ?></span><br>
                                        <span class="font-small-2 ml-1"><?= $company->e_company_address; ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-4 d-flex justify-content-end invoice-logo">
                                <span class="font-large-1 text-uppercase mt-1 text-bold-600"><?= $this->lang->line('Daftar Tagihan'); ?></span>
                            </div>
                        </div>
                        <hr class="mt-1 mb-1">


                        <!-- invoice address and contacts -->
                        <div class="row invoice-adress-info">
                            <div class="col-6 from-info">
                                <div class="info-title">
                                    <span class="text-muted">NO. </span><span class="font-medium-1"><?= $data->i_dt_id; ?></span>
                                </div>
                            </div>
                            <div class="col-6 to-info">
                                <div class="info-title text-right mb-1">
                                    <p class="font-medium-3 text-capitalize"><?= $data->e_area_name . ', ' . date('d', strtotime($data->d_dt)) . ' ' . bulan(date('m', strtotime($data->d_dt))) . ' ' . date('Y', strtotime($data->d_dt)); ?></p>
                                </div>
                            </div>
                        </div>
                        <!--product details table -->
                        <div class="product-details-table mt-0 mb-0">
                            <table class="table table-xs table-bordered mt-0 mb-0 text-uppercase">
                                <thead class="font-small-2">
                                    <tr>
                                        <th scope="col" rowspan="2" class="text-center" width="3%">No</th>
                                        <th scope="col" colspan="3" class="text-center" width="10%"><?= $this->lang->line('Faktur'); ?></th>
                                        <th scope="col" colspan="3" class="text-center" width="10%"><?= $this->lang->line('Debitur'); ?></th>
                                        <th scope="col" rowspan="2" class="text-center" width="5%"><?= $this->lang->line('Jumlah'); ?></th>
                                        <th scope="col" rowspan="2" class="text-center" width="10%"><?= $this->lang->line('Tunai'); ?></th>
                                        <th scope="col" colspan="4" class="text-center">Transfer / Cek / Giro Bilyet</th>
                                        <th scope="col" rowspan="2" class="text-center" width="10%"><?= $this->lang->line('Total'); ?></th>
                                        <th scope="col" rowspan="2" class="text-center" width="14%"><?= $this->lang->line('Catatan'); ?></th>
                                    </tr>
                                    <tr>
                                        <th scope="col" class="text-center" width="5%"><?= $this->lang->line('No'); ?></th>
                                        <th scope="col" class="text-center" width="5%"><?= $this->lang->line('Tgl'); ?></th>
                                        <th scope="col" class="text-center" width="5%"><?= $this->lang->line('Jt'); ?></th>
                                        <th scope="col" class="text-center" width="5%"><?= $this->lang->line('Kode'); ?></th>
                                        <th scope="col" class="text-center" width="10%"><?= $this->lang->line('Nama'); ?></th>
                                        <th scope="col" class="text-center" width="10%"><?= $this->lang->line('Kota'); ?></th>
                                        <th scope="col" class="text-center"><?= $this->lang->line('No'); ?></th>
                                        <th scope="col" class="text-center" width="4%"><?= $this->lang->line('Bank'); ?></th>
                                        <th scope="col" class="text-center" width="10%"><?= $this->lang->line('Jumlah'); ?></th>
                                        <th scope="col" class="text-center"><?= $this->lang->line('Tgl'); ?></th>
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
                                                <td><?= substr($key->i_nota_id, -6); ?></td>
                                                <td><?= date('d-m-y', strtotime($key->d_nota)); ?></td>
                                                <td><?= date('d-m-y', strtotime($key->d_jatuh_tempo)); ?></td>
                                                <td><?= $key->i_customer_id; ?></td>
                                                <td><?= $key->e_customer_name; ?></td>
                                                <td><?= $key->e_city_name; ?></td>
                                                <td class="text-right"><?= number_format($key->v_bayar); ?></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                    <?php
                                            $total += $key->v_bayar;
                                        }
                                    } ?>
                                </tbody>
                                <tfoot class="font-small-2">
                                    <tr>
                                        <th colspan="7" class="text-right"><?= $this->lang->line('Jumlah'); ?> Rp. </th>
                                        <th class="text-right"><?= number_format($total); ?></th>
                                        <th colspan="7"></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <hr class="mt-0 mb-0">

                        <!-- invoice total -->
                        <div class="invoice-total">
                            <div class="row">
                                <div class="col-sm-12">
                                    <table class="table nowrap table-borderless table-xs">
                                        <tbody class="font-small-2">
                                            <tr>
                                                <td>Sudah Terima : Tunai</td>
                                                <td colspan="5">= Rp.</td>
                                            </tr>
                                            <tr>
                                                <td width="160px">Giro / Cek = ............... lbr</td>
                                                <td>= Rp.</td>
                                                <td class="text-center">Ditagih Oleh :</td>
                                                <td class="text-center">Diserahkan Oleh :</td>
                                                <td class="text-center">Dibuat Oleh :</td>
                                                <td class="text-center">Diterima Oleh :</td>
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
                                                <td class="text-center" colspan="2">(<u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u>)</td>
                                                <td class="text-center">(<u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u>)</td>
                                                <td class="text-center">(<u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u>)</td>
                                                <td class="text-center">(<u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u>)</td>
                                                <td class="text-center">(<u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u>)</td>
                                            </tr>
                                            <tr>
                                                <td class="text-center" colspan="2">Kasir</td>
                                                <td class="text-center" colspan="2">Penagih</td>
                                                <td class="text-center" colspan="2">Adm Keuangan</td>
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