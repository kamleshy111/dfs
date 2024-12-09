<script setup>
import { Head, useForm, usePage } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { useRoute } from "vue-router";

import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';
import axios from 'axios';
import Multiselect from "vue-multiselect";
import { ref, onMounted } from "vue";

import Datepicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';

// Date format to use in the Datepicker
const dateFormat = "dd/MM/yyyy";

// Access props
const { customers, vehicleDetail } = usePage().props;

const form = ref({
    customer_id: vehicleDetail.customer_id || 0,
    device_id: vehicleDetail.device_id || 0,
    vehicle_register_number: vehicleDetail.vehicle_register_number || "",
    vehicle_type: vehicleDetail.vehicle_type || "",
    imei_number: vehicleDetail.imei_number || "",
    sim_card_number: vehicleDetail.sim_card_number || "",
    installation_date: vehicleDetail.installation_date || "",
    start_date: vehicleDetail.start_date || "",
    duration: vehicleDetail.duration || "",
    duration_unit: vehicleDetail.duration_unit || "",
    sim_operator: vehicleDetail.sim_operator || "",
    

});

// Devices list
const devices = ref([]);

// Fetch devices when the component is mounted or when customer_id changes
onMounted(() => {
  if (vehicleDetail.customer_id) {
    fetchDevices(vehicleDetail.customer_id);
  }
});

const fetchDevices = async (customerId) => {
  try {
    const response = await axios.get(`/api/customers/${customerId}/devices`);
    devices.value = response.data.devices;
    console.log("Devices fetched:", devices.value);
  } catch (error) {
    console.error("Error fetching devices:", error);
    toast.error("Failed to fetch devices. Please try again.");
  }
};

// Method to handle change event
const onCustomerChange = async () => {
  if (form.value.customer_id) {
    try {
      const response = await axios.get(`/api/customers/${form.value.customer_id}/devices`);
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

// go to back
const goBack = () => {
  window.location.href = "/vehicle-type";
};

// Form submit handler
const submitForm = async () => {
  try {
    const response = await axios.post(`/vehicle-type/update/${vehicleDetail.id}`, form.value);
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
                            <select class="form-control" v-model="form.customer_id" @change="onCustomerChange">
                                <option value="" disabled>Select Customer Name</option>
                                <option v-for="customer in customers" v-bind:value="customer.id" >{{ customer.first_name ? customer.first_name + ' ' + (customer.last_name ?? '') : '---' }}</option>
                            </select>
                            <label for="customerId" class="form-label">customers Name</label>
                        </div>
                        <div class="form-group col-md-6">
                            <select class="form-control" v-model="form.device_id">
                                <option value="" disabled>Select Device ID</option>
                                <option v-for="device in devices" v-bind:value="device.id" >{{ device.device_id }}</option>
                            </select>
                            <label for="device_id" class="form-label">Devices Name</label>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <select class="form-control" v-model="form.vehicle_type">
                                <option value="" disabled>Select Vehicle type</option>
                                <option value="car">Car</option>
                                <option value="truck">Truck</option>
                                <option value="motorcycle">Motorcycle</option>
                                <option value="bus">Bus</option>
                                <option value="other">Other</option>
                            </select>
                            <label for="vehicle_type" class="form-label">Select Vehicle type</label>
                        </div>
                        <div class="form-group col-md-6">
                            <input type="text" v-model="form.vehicle_register_number" class="form-control" id="vehicle_register_number"
                                placeholder="" />
                            <label for="vehicle_register_number" class="form-label">Vehicle Rregister Number</label>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <input type="text" v-model="form.sim_operator" class="form-control" id="sim_operator"
                                placeholder="" />
                            <label for="sim_operator" class="form-label">SIM Operator</label>
                        </div>
                        <div class="form-group col-md-6">
                            <input type="text" v-model="form.sim_card_number" class="form-control" id="sim_card_number"
                                placeholder="" />
                            <label for="sim_card_number" class="form-label">SIM Card Number</label>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <input type="text" v-model="form.imei_number" class="form-control" id="imei_number"
                                placeholder="" />
                            <label for="imei_number" class="form-label">IMEI Number</label>
                        </div>
                        <div class="form-group col-md-6">
                            <Datepicker v-model="form.installation_date" class="form-control" id="installation_date" :format="dateFormat" placeholder="" />
                            <label for="installation_date" class="form-label">Installation Date</label>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <Datepicker v-model="form.start_date" class="form-control" id="start_date" :format="dateFormat" placeholder="" />
                            <label for="start_date" class="form-label">Start Date</label>
                        </div>
                        <div class="form-row duration-unit">
                        <div class="form-group m-0 duration-select">
                        <input type="number" v-model="form.duration" class="form-control" id="duration" placeholder="" />
                        <label for="duration" class="form-label">Duration</label>
                        </div>
                        <div class="form-group m-0 duration-option">
                        <select v-model="form.duration_unit" class="form-control" id="duration_unit">
                            <option value="days">Days</option>
                            <option value="months">Months</option>
                            <option value="years">Years</option>
                        </select>
                        </div>
                   </div>
                    </div>
                    <div class="col-md-3 col-12 p-0 mt-4">
                        <PrimaryButton class="btn save-btn-custom">
                            Update Vehicle
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