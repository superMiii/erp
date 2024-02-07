<div class="content-header row">
    <div class="content-header-left col-md-12 col-12 mb-2">
        <h3 class="content-header-title mb-0"><?= $this->lang->line('Cetak'); ?></h3>
        <div class="row breadcrumbs-top">
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#" onclick="return false;"><?= $this->lang->line('Gudang'); ?></a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url($this->folder); ?>"><?= $this->lang->line($this->title); ?></a></li>
                    <li class="breadcrumb-item active"><?= $this->lang->line('Cetak'); ?> <?= $this->lang->line($this->title); ?></li>
                </ol>
            </div>
        </div>
    </div>
</div>
<div class="content-body">
    <!-- App invoice wrapper -->
    <section class="app-invoice-wrapper">
        <div class="row">
            <div class="col-xl-9 col-md-8 col-12 printable-content">
                <!-- using a bootstrap card -->
                <div class="card">
                    <!-- card body -->
                    <div class="card-body p-2 area-print">
                        <!-- card-header -->
                        <!-- <div class="card-header px-0">
                            <div class="row">
                                <div class="col-md-12 col-lg-7 col-xl-4 mb-50">
                                    <span class="invoice-id font-weight-bold">Invoice# </span>
                                    <span>948372</span>
                                </div>
                                <div class="col-md-12 col-lg-5 col-xl-8">
                                    <div class="d-flex align-items-center justify-content-end justify-content-xs-start">
                                        <div class="issue-date pr-2">
                                            <span class="font-weight-bold no-wrap">Issue Date: </span>
                                            <span>07/02/2019</span>
                                        </div>
                                        <div class="due-date">
                                            <span class="font-weight-bold no-wrap">DueDate: </span>
                                            <span>06/04/2019</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> -->

                        <!-- invoice logo and title -->
                        <div class="invoice-logo-title row">
                            <div class="col-12 d-flex flex-column justify-content-center align-items-start">
                                <h2 class="text-primary"><?= strtoupper($this->session->e_company_name); ?></h2>
                                <span>Alamat Perusahaan</span>
                            </div>
                            <!-- <div class="col-6 d-flex justify-content-end invoice-logo">
                                <img src="<?= base_url(); ?>assets/images/hade.jpeg" alt="company-logo" height="70" width="140">
                            </div> -->
                        </div>
                        <hr>

                        <!-- invoice address and contacts -->
                        <div class="row invoice-adress-info mb-1">
                            <div class="col-6 mt-1 from-info">
                                <div class="company-name mb-1">
                                    <span class="text-muted">Kepada Yth,</span>
                                </div>
                                <div class="info-title mb-1">
                                    <span>Bagian Pembelian</span>
                                </div>
                                <div class="company-name mb-1">
                                    <span class="text-muted"><?= strtoupper($this->session->e_company_name); ?></span>
                                </div>
                            </div>
                            <div class="col-6 mt-1 to-info">
                                <div class="info-title text-center mb-1">
                                    <h2 class="text-uppercase text-bold-600">Terima Barang dari Produksi</h2>
                                </div>
                                <div class="company-name text-center mb-1">
                                    <span class="text-muted">No. Dokumen : <?= $data->i_gi_id; ?></span>
                                </div>
                                <div class="company-address text-center mb-1">
                                    <span class="text-muted">Tanggal Dokumen : <?= date('d', strtotime($data->d_gi)) . ' ' . bulan(date('m', strtotime($data->d_gi))) . ' ' . date('Y', strtotime($data->d_gi)); ?></span>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <span class="text-muted">Dengan hormat,</span>
                                <p class="text-muted mt-1">Bersama surat ini kami mohon dikirimkan barang-barang sbb :</p>
                            </div>
                        </div>

                        <!--product details table -->
                        <div class="product-details-table table-responsive">
                            <table class="table table-xs table-column table-borderless">
                                <thead>
                                    <tr>
                                        <th scope="col" class="text-center" width="5%">No</th>
                                        <th scope="col">Kode</th>
                                        <th scope="col">Nama Barang</th>
                                        <th scope="col">Keterangan</th>
                                        <th scope="col" class="text-right">Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 0;
                                    if ($detail->num_rows() > 0) {
                                        foreach ($detail->result() as $key) {
                                            $i++; ?>
                                            <tr>
                                                <td class="text-center"><?= $i; ?></td>
                                                <td><?= $key->i_product_id; ?></td>
                                                <td><?= $key->e_product_name; ?></td>
                                                <td><?= $key->e_remark; ?></td>
                                                <td class="text-right"><?= $key->n_quantity; ?></td>
                                            </tr>
                                    <?php }
                                    } ?>
                                </tbody>
                            </table>
                        </div>
                        <hr>

                        <!-- invoice total -->
                        <div class="invoice-total">
                            <div class="row">
                                <div class="col-sm-12">
                                    <span>Demikian surat ini kami sampaikan, atas perhatian dan kerjasamanya kami ucapkan terima kasih.</span>
                                </div>
                                <div class="col-sm-12">
                                    <div class="table-responsive">
                                        <table class="table table-xs table-borderless" width="100%;">
                                            <tbody class="text-center">
                                                <tr>
                                                    <td>
                                                        <h6 class="mt-3 mb-5">Hormat kami,</h6>
                                                    </td>
                                                    <td>
                                                        <h6 class="mt-3 mb-5">Menyetujui,</h6>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-muted">( Kepala Gudang )</td>
                                                    <td class="text-muted">( Supervisor Administrasi )</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <span>Tanggal Cetak : <?= date('d') . ' ' . bulan(date('m')) . ' ' . date('Y') . ' ' . date('H:i:s'); ?></span>
                                </div>
                                <!-- <div class="col-4 col-sm-12 mt-75">
                                    <p>Demikian surat ini kami sampaikan, atas perhatian dan kerjasamanya kami ucapkan terima kasih.</p>
                                </div>
                                <div class="col-8 col-sm-12 d-flex text-center justify-content-end mt-75">
                                    <ul class="list-group cost-list">
                                        <li class="list-group-item each-cost border-0 p-50 d-flex justify-content-between">
                                            <span class="cost-title mb-5 mr-2">Menyetujui, </span>
                                        </li>
                                        <li class="list-group-item each-cost border-0 p-50 d-flex justify-content-between">
                                            <span class="cost-title mr-2">( Supervisor Administrasi )</span>
                                        </li>
                                        <li class="dropdown-divider"></li>
                                    </ul>
                                </div> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- buttons section -->
            <div class="col-xl-3 col-md-4 col-12 action-btns">
                <div class="card">
                    <div class="card-body p-2">
                        <!-- <a href="#" class="btn btn-primary btn-block mb-1"> <i class="feather icon-check mr-25 common-size"></i> Send Invoice</a> -->
                        <a href="#" class="btn btn-info btn-block mb-1 print-invoice"> <i class="feather icon-printer mr-25 common-size"></i> <?= $this->lang->line("Cetak"); ?></a>
                        <a href="<?= base_url($this->folder); ?>" class="btn btn-block mb-1 btn-secondary btn-min-width"><i class="icon-action-undo mr-1"></i><?= $this->lang->line("Kembali"); ?></a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
