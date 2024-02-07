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
                                                <th class="text-center" rowspan="2"><?= $this->lang->line('Kode'); ?></th>
                                                <th class="text-center" rowspan="2"><?= $this->lang->line('Nama Barang'); ?></th>
                                                <th class="text-center" rowspan="2"><?= $this->lang->line('Status Barang'); ?></th>
                                                <th class="text-center" colspan="4"><?= $this->lang->line('HARGA JUAL'); ?></th>
                                            </tr>
                                            <tr>
                                                <?php if ($price->num_rows() > 0) {
                                                    foreach ($price->result() as $key) { ?>
                                                        <th class="text-right"><?= $key->e_price_groupname; ?></th>
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
                                                        <td><?= $key->i_product_id; ?></td>
                                                        <td><?= $key->e_product_name; ?></td>
                                                        <td><?= $key->e_product_statusname; ?></td>
                                                        <?php foreach (json_decode($key->v_price) as $v_price) { ?>
                                                            <td class="text-right">Rp. <?= number_format($v_price); ?></td>
                                                        <?php } ?>
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
    </section>
    <!--/ Alternative pagination table -->
</div>