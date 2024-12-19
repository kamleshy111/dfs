<script setup>
import { ref, onMounted } from 'vue';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import Datepicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";
import { Bootstrap5Pagination } from 'laravel-vue-pagination';
import axios from "axios";

// Define props
const props = defineProps({
  notificationCount: {
    type: Number,
    required: true,
  },
  notificationsId: {
    type: Number,
    required: false,
  }
});

const openNotificationId = ref(null);

const notifications = ref([]);
const searchQuery = ref("");
const customerName = ref("");
const deviceId = ref("");
const date = ref(null);


const getData = async(page = 1) =>{
  try {

    const res = await axios.get(`api/allNotifications`, {
      params: {
        page,
        search: searchQuery.value,
        customerSearch : customerName.value,
        start_date: date.value,
        device_id: deviceId.value,
      },
    });
    notifications.value = res.data;
  } catch (error) {
    console.error('Error fetching data:', error);
  }
};

onMounted(() => {
    if (props.notificationsId) {
      openNotificationId.value = props.notificationsId;
    };
    getData();
});

const toggleNotification = async (id) => {
  if (id) {
    try {
      const response = await axios.post(`/api/notifications/${id}/mark-as-read`);
      console.log("Notification marked as read:", response.data);
    } catch (error) {
      console.error("Error marking notification as read:", error);
    }
  }

  openNotificationId.value = openNotificationId.value === id ? null : id;

};
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
        <div class="d-flex justify-content-between align-items-center mt-2">



            <div class="form-group col-md-3">
              <input v-model="customerName" type="text" class="form-control" placeholder="" @input="getData" />
              <label for="customerName" class="form-label">Customer Name</label>
            </div> 

            <div class="form-group col-md-3">
              <input v-model="deviceId" type="text" class="form-control" placeholder="" @input="getData" />
              <label for="deviceId" class="form-label">Device Id</label>
            </div>

            <div class="form-group col-md-3">
              <input v-model="searchQuery" type="text" class="form-control" placeholder="" @input="getData" />
              <label for="searchQuery" class="form-label">Search title and body</label>
            </div>

            <div class="form-group col-md-3">
              <input type="date" v-model="date" @input="getData" class="form-control" />
              <label for="date" class="form-label">Date</label>
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
          <div class="notifications-list" v-for="notification in notifications.data" :key="notification.id">
            <div class="notification-main" v-if="notification.status === 0">
              <div class="notification-item" @click="toggleNotification(notification.id)">
                <div class="icon-circle mr-3">
                  <i class="fas fa-sync-alt"></i>
                </div>
               
                <div class="notification-content">
                  <p><span class="highlight">{{ notification.title }}</span></p>
                  <small>{{ notification.date }}</small>
                </div>
                <i class="unread-dot"></i>
              </div>
              <div v-show="openNotificationId === notification.id" class="notification-details">
                <p>{{ notification.body }}.</p>
              </div>
            </div>

            <!-- Notification Item 3 -->
            <div class="notification-main" v-if="notification.status === 1">
              <div class="notification-item" @click="toggleNotification(notification.id)">
                <div class="icon-circle mr-3">
                  <i class="fas fa-sync-alt"></i>
                </div>
                <div class="notification-content">
                  <p><span class="highlight">{{ notification.title }}</span></p>
                  <small>{{ notification.date }}</small>
                </div>
              </div>
              <div v-show="openNotificationId === notification.id" class="notification-details">
                <p>{{ notification.body }}.</p>
              </div>
            </div>
          </div>
          <Bootstrap5Pagination 
                :data="notifications" 
                @pagination-change-page="getData"
            />
        </div>
      </section>
    </div>
  </AuthenticatedLayout>
</template>
<style>
.notification-card .pagination {
    justify-content: center;
    margin: 10px 0;
}</style>
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
    padding: 10px 10px;
    background-color: #f1f1f1;
    margin-top: -5px;
    border-radius: 5px;
    transition: max-height 0.3s ease-out, padding 0.3s ease-out;
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