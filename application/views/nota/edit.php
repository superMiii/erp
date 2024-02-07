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
                        <li class="breadcrumb-item"><a href="<?= base_url($this->folder); ?>"><?= $this->lang->line($this->title); ?></a></li>
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
                        <div class="card-header card-head-inverse <?= $this->session->e_color; ?> bg-darken-1 text-white">
                            <h4 class="card-title"><i class="icon-pencil"></i> <?= $this->lang->line('Ubah'); ?> <?= $this->lang->line($this->title); ?></h4>
                            <input type="hidden" id="path" value="<?= $this->folder; ?>">
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
                                                    <input type="hidden" name="id" id="id" value="<?= $data->i_nota; ?>">
                                                    <input type="hidden" name="i_document_old" id="i_document_old" value="<?= $data->i_nota_id; ?>">
                                                    <input type="text" name="i_document" id="i_document" readonly value="<?= $data->i_nota_id; ?>" class="form-control form-control-sm text-uppercase" data-validation-required-message="<?= $this->lang->line("Required"); ?>" maxlength="500" autocomplete="off" required aria-label="Text input with checkbox">
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
                                                <input type="date" class="form-control form-control-sm" value="<?= date('Y-m-d', strtotime($data->d_nota)); ?>" <?= konci(); ?> data-validation-required-message="<?= $this->lang->line("Required"); ?>" placeholder="" id="d_document" name="d_document" maxlength="500" autocomplete="off" required>
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
                                            <label><?= $this->lang->line("Nomor Surat Jalan"); ?> :</label>
                                            <div class="controls">
                                                <input type="hidden" name="i_do" id="i_do" class="form-control" value="<?= $data->i_do; ?>">
                                                <input type="text" value="<?= $data->i_do_id; ?>" readonly class="form-control form-control-sm text-uppercase" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Tanggal Surat Jalan"); ?> :</label>
                                            <div class="input-group input-group-sm controls">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">
                                                        <span class="fa fa-calendar-o"></span>
                                                    </span>
                                                </div>
                                                <input type="date" class="form-control form-control-sm" value="<?= date('Y-m-d', strtotime($data->d_do)); ?>" <?= konci(); ?> data-validation-required-message="<?= $this->lang->line("Required"); ?>" placeholder="" id="d_do" name="d_do" maxlength="500" autocomplete="off" required>
                                            </div>
                                        </div>
                                    </div>
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
                                </div>

                                <!-- Baris ke 3 -->
                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Nama Pelanggan"); ?> :</label>
                                            <div class="controls">
                                                <input type="hidden" name="i_customer" id="i_customer" class="form-control" value="<?= $data->i_customer; ?>">
                                                <input type="text" readonly class="form-control form-control-sm" value="<?= $data->e_customer_name; ?>" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Nama Pramuniaga"); ?> :</label>
                                            <div class="controls">
                                                <input type="hidden" name="i_salesman" id="i_salesman" class="form-control" value="<?= $data->i_salesman; ?>">
                                                <input type="text" readonly class="form-control form-control-sm" value="<?= $data->e_salesman_name; ?>" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Nama Kelompok Harga"); ?> :</label>
                                            <div class="controls">
                                                <input type="text" readonly class="form-control form-control-sm" value="<?= $data->e_price_groupname; ?>" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Baris ke 3 -->
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
                                                        <input type="checkbox" id="f_insentif" name="f_insentif" checked class="switch" data-group-cls="btn-group-sm" data-on-label="<?= $this->lang->line('Ya'); ?>" data-off-label="<?= $this->lang->line('Tidak'); ?>" data-switch-always />
                                                    </div>
                                                </fieldset>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("TOP"); ?> :</label>
                                            <div class="controls">
                                                <input type="text" readonly class="form-control form-control-sm" value="<?= $data->n_so_toplength; ?>" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Jatuh Tempo"); ?> :</label>
                                            <div class="input-group input-group-sm controls">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">
                                                        <span class="fa fa-calendar-o"></span>
                                                    </span>
                                                </div>
                                                <input type="date" class="form-control form-control-sm" value="<?= date('Y-m-d', strtotime('+' . $data->n_so_toplength . ' days', strtotime($data->d_do))); ?>" <?= konci(); ?> data-validation-required-message="<?= $this->lang->line("Required"); ?>" placeholder="" id="d_jatuh_tempo" name="d_jatuh_tempo" autocomplete="off" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("NPWP"); ?> :</label>
                                            <div class="controls">
                                                <input type="text" name="e_customer_pkpnpwp" id="e_customer_pkpnpwp" readonly class="form-control form-control-sm" value="<?= $data->e_customer_pkpnpwp; ?>">
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
                                                <textarea type="text" rows="3" class="form-control form-control-sm" placeholder="<?= $this->lang->line("Keterangan"); ?>" name="e_remark"><?= $data->e_remark; ?></textarea>
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
                                        <table class="table table-xs table-column table-bordered font-small-3" id="tabledetail">
                                            <thead class="<?= $this->session->e_color; ?> bg-darken-1 text-white">
                                                <tr>
                                                    <th class="text-center" width="3%">No</th>
                                                    <th class="text-center" width="30%" valign="center"><?= $this->lang->line("Nama Barang"); ?></th>
                                                    <th class="text-center" width="10%" valign="center"><?= $this->lang->line("Harga"); ?></th>
                                                    <th class="text-center" width="8%" valign="center"><?= $this->lang->line("Qty"); ?></th>
                                                    <th class="text-center" width="8%"><?= $this->lang->line("Disk1"); ?> %</th>
                                                    <th class="text-center" width="8%"><?= $this->lang->line("Disk2"); ?> %</th>
                                                    <th class="text-center" width="8%"><?= $this->lang->line("Disk3"); ?> %</th>
                                                    <th class="text-center" width="14%" valign="center"><?= $this->lang->line("Total"); ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $i = 0;
                                                $subtotal = 0;
                                                $distotal = 0;
                                                $dpp = 0;
                                                $ppn = 0;
                                                $grandtotal = 0;
                                                if ($detail->num_rows() > 0) {
                                                    foreach ($detail->result() as $key) {
                                                        $i++;
                                                        $total = $key->v_unit_price * $key->n_deliver;
                                                        $v_diskon1 = $total * ($key->n_nota_discount1 / 100);
                                                        $v_diskon2 = ($total - $v_diskon1) * ($key->n_nota_discount2 / 100);
                                                        $v_diskon3 = ($total - $v_diskon1 - $v_diskon2) * ($key->n_nota_discount3 / 100);
                                                        $v_total_discount = $v_diskon1 + $v_diskon2 + $v_diskon3;
                                                ?>
                                                        <tr class="align-baseline">
                                                            <td class="text-center" valign="center">
                                                                <spanx id="snum<?= $i; ?>"><?= $i; ?></spanx>
                                                            <td class="align-text-top"><?= '[' . $key->i_product_id . '] - ' . $key->e_product_name; ?></td>
                                                            <td class="text-right"><?= number_format($key->v_unit_price); ?></td>
                                                            <td class="text-center"><?= ($key->n_deliver); ?></td>
                                                            <td class="text-right"><?= $key->n_nota_discount1; ?></td>
                                                            <td class="text-right"><?= $key->n_nota_discount2; ?></td>
                                                            <td class="text-right"><?= $key->n_nota_discount3; ?></td>
                                                            <td class="text-right"><?= number_format($total); ?></td>
                                                            <input type="hidden" name="i_do[]" id="i_do<?= $i; ?>"" value=" <?= $key->i_do; ?>">
                                                            <input type="hidden" name="d_do[]" id="d_do<?= $i; ?>"" value=" <?= $key->d_do; ?>">
                                                            <input type="hidden" name="i_product[]" id="i_product<?= $i; ?>"" value=" <?= $key->i_product; ?>">
                                                            <input type="hidden" name="i_product_grade[]" id="i_product_grade<?= $i; ?>"" value=" <?= $key->i_product_grade; ?>">
                                                            <input type="hidden" name="i_product_motif[]" id="i_product_motif<?= $i; ?>"" value=" <?= $key->i_product_motif; ?>">
                                                            <input type="hidden" name="n_deliver[]" id="n_deliver<?= $i; ?>"" value=" <?= $key->n_deliver; ?>">
                                                            <input type="hidden" name="v_unit_price[]" id="v_unit_price<?= $i; ?>"" value=" <?= $key->v_unit_price; ?>">
                                                            <input type="hidden" name="e_product_name[]" id="e_product_name<?= $i; ?>"" value=" <?= $key->e_product_name; ?>">
                                                            <input type="hidden" name="n_nota_discount1[]" id="n_nota_discount1<?= $i; ?>"" value=" <?= $key->n_nota_discount1; ?>">
                                                            <input type="hidden" name="v_nota_discount1[]" id="v_nota_discount1<?= $i; ?>"" value=" <?= $v_diskon1; ?>">
                                                            <input type="hidden" name="n_nota_discount2[]" id="n_nota_discount2<?= $i; ?>"" value=" <?= $key->n_nota_discount2; ?>">
                                                            <input type="hidden" name="v_nota_discount2[]" id="v_nota_discount2<?= $i; ?>"" value=" <?= $v_diskon2; ?>">
                                                            <input type="hidden" name="n_nota_discount3[]" id="n_nota_discount3<?= $i; ?>"" value=" <?= $key->n_nota_discount3; ?>">
                                                            <input type="hidden" name="v_nota_discount3[]" id="v_nota_discount3<?= $i; ?>"" value=" <?= $v_diskon3; ?>">
                                                            <input type="hidden" name="n_nota_discount4[]" id="n_nota_discount4<?= $i; ?>"" value=" <?= $key->n_nota_discount4; ?>">
                                                            <input type="hidden" name="v_nota_discount4[]" id="v_nota_discount4<?= $i; ?>"" value=" <?= $key->v_nota_discount4; ?>">
                                                        </tr>
                                                <?php
                                                        $subtotal += $total;
                                                        $distotal += $v_total_discount;
                                                    }
                                                    $dpp = $subtotal - $distotal - $data->v_so_discounttotal;
                                                    $nppn = ($data->f_so_plusppn == 't') ? $data->n_so_ppn : 0;
                                                    $ppn = $dpp * ($nppn / 100);
                                                    $grandtotal = $dpp + $ppn;
                                                } ?>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th colspan="7" class="text-right"><?= $this->lang->line("Sub Total"); ?> Rp. </th>
                                                    <th class="text-right"><?= number_format($subtotal); ?></th>
                                                </tr>

                                                <tr>
                                                    <th colspan="7" class="text-right"><?= $this->lang->line("Disk"); ?> Rp. </th>
                                                    <th class="text-right"><?= number_format($distotal); ?></th>
                                                </tr>

                                                <!-- <tr>
                                                    <th colspan="7" class="text-right"><?= $this->lang->line("Diskon Tambahan"); ?> Rp. </th>
                                                    <th class="text-right"><?= number_format($data->v_so_discounttotal); ?></th>
                                                </tr> -->
                                                <?php if ($data->f_so_plusppn == 't') { ?>
                                                    <tr>
                                                        <th colspan="7" class="text-right"><?= $this->lang->line("DPP"); ?> Rp. </th>
                                                        <th class="text-right"><?= number_format($dpp); ?></th>
                                                    </tr>

                                                    <tr>
                                                        <th colspan="7" class="text-right"><?= $this->lang->line("PPN"); ?> Rp. </th>
                                                        <th class="text-right"><?= number_format($ppn); ?></th>
                                                    </tr>
                                                <?php } ?>
                                                <tr>
                                                    <th colspan="7" class="text-right"><?= $this->lang->line("Nilai Bersih"); ?> Rp. </th>
                                                    <th class="text-right"><?= number_format($grandtotal); ?></th>
                                                </tr>
                                                <input type="hidden" name="v_nota_gross" value="<?= $subtotal; ?>" readonly>
                                                <input type="hidden" name="v_nota_ppn" value="<?= $ppn; ?>" readonly>
                                                <input type="hidden" name="v_nota_discount" value="<?= $distotal + $data->v_so_discounttotal; ?>" readonly>
                                                <input type="hidden" name="v_nota_netto" value="<?= $grandtotal; ?>" readonly>
                                            </tfoot>
                                            <input type="hidden" id="jml" name="jml" value="<?= $i; ?>">
                                        </table>
                                    </div>
                                </div>
                                <div class="form-actions">
                                    <button type="submit" id="submit" class="btn btn-warning round btn-min-width mr-1"><i class="icon-paper-plane mr-1"></i><?= $this->lang->line("Ubah"); ?></button>
                                    <a href="<?= base_url($this->folder); ?>" class="btn btn-secondary round btn-min-width mr-1"><i class="icon-action-undo mr-1"></i><?= $this->lang->line("Kembali"); ?></a>
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