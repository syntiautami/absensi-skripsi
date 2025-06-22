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
            <a href="<?= base_url('admin/subject/class/') ?>">Mata Pelajaran Kelas</a>
        </li>
        <li class="breadcrumb-item">
            <a href="<?= base_url('admin/subject/class/academic-year/'.$academic_year['id'].'/') ?>"><?= $academic_year['name'] ?></a>
        </li>
        <li class="breadcrumb-item active" aria-current="page"><?= esc("{$class_semester['grade_name']} {$class_semester['class_code']}") ?></li>
    </ol>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <section class="content">
        <div class="card">
            <form action="" method="post">
                <div class="card-body">
                    <table id="subjectsTable" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th class="text-center">Mata Pelajaran</th>
                                <th class="text-center" style="width: 150px;">
                                    Aksi <br><br>
                                    <input type="checkbox" id="select-all">
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; foreach ($subjects as $key => $subject): ?>
                                <tr>
                                    <td class="text-center"><?= esc($subject['name']) ?></td>
                                    <td class="text-center">
                                        <input type="checkbox" class="input-subject" name="subjects[]" value="<?= $subject['id'] ?>" <?= in_array($subject['id'], $existing_subjects) ? 'checked' : '' ?> >
                                    </td>
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
        $('#subjectsTable').DataTable({
            "responsive": true,
            "autoWidth": false,
            "searching" : false,
            "lengthChange" : false,
            "paging": false,
            "info" : false,
            ordering: false
        });

        $('#select-all').change(function(e){
            const selectValue = $(this).is(':checked');
            $('input.input-subject').each(function(){
                $(this).prop('checked', selectValue);
            })
        });
        document.getElementById('select-all').addEventListener('change', function() {
            let checkboxes = document.querySelectorAll('.row-checkbox');
            $thi
            for (let checkbox of checkboxes) {
                checkbox.checked = this.checked;
            }
        });
    });
</script>
<?= $this->endSection() ?>