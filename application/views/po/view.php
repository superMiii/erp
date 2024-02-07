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
                        <li class="breadcrumb-item"><a href="<?= base_url($this->folder); ?>"><?= $this->lang->line($this->title); ?></a></li>
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
                    <div class="card">
                        <div class="card-header header-elements-inline <?= $this->session->e_color; ?> bg-darken-1 text-white">
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
                                                    <input type="hidden" name="id" id="id" class="form-control" value="<?= $data->i_po; ?>">
                                                    <input type="text" name="i_document_old" id="i_document_old" class="form-control" value="<?= $data->i_po_id; ?>" readonly>
                                                </div>
                                            </fieldset>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Tanggal Dokumen"); ?> :</label>
                                            <div class="input-group input-group-sm controls">
                                                <input type="text" class="form-control form-control-sm" readonly data-validation-required-message="<?= $this->lang->line("Required"); ?>" placeholder="" id="d_document" name="d_document" maxlength="500" autocomplete="off" required value="<?= $data->d_po; ?>">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">
                                                        <span class="fa fa-calendar-o"></span>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Tanggal Estimasi Kedatangan"); ?> :</label>
                                            <div class="input-group input-group-sm controls">
                                                <input type="text" class="form-control form-control-sm" readonly placeholder="" id="d_estimation" name="d_estimation" maxlength="500" autocomplete="off" value="<?= $data->d_estimation; ?>">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">
                                                        <span class="fa fa-calendar-o"></span>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Baris ke 2 -->
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Nama Area Provinsi"); ?> :</label>
                                            <div class="controls">
                                                <input type="text" class="form-control form-control-sm" readonly data-validation-required-message="<?= $this->lang->line("Required"); ?>" placeholder="" id="e_area_name" name="e_area_name" maxlength="500" autocomplete="off" value="<?= $data->i_area_id . ' - ' . $data->e_area_name; ?>" readonly>
                                                <input type="hidden" name="i_area" id="i_area" value="<?= $data->i_area; ?>">
                                                <input type="hidden" name="jenis" id="jenis" value="<?= $data->jenis; ?>">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Nama Pemasok"); ?> :</label>
                                            <div class="controls">
                                                <input type="text" name="i_supplier" id="i_supplier" class="form-control" value="<?= $data->i_supplier_id . ' - ' . $data->e_supplier_name; ?>" readonly>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label><?= $this->lang->line('Remark'); ?> :</label>
                                            <div class="controls">
                                                <textarea class="form-control text-capitalize" placeholder="<?= $this->lang->line('Remark'); ?>" id="e_po_remark" name="e_po_remark" autocomplete="off" readonly><?= $data->e_po_remark; ?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Baris Ke 3 -->
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label><?= $this->lang->line('Nomor SO'); ?> :</label>
                                            <div class="controls">
                                                <input type="text" id="i_so_id" name="i_so_id" value="<?= $data->i_so_id; ?>" class="form-control form-control-sm text-capitalize" placeholder="<?= $this->lang->line('Nomor SO'); ?>" autocomplete="off" readonly>
                                                <input type="hidden" name="i_so" id="i_so" value="<?= $data->i_so; ?>">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label><?= $this->lang->line('Tanggal SO'); ?> :</label>
                                            <div class="input-group input-group-sm controls">
                                                <input id="d_so" name="d_so" type='text' value="<?= $data->d_so; ?>" readonly class="form-control form-control-sm" placeholder="<?= $this->lang->line('Tanggal SO'); ?>" />
                                                <div class="input-group-append">
                                                    <span class="input-group-text">
                                                        <span class="fa fa-calendar-o"></span>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label><?= $this->lang->line('Nomor SR'); ?> :</label>
                                            <div class="controls">
                                                <input type="text" id="i_sr_id" name="i_sr_id" value="<?= $data->i_sr_id; ?>" class="form-control form-control-sm text-capitalize" placeholder="<?= $this->lang->line('Nomor SR'); ?>" autocomplete="off" readonly>
                                                <input type="hidden" name="i_sr" id="i_sr" value="<?= $data->i_sr; ?>">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label><?= $this->lang->line('Tanggal SR'); ?> :</label>
                                            <div class="input-group input-group-sm controls">
                                                <input id="d_sr" name="d_sr" type='text' value="<?= $data->d_sr; ?>" readonly class="form-control form-control-sm" placeholder="<?= $this->lang->line('Tanggal SR'); ?>" />
                                                <div class="input-group-append">
                                                    <span class="input-group-text">
                                                        <span class="fa fa-calendar-o"></span>
                                                    </span>
                                                </div>
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
                                                    <th rowspan="2" class="text-center" width="5%" valign="center">No</th>
                                                    <th rowspan="2" class="text-center" width="30%" valign="center"><?= $this->lang->line("Nama Barang"); ?></th>
                                                    <th rowspan="2" class="text-center" width="12%" valign="center"><?= $this->lang->line("Harga"); ?></th>
                                                    <th rowspan="2" class="text-center" width="8%" valign="center"><?= $this->lang->line("Qty"); ?></th>
                                                    <th rowspan="1" class="text-center" colspan="2" width="14%"><?= $this->lang->line("Diskon1"); ?></th>
                                                    <th rowspan="2 " class="text-center" width="14%" valign="center"><?= $this->lang->line("Total"); ?></th>
                                                </tr>
                                                <tr>
                                                    <th class="text-center" rowspan="1" colspan="1" width="6%">%</th>
                                                    <th class="text-center" rowspan="1" colspan="1" width="8%">Rp</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                            <tfoot>
                                                <tr hidden>
                                                    <th colspan="6" class="text-right"><?= $this->lang->line("Sub Total"); ?></th>
                                                    <th><input type="text" class="form-control form-control-sm text-right" id="tfoot_subtotal" name="tfoot_subtotal" value="0" readonly></th>
                                                </tr>

                                                <tr hidden>
                                                    <th colspan="6" class="text-right"><?= $this->lang->line("Diskon"); ?></th>
                                                    <th><input type="text" class="formatrupiah form-control form-control-sm text-right" value="0" onkeyup="formatrupiahkeyup(this);change_ndisc();hitung();" onkeydown="formatrupiahkeydown(this);change_ndisc();hitung();" onblur="if(this.value==''){this.value='0';hitung();}" onfocus="if(this.value=='0'){this.value='';}" id="tfoot_n_diskon" name="tfoot_n_diskon"></th>
                                                </tr hidden>

                                                <tr hidden>
                                                    <th colspan="6" class="text-right"><?= $this->lang->line("Diskon Rp"); ?></th>
                                                    <th><input type="text" class="formatrupiah form-control form-control-sm text-right" value="0" onkeyup="formatrupiahkeyup(this);change_vdisc();hitung();" onkeydown="formatrupiahkeydown(this);change_vdisc();hitung();" onblur="if(this.value==''){this.value='0';hitung();}" onfocus="if(this.value=='0'){this.value='';}" id="tfoot_v_diskon" name="tfoot_v_diskon"></th>
                                                </tr>
                                                <tr hidden>
                                                    <th colspan="6" class="text-right"><?= $this->lang->line("DPP"); ?></th>
                                                    <th><input type="text" class="form-control form-control-sm text-right" id="tfoot_v_dpp" name="tfoot_v_dpp" readonly></th>
                                                </tr>

                                                <tr hidden>
                                                    <th colspan="6" class="text-right"><?= $this->lang->line("PPN"); ?></th>
                                                    <th><input type="text" class="form-control form-control-sm text-right" id="tfoot_v_ppn" name="tfoot_v_ppn" readonly></th>
                                                </tr>

                                                <tr>
                                                    <th colspan="6" class="text-right"><?= $this->lang->line("Total"); ?></th>
                                                    <th><input type="text" class="form-control form-control-sm text-right" id="tfoot_total" name="tfoot_total" readonly></th>
                                                </tr>
                                            </tfoot>
                                            <input type="hidden" id="jml" name="jml" value="0">
                                        </table>
                                    </div>
                                </div>
                                <div class="form-actions">
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