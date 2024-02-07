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
</style>

<div class="content-header row">
    <div class="content-header-left col-md-12 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#" onclick="return false;"><?= $this->lang->line('Marketing'); ?></a></li>
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
                                <table class="table table-xs display nowrap text-uppercase table-striped table-bordered font-small-3" id="serverside" width="100%;">
                                    <thead class="<?= $this->session->e_color; ?> bg-darken-1 text-white">
                                        <?php
                                        $totob = 0;
                                        if ($data->num_rows() > 0) {
                                            foreach ($data->result() as $key) {
                                                $totob += $key->ob;
                                            }
                                        } ?>
                                        <tr>
                                            <th colspan="13">TOTAL OB = <?= $totob; ?></th>
                                        </tr>
                                        <tr>
                                            <th class="text-center" rowspan="2">No</th>
                                            <th class="text-center" rowspan="2"><?= $this->lang->line('Merk'); ?></th>
                                            <th class="text-center" colspan="3">OA</th>
                                            <th class="text-center" colspan="3">Sales Qty (Unit)</th>
                                            <th class="text-center" colspan="3">Net Sales (Rp.)</th>
                                            <th class="text-center" rowspan="2">% Ctr <br> Net Sales (Rp.)</th>
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
                                    <tbody class="font-small-2">
                                        <?php
                                        if ($data) {
                                            $no = 1;
                                            $totalob            = 0;
                                            $totaloaprev        = 0;
                                            $totaloa            = 0;
                                            $totalqtyprev       = 0;
                                            $totalqty           = 0;
                                            $totalvnotaprev     = 0;
                                            $totalvnota         = 0;
                                            $totalctrsales      = 0;
                                            $totalnotaberjalan  = 0;
                                            foreach ($data->result() as $key) {
                                                $totalnotaberjalan += $key->vnota;
                                            }


                                            foreach ($data->result() as $row) {
                                                $growthoa    = 0;
                                                $growthqty   = 0;
                                                $growthvnota = 0;

                                                //untuk OA
                                                if ($row->oaprev == 0) {
                                                    $growthoa = 0;
                                                } else {
                                                    $growthoa = (($row->oa - $row->oaprev) / $row->oaprev) * 100;
                                                }

                                                //untuk QTY
                                                if ($row->qtyprev == 0) {
                                                    $growthqty = 0;
                                                } else {
                                                    $growthqty = (($row->qty - $row->qtyprev) / $row->qtyprev) * 100;
                                                }

                                                //untuk Vnota
                                                if ($row->vnotaprev == 0) {
                                                    $growthvnota = 0;
                                                } else {
                                                    $growthvnota = (($row->vnota - $row->vnotaprev) / $row->vnotaprev) * 100;
                                                }
                                                if ($row->vnota == 0) {
                                                    $ctrsales = 0;
                                                } else {
                                                    $ctrsales =  ($row->vnota / $totalnotaberjalan) * 100;
                                                }
                                        ?>
                                                <tr>
                                                    <td class="text-center"><?= $no; ?></td>
                                                    <td><?= $row->e_product_groupname; ?></td>
                                                    <td class="text-right"><?= number_format($row->oaprev); ?></td>
                                                    <td class="text-right"><?= number_format($row->oa); ?></td>
                                                    <td class="text-right"><?= number_format($growthoa, 2); ?>%</td>
                                                    <td class="text-right"><?= number_format($row->qtyprev); ?></td>
                                                    <td class="text-right"><?= number_format($row->qty); ?></td>
                                                    <td class="text-right"><?= number_format($growthqty, 2); ?>%</td>
                                                    <td class="text-right"><?= number_format($row->vnotaprev, 2); ?></td>
                                                    <td class="text-right"><?= number_format($row->vnota, 2); ?></td>
                                                    <td class="text-right"><?= number_format($growthvnota, 2); ?>%</td>
                                                    <td class="text-right"><?= number_format($ctrsales, 2); ?>%</td>
                                                </tr>
                                            <?php
                                                $no++;
                                                $totalob            += $row->ob;
                                                $totaloaprev        += $row->oaprev;
                                                $totaloa            += $row->oa;
                                                $totalqtyprev       += $row->qtyprev;
                                                $totalqty           += $row->qty;
                                                $totalvnotaprev     += $row->vnotaprev;
                                                $totalvnota         += $row->vnota;
                                                $totalctrsales      += $ctrsales;
                                            }
                                            if ($totaloaprev > 0) {
                                                $totalgrowthoa      = (($totaloa - $totaloaprev) / $totaloaprev) * 100;
                                            } else {
                                                $totalgrowthoa      = 0;
                                            }
                                            if ($totalqtyprev > 0) {
                                                $totalgrowthqty     = (($totalqty - $totalqtyprev) / $totalqtyprev) * 100;
                                            } else {
                                                $totalgrowthqty     = 0;
                                            }
                                            if ($totalvnotaprev > 0) {
                                                $totalgrowthvnota = (($totalvnota - $totalvnotaprev) / $totalvnotaprev) * 100;
                                            } else {
                                                $totalgrowthvnota = 0;
                                            }
                                            ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th class="text-center" colspan="2"><b>Total</b></td>
                                            <th class="text-right"><b><?php echo number_format($totaloaprev, 0); ?></b></th>
                                            <th class="text-right"><b><?php echo number_format($totaloa, 0); ?></b></th>
                                            <th class="text-right"><b><?php echo number_format($totalgrowthoa, 2); ?>%</b></th>
                                            <th class="text-right"><b><?php echo number_format($totalqtyprev, 0); ?></b></th>
                                            <th class="text-right"><b><?php echo number_format($totalqty, 0); ?></b></th>
                                            <th class="text-right"><b><?php echo number_format($totalgrowthqty, 2); ?>%</b></th>
                                            <th class="text-right"><b><?php echo number_format($totalvnotaprev, 2); ?></b></th>
                                            <th class="text-right"><b><?php echo number_format($totalvnota, 2); ?></b></th>
                                            <th class="text-right"><b><?php echo number_format($totalgrowthvnota, 2); ?>%</b></th>
                                            <th class="text-right"><b><?php echo number_format($totalctrsales, 2); ?>%</b></th>
                                        </tr>
                                    <?php }
                                    ?>
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