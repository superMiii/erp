<style>
    .table td,
    .table.table-lg td {
        padding: 0.4rem 0.4rem;
    }

    .table td,
    .table.table-sm td {
        padding: 0.3rem 0.2rem;
    }

    .table>tfoot>tr>th,
    .table>tfoot>tr>td {
        border: none !important;
    }
</style>

<!-- <form class="form-validation" novalidate> -->
<?php echo form_open_multipart('Pengguna/add') ?>
<div class="content-header row">
    <div class="content-header-left col-md-12 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#" onclick="return false;"><?= $this->lang->line('Setting'); ?></a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url($this->folder); ?>"><?= $this->lang->line($this->title); ?></a></li>
                    <li class="breadcrumb-item active"><?= $this->lang->line('Tambah'); ?> <?= $this->lang->line($this->title); ?></li>
                </ol>
            </div>
        </div>
        <!-- <h3 class="content-header-title mb-0">Basic DataTables</h3> -->
    </div>
</div>

<!-- HEADER -->
<div class="content-body">
    <section id="pagination">
        <div class="row">
            <div class="col-12">
                <div class="card box-shadow-2">
                    <div class="card-header header-elements-inline <?= $this->session->e_color; ?> bg-darken-1 text-white">
                        <h4 class="card-title"><i class="icon-plus"></i> <?= $this->lang->line('Tambah'); ?> <?= $this->lang->line($this->title); ?></h4>
                        <input type="hidden" id="path" value="<?= $this->folder; ?>">
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
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label><?= $this->lang->line("Id Pengguna"); ?> :</label>
                                        <div class="controls">
                                            <input type="text" class="form-control text-lowercase" data-validation-required-message="<?= $this->lang->line("Required"); ?>" placeholder="<?= $this->lang->line("Id Pengguna"); ?>" id="i_user_id" name="i_user_id" maxlength="100" autofocus autocomplete="off" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label><?= $this->lang->line("Nama Pengguna"); ?> :</label>
                                        <div class="controls">
                                            <input type="text" class="form-control text-capitalize" data-validation-required-message="<?= $this->lang->line("Required"); ?>" placeholder="<?= $this->lang->line("Nama Pengguna"); ?>" id="e_user_name" name="e_user_name" maxlength="500" autocomplete="off" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label><?= $this->lang->line("Kata Sandi"); ?> :</label>
                                        <div class="controls">
                                            <input type="password" id="passwordkr" name="password" class="form-control" required data-validation-required-message="<?= $this->lang->line("Required"); ?>" placeholder="<?= $this->lang->line("Kata Sandi"); ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label><?= $this->lang->line("Ulangi Kata Sandi"); ?> :</label>
                                        <div class="controls">
                                            <input type="password" id="passwordyz" name="password2" data-validation-match-match="password" data-validation-required-message="<?= $this->lang->line("Required"); ?>" class="form-control" required placeholder="<?= $this->lang->line("Ulangi Kata Sandi"); ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Show Password</label>
                                        <div class="controls skin-square">
                                            <input type="checkbox" id="showPassword"> 
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <div class="controls">
                                            <fieldset>
                                                <div class="float-left">
                                                    <input type="checkbox" id="f_pusat" name="f_pusat" class="switch" data-onstyle="success" data-group-cls="btn-group-sm" data-on-label="<?= $this->lang->line('Daerah'); ?>" data-off-label="<?= $this->lang->line('Pusat'); ?>" data-switch-always />
                                                </div>
                                            </fieldset>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <input type="hidden" id="fotoku" name="fotoku" value="default.jpg">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Upload Foto:</label>
                                        <div class="controls">
                                            <input type="file" name="foto" class="form-control" placeholder="Foto">
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
<!-- END HEADER -->

<!-- TABLE COVER -->
<div class="content-body">
    <section id="pagination">
        <div class="row">
            <div class="col-12">
                <div class="card box-shadow-2">
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
                            <div class="table-responsive">
                                <table class="table table-lg table-column table-bordered" id="tablecover">
                                    <thead class="<?= $this->session->e_color; ?> bg-darken-1 font-medium-3 text-white">
                                        <tr>
                                            <th class="text-center" width="5%">No</th>
                                            <th width="50%"><?= $this->lang->line("Departemen"); ?></th>
                                            <th width="40%"><?= $this->lang->line("Level"); ?></th>
                                            <th class="text-center" width="5%"><i class="fa fa-plus-circle fa-2x" title="<?= $this->lang->line('Tambah'); ?>" id="addrow"></i></th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                    <input type="hidden" id="jml" name="jml" value="0">
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<!-- END TABLE COVER -->

<!-- TABLE AREA -->
<div class="content-body">
    <section id="pagination">
        <div class="row">
            <div class="col-12">
                <div class="card box-shadow-2">
                    <div class="card-header header-elements-inline <?= $this->session->e_color; ?> bg-darken-1 text-white">
                        <h4 class="card-title"><i class="feather icon-grid"></i> <?= $this->lang->line("Detail"); ?> <?= $this->lang->line("Company"); ?> & <?= $this->lang->line("Area"); ?> & <?= $this->lang->line("Kas / Bank"); ?></h4>
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
                            <div class="table-responsive">
                                <div class="form-body">
                                    <table class="table table-sm table-column table-bordered" id="tablecoverarea">
                                        <thead class="<?= $this->session->e_color; ?> bg-darken-1 font-medium-3 text-white">
                                            <tr>
                                                <th class="text-center" width="1%">No</th>
                                                <th width="25%"><?= $this->lang->line("Company"); ?></th>
                                                <th width="30%"><?= $this->lang->line("Area"); ?></th>
                                                <th width="10%" class="text-center"><?= $this->lang->line("Semua Area"); ?></th>
                                                <th width="25%"><?= $this->lang->line("Kas / Bank Masuk"); ?></th>
                                                <th width="25%"><?= $this->lang->line("Kas / Bank Keluar"); ?></th>
                                                <th width="35%"><?= $this->lang->line("Gudang"); ?></th>
                                                <th class="text-center" width="1%"><i class="fa fa-plus-circle fa-2x" title="<?= $this->lang->line('Tambah'); ?>" id="addriw"></i></th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                                <div class="form-actions">
                                    <input type="hidden" id="jmlx" name="jmlx" value="0">
                                    <!-- <button type="submit" class="btn <?= $this->session->e_color; ?> bg-darken-4 text-white btn-min-width"><i class="icon-paper-plane mr-1"></i><?= $this->lang->line("Simpan"); ?></button> -->
                                    <button type="submit" class="btn btn-success round btn-min-width mr-1"><i class="icon-paper-plane mr-1"></i><?= $this->lang->line("Simpan"); ?></button>
                                    <a href="<?= base_url($this->folder); ?>" class="btn btn-secondary round btn-min-width mr-1"><i class="icon-action-undo mr-1"></i><?= $this->lang->line("Kembali"); ?></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<!-- END TABLE AREA -->
<!-- </form> -->
<?php echo form_close(); ?>