<div class="content-header row">
    <div class="content-header-left col-md-12 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#" onclick="return false;"><?= $this->lang->line('Informasi & Ekspor'); ?></a></li>
                    <li class="breadcrumb-item"><a href="#" onclick="return false;"><?= $this->lang->line('Pembelian'); ?></a></li>
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
                                            <div class="col-md-4">
                                                <fieldset>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <button class="btn <?= $this->session->e_color; ?> bg-darken-1 text-white" type="button"><i class="feather icon-calendar"></i></button>
                                                        </div>
                                                        <input type="text" readonly required value="<?= $dfrom; ?>" name="dfrom" id="dfrom" class="form-control date" placeholder="Select Date" aria-label="Amount">
                                                    </div>
                                                </fieldset>
                                            </div>
                                            <div class="col-md-4">
                                                <fieldset>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <button class="btn <?= $this->session->e_color; ?> bg-darken-1 text-white" required type="button"><i class="feather icon-calendar"></i></button>
                                                        </div>
                                                        <input type="text" readonly value="<?= $dto; ?>" name="dto" id="dto" class="form-control date" placeholder="Select Date" aria-label="Amount">
                                                    </div>
                                                </fieldset>
                                            </div>
                                            <div class="col-md-3">
                                                <fieldset>
                                                    <div class="input-group">
                                                        <select class="form-control" name="i_supplier" id="i_supplier" data-placeholder="<?= $this->lang->line("Pilih") . ' ' . $this->lang->line("Area"); ?>" required data-validation-required-message="<?= $this->lang->line("Required"); ?>">
                                                            <option value="<?= $i_supplier; ?>"><?= $e_supplier_name; ?></option>
                                                        </select>
                                                    </div>
                                                </fieldset>
                                            </div>
                                            <div class="col-md-1">
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
                            <?php if (check_role($this->id_menu, 1)) {
                                $id_menu = $this->id_menu;
                            } else {
                                $id_menu = "";
                            } ?>
                            <input type="hidden" id="id_menu" value="<?= $id_menu; ?>">
                            <input type="hidden" id="path" value="<?= $this->folder; ?>">
                            <div class="table-responsive">
                                <table class="table table-xs display nowrap table-striped table-bordered" id="serverside" width="100%;">
                                    <thead class="<?= $this->session->e_color; ?> bg-darken-1 text-white">
                                        <tr>
                                            <th>No</th>
                                            <th><?= $this->lang->line('No Nota'); ?></th>
                                            <th><?= $this->lang->line('Tgl Nota'); ?></th>
                                            <th><?= $this->lang->line('No Penerimaan'); ?></th>
                                            <th><?= $this->lang->line('Tgl Terima'); ?></th>
                                            <th><?= $this->lang->line('Nama Pemasok'); ?></th>
                                            <th><?= $this->lang->line('Kode Barang'); ?></th>
                                            <th><?= $this->lang->line('Nama Barang'); ?></th>
                                            <th><?= $this->lang->line('Terima'); ?></th>
                                            <th><?= $this->lang->line('Harga Rp'); ?></th>
                                            <th><?= $this->lang->line('Diskon Rp'); ?></th>
                                            <th><?= $this->lang->line('Total Rp'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                    <tfoot>
                                        <?php
                                        $ka = 0;
                                        $yu = 0;
                                        $re = 0;
                                        $su = 0;
                                        if ($data->num_rows() > 0) {
                                            foreach ($data->result() as $key) {
                                                $ka += $key->kirim;
                                                $yu += $key->jml;
                                                $re += $key->disc;
                                                $su += $key->tot;
                                        ?>
                                        <?php }
                                        }
                                        ?>
                                        <tr>
                                            <th class="text-right" colspan="8"><strong>TOTAL</strong></th>
                                            <th class="text-right"><strong><?= $ka; ?></strong></th>
                                            <th class="text-right"><strong>Rp. <?= number_format($yu, 2); ?></strong></th>
                                            <th class="text-right"><strong>Rp. <?= number_format($re, 2); ?></strong></th>
                                            <th class="text-right"><strong>Rp. <?= number_format($su, 2); ?></strong></th>
                                        </tr>
                                    </tfoot>
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