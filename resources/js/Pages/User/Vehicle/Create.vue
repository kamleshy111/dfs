<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { Head, useForm, usePage } from "@inertiajs/vue3";

import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';

import { ref } from "vue";
import Multiselect from "vue-multiselect";




// Get props from the server
const { devices } = usePage().props;

const form = useForm({
  companyName: "",
  model: "",
  fuelType: "",
  chassisNumber: "",
  color: "",
  device_id: [],
});

// go to back
const goBack = () => {
  window.location.href = "/vehicle-type";
};

// Submit the form
const submit = async () => {
    try {
        // Send the form data to the server
        await form.post(route("vehicle-type.store"), {
            headers: {
            "Content-Type": "application/json",
            },
            onSuccess: () => {
              toast.success("Vehicle added successfully.");
            form.reset(); 
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
  <Head title="home" />

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
              <input type="text" v-model="form.companyName" class="form-control" id="companyName" placeholder="" />
              <label for="companyName" class="form-label">Company Name</label>
            </div>
            <div class="form-group col-md-6">
              <input type="text" v-model="form.model" class="form-control" id="model" placeholder="" />
              <label for="model" class="form-label">model</label>
            </div>
          </div>
          <div class="form-row">
            <div class="form-group col-md-6">
              <input type="text" v-model="form.fuelType" class="form-control" id="fuelType" placeholder="" />
              <label for="fuelType" class="form-label">Fuel Type</label>
            </div>
            <div class="form-group col-md-6">
              <input type="text" v-model="form.chassisNumber" class="form-control" id="chassisNumber" placeholder="" />
              <label for="chassisNumber" class="form-label">Chassis Number</label>
            </div>
          </div>
          <div class="form-row">  
            <div class="form-group col-md-6">
              <input type="text" v-model="form.color" class="form-control" id="color" placeholder="" />
              <label for="color" class="form-label">Color</label>
            </div>
            <div class="form-group col-md-6">
              <multiselect
                v-model="form.device_id"
                :options="devices"
                :multiple="true"
                :searchable="true"
                track-by="device_id"
                label="deviceName"
                placeholder="Select devices"
                class="form-control"
              ></multiselect>
              <label class="form-label">Devices</label>
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