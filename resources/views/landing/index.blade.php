<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5">
    <meta name="description" content="{{ app()->getLocale() === 'ar' ? 'سكولدر - منصة إدارة التعليم الرقمية الشاملة للمؤسسات التعليمية' : 'Scholder - Comprehensive Educational Management Platform for Educational Institutions' }}">
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
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    @endif
    
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.1.0/fonts/remixicon.css" rel="stylesheet">
    
    <style>
        :root {
            --teal: #14B8A6;
            --teal-dark: #0D9488;
            --teal-light: #2DD4BF;
            --orange: #F97316;
            --red: #EF4444;
            --bg: #FFFFFF;
            --bg-dark: #0F172A;
            --text: #0F172A;
            --text-secondary: #475569;
            --text-light: #94A3B8;
            --border: #E2E8F0;
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
            line-height: 1.6;
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        /* Container */
        .container { 
            max-width: 1280px; 
            margin: 0 auto; 
            padding: 0 24px; 
        }
        
        section { 
            padding: 80px 0; 
        }

        /* Header */
        header {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(20px);
            position: sticky;
            top: 0;
            z-index: 1000;
            border-bottom: 1px solid var(--border);
        }
        
        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 0;
        }
        
        .logo {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 24px;
            font-weight: 700;
            color: var(--text);
            text-decoration: none;
        }
        
        .logo-icon {
            width: 40px;
            height: 40px;
            background: var(--teal);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 20px;
        }
        
        .nav-links {
            display: flex;
            gap: 32px;
            list-style: none;
        }
        
        .nav-links a {
            color: var(--text-secondary);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s;
        }
        
        .nav-links a:hover {
            color: var(--text);
        }
        
        .nav-actions {
            display: flex;
            align-items: center;
            gap: 16px;
        }
        
        .btn-login {
            padding: 10px 20px;
            color: var(--text-secondary);
            text-decoration: none;
            font-weight: 500;
            border-radius: 8px;
            transition: all 0.3s;
        }
        
        .btn-login:hover {
            color: var(--text);
            background: var(--bg-secondary);
        }
        
        .btn-primary {
            padding: 10px 24px;
            background: var(--teal);
            color: white;
            text-decoration: none;
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.3s;
        }
        
        .btn-primary:hover {
            background: var(--teal-dark);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(20, 184, 166, 0.3);
        }
        
        .lang-switcher {
            display: flex;
            gap: 4px;
            background: var(--bg-secondary);
            padding: 4px;
            border-radius: 8px;
            margin-left: 16px;
        }
        
        .lang-btn {
            padding: 6px 12px;
            border: none;
            background: transparent;
            cursor: pointer;
            font-weight: 500;
            border-radius: 6px;
            transition: all 0.3s;
            color: var(--text-secondary);
            font-size: 14px;
        }
        
        .lang-btn.active {
            background: white;
            color: var(--text);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        /* Hero Section */
        .hero {
            padding: 120px 0 80px;
            text-align: center;
        }
        
        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            background: rgba(249, 115, 22, 0.1);
            border-radius: 50px;
            color: var(--orange);
            font-weight: 600;
            font-size: 14px;
            margin-bottom: 24px;
        }
        
        .hero-badge i {
            font-size: 16px;
        }
        
        .hero h1 {
            font-size: clamp(36px, 6vw, 64px);
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 24px;
            color: var(--text);
        }
        
        .hero h1 .highlight {
            color: var(--teal);
        }
        
        .hero p {
            font-size: clamp(18px, 2.5vw, 22px);
            color: var(--text-secondary);
            max-width: 700px;
            margin: 0 auto 40px;
        }
        
        .hero-cta {
            display: flex;
            gap: 16px;
            justify-content: center;
            flex-wrap: wrap;
        }
        
        .btn-cta-primary {
            padding: 16px 32px;
            background: var(--teal);
            color: white;
            text-decoration: none;
            font-weight: 600;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s;
        }
        
        .btn-cta-primary:hover {
            background: var(--teal-dark);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(20, 184, 166, 0.3);
        }
        
        .btn-cta-secondary {
            padding: 16px 32px;
            background: white;
            color: var(--text);
            text-decoration: none;
            font-weight: 600;
            border-radius: 8px;
            border: 1px solid var(--border);
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s;
        }
        
        .btn-cta-secondary:hover {
            border-color: var(--teal);
            color: var(--teal);
        }
        
        .hero-trust {
            margin-top: 32px;
            color: var(--text-light);
            font-size: 14px;
        }

        /* Section Headers */
        .section-header {
            text-align: center;
            max-width: 800px;
            margin: 0 auto 60px;
        }
        
        .section-badge {
            display: inline-block;
            padding: 6px 16px;
            background: rgba(20, 184, 166, 0.1);
            color: var(--teal);
            border-radius: 50px;
            font-weight: 600;
            font-size: 14px;
            margin-bottom: 16px;
        }
        
        .section-title {
            font-size: clamp(32px, 5vw, 48px);
            font-weight: 800;
            margin-bottom: 16px;
            color: var(--text);
        }
        
        .section-title .highlight {
            color: var(--teal);
        }
        
        .section-subtitle {
            font-size: 18px;
            color: var(--text-secondary);
        }

        /* Cards Grid */
        .cards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 24px;
            margin-top: 48px;
        }
        
        .card {
            background: white;
            padding: 32px;
            border-radius: 16px;
            border: 1px solid var(--border);
            transition: all 0.3s;
        }
        
        .card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 32px rgba(0, 0, 0, 0.1);
            border-color: var(--teal);
        }
        
        .card-icon {
            width: 56px;
            height: 56px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            font-size: 24px;
            background: rgba(20, 184, 166, 0.1);
            color: var(--teal);
        }
        
        .card h3 {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 12px;
            color: var(--text);
        }
        
        .card p {
            color: var(--text-secondary);
            line-height: 1.6;
        }
        
        /* Problem Cards */
        .problem-card {
            background: white;
            padding: 32px;
            border-radius: 16px;
            border: 1px solid var(--border);
            transition: all 0.3s;
        }
        
        .problem-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 32px rgba(0, 0, 0, 0.1);
        }
        
        .problem-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 16px;
            font-size: 24px;
            background: rgba(239, 68, 68, 0.1);
            color: var(--red);
        }
        
        .problem-card h3 {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 8px;
            color: var(--text);
        }
        
        .problem-card p {
            color: var(--text-secondary);
            font-size: 14px;
            line-height: 1.6;
        }

        /* Role Cards */
        .role-section {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 48px;
            align-items: center;
            margin-bottom: 80px;
        }
        
        .role-content h3 {
            font-size: 14px;
            color: var(--text-light);
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 8px;
        }
        
        .role-content h2 {
            font-size: 36px;
            font-weight: 800;
            margin-bottom: 16px;
            color: var(--text);
        }
        
        .role-content h2 .highlight {
            color: var(--orange);
        }
        
        .role-content p {
            color: var(--text-secondary);
            margin-bottom: 24px;
            line-height: 1.7;
        }
        
        .role-features {
            list-style: none;
            margin-bottom: 24px;
        }
        
        .role-features li {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 8px 0;
            color: var(--text-secondary);
        }
        
        .role-features li i {
            color: var(--teal);
            font-size: 20px;
        }
        
        .role-link {
            color: var(--teal);
            text-decoration: none;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        
        .role-preview {
            background: white;
            border-radius: 16px;
            padding: 24px;
            border: 1px solid var(--border);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
        }
        
        .role-preview-header {
            height: 40px;
            background: var(--teal);
            border-radius: 8px;
            margin-bottom: 16px;
        }
        
        .role-preview-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-bottom: 12px;
        }
        
        .role-preview-box {
            height: 80px;
            background: var(--bg-secondary);
            border-radius: 8px;
        }
        
        .role-preview-large {
            height: 120px;
            background: var(--bg-secondary);
            border-radius: 8px;
        }

        /* Product Preview */
        .product-preview {
            background: var(--bg-secondary);
            padding: 80px 0;
        }
        
        .preview-tabs {
            display: flex;
            justify-content: center;
            gap: 12px;
            margin-bottom: 40px;
            flex-wrap: wrap;
        }
        
        .preview-tab {
            padding: 12px 24px;
            background: white;
            border: 1px solid var(--border);
            border-radius: 8px;
            color: var(--text-secondary);
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
        }
        
        .preview-tab.active {
            background: var(--teal);
            color: white;
            border-color: var(--teal);
        }
        
        .preview-tab:hover {
            border-color: var(--teal);
        }
        
        .preview-mockup {
            background: white;
            border-radius: 16px;
            padding: 0;
            border: 1px solid var(--border);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
            max-width: 1200px;
            margin: 0 auto;
            overflow: hidden;
        }
        
        .mockup-header {
            background: #F3F4F6;
            padding: 12px 16px;
            display: flex;
            align-items: center;
            gap: 8px;
            border-bottom: 1px solid var(--border);
        }
        
        .mockup-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
        }
        
        .mockup-dot.red { background: #EF4444; }
        .mockup-dot.yellow { background: #F59E0B; }
        .mockup-dot.green { background: #10B981; }
        
        .mockup-url {
            flex: 1;
            text-align: center;
            color: var(--text-secondary);
            font-size: 14px;
        }
        
        .mockup-content {
            padding: 0;
            min-height: 600px;
            background: white;
            position: relative;
        }
        
        .preview-tab-content {
            display: none;
            animation: fadeIn 0.3s ease-in;
        }
        
        .preview-tab-content.active {
            display: block;
        }
        
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .mockup-image {
            width: 100%;
            height: auto;
            display: block;
            object-fit: contain;
            border-radius: 0 0 16px 16px;
        }
        
        .preview-description {
            text-align: center;
            margin-top: 24px;
            color: var(--text-secondary);
        }

        /* How It Works */
        .steps-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 32px;
            margin-top: 48px;
        }
        
        .step-card {
            text-align: center;
            position: relative;
        }
        
        .step-number {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            background: var(--teal);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            font-weight: 700;
            margin: 0 auto 24px;
        }
        
        .step-icon {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            background: rgba(20, 184, 166, 0.1);
            color: var(--teal);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            margin: 0 auto 24px;
        }
        
        .step-card h3 {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 12px;
            color: var(--text);
        }
        
        .step-card p {
            color: var(--text-secondary);
            line-height: 1.6;
        }
        
        .step-arrow {
            position: absolute;
            top: 32px;
            right: -16px;
            color: var(--teal);
            font-size: 24px;
        }

        /* Security Section */
        .security-section {
            background: var(--bg-dark);
            color: white;
            padding: 100px 0;
        }
        
        .security-section .section-title {
            color: white;
        }
        
        .security-section .section-subtitle {
            color: rgba(255, 255, 255, 0.7);
        }
        
        .security-card {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 32px;
            border-radius: 16px;
            transition: all 0.3s;
        }
        
        .security-card:hover {
            background: rgba(255, 255, 255, 0.08);
            transform: translateY(-4px);
        }
        
        .security-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 16px;
            font-size: 24px;
            background: rgba(20, 184, 166, 0.2);
            color: var(--teal-light);
        }
        
        .security-card h3 {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 8px;
            color: white;
        }
        
        .security-card p {
            color: rgba(255, 255, 255, 0.7);
            font-size: 14px;
            line-height: 1.6;
        }
        
        .compliance-badges {
            display: flex;
            justify-content: center;
            gap: 48px;
            margin-top: 48px;
            flex-wrap: wrap;
        }
        
        .compliance-badge {
            display: flex;
            align-items: center;
            gap: 12px;
            color: rgba(255, 255, 255, 0.7);
            font-size: 14px;
        }
        
        .compliance-icon {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            border: 1px solid rgba(255, 255, 255, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Pricing */
        .pricing-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 32px;
            margin-top: 48px;
        }
        
        .pricing-card {
            background: white;
            border: 2px solid var(--border);
            border-radius: 16px;
            padding: 40px 32px;
            position: relative;
            transition: all 0.3s;
        }
        
        .pricing-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
        }
        
        .pricing-card.popular {
            border-color: var(--teal);
            box-shadow: 0 8px 32px rgba(20, 184, 166, 0.2);
        }
        
        .popular-badge {
            position: absolute;
            top: -12px;
            left: 50%;
            transform: translateX(-50%);
            background: var(--teal);
            color: white;
            padding: 6px 24px;
            border-radius: 50px;
            font-size: 12px;
            font-weight: 700;
        }
        
        .pricing-icon {
            width: 56px;
            height: 56px;
            border-radius: 12px;
            background: rgba(20, 184, 166, 0.1);
            color: var(--teal);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            margin-bottom: 20px;
        }
        
        .pricing-card h3 {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 8px;
            color: var(--text);
        }
        
        .pricing-card p {
            color: var(--text-secondary);
            margin-bottom: 24px;
            font-size: 14px;
        }
        
        .pricing-amount {
            font-size: 32px;
            font-weight: 800;
            color: var(--text);
            margin-bottom: 32px;
        }
        
        .pricing-features {
            list-style: none;
            margin-bottom: 32px;
        }
        
        .pricing-features li {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 0;
            color: var(--text-secondary);
        }
        
        .pricing-features li::before {
            content: '✓';
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: var(--teal);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 12px;
            flex-shrink: 0;
        }
        
        .pricing-btn {
            width: 100%;
            padding: 14px;
            text-align: center;
            text-decoration: none;
            font-weight: 600;
            border-radius: 8px;
            display: block;
            transition: all 0.3s;
        }
        
        .pricing-btn-primary {
            background: var(--teal);
            color: white;
        }
        
        .pricing-btn-primary:hover {
            background: var(--teal-dark);
        }
        
        .pricing-btn-secondary {
            background: white;
            color: var(--text);
            border: 1px solid var(--border);
        }
        
        .pricing-btn-secondary:hover {
            border-color: var(--teal);
            color: var(--teal);
        }

        /* CTA Section */
        .cta-section {
            background: var(--teal);
            color: white;
            padding: 100px 0;
            text-align: center;
            border-radius: 32px;
            margin: 80px 0;
            position: relative;
            overflow: hidden;
        }
        
        .cta-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            opacity: 0.1;
        }
        
        .cta-section h2 {
            font-size: clamp(32px, 5vw, 48px);
            font-weight: 800;
            margin-bottom: 16px;
            position: relative;
            z-index: 1;
        }
        
        .cta-section p {
            font-size: 18px;
            margin-bottom: 32px;
            opacity: 0.95;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
            position: relative;
            z-index: 1;
        }
        
        .cta-buttons {
            display: flex;
            gap: 16px;
            justify-content: center;
            flex-wrap: wrap;
            position: relative;
            z-index: 1;
        }
        
        .cta-btn {
            padding: 16px 32px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        
        .cta-btn-primary {
            background: white;
            color: var(--teal);
        }
        
        .cta-btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }
        
        .cta-btn-secondary {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        
        .cta-btn-secondary:hover {
            background: rgba(255, 255, 255, 0.2);
        }
        
        .cta-note {
            margin-top: 24px;
            font-size: 14px;
            opacity: 0.9;
            position: relative;
            z-index: 1;
        }

        /* Footer */
        footer {
            background: var(--bg-dark);
            color: white;
            padding: 80px 0 40px;
        }
        
        .footer-content {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr 1fr;
            gap: 48px;
            margin-bottom: 48px;
        }
        
        .footer-brand h3 {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .footer-brand .logo-icon {
            width: 32px;
            height: 32px;
        }
        
        .footer-brand p {
            color: rgba(255, 255, 255, 0.7);
            line-height: 1.7;
            margin-bottom: 16px;
        }
        
        .footer-lang {
            display: flex;
            align-items: center;
            gap: 8px;
            color: rgba(255, 255, 255, 0.7);
            font-size: 14px;
        }
        
        .footer-column h4 {
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 16px;
            color: white;
        }
        
        .footer-column ul {
            list-style: none;
        }
        
        .footer-column ul li {
            margin-bottom: 12px;
        }
        
        .footer-column ul li a {
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            transition: color 0.3s;
        }
        
        .footer-column ul li a:hover {
            color: white;
        }
        
        .footer-bottom {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding-top: 32px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 16px;
        }
        
        .footer-bottom p {
            color: rgba(255, 255, 255, 0.7);
            font-size: 14px;
        }
        
        .footer-availability {
            display: flex;
            align-items: center;
            gap: 8px;
            color: rgba(255, 255, 255, 0.7);
            font-size: 14px;
        }
        
        .footer-availability i {
            color: var(--teal-light);
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .role-section {
                grid-template-columns: 1fr;
            }
            
            .pricing-grid {
                grid-template-columns: 1fr;
            }
            
            .footer-content {
                grid-template-columns: 1fr 1fr;
            }
        }
        
        @media (max-width: 768px) {
            .nav-links {
                display: none;
            }
            
            .hero {
                padding: 80px 0 60px;
            }
            
            section {
                padding: 60px 0;
            }
            
            .cards-grid {
                grid-template-columns: 1fr;
            }
            
            .steps-grid {
                grid-template-columns: 1fr;
            }
            
            .step-arrow {
                display: none;
            }
            
            .footer-content {
                grid-template-columns: 1fr;
            }
            
            .footer-bottom {
                flex-direction: column;
                text-align: center;
            }
        }

        /* RTL Support */
        [dir="rtl"] {
            text-align: right;
        }
        
        [dir="rtl"] .role-features li {
            flex-direction: row-reverse;
        }
        
        [dir="rtl"] .step-arrow {
            left: -16px;
            right: auto;
            transform: rotate(180deg);
        }
    </style>
</head>
<body>

<header>
    <nav class="container">
        <a href="#" class="logo">
            <div class="logo-icon">
                <i class="ri-graduation-cap-line"></i>
            </div>
            <span>Scholder</span>
        </a>
        <ul class="nav-links">
            <li><a href="#features">{{ app()->getLocale() === 'ar' ? 'المميزات' : 'Features' }}</a></li>
            <li><a href="#solutions">{{ app()->getLocale() === 'ar' ? 'الحلول' : 'Solutions' }}</a></li>
            <li><a href="#pricing">{{ app()->getLocale() === 'ar' ? 'الأسعار' : 'Pricing' }}</a></li>
            <li><a href="#security">{{ app()->getLocale() === 'ar' ? 'الأمان' : 'Security' }}</a></li>
        </ul>
        <div class="nav-actions">
            <div class="lang-switcher">
                <button onclick="switchLocale('en')" class="lang-btn {{ app()->getLocale() === 'en' ? 'active' : '' }}">
                    English
                </button>
                <button onclick="switchLocale('ar')" class="lang-btn {{ app()->getLocale() === 'ar' ? 'active' : '' }}">
                    العربية
                </button>
            </div>
            <a href="#cta" class="btn-primary">{{ app()->getLocale() === 'ar' ? 'ابدأ الآن' : 'Get Started' }}</a>
        </div>
    </nav>
</header>

<!-- Hero Section -->
<section class="hero">
    <div class="container">
        <div class="hero-badge">
            <i class="ri-flashlight-line"></i>
            <span>{{ app()->getLocale() === 'ar' ? 'متاح الآن للمدارس في جميع أنحاء العالم' : 'Now available for schools worldwide' }}</span>
        </div>
        <h1>
            {{ app()->getLocale() === 'ar' ? 'الجيل القادم من' : 'The Next-Generation' }}
            <br>
            <span class="highlight">{{ app()->getLocale() === 'ar' ? 'منصة إدارة التعليم' : 'Educational Management Platform' }}</span>
        </h1>
        <p>
            {{ app()->getLocale() === 'ar' 
                ? 'منصة سحابية للشبكات والمدارس والمعلمين والمشرفين والمديرين. امنح مؤسساتك التعليمية أدوات سير عمل حديثة.'
                : 'A cloud platform for networks, schools, teachers, supervisors, and administrators. Empower your educational institutions with modern workflow tools.' }}
        </p>
        <div class="hero-cta">
            <a href="#cta" class="btn-cta-primary">
                {{ app()->getLocale() === 'ar' ? 'ابدأ مجاناً' : 'Get Started Free' }}
                <i class="ri-arrow-right-line"></i>
            </a>
            <a href="#product-preview" class="btn-cta-secondary">
                <i class="ri-play-line"></i>
                {{ app()->getLocale() === 'ar' ? 'شاهد العرض التوضيحي' : 'Watch Demo' }}
            </a>
        </div>
        <p class="hero-trust">{{ app()->getLocale() === 'ar' ? 'موثوق به من قبل المؤسسات التعليمية في جميع أنحاء العالم' : 'Trusted by educational institutions worldwide' }}</p>
    </div>
</section>

<!-- Features Section -->
<section id="features">
    <div class="container">
        <div class="section-header">
            <span class="section-badge">{{ app()->getLocale() === 'ar' ? 'المميزات' : 'Features' }}</span>
            <h2 class="section-title">
                {{ app()->getLocale() === 'ar' ? 'كل ما تحتاجه لإدارة التعليم' : 'Everything You Need to Manage Education' }}
            </h2>
            <p class="section-subtitle">
                {{ app()->getLocale() === 'ar' 
                    ? 'أدوات شاملة مصممة خصيصاً للمؤسسات التعليمية الحديثة. من إدارة الملفات إلى التحليلات، لدينا كل ما تحتاجه.'
                    : 'Comprehensive tools designed specifically for modern educational institutions. From file management to analytics, we\'ve got you covered.' }}
            </p>
        </div>
        <div class="cards-grid">
            <div class="card">
                <div class="card-icon">
                    <i class="ri-building-4-line"></i>
                </div>
                <h3>{{ app()->getLocale() === 'ar' ? 'إدارة متعددة المستأجرين' : 'Multi-Tenant Management' }}</h3>
                <p>{{ app()->getLocale() === 'ar' ? 'إدارة الشبكات والمدارس والفروع من منصة واحدة.' : 'Manage networks, schools, and branches from a single platform.' }}</p>
            </div>
            <div class="card">
                <div class="card-icon">
                    <i class="ri-upload-cloud-line"></i>
                </div>
                <h3>{{ app()->getLocale() === 'ar' ? 'تقديم الملفات' : 'File Submissions' }}</h3>
                <p>{{ app()->getLocale() === 'ar' ? 'يرفع المعلمون العمل مع سير عمل تقديم منظم.' : 'Teachers upload work with structured submission workflows.' }}</p>
            </div>
            <div class="card">
                <div class="card-icon">
                    <i class="ri-file-check-line"></i>
                </div>
                <h3>{{ app()->getLocale() === 'ar' ? 'سير عمل المراجعة' : 'Review Workflows' }}</h3>
                <p>{{ app()->getLocale() === 'ar' ? 'يراجع المشرفون ويوافقون ويقدمون ملاحظات بسلاسة.' : 'Supervisors review, approve, and provide feedback seamlessly.' }}</p>
            </div>
            <div class="card">
                <div class="card-icon">
                    <i class="ri-dashboard-line"></i>
                </div>
                <h3>{{ app()->getLocale() === 'ar' ? 'لوحات تحكم المدير' : 'Admin Dashboards' }}</h3>
                <p>{{ app()->getLocale() === 'ar' ? 'أدوات تحليلية وإدارة قوية للمديرين.' : 'Powerful analytics and management tools for administrators.' }}</p>
            </div>
            <div class="card">
                <div class="card-icon">
                    <i class="ri-refresh-line"></i>
                </div>
                <h3>{{ app()->getLocale() === 'ar' ? 'تبديل الأدوار' : 'Role Switching' }}</h3>
                <p>{{ app()->getLocale() === 'ar' ? 'التبديل بين الأدوار فوراً داخل نفس المدرسة.' : 'Switch between roles instantly within the same school.' }}</p>
            </div>
            <div class="card">
                <div class="card-icon">
                    <i class="ri-global-line"></i>
                </div>
                <h3>{{ app()->getLocale() === 'ar' ? 'متعدد اللغات' : 'Multi-Language' }}</h3>
                <p>{{ app()->getLocale() === 'ar' ? 'دعم كامل للعربية والإنجليزية مع تخطيطات RTL.' : 'Full Arabic and English support with RTL layouts.' }}</p>
            </div>
            <div class="card">
                <div class="card-icon">
                    <i class="ri-wifi-off-line"></i>
                </div>
                <h3>{{ app()->getLocale() === 'ar' ? 'PWA والعمل دون اتصال' : 'PWA & Offline' }}</h3>
                <p>{{ app()->getLocale() === 'ar' ? 'يعمل دون اتصال كتطبيق ويب تقدمي.' : 'Works offline as a Progressive Web App.' }}</p>
            </div>
            <div class="card">
                <div class="card-icon">
                    <i class="ri-notification-line"></i>
                </div>
                <h3>{{ app()->getLocale() === 'ar' ? 'الإشعارات' : 'Notifications' }}</h3>
                <p>{{ app()->getLocale() === 'ar' ? 'تنبيهات فورية للطلبات والمراجعات والتحديثات.' : 'Real-time alerts for submissions, reviews, and updates.' }}</p>
            </div>
            <div class="card">
                <div class="card-icon">
                    <i class="ri-file-list-3-line"></i>
                </div>
                <h3>{{ app()->getLocale() === 'ar' ? 'سجلات النشاط' : 'Activity Logs' }}</h3>
                <p>{{ app()->getLocale() === 'ar' ? 'سجل تدقيق كامل لجميع الإجراءات والتغييرات.' : 'Complete audit trail of all actions and changes.' }}</p>
            </div>
            <div class="card">
                <div class="card-icon">
                    <i class="ri-shield-check-line"></i>
                </div>
                <h3>{{ app()->getLocale() === 'ar' ? 'المصادقة الآمنة' : 'Secure Auth' }}</h3>
                <p>{{ app()->getLocale() === 'ar' ? 'مصادقة على مستوى المؤسسة والتحكم في الوصول.' : 'Enterprise-grade authentication and access control.' }}</p>
            </div>
            <div class="card">
                <div class="card-icon">
                    <i class="ri-cloud-line"></i>
                </div>
                <h3>{{ app()->getLocale() === 'ar' ? 'التخزين السحابي' : 'Cloud Storage' }}</h3>
                <p>{{ app()->getLocale() === 'ar' ? 'تخزين ملفات آمن وقابل للتوسع مع وصول سهل.' : 'Secure, scalable file storage with easy access.' }}</p>
            </div>
            <div class="card">
                <div class="card-icon">
                    <i class="ri-user-settings-line"></i>
                </div>
                <h3>{{ app()->getLocale() === 'ar' ? 'إدارة المستخدمين' : 'User Management' }}</h3>
                <p>{{ app()->getLocale() === 'ar' ? 'تحكم كامل في المستخدمين والأدوار والأذونات.' : 'Complete control over users, roles, and permissions.' }}</p>
            </div>
            <div class="card">
                <div class="card-icon">
                    <i class="ri-smartphone-line"></i>
                </div>
                <h3>{{ app()->getLocale() === 'ar' ? 'تطبيقات الهاتف المحمول' : 'Mobile Apps' }}</h3>
                <p>{{ app()->getLocale() === 'ar' ? 'متاح على iOS و Android كتطبيق محمول أصلي.' : 'Available on iOS and Android as a native mobile app.' }}</p>
            </div>
        </div>
    </div>
</section>

<!-- Problem Section -->
<section id="problem" style="background: var(--bg-secondary);">
    <div class="container">
        <div class="section-header">
            <span class="section-badge" style="background: rgba(239, 68, 68, 0.1); color: var(--red);">{{ app()->getLocale() === 'ar' ? 'المشكلة' : 'The Problem' }}</span>
            <h2 class="section-title">
                {{ app()->getLocale() === 'ar' ? 'المدارس تكافح مع' : 'Schools Are Struggling With' }}
                <span class="highlight" style="color: var(--text-secondary); font-weight: 400;">{{ app()->getLocale() === 'ar' ? 'العمليات القديمة' : 'Outdated Processes' }}</span>
            </h2>
            <p class="section-subtitle">
                {{ app()->getLocale() === 'ar' 
                    ? 'إدارة التعليم التقليدية معطلة. سير العمل اليدوي والأدوات المتناثرة وعدم وجود رؤية تخلق الفوضى للجميع.'
                    : 'Traditional educational management is broken. Manual workflows, scattered tools, and lack of visibility create chaos for everyone.' }}
            </p>
        </div>
        <div class="cards-grid">
            <div class="problem-card">
                <div class="problem-icon">
                    <i class="ri-file-close-line"></i>
                </div>
                <h3>{{ app()->getLocale() === 'ar' ? 'سير العمل اليدوي' : 'Manual Workflows' }}</h3>
                <p>{{ app()->getLocale() === 'ar' ? 'العمليات القائمة على الورق تبطئ كل شيء وتخلق اختناقات.' : 'Paper-based processes slow everything down and create bottlenecks.' }}</p>
            </div>
            <div class="problem-card">
                <div class="problem-icon">
                    <i class="ri-message-3-line"></i>
                </div>
                <h3>{{ app()->getLocale() === 'ar' ? 'التواصل المجزأ' : 'Fragmented Communication' }}</h3>
                <p>{{ app()->getLocale() === 'ar' ? 'الرسائل متناثرة عبر المنصات، لا شيء مركزي.' : 'Messages scattered across platforms, nothing centralized.' }}</p>
            </div>
            <div class="problem-card">
                <div class="problem-icon">
                    <i class="ri-file-edit-line"></i>
                </div>
                <h3>{{ app()->getLocale() === 'ar' ? 'لا يوجد نظام مراجعة الملفات' : 'No File Review System' }}</h3>
                <p>{{ app()->getLocale() === 'ar' ? 'المعلمون يقدمون العمل دون حلقة مراجعة أو ملاحظات واضحة.' : 'Teachers submit work with no clear review or feedback loop.' }}</p>
            </div>
            <div class="problem-card">
                <div class="problem-icon">
                    <i class="ri-bar-chart-line"></i>
                </div>
                <h3>{{ app()->getLocale() === 'ar' ? 'لوحات التحكم مفقودة' : 'Missing Dashboards' }}</h3>
                <p>{{ app()->getLocale() === 'ar' ? 'المشرفون يفتقرون إلى الرؤية في أداء المعلم والتقدم.' : 'Supervisors lack visibility into teacher performance and progress.' }}</p>
            </div>
            <div class="problem-card">
                <div class="problem-icon">
                    <i class="ri-links-line"></i>
                </div>
                <h3>{{ app()->getLocale() === 'ar' ? 'أنظمة غير متصلة' : 'Disconnected Systems' }}</h3>
                <p>{{ app()->getLocale() === 'ar' ? 'أدوات متعددة لا تتزامن، مما يسبب صوامع البيانات.' : 'Multiple tools that don\'t sync, causing data silos.' }}</p>
            </div>
            <div class="problem-card">
                <div class="problem-icon">
                    <i class="ri-question-line"></i>
                </div>
                <h3>{{ app()->getLocale() === 'ar' ? 'مسؤوليات غير واضحة' : 'Unclear Responsibilities' }}</h3>
                <p>{{ app()->getLocale() === 'ar' ? 'المعلمون لا يعرفون ما يحتاج إلى الاهتمام أو المراجعة.' : 'Teachers don\'t know what needs attention or review.' }}</p>
            </div>
        </div>
    </div>
</section>

<!-- Solutions Section -->
<section id="solutions">
    <div class="container">
        <div class="section-header">
            <span class="section-badge" style="background: rgba(249, 115, 22, 0.1); color: var(--orange);">{{ app()->getLocale() === 'ar' ? 'الحلول' : 'Solutions' }}</span>
            <h2 class="section-title">
                {{ app()->getLocale() === 'ar' ? 'مصمم لكل دور في' : 'Built for Every Role in' }}
                <span class="highlight" style="color: var(--orange);">{{ app()->getLocale() === 'ar' ? 'مؤسستك' : 'Your Institution' }}</span>
            </h2>
            <p class="section-subtitle">
                {{ app()->getLocale() === 'ar' 
                    ? 'سواء كنت معلماً أو مشرفاً أو مديراً أو مالك شبكة، يوفر سكولدر أدوات مصممة خصيصاً لاحتياجاتك المحددة.'
                    : 'Whether you\'re a teacher, supervisor, admin, or network owner, Scholder provides tailored tools for your specific needs.' }}
            </p>
        </div>
        
        <!-- For Teachers -->
        <div class="role-section">
            <div class="role-content">
                <h3>{{ app()->getLocale() === 'ar' ? 'بسّط سير عملك اليومي' : 'Streamline your daily workflow' }}</h3>
                <h2>{{ app()->getLocale() === 'ar' ? 'للمعلمين' : 'For Teachers' }}</h2>
                <p>
                    {{ app()->getLocale() === 'ar' 
                        ? 'ارفع الملفات، وأدر المواد، وتتبع الملاحظات، وابق منظمًا بلوحة تحكم بديهية مصممة للمعلمين.'
                        : 'Upload files, manage subjects, track feedback, and stay organized with an intuitive dashboard designed for educators.' }}
                </p>
                <ul class="role-features">
                    <li><i class="ri-file-upload-line"></i> <span>{{ app()->getLocale() === 'ar' ? 'تقديم الملفات بسهولة' : 'Easy file submissions' }}</span></li>
                    <li><i class="ri-folder-line"></i> <span>{{ app()->getLocale() === 'ar' ? 'إدارة المواد' : 'Subject management' }}</span></li>
                    <li><i class="ri-chat-3-line"></i> <span>{{ app()->getLocale() === 'ar' ? 'تتبع الملاحظات' : 'Feedback tracking' }}</span></li>
                </ul>
                <a href="#" class="role-link">
                    {{ app()->getLocale() === 'ar' ? 'اعرف المزيد' : 'Learn more' }}
                    <i class="ri-arrow-right-line"></i>
                </a>
            </div>
            <div class="role-preview">
                <div class="role-preview-header"></div>
                <div class="role-preview-content">
                    <div class="role-preview-box"></div>
                    <div class="role-preview-box"></div>
                </div>
                <div class="role-preview-large"></div>
            </div>
        </div>
        
        <!-- For Supervisors -->
        <div class="role-section">
            <div class="role-preview">
                <div class="role-preview-header"></div>
                <div class="role-preview-content">
                    <div class="role-preview-box"></div>
                    <div class="role-preview-box"></div>
                </div>
                <div class="role-preview-large"></div>
            </div>
            <div class="role-content">
                <h3>{{ app()->getLocale() === 'ar' ? 'راقب بوضوح' : 'Oversee with clarity' }}</h3>
                <h2>{{ app()->getLocale() === 'ar' ? 'للمشرفين' : 'For Supervisors' }}</h2>
                <p>
                    {{ app()->getLocale() === 'ar' 
                        ? 'راجع الملفات المقدمة، وراقب أداء المعلمين، وقدم ملاحظات بنية لوحة تحكم مخصصة.'
                        : 'Review submitted files, monitor teacher performance, provide feedback with a dedicated dashboard.' }}
                </p>
                <ul class="role-features">
                    <li><i class="ri-file-search-line"></i> <span>{{ app()->getLocale() === 'ar' ? 'مراجعة الملفات المقدمة' : 'Review submitted files' }}</span></li>
                    <li><i class="ri-user-star-line"></i> <span>{{ app()->getLocale() === 'ar' ? 'مراقبة أداء المعلم' : 'Monitor teacher performance' }}</span></li>
                    <li><i class="ri-feedback-line"></i> <span>{{ app()->getLocale() === 'ar' ? 'تقديم ملاحظات' : 'Provide feedback' }}</span></li>
                </ul>
                <a href="#" class="role-link">
                    {{ app()->getLocale() === 'ar' ? 'اعرف المزيد' : 'Learn more' }}
                    <i class="ri-arrow-right-line"></i>
                </a>
            </div>
        </div>
        
        <!-- For Network Owners -->
        <div class="role-section">
            <div class="role-content">
                <h3>{{ app()->getLocale() === 'ar' ? 'الإشراف المؤسسي' : 'Enterprise oversight' }}</h3>
                <h2>{{ app()->getLocale() === 'ar' ? 'لأصحاب الشبكات' : 'For Network Owners' }}</h2>
                <p>
                    {{ app()->getLocale() === 'ar' 
                        ? 'أدر مدارس متعددة، واعرض سجلات النشاط، وانتحل شخصية المستخدمين للدعم، وامنح الاشتراكات على نطاق واسع.'
                        : 'Manage multiple schools, view activity logs, impersonate users for support, and control subscriptions at scale.' }}
                </p>
                <ul class="role-features">
                    <li><i class="ri-group-line"></i> <span>{{ app()->getLocale() === 'ar' ? 'إدارة المدارس المتعددة' : 'Multi-school management' }}</span></li>
                    <li><i class="ri-settings-3-line"></i> <span>{{ app()->getLocale() === 'ar' ? 'التحكم في الاشتراكات' : 'Subscription control' }}</span></li>
                    <li><i class="ri-global-line"></i> <span>{{ app()->getLocale() === 'ar' ? 'أدوات المؤسسة' : 'Enterprise tools' }}</span></li>
                </ul>
                <a href="#" class="role-link">
                    {{ app()->getLocale() === 'ar' ? 'اعرف المزيد' : 'Learn more' }}
                    <i class="ri-arrow-right-line"></i>
                </a>
            </div>
            <div class="role-preview">
                <div class="role-preview-header"></div>
                <div class="role-preview-content">
                    <div class="role-preview-box"></div>
                    <div class="role-preview-box"></div>
                </div>
                <div class="role-preview-large"></div>
            </div>
        </div>
    </div>
</section>

<!-- How It Works -->
<section id="how-it-works" style="background: var(--bg-secondary);">
    <div class="container">
        <div class="section-header">
            <span class="section-badge">{{ app()->getLocale() === 'ar' ? 'كيف يعمل' : 'How It Works' }}</span>
            <h2 class="section-title">
                {{ app()->getLocale() === 'ar' ? 'ابدأ في ثلاث خطوات بسيطة' : 'Get Started in Three Simple Steps' }}
            </h2>
            <p class="section-subtitle">
                {{ app()->getLocale() === 'ar' 
                    ? 'من التسجيل إلى الإنتاجية في دقائق. لا حاجة لإعداد معقد.'
                    : 'From signup to productivity in minutes. No complex setup required.' }}
            </p>
        </div>
        <div class="steps-grid">
            <div class="step-card">
                <div class="step-number">01</div>
                <div class="step-icon">
                    <i class="ri-user-add-line"></i>
                </div>
                <h3>{{ app()->getLocale() === 'ar' ? 'انضم إلى شبكتك' : 'Join Your Network' }}</h3>
                <p>
                    {{ app()->getLocale() === 'ar' 
                        ? 'سجّل واحصل على دعوة لشبكتك أو مدرستك. سيقوم مديرك بتعيينك للفرع الصحيح.'
                        : 'Sign up and get invited to your network or school. Your admin will assign you to the right branch.' }}
                </p>
            </div>
            <div class="step-card">
                <div class="step-arrow"><i class="ri-arrow-right-line"></i></div>
                <div class="step-number">02</div>
                <div class="step-icon">
                    <i class="ri-user-settings-line"></i>
                </div>
                <h3>{{ app()->getLocale() === 'ar' ? 'اختر دورك' : 'Choose Your Role' }}</h3>
                <p>
                    {{ app()->getLocale() === 'ar' 
                        ? 'اختر دورك - معلم أو مشرف أو مدير. بدّل بين الأدوار في أي وقت داخل مدرستك.'
                        : 'Select your role - teacher, supervisor, or admin. Switch between roles anytime within your school.' }}
                </p>
            </div>
            <div class="step-card">
                <div class="step-arrow"><i class="ri-arrow-right-line"></i></div>
                <div class="step-number">03</div>
                <div class="step-icon">
                    <i class="ri-dashboard-line"></i>
                </div>
                <h3>{{ app()->getLocale() === 'ar' ? 'الوصول إلى لوحة التحكم' : 'Access Your Dashboard' }}</h3>
                <p>
                    {{ app()->getLocale() === 'ar' 
                        ? 'احصل على وصول فوري إلى لوحة التحكم المخصصة مع جميع الأدوات التي تحتاجها للنجاح.'
                        : 'Get instant access to your personalized dashboard with all the tools you need to succeed.' }}
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Product Preview -->
<section id="product-preview" class="product-preview">
    <div class="container">
        <div class="section-header">
            <span class="section-badge" style="background: rgba(249, 115, 22, 0.1); color: var(--orange);">{{ app()->getLocale() === 'ar' ? 'معاينة المنتج' : 'Product Preview' }}</span>
            <h2 class="section-title">
                {{ app()->getLocale() === 'ar' ? 'شاهد سكولدر' : 'See Scholder' }}
                <span class="highlight" style="color: var(--orange);">{{ app()->getLocale() === 'ar' ? 'في العمل' : 'In Action' }}</span>
            </h2>
            <p class="section-subtitle">
                {{ app()->getLocale() === 'ar' 
                    ? 'استكشف الواجهات البديهية المصممة لكل نوع من المستخدمين.'
                    : 'Explore the intuitive interfaces designed for every user type.' }}
            </p>
        </div>
        <div class="preview-tabs">
            <a href="#" class="preview-tab active" data-tab="teacher">{{ app()->getLocale() === 'ar' ? 'لوحة تحكم المعلم' : 'Teacher Dashboard' }}</a>
            <a href="#" class="preview-tab" data-tab="supervisor">{{ app()->getLocale() === 'ar' ? 'لوحة تحكم المشرف' : 'Supervisor Dashboard' }}</a>
            <a href="#" class="preview-tab" data-tab="admin">{{ app()->getLocale() === 'ar' ? 'لوحة تحكم المدير' : 'Admin Dashboard' }}</a>
        </div>
        <div class="preview-mockup">
            <div class="mockup-header">
                <div class="mockup-dot red"></div>
                <div class="mockup-dot yellow"></div>
                <div class="mockup-dot green"></div>
                <div class="mockup-url" id="mockup-url">app.scholder.io/teacher</div>
            </div>
            <div class="mockup-content">
                <div class="preview-tab-content active" id="teacher-preview">
                    <img src="https://i.ibb.co/LXpMz4pJ/teacherdashboard.jpg" alt="{{ app()->getLocale() === 'ar' ? 'لوحة تحكم المعلم' : 'Teacher Dashboard' }}" class="mockup-image">
                </div>
                <div class="preview-tab-content" id="supervisor-preview">
                    <img src="https://i.ibb.co/PvN7fBFc/supervisordashboard.jpg" alt="{{ app()->getLocale() === 'ar' ? 'لوحة تحكم المشرف' : 'Supervisor Dashboard' }}" class="mockup-image">
                </div>
                <div class="preview-tab-content" id="admin-preview">
                    <img src="https://i.ibb.co/V06dBrH0/admindashboard.jpg" alt="{{ app()->getLocale() === 'ar' ? 'لوحة تحكم المدير' : 'Admin Dashboard' }}" class="mockup-image">
                </div>
            </div>
        </div>
        <p class="preview-description" id="preview-description">
            {{ app()->getLocale() === 'ar' 
                ? 'ارفع الملفات، وتتبع الطلبات، واستقبل الملاحظات.'
                : 'Upload files, track submissions, and receive feedback.' }}
        </p>
    </div>
</section>

<!-- Security Section -->
<section id="security" class="security-section">
    <div class="container">
        <div class="section-header">
            <span class="section-badge" style="background: rgba(255, 255, 255, 0.1); color: rgba(255, 255, 255, 0.9);">{{ app()->getLocale() === 'ar' ? 'الأمان والموثوقية' : 'Security & Reliability' }}</span>
            <h2 class="section-title">
                {{ app()->getLocale() === 'ar' ? 'الأمان على مستوى المؤسسة' : 'Enterprise-Grade' }}
                <span class="highlight" style="color: var(--teal-light);">{{ app()->getLocale() === 'ar' ? 'الأمان مدمج' : 'Security Built In' }}</span>
            </h2>
            <p class="section-subtitle">
                {{ app()->getLocale() === 'ar' 
                    ? 'بياناتك محمية بإجراءات أمنية رائدة في الصناعة. نأخذ الأمان على محمل الجد حتى تتمكن من التركيز على التعليم.'
                    : 'Your data is protected with industry-leading security measures. We take security seriously so you can focus on education.' }}
            </p>
        </div>
        <div class="cards-grid">
            <div class="security-card">
                <div class="security-icon">
                    <i class="ri-cloud-line"></i>
                </div>
                <h3>{{ app()->getLocale() === 'ar' ? 'مستضاف على السحابة' : 'Cloud Hosted' }}</h3>
                <p>{{ app()->getLocale() === 'ar' ? 'بنية تحتية موثوقة مع 99.9% من وقت التشغيل' : 'Reliable infrastructure with 99.9% uptime' }}</p>
            </div>
            <div class="security-card">
                <div class="security-icon">
                    <i class="ri-shield-check-line"></i>
                </div>
                <h3>{{ app()->getLocale() === 'ar' ? 'مصادقة آمنة' : 'Secure Authentication' }}</h3>
                <p>{{ app()->getLocale() === 'ar' ? 'مصادقة على مستوى المؤسسة مع دعم MFA' : 'Enterprise-grade auth with MFA support' }}</p>
            </div>
            <div class="security-card">
                <div class="security-icon">
                    <i class="ri-lock-line"></i>
                </div>
                <h3>{{ app()->getLocale() === 'ar' ? 'الوصول القائم على الأدوار' : 'Role-Based Access' }}</h3>
                <p>{{ app()->getLocale() === 'ar' ? 'أذونات دقيقة لكل مستخدم' : 'Granular permissions for every user' }}</p>
            </div>
            <div class="security-card">
                <div class="security-icon">
                    <i class="ri-line-chart-line"></i>
                </div>
                <h3>{{ app()->getLocale() === 'ar' ? 'سجلات النشاط' : 'Activity Logs' }}</h3>
                <p>{{ app()->getLocale() === 'ar' ? 'سجل تدقيق كامل لجميع الإجراءات' : 'Complete audit trail of all actions' }}</p>
            </div>
            <div class="security-card">
                <div class="security-icon">
                    <i class="ri-building-2-line"></i>
                </div>
                <h3>{{ app()->getLocale() === 'ar' ? 'متعدد المستأجرين' : 'Multi-Tenant' }}</h3>
                <p>{{ app()->getLocale() === 'ar' ? 'بيانات معزولة لكل منظمة' : 'Isolated data for each organization' }}</p>
            </div>
            <div class="security-card">
                <div class="security-icon">
                    <i class="ri-file-lock-line"></i>
                </div>
                <h3>{{ app()->getLocale() === 'ar' ? 'تشفير البيانات' : 'Data Encryption' }}</h3>
                <p>{{ app()->getLocale() === 'ar' ? 'تشفير من طرف إلى طرف في الراحة والعبور' : 'End-to-end encryption at rest and transit' }}</p>
            </div>
        </div>
        <div class="compliance-badges">
            <div class="compliance-badge">
                <div class="compliance-icon">
                    <i class="ri-checkbox-circle-line"></i>
                </div>
                <span>SOC 2 Compliant</span>
            </div>
            <div class="compliance-badge">
                <div class="compliance-icon">
                    <i class="ri-lock-line"></i>
                </div>
                <span>GDPR Ready</span>
            </div>
            <div class="compliance-badge">
                <div class="compliance-icon">
                    <i class="ri-file-shield-line"></i>
                </div>
                <span>ISO 27001</span>
            </div>
        </div>
    </div>
</section>

<!-- Pricing Section -->
<section id="pricing">
    <div class="container">
        <div class="section-header">
            <span class="section-badge">{{ app()->getLocale() === 'ar' ? 'الأسعار' : 'Pricing' }}</span>
            <h2 class="section-title">
                {{ app()->getLocale() === 'ar' ? 'أسعار بسيطة وشفافة' : 'Simple, Transparent' }}
                <span class="highlight">{{ app()->getLocale() === 'ar' ? 'الأسعار' : 'Pricing' }}</span>
            </h2>
            <p class="section-subtitle">
                {{ app()->getLocale() === 'ar' 
                    ? 'اختر الخطة التي تناسب احتياجاتك. تتضمن جميع الخطط الميزات الأساسية بدون رسوم مخفية.'
                    : 'Choose the plan that fits your needs. All plans include core features with no hidden fees.' }}
            </p>
        </div>
        <div class="pricing-grid">
            <div class="pricing-card">
                <div class="pricing-icon">
                    <i class="ri-school-line"></i>
                </div>
                <h3>{{ app()->getLocale() === 'ar' ? 'خطة المدرسة' : 'School Plan' }}</h3>
                <p>{{ app()->getLocale() === 'ar' ? 'مثالي للمدارس الفردية التي تبدأ' : 'Perfect for individual schools getting started.' }}</p>
                <div class="pricing-amount">{{ app()->getLocale() === 'ar' ? 'اتصل بنا' : 'Contact us' }}<br><small style="font-size: 16px; font-weight: 400;">{{ app()->getLocale() === 'ar' ? 'لكل مدرسة/شهر' : 'per school/month' }}</small></div>
                <ul class="pricing-features">
                    <li>{{ app()->getLocale() === 'ar' ? 'حتى 100 مستخدم' : 'Up to 100 users' }}</li>
                    <li>{{ app()->getLocale() === 'ar' ? 'تقديم ومراجعة الملفات' : 'File submissions & reviews' }}</li>
                    <li>{{ app()->getLocale() === 'ar' ? 'لوحات تحكم المعلم والمشرف' : 'Teacher & supervisor dashboards' }}</li>
                    <li>{{ app()->getLocale() === 'ar' ? 'تحليلات أساسية' : 'Basic analytics' }}</li>
                    <li>{{ app()->getLocale() === 'ar' ? 'دعم البريد الإلكتروني' : 'Email support' }}</li>
                    <li>{{ app()->getLocale() === 'ar' ? 'دعم العربية والإنجليزية' : 'Arabic & English support' }}</li>
                </ul>
                <a href="#" class="pricing-btn pricing-btn-secondary">{{ app()->getLocale() === 'ar' ? 'ابدأ الآن' : 'Get Started' }}</a>
            </div>
            <div class="pricing-card popular">
                <div class="popular-badge">{{ app()->getLocale() === 'ar' ? 'الأكثر شعبية' : 'Most Popular' }}</div>
                <div class="pricing-icon">
                    <i class="ri-group-line"></i>
                </div>
                <h3>{{ app()->getLocale() === 'ar' ? 'خطة الشبكة' : 'Network Plan' }}</h3>
                <p>{{ app()->getLocale() === 'ar' ? 'للشبكات التي تدير مدارس متعددة' : 'For networks managing multiple schools.' }}</p>
                <div class="pricing-amount">{{ app()->getLocale() === 'ar' ? 'اتصل بنا' : 'Contact us' }}<br><small style="font-size: 16px; font-weight: 400;">{{ app()->getLocale() === 'ar' ? 'لكل شبكة/شهر' : 'per network/month' }}</small></div>
                <ul class="pricing-features">
                    <li>{{ app()->getLocale() === 'ar' ? 'مدارس غير محدودة' : 'Unlimited schools' }}</li>
                    <li>{{ app()->getLocale() === 'ar' ? 'مستخدمون غير محدودون' : 'Unlimited users' }}</li>
                    <li>{{ app()->getLocale() === 'ar' ? 'تحليلات متقدمة' : 'Advanced analytics' }}</li>
                    <li>{{ app()->getLocale() === 'ar' ? 'سجلات النشاط ومسارات التدقيق' : 'Activity logs & audit trails' }}</li>
                    <li>{{ app()->getLocale() === 'ar' ? 'انتحال شخصية المستخدم' : 'User impersonation' }}</li>
                    <li>{{ app()->getLocale() === 'ar' ? 'دعم ذو أولوية' : 'Priority support' }}</li>
                    <li>{{ app()->getLocale() === 'ar' ? 'علامة تجارية مخصصة' : 'Custom branding' }}</li>
                    <li>{{ app()->getLocale() === 'ar' ? 'وصول API' : 'API access' }}</li>
                </ul>
                <a href="#" class="pricing-btn pricing-btn-primary">{{ app()->getLocale() === 'ar' ? 'ابدأ الآن' : 'Get Started' }}</a>
            </div>
            <div class="pricing-card">
                <div class="pricing-icon">
                    <i class="ri-star-line"></i>
                </div>
                <h3>{{ app()->getLocale() === 'ar' ? 'المؤسسة' : 'Enterprise' }}</h3>
                <p>{{ app()->getLocale() === 'ar' ? 'حلول مخصصة للمنظمات الكبيرة' : 'Custom solutions for large organizations.' }}</p>
                <div class="pricing-amount">{{ app()->getLocale() === 'ar' ? 'أسعار مخصصة' : 'Custom tailored' }}<br><small style="font-size: 16px; font-weight: 400;">{{ app()->getLocale() === 'ar' ? 'التسعير' : 'pricing' }}</small></div>
                <ul class="pricing-features">
                    <li>{{ app()->getLocale() === 'ar' ? 'كل شيء في الشبكة' : 'Everything in Network' }}</li>
                    <li>{{ app()->getLocale() === 'ar' ? 'بنية تحتية مخصصة' : 'Dedicated infrastructure' }}</li>
                    <li>{{ app()->getLocale() === 'ar' ? 'تكاملات مخصصة' : 'Custom integrations' }}</li>
                    <li>{{ app()->getLocale() === 'ar' ? 'ضمان SLA' : 'SLA guarantee' }}</li>
                    <li>{{ app()->getLocale() === 'ar' ? 'مدير نجاح مخصص' : 'Dedicated success manager' }}</li>
                    <li>{{ app()->getLocale() === 'ar' ? 'خيار على الموقع' : 'On-premise option' }}</li>
                    <li>{{ app()->getLocale() === 'ar' ? 'التدريب والبدء' : 'Training & onboarding' }}</li>
                    <li>{{ app()->getLocale() === 'ar' ? 'دعم هاتفي 24/7' : '24/7 phone support' }}</li>
                </ul>
                <a href="#" class="pricing-btn pricing-btn-secondary">{{ app()->getLocale() === 'ar' ? 'اتصل بالمبيعات' : 'Contact Sales' }}</a>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section id="cta" class="cta-section">
    <div class="container">
        <h2>{{ app()->getLocale() === 'ar' ? 'انضم إلى سكولدر اليوم' : 'Join Scholder Today' }}</h2>
        <p>
            {{ app()->getLocale() === 'ar' 
                ? 'امنح مدارسك أدوات تعليمية حديثة. غيّر طريقة إدارة مؤسستك لسير العمل والمراجعات والتعاون.'
                : 'Empower your schools with modern educational tools. Transform how your institution manages workflows, reviews, and collaboration.' }}
        </p>
        <div class="cta-buttons">
            <a href="#contact" class="cta-btn cta-btn-primary">
                {{ app()->getLocale() === 'ar' ? 'ابدأ مجاناً' : 'Get Started Free' }}
                <i class="ri-arrow-right-line"></i>
            </a>
            <a href="mailto:sales@scholder.io" class="cta-btn cta-btn-secondary">
                <i class="ri-mail-line"></i>
                {{ app()->getLocale() === 'ar' ? 'اتصل بالمبيعات' : 'Contact Sales' }}
            </a>
        </div>
        <p class="cta-note">
            {{ app()->getLocale() === 'ar' 
                ? 'لا حاجة لبطاقة ائتمان • مجاني للمعلمين الأفراد'
                : 'No credit card required • Free for individual teachers' }}
        </p>
    </div>
</section>

<!-- Footer -->
<footer>
    <div class="container">
        <div class="footer-content">
            <div class="footer-brand">
                <h3>
                    <div class="logo-icon">
                        <i class="ri-graduation-cap-line"></i>
                    </div>
                    Scholder
                </h3>
                <p>
                    {{ app()->getLocale() === 'ar' 
                        ? 'منصة إدارة التعليم من الجيل القادم للمدارس والشبكات والمعلمين في جميع أنحاء العالم.'
                        : 'The next-generation educational management platform for schools, networks, and educators worldwide.' }}
                </p>
                <div class="footer-lang">
                    <i class="ri-global-line"></i>
                    <span>{{ app()->getLocale() === 'ar' ? 'English | العربية' : 'English | العربية' }}</span>
                </div>
            </div>
            <div class="footer-column">
                <h4>{{ app()->getLocale() === 'ar' ? 'المنتج' : 'Product' }}</h4>
                <ul>
                    <li><a href="#features">{{ app()->getLocale() === 'ar' ? 'المميزات' : 'Features' }}</a></li>
                    <li><a href="#pricing">{{ app()->getLocale() === 'ar' ? 'الأسعار' : 'Pricing' }}</a></li>
                    <li><a href="#security">{{ app()->getLocale() === 'ar' ? 'الأمان' : 'Security' }}</a></li>
                    <li><a href="#product-preview">{{ app()->getLocale() === 'ar' ? 'معاينة المنتج' : 'Product Preview' }}</a></li>
                </ul>
            </div>
            <div class="footer-column">
                <h4>{{ app()->getLocale() === 'ar' ? 'الشركة' : 'Company' }}</h4>
                <ul>
                    <li><a href="#">{{ app()->getLocale() === 'ar' ? 'من نحن' : 'About' }}</a></li>
                    <li><a href="#">{{ app()->getLocale() === 'ar' ? 'المدونة' : 'Blog' }}</a></li>
                    <li><a href="#">{{ app()->getLocale() === 'ar' ? 'الوظائف' : 'Careers' }}</a></li>
                    <li><a href="#">{{ app()->getLocale() === 'ar' ? 'الصحافة' : 'Press' }}</a></li>
                </ul>
            </div>
            <div class="footer-column">
                <h4>{{ app()->getLocale() === 'ar' ? 'الموارد' : 'Resources' }}</h4>
                <ul>
                    <li><a href="#">{{ app()->getLocale() === 'ar' ? 'التوثيق' : 'Documentation' }}</a></li>
                    <li><a href="#">{{ app()->getLocale() === 'ar' ? 'مركز المساعدة' : 'Help Center' }}</a></li>
                    <li><a href="#">{{ app()->getLocale() === 'ar' ? 'مرجع API' : 'API Reference' }}</a></li>
                    <li><a href="#">{{ app()->getLocale() === 'ar' ? 'الحالة' : 'Status' }}</a></li>
                </ul>
            </div>
            <div class="footer-column">
                <h4>{{ app()->getLocale() === 'ar' ? 'قانوني' : 'Legal' }}</h4>
                <ul>
                    <li><a href="#">{{ app()->getLocale() === 'ar' ? 'سياسة الخصوصية' : 'Privacy Policy' }}</a></li>
                    <li><a href="#">{{ app()->getLocale() === 'ar' ? 'شروط الخدمة' : 'Terms of Service' }}</a></li>
                    <li><a href="#">{{ app()->getLocale() === 'ar' ? 'سياسة ملفات تعريف الارتباط' : 'Cookie Policy' }}</a></li>
                    <li><a href="#">{{ app()->getLocale() === 'ar' ? 'GDPR' : 'GDPR' }}</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>© {{ date('Y') }} Scholder. {{ app()->getLocale() === 'ar' ? 'جميع الحقوق محفوظة' : 'All rights reserved' }}.</p>
            <div class="footer-availability">
                <i class="ri-checkbox-circle-line"></i>
                <span>{{ app()->getLocale() === 'ar' ? 'متاح بالعربية والإنجليزية' : 'Available in Arabic & English' }}</span>
            </div>
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

// Product Preview Tabs
const tabDescriptions = {
    teacher: {
        en: 'Upload files, track submissions, and receive feedback.',
        ar: 'ارفع الملفات، وتتبع الطلبات، واستقبل الملاحظات.'
    },
    supervisor: {
        en: 'Review files, monitor teacher performance, and provide structured feedback.',
        ar: 'راجع الملفات، راقب أداء المعلمين، وقدم ملاحظات منظمة.'
    },
    admin: {
        en: 'Manage schools, view analytics, control subscriptions, and oversee operations.',
        ar: 'أدر المدارس، اعرض التحليلات، تحكم في الاشتراكات، وأشرف على العمليات.'
    }
};

const urlMap = {
    teacher: 'app.scholder.io/teacher',
    supervisor: 'app.scholder.io/supervisor',
    admin: 'app.scholder.io/admin'
};

document.querySelectorAll('.preview-tab').forEach(tab => {
    tab.addEventListener('click', function(e) {
        e.preventDefault();
        const tabType = this.getAttribute('data-tab');
        
        // Update active tab
        document.querySelectorAll('.preview-tab').forEach(t => t.classList.remove('active'));
        this.classList.add('active');
        
        // Update preview content
        document.querySelectorAll('.preview-tab-content').forEach(content => {
            content.classList.remove('active');
        });
        document.getElementById(tabType + '-preview').classList.add('active');
        
        // Update URL
        document.getElementById('mockup-url').textContent = urlMap[tabType];
        
        // Update description
        const locale = '{{ app()->getLocale() }}';
        document.getElementById('preview-description').textContent = tabDescriptions[tabType][locale];
    });
});
</script>
</body>
</html>
