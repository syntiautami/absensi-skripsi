<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Brand Logo -->
  <a href="<?= base_url('/admin/') ?>" class="brand-link">
    <img src="<?= base_url('assets/img/logo.png') ?>" 
         alt="Logo" 
         class="brand-image img-circle elevation-3"
         style="opacity: .8">
    <span class="brand-text font-weight-light">Sistem Absensi</span>
  </a>

  <!-- Sidebar -->
  <div class="sidebar">
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
        <li class="nav-item">
          <a href="<?= base_url('admin/user/') ?>" class="nav-link">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>User</p>
          </a>
        </li>
      </ul>
    </nav>
  </div>
</aside>
