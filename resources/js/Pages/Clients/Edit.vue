<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, useForm, usePage } from "@inertiajs/vue3";
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import Multiselect from "vue-multiselect";
import { ref } from "vue";

// Access props
const { devices, customerDetail } = usePage().props;

// Initialize form with default values
const form = useForm({
    first_name: customerDetail.first_name || '',
    last_name: customerDetail.last_name || '',
    email: customerDetail.email || '',
    phone: customerDetail.phone || '',
    address: customerDetail.address || '',
    device_id: customerDetail.devices?.map(d => d.id) || [],
    quantity: customerDetail.quantity || 1,
    file: customerDetail.file ||  null,
});

// File handling
const file = ref(null);
const fileName = ref('');

// Handle file upload
const handleFileUpload = (event) => {
    const uploadedFile = event.target.files[0];
    if (uploadedFile) {
        file.value = uploadedFile;
        form.file = uploadedFile;
        fileName.value = uploadedFile.name;
    }
};

// Clear file input
const clearFile = () => {
    file.value = null;
    form.file = null;
    fileName.value = '';
};

// Quantity increment/decrement
const incrementQuantity = () => form.quantity++;
const decrementQuantity = () => { if (form.quantity > 1) form.quantity--; };

// Submit form
const submit = () => {
    form.post(route('clients.update', customerDetail.id), {
        onSuccess: () => clearFile(),
    });
};

console.log("Preselected device IDs:", form.device_id); // Should output [1, 4]
console.log("Devices:", devices);
</script>

<template>
  <Head title="Add Customer" />

  <AuthenticatedLayout>
    <!-- Form -->
    <form @submit.prevent="submit">
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="first_name">First Name</label>
                <TextInput
                    id="first_name"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.first_name"
                    required
                    autofocus
                />
                <InputError :message="form.errors.first_name" />
            </div>
            <div class="form-group col-md-6">
                <label for="last_name">Last Name</label>
                <TextInput
                    id="last_name"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.last_name"
                    required
                />
                <InputError :message="form.errors.last_name" />
            </div>
        </div>

        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="email">Email</label>
                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="form.email"
                    required
                />
                <InputError :message="form.errors.email" />
            </div>
            <div class="form-group col-md-6">
                <label for="phone">Phone</label>
                <TextInput
                    id="phone"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.phone"
                    required
                />
                <InputError :message="form.errors.phone" />
            </div>
        </div>

        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="device_id">Device</label>
                <Multiselect
                    id="id"
                    v-model="form.device_id"
                    :options="devices"
                    :multiple="true"
                    :searchable="false"
                    track-by="id"
                    label="device_id" 
                    placeholder="Select devices"
                    class="form-control"
                />

            </div>
            <div class="form-group col-md-6">
                <label for="quantity">Quantity</label>
                <div class="input-group">
                    <button
                        class="btn btn-outline-secondary"
                        type="button"
                        @click="decrementQuantity"
                    >-</button>
                    <input
                        type="text"
                        v-model="form.quantity"
                        class="form-control text-center"
                        readonly
                    />
                    <button
                        class="btn btn-outline-secondary"
                        type="button"
                        @click="incrementQuantity"
                    >+</button>
                </div>
                <InputError :message="form.errors.quantity" />
            </div>
        </div>

        <div class="form-group">
            <label for="address">Address</label>
            <TextInput
                id="address"
                type="text"
                class="mt-1 block w-full"
                v-model="form.address"
                required
            />
            <InputError :message="form.errors.address" />
        </div>

        <div class="form-group">
            <label for="file">Upload File</label>
            <div
                class="upload-box text-center"
                @drop.prevent="handleFileUpload"
                @dragover.prevent="handleDragOver"
            >
                <i class="bi bi-cloud-arrow-up-fill"></i>
                <span>
                    Drop your file here, or 
                    <label for="file-upload" class="text-primary" style="cursor: pointer;">
                        browse
                    </label> .
                </span>
                <p>Supported formats: Excel, Word, PDF</p>
                <input
                    type="file"
                    id="file-upload"
                    class="d-none"
                    @change="handleFileUpload"
                />
                <p v-if="fileName">
                    {{ fileName }}
                    <button class="btn btn-link text-danger" @click="clearFile">Clear</button>
                </p>
            </div>
            <InputError :message="form.errors.file" />
        </div>

        <PrimaryButton class="btn btn-primary mt-3">Update Customer</PrimaryButton>
    </form>
  </AuthenticatedLayout>
</template>

<style scoped>
@import "vue-multiselect/dist/vue-multiselect.min.css";

.upload-box {
    border: 2px dashed #ccc;
    border-radius: 10px;
    padding: 20px;
    text-align: center;
    transition: background-color 0.2s ease;
}

.upload-box:hover {
    background-color: #f9f9f9;
}

.upload-box.drag-over {
    background-color: #e9e9e9;
}
</style>
