<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="home" />

    <AuthenticatedLayout>
        <div class="container my-5">
            <div class="form-container shadow">
                <div class="form-container shadow">
                    <form>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="deviceId">Device ID</label>
                                <input type="text" class="form-control" id="deviceId" placeholder="#123456TB789">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="companyName">Company Name</label>
                                <input type="text" class="form-control" id="companyName" placeholder="Cello NOX Device">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="orderId">Order ID</label>
                                <input type="text" class="form-control" id="orderId" placeholder="#123456TB789">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="purchaseDate">Purchase Date</label>
                                <input type="text" class="form-control" id="purchaseDate" placeholder="#123456TB789">
                            </div>
                        </div>
                        <div class="col-2 p-0 mt-4 mb-4">
                        <button type="submit" class="btn btn-custom btn-block text-white" style="font-size: 20px !important;">Save Customer</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script>
export default {
  props: {
    user: Object, // Define the user prop to receive the data
  },
};
</script>