<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import { Inertia } from "@inertiajs/inertia";

import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';


// Delete Vehicle
const deleteVehicle = async (id) => {
  try {
    await Inertia.delete(route("vehicle-type.destroy", id), {
      onSuccess: () => {
        toast.success("Vehicle deleted successfully.");
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
  <Head title="vehicles" />

  <AuthenticatedLayout>
    <div class="mt-5">
        <div class="table-container">
            <div class="d-flex justify-content-between align-items-center">
                <h4><i class="bi bi-truck-front-fill mr-2"></i>All vehicles</h4>
                <a :href="route('vehicle-type.create')">
                <button class="btn btn-primary btn-custom"><i class="bi bi-plus-circle-fill mr-2"></i>Add New vehicle</button>
            </a>
              </div>

            <table class="table table-hover table-bordered mt-3">
                <thead class="thead-light">
                    <tr>
                        <th scope="col">S No</th>
                        <th scope="col">Company Name</th>
                        <th scope="col">Model</th>
                        <th scope="col">Fuel Type</th>
                        <th scope="col">Chassis Number</th>
                        <th scope="col">Coler</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-if="vehicles.data.length === 0">
                        <td colspan="6" class="text-center">No vehicles found.</td>
                    </tr>
                    <tr v-for="(vehicle, index) in vehicles.data" :key="vehicle.id">
                        <td>{{ index + 1 }}</td>
                        <td>{{ vehicle.company_name }}</td>
                        <td>{{ vehicle.model }}</td>
                        <td>{{ vehicle.fuel_type }}</td>
                        <td>{{ vehicle.Chassis_number }}</td>
                        <td>{{ vehicle.color }}</td>
                        <td>

                        <!-- Edit button -->
                        <button class="btn btn-warning text-white action-btn" @click="editVehicle(vehicle.id)">
                        <i class="bi bi-pencil-fill"></i>
                        </button>

                        <!-- Delete button -->
                        <button class="btn btn-danger action-btn" @click="deleteVehicle(vehicle.id)">
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
                        <li class="page-item" :class="{ disabled: vehicles.current_page === 1 }">
                            <a class="page-link" :href="`?page=${vehicles.current_page - 1}`">Previous</a>
                        </li>

                        <!-- Page Numbers -->
                        <li v-for="page in totalPages" :key="page" class="page-item" :class="{ active: page === vehicles.current_page }">
                            <a class="page-link" :href="`?page=${page}`">{{ page }}</a>
                        </li>

                        <!-- Next Button -->
                        <li class="page-item" :class="{ disabled: vehicles.current_page === vehicles.last_page }">
                            <a class="page-link" :href="`?page=${vehicles.current_page + 1}`">Next</a>
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
    vehicles: Object, // Paginated vehicle data
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

  }

};
</script>