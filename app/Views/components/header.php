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
            <div class="font-weight-bold">SMA IT ALIA TANGERANG</div>
            <div class="text-muted"><?= esc($displayRole) ?></div>
        </div>
    </div>
</header>