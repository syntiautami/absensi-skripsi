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
            <a href="<?= base_url('admin/classes/') ?>">Kelas</a>
        </li>
        <li class="breadcrumb-item">
            <a href="<?= base_url('admin/classes/academic-year/'.$academic_year['id'].'/') ?>"><?= esc($academic_year['name']) ?></a>
        </li>
        <li class="breadcrumb-item">
            <a href="<?= base_url('admin/classes/academic-year/'.$academic_year['id'].'/semester/'.$semester['id'].'/class/') ?>">Semester <?= esc($semester['name']) ?></a>
        </li>
        <li class="breadcrumb-item">
            <a href="<?= base_url('admin/classes/academic-year/'.$academic_year['id'].'/semester/'.$semester['id'].'/class/'.$class_semester['id'].'/') ?>"><?= esc("{$class_semester['grade_name']} {$class_semester['name']}") ?></a>
        </li>
        <li class="breadcrumb-item">
            <a href="<?= base_url('admin/classes/academic-year/'.$academic_year['id'].'/semester/'.$semester['id'].'/class/'.$class_semester['id'].'/timetable/') ?>">Jadwal Pelajaran</a>
        </li>
        <?php
            helper('day')
        ?>
        <li class="breadcrumb-item active" aria-current="page"><?= day_indonesian($day) ?></li>
    </ol>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <section class="content">
        <div class="card">
            <?= $this->include('admin/classes/class_semester/components/tabs') ?>
            <form action="" method="post">
                <div class="card-body">
                    <table id="timetableTable" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th class="text-center">Periode</th>
                                <th class="text-center">Waktu</th>
                                <th class="text-center">Mata Pelajaran</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($timetable_list as $timetable_period) : ?>
                                <?php 
                                    // ambil timetable id yang lagi diloop
                                    $tid = $timetable_period['id'];

                                    // cek apakah existing data ada
                                    $selected_subject_id = isset($existing_css_data[$tid]) ? $existing_css_data[$tid] : '';
                                ?>
                                <tr>
                                    <td class="text-center"><?= $timetable_period['period'] ?></td>
                                    <td class="text-center"><?= date('H:i', strtotime($timetable_period['start_time'])) ?> - <?= date('H:i', strtotime($timetable_period['end_time'])) ?></td>
                                    <td>
                                        <select name="period[<?= $tid ?>]" id="" class="form-control">
                                            <option value="">-----</option>
                                            <?php foreach ($subjects as $subject): ?>
                                                existing_css_data
                                                <option value="<?= $subject['id'] ?>" <?= ($subject['id'] == $selected_subject_id) ? 'selected' : '' ?> ><?= $subject['name'] ?></option>
                                            <?php endforeach ?>
                                        </select>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-end mt-3">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </section>


<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script>
    $(function () {
        $('#timetableTable').DataTable({
            "responsive": true,
            "autoWidth": false,
            "searching" : false,
            "lengthChange" : false,
            "paging": false,
            "info" : false,
        });
    });
</script>
<?= $this->endSection() ?>