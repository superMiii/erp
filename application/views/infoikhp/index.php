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
                                                    </div>
                                                </fieldset>
                                            </div>
                                            <div class="col-md-3">
                                                <fieldset>
                                                    <div class="input-group">
                                                        <select class="form-control" name="i_area" id="i_area" data-placeholder="<?= $this->lang->line("Pilih") . ' ' . $this->lang->line("Area"); ?>" required data-validation-required-message="<?= $this->lang->line("Required"); ?>">
                                                            <option value="<?= $i_area; ?>"><?= $i_area_id . ' - ' . $e_area_name; ?></option>
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
                            <div class="form-body">

                                <div class="form-actions">
                                    <form action="<?= base_url($this->folder . '/export'); ?>" method="post">
                                        <a href="#" id="href" onclick="return exportexcel();" type="button" class="btn <?= $this->session->e_color; ?> bg-darken-1 text-white btn-min-width"><i class="fa fa-download fa-lg mr-1"></i><?= $this->lang->line("Ekspor"); ?></a>
                                        <a href='#' type="button" class="btn <?= $this->session->e_color; ?> bg-darken-1 text-white btn-min-width" onclick="openLink2('<?= $this->folder; ?>',' <?= encrypt_url($dfrom); ?>',' <?= encrypt_url($dto); ?>',' <?= encrypt_url($i_area); ?>',); return false;" title='Print Data'><i class="fa fa-print fa-lg mr-1"></i>Cetak</i></a>
                                    </form>
                                </div>

                                <div><br></div>
                                <div class=" table-responsive">
                                    <table class="table table-xs display nowrap table-striped table-bordered" id="serverside" width="100%;">
                                        <thead class="<?= $this->session->e_color; ?> bg-darken-1 text-white">
                                            <tr>
                                                <th class="text-center" rowspan="2">No</th>
                                                <th class="text-center" rowspan="2"><?= $this->lang->line('Tanggal'); ?></th>
                                                <th class="text-center" rowspan="2"><?= $this->lang->line('No Referensi'); ?></th>
                                                <th class="text-center" rowspan="2"><?= $this->lang->line('Uraian'); ?></th>
                                                <th class="text-center" colspan="2"><?= $this->lang->line('Penerimaan'); ?></th>
                                                <th class="text-center" colspan="2"><?= $this->lang->line('Pengeluaran'); ?></th>
                                                <th class="text-center" colspan="2"><?= $this->lang->line('Saldo Akhir'); ?></th>
                                            </tr>
                                            <tr>
                                                <th class="text-center"><?= $this->lang->line('Tunai'); ?></th>
                                                <th class="text-center"><?= $this->lang->line('Giro'); ?></th>
                                                <th class="text-center"><?= $this->lang->line('Tunai'); ?></th>
                                                <th class="text-center"><?= $this->lang->line('Giro'); ?></th>
                                                <th class="text-center"><?= $this->lang->line('Tunai'); ?></th>
                                                <th class="text-center"><?= $this->lang->line('Giro'); ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $i = 0;
                                            $debet_tunai = 0;
                                            $credit_tunai = 0;
                                            $saldo_akhir_tunai = 0;
                                            $debet_giro = 0;
                                            $credit_giro = 0;
                                            $saldo_akhir_giro = 0;
                                            if ($data->num_rows() > 0) {
                                                foreach ($data->result() as $key) {
                                                    $debet_tunai += $key->debet_tunai;
                                                    $credit_tunai += $key->credit_tunai;
                                                    $saldo_akhir_tunai = $key->saldo_akhir_tunai;

                                                    $debet_giro += $key->debet_giro;
                                                    $credit_giro += $key->credit_giro;
                                                    $saldo_akhir_giro = $key->saldo_akhir_giro;

                                                    $i++; ?>
                                                    <tr>
                                                        <td class="text-center"><?= $i; ?></td>
                                                        <td class="text-center"><?= $key->tanggal; ?></td>
                                                        <td class="text-center"><?= $key->kode; ?></td>
                                                        <td><?= $key->e_remark; ?></td>
                                                        <td class="text-right">Rp. <?= number_format($key->debet_tunai, 2); ?></td>
                                                        <td class="text-right">Rp. <?= number_format($key->debet_giro, 2); ?></td>
                                                        <td class="text-right">Rp. <?= number_format($key->credit_tunai, 2); ?></td>
                                                        <td class="text-right">Rp. <?= number_format($key->credit_giro, 2); ?></td>
                                                        <td class="text-right">Rp. <?= number_format($key->saldo_akhir_tunai, 2); ?></td>
                                                        <td class="text-right">Rp. <?= number_format($key->saldo_akhir_giro, 2); ?></td>
                                                    </tr>
                                            <?php }
                                            } ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th class="text-right" colspan="4"><strong>TOTAL</strong></th>
                                                <th class="text-right"><strong>Rp. <?= number_format($debet_tunai, 2); ?></strong></th>
                                                <th class="text-right"><strong>Rp. <?= number_format($debet_giro, 2); ?></strong></th>
                                                <th class="text-right"><strong>Rp. <?= number_format($credit_tunai, 2); ?></strong></th>
                                                <th class="text-right"><strong>Rp. <?= number_format($credit_giro, 2); ?></strong></th>
                                                <th class="text-right"><strong>Rp. <?= number_format($saldo_akhir_tunai, 2); ?></strong></th>
                                                <th class="text-right"><strong>Rp. <?= number_format($saldo_akhir_giro, 2); ?></strong></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                            <!-- <div class="form-actions">
                                <button type="button" id="export" class="btn <?= $this->session->e_color; ?> bg-darken-1 text-white btn-min-width export"><i class="fa fa-download fa-lg mr-1"></i><?= $this->lang->line("Ekspor"); ?></button>
                            </div> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--/ Alternative pagination table -->
</div>