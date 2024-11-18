<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
</script>
<template>
  <Head title="home" />

  <AuthenticatedLayout>
    <div class="container my-3">
      <div class="form-container shadow from-main-design">
        <form>
          <div class="form-row">
            <div class="form-group col-md-6">
              <input
                type="text"
                class="form-control"
                id="deviceId"
                placeholder=""
              />
              <label for="deviceId" class="form-label">Device dffgdgd ID</label>
            </div>
            <div class="form-group col-md-6">
              <input
                type="text"
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
                class="form-control"
                id="orderId"
                placeholder=""
              />
              <label for="orderId" class="form-label">Order ID</label>
            </div>
            <div class="form-group col-md-6">
              <input
                type="text"
                class="form-control"
                id="purchaseDate"
                placeholder=""
              />
              <label for="purchaseDate" class="form-label">Purchase Date</label>
            </div>
          </div>
          <div class="col-md-2 col-12 p-0 mt-4 mb-4">
            <button
              type="submit"
              class="btn btn-custom btn-block text-white"
              style="font-size: 20px !important"
            >
              Save Customer
            </button>
          </div>
        </form>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
