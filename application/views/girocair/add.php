<form class="form-validation" novalidate>
    <div class="content-header row">
        <div class="content-header-left col-md-12 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#" onclick="return false;"><?= $this->lang->line('Keuangan'); ?></a></li>
                        <li class="breadcrumb-item"><a href="#" onclick="return false;"><?= $this->lang->line('Giro'); ?></a></li>
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
                    <div class="card box-shadow-0 border-primary">
                        <div class="card-header card-head-inverse <?= $this->session->e_color; ?> bg-darken-1 text-white">
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
                                <div class="form-body">
                                    <!-- Baris ke 1 -->
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
                                                        <input type="hidden" name="id" id="id" placeholder="No Giro" value="<?= $data->i_giro; ?>">
                                                        <input type="hidden" name="i_document_old" id="i_document_old" value="<?= $data->i_giro_id; ?>">
                                                        <input type="text" readonly name="i_document" id="i_document" value="<?= $data->i_giro_id; ?>" placeholder="No Giro" class="form-control form-control-sm text-uppercase" data-validation-required-message="<?= $this->lang->line("Required"); ?>" maxlength="500" autocomplete="off" required aria-label="Text input with checkbox">
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
                                                    <input type="date" <?= konci(); ?> class="form-control form-control-sm date" min="<?= get_min_date2(); ?>" max="<?= date('Y-m-d'); ?>" value="<?= $data->d_giro; ?>" data-validation-required-message="<?= $this->lang->line("Required"); ?>" placeholder="" id="d_document" name="d_document" maxlength="500" autocomplete="off" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label><?= $this->lang->line("Tanggal Jatuh Tempo"); ?> :</label>
                                                <div class="input-group input-group-sm controls">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">
                                                            <span class="fa fa-calendar-o"></span>
                                                        </span>
                                                    </div>
                                                    <input type="date" class="form-control form-control-sm date" min="<?= get_min_date2(); ?>" value="<?= $data->d_giro_duedate; ?>" data-validation-required-message="<?= $this->lang->line("Required"); ?>" placeholder="" id="d_giro_duedate" name="d_giro_duedate" maxlength="500" autocomplete="off" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label><?= $this->lang->line("Tanggal Terima"); ?> :</label>
                                                <div class="input-group input-group-sm controls">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">
                                                            <span class="fa fa-calendar-o"></span>
                                                        </span>
                                                    </div>
                                                    <input type="date" class="form-control form-control-sm date" min="<?= get_min_date2(); ?>" max="<?= date('Y-m-d'); ?>" value="<?= $data->d_giro_terima; ?>" data-validation-required-message="<?= $this->lang->line("Required"); ?>" placeholder="" id="d_giro_terima" name="d_giro_terima" maxlength="500" autocomplete="off" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Baris ke 2 -->
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label><?= $this->lang->line("Nama Area Provinsi"); ?> :</label>
                                                <div class="controls">
                                                    <input type="text" readonly class="form-control form-control-sm" value="<?= $data->i_area_id . ' - ' . $data->e_area_name; ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label><?= $this->lang->line("Nama Pelanggan"); ?> :</label>
                                                <div class="controls">
                                                    <input type="text" readonly class="form-control form-control-sm" value="<?= $data->i_customer_id . ' - ' . $data->e_customer_name; ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label><?= $this->lang->line("No Daftar Tagihan"); ?> :</label>
                                                <div class="controls">
                                                    <input type="text" readonly class="form-control form-control-sm" value="<?= $data->i_dt_id . ' - ' . $data->d_dt; ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Bari ke 3 -->
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label><?= $this->lang->line("Bank"); ?> :</label>
                                                <div class="controls">
                                                    <input type="text" readonly value="<?= $data->e_giro_bank; ?>" class="form-control form-control-sm text-uppercase" maxlength="500" name="e_giro_bank" placeholder="<?= $this->lang->line("Bank"); ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label><?= $this->lang->line("Jumlah"); ?> :</label>
                                                <div class="controls">
                                                    <input type="text" readonly value="<?= number_format($data->v_jumlah); ?>" class="form-control form-control-sm formatrupiah" onkeyup="formatrupiahkeyup(this);" maxlength="12" name="v_jumlah" placeholder="<?= $this->lang->line("Jumlah"); ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label><?= $this->lang->line("Keterangan"); ?> :</label>
                                                <textarea readonly class="form-control text-capitalize" placeholder="<?= $this->lang->line('Keterangan'); ?>" name="e_giro_description"><?= $data->e_giro_description; ?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Baris ke 4 -->
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label><?= $this->lang->line("Tanggal Cair"); ?> :</label>
                                                <div class="input-group input-group-sm controls">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">
                                                            <span class="fa fa-calendar-o"></span>
                                                        </span>
                                                    </div>
                                                    <input type="date" class="form-control form-control-sm date" min="2021-10-01" max="<?= date('Y-m-d'); ?>" placeholder="mm/dd/yyyy" id="d_giro_cair" name="d_giro_cair" maxlength="500" autocomplete="off">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label><?= $this->lang->line("Tanggal Tolak"); ?> :</label>
                                                <div class="input-group input-group-sm controls">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">
                                                            <input type="checkbox" id="ceklis" name="f_giro_tolak" aria-label="Checkbox for following text input">
                                                        </div>
                                                    </div>
                                                    <input type="date" readonly class="form-control form-control-sm date" min="2021-10-01" max="<?= date('Y-m-d'); ?>" placeholder="mm/dd/yyyy" id="d_giro_tolak" name="d_giro_tolak" maxlength="500" autocomplete="off">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-actions">
                                    <button type="submit" id="submit" class="btn btn-success round btn-min-width mr-1"><i class="icon-paper-plane mr-1"></i><?= $this->lang->line("Simpan"); ?></button>
                                    <a href="<?= base_url($this->folder . '/indexx/' . encrypt_url($dfrom) . '/' . encrypt_url($dto) . '/' . encrypt_url($harea)); ?>" class="btn btn-secondary round btn-min-width mr-1"><i class="icon-action-undo mr-1"></i><?= $this->lang->line("Kembali"); ?></a>
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