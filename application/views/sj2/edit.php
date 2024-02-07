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
                                                <input type="text" readonly class="form-control form-control-sm" value="<?= $data->e_customer_name; ?>" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Baris ke 3 -->
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Alamat Pelanggan"); ?> :</label>
                                            <div class="controls">
                                                <input type="hidden" name="e_customer_address" id="e_customer_address" class="form-control" value="<?= $data->e_customer_address; ?>">
                                                <input type="text" readonly class="form-control form-control-sm" value="<?= $data->e_customer_address; ?>" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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
                                                <input type="text" readonly class="form-control form-control-sm" value="<?= $data->e_area_name; ?>" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <input type="hidden" class="form-control form-control-sm" value="<?= $data->f_so_stockdaerah; ?>" id="f_so_stockdaerah" name="f_so_stockdaerah"></textarea>
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
                                                    <th class="text-center" width="2%;" valign="center">No</th>
                                                    <th class="text-center" width="11%;" valign="center"><?= $this->lang->line("Kode Barang"); ?></th>
                                                    <th class="text-center" width="29%;" valign="center"><?= $this->lang->line("Nama Barang"); ?></th>
                                                    <th class="text-center" width="5%"><?= $this->lang->line("Disk1"); ?> %</th>
                                                    <th class="text-center" width="5%"><?= $this->lang->line("Disk2"); ?> %</th>
                                                    <th class="text-center" width="6%;" valign="center"><?= $this->lang->line("Qty Pesan"); ?></th>
                                                    <th class="text-center" width="5%;" valign="center"><?= $this->lang->line("Qty Stok"); ?></th>
                                                    <th class="text-center" width="9%;" valign="center"><?= $this->lang->line("Qty Kirim"); ?></th>
                                                    <th class="text-center" width="14%;" valign="center"><?= $this->lang->line("Harga"); ?></th>
                                                    <th class="text-center" width="14%;" valign="center"><?= $this->lang->line("Total"); ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $i = 0;
                                                $subtotal = 0;
                                                $distotal = 0;
                                                $dpp = 0;
                                                $ppn = 0;
                                                $grandtotal = 0;
                                                if ($detail->num_rows() > 0) {
                                                    foreach ($detail->result() as $key) {
                                                        $i++; ?>
                                                        <tr>
                                                            <td class="text-center" valign="center">
                                                                <spanx id="snum<?= $i; ?>"><?= $i; ?></spanx>
                                                            </td>
                                                            <td>
                                                                <input type="hidden" value="<?= $key->i_product; ?>" id="i_product<?= $i; ?>" name="i_product[]" readonly>
                                                                <?= $key->i_product_id; ?>
                                                            </td>
                                                            <td>
                                                                <input type="hidden" value="<?= $key->e_product_name; ?>" id="e_product_name<?= $i; ?>" name="e_product_name[]" readonly>
                                                                <?= $key->e_product_name; ?>
                                                            </td>
                                                            <td>
                                                                <input type="hidden" value="<?= $key->i_product_grade; ?>" id="i_product_grade<?= $i; ?>" name="i_product_grade[]" readonly>
                                                                <input type="hidden" value="<?= $key->i_product_motif; ?>" id="i_product_motif<?= $i; ?>" name="i_product_motif[]" readonly>
                                                                <?= $key->n_so_discount1; ?>
                                                            </td>
                                                            <td>
                                                                <?= $key->n_so_discount2; ?>
                                                            </td>
                                                            <td class="text-right">
                                                                <?= number_format($key->n_order); ?>
                                                                <input type="hidden" value="<?= $key->n_order; ?>" id="n_order<?= $i; ?>" name="n_order[]" readonly>
                                                            </td>
                                                            <td class="text-right">
                                                                <?= number_format($key->n_stock); ?>
                                                                <input type="hidden" value="<?= $key->n_stock; ?>" id="n_stock<?= $i; ?>" name="n_stock[]" readonly>
                                                            </td>
                                                            <td>
                                                                <input type="text" value="<?= $key->n_do; ?>" autocomplete="off" class="form-control text-right n_order form-control-sm" id="n_deliver<?= $i; ?>" name="n_deliver[]" onkeypress="return bilanganasli(event);" onkeyup="cek(<?= $i; ?>);" onblur="if(this.value=='' ){this.value='0';}" onfocus="if(this.value=='0' ){this.value='' ;}">
                                                                <input type="hidden" value="<?= $key->n_do; ?>" autocomplete="off" id="n_deliver_old<?= $i; ?>" name="n_deliver_old[]">
                                                            </td>



                                                            <td class="text-right">
                                                                Rp. <?= number_format($key->v_unit_price); ?>
                                                                <input type="hidden" value="<?= $key->v_unit_price; ?>" id="v_unit_price<?= $i; ?>" name="v_unit_price[]" readonly>
                                                            </td>
                                                            <?php
                                                            $total_baris = $key->v_unit_price * $key->n_deliver;
                                                            $v_diskon1 = $total_baris * ($key->n_so_discount1 / 100);
                                                            $v_diskon2 = ($total_baris - $v_diskon1) * ($key->n_so_discount2 / 100);
                                                            $v_diskon3 = ($total_baris - $v_diskon1 - $v_diskon2) * ($key->n_so_discount3 / 100);
                                                            $v_diskon4 = ($total_baris - $v_diskon1 - $v_diskon2 - $v_diskon3) * ($key->n_so_discount4 / 100);
                                                            $v_total_discount = $v_diskon1 + $v_diskon2 + $v_diskon3 + $v_diskon4;
                                                            $subtotal += $total_baris;
                                                            $distotal += $v_total_discount;
                                                            ?>
                                                            <td class="text-right">
                                                                Rp. <?= number_format($total_baris); ?>
                                                                <input type="hidden" value="<?= $total_baris; ?>" id="total_baris<?= $i; ?>" name="total_baris[]" readonly>
                                                            </td>
                                                        </tr>
                                                <?php }
                                                    $dpp = $subtotal - $distotal - $data->v_so_discounttotal;
                                                    $ppn = $dpp * ($data->n_so_ppn / 100);
                                                    $grandtotal = $dpp + $ppn;
                                                } ?>
                                            </tbody>


                                            <tfoot>
                                                <?php if ($grandtotal >= $this->session->v_meterai_limit) { ?>
                                                    <tr>
                                                        <th class="font-small-2" colspan="7"><?= $this->lang->line('Catatan'); ?> : Nilai Belum Termasuk Bea Meterai Rp. 10.000,-</th>
                                                        <th class="text-right" colspan="2">Sub Total</th>
                                                        <td class="text-right"><strong><?= number_format($subtotal); ?></strong></td>
                                                    </tr>
                                                    <tr>
                                                        <th <?php if ($data->f_so_plusppn == 't') { ?> rowspan="6" <?php } else { ?> rowspan="4" <?php } ?> colspan="7">
                                                            <div class="border-blue-grey">
                                                                <div class="card-content">
                                                                    <div class="text-center">
                                                                        <span class="mb-1"><strong>P E N T I N G</strong></span><br>
                                                                        <span class="mb-0"><small>PENERIMA WAJIB TTD DAN ATAU CAP TOKO,</small></span><br>
                                                                        <span class="mb-0"><small>SURAT JALAN INI MERUPAKAN BUKTI RESMI PENERIMAAN BARANG,</small></span><br>
                                                                        <span class="mb-0"><small>SURAT JALAN INI BUKAN MERUPAKAN BUKTI TAGIHAN,</small></span><br>
                                                                        <span class="mb-0"><small>TERIMA KOMPLEN BARANG PALING LAMA 3 HARI SETELAH BARANG DITERIMA</small></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </th>
                                                    </tr>
                                                <?php } else { ?>
                                                    <tr>
                                                        <th <?php if ($data->f_so_plusppn == 't') { ?> rowspan="7" <?php } else { ?> rowspan="4" <?php } ?> colspan="7">
                                                            <div class="border-blue-grey">
                                                                <div class="card-content">
                                                                    <div class="text-center">
                                                                        <span class="mb-1"><strong>P E N T I N G</strong></span><br>
                                                                        <span class="mb-0"><small>PENERIMA WAJIB TTD DAN ATAU CAP TOKO,</small></span><br>
                                                                        <span class="mb-0"><small>SURAT JALAN INI MERUPAKAN BUKTI RESMI PENERIMAAN BARANG,</small></span><br>
                                                                        <span class="mb-0"><small>SURAT JALAN INI BUKAN MERUPAKAN BUKTI TAGIHAN,</small></span><br>
                                                                        <span class="mb-0"><small>TERIMA KOMPLEN BARANG PALING LAMA 3 HARI SETELAH BARANG DITERIMA</small></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </th>
                                                        <th class="text-right" colspan="2">Sub Total</th>
                                                        <td class="text-right"><strong><?= number_format($subtotal); ?></strong></td>
                                                    </tr>
                                                <?php } ?>
                                                <tr>
                                                    <th class="text-right" colspan="2">Diskon Per Item</th>
                                                    <td class="text-right"><?= number_format($distotal); ?></td>
                                                </tr>
                                                <tr>
                                                    <th class="text-right" colspan="2">Diskon Tambahan</th>
                                                    <td class="text-right"><?= number_format($data->v_so_discounttotal); ?></li>
                                                    </td>
                                                </tr>
                                                <?php if ($data->f_so_plusppn == 't') { ?>
                                                    <tr>
                                                        <th class="text-right" colspan="2">Nilai Kotor</th>
                                                        <td class="text-right"><?= number_format($dpp); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th class="text-right" colspan="2">Pajak (<?= $data->n_so_ppn; ?>%)</th>
                                                        <td class="text-right"><?= number_format($ppn); ?></td>
                                                    </tr>
                                                <?php } ?>
                                                <tr>
                                                    <th class="text-right" colspan="2">Nilai Bersih</th>
                                                    <td class="text-right"><strong><?= number_format($grandtotal); ?></strong></td>
                                                </tr>
                                            </tfoot>



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