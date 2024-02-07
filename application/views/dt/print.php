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

<!-- <body oncontextmenu='return false;' onkeydown='return false;' onmousedown='return false;'> -->

<body>
    <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
            <td>
                <p class="header text-bold-700"><?= $this->lang->line('Daftar Tagihan'); ?></p>
            </td>
            <td rowspan=2 class="text-right" rowspan="3"><img src="<?= base_url(); ?>assets/images/<?= $company->e_company_logo; ?>" alt="company-logo" height="69" width="79"></td>
        </tr>
        <tr>
            <td><span class="judul">No. : <?= $data->i_dt_id; ?></span></td>
        </tr>
        <!-- <tr>
            <td><span class="judul">Tanggal Nota : <?= date('d', strtotime($data->d_nota)) . ' ' . bulan(date('m', strtotime($data->d_nota))) . ' ' . date('Y', strtotime($data->d_nota)); ?></span></td>
        </tr> -->
        <tr>
            <td colspan="2">
                <hr class="mt-0 mb-0">
            </td>
        </tr>
        <tr>
            <td width="50%"><span class="header2 text-bold-700"><?= $company->e_company_name; ?></span></td>
            <td class="text-right"><span class="text-bold-700 header2"><?= $data->e_area_name; ?></span></td>
        </tr>
        <tr>
            <td><span class="judul text-capitalize"><?php if ($company->e_company_npwp_code != null) { ?><?= $this->lang->line('NPWP'); ?> - <?= $company->e_company_npwp_code; ?><?php } ?></span></td>
            <td class="text-right"><?= date('d', strtotime($data->d_dt)) . ' ' . bulan(date('m', strtotime($data->d_dt))) . ' ' . date('Y', strtotime($data->d_dt)); ?></td>
        </tr>
        <tr>
            <td><span class="judul"><?php if ($company->e_company_phone != null) { ?><?= $this->lang->line('Telepon'); ?> - <?= $company->e_company_phone; ?><?php } ?></span></td>
            <td width="50%" class="text-right"></span></td>
        </tr>
        
    </table>
    <input type="hidden" id="id" value="<?= $data->i_dt; ?>">
    <input type="hidden" id="path" value="<?= base_url($this->folder); ?>">
    <table class="judul" width="100%" border="1" cellspacing="0" cellpadding="2">
        <thead>
            <tr>
                <th scope="col" rowspan="2" class="text-center" width="3%">No</th>
                <th scope="col" colspan="3" class="text-center" width="10%"><?= $this->lang->line('Faktur'); ?></th>
                <th scope="col" colspan="3" class="text-center" width="10%"><?= $this->lang->line('Debitur'); ?></th>
                <th scope="col" rowspan="2" class="text-center" width="5%"><?= $this->lang->line('Jumlah'); ?></th>
                <th scope="col" rowspan="2" class="text-center" width="5%"><?= $this->lang->line('Sisa'); ?></th>
                <th scope="col" rowspan="2" class="text-center" width="10%"><?= $this->lang->line('Tunai'); ?></th>
                <th scope="col" colspan="4" class="text-center">Transfer / Cek / Giro Bilyet</th>
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
        <tbody>
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
                        <td class="text-right"><?= number_format($key->v_sisa); ?></td>
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
        <tfoot>
            <tr>
                <th colspan="7" class="text-right"><?= $this->lang->line('Jumlah'); ?> Rp. </th>
                <th class="text-right text-bold-700"><?= number_format($total); ?></th>
                <th colspan="8"></th>
            </tr>
        </tfoot>
    </table>
    <hr class="mt-1 mb-0">
    <table width="100%;" class="font-14">
            <tbody>
                <tr>
                    <td width="10%">Sudah Terima : Tunai</td>
                    <td colspan="5">= Rp.</td>
                </tr>
                <tr>
                    <td width="160px">Giro / Cek = ....... lbr</td>
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
            <tfoot>
                <tr>
                    <td colspan="6"><span class="judul2"><br></span></td>
                </tr>
                <tr>
                    <td colspan="6"><span class="font-12">*Tanggal Cetak : <?= date('d') . ' ' . bulan(date('m')) . ' ' . date('Y') . ' ' . date('H:i:s'); ?></span></td>
                </tr>
                <tr>
                <td colspan="6" class="text-center"><a href="#" onclick="window.print();" class="button button1 no-print"> <i class="feather icon-printer mr-25 common-size"></i> <?= $this->lang->line("Cetak"); ?></a></td>
                </tr>
            </tfoot>
    </table>
    
    <!-- <?= put_footer(); ?> -->
</body>
<!-- END: Body-->

</html>