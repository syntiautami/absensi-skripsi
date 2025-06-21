<?= $this->extend('layouts/base') ?>
<?= $this->section('header') ?>
    <?= $this->include('components/header') ?>
<?= $this->endSection() ?>

<?= $this->section('breadcrumb') ?>
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="<?= base_url('teacher/') ?>">Sistem Absensi</a>
        </li>
        <li class="breadcrumb-item active" aria-current="page">Laporan Absensi</li>
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
                <table id="teacherReport" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th class="text-center">Tahun Pelajaran</th>
                            <th class="text-center">Semester</th>
                            <th class="text-center">Kelas</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center"><?= esc($homeroom['academic_year_name'] ?? '-') ?></td>
                            <td class="text-center"><?= esc($homeroom['semester_name'] ?? '-') ?></td>
                            <td class="text-center">
                                <?= esc($homeroom['grade_name'] ?? '-') ?> <?= esc($homeroom['class_code'] ?? '-') ?>
                            </td>
                            <td class="text-center">
                                <a href="<?= base_url('teacher/report/attendance/'.$homeroom['class_semester_id']. '/download/') ?>" class="btn btn-primary btn-sm">Unduh</a>
                            </td>
                        </tr>
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
        $('#teacherReport').DataTable({
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