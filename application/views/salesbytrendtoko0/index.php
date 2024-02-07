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
                                        <tr>
                                            <th class="text-center" rowspan="2"><?= $this->lang->line('Nama Area Provinsi'); ?></th>
                                            <th class="text-center" rowspan="2"><?= $this->lang->line('Kodelang'); ?></th>
                                            <th class="text-center" rowspan="2"><?= $this->lang->line('Pelanggan'); ?></th>
                                            <!-- <th class="text-center" rowspan="2">OB</th> -->
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
                                    <?php
                                    if ($data) {
                                        $totvnota = 0;
                                        $totqnota = 0;
                                        foreach ($data->result() as $ro) {
                                            $totvnota = $totvnota + $ro->vnota;
                                            $totqnota = $totqnota + $ro->qnota;
                                        }
                                    }
                                    ?>
                                    <tbody class="font-small-2">
                                        <?php
                                        if ($data) {
                                            $grwoa = 0;
                                            $grwqty = 0;
                                            $grwrp = 0;
                                            $totnota = 0;
                                            $totrp = 0;
                                            $ctrrp = 0;
                                            $totprevoa = 0;
                                            $totoa = 0;
                                            $totprevqnota = 0;
                                            $totprevvnota = 0;
                                            $totctrrp = 0;
                                            $totgrwoa = 0;
                                            $totgrwqty = 0;
                                            $totgrwrp = 0;
                                            $totpersenvnota = 0;
                                            $totob = 0;

                                            foreach ($data->result() as $row) {
                                                $totnota += $row->vnota;
                                            }
                                            $totrp = $totnota;


                                            foreach ($data->result() as $row) {

                                                if ($totvnota == 0) {
                                                    $persenvnota = 0;
                                                } else {
                                                    $persenvnota = ($row->vnota / $totvnota) * 100;
                                                }
                                                $totpersenvnota = $totpersenvnota + $persenvnota;

                                                if ($row->prevoa == 0) {
                                                    $grwoa = 0;
                                                } else { //jika pembagi tidak 0
                                                    $grwoa = (($row->oa - $row->prevoa) / $row->prevoa) * 100;
                                                }

                                                if ($row->prevqnota == 0) {
                                                    $grwqty = 0;
                                                } else { //jika pembagi tidak 0
                                                    $grwqty = (($row->qnota - $row->prevqnota) / $row->prevqnota) * 100;
                                                }

                                                if ($row->prevvnota == 0) {
                                                    $grwrp = 0;
                                                } else { //jika pembagi tidak 0
                                                    $grwrp = (($row->vnota - $row->prevvnota) / $row->prevvnota) * 100;
                                                }

                                                $totob = $totob + $row->ob;
                                                $ctrrp = ($row->vnota / $totrp) * 100;
                                                $totprevoa = $totprevoa + $row->prevoa;
                                                $totoa = $totoa + $row->oa;
                                                $totprevqnota = $totprevqnota + $row->prevqnota;
                                                $totprevvnota = $totprevvnota + $row->prevvnota;
                                                $totctrrp = $totctrrp + $ctrrp;; ?>
                                                <tr>
                                                    <td><?= $row->e_area_name; ?></td>
                                                    <td><?= $row->i_customer_id; ?></td>
                                                    <td><?= $row->e_customer_name; ?></td>
                                                    <!-- <td class="text-right"><?= number_format($row->ob); ?></td> -->
                                                    <td class="text-right"><?= number_format($row->prevoa); ?></td>
                                                    <td class="text-right"><?= number_format($row->oa); ?></td>
                                                    <td class="text-right"><?= number_format($grwoa, 2); ?></td>
                                                    <td class="text-right"><?= number_format($row->prevqnota); ?></td>
                                                    <td class="text-right"><?= number_format($row->qnota); ?></td>
                                                    <td class="text-right"><?= number_format($grwqty, 2); ?></td>
                                                    <td class="text-right">Rp. <?= number_format($row->prevvnota); ?></td>
                                                    <td class="text-right">Rp. <?= number_format($row->vnota); ?></td>
                                                    <td class="text-right"><?= number_format($grwrp, 2); ?></td>
                                                    <td class="text-right"><?= number_format($ctrrp, 2); ?></td>
                                                </tr>
                                            <?php                          }

                                            if ($totprevoa == 0) {
                                                $totgrwoa = 0;
                                            } else { //jika pembagi tidak 0
                                                $totgrwoa = (($totoa - $totprevoa) / $totprevoa) * 100;
                                            }

                                            if ($totprevqnota == 0) {
                                                $totgrwqty = 0;
                                            } else { //jika pembagi tidak 0
                                                $totgrwqty = (($totqnota - $totprevqnota) / $totprevqnota) * 100;
                                            }

                                            if ($totprevvnota == 0) {
                                                $totgrwrp = 0;
                                            } else { //jika pembagi tidak 0
                                                $totgrwrp = (($totvnota - $totprevvnota) / $totprevvnota) * 100;
                                            } ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th class="text-center" colspan="3"><b>Total</b></td>
                                                <!-- <th class="text-right"><b><?= number_format($totob); ?></b></th> -->
                                            <th class="text-right"><b><?= number_format($totprevoa); ?></b></th>
                                            <th class="text-right"><b><?= number_format($totoa); ?></b></th>
                                            <th class="text-right"><b><?= number_format($totgrwoa, 2); ?> %</b></th>
                                            <th class="text-right"><b><?= number_format($totprevqnota); ?></b></th>
                                            <th class="text-right"><b><?= number_format($totqnota); ?></b></th>
                                            <th class="text-right"><b><?= number_format($totgrwqty, 2); ?> %</b></th>
                                            <th class="text-right"><b><?= number_format($totprevvnota); ?></b></th>
                                            <th class="text-right"><b><?= number_format($totvnota); ?></b></th>
                                            <th class="text-right"><b><?= number_format($totgrwrp, 2); ?> %</b></th>
                                            <th class="text-right"><b><?= number_format($totctrrp, 2); ?> %</b></th>
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