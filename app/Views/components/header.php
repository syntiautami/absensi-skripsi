<?php
    $roleMap = [
        'teacher' => 'Guru',
        'admin' => 'Admin',
    ];

    $userRole = session()->get('role');
    $displayRole = isset($roleMap[$userRole]) ? $roleMap[$userRole] : ucfirst($userRole);
?>
<header class="navbar navbar-expand navbar-white navbar-light">
    <div class="d-flex align-items-center p-2">
        <div class="mr-3">
            <img src="<?= base_url('assets/img/logo.png') ?>" alt="Logo Sekolah" style="width: 50px; height: 50px; object-fit: cover;">
        </div>
        <div>
            <div class="font-weight-bold">
                <?php if ($userRole == 'teacher') : ?>
                    <?= session()->get('user')['first_name'].' '.session()->get('user')['last_name'] ?>
                <?php else : ?>
                    SMA IT ALIA TANGERANG
                <?php endif ?>
            </div>
            <div class="text-muted">
                <?= esc($displayRole) ?>
                <?php if (session()->get('homeroom_teacher') && $userRole == 'teacher') : ?>
                    (Wali Kelas <?= esc(session()->get('homeroom_teacher')['grade_name'])  ?> <?= esc(session()->get('homeroom_teacher')['class_code'])  ?>)
                <?php endif ?>
            </div>
        </div>
    </div>
</header>