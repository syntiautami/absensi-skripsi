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
            <a href="<?= base_url('admin/classes') ?>">Kelas</a>
        </li>
        <li class="breadcrumb-item">
            <a href="<?= base_url('admin/classes/academic-year/'.$academic_year['id'].'/') ?>"><?= esc($academic_year['name']) ?></a>
        </li>
        <li class="breadcrumb-item active" aria-current="page">Buat Kelas</li>
    </ol>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <section class="content">
        <div class="card">
            <form action="" method="post">
                <?= csrf_field() ?>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="col-form-label">Kelas</label>
                                <select name="grade_id" class="form-control">
                                    <?php foreach ($grades as $item): ?>
                                        <option value="<?= $item['id'] ?>"><?= $item['name'] ?></option>
                                        <?php endforeach ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class=" row">
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="code" class="col-form-label">Nama</label>
                                <input type="text" class="form-control" id="code" name="code" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <?php foreach ($semesters as $semester) : ?>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="wali-kelas">Wali Kelas Semester <?= $semester['name'] ?></label>
                                    <select name="form_teacher[<?= $semester['id'] ?>]" id="wali-kelas" class="form-control">
                                        <?php foreach ($teachers as $teacher): ?>
                                            <option value="<?= $teacher['id'] ?>">
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
                    <button type="submit" class="btn btn-primary">Simpan</button>
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