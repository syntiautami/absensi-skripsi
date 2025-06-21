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
            <a href="<?= base_url('admin/classes/') ?>">Kelas</a>
        </li>
        <li class="breadcrumb-item">
            <a href="<?= base_url('admin/classes/academic-year/'.$academic_year['id'].'/') ?>"><?= esc($academic_year['name']) ?></a>
        </li>
        <li class="breadcrumb-item">
            <a href="<?= base_url('admin/classes/academic-year/'.$academic_year['id'].'/semester/'.$semester['id'].'/class/') ?>">Semester <?= esc($semester['name']) ?></a>
        </li>
        <li class="breadcrumb-item">
            <a href="<?= base_url('admin/classes/academic-year/'.$academic_year['id'].'/semester/'.$semester['id'].'/class/'.$class_semester['id'].'/') ?>"><?= esc("{$class_semester['grade_name']} {$class_semester['name']}") ?></a>
        </li>
        <li class="breadcrumb-item active" aria-current="page">Jam Masuk</li>
    </ol>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <section class="content">
        <div class="card">
            <?= $this->include('admin/classes/class_semester/components/tabs') ?>
            <form action="" method="post">
                <?= csrf_field() ?>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="grace_period" class="col-form-label">Batas Keterlambatan</label>
                                <input type="number" class="form-control" id="grace_period" name="grace_period" value="<?= esc($class_semester['grace_period']) ?>">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="clock-in" class="col-form-label">Jam Masuk</label>
                                <div class="input-group" id="clock-in">
                                    <input type="time" name="clock-in" class="form-control" required
                                        value="<?= !empty($class_semester['clock_in']) ? $class_semester['clock_in'] : '' ?>" />
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="clock-out" class="col-form-label">Jam Pulang</label>
                                <div class="input-group" id="clock-out">
                                    <input type="time" name="clock-out" class="form-control" required
                                        value="<?= !empty($class_semester['clock_out']) ? $class_semester['clock_out'] : '' ?>" />
                                </div>
                            </div>
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
    <script>
        $(function(){
            $('form').validate({})
        })
    </script>
<?= $this->endSection() ?>