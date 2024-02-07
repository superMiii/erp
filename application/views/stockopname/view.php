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
                        <li class="breadcrumb-item"><a href="<?= base_url($this->folder . '/index/' . encrypt_url($dfrom) . '/' . encrypt_url($dto)); ?>"><?= $this->lang->line($this->title); ?></a></li>
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
                    <div class="card box-shadow-0 border-primary">
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
                                                    <input type="hidden" name="id" id="id" class="form-control" value="<?= $data->i_stockopname; ?>">
                                                    <input type="hidden" name="i_document_old" id="i_document_old" class="form-control" value="<?= $data->i_stockopname_id; ?>">
                                                    <input type="text" name="i_document" id="i_document" value="<?= $data->i_stockopname_id; ?>" readonly placeholder="SO-<?= date('ym'); ?>-000001" class="form-control text-uppercase" data-validation-required-message="<?= $this->lang->line("Required"); ?>" maxlength="500" autocomplete="off" required aria-label="Text input with checkbox">
                                                </div>
                                            </fieldset>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Nama Area Provinsi"); ?> :</label>
                                            <div class="controls">
                                                <input type="text" name="i_area" id="i_area" value="<?= $data->i_area_id . ' - ' . $data->e_area_name; ?>" readonly class="form-control" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Tanggal Dokumen"); ?> :</label>
                                            <div class="input-group controls">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">
                                                        <span class="fa fa-calendar-o"></span>
                                                    </span>
                                                </div>
                                                <input type="date" readonly class="form-control" min="<?= date('Y-m-d', strtotime("-30 day", strtotime(date('Y-m-d')))); ?>" max="<?= date('Y-m-d'); ?>" value="<?= $data->d_stockopname; ?>" data-validation-required-message="<?= $this->lang->line("Required"); ?>" placeholder="" id="d_document" name="d_document" maxlength="500" autocomplete="off" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Lokasi Gudang"); ?> :</label>
                                            <div class="controls">
                                                <input type="text" name="i_store_loc" id="i_store_loc" value="<?= $data->e_store_name . ' - ' . $data->e_store_loc_name; ?>" readonly class="form-control" required>
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
                                                    <th class="text-center" width="4%">No</th>
                                                    <th width="9%"><?= $this->lang->line("Kode Barang"); ?></th>
                                                    <th width="42%"><?= $this->lang->line("Nama Barang"); ?></th>
                                                    <th class="text-center" width="10%"><?= $this->lang->line("Nilai"); ?></th>
                                                    <th class="text-center" width="10%"><?= $this->lang->line("Motif"); ?></th>
                                                    <th class="text-center" width="14%"><?= $this->lang->line("Status"); ?></th>
                                                    <th class="text-center" width="11%"><?= $this->lang->line("Jumlah"); ?></th>
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
                                                            <input type="hidden" value="<?= $key->i_product; ?>" name="i_product[]" id="i_product<?= $i; ?>" readonly class="form-control form-control-sm">
                                                            <td><input type="text" value="<?= $key->i_product_id; ?>" readonly name="i_product_id[]" id="i_product_id<?= $i; ?>" class="form-control form-control-sm"></td>
                                                            <td><input type="text" value="<?= $key->e_product_name; ?>" readonly name="e_product_name[]" id="e_product_name<?= $i; ?>" class="form-control form-control-sm"></td>
                                                            <td>
                                                                <input type="hidden" value="<?= $key->i_product_grade; ?>" name="i_product_grade[]" id="i_product_grade<?= $i; ?>" readonly class="form-control form-control-sm">
                                                                <input type="text" value="<?= $key->e_product_gradename; ?>" id="e_product_gradename<?= $i; ?>" readonly class="form-control form-control-sm text-center">
                                                            </td>
                                                            <td>
                                                                <input type="hidden" value="<?= $key->i_product_motif; ?>" name="i_product_motif[]" id="i_product_motif<?= $i; ?>" readonly class="form-control form-control-sm">
                                                                <input type="text" value="<?= $key->e_product_motifname; ?>" id="e_product_motifname<?= $i; ?>" readonly class="form-control form-control-sm text-center">
                                                            </td>
                                                            <td><input type="text" value="<?= $key->akt; ?>" readonly name="akt[]" id="akt<?= $i; ?>" class="form-control form-control-sm text-center"></td>
                                                            <td><input type="number" value="<?= $key->n_stockopname; ?>" name="n_stockopname[]" readonly class="form-control form-control-sm " onblur="if(this.value=='' ){this.value='0' ;}" onfocus="if(this.value=='0' ){this.value='' ;}"></td>
                                                        </tr>
                                                <?php }
                                                } ?>
                                            </tbody>
                                            <input type="hidden" id="jml" name="jml" value="<?= $i; ?>">
                                        </table>
                                    </div>
                                </div>
                                <div class="form-actions">
                                    <!-- <button type="submit" id="submit" class="btn btn-primary btn-min-width"><i class="icon-paper-plane mr-1"></i><?= $this->lang->line("Ubah"); ?></button> -->
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