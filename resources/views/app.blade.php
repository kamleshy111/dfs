<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title inertia>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
        
        <!-- Scripts -->
        @routes
        @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
        @inertiaHead
        <style>

@import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');

body{
    font-family: "Poppins", sans-serif !important;
}
:root {
    --light-color: #2239c3cc;
  }

body {
    background-color: #f5f5f5;
}

.header {
    width: 80%;
    margin: 0 0 0 auto;
    padding-right: 20px;
}
.right-box{
    width: 81%;
    margin: 0 0 0 auto;
}

.sidebar {
    background-color: #fff;
    padding: 20px 10px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    min-height: 100vh;
}
.siderbar {
    position: absolute;
    left: 0;
    top: 0;
}

.logo {
    background: var(--light-color);
    color: #fff;
    padding: 5px 8px;
    border-radius: 50%;
    margin-right: 10px;
}

.nav {
    margin-top: 25px;
}

.nav .nav-link {
    color: #000000e3;
    font-size: 15px;
}

.form-control::placeholder {
    color: #000000de;
}

.form-control {
    border-radius: 5px;
    color: #000000de;
}

.upload-box {
    background-color: #f8f9fa;
    border: 2px dashed #2239c3cc;
    color: #666;
    padding: 70px 30px;
    border-radius: 8px;
    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: column;
}

.upload-box span {
    font-weight: bold;
}

.upload-box p {
    margin: 0;
    color: #000000a8;
}

.search-icon-header {
    position: absolute;
    top: 9px;
    right: 10px;
}

.icon-profile {
    display: flex;
    align-items: center;
}

.icon-profile .bi {
    border: 1px solid #ced4da;
    padding: 0px 10px;
    border-radius: 4px;
    background: #fff;
}

header h2 {
    font-size: 24px;
}

.upload-box .bi {
    color: #2239c3cc;
    font-size: 40px;
}

.upload-box span {
    font-size: 22px;
    color: #000000bf;
    font-weight: 500;
}
form {
    background: #ffff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.table-container {
    padding: 20px;
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.table .thead-light th {
    color: #fff;
    background: transparent;
    border-color: #ababab;
    font-size: 15px;
}

thead.thead-light {
    background: var(--light-color);
}

button.btn.btn-light.action-btn {
    background: var(--light-color);
    color: #fff;
}

.btn-custom {
    background: var(--light-color);
    border-color: var(--light-color);
    font-size: 15px;
}
.table tr td {
    font-size: 14px;
}
a.page-link {
    font-size: 14px;
}

.pagination {
    justify-content: end;
}

.pagination .page-link {
    color: var(--light-color);
}

.page-item.active .page-link {
    color: #fff;
    background-color: var(--light-color);
    border-color: var(--light-color);
}

.card-summary i {
    font-size: 2rem;
    color: #6c757d;
    margin-bottom: 10px;
}

.chart-container {
    background-color: #f8f9fa;
    border-radius: 10px;
    padding: 20px;
    margin: 10px 0;
}

.chart-container h6 {
    font-weight: bold;
}

.chart {
    height: 200px;
    width: 100%;
    background-color: #e9ecef;
    border-radius: 5px;
}

.progress-circle {
    font-size: 1.5rem;
    font-weight: bold;
}

.card-summary {
    background-color: #f8f9fa;
    border-radius: 10px;
    padding: 20px;
    margin: 10px 0;
    display: flex;
    justify-content: space-around;
    align-items: center;
}

.card-summary {
    background-color: #f8f9fa;
    border-radius: 10px;
    padding: 20px;
    margin: 10px 0;
    display: flex;
    justify-content: space-around;
    align-items: center;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.card-summary .bi {
    background: var(--light-color);
    color: #fff;
    padding: 8px 17px;
    border-radius: 8px;
}

.dashboard-card {
    border-radius: 8px;
    padding: 20px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.box-chart-main {
    padding: 33px 17px;
}


.login-container {
            display: flex;
            min-height: 100vh;
        }
        .login-box {
            background: white;
            padding: 40px;
            width: 100%;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .welcome-text {
            color: #333;
        }
       .welcome-text h2 {
    font-weight: bold;
    color: var(--light-color);
    font-size: 40px;
}
.welcome-text p {
    font-size: 18px;
}
        .login-btn {
    background-color: var(--light-color) !important;
    color: white;
    font-size: 20px !important;
    font-weight: 700 !important;
}
.h5 {
    font-size: 25px;
}
        .login-btn:hover{
            color: #fff;
        }
        .forgot{
            color: var(--light-color);
        }
        .info-box {
            background-color: var(--light-color);
            color: white;
            padding: 40px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        .info-box h2 {
            margin-top: 20px;
            font-size: 24px;
            font-weight: bold;
        }
        .info-text {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            opacity: 0.8;
        }
        .form-check-label {
            color: #333;
        }

             .swiper {
      width: 100%;
      height: 100%;
    }

    .swiper-slide {
      text-align: center;
      font-size: 18px;
      background: transparent;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .swiper-slide img {
      display: block;
      width: 100%;
      height: 100%;
      object-fit: cover;
    }
    .swiper-pagination-bullet {
    background: #fff;
}
.slider-content {
    padding: 35px 0px;
}
.login-container .row {
    height: 100%;
}

.login-box,
.second-box-content {
    height: 100%;
}

.info-box {
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
}
.display-4 {
    font-size: 35px !important;
    padding: 17px 20px;
}

.text-primary {
    color: var(--light-color) !important;
}
.login-container {
    display: flex;
    min-height: 100vh;
    width: 72%;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}

.table-container h4 {
    font-size: 16px;
}

.icon-box h4 {
    font-size: 30px;
    font-weight: 700;
}
.icon-box p {
    font-size: 15px;
}
.dashboard-card h5 {
    font-size: 16px;
}
.welcome-text h4 {
    font-size: 33px;
}
    .section-header, .payment-method, .invoice-table {
    background-color: #fff;
    border-radius: 0px 0px 5px 5px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    padding: 20px;
}

    .section-header h4,
    .payment-method h5 {
        color: #333;
    }

    .payment-method i {
        color: #ff5a00;
    }

    .badge {
        font-size: 0.85rem;
        padding: 0.4em 0.7em;
        border-radius: 12px;
    }

    .badge.bg-success {
    background-color: #00aa5c75 !important;
    color: #2e9723;
    font-weight: 500;
}

    .badge.bg-warning {
        background-color: #fff3cd;
        color: #856404;
    }

    .table-bordered th,
    .table-bordered td {
        border-color: #dee2e6;
    }

    .fa-ellipsis-h {
        cursor: pointer;
        color: #6c757d;
    }
.new-tg-1 p {
    font-size: 8px;
    color: #282828;
    font-weight: 500;
}
.new-tg-1 strong {
    font-size: 14px;
}
li.nav-item {
    margin-bottom: 10px;
}
.nav-link.active {
    background: var(--light-color);
    color: #fff;
}
button#dropdownMenuButton {
    border: none;
    background: transparent;
}
.dropdown {
    margin-left: 10px;
    margin-bottom: 10px;
}
ul#forms-nav {
    background: #e9e9e9;
    border-radius: 4px;
}
.text-muted {
    color: #6c757d!important;
    font-size: 10px;
    font-weight: 400;
}
p.text-muted.expiry {
    font-weight: 500;
    margin: 0;
}
.table-light tr th {
    font-size: 12px;
    font-weight: 500;
}
.progress-bar{
    background-color: var(--light-color);
}
.sidebar-heading {
    font-weight: 600;
    font-size: 20px;
}
.login-box form {
    background: transparent;
    padding: 0;
    box-shadow: none;
    border-radius: 0;
}
.form-label {
    position: absolute;
    left: 15px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 16px;
    color: #888;
    transition: 0.2s ease-in-out;
    pointer-events: none;
    background-color: #f9f9f9;
    padding: 0 5px;
}

.form-control:focus{
    border-color: #313F43;
}
.focus\:border-indigo-500:focus {
    --tw-border-opacity: 1;
    border-color: #313F43 !important;
}
.form-control:focus + .form-label,
.form-control:not(:placeholder-shown) + .form-label {
    top: -2px;
    font-size: 14px;
    color: #313F43;
}
.form-control {
    font-size: 16px;
    height: 60px;
}
.form-label {
    font-size: 16px;
}
.slider-content h4 {
    margin-top: 40px !important;
}
</style>
    </head>
    <body class="font-sans antialiased">
        @inertia
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>