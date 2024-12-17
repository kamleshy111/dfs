<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import { Inertia } from "@inertiajs/inertia";
import { ref } from "vue";
const loadingButtons = ref({});


// Define props
defineProps({
  notifications: {
    type: Array, // Corrected type: Array for table data
    required: true,
  },
  notificationCount: {
    type: Number,
    required: true,
  }
});

$(document).ready(function() {
    $('.notification-item').on('click', function() {
        var details = $(this).next('.notification-details'); // Get the corresponding .notification-details

        // Collapse all other open details
        $('.notification-details.open').not(details).removeClass('open');

        // Toggle the clicked notification's details
        details.toggleClass('open');
    });

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
      <div class="table-container">

        <div class="d-flex justify-content-between align-items-center">
            <h4><i class="bi bi-truck-front-fill mr-2"></i>All Report</h4>

           <div class="text-end mobile-btn-responsive">
              <!-- Mobile menu button -->
              <button class="btn btn-primary btn-custom d-block d-md-none" id="mobilebtn">
                  <i class="bi bi-three-dots-vertical"></i>
              </button>
              <div class="d-none d-md-flex" id="desktopButtons">
                <a :href="route('devices.map')">
              <button class="btn btn-primary btn-custom">
                <i class="bi bi-geo-alt-fill mr-2"></i>View on Map
              </button>
            </a>
              </div>

              <!-- Popup mobile menu -->
              <div id="mobileMenu" class="mobile-menu" style="display: none;">
                <a :href="route('devices.map')">
              <button class="btn btn-primary btn-custom">
                <i class="bi bi-geo-alt-fill mr-2"></i>View on Map
              </button>
            </a>
              </div>
           </div>
        </div>

      </div>

     <section class="notifications">
    <div class="notification-card">
        <!-- Header -->
        <div class="notification-header">
            <h5>Notifications <span class="badge bg-primary">{{ notificationCount }}</span></h5>
            <span class="mark-all">Mark all as read</span>
        </div>

        <!-- Notification List -->
        <div class="notifications-list" v-for="notification in notifications" :key="notification.id">
            <!-- Notification Item 1 -->
            <div class="notification-main" v-if="notification.status === 0">
                <div class="notification-item">
                   <div class="icon-circle mr-3" data-v-2245cac1=""><i class="fas fa-sync-alt" data-v-2245cac1=""></i></div>
                    <div class="notification-content">
                        <p><span class="highlight">{{ notification.title }}</span></p>
                        <small>{{ notification.date }}</small>
                    </div>
                    <div class="unread-dot"></div>
                </div>
                <div class="notification-details">
                    <p>{{ notification.body }}.</p>
                </div>
            </div>

            <!-- Notification Item 3 -->
            <div class="notification-main" v-if="notification.status === 1">
                <div class="notification-item">
                    <div class="icon-circle mr-3" data-v-2245cac1=""><i class="fas fa-sync-alt" data-v-2245cac1=""></i></div>
                    <div class="notification-content">
                        <p><span class="highlight">{{ notification.title }}</span></p>
                        <small>{{ notification.date }}</small>
                    </div>
                </div>
                <div class="notification-details">
                    <p>{{ notification.body }}.</p>
                </div>
            </div>
        </div>
    </div>
</section>



    </div>
  </AuthenticatedLayout>
</template>

<style scoped>
.notification-card {
    margin: 50px auto;
    background-color: white;
    border-radius: 10px;
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

.notification-header {
    padding: 15px 20px;
    border-bottom: 1px solid #f0f0f0;
}

.notification-header h5 {
    font-weight: bold;
    display: inline-block;
    margin: 0;
}
.notification-header .badge {
    background: #2196f3 !important;
}

.notification-header .mark-all {
    float: right;
    font-size: 0.9rem;
    color: #007bff;
    cursor: pointer;
}

.notifications-list {
    padding: 10px;
}

.notification-main[data-v-9e7b69c8] {
    padding: 5px;
    box-shadow: rgba(100, 100, 111, 0.2) 0px 7px 29px 0px;
    border-radius: 10px;
}
.notification-item {
    display: flex;
    align-items: center;
    padding: 12px 20px;
    border-bottom: 2px solid #f0f0f0;
    cursor: pointer;
    position: relative;
    background-color: #f9f9f9;
}

.notification-item:hover {
    background-color: #f1f1f1ba;
}

.notification-item .icon-circle {
    background-color: #2196f3;
    color: #fff;
}
.notification-content {
    flex-grow: 1;
}

.notification-content p {
    margin: 0;
    font-size: 0.9rem;
}

.notification-content small {
    color: gray;
    font-size: 0.8rem;
}

.notification-content .highlight {
    color: #2196f3;
    font-weight: bold;
}

.unread-dot {
    height: 8px;
    width: 8px;
    background-color: red;
    border-radius: 50%;
    margin-left: 8px;
}

.notification-details {
    max-height: 0;
    overflow: hidden;
    padding: 0 10px;
    background-color: #f1f1f1;
    margin-top: -5px;
    border-radius: 5px;
    transition: max-height 0.3s ease-out, padding 0.3s ease-out;
}

.notification-details.open {
    max-height: 200px;
    padding: 10px;
}

@media (max-width: 767px){
    .notification-content p {
    margin: 0;
    font-size: 12px;
}
.notification-item {
    padding: 12px 10px;
}
.icon-circle {
    width: 50px;
    font-size: 20px;
}
.unread-dot {
    width: 10px;
}
}


</style>