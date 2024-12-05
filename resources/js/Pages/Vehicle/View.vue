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
        <div class="col-md-12 col-12 mt-2">
          <div class="profile-card">
            <div class="profile-details mt-3 px-3">
              <h2 class="fs-4 mb-4 fw-bold text-center text-[#2239c3cc]">Vehicle Information</h2>
              <h4 class="mt-2">Vehicle Register Number: {{ props.Vehicles.vehicle_register_number }}</h4>
              <h4 class="mt-2">Vehicle Name: {{ props.Vehicles.vehicle_name }}</h4>
              <p class="mt-2">Vehicle Type : {{ props.Vehicles.vehicle_type }}</p>
              <h4 class="mt-2">IMEI Number: {{ props.Vehicles.imei_number }}</h4>
              <p class="mt-2">SIM Card Number: {{ props.Vehicles.sim_card_number }}</p>
              <p class="mt-2">Installation Date: {{ props.Vehicles.installation_date }}</p>

              <p class="mt-2">Start Date: {{ props.Vehicles.start_date }}</p>
              <p class="mt-2">Duration: {{ props.Vehicles.duration }}</p>
              <p class="mt-2">SIM Operator: {{ props.Vehicles.sim_operator }}</p>
              <p class="mt-2">Customer Name: {{ props.Vehicles.first_name +' '+ props.Vehicles.last_name}}</p>
              <p class="mt-2">Device Name: {{ props.Vehicles.deviceName }}</p>

            </div>
          </div>
        </div>
      </div>
    </div>

  </AuthenticatedLayout>
</template>
