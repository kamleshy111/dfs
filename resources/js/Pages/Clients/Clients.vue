<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import { Inertia } from '@inertiajs/inertia';
import { useRouter } from 'vue-router';
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';
import axios from 'axios';


const router = useRouter();

// Delete Clients
const deleteCustomer = async (id) =>  {
  try {
      const response = await axios.delete(`clients/destroy/${id}`);
          toast.success(response.data.message);
            // Reload the page
            setTimeout(function(){
              window.location.reload();
            }, 3000);
    } catch (error) {
      const errorMessage = error.response?.data?.message || 'An error occurred. Please try again.';
      toast.error(errorMessage);
    }
};
</script>

<template>
    <Head :title="clients" />

  <AuthenticatedLayout>
    <div class="my-3">
      <div class="form-container shadow">
        <div class="table-container">
          <div class="d-flex justify-content-between align-items-center">
            <h4 class="responsive-btn"><i class="bi bi-people-fill mr-2"></i>All Customers</h4>
            <a :href="route('clients.create')">
            <button class="btn btn-primary btn-custom responsive-btn">
              <i class="bi bi-plus-circle-fill mr-2 "></i>Add New Customer
            </button> 
            </a>
          </div>

          <div class="table-responsive">
            <table class="table table-hover table-bordered mt-3">
              <thead class="thead-light">
                <tr>
                  <th scope="col">S No</th>
                  <th scope="col">Name</th>
                  <th scope="col">Mobile Number</th>
                  <th scope="col">Email</th>
                  <th scope="col">Date & Time</th>
                  <th scope="col">Action</th>
                </tr>
              </thead>
              <tbody>
                
                <tr v-for="(customer, index) in customers.data" :key="customer.id">
                  <td>{{ index + 1 }}</td>
                  <td>{{ customer.first_name + ' ' + customer.last_name  }}</td>
                  <td>{{ customer.phone }}</td>
                  <td>{{ customer.email }}</td>
                  <td>{{ formatDate(customer.created_at) }}</td>
                  <td>
                    <!-- view button -->
                    <button class="btn btn-light action-btn" @click="viewCustomer(customer.id)">
                      <i class="bi bi-eye-fill"></i>
                    </button>

                    <!-- Edit button -->
                    <button class="btn btn-warning text-white action-btn" @click="editCustomer(customer.id)">
                      <i class="bi bi-pencil-fill"></i>
                    </button>

                    <!-- Delete button -->
                    <button class="btn btn-danger action-btn" @click="deleteCustomer(customer.id)">
                      <i class="bi bi-trash-fill"></i>
                    </button>
                  </td>
                </tr>

                <!-- Repeat the above row as needed -->
              </tbody>
            </table>
          </div>
          <!-- Pagination -->
          <div class="pagination-container">
            <nav aria-label="Page navigation">
              <ul class="pagination">
                <!-- Previous Button -->
                <li class="page-item" :class="{ disabled: customers.current_page === 1 }">
                  <a class="page-link" :href="`?page=${customers.current_page - 1}`">Previous</a>
                </li>

                <!-- Page Numbers -->
                <li v-for="page in totalPages" :key="page" class="page-item" :class="{ active: page === customers.current_page }">
                  <a class="page-link" :href="`?page=${page}`">{{ page }}</a>
                </li>

                <!-- Next Button -->
                <li class="page-item" :class="{ disabled: customers.current_page === customers.last_page }">
                  <a class="page-link" :href="`?page=${customers.current_page + 1}`">Next</a>
                </li>
              </ul>
            </nav>
          </div>
        </div>
      </div>
    </div>
    </AuthenticatedLayout>
</template>

<script>
export default {
  props: {
    user: Object,
    customers: Object, // Paginated customer data
  },
  computed: {
    totalPages() {
      return Array.from({ length: this.customers.last_page }, (_, index) => index + 1);
    }
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
    editCustomer(id) {
      window.location.href = `/clients/${id}/edit`; 
    },

    // Redirect to the view page
    viewCustomer(id) {
      window.location.href = `/clients/${id}/view`;
    },
  }

};
</script>
