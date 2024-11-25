<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import { Inertia } from "@inertiajs/inertia";
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';


// Delete device
const deleteDevice = async (id) => {
  try {
    await Inertia.delete(route("devices.destroy", id), {
      onSuccess: () => {
        toast.success("Device deleted successfully.");
      },
      onError: (errors) => {
        if (errors) {
          toast.error("An error occurred. Please check your input.");
        }
      },  
    });
  } catch (error) {
    toast.error("An unexpected error occurred.");
  }
};
</script>

<template>
  <Head title="device" />

  <AuthenticatedLayout>
    <div class="mt-5">
        <div class="table-container">
            <div class="d-flex justify-content-between align-items-center">
                <h4><i class="bi bi-truck-front-fill mr-2"></i>All Devices</h4>
                <a :href="route('devices.create')">
                <button class="btn btn-primary btn-custom"><i class="bi bi-plus-circle-fill mr-2"></i>Add New Device</button>
            </a>
              </div>

            <table class="table table-hover table-bordered mt-3">
                <thead class="thead-light">
                    <tr>
                        <th scope="col">S No</th>
                        <th scope="col">Device ID</th>
                        <th scope="col">Order ID</th>
                        <th scope="col">Purchase By (Company)</th>
                        <th scope="col">Date &amp; Time</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-if="devices.data.length === 0">
                        <td colspan="6" class="text-center">No devices found.</td>
                    </tr>
                    <tr v-for="(device, index) in devices.data" :key="device.id">
                        <td>{{ index + 1 }}</td>
                        <td>{{ device.device_id }}</td>
                        <td>{{ device.order_id }}</td>
                        <td>{{ device.company_name }}</td>
                        <td>{{ formatDate(device.date_time) }}</td>
                        <td>

                        <!-- view button -->
                        <button class="btn btn-light action-btn" @click="viewDevice(device.id)">
                        <i class="bi bi-eye-fill"></i>
                        </button>

                        <!-- Edit button -->
                        <button class="btn btn-warning text-white action-btn" @click="editDevice(device.id)">
                        <i class="bi bi-pencil-fill"></i>
                        </button>

                        <!-- Delete button -->
                        <button class="btn btn-danger action-btn" @click="deleteDevice(device.id)">
                          <i class="bi bi-trash-fill"></i>
                        </button>
                        </td>
                    </tr>
                    
                </tbody>
            </table>

            <!-- Pagination -->
            <div class="pagination-container">
                <nav aria-label="Page navigation">
                    <ul class="pagination">
                        <!-- Previous Button -->
                        <li class="page-item" :class="{ disabled: devices.current_page === 1 }">
                            <a class="page-link" :href="`?page=${devices.current_page - 1}`">Previous</a>
                        </li>

                        <!-- Page Numbers -->
                        <li v-for="page in totalPages" :key="page" class="page-item" :class="{ active: page === devices.current_page }">
                            <a class="page-link" :href="`?page=${page}`">{{ page }}</a>
                        </li>

                        <!-- Next Button -->
                        <li class="page-item" :class="{ disabled: devices.current_page === devices.last_page }">
                            <a class="page-link" :href="`?page=${devices.current_page + 1}`">Next</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
  </AuthenticatedLayout>
</template>

<script>
export default {
  props: {
    user: Object,
    devices: Object, // Paginated device data
  },
  computed: {
    totalPages() {
      return Array.from({ length: this.devices.last_page }, (_, index) => index + 1);
    },
  },
  methods: {
    formatDate(value) {
      if (!value) return '';
      
      // Create a new Date object from the ISO 8601 string
      const date = new Date(value);
      
      // Define the format options
      const dateOptions = {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
      };
      const timeOptions = {
        hour: '2-digit',
        minute: '2-digit',
        hour12: true
      };

      // Format the date and time separately and combine them
      const formattedDate = date.toLocaleDateString('en-GB', dateOptions);
      const formattedTime = date.toLocaleTimeString('en-GB', timeOptions);

      // Return the formatted string in the desired format
      return `${formattedDate} at ${formattedTime}`;
    },

    // Redirect to the edit page
    editDevice(id) {
      window.location.href = `/devices/${id}/edit`; 
    },

    // Redirect to the view page
    viewDevice(id) {
      window.location.href = `/devices/${id}/view`;
    },
  }

};
</script>