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
            <a href="<?= base_url('admin/report/attendance/') ?>">Laporan Absensi</a>
        </li>
        <li class="breadcrumb-item active" aria-current="page"><?= $academic_year['name'] ?></li>
    </ol>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <section class="content">
        <div class="card">
            <div class="card-body">
                <table id="gradesTable" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th class="text-center">Kelas</th>
                            <?php foreach ($semesters as $semester): ?>
                                <th class="text-center" style="width: 100px;">Semester <?= $semester['name'] ?></th>
                            <?php endforeach ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; foreach ($class_semester_years as $class_semester_year): ?>
                            <tr>
                                <td class="text-center"><?= esc("{$class_semester_year['grade_name']} {$class_semester_year['class_code']}") ?></td>
                                <?php foreach ($semesters as $semester): ?>
                                    <td class="text-center">
                                        <?php if (!empty($class_semester_data[$class_semester_year['id']][$semester['id']])): ?>
                                            <a href="<?= base_url('admin/report/attendance/'.$class_semester_data[$class_semester_year['id']][$semester['id']].'/download/') ?>" class="btn btn-sm btn-primary">Unduh</a>
                                        <?php else: ?>
                                            -
                                        <?php endif ?>
                                    </td>
                                <?php endforeach ?>
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
        $('#gradesTable').DataTable({
            "responsive": true,
            "autoWidth": false,
            "searching" : false,
            "lengthChange" : false,
            "paging": false,
            "info" : false,
        });
    });
</script>
<?= $this->endSection() ?>