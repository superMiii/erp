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
<?php echo form_open_multipart('Pengguna/edit') ?>
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
<div class="content-body">

    <!-- Alternative pagination table -->
    <section id="pagination">
        <div class="row">
            <div class="col-12">
                <div class="card box-shadow-2">
                    <div class="card-header header-elements-inline <?= $this->session->e_color; ?> bg-darken-1 text-white">
                        <h4 class="card-title"><i class="icon-plus"></i> <?= $this->lang->line('Ubah'); ?> <?= $this->lang->line($this->title); ?></h4>
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
                                            <input type="hidden" id="i_user" name="i_user" value="<?= $data->i_user; ?>">
                                            <input type="hidden" id="i_user_id_old" name="i_user_id_old" value="<?= $data->i_user_id; ?>">
                                            <input type="text" class="form-control text-lowercase" value="<?= $data->i_user_id; ?>" data-validation-required-message="<?= $this->lang->line("Required"); ?>" placeholder="<?= $this->lang->line("Id Pengguna"); ?>" id="i_user_id" name="i_user_id" maxlength="100" autocomplete="off" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label><?= $this->lang->line("Nama Pengguna"); ?> :</label>
                                        <div class="controls">
                                            <input type="text" class="form-control text-capitalize" value="<?= $data->e_user_name; ?>" data-validation-required-message="<?= $this->lang->line("Required"); ?>" placeholder="<?= $this->lang->line("Nama Pengguna"); ?>" id="e_user_name" name="e_user_name" maxlength="500" autocomplete="off" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label><?= $this->lang->line("Kata Sandi"); ?> :</label>
                                        <div class="controls">
                                            <input type="password" id="passwordkr" name="password" class="form-control" value="<?= decrypt_password($data->e_user_password); ?>" required data-validation-required-message="<?= $this->lang->line("Required"); ?>" placeholder="<?= $this->lang->line("Kata Sandi"); ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label><?= $this->lang->line("Ulangi Kata Sandi"); ?> :</label>
                                        <div class="controls">
                                            <input type="password" id="passwordyz" name="password2" value="<?= decrypt_password($data->e_user_password); ?>" data-validation-match-match="password" data-validation-required-message="<?= $this->lang->line("Required"); ?>" class="form-control" required placeholder="<?= $this->lang->line("Ulangi Kata Sandi"); ?>">
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
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <div class="controls">
                                            <fieldset>
                                                <div class="float-left">
                                                    <input type="checkbox" <?php if ($data->f_pusat == 'DAERAH') {
                                                                                echo "checked";
                                                                            } ?> id="f_pusat" name="f_pusat" class="switch" data-group-cls="btn-group-sm" data-on-label="<?= $this->lang->line('Daerah'); ?>" data-off-label="<?= $this->lang->line('Pusat'); ?>" data-switch-always />
                                                </div>
                                            </fieldset>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Upload Foto:</label>
                                        <div class="controls">

                                            <input type="hidden" id="fotoold" name="fotoold" value="<?= $data->ava; ?>">
                                            <input type="file" accept="image/png, image/gif, image/jpeg" name="fotonew" class="form-control" placeholder="fotonew">

                                        </div>
                                    </div>
                                </div>


                                <div class="col-sm-6">
                                    <div class="form-group">

                                        <img src="<?php echo base_url(); ?>assets/images/avatar/<?= $data->ava; ?>" width="90" height="90">

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
                            <table class="table table-lg table-column table-bordered" id="tablecover">
                                <thead class="<?= $this->session->e_color; ?> bg-darken-1 font-medium-3 text-white">
                                    <tr>
                                        <th class="text-center" width="5%">No</th>
                                        <th width="50%"><?= $this->lang->line("Departemen"); ?></th>
                                        <th width="40%"><?= $this->lang->line("Level"); ?></th>
                                        <th class="text-center" width="5%"><i class="fa fa-plus-circle fa-2x" title="<?= $this->lang->line('Tambah'); ?>" id="addrow"></i></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 0;
                                    if ($detail->num_rows() > 0) {
                                        foreach ($detail->result() as $key) {
                                            $i++; ?>
                                            <tr>
                                                <td class="text-center">
                                                    <spanx id="snum<?= $i; ?>"><?= $i; ?></spanx>
                                                </td>
                                                <td>
                                                    <select data-nourut="<?= $i; ?>" required class="form-control" name="i_departemen<?= $i; ?>" id="i_departemen<?= $i; ?>">
                                                        <option value="<?= $key->i_department; ?>"><?= $key->e_department_name; ?></option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <select data-urut="<?= $i; ?>" required class="form-control" multiple name="i_level<?= $i; ?>[]" id="i_level<?= $i; ?>">
                                                        <?php foreach (json_decode($key->level) as $i_level) { ?>
                                                            <option value="<?= explode("|", $i_level)[0]; ?>" selected><?= explode("|", $i_level)[1]; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </td>
                                                <td class="text-center"><i title="Delete" class="fa fa-minus-circle fa-2x text-danger ibtnDel"></i></td>
                                            </tr>
                                    <?php }
                                    } ?>
                                </tbody>
                                <input type="hidden" id="jml" name="jml" value="<?= $i; ?>">
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
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
                            <div class="form-body">
                                <div class="table-responsive">
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
                                        <tbody>
                                            <?php $ii = 0;
                                            if ($detaill->num_rows() > 0) {
                                                foreach ($detaill->result() as $row) {
                                                    $ii++; ?>
                                                    <tr>
                                                        <td class="text-center">
                                                            <spanx id="snum<?= $ii; ?>"><?= $ii; ?></spanx>
                                                        </td>
                                                        <td>
                                                            <select data-nourut="<?= $ii; ?>" required class="form-control" name="i_company<?= $ii; ?>" id="i_company<?= $ii; ?>">
                                                                <option value="<?= $row->i_company; ?>"><?= $row->e_company_name; ?></option>
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <select data-urut="<?= $ii; ?>" required class="form-control" multiple name="i_area<?= $ii; ?>[]" id="i_area<?= $ii; ?>">
                                                                <?php
                                                                if (is_array(json_decode($row->area)) || is_object(json_decode($row->area))) {
                                                                    foreach (json_decode($row->area) as $i_area) { ?>
                                                                        <option value="<?= explode("|", $i_area)[0]; ?>" selected><?= explode("|", $i_area)[1]; ?></option>
                                                                <?php }
                                                                } ?>
                                                            </select>
                                                        </td>
                                                        <td class="text-center">
                                                            <fieldset>
                                                                <div class="float-center"><input type="checkbox" onchange="cek(<?= $ii; ?>);" id="f_all_area<?= $ii; ?>" class="switch" data-onstyle="success" data-on-label="Ya" data-off-label="Tidak" data-switch-always /><input type="hidden" id="f_checked<?= $ii; ?>" name="f_checked<?= $ii; ?>" value="f" /></div>
                                                            </fieldset>
                                                        </td>
                                                        <td>
                                                            <select data-urutrv="<?= $ii; ?>" class="form-control" multiple name="i_rv_type<?= $ii; ?>[]" id="i_rv_type<?= $ii; ?>">
                                                                <?php
                                                                if (is_array(json_decode($row->rv)) || is_object(json_decode($row->rv))) {
                                                                    foreach (json_decode($row->rv) as $i_rv) { ?>
                                                                        <option value="<?= explode("|", $i_rv)[0]; ?>" selected><?= explode("|", $i_rv)[1]; ?></option>
                                                                <?php }
                                                                } ?>
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <select data-urutpv="<?= $ii; ?>" class="form-control" multiple name="i_pv_type<?= $ii; ?>[]" id="i_pv_type<?= $ii; ?>">
                                                                <?php
                                                                if (is_array(json_decode($row->pv)) || is_object(json_decode($row->pv))) {
                                                                    foreach (json_decode($row->pv) as $i_pv) { ?>
                                                                        <option value="<?= explode("|", $i_pv)[0]; ?>" selected><?= explode("|", $i_pv)[1]; ?></option>
                                                                <?php }
                                                                } ?>
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <select data-urutst="<?= $ii; ?>" class="form-control" multiple name="i_store<?= $ii; ?>[]" id="i_store<?= $ii; ?>">
                                                                <?php
                                                                if (is_array(json_decode($row->st)) || is_object(json_decode($row->st))) {
                                                                    foreach (json_decode($row->st) as $i_st) { ?>
                                                                        <option value="<?= explode("|", $i_st)[0]; ?>" selected><?= explode("|", $i_st)[1]; ?></option>
                                                                <?php }
                                                                } ?>
                                                            </select>
                                                        </td>
                                                        <td class="text-center"><i title="Delete" class="fa fa-minus-circle fa-2x text-danger ibtnDel"></i></td>
                                                    </tr>
                                            <?php }
                                            } ?>
                                        </tbody>
                                        <input type="hidden" id="jmlx" name="jmlx" value="<?= $ii; ?>">
                                    </table>
                                </div>
                            </div>
                            <div class="form-actions">
                                <button type="submit" class="btn btn-warning round btn-min-width mr-1"><i class="icon-paper-plane mr-1"></i><?= $this->lang->line("Ubah"); ?></button>
                                <a href="<?= base_url($this->folder); ?>" class="btn btn-secondary round btn-min-width mr-1"><i class="icon-action-undo mr-1"></i><?= $this->lang->line("Kembali"); ?></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<!-- </form> -->
<?php echo form_close(); ?>