<?= $this->extend('layouts/base') ?>
<?= $this->section('header') ?>
    <?= $this->include('components/header') ?>
<?= $this->endSection() ?>

<?= $this->section('breadcrumb') ?>
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="<?= base_url('admin/') ?>">Sistem Absensi</a>
        </li>
        <li class="breadcrumb-item active" aria-current="page">Mata Pelajaran</li>
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
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($subjects as $subject) : ?>
                            <tr>
                                <td class="text-center">
                                    <?= $subject['name'] ?>
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
        $('#subjectTable').DataTable({
            "responsive": true,
            "lengthChange" : false,
            "paging" : false,
            "info" : false,
            "fixedHeader" : true,
        });
    });
</script>
<?= $this->endSection() ?>