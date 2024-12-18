<script setup>
import { ref, onMounted } from "vue";
import ResponsiveNavLink from "@/Components/ResponsiveNavLink.vue";

const showingNavigationDropdown = ref(false);

// Props
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

// Reactive variable for the current date
const currentDate = ref("");

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
});
</script>
<template>
  <header class="header-main">
    <div
      v-if="role === 'admin'"
      class="d-flex justify-content-between align-items-center py-3 mb-4 border-bottom"
    >
      <div class="w-50">
        <!-- <h2 class="d-flex align-items-center">
                    {{ heading }}<img src="/storage/heand.png" class="ml-2" />
                </h2>-->
        <p class="header-date">Today is {{ currentDate }}</p>
      </div>
      <div class="d-flex align-items-center w-50 justify-content-end">
        <!-- <div class="position-relative mr-4 search-desktop">
          <input
            type="text"
            class="form-control mr-3 search-header"
            placeholder="Search here..."
            style="max-width: 300px"
          />
          <span class="search-icon-header"><i class="bi bi-search"></i></span>
        </div> -->
        <div class="icon-profile">
          <div class="dropdown">
            <button class="dropdown-toggle show" type="button" id="notificationDropdown" data-bs-toggle="dropdown" aria-expanded="true">
              <div class="icon-profile-bell">
                <i class="bi bi-bell animate-bell"></i>
                <!-- Notification badge for unread notifications -->
                <span class="notification-badge">{{ adminUnreadCount }}</span>
              </div>
            </button>
            <!-- <ul
              class="dropdown-menu notification-dropdown"
              aria-labelledby="notificationDropdown"
            >
              <li v-if="notifications.length === 0">
                <a class="dropdown-item" href="#">No new notifications</a>
              </li>
              <li v-for="notification in notifications" :key="notification.id">
                <a class="dropdown-item" :href="notification.link">
                  {{ notification.title }} {{ notification.date }}
                </a>
              </li>
            </ul> -->
            <ul
              class="dropdown-menu notification-dropdown"
              aria-labelledby="notificationDropdown"
              data-popper-placement="bottom-end"
            >
              <h5 class="dropdown-header-noti">
                <span class="notification-title">Notifications</span>
              </h5>
              <div class="d-flex align-items-center icon-box-profile" v-for="notification in notifications" :key="notification.id">
                <a :href="`/report/${notification.id}`">
                  <div class="icon-circle mr-3">
                    <i class="fas fa-sync-alt"></i>
                  </div>
                  <div>
                    <div style="line-height: 20px">
                      <span class="status-text">{{ notification.title }}</span>
                    </div>
                    <div class="d-flex align-items-center muted-text mt-1">
                      <span class="time"
                        ><i class="bi bi-clock mr-1"></i> {{ notification.date }}</span
                      >
                    </div>
                  </div>
                </a>
              </div>
            </ul>
          </div>
          <div class="dropdown img-header">
            <img
              src="https://via.placeholder.com/40"
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
    <div
      v-else
      class="d-flex justify-content-between align-items-center py-3 mb-4 border-bottom"
    >
      <div class="w-50">
        <p class="header-date">Today is {{ currentDate }}</p>
      </div>
      <div class="d-flex align-items-center w-50 justify-content-end">
        <!-- <div class="position-relative mr-4 search-desktop">
          <input
            type="text"
            class="form-control mr-3 search-header"
            placeholder="Search here..."
            style="max-width: 300px"
          />
          <span class="search-icon-header"><i class="bi bi-search"></i></span>
        </div> -->
        <div class="icon-profile">
          <div class="dropdown">
            <button class="dropdown-toggle show" type="button" id="notificationDropdown" data-bs-toggle="dropdown" aria-expanded="true">
              <div class="icon-profile-bell">
                <i class="bi bi-bell animate-bell"></i>
                <!-- Notification badge for unread notifications -->
                <span class="notification-badge">4</span>
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
              <div class="d-flex align-items-center icon-box-profile">
                <div class="icon-circle mr-3">
                  <i class="fas fa-sync-alt"></i>
                </div>
                <div>
                  <div style="line-height: 20px">
                    <span class="status-text">Your bid is placed</span>
                    <span class="text-muted"> waiting for auction ended</span>
                  </div>
                  <div class="d-flex align-items-center muted-text mt-1">
                    <span class="time"
                      ><i class="bi bi-clock mr-1"></i> 24 Minutes ago</span
                    >
                  </div>
                </div>
              </div>

              <div class="d-flex align-items-center icon-box-profile">
                <div class="icon-circle mr-3">
                  <i class="fas fa-sync-alt"></i>
                </div>
                <div>
                  <div style="line-height: 20px">
                    <span class="status-text">Your bid is placed</span>
                    <span class="text-muted"> waiting for auction ended</span>
                  </div>
                  <div class="d-flex align-items-center muted-text mt-1">
                    <span class="time"
                      ><i class="bi bi-clock mr-1"></i> 24 Minutes ago</span
                    >
                  </div>
                </div>
              </div>

              <div class="d-flex align-items-center icon-box-profile">
                <div class="icon-circle mr-3">
                  <i class="fas fa-sync-alt"></i>
                </div>
                <div>
                  <div style="line-height: 20px">
                    <span class="status-text">Your bid is placed</span>
                    <span class="text-muted"> waiting for auction ended</span>
                  </div>
                  <div class="d-flex align-items-center muted-text mt-1">
                    <span class="time"
                      ><i class="bi bi-clock mr-1"></i> 24 Minutes ago</span
                    >
                  </div>
                </div>
              </div>
              <div class="d-flex align-items-center icon-box-profile">
                <div class="icon-circle mr-3">
                  <i class="fas fa-sync-alt"></i>
                </div>
                <div>
                  <div style="line-height: 20px">
                    <span class="status-text">Your bid is placed</span>
                    <span class="text-muted"> waiting for auction ended</span>
                  </div>
                  <div class="d-flex align-items-center muted-text mt-1">
                    <span class="time"
                      ><i class="bi bi-clock mr-1"></i> 24 Minutes ago</span
                    >
                  </div>
                </div>
              </div>
            </ul>
          </div>
          <div class="dropdown img-header">
            <img
              src="https://via.placeholder.com/40"
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
                  class="nav-link d-flex px-0 justify-content-between align-items-center"
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

<script>
import { Link as InertiaLink } from "@inertiajs/vue3";
import toastr from "toastr";
import "toastr/build/toastr.min.css";
import "@fortawesome/fontawesome-free/css/all.min.css";
import Pusher from "pusher-js";

export default {
  name: "Notification",
  data() {
    return {
      notifications: [],
      adminUnreadCount: 0,
    };
  },
  components: {
    InertiaLink,
  },
  mounted() {
    this.getNotification();
    this.getAdminUnreadNotifications();
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
      if (data && this.role === "admin") {
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
    pusher.connection.bind("connected", function () {
      console.log("Pusher connected");
    });
  },
  methods: {
    async getNotification() {
      try {
        const response = await axios.get("/api/get-notification");
        this.notifications = response.data.notifications || [];
      } catch (error) {
        console.error("Error fetching notifications:", error);
      }
    },
    async getAdminUnreadNotifications () {
      try {
        const response = await axios.get("/api/admin-unread-notification");
        this.adminUnreadCount = response.data.adminUnreadCount;
      } catch (error) {
        console.error("Error fetching unread admin unread count:", error);
      }
    },
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
</style>

