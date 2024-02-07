<div class="content-header row">
    <div class="content-header-left col-md-12 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#" onclick="return false;"><?= $this->lang->line('Master'); ?></a></li>
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

                                <div class="form-group">
                                    <label><?= $this->lang->line('Kode Segmen'); ?> :</label>
                                    <div class="controls">
                                        <input type="hidden" class="form-control" id="id" name="id" value="<?= $data->i_pajak; ?>">
                                        <input type="hidden" class="form-control" id="kodeold" name="kodeold" value="<?= $data->i_pajak_id; ?>">
                                        <input type="text" class="form-control round text-uppercase" data-validation-required-message="<?= $this->lang->line('Required'); ?>" placeholder="<?= $this->lang->line('Masukan'); ?> <?= $this->lang->line('Kode Segment'); ?>" id="kode" name="kode" maxlength="30" autocomplete="off" value="<?= $data->i_pajak_id; ?>" required autofocus>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label><?= $this->lang->line('Tahun'); ?> :</label>
                                    <div class="controls">
                                        <input type="text" class="form-control round text-capitalize" placeholder="<?= $this->lang->line('Masukan'); ?> <?= $this->lang->line('Tahun'); ?>" id="n_year" name="n_year" autocomplete="off" value="<?= $data->n_year; ?>" required>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label><?= $this->lang->line('Nomor Mulai'); ?> :</label>
                                    <div class="controls">
                                        <input type="text" class="form-control round text-capitalize" placeholder="<?= $this->lang->line('Masukan'); ?> <?= $this->lang->line('Nomor Mulai'); ?>" id="n_start" name="n_start" onkeypress="return bilanganasli(event)" autocomplete="off" value="<?= $data->n_start; ?>" required onblur="if(this.value==''){this.value='0';}" onfocus="if(this.value=='0'){this.value='';}">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label><?= $this->lang->line('Nomor Akhir'); ?> :</label>
                                    <div class="controls">
                                        <input type="text" class="form-control round text-capitalize" placeholder="<?= $this->lang->line('Masukan'); ?> <?= $this->lang->line('Nomor Akhir'); ?>" id="n_end" name="n_end" autocomplete="off" onkeypress="return bilanganasli(event)" value="<?= $data->n_end; ?>" required autofocus>
                                    </div>
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