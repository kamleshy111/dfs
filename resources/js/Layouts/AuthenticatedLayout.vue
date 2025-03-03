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
                    <!-- {{ header }} -->
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
import { computed, ref, onMounted } from 'vue';
import { usePage } from '@inertiajs/vue3';

export default {
    props: {
        header: {
            type: String,
            required: false,
        },
    },
    components: { Sidebar, Header },
    setup() {
        const { props } = usePage();
        const role = computed(() => props.auth?.user?.role || 'user');

        // Sidebar toggle logic
        const siderbar = ref(null);
        const toggleSidebar = () => {
            if (siderbar.value) {
                siderbar.value.classList.toggle('active');
            }
        };

        onMounted(() => {
            // Get sidebar element after component is mounted
            siderbar.value = document.querySelector('.siderbar');
            const toggleBtn = document.querySelector('.toggle-btn');
            
            if (toggleBtn) {
                toggleBtn.addEventListener('click', toggleSidebar);
            }
        });

        return { role };
    },
};
</script>

