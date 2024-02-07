<style>
    .table.table-xs th,
    .table td,
    .table.table-xs td {
        padding: 0.4rem 0.4rem;
    }

    .table>tfoot>tr>th,
    .table>tfoot>tr>td {
        border: none !important;
    }
</style>
<form class="form-validation" novalidate>
    <div class="content-header row">
        <div class="content-header-left col-md-12 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#" onclick="return false;"><?= $this->lang->line('Gudang'); ?></a></li>
                        <li class="breadcrumb-item"><a href="<?= base_url($this->folder); ?>"><?= $this->lang->line($this->title); ?></a></li>
                        <li class="breadcrumb-item"><a href="<?= base_url($this->folder . '/indexx'); ?>"><?= $this->lang->line('Pesanan Sales'); ?></a></li>
                        <li class="breadcrumb-item active"><?= $this->lang->line('Detail'); ?> <?= $this->lang->line($this->title); ?></li>
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
                        <div class="card-header header-elements-inline <?= $this->session->e_color;?> bg-darken-1 text-white">
                            <h4 class="card-title"><i class="icon-eye"></i> <?= $this->lang->line('Detail'); ?> <?= $this->lang->line($this->title); ?></h4>
                            <input type="hidden" id="path" value="<?= $this->folder; ?>">
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
                                <!-- Baris ke 1 -->
                                <div class="row">
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Nomor Dokumen"); ?> :</label>
                                            <div class="controls">
                                                <input type="hidden" name="id" id="id" class="form-control" value="<?= $data->i_do; ?>">
                                                <input type="hidden" name="i_document_old" id="i_document_old" class="form-control" value="<?= $data->i_do_id; ?>">
                                                <input type="text" name="i_document" id="i_document" readonly value="<?= $data->i_do_id; ?>" placeholder="DO-<?= date('ym'); ?>-000001" class="form-control form-control-sm text-uppercase" data-validation-required-message="<?= $this->lang->line("Required"); ?>" maxlength="500" autocomplete="off" required aria-label="Text input with checkbox">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Tanggal Dokumen"); ?> :</label>
                                            <div class="input-group input-group-sm controls">
                                                <input type="text" class="form-control form-control-sm date" value="<?= date('d-m-Y', strtotime($data->d_do)); ?>" readonly data-validation-required-message="<?= $this->lang->line("Required"); ?>" placeholder="" id="d_document" name="d_document" maxlength="500" autocomplete="off" required>
                                                <div class="input-group-append">
                                                    <span class="input-group-text">
                                                        <span class="fa fa-calendar-o"></span>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Nama Area Provinsi"); ?> :</label>
                                            <div class="controls">
                                                <input type="hidden" name="i_area" id="i_area" class="form-control" value="<?= $data->i_area; ?>">
                                                <input type="text" readonly class="form-control form-control-sm" value="<?= $data->e_area_name; ?>" readonly data-validation-required-message="<?= $this->lang->line("Required"); ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Baris ke 2 -->
                                <div class="row">
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Nomor Pesanan"); ?> :</label>
                                            <div class="controls">
                                                <input type="hidden" name="i_so" id="i_so" class="form-control" value="<?= $data->i_so; ?>">
                                                <input type="text" value="<?= $data->i_so_id; ?>" readonly class="form-control form-control-sm text-uppercase" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Tanggal Pesanan"); ?> :</label>
                                            <div class="input-group input-group-sm controls">
                                                <input type="text" class="form-control form-control-sm" value="<?= date('d-m-Y', strtotime($data->d_so)); ?>" readonly data-validation-required-message="<?= $this->lang->line("Required"); ?>" placeholder="" id="d_document" name="d_document" maxlength="500" autocomplete="off" required>
                                                <div class="input-group-append">
                                                    <span class="input-group-text">
                                                        <span class="fa fa-calendar-o"></span>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Nama Pelanggan"); ?> :</label>
                                            <div class="controls">
                                                <input type="hidden" name="i_customer" id="i_customer" class="form-control" value="<?= $data->i_customer; ?>">
                                                <input type="text" readonly class="form-control form-control-sm" value="<?= $data->e_customer_name; ?>" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Baris ke 3 -->
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Nama Pramuniaga"); ?> :</label>
                                            <div class="controls">
                                                <input type="hidden" name="i_salesman" id="i_salesman" class="form-control" value="<?= $data->i_salesman; ?>">
                                                <input type="text" readonly class="form-control form-control-sm" value="<?= $data->e_salesman_name; ?>" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Gudang"); ?> :</label>
                                            <div class="controls">
                                                <input type="hidden" name="i_store" id="i_store" class="form-control" value="<?= $data->i_store; ?>">
                                                <input type="text" readonly class="form-control form-control-sm" value="<?= $data->e_area_name; ?>" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Baris ke 4 -->
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Keterangan"); ?> :</label>
                                            <div class="controls">
                                                <textarea type="text" readonly rows="3" class="form-control form-control-sm" name="e_remark"><?= $data->e_remark; ?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!--/ Alternative pagination table -->
    </div>
    <div class="content-body">
        <!-- Alternative pagination table -->
        <section id="pagination">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header header-elements-inline <?= $this->session->e_color;?> bg-darken-1 text-white">
                            <h4 class="card-title"><i class="fa fa-cart-arrow-down"></i> <?= $this->lang->line("Detail"); ?> <?= $this->lang->line($this->title); ?></h4>
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
                                <div class="form-body">
                                    <div class="table-responsive">
                                        <table class="table table-xs table-column table-bordered" id="tabledetail">
                                            <thead class="<?= $this->session->e_color;?> bg-darken-1 text-white">
                                                <tr>
                                                    <th class="text-center" width="10%;" valign="center">No</th>
                                                    <th class="text-center" valign="center"><?= $this->lang->line("Kode Barang"); ?></th>
                                                    <th class="text-center" valign="center"><?= $this->lang->line("Nama Barang"); ?></th>
                                                    <th class="text-center" valign="center"><?= $this->lang->line("Motif"); ?></th>
                                                    <th class="text-center" valign="center"><?= $this->lang->line("Qty Pesan"); ?></th>
                                                    <th class="text-center" valign="center"><?= $this->lang->line("Qty Stok"); ?></th>
                                                    <th class="text-center" width="15%;" valign="center"><?= $this->lang->line("Qty Kirim"); ?></th>
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
                                                            <td>
                                                                <input type="hidden" value="<?= $key->i_product; ?>" id="i_product<?= $i; ?>" name="i_product[]" readonly>
                                                                <?= $key->i_product_id; ?>
                                                            </td>
                                                            <td>
                                                                <input type="hidden" value="<?= $key->e_product_name; ?>" id="e_product_name<?= $i; ?>" name="e_product_name[]" readonly>
                                                                <?= $key->e_product_name; ?>
                                                            </td>
                                                            <td>
                                                                <input type="hidden" value="<?= $key->i_product_grade; ?>" id="i_product_grade<?= $i; ?>" name="i_product_grade[]" readonly>
                                                                <input type="hidden" value="<?= $key->i_product_motif; ?>" id="i_product_motif<?= $i; ?>" name="i_product_motif[]" readonly>
                                                                <?= $key->e_product_motifname; ?>
                                                            </td>
                                                            <td class="text-right">
                                                                <?= number_format($key->n_order); ?>
                                                                <input type="hidden" value="<?= $key->n_deliver; ?>" id="n_order<?= $i; ?>" name="n_order[]" readonly>
                                                            </td>
                                                            <td class="text-right">
                                                                <?= number_format($key->n_do); ?>
                                                                <input type="hidden" value="<?= $key->n_do ?>" id="n_stock<?= $i; ?>" name="n_stock[]" readonly>
                                                            <td class="text-right"><?= number_format($key->n_do); ?><input type="hidden" value="<?= $key->n_do; ?>" autocomplete="off" class="form-control text-right n_order form-control-sm" id="n_deliver<?= $i; ?>" name="n_deliver[]" onkeypress="return bilanganasli(event);hitung();" onkeyup="hitung()" onblur="if(this.value=='' ){this.value='0';}" onfocus="if(this.value=='0' ){this.value='' ;}"></td>
                                                        </tr>
                                                <?php }
                                                } ?>
                                            </tbody>
                                            <input type="hidden" id="jml" name="jml" value="<?= $i; ?>">
                                        </table>
                                    </div>
                                </div>
                                <div class="form-actions">
                                    <a href="<?= base_url($this->folder); ?>" class="btn btn-secondary btn-min-width"><i class="icon-action-undo mr-1"></i><?= $this->lang->line("Kembali"); ?></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!--/ Alternative pagination table -->
    </div>
</form>