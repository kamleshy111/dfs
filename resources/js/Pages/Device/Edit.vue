<script setup>
import { Head, useForm, usePage } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { useRoute } from "vue-router";
import { ref } from "vue";

import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';
import axios from 'axios';

// Access props
const { deviceDetail } = usePage().props;

const form = ref({
    device_id: deviceDetail.device_id || "",
    company_name: deviceDetail.company_name || "",
    order_id: deviceDetail.order_id || "",
    date_time: deviceDetail.date_time || "",

});

// go to back
const goBack = () => {
  window.location.href = "/devices";
};

// Form submit handler
const submitForm = async () => {
  try {
    const response = await axios.post(`/devices/update/${deviceDetail.id}`, form.value);
        toast.success(response.data.message);
  } catch (error) {
    const errorMessage = error.response?.data?.message || 'An error occurred. Please try again.';
    toast.error(errorMessage);
  }
};
</script>
<template>

    <Head title="Edit Device" />

    <AuthenticatedLayout>
        <div class="back-section">
            <button type="button" class="back-btn-custom" @click="goBack">
                <i class="bi bi-caret-left"></i> Back
            </button>
        </div>

        <div class="my-3">
            <div class="form-container shadow from-main-design">
                <form @submit.prevent="submitForm">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <input type="text" v-model="form.device_id" class="form-control" id="device_id"
                                placeholder="" />
                            <label for="device_id" class="form-label">Device ID</label>
                        </div>
                        <div class="form-group col-md-6">
                            <input type="text" v-model="form.company_name" class="form-control" id="company_name"
                                placeholder="" />
                            <label for="company_name" class="form-label">Company Name</label>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <input type="text" v-model="form.order_id" class="form-control" id="order_id"
                                placeholder="" />
                            <label for="order_id" class="form-label">Order ID</label>
                        </div>
                        <div class="form-group col-md-6">
                            <input type="datetime-local" v-model="form.date_time" class="form-control" id="date_time"
                                placeholder="" />
                            <label for="date_time" class="form-label">Purchase Date</label>
                        </div>
                    </div>
                    <div class="col-md-3 col-12 p-0 mt-4">
                        <PrimaryButton class="btn save-btn-custom" style="font-size: 20px !important">
                            Update Device
                        </PrimaryButton>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>