<style>
    .nowrap {
        white-space: nowrap !important;
    }
</style>
<div class="content-header row">
    <div class="content-header-left col-md-12 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#" onclick="return false;"><?= $this->lang->line('Informasi & Ekspor'); ?></a></li>
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
                                            <div class="col-md-3">
                                                <fieldset>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <button class="btn <?= $this->session->e_color; ?> bg-darken-1 text-white" type="button"><i class="feather icon-calendar"></i></button>
                                                        </div>
                                                        <input type="text" readonly required value="<?= $dfrom; ?>" name="dfrom" id="dfrom" class="form-control date" placeholder="Select Date" aria-label="Amount">
                                                    </div>
                                                </fieldset>
                                            </div>
                                            <div class="col-md-3">
                                                <fieldset>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <button class="btn <?= $this->session->e_color; ?> bg-darken-1 text-white" required type="button"><i class="feather icon-calendar"></i></button>
                                                        </div>
                                                        <input type="text" readonly value="<?= $dto; ?>" name="dto" id="dto" class="form-control date" placeholder="Select Date" aria-label="Amount">
                                                    </div>
                                                </fieldset>
                                            </div>
                                            <div class="col-md-2">
                                                <fieldset>
                                                    <div class="input-group">
                                                        <select class="form-control" name="i_area" id="i_area" data-placeholder="<?= $this->lang->line("Pilih") . ' ' . $this->lang->line("Area"); ?>" required data-validation-required-message="<?= $this->lang->line("Required"); ?>">
                                                            <option value="<?= $i_area; ?>"><?= $e_area_name; ?></option>
                                                        </select>
                                                    </div>
                                                </fieldset>
                                            </div>
                                            <div class="col-md-3">
                                                <fieldset>
                                                    <div class="input-group">
                                                        <select class="form-control" name="i_coa" id="i_coa" data-placeholder="<?= $this->lang->line("Pilih") . ' ' . $this->lang->line("Perkiraan"); ?>" required data-validation-required-message="<?= $this->lang->line("Required"); ?>">
                                                            <option value="<?= $i_coa; ?>"><?= $e_coa_name; ?></option>
                                                        </select>
                                                    </div>
                                                </fieldset>
                                            </div>
                                            <div class="col-md-1">
                                                <fieldset>
                                                    <div class="input-group">
                                                        <button class="btn btn-block <?= $this->session->e_color; ?> bg-darken-1 text-white" type="submit"><i class="feather icon-search fa-lg"></i></button>
                                                    </div>
                                                </fieldset>
                                            </div>
                                            <br>
                                        </div>
                                    </div>
                                        <!-- <br>
                                        <div class="col-md-8 row m-auto">
                                            <div class="col-md-8">
                                                <fieldset>
                                                    <div class="input-group">
                                                        <select class="form-control" name="i_coa2" id="i_coa2" data-placeholder="<?= $this->lang->line("Pilih"); ?> Rincian COA" >
                                                            <option value="<?= $i_coa2; ?>"><?= $e_coa_name2; ?></option>
                                                        </select>
                                                    </div>
                                                </fieldset>
                                            </div>
                                        </div> -->
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
                            <div class="form-body">
                                <div class="form-actions">
                                    <button type="button" id="export" class="btn <?= $this->session->e_color; ?> bg-darken-1 text-white btn-min-width export"><i class="fa fa-download fa-lg mr-1"></i><?= $this->lang->line("Ekspor"); ?></button>
                                </div><br>
                                <div class="table-responsive">
                                    <table class="table table-xs display nowrap table-striped table-bordered" id="serverside" width="100%;">
                                        <thead class="<?= $this->session->e_color; ?> bg-darken-1 text-white">
                                            <tr>
                                                <th>No</th>
                                                <th><?= $this->lang->line('No Referensi'); ?></th>
                                                <th><?= $this->lang->line('Nama Area Provinsi'); ?></th>
                                                <th><?= $this->lang->line('Tanggal Referensi'); ?></th>
                                                <th><?= $this->lang->line('Tanggal Bukti'); ?></th>
                                                <th><?= $this->lang->line('Keterangan'); ?></th>
                                                <th><?= $this->lang->line('Kode Perkiraan'); ?></th>
                                                <th><?= $this->lang->line('Perkiraan'); ?></th>
                                                <th class="text-right"><?= $this->lang->line('Debet'); ?></th>
                                                <th class="text-right"><?= $this->lang->line('Kredit'); ?></th>
                                                <th class="text-right"><?= $this->lang->line('Saldo'); ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $i = 0;
                                            $debet = 0;
                                            $credit = 0;
                                            $belance = 0;
                                            if ($data->num_rows() > 0) {
                                                foreach ($data->result() as $key) {
                                                    $debet += $key->debet;
                                                    $credit += $key->credit;
                                                    $belance = $key->belance;

                                                    $i++; ?>
                                                    <tr>
                                                        <td class="text-center"><?= $i; ?></td>
                                                        <td class="text-center"><?= $key->kode; ?></td>
                                                        <td class="text-center"><?= $key->araa; ?></td>
                                                        <td class="text-center"><?= $key->tanggal; ?></td>
                                                        <td class="text-center"><?= $key->tanggal_bukti; ?></td>
                                                        <td><?= $key->e_remark; ?></td>
                                                        <td class="text-center"><?= $key->i_coa_id; ?></td>
                                                        <td><?= $key->e_coa_name; ?></td>
                                                        <td class="text-right">Rp. <?= number_format($key->debet, 2); ?></td>
                                                        <td class="text-right">Rp. <?= number_format($key->credit, 2); ?></td>
                                                        <td class="text-right">Rp. <?= number_format($key->belance, 2); ?></td>
                                                    </tr>
                                            <?php }
                                            } ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th class="text-right" colspan="8"><strong>TOTAL</strong></th>
                                                <th class="text-right"><strong>Rp. <?= number_format($debet, 2); ?></strong></th>
                                                <th class="text-right"><strong>Rp. <?= number_format($credit, 2); ?></strong></th>
                                                <th class="text-right"><strong>Rp. <?= number_format($belance, 2); ?></strong></th>
                                            </tr>
                                        </tfoot>
                                    </table>
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