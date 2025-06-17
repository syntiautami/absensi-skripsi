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
                <a href="<?= base_url('admin/classes/') ?>">Kelas</a>
            </li>
            <li class="breadcrumb-item">
                <a href="<?= base_url('admin/classes/academic-year/'.$academic_year['id'].'/') ?>"><?= esc($academic_year['name']) ?></a>
            </li>
            <li class="breadcrumb-item">
                <a href="<?= base_url('admin/classes/academic-year/'.$academic_year['id'].'/semester/'.$semester['id'].'/class/') ?>">Semester <?= esc($semester['name']) ?></a>
            </li>
            <li class="breadcrumb-item active" aria-current="page"><?= esc("{$class_semester['section_name']} {$class_semester['grade_name']} {$class_semester['name']}") ?></li>
        </ol>
    </nav>
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
                            <p class="form-control-plaintext"><?= esc("{$class_semester['section_name']} {$class_semester['grade_name']} {$class_semester['name']}") ?></p>
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
                            <img src="<?= base_url('assets/users/' . $homeroomTeacher['profile_photo'] ?? 'default.jpg') ?>" alt="" class="img-circle elevation-2" width="50" height="50">
                            <span class="d-none d-md-inline font-weight-bold">
                                <?= esc("{$homeroomTeacher['first_name']} {$homeroomTeacher['last_name']}") ?>
                            </span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

<?= $this->endSection() ?>