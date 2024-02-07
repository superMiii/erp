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
                    <li class="breadcrumb-item"><a href="#" onclick="return false;"><?= $this->lang->line('Keuangan'); ?></a></li>
                    <!-- <li class="breadcrumb-item"><a href="<?= base_url($this->folder); ?>"><?= $this->lang->line($this->title); ?></a></li> -->
                    <li class="breadcrumb-item"><a href="<?= base_url($this->folder . '/index/' . encrypt_url($dfrom) . '/' . encrypt_url($dto)); ?>"><?= $this->lang->line($this->title); ?></a></li>
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
                <div class="card box-shadow-0 border-primary">
                    <div class="card-header card-head-inverse <?= $this->session->e_color; ?> bg-darken-1 text-white">
                        <h4 class="card-title"><i class="icon-eye"></i> <?= $this->lang->line('Detail'); ?> <?= $this->lang->line($this->title); ?></h4>
                        <input type="hidden" id="path" value="<?= $this->folder; ?>">
                        <input type="hidden" id="d_from" value="<?= encrypt_url($dfrom); ?>">
                        <input type="hidden" id="d_to" value="<?= encrypt_url($dto); ?>">
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
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Nomor Dokumen"); ?> :</label>
                                            <fieldset>
                                                <div class="input-group input-group-sm controls">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">
                                                            <span class="fa fa-hashtag"></span>
                                                        </div>
                                                    </div>
                                                    <input type="hidden" name="id" id="id" value="<?= $data->i_pv; ?>">
                                                    <input type="hidden" name="i_document_old" id="i_document_old" value="<?= $data->i_pv_id; ?>">
                                                    <input type="text" readonly name="i_document" id="i_document" value="<?= $data->i_pv_id; ?>" placeholder="<?= $this->lang->line("Nomor Dokumen"); ?>" class="form-control form-control-sm text-uppercase" data-validation-required-message="<?= $this->lang->line("Required"); ?>" maxlength="500" autocomplete="off" required aria-label="Text input with checkbox">
                                                </div>
                                                <span class="notekode" id="ada" hidden="true">* <?= $this->lang->line("Sudah Ada"); ?></span>
                                            </fieldset>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Tanggal Dokumen"); ?> :</label>
                                            <div class="input-group input-group-sm controls">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <span class="fa fa-calendar-o"></span>
                                                    </span>
                                                </div>
                                                <input type="text" readonly class="form-control form-control-sm date" value="<?= $data->date_pv; ?>" data-validation-required-message="<?= $this->lang->line("Required"); ?>" placeholder="" id="d_document" name="d_document" maxlength="500" autocomplete="off" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Nama Area Provinsi"); ?> :</label>
                                            <div class="controls">
                                                <input type="text" readonly class="form-control form-control-sm" value="<?= $data->i_area_id . ' - ' . $data->e_area_name; ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Baris ke 2 -->
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Tipe Voucher"); ?> :</label>
                                            <div class="controls">
                                                <input type="text" readonly class="form-control form-control-sm" value="<?= $data->i_pv_type_id . ' - ' . $data->e_pv_type_name; ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Perkiraan"); ?> :</label>
                                            <div class="controls">
                                                <input type="text" readonly class="form-control form-control-sm" value="<?= $data->i_coa_id . ' - ' . $data->e_coa_name; ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Keterangan"); ?> :</label>
                                            <textarea class="form-control text-capitalize" readonly name="e_remark" <?= $data->e_remark; ?>></textarea>
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
                <div class="card box-shadow-0 border-primary">
                    <div class="card-header card-head-inverse <?= $this->session->e_color; ?> bg-darken-1 text-white">
                        <h4 class="card-title"><i class="fa fa-money fa-lg"></i> <?= $this->lang->line("Detail"); ?> <?= $this->lang->line("Terima Voucher"); ?></h4>
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
                                                <th class="text-center">No</th>
                                                <th class="text-center"><?= $this->lang->line("Perkiraan"); ?></th>
                                                <th class="text-center"><?= $this->lang->line("Tgl Bukti"); ?></th>
                                                <?php if ($data->i_pv_type_id == 'BK') { ?>
                                                    <th class="text-center"><?= $this->lang->line("TF/GR/TN"); ?></th>
                                                    <th class="text-center"><?= $this->lang->line("Referensi"); ?></th>
                                                <?php } ?>
                                                <th class="text-center"><?= $this->lang->line("Keterangan"); ?></th>
                                                <th class="text-right"><?= $this->lang->line("Jumlah"); ?></th>
                                            </tr>
                                        </thead>
                                        <?php if ($data->i_pv_type_id == 'BK') { ?>
                                            <tbody>
                                                <?php $i = 0;
                                                $subtotal = 0;
                                                $saldo = 0;
                                                $saldoakhir = 0;
                                                if ($detail->num_rows() > 0) {
                                                    foreach ($detail->result() as $key) {
                                                        $i++; ?>
                                                        <tr>
                                                            <td class="text-center" valign="center"><?= $i; ?></td>
                                                            <td><?= $key->i_coa_id . ' - ' . $key->e_coa_name; ?></td>
                                                            <td><?= $key->date_bukti; ?></td>
                                                            <td><?= $key->e_pv_refference_type_name; ?></td>
                                                            <td><?= $key->i_referensi; ?></td>
                                                            <td><?= $key->e_remark; ?></td>
                                                            <td class="text-right"><?= number_format($key->v_pv); ?></td>
                                                        </tr>
                                                <?php
                                                        $subtotal += $key->v_pv;
                                                    }
                                                    $saldo = 0;
                                                    $saldoakhir = $saldo - $subtotal;
                                                } ?>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th class="text-right" colspan="6"><?= $this->lang->line('Sub Total'); ?> Rp. </th>
                                                    <th class="text-right"><?= number_format($subtotal); ?></th>
                                                </tr>
                                                <tr>
                                                    <th class="text-right" colspan="6"><?= $this->lang->line('Saldo'); ?> Rp. </th>
                                                    <th class="text-right"><?= number_format($saldo); ?></th>
                                                </tr>
                                                <tr>
                                                    <th class="text-right" colspan="6"><?= $this->lang->line('Sisa Saldo'); ?> Rp. </th>
                                                    <th class="text-right"><?= number_format($saldoakhir); ?></th>
                                                </tr>
                                            </tfoot>
                                        <?php } else { ?>
                                            <tbody>
                                                <?php $i = 0;
                                                $subtotal = 0;
                                                $saldo = 0;
                                                $saldoakhir = 0;
                                                if ($detail->num_rows() > 0) {
                                                    foreach ($detail->result() as $key) {
                                                        $i++; ?>
                                                        <tr>
                                                            <td class="text-center" valign="center"><?= $i; ?></td>
                                                            <td><?= $key->i_coa_id . ' - ' . $key->e_coa_name; ?></td>
                                                            <td><?= $key->date_bukti; ?></td>
                                                            <td><?= $key->e_remark; ?></td>
                                                            <td class="text-right"><?= number_format($key->v_pv); ?></td>
                                                        </tr>
                                                <?php
                                                        $subtotal += $key->v_pv;
                                                    }
                                                    $saldo = 0;
                                                    $saldoakhir = $saldo - $subtotal;
                                                } ?>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th class="text-right" colspan="4"><?= $this->lang->line('Sub Total'); ?> Rp. </th>
                                                    <th class="text-right"><?= number_format($subtotal); ?></th>
                                                </tr>
                                                <tr>
                                                    <th class="text-right" colspan="4"><?= $this->lang->line('Saldo'); ?> Rp. </th>
                                                    <th class="text-right"><?= number_format($saldo); ?></th>
                                                </tr>
                                                <tr>
                                                    <th class="text-right" colspan="4"><?= $this->lang->line('Sisa Saldo'); ?> Rp. </th>
                                                    <th class="text-right"><?= number_format($saldoakhir); ?></th>
                                                </tr>
                                            </tfoot>
                                        <?php } ?>


                                        <input type="hidden" id="jml" name="jml" value="<?= $i; ?>">
                                    </table>
                                </div>
                            </div>
                            <form action="<?= base_url($this->folder); ?>" method="post">
                                <input type="hidden" name="dfrom" value="<?= $dfrom; ?>">
                                <input type="hidden" name="dto" value="<?= $dto; ?>">
                                <div class="form-actions mb-0 mt-0">
                                    <!-- <button type="submit" class="btn btn-danger btn-min-width"><i class="icon-action-undo mb-0 mr-1"></i><?= $this->lang->line("Kembali"); ?></button> -->
                                    <a href="<?= base_url($this->folder . '/index/' . encrypt_url($dfrom) . '/' . encrypt_url($dto) . '/' . encrypt_url($type) . '/' . encrypt_url($area) . '/' . encrypt_url($coa)); ?>" class="btn btn-secondary round btn-min-width mr-1"><i class="icon-action-undo mr-1"></i><?= $this->lang->line("Kembali"); ?></a>
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