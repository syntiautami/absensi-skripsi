<?= $this->extend('layouts/base') ?>

<?= $this->section('header') ?>
    <?= view('components/header', ['role' => 'Admin']) ?>
<?= $this->endSection() ?>

<?= $this->section('breadcrumb') ?>
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="<?= base_url('admin/') ?>">Home</a>
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
                            <th class="text-center">Semester</th>
                            <th class="text-center" style="width: 200px;">Sedang Berjalan</th>
                            <th class="text-center" style="width: 100px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; foreach ($semesters as $item): ?>
                        <tr href="<?= base_url('admin/classes/') ?>">
                            <td class="text-center"><?= esc($item['name']) ?></td>
                            <td class="text-center">
                                <?php if ($item['in_session']): ?>
                                    <i class="fas fa-check text-success"></i>
                                <?php else: ?>
                                    <i class="fas fa-times text-danger"></i>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <a href="<?= base_url('admin/classes/academic-year/'.$academic_year['id'].'/semester/'.$item['id'].'/class/') ?>" class="btn btn-sm btn-success">Lihat</a>
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