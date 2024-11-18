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
            <div class="flex responsive-profile">
               
                <div class="siderbar">
                    <Sidebar />
                </div>


                <!-- Page Content -->
                <main class="right-box">
                    <!-- Page Heading -->
                <header
                    class="text-center w-100"
                    v-if="$slots.header"
                >
                    <div class="text-center ">
                        <slot name="header" />
                    </div>
                </header>
                
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

