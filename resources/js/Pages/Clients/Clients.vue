<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
const pageTitle = "Client List";
</script>

<template>
    <Head :title="pageTitle" />
    <AuthenticatedLayout>
        <div class="container my-3">
          <div class="form-container shadow">
            <div class="table-container">
              <div class="d-flex justify-content-between align-items-center">
                <h4><i class="bi bi-people-fill mr-2"></i>All Customers</h4>
                <button class="btn btn-primary btn-custom">
                  <i class="bi bi-plus-circle-fill mr-2"></i>Add New Customer
                </button>
              </div>

              <div class="table-responsive">
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
                      <td>
                        <button class="btn btn-light action-btn">
                          <i class="bi bi-eye-fill"></i>
                        </button>
                        <button class="btn btn-warning text-white action-btn">
                          <i class="bi bi-pencil-fill"></i>
                        </button>
                        <button class="btn btn-danger action-btn">
                          <i class="bi bi-trash-fill"></i>
                        </button>
                      </td>
                    </tr>

                    <tr>
                      <td>02</td>
                      <td>#002589</td>
                      <td>Jhon Rohan</td>
                      <td>+91 987065403210</td>
                      <td>19 Jan 2023 at 06:36 PM</td>
                      <td>
                        <button class="btn btn-light action-btn">
                          <i class="bi bi-eye-fill"></i>
                        </button>
                        <button class="btn btn-warning text-white action-btn">
                          <i class="bi bi-pencil-fill"></i>
                        </button>
                        <button class="btn btn-danger action-btn">
                          <i class="bi bi-trash-fill"></i>
                        </button>
                      </td>
                    </tr>

                    <tr>
                      <td>03</td>
                      <td>#002589</td>
                      <td>Jhon Rohan</td>
                      <td>+91 987065403210</td>
                      <td>19 Jan 2023 at 06:36 PM</td>
                      <td>
                        <button class="btn btn-light action-btn">
                          <i class="bi bi-eye-fill"></i>
                        </button>
                        <button class="btn btn-warning text-white action-btn">
                          <i class="bi bi-pencil-fill"></i>
                        </button>
                        <button class="btn btn-danger action-btn">
                          <i class="bi bi-trash-fill"></i>
                        </button>
                      </td>
                    </tr>

                    <tr>
                      <td>04</td>
                      <td>#002589</td>
                      <td>Jhon Rohan</td>
                      <td>+91 987065403210</td>
                      <td>19 Jan 2023 at 06:36 PM</td>
                      <td>
                        <button class="btn btn-light action-btn">
                          <i class="bi bi-eye-fill"></i>
                        </button>
                        <button class="btn btn-warning text-white action-btn">
                          <i class="bi bi-pencil-fill"></i>
                        </button>
                        <button class="btn btn-danger action-btn">
                          <i class="bi bi-trash-fill"></i>
                        </button>
                      </td>
                    </tr>

                    <tr>
                      <td>05</td>
                      <td>#002589</td>
                      <td>Jhon Rohan</td>
                      <td>+91 987065403210</td>
                      <td>19 Jan 2023 at 06:36 PM</td>
                      <td>
                        <button class="btn btn-light action-btn">
                          <i class="bi bi-eye-fill"></i>
                        </button>
                        <button class="btn btn-warning text-white action-btn">
                          <i class="bi bi-pencil-fill"></i>
                        </button>
                        <button class="btn btn-danger action-btn">
                          <i class="bi bi-trash-fill"></i>
                        </button>
                      </td>
                    </tr>

                    <tr>
                      <td>06</td>
                      <td>#002589</td>
                      <td>Jhon Rohan</td>
                      <td>+91 987065403210</td>
                      <td>19 Jan 2023 at 06:36 PM</td>
                      <td>
                        <button class="btn btn-light action-btn">
                          <i class="bi bi-eye-fill"></i>
                        </button>
                        <button class="btn btn-warning text-white action-btn">
                          <i class="bi bi-pencil-fill"></i>
                        </button>
                        <button class="btn btn-danger action-btn">
                          <i class="bi bi-trash-fill"></i>
                        </button>
                      </td>
                    </tr>

                    <tr>
                      <td>07</td>
                      <td>#002589</td>
                      <td>Jhon Rohan</td>
                      <td>+91 987065403210</td>
                      <td>19 Jan 2023 at 06:36 PM</td>
                      <td>
                        <button class="btn btn-light action-btn">
                          <i class="bi bi-eye-fill"></i>
                        </button>
                        <button class="btn btn-warning text-white action-btn">
                          <i class="bi bi-pencil-fill"></i>
                        </button>
                        <button class="btn btn-danger action-btn">
                          <i class="bi bi-trash-fill"></i>
                        </button>
                      </td>
                    </tr>

                    <tr>
                      <td>08</td>
                      <td>#002589</td>
                      <td>Jhon Rohan</td>
                      <td>+91 987065403210</td>
                      <td>19 Jan 2023 at 06:36 PM</td>
                      <td>
                        <button class="btn btn-light action-btn">
                          <i class="bi bi-eye-fill"></i>
                        </button>
                        <button class="btn btn-warning text-white action-btn">
                          <i class="bi bi-pencil-fill"></i>
                        </button>
                        <button class="btn btn-danger action-btn">
                          <i class="bi bi-trash-fill"></i>
                        </button>
                      </td>
                    </tr>

                    <tr>
                      <td>09</td>
                      <td>#002589</td>
                      <td>Jhon Rohan</td>
                      <td>+91 987065403210</td>
                      <td>19 Jan 2023 at 06:36 PM</td>
                      <td>
                        <button class="btn btn-light action-btn">
                          <i class="bi bi-eye-fill"></i>
                        </button>
                        <button class="btn btn-warning text-white action-btn">
                          <i class="bi bi-pencil-fill"></i>
                        </button>
                        <button class="btn btn-danger action-btn">
                          <i class="bi bi-trash-fill"></i>
                        </button>
                      </td>
                    </tr>

                    <tr>
                      <td>10</td>
                      <td>#002589</td>
                      <td>Jhon Rohan</td>
                      <td>+91 987065403210</td>
                      <td>19 Jan 2023 at 06:36 PM</td>
                      <td>
                        <button class="btn btn-light action-btn">
                          <i class="bi bi-eye-fill"></i>
                        </button>
                        <button class="btn btn-warning text-white action-btn">
                          <i class="bi bi-pencil-fill"></i>
                        </button>
                        <button class="btn btn-danger action-btn">
                          <i class="bi bi-trash-fill"></i>
                        </button>
                      </td>
                    </tr>

                    <tr>
                      <td>11</td>
                      <td>#002589</td>
                      <td>Jhon Rohan</td>
                      <td>+91 987065403210</td>
                      <td>19 Jan 2023 at 06:36 PM</td>
                      <td>
                        <button class="btn btn-light action-btn">
                          <i class="bi bi-eye-fill"></i>
                        </button>
                        <button class="btn btn-warning text-white action-btn">
                          <i class="bi bi-pencil-fill"></i>
                        </button>
                        <button class="btn btn-danger action-btn">
                          <i class="bi bi-trash-fill"></i>
                        </button>
                      </td>
                    </tr>

                    <!-- Repeat the above row as needed -->
                  </tbody>
                </table>
              </div>
              <!-- Pagination -->
              <div class="pagination-container">
                <nav aria-label="Page navigation">
                  <ul class="pagination">
                    <li class="page-item">
                      <a class="page-link" href="#">Previous</a>
                    </li>
                    <li class="page-item active">
                      <a class="page-link" href="#">1</a>
                    </li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item"><a class="page-link" href="#">4</a></li>
                    <li class="page-item"><a class="page-link" href="#">5</a></li>
                    <li class="page-item">
                      <a class="page-link" href="#">Next</a>
                    </li>
                  </ul>
                </nav>
              </div>
            </div>
          </div>
        </div>
    </AuthenticatedLayout>
</template>
