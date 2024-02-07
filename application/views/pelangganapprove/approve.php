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
                    <li class="breadcrumb-item"><a href="<?= base_url($this->folder); ?>"><?= $this->lang->line($this->title); ?></a></li>
                    <li class="breadcrumb-item active"><?= $this->lang->line('Tambah'); ?> <?= $this->lang->line($this->title); ?></li>
                </ol>
            </div>
        </div>
        <!-- <h3 class="content-header-title mb-0">Basic DataTables</h3> -->
    </div>
</div>


<!-- Form wizard with step validation section start -->
<section id="validation">
    <div class="row">
        <div class="col-12">
            <div class="card box-shadow-2 border-primary">
                <div class="card-header card-head-inverse <?= $this->session->e_color; ?> bg-darken-1 text-white">
                    <h4 class="card-title"><i class="icon-plus"></i> <?= $this->lang->line('Tambah'); ?> <?= $this->lang->line($this->title); ?></h4>
                    <a class="heading-elements-toggle"><i class="fa fa-ellipsis-h font-medium-3"></i></a>
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
                    <div class="card-body">
                        <form action="#" class="steps-validation wizard-circle">
                            <input type="hidden" id="path" value="<?= $this->folder; ?>">
                            <!-- Step 1 -->
                            <h6><i class="step-icon fa fa-user-plus mr-1"></i><?= $this->lang->line('Input Pelanggan Baru'); ?></h6>
                            <fieldset>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Nama Area Provinsi"); ?> :</label>
                                            <input type="hidden" id="textcari_area" value="<?= $this->lang->line('Pilih'); ?> <?= $this->lang->line('Nama Area Provinsi'); ?>">
                                            <div class="controls">
                                                <select class="form-control form-control-sm" id="iarea" required data-validation-required-message="<?= $this->lang->line("Required"); ?>" name="iarea">
                                                    <option value="<?= $data->i_area; ?>"><?= $data->i_area_id . ' - ' . $data->e_area_name; ?></option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Kode Kota / Kabupaten"); ?> :</label>
                                            <input type="hidden" id="textcari_city" value="<?= $this->lang->line('Pilih'); ?> <?= $this->lang->line('Kode Kota / Kabupaten'); ?>">
                                            <div class="controls">
                                                <select class="form-control form-control-sm" id="icity" required data-validation-required-message="<?= $this->lang->line("Required"); ?>" name="icity">
                                                    <option value="<?= $data->i_city; ?>"><?= $data->i_city_id . ' - ' . $data->e_city_name; ?></option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Nama Area Cakupan"); ?> :</label>
                                            <input type="hidden" id="textcari_cover" value="<?= $this->lang->line('Pilih'); ?> <?= $this->lang->line('Nama Area Cakupan'); ?>">
                                            <div class="controls">
                                                <select class="form-control form-control-sm" id="icover" required data-validation-required-message="<?= $this->lang->line("Required"); ?>" name="icover">
                                                    <option value="<?= $data->i_area_cover; ?>"><?= $data->i_area_cover_id . ' - ' . $data->e_area_cover_name; ?></option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <!-- Kode -> alamat -->
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label><?= $this->lang->line('Kode Pelanggan'); ?> :</label>
                                            <div class="controls">
                                                <input type="hidden" class="form-control" id="id" name="id" value="<?= $data->i_customer; ?>">
                                                <input type="hidden" class="form-control" id="kodeold" name="kodeold" value="<?= $data->i_customer_id; ?>">
                                                <input type="text" class="form-control form-control-sm text-capitalize" data-validation-required-message="<?= $this->lang->line('Required'); ?>" placeholder="<?= $this->lang->line('Masukan'); ?> <?= $this->lang->line('Kode Pelanggan'); ?>" id="kode" name="kode" maxlength="30" autocomplete="off" required autofocus value="<?= $data->i_customer_id; ?>">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <div class="form-group">
                                                <label><?= $this->lang->line('Nama Pelanggan'); ?> :</label>
                                                <div class="controls">
                                                    <input type="text" class="form-control form-control-sm text-capitalize" data-validation-required-message="<?= $this->lang->line('Required'); ?>" placeholder="<?= $this->lang->line('Masukan'); ?> <?= $this->lang->line('Nama Pelanggan'); ?>" id="nama" name="nama" maxlength="100" autocomplete="off" required autofocus value="<?= $data->e_customer_name; ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><?= $this->lang->line('Alamat Pelanggan'); ?> :</label>
                                            <div class="controls">
                                                <textarea class="form-control form-control-sm text-capitalize" data-validation-required-message="<?= $this->lang->line('Required'); ?>" placeholder="<?= $this->lang->line('Masukan'); ?> <?= $this->lang->line('Alamat Pelanggan'); ?>" id="alamat" name="alamat" autocomplete="off" required autofocus><?= $data->e_customer_address; ?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- luas kompetitor alamat kirim -->
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label><?= $this->lang->line('Kompetitor'); ?> :</label>
                                            <div class="controls">
                                                <input type="text" class="form-control form-control-sm text-capitalize" placeholder="<?= $this->lang->line('Masukan'); ?> <?= $this->lang->line('Kompetitor'); ?>" id="e_competitor" name="e_competitor" maxlength="70" autocomplete="off" value="<?= $data->e_competitor; ?>" autofocus>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <div class="form-group">
                                                <label><?= $this->lang->line('Luas Toko m2'); ?> :</label>
                                                <div class="controls">
                                                    <input type="text" onkeypress="return bilanganasli(event);" class="form-control form-control-sm text-capitalize" placeholder="<?= $this->lang->line('Masukan'); ?> <?= $this->lang->line('Luas Toko m2'); ?>" id="n_building_m2" name="n_building_m2" value="<?= $data->n_building_m2; ?>" maxlength="100" autocomplete="off" autofocus>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="controls skin-square">
                                                <input type="checkbox" id="chk-samasp" name="chk-samasp">
                                                <label for="chk-samasp">&nbsp;&nbsp;<?= $this->lang->line('Alamat Kirim'); ?>&nbsp;<?= $this->lang->line('Sama Dengan Alamat Pelanggan'); ?></label>
                                            </div>
                                            <div class="controls">
                                                <textarea class="form-control form-control-sm text-capitalize" placeholder="<?= $this->lang->line('Masukan'); ?> <?= $this->lang->line('Alamat Kirim'); ?>" id="e_shipment_address" name="e_shipment_address" autocomplete="off" autofocus><?= $data->e_shipment_address; ?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- tanggal -> ppn -->
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label><?= $this->lang->line('Tanggal Daftar'); ?> :</label>
                                            <div class="input-group input-group-sm">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">
                                                        <span class="fa fa-calendar-o"></span>
                                                    </span>
                                                </div>
                                                <input id="tanggal" name="tanggal" type='date' class="form-control form-control-sm" placeholder="<?= $this->lang->line('Tanggal Daftar'); ?>" data-validation-required-message="<?= $this->lang->line('Required'); ?>" required value="<?= $data->d_customer_register_edit; ?>" />
                                            </div>

                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label><?= $this->lang->line('Tanggal Mulai Usaha'); ?> :</label>
                                            <div class="input-group input-group-sm">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">
                                                        <span class="fa fa-calendar-o"></span>
                                                    </span>
                                                </div>
                                                <input id="d_start" name="d_start" type='date' class="form-control form-control-sm" placeholder="<?= $this->lang->line('Tanggal Mulai Usaha'); ?>" value="<?= $data->d_start_edit; ?>" />
                                            </div>

                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label><?= $this->lang->line('TOP'); ?> :</label>
                                            <div class="controls">
                                                <input type="number" class="form-control form-control-sm text-capitalize" data-validation-number-message="<?= $this->lang->line('Number'); ?>" data-validation-required-message="<?= $this->lang->line('Required'); ?>" placeholder="<?= $this->lang->line('Masukan'); ?> <?= $this->lang->line('TOP'); ?>" id="top" name="top" min=0 autocomplete="off" required autofocus value="<?= $data->n_customer_top; ?>">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label><?= $this->lang->line('Dengan PPN'); ?></label>
                                            <div class="controls skin-square">
                                                <input type="checkbox" id="chk-ppn" name="chk-ppn" <?php if ($data->f_customer_plusppn == 't') {
                                                                                                        echo "checked";
                                                                                                    } ?>>
                                                <label for="chk-ppn">&nbsp;&nbsp;</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Pemilik -> diskon -->
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label><?= $this->lang->line('Nama Pemilik'); ?> :</label>
                                            <div class="controls">
                                                <input type="text" class="form-control form-control-sm text-capitalize" data-validation-required-message="<?= $this->lang->line('Required'); ?>" placeholder="<?= $this->lang->line('Masukan'); ?> <?= $this->lang->line('Nama Pemilik'); ?>" id="pemilik" name="pemilik" maxlength="100" autocomplete="off" autofocus value="<?= $data->e_customer_owner; ?>">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <div class="form-group">
                                                <label><?= $this->lang->line('Nomor Telepon'); ?> :</label>
                                                <div class="controls">
                                                    <input type="text" class="form-control form-control-sm text-capitalize" data-validation-required-message="<?= $this->lang->line('Required'); ?>" placeholder="<?= $this->lang->line('Masukan'); ?> <?= $this->lang->line('Nomor Telepon'); ?>" id="telepon" name="telepon" maxlength="100" autocomplete="off" autofocus value="<?= $data->e_customer_phone; ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label><?= $this->lang->line('Nomor KTP Pemilik'); ?> :</label>
                                            <div class="controls">
                                                <input type="text" class="form-control form-control-sm text-capitalize" data-validation-required-message="<?= $this->lang->line('Required'); ?>" placeholder="<?= $this->lang->line('Masukan'); ?> <?= $this->lang->line('Nomor KTP Pemilik'); ?>" value="<?= $data->e_ktp_owner; ?>" id="e_ktp_owner" name="e_ktp_owner" maxlength="50" autocomplete="off" autofocus>
                                            </div>
                                        </div>
                                    </div>



                                    <!-- <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Diskon Fleksibel</label>
                                            <div class="controls skin-square">
                                                <input type="checkbox" id="chk-flx" name="chk-flx">
                                                <label for="chk-flx">&nbsp;&nbsp;</label>
                                            </div>
                                        </div>
                                    </div> -->


                                </div>


                                <!-- ktp dan pic -->
                                <div class="row">

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label><?= $this->lang->line('Diskon1'); ?> :</label>
                                            <div class="controls">
                                                <input type="number" minlength="1" maxlength="5" class="form-control form-control-sm text-capitalize" placeholder="<?= $this->lang->line('Masukan'); ?> <?= $this->lang->line('Diskon1'); ?>" id="diskon" name="diskon" value="<?= $data->disc1; ?>" onkeyup="hitung();" autocomplete="off" autofocus>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label><?= $this->lang->line('Diskon2'); ?> :</label>
                                            <div class="controls">
                                                <input type="number" minlength="1" maxlength="5" class="form-control form-control-sm text-capitalize" placeholder="<?= $this->lang->line('Masukan'); ?> <?= $this->lang->line('Diskon2'); ?>" id="diskon2" name="diskon2" value="<?= $data->disc2; ?>" onkeyup="hitung();" autocomplete="off" autofocus>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label><?= $this->lang->line('Diskon3'); ?> :</label>
                                            <div class="controls">
                                                <input type="number" minlength="1" maxlength="5" class="form-control form-control-sm text-capitalize" placeholder="<?= $this->lang->line('Masukan'); ?> <?= $this->lang->line('Diskon3'); ?>" id="diskon3" name="diskon3" value="<?= $data->disc3; ?>" onkeyup="hitung();" autocomplete="off" autofocus>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <div class="form-group">
                                                <label><?= $this->lang->line('Nama PIC'); ?> :</label>
                                                <div class="controls">
                                                    <input type="text" class="form-control form-control-sm text-capitalize" placeholder="<?= $this->lang->line('Masukan'); ?> <?= $this->lang->line('Nama PIC'); ?>" value="<?= $data->e_pic_name; ?>" id="e_pic_name" name="e_pic_name" maxlength="500" autocomplete="off" autofocus>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <div class="form-group">
                                                <label><?= $this->lang->line('Nomor Telepon PIC'); ?> :</label>
                                                <div class="controls">
                                                    <input type="text" class="form-control form-control-sm text-capitalize" placeholder="<?= $this->lang->line('Masukan'); ?> <?= $this->lang->line('Nomor Telepon PIC'); ?>" value="<?= $data->e_pic_phone; ?>" id="e_pic_phone" name="e_pic_phone" maxlength="120" autocomplete="off" autofocus>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- npwp -> alamat -->
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label><?= $this->lang->line('Kode NPWP'); ?> :</label>
                                            <div class="controls">
                                                <input type="text" class="form-control form-control-sm text-capitalize" placeholder="<?= $this->lang->line('Masukan'); ?> <?= $this->lang->line('Kode NPWP'); ?>" id="kode_npwp" name="kode_npwp" maxlength="70" autocomplete="off" autofocus value="<?= $data->e_customer_npwpcode; ?>">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <div class="form-group">
                                                <label><?= $this->lang->line('Nama NPWP'); ?> :</label>
                                                <div class="controls">
                                                    <input type="text" class="form-control form-control-sm text-capitalize" placeholder="<?= $this->lang->line('Masukan'); ?> <?= $this->lang->line('Nama NPWP'); ?>" id="nama_npwp" name="nama_npwp" maxlength="100" autocomplete="off" autofocus value="<?= $data->e_customer_npwpname; ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="controls skin-square">
                                                <input type="checkbox" id="chk-sama" name="chk-sama">
                                                <label for="chk-ppn">&nbsp;&nbsp;<?= $this->lang->line('Alamat NPWP'); ?>&nbsp;<?= $this->lang->line('Sama Dengan Alamat Pelanggan'); ?></label>
                                            </div>
                                            <div class="controls">
                                                <textarea class="form-control form-control-sm text-capitalize" placeholder="<?= $this->lang->line('Masukan'); ?> <?= $this->lang->line('Alamat NPWP'); ?>" id="alamat_npwp" name="alamat_npwp" autocomplete="off" autofocus> <?= $data->e_customer_npwpaddress; ?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>



                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><?= $this->lang->line('Ekspedisi Pelanggan'); ?> :</label>
                                            <div class="controls">
                                                <input type="text" class="form-control form-control-sm text-capitalize" data-validation-required-message="<?= $this->lang->line('Required'); ?>" id="e_ekspedisi_cus" name="e_ekspedisi_cus" value="<?= $data->e_ekspedisi_cus; ?>">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="form-group">
                                                <label><?= $this->lang->line('Pembayaran Ekspedisi'); ?> :</label>
                                                <div class="controls">
                                                    <input type="text" class="form-control form-control-sm text-capitalize" data-validation-required-message="<?= $this->lang->line('Required'); ?>" id="e_ekspedisi_bayar" name="e_ekspedisi_bayar" value="<?= $data->e_ekspedisi_bayar; ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <!-- group -> status -->
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Nama Kelompok Pelanggan"); ?> :</label>
                                            <input type="hidden" id="textcari_group" value="<?= $this->lang->line('Pilih'); ?> <?= $this->lang->line('Nama Kelompok Pelanggan'); ?>">
                                            <div class="controls">
                                                <select class="form-control form-control-sm" id="igroup" required data-validation-required-message="<?= $this->lang->line("Required"); ?>" name="igroup">
                                                    <option value="<?= $data->i_customer_group; ?>"><?= $data->i_customer_groupid . ' - ' . $data->e_customer_groupname; ?></option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Nama Tingkat Pelanggan"); ?> :</label>
                                            <input type="hidden" id="textcari_level" value="<?= $this->lang->line('Pilih'); ?> <?= $this->lang->line('Nama Tingkat Pelanggan'); ?>">
                                            <div class="controls">
                                                <select class="form-control form-control-sm" id="ilevel" required data-validation-required-message="<?= $this->lang->line("Required"); ?>" name="ilevel">
                                                    <option value="<?= $data->i_customer_level; ?>"><?= $data->i_customer_levelid . ' - ' . $data->e_customer_levelname; ?></option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Nama Status Pelanggan"); ?> :</label>
                                            <input type="hidden" id="textcari_status" value="<?= $this->lang->line('Pilih'); ?> <?= $this->lang->line('Nama Status Pelanggan'); ?>">
                                            <div class="controls">
                                                <select class="form-control form-control-sm" id="istatus" required data-validation-required-message="<?= $this->lang->line("Required"); ?>" name="istatus">
                                                    <option value="<?= $data->i_customer_status; ?>"><?= $data->i_customer_statusid . ' - ' . $data->e_customer_statusname; ?></option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <!-- kodeharga -> status -->
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Nama Kelompok Harga"); ?> :</label>
                                            <input type="hidden" id="textcari_price" value="<?= $this->lang->line('Pilih'); ?> <?= $this->lang->line('Nama Kelompok Harga'); ?>">
                                            <div class="controls">
                                                <select class="form-control form-control-sm" id="iprice" required data-validation-required-message="<?= $this->lang->line("Required"); ?>" name="iprice">
                                                    <option value="<?= $data->i_price_group; ?>"><?= $data->i_price_groupid . ' - ' . $data->e_price_groupname; ?></option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Nama Tipe Pelanggan"); ?> :</label>
                                            <input type="hidden" id="textcari_type" value="<?= $this->lang->line('Pilih'); ?> <?= $this->lang->line('Nama Tipe Pelanggan'); ?>">
                                            <div class="controls">
                                                <select class="form-control form-control-sm" id="itype" required data-validation-required-message="<?= $this->lang->line("Required"); ?>" name="itype">
                                                    <option value="<?= $data->i_customer_type; ?>"><?= $data->i_customer_typeid . ' - ' . $data->e_customer_typename; ?></option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Nama Pola Pembayaran"); ?> :</label>
                                            <input type="hidden" id="textcari_payment" value="<?= $this->lang->line('Pilih'); ?> <?= $this->lang->line('Nama Pola Pembayaran'); ?>">
                                            <div class="controls">
                                                <select class="form-control form-control-sm" id="ipayment" required data-validation-required-message="<?= $this->lang->line("Required"); ?>" name="ipayment">
                                                    <option value="<?= $data->i_customer_payment; ?>"><?= $data->i_customer_paymentid . ' - ' . $data->e_customer_paymentname; ?></option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- <div class="col-md-4">
                                        <div class="form-group">
                                            <div class="controls skin-square">
                                                <input type="checkbox" id="chk-flafon" name="chk-flafon">
                                                <label for="chk-ppn">&nbsp;<?= $this->lang->line('Centang Jika Buat'); ?>
                                                    <?= $this->lang->line("Nama Kelompok Bayar"); ?>&nbsp;<?= $this->lang->line("Baru"); ?>
                                                </label>
                                            </div>
                                            <input type="hidden" id="textcari_paygroup" value="<?= $this->lang->line('Pilih'); ?> <?= $this->lang->line('Nama Kelompok Bayar'); ?>">
                                            <div class="controls">
                                                <select class="form-control form-control-sm" id="ipaygroup" required data-validation-required-message="<?= $this->lang->line("Required"); ?>" name="ipaygroup">
                                                    <option value="<?= $data->i_customer_paygroup; ?>"><?= $data->i_customer_paygroupid . ' - ' . $data->e_customer_paygroupname; ?></option>
                                                </select>
                                            </div>
                                        </div>
                                    </div> -->
                                </div>
                            </fieldset>

                            <!-- Step 2 -->
                            <h6><i class="step-icon fa fa-pencil mr-1"></i><?= $this->lang->line('Input Surat Pemesanan Barang'); ?></h6>
                            <fieldset>
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
                                                    <input type="hidden" name="i_so" id="i_so" class="form-control" value="<?= $dataso->i_so; ?>">
                                                    <input readonly value="<?= $dataso->i_so_id; ?>" placeholder="SO-<?= date('ym'); ?>-000001" class="form-control form-control-sm text-uppercase">
                                                </div>
                                            </fieldset>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Tanggal Dokumen"); ?> :</label>
                                            <div class="input-group input-group-sm controls">
                                                <input readonly type="date" class="form-control form-control-sm date" value="<?= $dataso->d_so; ?>">
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
                                            <label><?= $this->lang->line("Nama Pramuniaga"); ?> :</label>
                                            <div class="controls">
                                                <input readonly class="form-control form-control-sm date" value="<?= $dataso->i_salesman_id . ' - ' . $dataso->e_salesman_name; ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Baris ke 3 -->
                                <input type="hidden" id="ppn" name="ppn" value="<?= $dataso->f_so_plusppn; ?>">
                                <input type="hidden" id="nppn" name="nppn" value="<?= $dataso->n_so_ppn; ?>">

                                <!-- Baris ke 4 -->
                                <div class="row">

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Kelompok Barang"); ?> :</label>
                                            <div class="controls">
                                                <input readonly class="form-control form-control-sm date" value="<?= $dataso->i_product_groupid . ' - ' . $dataso->e_product_groupname; ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label><?= $this->lang->line('Stock'); ?></label>
                                            <div class="controls">
                                                <fieldset>
                                                    <div class="float-left">
                                                        <input type="checkbox" <?php if ($dataso->f_so_stockdaerah == 't') {
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
                                                    <input readonly value="<?= $dataso->e_po_reff; ?>" class="form-control form-control-sm text-capitalize" placeholder="<?= $this->lang->line('Nomor PO'); ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label><?= $this->lang->line('Tanggal Berakhir PO'); ?> :</label>
                                            <div class="input-group input-group-sm controls">
                                                <input type='text' value="<?php if ($dataso->d_po_reff != '' || $dataso->d_po_reff != null) {
                                                                                echo date('d-m-Y', strtotime($dataso->d_po_reff));
                                                                            } ?>" readonly class="form-control form-control-sm date" placeholder="<?= $this->lang->line('Tanggal Berakhir PO'); ?>" />
                                                <div class="input-group-append">
                                                    <span class="input-group-text">
                                                        <span class="fa fa-calendar-o"></span>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Baris ke 5 -->
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Keterangan"); ?> :</label>
                                            <textarea class="form-control text-capitalize" readonly placeholder="<?= $this->lang->line('Keterangan'); ?>" id="e_remarkh" name="e_remarkh"><?= $dataso->e_remark; ?></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-body">
                                            <div class="table-responsive">

                                                <table class="table table-xs table-column table-bordered" id="tabledetail">
                                                    <thead class="<?= $this->session->e_color; ?> bg-darken-1 text-white">
                                                        <tr>
                                                            <th class="text-center" width="3%">No</th>
                                                            <th class="text-center" width="24%" valign="center"><?= $this->lang->line("Nama Barang"); ?></th>
                                                            <th class="text-center" width="10%" valign="center"><?= $this->lang->line("Harga"); ?></th>
                                                            <th class="text-center" width="14%" valign="center"><?= $this->lang->line("Jumlah"); ?></th>
                                                            <th class="text-center" width="6%"><?= $this->lang->line("Disk1"); ?> %</th>
                                                            <th class="text-center" width="6%"><?= $this->lang->line("Disk2"); ?> %</th>
                                                            <th class="text-center" width="6%"><?= $this->lang->line("Disk3"); ?> %</th>
                                                            <th class="text-center" width="14%" valign="center"><?= $this->lang->line("Total"); ?></th>
                                                            <th class="text-center" width="17%" valign="center"><?= $this->lang->line("Keterangan"); ?></th>
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
                                                                $i++;
                                                                $total = $key->v_unit_price * $key->n_order;
                                                                $v_diskon1 = $total * ($key->n_so_discount1 / 100);
                                                                $v_diskon2 = ($total - $v_diskon1) * ($key->n_so_discount2 / 100);
                                                                $v_diskon3 = ($total - $v_diskon1 - $v_diskon2) * ($key->n_so_discount3 / 100);
                                                                $v_total_discount = $v_diskon1 + $v_diskon2 + $v_diskon3; ?>
                                                                <tr>
                                                                    <td class="text-center"><?= $i; ?></td>
                                                                    <td><?= $key->i_product_id . ' - ' . $key->e_product_name; ?></td>
                                                                    <td class="text-right"><?= number_format($key->v_unit_price); ?></td>
                                                                    <td class="text-right"><?= $key->n_order; ?></td>
                                                                    <td><?= $key->n_so_discount1; ?></td>
                                                                    <td><?= $key->n_so_discount2; ?></td>
                                                                    <td><?= $key->n_so_discount3; ?></td>
                                                                    <td class="text-right"><?= number_format($total); ?></td>
                                                                    <td><?= $key->e_remark; ?></td>
                                                                </tr>
                                                        <?php
                                                                $subtotal += $total;
                                                                $distotal += $v_total_discount;
                                                            }
                                                            $dpp = $subtotal - $distotal - $dataso->v_so_discounttotal;
                                                            $nppn = ($dataso->f_so_plusppn == 't') ? $dataso->n_so_ppn : 0;
                                                            $ppn = $dpp * ($nppn / 100);
                                                            $grandtotal = $dpp + $ppn;
                                                        } ?>
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <?php if ($dataso->i_so_refference != '') {
                                                                $reff = $this->db->get_where('tm_so', ['f_so_cancel' => false, 'i_company' => $this->i_company, 'i_so' => $dataso->i_so_refference])->row()->i_so_id;
                                                            } else {
                                                                $reff = '';
                                                            } ?>
                                                            <td width="72%" colspan="2"><strong>KETERANGAN : ( <?= $reff; ?> ) <?= $dataso->e_remark; ?></strong></td>
                                                            <th width="17%" class="text-right" colspan="5"><?= $this->lang->line('Sub Total'); ?> Rp. </th>
                                                            <td width="11%" class="text-right"><strong><?= number_format($subtotal); ?></strong></td>
                                                        </tr>
                                                        <tr>
                                                            <th class="text-right" colspan="7"><?= $this->lang->line('Diskon Per Item'); ?> Rp. </th>
                                                            <td class="text-right"><?= number_format($distotal); ?></td>
                                                        </tr>
                                                        <tr>
                                                            <th class="text-right hidden" colspan="7"><?= $this->lang->line('Diskon Tambahan'); ?> Rp. </th>
                                                            <td class="text-right hidden"><?= number_format($dataso->v_so_discounttotal); ?></li>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <?php if ($dataso->f_so_plusppn == 't') { ?>
                                                                <th class="text-right" colspan="7"><?= $this->lang->line('DPP'); ?> Rp. </th>
                                                                <td class="text-right"><?= number_format($dpp); ?></td>
                                                            <?php } else { ?>
                                                                <th class="text-right" colspan="7"><?= $this->lang->line('Nilai Bersih'); ?> Rp. </th>
                                                                <th class="text-right"><?= number_format($grandtotal); ?></th>
                                                            <?php } ?>
                                                        </tr>
                                                        <tr>
                                                            <?php if ($dataso->f_so_plusppn == 't') { ?>
                                                                <th class="text-right" colspan="7"><?= $this->lang->line('PPN'); ?> (<?= $dataso->n_so_ppn; ?>%) Rp. </th>
                                                                <td class="text-right"><?= number_format($ppn); ?></li>
                                                                </td>
                                                            <?php } ?>
                                                        </tr>
                                                        <tr>
                                                            <?php if ($dataso->f_so_plusppn == 't') { ?>
                                                                <th class="text-right" colspan="7"><?= $this->lang->line('Nilai Bersih'); ?> Rp. </th>
                                                                <th class="text-right font-medium-3"><?= number_format($grandtotal); ?></th>
                                                            <?php } ?>
                                                        </tr>
                                                    </tfoot>
                                                    <input type="hidden" id="jml" name="jml" value="<?= $i; ?>">
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>