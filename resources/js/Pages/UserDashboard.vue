<template>
  <Head title="Client Dashboards" />

  <AuthenticatedLayout>
    <div class="container my-3">
      <!-- Summary Cards -->
      <div class="dropdowns-1">
        <button
          class="btn dropdown-toggles-1 main-btn-sec-1"
          type="button"
          id="dropdownMenuButton"
          data-bs-toggle="dropdown"
          aria-expanded="false"
        >
          <li class="dropdown-itemse drop-li-down" style="list-style: none;"
          >
            <div class="card-summarys-1 card-icons-1">
              <div class="icon-box">
                <!-- <h4>{{ adminStats.all_device_count }}</h4> -->
                <h4>{{ deviceStatus.deviceCount }}</h4>
                <p>All Devices</p>
              </div>
              <!-- <div class="selected_class-1 select-card-icn">
                <i
                  v-if="selected_filter == 'all'"
                  class="bi bi-chevron-down main-chevron-down"
                ></i>
              </div> -->
            </div>
          </li>
        </button>
        <!-- <ul
          class="dropdown-menu button-menu-drop-1"
          aria-labelledby="dropdownMenuButton"
        >
          <li @click="reloadMarkers(1)" class="dropdown-itemse">
            <div class="card-summarys-1">
              <div class="icon-box">
                <h4>{{ adminStats.active_device_count }}</h4>
                <p>Active Devices</p>
              </div>
              <div class="selected_class">
                    <i v-if="selected_filter == 1" class="bi bi-check-circle-fill"></i>
                </div>
            </div>
          </li>
          <li @click="reloadMarkers(0)" class="dropdown-itemse">
            <div class="card-summarys-1">
              <div class="icon-box">
                <h4>{{ adminStats.inactive_device_count }}</h4>
                <p>Inactive Devices</p>
              </div>
              <div class="selected_class">
                    <i v-if="selected_filter == 0" class="bi bi-check-circle-fill"></i>
                </div>
            </div>
          </li>
          <li @click="reloadMarkers(2)" class="dropdown-itemse">
            <div class="card-summarys-1">
              <div class="icon-box">
                <h4>{{ adminStats.expired_device_count }}</h4>
                <p>Expired Devices</p>
              </div>
              <div class="selected_class">
                    <i v-if="selected_filter == 2" class="bi bi-check-circle-fill"></i>
                </div>
            </div>
          </li>
        </ul> -->
          <!-- Device Dropdown List -->
          <ul v-if="deviceStatus.deviceStatus && deviceStatus.deviceStatus.length" class="dropdown-menu button-menu-drop-1" aria-labelledby="dropdownMenuButton">
            <li v-for="(device, index) in deviceStatus.deviceStatus" :key="index">
              
                <span v-if="device.status === 1" class="text-success">
                  <a :href="`/devices/map/${device.deviceId}`" class="text-success">
                    <strong>Device ID:</strong> <span>{{ device.deviceId }}</span>
                  </a>
                </span>


              <span v-else-if="device.status == 2"  class="text-black main-text-wrap-1">
                <a :href="`/devices/map/${device.deviceId}`" class="text-black">
                    <strong>Device ID:</strong> <span>{{ device.deviceId }}</span>
                  </a>
              </span>

              <span v-else class="text-color-change">
                <a :href="`/devices/map/${device.deviceId}`" class="text-brown">
                  <strong>Device ID:</strong> <span >{{ device.deviceId }}</span> 
                </a>
              </span>
            </li>
          </ul>
      </div>

      <div class="row table-container m-0">
        <Map :locations="locations" :zoom="5" />
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import axios from "axios";
import { ref, onMounted, watch } from "vue";
import { Head, usePage } from "@inertiajs/vue3";
import Map from "@/Components/Map.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

const { role, userId } = usePage().props;

const locations = ref([]);
const deviceStatus = ref({ deviceCount: 0, deviceStatus: [] });
/*const adminStats = ref({
  customer_amounts: 0,
  all_device_count: 0,
  active_device_count: 0,
  expired_device_count: 0,
  inactive_device_count: 0,
});
const selected_filter = ref("all");*/

const NotificationCreate = () => {
  console.log("data dashboardLink :");
  /* window.Echo.channel('notification')
        .listen('.received.notification', (data) => {
            console.log('Total Count data dashboardLink :', data);
            locations.value = data.locations;
            adminStats.value = data.adminStats;
        });
  window.Echo.channel("notification").listen(
    ".received.notification",
    loadMapData()
  );*/
  window.Echo.channel("notification").listen(
    ".received.notification",
    // getAdminStats(),
    getDeviceStatus(),
  );
};

onMounted(() => {
  // loadMapData();
  // getAdminStats();
  getDeviceStatus();
  NotificationCreate();
  window.onload = () => {
    const dashboardLink = document.querySelector(".sidebar li a");
    if (dashboardLink) {
      dashboardLink.click();
    }
  };
});

// const reloadMarkers = (selected) => {
//   selected_filter.value = selected;
//   loadMapData();
// };

/*const loadMapData = () => {
  if (selected_filter.value === "") {
    selected_filter.value = "all";
  }

  axios
    .get(
      import.meta.env.VITE_AJAX_URL +
        `get-devices/` +
        userId +
        `?status=${selected_filter.value}`
    )
    .then((response) => {
      if (response.status === 200) {
        locations.value = response.data.locations;
      }
    })
    .catch((error) => {
      console.error("Error fetching locations:", error);
    });
}; */

  /*const getAdminStats = () => {
    axios
      .get(import.meta.env.VITE_AJAX_URL + "get-admin-stats/" + userId)
      .then((response) => {
        if (response.status == 200) {
          adminStats.value = response.data;
        }
      })
      .catch((error) => {
        console.error("There was an error fetching the user devices:", error);
      });
  };*/

const getDeviceStatus = () => {
  axios
    .get(import.meta.env.VITE_AJAX_URL + "get-device-status")
    .then((response) => {
      if (response.status == 200) {
        console.log('device status list ',response.data)
        deviceStatus.value = response.data;
      }
    })
    .catch((error) => {
      console.error("There was an error fetching the user devices:", error);
    });
};
</script>

<style scoped>
.button-menu-drop-1 li {
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    padding: 12px 15px;
    margin-bottom: 10px;
    border-radius: 5px;
}
.main-text-wrap-1 a:hover {
    color: black !important;
}
.filter-div {
  cursor: pointer;
}
.my-custom-class {
  padding: 0;
}
.text-color-change a {
    color: brown;
}
.dropdown-toggles-1 {
  background-color: #007bff;
  color: #fff;
  border: none;
  padding: 0px;
  font-size: 16px;
  border-radius: 8px;
  transition: background-color 0.3s;
  box-shadow: none;
}
.dropdown-menu {
  background-color: #fff;
  border-radius: 8px;
  padding: 0;
  border: 1px solid #ddd;
  width: 331px;
}

.dropdown-itemse {
  padding: 0px;
  cursor: pointer;
  transition: background-color 0.3s;
  border-bottom: 1px solid #ddd;
}
.card-summarys-1 {
  display: flex;
  align-items: center;
  justify-content: space-between;
  box-shadow: none !important;
  border: 1px solid #00000094;
  padding: 12px 15px;
  border-radius: 5px;
  margin-bottom: 20px;
}
#dropdownMenuButton {
  width: 100%;
}
.icon-box {
  margin-left: 10px;
  text-align: left;
  color: black;
}
.main-chevron-down {
  color: black;
  font-size: 22px;
}

.selected_class-1 {
  display: flex;
  align-items: center;
}

.bi-check-circle-fill {
  color: #28a745;
  font-size: 18px;
}
.button-menu-drop-1 {
  top: -15px !important;
}
</style>
