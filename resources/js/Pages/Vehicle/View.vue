<script setup>
import { defineProps } from 'vue';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";


const props = defineProps({
  Vehicles: Object,
});

// go to back
const goBack = () => {
  window.location.href = "/vehicle-type";
};
</script>

<template>

  <Head title="Client Profile" />

  <AuthenticatedLayout>

    <div class="back-section"><button type="button" class="back-btn-custom" @click="goBack"><i class="bi bi-caret-left"></i>
        Back</button></div>

    <div class="py-3">
      <div class="row">
        <div class="col-md-4 col-12 mt-2">
          <div class="profile-card">
            <div class="profile-details mt-3 px-3">
              <h2 class="fs-4 mb-4 fw-bold text-center text-[#2239c3cc]">Vehicle Information</h2>
              <h4 class="mt-2">Company Name: {{ props.Vehicles.company_name }}</h4>
              <h4 class="mt-2">Model: {{ props.Vehicles.model }}</h4>
              <p class="mt-2">Year : {{ props.Vehicles.year }}</p>
              <h4 class="mt-2">Fuel Type: {{ props.Vehicles.fuel_type }}</h4>
              <p class="mt-2">Chassis Number: {{ props.Vehicles.Chassis_number }}</p>
              <p class="mt-2">Color: {{ props.Vehicles.color }}</p>

            </div>
          </div>
        </div>

        <div class="col-md-4 col-12 mt-2">
          <div class="profile-card">
            <div class="profile-details mt-3 px-3">
              <h2 class="fs-4 mb-4 fw-bold text-center text-[#2239c3cc]">Driver Information</h2>
              <p class="mt-2">Name : {{ props.Vehicles.driver_name }}</p>
              <p class="mt-2">License Number : {{ props.Vehicles.license_number }}</p>
              <p class="mt-2">License Expiry Date : {{ props.Vehicles.license_expiry_date }}</p>
              <p class="mt-2">Contact : {{ props.Vehicles.driver_contact }}</p>
              <p class="mt-2">Aaddress : {{ props.Vehicles.driver_address }}</p>
            </div>
          </div>
        </div>
        <div class="col-md-4 col-12 mt-2">
          <div class="profile-card">
            <div class="profile-details mt-3 px-3">
              <h2 class="fs-4 mb-4 fw-bold text-center text-[#2239c3cc]">Devices Information</h2>
              <ul class="mt-2">
                <!-- Loop through the devices and display their device IDs -->
                <li v-for="device in props.Vehicles.devices" :key="device.id">
                  <p>Device Name: {{ device.device_id }}</p>
                  <p>Company Name: {{ device.company_name }}</p><br>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>

  </AuthenticatedLayout>
</template>
