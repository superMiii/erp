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
                        <li class="breadcrumb-item"><a href="<?= base_url($this->folder . '/indexx'); ?>"><?= $this->lang->line('Daftar'); ?> <?= $this->lang->line('Retur Penjualan'); ?></a></li>
                        <li class="breadcrumb-item active"><?= $this->lang->line('Ubah'); ?> <?= $this->lang->line($this->title); ?></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="content-body">
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
                                                    <input type="hidden" name="id" id="id" value="<?= $data->i_bbm; ?>">
                                                    <input type="hidden" name="i_document_old" id="i_document_old" value="<?= $data->i_bbm_id; ?>">
                                                    <input type="text" name="i_document" id="i_document" readonly value="<?= $data->i_bbm_id; ?>" placeholder="BBM-<?= date('ym'); ?>-000001" class="form-control form-control-sm text-uppercase" data-validation-required-message="<?= $this->lang->line("Required"); ?>" maxlength="500" autocomplete="off" required aria-label="Text input with checkbox">
                                                </div>
                                            </fieldset>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Tanggal Dokumen"); ?> :</label>
                                            <div class="input-group input-group-sm controls">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">
                                                        <span class="fa fa-calendar-o"></span>
                                                    </span>
                                                </div>
                                                <input type="hidden" value="<?= $data->d_bbm; ?>" readonly data-validation-required-message="<?= $this->lang->line("Required"); ?>" id="d_document" name="d_document" required>
                                                <input type="text" class="form-control form-control-sm" value="<?= $data->date_now; ?>" readonly data-validation-required-message="<?= $this->lang->line("Required"); ?>" placeholder="" maxlength="500" autocomplete="off" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("No TTB"); ?> :</label>
                                            <div class="input-group input-group-sm">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        <span class="fa fa-hashtag"></span>
                                                    </div>
                                                </div>
                                                <input type="hidden" name="i_ttb" id="i_ttb" class="form-control" value="<?= $data->i_ttb; ?>">
                                                <input type="text" value="<?= $data->i_ttb_id; ?>" readonly class="form-control form-control-sm text-uppercase" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Tgl TTB"); ?> :</label>
                                            <div class="input-group input-group-sm controls">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">
                                                        <span class="fa fa-calendar-o"></span>
                                                    </span>
                                                </div>
                                                <input type="hidden" value="<?= $data->d_ttb; ?>" name="d_ttb">
                                                <input type="text" class="form-control form-control-sm" value="<?= $data->dttb; ?>" readonly data-validation-required-message="<?= $this->lang->line("Required"); ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Baris ke 2 -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Nama Area Provinsi"); ?> :</label>
                                            <div class="controls">
                                                <input type="hidden" name="i_area" id="i_area" class="form-control" value="<?= $data->i_area; ?>">
                                                <input type="text" readonly class="form-control form-control-sm" value="<?= '[ ' . $data->i_area_id . ' ] - ' . $data->e_area_name; ?>" readonly data-validation-required-message="<?= $this->lang->line("Required"); ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Nama Pelanggan"); ?> :</label>
                                            <div class="controls">
                                                <input type="hidden" name="i_customer" id="i_customer" class="form-control" value="<?= $data->i_customer; ?>">
                                                <input type="text" readonly class="form-control form-control-sm" value="<?= '[ ' . $data->i_customer_id . ' ] - ' . $data->e_customer_name; ?>" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Baris ke 3 -->
                                <div class="row">
                                    <div class="col-md-6">
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
                                            <label><?= $this->lang->line("Keterangan"); ?> :</label>
                                            <div class="controls">
                                                <textarea type="text" class="form-control" placeholder="<?= $this->lang->line("Keterangan"); ?>" name="e_remarkh"><?= $data->e_remark; ?></textarea>
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
    </div>
    <div class="content-body">
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
                                                    <th class="text-center" width="5%;">No</th>
                                                    <th><?= $this->lang->line("Kode Barang"); ?></th>
                                                    <th width="20%"><?= $this->lang->line("Nama Barang"); ?></th>
                                                    <th><?= $this->lang->line("Motif"); ?></th>
                                                    <th class="text-right" width="5%;"><?= $this->lang->line("Qty TTB"); ?></th>
                                                    <th><?= $this->lang->line("Kode Barang"); ?></th>
                                                    <th width="20%"><?= $this->lang->line("Nama Barang"); ?></th>
                                                    <th><?= $this->lang->line("Motif"); ?></th>
                                                    <th class="text-right" width="5%;"><?= $this->lang->line("Qty BBM"); ?></th>
                                                    <th><?= $this->lang->line("Keterangan"); ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $i = 0;
                                                if ($detail->num_rows() > 0) {
                                                    foreach ($detail->result() as $key) {
                                                        $i++; ?>
                                                        <tr>
                                                            <td class="text-center" valign="center">
                                                                <spanx id="snum<?= $i; ?>"><?= $i; ?></spanx>
                                                                <input type="hidden" value="<?= $key->i_bbm_item; ?>" id="i_bbm_item<?= $i; ?>" name="i_bbm_item[]" readonly>
                                                            </td>
                                                            <!-- Barang TTB -->
                                                            <td>
                                                                <input type="hidden" value="<?= $key->i_ttb_item; ?>" id="i_ttb_item<?= $i; ?>" name="i_ttb_item[]" readonly>
                                                                <input type="hidden" value="<?= $key->i_product1; ?>" id="i_product1<?= $i; ?>" name="i_product1[]" readonly>
                                                                <input type="text" class="form-control form-control-sm" value="<?= $key->i_product_id1; ?>" readonly>
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control form-control-sm" value="<?= $key->e_product_name1; ?>" readonly>
                                                            </td>
                                                            <td>
                                                                <input type="hidden" value="<?= $key->i_product1_grade; ?>" id="i_product_grade1<?= $i; ?>" name="i_product_grade1[]" readonly>
                                                                <input type="hidden" value="<?= $key->i_product1_motif; ?>" id="i_product_motif1<?= $i; ?>" name="i_product_motif1[]" readonly>
                                                                <input type="text" class="form-control form-control-sm" value="<?= $key->e_product_motifname1; ?>" readonly>
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control form-control-sm text-right" value="<?= $key->n_quantity1; ?>" id="n_ttb<?= $i; ?>" name="n_ttb[]" readonly>
                                                                <input type="hidden" value="<?= $key->v_unit_price; ?>" id="v_unit_price<?= $i; ?>" name="v_unit_price[]" readonly>
                                                            </td>
                                                            <!-- Barang BBM -->
                                                            <td>
                                                                <select data-nourut="<?= $i; ?>" required class="form-control select2-size-sm" name="i_product[]" id="i_product<?= $i; ?>">
                                                                    <option value="<?= $key->i_product; ?>"><?= $key->i_product_id; ?></option>
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control form-control-sm" id="e_product_name<?= $i; ?>" value="<?= $key->e_product_name; ?>" readonly>
                                                            </td>
                                                            <td>
                                                                <input type="hidden" value="<?= $key->i_product_grade; ?>" id="i_product_grade<?= $i; ?>" name="i_product_grade[]" readonly>
                                                                <input type="hidden" value="<?= $key->i_product_motif; ?>" id="i_product_motif<?= $i; ?>" name="i_product_motif[]" readonly>
                                                                <input type="text" class="form-control form-control-sm" value="<?= $key->e_product_motifname; ?>" id="e_product_motifname<?= $i; ?>" readonly>
                                                            </td>
                                                            <td><input type="number" value="<?= $key->n_quantity; ?>" autocomplete="off" class="form-control text-center n_bbm form-control-sm" id="n_bbm<?= $i; ?>" name="n_bbm[]" onkeyup="cek(<?= $i; ?>)" onblur="if(this.value=='' ){this.value='0';}" onfocus="if(this.value=='0' ){this.value='' ;}"></td>
                                                            <td>
                                                                <input type="text" class="form-control form-control-sm" placeholder="Note" value="<?= $key->e_remark; ?>" name="e_remark[]">
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
    </div>
</form>