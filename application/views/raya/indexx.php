<div class="content-header row">
    <div class="content-header-left col-md-12 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#" onclick="return false;"><?= $this->lang->line('Keuangan'); ?></a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url($this->folder); ?>"><?= $this->lang->line($this->title); ?>
                        </a></li>
                    <li class="breadcrumb-item active"><?= $this->lang->line('Daftar') . ' ' . $this->lang->line('Surat Jalan'); ?>
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
                    <div class="card-header header-elements-inline <?= $this->session->e_color;?> bg-darken-1 text-white">
                        <h4 class="card-title"><i class="feather icon-list"></i><?= $this->lang->line('Daftar'); ?> <?= $this->lang->line('Surat Jalan'); ?></h4>
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
                            <form class="form form-horizontal" action="<?= base_url($this->folder . '/indexx'); ?>" method="post">
                                <div class="form-body">
                                    <h6 class="form-section"></h6>
                                    <div class="form-group row m-auto">
                                        <div class="col-md-8 row m-auto">
                                            <div class="col-md-5">
                                                <fieldset>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <button class="btn <?= $this->session->e_color;?> bg-darken-1 text-white" type="button"><i class="feather icon-calendar"></i></button>
                                                        </div>
                                                        <input type="text" readonly required value="<?= $dfrom; ?>" name="dfrom" id="dfrom" class="form-control date" placeholder="Select Date" aria-label="Amount">
                                                    </div>
                                                </fieldset>
                                            </div>
                                            <div class="col-md-5">
                                                <fieldset>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <button class="btn <?= $this->session->e_color;?> bg-darken-1 text-white" required type="button"><i class="feather icon-calendar"></i></button>
                                                        </div>
                                                        <input type="text" readonly value="<?= $dto; ?>" name="dto" id="dto" class="form-control date" placeholder="Select Date" aria-label="Amount">
                                                    </div>
                                                </fieldset>
                                            </div>
                                            <div class="col-md-2">
                                                <fieldset>
                                                    <div class="input-group">
                                                        <button class="btn btn-block <?= $this->session->e_color;?> bg-darken-1 text-white" type="submit"><i class="feather icon-search fa-lg"></i></button>
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
                                    <thead class="<?= $this->session->e_color;?> bg-darken-1 text-white">
                                        <tr>
                                            <th>No</th>
                                            <th><?= $this->lang->line('No Dok SJ'); ?></th>
                                            <th><?= $this->lang->line('Tgl Dok SJ'); ?></th>
                                            <th><?= $this->lang->line('No Dok SPB'); ?></th>
                                            <th><?= $this->lang->line('Tgl Dok SPB'); ?></th>
                                            <th><?= $this->lang->line('No Dok DKB'); ?></th>
                                            <th><?= $this->lang->line('Tgl Dok DKB'); ?></th>
                                            <th><?= $this->lang->line('Nama Pelanggan'); ?></th>
                                            <th><?= $this->lang->line('Nama Area Provinsi'); ?></th>
                                            <th><?= $this->lang->line('Pemenuhan'); ?></th>
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