<template>

    <Head title="User Dashboard" />

    <AuthenticatedLayout header="Client Dashboard">
        <div class="container my-3 map-container">
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

const locations = ref([]);

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
</script>
