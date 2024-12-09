<template>

    <Head title="User Dashboard" />

    <AuthenticatedLayout>

        <div class="container my-3 map-container">
            <div id="map">
                <Map :center="center" :locations="locations" :zoom="5" />
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
import Map from '@/Components/Map.vue';
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const center = ref({ lat: 23.60094962562435, lng: 79.36667068131638 });
const locations = ref([]);

onMounted(() => {
    loadMapData();
});

const loadMapData = () => {
    axios
    .get(import.meta.env.VITE_AJAX_URL + 'get-devices')
    .then((response) => {
        if (response.status === 200) {
            locations.value = response.data.locations;
        }
    })
    .catch((error) => {
        console.error('Error fetching locations:', error);
    });
}
</script>
