<style>
    .table td,
    .table.table-lg td {
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
                        <li class="breadcrumb-item"><a href="#" onclick="return false;"><?= $this->lang->line('Master'); ?></a></li>
                        <li class="breadcrumb-item"><a href="#" onclick="return false;"><?= $this->lang->line('Area'); ?></a></li>
                        <li class="breadcrumb-item"><a href="<?= base_url($this->folder); ?>"><?= $this->lang->line($this->title); ?></a></li>
                        <li class="breadcrumb-item active"><?= $this->lang->line('Tambah'); ?><?= $this->lang->line($this->title); ?></li>
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
                                <form class="form-validation" novalidate>
                                    <div class="form-body">
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label><?= $this->lang->line('Kode Ekspedisi'); ?> :</label>
                                                    <div class="controls">
                                                        <input type="text" class="form-control text-uppercase" data-validation-required-message="<?= $this->lang->line('Required'); ?>" placeholder="<?= $this->lang->line('Masukan'); ?> <?= $this->lang->line('Kode Ekspedisi'); ?>" id="i_sl_ekspedisi_id" name="i_sl_ekspedisi_id" maxlength="100" autocomplete="off" required autofocus>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label><?= $this->lang->line('Nomor Telepon'); ?> :</label>
                                                    <div class="controls">
                                                        <input type="text" class="form-control text-capitalize" placeholder="<?= $this->lang->line('Masukan'); ?>  <?= $this->lang->line('Nomor Telepon'); ?>" id="e_sl_ekspedisi_phone" name="e_sl_ekspedisi_phone" maxlength="500" autocomplete="off">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label><?= $this->lang->line('Nama Ekspedisi'); ?> :</label>
                                                    <div class="controls">
                                                        <input type="text" class="form-control text-capitalize" data-validation-required-message="<?= $this->lang->line('Required'); ?>" placeholder="<?= $this->lang->line('Masukan'); ?>  <?= $this->lang->line('Nama Ekspedisi'); ?>" id="e_sl_ekspedisi" name="e_sl_ekspedisi" maxlength="500" autocomplete="off" required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label><?= $this->lang->line('Alamat Ekspedisi'); ?> :</label>
                                                    <div class="controls">
                                                        <input type="text" class="form-control text-capitalize" placeholder="<?= $this->lang->line('Masukan'); ?>  <?= $this->lang->line('Alamat Ekspedisi'); ?>" id="e_sl_ekspedisi_address" name="e_sl_ekspedisi_address" maxlength="500" autocomplete="off">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label><?= $this->lang->line('Kota Ekspedisi'); ?> :</label>
                                                    <div class="controls">
                                                        <input type="text" class="form-control text-capitalize" placeholder="<?= $this->lang->line('Masukan'); ?>  <?= $this->lang->line('Kota Ekspedisi'); ?>" id="e_sl_ekspedisi_city" name="e_sl_ekspedisi_city" maxlength="500" autocomplete="off">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label><?= $this->lang->line('Fax'); ?> :</label>
                                                    <div class="controls">
                                                        <input type="text" class="form-control text-capitalize" placeholder="<?= $this->lang->line('Masukan'); ?>  <?= $this->lang->line('Fax'); ?>" id="e_sl_ekspedisi_fax" name="e_sl_ekspedisi_fax" maxlength="500" autocomplete="off">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>


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
                            <h4 class="card-title"><i class="feather icon-grid"></i> <?= $this->lang->line("Detail"); ?> <?= $this->lang->line("Area"); ?> <?= $this->lang->line("Ekspedisi"); ?></h4>
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
                                        <table class="table table-lg table-column table-bordered" id="tablecoverarea">
                                            <thead class="<?= $this->session->e_color; ?> bg-darken-1 text-white">
                                                <tr>
                                                    <th class="text-center" width="5%">No</th>
                                                    <th width="30%"><?= $this->lang->line("Nama Area Provinsi"); ?></th>
                                                    <th class="text-center" width="5%"><i class="fa fa-plus-circle fa-2x" title="<?= $this->lang->line('Tambah'); ?>" id="addriw"></i></th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                    <div class="form-actions">
                                        <input type="hidden" id="jmlx" name="jmlx" value="0">
                                        <button type="submit" id="submit" class="btn btn-primary btn-min-width"><i class="icon-paper-plane mr-1"></i><?= $this->lang->line("Simpan"); ?></button>
                                        <a href="<?= base_url($this->folder); ?>" class="btn btn-danger btn-min-width"><i class="icon-action-undo mr-1"></i><?= $this->lang->line("Kembali"); ?></a>
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