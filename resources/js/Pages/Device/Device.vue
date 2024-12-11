<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import { Inertia } from "@inertiajs/inertia";
import { ref } from "vue";
import { toast } from "vue3-toastify";
import "vue3-toastify/dist/index.css";
import axios from "axios";


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
            <a class="mr-2">
              <button
                class="btn btn-primary btn-custom"
                @click="showPopup = true"
              >
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
      <!-- Popup -->
      <div v-if="showPopup" class="popup-overlay">
        <div class="popup">
          <header class="popup-header">
            <h2>Upload</h2>
            <button @click="closePopup" class="close-btn">✖</button>
          </header>
          <p class="popup-description">A single file does not exceed 2MB</p>

          <!-- Buttons -->
          <div class="popup-actions">
            <input type="file" @change="selectFile" class="file-input" />
          </div>

          <!-- File list -->
          <table class="file-table">
            <thead>
              <tr>
                <th>File Name</th>
                <th>File Size</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              <tr v-if="file">
                <td>{{ file.name }}</td>
                <td>{{ (file.size / 1024).toFixed(2) }} KB</td>
                <td>{{ uploadStatus }}</td>
              </tr>
            </tbody>
          </table>

          <!-- Footer -->
          <footer class="popup-footer">
            <button @click="uploadFile" class="upload-btn">Start Upload</button>
            <button @click="closePopup" class="cancel-btn">Cancel</button>
          </footer>
        </div>
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
      if (confirm('Are you sure you want to delete this device?')) {
        axios.delete(`/devices/destroy/${deviceId}`)
          .then((response) => {
            toast.success('Device deleted successfully!');
            const index = this.devices.findIndex(device => device.id === deviceId);
            if (index !== -1) {
              this.devices.splice(index, 1);
            }
          })
          .catch((error) => {
            toast.error('Failed to delete device.');
            console.error(error);
          });
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
    selectFile(event) {
      this.file = event.target.files[0];
      this.uploadStatus = "Ready to upload";
    },
    uploadFile() {
      if (!this.file) {
        alert("Please select a file first.");
        return;
      }

      const formData = new FormData();
      formData.append("file", this.file);

      // Perform the file upload using Axios
      axios
        .post("devices/upload-device", formData, {
          headers: { "Content-Type": "multipart/form-data" },
        })
        .then((response) => {
          this.uploadStatus = "Uploaded successfully";
          console.log("Upload Response:", response.data);
        })
        .catch((error) => {
          this.uploadStatus = "Upload failed";
          console.error("Upload Error:", error);
        });
    },
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

.popup-header h2 {
  font-size: 20px;
  margin: 0;
  color: #333;
}

.close-btn {
  background-color: transparent;
  border: none;
  font-size: 1.6em;
  cursor: pointer;
  color: #333;
  padding: 0;
  line-height: 1;
}

.popup-description {
  font-size: 1em;
  color: #777;
  margin: 20px 0;
}

.popup-actions {
  width: 100%;
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 20px;
}

.download-btn, .upload-btn, .cancel-btn {
    background-color: var(--light-color);
    color: white;
    border: none;
    padding: 6px 20px;
    border-radius: 5px;
    cursor: pointer;
    font-size: 15px;
    transition: background-color 0.3s;
}

.download-btn:hover,
.upload-btn:hover,
.cancel-btn:hover {
  background-color: #2239c3ab;
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
  }

  .popup-header h2 {
    font-size: 1.4em;
  }

  .popup-description {
    font-size: 0.9em;
  }

  .download-btn,
  .upload-btn,
  .cancel-btn {
    padding: 10px 18px;
    font-size: 0.9em;
  }

  .file-input {
    font-size: 0.9em;
  }

  .file-table th,
  .file-table td {
    padding: 8px;
  }
}
</style>
