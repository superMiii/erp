<html class="loading" lang="en" data-textdirection="ltr">

<head>
    <title>Print <?= $this->title; ?></title>
    <link rel="stylesheet" type="text/css" href="<?= base_url(); ?>app-assets/css/bootstrap-extended.css">
    <link rel="stylesheet" type="text/css" href="<?= base_url(); ?>assets/css/style.css">
    <link rel="stylesheet" type="text/css" href="<?= base_url(); ?>assets/css/print.css">
</head>

<body>
    <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
            <td width="50%">
                <p class="header text-bold-700">Bukti Barang Keluar</p>
            </td>
            <td class="text-right" rowspan="3"><img src="<?= base_url(); ?>assets/images/<?= $company->e_company_logo; ?>" alt="company-logo" height="77" width="100"></td>
        </tr>
        <tr>
            <td><span class="judul font-weight-bold">Nomor BBK : <?= $data->i_bbk_id; ?></span></td>
        </tr>
        <tr>
            <td><span class="judul font-weight-bold">Tanggal BBK : <?= date('d', strtotime($data->d_bbk)) . ' ' . bulan(date('m', strtotime($data->d_bbk))) . ' ' . date('Y', strtotime($data->d_bbk)); ?></span></td>
            <hr class="mt-0 mb-0">
        </tr>
        <tr>
            <td colspan="2">
                <hr class="mt-0 mb-0">
            </td>
        </tr>
        <tr>
            <td width="50%"><span class="header text-bold-700"><?= $company->e_company_name; ?></span></td>
            <td class="text-right"><span class="text-bold-700 header"><?= '[' . $data->i_customer_id . '] - ' . $data->e_customer_name; ?></span></td>
        </tr>
        <tr>
            <td><span class="judul text-capitalize"><?= $company->e_company_address; ?></span></td>
            <td class="text-right"><span class="judul"><?= $data->e_customer_address; ?></span></td>
        </tr>
        <tr>
            <td><span class="judul"><?php if ($company->e_company_npwp_code != null) { ?><?= $this->lang->line('NPWP'); ?> - <?= $company->e_company_npwp_code; ?><?php } ?></span></td>
            <td class="text-right"><span class="judul"><?= $data->e_city_name . ' - ' . $data->e_area_name; ?></span></td>
        </tr>
        <tr>
            <td><span class="judul"><?php if ($company->e_company_phone != null) { ?><?= $this->lang->line('Telepon'); ?> - <?= $company->e_company_phone; ?><?php } ?></span></td>
            <td colspan="2" class="text-right judul"><span><?= $data->e_customer_phone; ?></span></td>
        </tr>
        <tr>
            <td>
                <p>&nbsp;</p>
            </td>
        </tr>
        <tr>
            <td width="50%"><span class="header text-bold-300"><?= $this->lang->line('Keterangan :'); ?> <?= $data->e_remark; ?></span></td>
            <td colspan="2" class="text-right judul"><span>&nbsp;</span></td>
        </tr>
    </table>
    <input type="hidden" id="id" value="<?= $data->i_bbk; ?>">
    <input type="hidden" id="path" value="<?= base_url($this->folder); ?>">
    <table class="judul" width="100%" border="1" cellspacing="0" cellpadding="2">
        <thead>
            <tr>
                <th scope="col" class="text-center" width="3%">No</th>
                <th scope="col"><?= $this->lang->line('Kode Barang'); ?></th>
                <th scope="col"><?= $this->lang->line('Nama Barang'); ?></th>
                <th scope="col"><?= $this->lang->line('Grade Barang'); ?></th>
                <th class="text-right" scope="col"><?= $this->lang->line("Jumlah"); ?></th>
                <th scope="col"><?= $this->lang->line('Keterangan'); ?></th>
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
                        <td><?= $key->e_product_motifname; ?></td>
                        <td class="text-right"><?= $key->n_quantity; ?></td>
                        <td><?= $key->e_remark; ?></td>
                    </tr>
            <?php
                }
            } ?>

        </tbody>
    </table>
    <table class="judul" width="100%" border="0" cellspacing="0" cellpadding="2">
        <tfoot>
            <tr>
                <td>
                    <p>&nbsp;</p>
                </td>
            </tr>
            <tr>
                <td>
                    <p>&nbsp;</p>
                </td>
            </tr>
            <tr>
                <td>
                    <p>&nbsp;</p>
                </td>
            </tr>
        </tfoot>
    </table>
    <table width="100%;" class="judul">
        <tbody class="text-center">
            <tr>
                <td>
                    <p>&nbsp;</p>
                </td>
            </tr>
            <tr>
                <td>
                    <span class="mb-2">Penerima</span>
                </td>
                <td>
                    <span class="mb-2">Mengetahui</span>
                </td>
                <td>
                    <span class="mb-2">Pengirim</span>
                </td>
            </tr>
            <tr height="54px">
                <td></td>
            </tr>
            <tr>
                <td class="text-muted">
                    <p class="mt-3">( &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; )</p>
                </td>
                <td class="text-muted">
                    <p class="mt-3">( &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; )</p>
                </td>
                <td class="text-muted">
                    <p class="mt-3">( &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; )</p>
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