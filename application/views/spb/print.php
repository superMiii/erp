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
                <p class="header text-bold-700"><?= $this->lang->line('Surat Pemesanan Barang'); ?></p>
            </td>
            <td class="text-right" rowspan="3"><img src="<?= base_url(); ?>assets/images/<?= $company->e_company_logo; ?>" alt="company-logo" height="77" width="100"></td>
        </tr>
        <tr>
            <?php if ($data->d_approve == null || $data->d_approve == '') { ?>
                <td><span class="judul font-weight-bold">Nomor SPB : <?= $data->i_so_id; ?> ~ [BARU]</span></td>
            <?php } else { ?>
                <td><span class="judul font-weight-bold">Nomor SPB : <?= $data->i_so_id; ?></span></td>
            <?php } ?>
        </tr>
        <tr>
            <td><span class="judul font-weight-bold">Tanggal SPB : <?= date('d', strtotime($data->d_so)) . ' ' . bulan(date('m', strtotime($data->d_so))) . ' ' . date('Y', strtotime($data->d_so)); ?></span></td>
            <hr class="mt-0 mb-0">
        </tr>
        <tr>
            <td colspan="2">
                <hr class="mt-0 mb-0">
            </td>
        </tr>
        <tr>
            <td width="50%"><span class="header text-bold-700"><?= $company->e_company_name; ?></span></td>
            <?php if ($data->d_approve == null || $data->d_approve == '') { ?>
                <td class="text-right"><span class="text-bold-700 header">[BARU] ~ <?= '[' . $data->i_customer_id . '] - ' . $data->e_customer_name; ?></span></td>
            <?php } else { ?>
                <td class="text-right"><span class="text-bold-700 header"><?= '[' . $data->i_customer_id . '] - ' . $data->e_customer_name; ?></span></td>
            <?php } ?>
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
            <td width="50%"><span class="header text-bold-700"><?= $this->lang->line('Pramuniaga'); ?> - <?= '[' . $data->i_salesman_id . '] ' . $data->e_salesman_name; ?></span></td>
            <td colspan="2" class="text-right judul"><span><?= $this->lang->line('Masa Bayar'); ?> : <?= $data->n_so_toplength; ?> Hari</span></td>
        </tr>
    </table>
    <input type="hidden" id="id" value="<?= $data->i_so; ?>">
    <input type="hidden" id="path" value="<?= base_url($this->folder); ?>">
    <table class="judul" width="100%" border="1" cellspacing="0" cellpadding="2">
        <thead>
            <tr>
                <th scope="col" class="text-center" width="3%">No</th>
                <th scope="col"><?= $this->lang->line('Kode'); ?></th>
                <th scope="col"><?= $this->lang->line('Nama Barang'); ?></th>
                <th scope="col"><?= $this->lang->line('Disk1'); ?></th>
                <th scope="col"><?= $this->lang->line('Disk2'); ?></th>
                <th scope="col"><?= $this->lang->line('Keterangan'); ?></th>
                <th class="text-right" scope="col">Qty <br> Pesanan</th>
                <th class="text-right" scope="col">Qty <br> Pemenuhan</th>
                <th class="text-right" scope="col"><?= $this->lang->line('Harga'); ?></th>
                <th class="text-right" scope="col"><?= $this->lang->line('Total'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php $i = 0;
            $subtotal = 0;
            $distotal = 0;
            $dpp = 0;
            $ppn = 0;
            $grandtotal = 0;
            if ($detail->num_rows() > 0) {
                foreach ($detail->result() as $key) {
                    $i++;
                    $total = $key->v_unit_price * $key->n_order;
                    $v_diskon1 = $total * ($key->n_so_discount1 / 100);
                    $v_diskon2 = ($total - $v_diskon1) * ($key->n_so_discount2 / 100);
                    $v_diskon3 = ($total - $v_diskon1 - $v_diskon2) * ($key->n_so_discount3 / 100);
                    $v_total_discount = $v_diskon1 + $v_diskon2 + $v_diskon3;
            ?>
                    <tr>
                        <td class="text-center"><?= $i; ?></td>
                        <td><?= $key->i_product_id; ?></td>
                        <td><?= $key->e_product_name; ?></td>
                        <td><?= $key->n_so_discount1; ?></td>
                        <td><?= $key->n_so_discount2; ?></td>
                        <td><?= $key->e_remark; ?></td>
                        <td class="text-right"><?= $key->n_order; ?></td>
                        <td></td>
                        <td class="text-right"><?= number_format($key->v_unit_price); ?></td>
                        <td class="text-right"><?= number_format($total); ?></td>
                    </tr>
            <?php
                    $subtotal += $total;
                    $distotal += $v_total_discount;
                }
                $dpp = $subtotal - $distotal - $data->v_so_discounttotal;
                $nppn = ($data->f_so_plusppn == 't') ? $data->n_so_ppn : 0;
                $ppn = $dpp * ($nppn / 100);
                $grandtotal = $dpp + $ppn;
            } ?>
            <!-- <tr>
                                        <td colspan="6"><li class="dropdown-divider mt-0 mb-0"></li></td>
                                    </tr> -->
        </tbody>
    </table>
    <table class="judul" width="100%" border="0" cellspacing="0" cellpadding="2">
        <tfoot>
            <tr>
                <?php if ($data->i_so_refference != '') {
                    $reff = $this->db->get_where('tm_so', ['f_so_cancel' => false, 'i_company' => $this->i_company, 'i_so' => $data->i_so_refference])->row()->i_so_id;
                } else {
                    $reff = '';
                } ?>
                <td width="62%"><strong>KETERANGAN : ( <?= $reff; ?> ) <?= $data->e_remark; ?></strong></td>
                <th width="27%" class="text-right" colspan="2"><?= $this->lang->line('Sub Total'); ?> Rp. </th>
                <td width="11%" class="text-right"><strong><?= number_format($subtotal); ?></strong></td>
            </tr>
            <tr>
                <td><span><strong>EKSPEDISI : <?= $data->e_ekspedisi_cus . ' </strong>- ' . $data->e_ekspedisi_bayar; ?></span></td>
                <th class="text-right" colspan="2"><?= $this->lang->line('Diskon Per Item'); ?> Rp. </th>
                <td class="text-right"><?= number_format($distotal); ?></td>
            </tr>
            <tr>
                <td><span><strong>PEMENUHAN : <?= $data->e_pemenuhan; ?></span></td>
                <th class="text-right" colspan="2"><?= $this->lang->line('Diskon Tambahan'); ?> Rp. </th>
                <td class="text-right"><?= number_format($data->v_so_discounttotal); ?></li>
                </td>
            </tr>
            <tr>
                <!-- <td>Rata-rata Keterlambatan : 0 Hari</td> -->
                <td>Plafond : <?= $data->pla; ?></td>
                <?php if ($data->f_so_plusppn == 't') { ?>
                    <th class="text-right" colspan="2"><?= $this->lang->line('DPP'); ?> Rp. </th>
                    <td class="text-right"><?= number_format($dpp); ?></td>
                <?php } else { ?>
                    <th class="text-right" colspan="2"><?= $this->lang->line('Nilai Bersih'); ?> Rp. </th>
                    <th class="text-right"><?= number_format($grandtotal); ?></th>
                <?php } ?>
            </tr>
            <tr>
                <!-- <td>Saldo Piutang : Rp. 0,-</td> -->
                <td>Saldo Piutang : <?= $data->sissa; ?></td>
                <?php if ($data->f_so_plusppn == 't') { ?>
                    <th class="text-right" colspan="2"><?= $this->lang->line('PPN'); ?> (<?= $data->n_so_ppn; ?>%) Rp. </th>
                    <td class="text-right"><?= number_format($ppn); ?></li>
                    </td>
                <?php } ?>
            </tr>
            <tr>
                <!-- <td>Jumlah SPB : 0</td> -->
                <td>&nbsp;</td>
                <?php if ($data->f_so_plusppn == 't') { ?>
                    <th class="text-right" colspan="2"><?= $this->lang->line('Nilai Bersih'); ?> Rp. </th>
                    <th class="text-right font-medium-3"><?= number_format($grandtotal); ?></th>
                <?php } ?>
            </tr>
        </tfoot>
    </table>
    <table class="judul" width="100%" border="1" cellspacing="0" cellpadding="4">
        <tr>
            <td>
                <div class="card-content">
                    <span class="mb-1">
                        <P>Penyiapan :</P>
                    </span><br>
                </div>
            </td>
            <td>
                <div class="card-content">
                    <span class="mb-1">
                        <P>Cek Akhir :</P>
                    </span><br>
                </div>
            </td>
        </tr>
    </table>
    <table width="100%;" class="judul">
        <tbody class="text-center">
            <tr>
                <td>
                    <span class="mb-2">Hormat kami</span>
                </td>
                <td>
                    <span class="mb-2">Disetujui</span>
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
<!-- <script>
    window.addEventListener('afterprint', (event) => {
        console.log('After print');
    });

    window.onbeforeprint = function() {
        console.log('This will be called before the user prints.');
    };
    window.onafterprint = function() {
        console.log('This will be called after the user prints');
    };
</script> -->

</html>