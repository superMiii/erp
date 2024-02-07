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
                        <li class="breadcrumb-item"><a href="<?= base_url($this->folder . '/index/' . encrypt_url($dfrom) . '/' . encrypt_url($dto) . '/' . encrypt_url($harea)); ?>"><?= $this->lang->line($this->title); ?></a></li>
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
                                                    <input type="hidden" name="id" id="id" class="form-control" value="<?= $data->i_so; ?>">
                                                    <input type="hidden" name="i_periode" id="i_periode" class="form-control" value="<?= date('Ym', strtotime($data->d_so)); ?>">
                                                    <input type="hidden" name="i_document_old" id="i_document_old" class="form-control" value="<?= $data->i_so_id; ?>">
                                                    <input type="text" name="i_document" id="i_document" readonly value="<?= $data->i_so_id; ?>" placeholder="SO-<?= date('ym'); ?>-000001" class="form-control form-control-sm text-uppercase" data-validation-required-message="<?= $this->lang->line("Required"); ?>" maxlength="500" autocomplete="off" required aria-label="Text input with checkbox">
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
                                                <input type="date" class="form-control form-control-sm" value="<?= date('Y-m-d', strtotime($data->d_so)); ?>" <?= konci(); ?> data-validation-required-message="<?= $this->lang->line("Required"); ?>" placeholder="" id="d_document" name="d_document" maxlength="500" autocomplete="off" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-5">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Nama Area Provinsi"); ?> :</label>
                                            <div class="controls">
                                                <input type="hidden" class="form-control form-control-sm" id="i_area" name="i_area" readonly value="<?= $data->i_area; ?>">
                                                <input type="text" class="form-control form-control-sm" id="e_area" name="e_area" readonly value="<?= $data->i_area_id . ' - ' . $data->e_area_name; ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Baris ke 2 -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Nama Pelanggan"); ?> :</label>
                                            <div class="controls">
                                                <input type="hidden" class="form-control form-control-sm" id="i_customer" name="i_customer" readonly value="<?= $data->i_customer; ?>">
                                                <input type="text" class="form-control form-control-sm" id="e_customer" name="e_customer" readonly value="<?= $data->i_customer_id . ' - ' . $data->e_customer_name; ?>">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><?= $this->lang->line('Alamat Pelanggan'); ?> :</label>
                                            <div class="controls">
                                                <textarea class="form-control text-capitalize" placeholder="<?= $this->lang->line('Alamat Pelanggan'); ?>" id="alamat" name="alamat" autocomplete="off" readonly><?= $data->e_customer_address; ?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Bari ke 3 -->
                                <div class="row">
                                    <input type="hidden" id="i_price_group" name="i_price_group" value="<?= $data->i_price_group; ?>">
                                    <input type="hidden" id="ppn" name="ppn" value="<?= $data->f_so_plusppn; ?>">
                                    <input type="hidden" id="disc" name="disc" value="<?= $data->n_customer_discount1; ?>">
                                    <input type="hidden" id="disc2" name="disc2" value="<?= $data->n_customer_discount2; ?>">
                                    <input type="hidden" id="disc3" name="disc3" value="<?= $data->n_customer_discount3; ?>">

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <div class="form-group">
                                                <label><?= $this->lang->line('TOP'); ?> :</label>
                                                <div class="controls">
                                                    <input type="text" value="<?= $data->n_so_toplength; ?>" class="form-control form-control-sm text-capitalize" placeholder="<?= $this->lang->line('TOP'); ?>" id="n_so_toplength" name="n_so_toplength" autocomplete="off" readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <div class="form-group">
                                                <label><?= $this->lang->line('Kode NPWP'); ?> :</label>
                                                <div class="controls">
                                                    <input type="text" value="<?= $data->e_customer_pkpnpwp; ?>" class="form-control form-control-sm text-capitalize" placeholder="<?= $this->lang->line('Kode NPWP'); ?>" id="e_customer_pkpnpwp" name="e_customer_pkpnpwp" autocomplete="off" readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <div class="form-group">
                                                <label><?= $this->lang->line('PPN'); ?> :</label>
                                                <div class="controls">
                                                    <input type="text" value="<?= $data->eppn; ?>" class="form-control form-control-sm text-capitalize" data-validation-required-message="<?= $this->lang->line('Required'); ?>" placeholder="<?= $this->lang->line('PPN'); ?>" id="eppn" name="eppn" autocomplete="off" readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <div class="form-group">
                                                <label><?= $this->lang->line('Nama Kelompok Harga'); ?> :</label>
                                                <div class="controls">
                                                    <input type="text" value="<?= $data->e_price_groupname; ?>" class="form-control form-control-sm text-capitalize" data-validation-required-message="<?= $this->lang->line('Required'); ?>" placeholder="<?= $this->lang->line('Nama Kelompok Harga'); ?>" id="epricegroup" name="epricegroup" autocomplete="off" readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Baris ke 4 -->
                                <div class="row">

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Nama Pramuniaga"); ?> :</label>
                                            <div class="controls">
                                                <input type="hidden" class="form-control form-control-sm" id="i_salesman" name="i_salesman" readonly value="<?= $data->i_salesman; ?>">
                                                <input type="text" class="form-control form-control-sm" id="e_salesman" name="e_salesman" readonly value="<?= $data->i_salesman_id . ' - ' . $data->e_salesman_name; ?>">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label><?= $this->lang->line('Stock'); ?></label>
                                            <div class="controls">
                                                <fieldset>
                                                    <div class="float-left">
                                                        <input type="hidden" id="f_so_stockdaerah" name="f_so_stockdaerah" value="<?= $data->f_so_stockdaerah; ?>">
                                                        <input type="checkbox" disabled <?php if ($data->f_so_stockdaerah == 't') {
                                                                                            echo "checked";
                                                                                        } ?> class="switch" data-group-cls="btn-group-sm" data-on-label="<?= $this->lang->line('Daerah'); ?>" data-off-label="<?= $this->lang->line('Pusat'); ?>" />
                                                    </div>
                                                </fieldset>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <div class="form-group">
                                                <label><?= $this->lang->line('Nomor PO'); ?> :</label>
                                                <div class="controls">
                                                    <input type="text" id="i_so_po" name="i_so_po" value="<?= $data->e_po_reff; ?>" class="form-control form-control-sm text-capitalize" placeholder="<?= $this->lang->line('Nomor PO'); ?>" autocomplete="off" readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label><?= $this->lang->line('Tanggal PO'); ?> :</label>
                                            <div class="input-group input-group-sm controls">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">
                                                        <span class="fa fa-calendar-o"></span>
                                                    </span>
                                                </div>
                                                <input id="d_po" name="d_po" type='date' value="<?= date('Y-m-d', strtotime($data->d_po_reff)); ?>" <?= konci(); ?> class="form-control form-control-sm" placeholder="<?= $this->lang->line('Tanggal PO'); ?>" />
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Baris ke 5 -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Kelompok Barang"); ?> :</label>
                                            <div class="controls">
                                                <input type="hidden" class="form-control form-control-sm" id="i_product_group" name="i_product_group" readonly value="<?= $data->i_product_group; ?>">
                                                <input type="text" class="form-control form-control-sm" id="e_product_group" name="e_product_group" readonly value="<?= $data->i_product_groupid . ' - ' . $data->e_product_groupname; ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Keterangan"); ?> :</label>
                                            <div class="controls">
                                                <input readonly class="form-control form-control-sm" value="<?= $data->e_remark; ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Gudang"); ?> :</label>
                                            <div class="controls">
                                                <select class="form-control" name="i_store_loc" id="i_store_loc" required data-placeholder="<?= $this->lang->line("Pilih") . ' ' . $this->lang->line("Gudang"); ?>" data-validation-required-message="<?= $this->lang->line("Required"); ?>">
                                                    <option value=""></option>
                                                </select>
                                                <input type="hidden" class="form-control form-control-sm" id="i_store" name="i_store" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label><?= $this->lang->line('Buat OP'); ?></label>
                                            <div class="controls">
                                                <fieldset>
                                                    <div class="float-left">
                                                        <input type="checkbox" id="f_request_op" name="f_request_op" class="switch" data-onstyle="success" data-group-cls="btn-group-sm" data-on-label="<?= $this->lang->line('Ya'); ?>" data-off-label="<?= $this->lang->line('Tidak'); ?>" data-switch-always unchecked />
                                                    </div>
                                                </fieldset>
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
                                                    <th rowspan="1" class="text-center" width="3%" valign="center">No</th>
                                                    <th rowspan="1" class="text-center" width="37%" valign="center"><?= $this->lang->line("Nama Barang"); ?></th>
                                                    <th rowspan="1" class="text-center" width="15%" valign="center"><?= $this->lang->line("Qty"); ?></th>
                                                    <th rowspan="1" class="text-center" width="15%" valign="center"><?= $this->lang->line("Perkiraan Stok"); ?></th>
                                                    <th rowspan="1" class="text-center" width="15%" valign="center"><?= $this->lang->line("Jumlah OP"); ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                            <tfoot hidden>
                                                <tr>
                                                    <th colspan="10" class="text-right"><?= $this->lang->line("Sub Total"); ?></th>
                                                    <th><input type="text" class="form-control form-control-sm text-right" id="tfoot_subtotal" name="tfoot_subtotal" value="0" readonly></th>
                                                </tr>

                                                <tr>
                                                    <th colspan="10" class="text-right"><?= $this->lang->line("Diskon"); ?></th>
                                                    <th><input type="text" class="formatrupiah form-control form-control-sm text-right" value="0" onkeyup="formatrupiahkeyup(this);change_ndisc();hitung();" onkeydown="formatrupiahkeydown(this);change_ndisc();hitung();" onblur="if(this.value==''){this.value='0';hitung();}" onfocus="if(this.value=='0'){this.value='';}" id="tfoot_n_diskon" name="tfoot_n_diskon"></th>
                                                </tr>

                                                <tr>
                                                    <th colspan="10" class="text-right"><?= $this->lang->line("Diskon Rp"); ?></th>
                                                    <th><input type="text" readonly class="formatrupiah form-control form-control-sm text-right" value="0" onkeyup="formatrupiahkeyup(this);change_vdisc();hitung();" onkeydown="formatrupiahkeydown(this);change_vdisc();hitung();" onblur="if(this.value==''){this.value='0';hitung();}" onfocus="if(this.value=='0'){this.value='';}" id="tfoot_v_diskon" name="tfoot_v_diskon"></th>
                                                </tr>
                                                <tr>
                                                    <th colspan="10" class="text-right"><?= $this->lang->line("DPP"); ?></th>
                                                    <th><input type="text" class="form-control form-control-sm text-right" id="tfoot_v_dpp" name="tfoot_v_dpp" readonly></th>
                                                </tr>

                                                <tr>
                                                    <th colspan="10" class="text-right"><?= $this->lang->line("PPN"); ?></th>
                                                    <th><input type="text" class="form-control form-control-sm text-right" id="tfoot_v_ppn" name="tfoot_v_ppn" readonly></th>
                                                </tr>

                                                <tr>
                                                    <th colspan="10" class="text-right"><?= $this->lang->line("Total"); ?></th>
                                                    <th><input type="text" class="form-control form-control-sm text-right" id="tfoot_total" name="tfoot_total" readonly></th>
                                                </tr>
                                            </tfoot>
                                            <input type="hidden" id="jml" name="jml" value="0">
                                        </table>
                                    </div>
                                </div>
                                <div class="form-actions">
                                    <button type="submit" id="submit" class="btn btn-info round btn-min-width mr-1"><i class="fa fa-check-circle fa-lg mr-1"></i><?= $this->lang->line("Realisasi"); ?></button>
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