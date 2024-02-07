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
                    <div class="card box-shadow-2">
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
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Nomor Dokumen"); ?> :</label>
                                            <fieldset>
                                                <div class="input-group input-group-sm control">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">
                                                            <span class="fa fa-hashtag"></span>
                                                        </div>
                                                    </div>
                                                    <input type="hidden" name="id" id="id" class="form-control" value="<?= $data->i_convertion; ?>">
                                                    <input type="hidden" name="i_document_old" id="i_document_old" class="form-control" value="<?= $data->i_convertion_id; ?>">
                                                    <input type="text" name="i_document" id="i_document" value="<?= $data->i_convertion_id; ?>" readonly placeholder="BBK-<?= date('ym'); ?>-000001" class="form-control form-control-sm text-uppercase" data-validation-required-message="<?= $this->lang->line("Required"); ?>" maxlength="500" autocomplete="off" required aria-label="Text input with checkbox">
                                                </div>
                                            </fieldset>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Tanggal Dokumen"); ?> :</label>
                                            <div class="input-group input-group-sm controls">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">
                                                        <span class="fa fa-calendar-o"></span>
                                                    </span>
                                                </div>
                                                <input type="date" <?= koncix(); ?> class="form-control form-control-sm date" min="<?= date('Y-m-01'); ?>" max="<?= date('Y-m-d'); ?>" value="<?= $data->d_convertion; ?>" data-validation-required-message="<?= $this->lang->line("Required"); ?>" placeholder="" id="d_document" name="d_document" maxlength="500" autocomplete="off" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <!-- <div class="col-md-6">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Barang"); ?> :</label>
                                            <div class="controls">
                                                <select class="form-control" id="i_product_asal" name="i_product_asal" data-placeholder="<?= $this->lang->line("Pilih"); ?> <?= $this->lang->line("Barang"); ?>" required data-validation-required-message="<?= $this->lang->line("Required"); ?>">
                                                    <option value="<?= $data->i_product; ?>"><?= $data->i_product_id . ' - ' . $data->e_product_name; ?></option>
                                                </select>
                                                </div>
                                        </div>
                                    </div> -->
                                    <div class="col-sm-5">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Barang"); ?> :</label>
                                            <div class="controls">
                                                <input type="hidden" readonly name="i_product_asal" id="i_product_asal" value="<?= $data->i_product; ?>">
                                                <input type="text" readonly id="qntl" class="form-control form-control-sm" placeholder="<?= $this->lang->line("Barang"); ?>" value="<?= $data->i_product_id . ' - ' . $data->e_product_name; ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Motif"); ?> :</label>
                                            <div class="controls">
                                                <input type="hidden" readonly name="i_product_motif_asal" id="i_product_motif_asal" value="<?= $data->i_product_motif; ?>">
                                                <input type="text" readonly id="e_product_motif" class="form-control form-control-sm" placeholder="<?= $this->lang->line("Motif"); ?>" value="<?= $data->e_product_motifname; ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Grade"); ?> :</label>
                                            <div class="controls">
                                                <input type="hidden" readonly name="e_product_name_asal" id="e_product_name_asal" value="<?= $data->e_product_name; ?>">
                                                <input type="hidden" readonly name="i_product_grade_asal" id="i_product_grade_asal" value="<?= $data->i_product_grade; ?>">
                                                <input type="text" readonly id="e_product_grade" class="form-control form-control-sm" placeholder="<?= $this->lang->line("Motif"); ?>" value="<?= $data->e_product_gradename; ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Stok"); ?> :</label>
                                            <div class="controls">
                                                <input type="text" readonly id="n_stk" class="form-control form-control-sm" placeholder="<?= $this->lang->line("Stok"); ?> " value=" <?= $data->n_stk; ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Jumlah Asal"); ?> :</label>
                                            <div class="controls">
                                                <input type="hidden" readonly name="kon" id="kon" value="<?= $data->n_convertion; ?>">
                                                <input type="number" name="n_convertion" id="n_convertion" required class="form-control n_convertion form-control-sm" value="<?= $data->n_convertion; ?>" placeholder="0" onkeyup="raya();" onblur="if(this.value==''){this.value='0';}" onfocus="if(this.value=='0'){this.value='';}">
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
    </div>
    <div class="content-body">
        <section id="pagination">
            <div class="row">
                <div class="col-12">
                    <div class="card box-shadow-2">
                        <div class="card-header card-head-inverse <?= $this->session->e_color; ?> bg-darken-1 text-white">
                            <h4 class="card-title"><i class="feather icon-grid"></i> <?= $this->lang->line("Detail"); ?> <?= $this->lang->line("Departemen"); ?> & <?= $this->lang->line("Level"); ?></h4>
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
                                        <table class="table table-sm table-column table-bordered" id="tablecover">
                                            <thead class="<?= $this->session->e_color; ?> bg-darken-1 text-white">
                                                <tr>
                                                    <th class="text-center" width="5%">No</th>
                                                    <th width="50%"><?= $this->lang->line("Kode Nama Barang"); ?></th>
                                                    <th width="10%"><?= $this->lang->line("Motif"); ?></th>
                                                    <th width="10%"><?= $this->lang->line("Grade"); ?></th>
                                                    <th class="text-right" width="10%"><?= $this->lang->line("Jml Konversi"); ?></th>
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
                                                                    <option value="<?= $key->i_product; ?>"><?= $key->i_product_id . ' - ' . $key->e_product_name; ?></option>
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <input type="hidden" value="<?= $key->i_product_motif; ?>" name="i_product_motif[]" id="i_product_motif<?= $i; ?>" readonly class="form-control form-control-sm">
                                                                <input type="hidden" value="<?= $key->i_product_grade; ?>" name="i_product_grade[]" id="i_product_grade<?= $i; ?>" readonly class="form-control form-control-sm">
                                                                <input type="hidden" value="<?= $key->e_product_name; ?>" name="e_product_name[]" id="e_product_name<?= $i; ?>" readonly class="form-control form-control-sm">
                                                                <input type="text" value="<?= $key->e_product_motifname; ?>" id="e_product_motifname<?= $i; ?>" readonly class="form-control form-control-sm">
                                                            </td>
                                                            <td><input type="text" value="<?= $key->e_product_gradename; ?>" readonly name="e_product_gradename[]" id="e_product_gradename<?= $i; ?>" class="form-control form-control-sm"></td>
                                                            <td><input type="number" value="<?= $key->n_convertion; ?>" name="convertion[]" id="convertion<?= $i; ?>" class="form-control convertion form-control-sm" onblur="if(this.value=='' ){this.value='0' ;}" onfocus="if(this.value=='0' ){this.value='' ;}"></td>
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
                                    <!-- <a href="<?= base_url($this->folder); ?>" class="btn bg-danger bg-darken-1 text-white btn-min-width"><i class="icon-action-undo mr-1"></i><?= $this->lang->line("Kembali"); ?></a> -->
                                    <a href="<?= base_url($this->folder . '/index/' . encrypt_url($dfrom) . '/' . encrypt_url($dto)); ?>" class="btn btn-secondary round btn-min-width mr-1"><i class="icon-action-undo mr-1"></i><?= $this->lang->line("Kembali"); ?></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</form>