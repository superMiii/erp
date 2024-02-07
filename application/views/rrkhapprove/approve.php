<style>
    .table.table-xs th,
    .table td,
    .table.table-xs td {
        padding: 0.4rem 0.4rem;
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
                    <li class="breadcrumb-item"><a href="#" onclick="return false;"><?= $this->lang->line('Penjualan'); ?></a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url($this->folder . '/index/' . encrypt_url($dfrom) . '/' . encrypt_url($dto) . '/' . encrypt_url($harea)); ?>"><?= $this->lang->line($this->title); ?></a></li>
                    <li class="breadcrumb-item active"><?= $this->lang->line('Detail'); ?> <?= $this->lang->line($this->title); ?></li>
                </ol>
            </div>
        </div>
    </div>
</div>
<div class="content-body">
    <section id="pagination">
        <div class="row">
            <div class="col-12">
                <div class="card box-shadow-2">
                    <div class="card-header card-head-inverse <?= $this->session->e_color; ?> bg-darken-1 text-white">
                        <h4 class="card-title"><i class="icon-check"></i> <?= $this->lang->line('Detail'); ?> <?= $this->lang->line($this->title); ?></h4>
                        <input type="hidden" id="path" value="<?= $this->folder; ?>">
                        <input type="hidden" id="d_from" value="<?= encrypt_url($dfrom); ?>">
                        <input type="hidden" id="d_to" value="<?= encrypt_url($dto); ?>">
                        <input type="hidden" id="harea" value="<?= encrypt_url($harea); ?>">
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
                                <!-- Baris ke 1 -->
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Tanggal Dokumen"); ?> :</label>
                                            <div class="input-group input-group-sm controls">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <span class="fa fa-calendar-o"></span>
                                                    </span>
                                                </div>
                                                <input type="hidden" value="<?= $data->i_rrkh; ?>" id="id" name="id">
                                                <input type="hidden" value="<?= $data->d_rrkh; ?>" id="d_document_old" name="d_document_old">
                                                <input type="date" class="form-control form-control-sm readonly" min="2021-10-01" value="<?= $data->d_rrkh; ?>" data-validation-required-message="<?= $this->lang->line("Required"); ?>" placeholder="" id="d_document" name="d_document" maxlength="500" autocomplete="off" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Hari"); ?> :</label>
                                            <div class="input-group input-group-sm controls">
                                                <input type="text" readonly class="form-control form-control-sm" placeholder="" id="day" name="day" value="<?= date('l'); ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Tanggal Terima"); ?> :</label>
                                            <div class="input-group input-group-sm controls">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <span class="fa fa-calendar-o"></span>
                                                    </span>
                                                </div>
                                                <input type="date" readonly class="form-control form-control-sm date" min="2021-10-01" value="<?= $data->d_receive; ?>" data-validation-required-message="<?= $this->lang->line("Required"); ?>" placeholder="" id="d_receive" name="d_receive" maxlength="500" autocomplete="off" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Nama Area Provinsi"); ?> :</label>
                                            <div class="controls">
                                                <input type="text" readonly class="form-control form-control-sm" value="<?= $data->i_area_id . ' - ' . $data->e_area_name; ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Nama Pramuniaga"); ?> :</label>
                                            <div class="controls">
                                                <input type="text" readonly class="form-control form-control-sm" value="<?= $data->e_salesman_name; ?>">
                                            </div>
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
    <!--/ Alternative pagination table -->
</div>

<div class="content-body">
    <!-- Alternative pagination table -->
    <section id="pagination">
        <div class="row">
            <div class="col-12">
                <div class="card box-shadow-2">
                    <div class="card-header header-elements-inline">
                        <h4 class="card-title"><i class="feather icon-user-check"></i> <?= $this->lang->line("Detail"); ?> <?= $this->lang->line("Nota"); ?></h4>
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
                                <div class="table-responsive">
                                    <table class="table table-xs table-column table-bordered" id="tabledetail">
                                        <thead class="<?= $this->session->e_color; ?> bg-darken-1 text-white">
                                            <tr>
                                                <th class="text-center" width="5%">No</th>
                                                <th class="text-center" width="5%"><?= $this->lang->line("Bukti"); ?></th>
                                                <th width="25%"><?= $this->lang->line("Pelanggan"); ?></th>
                                                <th width="20%"><?= $this->lang->line("Kota"); ?></th>
                                                <th width="20%"><?= $this->lang->line("Rencana"); ?></th>
                                                <th width="5%"><?= $this->lang->line("Realisasi"); ?></th>
                                                <th width="20%"><?= $this->lang->line("Keterangan"); ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $i = 0;
                                            if ($detail->num_rows() > 0) {
                                                foreach ($detail->result() as $key) {
                                                    $i++; ?>
                                                    <tr>
                                                        <td class="text-center"><?= $i; ?></td>
                                                        <td class="text-center">
                                                            <div class="controls skin-square"><input type="checkbox" <?php if ($key->f_kunjungan_valid == 't') {
                                                                                                                            echo "checked";
                                                                                                                        } ?> id="f_kunjungan_valid<?= $i; ?>" name="f_kunjungan_valid[]"></div>
                                                        </td>
                                                        <td><?= $key->i_customer_id . ' - ' . $key->e_customer_name; ?></td>
                                                        <td><?= $key->e_city_name; ?></td>
                                                        <td><?= $key->e_kunjungan_type_name; ?></td>
                                                        <td class="text-center">
                                                            <div class="controls skin-square"><input type="checkbox" <?php if ($key->f_kunjungan_realisasi == 't') {
                                                                                                                            echo "checked";
                                                                                                                        } ?> id="f_kunjungan_realisasi<?= $i; ?>" name="f_kunjungan_realisasi[]"></div>
                                                        </td>
                                                        <td><?= $key->e_remark; ?></td>
                                                    </tr>
                                            <?php }
                                            } ?>
                                        </tbody>
                                        <input type="hidden" id="jml" name="jml" value="<?= $i; ?>">
                                    </table>
                                </div>
                            </div>
                            <div class="form-actions">
                                <form action="<?= base_url($this->folder); ?>" method="post">
                                    <input type="hidden" name="dfrom" value="<?= $dfrom; ?>">
                                    <input type="hidden" name="dto" value="<?= $dto; ?>">
                                    <div class="form-actions mb-0 mt-0">
                                        <button type="button" onclick="sweetapprovev33('<?= $this->folder; ?>','<?= $data->i_rrkh; ?>','null','<?= encrypt_url($dfrom); ?>','<?= encrypt_url($dto); ?>','<?= encrypt_url($harea); ?>');" class="btn btn-info round btn-min-width mr-1"><i class="fa fa-check-circle fa-lg mr-1"></i><?= $this->lang->line("Menyetujui"); ?></button>
                                        <a href="<?= base_url($this->folder . '/index/' . encrypt_url($dfrom) . '/' . encrypt_url($dto) . '/' . encrypt_url($harea)); ?>" class="btn btn-secondary round btn-min-width mr-1"><i class="icon-action-undo mr-1"></i><?= $this->lang->line("Kembali"); ?></a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>