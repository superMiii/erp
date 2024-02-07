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
                                            <div class="col-md-3" hidden>
                                                <div class="form-group">
                                                    <!-- <label><?= $this->lang->line("Bulan"); ?> :</label> -->
                                                    <div class="controls">
                                                        <select name="month" id="month" class="form-control select2">
                                                            <option value="01" <?php if ('01' == $month) {
                                                                                    echo "selected";
                                                                                } ?>>Januari</option>
                                                            <option value="02" <?php if ('02' == $month) {
                                                                                    echo "selected";
                                                                                } ?>>Februari</option>
                                                            <option value="03" <?php if ('03' == $month) {
                                                                                    echo "selected";
                                                                                } ?>>Maret</option>
                                                            <option value="04" <?php if ('04' == $month) {
                                                                                    echo "selected";
                                                                                } ?>>April</option>
                                                            <option value="05" <?php if ('05' == $month) {
                                                                                    echo "selected";
                                                                                } ?>>Mei</option>
                                                            <option value="06" <?php if ('06' == $month) {
                                                                                    echo "selected";
                                                                                } ?>>Juni</option>
                                                            <option value="07" <?php if ('07' == $month) {
                                                                                    echo "selected";
                                                                                } ?>>Juli</option>
                                                            <option value="08" <?php if ('08' == $month) {
                                                                                    echo "selected";
                                                                                } ?>>Agustus</option>
                                                            <option value="09" <?php if ('09' == $month) {
                                                                                    echo "selected";
                                                                                } ?>>September</option>
                                                            <option value="10" <?php if ('10' == $month) {
                                                                                    echo "selected";
                                                                                } ?>>Oktober</option>
                                                            <option value="11" <?php if ('11' == $month) {
                                                                                    echo "selected";
                                                                                } ?>>November</option>
                                                            <option value="12" <?php if ('12' == $month) {
                                                                                    echo "selected";
                                                                                } ?>>Desember</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <!-- <label><?= $this->lang->line("Tahun"); ?> :</label> -->
                                                    <div class="controls">
                                                        <select name="year" id="year" class="form-control select2">
                                                            <?php for ($i = 2021; $i <= date('Y') + 1; $i++) { ?>
                                                                <option value="<?= $i; ?>" <?php if ($year == $i) {
                                                                                                echo "selected";
                                                                                            } ?>><?= $i; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>
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
                                                <fieldset>
                                                    <div class="input-group">
                                                        <select class="form-control" name="i_salesman" id="i_salesman" data-placeholder="<?= $this->lang->line("Pilih") . ' ' . $this->lang->line("Pramuniaga"); ?>" required data-validation-required-message="<?= $this->lang->line("Required"); ?>">
                                                            <option value="<?= $i_salesman; ?>"><?= $e_salesman_name; ?></option>
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
                            <div class="table-responsive">
                                <table class="table table-xs display nowrap table-striped table-bordered" id="serverside" width="100%;">
                                    <thead class="<?= $this->session->e_color; ?> bg-darken-1 text-white">
                                        <tr>
                                            <th rowspan="2">No</th>
                                            <th rowspan="2"><?= $this->lang->line('Periode'); ?></th>
                                            <th rowspan="2"><?= $this->lang->line('Nama Area Provinsi'); ?></th>
                                            <th rowspan="2"><?= $this->lang->line('Nama Pramuniaga'); ?></th>
                                            <th colspan="8" class="text-center"><?= $this->lang->line('Penjualan'); ?></th>
                                            <th colspan="8" class="text-center"><?= $this->lang->line('Collection'); ?></th>
                                        </tr>
                                        <tr>
                                            <th><?= $this->lang->line('Target Penjualan'); ?></th>
                                            <th><?= $this->lang->line('SPB'); ?></th>
                                            <th><?= $this->lang->line('Retur'); ?></th>
                                            <th><?= $this->lang->line('Jumlah Nota'); ?></th>
                                            <th><?= $this->lang->line('%'); ?></th>
                                            <th><?= $this->lang->line('Insentif'); ?></th>
                                            <th><?= $this->lang->line('Pendingan'); ?></th>
                                            <th><?= $this->lang->line('%'); ?></th>
                                            <th><?= $this->lang->line('Target Tagihan'); ?></th>
                                            <th><?= $this->lang->line('Pelunasan Toko'); ?></th>
                                            <th><?= $this->lang->line('%'); ?></th>
                                            <th><?= $this->lang->line('Insentif'); ?></th>
                                            <th><?= $this->lang->line('Nota>90 Hari'); ?></th>
                                            <th><?= $this->lang->line('%'); ?></th>
                                            <th><?= $this->lang->line('Total Insentif'); ?></th>
                                            <th><?= $this->lang->line('Status'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                    <tfoot>
                                        <?php
                                        $A = 0;
                                        $B = 0;
                                        $C = 0;
                                        $D = 0;
                                        $E = 0;
                                        $F = 0;
                                        $G = 0;
                                        if ($data->num_rows() > 0) {
                                            foreach ($data->result() as $key) {
                                                $A += $key->so;
                                                $B += $key->retur;
                                                $C += $key->nota;
                                                $D += $key->sisa;
                                                $E += $key->realisasi;
                                                $F += $key->gohari;
                                                $G += $key->target;
                                        ?>
                                        <?php }
                                        } ?>
                                        <tr>
                                            <th class="text-right" colspan="4"><strong>TOTAL</strong></th>
                                            <th class="text-right"><strong>Rp. <?= number_format($G, 2); ?></strong></th>
                                            <th class="text-right"><strong>Rp. <?= number_format($A, 2); ?></strong></th>
                                            <th class="text-right"><strong>Rp. <?= number_format($B, 2); ?></strong></th>
                                            <th class="text-right"><strong>Rp. <?= number_format($C, 2); ?></strong></th>
                                            <th colspan="4" class="text-right">&nbsp;</th>
                                            <th class="text-right"><strong>Rp. <?= number_format($D, 2); ?></strong></th>
                                            <th class="text-right"><strong>Rp. <?= number_format($E, 2); ?></strong></th>
                                            <th colspan="2" class="text-right">&nbsp;</th>
                                            <th class="text-right"><strong>Rp. <?= number_format($F, 2); ?></strong></th>
                                            <th colspan="3" class="text-right">&nbsp;</th>
                                        </tr>

                                    </tfoot>
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