<template>

    <Head title="device" />

    <AuthenticatedLayout>
        <div class="mt-5">
            <div class="table-container">
                <div class="d-flex justify-content-between align-items-center">
                    <h4><i class="bi bi-bell-fill mr-2"></i>Notifications</h4>
                </div>

                <label for="devices">Device: </label>
                <select name="devices" id="devices">
                    <option v-for="d in device_select" :value="d.device_id" :key="d.device_id">{{d.device_id}}</option>
                </select>

                <DataTable class="display" :data="devices">
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
                </DataTable>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { Head } from "@inertiajs/vue3";
// import 'vue3-toastify/dist/index.css';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

import DataTable from 'datatables.net-vue3';
import DataTablesCore from 'datatables.net-dt';
DataTable.use(DataTablesCore);

var devices = ref('');
var device_select = ref([]);
onMounted(() => {
    getDevices();

    axios.get(import.meta.env.VITE_AJAX_URL + 'get-device-notifications')
        .then(response => {
            if (response.status == 200) {
                let data = [];
                let index = 1

                for( let device of response.data.device_notifications ) {
                    data.push([
                        index,
                        device.id,
                        device.coordinates,
                        device.location,
                        device.status,
                        `<img width="50px" height="50px" src="${device.capture}" onclick="window.open('${device.capture}', '_blank')" />`,
                        device.address
                    ]);
                    index++;
                }
                devices.value = data;
            }
        })
        .catch(error => {
            console.error("There was an error fetching the user devices:", error);
        });
});

const getDevices = () => {
    axios.get(import.meta.env.VITE_AJAX_URL + 'get-devices-1')
        .then(response => {
            if (response.status == 200) {
                console.log(response.data.devices);
                device_select.value = response.data.devices;
            }
        })
        .catch(error => {
            console.error("There was an error fetching the user devices:", error);
        });
}
</script>
<style>
@import 'datatables.net-dt';
</style>
