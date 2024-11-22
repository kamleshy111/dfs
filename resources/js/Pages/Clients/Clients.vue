<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import { Inertia } from '@inertiajs/inertia';
import { useRouter } from 'vue-router';


const router = useRouter();

// const deleteCustomer = async (id) => {
//   if (confirm('Are you sure you want to delete this customer?')) {
//     try {
//       await Inertia.delete(`/clients/${id}`);

//     } catch (error) {
//       alert('An error occurred while deleting the customer.');
//     }
//   }
// };


const deleteCustomer = async (id) => {
  if (confirm('Are you sure you want to delete this customer?')) {
    try {
      await Inertia.delete(route('clients.destroy', id));
      alert('Customer deleted successfully.');
    } catch (error) {
      console.error('Error deleting customer:', error);
      alert('An error occurred while deleting the customer.');
    }
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
                  <th scope="col">Date & Time</th>
                  <th scope="col">Action</th>
                </tr>
              </thead>
              <tbody>
                
                <tr v-for="(customer, index) in customers.data" :key="customer.id">
                  <td>{{ index + 1 }}</td>
                  <td>{{ customer.first_name }}</td>
                  <td>{{ customer.phone }}</td>
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
