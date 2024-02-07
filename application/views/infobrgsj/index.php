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
                                                <fieldset>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <button class="btn <?= $this->session->e_color; ?> bg-darken-1 text-white" type="button"><i class="feather icon-calendar"></i></button>
                                                        </div>
                                                        <input type="text" required value="<?= $dfrom; ?>" name="dfrom" id="dfrom" class="form-control date" placeholder="Select Date" aria-label="Amount">
                                                    </div>
                                                </fieldset>
                                            </div>
                                            <div class="col-md-3">
                                                <fieldset>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <button class="btn <?= $this->session->e_color; ?> bg-darken-1 text-white" required type="button"><i class="feather icon-calendar"></i></button>
                                                        </div>
                                                        <input type="text" value="<?= $dto; ?>" name="dto" id="dto" class="form-control date" placeholder="Select Date" aria-label="Amount">
                                                    </div>
                                                </fieldset>
                                            </div>
                                            <div class="col-md-4">
                                                <fieldset>
                                                    <div class="input-group">
                                                        <select class="form-control" name="i_product" id="i_product" data-placeholder="<?= $this->lang->line("Pilih") . ' ' . $this->lang->line("Product"); ?>" required data-validation-required-message="<?= $this->lang->line("Required"); ?>">
                                                            <option value="<?= $i_product; ?>"><?= $e_product_name; ?></option>
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
                                                <th class="text-center" rowspan="2"><?= $this->lang->line('Kode'); ?></th>
                                                <th class="text-center" rowspan="2"><?= $this->lang->line('Nama Barang'); ?></th>
                                                <th class="text-center" rowspan="2"><?= $this->lang->line('Status'); ?></th>
                                                <th class="text-center" colspan="<?= $hari->num_rows(); ?>"><?= $this->lang->line('Tanggal'); ?></th>
                                                <th class="text-center" rowspan="2"><?= $this->lang->line('Total'); ?></th>
                                            </tr>
                                            <tr>
                                                <?php if ($hari->num_rows() > 0) {
                                                    foreach ($hari->result() as $key) { ?>
                                                        <th class="text-right"><?= $key->hari; ?></th>
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
                                                        <td><?= explode('|', $key->e_prod)[0]; ?></td>
                                                        <td><?= explode('|', $key->e_prod)[1]; ?></td>
                                                        <td><?= explode('|', $key->e_prod)[2]; ?></td>
                                                        <?php
                                                        $x = 0;
                                                        $c = 1;
                                                        foreach (json_decode($key->krm) as $krm) {
                                                            $x += $krm;
                                                            if ($krm > 0) { ?>
                                                                <td class="text-right <?= 'bln' . $c ?>" data-value="<?= $krm ?>"><strong><?= number_format($krm); ?></strong></td>
                                                            <?php } else { ?>
                                                                <td class="text-right <?= 'bln' . $c ?>" data-value="<?= $krm ?>"><?= number_format($krm); ?></td>
                                                        <?php }
                                                            $c++;
                                                        } ?>
                                                        <td class="text-right"><strong><?= number_format($x); ?></strong></td>
                                                    </tr>
                                            <?php $i++;
                                                }
                                            } ?>
                                        </tbody>
                                        <!-- <tfoot>
                                        <?php
                                        $y = 0;
                                        if ($hari->num_rows() > 0) {
                                            foreach ($hari->result() as $key) {
                                                $y += $key->krm;
                                        ?>
                                        <?php }
                                        } ?>
                                        <tr>
                                            <th class="text-right" colspan="4"><strong>TOTAL</strong></th>
                                            <th class="text-center"><strong><?= number_format($y, 0); ?></strong></th>
                                        </tr>

                                        </tfoot> -->
                                        <!-- <tfoot>
                                            <tr>
                                                <th class="text-right" colspan="4"><strong>TOTAL</strong></th>
                                                <?php if ($data->num_rows() > 0) {
                                                    $sum_id = 1;
                                                    foreach ($data->result() as $key) { ?>
                                                        <th id="tfoot<?= $sum_id ?>" class="text-right">0</th>
                                                <?php
                                                        $sum_id++;
                                                    }
                                                } ?>
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