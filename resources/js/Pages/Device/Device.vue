<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
</script>

<template>
  <Head title="home" />

  <AuthenticatedLayout>
    <div class="mt-5">
        <div class="table-container">
            <div class="d-flex justify-content-between align-items-center">
                <h4><i class="bi bi-truck-front-fill mr-2"></i>All Devices</h4>
                <a :href="route('devices.create')">
                <button class="btn btn-primary btn-custom"><i class="bi bi-plus-circle-fill mr-2"></i>Add New Device</button>
            </a>
              </div>

            <table class="table table-hover table-bordered mt-3">
                <thead class="thead-light">
                    <tr>
                        <th scope="col">S No</th>
                        <th scope="col">Device ID</th>
                        <th scope="col">Order ID</th>
                        <th scope="col">Purchase By (Company)</th>
                        <th scope="col">Date &amp; Time</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Example row, repeat for each device -->
                    <tr>
                        <td>01</td>
                        <td>#002589</td>
                        <td>#002589</td>
                        <td>Cello NOX Device</td>
                        <td>19 Jan 2023 at 06:36 PM</td>
                        <td>
                            <button class="btn btn-light action-btn"><i class="bi bi-eye-fill"></i></button>
                            <button class="btn btn-warning text-white action-btn"><i class="bi bi-pencil-fill"></i></button>
                            <button class="btn btn-danger action-btn"><i class="bi bi-trash-fill"></i></button>
                        </td>
                    </tr>
                    
                    <tr>
                        <td>02</td>
                        <td>#002589</td>
                        <td>#002589</td>
                        <td>Cello NOX Device</td>
                        <td>19 Jan 2023 at 06:36 PM</td>
                        <td>
                            <button class="btn btn-light action-btn"><i class="bi bi-eye-fill"></i></button>
                            <button class="btn btn-warning text-white action-btn"><i class="bi bi-pencil-fill"></i></button>
                            <button class="btn btn-danger action-btn"><i class="bi bi-trash-fill"></i></button>
                        </td>
                    </tr>

                    <tr>
                        <td>03</td>
                        <td>#002589</td>
                        <td>#002589</td>
                        <td>Cello NOX Device</td>
                        <td>19 Jan 2023 at 06:36 PM</td>
                        <td>
                            <button class="btn btn-light action-btn"><i class="bi bi-eye-fill"></i></button>
                            <button class="btn btn-warning text-white action-btn"><i class="bi bi-pencil-fill"></i></button>
                            <button class="btn btn-danger action-btn"><i class="bi bi-trash-fill"></i></button>
                        </td>
                    </tr>

                    <tr>
                        <td>04</td>
                        <td>#002589</td>
                        <td>#002589</td>
                        <td>Cello NOX Device</td>
                        <td>19 Jan 2023 at 06:36 PM</td>
                        <td>
                            <button class="btn btn-light action-btn"><i class="bi bi-eye-fill"></i></button>
                            <button class="btn btn-warning text-white action-btn"><i class="bi bi-pencil-fill"></i></button>
                            <button class="btn btn-danger action-btn"><i class="bi bi-trash-fill"></i></button>
                        </td>
                    </tr>


                    <tr>
                        <td>05</td>
                        <td>#002589</td>
                        <td>#002589</td>
                        <td>Cello NOX Device</td>
                        <td>19 Jan 2023 at 06:36 PM</td>
                        <td>
                            <button class="btn btn-light action-btn"><i class="bi bi-eye-fill"></i></button>
                            <button class="btn btn-warning text-white action-btn"><i class="bi bi-pencil-fill"></i></button>
                            <button class="btn btn-danger action-btn"><i class="bi bi-trash-fill"></i></button>
                        </td>
                    </tr>

                    <tr>
                        <td>06</td>
                        <td>#002589</td>
                        <td>#002589</td>
                        <td>Cello NOX Device</td>
                        <td>19 Jan 2023 at 06:36 PM</td>
                        <td>
                            <button class="btn btn-light action-btn"><i class="bi bi-eye-fill"></i></button>
                            <button class="btn btn-warning text-white action-btn"><i class="bi bi-pencil-fill"></i></button>
                            <button class="btn btn-danger action-btn"><i class="bi bi-trash-fill"></i></button>
                        </td>
                    </tr>

                    <tr>
                        <td>08</td>
                        <td>#002589</td>
                        <td>#002589</td>
                        <td>Cello NOX Device</td>
                        <td>19 Jan 2023 at 06:36 PM</td>
                        <td>
                            <button class="btn btn-light action-btn"><i class="bi bi-eye-fill"></i></button>
                            <button class="btn btn-warning text-white action-btn"><i class="bi bi-pencil-fill"></i></button>
                            <button class="btn btn-danger action-btn"><i class="bi bi-trash-fill"></i></button>
                        </td>
                    </tr>

                    <tr>
                        <td>09</td>
                        <td>#002589</td>
                        <td>#002589</td>
                        <td>Cello NOX Device</td>
                        <td>19 Jan 2023 at 06:36 PM</td>
                        <td>
                            <button class="btn btn-light action-btn"><i class="bi bi-eye-fill"></i></button>
                            <button class="btn btn-warning text-white action-btn"><i class="bi bi-pencil-fill"></i></button>
                            <button class="btn btn-danger action-btn"><i class="bi bi-trash-fill"></i></button>
                        </td>
                    </tr>

                    <tr>
                        <td>10</td>
                        <td>#002589</td>
                        <td>#002589</td>
                        <td>Cello NOX Device</td>
                        <td>19 Jan 2023 at 06:36 PM</td>
                        <td>
                            <button class="btn btn-light action-btn"><i class="bi bi-eye-fill"></i></button>
                            <button class="btn btn-warning text-white action-btn"><i class="bi bi-pencil-fill"></i></button>
                            <button class="btn btn-danger action-btn"><i class="bi bi-trash-fill"></i></button>
                        </td>
                    </tr>


                </tbody>
            </table>

            <!-- Pagination -->
            <div class="pagination-container">
                <nav aria-label="Page navigation">
                    <ul class="pagination">
                        <li class="page-item"><a class="page-link" href="#">Previous</a></li>
                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item"><a class="page-link" href="#">4</a></li>
                        <li class="page-item"><a class="page-link" href="#">5</a></li>
                        <li class="page-item"><a class="page-link" href="#">Next</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
  </AuthenticatedLayout>
</template>