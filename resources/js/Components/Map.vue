<template>
    <GoogleMap
      :api-key="apiKey"
      :mapId="mapId"
      style="width: 100%; height: 80vh"
      :center="center"
      :zoom="zoom"
      @click="selectedInfoWindow = null"
    >
      <!-- Render Markers -->
      <AdvancedMarker
        v-for="(location, index) in locations"
        :key="index"
        :options="{ position: location.position, title: location.title }"
        :pin-options="getMarkerOptions(location)"
        @click="selectedInfoWindow = selectedInfoWindow ? null : location.content"
      />

      <!-- Info Window -->
      <div v-if="selectedInfoWindow" class="info-window">
        <div>
          <div class="item-wrap">
            <div class="item-header">
              <div class="item-wrap-slider">
                <div class="listing-gallery-wrap">
                  <div class="houzez-listing-carousel">
                    <img
                      width="50px"
                      height="50px"
                      :src="selectedInfoWindow.photo"
                      alt="#"
                    />
                  </div>
                </div>
              </div>
            </div>
            <div class="item-body flex-grow-1">
              <div>
                <h2 class="item-title">
                  {{ selectedInfoWindow.device_name }}
                </h2>
                <ul class="list-unstyled item-info">
                  <li>Message: {{ selectedInfoWindow.message }}</li>
                  <li>Status: {{ selectedInfoWindow.message_type }}</li>
                  <li>Last Active: {{ selectedInfoWindow.last_active }}</li>
                  <!-- <a :href="'/monitor/' + selectedInfoWindow.device_id" target="_blank">View Details</a> -->
                  <a :href="`/report?deviceId=${selectedInfoWindow.device_id}`" target="_blank">View Details</a>
                  
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </GoogleMap>
  </template>

  <script setup>
  import { ref, onMounted } from "vue";
  import { GoogleMap, AdvancedMarker } from "vue3-google-map";
  import Pusher from "pusher-js";
  import toastr from "toastr";

  const props = defineProps({
    mapId: { type: String, default: "4504f8b37365c3d0" },
    center: { type: Object, required: false, default: { lat: 23.60094962562435, lng: 79.36667068131638 } },
    zoom: { type: Number, default: 15 },
    locations: { type: Array, required: true },
  });

  const selectedInfoWindow = ref(null);
  const apiKey = import.meta.env.VITE_GOOGLE_MAP_API_KEY;

  const getMarkerOptions = (location) => {
    const iconElement = document.createElement("div");
    iconElement.innerHTML = `<i class="bi bi-truck"></i>`;
    iconElement.style.fontSize = "20px";
    iconElement.style.width = "20px";
    iconElement.style.height = "20px";
    iconElement.style.color = "#FFF";

    let iconType = '';
    if (location && location.content && location.content.message_type == 'danger') {
        iconType = 'c32222';
    } else if (location && location.content && location.content.message_type == 'inactive') {
        iconType = 'a1a19f';
    } else {
        iconType = '385be8';
    }

    const pin = new google.maps.marker.PinElement({
      glyph: iconElement,
      glyphColor: "#ff8300",
      background: "#" + iconType,
      borderColor: "#" + iconType
    });

    return pin;
  };

  const pusherNotification = () => {
      // Ensure Echo is already initialized
      const echo = window.Echo;
      echo.channel('notification') // Replace with the actual channel name
          .listen('TestNotification', (data) => { // Replace with the actual event name
              console.log('Received data:', data);

              // Display Toastr notification with icons and inline content
              if (data && role.value === 'admin') {
                  toastr.info(
                      `<div class="notification-content">
          <i class="fas fa-user"></i> <span>${data.vehicleId}</span>
          <i class="fas fa-user"></i> <span>${data.title}</span>
          <i class="fas fa-book" style="margin-left: 20px;"></i> <span>${data.body}</span>
        </div>`,
                      'New Post Notification',
                      {
                          closeButton: true,
                          progressBar: true,
                          timeOut: 0, // Set timeOut to 0 to make it persist until closed
                          extendedTimeOut: 0, // Ensure the notification stays open
                          positionClass: 'toast-top-right',
                          enableHtml: true,
                      }
                  );
              } else {
                  console.error('Invalid data received:', data);
              }
          });
  };

  onMounted(() => {

  });
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
