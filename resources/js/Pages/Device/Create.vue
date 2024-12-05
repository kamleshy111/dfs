<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

import GuestLayout from "@/Layouts/GuestLayout.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { Head, Link } from "@inertiajs/vue3";
import { ref, computed } from "vue";

import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';
import axios from 'axios';

import Datepicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';

// Form state
const form = ref({
  deviceId: '',
  companyName: '',
  orderId: '',
  purchaseDate: '',
  invoicePhotos: null,
});

// Image preview state
const imagePreview = computed(() => {
  return form.value.invoicePhotos
    ? URL.createObjectURL(form.value.invoicePhotos)
    : null;
});

// Handle file input change
const handleFileChange = (event) => {
  const file = event.target.files[0];
  if (file) {
    form.value.invoicePhotos = file;
  } else {
    form.value.invoicePhotos = null;
  }
};

// go to back
const goBack = () => {
  window.location.href = "/devices";
};

// Submit the form
const submitForm = async () => {
  try {

    let formData = new FormData();

    formData.append("deviceId", form.value.deviceId);
    formData.append("companyName", form.value.companyName);
    formData.append("orderId", form.value.orderId);
    formData.append("purchaseDate", form.value.purchaseDate);
    formData.append("invoicePhotos", form.value.invoicePhotos);

    // Send form data to the server
    const response = await axios.post('/devices/store', formData);

    // Handle successful response
    toast.success(response.data.message);
    form.value.deviceId = '';
    form.value.companyName = '',
      form.value.orderId = '';
    form.value.purchaseDate = '',
      form.value.invoicePhotos = '';

  } catch (error) {
    const errorMessage = error.response?.data?.message || 'An error occurred. Please try again.';
    toast.error(errorMessage);
  }
};
</script>
<template>

  <Head title="home" />

  <AuthenticatedLayout>
    <div class="back-section">
      <button type="button" class="back-btn-custom" @click="goBack">
        <i class="bi bi-caret-left"></i> Back
      </button>

      <div class="my-3">

        <form @submit.prevent="submitForm" enctype="multipart/form-data">
          <div class="form-row">
            <div class="form-group col-md-6">
              <input type="text" v-model="form.deviceId" class="form-control" id="deviceId" placeholder="" />
              <label for="deviceId" class="form-label">Device ID</label>
            </div>
            <div class="form-group col-md-6">
              <input type="text" v-model="form.companyName" class="form-control" id="companyName" placeholder="" />
              <label for="companyName" class="form-label">Company Name</label>
            </div>
          </div>
          <div class="form-row">
            <div class="form-group col-md-6">
              <input type="number" v-model="form.orderId" class="form-control" id="orderId" placeholder="" />
              <label for="orderId" class="form-label">Order ID</label>
            </div>
            <div class="form-group col-md-6">
              <Datepicker v-model="form.purchaseDate" class="form-control" id="purchaseDate" placeholder="" />
              <label for="purchaseDate" class="form-label">Purchase Date</label>
            </div>
            <div class="form-group col-md-12">
              <div style="border: 2px dashed #ccc; border-radius: 8px; padding: 20px; text-align: center;">
                <label for="fileUpload" style="cursor: pointer; text-align: center;">
                  <!-- Image preview, initially hidden -->
                  <img v-if="imagePreview" :src="imagePreview" alt="Invoice Photos"
                    style="width: 100px; height: 100px; object-fit: cover; border-radius: 8px; display: block; margin: 10px auto;" />

                  <!-- Upload icon and text when no image is selected -->
                  <p v-else>
                    <i id="uploadIcon" class="bi bi-cloud-arrow-up-fill" style="color: #2239c3cc; font-size: 40px;"></i>
                  </p>
                  <span style="font-size: 18px;">Click to Select Invoice <span style="color: #2239c3cc;">Image</span></span>

                  <!-- Image name placeholder, will be shown when a file is selected -->
                  <p v-if="form.invoicePhotos" style="font-size: 14px; color: #555;">{{ form.invoicePhotos.name }}</p>
                </label>

                <!-- Hidden file input -->
                <input id="fileUpload" type="file" style="display: none;" accept="image/*" @change="handleFileChange" />
              </div>
            </div>
          </div>
          <div class="col-md-3 col-12 p-0 mt-4">
            <PrimaryButton class="btn save-btn-custom" style="font-size: 20px !important">
              Save Customer
            </PrimaryButton>
          </div>
        </form>
      </div>
    </div>
  </AuthenticatedLayout>
</template>