<template>

    <Head title="Dashboards" />

    <AuthenticatedLayout>
        <div class="container my-3">
            <!-- Summary Cards -->
            <div class="row">
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
</style>
