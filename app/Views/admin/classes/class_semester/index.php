<?= $this->extend('layouts/base') ?>

<?= $this->section('styles') ?>
    <style>
        table td{
            vertical-align: middle !important;
        }
    </style>
<?= $this->endSection() ?>

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
                <a href="<?= base_url('admin/classes/') ?>">Kelas</a>
            </li>
            <li class="breadcrumb-item">
                <a href="<?= base_url('admin/classes/academic-year/'.$academic_year['id'].'/') ?>"><?= esc($academic_year['name']) ?></a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">Semester <?= esc($semester['name']) ?></li>
        </ol>
    </nav>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <section class="content">
        <div class="card">
            <div class="card-body">
                <table id="class_semester_table" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th class="text-center">Kelas</th>
                            <th class="text-center" style="width: 300px;">Wali Kelas</th>
                            <th class="text-center" style="width: 150px;">Total Siswa</th>
                            <th class="text-center" style="width: 100px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; foreach ($class_semesters as $item): ?>
                            <tr href="<?= base_url('admin/classes/') ?>">
                                <td class="text-center"><?= esc("{$item['section_name']} {$item['grade_name']} {$item['name']}") ?></td>
                                <td class="text-center">
                                    <?php if (!empty($class_homeroom[$item['id']])): ?>
                                        <?php foreach ($class_homeroom[$item['id']] as $teacher): ?>
                                            <div style="display: flex; align-items: center; margin-bottom: 5px;">
                                                <img src="<?= base_url('assets/users/' . $teacher['profile_photo'] ?? 'default.jpg') ?>" alt="<?= esc($teacher['first_name'].' '.$teacher['last_name']) ?>" style="width: 40px; height: 40px; object-fit: cover; border-radius: 50%; margin-right: 8px;">
                                                <span><?= esc($teacher['first_name'].' '.$teacher['last_name']) ?></span>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </td>
                                <td class="text-center"><?= $item['total_students'] ?></td>
                                <td class="text-center">
                                    <a href="<?= base_url('admin/classes/academic-year/'.$academic_year['id'].'/semester/'.$semester['id'].'/class/'.$item['id'].'/') ?>" class="btn btn-sm btn-success">Lihat</a>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
                <div class="d-flex justify-content-end mt-3">
                    <a href="<?= base_url('admin/classes/academic-year/'.$academic_year['id'].'/semester/'.$semester['id'].'/class/create/') ?>" class="btn btn-primary">
                        Buat Kelas
                    </a>
                </div>
            </div>
        </div>
    </section>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(function () {
        $('#class_semester_table').DataTable({
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