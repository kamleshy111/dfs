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
    <div>
        <div class="min-h-screen bg-gray-100">

            <div class="header">
                    <Header />
                </div>
            <div class="flex">
               
                <div class="siderbar">
                    <Sidebar />
                </div>

                <!-- Page Heading -->
                <header
                    class="bg-white shadow"
                    v-if="$slots.header"
                >
                    <div class="mx-auto max-w-7xl py-6 ">
                        <slot name="header" />
                    </div>
                </header>

                <!-- Page Content -->
                <main class="right-box">
                    <slot />
                </main>
            </div>
        </div>
    </div>
</template>

<script>
import Sidebar from './Sidebar.vue';
import Header from './Header.vue';

export default {
  components: {
    Sidebar,
    Header,
  },
};
</script>

