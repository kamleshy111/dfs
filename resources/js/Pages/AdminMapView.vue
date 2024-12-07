<template>

    <Head title="User Dashboard" />

    <AuthenticatedLayout>

        <div class="container my-3 map-container">
            <div id="map">
                <GoogleMap :api-key="apiKey" mapId="4504f8b37365c3d0" style="width: 100%; height: 80vh"
                    :center="center" :zoom="5" @click="selectedInfoWindow = null">
                    <!-- Loop through markers and render AdvancedMarker components -->
                    <AdvancedMarker v-for="(location, index) in locations" :key="index"
                        :options="{ position: location.position, title: location.title }"
                        :pin-options="getMarkerOptions(location)"
                        @click="selectedInfoWindow = selectedInfoWindow ? null : location.content" />

                    <!-- Info Window -->
                    <div v-if="selectedInfoWindow" class="info-window">
                        <div>
                            <div class="item-wrap">
                                <div class="item-header">
                                    <div class="item-wrap-slider">
                                        <div class="listing-gallery-wrap">
                                            <div class="houzez-listing-carousel">
                                                <img width="50px" height="50px" :src="selectedInfoWindow.photo"
                                                    alt="#" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="item-body flex-grow-1">
                                    <div>
                                        <h2 class="item-title">
                                            {{selectedInfoWindow.device_name }}
                                        </h2>
                                        <ul class="list-unstyled item-info">
                                            <li>Message: {{ selectedInfoWindow.message }}</li>
                                            <li>Status: {{ selectedInfoWindow.message_type }}</li>
                                            <li>Last Active: {{ selectedInfoWindow.last_active }}</li>
                                            <a :href="'/monitor/' + selectedInfoWindow.device_id"
                                                target="_blank">View Details</a>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </GoogleMap>
            </div>
            <!-- <div class="footer-map">
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
            </div> -->
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import axios from 'axios';
import { ref, onMounted } from 'vue';
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { GoogleMap, AdvancedMarker, InfoWindow } from 'vue3-google-map';

var locations = ref([]);
let selectedInfoWindow = ref(null);
const center = { lat: 23.60094962562435, lng: 79.36667068131638 };

const apiKey = import.meta.env.VITE_GOOGLE_MAP_API_KEY;

onMounted(() => {
    axios.get(import.meta.env.VITE_AJAX_URL + 'get-devices')
        .then(response => {
            if (response.status == 200) {
                setTimeout(() => locations = response.data.locations, 500);
            }
        })
        .catch(error => {
            console.error("There was an error fetching the user devices:", error);
        });
});

const getMarkerOptions = (location) => {
    const iconElement = document.createElement("div");
    iconElement.innerHTML = `<i class="bi bi-truck"></i>`;
    iconElement.style.fontSize = "20px";
    iconElement.style.width = "20px";
    iconElement.style.height = "20px";
    iconElement.style.color = "#FFF";

    const pin = new google.maps.marker.PinElement({
        glyph: iconElement,
        glyphColor: "#ff8300",
        background: "#2239c3cc",
        borderColor: "#2239c3cc",
    });

    return pin;
};
</script>
<style scoped>
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
.map-container {
    max-width: 100%;
    max-height: 100%;
}
</style>
