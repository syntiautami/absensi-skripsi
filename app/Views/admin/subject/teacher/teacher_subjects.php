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
        <li class="breadcrumb-item">
            <a href="<?= base_url('admin/subject/teacher/') ?>"><?= $academic_year['name'] ?></a>
        </li>
        <li class="breadcrumb-item active" aria-current="page"><?= esc("{$teacher['first_name']} {$teacher['last_name']}") ?></li>
    </ol>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <section class="content">
        <div class="card">
            <div class="card-body">
                <table id="teacherTable" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th class="text-center">Nama</th>
                            <?php $no = 1; foreach ($classSemesters as $key => $class_semester): ?>
                                <th class="text-center" style="width: 70px;"><?= $class_semester['kelas'] ?></th>
                            <?php endforeach ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; foreach ($subjects as $subject): ?>
                        <tr>
                            <td><?= esc($subject['name']) ?></td>
                            <?php $no = 1; foreach ($classSemesters as $key => $class_semester): ?>
                                <td class="text-center">
                                    <input type="checkbox" class="">
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
        $('#teacherTable').DataTable({
            "responsive": true,
            "lengthChange" : false,
            "paging" : false,
            "searching" : false,
            "info" : false,
            ordering: false,
            "autowidth" : true,
            "fixedHeader" : true,
            "fixedColumns" : {
                "leftColumns" : 1,
            }
        });
    });
</script>
<?= $this->endSection() ?>