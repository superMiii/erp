<div class="content-header row">
    <div class="content-header-left col-md-12 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#" onclick="return false;"><?= $this->lang->line('Master'); ?></a></li>
                    <li class="breadcrumb-item"><a href="#" onclick="return false;"><?= $this->lang->line('Gudang'); ?></a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url($this->folder); ?>"><?= $this->lang->line($this->title); ?></a></li>
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
                <div class="card">
                    <div class="card-header header-elements-inline <?= $this->session->e_color; ?> bg-darken-1 text-white">
                        <h4 class="card-title"><i class="icon-pencil"></i> <?= $this->lang->line('Ubah'); ?> <?= $this->lang->line($this->title); ?></h4>
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
                                <div class="form-body">
                                    <div class="form-group">
                                        <label><?= $this->lang->line("Nama Gudang"); ?> :</label>
                                        <div class="controls">
                                            <select class="form-control round" id="i_store" data-placeholder="<?= $this->lang->line("Pilih") . ' ' . $this->lang->line("Gudang"); ?>" required data-validation-required-message="<?= $this->lang->line("Required"); ?>" name="i_store">
                                                <option value="<?= $data->i_store; ?>"><?= $data->i_store_id . ' - ' . $data->e_store_name; ?></option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label><?= $this->lang->line('Kode Lokasi Gudang'); ?> :</label>
                                        <div class="controls">
                                            <input type="hidden" id="id" name="id" value="<?= $data->i_store_loc; ?>">
                                            <input type="hidden" id="i_store_loc_id_old" name="i_store_loc_id_old" value="<?= $data->i_store_loc_id; ?>">
                                            <input type="text" class="form-control text-capitalize" value="<?= $data->i_store_loc_id; ?>" data-validation-required-message="<?= $this->lang->line('Required'); ?>" placeholder="<?= $this->lang->line('Masukan'); ?> <?= $this->lang->line('Kode Lokasi Gudang'); ?>" id="i_store_loc_id" name="i_store_loc_id" maxlength="100" autocomplete="off" required autofocus>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label><?= $this->lang->line('Nama Lokasi Gudang'); ?> :</label>
                                        <div class="controls">
                                            <input type="text" class="form-control text-capitalize" value="<?= $data->e_store_loc_name; ?>" data-validation-required-message="<?= $this->lang->line('Required'); ?>" placeholder="<?= $this->lang->line('Masukan'); ?> <?= $this->lang->line('Nama Lokasi Gudang'); ?>" id="e_store_loc_name" name="e_store_loc_name" maxlength="500" autocomplete="off" required autofocus>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-actions">
                                    <button type="submit" id="submit" class="btn btn-warning round btn-min-width mr-1"><i class="icon-paper-plane mr-1"></i><?= $this->lang->line("Ubah"); ?></button>
                                    <a href="<?= base_url($this->folder); ?>" class="btn btn-secondary round btn-min-width mr-1"><i class="icon-action-undo mr-1"></i><?= $this->lang->line("Kembali"); ?></a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--/ Alternative pagination table -->
</div>