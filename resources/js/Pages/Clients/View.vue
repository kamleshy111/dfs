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
        <div class="profile-card col-md-6 col-12">
            <div class="profile-details mt-3 row">
                <div class="col-md-6 col-12">
                <h4 class="mt-2">Name: {{ props.customers.first_name }} {{ props.customers.last_name }}</h4>
                <p class="mt-2">Email: {{ props.customers.email }}</p>
                <p class="mt-2">Quantity: {{ props.customers.quantity }}</p>
                <p class="mt-2">Mobile: {{ props.customers.phone }}</p>
                <p class="mt-2">Address: {{ props.customers.address }}</p>
                </div>
            <div class="col-md-6 col-12">
                <p class="mt-2">Devices:</p>
                <ul class="mt-2">
                    <!-- Loop through the devices and display their device IDs -->
                    <li v-for="device in props.customers.devices" :key="device.id">
                        <p>Device ID: {{ device.device_id }}</p>
                        <p>Order ID: {{ device.order_id }}</p>
                        <p>Company Name: {{ device.company_name }}</p><br>
                    </li>
                </ul>
            </div>
            </div>
        </div>
    </div>
  </AuthenticatedLayout>
</template>
