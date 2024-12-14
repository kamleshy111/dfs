<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import { Inertia } from "@inertiajs/inertia";
import { ref } from "vue";
import { toast } from "vue3-toastify";
import "vue3-toastify/dist/index.css";
import axios from "axios";
import Swal from 'sweetalert2';


const loadingButtons = ref({});

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
    { data: 'deviceId' },
    { data: 'orderId'},
    { data: 'customerName'},
    { data: 'startData' },
    {
        title: 'Actions',
        data: null,
        render: (data, type, row) => {
            return `
            <div class="icon-all-dflex">
              <a href="/devices/${data.id}/view" class="btn btn-light action-btn"><i class="bi bi-eye-fill"></i> </a>
              <a class="btn btn-warning text-white action-btn" href="devices/${data.id}/edit" ><i class="bi bi-pencil-fill"></i> </a>
              <button class="btn-danger  action-btn delete-btn" data-id="${data.id}"><i class="bi bi-trash-fill"></i></button>
              <a class="btn btn-warning action-btn" href="devices/map/${data.id}"> <i class="bi bi-geo-alt-fill"></i></a>
              </div>
            `;
        }
    }
]
</script>

<template>
  <Head title="device" />

  <AuthenticatedLayout>
    <div class="mt-5">
      <div class="table-container">
        <div class="d-flex justify-content-between align-items-center">
          <h4><i class="bi bi-truck-front-fill mr-2"></i>All Devices</h4>
          <div class="text-end">
            <div class="mr-2">
                            
                <form @submit.prevent="uploadExcel" enctype="multipart/form-data" class="btn btn-primary btn-custom">
                    <input type="file" accept=".xlsx, .xls" class="file-upload-input" id="file-upload"
                        @change="onFileChange" />
                    <label for="file-upload" class="file-upload-button">
                      <i class="bi bi-cloud-upload mr-2"></i>
                    </label>
                    <button  type="submit" :disabled="!file || isImporting">Add New devices Upload & Inport</button>
                </form>
                <div class="file-p mt-2">File Upload .xlsx, .xls <a href="/sample.xlsx" download>Downlod Sample</a></div>
                <div v-if="totalChunks" class="count-design">
                    <div v-if="isImporting">
                        <div class="loader"></div>
                    </div>
                    <p v-if="totalChunks">Importing data... {{ currentChunk * 10 }}/{{ totalChunks * 10 }}
                    </p>
                    <p v-if="failed_count">Failed count: {{ failed_count }}</p>
                    <p v-if="success_count">Success count: {{ success_count }}</p>
                </div>
            </div>
            <a class="mr-2" :href="route('devices.create')">
              <button class="btn btn-primary btn-custom">
                <i class="bi bi-plus-circle-fill mr-2"></i>Add New Device
              </button>
            </a>
            <a :href="route('devices.map')">
              <button class="btn btn-primary btn-custom">
                <i class="bi bi-geo-alt-fill mr-2"></i>View on Map
              </button>
            </a>

          </div>
        </div>

        <div class="table-responsive">
            <!-- DataTable Component -->
            <DataTable :data="devices" :columns="columns" id="devices">
              <thead class="thead-light">
                <tr>
                  <th scope="col">S No</th>
                  <th scope="col">Device ID</th>
                  <th scope="col">Order ID</th>
                  <th scope="col">Customer Name</th>
                  <th scope="col">Start Data</th>
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
    data() {
        return {
            file: null,
            isImporting: false,
            totalChunks: 0,
            currentChunk: 0,
            failed_count: 0,
            success_count: 0,
        };
    },
    mounted() {
      this.setupDeleteButton();
    },
    methods: {
        setupDeleteButton() {
          const self = this;
          $(document).on('click', '.delete-btn', (event) => {
            const deviceId = $(event.target).closest('button').data('id');
            self.deleteDevice(deviceId);
          });
        },
        deleteDevice(deviceId) {
            Swal.fire({
                title: 'Are you sure?',
                text: 'Do you want to delete this device?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Send Axios request if confirmed
                    axios
                        .delete(`/devices/destroy/${deviceId}`)
                        .then(response => {
                            Swal.fire(
                                'Deleted!',
                                'Your device has been deleted.',
                                'success'
                            );

                            const index = this.devices.findIndex(device => device.id === deviceId);
                            if (index !== -1) {
                              this.devices.splice(index, 1);
                            }
                            // Optional: Update the UI or refresh data
                            console.log(response.data);
                        })
                        .catch(error => {
                            Swal.fire(
                                'Error!',
                                'Failed to delete the device. Please try again.',
                                'error'
                            );
                            location.reload();
                            console.error(error);
                        });
                } else {
                    console.log('device canceled the delete action.');
                }
            });
        },
        onFileChange(event) {
            this.file = event.target.files[0];
        },
        async uploadExcel() {
            let app = this;
            this.failed_count = 0;
            this.success_count = 0;

            if (!this.file) {
                this.message = "Please select a file.";
                this.success = false;
                return;
            }

            const formData = new FormData();
            formData.append("file", this.file);

            try {
                await axios.post('/devices/upload-excel', formData).then(function (response) {
                    console.log(response.data.totalChunks);
                    app.totalChunks = response.data.totalChunks;
                    app.currentChunk = 0;
                    app.isImporting = true;

                    // Step 2: Start importing data in chunks
                    app.importChunks();
                }).catch(
                    error => console.log(error)
                )
            } catch (error) {
                this.message = "Failed to upload file.";
                this.success = false;
            }
        },
        async importChunks() {
            let app = this;
            while (this.currentChunk < this.totalChunks) {
                try {
                    const formData = new FormData();
                    formData.append("file", this.file);
                    formData.append("chunkIndex", this.currentChunk);

                    await axios.post('/devices/import-chunk', formData).then(function (response) {
                        app.failed_count += response.data.failed_count;
                        app.success_count += response.data.success_count;
                    });

                    this.currentChunk++;
                } catch (error) {
                    console.error('Error importing chunk:', error);
                    break;
                }
            }

            this.isImporting = false;
            alert('Import completed!');
            // location.href = location.href;
        }
    },
};
</script>

<style>
.file-upload-container {
    position: relative;
    display: flex;
    align-items: center;
}

.file-upload-input {
    display: none;
}

.file-upload-button {
    background-color: transparent;
    margin: 0 10px 0 0;
    border: none;
    cursor: pointer;
    display: inline-flex;
    font-size: 12px;
    align-items: center;
}
.count-design {
    width: 220px;
    background: #fff;
    padding: 20px;
    box-shadow: rgba(99, 99, 99, 0.2) 0px 2px 8px 0px;
    margin-top: 10px;
    border-radius: 5px;
}

.file-upload-button svg {
    fill: #b70900;
    width: 30px;
    height: 30px;
    margin-right: 10px;
}
.loader {
    border: 8px solid #c2c2c2;
    border-top: 8px solid #b70900;
    border-radius: 50%;
    width: 30px;
    height: 30px;
    animation: spin 1.5s linear infinite;
    margin-bottom: 10px;
}


@keyframes spin {
    0% {
        transform: rotate(0deg);
    }
    100% {
        transform: rotate(360deg);
    }
}
</style>