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
                                            <th colspan="14">TOTAL OB = <?= $totob; ?></th>
                                        </tr>
                                        <tr>
                                            <th class="text-center" rowspan="2">No</th>
                                            <th class="text-center" rowspan="2"><?= $this->lang->line('Kode'); ?></th>
                                            <th class="text-center" rowspan="2"><?= $this->lang->line('Nama Barang'); ?></th>
                                            <th class="text-center" colspan="3">OA</th>
                                            <th class="text-center" colspan="3">Sales Qty (Unit)</th>
                                            <th class="text-center" colspan="3">Net Sales (Rp.)</th>
                                            <th class="text-center" rowspan="2">% Ctr <br> Net Sales (Rp.)</th>
                                            <th class="text-center" rowspan="2">% Ctr <br> Sales Qty </th>
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
                                            $totalob          = 0;
                                            $totaloaprev      = 0;
                                            $totaloa          = 0;
                                            $totalnetitemprev = 0;
                                            $totalnetitem     = 0;
                                            $totalqtyprev     = 0;
                                            $totalqty         = 0;
                                            $totalctrsales    = 0;
                                            $totalctrqty      = 0;
                                            foreach ($data->result() as $key) {
                                                $totalnetitem += $key->netitem;
                                                //$totalqty += $key->jml;
                                                $totalnetitemprev   += $key->netitemprev;
                                            }
                                            //echo "atas :".$totalnetitem."<pre>";
                                            foreach ($data->result() as $row) {
                                                $growthoa    = 0;
                                                $growthjml   = 0;
                                                $growthvnota = 0;

                                                //untuk OA
                                                if ($row->oaprev == 0) {
                                                    $growthoa = 0;
                                                } else {
                                                    $growthoa = (($row->oa - $row->oaprev) / $row->oaprev) * 100;
                                                }

                                                //untuk Qty
                                                if ($row->jmlprev == 0) {
                                                    $growthjml = 0;
                                                } else {
                                                    $growthjml = (($row->jml - $row->jmlprev) / $row->jmlprev) * 100;
                                                }

                                                //untuk net
                                                if ($row->netitemprev == 0) {
                                                    $growthnetitem = 0;
                                                } else {
                                                    $growthnetitem = (($row->netitem - $row->netitemprev) / $row->netitemprev) * 100;
                                                }

                                                $ctrnetsales = ($row->netitem / $totalnetitem) * 100;
                                                if ($totalqty == 0) {
                                                    $ctrqty = 0;
                                                } else {
                                                    $ctrqty = ($row->jml / $totalqty) * 100;
                                                }
                                        ?>
                                                <tr>
                                                    <td class="text-center"><?= $no; ?></td>
                                                    <td><?= $row->i_product_id; ?></td>
                                                    <td><?= $row->e_product_name; ?></td>
                                                    <td class="text-right"><?= number_format($row->oaprev); ?></td>
                                                    <td class="text-right"><?= number_format($row->oa); ?></td>
                                                    <td class="text-right"><?= number_format($growthoa, 2); ?>%</td>
                                                    <td class="text-right"><?= number_format($row->jmlprev); ?></td>
                                                    <td class="text-right"><?= number_format($row->jml); ?></td>
                                                    <td class="text-right"><?= number_format($growthjml, 2); ?>%</td>
                                                    <td class="text-right"><?= number_format($row->netitemprev, 2); ?></td>
                                                    <td class="text-right"><?= number_format($row->netitem, 2); ?></td>
                                                    <td class="text-right"><?= number_format($growthnetitem, 2); ?>%</td>
                                                    <td class="text-right"><?= number_format($ctrnetsales, 2); ?>%</td>
                                                    <td class="text-right"><?= number_format($ctrqty, 2); ?>%</td>
                                                </tr>
                                            <?php
                                                $no++;
                                                $totalob            += $row->ob;
                                                $totaloaprev        += $row->oaprev;
                                                $totaloa            += $row->oa;
                                                $totalqtyprev       += $row->jmlprev;
                                                $totalqty           += $row->jml;
                                                $totalctrsales      += $ctrnetsales;
                                                $totalctrqty        += $ctrqty;
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
                                            if ($totalnetitemprev > 0) {
                                                $totalgrowthnetitem = (($totalnetitem - $totalnetitemprev) / $totalnetitemprev) * 100;
                                            } else {
                                                $totalgrowthnetitem = 0;
                                            }
                                            ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th class="text-center" colspan="3"><b>Total</b></td>
                                            <th class="text-right"><b><?php echo number_format($totaloaprev, 0); ?></b></th>
                                            <th class="text-right"><b><?php echo number_format($totaloa, 0); ?></b></th>
                                            <th class="text-right"><b><?php echo number_format($totalgrowthoa, 2); ?>%</b></th>
                                            <th class="text-right"><b><?php echo number_format($totalqtyprev, 0); ?></b></th>
                                            <th class="text-right"><b><?php echo number_format($totalqty, 0); ?></b></th>
                                            <th class="text-right"><b><?php echo number_format($totalgrowthqty, 2); ?>%</b></th>
                                            <th class="text-right"><b><?php echo number_format($totalnetitemprev, 2); ?></b></th>
                                            <th class="text-right"><b><?php echo number_format($totalnetitem, 2); ?></b></th>
                                            <th class="text-right"><b><?php echo number_format($totalgrowthnetitem, 2); ?>%</b></th>
                                            <th class="text-right"><b><?php echo number_format($totalctrsales, 2); ?>%</b></th>
                                            <th class="text-right"><b><?php echo number_format($totalctrqty, 2); ?>%</b></th>
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