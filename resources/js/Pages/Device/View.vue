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
      
<div class="mt-4 mt-4 client-info-card shadow-lg rounded-lg bg-white p-4">
     <h2 class="section-title">Device Information</h2>
        <div class="mb-2">
            <div class="profile-details mt-3 row">
              <div class="w-75">
                <h4 class="mt-2 client-name">Device Id: <span>{{ props.devices.device_id }}</span></h4>
                <p class="mt-2 client-email">Order Id: <span>{{ props.devices.order_id }}</span></p>
                <p class="mt-2 client-email">Company Name: <span>{{ props.devices.company_name }}</span></p>
                <p class="mt-2 client-phone">Date : <span>{{ props.devices.date_time }}</span></p>
              </div>
              <div class="w-25">
                <p  class="mt-2"><strong>Invoice Photos:</strong>
                  <img v-if="props.devices.invoice_photos" width="150px" class="p-4" :src="props.devices.invoice_photos" alt="Invoice photos">
                  <p v-else class="p-4">No invoice photo available</p>
                </p>
              </div>
            </div>
        </div>
</div>

<div class="mt-4 client-info-card shadow-lg rounded-lg bg-white p-4">
        <div class="mr-4">
           <h2 class="section-title">Vehicle Information</h2>
            <div class="profile-details mt-3 d-flex justify-content-between">
              <div>
                <h4 class="mt-2 client-name">Customer Name:  <span>{{ props.devices.customerFirstName }} {{ props.devices.customerLsatName }}</span></h4>
             
                <p class="mt-2 client-name">Vehicle Type :  <span>{{ props.devices.vehicleType }}</span></p>
                <p class="mt-2 client-name">Vehicle Name:  <span>{{ props.devices.vehicleName }}</span></p>
                <p class="mt-2 client-name">Vehicle Register Number:  <span>{{ props.devices.vehicleRegisterNumber }}</span></p>
            
                <p class="mt-2 client-name">SIM Operator :  <span>{{ props.devices.simOperator }}</span></p>
              </div>
              <div>
                <p class="mt-2 client-name">SIM Card Number :  <span>{{ props.devices.simCardNumber }}</span></p>
                <p class="mt-2 client-name">IMEI Number :  <span>{{ props.devices.imeiNumber }}</span></p>
            
                <p class="mt-2 client-name">Installation Date :  <span>{{ props.devices.installationDate }}</span></p>
                <p class="mt-2 client-name">Start Date :  <span>{{ props.devices.startDate }}</span></p>
                <p class="mt-2 client-name">Duration : <span> {{ props.devices.duration }} {{ props.devices.durationUnit }}</span></p>
                <p class="mt-2 client-name">Expiration Date :  <span>{{ props.devices.expirationDate }}</span></p>
              </div>
              
            </div>
        </div>
</div>
    </div>
  </AuthenticatedLayout>
</template>
