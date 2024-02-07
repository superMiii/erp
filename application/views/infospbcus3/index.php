<style>
    .table.table-xs th,
    .table td,
    .table.table-xs td {
        padding: 0.3rem 0.3rem;
    }

    .table>tfoot>tr>th,
    .table>tfoot>tr>td {
        border: none !important;
    }

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
                    <li class="breadcrumb-item"><a href="#" onclick="return false;"><?= $this->lang->line('Penjualan'); ?></a></li>
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
                                                        <select class="form-control" name="i_customer" id="i_customer" data-placeholder="<?= $this->lang->line("Pilih") . ' ' . $this->lang->line("Pelanggan"); ?>" required data-validation-required-message="<?= $this->lang->line("Required"); ?>">
                                                            <option value="<?= $i_customer; ?>"><?= $e_customer_name; ?></option>
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
                                <div class="form-actions">
                                    <form action="<?= base_url($this->folder . '/export'); ?>" method="post">
                                        <!-- <a href="#" value="Export" target="blank" onclick="return exportexcel();"><input type="button" value="Export" /></a> -->

                                        <a href="#" id="href" target="blank" onclick="return exportexcel();" type="button" class="btn <?= $this->session->e_color; ?> bg-darken-1 text-white btn-min-width"><i class="fa fa-download fa-lg mr-1"></i><?= $this->lang->line("Ekspor"); ?></a>
                                    </form><br>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-xs display nowrap table-striped table-bordered serverside" id="serverside" width="100%;">
                                        <thead class="<?= $this->session->e_color; ?> bg-darken-1 text-white">
                                            <tr>
                                                <th rowspan="2" class="text-center" width="5%;">No</th>
                                                <th class="text-center" rowspan="2"><?= $this->lang->line('Nama Area Provinsi'); ?></th>
                                                <th class="text-center" rowspan="2"><?= $this->lang->line('Nama Pelanggan'); ?></th>
                                                <th class="text-center" rowspan="2"><?= $this->lang->line('Kode Barang'); ?></th>
                                                <th class="text-center" rowspan="2"><?= $this->lang->line('Nama Barang'); ?></th>
                                                <th class="text-center" rowspan="2"><?= $this->lang->line('Harga Terakhir'); ?></th>
                                                <th class="text-center" colspan="<?= $bulan->num_rows(); ?>"><?= $this->lang->line('Bulan'); ?></th>
                                                <th class="text-center" rowspan="2"><?= $this->lang->line('Total'); ?></th>
                                            </tr>
                                            <tr>
                                                <?php if ($bulan->num_rows() > 0) {
                                                    foreach ($bulan->result() as $key) { ?>
                                                        <th class="text-right"><?= $key->bulan; ?></th>
                                                <?php }
                                                } ?>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if ($data->num_rows() > 0) {
                                                $i = 1;
                                                foreach ($data->result() as $key) { ?>
                                                    <tr>
                                                        <td class="text-center"><?= $i; ?></td>
                                                        <td><?= $key->an; ?></td>
                                                        <td><?= $key->cn; ?></td>
                                                        <td><?= $key->mr; ?></td>
                                                        <td><?= $key->pn; ?></td>
                                                        <td><?= $key->latest_v_unit_price; ?></td>
                                                        <?php
                                                        $x = 0;
                                                        $c = 1;
                                                        foreach (json_decode($key->n_deliver) as $n_deliver) {
                                                            $x += $n_deliver;
                                                            if ($n_deliver > 0) { ?>
                                                                <td class="text-right <?= 'bln' . $c ?>" data-value="<?= $n_deliver ?>"><strong><?= number_format($n_deliver); ?></strong></td>
                                                            <?php } else { ?>
                                                                <td class="text-right <?= 'bln' . $c ?>" data-value="<?= $n_deliver ?>"><?= number_format($n_deliver); ?></td>
                                                        <?php }
                                                            $c++;
                                                        } ?>
                                                        <td class="text-right"><strong><?= number_format($x); ?></strong></td>
                                                    </tr>
                                            <?php $i++;
                                                }
                                            } ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th class="text-right" colspan="6"><strong>TOTAL</strong></th>
                                                <?php if ($bulan->num_rows() > 0) {
                                                    $sum_id = 1;
                                                    foreach ($bulan->result() as $key) { ?>
                                                        <th id="tfoot<?= $sum_id ?>" class="text-right">0</th>
                                                <?php
                                                        $sum_id++;
                                                    }
                                                } ?>
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