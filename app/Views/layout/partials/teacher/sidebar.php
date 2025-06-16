<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <a href="<?= base_url('/') ?>" class="brand-link">
    <i class="fas fa-chalkboard-teacher mx-2"></i>
    <span class="brand-text">Teacher Panel</span>
  </a>
  <div class="sidebar">
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column">
        <li class="nav-item">
          <a href="<?= base_url('teacher/dashboard') ?>" class="nav-link">
            <i class="nav-icon fas fa-home"></i>
            <p>Teacher Dashboard</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?= base_url('teacher/materials') ?>" class="nav-link">
            <i class="nav-icon fas fa-book"></i>
            <p>Materials</p>
          </a>
        </li>
      </ul>
    </nav>
  </div>
</aside>