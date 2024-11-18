<template>
    <div class="info-box w-100 h-100">
        <div class="swiper mySwiper swiper-initialized swiper-horizontal swiper-backface-hidden">
            <div class="swiper-container swiper-wrapper" >
                <Swiper
                    :modules="[Navigation, Pagination, Autoplay]"
                    :navigation="false"
                    :pagination="{ clickable: true }"
                    :autoplay="{ delay: 3000 }"
                    :loop="true"
                    class="mySwiper"
                >
                    <SwiperSlide v-for="(slide, index) in slides" :key="index">
                        <div class="slider-content text-center">
                            <i class="bi bi-car-front display-4 bg-white rounded-circle text-primary"></i>
                            <h4 class="h5 mt-6">{{ slide.title }}</h4>
                            <p class="custom-muted" style="margin-top: 30px; font-size: 16px;">
                                {{ slide.description }}
                            </p>
                        </div>
                    </SwiperSlide>
                </Swiper>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Swiper, SwiperSlide } from 'swiper/vue';
import { Navigation, Pagination, Autoplay } from 'swiper/modules';

const slides = [
    { title: 'Driver Fatigue 1', description: 'Lorem Ipsum is simply dummy text of the printing industry.' },
    { title: 'Driver Fatigue 2', description: 'Lorem Ipsum has been the industry’s standard dummy text.' },
    { title: 'Driver Fatigue 3', description: 'It is a long established fact that a reader will be distracted.' },
];
</script>

<style scoped>

</style>
