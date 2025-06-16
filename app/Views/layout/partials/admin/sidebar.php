<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <a href="<?= base_url('/') ?>" class="brand-link">
    <i class="fas fa-user-shield mx-2"></i>
    <span class="brand-text">Admin Panel</span>
  </a>
  <div class="sidebar">
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column">
        <li class="nav-item">
          <a href="<?= base_url('admin/dashboard') ?>" class="nav-link">
            <i class="nav-icon fas fa-home"></i>
            <p>Admin Dashboard</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?= base_url('admin/users') ?>" class="nav-link">
            <i class="nav-icon fas fa-users"></i>
            <p>Users</p>
          </a>
        </li>
      </ul>
    </nav>
  </div>
</aside>