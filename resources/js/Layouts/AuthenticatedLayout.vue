<template>
    <div>
        <div class="min-h-screen bg-gray-100">

            <div class="header">
                    <!-- <Header /> -->
                    <Header :role="role" />
                </div>
            <div class="flex">

                <div class="siderbar">
                    <Sidebar :role="role" />
                </div>


                <!-- Page Content -->
                <main class="right-box">
                    {{ header }}
                    <header class="text-center w-100" v-if="$slots.header">
                        <slot name="header" />
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
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3'; // For Inertia.js

export default {
    props: {
        header: {
            type: String,
            required: false, // Make it optional
        },
    },
    components: { Sidebar, Header },
    setup() {
        const { props } = usePage(); // Get props from Inertia
        const role = computed(() => props.auth?.user?.role || 'user'); // Default to user if no role is found
        return { role };
    },
};
</script>

