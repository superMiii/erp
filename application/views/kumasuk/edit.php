<form class="form-validation" novalidate>
    <div class="content-header row">
        <div class="content-header-left col-md-12 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#" onclick="return false;"><?= $this->lang->line('Keuangan'); ?></a></li>
                        <li class="breadcrumb-item"><a href="<?= base_url($this->folder . '/index/' . encrypt_url($dfrom) . '/' . encrypt_url($dto) . '/' . encrypt_url($harea)); ?>"><?= $this->lang->line($this->title); ?></a></li>
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
                    <div class="card box-shadow-0 border-primary">
                        <div class="card-header card-head-inverse <?= $this->session->e_color; ?> bg-darken-1 text-white">
                            <h4 class="card-title"><i class="icon-pencil"></i> <?= $this->lang->line('Ubah'); ?> <?= $this->lang->line($this->title); ?></h4>
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
                                                        <input type="hidden" name="id" id="id" placeholder="No Giro" value="<?= $data->i_kum; ?>">
                                                        <input type="hidden" name="i_document_old" id="i_document_old" value="<?= $data->i_kum_id; ?>">
                                                        <input type="text" name="i_document" id="i_document" value="<?= $data->i_kum_id; ?>" <?= konci(); ?> placeholder="TM-<?= date('ym'); ?>-000001" class="form-control form-control-sm text-uppercase" data-validation-required-message="<?= $this->lang->line("Required"); ?>" maxlength="500" autocomplete="off" required aria-label="Text input with checkbox">
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
                                                    <input type="date" class="form-control form-control-sm date" min="<?= get_min_date2(); ?>" max="<?= date('Y-m-d'); ?>" value="<?= $data->d_kum; ?>" data-validation-required-message="<?= $this->lang->line("Required"); ?>" placeholder="" id="d_document" name="d_document" maxlength="500" autocomplete="off" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label><?= $this->lang->line("Nama Area Provinsi"); ?> :</label>
                                                <div class="controls">
                                                    <select class="form-control" id="i_area" required data-placeholder="<?= $this->lang->line("Pilih") . ' ' . $this->lang->line("Area"); ?>" data-validation-required-message="<?= $this->lang->line("Required"); ?>" name="i_area">
                                                        <option value="<?= $data->i_area; ?>"><?= $data->i_area_id . ' - ' . $data->e_area_name; ?></option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Baris ke 2 -->
                                    <div class="row">
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label><?= $this->lang->line("Nama Pelanggan"); ?> :</label>
                                                <div class="controls">
                                                    <select class="form-control" name="i_customer" id="i_customer" data-placeholder="<?= $this->lang->line("Pilih") . ' ' . $this->lang->line("Pelanggan"); ?>" required data-validation-required-message="<?= $this->lang->line("Required"); ?>">
                                                        <option value="<?= $data->i_customer; ?>"><?= $data->i_customer_id . ' - ' . $data->e_customer_name; ?></option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label><?= $this->lang->line("Nama Pramuniaga"); ?> :</label>
                                                <div class="controls">
                                                    <select class="form-control" name="i_salesman" id="i_salesman" data-placeholder="<?= $this->lang->line("Pilih") . ' ' . $this->lang->line("Pramuniaga"); ?>" required data-validation-required-message="<?= $this->lang->line("Required"); ?>">
                                                        <option value="<?= $data->i_salesman; ?>"><?= $data->i_salesman_id . ' - ' . $data->e_salesman_name; ?></option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label><?= $this->lang->line("No Daftar Tagihan"); ?> :</label>
                                                <div class="controls">
                                                    <select class="form-control" name="i_dt" id="i_dt" data-placeholder="<?= $this->lang->line("Pilih") . ' ' . $this->lang->line("Daftar Tagihan"); ?>">
                                                        <option value="<?= $data->i_dt; ?>"><?= $data->i_dt_id . ' - ' . $data->d_dt; ?></option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Bari ke 3 -->
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label><?= $this->lang->line("Nama Bank"); ?> :</label>
                                                <div class="controls">
                                                    <select class="form-control" id="i_bank" required data-placeholder="<?= $this->lang->line("Pilih") . ' ' . $this->lang->line("Bank"); ?>" data-validation-required-message="<?= $this->lang->line("Required"); ?>" name="i_bank">
                                                        <option value="<?= $data->i_bank; ?>"><?= $data->i_bank_id . ' - ' . $data->e_bank_name; ?></option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label><?= $this->lang->line("Jumlah"); ?> :</label>
                                                <div class="controls">
                                                    <?php if ($data->alok > 0) { ?>
                                                        <input type="text" readonly value="<?= number_format($data->v_jumlah); ?>" class="form-control form-control-sm formatrupiah" onkeyup="formatrupiahkeyup(this);" maxlength="12" name="v_jumlah" placeholder="<?= $this->lang->line("Jumlah"); ?>">
                                                    <?php } else { ?>
                                                        <input type="text" value="<?= number_format($data->v_jumlah); ?>" class="form-control form-control-sm formatrupiah" onkeyup="formatrupiahkeyup(this);" maxlength="12" name="v_jumlah" placeholder="<?= $this->lang->line("Jumlah"); ?>">
                                                <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label><?= $this->lang->line("Atas Nama Pengirim"); ?> :</label>
                                                <div class="controls">
                                                    <input type="text" value="<?= $data->e_atasnama; ?>" class="form-control" required name="e_atasnama" id="e_atasnama" placeholder="<?= $this->lang->line("Atas Nama Pengirim"); ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Keterangan"); ?> :</label>
                                            <textarea class="form-control text-capitalize" placeholder="<?= $this->lang->line('Keterangan'); ?>" name="e_remark"><?= $data->e_remark; ?></textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-start align-items-center">
                                    <button type="submit" class="btn btn-warning round btn-min-width mr-1"><i class="icon-paper-plane mr-1"></i><?= $this->lang->line('Ubah'); ?></button>
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