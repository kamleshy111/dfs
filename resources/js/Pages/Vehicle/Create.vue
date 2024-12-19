<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { Head, usePage } from "@inertiajs/vue3";

import { toast } from "vue3-toastify";
import "vue3-toastify/dist/index.css";

import axios from "axios";
import { ref } from "vue";


import Datepicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';

// Props from the server
const { customers } = usePage().props;

// Reactive form state
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
  durationUnit: "years",
  simOperator: "",
});

// Date format to use in the Datepicker
 const dateFormat = "dd/MM/yyyy";


// Devices list
const devices = ref([]);

// Method to handle change event
const onCustomerChange = async () => {
  if (form.value.customerId) {
    try {
      const response = await axios.get(`/api/customers/${form.value.customerId}/devices`);
      devices.value = response.data.devices;
      console.log("Devices fetched:", devices.value);
    } catch (error) {
      console.error("Error fetching devices:", error);
      toast.error("Failed to fetch devices. Please try again.");
    }
  } else {
    devices.value = [];
  }
};

// Navigate back to the previous page
const goBack = () => {
  window.location.href = "/vehicle-type";
};

// Submit the form data
const submitForm = async () => {
  try {
    const response = await axios.post(`/vehicle-type/store`, form.value);
    toast.success(response.data.message);

    // Reset the form
    form.value = {
      customerId: "",
      deviceId: "",
      vehicleRegisterNumber: "",
      vehicleType: "",
      imeiNumber: "",
      simCardNumber: "",
      installationDate: "",
      startDate: "",
      duration: "",
      durationUnit: "",
      simOperator: "",
 
    };
  } catch (error) {
    const errorMessage = error.response?.data?.message || "An error occurred. Please try again.";
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
              <select class="form-control" v-model="form.customerId" @change="onCustomerChange" >
                  <option value="" disabled>Select Customer Name</option>
                  <option v-for="customer in customers" :key="customer.id" :value="customer.id">
                    {{ customer.first_name ? customer.first_name + ' ' + (customer.last_name ?? '') : '---' }}

                  </option>
                </select>
                <label for="customerId" class="form-label">Customers Name</label>

            </div>
            <div class="form-group col-md-6">
              <div v-if="devices.length">
                <select class="form-control" v-model="form.deviceId">
                  <option value="" disabled>Select Device</option>
                  <option v-for="device in devices" :key="device.id" :value="device.id">
                    {{ device.device_id }}
                  </option>
                </select>
                <label for="deviceId" class="form-label">Device Name</label>
              </div>
              <div v-else-if="form.customerId">
                  <select class="form-control">
                  <option value="" disabled>Select Device</option>
                </select>
                <label for="deviceId" class="form-label">Device Name</label>
              </div>
            </div>
          </div>
          <div class="form-row">
            <div class="form-group col-md-6">
              <select class="form-control" v-model="form.vehicleType">
                <option value="" disabled>Select Vehicle type</option>
                <option value="Dangerous Goods Transporter">Dangerous Goods Transporter</option>
                <option value="Ordinary Passenger Transport Vehicle">Non-Ordinary Passenger Transport Vehicle</option>
                <option value="Rural Passenger Transport Vehicle">Rural Passenger Transport Vehicle</option>
                <option value="Taxi">Taxi</option>
                <option value="Freight Vehicle">Freight Vehicle</option>
                <option value="Waste Truck">Waste Truck</option>
                <option value="Sanitation Vehicle">Sanitation Vehicle</option>
                <option value="Concrete Vehicle">Concrete Vehicle</option>
                <option value="Excavator">Excavator</option>
                <option value="Engineering Vehicle">Engineering Vehicle</option>
                <option value="other">Other</option>
              </select>
              <label for="vehicleType" class="form-label">Select Vehicle type</label>
            </div>
            <div class="form-group col-md-6">
              <input type="text" v-model="form.vehicleRegisterNumber" class="form-control" id="vehicleRegisterNumber" placeholder="" />
              <label for="vehicleRegisterNumber" class="form-label">Vehicle Register Number</label>
            </div>
          </div>
          <div class="form-row">
            <div class="form-group col-md-6">
              <input type="text" v-model="form.simOperator" class="form-control" id="simOperator" placeholder="" />
              <label for="simOperator" class="form-label">SIM Operator</label>
            </div>
            <div class="form-group col-md-6">
              <input type="text" v-model="form.simCardNumber" class="form-control" id="simCardNumber" placeholder="" />
              <label for="simCardNumber" class="form-label">SIM Card Number</label>
            </div>
          </div>
          <div class="form-row">

            <div class="form-group col-md-6">
              <input type="text" v-model="form.imeiNumber" class="form-control" id="imeiNumber" placeholder="" />
              <label for="imeiNumber" class="form-label">IMEI Number</label>
            </div>
            <div class="form-group col-md-6">
              <Datepicker v-model="form.installationDate" class="form-control" id="installationDate" :format="dateFormat" placeholder="" />
              <label for="installationDate" class="form-label">Installation Date</label>
            </div>
          </div>
          <div class="form-row">
            <div class="form-group col-md-6">
              <Datepicker v-model="form.startDate" class="form-control" id="startDate" :format="dateFormat" placeholder="" />
              <label for="startDate" class="form-label">Start Date</label>
            </div>

            <div class="col-md-6 col-12">
              <div class="form-row duration-unit">
              <div class="form-group m-0 duration-select">
                <input type="number" v-model="form.duration" class="form-control" id="duration" placeholder="" />
                <label for="duration" class="form-label">Duration</label>
              </div>
              <div class="form-group m-0 duration-option">
                <select v-model="form.durationUnit" class="form-control" id="durationUnit">
                  <option value="days">Days</option>
                  <option value="months">Months</option>
                  <option value="years">Years</option>
                </select>
              </div>
            </div>            
          </div>
          </div>

          <div class="col-md-3 col-12 p-0 mt-4">
            <PrimaryButton class="btn save-btn-custom">
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