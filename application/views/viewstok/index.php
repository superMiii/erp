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
                    <div class="card-header header-elements-inline <?= $this->session->e_color;?> bg-darken-1 text-white">
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


                                    <?php if (check_role($this->id_menu, 1)) {
                                        $id_menu = $this->id_menu;
                                    } else {
                                        $id_menu = "";
                                    } ?>
                                    <input type="hidden" id="id_menu" value="<?= $id_menu; ?>">
                                    <input type="hidden" id="path" value="<?= $this->folder; ?>">
                                    <div class="table-responsive">

                                        <div id="serverside_filter" class="dataTables_filter mt-1 ">
                                            <label> <input type="search" id="teangan" name="teangan" value="<?= $teangan; ?>" class="form-control form-control-sm" placeholder="Kode atau Nama Barang" aria-controls="serverside">
                                            </label>
                                            <button type="submit" class="btn <?= $this->session->e_color;?> bg-darken-1 text-white"><?= $this->lang->line("Cari"); ?></button>
                                        </div>
                                        <table class="table table-xs display nowrap table-striped table-bordered" id="serverside" width="100%;">
                                            <thead class="<?= $this->session->e_color;?> bg-darken-1 text-white">
                                                <tr>
                                                    <th>No</th>
                                                    <th><?= $this->lang->line('Kode Barang'); ?></th>
                                                    <th><?= $this->lang->line('Nama Barang'); ?></th>
                                                    <th><?= $this->lang->line('Motif Barang'); ?></th>
                                                    <th><?= $this->lang->line('Stok'); ?></th>
                                                    <th><?= $this->lang->line('Grade Barang'); ?></th>
                                                    <th><?= $this->lang->line('Gudang'); ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if ($data->num_rows() > 0) {
                                                    $i = 1;
                                                    foreach ($data->result() as $key) { ?>
                                                        <tr>
                                                            <td><?= $i; ?></td>
                                                            <td><?= $key->iproduct; ?></td>
                                                            <td><?= $key->ebr; ?></td>
                                                            <td><?= $key->imotif; ?></td>
                                                            <td><?= $key->nstok; ?></td>
                                                            <td><?= $key->igrade; ?></td>
                                                            <td><?= $key->igudang; ?></td>
                                                        </tr>
                                                <?php $i++;
                                                    }
                                                } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--/ Alternative pagination table -->
</div>