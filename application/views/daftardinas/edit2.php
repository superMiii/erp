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
                            <h4 class="card-title"><i class="icon-plus"></i> <?= $this->lang->line('Ubah'); ?> <?= $this->lang->line($this->title); ?></h4>
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
                                                    <input type="hidden" name="id" id="id" class="form-control" value="<?= $data->i_dinas; ?>">
                                                    <input type="hidden" name="i_document_old" id="i_document_old" class="form-control" value="<?= $data->i_dinas_id; ?>">
                                                    <input type="text" name="i_document" id="i_document" readonly value="<?= $data->i_dinas_id; ?>" placeholder="DNS-<?= date('ym'); ?>-000001" class="form-control text-uppercase" data-validation-required-message="<?= $this->lang->line("Required"); ?>" maxlength="500" autocomplete="off" required aria-label="Text input with checkbox">
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
                                                <input type="date" <?= konci(); ?> min="<?= get_min_date(); ?>" max="<?= date('Y-m-d'); ?>" class="form-control" value="<?= $data->d_dinas; ?>" data-validation-required-message="<?= $this->lang->line("Required"); ?>" placeholder="" id="d_document" name="d_document" maxlength="500" autocomplete="off" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Divisi</label>
                                            <div class="controls">
                                                <fieldset>
                                                    <div class="float-left">
                                                        <input type="checkbox" readonly <?php if ($data->f_pusat == 't') {
                                                                                    echo "checked";
                                                                                } ?> id="f_divisi" name="f_divisi" class="switch" data-group-cls="btn-group-sm" data-on-label="<?= $this->lang->line('Cabang'); ?>" data-off-label="<?= $this->lang->line('Pusat'); ?>" data-switch-always />
                                                    </div>
                                                </fieldset>
                                            </div>
                                        </div>
                                    </div>                                
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label><?= $this->lang->line('Asal Keberangkatan'); ?> : <span class="danger">*</span></label>
                                            <div class="controls">
                                            <input type="text" readonly class="form-control round text-capitalize" value="<?= $data->e_store_loc_name; ?>" id="e_store_loc_name" name="e_store_loc_name"  autocomplete="off">
                                            <input type="hidden" readonly class="form-control round text-capitalize" value="<?= $data->i_store; ?>" id="i_store7" name="i_store7"  autocomplete="off">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row"> 
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><?= $this->lang->line('Nama Karyawan'); ?> : <span class="danger">*</span></label>
                                            <div class="controls">
                                                <input type="text" readonly class="form-control round text-capitalize" value="<?= $data->e_staff_name; ?>" placeholder="<?= $this->lang->line('Masukan'); ?> <?= $this->lang->line('Nama Karyawan'); ?>" id="e_staff_name" name="e_staff_name" maxlength="179" autocomplete="off" autofocus>
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
                                                <input readonly id="d_berangkat" name="d_berangkat" type='date' value="<?= $data->d_berangkat; ?>" min="<?= date('Y-m-d'); ?>" class="form-control" value="<?= date('Y-m-d'); ?>" data-validation-required-message="<?= $this->lang->line("Required"); ?>" placeholder="<?= $this->lang->line('Tanggal Berangkat'); ?>" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label><?= $this->lang->line('Lama Perjalanan Dinas'); ?> : <span class="danger">*</span></label>
                                            <div class="controls">
                                                <input readonly type="number" value="<?= $data->n_lama_dinas; ?>" onkeyup="raya()" class="form-control form-control-sm text-capitalize" data-validation-number-message="<?= $this->lang->line('Number'); ?>" placeholder="<?= $this->lang->line('Hari'); ?>" id="n_lama_dinas" name="n_lama_dinas" min=0 autocomplete="off" autofocus>
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
                                                <input readonly id="d_kembali" name="d_kembali" type='date' value="<?= $data->d_kembali; ?>" min="<?= date('Y-m-d'); ?>" class="form-control" value="<?= date('Y-m-d'); ?>" placeholder="<?= $this->lang->line('Tanggal Kembali'); ?>" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">                                    
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Nama Atasan"); ?> : <span class="danger">*</span></label>
                                            <div class="controls">
                                            <input type="text" readonly class="form-control round text-capitalize" value="<?= $data->e_dn_atasan_name; ?>" id="e_dn_atasan_name" name="e_dn_atasan_name"  autocomplete="off">
                                            <input type="hidden" readonly class="form-control round text-capitalize" value="<?= $data->i_dn_atasan; ?>" id="i_dn_atasan" name="i_dn_atasan"  autocomplete="off">
                                            </div>
                                        </div>
                                    </div>                                 
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Departemen"); ?> : <span class="danger">*</span></label>
                                            <div class="controls">
                                            <input type="text" readonly class="form-control round text-capitalize" value="<?= $data->e_dn_departement_name; ?>" id="e_dn_departement_name" name="e_dn_departement_name"  autocomplete="off">
                                            <input type="hidden" readonly class="form-control round text-capitalize" value="<?= $data->i_dn_departement; ?>" id="i_dn_departement" name="i_dn_departement"  autocomplete="off">
                                            </div>
                                        </div>
                                    </div>                                 
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Jabatan"); ?> : <span class="danger">*</span></label>
                                            <div class="controls">
                                            <input type="text" readonly class="form-control round text-capitalize" value="<?= $data->e_dn_jabatan_name; ?>" id="e_dn_jabatan_name" name="e_dn_jabatan_name"  autocomplete="off">
                                            <input type="hidden" readonly class="form-control round text-capitalize" value="<?= $data->i_dn_jabatan; ?>" id="i_dn_jabatan" name="i_dn_jabatan"  autocomplete="off">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row"> 
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Provinsi Tujuan : <span class="danger">*</span></label>
                                            <div class="controls">
                                            <input type="text" readonly class="form-control round text-capitalize" value="<?= $data->e_area; ?>" id="e_area" name="e_area"  autocomplete="off">
                                            </div>
                                        </div>
                                    </div> 
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Kota Tujuan : <span class="danger">*</span></label>
                                            <div class="controls">
                                            <input type="text" readonly class="form-control round text-capitalize" value="<?= $data->e_kota; ?>" id="e_kota" name="e_kota"  autocomplete="off">
                                            </div>
                                        </div>
                                    </div>   
                                </div>

                                <div class="row"> 
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="font-medium-3" ><?= $this->lang->line('Anggaran Biaya'); ?> : <span class="danger">*</span></label>
                                            <div class="controls">
                                                <input type="text" readonly value="<?= number_format($data->v_anggaran_biaya); ?>" class="form-control round text-capitalize formatrupiah" placeholder="<?= $this->lang->line('Masukan'); ?> <?= $this->lang->line('Anggaran Biaya'); ?>" id="v_anggaran_biaya" name="v_anggaran_biaya" autocomplete="off" maxlength="12" autofocus>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row"> 
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label><?= $this->lang->line('Tiket1'); ?> :</label>
                                            <div class="controls">
                                                <input type="text" readonly value="<?= number_format($data->v_tiket1); ?>" class="form-control round text-capitalize formatrupiah"  placeholder="<?= $this->lang->line('Masukan'); ?> <?= $this->lang->line('Tiket1'); ?>" id="v_tiket1" maxlength="12" name="v_tiket1">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label><?= $this->lang->line('Tiket2'); ?> :</label>
                                            <div class="controls">
                                                <input type="text" readonly value="<?= number_format($data->v_tiket2); ?>" class="form-control round text-capitalize formatrupiah"  placeholder="<?= $this->lang->line('Masukan'); ?> <?= $this->lang->line('Tiket2'); ?>" id="v_tiket2" maxlength="12" name="v_tiket2">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row"> 
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label><?= $this->lang->line('Tol'); ?> :</label>
                                            <div class="controls">
                                                <input type="text" readonly value="<?= number_format($data->v_tol); ?>" class="form-control round text-capitalize formatrupiah"  placeholder="<?= $this->lang->line('Masukan'); ?> <?= $this->lang->line('Tol'); ?>" id="v_tol" name="v_tol" maxlength="12" autocomplete="off">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label><?= $this->lang->line('BBM'); ?> :</label>
                                            <div class="controls">
                                                <input type="text" readonly value="<?= number_format($data->v_bbm); ?>" class="form-control round text-capitalize formatrupiah"  placeholder="<?= $this->lang->line('Masukan'); ?> <?= $this->lang->line('BBM'); ?>" id="v_bbm" name="v_bbm" maxlength="12" autocomplete="off">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label><?= $this->lang->line('Parkir'); ?> :</label>
                                            <div class="controls">
                                                <input type="text" readonly value="<?= number_format($data->v_parkir); ?>" class="form-control round text-capitalize formatrupiah"  placeholder="<?= $this->lang->line('Masukan'); ?> <?= $this->lang->line('Parkir'); ?>" id="v_parkir" name="v_parkir" maxlength="12" autocomplete="off">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row"> 
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="font-medium-3" ><?= $this->lang->line('Penginapan'); ?> :</label>
                                            <div class="controls">
                                                <input type="text" readonly value="<?= number_format($data->v_penginapan); ?>" class="form-control round text-capitalize formatrupiah"  placeholder="<?= $this->lang->line('Masukan'); ?> <?= $this->lang->line('Penginapan'); ?>" id="v_penginapan" name="v_penginapan"  maxlength="12"autocomplete="off">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="font-medium-3" ><?= $this->lang->line('Laundry'); ?> :</label>
                                            <div class="controls">
                                                <input type="text" readonly value="<?= number_format($data->v_laundry); ?>" class="form-control round text-capitalize formatrupiah"  placeholder="<?= $this->lang->line('Masukan'); ?> <?= $this->lang->line('Laundry'); ?>" id="v_laundry" name="v_laundry" maxlength="12" autocomplete="off">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="font-medium-3" ><?= $this->lang->line('Uang Makan'); ?> :</label>
                                            <div class="controls">
                                                <input type="text" readonly value="<?= number_format($data->v_uangmakan); ?>" class="form-control round text-capitalize formatrupiah"  placeholder="<?= $this->lang->line('Masukan'); ?> <?= $this->lang->line('Uang Makan'); ?>" id="v_uangmakan" name="v_uangmakan"  maxlength="12" autocomplete="off">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="font-medium-3" ><?= $this->lang->line('Biaya Lain-lain'); ?> :</label>
                                            <div class="controls">
                                                <input type="text" readonly value="<?= number_format($data->v_lainlain); ?>" class="form-control round text-capitalize formatrupiah"  placeholder="<?= $this->lang->line('Masukan'); ?> <?= $this->lang->line('Biaya Lain-lain'); ?>" id="v_lainlain" name="v_lainlain" maxlength="12" autocomplete="off">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row"> 
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="font-medium-3" ><?= $this->lang->line('Target SPB'); ?> : <span class="danger">*</span></label>
                                            <div class="controls">
                                                <input type="text" readonly value="<?= number_format($data->v_spb_target); ?>" class="form-control round text-capitalize formatrupiah"  placeholder="<?= $this->lang->line('Masukan'); ?> <?= $this->lang->line('Target SPB'); ?>" id="v_spb_target" name="v_spb_target" autocomplete="off" maxlength="12" autofocus>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="font-medium-3" ><?= $this->lang->line('Realisasi Biaya'); ?> :</label>
                                            <div class="controls">
                                                <input type="text" value="<?= number_format($data->v_realisasi_biaya); ?>" class="form-control round text-capitalize formatrupiah"  placeholder="<?= $this->lang->line('Masukan'); ?> Realisasi Biaya " id="v_realisasi_biaya" name="v_realisasi_biaya" maxlength="12" autocomplete="off" onkeyup="itung();" onkeydown="itung();" onblur="if(this.value==''){this.value='0';itung();}" onfocus="if(this.value=='0'){this.value='';}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="font-medium-3" ><?= $this->lang->line('Realisasi SPB'); ?> :</label>
                                            <div class="controls">
                                                <input type="text" value="<?= number_format($data->v_spb_realisasi); ?>" class="form-control round text-capitalize formatrupiah"  placeholder="<?= $this->lang->line('Masukan'); ?> <?= $this->lang->line('Realisasi SPB'); ?>" id="v_spb_realisasi" name="v_spb_realisasi" maxlength="12" autocomplete="off" onkeyup="itung();" onkeydown="itung();" onblur="if(this.value==''){this.value='0';itung();}" onfocus="if(this.value=='0'){this.value='';}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="font-medium-3" ><?= $this->lang->line('Nota Yang Tertagih'); ?> :</label>
                                            <div class="controls">
                                                <input type="text" value="<?= number_format($data->v_nota_tertagih); ?>" class="form-control round text-capitalize formatrupiah"  placeholder="<?= $this->lang->line('Masukan'); ?> <?= $this->lang->line('Nota Yang Tertagih'); ?>" id="v_nota_tertagih" name="v_nota_tertagih" maxlength="12" autocomplete="off" onkeyup="itung();" onkeydown="itung();" onblur="if(this.value==''){this.value='0';itung();}" onfocus="if(this.value=='0'){this.value='';}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <div class="form-group">
                                            <label class="font-medium-3" >Persentase</label>
                                            <div class="controls">
                                                <input type="text" readonly value="<?= $data->n_biaya_vs_spb; ?>" onkeyup="vespa()" class="form-control round text-capitalize" placeholder="%" id="n_biaya_vs_spb" name="n_biaya_vs_spb" >
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row"> 
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Status</label>
                                            <div class="controls">
                                                <fieldset>
                                                    <div class="float-left">
                                                        <input type="checkbox" <?php if ($data->f_selesai == 't') {
                                                                                    echo "checked";
                                                                                } ?> id="f_selesai" name="f_selesai" class="switch" data-group-cls="btn-group-sm" data-on-label="<?= $this->lang->line('SELESAI'); ?>" data-off-label="Proses" data-switch-always />
                                                    </div>
                                                </fieldset>
                                            </div>
                                        </div>
                                    </div> 
                                    <div class="col-sm-10">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Keterangan :"); ?> </label>
                                            <div class="controls">
                                            <input type="text" readonly class="form-control round text-capitalize" value="<?= $data->e_remark; ?>" id="e_remark" name="e_remark">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-actions">
                                    <button type="submit" class="btn btn-warning round btn-min-width mr-1"><i class="icon-paper-plane mr-1"></i><?= $this->lang->line('Ubah'); ?></button>
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