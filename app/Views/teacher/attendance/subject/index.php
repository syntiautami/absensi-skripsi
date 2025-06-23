<?= $this->extend('layouts/base') ?>
<?= $this->section('header') ?>
    <?= $this->include('components/header') ?>
<?= $this->endSection() ?>

<?= $this->section('breadcrumb') ?>
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="<?= base_url('teacher/') ?>">Sistem Absensi</a>
        </li>
        <li class="breadcrumb-item active" aria-current="page">Absensi Mata Pelajaran</li>
    </ol>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <section class="content">
        <!-- Card Grid -->
        <div class="card">
            <div class="card-body">
                <table id="subjectTable" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th class="text-center">Mata Pelajaran</th>
                            <th class="text-center" style="width: 150px;">Tahun Ajaran</th>
                            <th class="text-center" style="width: 150px;">Semester</th>
                            <th class="text-center" style="width: 150px;">Kelas</th>
                            <th class="text-center" style="width: 150px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($teacher_class_semester_subjects as $teacher_class_semester_subject): ?>
                            <tr>
                                <td><?= esc($teacher_class_semester_subject['subject_name'] ?? '-') ?></td>
                                <td class="text-center"><?= esc($teacher_class_semester_subject['academic_year_name'] ?? '-') ?></td>
                                <td class="text-center"><?= esc($teacher_class_semester_subject['semester_name'] ?? '-') ?></td>
                                <td class="text-center"><?= esc($teacher_class_semester_subject['grade_name'] ?? '-') ?> <?= esc($teacher_class_semester_subject['class_code'] ?? '-') ?></td>
                                <td class="text-center">
                                    <a class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalAttendanceSubject-<?= $teacher_class_semester_subject['css_id'] ?>">Ambil Absen</a>
                                </td>
                            </tr>
                            
                            <!-- Modal per subject -->
                            <div class="modal fade modal-attendance-subject" 
                                id="modalAttendanceSubject-<?= $teacher_class_semester_subject['css_id'] ?>" 
                                tabindex="-1" role="dialog"
                                data-css-id="<?= $teacher_class_semester_subject['css_id'] ?>"
                                aria-labelledby="modalLabel<?= $teacher_class_semester_subject['css_id'] ?>" aria-hidden="true">

                                <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                                    <div class="modal-content">

                                        <div class="modal-header">
                                            <h5 class="modal-title text-center w-100" id="modalLabel<?= $teacher_class_semester_subject['css_id'] ?>">
                                                <?= esc($teacher_class_semester_subject['subject_name'] ?? '-') ?> 
                                                - <?= esc($teacher_class_semester_subject['grade_name'] ?? '-') ?> 
                                                <?= esc($teacher_class_semester_subject['class_code'] ?? '-') ?>
                                            </h5>
                                            <button type="button" class="close position-absolute" style="right: 1rem;" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>

                                        <div class="modal-body">
                                            <div class="d-flex justify-content-between mb-3">
                                                <button type="button" class="btn btn-primary btn-sm btn-prev-week">&laquo;</button>
                                                <strong>Minggu ke <span class="week-label"></span></strong>
                                                <button type="button" class="btn btn-primary btn-sm btn-next-week">&raquo;</button>
                                            </div>
                                            <div class="row">
                                                <?php 
                                                foreach ($ctpData[$teacher_class_semester_subject['css_id']] as $day): 
                                                    $dayIndex = array_search($day['name'], ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu']);
                                                    $date = $weekInfo['dates'][$weekInfo['current_week']][$dayIndex] ?? '';
                                                ?>
                                                    <div class="col mb-3 day-section" data-day-index="<?= $dayIndex ?>">
                                                        <h5><?= esc($day['name']) ?> <small>(<span class="day-date"><?= $date ?></span>)</small></h5>
                                                        <?php foreach ($day['periods'] as $period): ?>
                                                            <a href="<?= base_url('teacher/attendance/subject/'.$period['id'].'/year/'.date('Y', strtotime($date)).'/month/'.date('n', strtotime($date)).'/day/'.date('j', strtotime($date)).'/') ?>"
                                                                class="btn btn-block btn-primary mb-2 text-left attendance-link" data-period="<?= $period['id'] ?>">
                                                                <?= esc($period['start_time']) ?> - <?= esc($period['end_time']) ?>
                                                            </a>
                                                        <?php endforeach; ?>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- End Modal -->
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
        $('#subjectTable').DataTable();
    });
</script>
<script>
    // Global data untuk semua subject (bisa dari controller inject)
    const allWeekInfo = <?= json_encode($weekInfo) ?>;

    // Function untuk update tanggal di modal
    function updateModalDates($modal) {
        const cssId = $modal.data('css-id');
        let currentWeek = $modal.data('current-week');
        const dates = allWeekInfo.dates[currentWeek];

        $modal.find('.day-section').each(function() {
            const dayIndex = $(this).data('day-index');
            const newDate = dates[dayIndex] || '';

            $(this).find('.day-date').text(newDate);

            // Pecah newDate jadi Y M D
            const dateObj = new Date(newDate);
            const year = dateObj.getFullYear();
            const month = parseInt(('0' + (dateObj.getMonth() + 1)).slice(-2));
            const dayNum = parseInt(('0' + dateObj.getDate()).slice(-2));

            $(this).find('.attendance-link').each(function() {
                const periodId = $(this).data('period');
                const newHref = "<?= base_url('teacher/attendance/subject/')?>"+`${periodId}/year/${year}/month/${month}/day/${dayNum}/`;
                $(this).attr('href', newHref);
            });
        });

        $modal.find('.week-label').text(currentWeek);

        $modal.find('.btn-prev-week').prop('disabled', currentWeek <= 1);
        $modal.find('.btn-next-week').prop('disabled', currentWeek >= allWeekInfo.weeks);
    }

    // Prev button click
    $(document).on('click', '.modal-attendance-subject .btn-prev-week', function() {
        const $modal = $(this).closest('.modal-attendance-subject');
        let currentWeek = $modal.data('current-week');

        if (currentWeek > 1) {
            $modal.data('current-week', currentWeek - 1);
            updateModalDates($modal);
        }
    });

    // Next button click
    $(document).on('click', '.modal-attendance-subject .btn-next-week', function() {
        const $modal = $(this).closest('.modal-attendance-subject');
        let currentWeek = $modal.data('current-week');

        if (currentWeek < allWeekInfo.weeks) {
            $modal.data('current-week', currentWeek + 1);
            updateModalDates($modal);
        }
    });

    // Saat modal shown, set current week dan update
    $('.modal-attendance-subject').on('shown.bs.modal', function() {
        const $modal = $(this);
        const cssId = $modal.data('css-id');
        $modal.data('current-week', allWeekInfo.current_week);
        updateModalDates($modal);
    });
</script>

<?= $this->endSection() ?>