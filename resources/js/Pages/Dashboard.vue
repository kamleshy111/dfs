<template>

    <Head title="Dashboard" />

    <AuthenticatedLayout>

        <div class="container my-3">
            <!-- Summary Cards -->
            <div class="row">
                <div class="col-md-3">
                    <div class="card-summary">
                        <i class="bi bi-car-front"></i>
                        <div class="icon-box">
                            <h4>{{ adminStats.all_device_count }}</h4>
                            <p>Device</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card-summary">
                        <i class="bi bi-people"></i>
                        <div class="icon-box">
                            <h4>{{ adminStats.customer_count }}</h4>
                            <p>Total Clients</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card-summary">
                        <i class="bi bi-currency-dollar"></i>
                        <div class="icon-box">
                            <h4>{{ adminStats.customer_amounts }}</h4>
                            <p>Total Earning</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card-summary">
                        <i class="bi bi-box-seam"></i>
                        <div class="icon-box">
                            <h4>{{ adminStats.inactive_device_count }}</h4>
                            <p>Inactive Devices</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <!-- Sales Chart Card -->
                <div class="col-md-6">
                    <div class="dashboard-card bg-white">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5><i class="bi bi-bar-chart-line-fill mr-2"></i>Sale This Year</h5>
                            <span>This Year</span>
                        </div>
                        <canvas id="salesChart"></canvas>
                    </div>
                </div>

                <!-- Driver Status Card -->
                <div class="col-md-6">
                    <div class="dashboard-card bg-white">
                        <h5><i class="bi bi-bar-chart-line-fill mr-2"></i>Drivers Status</h5>
                        <div class="d-flex align-items-center justify-content-between box-chart-main">
                            <div class="rounded-chart">
                                <canvas id="driverStatusChart" width="200px" height="200px"></canvas>
                            </div>
                            <div class="ms-3">
                                <p class="active-device">Active Devices : 51</p>
                                <p>Active : {{ adminStats.active_device_count }}</p>
                                <p>Inactive : {{ adminStats.inactive_device_count }}</p>
                                <p>Expired : {{ adminStats.expired_device_count }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="dashboard-card mt-4">
                <h5 class=""><i class="bi bi-truck-front-fill mr-3"></i>Recent Customer</h5>
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
                        <!-- Example row, repeat for each customer -->
                        <tr v-for="customer in customers" :key="customer.id">

                            <td>{{ customer.first_name }} {{ customer.last_name }}</td>
                            <td>{{ customer.phone }}</td>
                            <td>{{ formatDate(customer.created_at) }}</td>
                            <td class="edite-box">
                                <button class="btn btn-light action-btn" @click="viewCustomer(customer.id)"> <i
                                        class="bi bi-eye-fill"></i></button>
                                <button class="btn btn-warning text-white action-btn"
                                    @click="editCustomer(customer.id)"><i class="bi bi-pencil-fill"></i></button>
                                <button class="btn btn-danger action-btn"><i class="bi bi-trash-fill"></i></button>
                            </td>
                        </tr>
                        <!-- Repeat the above row as needed -->
                    </tbody>
                </table>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import axios from 'axios';

const adminStats = ref({
    customer_amounts: 0,
    customer_count: 0,
    inactive_device_count: 0,
    all_device_count: 0,
    active_device_count: 0,
    expired_device_count: 0
});

const customers = ref([]);

// Format date method
function formatDate(value) {
    if (!value) return '';

    // Create a new Date object from the ISO 8601 string
    const date = new Date(value);

    // Define the format options
    const dateOptions = {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    };
    const timeOptions = {
        hour: '2-digit',
        minute: '2-digit',
        hour12: true
    };

    // Format the date and time separately and combine them
    const formattedDate = date.toLocaleDateString('en-GB', dateOptions);
    const formattedTime = date.toLocaleTimeString('en-GB', timeOptions);

    // Return the formatted string in the desired format
    return `${formattedDate} at ${formattedTime}`;
}

const getAdminStats = () => {
    axios.get(import.meta.env.VITE_AJAX_URL + 'get-admin-stats')
        .then(response => {
            if (response.status == 200) {
                adminStats.value = response.data;


                reinitializeChart(response.data.device_sold_by_month);

                reinitializeDonut([adminStats.value.active_device_count, adminStats.value.inactive_device_count, adminStats.value.expired_device_count]);

            }
        })
        .catch(error => {
            console.error("There was an error fetching the user devices:", error);
        });
}

onMounted(() => {
    // Onload functions
    getAdminStats();

    axios.get('/api/dashboard')
        .then(response => {
            // Customer Details the fetched data in the customers array
            customers.value = response.data.customers;
        })
        .catch(error => {
            console.error("There was an error fetching the customers:", error);
        });
});

var reinitializeDonut = (data) => {
    new Chart(document.getElementById('driverStatusChart').getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: ['Active', 'Inactive', 'Expired'],
            datasets: [{
                data: data,
                backgroundColor: ['#2239c3cc', '#ccc', '#dc3545'],
                borderWidth: 0,
            }],
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
        },
    });
}

var reinitializeChart = (device_sold_by_month) => {
    let tempdata = Array(12).fill(0);
    device_sold_by_month.forEach(item => {
        const monthIndex = item.month - 1;
        tempdata[monthIndex] = item.total;
    });

    const salesCtx = document.getElementById('salesChart').getContext('2d');
    new Chart(salesCtx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [{
                label: 'Sales',
                data: tempdata, // Start with 0 sales for all months
                fill: true,
                backgroundColor: '#2239c336',
                borderColor: '#2239c3cc',
                borderWidth: 2,
            }],
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
        },
    });
    // const salesData = ref();
}

function viewCustomer(id) {
    window.location.href = `/clients/${id}/view`;
}

function editCustomer(id) {
    window.location.href = `/clients/${id}/edit`;
}
</script>
