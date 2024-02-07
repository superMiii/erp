<div class="content-header row">
    <div class="content-header-left col-md-12 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#" onclick="return false;"><?= $this->lang->line('Master'); ?></a></li>
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
                    <div class="card-header header-elements-inline <?= $this->session->e_color;?> bg-darken-1 text-white">
                        <h4 class="card-title"><i class="icon-pencil"></i> <?= $this->lang->line('Ubah'); ?> <?= $this->lang->line($this->title); ?></h4>
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
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label><?= $this->lang->line('Kode Ekspedisi'); ?> :</label>
                                                <div class="controls">
                                                    <input type="hidden" id="i_sl_ekspedisi" name="i_sl_ekspedisi" value="<?= $data->i_sl_ekspedisi; ?>">
                                                    <input type="hidden" id="i_sl_ekspedisi_id_old" name="i_sl_ekspedisi_id_old" value="<?= $data->i_sl_ekspedisi_id; ?>">
                                                    <input type="text" class="form-control text-uppercase" value="<?= $data->i_sl_ekspedisi_id; ?>" data-validation-required-message="<?= $this->lang->line('Required'); ?>" placeholder="<?= $this->lang->line('Masukan'); ?> <?= $this->lang->line('Kode Ekspedisi'); ?>" id="i_sl_ekspedisi_id" name="i_sl_ekspedisi_id" maxlength="100" autocomplete="off" required autofocus>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label><?= $this->lang->line('Nama Ekspedisi'); ?> :</label>
                                                <div class="controls">
                                                    <input type="text" class="form-control text-capitalize" data-validation-required-message="<?= $this->lang->line('Required'); ?>" placeholder="<?= $this->lang->line('Masukan'); ?>  <?= $this->lang->line('Nama Ekspedisi'); ?>" id="e_sl_ekspedisi" name="e_sl_ekspedisi" value="<?= $data->e_sl_ekspedisi; ?>" maxlength="500" autocomplete="off" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label><?= $this->lang->line("Area"); ?> :</label>
                                                <div class="controls">
                                                    <select class="form-control select2" style="width: 100%" required data-placeholder="<?= $this->lang->line("Pilih"); ?> <?= $this->lang->line("Area"); ?>" data-validation-required-message="<?= $this->lang->line("Required"); ?>" name="i_area" id="i_area">
                                                        <option value=""></option>
                                                        <?php if ($area->num_rows() > 0) {
                                                            foreach ($area->result() as $key) { ?>
                                                                <option value="<?= $key->i_area; ?>" <?php if ($data->i_area == $key->i_area) {; ?> selected <?php } ?>><?= $key->i_area_id . ' - ' . $key->e_area_name; ?></option>
                                                        <?php }
                                                        } ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label><?= $this->lang->line('Kota Ekspedisi'); ?> :</label>
                                                <div class="controls">
                                                    <input type="text" class="form-control text-capitalize" placeholder="<?= $this->lang->line('Masukan'); ?>  <?= $this->lang->line('Kota Ekspedisi'); ?>" id="e_sl_ekspedisi_city" name="e_sl_ekspedisi_city" value="<?= $data->e_sl_ekspedisi_city; ?>" maxlength="500" autocomplete="off">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label><?= $this->lang->line('Alamat Ekspedisi'); ?> :</label>
                                                <div class="controls">
                                                    <input type="text" class="form-control text-capitalize" placeholder="<?= $this->lang->line('Masukan'); ?>  <?= $this->lang->line('Alamat Ekspedisi'); ?>" id="e_sl_ekspedisi_address" name="e_sl_ekspedisi_address" value="<?= $data->e_sl_ekspedisi_address; ?>" maxlength="500" autocomplete="off">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label><?= $this->lang->line('Nomor Telepon'); ?> :</label>
                                                <div class="controls">
                                                    <input type="text" class="form-control text-capitalize" placeholder="<?= $this->lang->line('Masukan'); ?>  <?= $this->lang->line('Nomor Telepon'); ?>" id="e_sl_ekspedisi_phone" name="e_sl_ekspedisi_phone" value="<?= $data->e_sl_ekspedisi_phone; ?>" maxlength="500" autocomplete="off">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label><?= $this->lang->line('Fax'); ?> :</label>
                                                <div class="controls">
                                                    <input type="text" class="form-control text-capitalize" placeholder="<?= $this->lang->line('Masukan'); ?>  <?= $this->lang->line('Fax'); ?>" id="e_sl_ekspedisi_fax" name="e_sl_ekspedisi_fax" value="<?= $data->e_sl_ekspedisi_fax; ?>" maxlength="500" autocomplete="off">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-actions">
                                    <button type="submit" class="btn btn-success btn-min-width"><i class="icon-paper-plane mr-1"></i><?= $this->lang->line('Ubah'); ?></button>
                                    <a href="<?= base_url($this->folder); ?>" class="btn btn-danger btn-min-width"><i class="icon-action-undo mr-1"></i><?= $this->lang->line('Kembali'); ?></a>
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