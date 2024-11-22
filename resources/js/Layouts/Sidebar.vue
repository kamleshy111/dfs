<script setup>
import { ref } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link } from '@inertiajs/vue3';
const showingNavigationDropdown = ref(false);
</script>
<template>
      <!-- Sidebar -->
      <nav class="d-none d-md-block sidebar">
          <div class="sidebar-sticky">
              <application-logo></application-logo>
              <ul class="nav flex-column">
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

                  <li class="nav-item">
                      <NavLink
                          :href="route('clients')"
                          :active="route().current('clients')"
                          class="nav-link"
                      >
                          <i class="bi bi-person-fill mr-3"></i>All Clients
                      </NavLink>
                  </li>
                  <li class="nav-item">
                      <NavLink
                          :href="route('devices')"
                          :active="route().current('devices')"
                          class="nav-link"
                      >
                          <i class="bi bi-sd-card-fill mr-3"></i>All Device
                      </NavLink>
                  </li>

                  <!--<li class="nav-item">
                      <a class="nav-link collapsed" data-bs-target="#forms-nav" data-bs-toggle="collapse" aria-expanded="false">
                          <i class="bi bi-sd-card-fill mr-3"></i>
                          <span>All Device</span>
                          <i class="bi bi-chevron-down chevron-icon ml-3"></i>
                      </a>
                      <ul id="forms-nav" class="nav-content collapse list-unstyled" data-bs-parent="#sidebar-nav"
                          style="">
                          <li>
                              <a :href="route('devices')" class="nav-link">
                                  <i class="bi bi-upload mr-3"></i><span>Device</span>
                              </a>vehicle-type
                          </li>
                      </ul>
                  </li>-->

                  <li class="nav-item"><a class="nav-link" href="#"><i
                              class="bi bi-credit-card-fill mr-3"></i>Payments</a></li>
                   <li class="nav-item">
                      <NavLink
                          :href="route('vehicle-type')"
                          :active="route().current('vehicle-type')"
                          class="nav-link"
                      >
                          <i class="bi bi-truck-front-fill mr-3"></i>Vehicle Type
                      </NavLink>
                  </li>
                  <li class="nav-item"><a class="nav-link" href="#"><i
                              class="bi bi-file-earmark-text-fill mr-3"></i>Manage Documents</a></li>
                  <li class="nav-item"><a class="nav-link" href="#"><i class="bi bi-star-fill mr-3"></i>Review &
                          Rating</a></li>
                  <li class="nav-item">
                    <ResponsiveNavLink
                      :href="route('logout')"
                      method="post"
                      as="button"
                      class="nav-link"
                    >
                      <i class="bi bi-box-arrow-left mr-3"></i>
                      Log Out
                    </ResponsiveNavLink>
                  </li>
              </ul>
          </div>
      </nav>
  </template>
