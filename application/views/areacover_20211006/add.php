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

<div class="content-header row">
    <div class="content-header-left col-md-12 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#" onclick="return false;"><?= $this->lang->line('Master'); ?></a></li>
                    <li class="breadcrumb-item"><a href="#" onclick="return false;"><?= $this->lang->line('Area'); ?></a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url($this->folder); ?>"><?= $this->lang->line($this->title); ?></a></li>
                    <li class="breadcrumb-item active"><?= $this->lang->line('Tambah'); ?>
                        <?= $this->lang->line($this->title); ?></li>
                </ol>
            </div>
        </div>
        <!-- <h3 class="content-header-title mb-0">Basic DataTables</h3> -->
    </div>
</div>
<div class="content-body">
    <section id="pagination">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header header-elements-inline <?= $this->session->e_color;?> bg-darken-1 text-white">
                        <h4 class="card-title"><i class="icon-plus"></i> <?= $this->lang->line('Tambah'); ?>
                            <?= $this->lang->line($this->title); ?></h4>
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
                                    <!-- <div class="form-group">
                                        <label><?= $this->lang->line("Nama Area Provinsi"); ?> :</label>
                                        <input type="hidden" id="textcari" value="<?= $this->lang->line('Pilih'); ?> <?= $this->lang->line('Nama Area Provinsi'); ?>">
                                        <div class="controls">
                                            <select class="form-control round" id="i_area" required data-validation-required-message="<?= $this->lang->line("Required"); ?>" name="i_area">
                                                <option value=""></option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label><?= $this->lang->line("Nama Kota / Kabupaten"); ?> :</label>
                                        <div class="row skin skin-line">
                                            <div class="col-md-4 col-sm-12">
                                                <fieldset>
                                                    <input type="checkbox" id="f_all_city" name="f_all_city">
                                                    <label for="f_all_city"><?= $this->lang->line('Semua Kota Berdasarkan Area'); ?></label>
                                                </fieldset>
                                            </div>
                                        </div>
                                        <input type="hidden" id="textcarikota" value="<?= $this->lang->line('Pilih'); ?> <?= $this->lang->line('Nama Kota / Kabupaten'); ?>">
                                        <div class="controls">
                                            <select class="form-control round" id="i_city" multiple name="i_city[]">
                                                <option value=""></option>
                                            </select>
                                        </div>
                                    </div> -->
                                    <div class="form-group">
                                        <label><?= $this->lang->line('Kode Area Cakupan'); ?> :</label>
                                        <div class="controls">
                                            <input type="text" class="form-control text-capitalize" data-validation-required-message="<?= $this->lang->line('Required'); ?>" placeholder="<?= $this->lang->line('Masukan'); ?> <?= $this->lang->line('Kode Area Cakupan'); ?>" id="kode" name="kode" maxlength="30" autocomplete="off" required autofocus>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label><?= $this->lang->line('Nama Area Cakupan'); ?> :</label>
                                        <div class="controls">
                                            <input type="text" class="form-control text-capitalize" data-validation-required-message="<?= $this->lang->line('Required'); ?>" placeholder="<?= $this->lang->line('Masukan'); ?> <?= $this->lang->line('Nama Area Cakupan'); ?>" id="nama" name="nama" maxlength="100" autocomplete="off" required autofocus>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-actions">
                                    <button type="submit" id="submit" class="btn btn-success btn-min-width"><i class="icon-paper-plane mr-1"></i><?= $this->lang->line("Simpan"); ?></button>
                                    <a href="<?= base_url($this->folder); ?>" class="btn btn-danger btn-min-width"><i class="icon-action-undo mr-1"></i><?= $this->lang->line("Kembali"); ?></a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>