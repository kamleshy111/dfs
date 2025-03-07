<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";

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
    { data: 'customerName' },
    { data: 'deviceName' },
    { data: 'startData'},
    { data: 'duration'},
    { data: 'expiresDate' },
];

$(document).ready(function() {
    // Check if the mobile menu was open before
    if (localStorage.getItem('mobileMenuVisible') === 'true') {
        $('#mobileMenu').show();
    }

    // Toggle mobile menu visibility and update localStorage
    $('#mobilebtn').click(function(e) {
        e.preventDefault();
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
  <Head title="Billings" />

  <AuthenticatedLayout>
    <div class="my-3">
      <div class="form-container shadow">
        <div class="table-container new-client-table">
          <div class="d-flex justify-content-between align-items-center">
             <h4 class="responsive-btn">
              <i class="bi bi-people-fill mr-2"></i>All Billings
            </h4>

           <div class="text-end mobile-btn-responsive">
              <!-- Mobile menu button -->
              <button class="btn btn-primary btn-custom d-block d-md-none" id="mobilebtn">
                  <i class="bi bi-three-dots-vertical"></i>
              </button>
              <div class="d-none d-md-flex" id="desktopButtons">
                 <a :href="route('dashboard')">
                <button class="btn btn-primary btn-custom">
                  Dashboard
                </button>
              </a>
              </div>

              <!-- Popup mobile menu -->
              <div id="mobileMenu" class="mobile-menu" style="display: none;">
                <a :href="route('dashboard')">
                <button class="btn btn-primary btn-custom">
                  Dashboard
                </button>
              </a>
              </div>
           </div>
        </div>
          <div class="table-responsive">
            <!-- DataTable Component -->
            <DataTable :data="devices" :columns="columns">
              <thead class="thead-light">
                <tr>
                  <th scope="col">S No</th>
                  <th scope="col">customer Name</th>
                  <th scope="col">Device ID</th>
                  <th scope="col">Start Date</th>
                  <th scope="col">Duration</th>
                  <th scope="col">Expired Date</th>
                </tr>
              </thead>
            </DataTable>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<!-- <script>
import toastr from "toastr";
import "toastr/build/toastr.min.css";
import "@fortawesome/fontawesome-free/css/all.min.css";
import Pusher from "pusher-js";

export default {
  name: "Notification",
  mounted() {
    Pusher.logToConsole = true;

    // Initialize Pusher
    const pusher = new Pusher("ba58f8359f6318f23ee1", {
      cluster: "ap2",
    });

    // Subscribe to the channel
    const channel = pusher.subscribe("notification");

    // Bind to the event
    channel.bind("test.notification", (data) => {
      console.log("Received data:", data); // Debugging line

      // Display Toastr notification with icons and inline content
      if (data.vehicleId && data.title) {
        toastr.info(
          `<div class="notification-content">
            <i class="fas fa-user"></i> <span>   ${data.vehicleId}</span>
            <i class="fas fa-user"></i> <span>   ${data.title}</span>
            <i class="fas fa-book" style="margin-left: 20px;"></i> <span>${data.body}</span>
          </div>`,
          "New Post Notification",
          {
            closeButton: true,
            progressBar: true,
            timeOut: 0, // Set timeOut to 0 to make it persist until closed
            extendedTimeOut: 0, // Ensure the notification stays open
            positionClass: "toast-top-right",
            enableHtml: true,
          }
        );
      } else {
        console.error("Invalid data received:", data);
      }
    });

    // Debugging line
    pusher.connection.bind("connected", function () {
      console.log("Pusher connected");
    });
  },
};
</script>

<style scoped>
/* Custom style for Toastr notifications */
.toast-info .toast-message {
  display: flex;
  align-items: center;
}

.toast-info .toast-message i {
  margin-right: 10px;
}

.toast-info .toast-message .notification-content {
  display: flex;
  flex-direction: row;
  align-items: center;
}
</style> -->

