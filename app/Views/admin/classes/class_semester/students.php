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
            <a href="<?= base_url('admin/classes/academic-year/'.$academic_year['id'].'/class_semester_year_id/'.$class_semester_year['id'].'/') ?>"><?= esc("{$class_semester_year['grade_name']} {$class_semester_year['class_code']}") ?></a>
        </li>
        <li class="breadcrumb-item active" aria-current="page">Siswa</li>
    </ol>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <section class="content">
        <div class="card">
            <?= $this->include('admin/classes/class_semester/components/tabs') ?>
            <form action="" method="post">
                <div class="card-body">
                    <table id="studentsTable" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 30px;">No</th>
                                <th class="text-center">Nama</th>
                                <th class="text-center" style="width: 200px;">Nomor Barcode</th>
                                <th class="text-center" style="width: 200px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; foreach ($student_data as $key => $student): ?>
                            <tr href="<?= base_url('admin/classes/') ?>">
                                <td class="text-center"><?= $no++ ?></td>
                                <td class=""><?= esc($student['name']) ?></td>
                                <td class="text-center">
                                    <input type="text" name="barcode_number[<?= $student['profile_id'] ?>]" 
                                    value="<?= esc($student['barcode_number'] ?? '') ?>"
                                    class="form-control" />
                                </td>
                                <td class="text-center">
                                    <a href="<?= base_url('admin/classes/academic-year/'.$academic_year['id'].'/class_semester_year/'.$class_semester_year['id'].'/students/'.$key.'/delete/') ?>" class="btn btn-sm btn-danger">Hapus</a>
                                </td>
                            </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-end mt-3">
                        <a class="btn btn-primary mr-3" data-toggle="modal" data-target="#modalTambahSiswa">Tambah Siswa</a>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
                <!-- Modal -->
                <div class="modal fade" id="modalTambahSiswa" tabindex="-1" role="dialog" aria-labelledby="modalTambahSiswaLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalTambahSiswaLabel">Pilih Siswa</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <table id="studentsTable-2" class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th style="width: 30px;"><input type="checkbox" id="checkAll"></th>
                                            <th>Nama Siswa</th>
                                            <th>Nomor Barcode</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($students as $student): ?>
                                        <tr>
                                            <td><input type="checkbox" name="students[]" value="<?= $student['id']; ?>"></td>
                                            <td><?= esc("{$student['first_name']} {$student['last_name']}"); ?></td>
                                            <td><?= esc($student['barcode_number']); ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
        
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Tambah</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>


<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script>
    $(function () {
        $('#studentsTable').DataTable({
            "responsive": true,
            "autoWidth": false,
            "searching" : false,
            "lengthChange" : false,
            "paging": false,
            "info" : false,
        });
        $('#studentsTable-2').DataTable({
            "responsive": true,
            "autoWidth": false,
            "lengthChange" : false,
            "paging": false,
            "info" : false,
        });
        document.getElementById('checkAll').addEventListener('click', function(){
            const checkboxes = document.querySelectorAll('input[name="siswa_ids[]"]');
            checkboxes.forEach((cb) => cb.checked = this.checked);
        });
    });
</script>
<?= $this->endSection() ?>