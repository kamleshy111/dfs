<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

import GuestLayout from "@/Layouts/GuestLayout.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";

const form = useForm({
  name: "",
  email: "",
  password: "",
  password_confirmation: "",
});
</script>
<template>
  <Head title="home" />

  <AuthenticatedLayout>
    <div class="back-section">
      <button type="button" class="back-btn-custom">
        <i class="bi bi-caret-left"></i> Back
      </button>
    </div>

    <div class="my-3">
      <div class="form-container shadow from-main-design">
        <form @submit.prevent="submit">
          <div class="form-row">
            <div class="form-group col-md-6">
              <input
                type="text"
                v-model="form.deviceId"
                class="form-control"
                id="deviceId"
                placeholder=""
              />
              <label for="deviceId" class="form-label">Device ID</label>
            </div>
            <div class="form-group col-md-6">
              <input
                type="text"
                v-model="form.companyName"
                class="form-control"
                id="companyName"
                placeholder=""
              />
              <label for="companyName" class="form-label">Company Name</label>
            </div>
          </div>
          <div class="form-row">
            <div class="form-group col-md-6">
              <input
                type="text"
                v-model="form.orderId"
                class="form-control"
                id="orderId"
                placeholder=""
              />
              <label for="orderId" class="form-label">Order ID</label>
            </div>
            <div class="form-group col-md-6">
              <input
                type="text"
                v-model="form.purchaseDate"
                class="form-control"
                id="purchaseDate"
                placeholder=""
              />
              <label for="purchaseDate" class="form-label">Purchase Date</label>
            </div>
          </div>
          <div class="col-md-3 col-12 p-0 mt-4">
            <PrimaryButton
              class="btn save-btn-custom"
              style="font-size: 20px !important"
            >
              Save Customer
            </PrimaryButton>
          </div>
        </form>
      </div>
    </div>
  </AuthenticatedLayout>
</template>