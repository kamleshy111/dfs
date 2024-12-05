<script setup>
import { defineProps } from 'vue';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";


const props = defineProps({
    vehicleInstalltionPhotos: Object,
    vehicleId: String,
});

// go to back
const goBack = () => {
  window.location.href = "/vehicle-type";
};
</script>

<template>

  <Head title="Client Profile" />

  <AuthenticatedLayout>

    <div class="back-section"><button type="button" class="back-btn-custom" @click="goBack"><i class="bi bi-caret-left"></i>
        Back</button></div>

        <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center pb-3">
                                <h5 class="card-title mt-3">Installtion Photo List</h5>
                                <!-- Add Photos button -->
                                <div class="row">
                                    <div class="col-md-12">
                                        <button class="btn btn-primary" @click="openFileInput">Add Photo</button>
                                        <input type="file" ref="fileInput" style="display: none" @change="handleFileChange">
                                    </div>
                                </div>
                            </div>
                            <div class="container">
                                <div class="row vehicle-gallery">
                                    <div v-for="(image, index) in vehicleInstalltionPhotos" :key="index" class="col-md-4">
                                        <div class="card mb-4 box-shadow">
                                            <img class="card-img-top" :src="image.photo_path" alt="Image">
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

   .vehicle-gallery img{ height: 300px;}
</style>
