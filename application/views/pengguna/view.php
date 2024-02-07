<form class="form-validation" novalidate>
    <div class="content-header row">
        <div class="content-header-left col-md-12 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#" onclick="return false;"><?= $this->lang->line('Setting'); ?></a></li>
                        <li class="breadcrumb-item"><a href="<?= base_url($this->folder); ?>"><?= $this->lang->line($this->title); ?></a></li>
                        <li class="breadcrumb-item active"><?= $this->lang->line('Detail'); ?> <?= $this->lang->line($this->title); ?></li>
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
                    <div class="card box-shadow-2">
                        <div class="card-header header-elements-inline <?= $this->session->e_color; ?> bg-darken-1 text-white">
                            <h4 class="card-title"><i class="feather icon-eye"></i> <?= $this->lang->line("Detail"); ?> <?= $this->lang->line("Pengguna"); ?></h4>
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
                                <table class="table table-sm table-column table-bordered" id="tablecover">
                                    <thead>
                                        <tr>
                                            <th width="50%;"><?= $this->lang->line("Id Pengguna"); ?></th>
                                            <th><?= $data->i_user_id; ?></th>
                                        </tr>
                                        <tr>
                                            <th><?= $this->lang->line("Nama Pengguna"); ?></th>
                                            <th><?= $data->e_user_name; ?></th>
                                        </tr>
                                        <tr>
                                            <th><?= $this->lang->line("Cabang"); ?></th>
                                            <th><?= $data->f_pusat; ?></th>
                                        </tr>
                                        <tr>
                                            <th><?= $this->lang->line("Status"); ?></th>
                                            <?php $aktif = ($data->f_status == 't') ? $this->lang->line('Aktif') : $this->lang->line('Tidak Aktif'); ?>
                                            <?php $color = ($data->f_status == 't') ? "success" : "danger"; ?>
                                            <th><span class="badge badge-<?= $color; ?>"><?= $aktif; ?></span></th>
                                        </tr>
                                        <tr>
                                            <th><?= $this->lang->line("Dibuat"); ?></th>
                                            <th><?= date('d-m-Y H:i:s', strtotime($data->d_user_entry)); ?></th>
                                        </tr>
                                        <tr>
                                            <th><?= $this->lang->line("Diubah"); ?></th>
                                            <th><?= date('d-m-Y H:i:s', strtotime($data->d_user_update)); ?></th>
                                        </tr>
                                        <tr>
                                            <td colspan="2" class="text-center">
                                                <img src="<?php echo base_url(); ?>assets/images/avatar/<?= $data->ava; ?>" width="90" height="90">
                                            </td>
                                        </tr>
                                    </thead>
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
                                <div class="table-responsive">
                                    <table class="table table-sm table-column table-bordered">
                                        <thead class="<?= $this->session->e_color; ?> bg-darken-1 text-white">
                                            <tr>
                                                <th class="text-center" width="5%">No</th>
                                                <th width="50%"><?= $this->lang->line("Departemen"); ?></th>
                                                <th width="40%"><?= $this->lang->line("Level"); ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $i = 0;
                                            if ($detail->num_rows() > 0) {
                                                foreach ($detail->result() as $key) {
                                                    $i++; ?>
                                                    <tr>
                                                        <td class="text-center"><?= $i; ?></td>
                                                        <td><?= $key->e_department_name; ?></td>
                                                        <td>
                                                            <?php $i = 0;
                                                            foreach (json_decode($key->level) as $i_level) {
                                                                $i++; ?>
                                                                <?= $i . '. ' . explode("|", $i_level)[1]; ?><br>
                                                            <?php } ?>
                                                        </td>
                                                    </tr>
                                            <?php }
                                            } ?>
                                        </tbody>
                                    </table>
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
                                    <table class="table table-sm table-column table-bordered" id="tablecoverarea">
                                        <thead class="<?= $this->session->e_color; ?> bg-darken-1 text-white">
                                            <tr>
                                                <th class="text-center" width="5%">No</th>
                                                <th><?= $this->lang->line("Company"); ?></th>
                                                <th><?= $this->lang->line("Area"); ?></th>
                                                <th><?= $this->lang->line("Kas / Bank Masuk"); ?></th>
                                                <th><?= $this->lang->line("Kas / Bank Keluar"); ?></th>
                                                <th><?= $this->lang->line("Gudang"); ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $ii = 0;
                                            if ($detaill->num_rows() > 0) {
                                                foreach ($detaill->result() as $row) {
                                                    $ii++; ?>
                                                    <tr>
                                                        <td class="text-center"><?= $ii; ?></td>
                                                        <td><?= $row->e_company_name; ?></td>
                                                        <td>
                                                            <?php $i = 0;
                                                            if (is_array(json_decode($row->area)) || is_object(json_decode($row->area))) {
                                                                foreach (json_decode($row->area) as $area) {
                                                                    $i++; ?>
                                                                    <?= $i . '. ' . explode("|", $area)[1]; ?><br>
                                                            <?php }
                                                            } ?>
                                                        </td>
                                                        <td>
                                                            <?php $i = 0;
                                                            if (is_array(json_decode($row->rv)) || is_object(json_decode($row->rv))) {
                                                                foreach (json_decode($row->rv) as $rv) {
                                                                    $i++; ?>
                                                                    <?= $i . '. ' . explode("|", $rv)[1]; ?><br>
                                                            <?php }
                                                            } ?>
                                                        </td>
                                                        <td>
                                                            <?php $i = 0;
                                                            if (is_array(json_decode($row->pv)) || is_object(json_decode($row->pv))) {
                                                                foreach (json_decode($row->pv) as $pv) {
                                                                    $i++; ?>
                                                                    <?= $i . '. ' . explode("|", $pv)[1]; ?><br>
                                                            <?php }
                                                            } ?>
                                                        </td>
                                                        <td>
                                                            <?php $i = 0;
                                                            if (is_array(json_decode($row->st)) || is_object(json_decode($row->st))) {
                                                                foreach (json_decode($row->st) as $st) {
                                                                    $i++; ?>
                                                                    <?= $i . '. ' . explode("|", $st)[1]; ?><br>
                                                            <?php }
                                                            } ?>
                                                        </td>
                                                    </tr>
                                            <?php }
                                            } ?>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="form-actions">
                                    <a href="<?= base_url($this->folder); ?>" class="btn btn-secondary round btn-min-width mr-1"><i class="icon-action-undo mr-1"></i><?= $this->lang->line("Kembali"); ?></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</form>