<template>
    <header class="d-flex justify-content-between align-items-center py-3 mb-4 border-bottom">
    <div class="">
        <h2>Add New Device</h2>
    </div>
    <div class="d-flex align-items-center w-50 justify-content-end">
        <div class="position-relative mr-4">
            <input type="text" class="form-control mr-3" placeholder="Search here..."
                style="max-width: 300px;">
            <span class="search-icon-header"><i class="bi bi-search"></i></span>
        </div>
        <div class="icon-profile">
            <i class="bi bi-bell mr-3" style="font-size: 1.5rem; cursor: pointer;"></i>
            <img src="https://via.placeholder.com/40" alt="Profile" class="rounded"
                style="cursor: pointer;">
        </div>
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