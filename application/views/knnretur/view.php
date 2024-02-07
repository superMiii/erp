<style>
    .table.table-xs th,
    .table td,
    .table.table-xs td {
        padding: 0.3rem 0.3rem;
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
                    <li class="breadcrumb-item"><a href="<?= base_url($this->folder . '/index/' . encrypt_url($dfrom) . '/' . encrypt_url($dto) . '/' . encrypt_url($harea)); ?>"><?= $this->lang->line($this->title); ?></a></li>
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
                                                    <input type="hidden" name="id" id="id" readonly value="<?= $data->i_kn; ?>">
                                                    <input type="hidden" name="i_document_old" id="i_document_old" readonly value="<?= $data->i_kn_id; ?>">
                                                    <input type="text" name="i_document" id="i_document" readonly value="<?= $data->i_kn_id; ?>" placeholder="KN-<?= date('ym'); ?>-000001" class="form-control form-control-sm text-uppercase" data-validation-required-message="<?= $this->lang->line("Required"); ?>" maxlength="500" autocomplete="off" required aria-label="Text input with checkbox">
                                                </div>
                                            </fieldset>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Tanggal Dokumen"); ?> :</label>
                                            <div class="input-group input-group-sm controls">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">
                                                        <span class="fa fa-calendar-o"></span>
                                                    </span>
                                                </div>
                                                <input type="date" class="form-control form-control-sm date" readonly value="<?= $data->d_kn; ?>" data-validation-required-message="<?= $this->lang->line("Required"); ?>" placeholder="" id="d_document" name="d_document" maxlength="500" autocomplete="off" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Nomor Referensi"); ?> :</label>
                                            <fieldset>
                                                <div class="input-group input-group-sm">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">
                                                            <span class="fa fa-hashtag"></span>
                                                        </div>
                                                    </div>
                                                    <input type="text" readonly name="i_refference" id="i_refference" value="<?= $data->i_refference; ?>" placeholder="000001" class="form-control form-control-sm text-uppercase" data-validation-required-message="<?= $this->lang->line("Required"); ?>" maxlength="500" autocomplete="off" required aria-label="Text input with checkbox">
                                                </div>
                                            </fieldset>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Tanggal Referensi"); ?> :</label>
                                            <div class="input-group input-group-sm controls">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">
                                                        <span class="fa fa-calendar-o"></span>
                                                    </span>
                                                </div>
                                                <input type="date" readonly class="form-control form-control-sm date" min="2021-10-01" max="<?= date('Y-m-d'); ?>" value="<?= $data->d_refference; ?>" data-validation-required-message="<?= $this->lang->line("Required"); ?>" placeholder="" id="d_refference" name="d_refference" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Baris ke 2 -->
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Nama Area Provinsi"); ?> :</label>
                                            <div class="controls">
                                                <input type="text" readonly class="form-control form-control-sm" value="<?= $data->i_area_id . ' - ' . $data->e_area_name; ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Nama Pelanggan"); ?> :</label>
                                            <div class="controls">
                                                <input type="text" readonly class="form-control form-control-sm" value="<?= $data->i_customer_id . ' - ' . $data->e_customer_name; ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Baris ke 4 -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><?= $this->lang->line('Alamat Pelanggan'); ?> :</label>
                                            <div class="controls">
                                                <textarea class="form-control text-capitalize clear" placeholder="<?= $this->lang->line('Alamat Pelanggan'); ?>" id="address" name="address" autocomplete="off" readonly><?= $data->e_customer_address; ?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Nama Pramuniaga"); ?> :</label>
                                            <div class="controls">
                                                <input type="text" readonly class="form-control form-control-sm" value="<?= $data->i_salesman_id . ' - ' . $data->e_salesman_name; ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Baris ke 5 -->
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Masalah"); ?> :</label>
                                            <div class="controls">
                                                <fieldset>
                                                    <div class="float-left">
                                                        <input readonly type="checkbox" id="f_masalah" name="f_masalah" <?php if ($data->f_masalah == 't') {
                                                                                                                            echo "checked";
                                                                                                                        } ?> class="switch" data-group-cls="btn-group-sm" data-on-label="<?= $this->lang->line('Ya'); ?>" data-off-label="<?= $this->lang->line('Tidak'); ?>" data-switch-always />
                                                    </div>
                                                </fieldset>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Insentif"); ?> :</label>
                                            <div class="controls">
                                                <fieldset>
                                                    <div class="float-left">
                                                        <input type="checkbox" readonly <?php if ($data->f_insentif == 't') {
                                                                                            echo "checked";
                                                                                        } ?> id="f_insentif" name="f_insentif" class="switch" data-group-cls="btn-group-sm" data-on-label="<?= $this->lang->line('Ya'); ?>" data-off-label="<?= $this->lang->line('Tidak'); ?>" data-switch-always />
                                                    </div>
                                                </fieldset>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Kotor"); ?> :</label>
                                            <div class="controls">
                                                <fieldset>
                                                    <div class="float-left">
                                                        <input type="text" id="v_gross" readonly name="v_gross" value="<?= number_format($data->v_gross); ?>" class="form-control form-control-sm" autocomplete="off" onkeyup="reformat(this); hetang();" data-validation-required-message="<?= $this->lang->line("Required"); ?>" placeholder="<?= $this->lang->line("Kotor"); ?>" onblur="if(this.value==''){this.value='0';hetang();}" onfocus="if(this.value=='0'){this.value='';}" value="0" />
                                                    </div>
                                                </fieldset>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Potongan"); ?> :</label>
                                            <div class="controls">
                                                <fieldset>
                                                    <div class="float-left">
                                                        <input type="text" id="v_discount" readonly name="v_discount" value="<?= number_format($data->v_discount); ?>" class="form-control form-control-sm" autocomplete="off" onkeyup="reformat(this); hetang();" data-validation-required-message="<?= $this->lang->line("Required"); ?>" placeholder="<?= $this->lang->line("Potongan"); ?>" onblur="if(this.value==''){this.value='0';hetang();}" onfocus="if(this.value=='0'){this.value='';}" />
                                                    </div>
                                                </fieldset>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Bersih"); ?> :</label>
                                            <div class="controls">
                                                <fieldset>
                                                    <div class="float-left">
                                                        <input type="text" id="v_netto" readonly value="<?= number_format($data->v_netto); ?>" readonly name="v_netto" class="form-control form-control-sm" autocomplete="off" onkeyup="reformat(this);" data-validation-required-message="<?= $this->lang->line("Required"); ?>" placeholder="<?= $this->lang->line("Bersih"); ?>" />
                                                    </div>
                                                </fieldset>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Sisa"); ?> :</label>
                                            <div class="controls">
                                                <fieldset>
                                                    <div class="float-left">
                                                        <input type="text" readonly id="v_sisa" value="<?= number_format($data->v_sisa); ?>" readonly name="v_sisa" class="form-control form-control-sm" autocomplete="off" onkeyup="reformat(this);" data-validation-required-message="<?= $this->lang->line("Required"); ?>" placeholder="<?= $this->lang->line("Sisa"); ?>" />
                                                    </div>
                                                </fieldset>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Baris ke 5 -->
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Keterangan"); ?> :</label>
                                            <textarea readonly class="form-control text-capitalize" placeholder="<?= $this->lang->line('Keterangan'); ?>" id="e_remark" name="e_remark"><?= $data->e_remark; ?></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-actions">
                                <form action="<?= base_url($this->folder); ?>" method="post">
                                    <input type="hidden" name="dfrom" value="<?= $dfrom; ?>">
                                    <input type="hidden" name="dto" value="<?= $dto; ?>">
                                    <a href="<?= base_url($this->folder . '/index/' . encrypt_url($dfrom) . '/' . encrypt_url($dto) . '/' . encrypt_url($harea)); ?>" class="btn btn-secondary round btn-min-width mr-1"><i class="icon-action-undo mr-1"></i><?= $this->lang->line("Kembali"); ?></a>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--/ Alternative pagination table -->
</div>