<!-- <form class="form-validation" novalidate> -->
<div class="content-header row">
    <div class="content-header-left col-md-12 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#" onclick="return false;"><?= $this->lang->line('Informasi & Ekspor'); ?></a></li>
                    <li class="breadcrumb-item"><a href="#" onclick="return false;"><?= $this->lang->line('Gudang'); ?></a></li>
                    <li class="breadcrumb-item active"><?= $this->lang->line($this->title); ?>
                    </li>
                </ol>
            </div>
        </div>
        <!-- <h3 class="content-header-title mb-0">Basic DataTables</h3> -->
    </div>
</div>
<div class="content-body">
    <!-- Alternative pagination table -->
    <section id="pagination">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header header-elements-inline <?= $this->session->e_color; ?> bg-darken-1 text-white">
                        <h4 class="card-title"><i class="feather icon-list"></i><?= $this->lang->line('Daftar'); ?> <?= $this->lang->line($this->title); ?></h4>
                        <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                        <div class="heading-elements">
                            <ul class="list-inline mb-0">
                                <li><a data-action="collapse"><i class="feather icon-minus"></i></a></li>
                                <li><a data-action="reload"><i class="feather icon-rotate-cw"></i></a></li>
                                <li><a data-action="expand"><i class="feather icon-maximize"></i></a></li>
                                <li><a data-action="close"><i class="feather icon-x"></i></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-content collapse show">
                        <div class="card-body card-dashboard">
                            <form class="form form-horizontal" action="<?= base_url($this->folder); ?>" method="post">
                                <div class="form-body">
                                    <h6 class="form-section"></h6>
                                    <div class="form-group row m-auto">
                                        <div class="col-md-12 row m-auto">
                                            <div class="col-md-5">
                                                <fieldset>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <button class="btn <?= $this->session->e_color; ?> bg-darken-1 text-white" type="button"><i class="feather icon-calendar"></i></button>
                                                        </div>
                                                        <input type="text" readonly required value="<?= $dfrom; ?>" name="dfrom" id="dfrom" class="form-control date" placeholder="Select Date" aria-label="Amount">
                                                    </div>
                                                </fieldset>
                                            </div>
                                            <div class="col-md-5">
                                                <fieldset>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <button class="btn <?= $this->session->e_color; ?> bg-darken-1 text-white" required type="button"><i class="feather icon-calendar"></i></button>
                                                        </div>
                                                        <input type="text" readonly value="<?= $dto; ?>" name="dto" id="dto" class="form-control date" placeholder="Select Date" aria-label="Amount">
                                                        <!-- <input type="hidden" value="<?= ($dfrom); ?>" name="d_from" id="d_from">
                                                        <input type="hidden" value="<?= ($dto); ?>" name="d_to" id="d_to"> -->
                                                        <input type="hidden" id="d_from" value="<?= encrypt_url($dfrom); ?>">
                                                        <input type="hidden" id="d_to" value="<?= encrypt_url($dto); ?>">
                                                    </div>
                                                </fieldset>
                                            </div>
                                            <div class="col-md-2">
                                                <fieldset>
                                                    <div class="input-group">
                                                        <button class="btn btn-block <?= $this->session->e_color; ?> bg-darken-1 text-white" type="submit"><i class="feather icon-search fa-lg"></i></button>
                                                    </div>
                                                </fieldset>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-actions">
                                </div>
                            </form>


                            <?php if (check_role($this->id_menu, 3)) { ?>
                                <form class="form-validation" novalidate>
                                    <div class="col-md-12 row m-auto">
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <div class="controls">
                                                    <input type="hidden" value="<?= ($dfrom); ?>" name="d_from2" id="d_from2">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <div class="controls">
                                                    <input type="hidden" value="<?= ($dto); ?>" name="d_to2" id="d_to2">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="d-flex justify-content-start align-items-center">
                                                <button type="submit" class="btn btn-secondary round btn-min-width mr-1"><i class="icon-paper-plane mr-1"></i><?= $this->lang->line('Simpan'); ?></button>
                                            </div>
                                        </div>

                                    </div>
                                </form>
                            <?php } ?>


                            <input type="hidden" id="path" value="<?= $this->folder; ?>">
                            <div class="table-responsive">
                                <table class="table table-xs display nowrap table-striped table-bordered" id="serverside" width="100%;">
                                    <thead class="<?= $this->session->e_color; ?> bg-darken-1 text-white">
                                        <tr>
                                            <th rowspan="2">No</th>
                                            <th rowspan="2"><?= $this->lang->line('Kode Barang'); ?></th>
                                            <th rowspan="2"><?= $this->lang->line('Nama Barang'); ?></th>
                                            <th rowspan="2"><?= $this->lang->line('Saldo Awal'); ?></th>
                                            <th colspan="4" class="text-center"><?= $this->lang->line('Penerimaan'); ?></th>
                                            <th colspan="5" class="text-center"><?= $this->lang->line('Pengeluaran'); ?></th>
                                            <th rowspan="2"><?= $this->lang->line('Penyesuaian Stok'); ?></th>
                                            <th rowspan="2"><?= $this->lang->line('Saldo Akhir'); ?></th>
                                            <th rowspan="2"><?= $this->lang->line('Stokopname'); ?></th>
                                            <th rowspan="2"><?= $this->lang->line('Selisih'); ?></th>
                                            <th rowspan="2"><?= $this->lang->line('GiT'); ?></th>
                                            <th rowspan="2"><?= $this->lang->line('Hystory GiT'); ?></th>
                                            <th rowspan="2"><?= $this->lang->line('Aksi'); ?></th>
                                        </tr>
                                        <tr>
                                            <th><?= $this->lang->line('Pembelian'); ?></th>
                                            <th><?= $this->lang->line('Retur Penjualan'); ?></th>
                                            <th><?= $this->lang->line('Retur Cabang'); ?></th>
                                            <th><?= $this->lang->line('Konversi Masuk'); ?></th>
                                            <th><?= $this->lang->line('Penjualan'); ?></th>
                                            <th><?= $this->lang->line('Pinjaman Cabang'); ?></th>
                                            <th><?= $this->lang->line('BBK Hadiah'); ?></th>
                                            <th><?= $this->lang->line('Retur Pembelian'); ?></th>
                                            <th><?= $this->lang->line('Konversi Keluar'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--/ Alternative pagination table -->
</div>

<!-- </form> -->