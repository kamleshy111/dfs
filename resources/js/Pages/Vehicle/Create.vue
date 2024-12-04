<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { Head, useForm, usePage } from "@inertiajs/vue3";

import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';
import axios from 'axios';

import { ref } from "vue";
import Multiselect from "vue-multiselect";




// Get props from the server
const { devices, customers } = usePage().props;

const form = ref({
  customerId: "",
  deviceId: "",
  vehicleRegisterNumber: "",
  vehicleType: "",
  imeiNumber: "",
  simCardNumber: "",
  installationDate: "",
  startDate: "",
  duration: "",
  simOperator: "",

});

// go to back
const goBack = () => {
  window.location.href = "/vehicle-type";
};

const submitForm = async () => {
  try {
    const response = await axios.post(`/vehicle-type/store`, form.value);
    toast.success(response.data.message);
    form.value = {
      customerId: "",
      deviceId: "",
      vehicleRegisterNumber: "",
      vehicleType: "",
      vehicleName: "",
      imeiNumber: "",
      simCardNumber: "",
      installationDate: "",
      startDate: "",
      duration: "",
      simOperator: "",
 
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
    </div>

    <div class="my-3">
      <div class="form-container shadow from-main-design">
        <form @submit.prevent="submitForm">
          <div class="form-row">
            <div class="form-group col-md-6">
              <select class="form-control" v-model="form.customerId">
                <option value="" disabled>Select Customer Name</option>
                <option v-for="customer in customers" v-bind:value="customer.id" >{{ customer.first_name +' '+ customer.last_name }}</option>
              </select>
              <label for="customerId" class="form-label">customers Name</label>
            </div>
            <div class="form-group col-md-6">
              <select class="form-control" v-model="form.deviceId">
                <option value="" disabled>Select Device ID</option>
                <option v-for="device in devices" v-bind:value="device.device_id" >{{ device.device_name }}</option>
              </select>
              <label for="deviceId" class="form-label">Devices Name</label>
            </div>
          </div>
          <div class="form-row">
            <div class="form-group col-md-6">
              <input type="text" v-model="form.vehicleRegisterNumber" class="form-control" id="vehicleRegisterNumber" placeholder="" />
              <label for="vehicleRegisterNumber" class="form-label">Vehicle Register Number</label>
            </div>
            <div class="form-group col-md-6">
              <input type="text" v-model="form.vehicleName" class="form-control" id="vehicleName" placeholder="" />
              <label for="vehicleName" class="form-label">Vehicle Name</label>
            </div>
          </div>
          <div class="form-row">
            <div class="form-group col-md-6">
              <select class="form-control" v-model="form.vehicleType">
                <option value="" disabled>Select Vehicle type</option>
                <option value="car">Car</option>
                <option value="truck">Truck</option>
                <option value="motorcycle">Motorcycle</option>
                <option value="bus">Bus</option>
                <option value="other">Other</option>
              </select>
              <label for="vehicleType" class="form-label">Select Vehicle type</label>
            </div>
            <div class="form-group col-md-6">
              <input type="text" v-model="form.imeiNumber" class="form-control" id="imeiNumber" placeholder="" />
              <label for="imeiNumber" class="form-label">IMEI Number</label>
            </div>
          </div>
          <div class="form-row">
            <div class="form-group col-md-6">
              <input type="text" v-model="form.simCardNumber" class="form-control" id="simCardNumber" placeholder="" />
              <label for="simCardNumber" class="form-label">SIM Card Number</label>
            </div>
            <div class="form-group col-md-6">
              <input type="date" v-model="form.installationDate" class="form-control" id="installationDate" placeholder="" />
              <label for="installationDate" class="form-label">Installation Date</label>
            </div>
          </div>
          <div class="form-row">
            <div class="form-group col-md-6">
              <input type="date" v-model="form.startDate" class="form-control" id="startDate" placeholder="" />
              <label for="startDate" class="form-label">Start Date</label>
            </div>
            <div class="form-group col-md-6">
              <input type="text" v-model="form.duration" class="form-control" id="duration" placeholder="" />
              <label for="duration" class="form-label">Duration</label>
            </div>
          </div>
          <div class="form-row">
            <div class="form-group col-md-6">
              <input type="text" v-model="form.simOperator" class="form-control" id="simOperator" placeholder="" />
              <label for="simOperator" class="form-label">SIM Operator</label>
            </div>
          </div>
          <div class="col-md-3 col-12 p-0 mt-4">
            <PrimaryButton class="btn save-btn-custom" style="font-size: 20px !important" >
              Save vehicle
            </PrimaryButton>
          </div>
        </form>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<style>
@import "vue-multiselect/dist/vue-multiselect.min.css";

</style>