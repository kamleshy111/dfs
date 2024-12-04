<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import { Inertia } from "@inertiajs/inertia";
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';
import axios from 'axios';


// Delete device
const deleteDevice = async (id) =>  {
  try {
      const response = await axios.delete(`devices/destroy/${id}`);
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
  <Head title="device" />

  <AuthenticatedLayout>
    <div class="mt-5">
        <div class="table-container">
            <div class="d-flex justify-content-between align-items-center">
                <h4><i class="bi bi-truck-front-fill mr-2"></i>All Devices</h4>
                <div class="text-end">
                  <a class="mr-2">
                    <button class="btn btn-primary btn-custom" @click="showPopup = true"><i class="bi bi-cloud-upload mr-2"></i>Add New
                      devices file</button>
                  </a>
                  <a :href="route('devices.create')">
                      <button class="btn btn-primary btn-custom"><i class="bi bi-plus-circle-fill mr-2"></i>Add New Device</button>
                  </a>
                </div>  
              </div>

            <table class="table table-hover table-bordered mt-3">
                <thead class="thead-light">
                    <tr>
                        <th scope="col">S No</th>
                        <th scope="col">Device ID</th>
                        <th scope="col">Order ID</th>
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
        <!-- Popup -->
        <div v-if="showPopup" class="popup-overlay">
          <div class="popup">
            <header>
              <h2>Upload</h2>
              <button @click="closePopup">✖</button>
            </header>
            <p>A single file does not exceed 2MB</p>

            <!-- Buttons -->
            <div>
              <button @click="downloadTemplate">Download template</button>
              <input type="file" @change="selectFile" />
            </div>

            <!-- File list -->
            <table>
              <thead>
                <tr>
                  <th>File Name</th>
                  <th>File Size</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <tr v-if="file">
                  <td>{{ file.name }}</td>
                  <td>{{ (file.size / 1024).toFixed(2) }} KB</td>
                  <td>{{ uploadStatus }}</td>
                  <td><button @click="uploadFile">Start Upload</button></td>
                </tr>
              </tbody>
            </table>

            <!-- Footer -->
            <footer>
              <button @click="closePopup">Cancel</button>
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
    devices: Object, // Paginated device data
  },
  computed: {
    totalPages() {
      return Array.from({ length: this.devices.last_page }, (_, index) => index + 1);
    },
  },
  methods: {
    
    closePopup() {
        this.showPopup = false;
        this.file = null;
        this.uploadStatus = "Pending";
    },
    downloadTemplate() {
      // Replace with your template file's URL
      window.open('/devices', '_blank');
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

<style>
  .popup-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100vw;
  height: 100vh;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 9999;
}

.popup {
  background: #fff;
  padding: 20px;
  width: 500px;
  border-radius: 8px;
}

.popup header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 20px;
}

.popup table {
  width: 100%;
  border-collapse: collapse;
}

.popup table th,
.popup table td {
  padding: 10px;
  text-align: left;
  border: 1px solid #ddd;
}

</style> 