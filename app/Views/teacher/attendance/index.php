<?= $this->extend('layouts/base') ?>
<?php
    $hariList = [
        'Sunday' => 'Minggu',
        'Monday' => 'Senin',
        'Tuesday' => 'Selasa',
        'Wednesday' => 'Rabu',
        'Thursday' => 'Kamis',
        'Friday' => 'Jumat',
        'Saturday' => 'Sabtu'
    ];
    $bulanList = [
        '01' => 'Januari',
        '02' => 'Februari',
        '03' => 'Maret',
        '04' => 'April',
        '05' => 'Mei',
        '06' => 'Juni',
        '07' => 'Juli',
        '08' => 'Agustus',
        '09' => 'September',
        '10' => 'Oktober',
        '11' => 'November',
        '12' => 'Desember'
    ];


    $hariInggris = date('l');
    $hari = $hariList[$hariInggris];
    $tanggal = date('d');
    $bulan = $bulanList[date('m')];
    $tahun = date('Y');
?>

<?= $this->section('styles') ?>
    <style>
        .card{
            box-shadow: none;
        }
    </style>
<?= $this->endSection() ?>
<?= $this->section('header') ?>
    <?= view('teacher/attendance/components/header', ['role' => 'Teacher']) ?>
<?= $this->endSection() ?>
<?= $this->section('breadcrumb') ?>
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="<?= base_url('teacher/') ?>">Home</a>
        </li>
        <li class="breadcrumb-item active" aria-current="page">Absensi Harian</li>
    </ol>
<?= $this->endSection() ?>
<?= $this->section('content') ?>
    <section class="content">
        <!-- Card Grid -->
        <div class="card">
            <form action="" method="post">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <select class="form-control" name="class" id="" disabled>
                                    <option value=""><?= esc(session()->get('homeroom_teacher')['section_name']) ?> <?= esc(session()->get('homeroom_teacher')['grade_name'])  ?> <?= esc(session()->get('homeroom_teacher')['class_code'])  ?></option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <?php $no = 1; foreach ($student_class_semesters as $scs): ?>
                            <?php
                                $statusClass = isset($studentAttendance[$scs['id']])
                                    ? $studentAttendance[$scs['id']]
                                    : 'present'; // default present kalau belum ada
                            ?>
                            <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6">
                                <div class="card text-center">
                                    <input type="hidden" data-id="<?= $scs['id'] ?>" name="absence_type[<?= $scs['id'] ?>]">
                                    <div class="card-body p-1 border-1">
                                        <div 
                                            class="user-pic"
                                        >
                                            <img src="<?= base_url('assets/users/default.jpg') ?>" 
                                            alt="Foto Siswa"
                                            data-toggle="modal" 
                                            data-target="#attendanceModal" 
                                            data-siswa="<?= $scs['id'] ?>" 
                                            class="img-fluid mb-1 rounded-circle border <?= esc($statusClass) ?>" 
                                            style="width: 100px; height: 100px; object-fit: cover;cursor: pointer;">
                                        </div>
                                        <div class="user-detail">
                                            <div class="small font-weight-bold"><?= esc("{$scs['first_name']} {$scs['last_name']}") ?></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Modal -->
                    <div class="modal fade" id="attendanceModal" tabindex="-1" role="dialog" aria-labelledby="attendanceModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content" style="background-color: #d5f5d5; border-radius: 15px;">
                                <div class="modal-header border-0">
                                    <h5 class="modal-title w-100 text-center" id="attendanceModalLabel">
                                    <strong>UBAH STATUS KEHADIRAN</strong><br>
                                    <small class="text-muted status-time"><?= $hari ?>, <?= $tanggal ?> <?= $bulan ?> <?= $tahun ?></small>
                                    </h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="position: absolute; right: 15px;">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body text-center">
                                    <h4 class="status-name primary-color"></h4>
                                    
                                    <div class="status-container d-flex justify-content-center flex-wrap my-3">
                                        <!-- Item -->
                                        <div class="mx-2 my-2 p-2 text-center status-item" data-status="present" style="cursor:pointer;">
                                            <div class="rounded-circle mx-auto" style="width: 60px; height: 60px; background-color: #2e7d32;"></div>
                                            <small class="d-block mt-2 font-weight-bold">Hadir</small>
                                        </div>
                                        
                                        <div class="mx-2 my-2 p-2 text-center status-item" data-status="absent" style="cursor:pointer;">
                                            <div class="rounded-circle mx-auto" style="width: 60px; height: 60px; background-color: #e74c3c;"></div>
                                            <small class="d-block mt-2 font-weight-bold">Alpa</small>
                                        </div>

                                        <div class="mx-2 my-2 p-2 text-center status-item" data-status="late" style="cursor:pointer;">
                                            <div class="rounded-circle mx-auto" style="width: 60px; height: 60px; background-color: #f39c12;"></div>
                                            <small class="d-block mt-2 font-weight-bold">Terlambat</small>
                                        </div>

                                        <div class="mx-2 my-2 p-2 text-center status-item" data-status="excused" style="cursor:pointer;">
                                            <div class="rounded-circle mx-auto" style="width: 60px; height: 60px; background-color: #3498db;"></div>
                                            <small class="d-block mt-2 font-weight-bold">Izin</small>
                                        </div>

                                        <div class="mx-2 my-2 p-2 text-center status-item" data-status="sick" style="cursor:pointer;">
                                            <div class="rounded-circle mx-auto" style="width: 60px; height: 60px; background-color: purple;"></div>
                                            <small class="d-block mt-2 font-weight-bold">Sakit</small>
                                        </div>
                                    </div>
                                    <button id="modal-btn" class="btn btn-success btn-block mt-4" type="button" data-dismiss="modal">SIMPAN</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>
                    <!-- Save Button -->
                    <div class="row">
                        <div class="col text-right mr-5">
                            <button class="btn btn-success px-4" type="submit">SIMPAN</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
    <!-- /.content -->
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $('#attendanceModal').on('show.bs.modal', function (event) {
        const card = $(event.relatedTarget)
        const siswaId = card.data('siswa');
        const studentName = $(card).parent().parent().find('.user-detail').find('div').text()

        const modal = $(this)
        modal.find('.status-name').text(studentName)
        modal.find('.status-name').attr('data-id',siswaId)
    })

    $('.status-item').click(function(e){
        $('.status-item').each(function(e){
            $(this).removeClass('active');
        })
        const modal = $(this).closest('.modal');
        modal.find('.status-name').attr('data-type', $(this).data('status'));
        $(this).toggleClass('active');
    })
    $('#modal-btn').click(function(e){
        const modal = $(this).closest('.modal');
        const studentId = modal.find('.status-name').attr('data-id');
        const statusType = modal.find('.status-name').attr('data-type');

        console.log({ id: studentId, type: statusType });

        modal.find('.status-item').removeClass('active');

        const hiddenInputStudent = $(`input[data-id='${studentId}']`);
        const imgElem = hiddenInputStudent.next().find('img');

        if (statusType) {
            imgElem.removeClass('present sick absent late excused');
            imgElem.addClass(statusType);
            hiddenInputStudent.val(statusType);
        }
    });

</script>
<?= $this->endSection() ?>