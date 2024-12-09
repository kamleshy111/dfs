<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import { Inertia } from "@inertiajs/inertia";
import { ref } from "vue";
import { toast } from "vue3-toastify";
import "vue3-toastify/dist/index.css";
import axios from "axios";


const loadingButtons = ref({});

// Delete device
const deleteDevice = async (id) => {
  if (confirm("Are you sure you want to delete this device?")) {
    loadingButtons.value[id] = true;
    try {
      const response = await axios.delete(`devices/destroy/${id}`);
      toast.success(response.data.message);
      // Reload the page
      setTimeout(function () {
        window.location.reload();
      }, 3000);
    } catch (error) {
      const errorMessage =
        error.response?.data?.message || "An error occurred. Please try again.";
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

        <table class="table table-hover table-bordered mt-3">
          <thead class="thead-light">
            <tr>
              <th scope="col">S No</th>
              <th scope="col">Device ID</th>
              <th scope="col">Order ID</th>
              <th scope="col">Customers Name</th>
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
              <td>
                {{
                  device.first_name
                    ? device.first_name + " " + (device.last_name ?? "")
                    : "---"
                }}
              </td>
              <td>{{ formatDate(device.date_time) }}</td>
              <td class="d-flex">
                <!-- view button -->
                <button
                  class="btn btn-light action-btn"
                  @click="viewDevice(device.id)"
                >
                  <i class="bi bi-eye-fill"></i>
                </button>

                <!-- Edit button -->
                <button
                  class="btn btn-warning text-white action-btn"
                  @click="editDevice(device.id)"
                >
                  <i class="bi bi-pencil-fill"></i>
                </button>

                <!-- Delete button -->
                <button
                    class="btn btn-danger action-btn"
                    :disabled="loadingButtons[device.id]"
                    @click="deleteDevice(device.id)"
                  >
                    <!-- Show trash icon if not loading -->
                    <span v-if="!loadingButtons[device.id]">
                      <i class="bi bi-trash-fill"></i>
                    </span>

                    <!-- Show spinner and Deleting text if loading -->
                    <span v-else>
                      <i class="spinner-border spinner-border-sm" role="status"></i>
                      Deleting...
                    </span>
                  </button>
                  <a
                      class="btn btn-warning action-btn"
                      :href="route('devices.map', {id: device.id})"
                  >
                      <i class="bi bi-geo-alt-fill"></i>
                  </a>
              </td>
            </tr>
          </tbody>
        </table>

        <!-- Pagination -->
        <div class="pagination-container">
          <nav aria-label="Page navigation">
            <ul class="pagination">
              <!-- Previous Button -->
              <li
                class="page-item"
                :class="{ disabled: devices.current_page === 1 }"
              >
                <a class="page-link" :href="`?page=${devices.current_page - 1}`"
                  >Previous</a
                >
              </li>

              <!-- Page Numbers -->
              <li
                v-for="page in totalPages"
                :key="page"
                class="page-item"
                :class="{ active: page === devices.current_page }"
              >
                <a class="page-link" :href="`?page=${page}`">{{ page }}</a>
              </li>

              <!-- Next Button -->
              <li
                class="page-item"
                :class="{
                  disabled: devices.current_page === devices.last_page,
                }"
              >
                <a class="page-link" :href="`?page=${devices.current_page + 1}`"
                  >Next</a
                >
              </li>
            </ul>
          </nav>
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
    devices: Object, // Paginated device data
  },
  computed: {
    totalPages() {
      return Array.from(
        { length: this.devices.last_page },
        (_, index) => index + 1
      );
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

    formatDate(value) {
      if (!value) return "---";

      // Create a new Date object from the ISO 8601 string
      const date = new Date(value);

      // Define the format options
      const dateOptions = {
        year: "numeric",
        month: "short",
        day: "numeric",
      };
      const timeOptions = {
        hour: "2-digit",
        minute: "2-digit",
        hour12: true,
      };

      // Format the date and time separately and combine them
      const formattedDate = date.toLocaleDateString("en-GB", dateOptions);
      const formattedTime = date.toLocaleTimeString("en-GB", timeOptions);

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
