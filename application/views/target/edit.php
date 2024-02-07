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
                        <li class="breadcrumb-item active"><?= $this->lang->line('Ubah'); ?> <?= $this->lang->line($this->title); ?></li>
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
                                <div class="row">
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Bulan"); ?> :</label>
                                            <div class="controls">
                                                <select name="month" id="month" class="form-control select2">
                                                    <option value="01" <?php if ('01' == $month) {
                                                                            echo "selected";
                                                                        } ?>>Januari</option>
                                                    <option value="02" <?php if ('02' == $month) {
                                                                            echo "selected";
                                                                        } ?>>Februari</option>
                                                    <option value="03" <?php if ('03' == $month) {
                                                                            echo "selected";
                                                                        } ?>>Maret</option>
                                                    <option value="04" <?php if ('04' == $month) {
                                                                            echo "selected";
                                                                        } ?>>April</option>
                                                    <option value="05" <?php if ('05' == $month) {
                                                                            echo "selected";
                                                                        } ?>>Mei</option>
                                                    <option value="06" <?php if ('06' == $month) {
                                                                            echo "selected";
                                                                        } ?>>Juni</option>
                                                    <option value="07" <?php if ('07' == $month) {
                                                                            echo "selected";
                                                                        } ?>>Juli</option>
                                                    <option value="08" <?php if ('08' == $month) {
                                                                            echo "selected";
                                                                        } ?>>Agustus</option>
                                                    <option value="09" <?php if ('09' == $month) {
                                                                            echo "selected";
                                                                        } ?>>September</option>
                                                    <option value="10" <?php if ('10' == $month) {
                                                                            echo "selected";
                                                                        } ?>>Oktober</option>
                                                    <option value="11" <?php if ('11' == $month) {
                                                                            echo "selected";
                                                                        } ?>>November</option>
                                                    <option value="12" <?php if ('12' == $month) {
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
                                                        <option value="<?= $i; ?>" <?php if ($year == $i) {
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
                                                    <option value="<?= $data->i_area; ?>"><?= $data->i_area_id . ' - ' . $data->e_area_name; ?></option>
                                                </select>
                                                <input type="hidden" name="i_area_old" value="<?= $data->i_area; ?>">
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
                                                    <th class="text-center" width="5%"><i class="fa fa-plus-circle fa-2x" title="<?= $this->lang->line('Ubah'); ?>" id="addrow"></i></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $i = 0;
                                                if ($detail->num_rows() > 0) {
                                                    foreach ($detail->result() as $key) {
                                                        $i++; ?>
                                                        <tr>
                                                            <td class="text-center">
                                                                <spanx id="snum<?= $i; ?>"><?= $i; ?></spanx>
                                                            </td>
                                                            <td>
                                                                <select data-nourut="<?= $i; ?>" required class="form-control" name="i_city[]" id="i_city<?= $i; ?>">
                                                                    <option value="<?= $key->i_city; ?>"><?= $key->e_city_name; ?></option>
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <select data-urut="<?= $i; ?>" required class="form-control" name="i_salesman[]" id="i_salesman<?= $i; ?>">
                                                                    <option value="<?= $key->i_salesman; ?>"><?= $key->e_salesman_name; ?></option>
                                                                </select>
                                                            </td>
                                                            <td><input type="text" class="form-control text-right" value="<?= number_format($key->v_target); ?>" onkeyup="onlyangka(this); reformat(this);" placeholder="Rp. 0,00" id="v_target_city<?= $i; ?>" name="v_target_city[]" maxlength="16" autocomplete="off" required onblur="if(this.value==''){this.value='0';}" onfocus="if(this.value=='0'){this.value='';}"></td>
                                                            <td class="text-center"><i title="Delete" class="fa fa-minus-circle fa-2x text-danger ibtnDel"></i></td>
                                                        </tr>
                                                <?php }
                                                } ?>
                                            </tbody>
                                            <input type="hidden" id="jml" name="jml" value="<?= $i; ?>">
                                        </table>
                                    </div>
                                </div>
                                <div class="form-actions">
                                    <button type="submit" class="btn btn-warning round btn-min-width mr-1"><i class="icon-paper-plane mr-1"></i><?= $this->lang->line("Ubah"); ?></button>
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