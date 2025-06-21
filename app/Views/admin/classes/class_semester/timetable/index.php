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
            <a href="<?= base_url('admin/classes/academic-year/'.$academic_year['id'].'/semester/'.$semester['id'].'/class/') ?>">Semester <?= esc($semester['name']) ?></a>
        </li>
        <li class="breadcrumb-item">
            <a href="<?= base_url('admin/classes/academic-year/'.$academic_year['id'].'/semester/'.$semester['id'].'/class/'.$class_semester['id'].'/') ?>"><?= esc("{$class_semester['grade_name']} {$class_semester['name']}") ?></a>
        </li>
        <li class="breadcrumb-item active" aria-current="page">Jadwal Pelajaran</li>
    </ol>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <section class="content">
        <div class="card">
            <?= $this->include('admin/classes/class_semester/components/tabs') ?>
            <form action="" method="post">
                <div class="card-body">
                    <table id="timetableTable" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th class="text-center">Hari</th>
                                <th class="text-center" style="width:100px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                helper('day');
                            for ($no = 1; $no <= 5; $no++): ?>
                                <tr>
                                    <td class="text-center" data-order="<?= $no ?>"><?= day_indonesian($no) ?></td>
                                    <td class="text-center">
                                        <a href="<?= base_url('admin/classes/academic-year/'.$academic_year['id'].'/semester/'.$semester['id'].'/class/'.$class_semester['id'].'/timetable/'.$no.'/') ?>" class="btn btn-sm btn-primary">Lihat</a>
                                    </td>
                                </tr>
                            <?php endfor ?>
                        </tbody>
                    </table>
                </div>
            </form>
        </div>
    </section>


<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script>
    $(function () {
        $('#timetableTable').DataTable({
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