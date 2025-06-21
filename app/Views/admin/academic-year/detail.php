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
            <a href="<?= base_url('admin/academic-year/') ?>">Tahun Pelajaran</a>
        </li>
        <li class="breadcrumb-item active" aria-current="page"><?= esc($academic_year['name']) ?></li>
    </ol>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <section class="content">
        <div class="card">
            <div class="card-header p-0 border-bottom-0">
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a class="nav-link active" href="#tab-detail" data-toggle="tab">Detail</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#tab-semester" data-toggle="tab">Info Semester</a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content">
                    <div class="active tab-pane" id="tab-detail">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Nama</label>
                                    <p class="form-control-plaintext"><?= esc($academic_year['name']) ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Tanggal Mulai</label>
                                    <p class="form-control-plaintext"><?= date('d-m-Y', strtotime($academic_year['start_date'])) ?></p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Tanggal Akhir</label>
                                    <p class="form-control-plaintext"><?= date('d-m-Y', strtotime($academic_year['end_date'])) ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Sedang Berjalan</label>
                                    <p class="form-control-plaintext">
                                        <?php if ($academic_year['in_session']): ?>
                                            <i class="fas fa-check text-success"></i>
                                        <?php else: ?>
                                            <i class="fas fa-times text-danger"></i>
                                        <?php endif; ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane" id="tab-semester">
                        <?php $no = 1; foreach ($semesters as $item): ?>
                            <div class="row">
                                <div class="col-md-12">
                                    <label>
                                        Semester
                                        <?php 
                                            if ($no == 1) {
                                                echo 'Pertama';
                                            } elseif ($no == 2) {
                                                echo 'Kedua';
                                            } else {
                                                echo $no;
                                            }
                                        ?>
                                        <?php $no++; ?>
                                    </label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Nama</label>
                                        <p class="form-control-plaintext"><?= esc($item['name']) ?></p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Tanggal Mulai</label>
                                        <p class="form-control-plaintext"><?= date('d-m-Y', strtotime($item['start_date'])) ?></p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Tanggal Akhir</label>
                                        <p class="form-control-plaintext"><?= date('d-m-Y', strtotime($item['end_date'])) ?></p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Sedang Berjalan</label>
                                        <p class="form-control-plaintext">
                                            <?php if ($item['in_session']): ?>
                                                <i class="fas fa-check text-success"></i>
                                            <?php else: ?>
                                                <i class="fas fa-times text-danger"></i>
                                            <?php endif; ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach ?>
                        <hr>
                        <div class="d-flex justify-content-end mt-3">
                            <a href="<?= base_url('admin/academic-year/'.$academic_year['id'].'/semester/edit/') ?>" class="btn btn-primary">
                                Ubah Semester
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

<?= $this->endSection() ?>