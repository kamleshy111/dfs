<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { Head, useForm, usePage } from "@inertiajs/vue3";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import { ref } from "vue";
import Multiselect from "vue-multiselect";

// Access data from Inertia props
const { devices } = usePage().props;

const form = useForm({
  first_name: "",
  last_name: "",
  email: "",
  phone: "",
  address: "",
  device_id: [],
  quantity: 1,
  file: null,
});

const file = ref(null);
const fileName = ref("");

const handleFileUpload = (event) => {
  const uploadedFile = event.target.files[0];
  if (uploadedFile) {
    file.value = uploadedFile;
    form.file = uploadedFile; // Include in form data
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

// Increase quantity
const incrementQuantity = () => {
  form.quantity++;
};

// Decrease quantity
const decrementQuantity = () => {
  if (form.quantity > 1) form.quantity--;
};

// Submit the form
const submit = () => {
  const formData = new FormData();
  for (const [key, value] of Object.entries(form)) {
    formData.append(key, value);
  }
  form.post(route("clients.store"), { data: formData });
};
</script>

<template>
  <Head title="Add Customer" />

  <AuthenticatedLayout>
  
   <div class="back-section">
     <button type="button" class="back-btn-custom"> <i class="bi bi-caret-left"></i> Back</button>
   </div>

    <!-- Form -->
    <form @submit.prevent="submit">
      <div class="form-row">
        <div class="form-group col-md-6">
          <TextInput
            id="first_name"
            type="text"
            class="mt-1 block w-full form-control"
            placeholder=""
            v-model="form.first_name"
            required
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
            required
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
            required
            autofocus
            autocomplete="email"
          />
          <label for="email" class="form-label">Email ID</label>
        </div>
        <div class="form-group col-md-6">
          <TextInput
            id="phone"
            type="number"
            class="mt-1 block w-full form-control"
            placeholder=""
            v-model="form.phone"
            required
            autofocus
            autocomplete="phone"
          />
          <label for="mobile" class="form-label">Mobile Number</label>
        </div>
      </div>
      <div class="form-row">
        <div class="form-group col-md-6">
          <multiselect
            v-model="form.device_id"
            :options="devices"
            :multiple="true"
            :searchable="true"
            track-by="id"
            label="device_id"
            placeholder="Select devices"
            class="form-control"
          ></multiselect>
        </div>
        <div class="form-group col-md-6">
          <div class="form-quantity">
            <label class="form-label">Add Quantity</label>
            <div class="input-group">
              <div class="input-group-prepend">
                <button
                  class="btn btn-outline-secondary"
                  type="button"
                  @click="decrementQuantity"
                >
                  -
                </button>
              </div>
              <input
                type="text"
                v-model="form.quantity"
                class="form-control text-center"
                readonly
              />
              <div class="input-group-append">
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
      </div>
      <div class="form-group relative">
        <TextInput
          id="address"
          type="text"
          class="mt-1 block w-full form-control"
          v-model="form.address"
          required
          autofocus
          autocomplete="address"
          placeholder=""
        />
         <label for="address" class="form-label">Address</label>
      </div>
      <div class="form-group">
        <div
          class="upload-box text-center"
          @drop="handleDrop"
          @dragover="handleDragOver"
          :class="{ 'drag-over': fileName }"
        >
          <i class="bi bi-cloud-arrow-up-fill"></i>
          <span>
            Drop Your File Here, or
            <label for="file-upload" style="color: #2239c3cc; cursor: pointer">
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

      <PrimaryButton
        class="btn save-btn-custom"
        style="
          background-color: var(--light-color);
          color: #fff;
          font-size: 20px;
        "
      >
        Save Customer
      </PrimaryButton>
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
