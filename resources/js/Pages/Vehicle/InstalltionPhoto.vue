<script setup>
import { defineProps, ref } from 'vue';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";

import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';
import axios from 'axios';

const props = defineProps({
    vehicleInstalltionPhotos: Object,
    vehicleId: String,
});

const fileInput = ref(null);
const photoList = ref(props.vehicleInstalltionPhotos);

const openFileInput = () => {
    fileInput.value.click();
};

// Handle file selection and upload
const handleFileChange = async (event) => {
    const file = event.target.files[0];
    if (file) {
        const formData = new FormData();
        formData.append('photo', file);
        formData.append('vehicle_id', props.vehicleId);

        try {
            const response = await axios.post('/vehicle-type/upload-installation-photo', formData);
            photoList.value.push(response.data); // Update the photo list
            toast.success('Photo uploaded successfully!');
        } catch (error) {
            toast.error('Failed to upload photo. Please try again.');
            console.error(error);
        }
    }
};

// Handle delete button click
const handleDeleteClick = async (photoId) => {
    if (confirm('Are you sure you want to delete this photo?')) {
        try {
         
            await axios.delete(`/vehicle-type/delete-installation-photo/${photoId}`);
            photoList.value = photoList.value.filter((photo) => photo.id !== photoId); // Remove from the list
            toast.success('Photo deleted successfully!');
        } catch (error) {
            toast.error('Failed to delete photo. Please try again.');
            console.error(error);
        }
    }
};

// Navigate back
const goBack = () => {
    window.location.href = "/vehicle-type";
};
</script>
<template>
    <Head title="Client Profile" />
    <AuthenticatedLayout>
      <div class="back-section">
        <button type="button" class="back-btn-custom" @click="goBack">
          <i class="bi bi-caret-left"></i> Back
        </button>
      </div>
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-body">
              <div class="d-flex justify-content-between align-items-center pb-3">
                <h5 class="card-title mt-3">Installation Photo List</h5>
                <button class="btn btn-primary" @click="openFileInput">Add Photo</button>
                <input type="file" ref="fileInput" style="display: none" @change="handleFileChange" />
              </div>
              <div class="container">
                <div class="row vehicle-gallery">
                  <div v-for="(image, index) in photoList" :key="index" class="col-md-4">
                    <div class="card mb-4 box-shadow"> 
                      <img class="card-img-top" :src="image.photo_path" alt="Installation Photo" />
                      <div class="card-body">
                        <button @click="handleDeleteClick(image.id)" class="btn btn-danger delete-btn">Delete</button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </AuthenticatedLayout>
  </template>
<style scoped>
.vehicle-gallery img {
    height: 300px;
    object-fit: cover; /* Ensures consistent image sizing */
}

.back-btn-custom {
    margin-bottom: 20px;
}
</style>
  