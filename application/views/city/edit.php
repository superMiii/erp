<div class="content-header row">
    <div class="content-header-left col-md-12 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#" onclick="return false;"><?= $this->lang->line('Master'); ?></a></li>
                    <li class="breadcrumb-item"><a href="#" onclick="return false;"><?= $this->lang->line('Area'); ?></a></li>
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

                                <div class="form-group">
                                    <label><?= $this->lang->line("Nama Area Provinsi"); ?> :</label>
                                    <input type="hidden" id="textcari" value="<?= $this->lang->line('Pilih'); ?> <?= $this->lang->line('Nama Area Provinsi'); ?>">
                                    <div class="controls">
                                        <select class="form-control round" id="iarea" required data-validation-required-message="<?= $this->lang->line("Required"); ?>" name="iarea">
                                            <option value="<?= $data->i_area; ?>"><?= $data->i_area_id . ' - ' . $data->e_area_name; ?></option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label><?= $this->lang->line('Kode Kota / Kabupaten'); ?> :</label>
                                    <div class="controls">
                                        <input type="hidden" class="form-control" id="id" name="id" value="<?= $data->i_city; ?>">
                                        <input type="hidden" class="form-control" id="kodeold" name="kodeold" value="<?= $data->i_city_id; ?>">
                                        <input type="text" class="form-control round text-capitalize" data-validation-required-message="<?= $this->lang->line('Required'); ?>" placeholder="<?= $this->lang->line('Masukan'); ?> <?= $this->lang->line('Kode Kota / Kabupaten'); ?>" id="kode" name="kode" maxlength="30" autocomplete="off" value="<?= $data->i_city_id; ?>" required autofocus>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label><?= $this->lang->line('Nama Kota / Kabupaten'); ?> :</label>
                                    <div class="controls">
                                        <input type="text" class="form-control round text-capitalize" data-validation-required-message="<?= $this->lang->line('Required'); ?>" placeholder="<?= $this->lang->line('Masukan'); ?> <?= $this->lang->line('Nama Kota / Kabupaten'); ?>" id="nama" name="nama" maxlength="100" autocomplete="off" value="<?= $data->e_city_name; ?>" required autofocus>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label><?= $this->lang->line('Toleransi TOP'); ?> :</label>
                                    <div class="controls">
                                        <input type="number" class="form-control round text-capitalize" data-validation-required-message="<?= $this->lang->line('Required'); ?>" placeholder="<?= $this->lang->line('Masukan'); ?> <?= $this->lang->line('Toleransi TOP'); ?>" id="ttop" name="ttop" maxlength="3" autocomplete="off" value="<?= $data->n_toleransi; ?>" required autofocus>
                                    </div>
                                </div>


                                <div class="d-flex justify-content-start align-items-center">
                                    <button type="submit" class="btn btn-warning round btn-min-width mr-1"><i class="icon-paper-plane mr-1"></i><?= $this->lang->line('Ubah'); ?></button>
                                    <a href="<?= base_url($this->folder); ?>" class="btn btn-secondary round btn-min-width mr-1"><i class="icon-action-undo mr-1"></i><?= $this->lang->line('Kembali'); ?></a>
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