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
                                            <th>No</th>
                                            <th><?= $this->lang->line('Nama Area Provinsi'); ?></th>
                                            <th><?= $this->lang->line('Pramuniaga'); ?></th>
                                            <th><?= $this->lang->line('Nota'); ?></th>
                                            <th><?= $this->lang->line('Tgl Nota'); ?></th>
                                            <th><?= $this->lang->line('Kode Pelanggan'); ?></th>
                                            <th><?= $this->lang->line('Nama Pelanggan'); ?></th>
                                            <th><?= $this->lang->line('TOP'); ?></th>
                                            <th><?= $this->lang->line('Toleransi'); ?></th>
                                            <th><?= $this->lang->line('Tgl Jatuh Tempo'); ?></th>
                                            <th><?= $this->lang->line('Lama'); ?></th>
                                            <th><?= $this->lang->line('Jumlah Nota'); ?></th>
                                            <th><?= $this->lang->line('Target'); ?></th>
                                            <th><?= $this->lang->line('Realisasi'); ?></th>
                                            <th><?= $this->lang->line('Persentase'); ?></th>
                                            <th><?= $this->lang->line('Keterangan'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                    <tfoot>
                                        <?php
                                        $target = 0;
                                        $nota = 0;
                                        $e = 0;
                                        $persen1 = 0;
                                        if ($data->num_rows() > 0) {
                                            foreach ($data->result() as $key) {
                                                $e += $key->v_nota_netto;
                                                $target += $key->v_target;
                                                $nota += $key->v_realisasi;
                                        ?>
                                        <?php }
                                        }
                                        if ($target == 0) {
                                            $persen1 = 0.00;
                                        } else {
                                            $persen1 = ($nota / $target) * 100;
                                        } 
                                        ?>
                                        <tr>
                                            <th class="text-right" colspan="11"><strong>TOTAL</strong></th>
                                            <th class="text-right"><strong>Rp. <?= number_format($e, 2); ?></strong></th>
                                            <th class="text-right"><strong>Rp. <?= number_format($target, 2); ?></strong></th>
                                            <th class="text-right"><strong>Rp. <?= number_format($nota, 2); ?></strong></th>
                                            <th class="text-right"><?= number_format($persen1, 2); ?> %</th>
                                            <th class="text-right">&nbsp;</th>
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