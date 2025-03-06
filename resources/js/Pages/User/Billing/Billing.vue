<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import { type } from "jquery";
import { ref,onMounted } from "vue";
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';
import axios from 'axios'; 

// Define props
defineProps({
  devices: {
    type: Array, // Corrected type: Array for table data
    required: true,
  },
  expirationVehicles: {
    type: Array
  }
});

const loading = ref(false);

const sendRenewalNotification = async () => {
  try {
    loading.value = true;
    const response = await axios.get(route('email.vehicle-renewal'));

    console.log(response.data.message);
    if (response.data.message) {
      toast.success('Vehicle renewal notification sent successfully!');
    } else {
      toast.error('Failed to send vehicle renewal notification. Please try again.');
    }
  } catch (error) {
    console.error("Error sending renewal notification:", error);
    toast.error('An error occurred. Please try again.');
  } finally {
    loading.value = false;
  }
};

// Column definitions for DataTable
const columns = [
//   { data: 'id', title: 'S No' },
  { 
    data: null,
    title: 'S No',
    render: (data, type, row, meta) => meta.row + 1,
  },
    { data: 'vehicleNumber' },
    { data: 'deviceName' },
    { data: 'startData'},
    { data: 'duration'},
    { data: 'expiresDate' }
];

</script>

<template>
  <Head title="Billings" />

  <AuthenticatedLayout>
    <div class="my-3">
      <div class="form-container shadow">
        <div class="table-container">
          <div class="d-flex justify-content-between align-items-center">
            <h4 class="responsive-btn">
              <i class="bi bi-people-fill mr-2"></i>All Billings
            </h4>
            <div class="d-flex justify-content-end">
            <div class="text-end mr-2">
              <div v-if="loading" >
                <div class="loader"></div>
              </div>
            <div  v-if="expirationVehicles.length > 0 && !loading">
              <button id="myButton" @click="sendRenewalNotification" class="btn btn-primary btn-custom">
                <i class="bi bi-bell-fill mr-2"></i> Send Notification for Vehicle Renewal
              </button>
            </div>
            </div>
            <div class="text-end">
              <a :href="route('dashboard')">
                <button class="btn btn-primary btn-custom">
                  Dashboard
                </button>
              </a>
            </div>
          </div>
          </div>
          <div class="table-responsive">
            <!-- DataTable Component -->
            <DataTable :data="devices" :columns="columns">
              <thead class="thead-light">
                <tr>
                  <th scope="col">S No</th>
                  <th scope="col">Vehicle Register Number</th>
                  <th scope="col">Device ID</th>
                  <th scope="col">Start Date</th>
                  <th scope="col">Duration</th>
                  <th scope="col">Expired Date</th>
                </tr>
              </thead>
            </DataTable>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<style scoped>
.loader {
    border: 4px solid #f3f3f3;
    border-top: 4px solid #3498db;
    border-radius: 50%;
    width: 35px;
    height: 35px;
    margin-right: 150px;
    animation: spin 1s linear infinite;
}

.btn-custom {
  padding: 10px 20px;
  font-size: 16px;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>