  <!-- Sidebar  data-background-color="dark"-->
  <div class="sidebar">
    <div class="sidebar-logo">
        <!-- Logo Header -->
        <div class="logo-header flex flex-col items-center py-4 mt-4">
          @if(auth()->user()->department == 'IT DEPARTMENT')
              <a href="/it_dept" class="logo mb-4">
                  <img src="bootstrap-template/assets/img/rhu-logo.png" alt="RHULogo" style="height:100px; width:auto;">
              </a>
              <p class="text-center text-lg mt-4 text-black font-sans font-semibold">Ehealth Lucban</p>
          @elseif(auth()->user()->department == 'SUPER ADMIN')
          <a href="/super_admin" class="logo mb-4">
              <img src="bootstrap-template/assets/img/rhu-logo.png" alt="RHULogo" style="height:100px; width:auto;">
          </a>
          <p class="text-center text-lg mt-4 text-black font-sans font-semibold">Ehealth Lucban</p>
          @elseif(auth()->user()->department == 'INVENTORY')
          <a href="/inventory" class="logo mb-4">
              <img src="bootstrap-template/assets/img/rhu-logo.png" alt="RHULogo" style="height:100px; width:auto;">
          </a>
          <p class="text-center text-lg mt-4 text-black font-sans font-semibold">Ehealth Lucban</p>
          @elseif(auth()->user()->department == 'LABORATORY')
          <a href="/it_dept" class="logo mb-4">
              <img src="bootstrap-template/assets/img/rhu-logo.png" alt="RHULogo" style="height:100px; width:auto;">
          </a>
          <p class="text-center text-lg mt-4 text-black font-sans font-semibold">Ehealth Lucban</p>
              @elseif(auth()->user()->department == 'CONSULTATION')
              <a href="/consultation" class="logo mb-4">
                  <img src="bootstrap-template/assets/img/rhu-logo.png" alt="RHULogo" style="height:100px; width:auto;">
              </a>
              <p class="text-center text-lg mt-4 text-black font-sans font-semibold">Ehealth Lucban</p>
              @elseif(auth()->user()->department == 'VACCINATION')
              <a href="/vaccination" class="logo mb-4">
                  <img src="bootstrap-template/assets/img/rhu-logo.png" alt="RHULogo" style="height:100px; width:auto;">
              </a>
              <p class="text-center text-lg mt-4 text-black font-sans font-semibold">Ehealth Lucban</p>
              @elseif(auth()->user()->department == 'BLOOD')
              <a href="/it_blood" class="logo mb-4">
                  <img src="bootstrap-template/assets/img/rhu-logo.png" alt="RHULogo" style="height:100px; width:auto;">
              </a>
              <p class="text-center text-lg mt-4 text-black font-sans font-semibold">Ehealth Lucban</p>
          @endif

            <div class="nav-toggle">
                <button class="btn btn-toggle toggle-sidebar">
                    <i class="gg-menu-right"></i>
                </button>
                <button class="btn btn-toggle sidenav-toggler">
                    <i class="gg-menu-left"></i>
                </button>
            </div>
            <button class="topbar-toggler more">
                <i class="gg-more-vertical-alt"></i>
            </button>
        </div>
        <!-- End Logo Header -->
    </div>
    <div class="sidebar-wrapper scrollbar scrollbar-inner" style="padding-top: 50px;">
        <div class="sidebar-content">
            <ul class="nav nav-secondary">

              {{-- for IT Department --}}
              @if(auth()->user()->department == 'IT DEPARTMENT')
                  <li class="nav-item {{ request()->is('/it_dept') ? 'active' : '' }}">
                      <a href="/it_dept">
                          <i class="fas fa-home"></i>
                          <p>Home</p>
                      </a>
                  </li>

                  <li class="nav-item {{ request()->is('registration_verification') ? 'active' : '' }}">
                      <a href="/registration_verification">
                          <i class="fas fa-th-list"></i>
                          <p>Registration Verification</p>
                      </a>
                  </li>

                  <li class="nav-item">
                      <a data-bs-toggle="collapse" href="#dashboard" class="collapsed" aria-expanded="false">
                          <i class="fas fa-th-large"></i>
                          <p>User Management</p>
                          <span class="caret"></span>
                      </a>
                      <div class="collapse" id="dashboard">
                          <ul class="nav nav-collapse">
                              <li>
                                  <a href="/user_account">
                                      <i class="fas fa-th-list"></i>
                                      <p>Patient Account</p>
                                  </a>
                              </li>
                              <li>
                                  <a href="/staff_account">
                                      <i class="fas fa-th-list"></i>
                                      <p>Admin Account</p>
                                  </a>
                              </li>
                          </ul>
                      </div>
                  </li>
                  <li class="nav-item {{ request()->is('logs') ? 'active' : '' }}">
                      <a href="/logs">
                          <i class="fas fa-th-list"></i>
                          <p>Activity Logs</p>
                      </a>
                  </li>

              @endif

              {{-- for Super Admin --}}
              @if(auth()->user()->department == 'SUPER ADMIN')
                  <li class="nav-item {{ request()->is('/super_admin') ? 'active' : '' }}">
                      <a href="/super_admin">
                          <i class="fas fa-home"></i>
                          <p>Home</p>
                      </a>
                  </li>

                  <li class="nav-item {{ request()->is('announcement') ? 'active' : '' }}">
                      <a href="/announcement">
                          <i class="fas fa-th-list"></i>
                          <p>Announcement</p>
                      </a>
                  </li>

                  <li class="nav-item">
                      <a data-bs-toggle="collapse" href="#dashboard" class="collapsed" aria-expanded="false">
                          <i class="fas fa-th-large"></i>
                          <p>Reports</p>
                          <span class="caret"></span>
                      </a>
                      <div class="collapse" id="dashboard">
                          <ul class="nav nav-collapse">
                              <li>
                                  <a href="/super_admin_appointments">
                                      <i class="fas fa-th-list"></i>
                                      <p>Appointments</p>
                                  </a>
                              </li>
                              <li>
                                  <a href="/super_admin_staff">
                                      <i class="fas fa-th-list"></i>
                                      <p>Staff's Management</p>
                                  </a>
                              </li>
                              <li>
                                  <a href="/super_patient">
                                      <i class="fas fa-th-list"></i>
                                      <p>Patient Records</p>
                                  </a>
                              </li>

                          </ul>
                      </div>
                  </li>

              @endif

               {{-- for Inventory --}}
              @if(auth()->user()->department == 'INVENTORY')
                  <li class="nav-item {{ request()->is('/inventory') ? 'active' : '' }}">
                      <a href="/inventory">
                          <i class="fas fa-home"></i>
                          <p>Home</p>
                      </a>
                  </li>

                  <li class="nav-item">
                      <a data-bs-toggle="collapse" href="#dashboard" class="collapsed" aria-expanded="false">
                          <i class="fas fa-th-large"></i>
                          <p>RHU Supplies</p>
                          <span class="caret"></span>
                      </a>
                      <div class="collapse" id="dashboard">
                          <ul class="nav nav-collapse">
                              <li>
                                  <a href="/medicines">
                                      <i class="fas fa-th-list"></i>
                                      <p>Medicines</p>
                                  </a>
                              </li>
                              <li>
                                  <a href="/medical_supplies">
                                      <i class="fas fa-th-list"></i>
                                      <p>Medical Supplies</p>
                                  </a>
                              </li>
                              <li>
                                  <a href="/medical_equipments">
                                      <i class="fas fa-th-list"></i>
                                      <p>Medical Equipments</p>
                                  </a>
                              </li>
                              <li>
                                  <a href="/vaccines">
                                      <i class="fas fa-th-list"></i>
                                      <p>Vaccines</p>
                                  </a>
                              </li>

                          </ul>
                      </div>
                  </li>

                  <li class="nav-item {{ request()->is('/inventory_staff_management') ? 'active' : '' }}">
                      <a href="/inventory_staff_management">
                          <i class="fas fa-home"></i>
                          <p>Staff Management</p>
                      </a>
                  </li>

                  <li class="nav-item {{ request()->is('inventory_reports') ? 'active' : '' }}">
                      <a href="/inventory_reports">
                          <i class="fas fa-th-list"></i>
                          <p>Reports</p>
                      </a>
                  </li>
              @endif

              {{-- for laboratory --}}
              @if(auth()->user()->department == 'LABORATORY')
                  <li class="nav-item {{ request()->is('/laboratory') ? 'active' : '' }}">
                      <a href="/laboratory">
                          <i class="fas fa-home"></i>
                          <p>Home</p>
                      </a>
                  </li>

                  <li class="nav-item {{ request()->is('display-lab_appointments') ? 'active' : '' }}">
                      <a href="/display-lab_appointments">
                          <i class="fas fa-th-list"></i>
                          <p>Appointments</p>
                      </a>
                  </li>
                  <li class="nav-item {{ request()->is('lab_staff_management') ? 'active' : '' }}">
                      <a href="/lab_staff_management">
                          <i class="fas fa-th-list"></i>
                          <p>Staff Management</p>
                      </a>
                  </li>
                  <li class="nav-item {{ request()->is('lab_tests') ? 'active' : '' }}">
                      <a href="/lab_tests">
                          <i class="fas fa-th-list"></i>
                          <p>Test List</p>
                      </a>
                  </li>
                  <li class="nav-item {{ request()->is('lab_patient_records') ? 'active' : '' }}">
                      <a href="/lab_patient_records">
                          <i class="fas fa-th-list"></i>
                          <p>Patient Records</p>
                      </a>
                  </li>
                  <li class="nav-item {{ request()->is('lab_report') ? 'active' : '' }}">
                      <a href="/lab_report">
                          <i class="fas fa-th-list"></i>
                          <p>Reports</p>
                      </a>
                  </li>
              @endif

              {{-- for consultation --}}
              @if(auth()->user()->department == 'CONSULTATION')
                  <li class="nav-item {{ request()->is('/consultation') ? 'active' : '' }}">
                      <a href="/consultation">
                          <i class="fas fa-home"></i>
                          <p>Home</p>
                      </a>
                  </li>

                  <li class="nav-item {{ request()->is('display-con_appointments') ? 'active' : '' }}">
                      <a href="/display-con_appointments">
                          <i class="fas fa-th-list"></i>
                          <p>Appointments</p>
                      </a>
                  </li>
                  <li class="nav-item {{ request()->is('con_staff_management') ? 'active' : '' }}">
                      <a href="/con_staff_management">
                          <i class="fas fa-th-list"></i>
                          <p>Staff Management</p>
                      </a>
                  </li>
                  <li class="nav-item {{ request()->is('con_patient_records') ? 'active' : '' }}">
                      <a href="/con_patient_records">
                          <i class="fas fa-th-list"></i>
                          <p>Patient Records</p>
                      </a>
                  </li>
                  <li class="nav-item {{ request()->is('con_report') ? 'active' : '' }}">
                      <a href="/con_report">
                          <i class="fas fa-th-list"></i>
                          <p>Reports</p>
                      </a>
                  </li>
              @endif

              {{-- for vaccination --}}
              @if(auth()->user()->department == 'VACCINATION')
                  <li class="nav-item {{ request()->is('/vaccination') ? 'active' : '' }}">
                      <a href="/vaccination">
                          <i class="fas fa-home"></i>
                          <p>Home</p>
                      </a>
                  </li>

                  <li class="nav-item {{ request()->is('display-vax_appointments') ? 'active' : '' }}">
                      <a href="/display-vax_appointments">
                          <i class="fas fa-th-list"></i>
                          <p>Appointments</p>
                      </a>
                  </li>
                  <li class="nav-item {{ request()->is('vax_staff_management') ? 'active' : '' }}">
                      <a href="/vax_staff_management">
                          <i class="fas fa-th-list"></i>
                          <p>Staff Management</p>
                      </a>
                  </li>
                  <li class="nav-item {{ request()->is('vax_patient_records') ? 'active' : '' }}">
                      <a href="/vax_patient_records">
                          <i class="fas fa-th-list"></i>
                          <p>Patient Records</p>
                      </a>
                  </li>
                  <li class="nav-item {{ request()->is('vax_report') ? 'active' : '' }}">
                      <a href="/vax_report">
                          <i class="fas fa-th-list"></i>
                          <p>Reports</p>
                      </a>
                  </li>
              @endif

               {{-- for blood --}}
               @if(auth()->user()->department == 'BLOOD')
                  <li class="nav-item {{ request()->is('/blood') ? 'active' : '' }}">
                      <a href="/blood">
                          <i class="fas fa-home"></i>
                          <p>Home</p>
                      </a>
                  </li>

                  <li class="nav-item {{ request()->is('display-blood_appointments') ? 'active' : '' }}">
                      <a href="/display-blood_appointments">
                          <i class="fas fa-th-list"></i>
                          <p>Appointments</p>
                      </a>
                  </li>
                  <li class="nav-item {{ request()->is('blood_staff_management') ? 'active' : '' }}">
                      <a href="/blood_staff_management">
                          <i class="fas fa-th-list"></i>
                          <p>Staff Management</p>
                      </a>
                  </li>
                  <li class="nav-item {{ request()->is('blood_patient_records') ? 'active' : '' }}">
                      <a href="/blood_patient_records">
                          <i class="fas fa-th-list"></i>
                          <p>Donor Management</p>
                      </a>
                  </li>
                  <li class="nav-item {{ request()->is('turned_overs') ? 'active' : '' }}">
                      <a href="/turned_overs">
                          <i class="fas fa-th-list"></i>
                          <p>Turned Over</p>
                      </a>
                  </li>
                  <li class="nav-item {{ request()->is('blood_reports') ? 'active' : '' }}">
                      <a href="/blood_reports">
                          <i class="fas fa-th-list"></i>
                          <p>Reports</p>
                      </a>
                  </li>
              @endif

            </ul>
        </div>
    </div>
</div>
<!-- End Sidebar -->
