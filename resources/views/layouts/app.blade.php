<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? config('app.name', 'Laravel') }}</title>

    <!-- SEO: Basic -->
    <meta name="author" content="Zoran Shefot Bogoevski">
    <meta name="robots" content="index,follow">
    <meta name="description"
        content="{{ $metaDescription ?? 'Administrative dashboard and API for Galileyo platform.' }}">
    <meta name="keywords" content="{{ $metaKeywords ?? 'galileyo, admin, dashboard, laravel' }}">
    <link rel="canonical" href="{{ url()->current() }}">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="{{ $title ?? config('app.name', 'Laravel') }}">
    <meta property="og:description"
        content="{{ $metaDescription ?? 'Administrative dashboard and API for Galileyo platform.' }}">
    <meta property="og:site_name" content="{{ config('app.name', 'Laravel') }}">
    @if (!empty($metaImage))
        <meta property="og:image" content="{{ $metaImage }}">
    @endif

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $title ?? config('app.name', 'Laravel') }}">
    <meta name="twitter:description"
        content="{{ $metaDescription ?? 'Administrative dashboard and API for Galileyo platform.' }}">
    @if (!empty($metaImage))
        <meta name="twitter:image" content="{{ $metaImage }}">
    @endif

    <!-- Publisher / Author URL -->
    <link rel="author" href="https://zorandev.info">

    <!-- Bootstrap 5.3.3 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Bootstrap 5 Compatibility - Keeps Bootstrap 4 look -->
    <link href="{{ asset('css/bootstrap5-compat.css') }}?v={{ time() }}" rel="stylesheet">
    <!-- Font Awesome 5.15.3 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Main CSS -->
    <link href="{{ asset('css/main.css') }}?v={{ time() }}" rel="stylesheet">
    <link href="{{ asset('css/site.css') }}?v={{ time() }}" rel="stylesheet">
    <!-- BS3 compatibility layer to keep existing design -->
    <link href="{{ asset('css/bs3-compat.css') }}?v={{ time() }}" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <div class="wrapper {{ session('sidebar_mini') ? 'wrapper-sidebar_mini' : '' }}">
        <header class="header">
            <a href="{{ route('site.index') }}" class="logo">
                <span class="logo__mini">L</span>
                <span class="logo__lg">{{ config('app.name', 'Laravel') }}</span>
            </a>

            <div class="pull-left">
                <button type="button" name="button" class="JS__sidebar_toggler_mobile btn btn-primary d-inline d-md-none"><i
                        class="fas fa-bars"></i></button>
            </div>

            <div class="pull-right">
                @if (session('loginFromSuper'))
                    <a href="{{ route('site.reset') }}" class="btn btn-admin">RESET</a>
                @endif

                <!-- Chat Button with Badge - Disabled for today -->
                {{--
                <a href="/admin/chat" class="btn btn-success" title="View Messages" style="position: relative;">
                    <i class="fas fa-comments"></i> Chat
                    <span id="chat-badge" class="badge badge-warning" style="position: absolute; top: -5px; right: -5px; display: none;">0</span>
                </a>
                --}}

                <a href="{{ route('site.self') }}" class="btn btn-info JS__load_in_modal">
                    <i class="fas fa-user-alt"></i> {{ Auth::user()->username ?? 'User' }}
                </a>

                <a href="{{ route('site.logout') }}" class="btn btn-danger">
                    <i class="fas fa-sign-out-alt"></i>
                </a>
            </div>
        </header>

        <aside
            class="sidebar"
            id="adminSidebar"
        >
            <div class="sidebar-header d-md-none" style="padding: 10px 15px; background: #0d4d61; display: flex; justify-content: space-between; align-items: center;">
                <h5 style="margin: 0; color: #fff;">Menu</h5>
                <button type="button" class="btn-close text-reset" style="background: #fff; opacity: 1;" id="closeSidebarBtn"></button>
            </div>
            <div class="offcanvas-body p-0">
            <ul id="w1" class="sidebar-menu" dropdowncaret="<span class=&quot;fa fa-chevron-down&quot;></span>">
                <li class="current-page">
                    <a href="{{ route('contact.index') }}">
                        <svg class="svg-inline--fa fa-info fa-w-6 fa-fw" aria-hidden="true" focusable="false"
                            data-prefix="fas" data-icon="info" role="img" xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 192 512" data-fa-i2svg="">
                            <path fill="currentColor"
                                d="M20 424.229h20V279.771H20c-11.046 0-20-8.954-20-20V212c0-11.046 8.954-20 20-20h112c11.046 0 20 8.954 20 20v212.229h20c11.046 0 20 8.954 20 20V492c0 11.046-8.954 20-20 20H20c-11.046 0-20-8.954-20-20v-47.771c0-11.046 8.954-20 20-20zM96 0C56.235 0 24 32.235 24 72s32.235 72 72 72 72-32.235 72-72S135.764 0 96 0z">
                            </path>
                        </svg>
                        <span>Customers' Requests</span>
                    </a>
                </li>
                <li>
                    <button type="button">
                        <svg class="svg-inline--fa fa-user fa-w-14 fa-fw" aria-hidden="true" focusable="false"
                            data-prefix="fas" data-icon="user" role="img" xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 448 512" data-fa-i2svg="">
                            <path fill="currentColor"
                                d="M224 256c70.7 0 128-57.3 128-128S294.7 0 224 0 96 57.3 96 128s57.3 128 128 128zm89.6 32h-16.7c-22.2 10.2-46.9 16-72.9 16s-50.6-5.8-72.9-16h-16.7C60.2 288 0 348.2 0 422.4V464c0 26.5 21.5 48 48 48h352c26.5 0 48-21.5 48-48v-41.6c0-74.2-60.2-134.4-134.4-134.4z">
                            </path>
                        </svg>
                        <span>Users
                            <svg class="svg-inline--fa fa-chevron-down fa-w-14" aria-hidden="true" focusable="false"
                                data-prefix="fas" data-icon="chevron-down" role="img"
                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" data-fa-i2svg="">
                                <path fill="currentColor"
                                    d="M207.029 381.476L12.686 187.132c-9.373-9.373-9.373-24.569 0-33.941l22.667-22.667c9.357-9.357 24.522-9.375 33.901-.04L224 284.505l154.745-154.021c9.379-9.335 24.544-9.317 33.901.04l22.667 22.667c9.373 9.373 9.373 24.569 0 33.941L240.971 381.476c-9.373 9.372-24.569 9.372-33.942 0z">
                                </path>
                            </svg>
                        </span>
                    </button>
                    <ul class="child_menu">
                        <li><a href="{{ route('user.index') }}">Accounts</a></li>
                        <li>
                            <button type="button">Private Feeds
                                <svg class="svg-inline--fa fa-chevron-down fa-w-14" aria-hidden="true"
                                    focusable="false" data-prefix="fas" data-icon="chevron-down" role="img"
                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" data-fa-i2svg="">
                                    <path fill="currentColor"
                                        d="M207.029 381.476L12.686 187.132c-9.373-9.373-9.373-24.569 0-33.941l22.667-22.667c9.357-9.357 24.522-9.375 33.901-.04L224 284.505l154.745-154.021c9.379-9.335 24.544-9.317 33.901.04l22.667 22.667c9.373 9.373 9.373 24.569 0 33.941L240.971 381.476c-9.373 9.372-24.569 9.372-33.942 0z">
                                    </path>
                                </svg>
                            </button>
                            <ul class="child_menu">
                                <li><a href="{{ route('follower-list.index') }}">Feeds</a></li>
                                <li><a href="{{ route('follower.index') }}">Followers</a></li>
                            </ul>
                        </li>
                        <li>
                            <button type="button">Influencers
                                <svg class="svg-inline--fa fa-chevron-down fa-w-14" aria-hidden="true"
                                    focusable="false" data-prefix="fas" data-icon="chevron-down" role="img"
                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" data-fa-i2svg="">
                                    <path fill="currentColor"
                                        d="M207.029 381.476L12.686 187.132c-9.373-9.373-9.373-24.569 0-33.941l22.667-22.667c9.357-9.357 24.522-9.375 33.901-.04L224 284.505l154.745-154.021c9.379-9.335 24.544-9.317 33.901.04l22.667 22.667c9.373 9.373 9.373 24.569 0 33.941L240.971 381.476c-9.373 9.372-24.569 9.372-33.942 0z">
                                    </path>
                                </svg>
                            </button>
                            <ul class="child_menu">
                                <li><a href="{{ route('influencer-assistant.index') }}">Assistants</a></li>
                                <li><a href="{{ route('user.promocode') }}">Influencers' Discounts</a></li>
                            </ul>
                        </li>
                        <li><a href="{{ route('phone-number.index') }}">Sat Devices</a></li>
                        <li><a href="{{ route('register.index') }}">Subscribers</a></li>
                        <li><a href="{{ route('register.signups') }}">Unfinished Signups</a></li>
                        <li><a href="{{ route('contract-line.unpaid') }}">Unpaid</a></li>
                    </ul>
                </li>
                <li>
                    <button type="button">
                        <svg class="svg-inline--fa fa-money-bill-wave fa-w-20 fa-fw" aria-hidden="true"
                            focusable="false" data-prefix="fas" data-icon="money-bill-wave" role="img"
                            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512" data-fa-i2svg="">
                            <path fill="currentColor"
                                d="M621.16 54.46C582.37 38.19 543.55 32 504.75 32c-123.17-.01-246.33 62.34-369.5 62.34-30.89 0-61.76-3.92-92.65-13.72-3.47-1.1-6.95-1.62-10.35-1.62C15.04 79 0 92.32 0 110.81v317.26c0 12.63 7.23 24.6 18.84 29.46C57.63 473.81 96.45 480 135.25 480c123.17 0 246.34-62.35 369.51-62.35 30.89 0 61.76 3.92 92.65 13.72 3.47 1.1 6.95 1.62 10.35 1.62 17.21 0 32.25-13.32 32.25-31.81V83.93c-.01-12.64-7.24-24.6-18.85-29.47zM48 132.22c20.12 5.04 41.12 7.57 62.72 8.93C104.84 170.54 79 192.69 48 192.69v-60.47zm0 285v-47.78c34.37 0 62.18 27.27 63.71 61.4-22.53-1.81-43.59-6.31-63.71-13.62zM320 352c-44.19 0-80-42.99-80-96 0-53.02 35.82-96 80-96s80 42.98 80 96c0 53.03-35.83 96-80 96zm272 27.78c-17.52-4.39-35.71-6.85-54.32-8.44 5.87-26.08 27.5-45.88 54.32-49.28v57.72zm0-236.11c-30.89-3.91-54.86-29.7-55.81-61.55 19.54 2.17 38.09 6.23 55.81 12.66v48.89z">
                            </path>
                        </svg>
                        <span>Finance
                            <svg class="svg-inline--fa fa-chevron-down fa-w-14" aria-hidden="true" focusable="false"
                                data-prefix="fas" data-icon="chevron-down" role="img"
                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" data-fa-i2svg="">
                                <path fill="currentColor"
                                    d="M207.029 381.476L12.686 187.132c-9.373-9.373-9.373-24.569 0-33.941l22.667-22.667c9.357-9.357 24.522-9.375 33.901-.04L224 284.505l154.745-154.021c9.379-9.335 24.544-9.317 33.901.04l22.667 22.667c9.373 9.373 9.373 24.569 0 33.941L240.971 381.476c-9.373 9.372-24.569 9.372-33.942 0z">
                                </path>
                            </svg>
                        </span>
                    </button>
                    <ul class="child_menu">
                        <li><a href="{{ route('invoice.index') }}">Invoices</a></li>
                        <li><a href="{{ route('money-transaction.index') }}">Transactions</a></li>
                        <li><a href="{{ route('money-transaction.report') }}">Stats</a></li>
                    </ul>
                </li>
                <li>
                    <button type="button">
                        <svg class="svg-inline--fa fa-database fa-w-14 fa-fw" aria-hidden="true" focusable="false"
                            data-prefix="fas" data-icon="database" role="img" xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 448 512" data-fa-i2svg="">
                            <path fill="currentColor"
                                d="M448 73.143v45.714C448 159.143 347.667 192 224 192S0 159.143 0 118.857V73.143C0 32.857 100.333 0 224 0s224 32.857 224 73.143zM448 176v102.857C448 319.143 347.667 352 224 352S0 319.143 0 278.857V176c48.125 33.143 136.208 48.572 224 48.572S399.874 209.143 448 176zm0 160v102.857C448 479.143 347.667 512 224 512S0 479.143 0 438.857V336c48.125 33.143 136.208 48.572 224 48.572S399.874 369.143 448 336z">
                            </path>
                        </svg>
                        <span>Reports
                            <svg class="svg-inline--fa fa-chevron-down fa-w-14" aria-hidden="true" focusable="false"
                                data-prefix="fas" data-icon="chevron-down" role="img"
                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" data-fa-i2svg="">
                                <path fill="currentColor"
                                    d="M207.029 381.476L12.686 187.132c-9.373-9.373-9.373-24.569 0-33.941l22.667-22.667c9.357-9.357 24.522-9.375 33.901-.04L224 284.505l154.745-154.021c9.379-9.335 24.544-9.317 33.901.04l22.667 22.667c9.373 9.373 9.373 24.569 0 33.941L240.971 381.476c-9.373 9.372-24.569 9.372-33.942 0z">
                                </path>
                            </svg>
                        </span>
                    </button>
                    <ul class="child_menu">
                        <li><a href="{{ route('report.influencer-total') }}">Influencer Totals</a></li>
                        <li><a href="{{ route('report.referral') }}">Compensations</a></li>
                        <li><a href="{{ route('report.sps-termination') }}">SPS Termination</a></li>
                        <li><a href="{{ route('report.statistic') }}">Active Users and Plans</a></li>
                        <li><a href="{{ route('report.ended') }}">Cancelled Plans</a></li>
                        <li><a href="{{ route('report.reaction') }}">Reactions</a></li>
                        <li><a href="{{ route('report.sold-devices') }}">Sold Devices</a></li>
                        <li class="nav-admin"><a href="{{ route('report.devices-plans') }}">Devices Plans</a></li>
                        <li class="nav-admin"><a href="{{ route('report.login-statistic') }}">Login Statistics</a>
                        </li>
                        <li class="nav-admin"><a href="{{ route('report.sms') }}">Messages Pool</a></li>
                        <li><a href="{{ route('report.customer-source') }}">Users' registration</a></li>
                        <li><a href="{{ route('emergency-tips-request.index') }}">Emergency Tips Requests</a></li>
                        <li class="nav-admin"><a href="{{ route('report.user-point') }}">User Points</a></li>
                    </ul>
                </li>
                <li>
                    <a href="{{ route('promocode.index') }}">
                        <svg class="svg-inline--fa fa-percent fa-w-14 fa-fw" aria-hidden="true" focusable="false"
                            data-prefix="fas" data-icon="percent" role="img" xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 448 512" data-fa-i2svg="">
                            <path fill="currentColor"
                                d="M112 224c61.9 0 112-50.1 112-112S173.9 0 112 0 0 50.1 0 112s50.1 112 112 112zm0-160c26.5 0 48 21.5 48 48s-21.5 48-48 48-48-21.5-48-48 21.5-48 48-48zm224 224c-61.9 0-112 50.1-112 112s50.1 112 112 112 112-50.1 112-112-50.1-112-112-112zm0 160c-26.5 0-48-21.5-48-48s21.5-48 48-48 48 21.5 48 48-21.5 48-48 48zM392.3.2l31.6-.1c19.4-.1 30.9 21.8 19.7 37.8L77.4 501.6a23.95 23.95 0 0 1-19.6 10.2l-33.4.1c-19.5 0-30.9-21.9-19.7-37.8l368-463.7C377.2 4 384.5.2 392.3.2z">
                            </path>
                        </svg>
                        <span>Promocodes</span>
                    </a>
                </li>
                <li>
                    <button type="button">
                        <svg class="svg-inline--fa fa-money-check fa-w-20 fa-fw" aria-hidden="true" focusable="false"
                            data-prefix="fas" data-icon="money-check" role="img"
                            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512" data-fa-i2svg="">
                            <path fill="currentColor"
                                d="M0 448c0 17.67 14.33 32 32 32h576c17.67 0 32-14.33 32-32V128H0v320zm448-208c0-8.84 7.16-16 16-16h96c8.84 0 16 7.16 16 16v32c0 8.84-7.16 16-16 16h-96c-8.84 0-16-7.16-16-16v-32zm0 120c0-4.42 3.58-8 8-8h112c4.42 0 8 3.58 8 8v16c0 4.42-3.58 8-8 8H456c-4.42 0-8-3.58-8-8v-16zM64 264c0-4.42 3.58-8 8-8h304c4.42 0 8 3.58 8 8v16c0 4.42-3.58 8-8 8H72c-4.42 0-8-3.58-8-8v-16zm0 96c0-4.42 3.58-8 8-8h176c4.42 0 8 3.58 8 8v16c0 4.42-3.58 8-8 8H72c-4.42 0-8-3.58-8-8v-16zM624 32H16C7.16 32 0 39.16 0 48v48h640V48c0-8.84-7.16-16-16-16z">
                            </path>
                        </svg>
                        <span>Products
                            <svg class="svg-inline--fa fa-chevron-down fa-w-14" aria-hidden="true" focusable="false"
                                data-prefix="fas" data-icon="chevron-down" role="img"
                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" data-fa-i2svg="">
                                <path fill="currentColor"
                                    d="M207.029 381.476L12.686 187.132c-9.373-9.373-9.373-24.569 0-33.941l22.667-22.667c9.357-9.357 24.522-9.375 33.901-.04L224 284.505l154.745-154.021c9.379-9.335 24.544-9.317 33.901.04l22.667 22.667c9.373 9.373 9.373 24.569 0 33.941L240.971 381.476c-9.373 9.372-24.569 9.372-33.942 0z">
                                </path>
                            </svg>
                        </span>
                    </button>
                    <ul class="child_menu">
                        <li><a href="{{ route('product.subscription') }}">Subscriptions</a></li>
                        <li><a href="{{ route('product.alert') }}">Alerts</a></li>
                        <li><a href="{{ route('product.device') }}">Devices</a></li>
                        <li><a href="{{ route('product.plan') }}">Devices Plans</a></li>
                        <li><a href="{{ route('bundle.index') }}">Bundles</a></li>
                    </ul>
                </li>
                <li>
                    <button type="button">
                        <svg class="svg-inline--fa fa-cog fa-w-16 fa-fw" aria-hidden="true" focusable="false"
                            data-prefix="fas" data-icon="cog" role="img" xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 512 512" data-fa-i2svg="">
                            <path fill="currentColor"
                                d="M487.4 315.7l-42.6-24.6c4.3-23.2 4.3-47 0-70.2l42.6-24.6c4.9-2.8 7.1-8.6 5.5-14-11.1-35.6-30-67.8-54.7-94.6-3.8-4.1-10-5.1-14.8-2.3L380.8 110c-17.9-15.4-38.5-27.3-60.8-35.1V25.8c0-5.6-3.9-10.5-9.4-11.7-36.7-8.2-74.3-7.8-109.2 0-5.5 1.2-9.4 6.1-9.4 11.7V75c-22.2 7.9-42.8 19.8-60.8 35.1L88.7 85.5c-4.9-2.8-11-1.9-14.8 2.3-24.7 26.7-43.6 58.9-54.7 94.6-1.7 5.4.6 11.2 5.5 14L67.3 221c-4.3 23.2-4.3 47 0 70.2l-42.6 24.6c-4.9 2.8-7.1 8.6-5.5 14 11.1 35.6 30 67.8 54.7 94.6 3.8 4.1 10 5.1 14.8 2.3l42.6-24.6c17.9 15.4 38.5 27.3 60.8 35.1v49.2c0 5.6 3.9 10.5 9.4 11.7 36.7 8.2 74.3 7.8 109.2 0 5.5-1.2 9.4-6.1 9.4-11.7v-49.2c22.2-7.9 42.8-19.8 60.8-35.1l42.6 24.6c4.9 2.8 11 1.9 14.8-2.3 24.7-26.7 43.6-58.9 54.7-94.6 1.5-5.5-.7-11.3-5.6-14.1zM256 336c-44.1 0-80-35.9-80-80s35.9-80 80-80 80 35.9 80 80-35.9 80-80 80z">
                            </path>
                        </svg>
                        <span>Settings
                            <svg class="svg-inline--fa fa-chevron-down fa-w-14" aria-hidden="true" focusable="false"
                                data-prefix="fas" data-icon="chevron-down" role="img"
                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" data-fa-i2svg="">
                                <path fill="currentColor"
                                    d="M207.029 381.476L12.686 187.132c-9.373-9.373-9.373-24.569 0-33.941l22.667-22.667c9.357-9.357 24.522-9.375 33.901-.04L224 284.505l154.745-154.021c9.379-9.335 24.544-9.317 33.901.04l22.667 22.667c9.373 9.373 9.373 24.569 0 33.941L240.971 381.476c-9.373 9.372-24.569 9.372-33.942 0z">
                                </path>
                            </svg>
                        </span>
                    </button>
                    <ul class="child_menu">
                        <li><a href="{{ route('settings.public') }}">Site Settings</a></li>
                        <li><a href="{{ route('email-template.index') }}">Email Templates</a></li>
                    </ul>
                </li>
                <li>
                    <button type="button">
                        <svg class="svg-inline--fa fa-sms fa-w-16 fa-fw" aria-hidden="true" focusable="false"
                            data-prefix="fas" data-icon="sms" role="img" xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 512 512" data-fa-i2svg="">
                            <path fill="currentColor"
                                d="M256 32C114.6 32 0 125.1 0 240c0 49.6 21.4 95 57 130.7C44.5 421.1 2.7 466 2.2 466.5c-2.2 2.3-2.8 5.7-1.5 8.7 1.3 3 4.1 4.8 7.3 4.8 66.3 0 116-31.8 140.6-51.4 32.7 12.3 69 19.4 107.4 19.4 141.4 0 256-93.1 256-208S397.4 32 256 32zM128.2 304H116c-4.4 0-8-3.6-8-8v-16c0-4.4 3.6-8 8-8h12.3c6 0 10.4-3.5 10.4-6.6 0-1.3-.8-2.7-2.1-3.8l-21.9-18.8c-8.5-7.2-13.3-17.5-13.3-28.1 0-21.3 19-38.6 42.4-38.6H156c4.4 0 8 3.6 8 8v16c0 4.4-3.6 8-8 8h-12.3c-6 0-10.4 3.5-10.4 6.6 0 1.3.8 2.7 2.1 3.8l21.9 18.8c8.5 7.2 13.3 17.5 13.3 28.1.1 21.3-19 38.6-42.4 38.6zm191.8-8c0 4.4-3.6 8-8 8h-16c-4.4 0-8-3.6-8-8v-68.2l-24.8 55.8c-2.9 5.9-11.4 5.9-14.3 0L224 227.8V296c0 4.4-3.6 8-8 8h-16c-4.4 0-8-3.6-8-8V192c0-8.8 7.2-16 16-16h16c6.1 0 11.6 3.4 14.3 8.8l17.7 35.4 17.7-35.4c2.7-5.4 8.3-8.8 14.3-8.8h16c8.8 0 16 7.2 16 16v104zm48.3 8H356c-4.4 0-8-3.6-8-8v-16c0-4.4 3.6-8 8-8h12.3c6 0 10.4-3.5 10.4-6.6 0-1.3-.8-2.7-2.1-3.8l-21.9-18.8c-8.5-7.2-13.3-17.5-13.3-28.1 0-21.3 19-38.6 42.4-38.6H396c4.4 0 8 3.6 8 8v16c0 4.4-3.6 8-8 8h-12.3c-6 0-10.4 3.5-10.4 6.6 0 1.3.8 2.7 2.1 3.8l21.9 18.8c8.5 7.2 13.3 17.5 13.3 28.1.1 21.3-18.9 38.6-42.3 38.6z">
                            </path>
                        </svg>
                        <span>Messages
                            <svg class="svg-inline--fa fa-chevron-down fa-w-14" aria-hidden="true" focusable="false"
                                data-prefix="fas" data-icon="chevron-down" role="img"
                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" data-fa-i2svg="">
                                <path fill="currentColor"
                                    d="M207.029 381.476L12.686 187.132c-9.373-9.373-9.373-24.569 0-33.941l22.667-22.667c9.357-9.357 24.522-9.375 33.901-.04L224 284.505l154.745-154.021c9.379-9.335 24.544-9.317 33.901.04l22.667 22.667c9.373 9.373 9.373 24.569 0 33.941L240.971 381.476c-9.373 9.372-24.569 9.372-33.942 0z">
                                </path>
                            </svg>
                        </span>
                    </button>
                    <ul class="child_menu">
                        <li><a href="{{ route('sms-pool.index') }}">Pool</a></li>
                        <li><a href="{{ route('sms-pool.send-dashboard') }}">Send</a></li>
                        <li><a href="{{ route('sms-schedule.index') }}">Schedule</a></li>
                        <li class="nav-admin"><a href="{{ route('twilio.incoming') }}">Incomings</a></li>
                    </ul>
                </li>

                <li>
                    <a href="{{ route('staff.index') }}">
                        <svg class="svg-inline--fa fa-user-tie fa-w-14 fa-fw" aria-hidden="true" focusable="false"
                            data-prefix="fas" data-icon="user-tie" role="img" xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 448 512" data-fa-i2svg="">
                            <path fill="currentColor"
                                d="M224 256c70.7 0 128-57.3 128-128S294.7 0 224 0 96 57.3 96 128s57.3 128 128 128zm95.8 32.6L272 480l-32-136 32-56h-96l32 56-32 136-47.8-191.4C56.9 292 0 350.3 0 422.4V464c0 26.5 21.5 48 48 48h352c26.5 0 48-21.5 48-48v-41.6c0-72.1-56.9-130.4-128.2-133.8z">
                            </path>
                        </svg>
                        <span>Staff</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('podcast.index') }}">
                        <svg class="svg-inline--fa fa-podcast fa-w-14 fa-fw" aria-hidden="true" focusable="false"
                            data-prefix="fas" data-icon="podcast" role="img" xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 448 512" data-fa-i2svg="">
                            <path fill="currentColor"
                                d="M267.429 488.563C262.286 507.573 242.858 512 224 512c-18.857 0-38.286-4.427-43.428-23.437C172.927 460.134 160 388.898 160 355.75c0-35.156 31.142-43.75 64-43.75s64 8.594 64 43.75c0 32.949-12.871 104.179-20.571 132.813zM156.867 288.554c-18.693-18.308-29.958-44.173-28.784-72.599 2.054-49.724 42.395-89.956 92.124-91.881C274.862 121.958 320 165.807 320 220c0 26.827-11.064 51.116-28.866 68.552-2.675 2.62-2.401 6.986.628 9.187 9.312 6.765 16.46 15.343 21.234 25.363 1.741 3.654 6.497 4.66 9.449 1.891 28.826-27.043 46.553-65.783 45.511-108.565-1.855-76.206-63.595-138.208-139.793-140.369C146.869 73.753 80 139.215 80 220c0 41.361 17.532 78.7 45.55 104.989 2.953 2.771 7.711 1.77 9.453-1.887 4.774-10.021 11.923-18.598 21.235-25.363 3.029-2.2 3.304-6.566.629-9.185zM224 0C100.204 0 0 100.185 0 224c0 89.992 52.602 165.647 125.739 201.408 4.333 2.118 9.267-1.544 8.535-6.31-2.382-15.512-4.342-30.946-5.406-44.339-.146-1.836-1.149-3.486-2.678-4.512-47.4-31.806-78.564-86.016-78.187-147.347.592-96.237 79.29-174.648 175.529-174.899C320.793 47.747 400 126.797 400 224c0 61.932-32.158 116.49-80.65 147.867-1.5 1.001-2.5 2.679-2.64 4.512-1.065 13.393-3.024 28.827-5.406 44.339-.732 4.766 4.203 8.429 8.535 6.31C393.227 391.135 448 315.675 448 224 448 100.205 347.815 0 224 0zm0 160c-35.346 0-64 28.654-64 64s28.654 64 64 64 64-28.654 64-64-28.654-64-64-64z">
                            </path>
                        </svg>
                        <span>Podcasts</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('news.index') }}">
                        <svg class="svg-inline--fa fa-newspaper fa-w-18 fa-fw" aria-hidden="true" focusable="false"
                            data-prefix="fas" data-icon="newspaper" role="img"
                            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" data-fa-i2svg="">
                            <path fill="currentColor"
                                d="M552 64H88c-13.255 0-24 10.745-24 24v8H24c-13.255 0-24 10.745-24 24v272c0 30.928 25.072 56 56 56h472c26.51 0 48-21.49 48-48V88c0-13.255-10.745-24-24-24zM56 400a8 8 0 0 1-8-8V144h16v248a8 8 0 0 1-8 8zm236-16H140c-6.627 0-12-5.373-12-12v-8c0-6.627 5.373-12 12-12h152c6.627 0 12 5.373 12 12v8c0 6.627-5.373 12-12 12zm208 0H348c-6.627 0-12-5.373-12-12v-8c0-6.627 5.373-12 12-12h152c6.627 0 12 5.373 12 12v8c0 6.627-5.373 12-12 12zm-208-96H140c-6.627 0-12-5.373-12-12v-8c0-6.627 5.373-12 12-12h152c6.627 0 12 5.373 12 12v8c0 6.627-5.373 12-12 12zm208 0H348c-6.627 0-12-5.373-12-12v-8c0-6.627 5.373-12 12-12h152c6.627 0 12 5.373 12 12v8c0 6.627-5.373 12-12 12zm0-96H140c-6.627 0-12-5.373-12-12v-40c0-6.627 5.373-12 12-12h360c6.627 0 12 5.373 12 12v40c0 6.627-5.373 12-12 12z">
                            </path>
                        </svg>
                        <span>News</span>
                    </a>
                </li>
                <li class="nav-admin">
                    <button type="button">
                        <svg class="svg-inline--fa fa-exclamation fa-w-6 fa-fw" aria-hidden="true" focusable="false"
                            data-prefix="fas" data-icon="exclamation" role="img"
                            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 192 512" data-fa-i2svg="">
                            <path fill="currentColor"
                                d="M176 432c0 44.112-35.888 80-80 80s-80-35.888-80-80 35.888-80 80-80 80 35.888 80 80zM25.26 25.199l13.6 272C39.499 309.972 50.041 320 62.83 320h66.34c12.789 0 23.331-10.028 23.97-22.801l13.6-272C167.425 11.49 156.496 0 142.77 0H49.23C35.504 0 24.575 11.49 25.26 25.199z">
                            </path>
                        </svg>
                        <span>Admin
                            <svg class="svg-inline--fa fa-chevron-down fa-w-14" aria-hidden="true" focusable="false"
                                data-prefix="fas" data-icon="chevron-down" role="img"
                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" data-fa-i2svg="">
                                <path fill="currentColor"
                                    d="M207.029 381.476L12.686 187.132c-9.373-9.373-9.373-24.569 0-33.941l22.667-22.667c9.357-9.357 24.522-9.375 33.901-.04L224 284.505l154.745-154.021c9.379-9.335 24.544-9.317 33.901.04l22.667 22.667c9.373 9.373 9.373 24.569 0 33.941L240.971 381.476c-9.373 9.372-24.569 9.372-33.942 0z">
                                </path>
                            </svg>
                        </span>
                    </button>
                    <ul class="child_menu">
                        <li class="nav-admin">
                            <a href="{{ route('settings.index') }}">
                                <svg class="svg-inline--fa fa-cog fa-w-16 fa-fw" aria-hidden="true" focusable="false"
                                    data-prefix="fas" data-icon="cog" role="img"
                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg="">
                                    <path fill="currentColor"
                                        d="M487.4 315.7l-42.6-24.6c4.3-23.2 4.3-47 0-70.2l42.6-24.6c4.9-2.8 7.1-8.6 5.5-14-11.1-35.6-30-67.8-54.7-94.6-3.8-4.1-10-5.1-14.8-2.3L380.8 110c-17.9-15.4-38.5-27.3-60.8-35.1V25.8c0-5.6-3.9-10.5-9.4-11.7-36.7-8.2-74.3-7.8-109.2 0-5.5 1.2-9.4 6.1-9.4 11.7V75c-22.2 7.9-42.8 19.8-60.8 35.1L88.7 85.5c-4.9-2.8-11-1.9-14.8 2.3-24.7 26.7-43.6 58.9-54.7 94.6-1.7 5.4.6 11.2 5.5 14L67.3 221c-4.3 23.2-4.3 47 0 70.2l-42.6 24.6c-4.9 2.8-7.1 8.6-5.5 14 11.1 35.6 30 67.8 54.7 94.6 3.8 4.1 10 5.1 14.8 2.3l42.6-24.6c17.9 15.4 38.5 27.3 60.8 35.1v49.2c0 5.6 3.9 10.5 9.4 11.7 36.7 8.2 74.3 7.8 109.2 0 5.5-1.2 9.4-6.1 9.4-11.7v-49.2c22.2-7.9 42.8-19.8 60.8-35.1l42.6 24.6c4.9 2.8 11 1.9 14.8-2.3 24.7-26.7 43.6-58.9 54.7-94.6 1.5-5.5-.7-11.3-5.6-14.1zM256 336c-44.1 0-80-35.9-80-80s35.9-80 80-80 80 35.9 80 80-35.9 80-80 80z">
                                    </path>
                                </svg>
                                <span>System Settings</span>
                            </a>
                        </li>
                        <li class="nav-admin"><a href="{{ route('email-pool.index') }}">Email Pool</a></li>
                        <li class="nav-admin"><a href="{{ route('credit-card.index') }}">Credit Cards</a></li>
                        <li class="nav-admin"><a href="{{ route('help.index') }}">Help</a></li>
                        <li class="nav-admin">
                            <button type="button">
                                <svg class="svg-inline--fa fa-user-secret fa-w-14 fa-fw" aria-hidden="true"
                                    focusable="false" data-prefix="fas" data-icon="user-secret" role="img"
                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" data-fa-i2svg="">
                                    <path fill="currentColor"
                                        d="M383.9 308.3l23.9-62.6c4-10.5-3.7-21.7-15-21.7h-58.5c11-18.9 17.8-40.6 17.8-64v-.3c39.2-7.8 64-19.1 64-31.7 0-13.3-27.3-25.1-70.1-33-9.2-32.8-27-65.8-40.6-82.8-9.5-11.9-25.9-15.6-39.5-8.8l-27.6 13.8c-9 4.5-19.6 4.5-28.6 0L182.1 3.4c-13.6-6.8-30-3.1-39.5 8.8-13.5 17-31.4 50-40.6 82.8-42.7 7.9-70 19.7-70 33 0 12.6 24.8 23.9 64 31.7v.3c0 23.4 6.8 45.1 17.8 64H56.3c-11.5 0-19.2 11.7-14.7 22.3l25.8 60.2C27.3 329.8 0 372.7 0 422.4v44.8C0 491.9 20.1 512 44.8 512h358.4c24.7 0 44.8-20.1 44.8-44.8v-44.8c0-48.4-25.8-90.4-64.1-114.1zM176 480l-41.6-192 49.6 32 24 40-32 120zm96 0l-32-120 24-40 49.6-32L272 480zm41.7-298.5c-3.9 11.9-7 24.6-16.5 33.4-10.1 9.3-48 22.4-64-25-2.8-8.4-15.4-8.4-18.3 0-17 50.2-56 32.4-64 25-9.5-8.8-12.7-21.5-16.5-33.4-.8-2.5-6.3-5.7-6.3-5.8v-10.8c28.3 3.6 61 5.8 96 5.8s67.7-2.1 96-5.8v10.8c-.1.1-5.6 3.2-6.4 5.8z">
                                    </path>
                                </svg>
                                <span>RBAC
                                    <svg class="svg-inline--fa fa-chevron-down fa-w-14" aria-hidden="true"
                                        focusable="false" data-prefix="fas" data-icon="chevron-down" role="img"
                                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" data-fa-i2svg="">
                                        <path fill="currentColor"
                                            d="M207.029 381.476L12.686 187.132c-9.373-9.373-9.373-24.569 0-33.941l22.667-22.667c9.357-9.357 24.522-9.375 33.901-.04L224 284.505l154.745-154.021c9.379-9.335 24.544-9.317 33.901.04l22.667 22.667c9.373 9.373 9.373 24.569 0 33.941L240.971 381.476c-9.373 9.372-24.569 9.372-33.942 0z">
                                        </path>
                                    </svg>
                                </span>
                            </button>
                            <ul class="child_menu">
                                <li class="nav-admin"><a href="{{ route('settings.index') }}">Permission</a></li>
                                <li class="nav-admin"><a href="{{ route('settings.index') }}">Role</a></li>
                            </ul>
                        </li>
                        <li class="nav-admin">
                            <button type="button">
                                <svg class="svg-inline--fa fa-database fa-w-14 fa-fw" aria-hidden="true"
                                    focusable="false" data-prefix="fas" data-icon="database" role="img"
                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" data-fa-i2svg="">
                                    <path fill="currentColor"
                                        d="M448 73.143v45.714C448 159.143 347.667 192 224 192S0 159.143 0 118.857V73.143C0 32.857 100.333 0 224 0s224 32.857 224 73.143zM448 176v102.857C448 319.143 347.667 352 224 352S0 319.143 0 278.857V176c48.125 33.143 136.208 48.572 224 48.572S399.874 209.143 448 176zm0 160v102.857C448 479.143 347.667 512 224 512S0 479.143 0 438.857V336c48.125 33.143 136.208 48.572 224 48.572S399.874 369.143 448 336z">
                                    </path>
                                </svg>
                                <span>Logs
                                    <svg class="svg-inline--fa fa-chevron-down fa-w-14" aria-hidden="true"
                                        focusable="false" data-prefix="fas" data-icon="chevron-down" role="img"
                                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" data-fa-i2svg="">
                                        <path fill="currentColor"
                                            d="M207.029 381.476L12.686 187.132c-9.373-9.373-9.373-24.569 0-33.941l22.667-22.667c9.357-9.357 24.522-9.375 33.901-.04L224 284.505l154.745-154.021c9.379-9.335 24.544-9.317 33.901.04l22.667 22.667c9.373 9.373 9.373 24.569 0 33.941L240.971 381.476c-9.373 9.372-24.569 9.372-33.942 0z">
                                        </path>
                                    </svg>
                                </span>
                            </button>
                            <ul class="child_menu">
                                <li class="nav-admin"><a href="{{ route('api-log.index') }}">API Log</a></li>
                                <li class="nav-admin"><a href="{{ route('active-record-log.index') }}">Active
                                        Record</a></li>
                                <li class="nav-admin"><a href="{{ route('settings.index') }}">Logger</a></li>
                                <li class="nav-admin"><a href="{{ route('apple.notifications') }}">Apple
                                        Notifications</a></li>
                            </ul>
                        </li>
                        <li class="nav-admin"><a href="{{ route('iex.webhooks') }}">Marketstack INDX</a></li>
                        <li class="nav-admin"><a href="{{ route('info-state.index') }}">Info State</a></li>
                        <li class="nav-admin"><a href="{{ route('iex.webhooks') }}">IEX</a></li>
                        <li class="nav-admin"><a href="{{ route('device.index') }}">Devices</a></li>
                    </ul>
                </li>
            </ul>
            </div>
        </aside>

        <div class="alert-destination">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
        </div>

        <div class="content_wrapper">
            <div class="content">
                @yield('content')
            </div>
            <footer class="main-footer">
                <div class="footer-content">
                    <strong>&copy; {{ date('Y') }} {{ config('app.name', 'Laravel') }}.</strong> All rights reserved.
                </div>
            </footer>
        </div>
    </div>

    <div id="JS__modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="loader" class="modal fade text-center in" tabindex="-1"
        style="background-color: rgba(0, 0, 0, 0.5);">
        <div class="loader__child">
            <i class="fas fa-cog fa-spin fa-10x"></i>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Desktop sidebar toggle (mini/expand functionality)
            const sidebarToggler = document.querySelector('.JS__sidebar_toggler');
            if (sidebarToggler) {
                sidebarToggler.addEventListener('click', function() {
                    const sidebar = document.querySelector('.sidebar');
                    const wrapper = document.querySelector('.wrapper');

                    if (sidebar.classList.contains('sidebar-mini')) {
                        sidebar.classList.remove('sidebar-mini');
                        wrapper.classList.remove('wrapper-sidebar_mini');
                    } else {
                        sidebar.classList.add('sidebar-mini');
                        wrapper.classList.add('wrapper-sidebar_mini');
                    }
                });
            }

            // Mobile sidebar toggle (show/hide functionality)
            const mobileToggler = document.querySelector('.JS__sidebar_toggler_mobile');
            if (mobileToggler) {
                mobileToggler.addEventListener('click', function() {
                    const wrapper = document.querySelector('.wrapper');
                    wrapper.classList.toggle('sidebar-open');
                });
            }

            // Close sidebar on mobile when clicking outside
            document.addEventListener('click', function(e) {
                const wrapper = document.querySelector('.wrapper');
                const sidebar = document.querySelector('.sidebar');
                const mobileToggler = document.querySelector('.JS__sidebar_toggler_mobile');
                
                if (window.innerWidth <= 767 && wrapper.classList.contains('sidebar-open')) {
                    // Check if click is outside sidebar
                    if (!sidebar.contains(e.target) && !mobileToggler.contains(e.target)) {
                        wrapper.classList.remove('sidebar-open');
                    }
                }
            });

            // Close sidebar button handler
            const closeSidebarBtn = document.getElementById('closeSidebarBtn');
            if (closeSidebarBtn) {
                closeSidebarBtn.addEventListener('click', function() {
                    const wrapper = document.querySelector('.wrapper');
                    wrapper.classList.remove('sidebar-open');
                });
            }

            // Note: submenu toggle is handled in public/js/admin.js using 'active' class
        });
    </script>

    <!-- jQuery (still needed for admin.js and live-filter.js) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- Bootstrap 5.3.3 JavaScript bundle (with Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <!-- Admin JavaScript -->
    <script src="{{ asset('js/admin.js') }}"></script>
    <!-- Live Filter JavaScript -->
    <script src="{{ asset('js/live-filter.js') }}"></script>
    
    <!-- Admin Live Chat Widget - Disabled for today, will be enabled tomorrow -->
    {{--
    @include('components.admin-chat-widget')
    --}}
    
    <!-- Chat Badge Update Script - Temporary disabled -->
    {{-- 
    <script>
        function updateChatBadge() {
            fetch('/api/v1/admin/chat/unread-count')
                .then(response => response.json())
                .then(data => {
                    const badge = document.getElementById('chat-badge');
                    if (badge && data.unread_count > 0) {
                        badge.textContent = data.unread_count;
                        badge.style.display = 'block';
                    } else if (badge) {
                        badge.style.display = 'none';
                    }
                })
                .catch(error => console.error('Error fetching unread count:', error));
        }
        
        // Update badge every 30 seconds
        setInterval(updateChatBadge, 30000);
        // Update immediately on page load
        updateChatBadge();
    </script>
    --}}
    
    @stack('scripts')
</body>

</html>
