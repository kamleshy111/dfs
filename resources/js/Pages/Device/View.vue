<script setup>
import { defineProps } from 'vue';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";

// Define props to receive the customer data passed from the controller
const props = defineProps({
  devices: Object,
});

// go to back
const goBack = () => {
  window.location.href = "/devices";
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
                <h4 class="mt-2">Device Id: {{ props.devices.device_id }}</h4>
                <p class="mt-2">Order Id: {{ props.devices.order_id }}</p>
                <p class="mt-2">Company Name: {{ props.devices.company_name }}</p>
                <p class="mt-2">Date : {{ props.devices.date_time }}</p>
                </div>
            </div>
        </div>
    </div>
  </AuthenticatedLayout>
</template>
