<div class="content-header row">
    <div class="content-header-left col-md-12 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#" onclick="return false;"><?= $this->lang->line('Master'); ?></a></li>
                    <li class="breadcrumb-item"><a href="#" onclick="return false;"><?= $this->lang->line('Pelanggan'); ?></a></li>
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
                            <form class="form-validation" novalidate>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Nama Area Provinsi"); ?> :</label>
                                            <input type="hidden" id="textcari_area" value="<?= $this->lang->line('Pilih'); ?> <?= $this->lang->line('Nama Area Provinsi'); ?>">
                                            <div class="controls">
                                                <select class="form-control round" id="iarea" required data-validation-required-message="<?= $this->lang->line("Required"); ?>" name="iarea">
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
                                                <select class="form-control round" id="icity" required data-validation-required-message="<?= $this->lang->line("Required"); ?>" name="icity">
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
                                                <select class="form-control round" id="icover" required data-validation-required-message="<?= $this->lang->line("Required"); ?>" name="icover">
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
                                                <input type="text" class="form-control round text-capitalize" data-validation-required-message="<?= $this->lang->line('Required'); ?>" placeholder="<?= $this->lang->line('Masukan'); ?> <?= $this->lang->line('Kode Pelanggan'); ?>" id="kode" name="kode" maxlength="30" autocomplete="off" required autofocus value="<?= $data->i_customer_id; ?>">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <div class="form-group">
                                                <label><?= $this->lang->line('Nama Pelanggan'); ?> :</label>
                                                <div class="controls">
                                                    <input type="text" class="form-control round text-capitalize" data-validation-required-message="<?= $this->lang->line('Required'); ?>" placeholder="<?= $this->lang->line('Masukan'); ?> <?= $this->lang->line('Nama Pelanggan'); ?>" id="nama" name="nama" maxlength="100" autocomplete="off" required autofocus value="<?= $data->e_customer_name; ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><?= $this->lang->line('Alamat Pelanggan'); ?> :</label>
                                            <div class="controls">
                                                <textarea class="form-control round text-capitalize" data-validation-required-message="<?= $this->lang->line('Required'); ?>" placeholder="<?= $this->lang->line('Masukan'); ?> <?= $this->lang->line('Alamat Pelanggan'); ?>" id="alamat" name="alamat" autocomplete="off" required autofocus><?= $data->e_customer_address; ?></textarea>
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
                                                <input type="text" class="form-control round text-capitalize" placeholder="<?= $this->lang->line('Masukan'); ?> <?= $this->lang->line('Kompetitor'); ?>" id="e_competitor" name="e_competitor" maxlength="70" autocomplete="off" value="<?= $data->e_competitor; ?>" autofocus>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <div class="form-group">
                                                <label><?= $this->lang->line('Luas Toko m2'); ?> :</label>
                                                <div class="controls">
                                                    <input type="text" class="form-control round text-capitalize" placeholder="<?= $this->lang->line('Masukan'); ?> <?= $this->lang->line('Luas Toko m2'); ?>" id="n_building_m2" name="n_building_m2" value="<?= $data->n_building_m2; ?>" maxlength="100" autocomplete="off" autofocus>
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
                                                <textarea class="form-control round text-capitalize" placeholder="<?= $this->lang->line('Masukan'); ?> <?= $this->lang->line('Alamat Kirim'); ?>" id="e_shipment_address" name="e_shipment_address" autocomplete="off" autofocus><?= $data->e_shipment_address; ?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- tanggal -> ppn -->
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label><?= $this->lang->line('Tanggal Daftar'); ?> :</label>
                                            <div class="input-group">
                                                <input id="tanggal" name="tanggal" max="<?= date('Y-m-d'); ?>" type='text' class="form-control pickadate-selectors" placeholder="<?= $this->lang->line('Tanggal Daftar'); ?>" data-validation-required-message="<?= $this->lang->line('Required'); ?>" required value="<?= $data->d_customer_register_edit; ?>" />
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
                                            <label><?= $this->lang->line('Tanggal Mulai Usaha'); ?> :</label>
                                            <div class="input-group">
                                                <input id="d_start" name="d_start" max="<?= date('Y-m-d'); ?>" type='text' class="form-control pickadate-selectors" placeholder="<?= $this->lang->line('Tanggal Mulai Usaha'); ?>" value="<?= $data->d_start_edit; ?>" />
                                                <div class="input-group-append">
                                                    <span class="input-group-text">
                                                        <span class="fa fa-calendar-o"></span>
                                                    </span>
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label><?= $this->lang->line('TOP'); ?> :</label>
                                            <div class="controls">
                                                <input type="number" class="form-control round text-capitalize" data-validation-number-message="<?= $this->lang->line('Number'); ?>" data-validation-required-message="<?= $this->lang->line('Required'); ?>" placeholder="<?= $this->lang->line('Masukan'); ?> <?= $this->lang->line('TOP'); ?>" id="top" name="top" min=0 autocomplete="off" required autofocus value="<?= $data->n_customer_top; ?>">
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
                                                <input type="text" class="form-control round text-capitalize" data-validation-required-message="<?= $this->lang->line('Required'); ?>" placeholder="<?= $this->lang->line('Masukan'); ?> <?= $this->lang->line('Nama Pemilik'); ?>" id="pemilik" name="pemilik" maxlength="100" autocomplete="off" autofocus value="<?= $data->e_customer_owner; ?>">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <div class="form-group">
                                                <label><?= $this->lang->line('Nomor Telepon'); ?> :</label>
                                                <div class="controls">
                                                    <input type="text" class="form-control round text-capitalize" data-validation-required-message="<?= $this->lang->line('Required'); ?>" placeholder="<?= $this->lang->line('Masukan'); ?> <?= $this->lang->line('Nomor Telepon'); ?>" id="telepon" name="telepon" maxlength="100" autocomplete="off" autofocus value="<?= $data->e_customer_phone; ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label><?= $this->lang->line('Diskon1'); ?> :</label>
                                            <div class="controls">
                                                <input type="text" minlength="1" maxlength="6" class="form-control round text-capitalize" placeholder="<?= $this->lang->line('Masukan'); ?> <?= $this->lang->line('Diskon1'); ?>" id="diskon" name="diskon" value="<?= $data->disc1; ?>" autocomplete="off" autofocus>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label><?= $this->lang->line('Diskon2'); ?> :</label>
                                            <div class="controls">
                                                <input type="text" minlength="1" maxlength="6" class="form-control round text-capitalize" placeholder="<?= $this->lang->line('Masukan'); ?> <?= $this->lang->line('Diskon2'); ?>" id="diskon2" name="diskon2" value="<?= $data->disc2; ?>" autocomplete="off" autofocus>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label><?= $this->lang->line('Diskon3'); ?> :</label>
                                            <div class="controls">
                                                <input type="text" minlength="1" maxlength="6" class="form-control round text-capitalize" placeholder="<?= $this->lang->line('Masukan'); ?> <?= $this->lang->line('Diskon3'); ?>" id="diskon3" name="diskon3" value="<?= $data->disc3; ?>" autocomplete="off" autofocus>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- ktp dan pic -->
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label><?= $this->lang->line('Nomor KTP Pemilik'); ?> :</label>
                                            <div class="controls">
                                                <input type="number" required class="form-control round text-capitalize" data-validation-required-message="<?= $this->lang->line('Required'); ?>" placeholder="<?= $this->lang->line('Masukan'); ?> <?= $this->lang->line('Nomor KTP Pemilik'); ?>" value="<?= $data->e_ktp_owner; ?>" id="e_ktp_owner" name="e_ktp_owner" maxlength="50" autocomplete="off" autofocus>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <div class="form-group">
                                                <label><?= $this->lang->line('Nama PIC'); ?> :</label>
                                                <div class="controls">
                                                    <input type="text" class="form-control round text-capitalize" placeholder="<?= $this->lang->line('Masukan'); ?> <?= $this->lang->line('Nama PIC'); ?>" value="<?= $data->e_pic_name; ?>" id="e_pic_name" name="e_pic_name" maxlength="500" autocomplete="off" autofocus>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <div class="form-group">
                                                <label><?= $this->lang->line('Nomor Telepon PIC'); ?> :</label>
                                                <div class="controls">
                                                    <input type="text" class="form-control round text-capitalize" placeholder="<?= $this->lang->line('Masukan'); ?> <?= $this->lang->line('Nomor Telepon PIC'); ?>" value="<?= $data->e_pic_phone; ?>" id="e_pic_phone" name="e_pic_phone" maxlength="120" autocomplete="off" autofocus>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <div class="controls">
                                                <label>Toko :</label>
                                                <fieldset>
                                                    <div class="float-left">
                                                        <input type="checkbox" <?php if ($data->f_pareto == 't') {
                                                                                    echo "checked";
                                                                                } ?> id="f_pareto" name="f_pareto" class="switch" data-group-cls="btn-group-sm" data-on-label="<?= $this->lang->line('Pareto'); ?>" data-off-label="<?= $this->lang->line('Nonpareto'); ?>" data-switch-always />
                                                    </div>
                                                </fieldset>
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
                                                <input type="text" class="form-control round text-capitalize" placeholder="<?= $this->lang->line('Masukan'); ?> <?= $this->lang->line('Kode NPWP'); ?>" id="kode_npwp" name="kode_npwp" maxlength="70" autocomplete="off" autofocus value="<?= $data->e_customer_npwpcode; ?>">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <div class="form-group">
                                                <label><?= $this->lang->line('Nama NPWP'); ?> :</label>
                                                <div class="controls">
                                                    <input type="text" class="form-control round text-capitalize" placeholder="<?= $this->lang->line('Masukan'); ?> <?= $this->lang->line('Nama NPWP'); ?>" id="nama_npwp" name="nama_npwp" maxlength="100" autocomplete="off" autofocus value="<?= $data->e_customer_npwpname; ?>">
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
                                                <textarea class="form-control round text-capitalize" placeholder="<?= $this->lang->line('Masukan'); ?> <?= $this->lang->line('Alamat NPWP'); ?>" id="alamat_npwp" name="alamat_npwp" autocomplete="off" autofocus> <?= $data->e_customer_npwpaddress; ?></textarea>
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
                                                <input type="text" class="form-control round text-capitalize" placeholder="<?= $this->lang->line('Masukan'); ?> <?= $this->lang->line('Ekspedisi Pelanggan'); ?>" id="e_ekspedisi_cus" name="e_ekspedisi_cus" maxlength="200" autocomplete="off" autofocus value="<?= $data->e_ekspedisi_cus ?>">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><?= $this->lang->line('Pembayaran Ekspedisi'); ?> :</label>
                                            <div class="controls">
                                                <select name="e_ekspedisi_bayar" id="e_ekspedisi_bayar" class="form-control select2">
                                                    <option value="<?= $data->e_ekspedisi_bayar ?>"><?= $data->e_ekspedisi_bayar ?></option>
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
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Nama Kelompok Pelanggan"); ?> :</label>
                                            <input type="hidden" id="textcari_group" value="<?= $this->lang->line('Pilih'); ?> <?= $this->lang->line('Nama Kelompok Pelanggan'); ?>">
                                            <div class="controls">
                                                <select class="form-control round" id="igroup" required data-validation-required-message="<?= $this->lang->line("Required"); ?>" name="igroup">
                                                    <option value="<?= $data->i_customer_group; ?>"><?= $data->i_customer_groupid . ' - ' . $data->e_customer_groupname; ?></option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Nama Tingkat Pelanggan"); ?> :</label>
                                            <input type="hidden" id="textcari_level" value="<?= $this->lang->line('Pilih'); ?> <?= $this->lang->line('Nama Tingkat Pelanggan'); ?>">
                                            <div class="controls">
                                                <select class="form-control round" id="ilevel" required data-validation-required-message="<?= $this->lang->line("Required"); ?>" name="ilevel">
                                                    <option value="<?= $data->i_customer_level; ?>"><?= $data->i_customer_levelid . ' - ' . $data->e_customer_levelname; ?></option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- <div class="col-md-4">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Nama Status Pelanggan"); ?> :</label>
                                            <input type="hidden" id="textcari_status" value="<?= $this->lang->line('Pilih'); ?> <?= $this->lang->line('Nama Status Pelanggan'); ?>">
                                            <div class="controls">
                                                <select class="form-control round" id="istatus" required data-validation-required-message="<?= $this->lang->line("Required"); ?>" name="istatus">
                                                    <option value="<?= $data->i_customer_status; ?>"><?= $data->i_customer_statusid . ' - ' . $data->e_customer_statusname; ?></option>
                                                </select>
                                            </div>
                                        </div>
                                    </div> -->

                                    <!-- <input type="hidden" id="istatus" value="<?= $data->i_customer_status; ?>"> -->

                                </div>


                                <!-- kodeharga -> status -->
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Nama Kelompok Harga"); ?> :</label>
                                            <input type="hidden" id="textcari_price" value="<?= $this->lang->line('Pilih'); ?> <?= $this->lang->line('Nama Kelompok Harga'); ?>">
                                            <div class="controls">
                                                <select class="form-control round" id="iprice" required data-validation-required-message="<?= $this->lang->line("Required"); ?>" name="iprice">
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
                                                <select class="form-control round" id="itype" required data-validation-required-message="<?= $this->lang->line("Required"); ?>" name="itype">
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
                                                <select class="form-control round" id="ipayment" required data-validation-required-message="<?= $this->lang->line("Required"); ?>" name="ipayment">
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
                                                <select class="form-control round" id="ipaygroup" required data-validation-required-message="<?= $this->lang->line("Required"); ?>" name="ipaygroup">
                                                    <option value="<?= $data->i_customer_paygroup; ?>"><?= $data->i_customer_paygroupid . ' - ' . $data->e_customer_paygroupname; ?></option>
                                                </select>
                                            </div>
                                        </div>
                                    </div> -->
                                </div>
                                <div class="d-flex justify-content-start align-items-center">
                                    <button type="submit" class="btn btn-warning round btn-min-width mr-1"><i class="icon-paper-plane mr-1"></i><?= $this->lang->line('Ubah'); ?></button>
                                    <a href="<?= base_url($this->folder); ?>" class="btn btn-secondary round btn-min-width mr-1"><i class="icon-action-undo mr-1"></i><?= $this->lang->line('Kembali'); ?></a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--/ Alternative pagination table -->
</div>