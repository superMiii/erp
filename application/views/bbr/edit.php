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
                        <li class="breadcrumb-item"><a href="#" onclick="return false;"><?= $this->lang->line('Pembelian'); ?></a></li>
                        <li class="breadcrumb-item"><a href="<?= base_url($this->folder . '/index/' . encrypt_url($dfrom) . '/' . encrypt_url($dto) . '/' . encrypt_url($i_supplier)); ?>"><?= $this->lang->line($this->title); ?></a></li>
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
                    <div class="card">
                        <div class="card-header header-elements-inline <?= $this->session->e_color; ?> bg-darken-1 text-white">
                            <h4 class="card-title"><i class="icon-pencil"></i> <?= $this->lang->line('Ubah'); ?> <?= $this->lang->line($this->title); ?></h4>
                            <input type="hidden" id="path" value="<?= $this->folder; ?>">
                            <input type="hidden" id="d_from" value="<?= encrypt_url($dfrom); ?>">
                            <input type="hidden" id="d_to" value="<?= encrypt_url($dto); ?>">
                            <input type="hidden" id="hsup" value="<?= encrypt_url($i_supplier); ?>">
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
                                                    <div class="input-group-prepend" hidden="">
                                                        <div class="input-group-text">
                                                            <input type="checkbox" id="ceklis" aria-label="Checkbox for following text input">
                                                        </div>
                                                    </div>
                                                    <input type="hidden" name="id" id="id" readonly value="<?= $data->i_bbr; ?>">
                                                    <input type="hidden" name="i_document_old" id="i_document_old" readonly value="<?= $data->i_bbr_id; ?>">
                                                    <input type="text" name="i_document" id="i_document" readonly value="<?= $data->i_bbr_id; ?>"  placeholder="PO-<?= date('ym'); ?>-000001" class="form-control form-control-sm text-uppercase" data-validation-required-message="<?= $this->lang->line("Required"); ?>" maxlength="500" autocomplete="off" required aria-label="Text input with checkbox">
                                                </div>
                                            </fieldset>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Tanggal Dokumen"); ?> :</label>
                                            <div class="input-group input-group-sm controls">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">
                                                        <span class="fa fa-calendar-o"></span>
                                                    </span>
                                                </div>
                                                <input type="date" <?= koncix(); ?> class="form-control form-control-sm" min="<?= date('Y-m-d', strtotime("-30 day", strtotime(date('Y-m-d')))); ?>" max="<?= date('Y-m-d'); ?>" data-validation-required-message="<?= $this->lang->line("Required"); ?>" placeholder="" id="d_document" name="d_document" maxlength="500" value="<?= $data->d_bbr; ?>" autocomplete="off" required>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Nama Pemasok"); ?> :</label>
                                            <div class="controls">
                                                <select class="form-control" name="i_supplier" id="i_supplier" data-placeholder="<?= $this->lang->line("Pilih") . ' ' . $this->lang->line("Nama Pemasok"); ?>" required data-validation-required-message="<?= $this->lang->line("Required"); ?>">
                                                    <option value="<?= $data->i_supplier; ?>"><?= $data->e_supplier_name; ?></option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>


                                </div>

                                <!-- Baris Ke 3 -->
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label><?= $this->lang->line('Remark'); ?> :</label>
                                            <div class="controls">
                                                <textarea required class="form-control text-capitalize" placeholder="<?= $this->lang->line('Remark'); ?>" id="e_po_remark" name="e_po_remark" autocomplete="off"><?= $data->e_po_remark; ?></textarea>
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
                    <div class="card">
                        <div class="card-header header-elements-inline <?= $this->session->e_color; ?> bg-darken-1 text-white">
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
                                                    <th class="text-center" width="5%" valign="center">No</th>
                                                    <th class="text-center" width="30%" valign="center"><?= $this->lang->line("Nama Barang"); ?></th>
                                                    <!-- <th class="text-center" width="12%" valign="center"><?= $this->lang->line("Motif"); ?></th> -->
                                                    <th class="text-center" width="12%" valign="center"><?= $this->lang->line("Harga"); ?></th>
                                                    <th class="text-center" width="8%" valign="center"><?= $this->lang->line("Stock"); ?></th>
                                                    <th class="text-center" width="8%" valign="center"><?= $this->lang->line("Qty"); ?></th>
                                                    <th class="text-center" width="14%" valign="center"><?= $this->lang->line("Total"); ?></th>
                                                    <th width="12%"><?= $this->lang->line("Keterangan"); ?></th>
                                                    <th class="text-center" width="5%"><i class="fa fa-plus-circle fa-lg" title="<?= $this->lang->line('Tambah'); ?>" id="addrow"></i></th>
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
                                                            <td>
                                                                <select data-nourut="<?= $i; ?>" required class="form-control select2-size-sm" name="i_product[]" id="i_product<?= $i; ?>">
                                                                    <option value="<?= $key->i_product; ?>"><?= $key->i_product_id . ' - ' . $key->e_product_name; ?></option>
                                                                </select>
                                                            </td>
                                                            <td><input type="text" autocomplete="off" class="form-control text-right v_unit_price form-control-sm" id="v_unit_price<?= $i; ?>" name="v_unit_price[]" value="<?= number_format($key->v_unit_price); ?>"  onkeypress="return bilanganasli(event);hetang();" onkeyup="hetang();" onblur="if(this.value=='' ){this.value='0';}" onfocus="if(this.value=='0' ){this.value='' ;}"></td>
                                                            <td><input type="text" class="form-control form-control-sm" id="st<?= $i; ?>" name="st[]" value="<?= $key->st; ?>" readonly></td>
                                                            <td><input type="number" autocomplete="off" class="form-control text-center n_order form-control-sm" id="n_order<?= $i; ?>" value="<?= $key->n_quantity; ?>" name="n_order[]"  onkeyup="hetang();miru(<?= $i; ?>);" onblur="if(this.value=='' ){this.value='0';}" onfocus="if(this.value=='0' ){this.value='' ;}"></td>
                                                            <td><input type="text" class="form-control text-right form-control-sm" id="total_item<?= $i; ?>" name="total_item[]" readonly></td>
                                                            <td>
                                                            <input type="hidden" name="i_product_motif[]" id="i_product_motif<?= $i; ?>" value="<?= $key->i_product_motif; ?>">
                                                            <input type="hidden" name="i_product_grade[]" id="i_product_grade<?= $i; ?>" value="<?= $key->i_product_grade; ?>">            
                                                            <input type="hidden" name="e_product_name[]" id="e_product_name<?= $i; ?>" value="<?= $key->e_product_name; ?>">  
                                                            <input type="hidden" class="form-control text-right form-control-sm" id="e_product_motifname<?= $i; ?>" name="e_product_motifname[]" value="<?= $key->e_product_motifname; ?>" readonly>
                                                            <input type="text" class="form-control form-control-sm" id="ket<?= $i; ?>" name="ket[]" value="<?= $key->ket; ?>">
                                                            </td>
                                                            <td class="text-center"><i title="Delete" class="fa fa-minus-circle fa-lg text-danger ibtnDel"></i></td>
                                                        </tr>
                                                <?php }
                                            } ?>

                                            </tbody>
                                            <tfoot>
                                                <tr hidden>
                                                    <th colspan="5" class="text-right"><?= $this->lang->line("Sub Total"); ?></th>
                                                    <th><input type="text" class="form-control form-control-sm text-right" id="tfoot_subtotal" name="tfoot_subtotal" value="0" readonly></th>
                                                </tr>

                                                <tr hidden>
                                                    <th colspan="5" class="text-right"><?= $this->lang->line("Diskon"); ?></th>
                                                    <th><input type="text" class="formatrupiah form-control form-control-sm text-right" value="0" onkeyup="formatrupiahkeyup(this);change_ndisc();hitung();" onkeydown="formatrupiahkeydown(this);change_ndisc();hitung();" onblur="if(this.value==''){this.value='0';hitung();}" onfocus="if(this.value=='0'){this.value='';}" id="tfoot_n_diskon" name="tfoot_n_diskon"></th>
                                                </tr hidden>

                                                <tr hidden>
                                                    <th colspan="5" class="text-right"><?= $this->lang->line("Diskon Rp"); ?></th>
                                                    <th><input type="text" class="formatrupiah form-control form-control-sm text-right" value="0" onkeyup="formatrupiahkeyup(this);change_vdisc();hitung();" onkeydown="formatrupiahkeydown(this);change_vdisc();hitung();" onblur="if(this.value==''){this.value='0';hitung();}" onfocus="if(this.value=='0'){this.value='';}" id="tfoot_v_diskon" name="tfoot_v_diskon"></th>
                                                </tr>
                                                <tr hidden>
                                                    <th colspan="5" class="text-right"><?= $this->lang->line("DPP"); ?></th>
                                                    <th><input type="text" class="form-control form-control-sm text-right" id="tfoot_v_dpp" name="tfoot_v_dpp" readonly></th>
                                                </tr>

                                                <tr hidden>
                                                    <th colspan="5" class="text-right"><?= $this->lang->line("PPN"); ?></th>
                                                    <th><input type="text" class="form-control form-control-sm text-right" id="tfoot_v_ppn" name="tfoot_v_ppn" readonly></th>
                                                </tr>

                                                <tr>
                                                    <th colspan="5" class="text-right"><?= $this->lang->line("Total"); ?></th>
                                                    <th><input type="text" class="form-control form-control-sm text-right" id="tfoot_total" name="tfoot_total" readonly></th>
                                                </tr>
                                            </tfoot>
                                            <input type="hidden" id="jml" name="jml"  value="<?= $i; ?>">
                                        </table>
                                    </div>
                                </div>
                                <div class="form-actions">
                                    <button type="submit" id="submit" class="btn btn-warning round btn-min-width mr-1"><i class="icon-paper-plane mr-1"></i><?= $this->lang->line("Ubah"); ?></button>
                                    <a href="<?= base_url($this->folder . '/index/' . encrypt_url($dfrom) . '/' . encrypt_url($dto) . '/' . encrypt_url($i_supplier)); ?>" class="btn btn-secondary round btn-min-width mr-1"><i class="icon-action-undo mr-1"></i><?= $this->lang->line("Kembali"); ?></a>
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