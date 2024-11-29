<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

import GuestLayout from "@/Layouts/GuestLayout.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { Head, Link  } from "@inertiajs/vue3";
import { ref } from "vue";

import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';
import axios from 'axios';

// Form state
const form = ref({
  deviceId: '',
  companyName: '',
  orderId: '',
  purchaseDate: '',
});

// go to back
const goBack = () => {
  window.location.href = "/devices";
};

// Submit the form
const submitForm = async () => {
  try {
    const response = await axios.post(`/devices/store`, form.value);
    toast.success(response.data.message);
    form.value = {
      device_id: "",
      company_name: "",
      order_id: "",
      date_time: "",
    };
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
               
        <form @submit.prevent="submitForm">
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