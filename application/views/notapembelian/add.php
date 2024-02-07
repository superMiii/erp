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
                        <li class="breadcrumb-item"><a href="<?= base_url($this->folder . '/index/' . encrypt_url($dfrom) . '/' . encrypt_url($dto) . '/' . encrypt_url($hsup)); ?>"><?= $this->lang->line($this->title); ?></a></li>
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
                            <input type="hidden" id="hsup" value="<?= encrypt_url($hsup); ?>">
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
                                                    <div class="input-group input-group-sm">
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
                                                    <input type="date" class="form-control form-control-sm" min="<?= get_min_date(); ?>" max="<?= date('Y-m-d'); ?>" value="<?= date('Y-m-d'); ?>" data-validation-required-message="<?= $this->lang->line("Required"); ?>" placeholder="" id="d_document" name="d_document" maxlength="500" autocomplete="off" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label><?= $this->lang->line("Nama Pemasok"); ?> :</label>
                                                <div class="controls">
                                                    <select class="form-control" name="i_supplier" id="i_supplier" data-placeholder="<?= $this->lang->line("Pilih") . ' ' . $this->lang->line("Nama Pemasok"); ?>" required data-validation-required-message="<?= $this->lang->line("Required"); ?>">
                                                        <option value=""></option>
                                                    </select>
                                                    <input type="hidden" name="f_supplier_pkp" id="f_supplier_pkp" value="f">
                                                    <input type="hidden" name="n_ppn" id="n_ppn" value="0">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Baris ke 2 -->
                                    <div class="row">
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label><?= $this->lang->line("TOP"); ?> :</label>
                                                <div class="controls">
                                                    <input type="text" readonly class="form-control form-control-sm" value="0" data-validation-required-message="<?= $this->lang->line("Required"); ?>" placeholder="" id="n_supplier_top" name="n_supplier_top" maxlength="500" autocomplete="off" required>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label><?= $this->lang->line("PPN"); ?> :</label>
                                                <div class="controls">
                                                    <input type="text" readonly class="form-control form-control-sm" value="" data-validation-required-message="<?= $this->lang->line("Required"); ?>" placeholder="" id="status_ppn" name="status_ppn" maxlength="500" autocomplete="off" required>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label><?= $this->lang->line("Tgl Jatuh Tempo"); ?> :</label>
                                                <div class="input-group input-group-sm controls">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text">
                                                            <span class="fa fa-calendar-o"></span>
                                                        </span>
                                                    </div>
                                                    <input type="date" class="form-control form-control-sm date" value="" data-validation-required-message="<?= $this->lang->line("Required"); ?>" placeholder="" id="d_jatuh_tempo" name="d_jatuh_tempo" maxlength="500" autocomplete="off" required>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label><?= $this->lang->line("Nota Pemasok"); ?> :</label>
                                                <div class="controls">
                                                    <input type="text" class="form-control form-control-sm" value="" data-validation-required-message="<?= $this->lang->line("Required"); ?>" placeholder="" id="i_nota_supplier" name="i_nota_supplier" maxlength="500" autocomplete="off" required>
                                                </div>
                                            </div>
                                        </div>


                                    </div>
                                    <!-- Bari ke 3 -->
                                    <div class="row">
                                        <!-- <div class="col-md-3">
                                            <div class="form-group">
                                                <label><?= $this->lang->line("Jumlah"); ?> :</label>
                                                <div class="controls">
                                                    <input type="text" readonly class="form-control form-control-sm formatrupiah" onkeyup="formatrupiahkeyup(this);" maxlength="12" name="v_jumlah" placeholder="<?= $this->lang->line("Jumlah"); ?>">
                                                </div>
                                            </div>
                                        </div> -->

                                        <div class="col-md-12">
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
                            <h4 class="card-title"><i class="fa fa-cart-arrow-down"></i> <?= $this->lang->line("Detail"); ?> <?= $this->lang->line("Nota"); ?></h4>
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
                                                    <th class="text-center" width="5%" valign="center">No</th>
                                                    <th class="text-center" width="20%" valign="center"><?= $this->lang->line("No Penerimaan") . ' / ' . $this->lang->line("Nomor PO"); ?></th>
                                                    <th class="text-center" width="20%" valign="center"><?= $this->lang->line("Tgl Penerimaan"); ?></th>
                                                    <th class="text-center" width="20%" valign="center"><?= $this->lang->line("Jumlah"); ?></th>
                                                    <th class="text-center" width="3%"><i class="fa fa-plus-circle fa-lg" title="<?= $this->lang->line('Tambah'); ?>" id="addrow"></i></th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                            <tfoot>
                                                <tr hidden>
                                                    <th colspan="3" class="text-right"><?= $this->lang->line("Sub Total"); ?> Rp. </th>
                                                    <th><input type="text" class="form-control form-control-sm text-right" id="tfoot_subtotal" name="tfoot_subtotal" value="0" readonly></th>
                                                    <th colspan="1"></th>
                                                </tr>

                                                <tr hidden>
                                                    <th colspan="3" class="text-right"><?= $this->lang->line("Diskon"); ?></th>
                                                    <th><input type="text" autocomplete="off" class="formatrupiah form-control form-control-sm text-right" value="0" onkeyup="formatrupiahkeyup(this);change_ndisc();hetang();" onkeydown="formatrupiahkeydown(this);change_ndisc();hetang();" onblur="if(this.value==''){this.value='0';hetang();}" onfocus="if(this.value=='0'){this.value='';}" id="tfoot_n_diskon" name="tfoot_n_diskon"></th>
                                                    <th colspan="1"></th>
                                                </tr>

                                                <tr hidden>
                                                    <th colspan="3" class="text-right"><?= $this->lang->line("Diskon Rp"); ?>. </th>
                                                    <th><input type="text" autocomplete="off" class="formatrupiah form-control form-control-sm text-right" value="0" onkeyup="formatrupiahkeyup(this);change_vdisc();hetang();" onkeydown="formatrupiahkeydown(this);change_vdisc();hetang();" onblur="if(this.value==''){this.value='0';hetang();}" onfocus="if(this.value=='0'){this.value='';}" id="tfoot_v_diskon" name="tfoot_v_diskon"></th>
                                                    <th colspan="2"></th>
                                                </tr>

                                                <!-- <tr>
                                                    <th colspan="3" class="text-right"><?= $this->lang->line("DPP"); ?> Rp. </th>
                                                    <th><input type="text" class="form-control form-control-sm text-right" id="tfoot_v_dpp" name="tfoot_v_dpp" readonly></th>
                                                    <th colspan="2"></th>
                                                </tr>

                                                <tr>
                                                    <th colspan="3" class="text-right"><?= $this->lang->line("PPN"); ?> (<?= $this->session->n_ppn; ?>%) Rp.</th>
                                                    <th><input type="text" class="form-control form-control-sm text-right" id="tfoot_v_ppn" name="tfoot_v_ppn" readonly></th>
                                                    <th colspan="2"></th>
                                                </tr> -->

                                                <tr>
                                                    <th colspan="3" class="text-right"><?= $this->lang->line("Nilai Bersih"); ?> Rp. </th>
                                                    <th><input type="text" class="form-control form-control-sm text-right font-weight-bold" id="tfoot_total" name="tfoot_total" readonly></th>
                                                    <th colspan="2"></th>
                                                </tr>
                                            </tfoot>
                                            <input type="hidden" id="jml" name="jml" value="0">
                                        </table>
                                    </div>
                                </div>
                                <div class="form-actions">
                                    <button type="submit" id="submit" class="btn btn-success round btn-min-width mr-1"><i class="icon-paper-plane mr-1"></i><?= $this->lang->line("Simpan"); ?></button>
                                    <!-- <a href="<?= base_url($this->folder); ?>" class="btn btn-danger btn-min-width"><i class="icon-action-undo mr-1"></i><?= $this->lang->line("Kembali"); ?></a> -->
                                    <a href="<?= base_url($this->folder . '/index/' . encrypt_url($dfrom) . '/' . encrypt_url($dto) . '/' . encrypt_url($hsup)); ?>" class="btn btn-secondary round btn-min-width mr-1"><i class="icon-action-undo mr-1"></i><?= $this->lang->line("Kembali"); ?></a>
                                </div>
                            </div>
                        </div>
                    </div>
                <div class="card box-shadow-0 border-primary">
                        <div class="card-header card-head-inverse <?= $this->session->e_color; ?> bg-darken-1 text-white">
                            <h4 class="card-title"><i class="fa fa-shopping-basket"></i> <?= $this->lang->line("Rincian Pembelian"); ?> </h4>
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
                            <div class="card-body card-dashboard">
                                <div class="form-body">
                                    <div class="table-responsive">
                                        <table class="table table-xs table-column table-bordered" id="tabledetail2">
                                            <thead class="<?= $this->session->e_color; ?> bg-darken-1 text-white">
                                                <tr>
                                                    <th class="text-center" width="3%" valign="center">No</th>
                                                    <th class="text-center" width="17%" valign="center"><?= $this->lang->line("No Penerimaan"); ?></th>
                                                    <th class="text-center" width="27%" valign="center"><?= $this->lang->line("Nama Barang"); ?></th>
                                                    <th class="text-center" width="19%" valign="center"><?= $this->lang->line("Harga"); ?></th>
                                                    <th class="text-center" width="9%" valign="center"><?= $this->lang->line("Qty"); ?></th>
                                                    <th class="text-center" width="6%" valign="center"><?= $this->lang->line("Disk"); ?></th>
                                                    <th class="text-center" width="19%" valign="center"><?= $this->lang->line("Total"); ?></th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                            <tfoot>
                                                
                                            </tfoot>
                                            <input type="hidden" id="jml2" name="jml2" value="0">
                                        </table>
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
</form>