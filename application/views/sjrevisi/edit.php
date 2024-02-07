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
                                                    <input type="hidden" name="i_price_group" id="i_price_group" class="form-control" value="<?= $data->i_price_group; ?>">
                                                    <input type="hidden" name="i_product_group" id="i_product_group" class="form-control" value="<?= $data->i_product_group; ?>">
                                                    <input type="hidden" name="id" id="id" class="form-control" value="<?= $data->i_do; ?>">
                                                    <input type="hidden" name="i_document_old" id="i_document_old" class="form-control" value="<?= $data->i_do_id; ?>">
                                                    <input type="text" name="i_document" id="i_document" readonly value="<?= $data->i_do_id; ?>" placeholder="DO-<?= date('ym'); ?>-000001" class="form-control form-control-sm text-uppercase" data-validation-required-message="<?= $this->lang->line("Required"); ?>" maxlength="500" autocomplete="off" required aria-label="Text input with checkbox">
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
                                                <input type="date" class="form-control form-control-sm" value="<?= date('Y-m-d', strtotime($data->d_do)); ?>" <?= konci(); ?> data-validation-required-message="<?= $this->lang->line("Required"); ?>" placeholder="" id="d_document" name="d_document" maxlength="500" autocomplete="off" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-5">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Nama Area Provinsi"); ?> :</label>
                                            <div class="controls">
                                                <input type="hidden" name="i_area" id="i_area" class="form-control" value="<?= $data->i_area; ?>">
                                                <input type="text" readonly class="form-control form-control-sm" value="<?= $data->e_area_name; ?>" readonly data-validation-required-message="<?= $this->lang->line("Required"); ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Baris ke 2 -->
                                <div class="row">
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Nomor Pesanan"); ?> :</label>
                                            <div class="controls">
                                                <input type="hidden" name="i_so" id="i_so" class="form-control" value="<?= $data->i_so; ?>">
                                                <input type="text" value="<?= $data->i_so_id; ?>" readonly class="form-control form-control-sm text-uppercase" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Tanggal Pesanan"); ?> :</label>
                                            <div class="input-group input-group-sm controls">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">
                                                        <span class="fa fa-calendar-o"></span>
                                                    </span>
                                                </div>
                                                <input type="date" class="form-control form-control-sm" value="<?= date('Y-m-d', strtotime($data->d_so)); ?>" <?= konci(); ?> data-validation-required-message="<?= $this->lang->line("Required"); ?>" placeholder="" id="d_so" name="d_so" maxlength="500" autocomplete="off" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Nama Pelanggan"); ?> :</label>
                                            <div class="controls">
                                                <input type="hidden" name="i_customer" id="i_customer" class="form-control" value="<?= $data->i_customer; ?>">
                                                <input type="hidden" name="n_customer_discount1" id="n_customer_discount1" class="form-control" value="<?= $data->n_customer_discount1; ?>">
                                                <input type="hidden" name="n_customer_discount2" id="n_customer_discount2" class="form-control" value="<?= $data->n_customer_discount2; ?>">
                                                <input type="hidden" name="n_customer_discount3" id="n_customer_discount3" class="form-control" value="<?= $data->n_customer_discount3; ?>">
                                                <input type="text" readonly class="form-control form-control-sm" value="<?= $data->e_customer_name; ?>" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Baris ke 3 -->
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Nama Pramuniaga"); ?> :</label>
                                            <div class="controls">
                                                <input type="hidden" name="i_salesman" id="i_salesman" class="form-control" value="<?= $data->i_salesman; ?>">
                                                <input type="text" readonly class="form-control form-control-sm" value="<?= $data->e_salesman_name; ?>" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Gudang"); ?> :</label>
                                            <div class="controls">
                                                <input type="hidden" name="i_store" id="i_store" class="form-control" value="<?= $data->i_store; ?>">
                                                <input type="hidden" name="i_store_location" id="i_store_location" class="form-control" value="<?= $data->i_store_location; ?>">
                                                <input type="text" readonly class="form-control form-control-sm" value="<?= $data->e_area_name; ?>" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Baris ke 4 -->
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Keterangan"); ?> :</label>
                                            <div class="controls">
                                                <textarea type="text" rows="3" class="form-control form-control-sm" placeholder="<?= $this->lang->line("Keterangan"); ?>" name="e_remark"><?= $data->remark2; ?></textarea>
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
                            <h4 class="card-title"><i class="fa fa-cart-arrow-down"></i> <?= $this->lang->line("Detail"); ?> <?= $this->lang->line($this->title); ?></h4>
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
                                                    <th class="text-center" width="5%;" valign="center">No</th>
                                                    <th width="45%" valign="center"><?= $this->lang->line("Nama Barang"); ?></th>
                                                    <th valign="center"><?= $this->lang->line("Grade Barang"); ?></th>
                                                    <th class="text-right" width="10%;" valign="center"><?= $this->lang->line("Qty Pesan"); ?></th>
                                                    <th class="text-right" width="10%;" valign="center"><?= $this->lang->line("Qty Stok"); ?></th>
                                                    <th class="text-right" width="10%;" valign="center"><?= $this->lang->line("Qty Kirim"); ?></th>
                                                    <th class="text-center" width="3%"><i class="fa fa-plus-circle fa-lg" title="<?= $this->lang->line('Tambah'); ?>" id="addrow"></i></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $i = 0;
                                                if ($detail->num_rows() > 0) {
                                                    foreach ($detail->result() as $key) {
                                                        $url = "'" . $this->folder . "'," . "'" . encrypt_url($key->i_do . '|' . $key->i_product) . "'";
                                                        $i++; ?>
                                                        <tr>
                                                            <td class="text-center" valign="center">
                                                                <spanx id="snum<?= $i; ?>"><?= $i; ?></spanx>
                                                                <input type="hidden" value="<?= $key->i_do_item; ?>" id="i_do_item<?= $i; ?>" name="i_do_item[]" readonly>
                                                            </td>
                                                            <td>
                                                                <select data-nourut="<?= $i; ?>" required class="form-control select2-size-sm" name="i_product[]" id="i_product<?= $i; ?>">
                                                                    <option value="<?= $key->i_product; ?>"><?= $key->i_product_id . ' - ' . $key->e_product_name; ?></option>
                                                                </select>
                                                                <input type="hidden" value="<?= $key->i_product; ?>" id="i_product_old<?= $i; ?>" name="i_product_old[]" readonly>
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control form-control-sm" id="e_product_motifname<?= $i; ?>" value="<?= $key->e_product_motifname; ?>" readonly>
                                                                <input type="hidden" value="<?= $key->e_product_name; ?>" id="e_product_name<?= $i; ?>" name="e_product_name[]" readonly>
                                                                <input type="hidden" value="<?= $key->i_product_grade; ?>" id="i_product_grade<?= $i; ?>" name="i_product_grade[]" readonly>
                                                                <input type="hidden" value="<?= $key->i_product_motif; ?>" id="i_product_motif<?= $i; ?>" name="i_product_motif[]" readonly>
                                                                <input type="hidden" value="<?= $key->i_product_status; ?>" id="i_product_status<?= $i; ?>" name="i_product_status[]" readonly>
                                                            </td>
                                                            <td class="text-right">
                                                                <input type="text" class="form-control form-control-sm text-right" value="<?= $key->n_order; ?>" id="n_order<?= $i; ?>" name="n_order[]" readonly>
                                                            </td>
                                                            <td class="text-right">
                                                                <input type="text" class="form-control form-control-sm text-right" value="<?= $key->n_stock; ?>" id="n_stock<?= $i; ?>" name="n_stock[]" readonly>
                                                            </td>
                                                            <td>
                                                                <input type="number" value="<?= $key->n_do; ?>" autocomplete="off" class="form-control text-center n_order form-control-sm" id="n_deliver<?= $i; ?>" name="n_deliver[]" onkeyup="cek(<?= $i; ?>)" onblur="if(this.value=='' ){this.value='0';}" onfocus="if(this.value=='0' ){this.value='' ;}">
                                                                <input type="hidden" value="<?= $key->n_do; ?>" id="n_deliver_old<?= $i; ?>" name="n_deliver_old[]">
                                                            </td>
                                                            <td class="text-center">
                                                                <input type="hidden" value="<?= $key->i_so_item; ?>" id="i_so_item<?= $i; ?>" name="i_so_item[]" readonly>
                                                                <input type="hidden" value="<?= $key->i_product; ?>" id="i_product_so<?= $i; ?>" name="i_product_so[]" readonly>
                                                                <input type="hidden" value="<?= $key->n_order; ?>" id="n_order_so<?= $i; ?>" name="n_order_so[]" readonly>
                                                                <input type="hidden" value="<?= $key->v_unit_price; ?>" id="v_price<?= $i; ?>" name="v_price[]" readonly>
                                                                <input type="hidden" value="<?= $key->n_so_discount1; ?>" id="n_so_discount1<?= $i; ?>" name="n_so_discount1[]" readonly>
                                                                <input type="hidden" value="<?= $key->n_so_discount2; ?>" id="n_so_discount2<?= $i; ?>" name="n_so_discount2[]" readonly>
                                                                <input type="hidden" value="<?= $key->n_so_discount3; ?>" id="n_so_discount3<?= $i; ?>" name="n_so_discount3[]" readonly>
                                                                <input type="hidden" value="<?= $key->n_so_discount4; ?>" id="n_so_discount4<?= $i; ?>" name="n_so_discount4[]" readonly>
                                                            </td>
                                                        </tr>
                                                <?php }
                                                } ?>
                                            </tbody>
                                            <input type="hidden" id="jml" name="jml" value="<?= $i; ?>">
                                        </table>
                                    </div>
                                </div>
                                <div class="form-actions">
                                    <button type="submit" id="submit" class="btn btn-warning round btn-min-width mr-1"><i class="icon-paper-plane mr-1"></i><?= $this->lang->line("Ubah"); ?></button>
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