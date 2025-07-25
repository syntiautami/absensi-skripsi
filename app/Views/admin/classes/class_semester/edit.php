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
            <a href="<?= base_url('admin/classes/academic-year/'.$academic_year['id'].'/class-semester-year/'.$class_semester_year['id'].'/') ?>"><?= esc("{$class_semester_year['grade_name']} {$class_semester_year['class_code']}") ?></a>
        </li>
        <li class="breadcrumb-item active" aria-current="page">Ubah</li>
    </ol>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <section class="content">
        <div class="card">
            <form action="" method="post">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="code">Nama</label>
                                <input type="text" id="code" class="form-control" name="code" value="<?= esc($class_semester_year['class_code']) ?>">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <?php foreach ($class_semesters as $class_semester) : ?>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="wali-kelas">Wali Kelas Semester <?= $class_semester['semester_name'] ?></label>
                                    <select name="form_teacher[<?= $class_semester['id'] ?>]" id="wali-kelas" class="form-control">
                                        <option value="">---</option>
                                        <?php foreach ($teachers as $teacher): ?>
                                            <option value="<?= $teacher['id'] ?>"
                                            <?= isset($class_homeroom[$class_semester['id']]) && $teacher['id'] == $class_homeroom[$class_semester['id']] ? 'selected' : '' ?>>
                                            <?= $teacher['first_name'] ?> <?= $teacher['last_name'] ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        <?php endforeach ?>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="d-flex justify-content-end mt-3">
                        <a href="<?= base_url('admin/classes/academic-year/'.$academic_year['id'].'/class-semester-year/'.$class_semester_year['id'].'/') ?>" class="btn btn-secondary mr-2">Kembali</a>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </section>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(function(){
        $('form').validate({});
    })
</script>
<?= $this->endSection() ?>