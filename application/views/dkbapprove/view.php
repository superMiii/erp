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
<form class="form-validation" novalidate>
    <div class="content-header row">
        <div class="content-header-left col-md-12 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#" onclick="return false;"><?= $this->lang->line('Gudang'); ?></a></li>
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
                    <div class="card">
                        <div class="card-header header-elements-inline <?= $this->session->e_color; ?> bg-darken-1 text-white">
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
                                <!-- Baris ke 1 -->
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Nomor Dokumen"); ?> :</label>
                                            <fieldset>
                                                <div class="input-group input-group-sm">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">
                                                            <span class="fa fa-hashtag"></span>
                                                        </div>
                                                    </div>
                                                    <input type="hidden" name="id" id="id" class="form-control" value="<?= $data->i_sl; ?>">
                                                    <input type="hidden" name="i_document_old" id="i_document_old" class="form-control" value="<?= $data->i_sl_id; ?>">
                                                    <input type="text" name="i_document" id="i_document" value="<?= $data->i_sl_id; ?>" readonly placeholder="SL-<?= date('ym'); ?>-000001" class="form-control form-control-sm text-uppercase" data-validation-required-message="<?= $this->lang->line("Required"); ?>" maxlength="500" autocomplete="off" required aria-label="Text input with checkbox">
                                                </div>
                                                <span class="notekode" id="ada" hidden="true">* <?= $this->lang->line("Sudah Ada"); ?></span>
                                            </fieldset>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Tanggal Dokumen"); ?> :</label>
                                            <div class="input-group input-group-sm controls">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">
                                                        <span class="fa fa-calendar-o"></span>
                                                    </span>
                                                </div>
                                                <input type="text" class="form-control form-control-sm date" value="<?= date('d-m-Y', strtotime($data->d_sl)); ?>" readonly data-validation-required-message="<?= $this->lang->line("Required"); ?>" placeholder="" id="d_document" name="d_document" maxlength="500" autocomplete="off" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-5">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Nama Area Provinsi"); ?> :</label>
                                            <div class="controls">
                                                <select disabled class="form-control form-control-sm" id="i_area" required data-placeholder="<?= $this->lang->line("Pilih") . ' ' . $this->lang->line("Area"); ?>" data-validation-required-message="<?= $this->lang->line("Required"); ?>" name="i_area">
                                                    <option value="<?= $data->i_area; ?>"><?= $data->i_area_id . ' - ' . $data->e_area_name; ?></option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Baris ke 2 -->
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Kirim Via"); ?> :</label>
                                            <div class="controls">
                                                <select disabled class="form-control form-control-sm select2" name="i_sl_via" id="i_sl_via" data-placeholder="<?= $this->lang->line("Pilih") . ' ' . $this->lang->line("Kirim Via"); ?>" required data-validation-required-message="<?= $this->lang->line("Required"); ?>">
                                                    <?php if ($via) {
                                                        foreach ($via->result() as $via) { ?>
                                                            <option value="<?php echo $via->i_sl_via; ?>" <?php if ($via->i_sl_via == $data->i_sl_via) { ?> selected <?php } ?>><?= $via->e_sl_via_name; ?></option>
                                                    <?php };
                                                    } ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Kirim Ke"); ?> :</label>
                                            <div class="controls">
                                                <select disabled class="form-control form-control-sm select2" name="i_sl_kirim" id="i_sl_kirim" data-placeholder="<?= $this->lang->line("Pilih") . ' ' . $this->lang->line("Kirim Ke"); ?>" required data-validation-required-message="<?= $this->lang->line("Required"); ?>">
                                                    <?php if ($kirim) {
                                                        foreach ($kirim->result() as $kirim) { ?>
                                                            <option value="<?php echo $kirim->i_sl_kirim; ?>" <?php if ($kirim->i_sl_kirim == $data->i_sl_kirim) { ?> selected <?php } ?>><?= $kirim->e_sl_kirim_name; ?></option>
                                                    <?php };
                                                    } ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <div class="form-group">
                                                <label><?= $this->lang->line('Nama Supir'); ?> :</label>
                                                <div class="controls">
                                                    <input type="text" readonly class="form-control form-control-sm text-capitalize" value="<?= $data->e_sopir_name; ?>" placeholder="<?= $this->lang->line('Nama Supir'); ?>" id="e_sopir_name" name="e_sopir_name" autocomplete="off">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <div class="form-group">
                                                <label><?= $this->lang->line('No Polisi'); ?> :</label>
                                                <div class="controls">
                                                    <input type="text" readonly class="form-control form-control-sm text-uppercase" value="<?= $data->i_kendaraan; ?>" placeholder="<?= $this->lang->line('No Polisi'); ?>" id="i_kendaraan" name="i_kendaraan" autocomplete="off">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- <div class="col-md-3">
                                        <div class="form-group">
                                            <div class="form-group">
                                                <label><?= $this->lang->line('Jumlah'); ?> :</label>
                                                <div class="controls">
                                                    <input type="text" value="0" class="form-control form-control-sm" placeholder="<?= $this->lang->line('Jumlah'); ?>" readonly id="v_sl" name="v_sl" autocomplete="off">
                                                </div>
                                            </div>
                                        </div>
                                    </div> -->
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
                    <div class="card">
                        <div class="card-header header-elements-inline <?= $this->session->e_color; ?> bg-darken-1 text-white">
                            <h4 class="card-title"><i class="fa fa-cart-arrow-down"></i> <?= $this->lang->line("Detail"); ?> <?= $this->lang->line("Surat Jalan"); ?></h4>
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
                                                    <th class="text-center" width="5%" valign="center">No</th>
                                                    <th class="text-center" width="15%" valign="center"><?= $this->lang->line("No Dok DO"); ?></th>
                                                    <th class="text-center" width="12%" valign="center"><?= $this->lang->line("Tgl Dok DO"); ?></th>
                                                    <th class="text-center" width="35%" valign="center"><?= $this->lang->line("Nama Pelanggan"); ?></th>
                                                    <th class="text-center" width="10%" valign="center"><?= $this->lang->line("Jumlah"); ?></th>
                                                    <th class="text-center" valign="center"><?= $this->lang->line("Keterangan"); ?></th>
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
                                                            <td><?= $key->i_do_id; ?></td>
                                                            <td><?= $key->date_do; ?></td>
                                                            <td><?= $key->e_customer_name; ?></td>
                                                            <td class="text-right"><?= number_format($key->v_jumlah); ?></td>
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
                                </div>
                            </div>
                        </div>
                        <div class="card-content collapse show">
                            <div class="card-body card-dashboard">
                                <h4><i class="fa fa-truck"></i> <?= $this->lang->line('Khusus Via Ekspedisi'); ?></h4>
                                <div class="form-body">
                                    <div class="table-responsive">
                                        <table class="table table-xs table-column table-bordered" id="tabledetailx">
                                            <thead class="<?= $this->session->e_color; ?> bg-darken-1 text-white">
                                                <tr>
                                                    <th class="text-center" width="5%" valign="center">No</th>
                                                    <th class="text-center" width="57%" valign="center"><?= $this->lang->line("Nama Ekspedisi"); ?></th>
                                                    <th class="text-center" valign="center"><?= $this->lang->line("Keterangan"); ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $ii = 0;
                                                if ($detailx->num_rows() > 0) {
                                                    foreach ($detailx->result() as $key) {
                                                        $ii++; ?>
                                                        <tr>
                                                            <td class="text-center" valign="center">
                                                                <spanxx id="snum<?= $ii; ?>"><?= $ii; ?></spanxx>
                                                            </td>
                                                            <td><?= '[ ' . $key->i_sl_ekspedisi_id . ' ] ' . $key->e_sl_ekspedisi; ?></td>
                                                            <td><?= $key->e_remark; ?></td>
                                                        </tr>
                                                <?php }
                                                } ?>
                                            </tbody>
                                            <input type="hidden" id="jmlx" name="jmlx" value="<?= $ii; ?>">
                                        </table>
                                    </div>
                                </div>
                                <div class="form-actions">
                                    <!-- <a href="<?= base_url($this->folder); ?>" class="btn btn-danger btn-min-width"><i class="icon-action-undo mr-1"></i><?= $this->lang->line("Kembali"); ?></a> -->
                                    <a href="<?= base_url($this->folder . '/index/' . encrypt_url($dfrom) . '/' . encrypt_url($dto)); ?>" class="btn btn-secondary round btn-min-width mr-1"><i class="icon-action-undo mr-1"></i><?= $this->lang->line("Kembali"); ?></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!--/ Alternative pagination table -->
    </div>
</form>