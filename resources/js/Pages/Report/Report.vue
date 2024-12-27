<script setup>
import { ref, onMounted, watch } from 'vue';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import Datepicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";
import { Bootstrap5Pagination } from 'laravel-vue-pagination';
import axios from "axios";

// Define props
const props = defineProps({
  notificationsId: {
    type: Number,
    required: false,
  }
});

const openNotificationId = ref(null);

const notifications = ref([]);
const totalCount = ref("");
const vehicleRegister = ref("");
const customerName = ref("");
const deviceId = ref("");
const startDate = ref(null);
const endDate = ref(null);

const validationErrors = ref({ startDate: "", endDate: "" });


const NotificationCreateCount = () => {
    window.Echo.channel('notificationAlert')
        .listen('.alert.notification', (data) => {
            console.log('Total Count data:', data);
            totalCount.value++;
        });
};

const notificationGetAll = () => {
  window.Echo.channel('notificationAlert')
    .listen('.alert.notification', async (data) => {
        console.log('Received all message:', data);
        try {
            const response = await axios.get('/api/allNotifications');
            notifications.value = response.data.notifications;
        } catch (error) {
            console.error('Error fetching notifications:', error);
        }
    });
};


// Validate the date range
const validateDates = () => {
  validationErrors.value.startDate = "";
  validationErrors.value.endDate = "";

  if (startDate.value && endDate.value) {
    const start = new Date(startDate.value);
    const end = new Date(endDate.value);
    if (start > end) {
      validationErrors.value.startDate = "Start Date must be less than or equal to End Date.";
      validationErrors.value.endDate = "End Date must be greater than or equal to  Start Date.";
      return false;
    }
  }
  return true;
};

const getData = async(page = 1) =>{

  if (!validateDates()) {
    return;
  }
  try {

    const res = await axios.get(`api/allNotifications`, {
      params: {
        page,
        vehicleRegisterSearch: vehicleRegister.value,
        customerSearch : customerName.value,
        device_id: deviceId.value,
        startDate: startDate.value,
        endDate: endDate.value,
        
      },
    });
    
    notifications.value = res.data.notifications;
    totalCount.value = res.data.totalCount;
  } catch (error) {
    console.error('Error fetching data:', error);
  }
};

// Watchers for date validation
watch([startDate, endDate], () => {
  validateDates();
  getData();
});

onMounted(() => {
    if (props.notificationsId) {
      openNotificationId.value = props.notificationsId;
    };
    getData();
    NotificationCreateCount();
    notificationGetAll();
});

const toggleNotification = async (id) => {
  const notification = notifications.value.data.find((n) => n.id === id);
  if (notification) {
   
    if (notification.readUnreadStatus === 0) {
      try {
        // Update notification read unread status 
        await axios.post(`/api/notifications/${id}/mark-as-read`);
        notification.readUnreadStatus = 1;
      } catch (error) {
        console.error("Error marking notification as read:", error);
      }
    }
    openNotificationId.value = openNotificationId.value === id ? null : id;
  }
};
</script>
<template>
  <Head title="device" />

  <AuthenticatedLayout>
    <div class="mt-5">
      <div class="table-container">

        <div class="d-flex justify-content-between align-items-center">
            <h4><i class="fa fa-filter mr-2"></i>Filters Notifications </h4>

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
        <div class="row align-items-center mt-4 notifications-searchbar ">
            <div class="form-group col-md-4">
              <input v-model="customerName" type="text" class="form-control pe-5" placeholder="" @input="getData" />
              <label for="customerName" class="form-label">Customer Name</label>
            </div>


            <div class="form-group col-md-4">
              <input v-model="deviceId" type="text" class="form-control" placeholder="" @input="getData" />
              <label for="deviceId" class="form-label">Device Id</label>
            </div>

            <div class="form-group col-md-4">
              <input v-model="vehicleRegister" type="text" class="form-control" placeholder="" @input="getData" />
              <label for="vehicleRegister" class="form-label">Vehicle Register Number</label>
            </div>
        </div>
        <div class="row align-items-center mt-3 notifications-searchbar-border">
          <div class="form-group row align-items-center col-md-8 mx-auto">
              <div class="form-group m-0 col-md-6">
                <input type="date" v-model="startDate" @input="getData" class="form-control" />
                <label for="startDate" class="form-label">Start Date</label>
                <small v-if="validationErrors.startDate" class="text-danger">{{ validationErrors.startDate }}</small>

              </div>
              <div class="form-group m-0 col-md-6 boder-class">
                <input type="date" v-model="endDate" @input="getData" class="form-control" />
                <label for="endDate" class="form-label">End Date</label>
                <small v-if="validationErrors.endDate" class="text-danger">{{ validationErrors.endDate }}</small>
              </div>
            </div>
        </div>

      </div>

      <section class="notifications">
        <div class="notification-card">
          <!-- Header -->
          <div class="notification-header">
            <h5>Notifications <span class="badge bg-primary">{{ totalCount }}</span></h5>
            <span class="mark-all">Mark all as read</span>
          </div>

          <!-- Notification List -->
          <div class="notifications-list" v-for="notification in notifications.data" :key="notification.id">
            <div class="notification-main" v-if="notification.readUnreadStatus === 0">
              <div class="notification-item" @click="toggleNotification(notification.id)">
                <div class="icon-circle mr-3">
                  <i class="fas fa-sync-alt"></i>
                </div>
               
                <div class="notification-content">
                  <p><span class="highlight">{{ notification.message }}</span></p>
                  <small>{{ notification.date }}</small>
                </div>
                <i class="unread-dot"></i>
              </div>
              <div v-show="openNotificationId === notification.id" class="notification-details">
                <p>Message : {{ notification.message }}</p>
                <p>Device Id : {{ notification.deviceId }}</p>
                <p>Customer Name : {{ notification.customerName }}</p>
                <p>Coordinates : {{ notification.coordinates }} </p>
                <p>Location : {{ notification.location }}</p>
                <p>Alert Type : {{ notification.alertType }}</p>
                <p>Vehicle Register Number : {{ notification.vehicleRegisterNumber }}</p>
                <p  class="mt-2"><strong>Captures:</strong></p>
                  <img v-if="notification.captures" width="150px" class="p-1 invoice-image" :src="notification.captures" alt="Invoice photos">
                  <p v-else class="p-4">No Captures available</p>
                
              </div>
            </div>

            <!-- Notification Item 3 -->
            <div class="notification-main" v-if="notification.readUnreadStatus === 1">
              <div class="notification-item" @click="toggleNotification(notification.id)">
                <div class="icon-circle mr-3">
                  <i class="fas fa-sync-alt"></i>
                </div>
                <div class="notification-content">
                  <p><span class="highlight">{{ notification.message }}</span></p>
                  <small>{{ notification.date }}</small>
                </div>
              </div>
              <div v-show="openNotificationId === notification.id" class="notification-details row">
                <ul class="col-md-8 col-12 list-group-notification">
                  <li><b>Customer Name:</b> {{ notification.customerName }}</li>
                  <li><b>Device Id:</b> {{ notification.deviceId }}</li>
                  <li><b>Alert Type:</b> {{ notification.alertType }}</li>
                  <li><b>Coordinates:</b> {{ notification.coordinates }}</li>
                  <li><b>Location:</b> {{ notification.location }}</li>
                  <li><b>Vehicle Register Number:</b> {{ notification.vehicleRegisterNumber }}</li>
                </ul>
                <div class="col-md-4 col-12">
                <p class="mt-2"><strong>Captures:</strong></p>
                <div class="capture-container">
                  <img v-if="notification.captures"  class="p-1 invoice-image" :src="notification.captures" alt="Invoice photos">
                  <p v-else class="p-4">No Captures available</p>
                </div>
              </div>
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
  }
</style>
<style scoped>
.capture-container {
    width: 85%;
}
.notification-details li {
    padding-bottom: 10px;
}
.list-group-notification{
  list-style-type: disc;
}
.notifications-searchbar-border .boder-class .form-control[data-v-9e7b69c8] {
    border-right: 1px solid rgb(209 213 219) !important;
    border-left: 0;
    border-radius: 0 4px 4px 0;
}
.notifications-searchbar .form-label {
    left: 20px;
}
.notifications-searchbar-border .form-group.m-0.col-md-6 {
    padding: 0;
}
.notifications-searchbar-border .form-control[data-v-9e7b69c8] {
    border-right: 0;
    border-radius: 4px 0 0px 4px;
}
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
      padding: 20px 30px;
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
  .notifications-searchbar-border .boder-class .form-control {
    border-right:  1px solid rgb(209 213 219) !important;
    border-left:  1px solid rgb(209 213 219) !important;
    border-radius: 4px !important;
    margin-bottom: 20px;
}

.notifications-searchbar-border .form-group.m-0.col-md-6 {
    padding: 0 !important;
    margin-bottom: 10px  !important;
    margin-top: 10px  !important;
}
.notifications-searchbar-border .form-control {
    border-right: 1px solid rgb(209 213 219) !important;
    border-radius: 4px !important;
}
.notifications-searchbar-border {
    margin-top: 0 !important;
}
  }


</style>