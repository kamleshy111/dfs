<script setup>
import { ref } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link } from '@inertiajs/vue3';

const showingNavigationDropdown = ref(false);
</script>
<template>
  <AuthenticatedLayout>
      <!-- Sidebar -->
      <nav class="d-none d-md-block sidebar">
          <div class="sidebar-sticky">
              <h5 class="sidebar-heading"><span><i class="bi bi-car-front-fill logo"></i></span>Driver Fatigue
              </h5>
              <ul class="nav flex-column">
                  <!-- <li class="nav-item"><a class="nav-link active" href="dasboard.html"><i
                              class="bi bi-grid-fill mr-3"></i>Dashboard</a></li> -->
                  <li class="nav-item">
                    <NavLink
                      :href="route('dashboard')"
                      :active="route().current('dashboard')"
                      class="nav-link"
                    >
                      <i class="bi bi-grid-fill mr-3"></i>
                      Dashboard
                    </NavLink>
                  </li>

                  <li class="nav-item"><a class="nav-link" href="all-customers.html"><i class="bi bi-person-fill mr-3"></i>All Clients</a></li>
                  <li class="nav-item">
                      <a class="nav-link collapsed" data-bs-target="#forms-nav" data-bs-toggle="collapse" href="alldevice.html" aria-expanded="false">
                          <i class="bi bi-sd-card-fill mr-3"></i><span>All Device</span><i
                              class="bi bi-chevron-down chevron-icon ml-3"></i>
                      </a>
                      <ul id="forms-nav" class="nav-content collapse list-unstyled" data-bs-parent="#sidebar-nav"
                          style="">
                          <li>
                              <a href="#" class="nav-link">
                                  <i class="bi bi-upload mr-3"></i><span>Content Upload</span>
                              </a>
                          </li>
                          <li>
                              <a href="#" class="nav-link">
                                  <i class="bi bi-file-earmark-bar-graph-fill mr-3"></i><span>Content Management</span>
                              </a>
                          </li>
                          <li>
                              <a href="#" class="nav-link">
                                  <i class="bi bi-gear-fill mr-3"></i><span>Category & Tags</span>
                              </a>
                          </li>
                      </ul>
                  </li>
                  <li class="nav-item"><a class="nav-link" href="#"><i
                              class="bi bi-credit-card-fill mr-3"></i>Payments</a></li>
                  <li class="nav-item"><a class="nav-link" href="#"><i
                              class="bi bi-truck-front-fill mr-3"></i>Vehicle Type</a></li>
                  <li class="nav-item"><a class="nav-link" href="#"><i
                              class="bi bi-file-earmark-text-fill mr-3"></i>Manage Documents</a></li>
                  <li class="nav-item"><a class="nav-link" href="#"><i class="bi bi-star-fill mr-3"></i>Review &
                          Rating</a></li>
                  <li class="nav-item"><a class="nav-link" href="#"><i class="bi bi-box-arrow-left mr-3"></i>Log
                          Out</a></li>
              </ul>
          </div>
      </nav>
    </AuthenticatedLayout>
  </template>
  
  <script>
  import { Link as InertiaLink } from '@inertiajs/vue3';
  
  export default {
    components: {
      InertiaLink,
    },
  };
  </script>
  