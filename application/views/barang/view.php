<div class="content-header row">
    <div class="content-header-left col-md-12 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#" onclick="return false;"><?= $this->lang->line('Master'); ?></a></li>
                    <li class="breadcrumb-item"><a href="#" onclick="return false;"><?= $this->lang->line('Barang'); ?></a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url($this->folder); ?>"><?= $this->lang->line($this->title); ?></a></li>
                    <li class="breadcrumb-item active"><?= $this->lang->line('Detail'); ?> <?= $this->lang->line($this->title); ?></li>
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
                        <h4 class="card-title"><i class="icon-eye"></i> <?= $this->lang->line('Detail'); ?> <?= $this->lang->line($this->title); ?></h4>
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
                                <div class="form-body">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label><?= $this->lang->line('Kode Barang'); ?> :</label>
                                                <div class="controls">
                                                    <input type="hidden" value="<?= $data->i_product; ?>" name="i_product">
                                                    <input type="hidden" value="<?= $data->i_product_id; ?>" name="i_product_id_old">
                                                    <input type="text" readonly class="form-control text-uppercase" value="<?= $data->i_product_id; ?>" data-validation-required-message="<?= $this->lang->line('Required'); ?>" placeholder="<?= $this->lang->line('Masukan'); ?> <?= $this->lang->line('Kode Barang'); ?>" id="i_product_id" name="i_product_id" maxlength="100" autocomplete="off" required autofocus>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label><?= $this->lang->line('Nama Barang'); ?> :</label>
                                                <div class="controls">
                                                    <input type="text" readonly class="form-control text-capitalize" value="<?= $data->e_product_name; ?>" data-validation-required-message="<?= $this->lang->line('Required'); ?>" placeholder="<?= $this->lang->line('Masukan'); ?>  <?= $this->lang->line('Nama Barang'); ?>" id="e_product_name" name="e_product_name" maxlength="500" autocomplete="off" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label><?= $this->lang->line("Kategori Barang"); ?> :</label>
                                                <div class="controls">
                                                    <?php if ($category->num_rows() > 0) {
                                                        foreach ($category->result() as $key) {
                                                            $a = $key->e_product_categoryname;
                                                        }
                                                    } ?>
                                                    <input type="text" readonly class="form-control text-capitalize" value="<?= $a; ?>" id="i_product_category" name="i_product_category">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label><?= $this->lang->line("Sub Kategori Barang"); ?> :</label>
                                                <div class="controls">
                                                    <input type="text" readonly class="form-control text-capitalize" value="<?= $data->e_product_subcategoryname; ?>" id="i_product_category" name="i_product_category">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label><?= $this->lang->line("Kelompok Barang"); ?> :</label>
                                                <div class="controls">
                                                    <?php if ($group->num_rows() > 0) {
                                                        foreach ($group->result() as $key) {
                                                            $b = $key->e_product_groupname;
                                                        }
                                                    } ?>
                                                    <input type="text" readonly class="form-control text-capitalize" value="<?= $b; ?>" id="i_product_group" name="i_product_group">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label><?= $this->lang->line("Status Barang"); ?> :</label>
                                                <div class="controls">
                                                    <?php if ($status->num_rows() > 0) {
                                                        foreach ($status->result() as $key) {
                                                            if ($key->i_product_status == $data->i_product_status) {
                                                                $c = $key->e_product_statusname;
                                                            }
                                                        }
                                                    } ?>
                                                    <input type="text" readonly class="form-control text-capitalize" value="<?= $c; ?>" id="i_product_status" name="i_product_status">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label><?= $this->lang->line("Series Barang"); ?> :</label>
                                                <div class="controls">
                                                    <?php if ($series->num_rows() > 0) {
                                                        foreach ($series->result() as $key) {
                                                            if ($key->i_product_series == $data->i_product_series) {
                                                                $d = $key->e_product_seriesname;
                                                            }
                                                        }
                                                    } ?>
                                                    <input type="text" readonly class="form-control text-capitalize" value="<?= $d; ?>" id="i_product_series" name="i_product_series">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label><?= $this->lang->line("Warna Barang"); ?> :</label>
                                                <div class="controls">
                                                    <?php if ($color->num_rows() > 0) {
                                                        foreach ($color->result() as $key) {
                                                            if ($key->i_product_color == $data->i_product_color) {
                                                                $e = $key->e_product_colorname;
                                                            }
                                                        }
                                                    } ?>
                                                    <input type="text" readonly class="form-control text-capitalize" value="<?= $e; ?>" id="i_product_color" name="i_product_color">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label><?= $this->lang->line("Motif Barang"); ?> :</label>
                                                <div class="controls">
                                                    <?php if ($motif->num_rows() > 0) {
                                                        foreach ($motif->result() as $key) {
                                                            if ($key->i_product_motif == $data->i_product_motif) {
                                                                $f = $key->e_product_motifname;
                                                            }
                                                        }
                                                    } ?>
                                                    <input type="text" readonly class="form-control text-capitalize" value="<?= $f; ?>" id="i_product_motif" name="i_product_motif">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-actions">
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