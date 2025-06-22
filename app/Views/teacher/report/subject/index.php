<?= $this->extend('layouts/base') ?>
<?= $this->section('header') ?>
    <?= $this->include('components/header') ?>
<?= $this->endSection() ?>

<?= $this->section('breadcrumb') ?>
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="<?= base_url('teacher/') ?>">Sistem Absensi</a>
        </li>
        <li class="breadcrumb-item active" aria-current="page">Laporan Absensi Mata Pelajaran</li>
    </ol>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <section class="content">
        <!-- Card Grid -->
        <div class="card">
            <div class="card-body">
                <?php
                    $homeroom = session()->get('homeroom_teacher');
                ?>
                <table id="subjectTable" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th class="text-center">Mata Pelajaran</th>
                            <th class="text-center" style="width: 200px;">Semester</th>
                            <th class="text-center" style="width: 150px;">Kelas</th>
                            <th class="text-center" style="width: 150px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($teacher_class_semester_subjects as $teacher_class_semester_subject): ?>
                            <tr>
                                <td class=""><?= esc($teacher_class_semester_subject['subject_name']) ?></td>
                                <td class="text-center"><?= esc("Semester {$teacher_class_semester_subject['semester_name']} {$teacher_class_semester_subject['academic_year_name']}") ?></td>
                                <td class="text-center"><?= esc("{$teacher_class_semester_subject['grade_name']} {$teacher_class_semester_subject['class_code']}") ?></td>
                                <td class="text-center">
                                    <a href="<?= base_url('teacher/report/attendance/subject'.$teacher_class_semester_subject['css_id']. '/download/') ?>" class="btn btn-primary btn-sm">Unduh</a>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
    <!-- /.content -->
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(function () {
        $('#subjectTable').DataTable();
    });
</script>
<?= $this->endSection() ?>