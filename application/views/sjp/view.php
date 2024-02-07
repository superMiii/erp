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
                                                    <input type="hidden" name="id" id="id" class="form-control" value="<?= $data->i_gs; ?>">
                                                    <input type="hidden" name="i_document_old" id="i_document_old" class="form-control" value="<?= $data->i_gs_id; ?>">
                                                    <input type="text" name="i_document" id="i_document" readonly value="<?= $data->i_gs_id; ?>" placeholder="DO-<?= date('ym'); ?>-000001" class="form-control form-control-sm text-uppercase" data-validation-required-message="<?= $this->lang->line("Required"); ?>" maxlength="500" autocomplete="off" required aria-label="Text input with checkbox">
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
                                                <input type="text" readonly class="form-control form-control-sm" value="<?= date('Y-m-d', strtotime($data->d_gs)); ?>" <?= konci(); ?> data-validation-required-message="<?= $this->lang->line("Required"); ?>" placeholder="" id="d_document" name="d_document" maxlength="500" autocomplete="off" required>
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
                                                <input type="hidden" name="i_sr" id="i_sr" class="form-control" value="<?= $data->i_sr; ?>">
                                                <input type="text" value="<?= $data->i_sr_id; ?>" readonly class="form-control form-control-sm text-uppercase" required>
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
                                                <input type="text" class="form-control form-control-sm" value="<?= $data->d_sr; ?>" <?= konci(); ?> data-validation-required-message="<?= $this->lang->line("Required"); ?>" placeholder="" id="d_sr" name="d_sr" maxlength="500" autocomplete="off" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Gudang Penerima"); ?> :</label>
                                            <div class="controls">
                                                <input type="hidden" name="i_store" id="i_store" class="form-control" value="<?= $data->i_store; ?>">
                                                <input type="hidden" name="i_store_loc" id="i_store_loc" class="form-control" value="<?= $data->i_store_loc; ?>">
                                                <input type="text" readonly class="form-control form-control-sm" value="<?= $data->e_store_name . ' - ' . $data->e_store_loc_name; ?>" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <!-- Baris ke 4 -->
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Keterangan"); ?> :</label>
                                            <div class="controls">
                                                <textarea readonly type="text" name="e_remark" data-validation-required-message="<?= $this->lang->line("Required"); ?>" required class="form-control"  placeholder="<?= $this->lang->line("Keterangan"); ?>"><?= $data->e_remark; ?></textarea>
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
                                                    <th class="text-center" width="10%;" valign="center">No</th>
                                                    <th class="text-center" valign="center"><?= $this->lang->line("Kode Barang"); ?></th>
                                                    <th class="text-center" valign="center"><?= $this->lang->line("Nama Barang"); ?></th>
                                                    <th class="text-center" valign="center"><?= $this->lang->line("Grade Barang"); ?></th>
                                                    <th class="text-center" valign="center"><?= $this->lang->line("Qty Pesan"); ?></th>
                                                    <!-- <th class="text-center" valign="center"><?= $this->lang->line("Qty Stok"); ?></th> -->
                                                    <th class="text-center" width="15%;" valign="center"><?= $this->lang->line("Qty Kirim"); ?></th>
                                                    <th class="text-center" width="15%;" valign="center"><?= $this->lang->line("Keterangan"); ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $i = 0;
                                                $total_rupiah = 0;
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
                                                                <input type="hidden" value="<?= $key->v_unit_price; ?>" id="v_unit_price<?= $i; ?>" name="v_unit_price[]" readonly>
                                                                <?= $key->e_product_motifname; ?>
                                                            </td>
                                                            <td class="text-right">
                                                                <?= number_format($key->n_quantity_order); ?>
                                                                <input type="hidden" value="<?= $key->n_quantity_order; ?>" id="n_order<?= $i; ?>" name="n_order[]" readonly>
                                                            </td>
                                                            <!--  <td class="text-right">
                                                                <?= number_format($key->stok); ?>
                                                                <input type="hidden" value="<?= $key->stok; ?>" id="n_stock<?= $i; ?>" name="n_stock[]" readonly>
                                                            </td> -->
                                                            <?php
                                                            // $total_order = ($key->n_quantity_order - $key->n_quantity_deliver);
                                                            // $stok = $key->stok;
                                                            // $n_sj = 0;
                                                            // if ($stok >= $total_order) {
                                                            //     $n_sj = $total_order;
                                                            // } else if ($stok < $total_order) {
                                                            //     $n_sj = $stok;
                                                            // } else {
                                                            //     $n_sj = abs($total_order - $stok);
                                                            // }

                                                            $total_rupiah += $key->n_quantity_deliver *  $key->v_unit_price;
                                                            ?>
                                                            <td><input type="text" readonly value="<?= $key->n_quantity_deliver;  ?>" autocomplete="off" class="form-control text-right n_order form-control-sm" id="n_deliver<?= $i; ?>" name="n_deliver[]" onkeypress="return bilanganasli(event);hitung();" onkeyup="cek(<?= $i; ?>);hitung();" onblur="if(this.value=='' ){this.value='0';}" onfocus="if(this.value=='0' ){this.value='' ;}"></td>

                                                            <td><input type="text" readonly value="<?= $key->e_remark; ?>" autocomplete="off" class="form-control form-control-sm text-left" id="e_remark<?= $i; ?>" name="e_remark[]"></td>
                                                        </tr>
                                                <?php }
                                                } ?>
                                            </tbody>
                                            <input type="hidden" id="jml" name="jml" value="<?= $i; ?>">
                                            <tfoot>
                                                <tr>
                                                    <td colspan="4" class="text-right"> <b>Total Rupiah</b></td>
                                                    <td colspan="4" class="text-right">
                                                        <input type="text" class="form-control form-control-sm text-right" value="<?= number_format($total_rupiah, 2); ?>" id="v_total" name="v_total" readonly>
                                                        <b><i>( <span id="terbilangnya"> <?= terbilang($total_rupiah); ?> rupiah </span> )</i></b>
                                                    </td>
                                                </tr>
                                            </tfoot>
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