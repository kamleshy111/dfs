<script setup>
import Checkbox from "@/Components/Checkbox.vue";
import GuestLayout from "@/Layouts/GuestLayout.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";

defineProps({
  canResetPassword: {
    type: Boolean,
  },
  status: {
    type: String,
  },
});

const form = useForm({
  email: "",
  password: "",
  remember: false,
});

const submit = () => {
  form.post(route("login"), {
    onFinish: () => form.reset("password"),
  });
};
</script>

<template>
  <GuestLayout>
    <Head title="Log in" />

    <div v-if="status" class="mb-4 text-sm font-medium text-green-600">
      {{ status }}
    </div>
    <div class="row w-100 align-items-stretch">
<div class="col-md-7 d-flex justify-content-center align-items-center p-0">
    <div class="login-box w-100">
      <div class="welcome-text text-left mb-4">
        <h4>Hello</h4>
        <h2>Welcome Back!</h2>
        <p>Enter Your Email And Choose Password To Setup Your Account</p>
      </div>
      <form @submit.prevent="submit">
        <div class="relative">

          <TextInput
            id="email"
            type="email"
            class="mt-1 block w-full form-control"
            v-model="form.email"
            required
            autofocus
            autocomplete="username"
            placeholder=""
          />

          <InputLabel for="email" value="Email" class="form-label" />

          <InputError class="mt-2" :message="form.errors.email" />
        </div>

        <div class="mt-4 relative">

          <TextInput
            id="password"
            type="password"
            class="mt-1 block w-full form-control"
            v-model="form.password"
            required
            autocomplete="current-password"
            placeholder=""
          />

          <InputLabel for="password" value="Password" class="form-label" />

          <InputError class="mt-2" :message="form.errors.password" />
        </div>

        <div class="mt-4 block">
          <label class="flex items-center justify-content-between">
            <div>
            <Checkbox name="remember" v-model:checked="form.remember" />
            <span class="ms-2 text-sm text-dark">Remember me</span>
            </div>
            <div>
                <Link
            v-if="canResetPassword"
            :href="route('password.request')"
            class="rounded-md text-sm text-gray-600  hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
          >
            Forgot your password?
          </Link>
            </div>
          </label>
        </div>

        <div class="mt-6 flex items-center justify-end">

          <PrimaryButton
            class="ms-4 login-btn btn-block py-3 justify-content-center h-[56px]"
            :class="{ 'opacity-25': form.processing }"
            :disabled="form.processing"
          >
            Login Your Account
          </PrimaryButton>
        </div>
      </form>
    </div>
</div>
<div class="col-md-5 second-box-content d-flex flex-column justify-content-center align-items-center p-0">
            <div class="info-box w-100 h-100">
                <div class="swiper mySwiper swiper-initialized swiper-horizontal swiper-backface-hidden">
                    <div class="swiper-wrapper" id="swiper-wrapper-9f45f63a1dbc1c09" aria-live="off" style="transition-duration: 0ms; transform: translate3d(-666px, 0px, 0px); transition-delay: 0ms;">
                        
                        
                        
                    <div class="swiper-slide swiper-slide-next" role="group" aria-label="1 / 3" data-swiper-slide-index="0" style="width: 333px;">
                            <div class="slider-content text-center">
                                <i class="bi bi-car-front display-4 bg-white rounded-circle text-primary"></i>
                                <h4 class="h5 mt-6">Driver Fatigue</h4>
                                <p class="custom-muted" style="margin-top: 200px; font-size: 16px;">Lorem Ipsum Is Simply Dummy Text Of The Printing And Typesetting Industry.</p>
                            </div>
                        </div><div class="swiper-slide swiper-slide-prev" role="group" aria-label="2 / 3" data-swiper-slide-index="1" style="width: 333px;">
                            <div class="slider-content text-center">
                                <i class="bi bi-car-front display-4 bg-white rounded-circle text-primary"></i>
                                <h4 class="h5 mt-6">Driver Fatigue</h4>
                                <p class="custom-muted" style="margin-top: 200px; font-size: 16px;">Lorem Ipsum Has Been The Industry’s Standard Dummy Text.</p>
                            </div>
                        </div><div class="swiper-slide swiper-slide-active" role="group" aria-label="3 / 3" data-swiper-slide-index="2" style="width: 333px;">
                            <div class="slider-content text-center">
                                <i class="bi bi-car-front display-4 bg-white rounded-circle text-primary"></i>
                                <h4 class="h5 mt-6">Driver Fatigue</h4>
                                <p class="custom-muted" style="margin-top: 230px; font-size: 16px;">Lorem Ipsum Is Simply Dummy Text Of The Printing Industry.</p>
                            </div>
                        </div></div>
                    <div class="swiper-pagination swiper-pagination-clickable swiper-pagination-bullets swiper-pagination-horizontal"><span class="swiper-pagination-bullet" tabindex="0" role="button" aria-label="Go to slide 1"></span><span class="swiper-pagination-bullet" tabindex="0" role="button" aria-label="Go to slide 2"></span><span class="swiper-pagination-bullet swiper-pagination-bullet-active" tabindex="0" role="button" aria-label="Go to slide 3" aria-current="true"></span></div>
                <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span></div>
            </div>
        </div>
</div>
  </GuestLayout>
</template>



<script>
        var swiper = new Swiper(".mySwiper", {
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
            },
            autoplay: {
                delay: 5000,
            },
            loop: true,
        });
</script>