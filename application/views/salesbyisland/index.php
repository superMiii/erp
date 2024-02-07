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
                                                        <input type="text" readonly required value="<?= $dfrom; ?>" name="dfrom" id="dfrom" class="form-control date" placeholder="Select Date" aria-label="Amount">
                                                    </div>
                                                </fieldset>
                                            </div>
                                            <div class="col-md-5">
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
                                            <th class="text-center" rowspan="2">No</th>
                                            <th class="text-center" rowspan="2"><?= $this->lang->line('Pulau'); ?></th>
                                            <th class="text-center" rowspan="2">OB</th>
                                            <th class="text-center" colspan="3">OA</th>
                                            <th class="text-center" colspan="3">Sales Qty(Unit)</th>
                                            <th class="text-center" colspan="3">Net Sales(Rp.)</th>
                                            <th class="text-center" rowspan="2">%Ctr Net Sales(Rp.)</th>
                                        </tr>
                                        <tr>
                                            <?php $prevth = $tahun - 1; ?>
                                            <th class="text-center"><?php echo $prevth ?></th>
                                            <th class="text-center"><?php echo $tahun ?></th>
                                            <th class="text-center">%</th>
                                            <th class="text-center"><?php echo $prevth ?></th>
                                            <th class="text-center"><?php echo $tahun ?></th>
                                            <th class="text-center">%</th>
                                            <th class="text-center"><?php echo $prevth ?></th>
                                            <th class="text-center"><?php echo $tahun ?></th>
                                            <th class="text-center">%</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                    <tfoot>
                                        <?php                                        
                                        $b1 = 0;
                                        $b2 = 0;
                                        $b3 = 0;
                                        $b4 = 0;
                                        $b5 = 0;
                                        $v5 = 0;
                                        $v6 = 0;
                                        if ($data->num_rows() > 0) {
                                            foreach ($data->result() as $key) {
                                                $b1 += $key->ob;
                                                $b2 += $key->oa;
                                                $b3 += $key->oaa;
                                                $b4 += $key->krm;
                                                $b5 += $key->krmm;
                                                $v5 += $key->yui;
                                                $v6 += $key->yuii;
                                        ?>
                                        <?php }
                                        } ?>
                                        <tr>
                                            <th class="text-right" colspan="2"><strong>TOTAL</strong></th>
                                            <th class="text-center"><?= number_format($b1); ?></th>
                                            <th class="text-center"><?= number_format($b2); ?></th>
                                            <th class="text-center"><?= number_format($b3); ?></th>
                                            <th class="text-right"></th>
                                            <th class="text-center"><?= number_format($b4); ?></th>
                                            <th class="text-center"><?= number_format($b5); ?></th>
                                            <th class="text-right"></th>
                                            <th class="text-right"><strong>Rp. <?= number_format($v5, 0); ?></strong></th>
                                            <th class="text-right"><strong>Rp. <?= number_format($v6, 0); ?></strong></th>
                                            <th class="text-right" colspan="2"></th>
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