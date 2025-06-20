<div class="card-header p-0">
    <ul class="nav nav-tabs" id="custom-tabs" role='tablist'>
        <li class="nav-item">
            <a class="nav-link <?= current_url() == base_url('admin/users/'.$role['id'].'/edit/'.$user['id'].'/user/') ? 'active' : '' ?>" href=<?= base_url('admin/users/'.$role['id'].'/edit/'.$user['id'].'/user/') ?>>Akun</a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= current_url() == base_url('admin/users/'.$role['id'].'/edit/'.$user['id'].'/profile/') ? 'active' : '' ?>" href=<?= base_url('admin/users/'.$role['id'].'/edit/'.$user['id'].'/profile/') ?>>Profil</a>
        </li>
        <?php if($role['id'] == 1 || $role['id'] == 4) : ?>
            <li class="nav-item">
                <a class="nav-link <?= current_url() == base_url('admin/users/'.$role['id'].'/edit/'.$user['id'].'/additional/') ? 'active' : '' ?>" href=<?= base_url('admin/users/'.$role['id'].'/edit/'.$user['id'].'/additional/') ?>>Data Tambahan <?= $role['alt_name'] ?></a>
            </li>
        <?php endif ?>
    </ul>
</div>