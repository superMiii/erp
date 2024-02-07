<div class="content-header row">
    <div class="content-header-left col-md-12 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#" onclick="return false;"><?= $this->lang->line('Informasi & Ekspor'); ?></a></li>
                    <li class="breadcrumb-item"><a href="#" onclick="return false;"><?= $this->lang->line('Gudang'); ?></a></li>
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
                                        <div class="col-md-8 row m-auto">
                                        <div class="col-md-4">
                                            <fieldset>
                                                <div class="input-group">
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
                                            </fieldset>
                                        </div>
                                        <div class="col-md-4">
                                            <fieldset>
                                                <div class="input-group">
                                                        <select name="year" id="year" class="form-control select2">
                                                            <?php for ($i = 2021; $i <= date('Y') + 1; $i++) { ?>
                                                                <option value="<?= $i; ?>" <?php if ($year == $i) {
                                                                                                echo "selected";
                                                                                            } ?>><?= $i; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </select>
                                                </div>
                                            </fieldset>
                                        </div>
                                            <div class="col-md-2">
                                                <fieldset>
                                                    <div class="input-group">
                                                        <button class="btn btn-block <?= $this->session->e_color; ?> bg-darken-1 text-white" type="submit"><i class="feather icon-search fa-lg"></i></button>
                                                    </div>
                                                </fieldset>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="form-group row m-auto">
                                    <div class="col-md-12 row m-auto">
                                        
                                        <div class="col-md-6">
                                            <fieldset>
                                                <div class="input-group">
                                                    <button class="btn btn-block closing btn-secondary bg-darken-1 text-white" type="button"><i class="fa fa-hourglass-half fa-spin fa-lg mr-2"></i>CLOSING COA SALDO</button>
                                                </div>
                                            </fieldset>
                                        </div>

                                        <div class="col-md-6">
                                            <fieldset>
                                                <div class="input-group">
                                                    <button class="btn btn-block miru btn-amber bg-darken-1 text-black" type="button"><i class="fa fa-slack fa-spin fa-lg mr-2"></i>UPDATE SALDO AWAL</button>
                                                </div>
                                            </fieldset>
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
                                            <th>COA</th>
                                            <th>periode</th>
                                            <th>coa_name</th>
                                            <th>saldo_awal</th>
                                            <th>mutasi_debet</th>
                                            <th>mutasi_kredit</th>
                                            <th>saldo_akhir</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                    <!-- <tfoot>
                                        <?php
                                        $tot = 0;
                                        if ($data->num_rows() > 0) {
                                            foreach ($data->result() as $key) {
                                                $tot += $key->n_stockopname;
                                        ?>
                                        <?php }
                                        } ?>
                                        <tr>
                                            <th class="text-right" colspan="5"><strong>TOTAL</strong></th>
                                            <th class="text-right"><strong><?= $tot; ?></strong></th>
                                        </tr>

                                    </tfoot> -->
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