<script setup>
import Checkbox from "@/Components/Checkbox.vue";
import GuestLayout from "@/Layouts/GuestLayout.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import SliderComponent from '@/Components/SliderComponent.vue';
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
    <div class="row align-items-stretch">
        <div class="col-md-7 w-100 main-class-top">
            <div class="login-box w-100">
              <div class="welcome-text text-left mb-4">
                <h2 class="main-text-3">Login</h2>
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
                    class="login-btn btn-block py-3 justify-content-center h-[56px]"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                  >
                    Login Your Account
                  </PrimaryButton>
                </div>
              </form>
            </div>
        </div>
    <!-- <div class="col-md-5 second-box-content d-flex flex-column justify-content-center align-items-center p-0 new-main-slider-cont">
        <SliderComponent />
    </div> -->
</div>
  </GuestLayout>
</template>
