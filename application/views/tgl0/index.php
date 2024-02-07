<div class="content-header row">
    <div class="content-header-left col-md-12 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#" onclick="return false;"><?= $this->lang->line('Setting'); ?></a></li>
                    <li class="breadcrumb-item active"><?= $this->lang->line($this->title); ?>
                    </li>
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
                        <h4 class="card-title"><i class="feather icon-list"></i><?= $this->lang->line('Daftar'); ?> <?= $this->lang->line($this->title); ?></h4>
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
                                <h6 class="form-section"></h6>
                                <div class="form-group row m-auto">
                                    <div class="col-md-12 row m-auto">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Tanggal Yang Dikunci :</label>
                                            <div class="input-group controls">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">
                                                        <span class="fa fa-calendar-o"></span>
                                                    </span>
                                                </div>
                                                <input type="hidden" name="id" id="id" class="form-control" value="<?= $data->i_t; ?>">
                                                <input id="d_tgl" name="d_tgl" type='date' value="<?= $data->d_tgl; ?>" class="form-control" value="<?= date('Y-m-d'); ?>"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>NORMAL / KUNCI :</label>
                                            <div class="controls">
                                                <fieldset>
                                                    <div class="float-left">
                                                        <input type="checkbox" <?php if ($data->f_tx == 't') {
                                                                                    echo "checked";
                                                                                } ?> 
                                                                                id="f_kunci" name="f_kunci" class="switch" data-group-cls="btn-group-sm" data-on-label="KUNCI" data-off-label="NORMAL" data-switch-always />
                                                    </div>
                                                </fieldset>
                                            </div>
                                        </div>
                                    </div>
                                        <div class="col-md-4">
                                            <fieldset>
                                                <div class="input-group">
                                                    <button class="btn btn-block closing <?= $this->session->e_color; ?> bg-darken-1 text-white" type="submit"><i class="fa fa-hourglass-half fa-spin fa-lg mr-2"></i>Closing</button>
                                                </div>
                                            </fieldset>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-actions">
                                <button type="submit" class="btn btn-warning round btn-min-width mr-1"><i class="icon-paper-plane mr-1"></i><?= $this->lang->line('Ubah'); ?></button>
                            </div>
                            <input type="hidden" id="path" value="<?= $this->folder; ?>">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--/ Alternative pagination table -->
</div>