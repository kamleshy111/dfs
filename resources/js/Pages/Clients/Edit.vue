<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { Head, useForm, usePage } from "@inertiajs/vue3";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import Multiselect from "vue-multiselect";
import { ref } from "vue";

import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';
import axios from 'axios';

// Access props
const { devices, customerDetail } = usePage().props;

const form = ref({
  first_name: customerDetail.first_name || "",
  last_name: customerDetail.last_name || "",
  email: customerDetail.email || "",
  phone: customerDetail.phone || "",
  address: customerDetail.address || "",
  device_id: customerDetail.device_id || [],
  quantity: customerDetail.quantity || '0',
  file: customerDetail.file || null,

});

// File handling
const file = ref(null);
const fileName = ref("");

// Handle file upload
const handleFileUpload = (event) => {
  const uploadedFile = event.target.files[0];
  if (uploadedFile) {
    file.value = uploadedFile;
    form.file = uploadedFile;
    fileName.value = uploadedFile.name;
  }
};

// Clear file input
const clearFile = () => {
  file.value = null;
  form.file = null;
  fileName.value = "";
};

// Quantity increment/decrement
const incrementQuantity = () => form.value.quantity++;
const decrementQuantity = () => {
  if (form.value.quantity > 1) form.value.quantity--;
};

// go to back
const goBack = () => {
  window.location.href = "/clients"; // Redirect to the desired Laravel route
};

const submitForm = async () => {
  try {
    const response = await axios.post(`/clients/update/${customerDetail.id}`, form.value);
        toast.success(response.data.message);
  } catch (error) {
    const errorMessage = error.response?.data?.message || 'An error occurred. Please try again.';
    toast.error(errorMessage);
  }
};

</script>

<template>
  <Head title="Add Customer" />

  <AuthenticatedLayout>
    <!-- Form -->
    
    <div class="back-section"><button type="button" class="back-btn-custom" @click="goBack"><i class="bi bi-caret-left"></i> Back</button></div>

    <form @submit.prevent="submitForm">
      <div class="form-row">
        <div class="form-group col-md-6">
          <TextInput
            id="first_name"
            type="text"
            class="mt-1 block w-full form-control"
            v-model="form.first_name"
            required
            autofocus
            placeholder=""
          />
          <label for="first_name" class="form-label">First Name</label>
        </div>
        <div class="form-group col-md-6">
          <TextInput
            id="last_name"
            type="text"
            class="mt-1 block w-full form-control"
            v-model="form.last_name"
            required
            placeholder=""
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
            v-model="form.email"
            required
            placeholder=""
          />
          <label for="email" class="form-label">Email</label>
        </div>
        <div class="form-group col-md-6">
          <TextInput
            id="phone"
            type="text"
            class="mt-1 block w-full form-control"
            v-model="form.phone"
            required
            placeholder=""
          />
          <label for="phone" class="form-label">Phone</label>
        </div>
      </div>

      <div class="form-row">
        <div class="form-group col-md-6">

          <Multiselect
              v-model="form.device_id"
              :options="devices"
              :multiple="true"
              track-by="id"
              label="device_id"
              placeholder="Select devices"
              class="form-control"
          />
        </div>
        <div class="form-group col-md-6">
          <div class="form-quantity">
            <label for="quantity" class="form-label">Quantity</label>
            <div class="input-group">
              <button
                class="btn btn-outline-secondary input-group-prepend"
                type="button"
                @click="decrementQuantity"
              >
                -
              </button>
              <input
                type="text"
                v-model="form.quantity"
                class="form-control text-center"
                readonly
              />
              <button
                class="btn btn-outline-secondary"
                type="button"
                @click="incrementQuantity"
              >
                +
              </button>
            </div>
          
          </div>
        </div>
      </div>

      <div class="form-group relative">
        <TextInput
          id="address"
          type="text"
          class="mt-1 block w-full form-control"
          v-model="form.address"
          required
        />
        <label for="address" class="form-label">Address</label>
      </div>

      <div class="form-group">
        <div
          class="upload-box text-center"
          @drop.prevent="handleFileUpload"
          @dragover.prevent="handleDragOver"
        >
          <i class="bi bi-cloud-arrow-up-fill"></i>
          <span>
            Drop your file here, or
            <label
              for="file-upload"
              class="text-primary"
              style="cursor: pointer"
            >
              browse
            </label>
            .
          </span>
          <p>Supported formats: Excel, Word, PDF</p>
          <input
            type="file"
            id="file-upload"
            class="d-none"
            @change="handleFileUpload"
          />
          <p v-if="fileName">
            {{ fileName }}
            <button class="btn btn-link text-danger" @click="clearFile">
              Clear
            </button>
          </p>
        </div>
      </div>

      <PrimaryButton class="btn save-btn-custom mt-3"
        >Update Customer</PrimaryButton
      >
    </form>
  </AuthenticatedLayout>
</template>

<style>
@import "vue-multiselect/dist/vue-multiselect.min.css";
</style>
<style scoped>

.upload-box {
  border: 2px dashed #ccc;
  border-radius: 10px;
  padding: 20px;
  text-align: center;
  transition: background-color 0.2s ease;
}

.upload-box:hover {
  background-color: #f9f9f9;
}

.upload-box.drag-over {
  background-color: #e9e9e9;
}
</style>
