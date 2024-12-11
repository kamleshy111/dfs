<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import { Inertia } from "@inertiajs/inertia";
import { ref } from "vue";
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';
import axios from 'axios';


const loadingButtons = ref({});


// Define props
defineProps({
  vehicles: {
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
    { data: 'vehicleType'},
    { data: 'vehicleRegisterNumber'},
    { data: 'installationDate' },
    {
        title: 'Actions',
        data: null,
        render: (data, type, row) => {
            return `
            <div class="icon-all-dflex">
              <a href="/clients/${data.id}/view" class="btn btn-light action-btn"><i class="bi bi-eye-fill"></i> </a>
              <a class="btn btn-warning text-white action-btn" href="clients/${data.id}/edit" ><i class="bi bi-pencil-fill"></i> </a>
              <button class="btn-danger  action-btn delete-btn" data-id="${data.id}"><i class="bi bi-trash-fill"></i></button>
              <a class="btn btn-warning action-btn" href="clients/map/${data.id}"> <i class="bi bi-geo-alt-fill"></i></a>
              </div>
            `;
        }
    }
]

// Delete Vehicle
const deleteVehicle = async (id) => {
  if (confirm("Are you sure you want to delete this vehicle?")) {
    loadingButtons.value[id] = true;
    try {
      const response = await axios.delete(`vehicle-type/destroy/${id}`);
      toast.success(response.data.message);
      // Reload the page
      setTimeout(function (){
        window.location.reload();
      }, 3000);
    } catch (error) {
      const errorMessage = error.response?.data?.message || 'An error occurred. Please try again.';
      toast.error(errorMessage);
    }
    setTimeout(() => {
      loadingButtons.value[id] = false; // Reset the loading state
      window.location.reload(); // Reload the page
    }, 4000);
  }
};
</script>

<template>

  <Head title="vehicles" />

  <AuthenticatedLayout>
    <div class="mt-5">
      <div class="table-container">
        <div class="d-flex justify-content-between align-items-center">
          <h4><i class="bi bi-truck-front-fill mr-2"></i>All vehicles</h4>

            
            <a :href="route('vehicle-type.create')">
              <button class="btn btn-primary btn-custom"><i class="bi bi-plus-circle-fill mr-2"></i>Add New
                vehicle</button>
            </a>
        </div>
          <DataTable :data="vehicles" :columns="columns" id="vehicles">
          <thead class="thead-light">
            <tr>
              <th scope="col">S No</th>
              <th scope="col">Customers Name</th>
              <th scope="col">Vehicle Register Number</th>
              <th scope="col">Vehicle Type</th>
              <th scope="col">Installation Date</th>
              <th scope="col">Action</th>
            </tr>
          </thead>
        </DataTable>


 
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script>
export default {
  data() {
      return {
        showPopup: false,
        file: null,
        uploadStatus: "Pending",
      };
    },
  props: {
    user: Object,
    // vehicles: Object, // Paginated vehicle data
  },
  computed: {
    totalPages() {
      return Array.from({ length: this.vehicles.last_page }, (_, index) => index + 1);
    },
  },
  methods: {
    // Redirect to the edit page
    editVehicle(id) {
      window.location.href = `/vehicle-type/${id}/edit`;
    },

    // Redirect to the view page
    viewDevice(id) {
      window.location.href = `/vehicle-type/${id}/view`;
    },

    // Installtion photos
    InstalltionPhotos(id) {
      window.location.href = `/vehicle-type/${id}/photos`;
    }

  }

};
</script>