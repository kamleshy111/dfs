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
              <h4 class="client-name">Vehicle Register Number:  <span>{{ props.Vehicles.vehicle_register_number }}</span></h4>
              <p class="client-name">Vehicle Type :  <span>{{ props.Vehicles.vehicle_type }}</span></p>
              <h4 class="client-name">IMEI Number:  <span>{{ props.Vehicles.imei_number }}</span></h4>
              <p class="client-name">SIM Card Number:  <span>{{ props.Vehicles.sim_card_number }}</span></p>
              <p class="client-name">Installation Date:  <span>{{ props.Vehicles.installation_date }}</span></p>
              </div>
            <div class="col-md-4">
              <p class="client-name">Start Date:  <span>{{ props.Vehicles.start_date }}</span></p>
              <p class="client-name">Duration : <span> {{ props.Vehicles.duration }}</span></p>
              <p class="client-name">Expiration Date: <span>{{ props.Vehicles.expirationDate }}</span></p>
              <p class="client-name">SIM Operator: <span>{{ props.Vehicles.sim_operator }}</span></p>
              <p class="client-name">Customer Name: <span>{{ props.Vehicles.first_name ? props.Vehicles.first_name + ' ' + (props.Vehicles.last_name ?? '') : '---' }}</span></p>
              <p class="client-name">Device Name: <span>{{ props.Vehicles.deviceName }}</span></p>
            </div>
            </div>
              <div class="profile-details row mt-3 px-3">
                <div v-for="(photo, index) in installationPhotos" :key="index" class="col-md-4 mt-3">
                   <div class="image-zoom-effect">
                    <img :src="photo" alt="Vehicle Installation Photo" />
                   </div>
                </div>
            </div>

          </div>
        </div>
      </div>
    </div>
</div>
  </AuthenticatedLayout>
</template>
