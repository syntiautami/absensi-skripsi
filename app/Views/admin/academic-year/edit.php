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
                <a href="<?= base_url('admin/academic-year/') ?>">Tahun Ajaran</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page"><?= esc($academic_year['name']) ?></li>
        </ol>
    </nav>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <section class="content">
        <div class="card">
            <form action="<?= base_url('admin/academic-year/'.$academic_year['id'].'/edit/') ?>" method="post">
                <?= csrf_field() ?>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-sm-8">
                            <label for="name" class="col-form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="<?= esc($academic_year['name']) ?>" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-4">
                            <label for="start_date" class="col-form-label">Tanggal Mulai</label>
                            <div class="input-group date" id="start_date_picker" data-target-input="nearest">
                                <input type="text" name="start_date" class="form-control datetimepicker-input"
                                    data-target="#start_date_picker"
                                    value="<?= date('d-m-Y', strtotime($academic_year['start_date'])) ?>" />
                                <div class="input-group-append" data-target="#start_date_picker" data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <label for="end_date" class="col-form-label">Tanggal Akhir</label>
                            <div class="input-group date" id="end_date_picker" data-target-input="nearest">
                                <input type="text" name="end_date" class="form-control datetimepicker-input"
                                    data-target="#end_date_picker"
                                    value="<?= date('d-m-Y', strtotime($academic_year['end_date'])) ?>" />
                                <div class="input-group-append" data-target="#end_date_picker" data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-4">
                            <label class="col-form-label">Sedang Berjalan</label>
                            <input type="checkbox" class="form-control" id="in_session" name="in_session"
                            <?= $academic_year['in_session'] ? 'checked' : '' ?> style="width:auto">
                        </div>
                        <div class="col-sm-4">
                            <label class="col-form-label">Semester yang sedang berjalan</label>
                            <select name="active_semester_id" class="form-control">
                                <?php $no = 1; foreach ($semesters as $item): ?>
                                    <option value="<?= $item['id'] ?>" <?= ($item['in_session']) ? 'selected' : '' ?>>Semester <?= esc($item['name']) ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="<?= base_url('admin/academic-year/') ?>" class="btn btn-secondary">Kembali</a>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </section>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script type="text/javascript">
    $(function () {
        $('#start_date_picker').datetimepicker({
            format: 'DD-MM-YYYY'
        });
        $('#end_date_picker').datetimepicker({
            format: 'DD-MM-YYYY'
        });

        // Ini buat pastiin begitu klik input langsung keluar
        $('#start_date_picker input').on('focus', function () {
            $('#start_date_picker').datetimepicker('show');
        });
        $('#end_date_picker input').on('focus', function () {
            $('#end_date_picker').datetimepicker('show');
        });
    });
</script>


<?= $this->endSection() ?>