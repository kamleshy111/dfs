<template>

    <Head title="Dashboards" />

    <AuthenticatedLayout>
        <div class="container my-3">
            <!-- Summary Cards -->
            <div class="row main-all-box-content-2">
                <div class="col-12 col-md-6 col-lg-3 filter-div" @click="reloadMarkers('all')">
                    <div class="card-summary">
                      <div class="card-summary-icon">
                        <i class="bi bi-car-front"></i>
                        <div class="icon-box">
                            <h4>{{ adminStats.all_device_count }}</h4>
                            <p>All Devices</p>
                        </div>
                      </div>
                        <div class="selected_class">
                            <i v-if="selected_filter == 'all'" class="bi bi-check-circle-fill"></i>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-3 filter-div"  @click="reloadMarkers(1)">
                    <div class="card-summary">
                      <div class="card-summary-icon">
                        <i class="bi bi-car-front"></i>
                        <div class="icon-box">
                            <h4>{{ adminStats.active_device_count }}</h4>
                            <p>Active Devices</p>
                        </div>
                      </div>
                        <div class="selected_class">
                            <i v-if="selected_filter == 1" class="bi bi-check-circle-fill"></i>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-3 filter-div"  @click="reloadMarkers(0)">
                    <div class="card-summary">
                      <div class="card-summary-icon">
                        <i class="bi bi-car-front"></i>
                        <div class="icon-box">
                            <h4>{{ adminStats.inactive_device_count }}</h4>
                            <p>Inactive Devices</p>

                        </div>
                      </div>
                        <div class="selected_class">
                            <i v-if="selected_filter == 0" class="bi bi-check-circle-fill"></i>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-3 filter-div"  @click="reloadMarkers(2)">
                    <div class="card-summary">
                      <div class="card-summary-icon">
                        <i class="bi bi-car-front"></i>
                        <div class="icon-box">
                            <h4>{{ adminStats.expired_device_count }}</h4>
                            <p>Expired Devices</p>
                        </div>
                      </div>
                        <div class="selected_class">
                            <i v-if="selected_filter == 2" class="bi bi-check-circle-fill"></i>
                        </div>
                    </div>
                </div>
            </div>

                <div class="dropdowns-1" id="dropdown-section-1">
                <input class="form-control dropdown-toggle" type="text" id="filterDropdown" placeholder="Select Filter" aria-expanded="false" readonly data-bs-toggle="dropdown">

                <ul class="dropdown-menu" aria-labelledby="filterDropdown" style="left: 23px;">
                    <li @click="reloadMarkers('all')">
                    <a class="dropdown-item" href="#">
                        <div class="dropdown-item-content">
                        <div class="d-flex align-items-center justify-content-between w-100">
                            <div class="device-info">
                            <h4>{{ adminStats.all_device_count }}</h4>
                            <p>All Devices</p>
                            </div>
                            <i v-if="selected_filter == 'all'" class="bi bi-check-circle-fill selected-icon"></i>
                        </div>
                        </div>
                    </a>
                    </li>
                    <li @click="reloadMarkers(1)">
                    <a class="dropdown-item" href="#">
                        <div class="dropdown-item-content">
                        <div class="d-flex align-items-center justify-content-between w-100">
                            <div class="device-info">
                            <h4>{{ adminStats.active_device_count }}</h4>
                            <p>Active Devices</p>
                            </div>
                            <i v-if="selected_filter == 1" class="bi bi-check-circle-fill selected-icon"></i>
                        </div>
                        </div>
                    </a>
                    </li>
                    <li @click="reloadMarkers(0)">
                    <a class="dropdown-item" href="#">
                        <div class="dropdown-item-content">
                        <div class="d-flex align-items-center justify-content-between w-100">
                            <div class="device-info">
                            <h4>{{ adminStats.inactive_device_count }}</h4>
                            <p>Inactive Devices</p>
                            </div>
                            <i v-if="selected_filter == 0" class="bi bi-check-circle-fill selected-icon"></i>
                        </div>
                        </div>
                    </a>
                    </li>
                    <li @click="reloadMarkers(2)">
                    <a class="dropdown-item" href="#">
                        <div class="dropdown-item-content">
                        <div class="d-flex align-items-center justify-content-between w-100">
                            <div class="device-info">
                            <h4>{{ adminStats.expired_device_count }}</h4>
                            <p>Expired Devices</p>
                            </div>
                            <i v-if="selected_filter == 2" class="bi bi-check-circle-fill selected-icon"></i>
                        </div>
                        </div>
                    </a>
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
import { Head } from "@inertiajs/vue3";
import Map from '@/Components/Map.vue';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

const locations = ref([]);
const adminStats = ref({
    customer_amounts: 0,
    customer_count: 0,
    all_device_count: 0,
    active_device_count: 0,
    expired_device_count: 0,
    inactive_device_count: 0,
});
const selected_filter = ref('all');

const NotificationCreate = () => {
    window.Echo.channel('notification')
        .listen('.received.notification', (data) => {
            console.log(data);
            console.log("hit hua hai");
            loadMapData();
            getAdminStats();
        });
};

onMounted(() => {
    loadMapData();
    getAdminStats();
    NotificationCreate();
    window.onload = () => {
        const dashboardLink = document.querySelector(".sidebar li a");
        if (dashboardLink) {
            console.log("DDDd");
            dashboardLink.click();
        }
    };
});

const reloadMarkers = (selected) => {
    selected_filter.value = selected;
    loadMapData();
}

const loadMapData = () => {
    if( selected_filter.value === '' ) {
        selected_filter.value = 'all';
    }

    axios
        .get(import.meta.env.VITE_AJAX_URL + `get-devices?status=${selected_filter.value}`)
        .then((response) => {
            if (response.status === 200) {
                locations.value = response.data.locations;
            }
        })
        .catch((error) => {
            console.error('Error fetching locations:', error);
        });
}

const getAdminStats = () => {
    axios
        .get(import.meta.env.VITE_AJAX_URL + "get-admin-stats")
        .then((response) => {
            if (response.status == 200) {
                adminStats.value = response.data;
            }
        })
        .catch((error) => {
            console.error("There was an error fetching the user devices:", error);
        });
};
</script>

<style scoped>
.filter-div {
    cursor: pointer;
}
#filterDropdown {
    cursor: pointer;
    background-color: #f8f9fa;
    border: 1px solid #ced4da;
    border-radius: 0.375rem;
    padding: 0 20px;
    font-size: 16px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}
#dropdown-section-1 {
    margin-bottom: 40px !important;
}
.dropdown-menu {
  width: 88%;
  border-radius: 0.375rem;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  padding: 0;
}
.dropdown-item {
  padding: 12px 16px;
  display: flex;
  justify-content: space-between;
  align-items: center;
}
.dropdown-item:hover {
  background-color: #f1f1f1;
  cursor: pointer;
}
.dropdown-item-content {
  display: flex;
  align-items: center;
  justify-content: space-between;
  width: 100%;
}
.device-info {
  display: flex;
  flex-direction: column;
  align-items: flex-start;
  margin-right: 10px;
}

.device-info h4 {
  font-size: 18px;
  font-weight: bold;
  margin-bottom: 4px;
}

.device-info p {
  font-size: 14px;
  color: #6c757d;
}
.selected-icon {
  color: #28a745;
  font-size: 1.5rem;
  margin-left: 10px;
}
#dropdown-section-1 {
    display: none;
}
.dropdown {
    margin-bottom: 0px !important;
}

@media (max-width: 768px) {
  .dropdown-item {
    padding: 10px;
  }
  #dropdown-section-1 {
    display: block;
}
.main-all-box-content-2 {
    display: none;
}
  .device-info h4 {
    font-size: 16px;
  }

  .device-info p {
    font-size: 12px;
  }
}

</style>
