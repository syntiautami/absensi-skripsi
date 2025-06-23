<?= $this->extend('layouts/base') ?>
<?= $this->section('header') ?>
    <?= $this->include('components/header') ?>
<?= $this->endSection() ?>

<?= $this->section('breadcrumb') ?>
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="<?= base_url('teacher/') ?>">Sistem Absensi</a>
        </li>
        <li class="breadcrumb-item active" aria-current="page">Beranda</li>
    </ol>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <section class="content">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 font-weight-bold">JADWAL PELAJARAN</h5>
            </div>
            <div class="card-body">
                <div class="row subjects-row">
                    <?php helper('day') ?>
                    <?php foreach ($ctp_data as $day => $ctpList): ?>
                        <div class="col mb-4">
                            <div class="card">
                                <div class="card-header font-weight-bold text-center">
                                    <h5 class="mb-0 font-weight-bold"><?= day_indonesian($day) ?></h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <?php foreach ($ctpList as $ctp): ?>
                                            <div class="col-sm-12 mb-2">
                                                <div class="d-flex justify-content-between align-items-center p-3 bg-success text-white rounded">
                                                    <div>
                                                        <div class="font-weight-bold"><?= $ctp['subject_name'] ?></div>
                                                        <div class="small"><?= esc("{$ctp['grade_name']} {$ctp['class_code']}") ?></div>
                                                    </div>
                                                    <div class="font-weight-bold">
                                                        <?= $ctp['start_time'] ?> - <?= $ctp['end_time'] ?>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach ?>
                </div>
            </div>
            <?php if (session()->get('homeroom_teacher')) : ?>
                <div class="card-header">
                    <h5 class="mb-0 font-weight-bold">KEHADIRAN HARI INI</h5>
                    <small class="text-muted" style="font-size: 1em;">Kelas <?= esc(session()->get('homeroom_teacher')['grade_name'])  ?> <?= esc(session()->get('homeroom_teacher')['class_code'])  ?></small>
                </div>
                <div class="card-body">
                    <div class="row">
                        <?php
                            $attendance_labels = [
                                'present' => 'TEPAT WAKTU',
                                'late'    => 'TERLAMBAT',
                                'sick'    => 'SAKIT',
                                'excused' => 'IZIN',
                                'absent'  => 'ALPA',
                                'total'   => 'TOTAL'
                            ];
                        ?>
                        <?php foreach ($attendance_data as $key => $value): ?>
                            <div class="col dashboard-<?= $key ?>">
                                <div class="small-box <?= $key ?>">
                                    <div class="inner text-center">
                                        <h3><?= $value ?></h3>
                                        <p class="text-uppercase"><?= isset($attendance_labels[$key]) ? $attendance_labels[$key] : ucfirst($key) ?></p>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach ?>
                    </div>
                </div>
            <?php endif ?>
        </div>
    </section>
    <!-- /.content -->
<?= $this->endSection() ?>