<template>

    <Head title="device" />

    <AuthenticatedLayout>
        <div class="mt-5">
            <div class="table-container">
                <div class="d-flex justify-content-between align-items-center">
                    <h4><i class="bi bi-bell-fill mr-2"></i>Notifications</h4>
                </div>

                <table class="table table-hover table-bordered mt-3">
                    <thead class="thead-light">
                        <tr>
                            <th scope="col">S No</th>
                            <th scope="col">Device ID</th>
                            <th scope="col">Coordinates</th>
                            <th scope="col">Location</th>
                            <th scope="col">Status</th>
                            <th scope="col">Captures</th>
                            <th scope="col">Address</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="device in devices" :key="device.id">
                            <td>{{'1'}}</td>
                            <td>{{device.id}}</td>
                            <td>{{device.coordinates}}</td>
                            <td>{{device.location}}</td>
                            <td>{{device.status}}</td>
                            <td><img width="50px" height="50px" :src="device.capture" @click="openImage(device.capture)" /></td>
                            <td>{{device.address}}</td>
                        </tr>
                        <tr v-if="devices.length < 0">
                            <td colspan="7" class="text-center">All Good!</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { Head } from "@inertiajs/vue3";
// import 'vue3-toastify/dist/index.css';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

var devices = ref('');

onMounted(() => {
    axios.get(import.meta.env.VITE_AJAX_URL + 'get-device-notifications')
        .then(response => {
            if (response.status == 200) {
                devices.value = response.data.device_notifications;
            }
        })
        .catch(error => {
            console.error("There was an error fetching the user devices:", error);
        });
});

const openImage = (url) => window.open(url, '_blank');

</script>
