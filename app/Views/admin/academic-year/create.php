<?= $this->extend('layouts/base') ?>

<?= $this->section('header') ?>
<?= $this->include('components/header') ?>
<?= $this->endSection() ?>

<?= $this->section('breadcrumb') ?>
<ol class="breadcrumb">
    <li class="breadcrumb-item">
        <a href="<?= base_url('admin/') ?>">Sistem Absensi</a>
    </li>
    <li class="breadcrumb-item">
        <a href="<?= base_url('admin/academic-year/') ?>">Tahun Ajaran</a>
    </li>
    <li class="breadcrumb-item active" aria-current="page">Buat Tahun Ajaran</li>
</ol>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="content">
    <div class="card">
        <form action="<?= base_url('admin/academic-year/create/') ?>" method="post">
            <?= csrf_field() ?>
            <div class="card-body">
                <div class="form-group row">
                    <div class="col-sm-8">
                        <label for="name" class="col-form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-4">
                        <label for="start_date" class="col-form-label">Tanggal Mulai</label>
                        <div class="input-group date" id="start_date_picker" data-target-input="nearest">
                            <input type="text" name="start_date" class="form-control datetimepicker-input"
                                data-target="#start_date_picker" />
                            <div class="input-group-append" data-target="#start_date_picker" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <label for="end_date" class="col-form-label">Tanggal Akhir</label>
                        <div class="input-group date" id="end_date_picker" data-target-input="nearest">
                            <input type="text" name="end_date" class="form-control datetimepicker-input"
                                data-target="#end_date_picker" />
                            <div class="input-group-append" data-target="#end_date_picker" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-4">
                        <label for="" class="col-form-label">Sedang Berjalan</label>
                        <input type="checkbox" class="form-control" id="in_session" name="in_session" style="width: auto;">
                    </div>
                    <div class="col-sm-4">
                        <label class="col-form-label">Semester yang sedang berjalan</label>
                        <select name="active_semester_id" class="form-control">
                            <option value="1">Semester Pertama</option>
                            <option value="2">Semester Kedua</option>
                        </select>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-6">
                        <label class="col-form-label">Semester Pertama</label>
                        <div class="form-group row">
                            <div class="col-sm-8">
                                <label for="first_semester-name" class="col-form-label">Name</label>
                                <input type="text" class="form-control" id="first_semester-name" name="first_semester-name" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-8">
                                <label for="first_semester-start_date" class="col-form-label">Tanggal Mulai</label>
                                <div class="input-group date" id="first_semester-start_date" data-target-input="nearest">
                                    <input type="text" name="first_semester-start_date" class="form-control datetimepicker-input"
                                        data-target="#first_semester-start_date" />
                                    <div class="input-group-append" data-target="#first_semester-start_date" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-8">
                                <label for="first_semester-end_date" class="col-form-label">Tanggal Akhir</label>
                                <div class="input-group date" id="first_semester-end_date" data-target-input="nearest">
                                    <input type="text" name="first_semester-end_date" class="form-control datetimepicker-input"
                                        data-target="#first_semester-end_date" />
                                    <div class="input-group-append" data-target="#first_semester-end_date" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="col-form-label">Semester Kedua</label>
                        <div class="form-group row">
                            <div class="col-sm-8">
                                <label for="second_semester-name" class="col-form-label">Name</label>
                                <input type="text" class="form-control" id="second_semester-name" name="second_semester-name" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-8">
                                <label for="second_semester-start_date" class="col-form-label">Tanggal Mulai</label>
                                <div class="input-group date" id="second_semester-start_date" data-target-input="nearest">
                                    <input type="text" name="second_semester-start_date" class="form-control datetimepicker-input"
                                        data-target="#second_semester-start_date" />
                                    <div class="input-group-append" data-target="#second_semester-start_date" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-8">
                                <label for="second_semester-end_date" class="col-form-label">Tanggal Akhir</label>
                                <div class="input-group date" id="second_semester-end_date" data-target-input="nearest">
                                    <input type="text" name="second_semester-end_date" class="form-control datetimepicker-input"
                                        data-target="#second_semester-end_date" />
                                    <div class="input-group-append" data-target="#second_semester-end_date" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="d-flex justify-content-end mt-3">
                    <a href="<?= base_url('admin/academic-year/') ?>" class="btn btn-secondary mr-2">Kembali</a>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</section>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script type="text/javascript">
    $(function() {
        $('#start_date_picker, #end_date_picker, #first_semester-start_date, #second_semester-start_date, #first_semester-end_date, #second_semester-end_date').datetimepicker({
            format: 'DD-MM-YYYY'
        });
    });
</script>


<?= $this->endSection() ?>