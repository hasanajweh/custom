<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}" class="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    @php
        $activeContextSchool = \App\Services\ActiveContext::getSchool();
        $activeContextRole = \App\Services\ActiveContext::getRole();

        $school = $school
            ?? request()->attributes->get('branch')
            ?? request()->attributes->get('school')
            ?? $activeContextSchool
            ?? (auth()->check() && auth()->user()?->school ? auth()->user()->school : null);

        $branch = $branch ?? request()->route('branch') ?? $school;
        $network = $network ?? request()->route('network') ?? $school?->network ?? (auth()->check() ? auth()->user()?->network : null);

        $currentSchool = $activeContextSchool ?? $school;
        $currentRole = $activeContextRole;

        $availableContexts = auth()->check() ? auth()->user()?->availableContexts() : collect();

        $schoolName = $school?->name ?? config('app.name');
        $schoolSlug = $school?->slug ?? '';
        $networkSlug = $network?->slug ?? $school?->network?->slug ?? (auth()->check() ? auth()->user()?->network?->slug : '');
        $isMainAdmin = $isMainAdmin ?? (bool) (auth()->check() ? auth()->user()?->is_main_admin : false);
        $hasTenantContext = $school && $school->network;
    @endphp
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
    <link rel="manifest" href="{{ url('/manifest.json') }}">

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


    @if(app()->getLocale() === 'ar')
        <link rel="stylesheet" href="/css/rtl.css">
    @endif


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

        html.rtl {
            direction: rtl;
        }

        html.ltr {
            direction: ltr;
        }

        .rtl * {
            font-family: 'Cairo', 'Rubik', 'Segoe UI', system-ui, sans-serif;
            letter-spacing: 0.02em;
            line-height: 1.8;
        }

        .ltr * {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            letter-spacing: normal;
            line-height: 1.6;
        }

        .font-heading {
            font-weight: 700;
        }

        .rtl .font-heading {
            font-family: 'Cairo', 'Rubik', sans-serif;
            letter-spacing: 0;
            line-height: 1.4;
        }

        .ltr .font-heading {
            font-family: 'Inter', sans-serif;
            letter-spacing: -0.02em;
            line-height: 1.3;
        }

        * {
            text-rendering: optimizeLegibility;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

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


        @supports (padding: max(0px)) {
            body {
                padding-left: max(0px, env(safe-area-inset-left));
                padding-right: max(0px, env(safe-area-inset-right));
            }

            .navbar {
                padding-top: max(0px, env(safe-area-inset-top));
            }
        }


        .brand-text {
            display: flex;
            flex-direction: column;
            line-height: 1.2;
        }

        .rtl .brand-text {
            align-items: flex-end;
        }

        .ltr .brand-text {
            align-items: flex-start;
        }

        .brand-name {
            font-size: 20px;
            font-weight: 800;
            color: var(--primary);
            line-height: 1.1;
            margin-bottom: 3px;
        }

        .rtl .brand-name {
            letter-spacing: 0;
        }

        .ltr .brand-name {
            letter-spacing: -0.025em;
        }

        .language-dropdown {
            position: absolute;
            top: calc(100% + 8px);
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

        .rtl .language-dropdown {
            left: 0;
            right: auto;
        }

        .ltr .language-dropdown {
            right: 0;
            left: auto;
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
            font-weight: 600;
            font-size: 14px;
            transition: all 0.2s ease;
            border-bottom: 1px solid #F3F4F6;
        }

        .rtl .language-option {
            text-align: right;
        }

        .ltr .language-option {
            text-align: left;
        }

        .language-option:last-child {
            border-bottom: none;
        }

        .language-option:hover {
            background-color: #F9FAFB !important;
            color: #667eea !important;
        }

        .rtl .language-option:hover {
            padding-right: 20px;
        }

        .ltr .language-option:hover {
            padding-left: 20px;
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
            top: var(--navbar-height);
            height: calc(100vh - var(--navbar-height));
            width: var(--sidebar-collapsed);
            background: #FFFFFF;
            z-index: 30;
            display: flex;
            flex-direction: column;
            transition: none; /* JS handles animation */
        }

        .rtl .sidebar {
            right: 0;
            left: auto;
        }

        .ltr .sidebar {
            left: 0;
            right: auto;
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
            white-space: nowrap;
        }

        .rtl .sidebar-item {
            font-size: 15px;
        }

        .ltr .sidebar-item {
            font-size: 14px;
        }

        .sidebar-item:hover {
            background: #F8FAFC;
            color: var(--primary);
        }

        .sidebar-item.active {
            color: var(--primary);
            font-weight: 700;
            background: linear-gradient(to right, rgba(99, 102, 241, 0.1), rgba(139, 92, 246, 0.05));
        }

        .rtl .sidebar-item.active {
            border-right: 4px solid var(--primary);
            padding-right: 8px;
        }

        .ltr .sidebar-item.active {
            border-left: 4px solid var(--primary);
            padding-left: 8px;
        }

        .sidebar-item i {
            min-width: 24px;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            flex-shrink: 0;
        }

        .rtl .sidebar-item i {
            margin-left: 12px;
        }

        .ltr .sidebar-item i {
            margin-right: 12px;
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
            margin-left: 0;
            margin-right: 0;
        }

        .sidebar-text {
            white-space: nowrap;
            overflow: hidden;
            max-width: 0;
            opacity: 0;
            color: #374151;
        }

        .rtl .sidebar-text {
            font-size: 15px;
        }

        .ltr .sidebar-text {
            font-size: 14px;
        }

        .sidebar.expanded .sidebar-text {
            max-width: 200px;
            opacity: 1;
        }

        .rtl .sidebar.expanded .sidebar-text {
            margin-right: 0;
        }

        .ltr .sidebar.expanded .sidebar-text {
            margin-left: 0;
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
            min-height: calc(100vh - var(--navbar-height));
            padding-top: var(--navbar-height);
            background: #FAFBFC;
            will-change: width, margin;
        }

        .rtl .main-content {
            margin-right: var(--sidebar-collapsed);
            margin-left: 0;
        }

        .ltr .main-content {
            margin-left: var(--sidebar-collapsed);
            margin-right: 0;
        }

        .rtl .sidebar.expanded ~ .main-content {
            margin-right: var(--sidebar-width);
            margin-left: 0;
        }

        .ltr .sidebar.expanded ~ .main-content {
            margin-left: var(--sidebar-width);
            margin-right: 0;
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
            line-height: 1.2;
        }

        .brand-name {
            font-size: 20px;
            font-weight: 800;
            color: var(--primary);
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
            white-space: nowrap;
        }

        .sidebar-item:hover {
            background: #F8FAFC;
            color: var(--primary);
        }

        .sidebar-item.active {
            color: var(--primary);
            font-weight: 700;
            background: linear-gradient(to right, rgba(99, 102, 241, 0.1), rgba(139, 92, 246, 0.05));
        }

        .sidebar-item i {
            min-width: 24px;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
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

        .sidebar-text {
            white-space: nowrap;
            overflow: hidden;
            max-width: 0;
            opacity: 0;
            color: #374151;
        }

        .sidebar.expanded .sidebar-text {
            max-width: 200px;
            opacity: 1;
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
            min-height: calc(100vh - var(--navbar-height));
            padding-top: var(--navbar-height);
            background: #FAFBFC;
            will-change: width, margin;
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

        /* Prevent body scroll when sidebar open */
        body.sidebar-open {
            overflow: hidden;
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
            border-right: 1px solid #E5E7EB;
        }

        table.table-bordered thead th:last-child,
        table.table-bordered tbody td:last-child {
            border-right: none;
        }

        .rtl table.table-bordered thead th,
        .rtl table.table-bordered tbody td {
            border-right: none;
            border-left: 1px solid #E5E7EB;
        }

        .rtl table.table-bordered thead th:last-child,
        .rtl table.table-bordered tbody td:last-child {
            border-left: none;
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
            justify-content: flex-end;
            align-items: center;
        }

        .rtl table td.actions-col {
            justify-content: flex-start;
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

<!-- Enhanced Navigation -->
<nav class="navbar fixed w-full top-0 z-50">
    <div class="px-6 sm:px-8 lg:px-10 h-full">
        <div class="flex justify-between items-center h-full">
            <div class="flex items-center">
                <!-- Sidebar Toggle -->
                <button onclick="toggleSidebar()"
                        class="p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg"
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
                <!-- Beautiful Language Switcher -->
                <div class="relative" x-data="{ open: false }">
                    <button 
                        @click="open = !open"
                        @click.away="open = false"
                        type="button"
                        class="flex items-center gap-2 px-3 py-2 rounded-xl bg-gradient-to-r from-indigo-50 to-purple-50 hover:from-indigo-100 hover:to-purple-100 border border-indigo-100 transition-all duration-200 group"
                    >
                        <span class="text-lg">{{ app()->getLocale() === 'ar' ? '🇸🇦' : '🇬🇧' }}</span>
                        <span class="text-sm font-medium text-gray-700 group-hover:text-indigo-700">
                            {{ app()->getLocale() === 'ar' ? 'العربية' : 'English' }}
                        </span>
                        <i class="ri-arrow-down-s-line text-gray-400 group-hover:text-indigo-500 transition-transform duration-200" :class="{ 'rotate-180': open }"></i>
                    </button>
                    
                    <div 
                        x-show="open"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 scale-95"
                        x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 scale-100"
                        x-transition:leave-end="opacity-0 scale-95"
                        class="absolute {{ app()->getLocale() === 'ar' ? 'left-0' : 'right-0' }} mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden z-50"
                        style="display: none;"
                    >
                        <button
                            onclick="switchLocale('ar')"
                            class="w-full flex items-center gap-3 px-4 py-3 text-sm hover:bg-gradient-to-r hover:from-indigo-50 hover:to-purple-50 transition-colors {{ app()->getLocale() === 'ar' ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-gray-700' }}"
                        >
                            <span class="text-xl">🇸🇦</span>
                            <span>العربية</span>
                            @if(app()->getLocale() === 'ar')
                                <i class="ri-check-line {{ app()->getLocale() === 'ar' ? 'mr-auto' : 'ml-auto' }} text-indigo-600"></i>
                            @endif
                        </button>
                        <button
                            onclick="switchLocale('en')"
                            class="w-full flex items-center gap-3 px-4 py-3 text-sm hover:bg-gradient-to-r hover:from-indigo-50 hover:to-purple-50 transition-colors {{ app()->getLocale() === 'en' ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-gray-700' }}"
                        >
                            <span class="text-xl">🇬🇧</span>
                            <span>English</span>
                            @if(app()->getLocale() === 'en')
                                <i class="ri-check-line {{ app()->getLocale() === 'ar' ? 'mr-auto' : 'ml-auto' }} text-indigo-600"></i>
                            @endif
                        </button>
                    </div>
                </div>

                <!-- Enhanced User Dropdown -->
                <div class="relative">
                    <button onclick="toggleUserMenu(event)"
                            class="flex items-center space-x-3 p-2 hover:bg-gray-100 rounded-lg {{ app()->getLocale() === 'ar' ? 'space-x-reverse' : '' }}"
                            aria-label="{{ __('messages.navigation.open_user_menu') }}">
                        @php
                            $role = strtolower($currentRole ?? '');
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
                            <p class="text-xs text-gray-500 font-medium">{{ __('messages.roles.' . $role) }}</p>
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
                                    <p class="text-sm text-gray-600 font-semibold">{{ __('messages.roles.' . $role) }}</p>
                                    <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>
                                </div>
                            </div>
                        </div>

                        @if(isset($availableContexts) && $availableContexts->count() > 1)
                        <div class="border-t border-gray-100 my-2"></div>
                        <div class="px-3 py-2">
                            <label class="flex items-center gap-2 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">
                                <i class="ri-swap-line"></i>
                                @lang('messages.switch_context')
                            </label>
                            <div class="space-y-1">
                                @foreach($availableContexts as $schoolCtx)
                                    @php
                                        $isCurrent = session('active_school_id') == $schoolCtx->school->id && session('active_role') == $schoolCtx->role;
                                        $roleColors = [
                                            'admin' => 'bg-purple-100 text-purple-700',
                                            'teacher' => 'bg-blue-100 text-blue-700',
                                            'supervisor' => 'bg-green-100 text-green-700',
                                        ];
                                        $roleColor = $roleColors[$schoolCtx->role] ?? 'bg-gray-100 text-gray-700';
                                    @endphp
                                    <form method="POST"
                                          action="{{ route('switch-context') }}"
                                          class="w-full"
                                          onsubmit="return true;">
                                        @csrf
                                        <input type="hidden" name="school_id" value="{{ $schoolCtx->school->id }}">
                                        <input type="hidden" name="role" value="{{ $schoolCtx->role }}">

                                        <button type="submit"
                                                class="flex items-center justify-between w-full px-3 py-2 rounded-lg text-sm transition-all duration-150 {{ $isCurrent ? 'bg-primary/10 border border-primary/30' : 'hover:bg-gray-50' }}">
                                            <div class="flex items-center gap-2 min-w-0">
                                                @if($isCurrent)
                                                    <i class="ri-checkbox-circle-fill text-green-500 flex-shrink-0"></i>
                                                @else
                                                    <i class="ri-circle-line text-gray-300 flex-shrink-0"></i>
                                                @endif
                                                <span class="truncate font-medium {{ $isCurrent ? 'text-primary' : 'text-gray-700' }}">
                                                    {{ $schoolCtx->school->name }}
                                                </span>
                                            </div>
                                            <span class="flex-shrink-0 px-2 py-0.5 text-xs font-medium rounded-full {{ $roleColor }}">
                                                {{ __('messages.roles.' . $schoolCtx->role) }}
                                            </span>
                                        </button>
                                    </form>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <!-- Menu Items -->
                        <div class="py-1">
                            @php
                                $networkSlug = auth()->user()?->network?->slug;
                                if ($isMainAdmin) {
                                    $profileUrl = $networkSlug ? route('main-admin.dashboard', ['network' => $networkSlug]) : '#';
                                    $logoutUrl = $networkSlug ? route('main-admin.logout', ['network' => $networkSlug]) : '#';
                                } else {
                                    try {
                                        $profileUrl = $hasTenantContext && $school ? tenant_route('profile.edit', $school) : '#';
                                        $logoutUrl = $hasTenantContext && $school ? tenant_route('logout', $school) : '#';
                                    } catch (\Exception $e) {
                                        $profileUrl = '#';
                                        $logoutUrl = '#';
                                    }
                                }
                            @endphp
                            <a href="{{ $profileUrl }}"
                               class="dropdown-item">
                                <i class="ri-user-line text-gray-500 {{ app()->getLocale() === 'ar' ? 'ml-3' : 'mr-3' }}"></i>
                                <span>{{ __('messages.navigation.profile') }}</span>
                            </a>

                            @if($currentRole === 'admin' && $school)
                                @php
                                    try {
                                        $activityLogsUrl = tenant_route('school.admin.activity-logs.index', $school);
                                    } catch (\Exception $e) {
                                        $activityLogsUrl = '#';
                                    }
                                @endphp
                                <a href="{{ $activityLogsUrl }}"
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

@if($hasTenantContext || $isMainAdmin)
<!-- Enhanced Sidebar -->
<aside id="sidebar" class="sidebar {{ app()->getLocale() === 'ar' ? 'rtl' : '' }}">
    <div class="sidebar-content">
        @php($activeSidebarRole = $currentRole)

        @if($isMainAdmin)
            @include('layouts.partials.admin-sidebar')
        @elseif($hasTenantContext && $activeSidebarRole === 'admin')
            @include('layouts.partials.admin-sidebar')
        @elseif($hasTenantContext && $activeSidebarRole === 'teacher')
            @include('layouts.partials.teacher-sidebar')
        @elseif($hasTenantContext && $activeSidebarRole === 'supervisor')
            @include('layouts.partials.supervisor-sidebar')
        @endif

        <div class="sidebar-footer">
            <div class="sidebar-footer-text">by: Ajw</div>
        </div>
    </div>
</aside>
@endif

<!-- Enhanced Main Content -->
<main class="main-content {{ app()->getLocale() === 'ar' ? 'rtl' : '' }}">
    <div class="p-6 md:p-8 max-w-7xl mx-auto">
        @if(Auth::check() && Auth::user()->isMainAdmin() && $currentRole === 'admin' && $activeContextSchool)
            <!-- Main Admin Viewing Banner -->
            <div class="bg-gradient-to-r from-yellow-400 to-orange-500 rounded-xl p-4 text-white shadow-lg mb-6">
                <div class="flex items-center justify-between flex-wrap gap-3">
                    <div class="flex items-center gap-3">
                        <i class="ri-eye-line text-2xl"></i>
                        <div>
                            <p class="font-semibold">{{ __('messages.main_admin.viewing_as_admin') }}</p>
                            <p class="text-sm text-yellow-100">{{ $activeContextSchool->name }}</p>
                        </div>
                    </div>
                    <a href="{{ route('main-admin.hierarchy', ['network' => ($network->slug ?? $activeContextSchool->network->slug)]) }}" 
                       class="inline-flex items-center gap-2 px-4 py-2 bg-white/20 hover:bg-white/30 backdrop-blur-sm rounded-lg font-medium transition-all duration-200 whitespace-nowrap">
                        <i class="ri-arrow-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }}-line"></i>
                        {{ __('messages.main_admin.back_to_dashboard') }}
                    </a>
                </div>
            </div>
        @endif
        @yield('content')
    </div>
</main>

<script>
function switchLocale(locale) {
    fetch("{{ route('locale.update') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}",
            "Accept": "application/json"
        },
        body: JSON.stringify({ locale: locale }),
        credentials: 'same-origin'
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        // Wait a moment to ensure session is saved
        setTimeout(() => {
            window.location.reload();
        }, 100);
    })
    .catch(error => {
        console.error('Language switch error:', error);
        // Fallback: reload anyway after a delay
        setTimeout(() => {
            window.location.reload();
        }, 100);
    });
}

    // ========================================
    // SIDEBAR INITIALIZATION
    // ========================================
    function initializeSidebar() {
        const sidebar = document.getElementById('sidebar');
        const main = document.querySelector('.main-content');
        const savedState = localStorage.getItem('sidebarExpanded');
        const isRTL = getComputedStyle(document.documentElement).direction === 'rtl';

        const expanded = savedState !== 'false';
        sidebar.classList.toggle('expanded', expanded);

        if (isRTL) {
            main.style.marginRight = expanded ? '280px' : '80px';
            main.style.marginLeft = '0px';
        } else {
            main.style.marginLeft = expanded ? '280px' : '80px';
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

    function toggleLanguageDropdown(event, dropdownId) {
        event.stopPropagation();
        const dropdown = document.getElementById(dropdownId);
        if (!dropdown) return;

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

        const isCurrentlyOpen = dropdown.style.display === 'block';
        dropdown.style.display = isCurrentlyOpen ? 'none' : 'block';
    }

    document.addEventListener('DOMContentLoaded', function () {
        initializeSidebar();

        // Global click listener to close dropdowns
        document.addEventListener('click', function (event) {
            const langDropdown = document.getElementById('languageDropdown');
            const langSwitcher = event.target.closest('.language-switcher');

            if (!langSwitcher) {
                if (langDropdown) {
                    langDropdown.style.display = 'none';
                    langDropdown.classList.remove('show');
                }
            }

            const userMenuDropdown = document.getElementById('userMenuDropdown');
            const userMenuButton = event.target.closest('[onclick*="toggleUserMenu"]');
            if (!userMenuButton && userMenuDropdown && !userMenuDropdown.contains(event.target)) {
                userMenuDropdown.style.display = 'none';
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
    @php
        $previewUrl = '#';
        if ($school && $school->network) {
            try {
                $previewUrl = tenant_route('school.admin.file-browser.preview-data', [$school, '__FILE_ID__']);
            } catch (\Exception $e) {
                \Log::warning('Failed to generate preview URL', ['error' => $e->getMessage()]);
            }
        }
    @endphp
    const previewDataUrlTemplate = @json($previewUrl);
    
    // Show preview modal for non-previewable files
    function showPreviewModal(data) {
        const modal = document.createElement('div');
        modal.id = 'previewModal';
        modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
        modal.innerHTML = `
            <div class="bg-white rounded-xl p-6 max-w-md w-full mx-4 shadow-2xl">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-900">${data.title || 'File Preview'}</h3>
                    <button onclick="this.closest('#previewModal').remove()" class="text-gray-400 hover:text-gray-600">
                        <i class="ri-close-line text-2xl"></i>
                    </button>
                </div>
                <div class="mb-4">
                    <p class="text-sm text-gray-600 mb-2"><strong>${data.filename || 'File'}</strong></p>
                    <p class="text-xs text-gray-500">Size: ${data.size || 'Unknown'}</p>
                    <p class="text-xs text-gray-500 mt-2">This file type (${data.extension?.toUpperCase() || 'UNKNOWN'}) cannot be previewed in the browser. Please download it to view.</p>
                </div>
                <div class="flex gap-3 justify-end">
                    <button onclick="this.closest('#previewModal').remove()" class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">
                        {{ __('messages.actions.close') }}
                    </button>
                    <a href="${data.downloadUrl || '#'}" class="px-4 py-2 text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                        <i class="ri-download-line"></i> {{ __('messages.files.download_file') }}
                    </a>
                </div>
            </div>
        `;
        document.body.appendChild(modal);
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                modal.remove();
            }
        });
    }

    function handlePreviewClick(event, schoolSlug, fileId) {
        event.preventDefault();
        const button = event.currentTarget;
        const originalHTML = button.innerHTML;
        button.innerHTML = '<i class="ri-loader-4-line animate-spin text-lg"></i>';
        button.disabled = true;

        const previewDataUrl = previewDataUrlTemplate.replace('__FILE_ID__', fileId);

        fetch(previewDataUrl)
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

                const errorMessage = @json(app()->getLocale() === 'ar'
                    ? 'حدث خطأ أثناء تحميل معلومات الملف'
                    : 'Error loading file information'
                );
                alert(errorMessage);
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
        if (sidebar) {
            const isExpanded = sidebar.classList.contains('expanded');
            localStorage.setItem('sidebarExpanded', isExpanded.toString());
        }
    });

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
        const indicator = document.getElementById('offlineIndicator');
        if (indicator) {
            indicator.classList.remove('show');
        }
    });

    window.addEventListener('offline', () => {
        const indicator = document.getElementById('offlineIndicator');
        if (indicator) {
            indicator.classList.add('show');
        }
    });

    if (!navigator.onLine) {
        const indicator = document.getElementById('offlineIndicator');
        if (indicator) {
            indicator.classList.add('show');
        }
    }
</script>

@stack('scripts')

</body>
</html>