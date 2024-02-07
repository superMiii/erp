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
                                            <label><?= $this->lang->line("Nama Area Provinsi"); ?> : <span class="danger">*</span></label>
                                            <input type="hidden" id="textcari_area" value="<?= $this->lang->line('Pilih'); ?> <?= $this->lang->line('Nama Area Provinsi'); ?>">
                                            <div class="controls">
                                                <select class="form-control form-control-sm" id="iarea" required data-validation-required-message="<?= $this->lang->line("Required"); ?>" name="iarea">
                                                    <option value=""></option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Kode Kota / Kabupaten"); ?> : <span class="danger">*</span></label>
                                            <input type="hidden" id="textcari_city" value="<?= $this->lang->line('Pilih'); ?> <?= $this->lang->line('Kode Kota / Kabupaten'); ?>">
                                            <div class="controls">
                                                <select class="form-control form-control-sm" id="icity" required data-validation-required-message="<?= $this->lang->line("Required"); ?>" name="icity">
                                                    <option value=""></option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Nama Area Cakupan"); ?> : <span class="danger">*</span></label>
                                            <input type="hidden" id="textcari_cover" value="<?= $this->lang->line('Pilih'); ?> <?= $this->lang->line('Nama Area Cakupan'); ?>">
                                            <div class="controls">
                                                <select class="form-control form-control-sm" id="icover" required data-validation-required-message="<?= $this->lang->line("Required"); ?>" name="icover">
                                                    <option value=""></option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <!-- Kode -> alamat -->
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label><?= $this->lang->line('Kode Pelanggan'); ?> : <span class="danger">*</span></label>
                                            <div class="controls">
                                                <input type="text" class="form-control form-control-sm text-capitalize" data-validation-required-message="<?= $this->lang->line('Required'); ?>" placeholder="<?= $this->lang->line('Masukan'); ?> <?= $this->lang->line('Kode Pelanggan'); ?>" id="kode" name="kode" maxlength="30" autocomplete="off" required autofocus>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <div class="form-group">
                                                <label><?= $this->lang->line('Nama Pelanggan'); ?> : <span class="danger">*</span></label>
                                                <div class="controls">
                                                    <input type="text" class="form-control form-control-sm text-capitalize" data-validation-required-message="<?= $this->lang->line('Required'); ?>" placeholder="<?= $this->lang->line('Masukan'); ?> <?= $this->lang->line('Nama Pelanggan'); ?>" id="nama" name="nama" maxlength="100" autocomplete="off" required autofocus>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><?= $this->lang->line('Alamat Pelanggan'); ?> : <span class="danger">*</span></label>
                                            <div class="controls">
                                                <textarea class="form-control form-control-sm text-capitalize" data-validation-required-message="<?= $this->lang->line('Required'); ?>" placeholder="<?= $this->lang->line('Masukan'); ?> <?= $this->lang->line('Alamat Pelanggan'); ?>" id="alamat" name="alamat" autocomplete="off" required autofocus></textarea>
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
                                                <input type="text" class="form-control form-control-sm text-capitalize" placeholder="<?= $this->lang->line('Masukan'); ?> <?= $this->lang->line('Kompetitor'); ?>" id="e_competitor" name="e_competitor" maxlength="70" autocomplete="off" autofocus>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <div class="form-group">
                                                <label><?= $this->lang->line('Luas Toko m2'); ?> :</label>
                                                <div class="controls">
                                                    <input type="text" class="form-control form-control-sm text-capitalize" placeholder="<?= $this->lang->line('Masukan'); ?> <?= $this->lang->line('Luas Toko m2'); ?>" id="n_building_m2" name="n_building_m2" maxlength="100" autocomplete="off" autofocus>
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
                                                <textarea class="form-control form-control-sm text-capitalize" placeholder="<?= $this->lang->line('Masukan'); ?> <?= $this->lang->line('Alamat Kirim'); ?>" id="e_shipment_address" name="e_shipment_address" autocomplete="off" autofocus></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- tanggal -> ppn -->
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label><?= $this->lang->line('Tanggal Daftar'); ?> : <span class="danger">*</span></label>
                                            <div class="input-group input-group-sm">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">
                                                        <span class="fa fa-calendar-o"></span>
                                                    </span>
                                                </div>
                                                <input id="tanggal" name="tanggal" min="<?= get_min_date(); ?>" max="<?= date('Y-m-d'); ?>" value="<?= date('Y-m-d'); ?>" type='date' class="form-control form-control-sm" placeholder="<?= $this->lang->line('Tanggal Daftar'); ?>" data-validation-required-message="<?= $this->lang->line('Required'); ?>" required />
                                            </div>

                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label><?= $this->lang->line('Tanggal Mulai Usaha'); ?> : </label>
                                            <div class="input-group input-group-sm">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">
                                                        <span class="fa fa-calendar-o"></span>
                                                    </span>
                                                </div>
                                                <input id="d_start" name="d_start" type='date' max="<?= date('Y-m-d'); ?>" class="form-control form-control-sm" placeholder="<?= $this->lang->line('Tanggal Mulai Usaha'); ?>" />
                                            </div>

                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label><?= $this->lang->line('TOP'); ?> : <span class="danger">*</span></label>
                                            <div class="controls">
                                                <input type="number" minlength="1" maxlength="4" value="30" class="form-control form-control-sm text-capitalize" data-validation-number-message="<?= $this->lang->line('Number'); ?>" data-validation-required-message="<?= $this->lang->line('Required'); ?>" placeholder="<?= $this->lang->line('Masukan'); ?> <?= $this->lang->line('TOP'); ?>" id="top" name="top" min=0 autocomplete="off" required autofocus>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label><?= $this->lang->line('Dengan PPN'); ?></label>
                                            <div class="controls skin-square">
                                                <input type="checkbox" id="chk-ppn" name="chk-ppn">
                                                <label for="chk-ppn">&nbsp;&nbsp;</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Pemilik -> diskon -->
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label><?= $this->lang->line('Nama Pemilik'); ?> : <span class="danger">*</span></label>
                                            <div class="controls">
                                                <input type="text" class="form-control form-control-sm text-capitalize" data-validation-required-message="<?= $this->lang->line('Required'); ?>" placeholder="<?= $this->lang->line('Masukan'); ?> <?= $this->lang->line('Nama Pemilik'); ?>" id="pemilik" name="pemilik" maxlength="100" autocomplete="off" autofocus>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <div class="form-group">
                                                <label><?= $this->lang->line('Nomor Telepon'); ?> : <span class="danger">*</span></label>
                                                <div class="controls">
                                                    <input type="text" class="form-control form-control-sm text-capitalize" data-validation-required-message="<?= $this->lang->line('Required'); ?>" placeholder="<?= $this->lang->line('Masukan'); ?> <?= $this->lang->line('Nomor Telepon'); ?>" id="telepon" name="telepon" maxlength="100" autocomplete="off" autofocus>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label><?= $this->lang->line('Nomor KTP Pemilik'); ?> : <span class="danger">*</span></label>
                                            <div class="controls">
                                                <input type="number" required data-validation-required-message="<?= $this->lang->line('Required'); ?>" class="form-control form-control-sm text-capitalize" placeholder="<?= $this->lang->line('Masukan'); ?> <?= $this->lang->line('Nomor KTP Pemilik'); ?>" id="e_ktp_owner" name="e_ktp_owner" maxlength="50" autocomplete="off" autofocus>
                                            </div>
                                        </div>
                                    </div>



                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Diskon Fleksibel</label>
                                            <div class="controls skin-square">
                                                <input type="checkbox" id="chk-flx" name="chk-flx">
                                                <label for="chk-flx">&nbsp;&nbsp;</label>
                                            </div>
                                        </div>
                                    </div>




                                </div>

                                <!-- ktp dan pic -->
                                <div class="row">

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label><?= $this->lang->line('Diskon1'); ?> :</label>
                                            <div class="controls">
                                                <input type="number" pattern="([0-9]|[1-9][0-9]|100)" onblur="if(this.value==''){this.value='0';}" onfocus="if(this.value=='0'){this.value='';}" minlength="1" maxlength="5" class="form-control form-control-sm text-capitalize" placeholder="<?= $this->lang->line('Diskon1'); ?>" id="diskon" name="diskon" value="0" autocomplete="off" autofocus>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label><?= $this->lang->line('Diskon2'); ?> :</label>
                                            <div class="controls">
                                                <input type="number" pattern="([0-9]|[1-9][0-9]|100)" onblur="if(this.value==''){this.value='0';}" onfocus="if(this.value=='0'){this.value='';}" minlength="1" maxlength="5" class="form-control form-control-sm text-capitalize" placeholder="<?= $this->lang->line('Diskon2'); ?>" id="diskon2" name="diskon2" value="0" autocomplete="off" autofocus>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label><?= $this->lang->line('Diskon3'); ?> :</label>
                                            <div class="controls">
                                                <input type="number" pattern="([0-9]|[1-9][0-9]|100)" onblur="if(this.value==''){this.value='0';}" onfocus="if(this.value=='0'){this.value='';}" minlength="1" maxlength="5" class="form-control form-control-sm text-capitalize" placeholder="<?= $this->lang->line('Diskon3'); ?>" id="diskon3" name="diskon3" value="0" autocomplete="off" autofocus>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <div class="form-group">
                                                <label><?= $this->lang->line('Nama PIC'); ?> :</label>
                                                <div class="controls">
                                                    <input type="text" class="form-control form-control-sm text-capitalize" placeholder="<?= $this->lang->line('Masukan'); ?> <?= $this->lang->line('Nama PIC'); ?>" id="e_pic_name" name="e_pic_name" maxlength="500" autocomplete="off" autofocus>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <div class="form-group">
                                                <label><?= $this->lang->line('Nomor Telepon PIC'); ?> :</label>
                                                <div class="controls">
                                                    <input type="text" class="form-control form-control-sm text-capitalize" placeholder="<?= $this->lang->line('Masukan'); ?> <?= $this->lang->line('Nomor Telepon PIC'); ?>" id="e_pic_phone" name="e_pic_phone" maxlength="120" autocomplete="off" autofocus>
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
                                                <input type="text" class="form-control form-control-sm text-capitalize" placeholder="<?= $this->lang->line('Masukan'); ?> <?= $this->lang->line('Kode NPWP'); ?>" id="kode_npwp" name="kode_npwp" maxlength="70" autocomplete="off" autofocus>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <div class="form-group">
                                                <label><?= $this->lang->line('Nama NPWP'); ?> :</label>
                                                <div class="controls">
                                                    <input type="text" class="form-control form-control-sm text-capitalize" placeholder="<?= $this->lang->line('Masukan'); ?> <?= $this->lang->line('Nama NPWP'); ?>" id="nama_npwp" name="nama_npwp" maxlength="100" autocomplete="off" autofocus>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="controls skin-square">
                                                <input type="checkbox" id="chk-sama" name="chk-sama">
                                                <label for="chk-sama">&nbsp;&nbsp;<?= $this->lang->line('Alamat NPWP'); ?>&nbsp;<?= $this->lang->line('Sama Dengan Alamat Pelanggan'); ?></label>
                                            </div>
                                            <div class="controls">
                                                <textarea class="form-control form-control-sm text-capitalize" placeholder="<?= $this->lang->line('Masukan'); ?> <?= $this->lang->line('Alamat NPWP'); ?>" id="alamat_npwp" name="alamat_npwp" autocomplete="off" autofocus></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <!-- ################## -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><?= $this->lang->line('Ekspedisi Pelanggan'); ?> :</label>
                                            <div class="controls">
                                                <input type="text" class="form-control round text-capitalize" placeholder="<?= $this->lang->line('Masukan'); ?> <?= $this->lang->line('Ekspedisi Pelanggan'); ?>" id="e_ekspedisi_cus" name="e_ekspedisi_cus" maxlength="200" autocomplete="off" autofocus>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><?= $this->lang->line('Pembayaran Ekspedisi'); ?> :</label>
                                            <div class="controls">
                                                <select name="e_ekspedisi_bayar" id="e_ekspedisi_bayar" class="form-control select2">
                                                    <option value="-">-</option>
                                                    <option value="Dibayar Toko Semua">Dibayar Toko Semua</option>
                                                    <option value="Dibayar Toko Sebagian">Dibayar Toko Sebagian</option>
                                                    <option value="Dibayar Toko">Dibayar Toko</option>
                                                    <option value="Dibayar Perusahaan">Dibayar Perusahaan</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- ################## -->



                                <!-- group -> status -->
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Nama Kelompok Pelanggan"); ?> : <span class="danger">*</span></label>
                                            <input type="hidden" id="textcari_group" value="<?= $this->lang->line('Pilih'); ?> <?= $this->lang->line('Nama Kelompok Pelanggan'); ?>">
                                            <div class="controls">
                                                <select class="form-control form-control-sm" id="igroup" required data-validation-required-message="<?= $this->lang->line("Required"); ?>" name="igroup">
                                                    <option value=""></option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Nama Tingkat Pelanggan"); ?> : <span class="danger">*</span></label>
                                            <input type="hidden" id="textcari_level" value="<?= $this->lang->line('Pilih'); ?> <?= $this->lang->line('Nama Tingkat Pelanggan'); ?>">
                                            <div class="controls">
                                                <select class="form-control form-control-sm" id="ilevel" required data-validation-required-message="<?= $this->lang->line("Required"); ?>" name="ilevel">
                                                    <option value=""></option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Nama Status Pelanggan"); ?> : <span class="danger">*</span></label>
                                            <input type="hidden" id="textcari_status" value="<?= $this->lang->line('Pilih'); ?> <?= $this->lang->line('Nama Status Pelanggan'); ?>">
                                            <div class="controls">
                                                <select class="form-control form-control-sm" id="istatus" required data-validation-required-message="<?= $this->lang->line("Required"); ?>" name="istatus">
                                                    <option value=""></option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <!-- kodeharga -> status -->
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Nama Kelompok Harga"); ?> : <span class="danger">*</span></label>
                                            <input type="hidden" id="textcari_price" value="<?= $this->lang->line('Pilih'); ?> <?= $this->lang->line('Nama Kelompok Harga'); ?>">
                                            <div class="controls">
                                                <select class="form-control form-control-sm" id="iprice" required data-validation-required-message="<?= $this->lang->line("Required"); ?>" name="iprice">
                                                    <option value=""></option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Nama Tipe Pelanggan"); ?> : <span class="danger">*</span></label>
                                            <input type="hidden" id="textcari_type" value="<?= $this->lang->line('Pilih'); ?> <?= $this->lang->line('Nama Tipe Pelanggan'); ?>">
                                            <div class="controls">
                                                <select class="form-control form-control-sm" id="itype" required data-validation-required-message="<?= $this->lang->line("Required"); ?>" name="itype">
                                                    <option value=""></option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Nama Pola Pembayaran"); ?> : <span class="danger">*</span></label>
                                            <input type="hidden" id="textcari_payment" value="<?= $this->lang->line('Pilih'); ?> <?= $this->lang->line('Nama Pola Pembayaran'); ?>">
                                            <div class="controls">
                                                <select class="form-control form-control-sm" id="ipayment" required data-validation-required-message="<?= $this->lang->line("Required"); ?>" name="ipayment">
                                                    <option value=""></option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- <div class="col-md-4">
                                        <div class="form-group">
                                            <div class="controls skin-square">
                                                <input type="checkbox" id="chk-flafon" name="chk-flafon" checked>
                                                <label for="chk-ppn">&nbsp;<?= $this->lang->line('Centang Jika Buat'); ?>
                                                    <?= $this->lang->line("Nama Kelompok Bayar"); ?>&nbsp;<?= $this->lang->line("Baru"); ?>
                                                </label>
                                            </div>
                                            <input type="hidden" id="textcari_paygroup" value="<?= $this->lang->line('Pilih'); ?> <?= $this->lang->line('Nama Kelompok Bayar'); ?>">
                                            <div class="controls">
                                                <select class="form-control form-control-sm" id="ipaygroup" name="ipaygroup" disabled>
                                                    <option value=""></option>
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
                                                    <input type="text" name="i_document" id="i_document" readonly placeholder="SO-<?= date('ym'); ?>-000001" class="form-control form-control-sm text-uppercase" data-validation-required-message="<?= $this->lang->line("Required"); ?>" maxlength="500" autocomplete="off" required aria-label="Text input with checkbox">
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
                                                <input type="date" class="form-control form-control-sm date" min="2021-10-01" max="<?= date('Y-m-d'); ?>" <?= konci(); ?> value="<?= date('Y-m-d'); ?>" data-validation-required-message="<?= $this->lang->line("Required"); ?>" placeholder="" id="d_document" name="d_document" maxlength="500" autocomplete="off" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Nama Pramuniaga"); ?> :</label>
                                            <div class="controls">
                                                <select class="form-control round" name="i_salesman" id="i_salesman" required data-placeholder="<?= $this->lang->line("Pilih") . ' ' . $this->lang->line("Pramuniaga"); ?>" data-validation-required-message="<?= $this->lang->line("Required"); ?>">
                                                    <option value=""></option>
                                                </select>
                                            </div>
                                        </div>
                                        <!-- <div class="form-group">
                                            <label><?= $this->lang->line("Nama Area Provinsi"); ?> :</label>
                                            <div class="controls">
                                                <select class="form-control" id="i_area" required data-placeholder="<?= $this->lang->line("Pilih") . ' ' . $this->lang->line("Area"); ?>" data-validation-required-message="<?= $this->lang->line("Required"); ?>" name="i_area">
                                                    <option value=""></option>
                                                </select>
                                            </div>
                                        </div> -->
                                    </div>
                                </div>
                                <!-- Baris ke 2 -->
                                <!-- <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Nama Pelanggan"); ?> :</label>
                                            <div class="controls">
                                                <select class="form-control" name="i_customer" id="i_customer" data-placeholder="<?= $this->lang->line("Pilih") . ' ' . $this->lang->line("Pelanggan"); ?>" required data-validation-required-message="<?= $this->lang->line("Required"); ?>">
                                                    <option value=""></option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><?= $this->lang->line('Alamat Pelanggan'); ?> :</label>
                                            <div class="controls">
                                                <textarea class="form-control text-capitalize" placeholder="<?= $this->lang->line('Alamat Pelanggan'); ?>" id="alamat" name="alamat" autocomplete="off" readonly></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div> -->
                                <!-- Bari ke 3 -->
                                <!-- <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <div class="form-group">
                                                <label><?= $this->lang->line('TOP'); ?> :</label>
                                                <div class="controls">
                                                    <input type="text" class="form-control form-control-sm text-capitalize" placeholder="<?= $this->lang->line('TOP'); ?>" id="n_so_toplength" name="n_so_toplength" autocomplete="off" readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <div class="form-group">
                                                <label><?= $this->lang->line('Kode NPWP'); ?> :</label>
                                                <div class="controls">
                                                    <input type="text" class="form-control form-control-sm text-capitalize" placeholder="<?= $this->lang->line('Kode NPWP'); ?>" id="e_customer_pkpnpwp" name="e_customer_pkpnpwp" autocomplete="off" readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <div class="form-group">
                                                <label><?= $this->lang->line('PPN'); ?> :</label>
                                                <div class="controls">
                                                    <input type="text" class="form-control form-control-sm text-capitalize" data-validation-required-message="<?= $this->lang->line('Required'); ?>" placeholder="<?= $this->lang->line('PPN'); ?>" id="eppn" name="eppn" autocomplete="off" readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <div class="form-group">
                                                <label><?= $this->lang->line('Nama Kelompok Harga'); ?> :</label>
                                                <div class="controls">
                                                    <input type="text" class="form-control form-control-sm text-capitalize" data-validation-required-message="<?= $this->lang->line('Required'); ?>" placeholder="<?= $this->lang->line('Nama Kelompok Harga'); ?>" id="epricegroup" name="epricegroup" autocomplete="off" readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div> -->

                                <!-- Baris ke 4 -->
                                <div class="row">
                                    <input type="hidden" id="ppn" name="ppn" value="<?= $this->session->f_company_plusppn; ?>">
                                    <input type="hidden" id="nppn" name="nppn" value="<?= $this->session->n_ppn; ?>">
                                    <div class="col-md-4">
                                        <!--  <div class="form-group">
                                            <label><?= $this->lang->line("Nama Pramuniaga"); ?> :</label>
                                            <div class="controls">
                                                <select class="form-control round" name="i_salesman" id="i_salesman" required data-placeholder="<?= $this->lang->line("Pilih") . ' ' . $this->lang->line("Pramuniaga"); ?>" data-validation-required-message="<?= $this->lang->line("Required"); ?>">
                                                    <option value=""></option>
                                                </select>
                                            </div>
                                        </div> -->
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Kelompok Barang"); ?> :</label>
                                            <div class="controls">
                                                <select class="form-control" name="i_product_group" id="i_product_group" required data-placeholder="<?= $this->lang->line("Pilih") . ' ' . $this->lang->line("Kelompok Barang"); ?>" data-validation-required-message="<?= $this->lang->line("Required"); ?>">
                                                    <option value=""></option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label><?= $this->lang->line('Stock'); ?></label>
                                            <div class="controls">
                                                <fieldset>
                                                    <div class="float-left">
                                                        <input type="checkbox" id="f_so_stockdaerah" name="f_so_stockdaerah" class="switch" data-onstyle="success" data-group-cls="btn-group-sm" data-on-label="<?= $this->lang->line('Daerah'); ?>" data-off-label="<?= $this->lang->line('Pusat'); ?>" data-switch-always />
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
                                                    <input type="text" id="i_so_po" name="i_so_po" class="form-control form-control-sm text-capitalize" placeholder="<?= $this->lang->line('Nomor PO'); ?>" autocomplete="off">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label><?= $this->lang->line('Tanggal Berakhir PO'); ?> :</label>
                                            <div class="input-group input-group-sm controls">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">
                                                        <span class="fa fa-calendar-o"></span>
                                                    </span>
                                                </div>
                                                <input id="d_po" name="d_po" type='date' min="<?= date('Y-m-d'); ?>" value="" class="form-control form-control-sm" placeholder="<?= $this->lang->line('Tanggal PO'); ?>" />
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Baris ke 5 -->
                                <div class="row">
                                    <!-- <div class="col-md-6">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Kelompok Barang"); ?> :</label>
                                            <div class="controls">
                                                <select class="form-control" name="i_product_group" id="i_product_group" required data-placeholder="<?= $this->lang->line("Pilih") . ' ' . $this->lang->line("Kelompok Barang"); ?>" data-validation-required-message="<?= $this->lang->line("Required"); ?>">
                                                    <option value=""></option>
                                                </select>
                                            </div>
                                        </div>
                                    </div> -->
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Keterangan"); ?> :</label>
                                            <textarea class="form-control text-capitalize" placeholder="<?= $this->lang->line('Keterangan'); ?>" id="e_remarkh" name="e_remarkh"></textarea>
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
                                                            <th class="text-center" width="21%" valign="center"><?= $this->lang->line("Nama Barang"); ?></th>
                                                            <th class="text-center" width="10%" valign="center"><?= $this->lang->line("Harga"); ?></th>
                                                            <th class="text-center" width="17%" valign="center"><?= $this->lang->line("Jumlah"); ?></th>
                                                            <th class="text-center" width="7%"><?= $this->lang->line("Disk1"); ?> %</th>
                                                            <th class="text-center" width="7%"><?= $this->lang->line("Disk2"); ?> %</th>
                                                            <th class="text-center hidden" width="7%"><?= $this->lang->line("Disk3"); ?> %</th>
                                                            <th class="text-center" width="14%" valign="center"><?= $this->lang->line("Total"); ?></th>
                                                            <th class="text-center" width="17%" valign="center"><?= $this->lang->line("Keterangan"); ?></th>
                                                            <th class="text-center" width="4%"><i class="fa fa-plus-circle fa-lg" title="<?= $this->lang->line('Tambah'); ?>" id="addrow"></i></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody></tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <th colspan="6" class="text-right"><?= $this->lang->line("Sub Total"); ?> Rp. </th>
                                                            <th><input type="text" class="form-control form-control-sm text-right" id="tfoot_subtotal" name="tfoot_subtotal" value="0" readonly></th>
                                                            <th colspan="2"></th>
                                                        </tr>

                                                        <tr>
                                                            <th colspan="6" class="text-right"><?= $this->lang->line("Diskon"); ?></th>
                                                            <th><input type="text" readonly autocomplete="off" class="formatrupiah form-control form-control-sm text-right" value="0" onkeyup="formatrupiahkeyup(this);change_ndisc();hitung();" onkeydown="formatrupiahkeydown(this);change_ndisc();hitung();" onblur="if(this.value==''){this.value='0';hitung();}" onfocus="if(this.value=='0'){this.value='';}" id="tfoot_n_diskon" name="tfoot_n_diskon"></th>
                                                            <th colspan="2"></th>
                                                        </tr>

                                                        <tr>
                                                            <th colspan="6" class="text-right"><?= $this->lang->line("Diskon Rp"); ?>. </th>
                                                            <th><input type="text" readonly autocomplete="off" class="formatrupiah form-control form-control-sm text-right" value="0" onkeyup="formatrupiahkeyup(this);change_vdisc();hitung();" onkeydown="formatrupiahkeydown(this);change_vdisc();hitung();" onblur="if(this.value==''){this.value='0';hitung();}" onfocus="if(this.value=='0'){this.value='';}" id="tfoot_v_diskon" name="tfoot_v_diskon"></th>
                                                            <th colspan="2"></th>
                                                        </tr>
                                                        <?php if ($this->session->f_company_plusppn == 't') { ?>
                                                            <tr>
                                                                <th colspan="6" class="text-right"><?= $this->lang->line("DPP"); ?> Rp. </th>
                                                                <th><input type="text" class="form-control form-control-sm text-right" id="tfoot_v_dpp" name="tfoot_v_dpp" readonly></th>
                                                                <th colspan="2"></th>
                                                            </tr>

                                                            <tr>
                                                                <th colspan="6" class="text-right"><?= $this->lang->line("PPN"); ?> (<?= $this->session->n_ppn; ?>%) Rp.</th>
                                                                <th><input type="text" class="form-control form-control-sm text-right" id="tfoot_v_ppn" name="tfoot_v_ppn" readonly></th>
                                                                <th colspan="2"></th>
                                                            </tr>
                                                        <?php } ?>

                                                        <tr>
                                                            <th colspan="6" class="text-right"><?= $this->lang->line("Nilai Bersih"); ?> Rp. </th>
                                                            <th><input type="text" class="form-control form-control-sm text-right font-weight-bold" id="tfoot_total" name="tfoot_total" readonly></th>
                                                            <th colspan="2"></th>
                                                        </tr>
                                                    </tfoot>
                                                    <input type="hidden" id="jml" name="jml" value="0">
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