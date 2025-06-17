<div class="card-header p-0">
    <ul class="nav nav-tabs" id="custom-tabs" role='tablist'>
        <li class="nav-item">
            <a class="nav-link <?= current_url() == base_url('admin/classes/academic-year/'.$academic_year['id'].'/semester/'.$semester['id'].'/class/'.$class_semester['id'].'/') ? 'active' : '' ?>" href=<?= base_url('admin/classes/academic-year/'.$academic_year['id'].'/semester/'.$semester['id'].'/class/'.$class_semester['id'].'/') ?>>Detail</a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= current_url() == base_url('admin/classes/academic-year/'.$academic_year['id'].'/semester/'.$semester['id'].'/class/'.$class_semester['id'].'/students/') ? 'active' : '' ?>" href=<?= base_url('admin/classes/academic-year/'.$academic_year['id'].'/semester/'.$semester['id'].'/class/'.$class_semester['id'].'/students/') ?>>Siswa</a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= current_url() == base_url('admin/classes/academic-year/'.$academic_year['id'].'/semester/'.$semester['id'].'/class/'.$class_semester['id'].'/class-hour/') ? 'active' : '' ?>" href=<?= base_url('admin/classes/academic-year/'.$academic_year['id'].'/semester/'.$semester['id'].'/class/'.$class_semester['id'].'/class-hour/') ?>>Jam Masuk</a>
        </li>
    </ul>
</div>