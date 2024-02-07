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
                <p class="header text-bold-700"><?= $this->lang->line('Bukti Barang Masuk'); ?></p>
            </td>
            <td class="text-right" rowspan="5"><img src="<?= base_url(); ?>assets/images/<?= $company->e_company_logo; ?>" alt="company-logo" height="119" width="141"></td>
        </tr>
        <tr>
            <td><span class="judul font-weight-bold">Nomor BBM : <?= $data->i_bbm_id; ?></span></td>
        </tr>
        <tr>
            <td><span class="judul font-weight-bold">Tanggal BBM : <?= date('d', strtotime($data->d_bbm)) . ' ' . bulan(date('m', strtotime($data->d_bbm))) . ' ' . date('Y', strtotime($data->d_bbm)); ?></span>

        </tr>
        <tr>
            <td><span class="judul font-weight-bold">Nomor TTB : <?= $data->i_ttb_id; ?></span></td>
        </tr>
        <tr>
            <td><span class="judul font-weight-bold">Tanggal TTB : <?= date('d', strtotime($data->d_ttb)) . ' ' . bulan(date('m', strtotime($data->d_ttb))) . ' ' . date('Y', strtotime($data->d_ttb)); ?></span>
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
        <!-- <tr>
            <td width="50%"><span class="header text-bold-700">&nbsp;</span></td>
            <td colspan="2" class="text-right text-bold-400"><span><?= $this->lang->line('Alasan Retur'); ?> : <?= $data->e_alasan_retur_name; ?></span></td>
        </tr> -->
        <tr>
            <td width="50%"><span class="header text-bold-700"><?= $this->lang->line('Pramuniaga'); ?> - <?= '[' . $data->i_salesman_id . '] ' . $data->e_salesman_name; ?></span></td>
            <td colspan="2" class="text-right judul"><span>&nbsp;</span></td>
        </tr>
    </table>
    <input type="hidden" id="id" value="<?= $data->i_bbm; ?>">
    <input type="hidden" id="path" value="<?= base_url($this->folder); ?>">
    <table class="judul" width="100%" border="1" cellspacing="0" cellpadding="2">
        <thead>
            <tr>
                <th scope="col" class="text-center" width="3%">No</th>
                <th scope="col"><?= $this->lang->line('Kode Barang'); ?></th>
                <th scope="col"><?= $this->lang->line('Nama Barang'); ?></th>
                <th scope="col"><?= $this->lang->line('Motif'); ?></th>
                <th class="text-right" scope="col"><?= $this->lang->line("Qty BBM"); ?></th>
                <th scope="col"><?= $this->lang->line('Keterangan'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php $i = 0;
            // $subtotal = 0;
            // $distotal = 0;
            // $dpp = 0;
            // $ppn = 0;
            // $grandtotal = 0;
            if ($detail->num_rows() > 0) {
                foreach ($detail->result() as $key) {
                    if ($key->n_quantity > 0) {
                    $i++;
                    //         $total = $key->v_unit_price * $key->n_quantity;
                    //         $v_ttb_discount1 = $total * ($key->n_ttb_discount1 / 100);
                    //         $v_ttb_discount2 = ($total - $v_ttb_discount1) * ($key->n_ttb_discount2 / 100);
                    //         $v_ttb_discount3 = ($total - $v_ttb_discount1 - $v_ttb_discount2) * ($key->n_ttb_discount3 / 100);
                    //         $v_total_discount = $v_ttb_discount1 + $v_ttb_discount2 + $v_ttb_discount3;
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
                    // $subtotal += $total;
                    // $distotal += $v_total_discount;
                }}
                // $dpp = $subtotal - $distotal - $data->v_ttb_discounttotal;
                // $nppn = ($data->f_ttb_plusppn == 't') ? $data->n_ttb_ppn : 0;
                // $ppn = $dpp * ($nppn / 100);
                // $grandtotal = $dpp + $ppn;
            
            } ?>
            <!-- <tr>
                                        <td colspan="6"><li class="dropdown-divider mt-0 mb-0"></li></td>
                                    </tr> -->
        </tbody>
    </table>
    <table class="judul" width="100%" border="0" cellspacing="0" cellpadding="2">
        <tfoot>
            <!-- <tr>
                                            <td colspan="3">Tanggal Daftar : <?= date('d', strtotime($data->d_customer_register)) . ' ' . bulan(date('m', strtotime($data->d_customer_register))) . ' ' . date('Y', strtotime($data->d_customer_register)); ?></td>
                                            <th class="text-right" colspan="2"><?= $this->lang->line('Sub Total'); ?> Rp. </th>
                                            <td class="text-right"><strong><?= number_format($subtotal); ?></strong></td>
                                        </tr>
                                        <tr>
                                            <td colspan="3">Plafond : Rp. 0,-</td>
                                            <th class="text-right" colspan="2"><?= $this->lang->line('Diskon Per Item'); ?> Rp. </th>
                                            <td class="text-right"><?= number_format($distotal); ?></td>
                                        </tr>
                                        <tr>
                                            <td colspan="3">Rata-rata Keterlambatan : 0 Hari</td>
                                            <th class="text-right" colspan="2"><?= $this->lang->line('Diskon Tambahan'); ?> Rp. </th>
                                            <td class="text-right"><?= number_format($data->v_ttb_discounttotal); ?></li>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="3">Saldo Piutang : Rp. 0,-</td>
                                            <?php if ($data->f_ttb_plusppn == 't') { ?>
                                                <th class="text-right" colspan="2"><?= $this->lang->line('DPP'); ?> Rp. </th>
                                                <td class="text-right"><?= number_format($dpp); ?></td>
                                            <?php } else { ?>
                                                <th class="text-right" colspan="2"><?= $this->lang->line('Nilai Bersih'); ?> Rp. </th>
                                                <th class="text-right"><?= number_format($grandtotal); ?></th>
                                            <?php } ?>
                                        </tr>
                                        <tr>
                                            <td colspan="3">Jumlah SPB : 0</td>
                                            <?php if ($data->f_ttb_plusppn == 't') { ?>
                                                <th class="text-right" colspan="2"><?= $this->lang->line('PPN'); ?> (<?= $data->n_so_ppn; ?>%) Rp. </th>
                                                <td class="text-right"><?= number_format($ppn); ?></li>
                                                </td>
                                            <?php } ?>
                                        </tr>
                                        <tr>
                                            <td colspan="3">Riwayat Penjualan : 0</td>
                                            <?php if ($data->f_ttb_plusppn == 't') { ?>
                                                <th class="text-right" colspan="2"><?= $this->lang->line('Nilai Bersih'); ?> Rp. </th>
                                                <th class="text-right font-medium-3"><?= number_format($grandtotal); ?></th>
                                            <?php } ?>
                                        </tr> -->
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