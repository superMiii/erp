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
                        <li class="breadcrumb-item active"><?= $this->lang->line('Ubah'); ?><?= $this->lang->line($this->title); ?></li>
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
                                            <label><?= $this->lang->line('Kode Area Cakupan'); ?> :</label>
                                            <div class="controls">
                                                <input type="hidden" id="i_area_cover" name="i_area_cover" value="<?= $data->i_area_cover; ?>">
                                                <input type="hidden" id="i_area_cover_id_old" name="i_area_cover_id_old" value="<?= $data->i_area_cover_id; ?>">
                                                <input type="text" value="<?= $data->i_area_cover_id; ?>" class="form-control text-uppercase" data-validation-required-message="<?= $this->lang->line('Required'); ?>" placeholder="<?= $this->lang->line('Masukan'); ?> <?= $this->lang->line('Kode Area Cakupan'); ?>" id="i_area_cover_id" name="i_area_cover_id" maxlength="30" autocomplete="off" required autofocus>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label><?= $this->lang->line('Nama Area Cakupan'); ?> :</label>
                                            <div class="controls">
                                                <input type="text" value="<?= $data->e_area_cover_name; ?>" class="form-control text-capitalize" data-validation-required-message="<?= $this->lang->line('Required'); ?>" placeholder="<?= $this->lang->line('Masukan'); ?> <?= $this->lang->line('Nama Area Cakupan'); ?>" id="e_area_cover_name" name="e_area_cover_name" maxlength="100" autocomplete="off" required autofocus>
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
        <!--/ Alternative pagination table -->
    </div>
    <div class="content-body">
        <!-- Alternative pagination table -->
        <section id="pagination">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header header-elements-inline <?= $this->session->e_color; ?> bg-darken-1 text-white">
                            <h4 class="card-title"><i class="feather icon-grid"></i> <?= $this->lang->line("Detail"); ?> <?= $this->lang->line("Company"); ?> & <?= $this->lang->line("Area"); ?></h4>
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
                                                    <th width="70%"><?= $this->lang->line("Nama Kota / Kabupaten"); ?></th>
                                                    <th width="15%"><?= $this->lang->line("Semua Kota"); ?></th>
                                                    <th class="text-center" width="5%"><i class="fa fa-plus-circle fa-2x" title="<?= $this->lang->line('Ubah'); ?>" id="addriw"></i></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $ii = 0;
                                                if ($detail->num_rows() > 0) {
                                                    foreach ($detail->result() as $row) {
                                                        $ii++; ?>
                                                        <tr>
                                                            <td class="text-center">
                                                                <spanx id="snum<?= $ii; ?>"><?= $ii; ?></spanx>
                                                            </td>
                                                            <td>
                                                                <select data-nourut="<?= $ii; ?>" required class="form-control" name="i_area<?= $ii; ?>" id="i_area<?= $ii; ?>">
                                                                    <option value="<?= $row->i_area; ?>"><?= '(' . $row->i_area_id . ') - ' . $row->e_area_name; ?></option>
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <select data-urut="<?= $ii; ?>" required class="form-control" multiple name="i_city<?= $ii; ?>[]" id="i_city<?= $ii; ?>">
                                                                    <?php foreach (json_decode($row->city) as $i_city) { ?>
                                                                        <option value="<?= explode("|", $i_city)[0]; ?>" selected><?= explode("|", $i_city)[1]; ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </td>
                                                            <td class="text-center">
                                                                <fieldset>
                                                                    <div class="float-center"><input type="checkbox" onchange="cek(<?= $ii; ?>);" id="f_all_city<?= $ii; ?>" class="switch" data-onstyle="success" data-on-label="Ya" data-off-label="Tidak" data-switch-always /><input type="hidden" id="f_checked<?= $ii; ?>" name="f_checked<?= $ii; ?>" value="f" /></div>
                                                                </fieldset>
                                                            </td>
                                                            <td class="text-center"><i title="Delete" class="fa fa-minus-circle fa-2x text-danger ibtnDel"></i></td>
                                                        </tr>
                                                <?php }
                                                } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="form-actions">
                                        <input type="hidden" id="jmlx" name="jmlx" value="<?= $ii; ?>">
                                        <button type="submit" id="submit" class="btn btn-warning round btn-min-width mr-1"><i class="icon-paper-plane mr-1"></i><?= $this->lang->line("Ubah"); ?></button>
                                        <a href="<?= base_url($this->folder); ?>" class="btn btn-secondary round btn-min-width mr-1"><i class="icon-action-undo mr-1"></i><?= $this->lang->line("Kembali"); ?></a>
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