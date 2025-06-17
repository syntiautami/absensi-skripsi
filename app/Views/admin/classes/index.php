<?= $this->extend('layouts/base') ?>

<?= $this->section('header') ?>
    <?= view('components/header', ['role' => 'Admin']) ?>
<?= $this->endSection() ?>

<?= $this->section('breadcrumb') ?>
    <nav aria-label="breadcrumb" style="margin-top:1rem;">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="<?= base_url('admin/') ?>">Home</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">Kelas</li>
        </ol>
    </nav>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <section class="content">
        <div class="card">
            <div class="card-body">
                <table id="academicYearTable" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th class="text-center">Tahun Pelajaran</th>
                            <th class="text-center" style="width: 200px;">Tanggal Mulai</th>
                            <th class="text-center" style="width: 200px;">Tanggal Akhir</th>
                            <th class="text-center" style="width: 200px;">Sedang Berjalan</th>
                            <th class="text-center" style="width: 100px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; foreach ($academic_years as $item): ?>
                        <tr href="<?= base_url('admin/classes/') ?>">
                            <td class="text-center"><?= esc($item['name']) ?></td>
                            <td class="text-center">
                                <?= (new DateTime($item['start_date']))->format('d-m-Y') ?>
                            </td>
                            <td class="text-center">
                                <?= (new DateTime($item['end_date']))->format('d-m-Y') ?>
                            </td>
                            <td class="text-center">
                                <?php if ($item['in_session']): ?>
                                    <i class="fas fa-check text-success"></i>
                                <?php else: ?>
                                    <i class="fas fa-times text-danger"></i>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <a href="<?= base_url('admin/classes/academic-year/'.$item['id'].'/') ?>" class="btn btn-sm btn-success">Lihat</a>
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
        $('#academicYearTable').DataTable({
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