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
                        <li class="breadcrumb-item"><a href="#" onclick="return false;"><?= $this->lang->line('Keuangan'); ?></a></li>
                        <!-- <li class="breadcrumb-item"><a href="<?= base_url($this->folder); ?>"><?= $this->lang->line($this->title); ?></a></li> -->
                        <li class="breadcrumb-item"><a href="<?= base_url($this->folder . '/index/' . encrypt_url($dfrom) . '/' . encrypt_url($dto) . '/' . encrypt_url($htype) . '/' . encrypt_url($harea) . '/' . encrypt_url($hcoa)); ?>"><?= $this->lang->line($this->title); ?></a></li>
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
                    <div class="card box-shadow-0 border-primary">
                        <div class="card-header card-head-inverse <?= $this->session->e_color; ?> bg-darken-1 text-white">
                            <h4 class="card-title"><i class="icon-plus"></i> <?= $this->lang->line('Tambah'); ?> <?= $this->lang->line($this->title); ?></h4>
                            <input type="hidden" id="path" value="<?= $this->folder; ?>">
                            <input type="hidden" id="d_from" value="<?= encrypt_url($dfrom); ?>">
                            <input type="hidden" id="d_to" value="<?= encrypt_url($dto); ?>">
                            <input type="hidden" id="htype" value="<?= encrypt_url($htype); ?>">
                            <input type="hidden" id="harea" value="<?= encrypt_url($harea); ?>">
                            <input type="hidden" id="hcoa" value="<?= encrypt_url($hcoa); ?>">
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
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label><?= $this->lang->line("Nomor Dokumen"); ?> :</label>
                                                <fieldset>
                                                    <div class="input-group input-group-sm controls">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">
                                                                <span class="fa fa-hashtag"></span>
                                                            </div>
                                                        </div>
                                                        <input type="text" readonly name="i_document" id="i_document" placeholder="<?= $this->lang->line("Nomor Dokumen"); ?>" class="form-control form-control-sm text-uppercase" data-validation-required-message="<?= $this->lang->line("Required"); ?>" maxlength="500" autocomplete="off" required aria-label="Text input with checkbox">
                                                    </div>
                                                    <span class="notekode" id="ada" hidden="true">* <?= $this->lang->line("Sudah Ada"); ?></span>
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
                                                    <input type="date" class="form-control form-control-sm date" min="<?= get_min_date2(); ?>" max="<?= date('Y-m-d'); ?>" value="<?= date('Y-m-d'); ?>" data-validation-required-message="<?= $this->lang->line("Required"); ?>" placeholder="" id="d_document" name="d_document" maxlength="500" autocomplete="off" required>
                                                </div>
                                            </div>
                                            <input type="hidden" class="form-control form-control-sm date" id="tgl" name="tgl">
                                            <input type="hidden" id="kodd" name="kodd">
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label><?= $this->lang->line("Nama Area Provinsi"); ?> :</label>
                                                <div class="controls">
                                                    <select class="form-control" id="i_area" required data-placeholder="<?= $this->lang->line("Pilih") . ' ' . $this->lang->line("Area"); ?>" data-validation-required-message="<?= $this->lang->line("Required"); ?>" name="i_area">
                                                        <option value=""></option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Baris ke 2 -->
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label><?= $this->lang->line("Tipe Voucher"); ?> :</label>
                                                <div class="controls">
                                                    <select class="form-control" name="i_rv_type" id="i_rv_type" data-placeholder="<?= $this->lang->line("Pilih") . ' ' . $this->lang->line("Tipe Voucher"); ?>" required data-validation-required-message="<?= $this->lang->line("Required"); ?>">
                                                        <option value=""></option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label><?= $this->lang->line("Perkiraan"); ?> :</label>
                                                <div class="controls">
                                                    <select class="form-control" name="i_coa" id="i_coa" data-placeholder="<?= $this->lang->line("Pilih") . ' ' . $this->lang->line("Perkiraan"); ?>" required data-validation-required-message="<?= $this->lang->line("Required"); ?>">
                                                        <option value=""></option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label><?= $this->lang->line("Keterangan"); ?> :</label>
                                                <textarea class="form-control text-capitalize" placeholder="<?= $this->lang->line('Keterangan'); ?>" name="e_remark"></textarea>
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
                    <div class="card box-shadow-0 border-primary">
                        <div class="card-header card-head-inverse <?= $this->session->e_color; ?> bg-darken-1 text-white">
                            <h4 class="card-title"><i class="fa fa-money fa-lg"></i> <?= $this->lang->line("Detail"); ?> <?= $this->lang->line("Terima Voucher"); ?></h4>
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
                                            <thead class="<?= $this->session->e_color; ?> bg-darken-1 text-white">
                                                <tr>
                                                    <th class="text-center" width="3%" valign="center">No</th>
                                                    <th class="text-center" width="17%" valign="center"><?= $this->lang->line("Perkiraan"); ?></th>
                                                    <th class="text-center" width="10%" valign="center"><?= $this->lang->line("Tgl Bukti"); ?></th>
                                                    <th class="text-center clear" width="10%" valign="center"><?= $this->lang->line("TF/GR/TN"); ?></th>
                                                    <th class="text-center clear" width="10%" valign="center"><?= $this->lang->line("Referensi"); ?></th>
                                                    <th class="text-center" width="20%" valign="center"><?= $this->lang->line("Keterangan"); ?></th>
                                                    <th class="text-center" width="27%" valign="center"><?= $this->lang->line("Jumlah"); ?></th>
                                                    <th class="text-center" width="3%"><i class="fa fa-plus-circle fa-lg" title="<?= $this->lang->line('Tambah'); ?>" id="addrow"></i></th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                            <tfoot>
                                                <tr>
                                                    <th class="text-right colay" colspan="6"><?= $this->lang->line('Sub Total'); ?></th>
                                                    <th><input type="text" class="form-control form-control-sm text-right" name="v_rv" id="v_rv" value="0" readonly></th>
                                                </tr>
                                                <tr>
                                                    <th class="text-right colay" colspan="6"><?= $this->lang->line('Saldo'); ?></th>
                                                    <th><input type="text" class="form-control form-control-sm text-right" name="v_saldo" id="v_saldo" value="0" readonly></th>
                                                </tr>
                                                <tr>
                                                    <th class="text-right colay" colspan="6"><?= $this->lang->line('Sisa Saldo'); ?></th>
                                                    <th><input type="text" class="form-control form-control-sm text-right" name="v_sisa_saldo" id="v_sisa_saldo" value="0" readonly></th>
                                                </tr>
                                            </tfoot>
                                            <input type="hidden" id="jml" name="jml" value="0">
                                            <input type="hidden" id="tex" name="tex" value="">
                                            <input type="hidden" id="wajib" name="wajib" value="">
                                        </table>
                                    </div>
                                </div>
                                <div class="form-actions">
                                    <button type="submit" id="submit" class="btn btn-success round btn-min-width mr-1"><i class="icon-paper-plane mr-1"></i><?= $this->lang->line("Simpan"); ?></button>
                                    <!-- <a href="<?= base_url($this->folder); ?>" class="btn btn-danger btn-min-width"><i class="icon-action-undo mr-1"></i><?= $this->lang->line("Kembali"); ?></a> -->
                                    <a href="<?= base_url($this->folder . '/index/' . encrypt_url($dfrom) . '/' . encrypt_url($dto) . '/' . encrypt_url($htype) . '/' . encrypt_url($harea) . '/' . encrypt_url($hcoa)); ?>" class="btn btn-secondary round btn-min-width mr-1"><i class="icon-action-undo mr-1"></i><?= $this->lang->line("Kembali"); ?></a>
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