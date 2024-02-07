<div class="content-header row">
    <div class="content-header-left col-md-12 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#" onclick="return false;"><?= $this->lang->line('Keuangan'); ?></a></li>
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
                                            <fieldset>
                                                <div class="input-group">
                                                    <select class="form-control select2" name="month" id="month" data-placeholder="<?= $this->lang->line("Pilih") . ' ' . $this->lang->line("Bulan"); ?>" required data-validation-required-message="<?= $this->lang->line("Required"); ?>">
                                                        <option value="<?= substr(get_periode(),4,2);?>"><?= bulan(substr(get_periode(),4,2));?></option>
                                                        <!-- <option value="02" <?php /* if (substr(get_periode(),4,2) == '02') { */ ?> selected <?php /* }else{ */?> hidden <?php /* } */ ?>>Februari</option>
                                                        <option value="03" <?php /* if (substr(get_periode(),4,2) == '03') { */ ?> selected <?php /* }else{ */?> hidden <?php /* } */ ?>>Maret</option>
                                                        <option value="04" <?php /* if (substr(get_periode(),4,2) == '04') { */ ?> selected <?php /* }else{ */?> hidden <?php /* } */ ?>>April</option>
                                                        <option value="05" <?php /* if (substr(get_periode(),4,2) == '05') { */ ?> selected <?php /* }else{ */?> hidden <?php /* } */ ?>>Mei</option>
                                                        <option value="06" <?php /* if (substr(get_periode(),4,2) == '06') { */ ?> selected <?php /* }else{ */?> hidden <?php /* } */ ?>>Juni</option>
                                                        <option value="07" <?php /* if (substr(get_periode(),4,2) == '07') { */ ?> selected <?php /* }else{ */?> hidden <?php /* } */ ?>>Juli</option>
                                                        <option value="08" <?php /* if (substr(get_periode(),4,2) == '08') { */ ?> selected <?php /* }else{ */?> hidden <?php /* } */ ?>>Agustus</option>
                                                        <option value="09" <?php /* if (substr(get_periode(),4,2) == '09') { */ ?> selected <?php /* }else{ */?> hidden <?php /* } */ ?>>September</option>
                                                        <option value="10" <?php /* if (substr(get_periode(),4,2) == '10') { */ ?> selected <?php /* }else{ */?> hidden <?php /* } */ ?>>Oktober</option>
                                                        <option value="11" <?php /* if (substr(get_periode(),4,2) == '11') { */ ?> selected <?php /* }else{ */?> hidden <?php /* } */ ?>>November</option>
                                                        <option value="12" <?php /* if (substr(get_periode(),4,2) == '12') { */ ?> selected <?php /* }else{ */?> hidden <?php /* } */ ?>>Desember</option> -->
                                                    </select>
                                                </div>
                                            </fieldset>
                                        </div>
                                        <div class="col-md-4">
                                            <fieldset>
                                                <div class="input-group">
                                                    <select class="form-control select2" name="year" id="year" data-placeholder="<?= $this->lang->line("Pilih") . ' ' . $this->lang->line("Tahun"); ?>" required data-validation-required-message="<?= $this->lang->line("Required"); ?>">
                                                        <!-- <?php
                                                        /* for ($i = 2021; $i <= date('Y'); $i++) { */ ?>
                                                            <option value="<?= $i; ?>" <?php /* if (date('Y') == $i) { */ ?> selected <?php /* } */ ?>><?= $i; ?></option>
                                                            <?php /* } */
                                                        ?> -->
                                                        <option value="<?= substr(get_periode(),0,4); ?>"><?= substr(get_periode(),0,4); ?></option>
                                                    </select>
                                                </div>
                                            </fieldset>
                                        </div>
                                        <div class="col-md-4">
                                            <fieldset>
                                                <div class="input-group">
                                                    <button class="btn btn-block closing <?= $this->session->e_color; ?> bg-darken-1 text-white" type="button"><i class="fa fa-hourglass-half fa-spin fa-lg mr-2"></i>Closing</button>
                                                </div>
                                            </fieldset>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-actions">
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