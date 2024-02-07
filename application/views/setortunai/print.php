<html class="loading" lang="en" data-textdirection="ltr">
<!-- BEGIN: Head-->

<head>
    <title>Print <?= $this->title; ?></title>
    <link rel="stylesheet" type="text/css" href="<?= base_url(); ?>app-assets/css/bootstrap-extended.css">
    <link rel="stylesheet" type="text/css" href="<?= base_url(); ?>assets/css/style.css">
    <link rel="stylesheet" type="text/css" href="<?= base_url(); ?>assets/css/print.css">
    <!-- END: Custom CSS-->
</head>

<body>
    <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
            <td>
                <p class="header text-uppercase text-bold-700"><?= $company->e_company_name; ?></p>
            </td>
            <td class="text-right" rowspan="4"><img src="<?= base_url(); ?>assets/images/<?= $company->e_company_logo; ?>" alt="company-logo" height="70" width="100"></td>
        </tr>
        <tr>
            <td><span class="judul font-weight-bold"><?= $company->e_company_address; ?></span></td>
        </tr>
        <tr>
            <td><span class="judul font-weight-bold"><?= $this->lang->line('Telepon'); ?> - <?= $company->e_company_phone; ?></span></td>
        </tr>
        <tr>
            <td><span class="judul font-weight-bold"><?php if ($company->e_company_npwp_code != null) { ?><?= $this->lang->line('NPWP'); ?> - <?= $company->e_company_npwp_code; ?><?php } ?></span></td>
            <hr class="mt-0 mb-0">
        </tr>
        <tr>
            <td colspan="2">
                <hr class="mt-0 mb-0">
            </td>
        </tr>
        <tr>
            <td><span class="header text-bold-700"><?= $data->e_bank_name; ?></span></td>
            <td width="50%" class="text-center"><span class="header text-bold-700"><?= $this->lang->line('Setor Tunai'); ?></span></td>
        </tr>
        <tr>
            <td><span class="judul text-capitalize"><?= $data->i_area_id . ' - ' . $data->e_area_name; ?></span></td>
            <td class="text-center"><span class="font-14">No. Dokumen : <strong><?= $data->i_st_id . '</strong> ' ?></span></td>
        </tr>
        <tr>
            <td width="50%"><span class="font-13"></span></td>
            <td class="text-center"><span class="judul">Tanggal Dokumen : <strong> <?= date('d', strtotime($data->d_st)) . ' ' . bulan(date('m', strtotime($data->d_st))) . ' ' . date('Y', strtotime($data->d_st)); ?> </strong></span></td>
        </tr>
        <tr>
            <td colspan="2"><span class="judul">Ket : <?= $data->e_remark; ?></span></td>
        </tr>
        <tr>
            <td colspan="2">&nbsp;</td>
        </tr>
    </table>
    <input type="hidden" id="id" value="<?= $data->i_st; ?>">
    <input type="hidden" id="path" value="<?= base_url($this->folder); ?>">
    <table class="judul" width="100%" border="1" cellspacing="0" cellpadding="2">
        <thead>
            <tr>
                <th scope="col" class="text-center" width="3%">No</th>
                <th class="text-center" width="20%" valign="center"><?= $this->lang->line("No Tunai Item"); ?></th>
                <th class="text-center" width="20%" valign="center"><?= $this->lang->line("Tgl Tunai Item"); ?></th>
                <th class="text-center" width="20%" valign="center"><?= $this->lang->line("Nama Pelanggan"); ?></th>
                <th class="text-center" width="20%" valign="center"><?= $this->lang->line("Jumlah"); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php $i = 0;
            if ($detail->num_rows() > 0) {
                foreach ($detail->result() as $key) {
                    $i++; ?>
                    <tr>
                        <td class="text-center" valign="center">
                            <spanx id="snum<?= $i; ?>"><?= $i; ?></spanx>
                        </td>
                        <td><?= $key->i_tunai_id; ?></td>
                        <td><?= $key->d_tunai; ?></td>
                        <td><?= $key->cus; ?></td>
                        <td class="text-right">Rp. <?= number_format($key->v_jumlah); ?></td>
                    </tr>
            <?php
                }
            } ?>

        </tbody>
        <tfoot>
            <tr>
                <th class="text-right" colspan="4"><?= $this->lang->line('Total'); ?></th>
                <th class="text-right">Rp. <?= number_format($data->v_jumlah); ?></th>
            </tr>
        </tfoot>
    </table>
    <table width="100%;" class="judul">
        <tbody class="text-center">
            <tr>
                <td colspan="6" class="text-left font-14"><span>&nbsp;</span></td>
            </tr>
            <tr>
                <td>
                    Mengetahui,
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
                    ( &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; )
                </td>
                <td>
                    &nbsp;
                </td>
                <td>
                    ( &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; )
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