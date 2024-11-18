<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});
</script>

<template>
    <Head title="Profile" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="text-center fs-3"
            >
                Profile
            </h2>
        </template>

        <div class="my-3">
            <div class="row">
                <div
                    class="col-md-6 col-12"
                >
                    <UpdateProfileInformationForm
                        :must-verify-email="mustVerifyEmail"
                        :status="status"
                        class="bg-white profile-design"
                    />
                </div>

                <div
                    class="col-md-6 col-12"
                >
                    <UpdatePasswordForm class="bg-white profile-design" />
                </div>

                <div
                    class="w-50 mt-5 d-none"
                >
                    <DeleteUserForm class="bg-white profile-design" />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
