<script setup>

import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import { Inertia } from '@inertiajs/inertia';
import { useRouter } from 'vue-router';
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';
import axios from 'axios';
import { ref,onMounted  } from "vue";



// Reactive reference for notifications
const notifications = ref([]);

// Define columns for the DataTable
const columns = [
  // { name: 'S No', field: 'index' },
  { 
    data: null,
    title: 'S No',
    render: (data, type, row, meta) => meta.row + 1,
  },
  { data: 'deviceId' },
  {   
    render: (data, type, row) => `${row.latitude}, ${row.longitude}`
  },
  {data: 'location' },
  { 
    title: 'Captures', 
    render: (data, type, row) => {
      // Check if the captures field contains a valid image URL
      return row.captures ? `<img src="${row.captures}" alt="Capture" style="width: 100px; height: auto;" />` : 'No Image';
    }
  },
  {data: 'message' },
  {data: 'alerts' },
];

const fetchNotifications = async () => {
  try {
    const response = await axios.get('/api/user-notifications'); 
    console.log(response);
    notifications.value = response.data.notifications;
  } catch (error) {
    console.error('Error fetching notifications:', error);
    toast.error('Failed to load notifications');
  }
};

const getNotificationUser = () => {
  window.Echo.channel('notificationAlert')
    .listen('.alert.notification', async (data) => {
        console.log('Received data:', data);
        try {
            const response = await axios.get('/api/user-notifications');
            notifications.value = response.data.notifications || [];
        } catch (error) {
            console.error('Error fetching notifications:', error);
        }
    });
};


onMounted(() => {
  fetchNotifications();
  getNotificationUser();
});
</script>

<template>

    <Head title="Devices" />

<AuthenticatedLayout>
    <div class="my-3">
      <div class="form-container shadow">
        <div class="table-container">
          <div class="d-flex justify-content-between align-items-center">
            <h4 class="responsive-btn">
              <i class="bi bi-people-fill mr-2"></i>All Clients
            </h4>
          </div>
          <div class="table-responsive">
            <!-- DataTable Component -->
            <DataTable :data="notifications" :columns="columns" id="notifications">
              <thead class="thead-light">
                <tr>
                  <th scope="col">S No</th>
                  <th scope="col">Device ID</th>
                  <th scope="col">Coordinates</th>
                  <th scope="col">Location</th>
                  <th scope="col">Captures</th>
                  <th scope="col">Message</th>
                  <th scope="col">Alert</th>
                </tr>
              </thead>
            </DataTable>
          </div>
        </div>
      </div>
    </div>
</AuthenticatedLayout>

</template>
