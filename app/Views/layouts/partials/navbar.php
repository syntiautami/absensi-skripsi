<nav class="main-header navbar navbar-expand navbar-white navbar-light">
  <!-- Left navbar links -->
  <ul class="navbar-nav">
    <!-- Select Role -->
    <li class="nav-item d-flex align-items-center">
      <?php
      $currentRole = session('role') ?? '';
      $roles = session('roles') ?? []
      ?>

      <?php if (count($roles) > 1): ?>
        <select class="form-control form-control-sm ml-2" style="min-width: 120px;" onchange="location.href='<?= base_url('role/') ?>' + this.value;">
          <?php foreach ($roles as $role): ?>
            <option value="<?= $role ?>" <?= $currentRole == $role ? 'selected' : '' ?>>
              <?= ucfirst($role) ?>
            </option>
          <?php endforeach; ?>
        </select>
      <?php endif; ?>
    </li>
  </ul>
  <!-- Right navbar links -->
  <ul class="navbar-nav ml-auto mr-3">
    <li class="nav-item dropdown">
      <a class="nav-link d-flex align-items-center" data-toggle="dropdown" href="#">
        <img src="<?= base_url('assets/users/' . (session('user')['photo'] ?? 'default.jpg')) ?>"
             alt="User Image"
             class="img-circle elevation-2"
             style="width: 40px; height: 40px; object-fit: cover; margin-right: 8px;">
        <span class="d-none d-md-inline font-weight-bold">
          <?= esc(session('user')['name'] ?? 'User') ?>
        </span>
        <i class="fas fa-caret-down ml-1"></i>
      </a>
      <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
        <a href="<?= base_url('settings') ?>" class="dropdown-item">
          <i class="fas fa-cog mr-2"></i> Settings
        </a>
        <div class="dropdown-divider"></div>
        <a href="<?= base_url('logout/') ?>" class="dropdown-item">
          <i class="fas fa-sign-out-alt mr-2"></i> Logout
        </a>
      </div>
    </li>
  </ul>
</nav>
