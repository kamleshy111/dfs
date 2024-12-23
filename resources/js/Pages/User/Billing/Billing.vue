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



const sendRenewalNotification = async () => {
  try {
    
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
    { data: 'expiresDate' },
    {
        title: 'Actions',
        data: null,
        render: (data, type, row) => {
        return `
            <div class="icon-all-dflex">
              <a href="javascript:void(0);" class="btn btn-light action-btn notify-btn" data-device="${data.id}">
                <i class="bi bi-bell"></i>
              </a>
              </div>
            `;
        }
    }
];

onMounted(() => {
  document.querySelectorAll(".notify-btn").forEach(button => {
    button.addEventListener('click', () => {
      const id = button.getAttribute('data-device');
      RenewalDevices(id)
    });
  });
});

const RenewalDevices = async (id ) => {
    try {
        const response = await axios.get(`/notification/${id}`);
        toast.success(response.data.message);
        // devices.value = response.data.devices;
    } catch (error) {
        console.error("Error Vehicle Renewal Reminder:", error);
        toast.error("Failed to message. Please try again.");
    }
};
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
            <div  v-if="expirationVehicles.length > 0">
              <button @click="sendRenewalNotification" class="btn btn-primary btn-custom">
                <i class="bi bi-bell-fill mr-2"></i> Send Notification for Vehicle Renewal
              </button>
            </div>
            </div>
            <div class="text-end">
              <a :href="route('clients.map')">
                <button class="btn btn-primary btn-custom">
                  <i class="bi bi-geo-alt-fill mr-2"></i>View on Map
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
                  <th scope="col">Action</th>
                </tr>
              </thead>
            </DataTable>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
