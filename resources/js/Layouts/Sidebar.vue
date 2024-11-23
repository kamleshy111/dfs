<script setup>
import { ref } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link } from '@inertiajs/vue3';

// Props
defineProps({
  role: {
    type: String,
    required: true,
  },
});

// State
const showingNavigationDropdown = ref(false);
</script>
<template>
    <!-- Sidebar -->
    <div>
        <nav class="d-none d-md-block sidebar">
            <div class="sidebar-sticky">
                <application-logo></application-logo>
                <ul class="nav flex-column">
                    <!-- Admin Sidebar -->
                    <template v-if="role === 'admin'">
                        <li class="nav-item">
                            <NavLink :href="route('dashboard')" :active="route().current('dashboard')" class="nav-link">
                                <i class="bi bi-grid-fill mr-3"></i>
                                Dashboard
                            </NavLink>
                        </li>
                        <li class="nav-item">
                            <NavLink :href="route('clients')" :active="route().current('clients')" class="nav-link">
                                <i class="bi bi-person-fill mr-3"></i>All Clients
                            </NavLink>
                        </li>
                        <li class="nav-item">
                            <NavLink :href="route('devices')" :active="route().current('devices')" class="nav-link">
                                <i class="bi bi-sd-card-fill mr-3"></i>All Device
                            </NavLink>
                        </li>
                        <li class="nav-item"><a class="nav-link" href="#"><i class="bi bi-credit-card-fill mr-3"></i>Payments</a>
                        </li>
                        <li class="nav-item">
                            <NavLink :href="route('vehicle-type')" :active="route().current('vehicle-type')"
                                class="nav-link">
                                <i class="bi bi-truck-front-fill mr-3"></i>Vehicle Type
                            </NavLink>
                        </li>
                        <li class="nav-item"><a class="nav-link" href="#"><i class="bi bi-file-earmark-text-fill mr-3"></i>Manage Documents</a>
                        </li>
                        <li class="nav-item"><a class="nav-link" href="#"><i class="bi bi-star-fill mr-3"></i>Review & Rating</a>
                        </li>
                        <li class="nav-item">
                            <ResponsiveNavLink :href="route('logout')" method="post" as="button" class="nav-link">
                                <i class="bi bi-box-arrow-left mr-3"></i>
                                Log Out
                            </ResponsiveNavLink>
                        </li>
                    </template>

                    <!-- User Sidebar -->
                    <template v-else>
                        <li class="nav-item">
                            <NavLink :href="route('dashboard')" :active="route().current('dashboard')" class="nav-link">
                                <i class="bi bi-grid-fill mr-3"></i>Dashboard
                            </NavLink>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link collapsed" data-bs-toggle="collapse" data-bs-target="#devices-nav" href="#">
                                <i class="bi bi-truck-front-fill mr-3"></i><span>Devices</span>
                                <i class="bi bi-chevron-down chevron-icon ml-3"></i>
                            </a>
                            <ul id="devices-nav" class="nav-content collapse list-unstyled">
                                <li><a href="#" class="nav-link">Devices ID:12345</a></li>
                                <li><a href="#" class="nav-link">Devices ID:54321</a></li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#"><i class="bi bi-person-circle mr-3"></i>Monitor</a>
                        </li>
                        <li class="nav-item">
                            <NavLink :href="route('billing')" :active="route().current('billing')" class="nav-link">
                                <i class="bi bi-credit-card-fill mr-3"></i>Billing
                            </NavLink>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#"><i class="bi bi-pie-chart-fill mr-3"></i>Report</a>
                        </li>
                        <li class="nav-item">
                            <ResponsiveNavLink :href="route('logout')" method="post" as="button" class="nav-link">
                                <i class="bi bi-box-arrow-left mr-3"></i>Log Out
                            </ResponsiveNavLink>
                        </li>
                    </template>
                </ul>
            </div>
        </nav>
    </div>
</template>
