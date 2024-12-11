<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import { Inertia } from '@inertiajs/inertia';
import { useRouter } from 'vue-router';
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';
import axios from 'axios';
import { ref } from "vue";

// Define props
defineProps({
    customers: {
    type: Array, 
    required: true,
  },
  user: {
    type: Object,
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
    { data: 'email'},
    { data: 'phone'},
    { data: 'created_at' },
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
</script>

<template>

    <Head title="Clients" />

    <AuthenticatedLayout>
        <div class="my-3">
            <div class="form-container shadow">
                <div class="table-container">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="responsive-btn"><i class="bi bi-people-fill mr-2"></i>All Customers</h4>
                        <div class="text-end">
                            <a class="mr-2" :href="route('clients.create')">
                                <button class="btn btn-primary btn-custom responsive-btn">
                                    <i class="bi bi-plus-circle-fill mr-2 "></i>Add New Customer
                                </button>
                            </a>
                            <a :href="route('clients.map')">
                                <button class="btn btn-primary btn-custom">
                                    <i class="bi bi-geo-alt-fill mr-2"></i>View on Map
                                </button>
                            </a>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <!-- DataTable Component -->
                        <DataTable :data="customers" :columns="columns" id="customers">
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
                        </DataTable>
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
  },
  mounted() {
    this.setupDeleteButton();
  },
  methods: {
    setupDeleteButton() {
      const self = this;
      $(document).on('click', '.delete-btn', (event) => {
        const customerId = $(event.target).closest('button').data('id');
        self.deleteDevice(customerId);
      });
    },

    deleteDevice(customerId) {
      if (confirm('Are you sure you want to delete this client?')) {
        axios.delete(`/clients/destroy/${customerId}`)
          .then((response) => {
            toast.success('client deleted successfully!');
            const index = this.customers.findIndex(customer => customer.id === customerId);
            if (index !== -1) {
              this.customers.splice(index, 1);
            }
          })
          .catch((error) => {
            toast.error('Failed to delete client.');
            console.error(error);
          });
      }
    },
  },
};
</script>
