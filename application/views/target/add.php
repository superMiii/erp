<style>
    .table td,
    .table.table-sm td {
        padding: 0.4rem 0.4rem;
    }

    .table>tfoot>tr>th,
    .table>tfoot>tr>td {
        border: none !important;
    }
</style>
<form class="form-validation" novalidate>
    <div class="content-header row">
        <div class="content-header-left col-md-12 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#" onclick="return false;"><?= $this->lang->line('Penjualan'); ?></a></li>
                        <li class="breadcrumb-item"><a href="<?= base_url($this->folder); ?>"><?= $this->lang->line($this->title); ?></a></li>
                        <li class="breadcrumb-item active"><?= $this->lang->line('Tambah'); ?> <?= $this->lang->line($this->title); ?></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="content-body">
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
                                <div class="row">
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Bulan"); ?> :</label>
                                            <div class="controls">
                                                <select name="month" id="month" class="form-control select2">
                                                    <option value="01" <?php if ('01' == date('m')) {
                                                                            echo "selected";
                                                                        } ?>>Januari</option>
                                                    <option value="02" <?php if ('02' == date('m')) {
                                                                            echo "selected";
                                                                        } ?>>Februari</option>
                                                    <option value="03" <?php if ('03' == date('m')) {
                                                                            echo "selected";
                                                                        } ?>>Maret</option>
                                                    <option value="04" <?php if ('04' == date('m')) {
                                                                            echo "selected";
                                                                        } ?>>April</option>
                                                    <option value="05" <?php if ('05' == date('m')) {
                                                                            echo "selected";
                                                                        } ?>>Mei</option>
                                                    <option value="06" <?php if ('06' == date('m')) {
                                                                            echo "selected";
                                                                        } ?>>Juni</option>
                                                    <option value="07" <?php if ('07' == date('m')) {
                                                                            echo "selected";
                                                                        } ?>>Juli</option>
                                                    <option value="08" <?php if ('08' == date('m')) {
                                                                            echo "selected";
                                                                        } ?>>Agustus</option>
                                                    <option value="09" <?php if ('09' == date('m')) {
                                                                            echo "selected";
                                                                        } ?>>September</option>
                                                    <option value="10" <?php if ('10' == date('m')) {
                                                                            echo "selected";
                                                                        } ?>>Oktober</option>
                                                    <option value="11" <?php if ('11' == date('m')) {
                                                                            echo "selected";
                                                                        } ?>>November</option>
                                                    <option value="12" <?php if ('12' == date('m')) {
                                                                            echo "selected";
                                                                        } ?>>Desember</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Tahun"); ?> :</label>
                                            <div class="controls">
                                                <select name="year" id="year" class="form-control select2">
                                                    <?php for ($i = 2021; $i <= date('Y') + 1; $i++) { ?>
                                                        <option value="<?= $i; ?>" <?php if (date('Y') == $i) {
                                                                                        echo "selected";
                                                                                    } ?>><?= $i; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Nama Area Provinsi"); ?> :</label>
                                            <div class="controls">
                                                <select required class="form-control" name="i_area" id="i_area" data-validation-required-message="<?= $this->lang->line("Required"); ?>" data-placeholder="<?= $this->lang->line("Nama Area Provinsi"); ?>">
                                                    <option value=""></option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <div class="content-body">
        <section id="pagination">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header header-elements-inline">
                            <h4 class="card-title"><i class="feather icon-grid"></i> <?= $this->lang->line("Detail"); ?> <?= $this->lang->line($this->title); ?> <?= $this->lang->line("Kota"); ?></h4>
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
                                <div class="table-responsive">
                                    <div class="form-body">
                                        <table class="table table-sm table-column table-bordered" id="tablecover">
                                            <thead class="<?= $this->session->e_color; ?> font-medium-3 text-uppercase bg-darken-1 text-white">
                                                <tr>
                                                    <th class="text-center" width="5%">No</th>
                                                    <th width="40%"><?= $this->lang->line("Kota"); ?></th>
                                                    <th width="40%"><?= $this->lang->line("Pramuniaga"); ?></th>
                                                    <th class="text-right" width="20%"><?= $this->lang->line("Target"); ?></th>
                                                    <th class="text-center" width="5%"><i class="fa fa-plus-circle fa-2x" title="<?= $this->lang->line('Tambah'); ?>" id="addrow"></i></th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                            <input type="hidden" id="jml" name="jml" value="0">
                                        </table>
                                    </div>
                                </div>
                                <div class="form-actions">
                                    <button type="submit" class="btn btn-success round btn-min-width mr-1"><i class="icon-paper-plane mr-1"></i><?= $this->lang->line("Simpan"); ?></button>
                                    <a href="<?= base_url($this->folder); ?>" class="btn btn-secondary round btn-min-width mr-1"><i class="icon-action-undo mr-1"></i><?= $this->lang->line("Kembali"); ?></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</form>