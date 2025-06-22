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
            <a href="<?= base_url('admin/report/attendance/subject/') ?>">Laporan Absensi Mata Pelajaran</a>
        </li>
        <li class="breadcrumb-item">
            <a href="<?= base_url('admin/report/attendance/subject/'.$academic_year['id'].'/') ?>"><?= $academic_year['name'] ?></a>
        </li>
        <li class="breadcrumb-item active" aria-current="page"><?= esc("{$class_semester_year['grade_name']} {$class_semester_year['class_code']}") ?></li>
    </ol>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <section class="content">
        <div class="card">
            <div class="card-body">
                <table id="gradesTable" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th class="text-center">Mata Pelajaran</th>
                            <?php foreach ($class_semesters as $class_semester): ?>
                                <th class="text-center" style="width: 100px;">Semester <?= $class_semester['semester_name'] ?></th>
                            <?php endforeach ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; foreach ($subjects as $subject): ?>
                            <tr>
                                <td><?= esc($subject['name']) ?></td>
                                <?php foreach ($class_semesters as $class_semester): ?>
                                    <td class="text-center">
                                        <?php if (!empty($class_semester_subject_data[$class_semester['id']][$subject['id']])): ?>
                                            <a href="<?= base_url('admin/report/attendance/subject/'.$class_semester_subject_data[$class_semester['id']][$subject['id']]).'/download/' ?>" class="btn btn-sm btn-primary">Unduh</a>
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