<style>
    .table.table-xs th,
    .table td,
    .table.table-xs td {
        padding: 0.4rem 0.4rem;
    }
</style>
<form class="form-validation" novalidate>
    <div class="content-header row">
        <div class="content-header-left col-md-12 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#" onclick="return false;"><?= $this->lang->line('Gudang'); ?></a></li>
                        <li class="breadcrumb-item"><a href="<?= base_url($this->folder . '/index/' . encrypt_url($dfrom) . '/' . encrypt_url($dto) . '/' . encrypt_url($harea)); ?>"><?= $this->lang->line($this->title); ?></a></li>
                        <li class="breadcrumb-item active"><?= $this->lang->line('Tambah'); ?> <?= $this->lang->line($this->title); ?></li>
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
                            <h4 class="card-title"><i class="icon-plus"></i> <?= $this->lang->line('Tambah'); ?> <?= $this->lang->line($this->title); ?></h4>
                            <input type="hidden" id="path" value="<?= $this->folder; ?>">
                            <input type="hidden" id="d_from" value="<?= encrypt_url($dfrom); ?>">
                            <input type="hidden" id="d_to" value="<?= encrypt_url($dto); ?>">
                            <input type="hidden" id="harea" value="<?= encrypt_url($harea); ?>">
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
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Nomor Dokumen"); ?> :</label>
                                            <fieldset>
                                                <div class="input-group controls">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">
                                                            <span class="fa fa-hashtag"></span>
                                                        </div>
                                                    </div>
                                                    <input type="text" name="i_document" id="i_document" readonly placeholder="SR-<?= date('ym'); ?>-000001" class="form-control text-uppercase" data-validation-required-message="<?= $this->lang->line("Required"); ?>" maxlength="500" autocomplete="off" required aria-label="Text input with checkbox">
                                                </div>
                                            </fieldset>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Tanggal Dokumen"); ?> :</label>
                                            <div class="input-group controls">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">
                                                        <span class="fa fa-calendar-o"></span>
                                                    </span>
                                                </div>
                                                <input type="date" <?= konci(); ?> min="<?= get_min_date(); ?>" max="<?= date('Y-m-d'); ?>" class="form-control" value="<?= tx_tgl(); ?>" data-validation-required-message="<?= $this->lang->line("Required"); ?>" placeholder="" id="d_document" name="d_document" maxlength="500" autocomplete="off" required>
                                                <!-- <input type="date" <?= konci(); ?> min="<?= get_min_date(); ?>" max="<?= date('Y-m-d'); ?>" class="form-control" value="2023-07-31" data-validation-required-message="<?= $this->lang->line("Required"); ?>" placeholder="" id="d_document" name="d_document" maxlength="500" autocomplete="off" required> -->
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Area"); ?> :</label>
                                            <div class="controls">
                                                <select class="form-control" id="i_area" name="i_area" data-placeholder="<?= $this->lang->line("Pilih"); ?> <?= $this->lang->line("Area"); ?>" required data-validation-required-message="<?= $this->lang->line("Required"); ?>">
                                                    <option value=""></option>
                                                </select>
                                                <!-- <input type="hidden" name="i_store" id="i_store">
                                                <input type="hidden" name="i_area" id="i_area"> -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Keterangan :"); ?> :</label>
                                            <div class="controls">
                                                <input type="text" name="e_remark" id="e_remark" class="form-control" placeholder="<?= $this->lang->line("Keterangan :"); ?>" >
                                                <!-- <textarea type="text" name="e_remark" class="form-control" placeholder="<?= $this->lang->line("Keterangan"); ?>"></textarea> -->
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
                        <div class="card-header header-elements-inline <?= $this->session->e_color; ?> bg-darken-1 text-white">
                            <h4 class="card-title"><i class="feather icon-grid"></i> <?= $this->lang->line("Detail"); ?> <?= $this->lang->line("Barang"); ?></h4>
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
                                        <table class="table table-xs table-column table-bordered" id="tablecover">
                                            <thead class="<?= $this->session->e_color; ?> bg-darken-1 text-white">
                                                <tr>
                                                    <th class="text-center" width="5%">No</th>
                                                    <th width="50%"><?= $this->lang->line("Kode Nama Barang"); ?></th>
                                                    <th width="10%"><?= $this->lang->line("Grade Barang"); ?></th>
                                                    <th width="10%"><?= $this->lang->line("Jml Pesan"); ?></th>
                                                    <th width="10%"><?= $this->lang->line("Jml Acc"); ?></th>
                                                    <th width="20%"><?= $this->lang->line("Keterangan"); ?></th>
                                                    <th class="text-center" width="5%"><i class="fa fa-plus-circle fa-lg" title="<?= $this->lang->line('Tambah'); ?>" id="addrow"></i></th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                            <input type="hidden" id="jml" name="jml" value="0">
                                            <tfoot>
                                                <tr>
                                                    <td colspan="3" class="text-right"> <b>Total Rupiah</b></td>
                                                    <td colspan="3" class="text-right">
                                                        <input type="text" class="form-control form-control-sm text-right" id="v_total" name="v_total" readonly>
                                                        <b><i>( <span id="terbilangnya"> rupiah </span> )</i></b>
                                                    </td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                                <div class="form-actions">
                                    <button type="submit" id="submit" class="btn btn-success round btn-min-width mr-1"><i class="icon-paper-plane mr-1"></i><?= $this->lang->line("Simpan"); ?></button>
                                    <a href="<?= base_url($this->folder . '/index/' . encrypt_url($dfrom) . '/' . encrypt_url($dto) . '/' . encrypt_url($harea)); ?>" class="btn btn-secondary round btn-min-width mr-1"><i class="icon-action-undo mr-1"></i><?= $this->lang->line("Kembali"); ?></a>
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