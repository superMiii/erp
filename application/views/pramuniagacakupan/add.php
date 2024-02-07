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
    </div>
</div>
<div class="content-body">
    <section id="pagination">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header header-elements-inline <?= $this->session->e_color; ?> bg-darken-1 text-white">
                        <h4 class="card-title"><i class="icon-plus"></i> <?= $this->lang->line('Tambah'); ?>
                            <?= $this->lang->line($this->title); ?></h4>
                        <input type="hidden" id="path" value="<?= $this->folder; ?>">
                        <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                        <div class="heading-elements">
                            <ul class="list-inline mb-0">
                                <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                                <li><a data-action="reload"><i class="ft-rotate-cw"></i></a></li>
                                <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                                <li><a data-action="close"><i class="ft-x"></i></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-content collapse show">
                        <div class="card-body card-dashboard">
                            <form class="form-validation" novalidate>
                                <div class="form-group">
                                    <label><?= $this->lang->line("Nama Area Cakupan"); ?> :</label>
                                    <input type="hidden" id="textcari" value="<?= $this->lang->line('Pilih'); ?> <?= $this->lang->line('Nama Area Cakupan'); ?>">
                                    <div class="controls">
                                        <select class="form-control round" id="i_area_cover" required data-validation-required-message="<?= $this->lang->line("Required"); ?>" name="i_area_cover">
                                            <option value=""></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label><?= $this->lang->line("Nama Pramuniaga"); ?> :</label>
                                    <input type="hidden" id="textcarisales" value="<?= $this->lang->line('Pilih'); ?> <?= $this->lang->line('Nama Pramuniaga'); ?>">
                                    <div class="controls">
                                        <select class="form-control round" id="i_salesman" required data-validation-required-message="<?= $this->lang->line("Required"); ?>" name="i_salesman">
                                            <option value=""></option>
                                        </select>
                                    </div>
                                </div>
                                <div hidden="true" class="form-group">
                                    <label><?= $this->lang->line('Tanggal Awal Cakupan'); ?> :</label>
                                    <div class="controls">
                                        <input type="date" value="<?= date('Y-m-01'); ?>" class="form-control" data-validation-required-message="<?= $this->lang->line('Required'); ?>" placeholder="<?= $this->lang->line('Masukan'); ?> <?= $this->lang->line('Tanggal Awal Cakupan'); ?>" id="d_salesman_areacoverstart" name="d_salesman_areacoverstart" required>
                                    </div>
                                </div>
                                <div hidden="true" class="form-group">
                                    <label><?= $this->lang->line('Tanggal Akhir Cakupan'); ?> :</label>
                                    <div class="controls">
                                        <input type="date" value="<?= date('Y-12-31'); ?>" class="form-control" data-validation-required-message="<?= $this->lang->line('Required'); ?>" placeholder="<?= $this->lang->line('Masukan'); ?> <?= $this->lang->line('Tanggal Akhir Cakupan'); ?>" id="d_salesman_areacoverfinish" name="d_salesman_areacoverfinish" required>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-start align-items-center">
                                    <button type="submit" class="btn btn-success round btn-min-width mr-1"><i class="icon-paper-plane mr-1"></i><?= $this->lang->line('Simpan'); ?></button>
                                    <a href="<?= base_url($this->folder); ?>" class="btn btn-secondary round btn-min-width mr-1"><i class="icon-action-undo mr-1"></i><?= $this->lang->line('Kembali'); ?></a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>