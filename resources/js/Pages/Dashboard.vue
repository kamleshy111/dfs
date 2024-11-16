

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
                        <h4>100K</h4>
                        <p>Device Sale</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card-summary">
                    <i class="bi bi-people"></i>
                    <div class="icon-box">
                        <h4>50K</h4>
                        <p>Total Clients</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card-summary">
                    <i class="bi bi-currency-dollar"></i>
                    <div class="icon-box">
                        <h4>10 Cr</h4>
                        <p>Total Earning</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card-summary">
                    <i class="bi bi-box-seam"></i>
                    <div class="icon-box">
                        <h4>10</h4>
                        <p>Cancelled Devices</p>
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
                            <p><strong>Active Devices : 51</strong></p>
                            <p>Today : 4</p>
                            <p>This Month : 20</p>
                            <p>This Year : 50</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="dashboard-card mt-4">
             <h5 class=""><i class="bi bi-bar-chart-line-fill mr-2"></i>Sale This Year</h5>
            <table class="table table-hover table-bordered mt-3">
                <thead class="thead-light">
                    <tr>
                        <th scope="col">S No</th>
                        <th scope="col">Booking ID</th>
                        <th scope="col">Name</th>
                        <th scope="col">Mobile Number</th>
                        <th scope="col">Date & Time</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Example row, repeat for each customer -->
                    <tr>
                        <td>01</td>
                        <td>#002589</td>
                        <td>Jhon Rohan</td>
                        <td>+91 987065403210</td>
                        <td>19 Jan 2023 at 06:36 PM</td>
                        <td class="edite-box">
                            <button class="btn btn-light action-btn"><i class="bi bi-eye-fill"></i></button>
                            <button class="btn btn-warning text-white action-btn"><i
                                    class="bi bi-pencil-fill"></i></button>
                            <button class="btn btn-danger action-btn"><i
                                    class="bi bi-trash-fill"></i></button>
                        </td>
                    </tr>

                    <tr>
                        <td>02</td>
                        <td>#002589</td>
                        <td>Jhon Rohan</td>
                        <td>+91 987065403210</td>
                        <td>19 Jan 2023 at 06:36 PM</td>
                        <td>
                            <button class="btn btn-light action-btn"><i class="bi bi-eye-fill"></i></button>
                            <button class="btn btn-warning text-white action-btn"><i
                                    class="bi bi-pencil-fill"></i></button>
                            <button class="btn btn-danger action-btn"><i
                                    class="bi bi-trash-fill"></i></button>
                        </td>
                    </tr>

                    <tr>
                        <td>03</td>
                        <td>#002589</td>
                        <td>Jhon Rohan</td>
                        <td>+91 987065403210</td>
                        <td>19 Jan 2023 at 06:36 PM</td>
                        <td>
                            <button class="btn btn-light action-btn"><i class="bi bi-eye-fill"></i></button>
                            <button class="btn btn-warning text-white action-btn"><i
                                    class="bi bi-pencil-fill"></i></button>
                            <button class="btn btn-danger action-btn"><i
                                    class="bi bi-trash-fill"></i></button>
                        </td>
                    </tr>


                    <tr>
                        <td>04</td>
                        <td>#002589</td>
                        <td>Jhon Rohan</td>
                        <td>+91 987065403210</td>
                        <td>19 Jan 2023 at 06:36 PM</td>
                        <td>
                            <button class="btn btn-light action-btn"><i class="bi bi-eye-fill"></i></button>
                            <button class="btn btn-warning text-white action-btn"><i
                                    class="bi bi-pencil-fill"></i></button>
                            <button class="btn btn-danger action-btn"><i
                                    class="bi bi-trash-fill"></i></button>
                        </td>
                    </tr>


                    <tr>
                        <td>05</td>
                        <td>#002589</td>
                        <td>Jhon Rohan</td>
                        <td>+91 987065403210</td>
                        <td>19 Jan 2023 at 06:36 PM</td>
                        <td>
                            <button class="btn btn-light action-btn"><i class="bi bi-eye-fill"></i></button>
                            <button class="btn btn-warning text-white action-btn"><i
                                    class="bi bi-pencil-fill"></i></button>
                            <button class="btn btn-danger action-btn"><i
                                    class="bi bi-trash-fill"></i></button>
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

    const salesData = ref({
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        datasets: [{
            label: 'Sales',
            data: [0, 50, 100, 200, 500, 700, 300, 600, 400, 300, 600, 900],
            fill: true,
            backgroundColor: '#2239c336',
            borderColor: '#2239c3cc',
            borderWidth: 2,
        }],
    });

    const driverData = ref({
        labels: ['Active', 'Inactive'],
        datasets: [{
            data: [51, 1],
            backgroundColor: ['#2239c3cc', '#ccc'],
            borderWidth: 0,
        }],
    });

    onMounted(() => {
        const salesCtx = document.getElementById('salesChart').getContext('2d');
        new Chart(salesCtx, {
            type: 'line',
            data: salesData.value,
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
            },
        });

        const driverCtx = document.getElementById('driverStatusChart').getContext('2d');
        new Chart(driverCtx, {
            type: 'doughnut',
            data: driverData.value,
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
            },
        });
    });
</script>
