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
var selected_filter = ref('all');

onMounted(() => {
    loadMapData();
    getAdminStats();
    window.onload = () => {
        reloadMarkers('all')
    };
});

const reloadMarkers = (selected) => {
    selected_filter.value = selected;
    loadMapData();
}

const loadMapData = () => {
    console.log( selected_filter.value === '' );
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

<!--
<script>
/*
const customers = ref([]);
axios
        .get("/api/dashboard")
        .then((response) => {
            customers.value = response.data.customers;
        })
        .catch((error) => {
            console.error("There was an error fetching the customers:", error);
        });
reinitializeChart(response.data.device_sold_by_month);

reinitializeDonut([
    adminStats.value.active_device_count,
    adminStats.value.inactive_device_count,
    adminStats.value.expired_device_count,
]);

var reinitializeDonut = (data) => {
    new Chart(document.getElementById("driverStatusChart").getContext("2d"), {
        type: "doughnut",
        data: {
            labels: ["Active", "Inactive", "Expired"],
            datasets: [
                {
                    data: data,
                    backgroundColor: ["#2239c3cc", "#ccc", "#dc3545"],
                    borderWidth: 0,
                },
            ],
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
        },
    });
};

var reinitializeChart = (device_sold_by_month) => {
    let tempdata = Array(12).fill(0);
    device_sold_by_month.forEach((item) => {
        const monthIndex = item.month - 1;
        tempdata[monthIndex] = item.total;
    });

    const salesCtx = document.getElementById("salesChart").getContext("2d");
    new Chart(salesCtx, {
        type: "line",
        data: {
            labels: [
                "Jan",
                "Feb",
                "Mar",
                "Apr",
                "May",
                "Jun",
                "Jul",
                "Aug",
                "Sep",
                "Oct",
                "Nov",
                "Dec",
            ],
            datasets: [
                {
                    label: "Sales",
                    data: tempdata, // Start with 0 sales for all months
                    fill: true,
                    backgroundColor: "#2239c336",
                    borderColor: "#2239c3cc",
                    borderWidth: 2,
                },
            ],
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
        },
    });
    // const salesData = ref();
};
function viewCustomer(id) {
  window.location.href = `/clients/${id}/view`;
}

function editCustomer(id) {
  window.location.href = `/clients/${id}/edit`;
}
// Format date method
function formatDate(value) {
  if (!value) return "";

  // Create a new Date object from the ISO 8601 string
  const date = new Date(value);

  // Define the format options
  const dateOptions = {
    year: "numeric",
    month: "short",
    day: "numeric",
  };
  const timeOptions = {
    hour: "2-digit",
    minute: "2-digit",
    hour12: true,
  };

  // Format the date and time separately and combine them
  const formattedDate = date.toLocaleDateString("en-GB", dateOptions);
  const formattedTime = date.toLocaleTimeString("en-GB", timeOptions);

  // Return the formatted string in the desired format
  return `${formattedDate} at ${formattedTime}`;
} */
</script>
<template><div class="row">
        <div class="col-md-6">
          <div class="dashboard-card bg-white">
            <div class="d-flex justify-content-between align-items-center mb-3">
              <h5>
                <i class="bi bi-bar-chart-line-fill mr-2"></i>Sales This Year
              </h5>
              <span>This Year</span>
            </div>
            <canvas id="salesChart"></canvas>
          </div>
        </div>
        <div class="col-md-6">
          <div class="dashboard-card bg-white">
            <h5>
              <i class="bi bi-bar-chart-line-fill mr-2"></i>Drivers Status
            </h5>
            <div
              class="d-flex align-items-center justify-content-between box-chart-main" style="height: 326px;"
            >
              <div class="rounded-chart">
                <canvas
                  id="driverStatusChart"
                  width="200px"
                  height="200px"
                ></canvas>
              </div>
              <div class="ms-3">
                <p class="active-device">Active Devices: {{ adminStats.active_device_count }}</p>
                <p>Inactive Devices: {{ adminStats.inactive_device_count }}</p>
                <p>Expired Devices: {{ adminStats.expired_device_count }}</p>
              </div>
            </div>
          </div>
        </div>
      </div> -->
            <!-- <div class="dashboard-card mt-4">
        <h5 class="">
          <i class="bi bi-truck-front-fill mr-3"></i>Recent Customer
        </h5>
        <table class="table table-hover table-bordered mt-3">
          <thead class="thead-light">
            <tr>
              <th scope="col">Name</th>
              <th scope="col">Mobile Number</th>
              <th scope="col">Date & Time</th>
              <th scope="col">Action</th>
            </tr>
          </thead>
          <tbody>

            <tr v-for="customer in customers" :key="customer.id">
              <td>{{ customer.first_name }} {{ customer.last_name }}</td>
              <td>{{ customer.phone }}</td>
              <td>{{ formatDate(customer.created_at) }}</td>
              <td class="edite-box">
                <button
                  class="btn btn-light action-btn"
                  @click="reloadMarkers('tomer'))"
                >
                  <i class="bi bi-eye-fill"></i>
                </button>
                <button
                  class="btn btn-warning text-white action-btn"
                  @click="reloadMarkers('tomer'))"
                >
                  <i class="bi bi-pencil-fill"></i>
                </button>
                <button class="btn btn-danger action-btn">
                  <i class="bi bi-trash-fill"></i>
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <template> -->
