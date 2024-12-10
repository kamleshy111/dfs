<script setup>
import { defineProps } from 'vue';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";

// Define props to receive the customer data passed from the controller
const props = defineProps({
  customers: Object,
});

// go to back
const goBack = () => {
  window.location.href = "/clients"; // Redirect to the desired Laravel route
};
</script>

<template>
  <Head title="Client Profile" />

  <AuthenticatedLayout>

    <div class="back-section"><button type="button" class="back-btn-custom" @click="goBack"><i class="bi bi-caret-left"></i> Back</button></div>

    <div class="py-3">
      <h2 class="fs-4 mb-2 fw-bold text-[#2239c3cc]">Client Information</h2>
        <div class="profile-card  profile-device">
            <div class="profile-details mt-3 d-flex justify-content-between">
                <div class="w-2/3	">
                <h4 class="mt-3">Name: {{ props.customers.first_name }} {{ props.customers.last_name }}</h4>
                <p class="mt-3">Primary Email: {{ props.customers.email }}</p>
                <p class="mt-3">Secondary Email: {{ props.customers.secondary_email }}</p>
                <p class="mt-3">Primary Mobile: {{ props.customers.phone }}</p>
              <p class="mt-3">Address: {{ props.customers.address }}</p>

                
                </div>
            <div class="w-1/3	">
              <p class="mt-3">Secondary Mobile: {{ props.customers.secondary_phone }}</p>

                <p class="mt-2">Devices:</p>
                <ul class="mt-3">
                    <!-- Loop through the devices and display their device IDs -->
                    
                        <p class="mt-3">Active : {{ props.customers.activeCount }}</p>
                        <p class="mt-3">InActive : {{ props.customers.inActiveCount }}</p>
                        <p class="mt-3">Expired : {{ props.customers.expiredCount }}</p><br>
    
                </ul>
            </div>
            </div>
        </div>
    </div>
  </AuthenticatedLayout>
</template>
