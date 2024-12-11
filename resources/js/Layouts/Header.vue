<script setup>
import { ref, onMounted } from "vue";
import ResponsiveNavLink from "@/Components/ResponsiveNavLink.vue";

const showingNavigationDropdown = ref(false);

// Props
const props = defineProps({
    role: {
        type: String,
        required: true,
    },
    heading: {
        type: String,
        default: "Dashboard", // Default value if no heading is provided
    }
});

// Reactive variable for the current date
const currentDate = ref("");

onMounted(() => {
    const today = new Date();
    const dayNames = [
        "Sunday",
        "Monday",
        "Tuesday",
        "Wednesday",
        "Thursday",
        "Friday",
        "Saturday",
    ];
    const monthNames = [
        "January",
        "February",
        "March",
        "April",
        "May",
        "June",
        "July",
        "August",
        "September",
        "October",
        "November",
        "December",
    ];

    // Format the current date
    const day = dayNames[today.getDay()];
    const date = today.getDate();
    const month = monthNames[today.getMonth()];
    const year = today.getFullYear();

    currentDate.value = `${day}, ${date} ${month} ${year}`;
});
</script>
<template>
    <header class="header-main">
        <div v-if="role === 'admin'" class="d-flex justify-content-between align-items-center py-3 mb-4 border-bottom">
            <div class="w-50">
               <!-- <h2 class="d-flex align-items-center">
                    {{ heading }}<img src="/storage/heand.png" class="ml-2" />
                </h2>-->
                <p class="header-date">Today is {{ currentDate }}</p>
            </div>
            <div class="d-flex align-items-center w-50 justify-content-end">
                <!-- <div class="position-relative mr-4 search-desktop">
          <input
            type="text"
            class="form-control mr-3 search-header"
            placeholder="Search here..."
            style="max-width: 300px"
          />
          <span class="search-icon-header"><i class="bi bi-search"></i></span>
        </div> -->
                <div class="icon-profile">
                    <div class="dropdown">
                        <button class="dropdown-toggle" type="button" id="notificationDropdown"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-bell"></i>
                        </button>
                        <ul class="dropdown-menu notification-dropdown" aria-labelledby="notificationDropdown">
                            <li>
                                <a class="dropdown-item" href="#">New comment on your post</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="#">You have a new follower</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="#">Your subscription is about to expire</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="#">New message received</a>
                            </li>
                        </ul>
                    </div>
                    <div class="dropdown img-header">
                        <img src="https://via.placeholder.com/40" alt="Profile" class="rounded-circle"
                            style="cursor: pointer" data-bs-toggle="dropdown" aria-expanded="true" />
                        <ul class="dropdown-menu mt-2 p-2" data-popper-placement="bottom-end" style="
                position: absolute;
                inset: 0px 0px auto auto;
                margin: 0px;
                transform: translate(0px, 42px);
              ">
                            <li class="nav-link d-flex px-0 justify-content-between align-items-center mb-2">
                                <a class="dropdown-item" :href="route('profile.edit')">Profile</a>
                                <i class="bi bi-person dropdown-icon"></i>
                            </li>
                            <li class="nav-item">
                                <ResponsiveNavLink :href="route('logout')" method="post" as="button"
                                    class="nav-link d-flex m-0 px-0 justify-content-between align-items-center">
                                    Log Out
                                    <i class="bi bi-box-arrow-left dropdown-icon"></i>
                                </ResponsiveNavLink>
                            </li>
                        </ul>
                    </div>
                </div>

                <button class="toggle-btn">☰</button>
            </div>
        </div>
        <div v-else class="d-flex justify-content-between align-items-center py-3 mb-4 border-bottom">
            <div class="w-50">
                <h2 class="d-flex align-items-center">
                    Dashboard<img src="/storage/heand.png" class="ml-2" />
                </h2>
                <p class="header-date">Today is {{ currentDate }}</p>
            </div>
            <div class="d-flex align-items-center w-50 justify-content-end">
                <!-- <div class="position-relative mr-4 search-desktop">
          <input
            type="text"
            class="form-control mr-3 search-header"
            placeholder="Search here..."
            style="max-width: 300px"
          />
          <span class="search-icon-header"><i class="bi bi-search"></i></span>
        </div> -->
                <div class="icon-profile">
                    <div class="dropdown">
                        <button class="dropdown-toggle" type="button" id="notificationDropdown"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-bell"></i>
                        </button>
                        <ul class="dropdown-menu notification-dropdown" aria-labelledby="notificationDropdown">
                            <li>
                                <a class="dropdown-item" href="#">New comment on your post</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="#">You have a new follower</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="#">Your subscription is about to expire</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="#">New message received</a>
                            </li>
                        </ul>
                    </div>
                    <div class="dropdown img-header">
                        <img src="https://via.placeholder.com/40" alt="Profile" class="rounded-circle"
                            style="cursor: pointer" data-bs-toggle="dropdown" aria-expanded="true" />
                        <ul class="dropdown-menu mt-2 p-2" data-popper-placement="bottom-end" style="
                position: absolute;
                inset: 0px 0px auto auto;
                margin: 0px;
                transform: translate(0px, 42px);
              ">
                            <li class="nav-link d-flex px-0 justify-content-between align-items-center mb-2">
                                <a class="dropdown-item" :href="route('profile.edit')">Profile</a>
                                <i class="bi bi-person dropdown-icon"></i>
                            </li>
                            <li class="nav-item">
                                <ResponsiveNavLink :href="route('logout')" method="post" as="button"
                                    class="nav-link d-flex px-0 justify-content-between align-items-center">
                                    Log Out
                                    <i class="bi bi-box-arrow-left dropdown-icon"></i>
                                </ResponsiveNavLink>
                            </li>
                        </ul>
                    </div>
                </div>

                <button class="toggle-btn">☰</button>
            </div>
        </div>
    </header>
</template>

<script>
import { Link as InertiaLink } from "@inertiajs/vue3";

export default {
    components: {
        InertiaLink,
    },
};
</script>
