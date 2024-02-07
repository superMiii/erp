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
                        <li class="breadcrumb-item"><a href="<?= base_url($this->folder . '/index/' . encrypt_url($dfrom) . '/' . encrypt_url($dto) . '/' . encrypt_url($harea)); ?>"><?= $this->lang->line($this->title); ?></a></li>
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
                    <div class="card box-shadow-2">
                        <div class="card-header card-head-inverse <?= $this->session->e_color; ?> bg-darken-1 text-white">
                            <h4 class="card-title"><i class="icon-plus"></i> <?= $this->lang->line('Tambah'); ?> <?= $this->lang->line($this->title); ?></h4>
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
                                                <div class="input-group input-group-sm controls">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">
                                                            <span class="fa fa-calendar-o"></span>
                                                        </span>
                                                    </div>
                                                    <input type="date" readonly class="form-control form-control-sm date" value="<?= $data->d_alokasi; ?>" data-validation-required-message="<?= $this->lang->line("Required"); ?>" placeholder="" id="d_document" name="d_document" maxlength="500" autocomplete="off" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label><?= $this->lang->line("Nomor KN"); ?> :</label>
                                                <fieldset>
                                                    <div class="input-group input-group-sm">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">
                                                                <span class="fa fa-hashtag"></span>
                                                            </div>
                                                        </div>
                                                        <input type="hidden" readonly name="i_kn" id="i_kn" value="<?= $data->i_kn; ?>">
                                                        <input type="text" readonly name="i_kn_id" id="i_kn_id" value="<?= $data->i_kn_id; ?>" class="form-control form-control-sm text-uppercase">
                                                    </div>
                                                </fieldset>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label><?= $this->lang->line("Tanggal KN"); ?> :</label>
                                                <div class="input-group input-group-sm controls">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">
                                                            <span class="fa fa-calendar-o"></span>
                                                        </span>
                                                    </div>
                                                    <input type="date" readonly class="form-control form-control-sm" value="<?= $data->d_kn; ?>" id="d_kn" name="d_kn">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Baris ke 2 -->
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label><?= $this->lang->line("Area"); ?> :</label>
                                                <input type="hidden" readonly class="form-control form-control-sm" value="<?= $data->i_area; ?>" id="i_area" name="i_area">
                                                <input type="text" readonly class="form-control form-control-sm" value="<?= $data->e_area_name; ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label><?= $this->lang->line("Nama Pelanggan"); ?> :</label>
                                                <input type="hidden" readonly class="form-control form-control-sm" value="<?= $data->i_customer; ?>" id="i_customer" name="i_customer">
                                                <input type="text" readonly class="form-control form-control-sm" value="<?= $data->i_customer_id . ' - ' . $data->e_customer_name; ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label><?= $this->lang->line("Pramuniaga"); ?> :</label>
                                                <input type="hidden" readonly class="form-control form-control-sm" value="<?= $data->i_salesman; ?>" id="i_salesman" name="i_salesman">
                                                <input type="text" readonly class="form-control form-control-sm" value="<?= $data->e_salesman_name; ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label><?= $this->lang->line("Jumlah"); ?> :</label>
                                                <input type="text" readonly name="v_jumlah" id="vjumlah" class="form-control form-control-sm" value="<?= number_format($data->v_netto); ?>">
                                                <input type="hidden" id="vlebih" name="v_lebih" value="0">
                                                <input type="hidden" id="vsisa" name="vsisa" value="<?= $data->v_netto; ?>">
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
                            <h4 class="card-title"><i class="fa fa-cart-arrow-down"></i> <?= $this->lang->line("Detail"); ?> <?= $this->lang->line("Nota"); ?></h4>
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
                                                    <th class="text-center" width="12%"><?= $this->lang->line("Sisa"); ?></th>
                                                    <th class="text-center" width="12%"><?= $this->lang->line("Lebih"); ?></th>
                                                    <th class="text-center"><?= $this->lang->line("Keterangan"); ?></th>
                                                    <!-- <th class="text-center" width="3%"><i class="fa fa-plus-circle fa-lg" title="<?= $this->lang->line('Ubah'); ?>" id="addrow"></i></th> -->
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $i = 0;
                                                if ($detail->num_rows() > 0) {
                                                    foreach ($detail->result() as $key) {
                                                        $i++; ?>
                                                        <tr>
                                                            <td class="text-center" valign="center">
                                                                <spanx id="snum<?= $i; ?>"><?= $i; ?></spanx>
                                                            </td>
                                                            <!-- <td>
                                                                <select data-nourut="<?= $i; ?>" required class="form-control select2-size-sm" name="i_nota[]" id="i_nota<?= $i; ?>">
                                                                    <option value="<?= $key->i_nota; ?>"><?= $key->i_nota_id; ?></option>
                                                                </select>
                                                            </td> -->
                                                            <td><input readonly class="form-control form-control-sm" type="text" id="i_nota<?= $i; ?>" name="i_nota[]" value="<?= $key->i_nota_id; ?>"></td>
                                                            <td><input type="text" value="<?= $key->dnota; ?>" readonly class="form-control form-control-sm" id="dnota<?= $i; ?>" readonly><input type="hidden" value="<?= $key->d_nota; ?>" readonly class="form-control form-control-sm" id="d_nota<?= $i; ?>" name="d_nota[]" readonly></td>
                                                            <td><input readonly class="form-control form-control-sm text-right" type="text" id="vnota<?= $i; ?>" name="vnota[]" value="<?= number_format($key->v_nota); ?>"></td>
                                                            <!-- <td><input autocomplete="off" class="form-control form-control-sm text-right" type="text" id="vjumlah<?= $i; ?>" name="vjumlah[]" value="<?= number_format($key->v_jumlah); ?>" onkeydown="reformat(this);hetang();" onkeyup="onlyangka(this); reformat(this); hetang();" onpaste="return false;" onblur=\"if(this.value=='' ){this.value='0' ;hetang();}\" onfocus=\"if(this.value=='0' ){this.value='' ;}\"></td> -->
                                                            <td><input readonly class="form-control form-control-sm text-right" type="text" id="vjumlah<?= $i; ?>" name="vjumlah[]" value="<?= number_format($key->v_jumlah); ?>"></td>
                                                            <td><input readonly class="form-control form-control-sm text-right" type="text" id="vsesa<?= $i; ?>" name="vsesa[]" value="<?= number_format($key->v_sisa); ?>"><input type="hidden" id="vsisa<?= $i; ?>" name="vsisa<?= $i; ?>" value="<?= number_format($key->v_nota); ?>"></td>
                                                            <td><input readonly class="form-control form-control-sm text-right" type="text" id="vlebih<?= $i; ?>" name="vlebih[]" value="0"></td>
                                                            <td><input readonly class="form-control form-control-sm" type="text" id="eremark<?= $i; ?>" name="eremark[]" value="<?= $key->e_remark; ?>"></td>
                                                            <!-- <td>
                                                                <select class="form-control form-control-sm" id="eremark<?= $i; ?>" name="eremark[]">
                                                                    <option value="" <?php if ($key->e_remark == '' || $key->e_remark == null) {
                                                                                            echo 'selected';
                                                                                        } ?>></option>
                                                                    <option value="-" <?php if ($key->e_remark == '-') {
                                                                                            echo 'selected';
                                                                                        } ?>></option>
                                                                    <option value="Retur" <?php if ($key->e_remark == 'Retur') {
                                                                                                echo 'selected';
                                                                                            } ?>>Retur</option>
                                                                    <option value="Biaya Promo" <?php if ($key->e_remark == 'Biaya Promo') {
                                                                                                    echo 'selected';
                                                                                                } ?>>Biaya Promo</option>
                                                                    <option value="Kurang Bayar" <?php if ($key->e_remark == 'Kurang Bayar') {
                                                                                                        echo 'selected';
                                                                                                    } ?>>Kurang Bayar</option>
                                                                    <option value="Biaya Ekspedisi" <?php if ($key->e_remark == 'Biaya Ekspedisi') {
                                                                                                        echo 'selected';
                                                                                                    } ?>>Biaya Ekspedisi</option>
                                                                    <option value="Biaya Administrasi" <?php if ($key->e_remark == 'Biaya Administrasi') {
                                                                                                            echo 'selected';
                                                                                                        } ?>>Biaya Administrasi</option>
                                                                </select>
                                                            </td>
                                                            <td class="text-center"><i title="Delete" class="fa fa-minus-circle fa-lg text-danger ibtnDel"></i></td> -->
                                                        </tr>
                                                <?php }
                                                } ?>
                                            </tbody>
                                            <!-- <tfoot>
                                                <tr>
                                                    <th class="text-right" colspan="3"><?= $this->lang->line('Total'); ?></th>
                                                    <th><input type="text" class="form-control form-control-sm text-right" name="v_jumlah" id="v_jumlah" value="0" readonly></th>
                                                </tr>
                                            </tfoot> -->
                                            <input type="hidden" id="jml" name="jml" value="<?= $i; ?>">
                                        </table>
                                    </div>
                                </div>
                                <div class="form-actions">
                                    <a href="<?= base_url($this->folder . '/index/' . encrypt_url($dfrom) . '/' . encrypt_url($dto) . '/' . encrypt_url($harea)); ?>" class="btn btn-secondary round btn-min-width mr-1"><i class="icon-action-undo mr-1"></i><?= $this->lang->line("Kembali"); ?></a>
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