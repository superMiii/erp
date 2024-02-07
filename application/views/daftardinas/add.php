<style>
    .table.table-xs th,
    .table td,
    .table.table-xs td {
        padding: 0.4rem 0.4rem;
    }
</style>
<form class="form-validation" novalidate>
    <div class="content-header row">
        <div class="content-header-left col-md-12 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#" onclick="return false;"><?= $this->lang->line('Dinas'); ?></a></li>
                        <li class="breadcrumb-item"><a href="<?= base_url($this->folder . '/index/' . encrypt_url($dfrom) . '/' . encrypt_url($dto) . '/' . encrypt_url($hstore)); ?>"><?= $this->lang->line($this->title); ?></a></li>
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
                            <input type="hidden" id="d_from" value="<?= encrypt_url($dfrom); ?>">
                            <input type="hidden" id="d_to" value="<?= encrypt_url($dto); ?>">
                            <input type="hidden" id="hstore" value="<?= encrypt_url($hstore); ?>">
                            <input type="hidden" id="i_user" name="i_user" value="<?= $this->session->i_user; ?>">
                            <input type="hidden" id="e_user_name" name="e_user_name" value="<?= $this->session->e_user_name; ?>">
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
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Nomor Dokumen"); ?> :</label>
                                            <fieldset>
                                                <div class="input-group controls">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">
                                                            <span class="fa fa-hashtag"></span>
                                                        </div>
                                                    </div>
                                                    <input type="text" name="i_document" id="i_document" readonly placeholder="DNS-<?= date('ym'); ?>-000001" class="form-control text-uppercase" data-validation-required-message="<?= $this->lang->line("Required"); ?>" maxlength="500" autocomplete="off" required aria-label="Text input with checkbox">
                                                </div>
                                            </fieldset>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Tanggal Dokumen"); ?> :</label>
                                            <div class="input-group controls">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">
                                                        <span class="fa fa-calendar-o"></span>
                                                    </span>
                                                </div>
                                                <input type="date" <?= konci(); ?> min="<?= get_min_date(); ?>" max="<?= date('Y-m-d'); ?>" class="form-control" value="<?= date('Y-m-d'); ?>" data-validation-required-message="<?= $this->lang->line("Required"); ?>" placeholder="" id="d_document" name="d_document" maxlength="500" autocomplete="off" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Divisi</label>
                                            <div class="controls">
                                                <fieldset>
                                                    <div class="float-left">
                                                        <input type="checkbox" id="f_divisi" name="f_divisi" class="switch" data-onstyle="success" data-group-cls="btn-group-sm" data-on-label="<?= $this->lang->line('Cabang'); ?>" data-off-label="<?= $this->lang->line('Pusat'); ?>" data-switch-always />
                                                    </div>
                                                </fieldset>
                                            </div>
                                        </div>
                                    </div>                                
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label><?= $this->lang->line('Asal Keberangkatan'); ?> : <span class="danger">*</span></label>
                                            <div class="controls">
                                                <select class="form-control" id="i_store7" name="i_store7" data-placeholder="<?= $this->lang->line("Pilih"); ?> Asal Keberangkatan" >
                                                    <option value=""></option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">      
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><?= $this->lang->line('Nama Karyawan'); ?> : <span class="danger">*</span></label>
                                            <div class="controls">
                                                <input autofocus type="text" class="form-control round text-capitalize"  placeholder="<?= $this->lang->line('Masukan'); ?> <?= $this->lang->line('Nama Karyawan'); ?>" id="e_staff_name" name="e_staff_name" maxlength="179" value="">
                                            </div>
                                        </div>
                                    </div> 
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label><?= $this->lang->line('Tanggal Berangkat'); ?> : <span class="danger">*</span></label>
                                            <div class="input-group controls">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">
                                                        <span class="fa fa-calendar-o"></span>
                                                    </span>
                                                </div>
                                                <input id="d_berangkat" name="d_berangkat" type='date' min="<?= date('Y-m-d'); ?>" class="form-control" value="<?= date('Y-m-d'); ?>" data-validation-required-message="<?= $this->lang->line("Required"); ?>" placeholder="<?= $this->lang->line('Tanggal Berangkat'); ?>" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label><?= $this->lang->line('Lama Perjalanan Dinas'); ?> : <span class="danger">*</span></label>
                                            <div class="controls">
                                                <input type="number" onkeyup="raya()" class="form-control form-control-sm text-capitalize" data-validation-number-message="<?= $this->lang->line('Number'); ?>" placeholder="<?= $this->lang->line('Hari'); ?>" id="n_lama_dinas" name="n_lama_dinas" min=0 autocomplete="off" autofocus>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label><?= $this->lang->line('Tanggal Kembali'); ?> : <span class="danger">*</span></label>
                                            <div class="input-group controls">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">
                                                        <span class="fa fa-calendar-o"></span>
                                                    </span>
                                                </div>
                                                <input id="d_kembali" readonly name="d_kembali" type='date' min="<?= date('Y-m-d'); ?>" class="form-control" value="<?= date('Y-m-d'); ?>" data-validation-required-message="<?= $this->lang->line("Required"); ?>" placeholder="<?= $this->lang->line('Tanggal Kembali'); ?>" />
                                            </div>
                                        </div>
                                    </div> 
                                </div>
                                <div class="row">                              
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Nama Atasan"); ?> : <span class="danger">*</span></label>
                                            <div class="controls">
                                                <select class="form-control" id="i_dn_atasan" name="i_dn_atasan" data-placeholder="<?= $this->lang->line("Pilih"); ?> <?= $this->lang->line("Nama Atasan"); ?>" >
                                                    <option value=""></option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>                                 
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Departemen"); ?> : <span class="danger">*</span></label>
                                            <div class="controls">
                                                <select class="form-control" id="i_dn_departement" name="i_dn_departement" data-placeholder="<?= $this->lang->line("Pilih"); ?> <?= $this->lang->line("Departemen"); ?>" >
                                                    <option value=""></option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>                                 
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Jabatan"); ?> : <span class="danger">*</span></label>
                                            <div class="controls">
                                                <select class="form-control" id="i_dn_jabatan" name="i_dn_jabatan" data-placeholder="<?= $this->lang->line("Pilih"); ?> <?= $this->lang->line("Jabatan"); ?>" >
                                                    <option value=""></option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">   
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Provinsi Tujuan : <span class="danger">*</span></label>
                                            <div class="controls">
                                                <input autofocus type="text" class="form-control round text-capitalize"  placeholder="<?= $this->lang->line('Masukan'); ?> <?= $this->lang->line('Area Provinsi'); ?>" id="e_area" name="e_area" maxlength="197" value="">
                                            </div>
                                        </div>
                                    </div> 
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Kota Tujuan : <span class="danger">*</span></label>
                                            <div class="controls">
                                                <input autofocus type="text" class="form-control round text-capitalize" placeholder="<?= $this->lang->line('Masukan'); ?> <?= $this->lang->line('City'); ?>" id="e_kota" name="e_kota" maxlength="497" value="">
                                            </div>
                                        </div>
                                    </div>    
                                </div>

                                <div class="row"> 
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="font-medium-3" ><?= $this->lang->line('Anggaran Biaya'); ?> : <span class="danger">*</span></label>
                                            <div class="controls">
                                                <input type="text" class="form-control round text-capitalize formatrupiah" placeholder="<?= $this->lang->line('Masukan'); ?> <?= $this->lang->line('Anggaran Biaya'); ?>" id="v_anggaran_biaya" name="v_anggaran_biaya" autocomplete="off" maxlength="12" autofocus>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row"> 
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label><?= $this->lang->line('Tiket1'); ?> :</label>
                                            <div class="controls">
                                                <input type="text" class="form-control round text-capitalize formatrupiah"  placeholder="<?= $this->lang->line('Masukan'); ?> <?= $this->lang->line('Tiket1'); ?>" id="v_tiket1" name="v_tiket1" maxlength="12">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label><?= $this->lang->line('Tiket2'); ?> :</label>
                                            <div class="controls">
                                                <input type="text" class="form-control round text-capitalize formatrupiah"  placeholder="<?= $this->lang->line('Masukan'); ?> <?= $this->lang->line('Tiket2'); ?>" id="v_tiket2" name="v_tiket2" maxlength="12">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row"> 
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label><?= $this->lang->line('Tol'); ?> :</label>
                                            <div class="controls">
                                                <input type="text" class="form-control round text-capitalize formatrupiah"  placeholder="<?= $this->lang->line('Masukan'); ?> <?= $this->lang->line('Tol'); ?>" id="v_tol" name="v_tol" autocomplete="off" maxlength="12">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label><?= $this->lang->line('BBM'); ?> :</label>
                                            <div class="controls">
                                                <input type="text" class="form-control round text-capitalize formatrupiah"  placeholder="<?= $this->lang->line('Masukan'); ?> <?= $this->lang->line('BBM'); ?>" id="v_bbm" name="v_bbm" autocomplete="off" maxlength="12">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label><?= $this->lang->line('Parkir'); ?> :</label>
                                            <div class="controls">
                                                <input type="text" class="form-control round text-capitalize formatrupiah"  placeholder="<?= $this->lang->line('Masukan'); ?> <?= $this->lang->line('Parkir'); ?>" id="v_parkir" name="v_parkir" autocomplete="off" maxlength="12">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row"> 
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="font-medium-3" ><?= $this->lang->line('Penginapan'); ?> :</label>
                                            <div class="controls">
                                                <input type="text" class="form-control round text-capitalize formatrupiah"  placeholder="<?= $this->lang->line('Masukan'); ?> <?= $this->lang->line('Penginapan'); ?>" id="v_penginapan" name="v_penginapan" autocomplete="off" maxlength="12">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="font-medium-3" ><?= $this->lang->line('Laundry'); ?> :</label>
                                            <div class="controls">
                                                <input type="text" class="form-control round text-capitalize formatrupiah"  placeholder="<?= $this->lang->line('Masukan'); ?> <?= $this->lang->line('Laundry'); ?>" id="v_laundry" name="v_laundry" autocomplete="off" maxlength="12">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="font-medium-3" ><?= $this->lang->line('Uang Makan'); ?> :</label>
                                            <div class="controls">
                                                <input type="text" class="form-control round text-capitalize formatrupiah"  placeholder="<?= $this->lang->line('Masukan'); ?> <?= $this->lang->line('Uang Makan'); ?>" id="v_uangmakan" name="v_uangmakan" autocomplete="off" maxlength="12">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="font-medium-3" ><?= $this->lang->line('Biaya Lain-lain'); ?> :</label>
                                            <div class="controls">
                                                <input type="text" class="form-control round text-capitalize formatrupiah"  placeholder="<?= $this->lang->line('Masukan'); ?> <?= $this->lang->line('Biaya Lain-lain'); ?>" id="v_lainlain" name="v_lainlain" autocomplete="off" maxlength="12">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row"> 
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="font-medium-3" ><?= $this->lang->line('Target SPB'); ?> : <span class="danger">*</span></label>
                                            <div class="controls">
                                                <input type="text" class="form-control round text-capitalize formatrupiah"  placeholder="<?= $this->lang->line('Masukan'); ?> <?= $this->lang->line('Target SPB'); ?>" id="v_spb_target" name="v_spb_target" autocomplete="off" autofocus maxlength="12">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="font-medium-3" ><?= $this->lang->line('Realisasi Biaya'); ?> :</label>
                                            <div class="controls">
                                                <input type="text" readonly value="0" class="form-control round text-capitalize formatrupiah"  placeholder="<?= $this->lang->line('Masukan'); ?> Realisasi Biaya " id="v_realisasi_biaya" name="v_realisasi_biaya" maxlength="12" autocomplete="off">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="font-medium-3" ><?= $this->lang->line('Realisasi SPB'); ?> :</label>
                                            <div class="controls">
                                                <input type="text" readonly value="0" class="form-control round text-capitalize formatrupiah"  placeholder="<?= $this->lang->line('Masukan'); ?> <?= $this->lang->line('Realisasi SPB'); ?>" id="v_spb_realisasi" name="v_spb_realisasi" maxlength="12" autocomplete="off">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="font-medium-3" ><?= $this->lang->line('Nota Yang Tertagih'); ?> :</label>
                                            <div class="controls">
                                                <input type="text" readonly value="0" class="form-control round text-capitalize formatrupiah"  placeholder="<?= $this->lang->line('Masukan'); ?> <?= $this->lang->line('Nota Yang Tertagih'); ?>" id="v_nota_tertagih" name="v_nota_tertagih" maxlength="12" autocomplete="off">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <div class="form-group">
                                            <label class="font-medium-3" >Persentase</label>
                                            <div class="controls">
                                                <input type="text" readonly value="0" onkeyup="vespa()" class="form-control round text-capitalize" placeholder="%" id="n_biaya_vs_spb" name="n_biaya_vs_spb" >
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">          
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Keterangan :"); ?> </label>
                                            <div class="controls">
                                                <textarea type="text" name="e_remark" class="form-control" placeholder="<?= $this->lang->line("Keterangan :"); ?>">Jadwal Kunjungan sudah sesuai dengan RKB .</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-actions">
                                    <button type="submit" id="submit" class="btn btn-success round btn-min-width mr-1"><i class="icon-paper-plane mr-1"></i><?= $this->lang->line("Simpan"); ?></button>
                                    <a href="<?= base_url($this->folder . '/index/' . encrypt_url($dfrom) . '/' . encrypt_url($dto) . '/' . encrypt_url($hstore)); ?>" class="btn btn-secondary round btn-min-width mr-1"><i class="icon-action-undo mr-1"></i><?= $this->lang->line("Kembali"); ?></a>
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