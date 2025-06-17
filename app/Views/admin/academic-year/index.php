<?= $this->extend('layouts/base') ?>

<?= $this->section('title') ?>
Admin Academic Year
<?= $this->endSection() ?>

<?= $this->section('header') ?>
    <header class="navbar navbar-expand navbar-white navbar-light">
        <div class="d-flex align-items-center p-2">
            <div class="mr-3">
                <img src="<?= base_url('assets/img/logo.png') ?>" alt="Logo Sekolah" style="width: 50px; height: 50px; object-fit: cover;">
            </div>
            <div>
                <div class="font-weight-bold">Nama Sekolah</div>
                <div class="text-muted">admin</div>
            </div>
        </div>
    </header>
<?= $this->endSection() ?>

<?= $this->section('breadcrumb') ?>
    <nav aria-label="breadcrumb" style="margin-top:1rem;">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="<?= base_url('admin/') ?>">Home</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">Tahun Ajaran</li>
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
                            <th class="text-center">No</th>
                            <th class="text-center">Tahun Akademik</th>
                            <th class="text-center">Tanggal Mulai</th>
                            <th class="text-center">Tanggal Akhir</th>
                            <th class="text-center">Sedang Berjalan</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; foreach ($academic_years as $item): ?>
                        <tr>
                            <td class="text-center"><?= $no++ ?></td>
                            <td class="text-center"><?= esc($item['name']) ?></td>
                            <td class="text-center"><?= date('d-m-Y', strtotime($item['start_date'])) ?></td>
                            <td class="text-center"><?= date('d-m-Y', strtotime($item['end_date'])) ?></td>
                            <td class="text-center">
                                <?php if ($item['in_session']): ?>
                                    <i class="fas fa-check text-success"></i>
                                <?php else: ?>
                                    <i class="fas fa-times text-danger"></i>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <!-- tombol edit / hapus -->
                                <a href="<?= base_url('admin/academic-year/edit/'.$item['id']) ?>" class="btn btn-sm btn-primary">Edit</a>
                            </td>
                        </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
                <div class="d-flex justify-content-end mt-3">
                    <a href="<?= base_url('admin/academic-year/create') ?>" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Buat Tahun Ajaran
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
            "info" : false
        });
    });
</script>
<?= $this->endSection() ?>