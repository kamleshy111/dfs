<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import { Inertia } from "@inertiajs/inertia";
import { ref } from "vue";
const loadingButtons = ref({});

</script>

<template>
  <Head title="device" />

  <AuthenticatedLayout>
    <div class="mt-5">
      <div class="table-container">
        <div class="d-flex justify-content-between align-items-center">
          <h4><i class="bi bi-truck-front-fill mr-2"></i>All Report</h4>
          <div class="text-end">
            <div class="mr-2">
            </div>
            <a :href="route('devices.map')">
              <button class="btn btn-primary btn-custom">
                <i class="bi bi-geo-alt-fill mr-2"></i>View on Map
              </button>
            </a>

          </div>
        </div>

        <div class="table-responsive">
            <!-- DataTable Component -->
            
          </div>

      </div>
    </div>
  </AuthenticatedLayout>
</template>