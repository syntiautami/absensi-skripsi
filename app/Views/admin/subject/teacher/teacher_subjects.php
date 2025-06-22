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
            <a href="<?= base_url('admin/subject/teacher/') ?>">Mata Pelajaran Guru</a>
        </li>
        <li class="breadcrumb-item">
            <a href="<?= base_url('admin/subject/teacher/academic-year/'.$academic_year['id'].'/') ?>"><?= $academic_year['name'] ?></a>
        </li>
        <li class="breadcrumb-item active" aria-current="page"><?= esc("{$class_semester_year['grade_name']} {$class_semester_year['class_code']}") ?></li>
    </ol>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <section class="content">
        <div class="card">
            <form action="" method="post">
                <div class="card-body">
                    <table id="teacherSubjectTable" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 300px;">Nama Guru</th>
                                <?php foreach ($subjects_data as $key => $subject) : ?>
                                    <th class="text-center" style="width: 100px;"><?= $subject['name'] ?></th>
                                <?php endforeach ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; foreach ($teachers as $teacher): ?>
                                <tr>
                                    <td class=""><?= esc("{$teacher['first_name']} {$teacher['last_name']}") ?></td>
                                    
                                    <?php 
                                    $exsist_subjects = $existing_teacher_subjects[$teacher['id']] ?? [];
                                    foreach ($subjects_data as $key => $subject) : ?>
                                        <td class="text-center">
                                            <input type="checkbox" class="input-subject" name="teachers[<?=$teacher['id']?>]" value="<?= $subject['id'] ?>" <?= in_array($subject['id'], $exsist_subjects) ? 'checked' : '' ?> >
                                        </td>
                                    <?php endforeach ?>
                                </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
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
        $('#teacherSubjectTable').DataTable({
            "responsive": true,
            "lengthChange" : false,
            "paging" : false,
            "searching" : false,
            "info" : false,
            ordering: false,
            scrollX: true,
            "fixedHeader" : true,
            "fixedColumns" : {
                "leftColumns" : 1,
            }
        });
    });
</script>
<?= $this->endSection() ?>