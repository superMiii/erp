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
                    <li class="breadcrumb-item"><a href="#" onclick="return false;"><?= $this->lang->line('Pembelian'); ?></a></li>
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
                            <div class="form-body">
                                <div class="form-actions">
                                    <form action="<?= base_url($this->folder . '/export'); ?>" method="post">
                                        <!-- <button type="button" id="export" class="btn <?= $this->session->e_color; ?> bg-darken-1 text-white btn-min-width"><i class="fa fa-download fa-lg mr-1"></i><?= $this->lang->line("Ekspor"); ?></button> -->
                                        <a href="#" id="href" target="blank" onclick="return exportexcel();" type="button" class="btn <?= $this->session->e_color; ?> bg-darken-1 text-white btn-min-width"><i class="fa fa-download fa-lg mr-1"></i><?= $this->lang->line("Ekspor"); ?></a>
                                    </form>
                                </div>
                                <div><br></div>
                                <div class="table-responsive">
                                    <table class="table table-xs display nowrap table-striped table-bordered" id="serversideq" width="100%;">
                                        <thead class="<?= $this->session->e_color; ?> bg-darken-1 text-white">
                                            <tr>
                                                <th class="text-center" width="5%;">No</th>
                                                <th><?= $this->lang->line('Kode'); ?></th>
                                                <th><?= $this->lang->line('Nama Barang'); ?></th>
                                                <?php if ($price->num_rows() > 0) {
                                                    foreach ($price->result() as $key) { ?>
                                                        <th class="text-right"><?= $key->e_price_groupname; ?></th>
                                                <?php }
                                                } ?>
                                                <th><?= $this->lang->line('Harga Beli'); ?></th>
                                                <th><?= $this->lang->line('Kelompok Barang'); ?></th>
                                                <th><?= $this->lang->line('Kategori Barang'); ?></th>
                                                <th><?= $this->lang->line('Sub Kategori Barang'); ?></th>
                                                <th><?= $this->lang->line('Tgl Daftar'); ?></th>
                                                <th><?= $this->lang->line('Grade Barang'); ?></th>
                                                <th><?= $this->lang->line('Status'); ?></th>
                                                <th><?= $this->lang->line('Status Barang'); ?></th>
                                                <th><?= $this->lang->line('Kode Pemasok'); ?></th>
                                                <th><?= $this->lang->line('Nama Pemasok'); ?></th>
                                                <th><?= $this->lang->line('Stok'); ?></th>
                                                <th><?= $this->lang->line('Kelompok Pemasok'); ?></th>
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
                                                        <?php foreach (json_decode($key->v_price) as $v_price) { ?>
                                                            <td class="text-right">Rp. <?= number_format($v_price); ?></td>
                                                        <?php } ?>
                                                        <td>Rp. <?= number_format($key->v_price2); ?></td>
                                                        <td><?= $key->e_product_groupname; ?></td>
                                                        <td><?= $key->e_product_categoryname; ?></td>
                                                        <td><?= $key->e_product_subcategoryname; ?></td>
                                                        <td><?= $key->d_product_entry; ?></td>
                                                        <td><?= $key->e_product_gradename; ?></td>
                                                        <td><?= $key->f_product_active; ?></td>
                                                        <td><?= $key->e_product_statusname; ?></td>
                                                        <td><?= $key->i_supplier_id; ?></td>
                                                        <td><?= $key->e_supplier_name; ?></td>
                                                        <td><?= $key->n_quantity_stock; ?></td>
                                                        <td><?= $key->e_supplier_groupname; ?></td>
                                                    </tr>
                                            <?php $i++;
                                                }
                                            } ?>
                                        </tbody>
                                    </table>
                                    <table class="table table-xs display nowrap table-striped table-bordered" hidden id="serverside" width="100%;">
                                        <thead class="<?= $this->session->e_color; ?> bg-darken-1 text-white">
                                            <tr>
                                                <th class="text-center" width="5%;">No</th>
                                                <th><?= $this->lang->line('Kode'); ?></th>
                                                <th><?= $this->lang->line('Nama Barang'); ?></th>
                                                <?php if ($price->num_rows() > 0) {
                                                    foreach ($price->result() as $key) { ?>
                                                        <th class="text-right"><?= $key->e_price_groupname; ?></th>
                                                <?php }
                                                } ?>
                                                <th><?= $this->lang->line('Harga Beli'); ?></th>
                                                <th><?= $this->lang->line('Kelompok Barang'); ?></th>
                                                <th><?= $this->lang->line('Kategori Barang'); ?></th>
                                                <th><?= $this->lang->line('Sub Kategori Barang'); ?></th>
                                                <th><?= $this->lang->line('Tgl Daftar'); ?></th>
                                                <th><?= $this->lang->line('Grade Barang'); ?></th>
                                                <th><?= $this->lang->line('Status'); ?></th>
                                                <th><?= $this->lang->line('Status Barang'); ?></th>
                                                <th><?= $this->lang->line('Kode Pemasok'); ?></th>
                                                <th><?= $this->lang->line('Nama Pemasok'); ?></th>
                                                <th><?= $this->lang->line('Stok'); ?></th>
                                                <th><?= $this->lang->line('Kelompok Pemasok'); ?></th>
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
                                                        <?php foreach (json_decode($key->v_price) as $v_price) { ?>
                                                            <td class="text-right"><?= $v_price; ?></td>
                                                        <?php } ?>
                                                        <td><?= $key->v_price2; ?></td>
                                                        <td><?= $key->e_product_groupname; ?></td>
                                                        <td><?= $key->e_product_categoryname; ?></td>
                                                        <td><?= $key->e_product_subcategoryname; ?></td>
                                                        <td><?= $key->d_product_entry; ?></td>
                                                        <td><?= $key->e_product_gradename; ?></td>
                                                        <td><?= $key->f_product_active; ?></td>
                                                        <td><?= $key->e_product_statusname; ?></td>
                                                        <td><?= $key->i_supplier_id; ?></td>
                                                        <td><?= $key->e_supplier_name; ?></td>
                                                        <td><?= $key->n_quantity_stock; ?></td>
                                                        <td><?= $key->e_supplier_groupname; ?></td>
                                                    </tr>
                                            <?php $i++;
                                                }
                                            } ?>
                                        </tbody>
                                    </table>
                                    <input type="hidden" id="path" value="<?= $this->folder; ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </section>
    <!--/ Alternative pagination table -->
</div>