<style>
    .table.table-xs th,
    .table td,
    .table.table-xs td {
        padding: 0.3rem 0.3rem;
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
                                <div class="row">
                                    <!-- Baris ke 1 -->
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Nomor Dokumen"); ?> :</label>
                                            <fieldset>
                                                <div class="input-group input-group-sm">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">
                                                            <span class="fa fa-hashtag"></span>
                                                        </div>
                                                    </div>
                                                    <input type="text" name="i_document" id="i_document" readonly placeholder="SO-<?= date('ym'); ?>-000001" class="form-control form-control-sm text-uppercase" data-validation-required-message="<?= $this->lang->line("Required"); ?>" maxlength="500" autocomplete="off" required aria-label="Text input with checkbox">
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
                                                <?php if ($this->i_company == '5') { ?>
                                                    <input type="date" class="form-control form-control-sm date" min="2021-10-01" max="<?= date('Y-m-d'); ?>" <?= koncix(); ?> value="<?= date('Y-m-d'); ?>" data-validation-required-message="<?= $this->lang->line("Required"); ?>" placeholder="" id="d_document" name="d_document" maxlength="500" autocomplete="off" required>
                                                <?php } else { ?>
                                                    <input type="date" class="form-control form-control-sm date" min="2021-10-01" max="<?= date('Y-m-d'); ?>" <?= konci(); ?> value="<?= date('Y-m-d'); ?>" data-validation-required-message="<?= $this->lang->line("Required"); ?>" placeholder="" id="d_document" name="d_document" maxlength="500" autocomplete="off" required>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Nama Promo"); ?> :</label>
                                            <div class="controls">
                                                <select class="form-control" id="i_promo" required data-placeholder="<?= $this->lang->line("Pilih") . ' ' . $this->lang->line("Promo"); ?>" data-validation-required-message="<?= $this->lang->line("Required"); ?>" name="i_promo">
                                                    <option value=""></option>
                                                </select>
                                                <input type="hidden" id="f_all_product" name="f_all_product">
                                                <input type="hidden" id="f_all_customer" name="f_all_customer">
                                                <input type="hidden" id="f_all_area" name="f_all_area">
                                                <input type="hidden" id="f_plus_discount" name="f_plus_discount" value="t">
                                                <input type="hidden" id="n_promo_discount1" name="n_promo_discount1" value="0">
                                                <input type="hidden" id="n_promo_discount2" name="n_promo_discount2" value="0">
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Baris ke 2 -->
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Nama Area Provinsi"); ?> :</label>
                                            <div class="controls">
                                                <select class="form-control" id="i_area" required data-placeholder="<?= $this->lang->line("Pilih") . ' ' . $this->lang->line("Area"); ?>" data-validation-required-message="<?= $this->lang->line("Required"); ?>" name="i_area">
                                                    <option value=""></option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Nama Pelanggan"); ?> :</label>
                                            <div class="controls">
                                                <select class="form-control" name="i_customer" id="i_customer" data-placeholder="<?= $this->lang->line("Pilih") . ' ' . $this->lang->line("Pelanggan"); ?>" required data-validation-required-message="<?= $this->lang->line("Required"); ?>">
                                                    <option value=""></option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Baris ke 3 -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><?= $this->lang->line('Alamat Pelanggan'); ?> :</label>
                                            <div class="controls">
                                                <textarea class="form-control text-capitalize" placeholder="<?= $this->lang->line('Alamat Pelanggan'); ?>" id="alamat" name="alamat" autocomplete="off" readonly></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" id="i_price_group" name="i_price_group">
                                    <input type="hidden" id="ppn" name="ppn" value="<?= $this->session->f_company_plusppn; ?>">
                                    <input type="hidden" id="nppn" name="nppn" value="<?= $this->session->n_ppn; ?>">
                                    <input type="hidden" id="disc1" name="disc1">
                                    <input type="hidden" id="disc2" name="disc2">
                                    <input type="hidden" id="disc3" name="disc3">
                                    <input type="hidden" id="disc4" name="disc4">
                                    <input type="hidden" id="i_promo_type_id" name="i_promo_type_id">
                                    <input type="hidden" id="e_user_name" name="e_user_name" value="<?= $this->session->e_user_name; ?>">


                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <div class="form-group">
                                                <label><?= $this->lang->line('TOP'); ?> :</label>
                                                <div class="controls">
                                                    <input type="text" class="form-control form-control-sm text-capitalize" placeholder="<?= $this->lang->line('TOP'); ?>" id="n_so_toplength" name="n_so_toplength" autocomplete="off" readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <div class="form-group">
                                                <label><?= $this->lang->line('Kode NPWP'); ?> :</label>
                                                <div class="controls">
                                                    <input type="text" class="form-control form-control-sm text-capitalize" placeholder="<?= $this->lang->line('Kode NPWP'); ?>" id="e_customer_pkpnpwp" name="e_customer_pkpnpwp" autocomplete="off" readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Baris ke 4 -->
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <div class="form-group">
                                                <label><?= $this->lang->line('PPN'); ?> :</label>
                                                <div class="controls">
                                                    <input type="text" class="form-control form-control-sm text-capitalize" data-validation-required-message="<?= $this->lang->line('Required'); ?>" placeholder="<?= $this->lang->line('PPN'); ?>" id="eppn" name="eppn" autocomplete="off" readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <div class="form-group">
                                                <label><?= $this->lang->line('Nama Kelompok Harga'); ?> :</label>
                                                <div class="controls">
                                                    <input type="text" class="form-control form-control-sm text-capitalize" data-validation-required-message="<?= $this->lang->line('Required'); ?>" placeholder="<?= $this->lang->line('Nama Kelompok Harga'); ?>" id="epricegroup" name="epricegroup" autocomplete="off" readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Nama Pramuniaga"); ?> :</label>
                                            <div class="controls">
                                                <select class="form-control round" name="i_salesman" id="i_salesman" required data-placeholder="<?= $this->lang->line("Pilih") . ' ' . $this->lang->line("Pramuniaga"); ?>" data-validation-required-message="<?= $this->lang->line("Required"); ?>">
                                                    <option value=""></option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label><?= $this->lang->line('Stock'); ?></label>
                                            <div class="controls">
                                                <fieldset>
                                                    <div class="float-left">
                                                        <input type="checkbox" id="f_so_stockdaerah" name="f_so_stockdaerah" class="switch" data-onstyle="success" data-group-cls="btn-group-sm" data-on-label="<?= $this->lang->line('Daerah'); ?>" data-off-label="<?= $this->lang->line('Pusat'); ?>" data-switch-always />
                                                    </div>
                                                </fieldset>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Baris ke 5 -->
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <div class="form-group">
                                                <label><?= $this->lang->line('Nomor PO'); ?> :</label>
                                                <div class="controls">
                                                    <input type="text" id="i_so_po" name="i_so_po" class="form-control form-control-sm text-capitalize" placeholder="<?= $this->lang->line('Nomor PO'); ?>" autocomplete="off">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label><?= $this->lang->line('Tanggal Berakhir PO'); ?> :</label>
                                            <div class="input-group input-group-sm controls">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">
                                                        <span class="fa fa-calendar-o"></span>
                                                    </span>
                                                </div>
                                                <input id="d_po" name="d_po" type='date' min="<?= date('Y-m-d'); ?>" value="" class="form-control form-control-sm" placeholder="<?= $this->lang->line('Tanggal PO'); ?>" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Kelompok Barang"); ?> :</label>
                                            <div class="controls">
                                                <select class="form-control" name="i_product_group" id="i_product_group" required data-placeholder="<?= $this->lang->line("Pilih") . ' ' . $this->lang->line("Kelompok Barang"); ?>" data-validation-required-message="<?= $this->lang->line("Required"); ?>">
                                                    <option value=""></option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Baris ke 8 -->
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Keterangan"); ?> :</label>
                                            <textarea class="form-control text-capitalize" placeholder="<?= $this->lang->line('Keterangan'); ?>" id="e_remarkh" name="e_remarkh"></textarea>
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
                            <h4 class="card-title"><i class="fa fa-cart-arrow-down"></i> <?= $this->lang->line("Detail"); ?> <?= $this->lang->line("Pesanan Sales"); ?></h4>
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
                                                    <th class="text-center" width="2%">No</th>
                                                    <th class="text-center" width="19%" valign="center"><?= $this->lang->line("Nama Barang"); ?></th>
                                                    <th class="text-center" width="10%" valign="center"><?= $this->lang->line("Harga"); ?></th>
                                                    <th class="text-center" width="17%" valign="center"><?= $this->lang->line("Jumlah"); ?></th>
                                                    <th class="text-center" width="5%"><?= $this->lang->line("Disk1"); ?> %</th>
                                                    <th class="text-center" width="5%"><?= $this->lang->line("Disk2"); ?> %</th>
                                                    <th class="text-center" width="5%"><?= $this->lang->line("Disk3"); ?> %</th>
                                                    <th class="text-center" width="5%"><?= $this->lang->line("Disk4"); ?> %</th>
                                                    <th class="text-center" width="14%" valign="center"><?= $this->lang->line("Total"); ?></th>
                                                    <th class="text-center" width="14%" valign="center"><?= $this->lang->line("Keterangan"); ?></th>
                                                    <th class="text-center" width="4%"><i class="fa fa-plus-circle fa-lg" title="<?= $this->lang->line('Tambah'); ?>" id="addrow"></i></th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                            <tfoot>
                                                <tr>
                                                    <th colspan="8" class="text-right"><?= $this->lang->line("Sub Total"); ?> Rp. </th>
                                                    <th><input type="text" class="form-control form-control-sm text-right" id="tfoot_subtotal" name="tfoot_subtotal" value="0" readonly></th>
                                                    <th colspan="2"></th>
                                                </tr>

                                                <tr>
                                                    <th colspan="8" class="text-right hidden"><?= $this->lang->line("Diskon"); ?></th>
                                                    <th><input type="hidden" readonly autocomplete="off" class="formatrupiah form-control form-control-sm text-right" value="0" onkeyup="formatrupiahkeyup(this);change_ndisc();hitung();" onkeydown="formatrupiahkeydown(this);change_ndisc();hitung();" onblur="if(this.value==''){this.value='0';hitung();}" onfocus="if(this.value=='0'){this.value='';}" id="tfoot_n_diskon" name="tfoot_n_diskon"></th>
                                                    <th colspan="2"></th>
                                                </tr>

                                                <tr>
                                                    <th colspan="8" class="text-right hidden"><?= $this->lang->line("Diskon Rp"); ?>. </th>
                                                    <th><input type="hidden" readonly autocomplete="off" class="formatrupiah form-control form-control-sm text-right" value="0" onkeyup="formatrupiahkeyup(this);change_vdisc();hitung();" onkeydown="formatrupiahkeydown(this);change_vdisc();hitung();" onblur="if(this.value==''){this.value='0';hitung();}" onfocus="if(this.value=='0'){this.value='';}" id="tfoot_v_diskon" name="tfoot_v_diskon"></th>
                                                    <th colspan="2"></th>
                                                </tr>
                                                <?php if ($this->session->f_company_plusppn == 't') { ?>
                                                    <tr>
                                                        <th colspan="8" class="text-right"><?= $this->lang->line("DPP"); ?> Rp. </th>
                                                        <th><input type="text" class="form-control form-control-sm text-right" id="tfoot_v_dpp" name="tfoot_v_dpp" readonly></th>
                                                        <th colspan="2"></th>
                                                    </tr>

                                                    <tr>
                                                        <th colspan="8" class="text-right"><?= $this->lang->line("PPN"); ?> (<?= $this->session->n_ppn; ?>%) Rp.</th>
                                                        <th><input type="text" class="form-control form-control-sm text-right" id="tfoot_v_ppn" name="tfoot_v_ppn" readonly></th>
                                                        <th colspan="2"></th>
                                                    </tr>
                                                <?php } ?>

                                                <tr>
                                                    <th colspan="8" class="text-right"><?= $this->lang->line("Nilai Bersih"); ?> Rp. </th>
                                                    <th><input type="text" class="form-control form-control-sm text-right font-weight-bold" id="tfoot_total" name="tfoot_total" readonly></th>
                                                    <th colspan="2"></th>
                                                </tr>
                                            </tfoot>
                                            <input type="hidden" id="jml" name="jml" value="0">
                                        </table>
                                    </div>
                                </div>
                                <div class="form-actions">
                                    <button type="submit" id="submit" class="btn btn-success round btn-min-width mr-1"><i class="icon-paper-plane mr-1"></i><?= $this->lang->line("Simpan"); ?></button>
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