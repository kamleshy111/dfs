<script setup>
import { ref, onMounted } from "vue";
import ResponsiveNavLink from "@/Components/ResponsiveNavLink.vue";
import toastr from "toastr";
import "toastr/build/toastr.min.css";
import "@fortawesome/fontawesome-free/css/all.min.css";
import Pusher from "pusher-js";
import Echo from 'laravel-echo';
import axios from 'axios';

const notifications = ref([]);
const adminUnreadCount = ref(0);
const props = defineProps({
    role: {
        type: String,
        required: true,
    },
    heading: {
        type: String,
        default: "Dashboard", // Default value if no heading is provided
    },
});

const currentDate = ref("");

const fetchNotifications = async () => {
    try {
        const response = await axios.get('/api/get-notification');
        notifications.value = response.data.notifications || [];
        adminUnreadCount.value = response.data.adminUnreadCount || 0;
    } catch (error) {
        console.error('Error fetching notifications:', error);
    }
};

const readNotification = () => {
    window.Echo.channel('notificationAlert')
        .listen('.alert.notification', fetchNotifications);

    window.Echo.channel('read-title')
        .listen('.read.notification', fetchNotifications);

};

onMounted(() => {
  const today = new Date();
  const dayNames = [
    "Sunday",
    "Monday",
    "Tuesday",
    "Wednesday",
    "Thursday",
    "Friday",
    "Saturday",
  ];
  const monthNames = [
    "January",
    "February",
    "March",
    "April",
    "May",
    "June",
    "July",
    "August",
    "September",
    "October",
    "November",
    "December",
  ];

  // Format the current date
  const day = dayNames[today.getDay()];
  const date = today.getDate();
  const month = monthNames[today.getMonth()];
  const year = today.getFullYear();

  currentDate.value = `${day}, ${date} ${month} ${year}`;

    fetchNotifications();
    readNotification();
});

</script>
<template>
  <header class="header-main">
    <div
      class="d-flex justify-content-between align-items-center py-3 mb-4 border-bottom"
    >
      <div class="w-50">
        <!-- <p class="header-date new-header-dates">Today is {{ currentDate }}</p> -->
      </div>
      <div class="d-flex align-items-center w-50 justify-content-end">
        <div class="icon-profile">
          <div class="dropdown">
            <button class="dropdown-toggle show" type="button" id="notificationDropdown" data-bs-toggle="dropdown" aria-expanded="true">
              <div class="icon-profile-bell">
                <i class="bi bi-bell animate-bell"></i>
                <span class="notification-badge">{{ adminUnreadCount }}</span>
              </div>
            </button>
            <ul
              class="dropdown-menu notification-dropdown"
              aria-labelledby="notificationDropdown"
              data-popper-placement="bottom-end"
            >
              <h5 class="dropdown-header-noti">
                <span class="notification-title">Notifications</span>
              </h5>
              <div class="icon-box-profile" v-for="notification in notifications.slice(0, 10)" :key="notification.id">
                  <a :href="`/report?id=${notification.id}`" class="d-flex align-items-center ">

                  <div class="icon-circle mr-3">
                    <i class="fas fa-sync-alt"></i>
                  </div>
                  <div>
                    <div style="line-height: 20px">
                      <span class="status-text">{{ notification.message }}</span>
                    </div>
                    <div class="d-flex align-items-center muted-text mt-1">
                      <span class="time"
                        ><i class="bi bi-clock mr-1"></i> {{ notification.date }}</span
                      >
                    </div>
                  </div>
                </a>
              </div>
              <div class="text-center mt-2">
                <a :href="`/report`" class="status-text">view more</a>
              </div>
            </ul>
          </div>
          <div class="dropdown img-header">
            <img
              src="https://img.icons8.com/officel/40/user.png"
              alt="Profile"
              class="rounded-circle"
              style="cursor: pointer"
              data-bs-toggle="dropdown"
              aria-expanded="true"
            />
            <ul
              class="dropdown-menu mt-2 p-2"
              data-popper-placement="bottom-end"
              style="
                position: absolute;
                inset: 0px 0px auto auto;
                margin: 0px;
                transform: translate(0px, 42px);
              "
            >
              <li
                class="nav-link d-flex px-0 justify-content-between align-items-center mb-2"
              >
                <a class="dropdown-item" :href="route('profile.edit')"
                  >Profile</a
                >
                <i class="bi bi-person dropdown-icon"></i>
              </li>
              <li class="nav-item">
                <ResponsiveNavLink
                  :href="route('logout')"
                  method="post"
                  as="button"
                  class="nav-link d-flex m-0 px-0 justify-content-between align-items-center"
                >
                  Log Out
                  <i class="bi bi-box-arrow-left dropdown-icon"></i>
                </ResponsiveNavLink>
              </li>
            </ul>
          </div>
        </div>
        <button class="toggle-btn">☰</button>
      </div>
    </div>
  </header>
</template>
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
</style>

