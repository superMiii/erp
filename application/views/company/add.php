<div class="content-header row">
    <div class="content-header-left col-md-12 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#" onclick="return false;"><?= $this->lang->line('Setting'); ?></a></li>
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
                        <h4 class="card-title"><i class="icon-plus"></i> <?= $this->lang->line('Tambah'); ?> <?= $this->lang->line($this->title); ?></h4>
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
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label><?= $this->lang->line('Kode Perusahaan'); ?> :</label>
                                            <div class="controls">
                                                <input type="text" class="form-control round text-capitalize" data-validation-required-message="<?= $this->lang->line('Required'); ?>" placeholder="<?= $this->lang->line('Masukan'); ?> <?= $this->lang->line('Kode Perusahaan'); ?>" id="kode" name="kode" maxlength="30" autocomplete="off" required autofocus>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <div class="form-group">
                                                <label><?= $this->lang->line('Nama Pemilik'); ?> :</label>
                                                <div class="controls">
                                                    <input type="text" class="form-control round text-capitalize" placeholder="<?= $this->lang->line('Masukan'); ?> <?= $this->lang->line('Nama Pemilik'); ?>" id="pemilik" name="pemilik" maxlength="100" autocomplete="off" autofocus>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>&nbsp;</label>
                                            <div class="controls skin-square">
                                                <input type="checkbox" id="chk-ppn" name="chk-ppn">
                                                <label for="chk-ppn">&nbsp;&nbsp; <?= $this->lang->line('Tambah PPN'); ?></label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label><?= $this->lang->line('Jumlah PPN %'); ?> :</label>
                                            <div class="controls">
                                                <input type="number" pattern="([0-9]|[1-9][0-9]|100)" onkeypress="return bilanganasli(event);" minlength="1" maxlength="2" class="form-control round text-capitalize" id="nppn" name="nppn" maxlength="2" value="0" autocomplete="off" autofocus>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><?= $this->lang->line('Nama Perusahaan'); ?> :</label>
                                            <div class="controls">
                                                <input type="text" class="form-control round text-capitalize" data-validation-required-message="<?= $this->lang->line('Required'); ?>" placeholder="<?= $this->lang->line('Masukan'); ?> <?= $this->lang->line('Nama Perusahaan'); ?>" id="nama" name="nama" maxlength="500" autocomplete="off" autofocus>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>&nbsp;</label>
                                            <div class="controls skin-square">
                                                <input type="checkbox" id="chk-mtr" name="chk-mtr">
                                                <label for="chk-mtr">&nbsp;&nbsp; <?= $this->lang->line('Tambah Materai'); ?></label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label><?= $this->lang->line('Harga Materai'); ?> :</label>
                                            <div class="controls">
                                                <input type="number" class="form-control round text-capitalize" id="v_meterai" name="v_meterai" value="0" autocomplete="off" autofocus>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label><?= $this->lang->line('Pengenaan Materai'); ?> :</label>
                                            <div class="controls">
                                                <input type="number" class="form-control round text-capitalize" id="v_meterai_limit" name="v_meterai_limit" value="0" autocomplete="off" autofocus>
                                            </div>
                                        </div>
                                    </div>
                                </div>




                                <div class="row">
                                </div>

                                <!-- Kode -> alamat -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><?= $this->lang->line('Alamat Perusahaan'); ?> :</label>
                                            <div class="controls">
                                                <textarea class="form-control round text-capitalize" data-validation-required-message="<?= $this->lang->line('Required'); ?>" placeholder="<?= $this->lang->line('Masukan'); ?> <?= $this->lang->line('Alamat Perusahaan'); ?>" id="alamat" name="alamat" autocomplete="off" autofocus></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label><?= $this->lang->line('Nomor Telepon'); ?> :</label>
                                            <div class="controls">
                                                <input type="text" class="form-control round text-capitalize" placeholder="<?= $this->lang->line('Masukan'); ?> <?= $this->lang->line('Nomor Telepon'); ?>" id="telp" name="telp" maxlength="100" autocomplete="off" autofocus>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <div class="form-group">
                                                <label><?= $this->lang->line('Fax'); ?> :</label>
                                                <div class="controls">
                                                    <input type="text" class="form-control round text-capitalize" placeholder="<?= $this->lang->line('Masukan'); ?> <?= $this->lang->line('Fax'); ?>" id="fax" name="fax" maxlength="100" autocomplete="off" autofocus>
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
                                                <input type="text" class="form-control round text-capitalize" placeholder="<?= $this->lang->line('Masukan'); ?> <?= $this->lang->line('Kode NPWP'); ?>" id="kode_npwp" name="kode_npwp" maxlength="100" autocomplete="off" autofocus>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <div class="form-group">
                                                <label><?= $this->lang->line('Nama NPWP'); ?> :</label>
                                                <div class="controls">
                                                    <input type="text" class="form-control round text-capitalize" placeholder="<?= $this->lang->line('Masukan'); ?> <?= $this->lang->line('Nama NPWP'); ?>" id="nama_npwp" name="nama_npwp" maxlength="100" autocomplete="off" autofocus>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="controls skin-square">
                                                <input type="checkbox" id="chk-siam" name="chk-siam">
                                                <label for="chk-siam">&nbsp;&nbsp;<?= $this->lang->line('Alamat NPWP'); ?>&nbsp;<?= $this->lang->line('Sama Dengan Alamat Perusahaan'); ?></label>
                                            </div>
                                            <div class="controls">
                                                <textarea class="form-control round text-capitalize" placeholder="<?= $this->lang->line('Masukan'); ?> <?= $this->lang->line('Alamat NPWP'); ?>" id="alamat_npwp" name="alamat_npwp" autocomplete="off" autofocus></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- bank -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><?= $this->lang->line('Nomor Rekening'); ?> 1 :</label>
                                            <div class="controls">
                                                <input type="text" class="form-control round text-capitalize" placeholder="<?= $this->lang->line('Masukan'); ?> <?= $this->lang->line('Nomor Rekening'); ?>" id="rekening" name="rekening" maxlength="197" autocomplete="off" autofocus>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <div class="form-group">
                                                <label><?= $this->lang->line('Atas Nama'); ?> 1 :</label>
                                                <div class="controls">
                                                    <input type="text" class="form-control round text-capitalize" placeholder="<?= $this->lang->line('Masukan'); ?> <?= $this->lang->line('Atas Nama'); ?>" id="an" name="an" maxlength="197" autocomplete="off" autofocus>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <div class="form-group">
                                                <label><?= $this->lang->line('Nama Bank'); ?> 1 :</label>
                                                <div class="controls">
                                                    <input type="text" class="form-control round text-capitalize" placeholder="<?= $this->lang->line('Masukan'); ?> <?= $this->lang->line('Nama Bank'); ?>" id="bang" name="bang" maxlength="197" autocomplete="off" autofocus>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><?= $this->lang->line('Nomor Rekening'); ?> 2 :</label>
                                            <div class="controls">
                                                <input type="text" class="form-control round text-capitalize" placeholder="<?= $this->lang->line('Masukan'); ?> <?= $this->lang->line('Nomor Rekening'); ?>" id="rekening2" name="rekening2" maxlength="197" autocomplete="off" autofocus>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <div class="form-group">
                                                <label><?= $this->lang->line('Atas Nama'); ?> 2 :</label>
                                                <div class="controls">
                                                    <input type="text" class="form-control round text-capitalize" placeholder="<?= $this->lang->line('Masukan'); ?> <?= $this->lang->line('Atas Nama'); ?>" id="an2" name="an2" maxlength="197" autocomplete="off" autofocus>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <div class="form-group">
                                                <label><?= $this->lang->line('Nama Bank'); ?> 2 :</label>
                                                <div class="controls">
                                                    <input type="text" class="form-control round text-capitalize" placeholder="<?= $this->lang->line('Masukan'); ?> <?= $this->lang->line('Nama Bank'); ?>" id="bang2" name="bang2" maxlength="197" autocomplete="off" autofocus>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><?= $this->lang->line('Nomor Rekening'); ?> 3 :</label>
                                            <div class="controls">
                                                <input type="text" class="form-control round text-capitalize" placeholder="<?= $this->lang->line('Masukan'); ?> <?= $this->lang->line('Nomor Rekening'); ?>" id="rekening3" name="rekening3" maxlength="197" autocomplete="off" autofocus>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <div class="form-group">
                                                <label><?= $this->lang->line('Atas Nama'); ?> 3 :</label>
                                                <div class="controls">
                                                    <input type="text" class="form-control round text-capitalize" placeholder="<?= $this->lang->line('Masukan'); ?> <?= $this->lang->line('Atas Nama'); ?>" id="an3" name="an3" maxlength="197" autocomplete="off" autofocus>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <div class="form-group">
                                                <label><?= $this->lang->line('Nama Bank'); ?> 3 :</label>
                                                <div class="controls">
                                                    <input type="text" class="form-control round text-capitalize" placeholder="<?= $this->lang->line('Masukan'); ?> <?= $this->lang->line('Nama Bank'); ?>" id="bang3" name="bang3" maxlength="197" autocomplete="off" autofocus>
                                                </div>
                                            </div>
                                        </div>
                                    </div>





                                </div>

                                <div class="d-flex justify-content-start align-items-center">
                                    <button type="submit" class="btn btn-success round btn-min-width mr-1"><i class="icon-paper-plane mr-1"></i><?= $this->lang->line('Simpan'); ?></button>
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