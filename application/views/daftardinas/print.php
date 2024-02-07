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
                <p class="header text-uppercase text-bold-700"><?= $company->e_company_name; ?></p>
            </td>
            <td class="text-right" rowspan="4"><img src="<?= base_url(); ?>assets/images/<?= $company->e_company_logo; ?>" alt="company-logo" height="70" width="100"></td>
        </tr>
        <tr>
            <td><span class="font-13 font-weight-bold"><?= $company->e_company_address; ?></span></td>
        </tr>
        <tr>
            <td><span class="font-12 font-weight-bold"><?= $this->lang->line('Telepon'); ?> - <?= $company->e_company_phone; ?></span></td>
        </tr>
        <tr>
            <td><span class="font-12 font-weight-bold"><?php if ($company->e_company_npwp_code != null) { ?><?= $this->lang->line('NPWP'); ?> - <?= $company->e_company_npwp_code; ?><?php } ?></span></td>
            <hr class="mt-0 mb-0">
        </tr>
        <tr>
            <td colspan="2">
                <hr class="mt-0 mb-0">
            </td>
        </tr>
        <tr>
            <td width="50%"><span class="font-13">Kepada Yth,</span></td>
            <td width="50%" class="text-center"><span class="header text-bold-700">SURAT PENGEMBALIAN BARANG</span></td>
        </tr>
        <tr>
            <td><span class="font-12 text-capitalize">Kepala Bagian Gudang Pusat</span></td>
            <td class="text-center"><span class="font-14">No. Dokumen : <strong><?= $data->i_gs_id . '</strong> / ' . $data->i_area_id . ' - ' . $data->e_area_name; ?></span></td>
        </tr>
        <tr>
            <td><span class="judul text-bold-700"><?= $company->e_company_name; ?></span></td>
            <td class="text-center"><span class="font-12">Tanggal Dokumen : <?= date('d', strtotime($data->d_gs)) . ' ' . bulan(date('m', strtotime($data->d_gs))) . ' ' . date('Y', strtotime($data->d_gs)); ?></span></td>
        </tr>
        <tr>
            <td colspan="2">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="2"><span class="font-12">Dengan hormat,</span></td>
        </tr>
        <tr>
            <td colspan="2"><span class="font-12">Bersama surat ini kami mohon diterima pengembalian barang-barang sebagai berikut :</span></td>
        </tr>
    </table>
    <input type="hidden" id="id" value="<?= $data->i_gs; ?>">
    <input type="hidden" id="path" value="<?= base_url($this->folder); ?>">
    <table class="font-12" width="100%" border="1" cellspacing="0" cellpadding="2">
        <thead>
            <tr>
                <th scope="col" class="text-center" width="3%">No</th>
                <th class="text-left"><?= $this->lang->line('Kode'); ?></th>
                <th class="text-left"><?= $this->lang->line('Nama Barang'); ?></th>
                <th class="text-left"><?= $this->lang->line('Keterangan Tambahan'); ?></th>
                <th class="text-right" scope="col"><?= $this->lang->line("Jml Pengembalian"); ?></th>
                <th class="text-right" scope="col"><?= $this->lang->line("Jml Acc"); ?></th>
                <th class="text-right" scope="col"><?= $this->lang->line('Jml Terima Pusat'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php $i = 0;
            if ($detail->num_rows() > 0) {
                foreach ($detail->result() as $key) {
                    $i++;
            ?>
                    <tr>
                        <td class="text-center"><?= $i; ?></td>
                        <td><?= $key->i_product_id; ?></td>
                        <td><?= $key->e_product_name; ?></td>
                        <td><?= $key->e_remark; ?></td>
                        <td class="text-right"><?= $key->n_quantity_order; ?></td>
                        <td class="text-right"><?= $key->n_quantity_deliver; ?></td>
                        <td class="text-right"><?= $key->n_quantity_receive; ?></td>
                    </tr>
            <?php
                }
            } ?>

        </tbody>
    </table>
    <table width="100%;" class="font-12">
        <tbody class="text-center">
            <tr>
                <td colspan="6" class="text-left font-14"><span>Demikian surat ini kami sampaikan, atas perhatian dan kerjasamanya kami ucapkan terima kasih.</span></td>
            </tr>
            <tr>
                <td>
                    Hormat Kami
                </td>
                <td>
                    &nbsp;
                </td>
                <td>
                    Menyetujui,
                </td>
            </tr>
            <tr height="50px">
                <td colspan="6"></td>
            </tr>
            <tr>
                <td>
                    ( Pengirim )
                </td>
                <td>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                </td>
                <td>
                    ( Penerima )
                </td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="6"><span class="font-12">*Tanggal Cetak : <?= date('d') . ' ' . bulan(date('m')) . ' ' . date('Y') . ' ' . date('H:i:s'); ?></span></td>
            </tr>
            <tr>
                <td colspan="6" class="text-center"><a href="#" onclick="window.print();" class="button button1 no-print"> <i class="feather icon-printer mr-25 common-size"></i> <?= $this->lang->line("Cetak"); ?></a></td>
            </tr>
        </tfoot>
    </table>
    <?= put_footer(); ?>
</body>
<!-- END: Body-->

</html>