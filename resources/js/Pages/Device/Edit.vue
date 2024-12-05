<script setup>
import { Head, useForm, usePage } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { ref, computed } from "vue";
import { toast } from "vue3-toastify";
import "vue3-toastify/dist/index.css";
import axios from "axios";

// Access props
const { deviceDetail } = usePage().props;

// Form setup
const form = ref({
    device_id: deviceDetail.device_id || "",
    company_name: deviceDetail.company_name || "",
    order_id: deviceDetail.order_id || "",
    date_time: deviceDetail.date_time || "",
    invoice_photos: deviceDetail.invoice_photos || "",
});

// File name and preview state
const fileName = ref(null); // Track selected file name

// Handle file input change
const handleFileChange = (event) => {
    const file = event.target.files[0];
    if (file) {
        form.value.invoice_photos = file;
        fileName.value = file.name; // Store file name
    }
};

// Image preview logic
const imagePreview = computed(() => {
    if (form.value.invoice_photos instanceof File) {
        return URL.createObjectURL(form.value.invoice_photos); // Preview newly uploaded image
    } else if (form.value.invoice_photos) {
        return form.value.invoice_photos; // Existing image URL
    }
    return null;
});


// Go back
const goBack = () => {
    window.location.href = "/devices";
};

// Form submit handler
const submitForm = async () => {
    try {
        let formData = new FormData();
        formData.append("device_id", form.value.device_id);
        formData.append("company_name", form.value.company_name);
        formData.append("order_id", form.value.order_id);
        formData.append("date_time", form.value.date_time);

        if (form.value.invoice_photos instanceof File) {
            formData.append("invoice_photos", form.value.invoice_photos);
        }

        // Send the update request
        const response = await axios.post(`/devices/update/${deviceDetail.id}`, formData);

        // Handle successful response
        toast.success(response.data.message);

        // Reset file input
        form.value.invoice_photos = response.data.updated_invoice_photo_url || form.value.invoice_photos;
    } catch (error) {
        const errorMessage = error.response?.data?.message || "An error occurred. Please try again.";
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
                <form @submit.prevent="submitForm" enctype="multipart/form-data">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <input type="text" v-model="form.device_id" class="form-control" id="device_id"
                                placeholder="" />
                            <label for="device_id" class="form-label">Device ID</label>
                        </div>
                        <div class="form-group col-md-6">
                            <input
                                type="text"
                                v-model="form.company_name"
                                class="form-control"
                                id="company_name"
                                placeholder=""
                            />
                            <label for="company_name" class="form-label">Company Name</label>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <input
                                type="text"
                                v-model="form.order_id"
                                class="form-control"
                                id="order_id"
                                placeholder=""
                            />
                            <label for="order_id" class="form-label">Order ID</label>
                        </div>
                        <div class="form-group col-md-6">
                            <input type="datetime-local" v-model="form.date_time" class="form-control" id="date_time"
                                placeholder="" />
                            <label for="date_time" class="form-label">Purchase Date</label>
                        </div>
                    
                        <div class="form-group col-md-12">
                            <div style="border: 2px dashed #ccc; border-radius: 8px; padding: 20px; text-align: center;">
                                <label for="invoice_photos" style="cursor: pointer; text-align: center;">
                                    <!-- <input type="file" id="invoice_photos" @change="handleFileChange" /> -->

                                    <!-- Image Preview -->
                                    <div v-if="imagePreview">
                                        <img :src="imagePreview" alt="Invoice Photo Preview" class="img-fluid preview-img" style="width: 100px; height: 100px; object-fit: cover; border-radius: 8px; display: block; margin: 10px auto;" />
                                    </div>
                                    <span style="font-size: 18px;" class="mt-2">Click to Select Invoice <span style="color: #2239c3cc;">Image</span></span>

                                    <!-- Hidden file input -->
                                    <input id="invoice_photos" type="file" style="display: none;" accept="image/*" @change="handleFileChange" />
                                    <p v-if="fileName">
                                        {{ fileName }}
                                        <button class="btn btn-link text-danger" @click="clearFile">
                                        Clear
                                        </button>
                                    </p>
                                </label>    
                            </div>
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