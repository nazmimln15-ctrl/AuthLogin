  <aside class="main-sidebar sidebar-dark-primary elevation-4">
      <!-- Brand Logo -->
      <p href="index3.html" class="brand-link">
          <span class="brand-text font-weight-light">{{ config('app.name') }}</span>
      </p>

      <!-- Sidebar -->
      <div class="sidebar">
          <!-- Sidebar user panel (optional) -->
          <div class="user-panel mt-3 pb-3 mb-3 d-flex">
          
              <div class="info">
                  <a href="#" class="d-block">{{ auth()->user()->name }}</a>
              </div>
          </div>

          <!-- SidebarSearch Form -->
          <div class="form-inline">
              <div class="input-group" data-widget="sidebar-search">
                  <input class="form-control form-control-sidebar" type="search" placeholder="Search"
                      aria-label="Search">
                  <div class="input-group-append">
                      <button class="btn btn-sidebar">
                          <i class="fas fa-search fa-fw"></i>
                      </button>
                  </div>
              </div>
          </div>

          <!-- Sidebar Menu -->
          <nav class="mt-2">
              <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                  data-accordion="false">
                  <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
                  @if (auth()->user()->role == 'admin')
                      <li class="nav-item">
                          <a href="/dashboard" class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}">
                              <i class="nav-icon fas fa-tachometer-alt"></i>
                              <p>
                                  Dashboard
                              </p>
                          </a>
                      </li>
                      <li class="nav-item">
                          <a href="/user" class="nav-link" {{ request()->is('user') ? 'active' : '' }}>
                              <i class="nav-icon fas fa-user"></i>
                              <p>
                                  Users
                              </p>
                          </a>
                      </li>
                  @elseif (auth()->user()->role == 'dosen')
                      <li class="nav-item">
                          <a href="/dashboard" class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}">
                              <i class="nav-icon fas fa-tachometer-alt"></i>
                              <p>
                                  Dashboard
                              </p>
                          </a>
                      </li>
                      <li class="nav-item">
                          <a href="/attendance/create" class="nav-link {{ request()->is('attendance/create') ? 'active' : '' }}">
                              <i class="nav-icon fas fa-user"></i>
                              <p>
                                  Generate QR
                              </p>
                          </a>
                      </li>
                      <li class="nav-item">
                          <a href="/absensi" class="nav-link {{ request()->is('absensi') ? 'active' : '' }}">
                              <i class="nav-icon fas fa-user"></i>
                              <!-- tempat dosen melihat matakuliah dan list maasiswa yang telah absen, dosen bisa download file xlsx dari absensi mahasiswa tiap matakuliah -->
                              <p>
                                  Absensi
                              </p>
                          </a>
                      </li>
                  @elseif (auth()->user()->role == 'mahasiswa')
                      <li class="nav-item">
                          <a href="/mahasiswa" class="nav-link {{ request()->is('mahasiswa') ? 'active' : '' }}">
                              <i class="nav-icon fas fa-tachometer-alt"></i>
                              <p>
                                  Dashboard
                              </p>
                          </a>
                      </li>
                      <li class="nav-item">
                          <a href="/scanner" class="nav-link {{ request()->is('scanner') ? 'active' : '' }}">
                              <i class="nav-icon fas fa-user"></i>
                              <p>
                                  Scanner
                              </p>
                          </a>
                      </li>
                  @endif
                  <li class="nav-item">
                      <a href="/logout" class="nav-link">
                          <i class="nav-icon fas fa-power-off"></i>
                          <p>
                              Logout
                          </p>
                      </a>
                  </li>
              </ul>
          </nav>
          <!-- /.sidebar-menu -->
      </div>
      <!-- /.sidebar -->
  </aside>
