<script setup>
import { Head, useForm, usePage } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
// import { useRoute } from "vue-router";

import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';

// Access props
const { vehicleDetail } = usePage().props;

// Initialize the form with existing vehicle data
const form = useForm({
    company_name: vehicleDetail.company_name || "",
    model: vehicleDetail.model || "",
    fuel_type: vehicleDetail.fuel_type || "",
    Chassis_number: vehicleDetail.Chassis_number || "",
    color: vehicleDetail.color || "",

});

// go to back
const goBack = () => {
  window.location.href = "/vehicle-type";
};

// Form submit handler
const submit = async () => {
    try {
        // Send the form data to the server
        await form.post(route("vehicle-type.update", vehicleDetail.id), {
            headers: {
            "Content-Type": "application/json",
            },
            onSuccess: () => {
            toast.success("vehicle updated successfully.");
            
            window.location.href = "/vehicle-type";
            },
            onError: (errors) => {
            if (errors) {
                toast.error("An error occurred. Please check your input.");
            }
            },
        });
    } catch (error) {

        toast.error("An unexpected error occurred.");
    }
};
</script>
<template>

    <Head title="Edit Device" />

    <AuthenticatedLayout>
        <div class="back-section">
            <button type="button" class="back-btn-custom" @click="goBack">
                <i class="bi bi-caret-left"></i> Back
            </button>
        </div>

        <div class="my-3">
            <div class="form-container shadow from-main-design">
                <form @submit.prevent="submit">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <input type="text" v-model="form.company_name" class="form-control" id="company_name"
                                placeholder="Enter Company Name" />
                            <label for="company_name" class="form-label">Company Name</label>
                        </div>
                        <div class="form-group col-md-6">
                            <input type="text" v-model="form.model" class="form-control" id="model"
                                placeholder="Enter Model" />
                            <label for="model" class="form-label">Model</label>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <input type="text" v-model="form.fuel_type" class="form-control" id="fuel_type"
                                placeholder="Enter Fuel Type" />
                            <label for="fuel_type" class="form-label">Fuel Type</label>
                        </div>
                        <div class="form-group col-md-6">
                            <input type="text" v-model="form.Chassis_number" class="form-control" id="Chassis_number"
                                placeholder="Enter Chassis Number" />
                            <label for="Chassis_number" class="form-label">Chassis Number</label>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <input type="text" v-model="form.color" class="form-control" id="color"
                                placeholder="Enter Fuel Type" />
                            <label for="color" class="form-label">Color</label>
                        </div>
                    </div>
                    <div class="col-md-3 col-12 p-0 mt-4">
                        <PrimaryButton class="btn save-btn-custom" style="font-size: 20px !important">
                            Update Vehicle
                        </PrimaryButton>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>