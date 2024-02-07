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
                        <li class="breadcrumb-item"><a href="#" onclick="return false;"><?= $this->lang->line('Stok'); ?></a></li>
                        <!-- <li class="breadcrumb-item"><a href="<?= base_url($this->folder); ?>"><?= $this->lang->line($this->title); ?></a></li> -->
                        <li class="breadcrumb-item"><a href="<?= base_url($this->folder . '/index/' . encrypt_url($dfrom) . '/' . encrypt_url($dto)); ?>"><?= $this->lang->line($this->title); ?></a></li>
                        <li class="breadcrumb-item active"><?= $this->lang->line('Ubah'); ?> <?= $this->lang->line($this->title); ?></li>
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
                            <h4 class="card-title"><i class="icon-pencil"></i> <?= $this->lang->line('Ubah'); ?> <?= $this->lang->line($this->title); ?></h4>
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
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Nomor Dokumen"); ?> :</label>
                                            <fieldset>
                                                <div class="input-group input-group-sm">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">
                                                            <span class="fa fa-hashtag"></span>
                                                        </div>
                                                    </div>
                                                    <input type="hidden" name="id" id="id" class="form-control" value="<?= $data->i_adjustment; ?>">
                                                    <input type="hidden" name="i_document_old" id="i_document_old" class="form-control" value="<?= $data->i_adjustment_id; ?>">
                                                    <input type="text" name="i_document" id="i_document" value="<?= $data->i_adjustment_id; ?>" readonly placeholder="ADJ-<?= date('ym'); ?>-000001" class="form-control form-control-sm text-uppercase" data-validation-required-message="<?= $this->lang->line("Required"); ?>" maxlength="500" autocomplete="off" required aria-label="Text input with checkbox">
                                                </div>
                                            </fieldset>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Tanggal Dokumen"); ?> :</label>
                                            <div class="input-group input-group-sm">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">
                                                        <span class="fa fa-calendar-o"></span>
                                                    </span>
                                                </div>
                                                <input type="date" class="form-control form-control-sm" readonly min="<?= date('Y-m-01'); ?>" max=<?= date('Y-m-d'); ?> value="<?= $data->d_adjustment; ?>" data-validation-required-message="<?= $this->lang->line("Required"); ?>" placeholder="" id="d_document" name="d_document" maxlength="500" autocomplete="off" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Lokasi Gudang"); ?> :</label>
                                            <div class="controls">
                                                <select class="form-control" id="i_store_loc" name="i_store_loc" data-placeholder="<?= $this->lang->line("Pilih"); ?> <?= $this->lang->line("Lokasi Gudang"); ?>" required data-validation-required-message="<?= $this->lang->line("Required"); ?>">
                                                    <option value="<?= $data->i_store_loc; ?>"><?= $data->i_store_loc_id . ' - ' . $data->e_store_name . ' - ' . $data->e_store_loc_name; ?></option>
                                                </select>
                                                <input type="hidden" name="i_store" id="i_store" value="<?= $data->i_store; ?>">
                                                <input type="hidden" name="i_area" id="i_area" value="<?= $data->i_area; ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Nomor Stokopname"); ?> :</label>
                                            <div class="controls">
                                                <select class="form-control form-control-sm" id="i_stockopname" name="i_stockopname" data-placeholder="<?= $this->lang->line("Pilih"); ?> <?= $this->lang->line("Stokopname"); ?>" required data-validation-required-message="<?= $this->lang->line("Required"); ?>">
                                                    <option value="<?= $data->i_stockopname; ?>"><?= $data->i_stockopname_id . ' - ' . $data->d_stockopname; ?></option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Keterangan"); ?> :</label>
                                            <div class="controls">
                                                <textarea type="text" required name="e_remark0" data-validation-required-message="<?= $this->lang->line("Required"); ?>" class="form-control" required placeholder="<?= $this->lang->line("Keterangan"); ?>"><?= $data->e_remark; ?></textarea>
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
                                                    <th width="15%"><?= $this->lang->line("Kode Barang"); ?></th>
                                                    <th width="10%"><?= $this->lang->line("Nilai"); ?></th>
                                                    <th width="30%"><?= $this->lang->line("Nama Barang"); ?></th>
                                                    <th width="10%"><?= $this->lang->line("Motif"); ?></th>
                                                    <th width="10%"><?= $this->lang->line("Jumlah"); ?></th>
                                                    <th><?= $this->lang->line("Keterangan"); ?></th>
                                                    <th class="text-center" width="5%"><i class="fa fa-plus-circle fa-lg" title="<?= $this->lang->line('Tambah'); ?>" id="addrow"></i></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $i = 0;
                                                if ($detail->num_rows() > 0) {
                                                    foreach ($detail->result() as $key) {
                                                        $i++; ?>
                                                        <tr>
                                                            <td class="text-center">
                                                                <spanx id="snum<?= $i; ?>"><?= $i; ?></spanx>
                                                            </td>
                                                            <td>
                                                                <select data-nourut="<?= $i; ?>" required class="form-control select2-size-sm" name="i_product[]" id="i_product<?= $i; ?>">
                                                                    <option value="<?= $key->i_product; ?>"><?= $key->i_product_id; ?></option>
                                                                </select>
                                                            </td>
                                                            <td><input type="text" id="e_product_gradename<?= $i; ?>" value="<?= $key->e_product_gradename; ?>" readonly class="form-control form-control-sm"></td>
                                                            <td><input type="text" id="e_product_name<?= $i; ?>" name="e_product_name[]" value="<?= $key->e_product_name; ?>" readonly class="form-control form-control-sm"></td>
                                                            <td>
                                                                <input type="hidden" id="i_product_motif<?= $i; ?>" name="i_product_motif[]" value="<?= $key->i_product_motif; ?>">
                                                                <input type="hidden" id="i_product_grade<?= $i; ?>" name="i_product_grade[]" value="<?= $key->i_product_grade; ?>">
                                                                <input type="text" id="e_product_motifname<?= $i; ?>" value="<?= $key->e_product_motifname; ?>" readonly class="form-control form-control-sm">
                                                            </td>
                                                            <td><input type="number" value="<?= $key->n_adjustment; ?>" name="n_adjustment[]" class="form-control n_adjustment form-control-sm" onblur="if(this.value=='' ){this.value='0' ;}" onfocus="if(this.value=='0' ){this.value='' ;}"></td>
                                                            <td><input type="text" id="e_remark<?= $i; ?>" name="e_remark[]" value="<?= $key->e_remark; ?>" class="form-control form-control-sm"></td>
                                                            <td class="text-center"><i title="Delete" class="fa fa-minus-circle fa-lg text-danger ibtnDel"></i></td>
                                                        </tr>
                                                <?php }
                                                } ?>
                                            </tbody>
                                            <input type="hidden" id="jml" name="jml" value="<?= $i; ?>">
                                        </table>
                                    </div>
                                </div>
                                <div class="form-actions">
                                    <button type="submit" id="submit" class="btn btn-warning round btn-min-width mr-1"><i class="icon-paper-plane mr-1"></i><?= $this->lang->line("Ubah"); ?></button>
                                    <!-- <a href="<?= base_url($this->folder); ?>" class="btn btn-danger btn-min-width"><i class="icon-action-undo mr-1"></i><?= $this->lang->line("Kembali"); ?></a> -->
                                    <a href="<?= base_url($this->folder . '/index/' . encrypt_url($dfrom) . '/' . encrypt_url($dto)); ?>" class="btn btn-secondary round btn-min-width mr-1"><i class="icon-action-undo mr-1"></i><?= $this->lang->line("Kembali"); ?></a>
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