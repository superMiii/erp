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
                            <form class="form form-horizontal" action="<?= base_url($this->folder); ?>" method="post">
                                <div class="form-body">
                                    <h6 class="form-section"></h6>
                                    <div class="form-group row m-auto">
                                        <div class="col-md-12 row m-auto">
                                            <div class="col-md-4">
                                                <fieldset>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <button class="btn <?= $this->session->e_color; ?> bg-darken-1 text-white" type="button"><i class="feather icon-calendar"></i></button>
                                                        </div>
                                                        <input type="text" readonly required value="<?= $dfrom; ?>" name="dfrom" id="dfrom" class="form-control date" placeholder="Select Date" aria-label="Amount">
                                                    </div>
                                                </fieldset>
                                            </div>
                                            <div class="col-md-4">
                                                <fieldset>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <button class="btn <?= $this->session->e_color; ?> bg-darken-1 text-white" required type="button"><i class="feather icon-calendar"></i></button>
                                                        </div>
                                                        <input type="text" readonly value="<?= $dto; ?>" name="dto" id="dto" class="form-control date" placeholder="Select Date" aria-label="Amount">
                                                        <input type="hidden" value="<?= encrypt_url($dfrom); ?>" name="d_from" id="d_from">
                                                        <input type="hidden" value="<?= encrypt_url($dto); ?>" name="d_to" id="d_to">
                                                        <input type="hidden" value="<?= encrypt_url($i_type); ?>" name="htype" id="htype">
                                                        <input type="hidden" value="<?= encrypt_url($i_area); ?>" name="harea" id="harea">
                                                        <input type="hidden" value="<?= encrypt_url($i_coa); ?>" name="hcoa" id="hcoa">
                                                    </div>
                                                </fieldset>
                                            </div>
                                            <div class="col-md-4">
                                                <fieldset>
                                                    <div class="input-group">
                                                        <select class="form-control" name="i_area" id="i_area" data-placeholder="<?= $this->lang->line("Pilih") . ' ' . $this->lang->line("Area"); ?>" required data-validation-required-message="<?= $this->lang->line("Required"); ?>">
                                                            <option value="<?= $i_area; ?>"><?= $e_area_name; ?></option>
                                                        </select>
                                                    </div>
                                                </fieldset>
                                            </div>
                                            <div class="col-md-4">
                                                <br>
                                                <fieldset>
                                                    <div class="input-group">
                                                        <select class="form-control" name="i_rv_type" id="i_rv_type" data-placeholder="<?= $this->lang->line("Pilih") . ' ' . $this->lang->line("Tipe Voucher"); ?>" required data-validation-required-message="<?= $this->lang->line("Required"); ?>">
                                                            <option value="0" <?php if ($i_type == '0') {
                                                                                    echo "selected";
                                                                                } ?>>Semua</option>
                                                            <?php if ($type->num_rows() > 0) {
                                                                foreach ($type->result() as $key) { ?>
                                                                    <option value="<?= $key->i_rv_type; ?>" <?php if ($key->i_rv_type == $i_type) {
                                                                                                                echo "selected";
                                                                                                            } ?>><?= $key->e_rv_type_name; ?></option>
                                                            <?php }
                                                            } ?>
                                                        </select>
                                                    </div>
                                                </fieldset>
                                            </div>
                                            <div class="col-md-6">
                                                <br>
                                                <fieldset>
                                                    <div class="input-group">
                                                        <select class="form-control" name="i_coa" id="i_coa" data-placeholder="<?= $this->lang->line("Pilih") . ' ' . $this->lang->line("COA"); ?>" required data-validation-required-message="<?= $this->lang->line("Required"); ?>">
                                                            <option value="<?= $i_coa; ?>"><?= $e_coa_name; ?></option>
                                                        </select>
                                                    </div>
                                                </fieldset>
                                            </div>
                                            <div class="col-md-2">
                                                <br>
                                                <fieldset>
                                                    <div class="input-group">
                                                        <button class="btn btn-block <?= $this->session->e_color; ?> bg-darken-1 text-white" type="submit"><i class="feather icon-search fa-lg"></i></button>
                                                    </div>
                                                </fieldset>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-actions">
                                </div>
                            </form>
                            <?php if (check_role($this->id_menu, 1)) {
                                $id_menu = $this->id_menu;
                            } else {
                                $id_menu = "";
                            } ?>
                            <input type="hidden" id="id_menu" value="<?= $id_menu; ?>">
                            <input type="hidden" id="path" value="<?= $this->folder; ?>">
                            <div class="table-responsive">
                                <table class="table table-xs display nowrap table-striped table-bordered" id="serverside" width="100%;">
                                    <thead class="<?= $this->session->e_color; ?> bg-darken-1 text-white">
                                        <tr>
                                            <th>No</th>
                                            <th><?= $this->lang->line('Tgl Dokumen'); ?></th>
                                            <th><?= $this->lang->line('No Dokumen'); ?></th>
                                            <th><?= $this->lang->line('Perkiraan'); ?></th>
                                            <th><?= $this->lang->line('Nama Area Provinsi'); ?></th>
                                            <th><?= $this->lang->line('Jumlah'); ?></th>
                                            <th><?= $this->lang->line('Alokasi'); ?></th>
                                            <th><?= $this->lang->line('Status'); ?></th>
                                            <th class="text-center"><i class="fa fa-print fa-lg"></i></th>
                                            <th><?= $this->lang->line('Aksi'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--/ Alternative pagination table -->
</div>