<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";

// Define props
defineProps({
  devices: {
    type: Array, // Corrected type: Array for table data
    required: true,
  },
});

// Column definitions for DataTable
const columns = [
//   { data: 'id', title: 'S No' },
    { 
    data: null,
    title: 'S No',
    render: (data, type, row, meta) => meta.row + 1,
    },
    { data: 'customerName' },
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
              <a href="/notification/${data.id}" class="btn btn-light action-btn"><i class="bi bi-bell"></i> </a>
              </div>
            `;
        }
    }
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
            <div class="text-end">
              <a :href="route('clients.map')">
                <button class="btn btn-primary btn-custom">
                  <i class="bi bi-geo-alt-fill mr-2"></i>View on Map
                </button>
              </a>
            </div>
          </div>
          <div class="table-responsive">
            <!-- DataTable Component -->
            <DataTable :data="devices" :columns="columns">
              <thead class="thead-light">
                <tr>
                  <th scope="col">S No</th>
                  <th scope="col">customer Name</th>
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
