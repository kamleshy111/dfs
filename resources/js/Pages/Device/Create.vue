<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

import GuestLayout from "@/Layouts/GuestLayout.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { Head, Link  } from "@inertiajs/vue3";
import { ref, computed } from "vue";

import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';
import axios from 'axios';

// Form state
const form = ref({
  deviceId: '',
  companyName: '',
  orderId: '',
  purchaseDate: '',
  invoicePhotos: null,
});

// Handle file input change
const handleFileChange = (event) => {
  const file = event.target.files[0];
  if (file) {
    form.value.invoicePhotos = file;
  }
};


const imagePreview = computed(() => {
  return form.value.invoicePhotos
    ? URL.createObjectURL(form.value.invoicePhotos)
    : null;
});

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
              <input type="datetime-local" v-model="form.purchaseDate" class="form-control" id="purchaseDate" placeholder="" />
              <label for="purchaseDate" class="form-label">Purchase Date</label>
            </div>
            <div class="form-group col-md-6">
              <input type="file" accept="image/*" @change="handleFileChange" class="form-control" id="invoicePhotos" required>
              <label for="invoicePhotos" class="form-label">Invoice Photos: </label>

              <img v-if="form.invoicePhotos" :src="imagePreview" alt="Invoice Photos" class="mt-2" style="max-width:20%;">
            </div>
          </div>
          <div class="col-md-3 col-12 p-0 mt-4">
            <PrimaryButton class="btn save-btn-custom" style="font-size: 20px !important" >
              Save Customer
            </PrimaryButton>
          </div>
        </form>
      </div>
    </div>
  </AuthenticatedLayout>
</template>