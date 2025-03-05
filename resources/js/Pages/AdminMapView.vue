<template>

    <Head title="User Dashboard" />

    <AuthenticatedLayout header="Client Dashboard">
        <div class="container my-3 map-container">
            <button class="dropdown-toggles-1 main-btn-sec-1" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false" >
          <li class="dropdown-itemse drop-li-down" style="list-style: none;">
            <div class="card-summarys-1 card-icons-1">
              <div class="icon-box">
                <p> Devices ID: {{ devide_id }}</p>
              </div>
            </div>
          </li>
        </button>
            <div class="map-text-list">
                <ul v-if="deviceStatus.deviceStatus && deviceStatus.deviceStatus.length" class="dropdown-menu button-menu-drop-1" aria-labelledby="dropdownMenuButton">
                <li v-for="(device, index) in deviceStatus.deviceStatus" :key="index">
                    
                    <span v-if="device.status === 1" class="text-success">
                        <a :href="`/devices/map/${device.deviceId}`" class="text-success">
                        <strong>Device ID:</strong> <span>{{ device.deviceId }}</span>
                        </a>
                    </span>
                    
                    <span v-else-if="device.status == 2"  class="text-black main-text-wrap-1">
                    <a :href="`/devices/map/${device.deviceId}`" class="text-black">
                        <strong>Device ID:</strong> <span>{{ device.deviceId }}</span>
                        </a>
                    </span>

                    <span v-else class="text-color-change">
                    <a :href="`/devices/map/${device.deviceId}`" class="text-brown">
                        <strong>Device ID:</strong> <span >{{ device.deviceId }}</span> 
                    </a>
                    </span>
                </li>
                </ul>
            </div>
            <div id="map">
                <Map :locations="locations" :zoom="5" />
            </div>
        </div>
    </AuthenticatedLayout>

</template>

<script setup>
import axios from 'axios';
import { ref, onMounted } from 'vue';
import Map from '@/Components/Map.vue';
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';


const devide_id = location.pathname.split("/").pop();
const locations = ref([]);
const deviceStatus = ref({ deviceCount: 0, deviceStatus: [] });

const props = defineProps({
    customer_id: {
        type: String,
        required: false,
    },
    value: {
        default: null,
    },
    device_id: {
        type: String,
        required: false,
    },
});

onMounted(() => {
    loadMapData();
    getDeviceStatus();
});

const loadMapData = () => {
    let query_string = '';
    if(props.customer_id) {
        query_string = '?customer_id=' + props.customer_id;
    }
    if(props.device_id) {
        query_string = '?device_id=' + props.device_id;
    }
    axios
    .get(import.meta.env.VITE_AJAX_URL + 'get-devices' + query_string)
    .then((response) => {
        if (response.status === 200) {
            locations.value = response.data.locations;
        }
    })
    .catch((error) => {
        console.error('Error fetching locations:', error);
    });
}

const getDeviceStatus = () => {
  axios
    .get(import.meta.env.VITE_AJAX_URL + "get-device-status")
    .then((response) => {
      if (response.status == 200) {
        console.log('device status list ',response.data)
        deviceStatus.value = response.data;
      }
    })
    .catch((error) => {
      console.error("There was an error fetching the user devices:", error);
    });
};
</script>
<style>
.text-color-change a {
    color: brown;
}
.main-text-wrap-1 a:hover {
    color: black !important;
}
.icon-box {
    border: 2px solid #a5a5a536;
    padding: 10px 40px;
    font-weight: 500;
    border-radius: 4px;
    background: white;
    margin-left: 0px !important;
    margin-bottom: 20px;
}
.button-menu-drop-1 li {
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    padding: 12px 15px;
    margin-bottom: 10px;
    border-radius: 5px;
    background: white;
}
.map-text-list {
    width: 250px;
    position: absolute;
    z-index: 9999;
    height: 490px;
    
}
.map-text-list::-webkit-scrollbar {
  width: 0px;
}

.map-text-list::-webkit-scrollbar-track {
  background: #f1f1f1;
}

.map-text-list::-webkit-scrollbar-thumb {
  background: #888;
  border-radius: 10px;
}
</style>
