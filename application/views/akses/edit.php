<div class="content-header row">
    <div class="content-header-left col-md-12 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#" onclick="return false;"><?= $this->lang->line('Setting'); ?></a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url($this->folder); ?>"><?= $this->lang->line($this->title); ?></a></li>
                    <li class="breadcrumb-item active"><?= $this->lang->line('Ubah'); ?> <?= $this->lang->line($this->title); ?></li>
                </ol>
            </div>
        </div>
        <!-- <h3 class="content-header-title mb-0">Basic DataTables</h3> -->
    </div>
</div>
<form class="form-validation" novalidate>
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
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><?= $this->lang->line('Departemen'); ?> :</label>
                                            <div class="controls">
                                                <select class="form-control select2" style="width: 100%" required data-placeholder="<?= $this->lang->line('Departemen'); ?>" data-validation-required-message="<?= $this->lang->line('Required'); ?>" name="idepartment">
                                                    <option value=""></option>
                                                    <?php if ($department->num_rows() > 0) {
                                                        foreach ($department->result() as $key) { ?>
                                                            <option value="<?= $key->i_department; ?>" <?php if ($key->i_department == $i_department) { ?> selected <?php } ?>><?= $key->e_department_name; ?></option>
                                                    <?php }
                                                    } ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><?= $this->lang->line('Level'); ?> :</label>
                                            <div class="controls">
                                                <select class="form-control select2" style="width: 100%" required data-placeholder="<?= $this->lang->line('Level'); ?>" data-validation-required-message="<?= $this->lang->line('Required'); ?>" name="ilevel">
                                                    <option value=""></option>
                                                    <?php if ($level->num_rows() > 0) {
                                                        foreach ($level->result() as $key) { ?>
                                                            <option value="<?= $key->i_level; ?>" <?php if ($key->i_level == $i_level) { ?> selected <?php } ?>><?= $key->e_level_name; ?></option>
                                                    <?php }
                                                    } ?>
                                                </select>
                                            </div>
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
    <div class="content-body">
        <!-- Dual Listbox start -->
        <section class="basic-dual-listbox">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title"><i class="fa fa-th-large"></i> <?= $this->lang->line('Daftar'); ?> Menu</h4>
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
                            <div class="card-body">
                                <div class="form-group">
                                    <select name="imenu[]" multiple="multiple" size="10" class="duallistbox">
                                        <?php if ($menu->num_rows() > 0) {
                                            foreach ($menu->result() as $key) { ?>
                                                <option value="<?= $key->i_menu . '|' . $key->i_power; ?>" <?= $key->selected; ?>><?= $key->i_menu . ' - ' . $key->e_menu . ' [' . $key->e_power_name . ']'; ?></option>
                                        <?php }
                                        } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <div class="content-body">
        <!-- Dual Listbox start -->
        <section class="basic-dual-listbox">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title"><i class="icon-grid"></i> <?= $this->lang->line('Daftar'); ?> Sub Menu</h4>
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
                            <div class="card-body">
                                <div class="form-group">
                                    <select name="isubmenu[]" multiple="multiple" size="10" class="duallistbox" data-validation-required-message="<?= $this->lang->line('Required'); ?>" required>
                                        <?php if ($submenu->num_rows() > 0) {
                                            foreach ($submenu->result() as $key) { ?>
                                                <option value="<?= $key->i_menu . '|' . $key->i_power; ?>" <?= $key->selected; ?>><?= $key->i_menu . ' - ' . $key->e_menu . ' [' . $key->e_power_name . ']'; ?></option>
                                        <?php }
                                        } ?>
                                    </select>
                                </div>
                                <div class="d-flex justify-content-start align-items-center">
                                    <button type="submit" class="btn btn-warning round btn-min-width mr-1"><i class="icon-paper-plane mr-1"></i><?= $this->lang->line('Ubah'); ?></button>
                                    <a href="<?= base_url($this->folder); ?>" class="btn btn-secondary round btn-min-width mr-1"><i class="icon-action-undo mr-1"></i><?= $this->lang->line('Kembali'); ?></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</form>