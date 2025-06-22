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
            <a href="<?= base_url('admin/report/attendance/') ?>">Mata Pelajaran Kelas</a>
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
                            <?php $no = 1; foreach ($semesters as $semester): ?>
                                <th class="text-center" style="width: 100px;">Semester <?= $semester ?></th>
                            <?php endforeach ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; foreach ($classSemesters as $key => $class_semester): ?>
                            <tr>
                                <td class="text-center"><?= esc($class_semester['kelas']) ?></td>
                                <?php $no = 1; foreach ($semesters as $semester): ?>
                                    <td class="text-center">
                                        <a href="<?= base_url('admin/subject/class/academic-year/'.$academic_year['id'].'/class/'.$class_semester[$semester]['cs_id'].'/') ?>" class="btn btn-sm btn-primary">Lihat</a>
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