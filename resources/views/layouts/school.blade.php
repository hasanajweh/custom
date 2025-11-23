<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}" class="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    @php
        $school = $school ?? request()->route('school') ?? request()->route('branch') ?? auth()->user()?->school;
        $branch = $branch ?? request()->route('branch');
        $network = $network ?? request()->route('network') ?? $school?->network ?? auth()->user()?->network;

        $schoolName = $school?->name ?? config('app.name');
        $schoolSlug = $school?->slug ?? '';
        $networkSlug = $network?->slug ?? $school?->network?->slug ?? auth()->user()?->network?->slug ?? '';
        $isMainAdmin = $isMainAdmin ?? (bool) auth()->user()?->is_main_admin;
        $hasTenantContext = $school && $school->network;
    @endphp
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover, maximum-scale=5.0, user-scalable=yes">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- ============================================ -->
    <!-- UNIVERSAL PWA META TAGS - ALL PLATFORMS -->
    <!-- ============================================ -->

    <!-- Core PWA -->
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="application-name" content="{{ $schoolName }}">

    <!-- iOS Safari - CRITICAL FOR iOS PWA -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="{{ $schoolName }}">
    <meta name="apple-touch-fullscreen" content="yes">

    <!-- iOS Splash Screens - iPhone (Most Common Models) -->
    <link rel="apple-touch-startup-image" media="screen and (device-width: 430px) and (device-height: 932px) and (-webkit-device-pixel-ratio: 3) and (orientation: portrait)" href="/splash/iPhone_15_Pro_Max_portrait.png">
    <link rel="apple-touch-startup-image" media="screen and (device-width: 393px) and (device-height: 852px) and (-webkit-device-pixel-ratio: 3) and (orientation: portrait)" href="/splash/iPhone_15_Pro_portrait.png">
    <link rel="apple-touch-startup-image" media="screen and (device-width: 428px) and (device-height: 926px) and (-webkit-device-pixel-ratio: 3) and (orientation: portrait)" href="/splash/iPhone_14_Plus_portrait.png">
    <link rel="apple-touch-startup-image" media="screen and (device-width: 390px) and (device-height: 844px) and (-webkit-device-pixel-ratio: 3) and (orientation: portrait)" href="/splash/iPhone_14_portrait.png">
    <link rel="apple-touch-startup-image" media="screen and (device-width: 375px) and (device-height: 812px) and (-webkit-device-pixel-ratio: 3) and (orientation: portrait)" href="/splash/iPhone_13_mini_portrait.png">

    <!-- iOS Splash Screens - iPad (Most Common Models) -->
    <link rel="apple-touch-startup-image" media="screen and (device-width: 1024px) and (device-height: 1366px) and (-webkit-device-pixel-ratio: 2) and (orientation: portrait)" href="/splash/iPad_Pro_12.9_portrait.png">
    <link rel="apple-touch-startup-image" media="screen and (device-width: 834px) and (device-height: 1194px) and (-webkit-device-pixel-ratio: 2) and (orientation: portrait)" href="/splash/iPad_Pro_11_portrait.png">
    <link rel="apple-touch-startup-image" media="screen and (device-width: 820px) and (device-height: 1180px) and (-webkit-device-pixel-ratio: 2) and (orientation: portrait)" href="/splash/iPad_Air_portrait.png">
    <link rel="apple-touch-startup-image" media="screen and (device-width: 810px) and (device-height: 1080px) and (-webkit-device-pixel-ratio: 2) and (orientation: portrait)" href="/splash/iPad_portrait.png">
    <link rel="apple-touch-startup-image" media="screen and (device-width: 768px) and (device-height: 1024px) and (-webkit-device-pixel-ratio: 2) and (orientation: portrait)" href="/splash/iPad_9.7_portrait.png">

    <!-- Windows/IE -->
    <meta name="msapplication-TileColor" content="#3B82F6">
    <meta name="msapplication-TileImage" content="/Scholder-144.png">
    <meta name="msapplication-tap-highlight" content="no">
    <meta name="msapplication-config" content="/browserconfig.xml">

    <!-- Theme Colors - All Browsers -->
    <meta name="theme-color" content="#3B82F6">
    <meta name="theme-color" media="(prefers-color-scheme: light)" content="#3B82F6">
    <meta name="theme-color" media="(prefers-color-scheme: dark)" content="#1E40AF">

    <!-- Description & Keywords -->
    <meta name="description" content="Educational platform for {{ $schoolName }} - Manage files, assignments, and resources">
    <meta name="keywords" content="education, {{ $schoolName }}, learning, teaching, resources">

    <title>@yield('title', $schoolName . ' - ' . __('messages.app.name'))</title>

    <!-- ============================================ -->
    <!-- ICONS - ALL PLATFORMS & SIZES -->
    <!-- ============================================ -->

    <!-- Standard Favicon -->
    <link rel="icon" type="image/png" sizes="32x32" href="/Scholder-192.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/Scholder-192.png">
    <link rel="shortcut icon" href="/Scholder-192.png">

    <!-- Apple Touch Icons - ALL iOS DEVICES -->
    <link rel="apple-touch-icon" href="/Scholder-180.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/Scholder-180.png">
    <link rel="apple-touch-icon" sizes="152x152" href="/Scholder-152.png">
    <link rel="apple-touch-icon" sizes="144x144" href="/Scholder-144.png">
    <link rel="apple-touch-icon" sizes="120x120" href="/Scholder-128.png">
    <link rel="apple-touch-icon" sizes="114x114" href="/Scholder-128.png">
    <link rel="apple-touch-icon" sizes="76x76" href="/Scholder-96.png">
    <link rel="apple-touch-icon" sizes="72x72" href="/Scholder-72.png">
    <link rel="apple-touch-icon" sizes="60x60" href="/Scholder-72.png">
    <link rel="apple-touch-icon" sizes="57x57" href="/Scholder-72.png">

    <!-- Safari Pinned Tab -->
    <link rel="mask-icon" href="/Scholder-192.png" color="#3B82F6">

    <!-- Android/Chrome -->
    <link rel="icon" type="image/png" sizes="192x192" href="/Scholder-192.png">
    <link rel="icon" type="image/png" sizes="512x512" href="/Scholder-512.png">

    <!-- Web App Manifest - UNIVERSAL -->
    <link rel="manifest" href="{{ url('/manifest.json' . ($schoolSlug ? '?school=' . $schoolSlug : '')) }}">

    <!-- Enhanced Font Loading with Language Support -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    @if(app()->getLocale() === 'ar')
        <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    @else
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    @endif

    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.1.0/fonts/remixicon.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.5/dist/cdn.min.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.1.0/fonts/remixicon.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.5/dist/cdn.min.js"></script>


    <style>
        :root {
            --primary: #2563EB;
            --primary-dark: #1E40AF;
            --primary-light: #DBEAFE;
            --primary-lighter: #EFF6FF;
            --secondary: #F8FAFC;
            --secondary-dark: #F1F5F9;
            --accent: #7C3AED;
            --accent-light: #EDE9FE;
            --text-primary: #0F172A;
            --text-secondary: #475569;
            --text-muted: #64748B;
            --text-light: #94A3B8;
            --border: #E2E8F0;
            --border-light: #F1F5F9;
            --success: #059669;
            --success-light: #ECFDF5;
            --warning: #D97706;
            --warning-light: #FFFBEB;
            --danger: #DC2626;
            --danger-light: #FEF2F2;
            --info: #0284C7;
            --info-light: #F0F9FF;
            --sidebar-width: 280px;
            --sidebar-collapsed: 80px;
            --navbar-height: 70px;
            --brand-primary: #2563EB;
            --brand-text: #0F172A;
            --brand-text-light: #475569;
            --brand-background: #F8FAFC;
            --brand-secondary: #FFFFFF;
            --brand-border: #E2E8F0;
        }

        * {
            @if(app()->getLocale() === 'ar')
                font-family: 'Cairo', 'Rubik', 'Segoe UI', system-ui, sans-serif;
            @else
                font-family: 'Inter', system-ui, -apple-system, sans-serif;
            @endif
            text-rendering: optimizeLegibility;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        @if(app()->getLocale() === 'ar')
        * {
            letter-spacing: 0.02em;
            line-height: 1.8;
        }

        .font-heading {
            font-family: 'Cairo', 'Rubik', sans-serif;
            font-weight: 700;
            letter-spacing: 0;
            line-height: 1.4;
        }
        @else
        .font-heading {
            font-family: 'Inter', sans-serif;
            font-weight: 700;
            letter-spacing: -0.02em;
            line-height: 1.3;
        }
        @endif

        body {
            overflow-x: hidden;
            font-size: 14px;
            line-height: 1.6;
            color: var(--text-primary);
            background: #FFFFFF;
            min-height: 100vh;
            font-weight: 400;
            overscroll-behavior-y: none;
        }

        /* ===== ADDITIONAL MOBILE ENHANCEMENTS ===== */
        
        /* Better focus states */
        *:focus-visible {
            outline: 2px solid var(--primary);
            outline-offset: 2px;
        }

        /* Touch-friendly interactions */
        button, .btn, a.button {
            -webkit-user-select: none;
            -moz-user-select: none;
            user-select: none;
            -webkit-tap-highlight-color: transparent;
        }

        /* Responsive images */
        img {
            max-width: 100%;
            height: auto;
        }

        /* Smooth scrolling */
        html {
            scroll-behavior: smooth;
        }

        /* Tablet optimizations */
        @media (min-width: 769px) and (max-width: 1024px) {
            body {
                font-size: 15px;
            }
        }

        /* Hide mobile-only on desktop */
        @media (min-width: 769px) {
            .mobile-only {
                display: none !important;
            }
            
            .sidebar-header {
                display: none !important;
            }

            .mobile-language-switcher {
                display: none !important;
            }
        }

        /* Landscape mobile */
        @media (max-width: 768px) and (orientation: landscape) {
            .navbar {
                height: 56px !important;
            }
        }


        @supports (padding: max(0px)) {
            body {
                padding-left: max(0px, env(safe-area-inset-left));
                padding-right: max(0px, env(safe-area-inset-right));
            }

            .navbar {
                padding-top: max(0px, env(safe-area-inset-top));
            }
        }

        /* UNIVERSAL PWA Install Banners */
        .pwa-install-banner {
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 16px 24px;
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(102, 126, 234, 0.4);
            display: none;
            align-items: center;
            gap: 16px;
            z-index: 99999;
            max-width: 90%;
            animation: slideUp 0.3s ease;
        }

        .pwa-install-banner.show {
            display: flex;
        }

        @keyframes slideUp {
            from { transform: translateX(-50%) translateY(100px); opacity: 0; }
            to { transform: translateX(-50%) translateY(0); opacity: 1; }
        }

        .pwa-install-btn {
            background: white;
            color: #667eea;
            border: none;
            padding: 10px 20px;
            border-radius: 10px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
            white-space: nowrap;
        }

        .pwa-install-btn:hover {
            transform: scale(1.05);
        }

        /* iOS Safari Install Banner */
        .ios-install-banner {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(to top, rgba(0,0,0,0.95) 0%, rgba(0,0,0,0.9) 100%);
            color: white;
            padding: 24px 20px;
            text-align: center;
            display: none;
            z-index: 99999;
            animation: slideUpIOS 0.3s ease;
            backdrop-filter: blur(10px);
        }

        .ios-install-banner.show {
            display: block;
        }

        @keyframes slideUpIOS {
            from { transform: translateY(100%); }
            to { transform: translateY(0); }
        }

        .ios-share-icon {
            font-size: 28px;
            animation: bounce 1.5s infinite;
            display: inline-block;
        }

        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-8px); }
        }

        .pwa-offline-indicator {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: #EF4444;
            color: white;
            padding: 12px 20px;
            border-radius: 12px;
            display: none;
            align-items: center;
            gap: 10px;
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
            z-index: 99999;
        }

        .pwa-offline-indicator.show {
            display: flex;
        }

        .rtl {
            direction: rtl;
        }

        .rtl .sidebar {
            right: 0;
            left: auto;
        }

        .rtl .main-content {
            margin-right: var(--sidebar-collapsed);
            margin-left: 0;
        }

        .rtl .sidebar.expanded ~ .main-content {
            margin-right: var(--sidebar-width);
            margin-left: 0;
        }

        .navbar {
            background: #FFFFFF;
            border-bottom: 1px solid var(--border);
            height: var(--navbar-height);
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05);
            position: fixed;
            z-index: 50;
        }

        .logo-container {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 10px 16px;
            border-radius: 10px;
        }

        .logo-img {
            height: 42px;
            width: 42px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .brand-text {
            display: flex;
            flex-direction: column;
            align-items: {{ app()->getLocale() === 'ar' ? 'flex-end' : 'flex-start' }};
            line-height: 1.2;
        }

        .brand-name {
            font-size: 20px;
            font-weight: 800;
            color: var(--primary);
            letter-spacing: {{ app()->getLocale() === 'ar' ? '0' : '-0.025em' }};
            line-height: 1.1;
            margin-bottom: 3px;
        }

        .school-name {
            font-size: 13px;
            color: var(--text-muted);
            font-weight: 500;
            line-height: 1.2;
        }

        .language-switcher {
            position: relative;
        }

        .language-btn {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 18px;
            border-radius: 12px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #FFFFFF !important;
            border: none;
            cursor: pointer;
            font-weight: 700;
            font-size: 14px;
            min-width: 140px;
            justify-content: space-between;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
            transition: all 0.3s ease;
        }

        .language-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(102, 126, 234, 0.4);
            background: linear-gradient(135deg, #5a67d8 0%, #6b46a0 100%);
        }

        .language-btn:active {
            transform: translateY(0);
        }

        .language-btn span,
        .language-btn i {
            color: #FFFFFF !important;
        }

        .language-dropdown {
            position: absolute;
            top: calc(100% + 8px);
        {{ app()->getLocale() === 'ar' ? 'left' : 'right' }}: 0;
            background: #FFFFFF;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            border: 1px solid #E5E7EB;
            min-width: 200px;
            z-index: 1000;
            overflow: hidden;
            display: none;
            opacity: 0;
            transform: translateY(-10px);
            transition: opacity 0.2s ease, transform 0.2s ease;
        }

        .language-dropdown.show {
            display: block;
            opacity: 1;
            transform: translateY(0);
        }

        .language-option {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 14px 16px;
            text-decoration: none;
            color: #374151 !important;
            background-color: #FFFFFF !important;
            border: none;
            cursor: pointer;
            width: 100%;
            text-align: {{ app()->getLocale() === 'ar' ? 'right' : 'left' }};
            font-weight: 600;
            font-size: 14px;
            transition: all 0.2s ease;
            border-bottom: 1px solid #F3F4F6;
        }

        .language-option:last-child {
            border-bottom: none;
        }

        .language-option:hover {
            background-color: #F9FAFB !important;
            color: #667eea !important;
            padding-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }}: 20px;
        }

        .language-option.active {
            background-color: #EEF2FF !important;
            color: #667eea !important;
            font-weight: 700;
        }

        .language-option.active i {
            color: #667eea !important;
        }

        .language-option span {
            color: inherit !important;
        }

        .flag-icon {
            width: 24px;
            height: 18px;
            border-radius: 4px;
            object-fit: cover;
        }

        .sidebar {
            position: fixed;
        {{ app()->getLocale() === 'ar' ? 'right' : 'left' }}: 0;
            top: var(--navbar-height);
            height: calc(100vh - var(--navbar-height));
            width: var(--sidebar-collapsed);
            background: #FFFFFF;
            z-index: 30;
            display: flex;
            flex-direction: column;
            transition: none; /* JS handles animation */
        }

        .sidebar.expanded {
            width: var(--sidebar-width);
        }

        .sidebar-content {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
            padding: 24px 12px;
            scrollbar-width: thin;
            scrollbar-color: var(--border) transparent;
        }

        .sidebar-item {
            display: flex;
            align-items: center;
            padding: 12px;
            margin: 4px 0;
            color: #374151;
            text-decoration: none;
            border-radius: 10px;
            position: relative;
            min-height: 44px;
            font-weight: 600;
            font-size: {{ app()->getLocale() === 'ar' ? '15px' : '14px' }};
            white-space: nowrap;
        }

        .sidebar-item:hover {
            background: #F8FAFC;
            color: var(--primary);
        }

        .sidebar-item.active {
            color: var(--primary);
            font-weight: 700;
        }

        .sidebar-item i {
            min-width: 24px;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-{{ app()->getLocale() === 'ar' ? 'left' : 'right' }}: 12px;
            font-size: 20px;
            flex-shrink: 0;
        }

        .sidebar-item:nth-child(1) i { color: #3B82F6; }
        .sidebar-item:nth-child(2) i { color: #8B5CF6; }
        .sidebar-item:nth-child(3) i { color: #F59E0B; }
        .sidebar-item:nth-child(4) i { color: #10B981; }
        .sidebar-item:nth-child(5) i { color: #EF4444; }
        .sidebar-item:nth-child(7) i { color: #EC4899; }
        .sidebar-item:nth-child(8) i { color: #06B6D4; }

        .sidebar-item.active i {
            color: inherit;
        }

        .sidebar:not(.expanded) .sidebar-item i {
            margin-{{ app()->getLocale() === 'ar' ? 'left' : 'right' }}: 0;
        }

        .sidebar-text {
            font-size: {{ app()->getLocale() === 'ar' ? '15px' : '14px' }};
            white-space: nowrap;
            overflow: hidden;
            max-width: 0;
            opacity: 0;
            color: #374151;
        }

        .sidebar.expanded .sidebar-text {
            max-width: 200px;
            opacity: 1;
            margin-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }}: 0;
        }

        .sidebar-item:hover .sidebar-text,
        .sidebar-item.active .sidebar-text {
            color: inherit;
        }

        .sidebar-divider {
            margin: 20px 0;
            padding: 0 12px;
            overflow: hidden;
        }

        .sidebar:not(.expanded) .sidebar-divider {
            margin: 12px 0;
        }

        .sidebar-divider-line {
            height: 1px;
            background: var(--border);
        }

        .sidebar-divider-title {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            color: var(--text-light);
            letter-spacing: 0.05em;
            margin-top: 12px;
            margin-bottom: 8px;
            white-space: nowrap;
            overflow: hidden;
            opacity: 0;
            max-height: 0;
        }

        .sidebar.expanded .sidebar-divider-title {
            opacity: 1;
            max-height: 30px;
        }

        .main-content {
            margin-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }}: var(--sidebar-collapsed);
            min-height: calc(100vh - var(--navbar-height));
            padding-top: var(--navbar-height);
            background: #FAFBFC;
            will-change: width, margin;
        }

        .sidebar.expanded ~ .main-content {
            margin-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }}: var(--sidebar-width);
        }

        .dropdown-menu {
            background: #FFFFFF;
            border-radius: 12px;
            box-shadow: 0 10px 30px -5px rgba(0,0,0,0.1);
            border: 1px solid var(--border);
            overflow: hidden;
            z-index: 100;
        }

        .dropdown-item {
            display: flex;
            align-items: center;
            width: 100%;
            padding: 10px 16px;
            font-weight: 500;
            color: var(--text-secondary);
        }

        .dropdown-item:hover {
            background-color: #F8FAFC;
            color: var(--primary);
        }

        .dropdown-item i {
            font-size: 20px;
        }

        .sidebar-footer {
            margin-top: auto;
            padding: 20px 12px;
            border-top: 1px solid var(--border);
            text-align: center;
        }

        .sidebar-footer-text {
            font-size: 12px;
            color: var(--text-muted);
            font-weight: 600;
            margin-bottom: 4px;
            white-space: nowrap;
            overflow: hidden;
            opacity: 0;
        }

        .sidebar.expanded .sidebar-footer-text {
            opacity: 1;
        }

        .sidebar:not(.expanded) .sidebar-footer {
            padding: 12px;
        }

        /* === MOBILE LANGUAGE SWITCHER STYLES === */
        .mobile-language-switcher {
            display: none; /* Hidden by default, shown in media query */
            padding: 16px 12px;
        }

        .mobile-language-switcher .language-btn {
            width: 100%;
            background: var(--primary-lighter);
            color: var(--primary) !important;
            box-shadow: none;
        }
        
        .mobile-language-switcher .language-btn:hover {
            background: var(--primary-light);
            transform: none; /* Disable hover transform */
        }
        
        .mobile-language-switcher .language-btn span,
        .mobile-language-switcher .language-btn i {
            color: var(--primary) !important;
        }
        
        .mobile-language-switcher .language-dropdown {
            /* Adjust dropdown for sidebar */
            position: relative; /* Unset absolute */
            top: auto;
            left: auto;
            right: auto;
            width: 100%;
            margin-top: 8px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
            opacity: 1; /* Always visible when toggled */
            transform: none; /* No animation */
        }


        @media (max-width: 768px) {
            .navbar {
                background: rgba(255, 255, 255, 0.95);
                backdrop-filter: blur(12px);
                -webkit-backdrop-filter: blur(12px);
                border-bottom: 1px solid var(--border);
                height: 60px;
                padding: 0 16px;
            }

            /* === HIDE DESKTOP LANGUAGE SWITCHER === */
            .navbar .desktop-language-switcher {
                display: none !important;
            }

            /* HIDE SIDEBAR BY DEFAULT ON MOBILE */
            .sidebar {
                position: fixed;
                top: 0;
            {{ app()->getLocale() === 'ar' ? 'right' : 'left' }}: 0;
                height: 100vh;
                width: var(--sidebar-width); /* Use expanded width */
                background: #FFFFFF;
                z-index: 1000;
                display: flex;
                flex-direction: column;
                box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
                transform: translateX(-100%);
                transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            }

            /* RTL: Hide sidebar to the right */
            .rtl .sidebar {
                transform: translateX(100%);
            }

            /* Show sidebar when mobile-open class is added */
            .sidebar.mobile-open {
                transform: translateX(0) !important;
            }

            /* REMOVE SIDEBAR MARGIN FROM MAIN CONTENT ON MOBILE */
            .main-content {
                margin-left: 0 !important;
                margin-right: 0 !important;
                padding-top: 60px;
                width: 100%;
                min-width: 100%;
            }

            /* Mobile overlay backdrop */
            .mobile-overlay {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0, 0, 0, 0.5);
                z-index: 999;
                opacity: 0;
                transition: opacity 0.3s ease;
            }

            .mobile-overlay.show {
                display: block;
                opacity: 1;
            }

            /* PWA banner mobile */
            .pwa-install-banner {
                flex-direction: column;
                text-align: center;
                padding: 12px 16px;
                max-width: calc(100% - 32px);
            }

            /* Sidebar header visible on mobile */
            .sidebar-header {
                display: flex !important;
                justify-content: space-between;
                align-items: center;
                padding: 16px;
                border-bottom: 1px solid var(--border);
                /* Match navbar height roughly */
                height: 60px;
            }

            /* === SHOW MOBILE LANGUAGE SWITCHER === */
            .sidebar .mobile-language-switcher {
                display: block !important;
            }

            /* === SHOW SIDEBAR LABELS ON MOBILE === */
            .sidebar .sidebar-text {
                max-width: 200px;
                opacity: 1;
            }

            .sidebar .sidebar-divider-title {
                opacity: 1;
                max-height: 30px;
            }

            /* Fix icon margin on mobile (resetting desktop collapsed rule) */
            .sidebar:not(.expanded) .sidebar-item i {
                margin-right: 12px;
                margin-left: 0;
            }
            .rtl .sidebar:not(.expanded) .sidebar-item i {
                margin-left: 12px;
                margin-right: 0;
            }
            /* === END SIDEBAR LABEL FIX === */


            /* Better touch targets */
            button:not(.language-option):not(.dropdown-item), a.btn {
                min-height: 44px;
                padding: 12px 16px;
            }

            /* Prevent iOS zoom on inputs */
            input[type="text"],
            input[type="email"],
            input[type="password"],
            input[type="number"],
            input[type="tel"],
            input[type="search"],
            select,
            textarea {
                font-size: 16px !important;
            }

            /* Full width dropdowns */
            #userMenuDropdown {
                position: fixed !important;
                left: 16px !important;
                right: 16px !important;
                width: calc(100vw - 32px) !important;
                max-width: calc(100vw - 32px) !important;
            }

            /* Better content padding */
            .main-content > div {
                padding: 16px !important;
            }

            /* Hide desktop elements */
            .desktop-only {
                display: none !important;
            }

            /* Logo adjustments */
            .brand-name {
                font-size: 18px !important;
            }

            .school-name {
                font-size: 12px !important;
            }

            /* Responsive tables */
            table {
                display: block;
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }

            /* Better spacing */
            body {
                font-size: 16px;
            }

            /* Prevent body scroll when sidebar open */
            body.sidebar-open {
                overflow: hidden;
            }
        }

        select,
        select option {
            color: #1F2937 !important;
            background-color: #FFFFFF !important;
        }

        select:focus {
            outline: 2px solid var(--primary);
            outline-offset: 2px;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[type="number"],
        input[type="search"],
        input[type="date"],
        input[type="datetime-local"],
        input[type="time"],
        textarea {
            color: #1F2937 !important;
            background-color: #FFFFFF !important;
        }

        input[type="date"]::-webkit-calendar-picker-indicator,
        input[type="datetime-local"]::-webkit-calendar-picker-indicator,
        input[type="time"]::-webkit-calendar-picker-indicator {
            filter: invert(0.5);
            cursor: pointer;
        }

        input::placeholder,
        textarea::placeholder {
            color: #9CA3AF !important;
            opacity: 1;
        }

        @media (max-width: 768px) {
            thead th,
            tbody td {
                padding: 12px 16px;
                font-size: 13px;
            }

            thead th:first-child,
            tbody td:first-child {
                padding-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }}: 16px;
            }

            thead th:last-child,
            tbody td:last-child {
                padding-{{ app()->getLocale() === 'ar' ? 'left' : 'right' }}: 16px;
            }

            tbody td .btn {
                padding: 6px 10px;
                font-size: 12px;
            }
        }

        table.table-striped tbody tr:nth-child(even) {
            background-color: #F9FAFB;
        }

        table.table-striped tbody tr:nth-child(even):hover {
            background-color: #F3F4F6;
        }

        table.table-bordered {
            border: 1px solid #E5E7EB;
        }

        table.table-bordered thead th,
        table.table-bordered tbody td {
            border-{{ app()->getLocale() === 'ar' ? 'left' : 'right' }}: 1px solid #E5E7EB;
        }

        table.table-bordered thead th:last-child,
        table.table-bordered tbody td:last-child {
            border-{{ app()->getLocale() === 'ar' ? 'left' : 'right' }}: none;
        }

        table.table-compact thead th,
        table.table-compact tbody td {
            padding: 10px 16px;
            font-size: 13px;
        }

        table th.checkbox-col,
        table td.checkbox-col {
            width: 50px;
            text-align: center;
            padding: 16px 10px;
        }

        table th.actions-col,
        table td.actions-col {
            width: auto;
            white-space: nowrap;
        }

        table td.actions-col {
            display: flex;
            gap: 8px;
            justify-content: {{ app()->getLocale() === 'ar' ? 'flex-start' : 'flex-end' }};
            align-items: center;
        }

        .dropdown-item,
        [role="option"],
        [role="menuitem"] {
            color: #1F2937 !important;
            background-color: #FFFFFF !important;
        }

        .dropdown-item:hover,
        [role="option"]:hover,
        [role="menuitem"]:hover {
            background-color: #F3F4F6 !important;
            color: var(--primary) !important;
        }
    
</style>
    @stack('styles')

</head>
<body class="bg-white {{ app()->getLocale() === 'ar' ? 'rtl' : '' }}">
@auth

<!-- Chrome/Edge/Brave/Opera Install Banner -->
<div id="pwaInstallBanner" class="pwa-install-banner">
    <div>
        <div class="font-bold mb-1">Install {{ $schoolName }}</div>
        <div class="text-sm opacity-90">Quick access from your home screen or desktop</div>
    </div>
    <button id="pwaInstallBtn" class="pwa-install-btn">
        <i class="ri-download-line mr-2"></i>Install App
    </button>
    <button onclick="dismissInstallBanner()" class="text-white opacity-75 hover:opacity-100">
        <i class="ri-close-line text-xl"></i>
    </button>
</div>

<!-- iOS Safari Install Banner -->
<div id="iosInstallBanner" class="ios-install-banner">
    <div class="ios-install-instructions">
        <div class="ios-share-icon">
            <i class="ri-share-box-line"></i>
        </div>
        <div class="font-bold text-lg mb-2">Install {{ $schoolName }}</div>
        <div class="text-sm opacity-90 mb-3">
            Tap <strong>Share</strong> <i class="ri-share-box-line mx-1"></i> then <strong>"Add to Home Screen"</strong>
        </div>
        <button onclick="dismissIOSBanner()" class="px-6 py-2 bg-white/20 rounded-lg text-white font-semibold">
            Got it!
        </button>
    </div>
</div>

<!-- Offline Indicator -->
<div id="offlineIndicator" class="pwa-offline-indicator">
    <i class="ri-wifi-off-line text-xl"></i>
    <span>You're offline</span>
</div>

<!-- Mobile Overlay -->
<div id="mobileOverlay" class="mobile-overlay" onclick="closeMobileSidebar()"></div>

<!-- Enhanced Navigation -->
@auth
<nav class="navbar fixed w-full top-0 z-50">
    <div class="px-6 sm:px-8 lg:px-10 h-full">
        <div class="flex justify-between items-center h-full">
            <div class="flex items-center">
                <!-- Mobile Menu Toggle -->
                <button onclick="toggleMobileSidebar()"
                        class="p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg md:hidden"
                        aria-label="{{ __('messages.navigation.open_sidebar') }}">
                    <i class="ri-menu-line text-xl"></i>
                </button>

                <!-- Desktop Sidebar Toggle -->
                <button onclick="toggleSidebar()"
                        class="p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg hidden md:block"
                        aria-label="{{ __('messages.navigation.open_sidebar') }}">
                    <i class="ri-menu-line text-xl"></i>
                </button>

                <!-- Enhanced Logo -->
                <div class="logo-container {{ app()->getLocale() === 'ar' ? 'mr-4' : 'ml-4' }}">
                    <img src="/WayUp.png" alt="{{ __('messages.app.name') }}" class="logo-img">
                    <div class="brand-text">
                        <h1 class="brand-name font-heading">{{ __('messages.app.name') }}</h1>
                        <span class="school-name">{{ $schoolName }}</span>
                    </div>
                </div>
            </div>

            <!-- Right Navigation -->
            <div class="flex items-center space-x-4 {{ app()->getLocale() === 'ar' ? 'space-x-reverse' : '' }}">
                <!-- Enhanced Language Switcher (Desktop) -->
                <div class="language-switcher desktop-language-switcher">
                    <button onclick="toggleLanguageDropdown(event, 'languageDropdown')"
                            class="language-btn"
                            type="button"
                            aria-label="{{ __('messages.ui.toggle_language') }}">
                        <div class="flex items-center gap-2">
                            @if(app()->getLocale() === 'ar')
                                <img src="https://flagcdn.com/w20/sa.png" alt="العربية" class="flag-icon">
                                <span class="hidden sm:inline">العربية</span>
                            @else
                                <img src="https://flagcdn.com/w20/us.png" alt="English" class="flag-icon">
                                <span class="hidden sm:inline">English</span>
                            @endif
                        </div>
                        <i class="ri-arrow-down-s-line text-lg"></i>
                    </button>

                    <div id="languageDropdown" class="language-dropdown">
                        <!-- English Option -->
                        <form method="POST" action="{{ route('language.switch', ['locale' => 'en']) }}" class="w-full">
                            @csrf
                            @if($hasTenantContext)
                                <input type="hidden" name="network" value="{{ $networkSlug }}">
                                <input type="hidden" name="branch" value="{{ $schoolSlug }}">
                            @endif
                            <button type="submit"
                                    class="language-option {{ app()->getLocale() === 'en' ? 'active' : '' }}">
                                <img src="https://flagcdn.com/w20/us.png" alt="English" class="flag-icon">
                                <span>English</span>
                                @if(app()->getLocale() === 'en')
                                    <i class="ri-check-line {{ app()->getLocale() === 'ar' ? 'mr-auto' : 'ml-auto' }} text-blue-600"></i>
                                @endif
                            </button>
                        </form>

                        <!-- Arabic Option -->
                        <form method="POST" action="{{ route('language.switch', ['locale' => 'ar']) }}" class="w-full">
                            @csrf
                            @if($hasTenantContext)
                                <input type="hidden" name="network" value="{{ $networkSlug }}">
                                <input type="hidden" name="branch" value="{{ $schoolSlug }}">
                            @endif
                            <button type="submit"
                                    class="language-option {{ app()->getLocale() === 'ar' ? 'active' : '' }}">
                                <img src="https://flagcdn.com/w20/sa.png" alt="العربية" class="flag-icon">
                                <span>العربية</span>
                                @if(app()->getLocale() === 'ar')
                                    <i class="ri-check-line {{ app()->getLocale() === 'ar' ? 'mr-auto' : 'ml-auto' }} text-blue-600"></i>
                                @endif
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Enhanced User Dropdown -->
                <div class="relative">
                    <button onclick="toggleUserMenu(event)"
                            class="flex items-center space-x-3 p-2 hover:bg-gray-100 rounded-lg {{ app()->getLocale() === 'ar' ? 'space-x-reverse' : '' }}"
                            aria-label="{{ __('messages.navigation.open_user_menu') }}">
                        @php
                            $role = strtolower(Auth::user()->role);
                            $bgColors = [
                                'teacher' => 'bg-blue-600',
                                'supervisor' => 'bg-indigo-600',
                                'admin' => 'bg-gray-700',
                            ];
                            $bgColor = $bgColors[$role] ?? 'bg-blue-600';
                        @endphp

                        <div class="w-9 h-9 rounded-lg {{ $bgColor }} text-white flex items-center justify-center font-bold text-sm">
                            @if($role === 'admin')
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            @elseif($role === 'teacher')
                                <i class="ri-user-line text-lg"></i>
                            @elseif($role === 'supervisor')
                                <i class="ri-user-star-line text-lg"></i>
                            @else
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            @endif
                        </div>

                        <div class="hidden sm:block text-left {{ app()->getLocale() === 'ar' ? 'text-right' : '' }}">
                            <p class="text-sm font-bold text-gray-700">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-gray-500 font-medium">{{ __('messages.roles.' . Auth::user()->role) }}</p>
                        </div>
                        <i class="ri-arrow-down-s-line text-gray-400"></i>
                    </button>

                    <div id="userMenuDropdown" class="absolute {{ app()->getLocale() === 'ar' ? 'left-0' : 'right-0' }} mt-2 w-64 dropdown-menu" style="display: none;">
                        <!-- User Info Header -->
                        <div class="p-4 bg-gray-50 border-b border-gray-100">
                            <div class="flex items-center space-x-3 {{ app()->getLocale() === 'ar' ? 'space-x-reverse' : '' }}">
                                <div class="w-12 h-12 rounded-lg {{ $bgColor }} text-white flex items-center justify-center font-bold">
                                    @if($role === 'admin')
                                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                    @elseif($role === 'teacher')
                                        <i class="ri-user-line text-xl"></i>
                                    @elseif($role === 'supervisor')
                                        <i class="ri-user-star-line text-xl"></i>
                                    @else
                                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                    @endif
                                </div>

                                <div class="flex-1 min-w-0">
                                    <p class="font-bold text-gray-900">{{ Auth::user()->name }}</p>
                                    <p class="text-sm text-gray-600 font-semibold">{{ __('messages.roles.' . Auth::user()->role) }}</p>
                                    <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Menu Items -->
                        <div class="py-1">
                            @php
                                $networkSlug = auth()->user()?->network?->slug;
                                if ($isMainAdmin) {
                                    $profileUrl = $networkSlug ? route('main-admin.dashboard', ['network' => $networkSlug]) : '#';
                                    $logoutUrl = $networkSlug ? route('main-admin.logout', ['network' => $networkSlug]) : '#';
                                } else {
                                    $profileUrl = safe_tenant_route('profile.edit', $school, '#');
                                    $logoutUrl = safe_tenant_route('logout', $school, '#');
                                }
                            @endphp
                            <a href="{{ $profileUrl }}"
                               class="dropdown-item">
                                <i class="ri-user-line text-gray-500 {{ app()->getLocale() === 'ar' ? 'ml-3' : 'mr-3' }}"></i>
                                <span>{{ __('messages.navigation.profile') }}</span>
                            </a>

                            @if(Auth::user()->role === 'admin')
                                <a href="{{ safe_tenant_route('school.admin.activity-logs.index', $school) }}"
                                   class="dropdown-item">
                                    <i class="ri-history-line text-indigo-500 {{ app()->getLocale() === 'ar' ? 'ml-3' : 'mr-3' }}"></i>
                                    <span>{{ __('messages.navigation.activity_logs') }}</span>
                                </a>
                            @endif

                            <div class="border-t border-gray-100 my-1"></div>

                            <form method="POST" action="{{ $logoutUrl }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-red-600 w-full">
                                    <i class="ri-logout-box-line {{ app()->getLocale() === 'ar' ? 'ml-3' : 'mr-3' }}"></i>
                                    <span>{{ __('messages.auth.logout') }}</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>
@endauth

@auth
@if($hasTenantContext || $isMainAdmin)
<!-- Enhanced Sidebar with Mobile Support -->
<aside id="sidebar" class="sidebar {{ app()->getLocale() === 'ar' ? 'rtl' : '' }}">
    <!-- Mobile Sidebar Header -->
    <div class="sidebar-header md:hidden">
        <div class="flex items-center space-x-3 {{ app()->getLocale() === 'ar' ? 'space-x-reverse' : '' }}">
            <img src="/WayUp.png" alt="{{ __('messages.app.name') }}" class="w-8 h-8 rounded-lg">
            <h2 class="font-bold text-lg">{{ __('messages.navigation.menu') }}</h2>
        </div>
        <button onclick="closeMobileSidebar()" class="p-2 hover:bg-gray-100 rounded-lg">
            <i class="ri-close-line text-xl"></i>
        </button>
    </div>

    <div class="sidebar-content">
        <nav class="space-y-1">
            @php
                $networkSlug = $networkSlug ?? auth()->user()?->network?->slug;
                $dashboardUrl = $isMainAdmin
                    ? ($networkSlug ? route('main-admin.dashboard', ['network' => $networkSlug]) : '#')
                    : ($hasTenantContext
                        ? (Auth::user()->role === 'admin'
                            ? safe_tenant_route('school.admin.dashboard', $school)
                            : safe_tenant_route('dashboard', $school))
                        : '#');
            @endphp
            <a href="{{ $dashboardUrl }}"
               class="sidebar-item {{ request()->routeIs('dashboard') || request()->routeIs('main-admin.dashboard') || request()->routeIs('school.admin.dashboard') ? 'active' : '' }}">
                <i class="ri-dashboard-3-line"></i>
                <span class="sidebar-text">{{ __('messages.navigation.dashboard') }}</span>
            </span></a>

            @if($isMainAdmin)
                <div class="sidebar-divider">
                    <div class="sidebar-divider-line"></div>
                    <div class="sidebar-divider-title">
                        {{ __('messages.main_admin.navigation.section') }}
                    </div>
                </div>

                <a href="{{ $networkSlug ? route('main-admin.users.index', ['network' => $networkSlug]) : '#' }}"
                   class="sidebar-item {{ request()->routeIs('main-admin.users.*') ? 'active' : '' }}">
                    <i class="ri-team-line"></i>
                    <span class="sidebar-text">{{ __('messages.main_admin.navigation.users') }}</span>
                </span></a>

                <a href="{{ $networkSlug ? route('main-admin.hierarchy', ['network' => $networkSlug]) : '#' }}"
                   class="sidebar-item {{ request()->routeIs('main-admin.hierarchy') ? 'active' : '' }}">
                    <i class="ri-git-branch-line"></i>
                    <span class="sidebar-text">{{ __('messages.main_admin.navigation.hierarchy') }}</span>
                </span></a>

                <a href="{{ $networkSlug ? route('main-admin.subjects-grades', ['network' => $networkSlug]) : '#' }}"
                   class="sidebar-item {{ request()->routeIs('main-admin.subjects-grades*') ? 'active' : '' }}">
                    <i class="ri-book-2-line"></i>
                    <span class="sidebar-text">{{ __('messages.main_admin.navigation.subjects_grades') }}</span>
                </span></a>
            @elseif($hasTenantContext && Auth::user()->role === 'admin')
                <a href="{{ safe_tenant_route('school.admin.users.index', $school) }}"
                   class="sidebar-item {{ request()->routeIs('school.admin.users.*') ? 'active' : '' }}">
                    <i class="ri-team-line"></i>
                    <span class="sidebar-text">{{ __('messages.navigation.manage_users') }}</span>
                </span></a>

                <a href="{{ safe_tenant_route('school.admin.file-browser.index', $school) }}"
                   class="sidebar-item {{ request()->routeIs('school.admin.file-browser.*') ? 'active' : '' }}">
                    <i class="ri-folder-3-line"></i>
                    <span class="sidebar-text">{{ __('messages.navigation.file_browser') }}</span>
                </span></a>

                <a href="{{ safe_tenant_route('school.admin.plans.index', $school) }}"
                   class="sidebar-item {{ request()->routeIs('school.admin.plans.*') ? 'active' : '' }}">
                    <i class="ri-calendar-check-line"></i>
                    <span class="sidebar-text">{{ __('messages.navigation.plans') }}</span>
                </span></a>

                <a href="{{ safe_tenant_route('school.admin.supervisors.index', $school) }}"
                   class="sidebar-item {{ request()->routeIs('school.admin.supervisors.*') ? 'active' : '' }}">
                    <i class="ri-user-star-line"></i>
                    <span class="sidebar-text">{{ __('messages.navigation.supervisors') }}</span>
                </span></a>

                <div class="sidebar-divider">
                    <div class="sidebar-divider-line"></div>
                    <div class="sidebar-divider-title">
                        {{ __('messages.navigation.content_management') }}
                    </div>
                </div>

                <a href="{{ safe_tenant_route('school.admin.subjects.index', $school) }}"
                   class="sidebar-item {{ request()->routeIs('school.admin.subjects.*') ? 'active' : '' }}">
                    <i class="ri-book-2-line"></i>
                    <span class="sidebar-text">{{ __('messages.navigation.subjects') }}</span>
                </span></a>

                <a href="{{ safe_tenant_route('school.admin.grades.index', $school) }}"
                   class="sidebar-item {{ request()->routeIs('school.admin.grades.*') ? 'active' : '' }}">
                    <i class="ri-graduation-cap-line"></i>
                    <span class="sidebar-text">{{ __('messages.navigation.grades') }}</span>
                </span></a>

            @elseif($hasTenantContext && Auth::user()->role === 'teacher')
                <a href="{{ safe_tenant_route('teacher.files.index', $school) }}"
                   class="sidebar-item {{ request()->routeIs('teacher.files.index') || request()->routeIs('teacher.files.show') ? 'active' : '' }}">
                    <i class="ri-file-list-3-line"></i>
                    <span class="sidebar-text">{{ __('messages.navigation.my_files') }}</span>
                </span></a>

                <a href="{{ safe_tenant_route('teacher.files.create', $school) }}"
                   class="sidebar-item {{ request()->routeIs('teacher.files.create') ? 'active' : '' }}">
                    <i class="ri-upload-cloud-2-line"></i>
                    <span class="sidebar-text">{{ __('messages.navigation.upload_file') }}</span>
                </span></a>

            @elseif($hasTenantContext && Auth::user()->role === 'supervisor')
                <a href="{{ safe_tenant_route('supervisor.reviews.index', $school) }}"
                   class="sidebar-item {{ request()->routeIs('supervisor.reviews.*') ? 'active' : '' }}">
                    <i class="ri-file-search-line"></i>
                    <span class="sidebar-text">{{ __('messages.navigation.review_files') }}</span>
                </span></a>

                <a href="{{ safe_tenant_route('supervisor.files.create', $school) }}"
                   class="sidebar-item {{ request()->routeIs('supervisor.files.create') ? 'active' : '' }}">
                    <i class="ri-upload-cloud-2-line"></i>
                    <span class="sidebar-text">{{ __('messages.navigation.upload_file') }}</span>
                </span></a>
            @endif
        </nav>

        <!-- Mobile Language Switcher (Moved here) -->
        <div class="mobile-language-switcher">
            <div class="language-switcher">
                <button onclick="toggleLanguageDropdown(event, 'languageDropdownMobile')"
                        class="language-btn"
                        type="button"
                        aria-label="{{ __('messages.ui.toggle_language') }}">
                    <div class="flex items-center gap-2">
                        @if(app()->getLocale() === 'ar')
                            <img src="https://flagcdn.com/w20/sa.png" alt="العربية" class="flag-icon">
                            <span>العربية</span>
                        @else
                            <img src="https://flagcdn.com/w20/us.png" alt="English" class="flag-icon">
                            <span>English</span>
                        @endif
                    </div>
                    <i class="ri-arrow-down-s-line text-lg"></i>
                </button>

                <div id="languageDropdownMobile" class="language-dropdown">
                    <!-- English Option -->
                    <form method="POST" action="{{ route('language.switch', ['locale' => 'en']) }}" class="w-full">
                        @csrf
                        @if($hasTenantContext)
                            <input type="hidden" name="network" value="{{ $networkSlug }}">
                            <input type="hidden" name="branch" value="{{ $schoolSlug }}">
                        @endif
                        <button type="submit"
                                class="language-option {{ app()->getLocale() === 'en' ? 'active' : '' }}">
                            <img src="https://flagcdn.com/w20/us.png" alt="English" class="flag-icon">
                            <span>English</span>
                            @if(app()->getLocale() === 'en')
                                <i class="ri-check-line {{ app()->getLocale() === 'ar' ? 'mr-auto' : 'ml-auto' }} text-blue-600"></i>
                            @endif
                        </button>
                    </form>

                    <!-- Arabic Option -->
                    <form method="POST" action="{{ route('language.switch', ['locale' => 'ar']) }}" class="w-full">
                        @csrf
                        @if($hasTenantContext)
                            <input type="hidden" name="network" value="{{ $networkSlug }}">
                            <input type="hidden" name="branch" value="{{ $schoolSlug }}">
                        @endif
                        <button type="submit"
                                class="language-option {{ app()->getLocale() === 'ar' ? 'active' : '' }}">
                            <img src="https://flagcdn.com/w20/sa.png" alt="العربية" class="flag-icon">
                            <span>العربية</span>
                            @if(app()->getLocale() === 'ar')
                                <i class="ri-check-line {{ app()->getLocale() === 'ar' ? 'mr-auto' : 'ml-auto' }} text-blue-600"></i>
                            @endif
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="sidebar-footer">
            <div class="sidebar-footer-text">by: Ajw</div>
        </div>
    </div>
</aside>
@endif
@endauth

<!-- Enhanced Main Content -->
<main class="main-content {{ app()->getLocale() === 'ar' ? 'rtl' : '' }}">
    <div class="p-6 md:p-8 max-w-7xl mx-auto">
        @yield('content')
    </div>
</main>

<script>
    // ========================================
    // UNIVERSAL PWA SERVICE WORKER - ALL BROWSERS
    // ========================================
    let deferredPrompt;
    let isInstalled = false;

    if ('serviceWorker' in navigator) {
        window.addEventListener('load', () => {
            navigator.serviceWorker.register('/sw.js')
                .then(registration => {
                    console.log('✅ Service Worker registered:', registration.scope);

                    setInterval(() => {
                        registration.update();
                    }, 60000);

                    registration.addEventListener('updatefound', () => {
                        const newWorker = registration.installing;
                        newWorker.addEventListener('statechange', () => {
                            if (newWorker.state === 'installed' && navigator.serviceWorker.controller) {
                                console.log('🔄 New version available!');
                                setTimeout(() => {
                                    newWorker.postMessage({ type: 'SKIP_WAITING' });
                                    window.location.reload();
                                }, 3000);
                            }
                        });
                    });
                })
                .catch(err => console.error('❌ Service Worker registration failed:', err));
        });
    }

    function detectBrowserAndPlatform() {
        const ua = navigator.userAgent.toLowerCase();
        const standalone = window.matchMedia('(display-mode: standalone)').matches || window.navigator.standalone;

        return {
            isIOS: /iphone|ipad|ipod/.test(ua),
            isSafari: /safari/.test(ua) && !/chrome/.test(ua),
            isChrome: /chrome/.test(ua) && !/edge/.test(ua),
            isEdge: /edge/.test(ua),
            isBrave: navigator.brave !== undefined,
            isFirefox: /firefox/.test(ua),
            isOpera: /opr/.test(ua) || /opera/.test(ua),
            isStandalone: standalone,
            isMobile: /mobile/.test(ua)
        };
    }

    window.addEventListener('DOMContentLoaded', () => {
        const browser = detectBrowserAndPlatform();

        if (browser.isStandalone) {
            isInstalled = true;
            console.log('✅ App is already installed');
            return;
        }

        if (browser.isIOS && browser.isSafari) {
            setTimeout(() => {
                if (!localStorage.getItem('iosBannerDismissed')) {
                    document.getElementById('iosInstallBanner').classList.add('show');
                }
            }, 2000);
        }

        window.addEventListener('beforeinstallprompt', (e) => {
            console.log('✅ Install prompt captured');
            e.preventDefault();
            deferredPrompt = e;

            if (!localStorage.getItem('pwaBannerDismissed')) {
                setTimeout(() => {
                    showInstallBanner();
                }, 2000);
            }
        });

        window.addEventListener('appinstalled', () => {
            console.log('✅ PWA installed successfully');
            isInstalled = true;
            hideInstallBanner();
            hideIOSBanner();
            localStorage.setItem('pwaInstalled', 'true');
        });
    });

    function showInstallBanner() {
        const banner = document.getElementById('pwaInstallBanner');
        if (banner && deferredPrompt && !isInstalled) {
            banner.classList.add('show');
        }
    }

    function hideInstallBanner() {
        const banner = document.getElementById('pwaInstallBanner');
        if (banner) {
            banner.classList.remove('show');
        }
    }

    function dismissInstallBanner() {
        hideInstallBanner();
        localStorage.setItem('pwaBannerDismissed', 'true');
        deferredPrompt = null;
    }

    function hideIOSBanner() {
        const banner = document.getElementById('iosInstallBanner');
        if (banner) {
            banner.classList.remove('show');
        }
    }

    function dismissIOSBanner() {
        hideIOSBanner();
        localStorage.setItem('iosBannerDismissed', 'true');
    }

    document.addEventListener('DOMContentLoaded', () => {
        const installBtn = document.getElementById('pwaInstallBtn');
        if (installBtn) {
            installBtn.addEventListener('click', async () => {
                if (!deferredPrompt) {
                    alert('To install: Click the menu (⋮) and select "Install app"');
                    return;
                }

                deferredPrompt.prompt();
                const { outcome } = await deferredPrompt.userChoice;

                if (outcome === 'accepted') {
                    hideInstallBanner();
                }

                deferredPrompt = null;
            });
        }
    });

    window.addEventListener('online', () => {
        document.getElementById('offlineIndicator').classList.remove('show');
    });

    window.addEventListener('offline', () => {
        document.getElementById('offlineIndicator').classList.add('show');
    });

    if (!navigator.onLine) {
        document.getElementById('offlineIndicator').classList.add('show');
    }

    // ========================================
    // SIDEBAR INITIALIZATION
    // ========================================
    function initializeSidebar() {
        const sidebar = document.getElementById('sidebar');
        const main = document.querySelector('.main-content');
        const savedState = localStorage.getItem('sidebarExpanded');
        const isDesktop = window.innerWidth > 768;
        const isRTL = getComputedStyle(document.documentElement).direction === 'rtl';

        if (isDesktop) {
            const expanded = savedState !== 'false';
            sidebar.classList.toggle('expanded', expanded);

            if (isRTL) {
                main.style.marginRight = expanded ? '280px' : '80px';
                main.style.marginLeft = '0px';
            } else {
                main.style.marginLeft = expanded ? '280px' : '80px';
                main.style.marginRight = '0px';
            }
        } else {
            sidebar.classList.remove('expanded');
            main.style.marginLeft = '0px';
            main.style.marginRight = '0px';
        }
    }

    // ========================================
    // TOGGLE SIDEBAR (Desktop)
    // ========================================
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const main = document.querySelector('.main-content');
        const isRTL = getComputedStyle(document.documentElement).direction === 'rtl';
        const expanded = sidebar.classList.contains('expanded');

        // Set start/end values
        const startWidth = expanded ? 280 : 80;
        const endWidth = expanded ? 80 : 280;

        const startMargin = expanded ? 280 : 80;
        const endMargin = expanded ? 80 : 280;

        const duration = 200; // ms
        const startTime = performance.now();

        sidebar.classList.toggle('expanded', !expanded);
        localStorage.setItem('sidebarExpanded', (!expanded).toString());

        function animate(time) {
            const progress = Math.min((time - startTime) / duration, 1);
            const ease = 1 - Math.pow(1 - progress, 3); // cubic ease-out

            const currentWidth = startWidth + (endWidth - startWidth) * ease;
            const currentMargin = startMargin + (endMargin - startMargin) * ease;

            sidebar.style.width = currentWidth + 'px';

            if (isRTL) {
                main.style.marginRight = currentMargin + 'px';
                main.style.marginLeft = '0px';
            } else {
                main.style.marginLeft = currentMargin + 'px';
                main.style.marginRight = '0px';
            }

            if (progress < 1) {
                requestAnimationFrame(animate);
            } else {
                // Cleanup inline styles
                sidebar.style.width = '';
                main.style.marginLeft = '';
                main.style.marginRight = '';
            }
        }

        requestAnimationFrame(animate);
    }

    // ========================================
    // MOBILE SIDEBAR & DROPDOWN LOGIC
    // ========================================
    function toggleMobileSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('mobileOverlay');

        sidebar.classList.toggle('mobile-open');
        overlay.classList.toggle('show');

        document.body.classList.toggle('sidebar-open');
    }

    function closeMobileSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('mobileOverlay');

        sidebar.classList.remove('mobile-open');
        overlay.classList.remove('show');
        document.body.classList.remove('sidebar-open');
    }

    function toggleLanguageDropdown(event, dropdownId) {
        event.stopPropagation();
        const dropdown = document.getElementById(dropdownId);
        if (!dropdown) return;

        // Close the other dropdown if it exists
        const otherDropdownId = dropdownId === 'languageDropdown' ? 'languageDropdownMobile' : 'languageDropdown';
        const otherDropdown = document.getElementById(otherDropdownId);
        if (otherDropdown) {
            otherDropdown.style.display = 'none';
            otherDropdown.classList.remove('show');
        }

        // Close user menu
        document.getElementById('userMenuDropdown').style.display = 'none';

        const isCurrentlyOpen = dropdown.classList.contains('show');
        if (isCurrentlyOpen) {
            dropdown.style.display = 'none';
            dropdown.classList.remove('show');
        } else {
            dropdown.style.display = 'block';
            // Apply 'show' class for animations (desktop) or just visibility
            setTimeout(() => dropdown.classList.add('show'), 10);
        }
    }

    function toggleUserMenu(event) {
        event.stopPropagation();
        const dropdown = document.getElementById('userMenuDropdown');
        if (!dropdown) return;

        // Close language dropdowns
        const langDropdown = document.getElementById('languageDropdown');
        if (langDropdown) {
            langDropdown.style.display = 'none';
            langDropdown.classList.remove('show');
        }
        const langDropdownMobile = document.getElementById('languageDropdownMobile');
        if (langDropdownMobile) {
            langDropdownMobile.style.display = 'none';
            langDropdownMobile.classList.remove('show');
        }

        const isCurrentlyOpen = dropdown.style.display === 'block';
        dropdown.style.display = isCurrentlyOpen ? 'none' : 'block';
    }

    document.addEventListener('DOMContentLoaded', function () {
        initializeSidebar();

        // Global click listener to close dropdowns
        document.addEventListener('click', function (event) {
            const langDropdown = document.getElementById('languageDropdown');
            const langDropdownMobile = document.getElementById('languageDropdownMobile');
            const langSwitcher = event.target.closest('.language-switcher');
            
            if (!langSwitcher) {
                if (langDropdown) {
                    langDropdown.style.display = 'none';
                    langDropdown.classList.remove('show');
                }
                if (langDropdownMobile) {
                    langDropdownMobile.style.display = 'none';
                    langDropdownMobile.classList.remove('show');
                }
            }

            const userMenuDropdown = document.getElementById('userMenuDropdown');
            const userMenuButton = event.target.closest('[onclick*="toggleUserMenu"]');
            if (!userMenuButton && userMenuDropdown && !userMenuDropdown.contains(event.target)) {
                userMenuDropdown.style.display = 'none';
            }
        });

        // Resize handler
        window.addEventListener('resize', function () {
            if (window.innerWidth <= 768) {
                closeMobileSidebar();
            } else {
                initializeSidebar();
            }
        });

        // Auto-hide alerts
        setTimeout(function () {
            document.querySelectorAll('.alert').forEach(function (alert) {
                alert.style.opacity = '0';
                setTimeout(() => alert.style.display = 'none', 300);
            });
        }, 5000);
    });

    // ========================================
    // FILE PREVIEW LOGIC (Unchanged)
    // ========================================
    function handlePreviewClick(event, schoolSlug, fileId) {
        event.preventDefault();
        const button = event.currentTarget;
        const originalHTML = button.innerHTML;
        button.innerHTML = '<i class="ri-loader-4-line animate-spin text-lg"></i>';
        button.disabled = true;

        fetch(`/${networkSlug}/${schoolSlug}/admin/file-browser/${fileId}/preview-data`)
            .then(response => response.json())
            .then(data => {
                button.innerHTML = originalHTML;
                button.disabled = false;

                if (data.isPreviewable) {
                    window.open(data.url, '_blank');
                } else {
                    showPreviewModal({
                        title: data.title,
                        filename: data.filename,
                        extension: data.extension,
                        size: data.size,
                        downloadUrl: data.downloadUrl
                    });
                }
            })
            .catch(error => {
                console.error('Error fetching file data:', error);
                button.innerHTML = originalHTML;
                button.disabled = false;

                @if(app()->getLocale() === 'ar')
                alert('حدث خطأ أثناء تحميل معلومات الملف');
                @else
                alert('Error loading file information');
                @endif
            });
    }

    const networkSlug = '{{ $networkSlug }}';

    function handleTeacherPreviewClick(event, schoolSlug, fileId) {
        event.preventDefault();

        const button = event.currentTarget;
        const originalHTML = button.innerHTML;
        button.innerHTML = '<i class="ri-loader-4-line animate-spin text-lg"></i>';
        button.disabled = true;

        const filename = button.getAttribute('data-filename');
        const title = button.getAttribute('data-title');
        const size = button.getAttribute('data-size');
        const extension = filename.split('.').pop().toLowerCase();
        const downloadUrl = `/${networkSlug}/${schoolSlug}/teacher/files/${fileId}/download`;
        const previewUrl = `/${networkSlug}/${schoolSlug}/teacher/files/${fileId}/preview`;
        const previewableExtensions = ['pdf', 'jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg', 'webp', 'txt'];
        const isPreviewable = previewableExtensions.includes(extension);

        button.innerHTML = originalHTML;
        button.disabled = false;

        if (isPreviewable) {
            window.open(previewUrl, '_blank');
        } else {
            showPreviewModal({
                title: title,
                filename: filename,
                extension: extension,
                size: size,
                downloadUrl: downloadUrl
            });
        }
    }

    function handleSupervisorPreviewClick(event, schoolSlug, fileId) {
        event.preventDefault();

        const button = event.currentTarget;
        const originalHTML = button.innerHTML;
        button.innerHTML = '<i class="ri-loader-4-line animate-spin text-lg"></i>';
        button.disabled = true;

        const filename = button.getAttribute('data-filename');
        const title = button.getAttribute('data-title');
        const size = button.getAttribute('data-size');
        const extension = filename.split('.').pop().toLowerCase();
        const downloadUrl = `/${networkSlug}/${schoolSlug}/supervisor/review-files/${fileId}/download`;
        const previewUrl = `/${networkSlug}/${schoolSlug}/supervisor/review-files/${fileId}/preview`;
        const previewableExtensions = ['pdf', 'jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg', 'webp', 'txt'];
        const isPreviewable = previewableExtensions.includes(extension);

        button.innerHTML = originalHTML;
        button.disabled = false;

        if (isPreviewable) {
            window.open(previewUrl, '_blank');
        } else {
            showPreviewModal({
                title: title,
                filename: filename,
                extension: extension,
                size: size,
                downloadUrl: downloadUrl
            });
        }
    }

    window.addEventListener('beforeunload', function () {
        const sidebar = document.getElementById('sidebar');
        if (sidebar && window.innerWidth > 768) {
            const isExpanded = sidebar.classList.contains('expanded');
            localStorage.setItem('sidebarExpanded', isExpanded.toString());
        }
    });
</script>


@stack('scripts')

@endauth

</body>
</html>
