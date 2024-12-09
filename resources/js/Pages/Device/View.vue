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
      
<div class="mt-4">
     <h2 class="fs-4 mb-1 fw-bold  text-[#2239c3cc]">Device Information</h2>
        <div class="profile-card profile-device mb-2">
            <div class="profile-details mt-3 row">
              <div class="w-75">
                <h4 class="mt-2">Device Id: {{ props.devices.device_id }}</h4>
                <p class="mt-2">Order Id: {{ props.devices.order_id }}</p>
                <p class="mt-2">Company Name: {{ props.devices.company_name }}</p>
                <p class="mt-2">Date : {{ props.devices.date_time }}</p>
              </div>
              <div class="w-25">
                <p  class="mt-2"><strong>Invoice Photos:</strong><img width="150px" class="p-4" :src="props.devices.invoice_photos" alt="Invoice photos"></p>
              </div>
            </div>
        </div>
</div>

<div class="mt-4">
     <h2 class="fs-4 mb-1 fw-bold  text-[#2239c3cc]">Vehicle Information</h2>
        <div class="profile-card  profile-device mr-4 mt-2">
            <div class="profile-details mt-3 d-flex justify-content-between">
              <div>
                <h4 class="mt-2">Customer Name: {{ props.devices.customerFirstName }} {{ props.devices.customerLsatName }}</h4>
             
                <p class="mt-2">Vehicle Type : {{ props.devices.vehicleType }}</p>
                <p class="mt-2">Vehicle Name: {{ props.devices.vehicleName }}</p>
                <p class="mt-2">Vehicle Register Number: {{ props.devices.vehicleRegisterNumber }}</p>
            
                <p class="mt-2">SIM Operator : {{ props.devices.simOperator }}</p>
              </div>
              <div>
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
    </div>
  </AuthenticatedLayout>
</template>
