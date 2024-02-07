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
<div class="content-header row">
    <div class="content-header-left col-md-12 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#" onclick="return false;"><?= $this->lang->line('Penjualan'); ?></a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url($this->folder . '/index/' . encrypt_url($dfrom) . '/' . encrypt_url($dto) . '/' . encrypt_url($harea)); ?>"><?= $this->lang->line($this->title); ?></a></li>
                    <li class="breadcrumb-item active"><?= $this->lang->line('Detail'); ?> <?= $this->lang->line($this->title); ?></li>
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
                        <h4 class="card-title"><i class="icon-eye"></i> <?= $this->lang->line('Detail'); ?> <?= $this->lang->line($this->title); ?></h4>
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
                                                <input type="hidden" name="id" id="id" class="form-control" value="<?= $data->i_so; ?>">
                                                <input readonly value="<?= $data->i_so_id; ?>" placeholder="SO-<?= date('ym'); ?>-000001" class="form-control form-control-sm text-uppercase">
                                            </div>
                                        </fieldset>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label><?= $this->lang->line("Tanggal Dokumen"); ?> :</label>
                                        <div class="input-group input-group-sm controls">
                                            <input readonly class="form-control form-control-sm date" value="<?= date('d-m-Y', strtotime($data->d_so)); ?>">
                                            <div class="input-group-append">
                                                <span class="input-group-text">
                                                    <span class="fa fa-calendar-o"></span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label><?= $this->lang->line("Nama Promo"); ?> :</label>
                                        <div class="controls">
                                            <input readonly class="form-control form-control-sm" value="<?= $data->e_promo_name; ?>">
                                        </div>
                                    </div>
                                </div>
                                <!-- Baris ke 2 -->
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label><?= $this->lang->line("Nama Area Provinsi"); ?> :</label>
                                        <div class="controls">
                                            <input readonly class="form-control form-control-sm" value="<?= $data->i_area_id . ' - ' . $data->e_area_name; ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><?= $this->lang->line("Nama Pelanggan"); ?> :</label>
                                        <div class="controls">
                                            <input readonly class="form-control form-control-sm date" value="<?= $data->i_customer_id . ' - ' . $data->e_customer_name; ?>">
                                        </div>
                                    </div>
                                </div>

                                <!-- Bari ke 3 -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><?= $this->lang->line('Alamat Pelanggan'); ?> :</label>
                                        <div class="controls">
                                            <textarea class="form-control text-capitalize" readonly><?= $data->e_customer_address; ?></textarea>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" id="ppn" name="ppn" value="<?= $data->f_so_plusppn; ?>">
                                <input type="hidden" id="nppn" name="nppn" value="<?= $data->n_so_ppn; ?>">
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

                                <!-- Baris ke 4 -->

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label><?= $this->lang->line("Nama Pramuniaga"); ?> :</label>
                                        <div class="controls">
                                            <input readonly class="form-control form-control-sm date" value="<?= $data->i_salesman_id . ' - ' . $data->e_salesman_name; ?>">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label><?= $this->lang->line('Stock'); ?></label>
                                        <div class="controls">
                                            <fieldset>
                                                <div class="float-left">
                                                    <input type="checkbox" readonly <?php if ($data->f_so_stockdaerah == 't') {
                                                                                        echo "checked";
                                                                                    } ?> class="switch" data-group-cls="btn-group-sm" data-on-label="<?= $this->lang->line('Daerah'); ?>" data-off-label="<?= $this->lang->line('Pusat'); ?>" data-switch-always />
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
                                                <input readonly value="<?= $data->e_po_reff; ?>" class="form-control form-control-sm text-capitalize" placeholder="<?= $this->lang->line('Nomor PO'); ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label><?= $this->lang->line('Tanggal Berakhir PO'); ?> :</label>
                                        <div class="input-group input-group-sm controls">
                                            <input type='text' value="<?php if ($data->d_po_reff != '' || $data->d_po_reff != null) {
                                                                            echo date('d-m-Y', strtotime($data->d_po_reff));
                                                                        } ?>" readonly class="form-control form-control-sm date" placeholder="<?= $this->lang->line('Tanggal Berakhir PO'); ?>" />
                                            <div class="input-group-append">
                                                <span class="input-group-text">
                                                    <span class="fa fa-calendar-o"></span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Baris ke 5 -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><?= $this->lang->line("Kelompok Barang"); ?> :</label>
                                        <div class="controls">
                                            <input readonly class="form-control form-control-sm date" value="<?= $data->i_product_groupid . ' - ' . $data->e_product_groupname; ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label><?= $this->lang->line("Keterangan"); ?> :</label>
                                        <textarea class="form-control text-capitalize" readonly placeholder="<?= $this->lang->line('Keterangan'); ?>" id="e_remarkh" name="e_remarkh"><?= $data->e_remark; ?></textarea>
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
                                                <th class="text-center" width="3%">No</th>
                                                <th class="text-center" width="19%" valign="center"><?= $this->lang->line("Nama Barang"); ?></th>
                                                <th class="text-center" width="10%" valign="center"><?= $this->lang->line("Harga"); ?></th>
                                                <th class="text-center" width="17%" valign="center"><?= $this->lang->line("Jumlah"); ?></th>
                                                <th class="text-center" width="5%"><?= $this->lang->line("Disk1"); ?> %</th>
                                                <th class="text-center" width="5%"><?= $this->lang->line("Disk2"); ?> %</th>
                                                <th class="text-center" width="5%"><?= $this->lang->line("Disk3"); ?> %</th>
                                                <th class="text-center" width="5%"><?= $this->lang->line("Disk4"); ?> %</th>
                                                <th class="text-center" width="14%" valign="center"><?= $this->lang->line("Total"); ?></th>
                                                <th class="text-center" width="17%" valign="center"><?= $this->lang->line("Keterangan"); ?></th>
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
                                                        </td>
                                                        <td>
                                                            <input type="text" readonly class="form-control form-control-sm" value="<?= $key->i_product_id . ' - ' . $key->e_product_name; ?>">
                                                        </td>
                                                        <td>
                                                            <input type="text" value="<?= number_format($key->v_unit_price); ?>" class="form-control form-control-sm text-right" id="v_price<?= $i; ?>" name="v_price[]" readonly>
                                                            <input type="hidden" value="<?= $key->e_product_name; ?>" class="form-control form-control-sm" id="e_product_name<?= $i; ?>" name="e_product_name[]" readonly>
                                                            <input type="hidden" value="<?= $key->i_product_motif; ?>" class="form-control form-control-sm" id="i_product_motif<?= $i; ?>" name="i_product_motif[]" readonly>
                                                            <input type="hidden" value="<?= $key->i_product_grade; ?>" class="form-control form-control-sm" id="i_product_grade<?= $i; ?>" name="i_product_grade[]" readonly>
                                                            <input type="hidden" value="<?= $key->i_product_status; ?>" class="form-control form-control-sm" id="i_product_status<?= $i; ?>" name="i_product_status[]" readonly>
                                                            <input type="hidden" value="<?= $key->v_so_discount1; ?>" class="form-control text-right form-control-sm" id="v_disc1<?= $i; ?>" name="v_disc[]" readonly>
                                                            <input type="hidden" value="<?= $key->v_so_discount2; ?>" class="form-control text-right form-control-sm" id="v_disc2<?= $i; ?>" name="v_disc2[]" readonly>
                                                            <input type="hidden" value="<?= $key->v_so_discount3; ?>" class="form-control text-right form-control-sm" id="v_disc3<?= $i; ?>" name="v_disc3[]" readonly>
                                                            <input type="hidden" value="<?= $key->v_so_discount4; ?>" class="form-control text-right form-control-sm" id="v_disc4<?= $i; ?>" name="v_disc4[]" readonly>
                                                        </td>
                                                        <td><input type="text" value="<?= $key->n_order; ?>" readonly autocomplete="off" class="form-control text-right n_order form-control-sm" id="n_order<?= $i; ?>" value="1" name="n_order[]" onkeypress="return bilanganasli(event);hitung();" onkeyup="hitung()" onblur="if(this.value=='' ){this.value='1' ;hitung();}" onfocus="if(this.value=='1' ){this.value='' ;}"></td>
                                                        <td><input type="text" value="<?= $key->n_so_discount1; ?>" class="form-control text-right form-control-sm" id="n_disc1<?= $i; ?>" name="n_disc1[]" readonly></td>
                                                        <td><input type="text" value="<?= $key->n_so_discount2; ?>" class="form-control text-right form-control-sm" id="n_disc2<?= $i; ?>" name="n_disc2[]" readonly></td>
                                                        <td><input type="text" value="<?= $key->n_so_discount3; ?>" class="form-control text-right form-control-sm" id="n_disc3<?= $i; ?>" name="n_disc3[]" readonly></td>
                                                        <td><input type="text" value="<?= $key->n_so_discount4; ?>" class="form-control text-right form-control-sm" id="n_disc4<?= $i; ?>" name="n_disc4[]" readonly></td>
                                                        <td><input type="text" class="form-control text-right form-control-sm text-right" id="total_baris<?= $i; ?>" name="total_baris[]" readonly></td>
                                                        <td><input type="text" class="form-control form-control-sm " value="<?= $key->e_remark; ?>" readonly></td>
                                                    </tr>
                                            <?php }
                                            } ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="8" class="text-right"><?= $this->lang->line("Sub Total"); ?> Rp. </th>
                                                <th><input type="text" class="form-control form-control-sm text-right" id="tfoot_subtotal" name="tfoot_subtotal" value="0" readonly></th>
                                            </tr>

                                            <tr>
                                                <th colspan="8" class="text-right hidden"><?= $this->lang->line("Diskon"); ?></th>
                                                <th><input type="hidden" readonly class="formatrupiah form-control form-control-sm text-right" value="0" onkeyup="formatrupiahkeyup(this);change_ndisc();hitung();" onkeydown="formatrupiahkeydown(this);change_ndisc();hitung();" onblur="if(this.value==''){this.value='0';hitung();}" onfocus="if(this.value=='0'){this.value='';}" id="tfoot_n_diskon" name="tfoot_n_diskon"></th>
                                            </tr>

                                            <tr>
                                                <th colspan="8" class="text-right hidden"><?= $this->lang->line("Diskon Rp"); ?>. </th>
                                                <th><input type="hidden" readonly class="formatrupiah form-control form-control-sm text-right" value="<?= number_format($data->v_so_discounttotal, 2); ?>" onkeyup="formatrupiahkeyup(this);change_vdisc();hitung();" onkeydown="formatrupiahkeydown(this);change_vdisc();hitung();" onblur="if(this.value==''){this.value='0';hitung();}" onfocus="if(this.value=='0'){this.value='';}" id="tfoot_v_diskon" name="tfoot_v_diskon"></th>
                                            </tr>
                                            <tr>
                                                <th colspan="8" class="text-right"><?= $this->lang->line("DPP"); ?> Rp. </th>
                                                <th><input type="text" class="form-control form-control-sm text-right" id="tfoot_v_dpp" name="tfoot_v_dpp" readonly></th>
                                            </tr>

                                            <tr>
                                                <th colspan="8" class="text-right"><?= $this->lang->line("PPN"); ?> (<?= $data->n_so_ppn; ?>%) Rp. </th>
                                                <th><input type="text" class="form-control form-control-sm text-right" id="tfoot_v_ppn" name="tfoot_v_ppn" readonly></th>
                                            </tr>

                                            <tr>
                                                <th colspan="8" class="text-right"><?= $this->lang->line("Nilai Bersih"); ?> Rp. </th>
                                                <th><input type="text" class="form-control form-control-sm text-right font-weight-bold" id="tfoot_total" name="tfoot_total" readonly></th>
                                            </tr>
                                        </tfoot>
                                        <input type="hidden" id="jml" name="jml" value="<?= $i; ?>">
                                    </table>
                                </div>
                            </div>
                            <div class="form-actions">
                                <form action="<?= base_url($this->folder); ?>" method="post">
                                    <a href="<?= base_url($this->folder . '/index/' . encrypt_url($dfrom) . '/' . encrypt_url($dto) . '/' . encrypt_url($harea)); ?>" class="btn btn-secondary round btn-min-width mr-1"><i class="icon-action-undo mr-1"></i><?= $this->lang->line("Kembali"); ?></a>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--/ Alternative pagination table -->
</div>