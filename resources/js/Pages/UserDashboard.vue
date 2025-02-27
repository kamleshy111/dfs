<template>

    <Head title="Client Dashboards" />

    <AuthenticatedLayout>
        <div class="container my-3">
            <!-- Summary Cards -->
<div class="dropdowns-1">
    <button class="btn dropdown-toggles-1 main-btn-sec-1" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
        <li @click="reloadMarkers('all')" class="dropdown-itemse drop-li-down">
            <div class="card-summarys-1 card-icons-1">
                <div class="icon-box">
                    <h4>{{ adminStats.all_device_count }}</h4>
                    <p>All Devices</p>
                </div>
                <div class="selected_class-1 select-card-icn">
                    <i v-if="selected_filter == 'all'" class="bi bi-chevron-down main-chevron-down"></i>
                </div>
            </div>
        </li>
    </button>
    <ul class="dropdown-menu button-menu-drop-1" aria-labelledby="dropdownMenuButton">
        <li @click="reloadMarkers(1)" class="dropdown-itemse">
            <div class="card-summarys-1">
                <div class="icon-box">
                    <h4>{{ adminStats.active_device_count }}</h4>
                    <p>Active Devices</p>
                </div>
                <!-- <div class="selected_class">
                    <i v-if="selected_filter == 1" class="bi bi-check-circle-fill"></i>
                </div> -->
            </div>
        </li>
        <li @click="reloadMarkers(0)" class="dropdown-itemse">
            <div class="card-summarys-1">
                <div class="icon-box">
                    <h4>{{ adminStats.inactive_device_count }}</h4>
                    <p>Inactive Devices</p>
                </div>
                <!-- <div class="selected_class">
                    <i v-if="selected_filter == 0" class="bi bi-check-circle-fill"></i>
                </div> -->
            </div>
        </li>
        <li @click="reloadMarkers(2)" class="dropdown-itemse">
            <div class="card-summarys-1">
                <div class="icon-box">
                    <h4>{{ adminStats.expired_device_count }}</h4>
                    <p>Expired Devices</p>
                </div>
                <!-- <div class="selected_class">
                    <i v-if="selected_filter == 2" class="bi bi-check-circle-fill"></i>
                </div> -->
            </div>
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
import {Head, usePage} from "@inertiajs/vue3";
import Map from '@/Components/Map.vue';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

const { role, userId } = usePage().props;

const locations = ref([]);
const adminStats = ref({
    customer_amounts: 0,
    all_device_count: 0,
    active_device_count: 0,
    expired_device_count: 0,
    inactive_device_count: 0,
});
const selected_filter = ref('all');

const NotificationCreate = () => {
    console.log('data dashboardLink :');
   /* window.Echo.channel('notification')
        .listen('.received.notification', (data) => {
            console.log('Total Count data dashboardLink :', data);
            locations.value = data.locations;
            adminStats.value = data.adminStats;
        });*/
    window.Echo.channel('notification')
        .listen('.received.notification', loadMapData());
    window.Echo.channel('notification')
        .listen('.received.notification', getAdminStats());
};

onMounted(() => {
    loadMapData();
    getAdminStats();
    NotificationCreate();
    window.onload = () => {
        const dashboardLink = document.querySelector(".sidebar li a");
        if (dashboardLink) {
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
        .get(import.meta.env.VITE_AJAX_URL + `get-devices/`+ userId +`?status=${selected_filter.value}`)
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
        .get(import.meta.env.VITE_AJAX_URL + "get-admin-stats/"+ userId)
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
