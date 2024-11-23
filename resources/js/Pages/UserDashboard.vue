

<template>
    <Head title="User Dashboard" />

    <AuthenticatedLayout>
 
    <div class="container my-3">                   
        <div class="map">
            <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d48389.78314118045!2d-74.006138!3d40.710059!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c25a22a3bda30d%3A0xb89d1fe6bc499443!2sDowntown%20Conference%20Center!5e0!3m2!1sen!2sus!4v1676961268712!5m2!1sen!2sus" frameborder="0" style="border:0; width: 100%; height: 100vh;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div> 
        <div class="footer-map">
            <nav class="nav justify-content-center">
                <a class="nav-link" href="#">GPS</a>
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
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';



</script>
