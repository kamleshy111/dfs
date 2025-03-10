<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import { Inertia } from "@inertiajs/inertia";
import { ref } from "vue";
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';
import axios from 'axios';
import Swal from 'sweetalert2';


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
    { data: 'vehicleRegisterNumber'},
    { data: 'vehicleType'},
    { data: 'installationDate' },
    {
        title: 'Actions',
        data: null,
        render: (data, type, row) => {
            return `
            <div class="icon-all-dflex">
              <a href="/vehicle-type/${data.id}/view" class="btn btn-light action-btn"><i class="bi bi-eye-fill"></i> </a>
              <a class="btn btn-warning text-white action-btn" href="vehicle-type/${data.id}/edit" ><i class="bi bi-pencil-fill"></i> </a>
               <a class="btn btn-light action-btn" href="vehicle-type/${data.id}/photos" ><i class="bi bi-cloud-upload"></i></a>
              <button class="btn-danger  action-btn delete-btn" data-id="${data.id}"><i class="bi bi-trash-fill"></i></button>
              </div>
            `;
        }
    }
]
</script>

<template>

  <Head title="vehicles" />

  <AuthenticatedLayout>
    <div class="mt-5">
      <div class="table-container new-client-table">
        <div class="d-flex justify-content-between align-items-center">
            <h4 class="responsive-btn">
             <i class="bi bi-people-fill mr-2"></i>All Clients
            </h4>

           <div class="text-end mobile-btn-responsive">
              <!-- Mobile menu button -->
              <button class="btn btn-primary btn-custom d-block d-md-none" id="mobilebtn">
                  <i class="bi bi-three-dots-vertical"></i>
              </button>
              <div class="d-none d-md-flex" id="desktopButtons">
                <a :href="route('vehicle-type.create')">
                    <button class="btn btn-primary btn-custom"><i class="bi bi-plus-circle-fill mr-2"></i>Add New
                      vehicle</button>
                  </a>
              </div>

              <!-- Popup mobile menu -->
              <div id="mobileMenu" class="mobile-menu" style="display: none;">
                <a :href="route('vehicle-type.create')">
                    <button class="btn btn-primary btn-custom"><i class="bi bi-plus-circle-fill mr-2"></i>Add New
                      vehicle</button>
                  </a>
              </div>
           </div>
        </div>

        <div class="table-responsive">
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
        const vehicleId = $(event.target).closest('button').data('id');
        self.deleteVehicle(vehicleId);
      });
    },

    deleteVehicle(vehicleId) {
        Swal.fire({
            title: 'Are you sure?',
            text: 'Do you want to delete this vehicle?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Send Axios request if confirmed
                axios
                    .delete(`/vehicle-type/destroy/${vehicleId}`)
                    .then(response => {
                        Swal.fire(
                            'Deleted!',
                            'Your vehicle has been deleted.',
                            'success'
                        );

                        const index = this.vehicles.findIndex(device => device.id === vehicleId);
                        if (index !== -1) {
                           this.vehicles.splice(index, 1);
                         }
                        // Optional: Update the UI or refresh data
                        console.log(response.data);
                    })
                    .catch(error => {
                        Swal.fire(
                            'Error!',
                            'Failed to delete the vehicle. Please try again.',
                            'error'
                        );
                        location.reload();
                        console.error(error);
                    });
            } else {
                console.log('vehicle canceled the delete action.');
            }
        });
    },
  },
};

$(document).ready(function() {
    // Check if the mobile menu was open before
    if (localStorage.getItem('mobileMenuVisible') === 'true') {
        $('#mobileMenu').show();
    }

    // Toggle mobile menu visibility and update localStorage
    $('#mobilebtn').click(function(e) {
        e.stopPropagation();
        const isVisible = $('#mobileMenu').is(':visible');
        $('#mobileMenu').toggle();
        localStorage.setItem('mobileMenuVisible', !isVisible);
    });

    // Close the mobile menu if clicking outside
    $(document).click(function(e) {
        if (!$(e.target).closest('#mobilebtn, #mobileMenu').length) {
            $('#mobileMenu').hide();
            localStorage.setItem('mobileMenuVisible', 'false');
        }
    });

    // Prevent closing menu if clicking inside the menu
    $('#mobileMenu').click(function(e) {
        e.stopPropagation();
    });
});
</script>