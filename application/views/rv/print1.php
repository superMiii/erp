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
            <td colspan="2">
                <hr class="mt-0 mb-0">
            </td>
        </tr>
        <tr>
            <td width="50%"><span class="header text-bold-700"><?= $company->e_company_name; ?></span></td>
            <td class="text-right" rowspan="4"><img src="<?= base_url(); ?>assets/images/<?= $company->e_company_logo; ?>" alt="company-logo" height="77" width="100"></td>
        </tr>
        <tr>
            <td><span class="judul text-capitalize"><?= $company->e_company_address; ?></span></td>
        </tr>
        <tr>
            <td><span class="judul"><?php if ($company->e_company_npwp_code != null) { ?><?= $this->lang->line('NPWP'); ?> - <?= $company->e_company_npwp_code; ?><?php } ?></span></td>
        </tr>
        <tr>
            <td><span class="judul"><?php if ($company->e_company_phone != null) { ?><?= $this->lang->line('Telepon'); ?> - <?= $company->e_company_phone; ?><?php } ?></span></td>
        </tr>
        <tr>
            <td colspan="2">
                <hr class="mt-0 mb-0">
            </td>
        </tr>
        <tr>
            <td class="text-center" colspan="2">
                <p class="header text-bold-700">Bukti Pembayaran <?= $data->e_coa_name; ?></p>
            </td>
        </tr>
        <tr>
            <td><span class="judul header text-bold-700"><?= $data->i_rv_id; ?></span></td>
            <td class="text-right"><span class="judul header text-bold-700"><?= $data->e_area_name . ', ' . date('d', strtotime($data->d_rv)) . ' ' . bulan(date('m', strtotime($data->d_rv))) . ' ' . date('Y', strtotime($data->d_rv)); ?></span></td>
        </tr>
    </table>
    <input type="hidden" id="id" value="<?= $data->i_rv; ?>">
    <input type="hidden" id="path" value="<?= base_url($this->folder); ?>">
    <table class="judul" width="100%" border="1" cellspacing="0" cellpadding="2">
        <thead>
            <tr>
                <th class="text-center">No</th>
                <th class="text-center"><?= $this->lang->line("Perkiraan"); ?></th>
                <th class="text-center"><?= $this->lang->line("Tgl Bukti"); ?></th>
                <th class="text-center"><?= $this->lang->line("Keterangan"); ?></th>
                <th class="text-right"><?= $this->lang->line("Jumlah"); ?></th>
            </tr>
        </thead>

        <tbody>
            <?php $i = 0;
            $subtotal = 0;
            $saldo = 0;
            $saldoakhir = 0;
            if ($detail->num_rows() > 0) {
                foreach ($detail->result() as $key) {
                    $i++; ?>
                    <tr>
                        <td class="text-center" valign="center"><?= $i; ?></td>
                        <td><?= $key->i_coa_id; ?></td>
                        <td><?= $key->date_bukti; ?></td>
                        <td><?= $key->e_remark; ?></td>
                        <td class="text-right"><?= number_format($key->v_rv); ?></td>
                    </tr>
            <?php
                    $subtotal += $key->v_rv;
                }
                $saldo = 0;
                $saldoakhir = $subtotal + $saldo;
            } ?>
        </tbody>
        <table class="judul" width="100%" border="0" cellspacing="0" cellpadding="2">
            <tfoot>
                <tr>
                    <th width="87%" colspan="4" class="text-right"><?= $this->lang->line('Jumlah'); ?> Rp. </th>
                    <th class="text-right"><?= number_format($subtotal); ?></th>
                </tr>
                <tr>
                    <th colspan="5" class="text-right"><span class="text-capitalize"><em>(Terbilang : <?= terbilang($subtotal); ?> Rupiah)</em></span></th>
                </tr>
            </tfoot>
        </table>

    </table>

    <table class="judul" width="100%" border="0" cellspacing="0" cellpadding="2">
        <tbody class="text-center">
            <tr>
                <td colspan="6">
                    <p>&nbsp;</p>
                </td>
            </tr>
            <tr>
                <td width="18%">
                    <span class="mb-2">Dibayar :</span>
                </td>
                <td width="18%>
                    <span class=" mb-2">Diperiksa :</span>
                </td>
                <td width="18%>
                    <span class=" mb-2">Diketahui :</span>
                </td>
                <td width="18%>
                    <span class=" mb-2">Disetujui :</span>
                </td>
                <td width="10%>
                    <span class=" mb-2">&nbsp;</span>
                </td>
                <td width="18%>
                    <span class=" mb-2">Diterima :</span>
                </td>
            </tr>
            <tr height="70px">
                <td colspan="6"></td>
            </tr>
            <td class="text-muted">
                <p class="mt-3">( &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; )</p>
            </td>
            <td class="text-muted">
                <p class="mt-3">( &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; )</p>
            </td>
            <td class="text-muted">
                <p class="mt-3">( &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; )</p>
            </td>
            <td class="text-muted">
                <p class="mt-3">( &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; )</p>
            </td>
            <td class="text-muted">
                <p class="mt-3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </p>
            </td>
            <td class="text-muted">
                <p class="mt-3">( &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; )</p>
            </td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="6" class="text-center"><a href="#" onclick="window.print();" class="button button1 no-print"> <i class="feather icon-printer mr-25 common-size"></i> <?= $this->lang->line("Cetak"); ?></a></td>
            </tr>
        </tfoot>
    </table>
    <?= put_footer(); ?>
</body>
<!-- END: Body-->

</html>