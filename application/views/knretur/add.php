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
                    <div class="card box-shadow-0 border-primary">
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
                                                        <input type="text" name="i_document" id="i_document" readonly placeholder="TTB-<?= date('ym'); ?>-000001" class="form-control form-control-sm text-uppercase" data-validation-required-message="<?= $this->lang->line("Required"); ?>" maxlength="500" autocomplete="off" required aria-label="Text input with checkbox">
                                                    </div>
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
                                                    <input type="date" class="form-control form-control-sm date" min="<?= get_min30(); ?>" max="<?= date('Y-m-d'); ?>" max="<?= date('Y-m-d'); ?>" value="<?= date('Y-m-d'); ?>" data-validation-required-message="<?= $this->lang->line("Required"); ?>" placeholder="" id="d_document" name="d_document" maxlength="500" autocomplete="off" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label><?= $this->lang->line("Nomor Referensi"); ?> :</label>
                                                <fieldset>
                                                    <div class="input-group input-group-sm">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">
                                                                <span class="fa fa-hashtag"></span>
                                                            </div>
                                                        </div>
                                                        <input type="hidden" readonly name="i_refference" value="<?= $data->i_bbm; ?>">
                                                        <input type="text" readonly name="i_refference_bbm" id="i_refference" value="<?= $data->i_bbm_id; ?>" placeholder="000001" class="form-control form-control-sm text-uppercase" data-validation-required-message="<?= $this->lang->line("Required"); ?>" maxlength="500" autocomplete="off" required aria-label="Text input with checkbox">
                                                    </div>
                                                </fieldset>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label><?= $this->lang->line("Tanggal Referensi"); ?> :</label>
                                                <div class="input-group input-group-sm controls">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text">
                                                            <span class="fa fa-calendar-o"></span>
                                                        </span>
                                                    </div>
                                                    <input type="text" readonly class="form-control form-control-sm date" value="<?= $data->d_bbm; ?>" data-validation-required-message="<?= $this->lang->line("Required"); ?>" placeholder="" id="d_refference" name="d_refference" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Baris ke 2 -->
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label><?= $this->lang->line("Nama Area Provinsi"); ?> :</label>
                                                <div class="controls">
                                                    <input type="hidden" id="i_area" name="i_area" value="<?= $data->i_area; ?>">
                                                    <input type="text" readonly class="form-control form-control-sm" value="<?= $data->i_area_id . ' - ' . $data->e_area_name; ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label><?= $this->lang->line("Nama Pelanggan"); ?> :</label>
                                                <div class="controls">
                                                    <input type="hidden" id="i_customer" name="i_customer" value="<?= $data->i_customer; ?>">
                                                    <input type="text" readonly class="form-control form-control-sm" value="<?= $data->i_customer_id . ' - ' . $data->e_customer_name; ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Baris ke 4 -->
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label><?= $this->lang->line('Alamat Pelanggan'); ?> :</label>
                                                <div class="controls">
                                                    <textarea class="form-control text-capitalize clear" placeholder="<?= $this->lang->line('Alamat Pelanggan'); ?>" id="address" name="address" autocomplete="off" readonly><?= $data->e_customer_address; ?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label><?= $this->lang->line("Nama Pramuniaga"); ?> :</label>
                                                <div class="controls">
                                                    <input type="hidden" id="i_salesman" name="i_salesman" value="<?= $data->i_salesman; ?>">
                                                    <input type="text" readonly class="form-control form-control-sm" value="<?= $data->i_salesman_id . ' - ' . $data->e_salesman_name; ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Baris ke 5 -->
                                    <div class="row">
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label><?= $this->lang->line("Masalah"); ?> :</label>
                                                <div class="controls">
                                                    <fieldset>
                                                        <div class="float-left">
                                                            <input type="checkbox" id="f_masalah" name="f_masalah" class="switch" data-group-cls="btn-group-sm" data-on-label="<?= $this->lang->line('Ya'); ?>" data-off-label="<?= $this->lang->line('Tidak'); ?>" data-switch-always />
                                                        </div>
                                                    </fieldset>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label><?= $this->lang->line("Insentif"); ?> :</label>
                                                <div class="controls">
                                                    <fieldset>
                                                        <div class="float-left">
                                                            <input type="checkbox" id="f_insentif" name="f_insentif" class="switch" data-group-cls="btn-group-sm" data-on-label="<?= $this->lang->line('Ya'); ?>" data-off-label="<?= $this->lang->line('Tidak'); ?>" data-switch-always />
                                                        </div>
                                                    </fieldset>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- <div class="col-sm-3">
                                            <div class="form-group">
                                                <label><?= $this->lang->line("Nomor Pajak"); ?> :</label>
                                                <select class="form-control" name="i_pajak" id="i_pajak" data-placeholder="<?= $this->lang->line("Pilih") . ' ' . $this->lang->line("Nomor Pajak"); ?>">
                                                    <option value=""></option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label><?= $this->lang->line("Tanggal Pajak"); ?> :</label>
                                                <div class="input-group input-group-sm controls">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text">
                                                            <span class="fa fa-calendar-o"></span>
                                                        </span>
                                                    </div>
                                                    <input type="text" readonly class="form-control form-control-sm" value="" placeholder="yyyy-mm-dd" id="d_pajak" name="d_pajak">
                                                </div>
                                            </div>
                                        </div> -->
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label><?= $this->lang->line("Keterangan"); ?> :</label>
                                                <textarea required class="form-control text-capitalize" placeholder="<?= $this->lang->line('Keterangan'); ?>" id="e_remark" name="e_remark"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-actions">
                                    <button type="submit" id="submit" class="btn btn-success round btn-min-width mr-1"><i class="icon-paper-plane mr-1"></i><?= $this->lang->line("Simpan"); ?></button>
                                    <a href="<?= base_url($this->folder . '/indexx/' . encrypt_url($dfrom) . '/' . encrypt_url($dto) . '/' . encrypt_url($harea)); ?>" class="btn btn-secondary round btn-min-width mr-1"><i class="icon-action-undo mr-1"></i><?= $this->lang->line("Kembali"); ?></a>
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
                                                    <th class="text-center" width="1%">No</th>
                                                    <th class="text-center" width="14%" valign="center"><?= $this->lang->line("Nama Barang"); ?></th>
                                                    <th class="text-center" width="7%"><?= $this->lang->line("Disk1"); ?> %</th>
                                                    <th class="text-center" width="4%"><?= $this->lang->line("Disk2"); ?> %</th>
                                                    <th class="text-center" width="4%"><?= $this->lang->line("Disk3"); ?> %</th>
                                                    <th class="text-center" width="7%" valign="center"><?= $this->lang->line("Qty"); ?></th>
                                                    <th class="text-center" width="11%" valign="center"><?= $this->lang->line("Harga"); ?></th>
                                                    <th class="text-center" width="14%" valign="center"><?= $this->lang->line("Total"); ?></th>
                                                    <?php if ($data->f_ttb_plusppn == 't') { ?>
                                                        <th class="text-center" width="17%" valign="center"><?= $this->lang->line("Nomor Pajak"); ?></th>
                                                        <th class="text-center" width="7%" valign="center"><?= $this->lang->line("Tanggal Pajak"); ?></th>
                                                    <?php } ?>
                                                    <th class="text-center" width="14%" valign="center"><?= $this->lang->line("Keterangan"); ?></th>
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
                                                            <td><?= $key->i_product_id . ' - ' . $key->e_product_name; ?></td>
                                                            <td><input type="text" class="form-control text-right form-control-sm" id="n_ttb_discount1<?= $i; ?>" name="n_ttb_discount1[]" value="<?= $key->n_ttb_discount1; ?>" readonly><input type="hidden" id="v_ttb_discount1<?= $i; ?>" name="v_ttb_discount1[]" value="0" readonly></td>
                                                            <td><input type="text" class="form-control text-right form-control-sm" id="n_ttb_discount2<?= $i; ?>" name="n_ttb_discount2[]" value="<?= $key->n_ttb_discount2; ?>" readonly><input type="hidden" id="v_ttb_discount2<?= $i; ?>" name="v_ttb_discount2[]" value="0" readonly></td>
                                                            <td><input type="text" class="form-control text-right form-control-sm" id="n_ttb_discount3<?= $i; ?>" name="n_ttb_discount3[]" value="<?= $key->n_ttb_discount3; ?>" readonly><input type="hidden" id="v_ttb_discount3<?= $i; ?>" name="v_ttb_discount3[]" value="0" readonly></td>
                                                            <td><input type="text" autocomplete="off" class="form-control text-right n_quantity form-control-sm" id="n_quantity<?= $i; ?>" value="<?= $key->n_quantity; ?>" name="n_quantity[]" readonly onkeypress="return bilanganasli(event);hetang();" onkeyup="hetang(); cek(<?= $i; ?>); hetangdiskonrp(); hetangdiskon();" onblur="if(this.value=='' ){this.value='0' ;hetang();}" onfocus="if(this.value=='0' ){this.value='' ;}"></td>
                                                            <td><input type="text" class="form-control text-right form-control-sm" id="v_unit_price<?= $i; ?>" name="v_unit_price[]" value="<?= number_format($key->v_unit_price); ?>" readonly></td>
                                                            <td><input type="text" class="form-control text-right form-control-sm" id="total_item<?= $i; ?>" name="total_item[]" readonly></td>
                                                            <?php if ($data->f_ttb_plusppn == 't') { ?>
                                                                <td><input type="text" class="form-control text-right form-control-sm" id="i_seri_pajak<?= $i; ?>" name="i_seri_pajak[]" value="<?= $key->i_seri_pajak; ?>" readonly></td>
                                                                <td><input type="text" class="form-control text-right form-control-sm" id="d_nota<?= $i; ?>" name="d_nota[]" value="<?= $key->d_pajak; ?>" readonly></td>
                                                            <?php } ?>
                                                            <td><input type="text" class="form-control form-control-sm" name="e_ttb_remark[]" value="TTB Retur"></td>
                                                        </tr>
                                                <?php }
                                                } ?>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th colspan="7" class="text-right"><?= $this->lang->line("Sub Total"); ?> Rp. </th>
                                                    <th><input type="text" class="form-control form-control-sm text-right tfoot" id="v_ttb_gross" name="v_gross" value="0" readonly></th>
                                                    <th colspan="2"></th>
                                                </tr>

                                                <!-- <tr>
                                                    <th colspan="7" class="text-right"><?= $this->lang->line("Diskon"); ?></th>
                                                    <th><input type="text" autocomplete="off" readonly class="formatrupiah form-control form-control-sm text-right tfoot" value="0" onkeyup="formatrupiahkeyup(this);hetangdiskon();" onkeydown="formatrupiahkeydown(this);hetangdiskon();" onblur="if(this.value==''){this.value='0';hetang();}" onfocus="if(this.value=='0'){this.value='';}" id="n_ttb_discounttotal" name="n_ttb_discounttotal"></th>
                                                    <th colspan="2"></th>
                                                </tr>

                                                <tr>
                                                    <th colspan="7" class="text-right"><?= $this->lang->line("Diskon Rp"); ?>. </th>
                                                    <th><input type="text" autocomplete="off" readonly class="formatrupiah form-control form-control-sm text-right tfoot" value="<?= number_format($data->v_ttb_discounttotal); ?>" onkeyup="formatrupiahkeyup(this);hetangdiskonrp();" onkeydown="formatrupiahkeydown(this);hetangdiskonrp();" onblur="if(this.value==''){this.value='0';hetang();}" onfocus="if(this.value=='0'){this.value='';}" id="v_ttb_discounttotal" name="v_discount"></th>
                                                    <th colspan="2"></th>
                                                </tr> -->
                                                <input type="hidden" autocomplete="off" readonly class="formatrupiah form-control form-control-sm text-right tfoot" value="0" onkeyup="formatrupiahkeyup(this);hetangdiskon();" onkeydown="formatrupiahkeydown(this);hetangdiskon();" onblur="if(this.value==''){this.value='0';hetang();}" onfocus="if(this.value=='0'){this.value='';}" id="n_ttb_discounttotal" name="n_ttb_discounttotal">
                                                <input type="hidden" autocomplete="off" readonly class="formatrupiah form-control form-control-sm text-right tfoot" value="<?= number_format($data->v_ttb_discounttotal); ?>" onkeyup="formatrupiahkeyup(this);hetangdiskonrp();" onkeydown="formatrupiahkeydown(this);hetangdiskonrp();" onblur="if(this.value==''){this.value='0';hetang();}" onfocus="if(this.value=='0'){this.value='';}" id="v_ttb_discounttotal" name="v_discount">
                                                <tr>
                                                    <th colspan="7" class="text-right"><?= $this->lang->line("Diskon Per Item"); ?> Rp. </th>
                                                    <th><input type="text" class="form-control form-control-sm text-right tfoot" id="distotal" name="distotal" readonly></th>
                                                    <th colspan="2"></th>
                                                </tr>
                                                <?php if ($data->f_ttb_plusppn == 't') { ?>
                                                    <tr>
                                                        <th colspan="7" class="text-right"><?= $this->lang->line("DPP"); ?> Rp. </th>
                                                        <th><input type="text" class="form-control form-control-sm text-right tfoot" id="v_ttb_dpp" name="v_ttb_dpp" readonly></th>
                                                        <th colspan="2"></th>
                                                    </tr>

                                                    <tr>
                                                        <th colspan="7" class="text-right"><?= $this->lang->line("PPN"); ?> ( %)</th>
                                                        <th><input type="text" readonly autocomplete="off" class="formatrupiah form-control form-control-sm font-weight-bold tfoot" value="<?= number_format($data->n_ppn_r); ?>" onkeyup="formatrupiahkeyup(this);hetangdiskon();" onkeydown="formatrupiahkeydown(this);hetangdiskon();" onblur="if(this.value==''){this.value='0';hetang();}" onfocus="if(this.value=='0'){this.value='';}" id="n_ppn" name="n_ppn"></th>
                                                        <th colspan="2"></th>
                                                    </tr>

                                                    <tr>
                                                        <!-- <th colspan="7" class="text-right"><?= $this->lang->line("PPN"); ?> (<?= $this->session->n_ppn; ?>%) Rp.</th> -->
                                                        <th colspan="7" class="text-right"><?= $this->lang->line("PPN"); ?> Rp.</th>
                                                        <th><input type="text" class="form-control form-control-sm text-right tfoot" id="v_ttb_ppn" name="v_ttb_ppn" readonly></th>
                                                        <th colspan="2"></th>
                                                    </tr>
                                                <?php } ?>

                                                <tr>
                                                    <th colspan="7" class="text-right"><?= $this->lang->line("Nilai Bersih"); ?> Rp. </th>
                                                    <th><input type="text" class="form-control form-control-sm text-right font-weight-bold tfoot" id="v_ttb_netto" name="v_netto" readonly></th>
                                                    <th colspan="2"></th>
                                                </tr>
                                            </tfoot>
                                            <input type="hidden" id="jml" name="jml" value="<?= $i; ?>">
                                            <input type="hidden" id="f_ttb_pkp" name="f_ttb_pkp" value="<?= $data->f_ttb_pkp; ?>">
                                            <input type="hidden" id="f_ttb_plusdiscount" name="f_ttb_plusdiscount">
                                            <input type="hidden" id="f_ttb_plusppn" name="f_ttb_plusppn" value="<?= $data->f_ttb_plusppn; ?>">
                                            <input type="hidden" id="n_ppn" name="n_ppn" value="<?= $this->session->n_ppn; ?>">
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</form>