<script setup>
import { ref, onMounted } from "vue";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import NavLink from "@/Components/NavLink.vue";
import ResponsiveNavLink from "@/Components/ResponsiveNavLink.vue";
import { Link } from "@inertiajs/vue3";
import axios from "axios";

// Props
defineProps({
  role: {
    type: String,
    required: true,
  },
});

const showingNavigationDropdown = ref(false);

$(document).ready(function() {
      $('.sidebar .nav-link').click(function() {
        $('.sidebar .nav-link').removeClass('active');
        $(this).addClass('active');
      });
    });

</script>
<template>
  <!-- Sidebar -->
  <div>
    <nav class="sidebar">
      <div class="sidebar-sticky">
        <application-logo></application-logo>
        <ul class="nav flex-column">
          <!-- Admin Sidebar -->
          <template v-if="role === 'admin'">
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
            <li class="nav-item">
              <NavLink
                :href="route('vehicle-type')"
                :active="route().current('vehicle-type')"
                class="nav-link"
              >
                <i class="bi bi-truck-front-fill mr-3"></i>Vehicle Type
              </NavLink>
            </li>

            <li class="nav-item">
              <NavLink
                :href="route('all-billing')"
                :active="route().current('all-billing')"
                class="nav-link"
              >
                <i class="bi bi-credit-card-fill mr-3"></i>Billing
              </NavLink>
            </li>

            <li class="nav-item">
              <NavLink
                :href="route('report')"
                :active="route().current('report')"
                class="nav-link"
              >
                <i class="bi bi-credit-card-fill mr-3"></i>Report
              </NavLink>
            </li>
            <!-- <li class="nav-item"><a class="nav-link" href="#"><i
                                    class="bi bi-credit-card-fill mr-3"></i>Payments</a>
                        </li>
                        <li class="nav-item"><a class="nav-link" href="#"><i
                                    class="bi bi-file-earmark-text-fill mr-3"></i>Manage Documents</a>
                        </li>
                        <li class="nav-item"><a class="nav-link" href="#"><i class="bi bi-star-fill mr-3"></i>Review &
                                Rating</a>
                        </li> -->

          </template>

          <!-- User Sidebar -->
          <template v-else>
            <li class="nav-item">
              <NavLink
                :href="route('dashboard')"
                :active="route().current('dashboard')"
                class="nav-link"
              >
                <i class="bi bi-grid-fill mr-3"></i>Dashboard
              </NavLink>
            </li>
            <!-- <li class="nav-item">
              <NavLink
                :href="route('monitor', 1)"
                :active="route().current('monitor')"
                class="nav-link"
              >
                <i class="bi bi-truck-front-fill mr-3"></i>Devices
              </NavLink>
            </li> -->

            <li class="nav-item">
              <NavLink
                :href="route('billing')"
                :active="route().current('billing')"
                class="nav-link"
              >
                <i class="bi bi-credit-card-fill mr-3"></i>Billing
              </NavLink>
            </li>

            <li class="nav-item">
              <NavLink
                :href="route('report')"
                :active="route().current('report')"
                class="nav-link"
              >
                <i class="bi bi-credit-card-fill mr-3"></i>Report
              </NavLink>
            </li>
          </template>
          <li class="nav-item">
              <ResponsiveNavLink
                :href="route('logout')"
                method="post"
                as="button"
                class="nav-link"
              >
                <i class="bi bi-box-arrow-left mr-3"></i>Log Out
              </ResponsiveNavLink>
            </li>
        </ul>
      </div>
    </nav>
  </div>
</template>
