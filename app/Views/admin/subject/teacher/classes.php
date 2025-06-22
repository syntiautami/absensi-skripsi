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
            <a href="<?= base_url('admin/subject/teacher/') ?>">Mata Pelajaran Guru</a>
        </li>
        <li class="breadcrumb-item active" aria-current="page"><?= $academic_year['name'] ?></li>
    </ol>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <section class="content">
        <div class="card">
            <div class="card-body">
                <table id="teacherTable" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th class="text-center">Kelas</th>
                            <th class="text-center" style="width: 150px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; foreach ($class_semester_years as $class_semester_year): ?>
                        <tr>
                            <td class="text-center"><?= esc("{$class_semester_year['grade_name']} {$class_semester_year['class_code']}") ?></td>
                            <td class="text-center">
                                <a href="<?= base_url('admin/subject/teacher/academic-year/'.$academic_year['id'].'/class_semester_year/'.$class_semester_year['id'].'/') ?>" class="btn btn-sm btn-primary">Lihat</a>
                            </td>
                        </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(function () {
        $('#teacherTable').DataTable({
            "responsive": true,
            "lengthChange" : false,
            "info" : false,
            searching: false,
            paging: false
        });
    });
</script>
<?= $this->endSection() ?>