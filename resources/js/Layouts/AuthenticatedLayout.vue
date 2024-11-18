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

