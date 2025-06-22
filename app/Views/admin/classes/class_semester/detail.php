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
        <li class="breadcrumb-item active" aria-current="page"><?= esc("{$class_semester_year['grade_name']} {$class_semester_year['class_code']}") ?></li>
    </ol>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <section class="content">
        <div class="card">
            <?= $this->include('admin/classes/class_semester/components/tabs') ?>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label>Nama</label>
                            <p class="form-control-plaintext"><?= esc("{$class_semester_year['grade_name']} {$class_semester_year['class_code']}") ?></p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-8">
                        <label for="">Wali Kelas</label>
                    </div>
                </div>
                <?php foreach ($class_homeroom as $homeroomTeacher): ?>
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <img src="<?= base_url($homeroomTeacher['profile_photo'] ?? 'assets/img/users/default.jpg') ?>" alt="" class="img-circle elevation-2" width="50" height="50">
                            <span class="d-none d-md-inline font-weight-bold">
                                <?= esc("{$homeroomTeacher['first_name']} {$homeroomTeacher['last_name']}") ?>
                            </span>
                        </div>
                    </div>
                <?php endforeach; ?>
                <hr>
                <div class="d-flex justify-content-end mt-3">
                    <a href="<?= base_url('admin/classes/academic-year/'.$academic_year['id'].'/class_semester_year/'.$class_semester_year['id'].'/edit/') ?>" class="btn btn-primary">
                        Ubah Kelas
                    </a>
                </div>
            </div>
        </div>
    </section>

<?= $this->endSection() ?>