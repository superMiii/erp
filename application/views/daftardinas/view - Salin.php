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
                    <div class="card">
                        <div class="card-header header-elements-inline <?= $this->session->e_color; ?> bg-darken-1 text-white">
                            <h4 class="card-title"><i class="icon-plus"></i> <?= $this->lang->line('Tambah'); ?> <?= $this->lang->line($this->title); ?></h4>
                            <input type="hidden" id="path" value="<?= $this->folder; ?>">
                            <input type="hidden" id="d_from" value="<?= encrypt_url($dfrom); ?>">
                            <input type="hidden" id="d_to" value="<?= encrypt_url($dto); ?>">
                            <input type="hidden" id="harea" value="<?= encrypt_url($harea); ?>">
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
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label><?= $this->lang->line('Nama Karyawan'); ?> : <span class="danger">*</span></label>
                                            <div class="controls">
                                                <input type="text" readonly class="form-control round text-capitalize" value="<?= $data->e_staff_name; ?>" id="e_staff_name" name="e_staff_name" maxlength="179" autocomplete="off">
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
                                </div>
                                <div class="row">                                    
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Nama Atasan"); ?> : <span class="danger">*</span></label>
                                            <input type="text" readonly class="form-control round text-capitalize" value="<?= $data->e_dn_atasan_name; ?>" id="e_dn_atasan_name" name="e_dn_atasan_name" maxlength="179" autocomplete="off">                                            
                                        </div>
                                    </div>                                 
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Departemen"); ?> : <span class="danger">*</span></label>
                                            <input type="text" readonly class="form-control round text-capitalize" value="<?= $data->e_dn_departement_name; ?>" id="e_dn_departement_name" name="e_dn_departement_name" maxlength="179" autocomplete="off">
                                        </div>
                                    </div>                                 
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Jabatan"); ?> : <span class="danger">*</span></label>
                                            <input type="text" readonly class="form-control round text-capitalize" value="<?= $data->e_dn_jabatan_name; ?>" id="e_dn_jabatan_name" name="e_dn_jabatan_name" maxlength="179" autocomplete="off">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">                                    
                                    <div class="col-md-7">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Area"); ?> Tujuan : <span class="danger">*</span></label>
                                            <input type="text" readonly class="form-control round text-capitalize" value="<?= $data->i_area_id . ' - ' . $data->e_area_name; ?>" id="e_area_name" name="e_area_name" maxlength="179" autocomplete="off">
                                        </div>
                                    </div>                                    
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Kota"); ?> Tujuan : <span class="danger">*</span></label>
                                            <input type="text" readonly class="form-control round text-capitalize" value="<?= $data->e_city_name; ?>" id="e_city_name" name="e_city_name" maxlength="179" autocomplete="off">
                                        </div>
                                    </div>
                                </div>
                                <div class="row"> 
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label><?= $this->lang->line('Lama Perjalanan Dinas'); ?> : <span class="danger">*</span></label>
                                            <input type="text" readonly class="form-control round text-capitalize" value="<?= $data->n_lama_dinas; ?>" id="n_lama_dinas" name="n_lama_dinas" maxlength="179" autocomplete="off">
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
                                            <label><?= $this->lang->line('Tanggal Kembali'); ?> : <span class="danger">*</span></label>
                                            <div class="input-group controls">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">
                                                        <span class="fa fa-calendar-o"></span>
                                                    </span>
                                                </div>
                                                <input readonly id="d_kembali" name="d_kembali" type='date' value="<?= $data->d_kembali; ?>" min="<?= date('Y-m-d'); ?>" class="form-control" value="<?= date('Y-m-d'); ?>" data-validation-required-message="<?= $this->lang->line("Required"); ?>" placeholder="<?= $this->lang->line('Tanggal Kembali'); ?>" />
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label><?= $this->lang->line("Keterangan :"); ?> </label>
                                            <input type="text" readonly class="form-control round text-capitalize" value="<?= $data->e_staff_name; ?>" id="e_staff_name" name="e_staff_name">
                                        </div>
                                    </div>
                                </div>

                                <div class="row"> 
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="font-medium-3" ><?= $this->lang->line('Anggaran Biaya'); ?> : <span class="danger">*</span></label>
                                            <input type="text" readonly class="form-control round text-capitalize" value="<?= $data->v_anggaran_biaya; ?>" id="v_anggaran_biaya" name="v_anggaran_biaya">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row"> 
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label><?= $this->lang->line('Tiket1'); ?> :</label>
                                            <input type="text" readonly class="form-control round text-capitalize" value="<?= $data->v_tiket1; ?>" id="v_tiket1" name="v_tiket1">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label><?= $this->lang->line('Tiket2'); ?> :</label>
                                            <input type="text" readonly class="form-control round text-capitalize" value="<?= $data->v_tiket2; ?>" id="v_tiket2" name="v_tiket2">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row"> 
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label><?= $this->lang->line('Tol'); ?> :</label>
                                            <input type="text" readonly class="form-control round text-capitalize" value="<?= $data->v_tol; ?>" id="v_tol" name="v_tol">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label><?= $this->lang->line('BBM'); ?> :</label>
                                            <input type="text" readonly class="form-control round text-capitalize" value="<?= $data->v_bbm; ?>" id="v_bbm" name="v_bbm">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label><?= $this->lang->line('Parkir'); ?> :</label>
                                            <input type="text" readonly class="form-control round text-capitalize" value="<?= $data->v_parkir; ?>" id="v_parkir" name="v_parkir">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row"> 
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="font-medium-3" ><?= $this->lang->line('Penginapan'); ?> :</label>
                                            <input type="text" readonly class="form-control round text-capitalize" value="<?= $data->v_penginapan; ?>" id="v_penginapan" name="v_penginapan">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="font-medium-3" ><?= $this->lang->line('Laundry'); ?> :</label>
                                            <input type="text" readonly class="form-control round text-capitalize" value="<?= $data->v_laundry; ?>" id="v_laundry" name="v_laundry">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="font-medium-3" ><?= $this->lang->line('Uang Makan'); ?> :</label>
                                            <input type="text" readonly class="form-control round text-capitalize" value="<?= $data->v_uangmakan; ?>" id="v_uangmakan" name="v_uangmakan">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="font-medium-3" ><?= $this->lang->line('Biaya Lain-lain'); ?> :</label>
                                            <input type="text" readonly class="form-control round text-capitalize" value="<?= $data->v_lainlain; ?>" id="v_lainlain" name="v_lainlain">
                                        </div>
                                    </div>
                                </div>

                                <div class="row"> 
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="font-medium-3" ><?= $this->lang->line('Target SPB'); ?> : <span class="danger">*</span></label>
                                            <input type="text" readonly class="form-control round text-capitalize" value="<?= $data->v_spb_target; ?>" id="v_spb_target" name="v_spb_target">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="font-medium-3" ><?= $this->lang->line('Realisasi SPB'); ?> :</label>
                                            <input type="text" readonly class="form-control round text-capitalize" value="<?= $data->v_spb_realisasi; ?>" id="v_spb_realisasi" name="v_spb_realisasi">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="font-medium-3" ><?= $this->lang->line('% Biaya Vs SPB'); ?> :</label>
                                            <input type="text" readonly class="form-control round text-capitalize" value="<?= $data->n_biaya_vs_spb; ?>" id="n_biaya_vs_spb" name="n_biaya_vs_spb">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="font-medium-3" ><?= $this->lang->line('Nota Yang Tertagih'); ?> :</label>
                                            <input type="text" readonly class="form-control round text-capitalize" value="<?= $data->v_nota_tertagih; ?>" id="v_nota_tertagih" name="v_nota_tertagih">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-actions">
                                    <a href="<?= base_url($this->folder . '/index/' . encrypt_url($dfrom) . '/' . encrypt_url($dto) . '/' . encrypt_url($harea)); ?>" class="btn btn-secondary round btn-min-width mr-1"><i class="icon-action-undo mr-1"></i><?= $this->lang->line("Kembali"); ?></a>
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