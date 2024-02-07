<html class="loading" lang="en" data-textdirection="ltr">
<!-- BEGIN: Head-->

<html class="loading" lang="en" data-textdirection="ltr">
<!-- BEGIN: Head-->

<head>
    <title>Print <?= $this->title; ?></title>
    <link rel="stylesheet" type="text/css" href="<?= base_url(); ?>app-assets/css/bootstrap-extended.css">
    <link rel="stylesheet" type="text/css" href="<?= base_url(); ?>assets/css/style.css">
    <link rel="stylesheet" type="text/css" href="<?= base_url(); ?>assets/css/print.css">

    <style>
        @page {
            size: A4 landscape;
        }

        /* Size in mm */
        @page {
            size: 100mm 200mm landscape;
        }

        /* Size in inches */
        @page {
            size: 4in 6in landscape;
        }
    </style>
</head>
<!-- END: Head-->

<!-- BEGIN: Body-->

<body>

    <!-- <body> -->
    <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
            <td>
                <p class="header text-bold-700"><?= $company->e_company_name; ?></p>
            </td>
            <td class="text-right" rowspan="2"><img src="<?= base_url(); ?>assets/images/<?= $company->e_company_logo; ?>" alt="company-logo" height="40" width="50"></td>
        </tr>
        <tr>
            <td><span class="judul2 font-weight-bold">
                </span></td>
            <hr class="mt-0 mb-0">
        </tr>
        <tr>
            <td colspan="2">
                <hr class="mt-0 mb-0">
            </td>
        </tr>
        <tr>
            <td colspan="2" width="50%" class="text-center"><span><?= $this->lang->line('IKHP'); ?></span></td>
        </tr>
        <tr>
            <td colspan="2" width="50%" class="text-center"><span><?= $e_area_name; ?></span></td>
        </tr>
        <tr>
            <td colspan="2" width="50%" class="text-center"><span><?= $dfrom; ?> Sampai <?= $dto; ?></span></td>
        </tr>
        <tr>
            <td colspan="2" class="text-right judul2"><span><br></span></td>
        </tr>
    </table>


    <!-- <input type="hidden" id="id" value="<?= $id_menu; ?>"> -->
    <input type="hidden" id="path" value="<?= base_url($this->folder); ?>">

                                        <table class="judul2" width="100%" border="1" cellspacing="0" cellpadding="2">
                                        <thead>
                                            <tr>
                                                <th class="text-center" rowspan="2">No</th>
                                                <th class="text-center" rowspan="2"><?= $this->lang->line('Tanggal'); ?></th>
                                                <th class="text-center" rowspan="2"><?= $this->lang->line('No Referensi'); ?></th>
                                                <th class="text-center" rowspan="2"><?= $this->lang->line('Uraian'); ?></th>
                                                <th class="text-center" colspan="2"><?= $this->lang->line('Penerimaan'); ?></th>
                                                <th class="text-center" colspan="2"><?= $this->lang->line('Pengeluaran'); ?></th>
                                                <th class="text-center" colspan="2"><?= $this->lang->line('Saldo Akhir'); ?></th>
                                            </tr>
                                            <tr>
                                                <th class="text-center"><?= $this->lang->line('Tunai'); ?></th>
                                                <th class="text-center"><?= $this->lang->line('Giro'); ?></th>
                                                <th class="text-center"><?= $this->lang->line('Tunai'); ?></th>
                                                <th class="text-center"><?= $this->lang->line('Giro'); ?></th>
                                                <th class="text-center"><?= $this->lang->line('Tunai'); ?></th>
                                                <th class="text-center"><?= $this->lang->line('Giro'); ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php $i = 0;
                                            $debet_tunai = 0;
                                            $credit_tunai = 0;
                                            $saldo_akhir_tunai = 0;
                                            $debet_giro = 0;
                                            $credit_giro = 0;
                                            $saldo_akhir_giro = 0;
                                            if ($data->num_rows() > 0) {
                                                foreach ($data->result() as $key) {
                                                    $debet_tunai += $key->debet_tunai;
                                                    $credit_tunai += $key->credit_tunai;
                                                    $saldo_akhir_tunai = $key->saldo_akhir_tunai;

                                                    $debet_giro += $key->debet_giro;
                                                    $credit_giro += $key->credit_giro;
                                                    $saldo_akhir_giro = $key->saldo_akhir_giro;

                                                    $i++; ?>
                                                    <tr>
                                                        <td class="text-center"><?= $i; ?></td>
                                                        <td class="text-center"><?= $key->tanggal; ?></td>
                                                        <td class="text-center"><?= $key->kode; ?></td>
                                                        <td><?= $key->e_remark; ?></td>
                                                        <td class="text-right">Rp. <?= number_format($key->debet_tunai, 2); ?></td>
                                                        <td class="text-right">Rp. <?= number_format($key->debet_giro, 2); ?></td>
                                                        <td class="text-right">Rp. <?= number_format($key->credit_tunai, 2); ?></td>
                                                        <td class="text-right">Rp. <?= number_format($key->credit_giro, 2); ?></td>
                                                        <td class="text-right">Rp. <?= number_format($key->saldo_akhir_tunai, 2); ?></td>
                                                        <td class="text-right">Rp. <?= number_format($key->saldo_akhir_giro, 2); ?></td>
                                                    </tr>
                                            <?php }
                                            } ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th class="text-right" colspan="4"><strong>TOTAL</strong></th>
                                                <th class="text-right"><strong>Rp. <?= number_format($debet_tunai, 2); ?></strong></th>
                                                <th class="text-right"><strong>Rp. <?= number_format($debet_giro, 2); ?></strong></th>
                                                <th class="text-right"><strong>Rp. <?= number_format($credit_tunai, 2); ?></strong></th>
                                                <th class="text-right"><strong>Rp. <?= number_format($credit_giro, 2); ?></strong></th>
                                                <th class="text-right"><strong>Rp. <?= number_format($saldo_akhir_tunai, 2); ?></strong></th>
                                                <th class="text-right"><strong>Rp. <?= number_format($saldo_akhir_giro, 2); ?></strong></th>
                                            </tr>
                                        </tfoot>
                                    </table>
        <table width="100%;" class="judul4"><br>
        <tbody class="text-center">
            <tr>
                <td>
                    Dibuat
                </td>
                <td>
                &nbsp;
                </td>
                <td>
                Mengetahui
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
                     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
                </td>
                <td class="text-muted">
                    ( &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  )
                </td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="10" class="text-center"><a href="#" onclick="window.print();" class="button button1 no-print"> <i class="feather icon-printer mr-25 common-size"></i> <?= $this->lang->line("Cetak"); ?></a></td>
            </tr>
        </tfoot>
    </table>
        <?= put_footer(); ?>


</body>

</html>