<?= $this->extend('layouts/base') ?>

<?= $this->section('header') ?>
    <?= $this->include('components/header') ?>
<?= $this->endSection() ?>

<?= $this->section('breadcrumb') ?>
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="<?= base_url('admin/') ?>">Sistem Absensi</a>
        </li>
        <li class="breadcrumb-item active" aria-current="page">Tahun Pelajaran</li>
    </ol>
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
                            <th class="text-center" style="width: 150px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; foreach ($academic_years as $item): ?>
                        <tr>
                            <td class="text-center"><?= esc($item['name']) ?></td>
                            <td class="text-center" data-order="<?= (new DateTime($item['start_date']))->format('Y-m-d') ?>">
                                <?= (new DateTime($item['start_date']))->format('d-m-Y') ?>
                            </td>
                            <td class="text-center" data-order="<?= (new DateTime($item['end_date']))->format('Y-m-d') ?>">
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
                                <!-- tombol edit / hapus -->
                                <a href="<?= base_url('admin/academic-year/'.$item['id'].'/') ?>" class="btn btn-sm btn-primary">Lihat</a>
                                <a href="<?= base_url('admin/academic-year/'.$item['id'].'/edit/') ?>" class="btn btn-sm btn-primary">Ubah</a>
                            </td>
                        </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
                <div class="d-flex justify-content-end mt-3">
                    <a href="<?= base_url('admin/academic-year/create/') ?>" class="btn btn-primary">
                        Buat Tahun Pelajaran
                    </a>
                </div>
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
            "info" : false,
            "order" : [['1', 'desc']]
        });
    });
</script>
<?= $this->endSection() ?>