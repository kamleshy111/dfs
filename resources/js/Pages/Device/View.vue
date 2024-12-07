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

    <div class="py-3 row">
        <div class="profile-card col-md-6 col-12">
            <div class="profile-details mt-3 row">
                <div class="col-md-6 col-12">
                <h4 class="mt-2">Device Id: {{ props.devices.device_id }}</h4>
                <p class="mt-2">Order Id: {{ props.devices.order_id }}</p>
                <p class="mt-2">Company Name: {{ props.devices.company_name }}</p>
                <p class="mt-2">Date : {{ props.devices.date_time }}</p>
                <p  class="mt-2"><strong>invoice_photos:</strong><img width="150" class="p-4" :src="props.devices.invoice_photos" alt="Invoice photos"></p>
                </div>
            </div>
        </div>

        <div class="profile-card col-md-6 col-12">
            <div class="profile-details mt-3 row">
                <div class="col-md-6 col-12">
                  <strong>Vehicle Information</strong>
                <h4 class="mt-2">Customer Name: {{ props.devices.customerFirstName }} {{ props.devices.customerLsatName }}</h4>
                
                <p class="mt-2">Vehicle Type : {{ props.devices.vehicleType }}</p>
                <p class="mt-2">Vehicle Name: {{ props.devices.vehicleName }}</p>
                <p class="mt-2">Vehicle Register Number: {{ props.devices.vehicleRegisterNumber }}</p>

                <p class="mt-2">SIM Operator : {{ props.devices.simOperator }}</p>
                <p class="mt-2">SIM Card Number : {{ props.devices.simCardNumber }}</p>
                <p class="mt-2">IMEI Number : {{ props.devices.imeiNumber }}</p>
                
                <p class="mt-2">Installation Date : {{ props.devices.installationDate }}</p>
                <p class="mt-2">Start Date : {{ props.devices.startDate }}</p>
                <p class="mt-2">Duration : {{ props.devices.duration }} {{ props.devices.durationUnit }}</p>
                <p class="mt-2">Expiration Date : {{ props.devices.expirationDate }}</p>
                </div>
            </div>
        </div>
    </div>
  </AuthenticatedLayout>
</template>
