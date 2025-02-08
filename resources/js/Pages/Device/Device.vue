<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import { Inertia } from "@inertiajs/inertia";
import { ref } from "vue";
import { toast } from "vue3-toastify";
import "vue3-toastify/dist/index.css";
import axios from "axios";
import Swal from "sweetalert2";

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
    title: "S No",
    render: (data, type, row, meta) => meta.row + 1,
  },
  { data: "deviceId" },
  { data: "orderId" },
  { data: "customerName" },
  { data: "startData" },
  {
    title: "Actions",
    data: null,
    render: (data, type, row) => {
      return `
            <div class="icon-all-dflex">
              <a href="/devices/${data.id}/view" class="btn btn-light action-btn"><i class="bi bi-eye-fill"></i> </a>
              <a class="btn btn-warning text-white action-btn" href="devices/${data.id}/edit" ><i class="bi bi-pencil-fill"></i> </a>
              <button class="btn-danger  action-btn delete-btn" data-id="${data.id}"><i class="bi bi-trash-fill"></i></button>
              <a class="btn btn-warning action-btn" href="devices/map/${data.deviceId}"> <i class="bi bi-geo-alt-fill"></i></a>
              </div>
            `;
    },
  },
];

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

<template>
  <Head title="device" />

  <AuthenticatedLayout>
    <div class="mt-5">
      <div class="table-container new-client-table">
        <div class="d-flex justify-content-between align-items-center">
          <h4><i class="bi bi-truck-front-fill mr-2"></i>All Devices</h4>
          <div class="text-end mobile-btn-responsive">
            <div class="mr-2">
              <div v-if="showPopup" class="popup-overlay">
                <div class="popup">
                  <header class="popup-header">
                    <h2>Upload</h2>
                    <button @click="closePopup" class="close-btn">
                      <i class="bi bi-x"></i>
                    </button>
                  </header>
                  <p class="popup-description">
                    A Single file does not exceed 2Mb
                  </p>

                  <form
                    @submit.prevent="uploadExcel"
                    enctype="multipart/form-data"
                  >
                    <label for="file-upload" class="file-upload-icon">
                      <i class="fa fa-upload"></i>
                    </label>
                    <div class="upload-text-popup mb-2">
                        <h5>Upload your file</h5>
                      </div>
                    <input
                      type="file"
                      accept=".xlsx, .xls"
                      class="file-upload-input"
                      id="file-upload"
                      @change="onFileChange"
                    />
                    <div
                      v-if="fileName"
                      id="file-name"
                      class="file-name-display"
                    >
                      <b>Selected File:</b> {{ fileName }}
                    </div>

                    <div class="popup-actions">
                      <button type="submit" class="btn btn-success">
                        Start Upload
                      </button>
                      <button @click="closePopup" class="cancel-btn">
                        Cancle
                      </button>
                    </div>
                    <div class="file-p mt-3">
                      File Upload .xlsx, .xls
                      <a href="/sample.xlsx" download class="download-text"
                        >Downlod Sample</a
                      >
                    </div>
                    <div v-if="totalChunks" class="count-design">
                      <div v-if="isImporting">
                        <div class="loader"></div>
                      </div>
                      <p v-if="totalChunks">
                        Importing data... {{ currentChunk * 10 }}/{{
                          totalChunks * 10
                        }}
                      </p>
                      <p v-if="failed_count">
                        Failed count: {{ failed_count }}
                      </p>
                      <p v-if="success_count">
                        Success count: {{ success_count }}
                      </p>
                    </div>
                  </form>
                </div>
              </div>
            </div>
            <!-- <a class="mr-2">
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
            </a> -->

             <!-- Mobile menu button -->
              <button class="btn btn-primary btn-custom d-block d-md-none" id="mobilebtn">
                  <i class="bi bi-three-dots-vertical"></i>
              </button>
              <div class="d-none d-md-flex" id="desktopButtons">
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

              <!-- Popup mobile menu -->
              <div id="mobileMenu" class="mobile-menu" style="display: none;">
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
      fileName: "", // Store the selected file name
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
      $(document).on("click", ".delete-btn", (event) => {
        const deviceId = $(event.target).closest("button").data("id");
        self.deleteDevice(deviceId);
      });
    },
    deleteDevice(deviceId) {
      Swal.fire({
        title: "Are you sure?",
        text: "Do you want to delete this device?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!",
      }).then((result) => {
        if (result.isConfirmed) {
          axios
            .delete(`/devices/destroy/${deviceId}`)
            .then((response) => {
              Swal.fire("Deleted!", "Your device has been deleted.", "success");
              const index = this.devices.findIndex(
                (device) => device.id === deviceId
              );
              if (index !== -1) {
                this.devices.splice(index, 1);
              }
              console.log(response.data);
            })
            .catch((error) => {
              Swal.fire(
                "Error!",
                "Failed to delete the device. Please try again.",
                "error"
              );
              location.reload();
              console.error(error);
            });
        } else {
          console.log("Device canceled the delete action.");
        }
      });
    },
    // Method to handle file selection and display its name
    onFileChange(event) {
      this.file = event.target.files[0];
      this.fileName = this.file ? this.file.name : ""; // Update the file name
    },
    async uploadExcel() {
      let app = this;
      this.failed_count = 0;
      this.success_count = 0;

      if (!this.file || this.file.length === 0) {
        toast.error("Please select a file.", { autoClose: 3000 });
        return;
      }

      if (
        this.file.type !==
        "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
      ) {
        toast.error("Invalid file type. Only .xlsx files are allowed.", {
          autoClose: 3000,
        });
        this.file = null;
        return;
      }

      const formData = new FormData();
      formData.append("file", this.file);

      try {
        await axios
          .post("/devices/upload-excel", formData)
          .then(function (response) {
            console.log(response.data.totalChunks);
            app.totalChunks = response.data.totalChunks;
            app.currentChunk = 0;
            app.isImporting = true;

            // Start importing data in chunks
            app.importChunks();
          })
          .catch((error) => console.log(error));
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

          await axios
            .post("/devices/import-chunk", formData)
            .then(function (response) {
              app.failed_count += response.data.failed_count;
              app.success_count += response.data.success_count;
            });

          this.currentChunk++;
        } catch (error) {
          console.error("Error importing chunk:", error);
          break;
        }
      }

      this.isImporting = false;
      toast.success("Import completed!");
      setTimeout(() => {
        window.location.reload();
      }, 3000); // Reloads page after 3 seconds
    },
  },
  closePopup() {
    this.showPopup = false;
    this.file = null;
    this.uploadStatus = "Pending";
  },
  downloadTemplate() {
    window.open("/devices", "_blank");
  },
};
</script>

<style scoped>
.download-text {
    color: var(--light-color);
    text-decoration: underline;
}
.file-upload-input {
  display: none; /* Hide the default file input */
}

.file-upload-icon[data-v-63488cba] {
  cursor: pointer;
  font-size: 24px;
  color: #fff;
  background-color: var(--light-color);
  padding: 10px 18px;
  border-radius: 50%;
  border: 1px solid var(--light-color);
  transition: 0.3s;
}

.file-upload-icon:hover {
  color: var(--light-color);
  background-color: transparent !important;
}

/* Overlay for Popup */
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

/* Main Popup Box */
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

/* Header Section */
.popup-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  width: 100%;
  border-bottom: 2px solid #f0f0f0;
  padding-bottom: 10px;
  margin-bottom: 15px;
}

.popup-header h2 {
  font-size: 18px;
  font-weight: bold;
  margin: 0;
}

.popup .close-btn[data-v-63488cba] {
  background: #fff;
  padding: 8px;
  font-size: 16px;
  position: absolute;
  right: -10px;
  top: -18px;
  cursor: pointer;
  border-radius: 50%;
  box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
  width: 30px;
  height: 30px;
  display: flex;
  justify-content: center;
  align-items: center;
  font-size: 20px;
}

/* File Upload Section */
.file-upload-container {
  position: relative;
  display: flex;
  align-items: center;
  width: 100%;
  margin-bottom: 20px;
}

.file-input {
  padding: 10px;
  font-size: 1em;
  border-radius: 5px;
  border: 1px solid #ddd;
  margin-top: 10px;
  cursor: pointer;
  width: 100%;
}

.file-upload-button {
  background-color: transparent;
  border: none;
  cursor: pointer;
  font-size: 14px;
  display: inline-flex;
  align-items: center;
  margin-right: 15px;
}

.file-upload-button svg {
  width: 24px;
  height: 24px;
  fill: #b70900;
  margin-right: 10px;
}

.file-table {
  width: 100%;
  border-collapse: collapse;
  font-size: 0.9em;
  margin-bottom: 20px;
}

.file-table th,
.file-table td {
  padding: 12px;
  text-align: left;
  border-bottom: 1px solid #ddd;
}

.file-table th {
  background-color: #f4f4f4;
}

.file-table tr:hover {
  background-color: #f9f9f9;
}

/* Buttons Section */
.popup-actions[data-v-63488cba] {
    display: flex;
    justify-content: center;
    width: 100%;
    margin-top: 20px;
    gap: 10px;
}
.popup .btn.btn-success {
  background-color: transparent;
  border: 1px solid var(--light-color);
  font-size: 14px;
  color: var(--light-color);
  padding: 0px 8px;
  border-radius: 4px;
}

.upload-btn {
  padding: 12px 20px;
  border-radius: 4px;
  cursor: pointer;
  display: inline-block;
  border: 1px solid var(--light-color);
  background-color: #28a745;
  color: white;
  font-size: 13px;
}

.upload-btn:hover {
  color: #fff;
  background-color: #218838;
}

.cancel-btn[data-v-63488cba][data-v-63488cba] {
    background-color: transparent;
    color: #333;
    padding: 6px 14px;
    border-radius: 4px;
    cursor: pointer;
    border: 1px solid #333;
    font-size: 13px;
}

.cancel-btn:hover {
  background-color: #ccc;
}

.download-btn:hover,
.upload-btn:hover,
.cancel-btn:hover {
  background-color: transparent;
}

/* Progress Section */
.count-design {
  width: 100%;
  margin-top: 20px;
  text-align: center;
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
.popup form {
  box-shadow: unset;
  padding: 0;
  margin-top: 15px;
  text-align: center;
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
    width: 30px;
    height: 30px;
    margin-right: 10px;
  }
}
</style>
