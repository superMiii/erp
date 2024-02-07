<div class="content-header row">
    <div class="content-header-left col-md-12 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#" onclick="return false;"><?= $this->lang->line('Keuangan'); ?></a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url($this->folder . '/index/' . encrypt_url($dfrom) . '/' . encrypt_url($dto) . '/' . encrypt_url($harea)); ?>"><?= $this->lang->line($this->title); ?></a></li>
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
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Nomor Dokumen"); ?> :</label>
                                            <fieldset>
                                                <div class="input-group input-group-sm">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">
                                                            <span class="fa fa-hashtag"></span>
                                                        </div>
                                                    </div>
                                                    <input type="text" readonly value="<?= $data->i_alokasi_id; ?>" name="i_document" id="i_document" placeholder="<?= $this->lang->line("Nomor Dokumen"); ?>" class="form-control form-control-sm text-uppercase" data-validation-required-message="<?= $this->lang->line("Required"); ?>" maxlength="500" autocomplete="off" required aria-label="Text input with checkbox">
                                                </div>
                                                <span class="notekode" id="ada" hidden="true">* <?= $this->lang->line("Sudah Ada"); ?></span>
                                            </fieldset>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Tanggal Dokumen"); ?> :</label>
                                            <div class="input-group input-group-sm controls" readonly value="<?= date('d-m-Y', strtotime($data->d_alokasi)); ?>">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <span class="fa fa-calendar-o"></span>
                                                    </span>
                                                </div>
                                                <input type="date" readonly class="form-control form-control-sm date" min="<?= get_min_date(); ?>" max="<?= date('Y-m-31'); ?>" value="<?= $data->d_alokasi; ?>" data-validation-required-message="<?= $this->lang->line("Required"); ?>" placeholder="" id="d_document" name="d_document" maxlength="500" autocomplete="off" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Nomor Voucher"); ?> :</label>
                                            <fieldset>
                                                <div class="input-group input-group-sm">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">
                                                            <span class="fa fa-hashtag"></span>
                                                        </div>
                                                    </div>
                                                    <input type="hidden" readonly name="i_rv" id="i_rv" value="<?= $data->i_rv; ?>">
                                                    <input type="hidden" readonly name="i_rv_item" id="i_rv_item" value="<?= $data->i_rv_item; ?>">
                                                    <input type="text" readonly name="i_rv_id" id="i_rv_id" value="<?= $data->i_rv_id; ?>" class="form-control form-control-sm text-uppercase">
                                                </div>
                                            </fieldset>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Tanggal Voucher"); ?> :</label>
                                            <div class="input-group input-group-sm controls">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <span class="fa fa-calendar-o"></span>
                                                    </span>
                                                </div>
                                                <input type="date" readonly class="form-control form-control-sm" value="<?= $data->d_bukti; ?>" id="d_bukti" name="d_bukti">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Baris ke 2 -->
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Bank"); ?> :</label>
                                            <input type="text" readonly class="form-control form-control-sm" value="<?= $data->e_coa_name; ?>" name="e_bank_name">
                                        </div>
                                    </div>
                                    <!-- <div class="col-md-4">
                                            <div class="form-group">
                                                <label><?= $this->lang->line("Area"); ?> :</label>
                                                <input type="hidden" readonly class="form-control form-control-sm" value="<?= $data->i_area; ?>" id="i_area" name="i_area">
                                                <input type="text" readonly class="form-control form-control-sm" value="<?= $data->e_area_name; ?>">
                                            </div>
                                        </div> -->
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Area"); ?> :</label>
                                            <div class="controls">
                                                <input type="text" readonly value="<?= $data->i_area_id . " - " . $data->e_area_name; ?>" class="form-control form-control-sm text-uppercase" maxlength="500" name="i_area" placeholder="<?= $this->lang->line("Area"); ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Nama Pelanggan"); ?> :</label>
                                            <div class="controls">
                                                <input type="text" readonly value="<?= $data->i_customer_id . " - " . $data->e_customer_name; ?>" class="form-control form-control-sm text-uppercase" maxlength="500" name="i_customer" placeholder="<?= $this->lang->line("Pelanggan"); ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Jumlah"); ?> :</label>
                                            <input type="text" readonly name="v_jumlah" id="vjumlah" class="form-control form-control-sm" value="<?= number_format($data->v_rv_saldo); ?>">
                                            <input type="hidden" id="vlebih" name="v_lebih" value="0">
                                            <input type="hidden" id="vsisa" name="vsisa" value="<?= $data->v_rv_saldo; ?>">
                                            <input type="hidden" name="jml" id="jml" value="0">
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
                        <h4 class="card-title"><i class="fa fa-cart-arrow-down"></i> <?= $this->lang->line("Detail"); ?> <?= $this->lang->line("Alokasi AR"); ?></h4>
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
                                                <th class="text-center" width="12%"><?= $this->lang->line("No Nota"); ?></th>
                                                <th class="text-center" width="12%"><?= $this->lang->line("Tgl Nota"); ?></th>
                                                <th class="text-center" width="12%"><?= $this->lang->line("Nilai"); ?></th>
                                                <th class="text-center" width="12%"><?= $this->lang->line("Bayar"); ?></th>
                                                <!-- <th class="text-center" width="12%"><?= $this->lang->line("Sisa"); ?></th> -->
                                                <th class="text-center"><?= $this->lang->line("Keterangan"); ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $i = 0;
                                            if ($detail->num_rows() > 0) {
                                                foreach ($detail->result() as $key) {
                                                    $i++; ?>
                                                    <tr>
                                                        <td class="text-center" valign="center">
                                                            <spanx id="snum<?= $i; ?>"><?= $i; ?></spanx>
                                                        </td>
                                                        <td><?= $key->i_nota_id; ?></td>
                                                        <td><?= $key->d_nota; ?></td>
                                                        <td class="text-right"><?= number_format($key->v_nota); ?></td>
                                                        <td class="text-right"><?= number_format($key->v_jumlah); ?></td>
                                                        <!-- <td class="text-right"><?= number_format($key->v_sisa); ?></td> -->
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
    <!--/ Alternative pagination table -->
</div>