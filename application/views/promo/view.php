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
<div class="content-header row">
    <div class="content-header-left col-md-12 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#" onclick="return false;"><?= $this->lang->line('Penjualan'); ?></a></li>
                    <!-- <li class="breadcrumb-item"><a href="<?= base_url($this->folder); ?>"><?= $this->lang->line($this->title); ?></a></li> -->
                    <li class="breadcrumb-item"><a href="<?= base_url($this->folder . '/index/' . encrypt_url($dfrom) . '/' . encrypt_url($dto)); ?>"><?= $this->lang->line($this->title); ?></a></li>
                    <li class="breadcrumb-item active"><?= $this->lang->line('Detail'); ?> <?= $this->lang->line($this->title); ?></li>
                </ol>
            </div>
        </div>
    </div>
</div>
<!-- HEADER -->
<div class="content-body">
    <section id="pagination">
        <div class="row">
            <div class="col-12">
                <div class="card box-shadow-2">
                    <div class="card-header card-head-inverse <?= $this->session->e_color; ?> bg-darken-1 text-white">
                        <h4 class="card-title"><i class="icon-eye"></i> <?= $this->lang->line('Detail'); ?> <?= $this->lang->line($this->title); ?></h4>
                        <input type="hidden" id="path" value="<?= $this->folder; ?>">
                        <input type="hidden" id="d_from" value="<?= encrypt_url($dfrom); ?>">
                        <input type="hidden" id="d_to" value="<?= encrypt_url($dto); ?>">
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
                                <!-- Baris ke 1 -->
                                <div class="row">
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Nomor Dokumen"); ?> :</label>
                                            <fieldset>
                                                <div class="input-group input-group-sm">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">
                                                            <span class="fa fa-hashtag"></span>
                                                        </div>
                                                    </div>
                                                    <input type="text" name="i_document" id="i_document" value="<?= $data->i_promo_id; ?>" readonly placeholder="PR-<?= date('ym'); ?>-000001" class="form-control form-control-sm text-uppercase" data-validation-required-message="<?= $this->lang->line("Required"); ?>" maxlength="500" autocomplete="off" required aria-label="Text input with checkbox">
                                                </div>
                                            </fieldset>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Tanggal Dokumen"); ?> :</label>
                                            <div class="input-group input-group-sm controls">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <span class="fa fa-calendar-o"></span>
                                                    </span>
                                                </div>
                                                <input type="text" class="form-control form-control-sm date" readonly value="<?= $data->dpromo; ?>" data-validation-required-message="<?= $this->lang->line("Required"); ?>" placeholder="" id="d_document" name="d_document" maxlength="500" autocomplete="off" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Berlaku Dari"); ?> :</label>
                                            <div class="input-group input-group-sm controls">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <span class="fa fa-calendar-o"></span>
                                                    </span>
                                                </div>
                                                <input type="text" class="form-control form-control-sm date" readonly value="<?= $data->dstart; ?>" data-validation-required-message="<?= $this->lang->line("Required"); ?>" placeholder="" id="d_promo_start" name="d_promo_start" maxlength="500" autocomplete="off" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Berlaku Sampai"); ?> :</label>
                                            <div class="input-group input-group-sm controls">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <span class="fa fa-calendar-o"></span>
                                                    </span>
                                                </div>
                                                <input type="text" class="form-control form-control-sm date" readonly value="<?= $data->dfinish; ?>" data-validation-required-message="<?= $this->lang->line("Required"); ?>" placeholder="" id="d_promo_finish" name="d_promo_finish" maxlength="500" autocomplete="off" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Baris ke 2 -->
                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Keterangan Promo"); ?> :</label>
                                            <div class="controls">
                                                <input type="text" class="form-control form-control-sm text-capitalize" readonly value="<?= $data->e_promo_name; ?>" maxlength="500" name="e_promo_name" required placeholder="<?= $this->lang->line("Keterangan Promo"); ?>" data-validation-required-message="<?= $this->lang->line("Required"); ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Tipe Promo"); ?> :</label>
                                            <div class="controls">
                                                <input type="text" class="form-control form-control-sm text-capitalize" readonly value="<?= $data->e_promo_type_name; ?>" maxlength="500" name="e_promo_type_name" required placeholder="<?= $this->lang->line("Tipe Promo"); ?>" data-validation-required-message="<?= $this->lang->line("Required"); ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Kelompok Harga"); ?> :</label>
                                            <div class="controls">
                                                <input type="text" class="form-control form-control-sm text-capitalize" readonly value="<?= $data->e_price_groupname; ?>" maxlength="500" name="e_price_groupname" required placeholder="<?= $this->lang->line("Kelompok Harga"); ?>" data-validation-required-message="<?= $this->lang->line("Required"); ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Baris ke 3 -->
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group text-center">
                                            <label><?= $this->lang->line("Semua Barang"); ?> :</label>
                                            <div class="controls skin-square">
                                                <input type="checkbox" <?php if ($data->f_all_product == 't') {
                                                                            echo "checked";
                                                                        } ?> id="f_all_product" name="f_all_product">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group text-center">
                                            <label><?= $this->lang->line("Semua Pelanggan"); ?> :</label>
                                            <div class="controls skin-square">
                                                <input type="checkbox" <?php if ($data->f_all_customer == 't') {
                                                                            echo "checked";
                                                                        } ?> id="f_all_customer" name="f_all_customer">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group text-center">
                                            <label><?= $this->lang->line("Semua Area"); ?> :</label>
                                            <div class="controls skin-square">
                                                <input type="checkbox" <?php if ($data->f_all_area == 't') {
                                                                            echo "checked";
                                                                        } ?> id="f_all_area" name="f_all_area">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2"></div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Diskon1"); ?> % :</label>
                                            <div class="controls">
                                                <input type="number" class="form-control form-control-sm" readonly id="n_promo_discount1" name="n_promo_discount1" value="<?= $data->n_promo_discount1; ?>" min="0" max="100">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Diskon2"); ?> % :</label>
                                            <div class="controls">
                                                <input type="number" class="form-control form-control-sm" readonly id="n_promo_discount2" name="n_promo_discount2" value="<?= $data->n_promo_discount2; ?>" min="0" max="100">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-actions">
                                <form action="<?= base_url($this->folder); ?>" method="post">
                                    <input type="hidden" name="dfrom" value="<?= $dfrom; ?>">
                                    <input type="hidden" name="dto" value="<?= $dto; ?>">
                                    <div class="form-actions mb-0 mt-0">
                                        <!-- <button type="submit" class="btn bg-danger bg-darken-3 text-white btn-min-width"><i class="icon-action-undo mb-0 mr-1"></i><?= $this->lang->line("Kembali"); ?></button> -->
                                        <a href="<?= base_url($this->folder . '/index/' . encrypt_url($dfrom) . '/' . encrypt_url($dto)); ?>" class="btn btn-secondary round btn-min-width mr-1"><i class="icon-action-undo mr-1"></i><?= $this->lang->line("Kembali"); ?></a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<!-- END HEADER -->

<!-- TABEL PRODUCT -->
<?php $p = 0;
if ($detail->num_rows() > 0) { ?>
    <div class="content-body tableproduct" <?php if ($data->f_all_product == 't') {
                                                echo "hidden='true'";
                                            } ?>>
        <section id="pagination">
            <div class="row">
                <div class="col-12">
                    <div class="card box-shadow-2">
                        <div class="card-header header-elements-inline">
                            <h4 class="card-title"><i class="feather icon-box"></i> <?= $this->lang->line("Detail"); ?> <?= $this->lang->line("Barang"); ?></h4>
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
                                <div class="table-responsive">
                                    <table class="table table-xs table-column table-bordered" id="tableproduct">
                                        <thead class="<?= $this->session->e_color; ?> bg-darken-1 text-white">
                                            <tr>
                                                <th class="text-center" width="5%">No</th>
                                                <th><?= $this->lang->line("Kode Barang"); ?></th>
                                                <th><?= $this->lang->line("Nama Barang"); ?></th>
                                                <th><?= $this->lang->line("Motif"); ?></th>
                                                <th class="text-right"><?= $this->lang->line("Harga"); ?></th>
                                                <th class="text-right"><?= $this->lang->line("Minimal Order"); ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($detail->result() as $key) {
                                                $p++; ?>
                                                <tr>
                                                    <td class="text-center"><?= $p; ?></td>
                                                    <td><?= $key->i_product_id; ?></td>
                                                    <td><?= $key->e_product_name; ?></td>
                                                    <td><?= $key->e_product_motifname; ?></td>
                                                    <td class="text-right"><?= number_format($key->v_unit_price); ?></td>
                                                    <td class="text-right"><?= $key->n_quantity_min; ?></td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                        <input type="hidden" id="jml_product" name="jml_product" value="<?= $p; ?>">
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
<?php } ?>
<!-- END TABEL PRODUCT -->

<!-- TABEL CUSTOMER -->
<?php $c = 0;
if ($customer->num_rows() > 0) { ?>
    <div class="content-body tablecustomer" <?php if ($data->f_all_customer == 't') {
                                                echo "hidden='true'";
                                            } ?>>
        <section id="pagination">
            <div class="row">
                <div class="col-12">
                    <div class="card box-shadow-2">
                        <div class="card-header header-elements-inline">
                            <h4 class="card-title"><i class="feather icon-users"></i> <?= $this->lang->line("Detail"); ?> <?= $this->lang->line("Pelanggan"); ?></h4>
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
                                <div class="table-responsive">
                                    <table class="table table-xs table-column table-bordered" id="tablecustomer">
                                        <thead class="<?= $this->session->e_color; ?> bg-darken-1 text-white">
                                            <tr>
                                                <th class="text-center" width="5%">No</th>
                                                <th><?= $this->lang->line("Kode Pelanggan"); ?></th>
                                                <th><?= $this->lang->line("Nama Pelanggan"); ?></th>
                                                <th><?= $this->lang->line("Alamat Pelanggan"); ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($customer->result() as $key) {
                                                $c++; ?>
                                                <tr>
                                                    <td class="text-center"><?= $c; ?></td>
                                                    <td><?= $key->i_customer_id; ?></td>
                                                    <td><?= $key->e_customer_name; ?></td>
                                                    <td><?= $key->e_customer_address; ?></td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                        <input type="hidden" id="jml_customer" name="jml_customer" value="<?= $c; ?>">
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
<?php } ?>
<!-- END TABEL CUSTOMER -->

<!-- TABEL AREA -->
<?php $a = 0;
if ($area->num_rows() > 0) { ?>
    <div class="content-body tablearea" <?php if ($data->f_all_area == 't') {
                                            echo "hidden='true'";
                                        } ?>>
        <section id="pagination">
            <div class="row">
                <div class="col-12">
                    <div class="card box-shadow-2">
                        <div class="card-header header-elements-inline">
                            <h4 class="card-title"><i class="feather icon-map-pin"></i> <?= $this->lang->line("Detail"); ?> <?= $this->lang->line("Area"); ?></h4>
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
                                <div class="table-responsive">
                                    <table class="table table-xs table-column table-bordered" id="tablearea">
                                        <thead class="<?= $this->session->e_color; ?> bg-darken-1 text-white">
                                            <tr>
                                                <th class="text-center" width="5%">No</th>
                                                <th><?= $this->lang->line("Kode Area"); ?></th>
                                                <th><?= $this->lang->line("Nama Area Provinsi"); ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($area->result() as $key) {
                                                $a++; ?>
                                                <tr>
                                                    <td class="text-center"><?= $c; ?></td>
                                                    <td><?= $key->i_area_id; ?></td>
                                                    <td><?= $key->e_area_name; ?></td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                        <input type="hidden" id="jml_area" name="jml_area" value="<?= $a; ?>">
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
<?php } ?>
<!-- END TABEL AREA -->