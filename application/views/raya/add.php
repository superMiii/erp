<div class="content-header row">
    <div class="content-header-left col-md-12 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#" onclick="return false;"><?= $this->lang->line('Setting'); ?></a></li>
                        <li class="breadcrumb-item"><a href="<?= base_url($this->folder . '/index/' . encrypt_url($dfrom) . '/' . encrypt_url($dto) . '/' . encrypt_url($i_store)); ?>"><?= $this->lang->line($this->title); ?></a></li>
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
                            <input type="hidden" id="d_from" value="<?= encrypt_url($dfrom); ?>">
                            <input type="hidden" id="d_to" value="<?= encrypt_url($dto); ?>">
                            <input type="hidden" id="hsup" value="<?= encrypt_url($i_store); ?>">
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
                                    <label><?= $this->lang->line("Stok"); ?> :</label>
                                    <div class="controls">
                                        <input type="number" class="form-control" data-validation-required-message="<?= $this->lang->line("Required"); ?>" placeholder="<?= $this->lang->line("Stok"); ?>" id="Stok" name="Stok" maxlength="30" autocomplete="off" required autofocus>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label><?= $this->lang->line("Kode Barang"); ?> :</label>
                                    <div class="controls">
                                        <select class="form-control" data-placeholder="Pilih Kode Barang" id="iproduct" required data-validation-required-message="<?= $this->lang->line("Required"); ?>" name="iproduct">
                                            <option value=""></option>

                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label><?= $this->lang->line("Nama Barang"); ?> :</label>
                                    <div class="controls">
                                        <input type="text" readonly class="form-control text-capitalize" data-validation-required-message="<?= $this->lang->line("Required"); ?>" placeholder="<?= $this->lang->line("Nama Barang"); ?>" id="ebr" name="ebr" maxlength="100" autocomplete="off" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label><?= $this->lang->line("Motif Barang"); ?> :</label>
                                    <div class="controls">
                                        <select class="form-control select2" data-placeholder="Pilih Motif" id="imotif" required data-validation-required-message="<?= $this->lang->line("Required"); ?>" name="imotif">
                                            <option value=""></option>
                                            <?php if ($imotif->num_rows() > 0) {
                                                foreach ($imotif->result() as $key) { ?>
                                                    <option value="<?= $key->i_product_motif; ?>"><?= $key->e_product_motifname; ?></option>
                                            <?php }
                                            } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label><?= $this->lang->line("Grade Barang"); ?> :</label>
                                    <div class="controls">
                                        <select class="form-control select2" data-placeholder="Pilih Grade" id="igrade" required data-validation-required-message="<?= $this->lang->line("Required"); ?>" name="igrade">
                                            <option value=""></option>
                                            <?php if ($igrade->num_rows() > 0) {
                                                foreach ($igrade->result() as $key) { ?>
                                                    <option value="<?= $key->i_product_grade; ?>"><?= $key->e_product_gradename; ?></option>
                                            <?php }
                                            } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label><?= $this->lang->line("Gudang"); ?> :</label>
                                    <div class="controls">
                                        <select class="form-control select2" data-placeholder="Pilih Gudang" id="igudang" required data-validation-required-message="<?= $this->lang->line("Required"); ?>" name="igudang">
                                            <option value=""></option>
                                            <?php if ($igudang->num_rows() > 0) {
                                                foreach ($igudang->result() as $key) { ?>
                                                    <option value="<?= $key->i_store; ?>"><?= $key->e_store_name; ?></option>
                                            <?php }
                                            } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label><?= $this->lang->line("Lokasi Gudang"); ?> :</label>
                                    <div class="controls">
                                        <select class="form-control" id="istorloc" required data-validation-required-message="<?= $this->lang->line("Required"); ?>" name="istorloc">
                                            <option value=""></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-start align-items-center">
                                    <button type="submit" id="submit" class="btn btn-success round btn-min-width mr-1"><i class="icon-paper-plane mr-1"></i><?= $this->lang->line("Simpan"); ?></button>
                                    <a href="<?= base_url($this->folder . '/index/' . encrypt_url($dfrom) . '/' . encrypt_url($dto) . '/' . encrypt_url($i_store)); ?>" class="btn btn-secondary round btn-min-width mr-1"><i class="icon-action-undo mr-1"></i><?= $this->lang->line("Kembali"); ?></a>
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