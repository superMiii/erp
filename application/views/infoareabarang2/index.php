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
                        <h4 class="card-title"><i class="feather icon-list"></i><?= $this->lang->line($this->title); ?></h4>
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
                                            <div class="col-md-5">
                                                <fieldset>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <button class="btn <?= $this->session->e_color; ?> bg-darken-1 text-white" type="button"><i class="feather icon-calendar"></i></button>
                                                        </div>
                                                        <input type="text" required value="<?= $dfrom; ?>" name="dfrom" id="dfrom" class="form-control date" placeholder="Select Date" aria-label="Amount">
                                                    </div>
                                                </fieldset>
                                            </div>
                                            <div class="col-md-5">
                                                <fieldset>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <button class="btn <?= $this->session->e_color; ?> bg-darken-1 text-white" required type="button"><i class="feather icon-calendar"></i></button>
                                                        </div>
                                                        <input type="text" value="<?= $dto; ?>" name="dto" id="dto" class="form-control date" placeholder="Select Date" aria-label="Amount">
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
                            <div class="form-body">

                                <div class="form-actions">
                                    <form action="<?= base_url($this->folder . '/export'); ?>" method="post">
                                        <a href="#" id="href" target="blank" onclick="return exportexcel();" type="button" class="btn <?= $this->session->e_color; ?> bg-darken-1 text-white btn-min-width"><i class="fa fa-download fa-lg mr-1"></i><?= $this->lang->line("Ekspor"); ?></a>
                                    </form>
                                </div>

                                <div><br></div>
                                <div class="table-responsive">
                                    <table class="table table-xs display nowrap table-striped table-bordered" id="serverside" width="100%;">
                                        <thead class="<?= $this->session->e_color; ?> bg-darken-1 text-white">
                                            <tr>
                                                <th rowspan="2" class="text-center" width="5%;">No</th>
                                                <th class="text-center" rowspan="2"><?= $this->lang->line('Kategori'); ?></th>
                                                <th class="text-center" rowspan="2"><?= $this->lang->line('Kode'); ?></th>
                                                <th class="text-center" rowspan="2"><?= $this->lang->line('Nama Barang'); ?></th>
                                                <th class="text-center" colspan="<?= $area->num_rows(); ?>"><?= $this->lang->line('Area'); ?></th>
                                                <th class="text-center" rowspan="2"><?= $this->lang->line('Total'); ?></th>
                                            </tr>
                                            <tr>
                                                <?php if ($area->num_rows() > 0) {
                                                    foreach ($area->result() as $key) { ?>
                                                        <th class="text-right"><?= $key->i_area_id; ?></th>
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
                                                        <td><?= $key->e_product_categoryname; ?></td>
                                                        <td><?= $key->i_product_id; ?></td>
                                                        <td><?= $key->e_product_name; ?></td>
                                                        <?php $x = 0;
                                                        foreach (json_decode($key->n_deliver) as $n_deliver) {
                                                            $x += $n_deliver;
                                                            if ($n_deliver > 0) { ?>
                                                                <td class="text-right"><strong><?= $n_deliver; ?></strong></td>
                                                            <?php } else { ?>
                                                                <td class="text-right"><?= $n_deliver; ?></td>
                                                        <?php }
                                                        } ?>
                                                        <td class="text-right"><strong><?= $x; ?></strong></td>
                                                    </tr>
                                            <?php $i++;
                                                }
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
    <!--/ Alternative pagination table -->
</div>