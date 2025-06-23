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
            <a href="<?= base_url('admin/classes') ?>">Kelas</a>
        </li>
        <li class="breadcrumb-item active" aria-current="page"><?= esc($academic_year['name']) ?></li>
    </ol>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <section class="content">
        <div class="card">
            <div class="card-body">
                <table id="semesterTable" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 300px;">Kelas</th>
                            <th class="text-center">Wali Kelas</th>
                            <th class="text-center" style="width: 200px;">Total Siswa</th>
                            <th class="text-center" style="width: 150px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; foreach ($class_semester_years as $class_semester_year): ?>
                            <tr href="<?= base_url('admin/classes/') ?>">
                                <td class="text-center"><?= esc("{$class_semester_year['grade_name']} {$class_semester_year['class_code']}") ?></td>
                                <td class="">
                                    <?php if (isset($form_teacher_data[$class_semester_year['id']])) : ?>
                                        <?php foreach ($form_teacher_data[$class_semester_year['id']] as $teacher): ?>
                                            <div class="user-pic">
                                                <img src="<?= base_url($teacher['profile_photo'] ?? 'assets/img/users/default.jpg') ?>" alt="<?= esc($teacher['name']) ?>">
                                                <span><?= esc($teacher['name']) ?></span>
                                            </div>
                                        <?php endforeach ?>
                                    <?php endif ?>
                                </td>
                                <td class="text-center">
                                    <?= count($student_data[$class_semester_year['id']] ?? []) ?>
                                </td>
                                <td class="text-center">
                                    <a href="<?= base_url('admin/classes/academic-year/'.$academic_year['id'].'/class-semester-year/'.$class_semester_year['id'].'/') ?>" class="btn btn-sm btn-primary">Lihat</a>
                                    <a class="btn btn-sm btn-danger btn-delete-user"
                                    data-url="<?= base_url('admin/classes/academic-year/'.$academic_year['id'].'/delete/'.$class_semester_year['id'].'/') ?>">
                                        Hapus
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
                <div class="d-flex justify-content-end mt-3">
                    <a href="<?= base_url('admin/classes/academic-year/'.$academic_year['id'].'/class-semester-year/create/') ?>" class="btn btn-primary">
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
        $('#semesterTable').DataTable({
            "responsive": true,
            "autoWidth": false,
            "searching" : false,
            "lengthChange" : false,
            "paging": false,
            "info" : false
        });

        $('.btn-delete-user').click(function(){
            const url = $(this).data('url');

            Swal.fire({
                title: 'Anda yakin ingin menghapus data ini ?',
                text: "Data yang dihapus tidak bisa dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            });
        })
    });
</script>
<?= $this->endSection() ?>