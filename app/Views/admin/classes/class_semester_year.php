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
        <li class="breadcrumb-item active" aria-current="page"><?= esc($academic_year['name']) ?></li>
    </ol>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <section class="content">
        <div class="card">
            <div class="card-body">
                <table id="semesterTable" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th class="text-center">Kelas</th>
                            <th class="text-center" style="width: 200px;">Wali Kelas</th>
                            <th class="text-center" style="width: 100px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; foreach ($class_semester_years as $class_semester_year): ?>
                        <tr href="<?= base_url('admin/classes/') ?>">
                            <td class="text-left"><?= esc("{$class_semester_year['grade_name']} {$class_semester_year['class_code']}") ?></td>
                            <td class="text-center"></td>
                            <td class="text-center">
                                <a href="<?= base_url('admin/classes/academic-year/'.$academic_year['id'].'/class_semester_year/'.$class_semester_year['id'].'/') ?>" class="btn btn-sm btn-primary">Lihat</a>
                            </td>
                        </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
                <div class="d-flex justify-content-end mt-3">
                    <a href="<?= base_url('admin/classes/academic-year/'.$academic_year['id'].'/class_semester_year/create/') ?>" class="btn btn-primary">
                        Buat Kelas
                    </a>
                </div>
            </div>
        </div>
    </section>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(function () {
        $('#semesterTable').DataTable({
            "responsive": true,
            "autoWidth": false,
            "searching" : false,
            "lengthChange" : false,
            "paging": false,
            "info" : false
        });
    });
</script>
<?= $this->endSection() ?>