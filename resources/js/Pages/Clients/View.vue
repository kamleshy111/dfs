<script setup>
import { defineProps } from "vue";
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
    <div class="back-section">
      <button type="button" class="back-btn-custom" @click="goBack">
        <i class="bi bi-caret-left"></i> Back
      </button>
    </div>

    <div class="container py-5 px-0">
      <div class="client-info-card shadow-lg rounded-lg bg-white p-4">
        <h2 class="section-title">Client Information</h2>
        <div class="row">
          <!-- Left Section: Client Information -->
          <div class="col-md-8">
            <div class="client-info-section">
              <h4 class="client-name">
                Name:
                <span
                  >{{ props.customers.first_name }}
                  {{ props.customers.last_name }}</span
                >
              </h4>
              <p class="client-email">
                Primary Email: <span>{{ props.customers.email }}</span>
              </p>
              <p class="client-email">
                Secondary Email:
                <span>{{ props.customers.secondary_email }}</span>
              </p>
              <p class="client-phone">
                Primary Mobile: <span>{{ props.customers.phone }}</span>
              </p>
              <p class="client-address">
                Address: <span>{{ props.customers.address }}</span>
              </p>
            </div>
          </div>

          <!-- Right Section: Devices and Status -->
          <div class="col-md-4">
            <div class="client-info-section">
              <p class="client-phone">
                Secondary Mobile:
                <span>{{ props.customers.secondary_phone }}</span>
              </p>

              <p class="device-title mb-2">Devices:</p>
              <ul class="device-status-list">
                <li>
                  <p class="client-devices">
                    Active: <span>{{ props.customers.activeCount }}</span>
                  </p>
                </li>
                <li>
                  <p class="client-devices">
                    Inactive: <span>{{ props.customers.inActiveCount }}</span>
                  </p>
                </li>
                <li>
                  <p class="client-devices">
                    Expired: <span>{{ props.customers.expiredCount }}</span>
                  </p>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
