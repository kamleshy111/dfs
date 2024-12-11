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
    const files = Array.from(event.target.files); // Get all selected files
    if (files.length > 0 && files.length <= 2) {
        const promises = files.map(async (file) => {
            const formData = new FormData();
            formData.append('photo', file);
            formData.append('vehicle_id', props.vehicleId);

            try {
                const response = await axios.post('/vehicle-type/upload-installation-photo', formData);
                // photoList.value.push(response.data); // Update the photo list
                window.location.reload();
            } catch (error) {
                console.error('Failed to upload photo:', error);
                toast.error('Failed to upload some photos. Please try again.');
            }
        });

        try {
            await Promise.all(promises); // Wait for all uploads to complete
            toast.success('All photos uploaded successfully!');
        } catch {
            toast.error('Some photos failed to upload.');
        }
    } else {

      toast.error('Maximum of two photos uploaded!');
    }
};

// Handle delete button click
const handleDeleteClick = async (photoId) => {
    if (confirm('Are you sure you want to delete this photo?')) {
        try {
         
            await axios.delete(`/vehicle-type/delete-installation-photo/${photoId}`);
            photoList.value = photoList.value.filter((photo) => photo.id !== photoId);
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
              <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title m-0">Installation Photo List</h5>
                <button class="btn btn-primary" @click="openFileInput">Add Photos</button>
                <input type="file" ref="fileInput" style="display: none" @change="handleFileChange" multiple />
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
  