<template>

    <Head title="User Dashboard" />

    <AuthenticatedLayout>

        <div class="container my-3">
            <div id="map">
                <GoogleMap :api-key="apiKey" mapId="4504f8b37365c3d0" style="width: 100%; height: 500px"
                    :center="center" :zoom="15" @click="selectedInfoWindow = null">
                    <!-- Loop through markers and render AdvancedMarker components -->
                    <AdvancedMarker v-for="(location, index) in locations" :key="index"
                        :options="{ position: location.position, title: location.title }"
                        @click="selectedInfoWindow = selectedInfoWindow ? null : location.content" />

                    <!-- Info Window -->
                    <div v-if="selectedInfoWindow" class="info-window">
                        <div>
                            <div class="item-wrap">
                                <div class="item-header">
                                    <div class="item-wrap-slider">
                                        <div class="listing-gallery-wrap">
                                            <div class="houzez-listing-carousel">
                                                <img width="50px" height="50px" :src="selectedInfoWindow.photo" alt="#" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="item-body flex-grow-1">
                                    <div>
                                        <h2 class="item-title">
                                            <a target="_blank"
                                                :href="selectedInfoWindow.url">{{ selectedInfoWindow.device_name }}</a>
                                        </h2>
                                        <ul class="list-unstyled item-info">
                                            <li>Message: {{ selectedInfoWindow.message }}</li>
                                            <li>Status: {{ selectedInfoWindow.message_type }}</li>
                                            <li>Last Active: {{ selectedInfoWindow.last_active }}</li>
                                            <a :href="'/dashboard/monitor/' + selectedInfoWindow.device_id">View Details</a>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </GoogleMap>
            </div>
            <div class="footer-map">
                <nav class="nav justify-content-center">
                    <a class="nav-link active" href="#">GPS</a>
                    <a class="nav-link" href="#">Safe Alarm</a>
                    <a class="nav-link" href="#">Overspeed</a>
                    <a class="nav-link" href="#">Fatigue</a>
                    <a class="nav-link" href="#">Displacement</a>
                    <a class="nav-link" href="#">Map Fence</a>
                    <a class="nav-link" href="#">Other Alarm</a>
                    <a class="nav-link" href="#">Driver</a>
                    <a class="nav-link" href="#">Media Files</a>
                    <a class="nav-link" href="#">System</a>
                </nav>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import axios from 'axios';
import { ref, onMounted } from 'vue';
import { Head } from '@inertiajs/vue3';
import { GoogleMap, AdvancedMarker, InfoWindow } from 'vue3-google-map';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const apiKey = import.meta.env.VITE_GOOGLE_MAP_API_KEY;

var locations = ref([]);
let selectedInfoWindow = ref(null);
const center = { lat: 40.689247, lng: -74.044502 }

onMounted(() => {
    axios.get(import.meta.env.VITE_AJAX_URL + 'get-devices')
        .then(response => {
            if (response.status == 200) {
                locations = response.data.locations;
                console.log(locations)
            }
        })
        .catch(error => {
            console.error("There was an error fetching the user devices:", error);
        });
});
</script>
<!--
message
message type normal denger
lat long -> multip
time ->
photos
 -->
<style>
/* Simple styling for the custom Info Window */
.info-window {
    top: 50%;
    left: 50%;
    position: absolute;
    background: white;
    padding: 10px;
    border: 1px solid black;
    border-radius: 5px;
    z-index: 1000;
}
</style>
