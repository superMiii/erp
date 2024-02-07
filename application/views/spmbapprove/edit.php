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
                        <li class="breadcrumb-item active"><?= $this->lang->line($this->title); ?></li>
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
                            <h4 class="card-title"><i class="icon-pencil"></i> <?= $this->lang->line($this->title); ?></h4>
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
                                                <div class="input-group control">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">
                                                            <span class="fa fa-hashtag"></span>
                                                        </div>
                                                    </div>
                                                    <input type="hidden" name="id" id="id" class="form-control" value="<?= $data->i_sr; ?>">
                                                    <input type="hidden" name="i_document_old" id="i_document_old" class="form-control" value="<?= $data->i_sr_id; ?>">
                                                    <input type="text" name="i_document" id="i_document" value="<?= $data->i_sr_id; ?>" readonly placeholder="SR-<?= date('ym'); ?>-000001" class="form-control text-uppercase" data-validation-required-message="<?= $this->lang->line("Required"); ?>" maxlength="500" autocomplete="off" required aria-label="Text input with checkbox">
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
                                                <input type="date" class="form-control date" <?= konci(); ?> value="<?= $data->d_sr; ?>" data-validation-required-message="<?= $this->lang->line("Required"); ?>" placeholder="" id="d_document" name="d_document" maxlength="500" autocomplete="off" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Area"); ?> :</label>
                                            <div class="controls">
                                                <input type="hidden" value="<?= $data->i_area; ?>" name="i_area" class="form-control" readonly />
                                                <select disabled class="form-control" id="i_area" data-placeholder="<?= $this->lang->line("Pilih"); ?> <?= $this->lang->line("Area"); ?>" required data-validation-required-message="<?= $this->lang->line("Required"); ?>">
                                                    <option value="<?= $data->i_area; ?>"><?= $data->i_area_id . ' - ' . $data->e_area_name; ?></option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Keterangan Menyetujui"); ?> :</label>
                                            <div class="controls">
                                                <textarea autofocus data-validation-required-message="<?= $this->lang->line("Required"); ?>" class="form-control" required placeholder="<?= $this->lang->line('Alasan Setuju'); ?>" id="e_note" value=""></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-actions">
                                    <button type="button" onclick="app('<?= $this->folder; ?>','<?= $data->i_sr; ?>');" class="btn btn-info round btn-min-width mr-1"><i class="fa fa-check-circle fa-lg mr-1"></i><?= $this->lang->line("Menyetujui"); ?></button>
                                </div>
                                <!-- <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Keterangan"); ?> :</label>
                                            <div class="controls">
                                                <textarea type="text" name="e_remark" data-validation-required-message="<?= $this->lang->line("Required"); ?>" class="form-control" required placeholder="<?= $this->lang->line("Keterangan"); ?>"><?= $data->e_remark; ?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div> -->
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
                                        <table class="table table-xs table-column table-bordered" id="tablecover">
                                            <thead class="<?= $this->session->e_color; ?> bg-darken-1 text-white">
                                                <tr>
                                                    <th class="text-center" width="5%">No</th>
                                                    <th width="50%"><?= $this->lang->line("Kode Nama Barang"); ?></th>
                                                    <th width="10%"><?= $this->lang->line("Grade Barang"); ?></th>
                                                    <th width="10%"><?= $this->lang->line("Jml Pesan"); ?></th>
                                                    <th width="10%"><?= $this->lang->line("Jml Acc"); ?></th>
                                                    <th width="20%"><?= $this->lang->line("Keterangan"); ?></th>
                                                    <!-- <th class="text-center" width="5%"><i class="fa fa-plus-circle fa-lg" title="<?= $this->lang->line('Tambah'); ?>" id="addrow"></i></th> -->
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $i = 0;
                                                $total_rupiah0 = 0;
                                                $total_rupiah = 0;
                                                if ($detail->num_rows() > 0) {
                                                    foreach ($detail->result() as $key) {
                                                        $i++; ?>
                                                        <tr>
                                                            <td class="text-center">
                                                                <spanx id="snum<?= $i; ?>"><?= $i; ?></spanx>
                                                            </td>
                                                            <td><input type="text" value="<?= $key->i_product_id . ' - ' . $key->e_product_name; ?>" readonly name="i_product[]" id="i_product<?= $i; ?>" class="form-control form-control-sm"></td>
                                                            <td>
                                                                <input type="hidden" value="<?= $key->i_product_motif; ?>" name="i_product_motif[]" id="i_product_motif<?= $i; ?>" readonly class="form-control form-control-sm">
                                                                <input type="hidden" value="<?= $key->i_product_grade; ?>" name="i_product_grade[]" id="i_product_grade<?= $i; ?>" readonly class="form-control form-control-sm">
                                                                <input type="hidden" value="<?= $key->n_stock; ?>" name="n_stock[]" id="n_stock<?= $i; ?>" readonly class="form-control form-control-sm">
                                                                <input type="hidden" value="<?= $key->v_unit_price; ?>" name="v_unit_price[]" id="v_unit_price<?= $i; ?>" readonly class="form-control form-control-sm">
                                                                <input type="hidden" value="<?= $key->n_saldo; ?>" name="n_saldo[]" id="n_saldo<?= $i; ?>" readonly class="form-control form-control-sm">
                                                                <input type="text" value="<?= $key->e_product_motifname; ?>" id="e_product_motifname<?= $i; ?>" readonly class="form-control form-control-sm">
                                                            </td>
                                                            <td><input type="number" value="<?= $key->n_order; ?>" readonly name="n_order[]" id="n_order<?= $i; ?>" class="form-control form-control-sm" onblur="if(this.value=='' ){this.value='0' ;}" onfocus="if(this.value=='0' ){this.value='' ;}"></td>
                                                            <td><input type="number" value="<?= $key->n_acc; ?>" readonly name="n_acc[]" id="n_acc<?= $i; ?>" class="form-control form-control-sm" onkeyup="hetang('<?= $i; ?>');" onblur="if(this.value=='' ){this.value='0' ;}" onfocus="if(this.value=='0' ){this.value='' ;}"></td>
                                                            <td><input type="text" value="<?= $key->e_remark; ?>" readonly name="e_remarkitem[]" id="e_remarkitem<?= $i; ?>" class="form-control form-control-sm"></td>
                                                            <!-- <td class="text-center"><i title="Delete" class="fa fa-minus-circle fa-lg text-danger ibtnDel"></i></td> -->
                                                        </tr>
                                                <?php 
                                                            $total_rupiah += $key->n_order *  $key->v_unit_price;
                                                            $total_rupiah0 += $key->n_acc *  $key->v_unit_price;
                                                        }
                                                } ?>
                                            </tbody>
                                            <input type="hidden" id="jml" name="jml" value="<?= $i; ?>">
                                            <tfoot>
                                                <tr>
                                                    <td colspan="2" class="text-right"> <b>Total Rupiah</b></td>
                                                    <td colspan="2" class="text-right">
                                                        <input type="text" class="form-control form-control-sm text-right" value="<?= number_format($total_rupiah, 0); ?>" id="v_total" name="v_total" readonly>
                                                    </td>
                                                    <td colspan="2" class="text-right">
                                                        <input type="text" class="form-control form-control-sm text-right" value="<?= number_format($total_rupiah0, 0); ?>" id="v_total0" name="v_total0" readonly>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="6" class="text-right">
                                                        <b><i>( <span id="terbilangnya"> <?= terbilang($total_rupiah0); ?> rupiah </span> )</i></b>
                                                    </td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                                <div class="form-actions">
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