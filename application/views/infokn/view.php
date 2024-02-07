<style>
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
                    <li class="breadcrumb-item"><a href="#" onclick="return false;"><?= $this->lang->line('Gudang'); ?></a></li>
                    <li class="breadcrumb-item active"><a href="<?= base_url($this->folder . '/index/' . $dfrom . '/' . $dto . '/' . $i_area . '/' . $i_customer); ?>"><?= $this->lang->line($this->title); ?></a></li>
                    <li class="breadcrumb-item active">Detail <?= $this->lang->line($this->title); ?></li>
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
                            <input type="hidden" id="path" value="<?= $this->folder; ?>">
                            <h4 class="text-center mb-0"><?= $this->title ?></h4>
                            <h5 class="text-center text-bold-600 mb-0"><?= $i_bbm_id; ?></h5>
                            <h5 class="text-center text-bold-600 mb-0"><?= $e_customer_name; ?></h5>
                            <div class="form-actions">
                                <button type="button" onclick="exporttabel();" class="btn <?= $this->session->e_color; ?> bg-darken-1 text-white btn-min-width"><i class="fa fa-download fa-lg mr-1"></i><?= $this->lang->line("Ekspor"); ?></button>
                            </div>
                            <div><br></div>
                            <div class="table-responsive">
                                <table class="table table-xs display nowrap table-striped table-bordered" id="serverside2" width="100%;">
                                    <thead class="<?= $this->session->e_color; ?> bg-darken-1 text-white">
                                        <tr>
                                            <th>No</th>
                                            <th><?= $this->lang->line('Kode Barang'); ?></th>
                                            <th><?= $this->lang->line('Nama Barang'); ?></th>
                                            <th><?= $this->lang->line('Disk1'); ?></th>
                                            <th><?= $this->lang->line('Disk2'); ?></th>
                                            <th><?= $this->lang->line('Disk3'); ?></th>
                                            <th><?= $this->lang->line('Qty'); ?></th>
                                            <th><?= $this->lang->line('Harga'); ?></th>
                                            <th><?= $this->lang->line('Nomor Pajak'); ?></th>
                                            <th><?= $this->lang->line('Tanggal Pajak'); ?></th>
                                            <th><?= $this->lang->line('Keterangan'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $i = 0;
                                        if ($detail->num_rows() > 0) {
                                            foreach ($detail->result() as $key) {
                                                $i++; ?>
                                                <tr>
                                                    <td class="text-center"><?= $i; ?></td>
                                                    <td><?= $key->i_product_id; ?></td>
                                                    <td><?= $key->e_product_name; ?></td>
                                                    <td><?= $key->n_ttb_discount1; ?></td>
                                                    <td><?= $key->n_ttb_discount2; ?></td>
                                                    <td><?= $key->n_ttb_discount3; ?></td>
                                                    <td><?= $key->n_quantity; ?></td>
                                                    <td class="text-right"><?= $key->v_unit_price; ?></td>
                                                    <td><?= $key->i_seri_pajak; ?></td>
                                                    <td><?= $key->d_pajak; ?></td>
                                                    <td><?= $key->e_remark; ?></td>
                                                </tr>
                                        <?php }
                                        } ?>
                                    </tbody>
                                    <tfoot>
                                    </tfoot>
                                </table>
                            </div>
                            <br>
                            <div class="form-actions">
                                <a href="<?= base_url($this->folder . '/index/' . encrypt_url($dfrom) . '/' . encrypt_url($dto) . '/' . encrypt_url($i_area) . '/' . encrypt_url($i_customer)); ?>" class="btn btn-secondary round btn-min-width mr-1"><i class="icon-action-undo mr-1"></i><?= $this->lang->line("Kembali"); ?></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--/ Alternative pagination table -->
</div>