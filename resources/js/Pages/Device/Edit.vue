<script setup>
import { Head, useForm, usePage } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
// import { useRoute } from "vue-router";

import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';

// Access props
const { deviceDetail } = usePage().props;

// Initialize the form with existing device data
const form = useForm({
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
const submit = async () => {
    try {
        // Send the form data to the server
        await form.post(route("devices.update", deviceDetail.id), {
            headers: {
            "Content-Type": "application/json",
            },
            onSuccess: () => {
            toast.success("Device updated successfully.", {
                    autoClose: 12000, // Toast stays for 3 seconds
                });
            form.reset(); // Reset the form if needed
            
            window.location.href = "/devices";
            },
            onError: (errors) => {
            if (errors) {
                toast.error("An error occurred. Please check your input.");
            }
            },
        });
    } catch (error) {

        toast.error("An unexpected error occurred.");
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
                <form @submit.prevent="submit">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <input type="text" v-model="form.device_id" class="form-control" id="device_id"
                                placeholder="Enter Device ID" />
                            <label for="device_id" class="form-label">Device ID</label>
                        </div>
                        <div class="form-group col-md-6">
                            <input type="text" v-model="form.company_name" class="form-control" id="company_name"
                                placeholder="Enter Company Name" />
                            <label for="company_name" class="form-label">Company Name</label>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <input type="text" v-model="form.order_id" class="form-control" id="order_id"
                                placeholder="Enter Order ID" />
                            <label for="order_id" class="form-label">Order ID</label>
                        </div>
                        <div class="form-group col-md-6">
                            <input type="datetime-local" v-model="form.date_time" class="form-control" id="date_time"
                                placeholder="Enter Purchase Date" />
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