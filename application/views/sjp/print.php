<html class="loading" lang="en" data-textdirection="ltr">
<!-- BEGIN: Head-->

<head>
    <title>Print <?= $this->title; ?></title>
    <link rel="stylesheet" type="text/css" href="<?= base_url(); ?>app-assets/css/bootstrap-extended.css">
    <link rel="stylesheet" type="text/css" href="<?= base_url(); ?>assets/css/style.css">
    <link rel="stylesheet" type="text/css" href="<?= base_url(); ?>assets/css/print.css">
</head>
<!-- END: Head-->

<!-- BEGIN: Body-->

<body>

    <!-- <body> -->
    <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
            <td>
                <p class="header text-uppercase text-primary"><?= $this->lang->line('Surat Jalan Pinjaman'); ?></p>
            </td>
            <td class="text-right" rowspan="4"><img src="<?= base_url(); ?>assets/images/<?= $company->e_company_logo; ?>" alt="company-logo" height="100" width="125"></td>
        </tr>
        <!-- <tr>
            <td><span class="judul4 font-weight-bold">
                    <br>
                </span></td>
            <hr class="mt-0 mb-0">
        </tr> -->
        <tr>
            <td><span class="judul4 font-weight-bold">Nomor SJP : <strong><?= $data->i_gs_id; ?></strong></span></td>
        </tr>
        <tr>
            <td><span class="judul4 font-weight-bold">Tanggal SJP : <strong><?= date('d', strtotime($data->d_gs)) . ' ' . bulan(date('m', strtotime($data->d_gs))) . ' ' . date('Y', strtotime($data->d_gs)); ?></strong></span></td>
        </tr>
        <tr>
            <td><span class="judul4 font-weight-bold"><?= $this->lang->line('Nomor SR'); ?> : <?= $data->i_sr_id; ?></span></td>
        </tr>
        <tr>
            <td colspan="2">
                <hr class="mt-0 mb-0">
            </td>
        </tr>
        <tr>
            <td width="50%"><span class="header text-bold-700"><?= $company->e_company_name; ?></span></td>
            <td width="50%" class="text-right"><span class="text-bold-700 header"><?= 'Kepada Yth.'; ?></span></td>
        </tr>
        <tr>
            <td><span class="judul4 text-capitalize"><?= $company->e_company_address; ?></span></td>
            <td class="text-right"><span class="text-bold-700 header"><span class="judul4"><?= $data->e_store_name . ' - ' . $data->e_store_loc_name; ?></span></td>
        </tr>
        <tr>
            <td><span class="judul4"><?php if ($company->e_company_npwp_code != null) { ?><?= $this->lang->line('NPWP'); ?> - <?= $company->e_company_npwp_code; ?><?php } ?></span></td>
            <td class="text-right"></span></td>
        </tr>
        <tr>
            <td><span class="judul4"><?php if ($company->e_company_phone != null) { ?><?= $this->lang->line('Telepon'); ?> - <?= $company->e_company_phone; ?><?php } ?></span></td>
            <td class="text-right"><span class="judul4"></span></td>
        </tr>
        <tr>
            <td colspan="2" class="text-right judul4"><span><br></span></td>
        </tr>
        <tr>
            <td colspan="2"><span class="judul4"><U>Harap diterima barang-barang berikut ini :</U></span></td>
        </tr>
    </table>

    <input type="hidden" id="id" value="<?= $data->i_gs; ?>">
    <input type="hidden" id="path" value="<?= base_url($this->folder); ?>">
    <table class="judul4" width="100%" border="1" cellspacing="0" cellpadding="2">
        <thead>
            <tr>
                <th scope="col" class="text-center" width="3%">No</th>
                <th scope="col"><?= $this->lang->line('Kode'); ?></th>
                <th scope="col"><?= $this->lang->line('Nama Barang'); ?></th>
                <th class="text-right" scope="col">Qty</th>
                <th class="text-right" scope="col"><?= $this->lang->line('Keterangan'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php $i = 0;
            $grandtotal = 0;
            if ($detail->num_rows() > 0) {
                foreach ($detail->result() as $key) {
                    if ($key->n_quantity_deliver > 0) {
                        $i++;
            ?>
                        <tr>
                            <td class="text-center"><?= $i; ?></td>
                            <td><?= $key->i_product_id; ?></td>
                            <td><?= $key->e_product_name; ?></td>
                            <td class="text-right"><?= $key->n_quantity_deliver; ?></td>
                            <td class="text-right"><?= $key->e_remark; ?></td>
                        </tr>
            <?php
                    }
                }
            } ?>

        </tbody>
        <tfoot>
            <tr>
                <td class="text-right" colspan="4">Nilai Bersih</td>
                <td class="text-right"><strong><?= number_format($data->v_gs, 2); ?></strong></td>
            </tr>
            <tr>
                <td class="text-right" colspan="5"><strong><?= terbilang($data->v_gs); ?></strong></td>
            </tr>
        </tfoot>
        <table width="100%;" class="judul4">
            <tbody class="text-center">
                <tr>
                    <!-- <td colspan="6" class="text-right"><span>Bandung, <?= date('d', strtotime($data->d_gs)) . ' ' . bulan(date('m', strtotime($data->d_gs))) . ' ' . date('Y', strtotime($data->d_gs)); ?></span></td> -->
                    <br>
                </tr>
                <tr>
                    <td>
                        Penerima
                    </td>
                    <td>
                        Mengetahui
                    </td>
                    <td>
                        Pengirim
                    </td>
                </tr>
                <tr height="40px">
                    <td colspan="6"></td>
                </tr>
                <tr>
                    <td class="text-muted">
                        ( &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; )
                    </td>
                    <td class="text-muted">
                        ( &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; )
                    </td>
                    <td class="text-muted">
                        ( &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; )
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

</html>