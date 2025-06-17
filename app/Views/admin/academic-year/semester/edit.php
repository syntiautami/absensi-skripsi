<?= $this->extend('layouts/base') ?>

<?= $this->section('header') ?>
    <?= view('components/header', ['role' => 'Admin']) ?>
<?= $this->endSection() ?>

<?= $this->section('breadcrumb') ?>
    <nav aria-label="breadcrumb" style="margin-top:1rem;">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="<?= base_url('admin/') ?>">Home</a>
            </li>
            <li class="breadcrumb-item">
                <a href="<?= base_url('admin/academic-year/') ?>">Tahun Pelajaran</a>
            </li>
            <li class="breadcrumb-item">
                <a href="<?= base_url('admin/academic-year/'.$academic_year['id']) ?>">Tahun Pelajaran</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">Ubah Semester</li>
        </ol>
    </nav>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <section class="content">
        <div class="card">
            <form action="<?= base_url('admin/academic-year/'.$academic_year['id'].'/semester/edit/') ?>" method="post">
                <?= csrf_field() ?>
                <div class="card-body">
                    <div class="row">
                        <?php foreach ($semesters as $key => $semester): ?>
                            <input type="hidden" name="semesters[<?= $key ?>][id]" value="<?= esc($semester['id']) ?>">
                            <div class="col-md-6">
                                <label class="col-form-label">Semester <?= $key + 1 ?></label>
                                <div class="form-group row">
                                    <div class="col-sm-8">
                                        <label for="semesters[<?= $key ?>][name]" class="col-form-label">Name</label>
                                        <input type="text" class="form-control" id="semesters[<?= $key ?>][name]" name="semesters[<?= $key ?>][name]" value="<?= esc($semester['name']) ?>" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label for="[<?= $key ?>-start_date" class="col-form-label">Tanggal Mulai</label>
                                        <div class="input-group date" id="<?= $key ?>-start_date" data-target-input="nearest">
                                            <input type="text" name="semesters[<?= $key ?>][start_date]" class="form-control datetimepicker-input"
                                                data-target="#<?= $key ?>-start_date" value="<?= date('d-m-Y', strtotime($semester['start_date'])) ?>"/>
                                            <div class="input-group-append" data-target="#<?= $key ?>-start_date" data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <label for="[<?= $key ?>-end_date" class="col-form-label">Tanggal Akhir</label>
                                        <div class="input-group date" id="<?= $key ?>-end_date" data-target-input="nearest">
                                            <input type="text" name="semesters[<?= $key ?>][end_date]" class="form-control datetimepicker-input"
                                                data-target="#<?= $key ?>-end_date" value="<?= date('d-m-Y', strtotime($semester['end_date'])) ?>"/>
                                            <div class="input-group-append" data-target="#<?= $key ?>-end_date" data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label class="col-form-label">Sedang Berjalan</label>
                                        <input type="checkbox" class="form-control in-session-checkbox" id="in_session" name="semesters[<?= $key ?>][in_session]"
                                        <?= $semester['in_session'] ? 'checked' : '' ?> style="width:auto">
                                    </div>
                                </div>
                            </div>
                        <?php endforeach ?>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="<?= base_url('admin/academic-year/'.$academic_year['id']) ?>" class="btn btn-secondary">Kembali</a>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </section>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script type="text/javascript">
    $(function () {
        $('#1-start_date, #0-start_date, #1-end_date, #0-end_date').datetimepicker({
            format: 'DD-MM-YYYY'
        });
        $('.in-session-checkbox').on('change', function() {
            if ($(this).is(':checked')) {
                $('.in-session-checkbox').not(this).prop('checked', false);
            }
        });
    });
</script>


<?= $this->endSection() ?>