<template>

    <Head title="billing" />

    <AuthenticatedLayout>
        <div class="mt-5">
            <div class="table-container">
                <div class="d-flex justify-content-between align-items-center">
                    <h4><i class="bi bi-receipt"></i> Billing Information</h4>
                    <a :href="route('clients.create')">
                        <button class="btn btn-primary btn-custom responsive-btn">
                            <i class="bi bi-download"></i> Download
                        </button>
                    </a>
                </div>
                <DataTable class="display" :data="invoices">
                    <template #default>
                        <thead class="thead-light">
                            <tr>
                                <th>S No</th>
                                <th>Invoice ID</th>
                                <th>Device name</th>
                                <th>Status</th>
                                <th>Payment Mode</th>
                                <th>Payment Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- <tr v-for="(invoice, i) in invoices" :key="invoice.id">

                                <td>{{ invoice.id }}</td>
                                <td>{{ invoice.device_name }} sdasdasd</td>
                                <td v-html="device_status(invoice.status)"></td>
                                <td>{{ invoice.payment_mode }}</td>
                                <td v-html="payment_tag(invoice.payment_status)"></td>
                            </tr> -->
                        </tbody>
                    </template>
                </DataTable>
            </div>
        </div>
        <!-- <div class="container mt-5"> -->
        <!-- Row for Plan Summary and Payment Method -->
        <!-- <div class="row mb-3"> -->
        <!-- Current Plan Summary Section -->
        <!-- <div class="col-md-6">
                    <div class="d-flex justify-content-between align-items-center"
                        style="background: #fff; padding: 7px 17px; border: 1px solid #00000024;">
                        <div>
                            <h4 class="fw-bold" style="font-size: 14px;">Current Plan Summary</h4>
                        </div>
                        <div>
                            <button style="font-size: 10px; background:var(--light-color); padding: 8px 12px;"
                                class="btn btn-primary">Upgrade</button>
                        </div>
                    </div>
                    <div class="section-header">
                        <div class="d-flex justify-content-between align-items-center new-tg-1">
                            <div>
                                <p class="mb-0">PLAN NAME</p>
                                <strong>Growth Plan</strong>
                            </div>
                            <div>
                                <p class="mb-0">BILLING CYCLE:</p><strong>Monthly</strong>
                            </div>
                            <div>
                                <p class="mb-0">PLAN COST:</p> <strong>$5698</strong>
                            </div>
                        </div>
                        <div class="new-tg-1 mt-3">
                            <p class="mb-0">USAGE</p>
                            <p class="mb-0 mt-1">4850 out of 5k monthly active users</p>
                        </div>
                        <div class="progress mt-3">
                            <div class="progress-bar" role="progressbar" style="width: 97%;" aria-valuenow="97"
                                aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div> -->

        <!-- Payment Method Section -->
        <!-- <div class="col-md-6">
                    <div class="d-flex justify-content-between align-items-center"
                        style="background: #fff; padding: 11px 17px; border: 1px solid #00000024;">
                        <div>
                            <h4 class="fw-bold" style="font-size: 14px;">Payment Method</h4>
                        </div>
                    </div>
                    <div class="payment-method">
                        <div class="d-flex justify-content-between py-3">
                            <div>
                                <div class="me-3">
                                    <i class="fa-brands fa-cc-mastercard fa-2x"></i>
                                </div>
                                <div>
                                    <p class="mb-0" style="font-size: 12px; font-weight: 500; color: #2C2C2C;">Master
                                        Card <br><span> **** **** **** 4002</span></p>
                                    <p class="text-muted expiry">Expiry on 20/2024</p>
                                    <p class="text-muted">billing@acme.corp</p>
                                </div>
                            </div>
                            <button style="height: 40px;background-color: #fff; color: #000; font-size: 12px;"
                                class="btn btn-outline-secondary ms-auto">Change</button>
                        </div>
                    </div>
                </div> -->
        <!-- </div> -->

        <!-- Invoice Table Section -->
        <!-- <div class="invoice-table1 mb-3 mt-5">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-3">Invoice</h6>
                        <p style="font-size: 10px;">Effortlessly handle your billing and invoices right here.</p>
                    </div>
                    <div class="">
                        <button style="background:var(--light-color);" class="btn btn-primary">Download</button>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Invoice ID</th>
                                <th>Billing Date</th>
                                <th>Plan</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>#23456</td>
                                <td>23 Jan 2023</td>
                                <td>Basic Plan</td>
                                <td>$1200</td>
                                <td><span class="badge bg-success">Paid</span></td>
                                <td><i class="fa fa-ellipsis-h"></i></td>
                            </tr>
                            <tr>
                                <td>#56489</td>
                                <td>23 Feb 2023</td>
                                <td>Pro Plan</td>
                                <td>$7000</td>
                                <td><span class="badge bg-success">Paid</span></td>
                                <td><i class="fa fa-ellipsis-h"></i></td>
                            </tr>
                            <tr>
                                <td>#56489</td>
                                <td>23 Mar 2023</td>
                                <td>Pro Plan</td>
                                <td>$7000</td>
                                <td><span class="badge bg-success">Paid</span></td>
                                <td><i class="fa fa-ellipsis-h"></i></td>
                            </tr>
                            <tr>
                                <td>#98380</td>
                                <td>23 Apr 2023</td>
                                <td>Growth Plan</td>
                                <td>$5698</td>
                                <td><span class="badge bg-success">Paid</span></td>
                                <td><i class="fa fa-ellipsis-h"></i></td>
                            </tr>
                            <tr>
                                <td>#90394</td>
                                <td>23 May 2023</td>
                                <td>Basic Plan</td>
                                <td>$1200</td>
                                <td><span class="badge bg-success">Paid</span></td>
                                <td><i class="fa fa-ellipsis-h"></i></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div> -->
    </AuthenticatedLayout>
</template>
<script setup>
import { Head } from "@inertiajs/vue3";
import DataTable from 'datatables.net-vue3';
import { ref, onMounted, nextTick } from 'vue';
import DataTablesCore from 'datatables.net-dt';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import $ from 'jquery';

DataTable.use(DataTablesCore);

var invoices = ref([]);



const payment_tag = (payment_status) => {
    if (payment_status == 'paid') {
        return '<span class="badge bg-success">Paid</span>';
    } else if (payment_status == 'partially_paid') {
        return '<span class="badge bg-warning">Partially Paid</span>';
    } else if (payment_status == 'pending') {
        return '<span class="badge bg-secondary">Pending</span>';
    }
}

const device_status = (status) => {
    if (status == 'expired') {
        return '<span class="badge bg-danger">Expired</span>';
    } else if (status == 'active') {
        return '<span class="badge bg-success">Active</span>';
    }
}

onMounted(() => {
    axios.get(import.meta.env.VITE_AJAX_URL + 'get-billing-invoices')
        .then(response => {
            if (response.status == 200) {
                let data = [];
                let index = 1

                for( let invoice of response.data.billing_invoices ) {
                    data.push([
                        index,
                        invoice.id,
                        invoice.device_name,
                        device_status(invoice.status),
                        invoice.payment_mode,
                        payment_tag(invoice.payment_status)
                    ]);
                    index++;
                }
                invoices.value = data;
            }
        })
        .catch(error => {
            console.error("There was an error fetching the invoices:", error);
        });
});

</script>
<style>
@import 'datatables.net-dt';
</style>
