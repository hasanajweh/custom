<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5">
    <meta name="description" content="{{ app()->getLocale() === 'ar' ? 'سكولدر - منصة إدارة الملفات السحابية الشاملة للمؤسسات التعليمية' : 'Scholder - Comprehensive Cloud File Management Platform for Educational Institutions' }}">
    <meta property="og:title" content="{{ app()->getLocale() === 'ar' ? 'سكولدر - منصة التعليم الرقمي' : 'Scholder - Digital Education Platform' }}">
    <meta property="og:description" content="{{ app()->getLocale() === 'ar' ? 'منصة متعددة المستأجرين لإدارة المدارس والشبكات التعليمية' : 'Multi-tenant platform for managing schools and educational networks' }}">
    <meta property="og:type" content="website">
    <title>{{ app()->getLocale() === 'ar' ? 'سكولدر - منصة التعليم الرقمي' : 'Scholder - Digital Education Platform' }}</title>
    
    <!-- Enhanced Fonts for Arabic -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    @if(app()->getLocale() === 'ar')
        <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800;900&family=Tajawal:wght@300;400;500;700;800;900&display=swap" rel="stylesheet">
    @else
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Playfair+Display:wght@700;900&display=swap" rel="stylesheet">
    @endif
    
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.1.0/fonts/remixicon.css" rel="stylesheet">
    
    <style>
        :root {
            --primary: #4F46E5;
            --primary-dark: #3730A3;
            --primary-light: #6366F1;
            --accent: #14B8A6;
            --accent-light: #2DD4BF;
            --accent-dark: #0D9488;
            --bg: #FFFFFF;
            --bg-secondary: #F8FAFC;
            --bg-tertiary: #F1F5F9;
            --text: #0F172A;
            --text-secondary: #475569;
            --text-light: #94A3B8;
            --border: #E2E8F0;
            --gradient-1: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --gradient-2: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --gradient-3: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            --gradient-4: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
        }

        * { 
            margin: 0; 
            padding: 0; 
            box-sizing: border-box; 
        }
        
        html { 
            scroll-behavior: smooth; 
        }
        
        body {
            font-family: {{ app()->getLocale() === 'ar' ? "'Cairo', 'Tajawal', sans-serif" : "'Inter', sans-serif" }};
            color: var(--text);
            background: var(--bg);
            line-height: 1.8;
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        /* Animated Gradient Background */
        .gradient-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 25%, #f093fb 50%, #4facfe 75%, #00f2fe 100%);
            background-size: 400% 400%;
            animation: gradientShift 20s ease infinite;
            opacity: 0.03;
        }
        
        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        /* Container */
        .container { 
            max-width: 1280px; 
            margin: 0 auto; 
            padding: 0 24px; 
            position: relative; 
            z-index: 1; 
        }
        
        section { 
            padding: 100px 0; 
            position: relative; 
        }

        /* Header */
        header {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            position: sticky;
            top: 0;
            z-index: 1000;
            border-bottom: 1px solid var(--border);
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.03);
        }
        
        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 0;
        }
        
        .logo-text {
            font-family: {{ app()->getLocale() === 'ar' ? "'Cairo', sans-serif" : "'Playfair Display', serif" }};
            font-size: 32px;
            font-weight: 900;
            background: var(--gradient-1);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            letter-spacing: -0.02em;
        }

        /* Language Switcher */
        .lang-switcher {
            display: flex;
            gap: 4px;
            background: var(--bg-secondary);
            padding: 4px;
            border-radius: 12px;
            border: 1px solid var(--border);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        }
        
        .lang-btn {
            padding: 10px 20px;
            border: none;
            background: transparent;
            cursor: pointer;
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.3s ease;
            color: var(--text-secondary);
            font-size: 15px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        
        .lang-btn.active {
            background: var(--gradient-1);
            color: white;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
        }

        /* Typography */
        h1, h2, h3, h4 { 
            font-weight: 800; 
            line-height: 1.2;
        }
        
        h1 { 
            font-size: clamp(42px, 8vw, 80px); 
            margin-bottom: 24px; 
        }
        
        h2 { 
            font-size: clamp(32px, 6vw, 56px); 
            margin-bottom: 20px; 
        }
        
        h3 { 
            font-size: clamp(24px, 4vw, 32px); 
            margin-bottom: 16px; 
        }

        .gradient-text {
            background: var(--gradient-1);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Hero Section */
        .hero {
            padding: 120px 0 80px;
            background: linear-gradient(180deg, var(--bg-secondary) 0%, var(--bg) 100%);
            position: relative;
            overflow: hidden;
        }
        
        .hero-content {
            text-align: center;
            max-width: 900px;
            margin: 0 auto;
        }
        
        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 24px;
            background: var(--gradient-1);
            border-radius: 50px;
            color: white;
            font-weight: 700;
            font-size: 15px;
            margin-bottom: 32px;
            box-shadow: 0 8px 24px rgba(79, 70, 229, 0.3);
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        
        .hero p {
            font-size: clamp(18px, 3vw, 22px);
            line-height: 1.8;
            color: var(--text-secondary);
            margin-bottom: 40px;
        }

        /* Section Headers */
        .section-header {
            text-align: center;
            max-width: 800px;
            margin: 0 auto 60px;
        }
        
        .section-header p {
            font-size: clamp(16px, 2.5vw, 20px);
            color: var(--text-secondary);
            line-height: 1.8;
            margin-top: 16px;
        }

        /* Cards Grid */
        .cards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 32px;
            margin-top: 48px;
        }
        
        .card {
            background: white;
            padding: 40px 32px;
            border-radius: 24px;
            border: 1px solid var(--border);
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }
        
        .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--gradient-1);
            transform: scaleX(0);
            transition: transform 0.4s ease;
        }
        
        .card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.12);
            border-color: var(--primary-light);
        }
        
        .card:hover::before {
            transform: scaleX(1);
        }
        
        .card-icon {
            width: 64px;
            height: 64px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 24px;
            font-size: 32px;
        }
        
        .card h3 {
            margin-bottom: 16px;
            color: var(--text);
        }
        
        .card p {
            color: var(--text-secondary);
            line-height: 1.7;
            font-size: 16px;
        }

        /* Role Cards */
        .role-card {
            background: white;
            padding: 36px;
            border-radius: 24px;
            border: 2px solid var(--border);
            transition: all 0.4s ease;
            position: relative;
        }
        
        .role-card:hover {
            border-color: var(--primary);
            transform: translateY(-4px);
            box-shadow: 0 16px 48px rgba(79, 70, 229, 0.15);
        }
        
        .role-header {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 20px;
        }
        
        .role-icon {
            width: 56px;
            height: 56px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            flex-shrink: 0;
        }
        
        .role-title {
            font-size: 24px;
            font-weight: 800;
            color: var(--text);
        }
        
        .role-description {
            color: var(--text-secondary);
            line-height: 1.8;
            font-size: 16px;
            margin-bottom: 20px;
        }
        
        .role-features {
            list-style: none;
            margin-top: 20px;
        }
        
        .role-features li {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 10px 0;
            color: var(--text-secondary);
            font-size: 15px;
        }
        
        .role-features li::before {
            content: '✓';
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: var(--gradient-4);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 14px;
            flex-shrink: 0;
        }

        /* Architecture Diagram */
        .architecture-diagram {
            background: var(--bg-secondary);
            border-radius: 32px;
            padding: 60px 40px;
            margin: 60px 0;
            position: relative;
            overflow: hidden;
        }
        
        .diagram-level {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 24px;
            margin: 32px 0;
            flex-wrap: wrap;
        }
        
        .diagram-node {
            background: white;
            padding: 24px 32px;
            border-radius: 16px;
            border: 2px solid var(--border);
            font-weight: 700;
            font-size: 18px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            min-width: 200px;
            text-align: center;
        }
        
        .diagram-node:hover {
            transform: scale(1.05);
            border-color: var(--primary);
            box-shadow: 0 8px 24px rgba(79, 70, 229, 0.2);
        }
        
        .diagram-arrow {
            font-size: 32px;
            color: var(--primary);
            margin: 16px 0;
        }

        /* Feature Highlights */
        .feature-highlight {
            background: var(--gradient-1);
            color: white;
            padding: 60px 40px;
            border-radius: 32px;
            margin: 60px 0;
            text-align: center;
        }
        
        .feature-highlight h3 {
            color: white;
            margin-bottom: 20px;
            font-size: 36px;
        }
        
        .feature-highlight p {
            font-size: 20px;
            opacity: 0.95;
            line-height: 1.8;
        }

        /* Stats Section */
        .stats-section {
            background: var(--bg-secondary);
            padding: 80px 0;
            border-radius: 32px;
            margin: 60px 0;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 32px;
            margin-top: 40px;
        }
        
        .stat-item {
            text-align: center;
        }
        
        .stat-value {
            font-size: clamp(36px, 6vw, 56px);
            font-weight: 900;
            background: var(--gradient-1);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 8px;
        }
        
        .stat-label {
            font-size: 18px;
            font-weight: 600;
            color: var(--text-secondary);
        }

        /* CTA Section */
        .cta-section {
            background: var(--gradient-1);
            color: white;
            padding: 100px 40px;
            border-radius: 32px;
            text-align: center;
            margin: 80px 0;
        }
        
        .cta-section h2 {
            color: white;
            margin-bottom: 24px;
            font-size: 48px;
        }
        
        .cta-section p {
            font-size: 20px;
            opacity: 0.95;
            margin-bottom: 40px;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }
        
        .btn-cta {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            padding: 18px 40px;
            background: white;
            color: var(--primary);
            border-radius: 16px;
            font-weight: 700;
            font-size: 18px;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
        }
        
        .btn-cta:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 32px rgba(0, 0, 0, 0.2);
        }

        /* Footer */
        footer {
            background: var(--text);
            color: white;
            padding: 80px 0 40px;
            margin-top: 100px;
        }
        
        .footer-content {
            text-align: center;
        }
        
        .footer-content p {
            opacity: 0.8;
            margin-top: 20px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            section { padding: 60px 0; }
            .cards-grid { grid-template-columns: 1fr; gap: 24px; }
            .diagram-level { flex-direction: column; }
            .diagram-arrow { transform: rotate(90deg); }
        }

        /* RTL Support */
        [dir="rtl"] {
            text-align: right;
        }
        
        [dir="rtl"] .role-features li {
            flex-direction: row-reverse;
        }
    </style>
</head>
<body>
<div class="gradient-bg"></div>

<header>
    <nav class="container">
        <div class="logo-text">Scholder</div>
        <div class="lang-switcher">
            <button 
               onclick="switchLocale('en')"
               class="lang-btn {{ app()->getLocale() === 'en' ? 'active' : '' }}">
                <span>🇬🇧</span> English
            </button>
            <button 
               onclick="switchLocale('ar')"
               class="lang-btn {{ app()->getLocale() === 'ar' ? 'active' : '' }}">
                <span>🇸🇦</span> العربية
            </button>
        </div>
    </nav>
</header>

<!-- Hero Section -->
<section class="hero">
    <div class="container">
        <div class="hero-content">
            <div class="hero-badge">
                <i class="ri-global-line"></i>
                <span>{{ app()->getLocale() === 'ar' ? 'منصة متعددة المستأجرين' : 'Multi-Tenant Platform' }}</span>
            </div>
            <h1>
                <span class="gradient-text">{{ app()->getLocale() === 'ar' ? 'نظام إدارة المدارس' : 'School Management System' }}</span>
                <br>
                {{ app()->getLocale() === 'ar' ? 'المتكامل' : 'Enterprise Platform' }}
            </h1>
            <p>
                {{ app()->getLocale() === 'ar' 
                    ? 'منصة Laravel متعددة المستأجرين حيث تحتوي الشبكات (المناطق التعليمية) على فروع (مدارس)، وكل طلب يحمل معرفات الشبكة والفرع للحفاظ على البيانات والواجهة محددة بشكل صحيح.' 
                    : 'A multi-tenant Laravel platform where networks (districts) contain branches (schools), and every request carries both network and branch identifiers to keep data and UI scoped correctly.' }}
            </p>
        </div>
    </div>
</section>

<!-- System Overview -->
<section id="overview">
    <div class="container">
        <div class="section-header">
            <h2 class="gradient-text">{{ app()->getLocale() === 'ar' ? 'نظرة عامة على النظام' : 'System Overview' }}</h2>
            <p>
                {{ app()->getLocale() === 'ar' 
                    ? 'منصة متكاملة مصممة خصيصاً للمؤسسات التعليمية مع عزل عميق للمستأجرين ومرونة في الإشراف' 
                    : 'An integrated platform designed specifically for educational institutions with deep tenant isolation and flexible oversight' }}
            </p>
        </div>

        <div class="architecture-diagram">
            <div class="diagram-level">
                <div class="diagram-node" style="background: var(--gradient-1); color: white; border: none;">
                    {{ app()->getLocale() === 'ar' ? 'المشرف العام' : 'SuperAdmin' }}
                </div>
            </div>
            <div class="diagram-arrow">↓</div>
            <div class="diagram-level">
                <div class="diagram-node" style="background: var(--gradient-2); color: white; border: none;">
                    {{ app()->getLocale() === 'ar' ? 'الشبكة' : 'Network' }}
                </div>
            </div>
            <div class="diagram-arrow">↓</div>
            <div class="diagram-level">
                <div class="diagram-node">{{ app()->getLocale() === 'ar' ? 'المدير الرئيسي' : 'Main Admin' }}</div>
            </div>
            <div class="diagram-arrow">↓</div>
            <div class="diagram-level">
                <div class="diagram-node">{{ app()->getLocale() === 'ar' ? 'الفرع 1' : 'Branch 1' }}</div>
                <div class="diagram-node">{{ app()->getLocale() === 'ar' ? 'الفرع 2' : 'Branch 2' }}</div>
                <div class="diagram-node">{{ app()->getLocale() === 'ar' ? 'الفرع 3' : 'Branch 3' }}</div>
            </div>
            <div class="diagram-arrow">↓</div>
            <div class="diagram-level">
                <div class="diagram-node" style="font-size: 16px;">{{ app()->getLocale() === 'ar' ? 'مدير الفرع' : 'Branch Admin' }}</div>
                <div class="diagram-node" style="font-size: 16px;">{{ app()->getLocale() === 'ar' ? 'المشرف' : 'Supervisor' }}</div>
                <div class="diagram-node" style="font-size: 16px;">{{ app()->getLocale() === 'ar' ? 'المعلم' : 'Teacher' }}</div>
            </div>
        </div>

        <div class="cards-grid">
            <div class="card">
                <div class="card-icon" style="background: var(--gradient-1); color: white;">
                    <i class="ri-building-4-line"></i>
                </div>
                <h3>{{ app()->getLocale() === 'ar' ? 'عزل المستأجرين' : 'Tenant Isolation' }}</h3>
                <p>
                    {{ app()->getLocale() === 'ar' 
                        ? 'كل فرع (مدرسة) معزول تماماً عن الآخرين مع ضمانات أمنية قوية لمنع الوصول غير المصرح به' 
                        : 'Each branch (school) is completely isolated from others with strong security guarantees to prevent unauthorized access' }}
                </p>
            </div>
            
            <div class="card">
                <div class="card-icon" style="background: var(--gradient-2); color: white;">
                    <i class="ri-shield-check-line"></i>
                </div>
                <h3>{{ app()->getLocale() === 'ar' ? 'الأمان أولاً' : 'Security First' }}</h3>
                <p>
                    {{ app()->getLocale() === 'ar' 
                        ? 'إيقاف مبكر للطلبات غير المتطابقة، وإنفاذ دورات الوسطاء، والتحقق من الوصول للمستأجرين' 
                        : 'Early aborts for mismatched requests, enforced role middleware, and verified tenant access' }}
                </p>
            </div>
            
            <div class="card">
                <div class="card-icon" style="background: var(--gradient-3); color: white;">
                    <i class="ri-swap-line"></i>
                </div>
                <h3>{{ app()->getLocale() === 'ar' ? 'تبديل السياق' : 'Context Switching' }}</h3>
                <p>
                    {{ app()->getLocale() === 'ar' 
                        ? 'إدارة السياق المدعومة بالجلسة تسمح للمستخدمين متعددي الأدوار بالتنقل بسلاسة بين المدارس والأدوار' 
                        : 'Session-backed context management lets multi-role users move smoothly between schools and roles' }}
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Roles Section -->
<section id="roles" style="background: var(--bg-secondary);">
    <div class="container">
        <div class="section-header">
            <h2 class="gradient-text">{{ app()->getLocale() === 'ar' ? 'الأدوار والمسؤوليات' : 'Roles & Responsibilities' }}</h2>
            <p>
                {{ app()->getLocale() === 'ar' 
                    ? 'أدوار منفصلة بوضوح مع مسؤوليات محددة لكل مستوى في النظام' 
                    : 'Cleanly separated roles with well-defined responsibilities for each level in the system' }}
            </p>
        </div>

        <div class="cards-grid">
            <div class="role-card">
                <div class="role-header">
                    <div class="role-icon" style="background: var(--gradient-1); color: white;">
                        <i class="ri-admin-line"></i>
                    </div>
                    <div class="role-title">{{ app()->getLocale() === 'ar' ? 'المشرف العام' : 'SuperAdmin' }}</div>
                </div>
                <p class="role-description">
                    {{ app()->getLocale() === 'ar' 
                        ? 'ينشئ الشبكات والفروع وبيانات اعتماد المدير الرئيسي؛ يدير الاشتراكات والخطط وسجلات النشاط، لكنه لا يمكنه لمس المحتوى الأكاديمي' 
                        : 'Creates networks, branches, and main-admin credentials; manages subscriptions, plans, and activity logs, but cannot touch academic content' }}
                </p>
                <ul class="role-features">
                    <li>{{ app()->getLocale() === 'ar' ? 'إدارة الشبكات والفروع' : 'Network & Branch Management' }}</li>
                    <li>{{ app()->getLocale() === 'ar' ? 'إدارة الاشتراكات والخطط' : 'Subscription & Plan Management' }}</li>
                    <li>{{ app()->getLocale() === 'ar' ? 'سجلات النشاط والتدقيق' : 'Activity Logs & Auditing' }}</li>
                </ul>
            </div>

            <div class="role-card">
                <div class="role-header">
                    <div class="role-icon" style="background: var(--gradient-2); color: white;">
                        <i class="ri-building-line"></i>
                    </div>
                    <div class="role-title">{{ app()->getLocale() === 'ar' ? 'المدير الرئيسي' : 'Main Admin' }}</div>
                </div>
                <p class="role-description">
                    {{ app()->getLocale() === 'ar' 
                        ? 'يشرف على الشبكة بأكملها مع لوحات تحكم تعرض مقاييس عبر الفروع وأدوات لإدارة المستخدمين والمواد والصفوف وإعدادات الفروع في كل مكان' 
                        : 'Oversees the entire network with dashboards showing cross-branch metrics and tools to manage users, subjects, grades, and branch settings everywhere' }}
                </p>
                <ul class="role-features">
                    <li>{{ app()->getLocale() === 'ar' ? 'عرض كمدير لأي فرع' : 'View as Admin for any branch' }}</li>
                    <li>{{ app()->getLocale() === 'ar' ? 'إدارة المستخدمين عبر الفروع' : 'User Management Across Branches' }}</li>
                    <li>{{ app()->getLocale() === 'ar' ? 'لوحة تحكم موحدة' : 'Unified Command Center' }}</li>
                </ul>
            </div>

            <div class="role-card">
                <div class="role-header">
                    <div class="role-icon" style="background: var(--gradient-3); color: white;">
                        <i class="ri-school-line"></i>
                    </div>
                    <div class="role-title">{{ app()->getLocale() === 'ar' ? 'مدير الفرع' : 'Branch Admin' }}</div>
                </div>
                <p class="role-description">
                    {{ app()->getLocale() === 'ar' 
                        ? 'يدير الأكاديميات على مستوى الفرع - المستخدمين والمواد والصفوف ومستودعات الملفات والخطط وموارد المشرفين داخل فرعهم' 
                        : 'Manages branch-level academics—users, subjects, grades, file repositories, plans, and supervisor resources inside their branch' }}
                </p>
                <ul class="role-features">
                    <li>{{ app()->getLocale() === 'ar' ? 'إدارة المستخدمين المحليين' : 'Local User Management' }}</li>
                    <li>{{ app()->getLocale() === 'ar' ? 'إدارة المواد والصفوف' : 'Subjects & Grades Management' }}</li>
                    <li>{{ app()->getLocale() === 'ar' ? 'مستودع الملفات الكامل' : 'Complete File Repository' }}</li>
                </ul>
            </div>

            <div class="role-card">
                <div class="role-header">
                    <div class="role-icon" style="background: var(--gradient-4); color: white;">
                        <i class="ri-user-search-line"></i>
                    </div>
                    <div class="role-title">{{ app()->getLocale() === 'ar' ? 'المشرف' : 'Supervisor' }}</div>
                </div>
                <p class="role-description">
                    {{ app()->getLocale() === 'ar' 
                        ? 'يراجع إرسالات المعلمين ويحمّل موارد إشرافية داخل فرعهم، مع لوحات تحكم مخصصة وتدفقات مراجعة/ملفات مخصصة' 
                        : 'Reviews teacher submissions and uploads supervisory resources within their branch, with dedicated dashboards and review/file flows' }}
                </p>
                <ul class="role-features">
                    <li>{{ app()->getLocale() === 'ar' ? 'مراجعة ملفات المعلمين' : 'Review Teacher Files' }}</li>
                    <li>{{ app()->getLocale() === 'ar' ? 'رفع موارد إشرافية' : 'Upload Supervisory Resources' }}</li>
                    <li>{{ app()->getLocale() === 'ar' ? 'لوحة تحكم مخصصة' : 'Dedicated Dashboard' }}</li>
                </ul>
            </div>

            <div class="role-card">
                <div class="role-header">
                    <div class="role-icon" style="background: linear-gradient(135deg, #fa709a, #fee140); color: white;">
                        <i class="ri-user-line"></i>
                    </div>
                    <div class="role-title">{{ app()->getLocale() === 'ar' ? 'المعلم' : 'Teacher' }}</div>
                </div>
                <p class="role-description">
                    {{ app()->getLocale() === 'ar' 
                        ? 'يركز على المكتبات الشخصية وإرسالات خطط الدروس/الملفات عبر مسارات محددة للفرع ولوحة التحكم' 
                        : 'Focuses on personal libraries and lesson-plan/file submissions via their branch-specific routes and dashboard' }}
                </p>
                <ul class="role-features">
                    <li>{{ app()->getLocale() === 'ar' ? 'إدارة الملفات الشخصية' : 'Personal File Management' }}</li>
                    <li>{{ app()->getLocale() === 'ar' ? 'رفع خطط الدروس' : 'Upload Lesson Plans' }}</li>
                    <li>{{ app()->getLocale() === 'ar' ? 'مكتبة أكاديمية' : 'Academic Library' }}</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- Routing & Access Control -->
<section id="routing">
    <div class="container">
        <div class="section-header">
            <h2 class="gradient-text">{{ app()->getLocale() === 'ar' ? 'التوجيه والتحكم في الوصول' : 'Routing & Access Control' }}</h2>
            <p>
                {{ app()->getLocale() === 'ar' 
                    ? 'مساحات أسماء URL تعكس التسلسل الهرمي مع حماية قوية عبر الوسطاء' 
                    : 'URL namespaces mirror the hierarchy with strong protection through middleware' }}
            </p>
        </div>

        <div class="cards-grid">
            <div class="card">
                <div class="card-icon" style="background: var(--gradient-1); color: white;">
                    <i class="ri-route-line"></i>
                </div>
                <h3>{{ app()->getLocale() === 'ar' ? 'هيكل URL' : 'URL Structure' }}</h3>
                <p>
                    {{ app()->getLocale() === 'ar' 
                        ? '/superadmin/... للشبكات، /{network}/main-admin/... للإدارة على مستوى الشبكة، و/{network}/{school}/(admin|teacher|supervisor)/... لأدوار الفروع' 
                        : '/superadmin/... for networks, /{network}/main-admin/... for network-level management, and /{network}/{school}/(admin|teacher|supervisor)/... for branch roles' }}
                </p>
            </div>
            
            <div class="card">
                <div class="card-icon" style="background: var(--gradient-2); color: white;">
                    <i class="ri-shield-user-line"></i>
                </div>
                <h3>{{ app()->getLocale() === 'ar' ? 'حماية الوسطاء' : 'Middleware Protection' }}</h3>
                <p>
                    {{ app()->getLocale() === 'ar' 
                        ? 'تضمن وسطاء المستأجر أن معرف الفرع ينتمي إلى الشبكة وأن المستخدم المصادق عليه يحمل الدور الصحيح' 
                        : 'Tenant middleware ensures the branch identifier belongs to the network and the authenticated user holds the right role' }}
                </p>
            </div>
            
            <div class="card">
                <div class="card-icon" style="background: var(--gradient-3); color: white;">
                    <i class="ri-lock-line"></i>
                </div>
                <h3>{{ app()->getLocale() === 'ar' ? 'عزل البيانات' : 'Data Isolation' }}</h3>
                <p>
                    {{ app()->getLocale() === 'ar' 
                        ? 'يتم حظر المجموعات غير الصالحة بـ 403/404 قبل تشغيل المتحكمات، مما يحمي حدود البيانات عبر المستأجرين' 
                        : 'Invalid combinations are blocked with 403/404 before controllers run, protecting cross-tenant data boundaries' }}
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Context Handling -->
<section id="context" style="background: var(--bg-secondary);">
    <div class="container">
        <div class="section-header">
            <h2 class="gradient-text">{{ app()->getLocale() === 'ar' ? 'معالجة السياق' : 'Context Handling' }}</h2>
            <p>
                {{ app()->getLocale() === 'ar' 
                    ? 'إدارة سياق متقدمة للمستخدمين متعددي الأدوار' 
                    : 'Advanced context management for multi-role users' }}
            </p>
        </div>

        <div class="feature-highlight">
            <h3>{{ app()->getLocale() === 'ar' ? 'ActiveContext' : 'ActiveContext' }}</h3>
            <p>
                {{ app()->getLocale() === 'ar' 
                    ? 'يتتبع المدرسة والدور النشطين للمستخدم في الجلسة، مما يفرض أن مستخدمي الفروع يختارون فقط الأدوار التي يحملونها فعلياً بينما يسمح للمدير الرئيسي بالتنقل بأمان إلى أي فرع داخل شبكتهم' 
                    : 'Tracks the user\'s active school and role in session, enforcing that branch users only select roles they actually hold while letting Main Admin safely navigate to any branch within their network' }}
            </p>
        </div>

        <div class="cards-grid">
            <div class="card">
                <h3>{{ app()->getLocale() === 'ar' ? 'التحقق التلقائي' : 'Auto-Validation' }}</h3>
                <p>
                    {{ app()->getLocale() === 'ar' 
                        ? 'التحقق المزدوج من تناسق المعرفات، واستنتاج دور صالح عند فقدانه، وإعادة التوجيه إلى المنطقة المحددة بالدور الصحيحة' 
                        : 'Double-checks slug consistency, derives a valid role when missing, and redirects to the correct role-specific area' }}
                </p>
            </div>
            
            <div class="card">
                <h3>{{ app()->getLocale() === 'ar' ? 'التبديل السلس' : 'Smooth Switching' }}</h3>
                <p>
                    {{ app()->getLocale() === 'ar' 
                        ? 'التحقق التلقائي لمنع السياقات القديمة أو غير المصرح بها، مما يبقي الدور النشط متزامناً مع الأذونات الحقيقية' 
                        : 'Automatic validation to prevent stale or unauthorized contexts, keeping the active role synchronized with real permissions' }}
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Platform Strengths -->
<section id="strengths">
    <div class="container">
        <div class="section-header">
            <h2 class="gradient-text">{{ app()->getLocale() === 'ar' ? 'نقاط القوة في المنصة' : 'Platform Strengths' }}</h2>
        </div>

        <div class="cards-grid">
            <div class="card">
                <div class="card-icon" style="background: var(--gradient-1); color: white;">
                    <i class="ri-shield-star-line"></i>
                </div>
                <h3>{{ app()->getLocale() === 'ar' ? 'عزل عميق مع إشراف مرن' : 'Deep Isolation with Flexible Oversight' }}</h3>
                <p>
                    {{ app()->getLocale() === 'ar' 
                        ? 'التوجيه القائم على المعرفات بالإضافة إلى الوسطاء يحافظ على الفروع معزولة بينما لا يزال يسمح للمدير الرئيسي بالتنقل عبر الشبكة بأكملها بأمان' 
                        : 'Slug-based routing plus middleware keeps branches walled off while still allowing Main Admin to traverse the entire network securely' }}
                </p>
            </div>
            
            <div class="card">
                <div class="card-icon" style="background: var(--gradient-2); color: white;">
                    <i class="ri-refresh-line"></i>
                </div>
                <h3>{{ app()->getLocale() === 'ar' ? 'تبديل السياق القوي' : 'Robust Context Switching' }}</h3>
                <p>
                    {{ app()->getLocale() === 'ar' 
                        ? 'إدارة السياق المدعومة بالجلسة تسمح للمستخدمين متعددي الأدوار بالتنقل بسلاسة بين المدارس والأدوار، مع التحقق التلقائي لمنع السياقات القديمة أو غير المصرح بها' 
                        : 'Session-backed context management lets multi-role users move smoothly between schools and roles, with automatic validation to prevent stale or unauthorized contexts' }}
                </p>
            </div>
            
            <div class="card">
                <div class="card-icon" style="background: var(--gradient-3); color: white;">
                    <i class="ri-smartphone-line"></i>
                </div>
                <h3>{{ app()->getLocale() === 'ar' ? 'جاهزية PWA' : 'PWA Readiness' }}</h3>
                <p>
                    {{ app()->getLocale() === 'ar' 
                        ? 'البيان، ومعالجات البروتوكول، وأهداف المشاركة، وإمكانيات وضع عدم الاتصال/سطح المكتب مدمجة في طبقة التوجيه' 
                        : 'Manifest, protocol handlers, share targets, and offline/desktop affordances are baked into the routing layer' }}
                </p>
            </div>
            
            <div class="card">
                <div class="card-icon" style="background: var(--gradient-4); color: white;">
                    <i class="ri-file-list-3-line"></i>
                </div>
                <h3>{{ app()->getLocale() === 'ar' ? 'قابلية المراقبة الشاملة' : 'Comprehensive Observability' }}</h3>
                <p>
                    {{ app()->getLocale() === 'ar' 
                        ? 'مسارات سجل النشاط موجودة لكل من المشرف العام ومديري الفروع، مما يتيح إمكانية التدقيق عبر عمليات الحوكمة والأكاديمية' 
                        : 'Activity log routes exist for both SuperAdmin and branch admins, enabling auditability across governance and academic operations' }}
                </p>
            </div>
            
            <div class="card">
                <div class="card-icon" style="background: linear-gradient(135deg, #fa709a, #fee140); color: white;">
                    <i class="ri-lock-password-line"></i>
                </div>
                <h3>{{ app()->getLocale() === 'ar' ? 'الأمان أولاً' : 'Security-First Defaults' }}</h3>
                <p>
                    {{ app()->getLocale() === 'ar' 
                        ? 'الإيقاف المبكر للطلبات غير المتطابقة، وإنفاذ دورات الوسطاء، والتحقق من الوصول للمستأجرين يقلل من نطاق سوء التكوين أو الطلبات الخبيثة' 
                        : 'Early aborts for mismatched requests, enforced role middleware, and verified tenant access reduce the blast radius of misconfiguration or malicious requests' }}
                </p>
            </div>
            
            <div class="card">
                <div class="card-icon" style="background: linear-gradient(135deg, #ffecd2, #fcb69f); color: white;">
                    <i class="ri-puzzle-line"></i>
                </div>
                <h3>{{ app()->getLocale() === 'ar' ? 'تكامل مثير للإعجاب' : 'Impressive Integration' }}</h3>
                <p>
                    {{ app()->getLocale() === 'ar' 
                        ? 'يجمع النظام بين حدود متعددة المستأجرين الصارمة مع نموذج إشراف المدير الرئيسي المتقدم، مما يوفر تجارب دورية دقيقة مع الحفاظ على الحوكمة والعمليات الأكاديمية والقدرات على مستوى المنصة منفصلة ومتكاملة بشكل مثير للإعجاب' 
                        : 'The system combines strict multi-tenant boundaries with an elevated Main Admin oversight model, offering granular role experiences while keeping governance, academic operations, and platform-wide capabilities neatly separated and impressively integrated' }}
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="stats-section">
    <div class="container">
        <div class="section-header">
            <h2 class="gradient-text">{{ app()->getLocale() === 'ar' ? 'الأرقام' : 'By The Numbers' }}</h2>
        </div>
        <div class="stats-grid">
            <div class="stat-item">
                <div class="stat-value">149</div>
                <div class="stat-label">{{ app()->getLocale() === 'ar' ? 'مدرسة' : 'Schools' }}</div>
            </div>
            <div class="stat-item">
                <div class="stat-value">585K+</div>
                <div class="stat-label">{{ app()->getLocale() === 'ar' ? 'ملف' : 'Files' }}</div>
            </div>
            <div class="stat-item">
                <div class="stat-value">3.7K+</div>
                <div class="stat-label">{{ app()->getLocale() === 'ar' ? 'مستخدم' : 'Users' }}</div>
            </div>
            <div class="stat-item">
                <div class="stat-value">100%</div>
                <div class="stat-label">{{ app()->getLocale() === 'ar' ? 'آمن' : 'Secure' }}</div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="cta-section">
    <div class="container">
        <h2>{{ app()->getLocale() === 'ar' ? 'ابدأ رحلتك الرقمية اليوم' : 'Start Your Digital Journey Today' }}</h2>
        <p>
            {{ app()->getLocale() === 'ar' 
                ? 'انضم إلى المؤسسات التعليمية التي تستخدم سكولدر لإدارة ملفاتها بكفاءة وأمان' 
                : 'Join educational institutions using Scholder to manage their files efficiently and securely' }}
        </p>
        <a href="#contact" class="btn-cta">
            <i class="ri-mail-line"></i>
            {{ app()->getLocale() === 'ar' ? 'تواصل معنا' : 'Contact Us' }}
        </a>
    </div>
</section>

<!-- Footer -->
<footer>
    <div class="container">
        <div class="footer-content">
            <h3 class="logo-text" style="color: white; margin-bottom: 16px;">Scholder</h3>
            <p>
                {{ app()->getLocale() === 'ar' 
                    ? 'منصة إدارة الملفات السحابية الآمنة للمؤسسات — مصممة للإداريين والمعلمين والمشرفين والسكرتاريين' 
                    : 'Secure cloud file management for institutions — designed for administrators, teachers, supervisors and secretaries' }}
            </p>
            <p style="margin-top: 24px; opacity: 0.6;">
                © {{ date('Y') }} Scholder. {{ app()->getLocale() === 'ar' ? 'جميع الحقوق محفوظة' : 'All rights reserved' }}.
            </p>
        </div>
    </div>
</footer>

<script>
function switchLocale(locale) {
    fetch("{{ route('locale.update') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify({ locale })
    }).then(() => {
        window.location.reload();
    });
}

// Smooth scroll for anchor links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    });
});

// Animate cards on scroll
const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
};

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.style.opacity = '1';
            entry.target.style.transform = 'translateY(0)';
        }
    });
}, observerOptions);

document.querySelectorAll('.card, .role-card').forEach(card => {
    card.style.opacity = '0';
    card.style.transform = 'translateY(30px)';
    card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
    observer.observe(card);
});
</script>
</body>
</html>
