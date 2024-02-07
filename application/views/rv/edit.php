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
                        <li class="breadcrumb-item"><a href="#" onclick="return false;"><?= $this->lang->line('Keuangan'); ?></a></li>
                        <!-- <li class="breadcrumb-item"><a href="<?= base_url($this->folder); ?>"><?= $this->lang->line($this->title); ?></a></li> -->
                        <li class="breadcrumb-item"><a href="<?= base_url($this->folder . '/index/' . encrypt_url($dfrom) . '/' . encrypt_url($dto) . '/' . encrypt_url($htype) . '/' . encrypt_url($harea) . '/' . encrypt_url($hcoa)); ?>"><?= $this->lang->line($this->title); ?></a></li>
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
                    <div class="card box-shadow-0 border-primary">
                        <div class="card-header card-head-inverse <?= $this->session->e_color; ?> bg-darken-1 text-white">
                            <h4 class="card-title"><i class="icon-pencil"></i> <?= $this->lang->line('Ubah'); ?> <?= $this->lang->line($this->title); ?></h4>
                            <input type="hidden" id="path" value="<?= $this->folder; ?>">
                            <input type="hidden" id="d_from" value="<?= encrypt_url($dfrom); ?>">
                            <input type="hidden" id="d_to" value="<?= encrypt_url($dto); ?>">
                            <input type="hidden" id="htype" value="<?= encrypt_url($htype); ?>">
                            <input type="hidden" id="harea" value="<?= encrypt_url($harea); ?>">
                            <input type="hidden" id="hcoa" value="<?= encrypt_url($hcoa); ?>">
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
                                                        <input type="hidden" name="id" id="id" value="<?= $data->i_rv; ?>">
                                                        <input type="hidden" name="i_document_old" id="i_document_old" value="<?= $data->i_rv_id; ?>">
                                                        <input type="text" readonly name="i_document" id="i_document" value="<?= $data->i_rv_id; ?>" placeholder="<?= $this->lang->line("Nomor Dokumen"); ?>" class="form-control form-control-sm text-uppercase" data-validation-required-message="<?= $this->lang->line("Required"); ?>" maxlength="500" autocomplete="off" required aria-label="Text input with checkbox">
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
                                                    <input type="date" class="form-control form-control-sm date" min="<?= get_min_date2(); ?>" max="<?= date('Y-m-d'); ?>" value="<?= $data->d_rv; ?>" data-validation-required-message="<?= $this->lang->line("Required"); ?>" placeholder="" id="d_document" name="d_document" maxlength="500" autocomplete="off" required>
                                                </div>
                                            </div>
                                            <input type="hidden" class="form-control form-control-sm date" id="tgl" name="tgl">
                                            <input type="hidden" id="kodd" name="kodd">
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label><?= $this->lang->line("Nama Area Provinsi"); ?> :</label>
                                                <div class="controls">
                                                    <select readonly class="form-control" id="i_area" required data-placeholder="<?= $this->lang->line("Pilih") . ' ' . $this->lang->line("Area"); ?>" data-validation-required-message="<?= $this->lang->line("Required"); ?>" name="i_area">
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
                                                <label><?= $this->lang->line("Tipe Voucher"); ?> :</label>
                                                <div class="controls">
                                                    <select readonly class="form-control" name="i_rv_type" id="i_rv_type" data-placeholder="<?= $this->lang->line("Pilih") . ' ' . $this->lang->line("Tipe Voucher"); ?>" required data-validation-required-message="<?= $this->lang->line("Required"); ?>">
                                                        <option value="<?= $data->i_rv_type; ?>"><?= $data->i_rv_type_id . ' - ' . $data->e_rv_type_name; ?></option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label><?= $this->lang->line("Perkiraan"); ?> :</label>
                                                <div class="controls">
                                                    <select class="form-control" name="i_coa" id="i_coa" data-placeholder="<?= $this->lang->line("Pilih") . ' ' . $this->lang->line("Perkiraan"); ?>" required data-validation-required-message="<?= $this->lang->line("Required"); ?>">
                                                        <option value="<?= $data->i_coa; ?>"><?= $data->i_coa_id . ' - ' . $data->e_coa_name; ?></option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label><?= $this->lang->line("Keterangan"); ?> :</label>
                                                <input type="TEXT" class="form-control text-capitalize" name="e_remark" id="e_remark" value="<?= $data->e_remark; ?>">
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
                                                    <th class="text-center" width="3%" valign="center">No</th>
                                                    <th class="text-center" width="17%" valign="center"><?= $this->lang->line("Perkiraan"); ?></th>
                                                    <th class="text-center" width="10%" valign="center"><?= $this->lang->line("Tgl Bukti"); ?></th>
                                                    <th class="text-center clear" width="10%" valign="center"><?= $this->lang->line("TF/GR/TN"); ?></th>
                                                    <th class="text-center clear" width="10%" valign="center"><?= $this->lang->line("Referensi"); ?></th>
                                                    <th class="text-center" width="20%" valign="center"><?= $this->lang->line("Keterangan"); ?></th>
                                                    <th class="text-center" width="27%" valign="center"><?= $this->lang->line("Jumlah"); ?></th>
                                                    <th class="text-center" width="3%"><i class="fa fa-plus-circle fa-lg" title="<?= $this->lang->line('Tambah'); ?>" id="addrow"></i></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $i = 0;
                                                $subtotal = 0;
                                                $saldo = 0;
                                                $saldoakhir = 0;
                                                if ($data->i_rv_type_id != "BM") {
                                                    $tex = "hidden";
                                                } else {
                                                    $tex = "";
                                                }
                                                if ($detail->num_rows() > 0) {

                                                    foreach ($detail->result() as $key) {
                                                        $i++; ?>
                                                        <tr>
                                                            <td class="text-center" valign="center">
                                                                <spanx id="snum<?= $i; ?>"><?= $i; ?></spanx>
                                                            </td>
                                                            <td><select data-nourut="<?= $i; ?>" required class="form-control isi select2-size-sm" name="i_coa_item[]" id="i_coa_item<?= $i; ?>">
                                                                    <option value="<?= $key->i_coa; ?>"><?= $key->i_coa_id . ' - ' . $key->e_coa_name; ?></option>
                                                                </select></td>
                                                            <td><input type="date" max="<?= $data->d_rv; ?>" value="<?= $key->d_bukti; ?>" class="form-control isi form-control-sm" id="d_bukti<?= $i; ?>" name="d_bukti[]"></td>
                                                            <td class="clear" <?= $tex; ?>><select data-nourut="<?= $i; ?>" class="form-control select2-size-sm" name="i_rv_refference_type[]" id="i_rv_refference_type<?= $i; ?>">
                                                                    <option value="<?= $key->i_rv_refference_type; ?>"><?= $key->e_rv_refference_type_name; ?></option>
                                                                </select></td>
                                                            <td class="clear" <?= $tex; ?>><select data-nourut="<?= $i; ?>" class="form-control select2-size-sm" name="i_rv_refference[]" id="i_rv_refference<?= $i; ?>">
                                                                    <option value="<?= $key->i_rv_refference; ?>"><?= $key->i_referensi; ?></option>
                                                                </select></td>
                                                            <input type="hidden" class="form-control isi form-control-sm" value="<?= $key->ara; ?>" id="araa<?= $i; ?>" name="araa[]">
                                                            <input type="hidden" class="form-control isi form-control-sm" value="<?= $key->v_rv; ?>" id="jum<?= $i; ?>" name="jum[]">
                                                            <td><input type="text" class="form-control isi form-control-sm" value="<?= $key->e_remark; ?>" id="e_remark_item<?= $i; ?>" name="e_remark_item[]"></td>
                                                            <td><input type="text" class="formatrupiah isi form-control form-control-sm text-right v_rv" value="<?= number_format($key->v_rv); ?>" id="v_rv_item<?= $i; ?>" name="v_rv_item[]" onkeyup="reformat(this);hetang();juma(<?= $i; ?>);" autocomplete="off" onblur="if(this.value==''){this.value='0';hitung();}" onfocus="if(this.value=='0'){this.value='';}"></td>
                                                            <td class="text-center"><i title="Delete" class="fa fa-minus-circle fa-lg text-danger ibtnDel"></i></td>
                                                        </tr>
                                                <?php
                                                        $subtotal += $key->v_rv;
                                                    }
                                                    $saldo = 0;
                                                    $saldoakhir = $subtotal + $saldo;
                                                } ?>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th class="text-right colay" colspan="6"><?= $this->lang->line('Sub Total'); ?></th>
                                                    <th><input type="text" class="form-control form-control-sm text-right" name="v_rv" id="v_rv" value="<?= number_format($subtotal); ?>" readonly></th>
                                                </tr>
                                                <tr>
                                                    <th class="text-right colay" colspan="6"><?= $this->lang->line('Saldo'); ?></th>
                                                    <th><input type="text" class="form-control form-control-sm text-right" name="v_saldo" id="v_saldo" value="<?= number_format($saldo); ?>" readonly></th>
                                                </tr>
                                                <tr>
                                                    <th class="text-right colay" colspan="6"><?= $this->lang->line('Sisa Saldo'); ?></th>
                                                    <th><input type="text" class="form-control form-control-sm text-right" name="v_sisa_saldo" id="v_sisa_saldo" value="<?= number_format($saldoakhir); ?>" readonly></th>
                                                </tr>
                                            </tfoot>
                                            <input type="hidden" id="jml" name="jml" value="<?= $i; ?>">
                                            <input type="hidden" id="tex" name="tex" value="<?= $data->i_rv_type_id ?>">
                                            <input type="hidden" id="wajib" name="wajib" value="">
                                        </table>
                                    </div>
                                </div>
                                <div class="form-actions">
                                    <button type="submit" id="submit" class="btn btn-warning round btn-min-width mr-1"><i class="icon-paper-plane mr-1"></i><?= $this->lang->line("Ubah"); ?></button>
                                    <!-- <a href="<?= base_url($this->folder); ?>" class="btn btn-danger btn-min-width"><i class="icon-action-undo mr-1"></i><?= $this->lang->line("Kembali"); ?></a> -->
                                    <a href="<?= base_url($this->folder . '/index/' . encrypt_url($dfrom) . '/' . encrypt_url($dto) . '/' . encrypt_url($htype) . '/' . encrypt_url($harea) . '/' . encrypt_url($hcoa)); ?>" class="btn btn-secondary round btn-min-width mr-1"><i class="icon-action-undo mr-1"></i><?= $this->lang->line("Kembali"); ?></a>
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