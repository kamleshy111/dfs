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


const showPopup = ref(false);

const uploadStatus = ref("Pending");

const closePopup = () => {
  showPopup.value = false;
  file.value = null;
  uploadStatus.value = "Pending";

};

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
                   
              <div v-if="showPopup" class="popup-overlay">
                <div class="popup">
                    <header class="popup-header">
                      <h2>Upload</h2>
                      <button @click="closePopup" class="close-btn">*</button>
                    </header>
                    <p class="popup-description">A Single file does not exceed 2Mb</p>
                    
                    <form @submit.prevent="uploadExcel" enctype="multipart/form-data">
                      <input type="file" accept=".xlsx, .xls" class="file-upload-input" id="file-upload"
                            @change="onFileChange" />
                      <div class="popup-actions">
                      
                          <button type="submit" class="btn btn-success">Start Upload</button>
                          <button @click="closePopup" class="cancel-btn">Cancle</button>
                          
                 
                        </div>
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
                    </form>
                  </div>
                </div>
 
            </div>
            <a class="mr-2">
              <button class="btn btn-primary btn-custom" @click="showPopup = true">
              <i class="bi bi-cloud-upload mr-2"></i>Add New devices file
                </button>
            </a>
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
            showPopup: false,
        };
    },
    mounted() {
      this.setupDeleteButton();
    },
    props: {
      user: Object,
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
          
            if (!this.file || this.file.length === 0) {
                toast.error("Please select a file.", { autoClose: 3000 });
                return;
            }

            if (this.file.type !== "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet") {
               
              
              toast.error("Invalid file type. Only .xlsx files are allowed.", { autoClose: 3000 });
                this.file = null;
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
              toast.error("Failed to upload file.", { autoClose: 3000 });
                return;
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
            toast.success('Import completed!');
            setTimeout(() => {
              window.location.reload();
            }, 3000); // Reloads page after 3 seconds
                  
        }
    },
    closePopup() {
      this.showPopup = false;
      this.file = null;
      this.uploadStatus = "Pending";
    },
    downloadTemplate() {
      // Replace with your template file's URL
      window.open("/devices", "_blank");
    },
};
</script>

<style>
.popup-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.6); /* Dark overlay */
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000;
}
.popup {
  background-color: #fff;
  border-radius: 10px;
  width: 450px;
  max-width: 90%;
  padding: 30px;
  box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
  display: flex;
  flex-direction: column;
  align-items: center;
  position: relative;
}

.popup-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  width: 100%;
  border-bottom: 2px solid #f0f0f0;
  padding-bottom: 10px;
}
.file-upload-container {
    position: relative;
    display: flex;
    align-items: center;
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
}
.upload-btn{
      border: 1px solid var(--light-color) !important;
}
.upload-btn:hover{
  color: var(--light-color);
}

.download-btn:hover, .upload-btn:hover, .cancel-btn:hover {
    background-color: transparent;
}

.file-input {
  padding: 10px;
  font-size: 1em;
  border-radius: 5px;
  border: 1px solid #ddd;
  margin-top: 10px;
  cursor: pointer;
}

.file-table {
  width: 100%;
  border-collapse: collapse;
  margin-bottom: 20px;
  font-size: 0.9em;
}

.file-table th,
.file-table td {
  padding: 12px;
  text-align: left;
  border-bottom: 1px solid #ddd;
}

.file-table th {
  background-color: #f4f4f4;
  font-weight: normal;
}

.file-table tr:hover {
  background-color: #f9f9f9;
}

.popup-footer {
  display: flex;
  justify-content: flex-end;
  width: 100%;
}

.cancel-btn {
  background-color: #e0e0e0;
  color: #333;
  padding: 12px 20px;
  border-radius: 5px;
  margin-left: 10px;
  cursor: pointer;
}

.cancel-btn:hover {
  background-color: #ccc;
}
.popup .close-btn {
  background: #fff;
  padding: 8px;
  font-size: 16px;
  position: absolute;
  right: -10px;
  box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
  top: -18px;
  border-radius: 18%;
}

/* Responsive Design */
@media (max-width: 600px) {
  .popup {
    width: 95%;
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