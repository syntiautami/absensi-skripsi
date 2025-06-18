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
                <a href="<?= base_url('admin/classes/') ?>">Kelas</a>
            </li>
            <li class="breadcrumb-item">
                <a href="<?= base_url('admin/classes/academic-year/'.$academic_year['id'].'/') ?>"><?= esc($academic_year['name']) ?></a>
            </li>
            <li class="breadcrumb-item">
                <a href="<?= base_url('admin/classes/academic-year/'.$academic_year['id'].'/semester/'.$semester['id'].'/class/') ?>">Semester <?= esc($semester['name']) ?></a>
            </li>
            <li class="breadcrumb-item active" aria-current="page"><?= esc("{$class_semester['section_name']} {$class_semester['grade_name']} {$class_semester['name']}") ?></li>
        </ol>
    </nav>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <section class="content">
        <div class="card">
            <?= $this->include('admin/classes/class_semester/components/tabs') ?>
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
                        <?php $no = 1; foreach ($student_class_semesters as $student): ?>
                        <tr href="<?= base_url('admin/classes/') ?>">
                            <td class="text-center"><?= $no++ ?></td>
                            <td class="text-center"><?= esc("{$student['first_name']} {$student['last_name']}") ?></td>
                            <td class="text-center">
                                <input type="text" name="barcode_number[<?= $student['id'] ?>]" 
                                value="<?= esc($student['barcode_number'] ?? '') ?>" 
                                class="form-control" />
                            </td>
                            <td class="text-center">
                                <a href="<?= base_url('admin/classes/academic-year/'.$academic_year['id'].'/semester/'.$semester['id'].'/class/'.$class_semester['id'].'/students/delete/') ?>" class="btn btn-sm btn-danger">Hapus</a>
                            </td>
                        </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
                <div class="d-flex justify-content-end mt-3">
                    <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#modalTambahSiswa">
                        Tambah Siswa
                    </button>
                </div>
            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="modalTambahSiswa" tabindex="-1" role="dialog" aria-labelledby="modalTambahSiswaLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <form method="post" action="<?= base_url('admin/classes/academic-year/'.$academic_year['id'].'/semester/'.$semester['id'].'/class/'.$class_semester['id'].'/students/add/') ?>">
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
                                        <th>NIS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($students as $student): ?>
                                    <tr>
                                        <td><input type="checkbox" name="siswa_ids[]" value="<?= $student['id']; ?>"></td>
                                        <td><?= esc("{$student['first_name']} {$student['last_name']}"); ?></td>
                                        <td><?= esc($student['user_id']); ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
    
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success">Tambah</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>


<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script>
    $(function () {
        $('#studentsTable, #studentsTable-2').DataTable({
            "responsive": true,
            "autoWidth": false,
            "searching" : false,
            "lengthChange" : false,
            "paging": false,
            "info" : false,
            "order": [[ 1, "desc" ]]
        });
        document.getElementById('checkAll').addEventListener('click', function(){
            const checkboxes = document.querySelectorAll('input[name="siswa_ids[]"]');
            checkboxes.forEach((cb) => cb.checked = this.checked);
        });
    });
</script>
<?= $this->endSection() ?>