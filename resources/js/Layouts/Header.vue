<script setup>
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';

</script>
<template>
    <header class="d-flex justify-content-between align-items-center py-3 mb-4 border-bottom">
    <div class="">
        <h2>Add New Device</h2>
    </div>
    <div class="d-flex align-items-center w-50 justify-content-end">
        <div class="position-relative mr-4 search-desktop">
            <input type="text" class="form-control mr-3 search-header" placeholder="Search here..."
                style="max-width: 300px;">
            <span class="search-icon-header"><i class="bi bi-search"></i></span>
        </div>
        <div class="icon-profile">
            <i class="bi bi-bell mr-3" style="font-size: 1.5rem; cursor: pointer;"></i>
            <div class="dropdown">
            <img src="https://via.placeholder.com/40" alt="Profile" class="rounded-circle" style="cursor: pointer;" data-bs-toggle="dropdown" aria-expanded="true">
            <ul class="dropdown-menu mt-2 p-2" data-popper-placement="bottom-end" style="position: absolute; inset: 0px 0px auto auto; margin: 0px; transform: translate(0px, 42px);">
                <li><a class="dropdown-item" :href="route('profile.edit')">Profile</a></li>
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
        </div>

       <button class="toggle-btn">☰</button>

    </div>
</header>

</template>

<script>
import { Link as InertiaLink } from '@inertiajs/vue3';

export default {
  components: {
    InertiaLink,
  },
};
</script>