<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { Head, useForm, usePage } from "@inertiajs/vue3";
import TextInput from "@/Components/TextInput.vue";
import { ref } from "vue";
import Multiselect from "vue-multiselect";

import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';
import axios from 'axios';

// Access data from Inertia props
const { devices } = usePage().props;

const form = ref({
  first_name: "",
  last_name: "",
  email: "",
  secondary_email: "",
  phone: "",
  secondary_phone: "",
  address: "",
  device_id: [],
  file: null,
});


const file = ref(null);
const fileName = ref("");

const selectedSource = ref(""); // Manage the selected source

const handleFileUpload = (event) => {
  const uploadedFile = event.target.files[0];
  if (uploadedFile) {
    file.value = uploadedFile;
    form.value.file = uploadedFile; // Include in form data
    fileName.value = uploadedFile.name;
  }
};

const handleDrop = (event) => {
  event.preventDefault();
  const droppedFile = event.dataTransfer.files[0];
  if (droppedFile) {
    file.value = droppedFile;
    form.file = droppedFile; // Include in form data
    fileName.value = droppedFile.name;
  }
};

const handleDragOver = (event) => {
  event.preventDefault();
};

const clearFile = () => {
  file.value = null;
  form.file = null;
  fileName.value = "";
};

// go to back
const goBack = () => {
  window.location.href = "/clients"; // Redirect to the desired Laravel route
};

const submitForm = async () => {
  try {

    let formData = new FormData();

    formData.append("first_name", form.value.first_name);
    formData.append("last_name", form.value.last_name);
    formData.append("email", form.value.email);
    formData.append("secondary_email",form.value.secondary_email);
    formData.append("phone", form.value.phone);
    formData.append("secondary_phone", form.value.secondary_phone);
    formData.append("address", form.value.address);
    form.value.device_id.forEach((deviceId) => {
      formData.append('device_id[]', deviceId.id);
    });
    formData.append("file", form.value.file);

    // Send form data to the server
    const response = await axios.post('/clients/store', formData);

    // Handle successful response
    toast.success(response.data.message);
    form.value.first_name = '';
    form.value.last_name = '';
    form.value.email = '';
    form.value.secondary_email = '',
    form.value.phone = '';
    form.value.secondary_phone = '',
    form.value.address = '';
    form.value.device_id = [];
    form.value.file = '';

    // Reload the window
    setTimeout(() => {
      window.location.reload();
    }, 3000);
    
  } catch (error) {
    const errorMessage = error.response?.data?.message || 'An error occurred. Please try again.';
    toast.error(errorMessage);
  }
};
</script>

<template>
  <Head title="Add Customer" />

  <AuthenticatedLayout>
  
   <div class="back-section">
     <button type="button" class="back-btn-custom" @click="goBack"> <i class="bi bi-caret-left"></i> Back</button>
   </div>

    <!-- Form -->
    <form @submit.prevent="submitForm" enctype="multipart/form-data">
      <div class="form-row">
        <div class="form-group col-md-6">
          <TextInput
            id="first_name"
            type="text"
            class="mt-1 block w-full form-control"
            placeholder=""
            v-model="form.first_name"
            autofocus
            autocomplete="first_name"
          />
          <label for="first_name" class="form-label">First Name</label>
        </div>

        <div class="form-group col-md-6">
          <TextInput
            id="last_name"
            type="text"
            class="mt-1 block w-full form-control"
            placeholder=""
            v-model="form.last_name"
            autofocus
            autocomplete="last_name"
          />
          <label for="last_name" class="form-label">Last Name</label>
        </div>
      </div>
      <div class="form-row">
        <div class="form-group col-md-6">
          <TextInput
            id="email"
            type="email"
            class="mt-1 block w-full form-control"
            placeholder=""
            v-model="form.email"
            autofocus
            autocomplete="email"
          />
          <label for="email" class="form-label">Primary Email ID</label>
        </div>
        <div class="form-group col-md-6">
          <TextInput
            id="secondary_email"
            type="email"
            class="mt-1 block w-full form-control"
            placeholder=""
            v-model="form.secondary_email"
            autofocus
            autocomplete="secondary_email"
          />
          <label for="secondary_email" class="form-label">Secondary Email ID</label>
        </div>
      </div>
      <div class="form-row">
        <div class="form-group col-md-6">
          <TextInput
            id="phone"
            type="number"
            class="mt-1 block w-full form-control"
            placeholder=""
            v-model="form.phone"
            autofocus
            autocomplete="phone"
          />
          <label for="phone" class="form-label">Primary Mobile Number</label>
        </div>
        <div class="form-group col-md-6">
          <TextInput
            id="secondary_phone"
            type="number"
            class="mt-1 block w-full form-control"
            placeholder=""
            v-model="form.secondary_phone"
            autofocus
            autocomplete="secondary_phone"
          />
          <label for="secondary_phone" class="form-label">Secondary Mobile Number</label>
        </div>
      </div>
      <div class="form-group relative">
        <TextInput
          id="address"
          type="text"
          class="mt-1 block w-full form-control"
          v-model="form.address"
          autofocus
          autocomplete="address"
          placeholder=""
        />
         <label for="address" class="form-label">Address</label>
      </div>
      <div class="form-row">
       
      
        <div class="form-group col-md-6">
          <select class="form-control" id="deviceSource"  v-model="selectedSource" >
                <option value="">Select Devices Sources</option>
                <option value="Get-Sheet">Get-Sheet</option>
                <option value="Get-Dropdown">Get-Dropdown</option>
              </select>
              <label for="deviceSource" class="form-label">Select Devices Sources</label>
        </div>
      </div>
      <div class="form-row" v-if="selectedSource === 'Get-Dropdown'">
        <div class="form-group col-md-6">
          <multiselect
            v-model="form.device_id"
            :options="devices"
            :multiple="true"
            :searchable="true"
            track-by="id"
            label="device_id"
            placeholder="Select devices"
             @update:model-value="watchDeviceSelection"
          ></multiselect>
        </div>
      </div>
      <div class="form-group"  v-if="selectedSource === 'Get-Sheet'">
        <div
          class="upload-box text-center"
          @drop="handleDrop"
          @dragover="handleDragOver"
          :class="{ 'drag-over': fileName }"
        >
          <i class="bi bi-cloud-arrow-up-fill"></i>
          <span>
            Drop Your File Here, or
            <label for="file-upload" style="color: var(--light-color); cursor: pointer">
              Browse
            </label>
          </span>
          <p>Supports file, Excel, Word, PDF</p>
          <input
            type="file"
            id="file-upload"
            class="d-none"
            @change="handleFileUpload"
          />
          <!-- Display uploaded file name -->
          <p v-if="fileName">
            {{ fileName }}
            <button class="btn btn-link text-danger" @click="clearFile">
              Clear
            </button>
          </p>
        </div>
      </div>

      <PrimaryButton class="btn save-btn-custom">Save Customer</PrimaryButton>
    </form>
  </AuthenticatedLayout>
</template>

<style>
@import "vue-multiselect/dist/vue-multiselect.min.css";
.upload-box {
  border: 2px dashed #d1d5db;
  border-radius: 10px;
  padding: 20px;
  text-align: center;
  background-color: #fff;
  box-shadow: 0 .125rem .25rem rgba(var(--bs-body-color-rgb),.075)!important;
  transition: background-color 0.2s ease;
}

.upload-box:hover {
  background-color: #f9f9f9;
}

.upload-box.drag-over {
  background-color: #e9e9e9;
}
</style>
