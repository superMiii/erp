<div class="content-header row">
    <div class="content-header-left col-md-12 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#" onclick="return false;"><?= $this->lang->line('Master'); ?></a></li>
                    <li class="breadcrumb-item"><a href="#" onclick="return false;"><?= $this->lang->line('Barang'); ?></a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url($this->folder); ?>"><?= $this->lang->line($this->title); ?></a></li>
                    <li class="breadcrumb-item active"><?= $this->lang->line('Tambah'); ?> <?= $this->lang->line($this->title); ?></li>
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
                        <h4 class="card-title"><i class="icon-plus"></i> <?= $this->lang->line('Tambah'); ?> <?= $this->lang->line($this->title); ?></h4>
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
                                <div class="form-group">
                                    <label><?= $this->lang->line("Kategori Barang"); ?> :</label>
                                    <div class="controls">
                                        <select class="form-control select2" style="width: 100%" required data-placeholder="<?= $this->lang->line("Pilih"); ?> <?= $this->lang->line("Kategori Barang"); ?>" data-validation-required-message="<?= $this->lang->line("Required"); ?>" name="i_product_category">
                                            <option value=""></option>
                                            <?php if ($category->num_rows() > 0) {
                                                foreach ($category->result() as $key) { ?>
                                                    <option value="<?= $key->i_product_category; ?>"><?= $key->e_product_categoryname; ?></option>
                                            <?php }
                                            } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label><?= $this->lang->line('Kode Sub Kategori'); ?> :</label>
                                    <div class="controls">
                                        <input type="text" class="form-control text-uppercase" data-validation-required-message="<?= $this->lang->line('Required'); ?>" placeholder="<?= $this->lang->line('Masukan'); ?> <?= $this->lang->line('Kode Sub Kategori'); ?>" id="i_product_subcategoryid" name="i_product_subcategoryid" maxlength="100" autocomplete="off" required autofocus>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label><?= $this->lang->line('Nama Sub Kategori'); ?> :</label>
                                    <div class="controls">
                                        <input type="text" class="form-control text-capitalize" data-validation-required-message="<?= $this->lang->line('Required'); ?>" placeholder="<?= $this->lang->line('Masukan'); ?>  <?= $this->lang->line('Nama Sub Kategori'); ?>" id="e_product_subcategoryname" name="e_product_subcategoryname" maxlength="500" autocomplete="off" required>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-start align-items-center">
                                    <button type="submit" class="btn btn-success round btn-min-width mr-1"><i class="icon-paper-plane mr-1"></i><?= $this->lang->line('Simpan'); ?></button>
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