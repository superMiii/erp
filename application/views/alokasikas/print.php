<html class="loading" lang="en" data-textdirection="ltr">

<head>
    <title>Print <?= $this->title; ?></title>
    <link rel="stylesheet" type="text/css" href="<?= base_url(); ?>app-assets/css/bootstrap-extended.css">
    <link rel="stylesheet" type="text/css" href="<?= base_url(); ?>assets/css/style.css">
    <link rel="stylesheet" type="text/css" href="<?= base_url(); ?>assets/css/print.css">
</head>
<!-- END: Head-->

<!-- BEGIN: Body-->

<!-- <body oncontextmenu='return false;' onkeydown='return false;' onmousedown='return false;'> -->

<body>
    <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
            <td>
                <p class="header text-uppercase text-primary"><?= $this->lang->line('Alokasi Bank Masuk'); ?></p>
            </td>
            <td class="text-right" rowspan="4"><img src="<?= base_url(); ?>assets/images/<?= $company->e_company_logo; ?>" alt="company-logo" height="70" width="100"></td>
        </tr>
        <tr>
            <td width="50%"><span class="header text-bold-700"><?= $company->e_company_name; ?></span></td>
        </tr>
        <tr>
            <td><span class="judul text-capitalize"><?= $company->e_company_address; ?></span></td>
        </tr>
        <tr>
            <td><span class="judul"><?php if ($company->e_company_npwp_code != null) { ?><?= $this->lang->line('NPWP'); ?> - <?= $company->e_company_npwp_code; ?><?php } ?></span></td>
        </tr>
        <tr>
            <td><span class="judul"><?php if ($company->e_company_phone != null) { ?><?= $this->lang->line('Telepon'); ?> - <?= $company->e_company_phone; ?><?php } ?></span></td>
            <hr class="mt-0 mb-0">
        </tr>
        <tr>
            <td colspan="2">
                <hr class="mt-0 mb-0">
            </td>
        </tr>
        <tr>
            <td><span class="header text-uppercase text-primary">Nomor Alokasi : <?= $data->i_alokasi_id; ?></span></td>
            <td class="text-right"><span class="text-bold-700 header"><?= '[' . $data->i_customer_id . '] - ' . $data->e_customer_name; ?></span></td>
        </tr>
        <tr>
            <td><span class="judul font-weight-bold">Tanggal Alokasi : <?= date('d', strtotime($data->d_alokasi)) . ' ' . bulan(date('m', strtotime($data->d_alokasi))) . ' ' . date('Y', strtotime($data->d_alokasi)); ?></span></td>
            <td class="text-right"><span class="judul"><?= $data->e_customer_address; ?></span></td>
        </tr>
        <tr>
            <td><span class="judul font-weight-bold">Nomor Vocher : <?= $data->i_rv_id; ?></span></td>
            <td class="text-right"><span class="judul"><?= $data->e_area_name; ?></span></td>
        </tr>
        <tr>
            <td><span class="judul font-weight-bold">Tanggal Vocher : <?= date('d', strtotime($data->d_bukti)) . ' ' . bulan(date('m', strtotime($data->d_bukti))) . ' ' . date('Y', strtotime($data->d_bukti)); ?></span></td>
            <td colspan="2" class="text-right judul"><span><?= $data->e_customer_phone; ?></span></td>
        </tr>
        <tr>
            <td>
                <br>
            </td>
        </tr>
        <tr>
            <td width="50%"><span class="header text-bold-700"><?= $data->e_coa_name; ?></span></td>
        </tr>
        <tr>
            <td width="50%">Sejumlah :<span class="header text-bold-700"> Rp.<?= number_format($data->v_rv_saldo); ?></span> </td>
        </tr>
        <td colspan="2">
            <hr class="mt-0 mb-0">
        </td>
    </table>
    <input type="hidden" id="id" value="<?= $data->i_alokasi; ?>">
    <input type="hidden" id="path" value="<?= base_url($this->folder); ?>">
    <table class="judul" width="100%" border="1" cellspacing="0" cellpadding="2">
        <thead>
            <tr>
                <th class="text-center" width="5%">No</th>
                <th class="text-center" width="12%"><?= $this->lang->line("No Nota"); ?></th>
                <th class="text-center" width="12%"><?= $this->lang->line("Tgl Nota"); ?></th>
                <th class="text-center" width="12%"><?= $this->lang->line("Nilai"); ?></th>
                <th class="text-center" width="12%"><?= $this->lang->line("Bayar"); ?></th>
                <!-- <th class="text-center" width="12%"><?= $this->lang->line("Sisa"); ?></th> -->
                <th class="text-center"><?= $this->lang->line("Keterangan"); ?></th>
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
                        <td><?= $key->i_nota_id; ?></td>
                        <td><?= date('d', strtotime($key->d_nota)) . ' ' . bulan(date('m', strtotime($key->d_nota))) . ' ' . date('Y', strtotime($key->d_nota)); ?></td>
                        <td class="text-right">Rp.<?= number_format($key->v_nota); ?></td>
                        <td class="text-right">Rp.<?= number_format($key->v_jumlah); ?></td>
                        <!-- <td class="text-right"><?= number_format($key->v_sisa); ?></td> -->
                        <td><?= $key->e_remark; ?></td>
                    </tr>
            <?php }
            } ?>

        </tbody>
        <!-- <tfoot>

            <?php if ($grandtotal >= $this->session->v_meterai_limit) { ?>
                <tr>
                    <th class="font-small-2" colspan="5"><?= $this->lang->line('Catatan'); ?> : Nilai Belum Termasuk Bea Meterai Rp. 10.000,-</th>
                    <!-- <th class="font-small-2" colspan="5">&nbsp;&nbsp;&nbsp;</th> -->
        <th class="text-right" colspan="3">Sub Total</th>
        <td class="text-right"><strong><?= number_format($subtotal); ?></strong></td>
        </tr>
        <tr>
            <th <?php if ($data->f_so_plusppn == 't') { ?> rowspan="6" <?php } else { ?> rowspan="4" <?php } ?> colspan="5">
                <div class="border-blue-grey">
                    <div class="card-content">
                        <div class="text-center">
                            <span class="mb-1"><strong>P E N T I N G</strong></span><br>
                            <span class="mb-0"><small>PENERIMA WAJIB TTD DAN ATAU CAP TOKO,</small></span><br>
                            <span class="mb-0"><small>SURAT JALN INI MERUPAKAN BUKTI RESMI PENERIMAAN BARANG,</small></span><br>
                            <span class="mb-0"><small>SURAT JALAN INI BUKAN MERUPAKAN BUKTI TAGIHAN,</small></span><br>
                            <span class="mb-0"><small>TERIMA KOMPLEN BARANG PALING LAMA 3 HARI SETELAH BARANG DITERIMA</small></span>
                        </div>
                    </div>
                </div>
            </th>
        </tr>
    <?php } else { ?>
        <tr>
            <th <?php if ($data->f_so_plusppn == 't') { ?> rowspan="7" <?php } else { ?> rowspan="4" <?php } ?> colspan="5">
                <div class="border-blue-grey">
                    <div class="card-content">
                        <div class="text-center">
                            <span class="mb-1"><strong>P E N T I N G</strong></span><br>
                            <span class="mb-0"><small>PENERIMA WAJIB TTD DAN ATAU CAP TOKO,</small></span><br>
                            <span class="mb-0"><small>SURAT JALN INI MERUPAKAN BUKTI RESMI PENERIMAAN BARANG,</small></span><br>
                            <span class="mb-0"><small>SURAT JALAN INI BUKAN MERUPAKAN BUKTI TAGIHAN,</small></span><br>
                            <span class="mb-0"><small>TERIMA KOMPLEN BARANG PALING LAMA 3 HARI SETELAH BARANG DITERIMA</small></span>
                        </div>
                    </div>
                </div>
            </th>
            <th class="text-right" colspan="3">Sub Total</th>
            <td class="text-right"><strong><?= number_format($subtotal); ?></strong></td>
        </tr>
    <?php } ?>
    <tr>
        <th class="text-right" colspan="3">Diskon Per Item</th>
        <td class="text-right"><?= number_format($distotal); ?></td>
    </tr>
    <tr>
        <th class="text-right" colspan="3">Diskon Tambahan</th>
        <td class="text-right"><?= number_format($data->v_so_discounttotal); ?></li>
        </td>
    </tr>
    <?php if ($data->f_so_plusppn == 't') { ?>
        <tr>
            <th class="text-right" colspan="3">Nilai Kotor</th>
            <td class="text-right"><?= number_format($dpp); ?></td>
        </tr>
        <tr>
            <th class="text-right" colspan="3">Pajak (10%)</th>
            <td class="text-right"><?= number_format($ppn); ?></td>
        </tr>
    <?php } ?>
    <tr>
        <th class="text-right" colspan="3">Nilai Bersih</th>
        <td class="text-right"><strong><?= number_format($grandtotal); ?></strong></td>
    </tr>
    </tfoot> -->
    </table>
    <table width="100%;" class="judul">
        <tbody class="text-center">
            <!-- <th class="text-left" colspan="5"><?= $this->lang->line('Keterangan'); ?> : <?= $data->e_remark; ?> </th>
            <tr>
                <td colspan="6" class="text-right"><span>Bandung, <?= date('d', strtotime($data->d_do)) . ' ' . bulan(date('m', strtotime($data->d_do))) . ' ' . date('Y', strtotime($data->d_do)); ?></span></td>
            </tr> -->
            <td>
                <br>
            </td>
            <tr>
                <td>
                    Pengirim
                </td>
                <td>
                    Mengetahui
                </td>
                <td>
                    Menyetujui
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
<!-- END: Body-->

</html>