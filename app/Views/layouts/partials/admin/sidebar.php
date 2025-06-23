<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Brand Logo -->
  <a href="<?= base_url('/admin/') ?>" class="brand-link">
    <img src="<?= base_url('assets/img/logo.png') ?>" 
         alt="Logo" 
         class="brand-image img-circle elevation-3"
         style="opacity: .8">
    <span class="brand-text">Sistem Absensi</span>
  </a>

  <!-- Sidebar -->
  <div class="sidebar">
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
        <li class="nav-item">
          <a href="<?= base_url('admin/') ?>" class="nav-link <?= ($viewing ?? '') === 'dashboard' ? 'active' : '' ?>">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>Beranda</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?= base_url('admin/users/') ?>" class="nav-link <?= ($viewing ?? '') === 'user' ? 'active' : '' ?>">
            <i class="nav-icon fas fa-solid fa-user"></i>
            <p>Pengguna</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?= base_url('admin/academic-year/') ?>" class="nav-link <?= ($viewing ?? '') === 'academic-year' ? 'active' : '' ?>">
            <i class="nav-icon fas fa-solid fa-calendar"></i>
            <p>Tahun Ajaran</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?= base_url('admin/classes/') ?>" class="nav-link <?= ($viewing ?? '') === 'classes' ? 'active' : '' ?>">
            <i class="nav-icon fas fa-users"></i>
            <p>Kelas</p>
          </a>
        </li>
        <!-- Mata Pelajaran -->
        <li class="nav-item has-treeview <?= in_array($viewing ?? '', ['subject', 'teacher-subject','class-subject']) ? 'menu-open' : '' ?>">
          <a href="#" class="nav-link <?= in_array($viewing ?? '', ['subject', 'teacher-subject','class-subject']) ? 'active' : '' ?>">
            <i class="nav-icon fas fa-book"></i>
            <p>
              Mata Pelajaran
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="<?= base_url('admin/subject/') ?>" class="nav-link <?= ($viewing ?? '') === 'subject' ? 'active' : '' ?>">
                <p>Mata Pelajaran</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?= base_url('admin/subject/teacher/') ?>" class="nav-link <?= ($viewing ?? '') === 'teacher-subject' ? 'active' : '' ?>">
                <p>Mata Pelajaran Guru</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?= base_url('admin/subject/class/') ?>" class="nav-link <?= ($viewing ?? '') === 'class-subject' ? 'active' : '' ?>">
                <p>Mata Pelajaran Kelas</p>
              </a>
            </li>
          </ul>
        </li>
        <!-- Laporan -->
        <li class="nav-item has-treeview <?= in_array($viewing ?? '', ['report', 'report-subject']) ? 'menu-open' : '' ?>">
          <a href="#" class="nav-link <?= in_array($viewing ?? '', ['report', 'report-subject']) ? 'active' : '' ?>">
            <i class="nav-icon fas fa-file-alt"></i>
            <p>
              Laporan
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="<?= base_url('admin/report/attendance/') ?>" class="nav-link <?= ($viewing ?? '') === 'report' ? 'active' : '' ?>">
                <p>Absensi</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?= base_url('admin/report/attendance/subject/') ?>" class="nav-link <?= ($viewing ?? '') === 'report-subject' ? 'active' : '' ?>">
                <p>Absensi Mata Pelajaran</p>
              </a>
            </li>
          </ul>
        </li>
      </ul>
    </nav>
  </div>
</aside>
