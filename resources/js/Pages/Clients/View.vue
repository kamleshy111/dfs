<script setup>
import { defineProps } from 'vue';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";

// Define props to receive the customer data passed from the controller
const props = defineProps({
  customers: Object,
});
</script>

<template>
  <Head title="Client Profile" />

  <AuthenticatedLayout>
    <div class="container py-5">
        <div class="profile-card">
            <div class="text-center">
                <!-- Use the customer's first name for the profile image (or use a placeholder if not available) -->
                <img 
                  :src="props.customers.file ? props.customers.file : 'https://via.placeholder.com/120'" 
                  alt="Profile Image" 
                  class="profile-image"
                />
            </div>
            <div class="profile-details mt-3">
                <!-- Display customer details dynamically from the 'customers' prop -->
                <h4>Name: {{ props.customers.first_name }} {{ props.customers.last_name }}</h4>
                <p>Email: {{ props.customers.email }}</p>
                <p>Quantity: {{ props.customers.quantity }}</p>
                <p>Mobile: {{ props.customers.phone }}</p>

                <p>Devices:</p>
                <ul>
                    <!-- Loop through the devices and display their device IDs -->
                    <li v-for="device in props.customers.devices" :key="device.id">
                        <p>Device ID: {{ device.device_id }}</p>
                        <p>Order ID: {{ device.order_id }}</p>
                        <p>Company Name: {{ device.company_name }}</p><br>
                    </li>
                </ul>
                <p>Address: {{ props.customers.address }}</p>
            </div>
        </div>
    </div>
  </AuthenticatedLayout>
</template>
