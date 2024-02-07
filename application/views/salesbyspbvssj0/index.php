<style>
    .table.table-xs th,
    .table td,
    .table.table-xs td {
        padding: 0.3rem 0.3rem;
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
                                <table class="table table-xs display nowrap table-striped text-capitalize table-bordered font-small-3" id="serverside" width="100%;">
                                    <thead class="<?= $this->session->e_color; ?> bg-darken-1 text-white">
                                        <tr>
                                            <!-- <th class="text-center"><?= $this->lang->line('Kodelang'); ?></th> -->
                                            <th><?= $this->lang->line('Pelanggan'); ?></th>
                                            <th class="text-center"><?= $this->lang->line('Status'); ?></th>
                                            <!-- <th><?= $this->lang->line('Kode Area'); ?></th> -->
                                            <th><?= $this->lang->line('Nama Area Provinsi'); ?></th>
                                            <th><?= $this->lang->line('Kota'); ?></th>
                                            <th class="text-center"><?= $this->lang->line('No SPB'); ?></th>
                                            <th class="text-center"><?= $this->lang->line('Tgl SPB'); ?></th>
                                            <th class="text-center"><?= $this->lang->line('Tgl App SPB'); ?></th>
                                            <th class="text-right"><?= str_replace('\r\n', "<br>", $this->lang->line('SPBApp')); ?></th>
                                            <th class="text-center"><?= $this->lang->line('No SJ'); ?></th>
                                            <th class="text-center"><?= $this->lang->line('Tgl SJ'); ?></th>
                                            <th class="text-center"><?= str_replace('\r\n', "<br>", $this->lang->line('SPBSJ')); ?></th>
                                            <th class="text-center"><?= $this->lang->line('No Nota'); ?></th>
                                            <th class="text-center"><?= $this->lang->line('Tgl Nota'); ?></th>
                                            <th class="text-right"><?= str_replace('\r\n', "<br>", $this->lang->line('SJNota')); ?></th>
                                            <th class="text-right"><?= str_replace('\r\n', "<br>", $this->lang->line('SPBNota')); ?></th>
                                            <!-- <th class="text-center" ><?= $this->lang->line('Tgl Terima Toko'); ?></th>
                                            <th class="text-center" ><?= $this->lang->line('NotaTerima Toko'); ?></th>
                                            <th class="text-center" ><?= $this->lang->line('SPBTerima Toko'); ?></th> -->
                                            <th class="text-right"><?= $this->lang->line('Nilai Penjualan'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody class="font-small-2">
                                        <?php
                                        $total = 0;
                                        if ($data) {
                                            foreach ($data->result() as $row) { ?>
                                                <tr>
                                                    <!-- <td class="text-center"><?= $row->i_customer_id; ?></td> -->
                                                    <td><?= '[' . $row->i_customer_id . '] - ' . $row->e_customer_name; ?></td>
                                                    <td><?= $row->e_customer_statusname; ?></td>
                                                    <!-- <td class="text-center"><?= $row->i_area_id; ?></td> -->
                                                    <td><?= '[' . $row->i_area_id . '] - ' . $row->e_area_name; ?></td>
                                                    <td><?= $row->e_city_name; ?></td>
                                                    <td class="text-center"><?= $row->i_so_id; ?></td>
                                                    <td class="text-center"><?= $row->d_so; ?></td>
                                                    <td class="text-center"><?= $row->d_approve; ?></td>
                                                    <td class="text-right"><?= $row->so_approve_hari; ?></td>
                                                    <td class="text-center"><?= $row->i_do_id; ?></td>
                                                    <td class="text-center"><?= $row->d_do; ?></td>
                                                    <td class="text-right"><?= $row->so_sj_hari; ?></td>
                                                    <td class="text-center"><?= $row->i_nota_id; ?></td>
                                                    <td class="text-center"><?= $row->d_nota; ?></td>
                                                    <td class="text-right"><?= $row->sj_nota_hari; ?></td>
                                                    <td class="text-right"><?= $row->so_nota_hari; ?></td>
                                                    <td class="text-right">Rp. <?= number_format($row->v_nota_netto); ?></td>
                                                </tr>
                                        <?php
                                                $total += $row->v_nota_netto;
                                            }
                                        } ?>
                                    </tbody>
                                    <tfoot>
                                        <th colspan="15" class="text-center">TOTAL</th>
                                        <th class="text-right">Rp. <?= number_format($total); ?></th>
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