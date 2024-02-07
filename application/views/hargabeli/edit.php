<div class="content-header row">
    <div class="content-header-left col-md-12 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#" onclick="return false;"><?= $this->lang->line('Master'); ?></a></li>
                    <li class="breadcrumb-item"><a href="#" onclick="return false;"><?= $this->lang->line('Barang'); ?></a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url($this->folder); ?>"><?= $this->lang->line($this->title); ?></a></li>
                    <li class="breadcrumb-item active"><?= $this->lang->line('Ubah'); ?> <?= $this->lang->line($this->title); ?></li>
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
                        <h4 class="card-title"><i class="icon-pencil"></i> <?= $this->lang->line('Ubah'); ?> <?= $this->lang->line($this->title); ?></h4>
                        <input type="hidden" id="path" value="<?= $this->folder; ?>">
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
                            <form class="form-validation" novalidate>


                                <input type="hidden" name="i_supplier_price" value="<?= $data->i_supplier_price; ?>">
                                <div class="form-group">
                                    <label><?= $this->lang->line("Pemasok"); ?> :</label>
                                    <input type="hidden" id="textcari" value="<?= $this->lang->line('Pilih'); ?> <?= $this->lang->line('Pemasok'); ?>">
                                    <div class="controls">
                                        <input type="hidden" name="isupplierold" value="<?= $data->i_supplier; ?>">
                                        <select class="form-control round" id="isupplier" required data-validation-required-message="<?= $this->lang->line("Required"); ?>" name="isupplier">
                                            <option value="<?= $data->i_supplier; ?>"><?= $data->i_supplier_id . ' - ' . $data->e_supplier_name; ?></option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label><?= $this->lang->line("Barang"); ?> :</label>
                                    <input type="hidden" id="textcari_barang" value="<?= $this->lang->line('Pilih'); ?> <?= $this->lang->line('Barang'); ?>">
                                    <div class="controls">
                                        <input type="hidden" name="iproductold" value="<?= $data->i_product; ?>">
                                        <select class="form-control round" id="iproduct" required data-validation-required-message="<?= $this->lang->line("Required"); ?>" name="iproduct">
                                            <option value="<?= $data->i_product; ?>"><?= $data->i_product_id . ' - ' . $data->e_product_name; ?></option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label><?= $this->lang->line('Harga'); ?> :</label>
                                    <div class="controls">
                                        <input type="text" class="form-control formatrupiah" data-validation-required-message="<?= $this->lang->line('Required'); ?>" placeholder="<?= $this->lang->line('Masukan'); ?> <?= $this->lang->line('Harga'); ?>" id="v_price" name="v_price" autocomplete="off" required autofocus value="<?= number_format($data->v_price, 2, '.', ','); ?>">
                                    </div>
                                </div>
                                <div class="d-flex justify-content-start align-items-center">
                                    <button type="submit" class="btn btn-warning round btn-min-width mr-1"><i class="icon-paper-plane mr-1"></i><?= $this->lang->line('Ubah'); ?></button>
                                    <a href="<?= base_url($this->folder); ?>" class="btn btn-secondary round btn-min-width mr-1"><i class="icon-action-undo mr-1"></i><?= $this->lang->line('Kembali'); ?></a>
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