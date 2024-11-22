<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";

const form = useForm({
  companyName: "",
  model: "",
  fuelType: "",
  chassisNumber: "",
  color: "",
});

const submit = () => {
  form.post(route("devices.store"), {
    onSuccess: () => {
      form.reset();
    },
  });
};
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
              <input type="text" v-model="form.companyName" class="form-control" id="companyName" placeholder="" />
              <label for="companyName" class="form-label">Company Name</label>
            </div>
            <div class="form-group col-md-6">
              <input type="text" v-model="form.model" class="form-control" id="model" placeholder="" />
              <label for="model" class="form-label">model</label>
            </div>
          </div>
          <div class="form-row">
            <div class="form-group col-md-6">
              <input type="text" v-model="form.fuelType" class="form-control" id="fuelType" placeholder="" />
              <label for="fuelType" class="form-label">Fuel Type</label>
            </div>
            <div class="form-group col-md-6">
              <input type="text" v-model="form.chassisNumber" class="form-control" id="chassisNumber" placeholder="" />
              <label for="chassisNumber" class="form-label">Chassis Number</label>
            </div>
            <div class="form-group col-md-6">
              <input type="text" v-model="form.color" class="form-control" id="color" placeholder="" />
              <label for="color" class="form-label">Color</label>
            </div>
          </div>
          <div class="col-md-3 col-12 p-0 mt-4">
            <PrimaryButton class="btn save-btn-custom" style="font-size: 20px !important" >
              Save vehicle
            </PrimaryButton>
          </div>
        </form>
      </div>
    </div>
  </AuthenticatedLayout>
</template>