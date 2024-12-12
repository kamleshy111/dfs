<script setup>
import { defineProps } from 'vue';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";


const props = defineProps({
  Vehicles: Object,
  installationPhotos: Array,
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

<div class="container py-5 px-0">
    <div class="client-info-card shadow-lg rounded-lg bg-white p-4">
      <div class="row">
        <div class="mt-2 p-0">
          <h2 class="section-title">Vehicle Information</h2>
          <div class="">
            <div class="profile-details mt-3 d-flex justify-content-between">
              <div class="col-md-8">
              <h4 class="mt-3">Vehicle Register Number: {{ props.Vehicles.vehicle_register_number }}</h4>
              <p class="mt-3">Vehicle Type : {{ props.Vehicles.vehicle_type }}</p>
              <h4 class="mt-3">IMEI Number: {{ props.Vehicles.imei_number }}</h4>
              <p class="mt-3">SIM Card Number: {{ props.Vehicles.sim_card_number }}</p>
              <p class="mt-3">Installation Date: {{ props.Vehicles.installation_date }}</p>
              </div>
            <div class="col-md-4">
              <p class="mt-3">Start Date: {{ props.Vehicles.start_date }}</p>
              <p class="mt-3">Expiration Date: {{ props.Vehicles.expirationDate }}</p>
              <p class="mt-3">SIM Operator: {{ props.Vehicles.sim_operator }}</p>
              <p class="mt-3">Customer Name: {{ props.Vehicles.first_name ? props.Vehicles.first_name + ' ' + (props.Vehicles.last_name ?? '') : '---' }}</p>
              <p class="mt-3">Device Name: {{ props.Vehicles.deviceName }}</p>
            </div>
            </div>
            <div class="profile-details mt-3 px-3">
              <div v-for="(photo, index) in installationPhotos" :key="index">
                <img :src="photo" alt="Vehicle Installation Photo" />
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>
</div>
  </AuthenticatedLayout>
</template>
