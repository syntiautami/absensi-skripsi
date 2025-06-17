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
            <li class="breadcrumb-item">
                <a href="<?= base_url('admin/academic-year/') ?>">Tahun Ajaran</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page"><?= esc($academic_year['name']) ?></li>
        </ol>
    </nav>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <section class="content">
        <div class="card">
            <div class="card-body">
                <dl class="row">
                    <dt class="col-sm-4">Nama Tahun Akademik</dt>
                    <dd class="col-sm-8"><?= esc($academicYear['name']) ?></dd>

                    <dt class="col-sm-4">Tanggal Mulai</dt>
                    <dd class="col-sm-8"><?= date('d-m-Y', strtotime($academicYear['start_date'])) ?></dd>

                    <dt class="col-sm-4">Tanggal Akhir</dt>
                    <dd class="col-sm-8"><?= date('d-m-Y', strtotime($academicYear['end_date'])) ?></dd>

                    <dt class="col-sm-4">Sedang Berjalan</dt>
                    <dd class="col-sm-8">
                        <?= $academicYear['in_session'] ? '<span class="badge bg-success">Ya</span>' : '<span class="badge bg-secondary">Tidak</span>' ?>
                    </dd>
                </dl>
            </div>

            <div class="card-footer">
                <a href="<?= site_url('admin/academic-year/') ?>" class="btn btn-secondary">Kembali</a>
            </div>
        </div>
    </section>

<?= $this->endSection() ?>