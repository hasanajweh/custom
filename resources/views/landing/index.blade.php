<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5">
    <meta name="description" content="Scholder (سكولدر) - Your gateway to the digital school. Secure file management for schools in Qatar, Jordan, and Palestine.">
    <meta property="og:title" content="Scholder - Cloud File Management for Schools">
    <meta property="og:description" content="Step into the future of school administration with Scholder, the premier cloud platform for educational institutions.">
    <meta property="og:type" content="website">
    <title>Scholder - Your Gateway to the Digital School</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Playfair+Display:wght@700;900&family=Tajawal:wght@400;500;700;800;900&display=swap" rel="stylesheet">
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
            --red: #EF4444;
            --orange: #F59E0B;
            --green: #10B981;
            --blue: #3B82F6;
            --purple: #8B5CF6;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body {
            font-family: 'Inter', sans-serif;
            color: var(--text);
            background: var(--bg);
            line-height: 1.6;
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        [lang="ar"] body { font-family: 'Tajawal', sans-serif; }

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
            animation: gradientShift 15s ease infinite;
            opacity: 0.03;
        }
        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        /* Floating Elements */
        .floating-elements {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            overflow: hidden;
            pointer-events: none;
        }
        .float-element {
            position: absolute;
            opacity: 0.08;
            animation: float 20s infinite ease-in-out;
        }
        @keyframes float {
            0%, 100% { transform: translate(0, 0) rotate(0deg); }
            33% { transform: translate(30px, -30px) rotate(120deg); }
            66% { transform: translate(-20px, 20px) rotate(240deg); }
        }

        /* Container - Mobile Responsive */
        .container { 
            max-width: 1280px; 
            margin: 0 auto; 
            padding: 0 20px; 
            position: relative; 
            z-index: 1; 
        }
        .container-wide { 
            max-width: 1400px; 
            margin: 0 auto; 
            padding: 0 20px; 
            position: relative; 
            z-index: 1; 
        }
        section { 
            padding: 80px 0; 
            position: relative; 
            z-index: 1; 
        }
        .section-header { 
            text-align: center; 
            max-width: 900px; 
            margin: 0 auto 40px; 
        }

        /* Typography - Mobile Responsive */
        h1, h2, h3 { font-weight: 800; }
        h1 { 
            font-size: clamp(36px, 8vw, 72px); 
            line-height: 1.1; 
            margin-bottom: 24px; 
            color: var(--text); 
        }
        h2 { 
            font-size: clamp(32px, 6vw, 56px); 
            margin-bottom: 20px; 
            color: var(--text); 
            line-height: 1.2; 
        }
        h3 { 
            font-size: clamp(20px, 4vw, 28px); 
            margin-bottom: 16px; 
        }

        .logo-text {
            font-family: 'Playfair Display', serif;
            font-size: clamp(28px, 5vw, 42px);
            font-weight: 900;
            background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            letter-spacing: -0.02em;
        }

        .gradient-text {
            background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .section-header p { 
            font-size: clamp(16px, 3vw, 20px); 
            color: var(--text-secondary); 
            line-height: 1.7; 
        }

        /* Header - Mobile Responsive */
        header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            position: sticky;
            top: 0;
            z-index: 100;
            border-bottom: 1px solid var(--border);
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
        }
        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px 0;
            position: relative;
        }

        /* Mobile Menu Toggle */
        .mobile-menu-toggle {
            display: none;
            background: none;
            border: none;
            cursor: pointer;
            padding: 8px;
            margin-left: 12px;
        }
        .mobile-menu-toggle svg {
            width: 24px;
            height: 24px;
            fill: var(--text);
        }

        /* Language Toggle - Mobile Responsive */
        .lang-toggle {
            display: flex;
            gap: 4px;
            background: var(--bg-secondary);
            padding: 4px;
            border-radius: 12px;
            border: 1px solid var(--border);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        }
        .lang-btn {
            padding: 8px 16px;
            border: none;
            background: transparent;
            cursor: pointer;
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.3s ease;
            color: var(--text-secondary);
            font-size: 14px;
            white-space: nowrap;
        }
        .lang-btn.active {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
            transform: translateY(-1px);
        }

        /* Hero Section - Mobile Responsive */
        .hero {
            padding: 60px 0;
            background: linear-gradient(180deg, var(--bg-secondary) 0%, var(--bg) 100%);
            position: relative;
            overflow: hidden;
        }
        .hero-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 40px;
            align-items: center;
        }
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
            border-radius: 50px;
            color: white;
            font-weight: 600;
            font-size: 14px;
            margin-bottom: 20px;
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.05); opacity: 0.9; }
        }
        .hero p {
            font-size: clamp(18px, 3vw, 22px);
            line-height: 1.7;
            color: var(--text-secondary);
            margin-bottom: 32px;
        }
        .cta-group {
            display: flex;
            gap: 16px;
            flex-wrap: wrap;
        }
        .btn-primary, .btn-secondary {
            padding: 16px 32px;
            border: none;
            border-radius: 16px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            min-height: 54px;
            white-space: nowrap;
        }
        .btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            box-shadow: 0 8px 24px rgba(79, 70, 229, 0.3), 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 32px rgba(79, 70, 229, 0.4), 0 4px 12px rgba(0, 0, 0, 0.15);
        }
        .btn-secondary {
            background: white;
            color: var(--primary);
            border: 2px solid var(--border);
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        }
        .btn-secondary:hover {
            background: var(--bg-secondary);
            transform: translateY(-2px);
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.12);
        }

        /* Hero Visual - Mobile Responsive */
        .hero-visual {
            position: relative;
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
        }
        .dashboard-preview {
            background: white;
            border-radius: 24px;
            box-shadow: 0 40px 120px rgba(0, 0, 0, 0.15), 0 8px 32px rgba(0, 0, 0, 0.08);
            padding: 20px;
            position: relative;
            overflow: hidden;
            transform: perspective(1000px) rotateY(-5deg) rotateX(5deg);
            transition: transform 0.5s ease;
        }
        .dashboard-preview:hover {
            transform: perspective(1000px) rotateY(0) rotateX(0) scale(1.02);
        }
        .dashboard-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 20px;
        }
        .window-controls {
            display: flex;
            gap: 8px;
        }
        .window-controls span {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            display: block;
        }
        .dashboard-navbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: var(--bg-secondary);
            padding: 12px 16px;
            border-radius: 12px;
            margin-bottom: 20px;
        }
        .nav-links {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }
        .nav-link {
            padding: 8px 14px;
            color: var(--text-secondary);
            font-size: 14px;
            font-weight: 500;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        .nav-link.active {
            background: white;
            color: var(--primary);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        /* Stats Grid - Mobile Responsive */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
            gap: 16px;
            margin-bottom: 24px;
        }
        .stat-card {
            background: linear-gradient(135deg, var(--bg-secondary) 0%, white 100%);
            padding: 16px;
            border-radius: 16px;
            border: 1px solid var(--border);
            transition: all 0.3s ease;
        }
        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
        }
        .stat-label {
            font-size: 12px;
            color: var(--text-light);
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
        }
        .stat-value {
            font-size: clamp(24px, 4vw, 32px);
            font-weight: 900;
            background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .stat-change {
            font-size: 12px;
            color: var(--green);
            display: flex;
            align-items: center;
            gap: 4px;
            margin-top: 6px;
        }

        /* Files Preview - Mobile Responsive */
        .files-preview {
            background: var(--bg-secondary);
            border-radius: 16px;
            padding: 20px;
        }
        .files-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
            flex-wrap: wrap;
            gap: 12px;
        }
        .files-header h4 {
            font-size: 16px;
            font-weight: 700;
            color: var(--text);
        }
        .file-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px;
            background: white;
            border-radius: 12px;
            margin-bottom: 8px;
            border: 1px solid var(--border);
            transition: all 0.3s ease;
        }
        .file-item:hover {
            transform: translateX(4px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }
        .file-info {
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 0;
        }
        .file-icon {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .file-details h5 {
            font-size: 14px;
            font-weight: 600;
            color: var(--text);
            margin-bottom: 2px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .file-details span {
            font-size: 12px;
            color: var(--text-light);
        }

        /* Floating Cards - Mobile Responsive */
        .floating-card {
            position: absolute;
            background: white;
            border-radius: 16px;
            padding: 12px 16px;
            box-shadow: 0 16px 48px rgba(0, 0, 0, 0.15), 0 4px 16px rgba(0, 0, 0, 0.08);
            z-index: 10;
            animation: floatCard 4s ease-in-out infinite;
            display: none;
        }
        @keyframes floatCard {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        /* Features Section - Mobile Responsive */
        .features-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 24px;
            margin-top: 40px;
        }
        .feature-card {
            background: white;
            padding: 32px 24px;
            border-radius: 24px;
            border: 1px solid var(--border);
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
            transition: all 0.3s ease;
            opacity: 0;
            transform: translateY(20px);
        }
        .feature-card.animate-in {
            opacity: 1;
            transform: translateY(0);
        }
        .feature-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15), 0 8px 24px rgba(0, 0, 0, 0.08);
            border-color: var(--primary-light);
        }
        .feature-icon {
            width: 64px;
            height: 64px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
        }
        .feature-icon svg {
            width: 32px;
            height: 32px;
            fill: white;
        }
        .feature-card h3 {
            font-size: 22px;
            margin-bottom: 12px;
            color: var(--text);
        }
        .feature-card p {
            color: var(--text-secondary);
            line-height: 1.6;
            font-size: 15px;
        }

        /* Scenarios Section - Mobile Responsive */
        .scenarios-container {
            background: var(--bg-secondary);
            border-radius: 32px;
            padding: 40px 24px;
            box-shadow: 0 8px 40px rgba(0, 0, 0, 0.08);
        }
        .scenarios-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 20px;
            margin-top: 32px;
        }
        .scenario-card {
            background: white;
            padding: 24px;
            border-radius: 20px;
            border: 1px solid var(--border);
            transition: all 0.3s ease;
            cursor: pointer;
            opacity: 0;
            transform: translateY(20px);
        }
        .scenario-card.animate-in {
            opacity: 1;
            transform: translateY(0);
        }
        .scenario-card:hover {
            transform: translateY(-4px) scale(1.02);
            box-shadow: 0 16px 48px rgba(0, 0, 0, 0.12);
            border-color: var(--accent-light);
        }
        .scenario-header {
            display: flex;
            align-items: flex-start;
            gap: 16px;
            margin-bottom: 16px;
        }
        .scenario-icon {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .scenario-icon svg {
            width: 24px;
            height: 24px;
            fill: white;
        }
        .scenario-card h4 {
            font-size: 18px;
            margin-bottom: 8px;
            color: var(--text);
        }
        .scenario-card p {
            color: var(--text-secondary);
            font-size: 14px;
            line-height: 1.5;
        }

        /* Pricing Section - Mobile Responsive */
        .pricing-header {
            text-align: center;
            margin-bottom: 40px;
        }
        .billing-toggle-wrapper {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 16px;
            margin-top: 24px;
            flex-wrap: wrap;
        }
        .billing-label {
            font-size: 16px;
            font-weight: 600;
            color: var(--text-secondary);
            transition: color 0.3s ease;
        }
        .billing-label.active {
            color: var(--text);
        }
        .billing-toggle {
            position: relative;
            width: 64px;
            height: 32px;
            background: var(--bg-tertiary);
            border-radius: 50px;
            cursor: pointer;
            transition: all 0.3s ease;
            border: 2px solid var(--border);
        }
        .billing-toggle.yearly {
            background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
        }
        .billing-toggle::after {
            content: '';
            position: absolute;
            top: 3px;
            left: 3px;
            width: 24px;
            height: 24px;
            background: white;
            border-radius: 50%;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        }
        .billing-toggle.yearly::after {
            left: calc(100% - 27px);
        }
        .save-badge {
            background: linear-gradient(135deg, var(--green) 0%, var(--accent) 100%);
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 700;
            animation: pulse 2s infinite;
        }

        /* Pricing Cards - Mobile Responsive */
        .pricing-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 24px;
            margin-top: 40px;
        }
        .price-card {
            background: white;
            border-radius: 24px;
            padding: 32px 24px;
            border: 2px solid var(--border);
            position: relative;
            transition: all 0.3s ease;
            opacity: 0;
            transform: translateY(20px);
        }
        .price-card.animate-in {
            opacity: 1;
            transform: translateY(0);
        }
        .price-card.featured {
            border-color: var(--primary);
            box-shadow: 0 20px 60px rgba(79, 70, 229, 0.2), 0 8px 24px rgba(79, 70, 229, 0.1);
            transform: scale(1.02);
        }
        .popular-badge {
            position: absolute;
            top: -12px;
            left: 50%;
            transform: translateX(-50%);
            background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
            color: white;
            padding: 6px 20px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 700;
            box-shadow: 0 4px 16px rgba(79, 70, 229, 0.3);
        }
        .price-card h3 {
            font-size: 24px;
            margin-bottom: 8px;
            color: var(--text);
        }
        .price-card .subtitle {
            color: var(--text-secondary);
            font-size: 14px;
            margin-bottom: 24px;
        }
        .price {
            display: flex;
            align-items: baseline;
            gap: 8px;
            margin-bottom: 32px;
        }
        .price-currency {
            font-size: 20px;
            color: var(--text-secondary);
            font-weight: 600;
        }
        .price-value {
            font-size: clamp(36px, 6vw, 48px);
            font-weight: 900;
            color: var(--text);
        }
        .price-period {
            font-size: 16px;
            color: var(--text-secondary);
        }
        .features-list {
            list-style: none;
            margin-bottom: 32px;
        }
        .features-list li {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 0;
            border-bottom: 1px solid var(--bg-secondary);
            font-size: 15px;
            color: var(--text);
        }
        .features-list li:last-child {
            border-bottom: none;
        }
        .check-icon {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--green) 0%, var(--accent) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .check-icon svg {
            width: 12px;
            height: 12px;
            fill: white;
        }
        .btn-choose {
            width: 100%;
            padding: 16px;
            border: 2px solid var(--border);
            background: white;
            color: var(--primary);
            border-radius: 16px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .btn-choose:hover {
            background: var(--bg-secondary);
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
        }
        .price-card.featured .btn-choose {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            border: none;
            box-shadow: 0 8px 24px rgba(79, 70, 229, 0.3);
        }
        .price-card.featured .btn-choose:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 32px rgba(79, 70, 229, 0.4);
        }

        /* Regional Coverage Section - Mobile Responsive */
        .coverage-map {
            background: var(--bg-secondary);
            border-radius: 32px;
            padding: 40px 24px;
            position: relative;
            overflow: hidden;
        }
        .map-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 300px;
            position: relative;
            margin-bottom: 32px;
        }
        .map-svg {
            width: 100%;
            max-width: 500px;
            height: auto;
        }
        .country-marker {
            position: absolute;
            width: 16px;
            height: 16px;
            background: var(--accent);
            border: 3px solid white;
            border-radius: 50%;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            animation: pulse 2s infinite;
            cursor: pointer;
        }
        .country-marker::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 40px;
            height: 40px;
            background: var(--accent);
            border-radius: 50%;
            transform: translate(-50%, -50%);
            opacity: 0.3;
            animation: ripple 2s infinite;
        }
        @keyframes ripple {
            0% { transform: translate(-50%, -50%) scale(1); opacity: 0.3; }
            100% { transform: translate(-50%, -50%) scale(2.5); opacity: 0; }
        }
        .country-tooltip {
            position: absolute;
            background: white;
            padding: 12px 16px;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
            white-space: nowrap;
            font-weight: 600;
            color: var(--text);
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease;
            transform: translate(-50%, -140%);
        }
        .country-marker:hover .country-tooltip {
            opacity: 1;
        }

        /* Map legend */
        .map-legend {
            display: flex;
            justify-content: center;
            gap: 24px;
            flex-wrap: wrap;
        }
        .map-legend > div {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .legend-indicator {
            width: 16px;
            height: 16px;
            border-radius: 4px;
        }
        .legend-text {
            font-size: 14px;
            font-weight: 600;
            color: var(--text-secondary);
        }

        /* Footer - Mobile Responsive */
        footer {
            background: var(--text);
            color: white;
            padding: 60px 0 30px;
            margin-top: 80px;
        }
        .footer-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 40px;
            margin-bottom: 40px;
        }
        .footer-brand h3 {
            font-size: clamp(24px, 4vw, 32px);
            margin-bottom: 8px;
            font-weight: 900;
        }
        .footer-brand p {
            color: rgba(255, 255, 255, 0.7);
            margin-bottom: 20px;
            font-size: 14px;
        }
        .footer-email {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            color: white;
            text-decoration: none;
            padding: 12px 24px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            transition: all 0.3s ease;
            font-size: 14px;
        }
        .footer-email:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
        }
        .footer-section h4 {
            font-size: 16px;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .footer-section .location-name {
            font-size: 14px;
            color: rgba(255, 255, 255, 0.7);
        }
        .inline-flag {
            width: 24px;
            height: auto;
            border-radius: 4px;
        }
        .footer-bottom {
            padding-top: 32px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            text-align: center;
        }
        .footer-bottom p {
            color: rgba(255, 255, 255, 0.7);
            font-size: 14px;
            line-height: 1.8;
        }

        /* Counters Section */
        .counters {
            margin-top: 24px;
            display: grid;
            gap: 20px;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            align-items: center;
        }
        .counter-card {
            background: white;
            padding: 28px;
            border-radius: 16px;
            border: 1px solid var(--border);
            text-align: center;
            box-shadow: 0 12px 36px rgba(0,0,0,0.06);
            opacity: 0;
            transform: translateY(20px);
            transition: all 0.6s cubic-bezier(.2,.9,.2,1);
        }
        .counter-card.animate-in {
            opacity: 1;
            transform: translateY(0);
        }
        .counter-value {
            font-size: clamp(28px, 6vw, 44px);
            font-weight: 900;
            background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 6px;
        }
        .counter-label {
            font-weight: 700;
            color: var(--text-secondary);
            font-size: 14px;
        }

        /* Responsive Breakpoints */
        @media (min-width: 640px) {
            .container, .container-wide { padding: 0 32px; }
            section { padding: 100px 0; }
            .section-header { margin-bottom: 60px; }
            
            .hero { padding: 100px 0; }
            .hero-grid { gap: 60px; }
            
            .features-grid { grid-template-columns: repeat(2, 1fr); gap: 32px; }
            .scenarios-grid { grid-template-columns: repeat(2, 1fr); gap: 24px; }
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
            
            .lang-btn { padding: 10px 20px; font-size: 15px; }
            .btn-primary, .btn-secondary { padding: 18px 36px; font-size: 17px; }
        }

        @media (min-width: 768px) {
            .mobile-menu-toggle { display: none; }
            
            .hero-grid { 
                grid-template-columns: 1.1fr 1fr; 
                gap: 80px; 
            }
            
            .floating-card { display: block; }
            .floating-card:nth-child(1) { top: 10%; right: -20px; }
            .floating-card:nth-child(2) { bottom: 20%; left: -20px; }
            
            .pricing-grid { grid-template-columns: repeat(2, 1fr); gap: 32px; }
            .footer-grid { 
                grid-template-columns: 2fr repeat(3, 1fr); 
                gap: 48px; 
            }
        }

        @media (min-width: 1024px) {
            .container { padding: 0 40px; }
            .container-wide { padding: 0 40px; }
            section { padding: 120px 0; }
            .section-header { margin-bottom: 80px; }
            
            .features-grid { grid-template-columns: repeat(3, 1fr); gap: 40px; }
            .scenarios-grid { grid-template-columns: repeat(3, 1fr); gap: 32px; }
            .pricing-grid { grid-template-columns: repeat(3, 1fr); gap: 40px; }
            .stats-grid { grid-template-columns: repeat(4, 1fr); }
            
            .hero { padding: 140px 0 120px; }
            .hero-grid { gap: 100px; }
        }

        @media (max-width: 767px) {
            .mobile-menu-toggle { 
                display: block; 
            }
            
            .nav-links {
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                background: white;
                border-top: 1px solid var(--border);
                box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
                padding: 20px;
                display: none;
                flex-direction: column;
                gap: 12px;
            }
            
            .nav-links.active {
                display: flex;
            }
            
            .nav-link {
                width: 100%;
                text-align: center;
                padding: 12px;
            }
            
            .cta-group {
                flex-direction: column;
                width: 100%;
            }
            
            .btn-primary, .btn-secondary {
                width: 100%;
                justify-content: center;
            }
            
            .price-card.featured {
                transform: scale(1);
            }
            
            .dashboard-preview {
                transform: none;
            }
            
            .dashboard-preview:hover {
                transform: scale(1);
            }
        }

        /* RTL Support */
        [dir="rtl"] {
            text-align: right;
        }
        
        [dir="rtl"] .cta-group {
            direction: rtl;
        }
        
        [dir="rtl"] .features-list li,
        [dir="rtl"] .file-info,
        [dir="rtl"] .scenario-header {
            flex-direction: row-reverse;
        }
        
        [dir="rtl"] .stat-change {
            flex-direction: row-reverse;
        }
        
        [dir="rtl"] .nav-links {
            flex-direction: row-reverse;
        }
        
        [dir="rtl"] .mobile-menu-toggle {
            margin-left: 0;
            margin-right: 12px;
        }
    </style>
</head>
<body>
<div class="gradient-bg"></div>
<div class="floating-elements" id="floatingElements"></div>

<header>
    <nav class="container">
        <div class="logo-text">Scholder</div>
        <div class="lang-toggle">
            <button class="lang-btn active" data-lang="en">English</button>
            <button class="lang-btn" data-lang="ar">العربية</button>
        </div>
        <button class="mobile-menu-toggle" id="mobileMenuToggle" aria-label="Toggle menu">
            <svg viewBox="0 0 24 24"><path d="M3 12h18M3 6h18M3 18h18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
        </button>
    </nav>
</header>

<section class="hero" id="hero">
    <div class="container">
        <div class="hero-grid">
            <div>
                <div class="badge">
                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                    <span data-en="Now Serving 3 Countries" data-ar="نخدم الآن 3 دول">Now Serving 3 Countries</span>
                </div>
                <h1>
                    <span data-en="Your Gateway to the" data-ar="بوابتك إلى">Your Gateway to the</span>
                    <span class="gradient-text" data-en="Digital School" data-ar="المدرسة الرقمية">Digital School</span>
                </h1>
                <p data-en="Revolutionize school administration with our comprehensive cloud-based file management platform. Secure, efficient, and designed specifically for educational institutions across the Middle East." data-ar="أحدث ثورة في إدارة المدرسة مع منصتنا الشاملة لإدارة الملفات السحابية. آمنة وفعالة ومصممة خصيصًا للمؤسسات التعليمية في الشرق الأوسط.">
                    Revolutionize school administration with our comprehensive cloud-based file management platform. Secure, efficient, and designed specifically for educational institutions across the Middle East.
                </p>
                <div class="cta-group">
                    <button class="btn-primary">
                        <span data-en="Start Free Trial" data-ar="ابدأ التجربة المجانية">Start Free Trial</span>
                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24"><path d="M5 12h14m-7-7l7 7-7 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </button>
                    <!-- Watch Demo removed as requested -->
                </div>
            </div>
            
            <div class="hero-visual">
                <div class="dashboard-preview">
                    <div class="dashboard-header">
                        <div class="window-controls">
                            <span style="background: #FF5F56;"></span>
                            <span style="background: #FFBD2E;"></span>
                            <span style="background: #27C93F;"></span>
                        </div>
                        <span style="font-size: 14px; color: var(--text-secondary); font-weight: 600;">Scholder Dashboard</span>
                    </div>
                    
                    <div class="dashboard-navbar">
                        <div class="nav-links">
                            <span class="nav-link active" data-en="Overview" data-ar="نظرة عامة">Overview</span>
                            <span class="nav-link" data-en="Files" data-ar="الملفات">Files</span>
                            <span class="nav-link" data-en="Reports" data-ar="التقارير">Reports</span>
                        </div>
                    </div>
                    
                    <div class="stats-grid">
                        <div class="stat-card">
                            <div class="stat-label" data-en="Total Files" data-ar="إجمالي الملفات">Total Files</div>
                            <div class="stat-value">12.4K</div>
                            <div class="stat-change">
                                <svg width="12" height="12" fill="currentColor" viewBox="0 0 24 24"><path d="M7 14l5-5 5 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                <span>+24%</span>
                            </div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-label" data-en="Active Users" data-ar="المستخدمون النشطون">Active Users</div>
                            <div class="stat-value">847</div>
                            <div class="stat-change">
                                <svg width="12" height="12" fill="currentColor" viewBox="0 0 24 24"><path d="M7 14l5-5 5 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                <span>+18%</span>
                            </div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-label" data-en="Storage Used" data-ar="التخزين المستخدم">Storage Used</div>
                            <div class="stat-value">2.8TB</div>
                            <div class="stat-change">
                                <svg width="12" height="12" fill="currentColor" viewBox="0 0 24 24"><path d="M7 14l5-5 5 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                <span>+32%</span>
                            </div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-label" data-en="Departments" data-ar="الأقسام">Departments</div>
                            <div class="stat-value">24</div>
                            <div class="stat-change">
                                <svg width="12" height="12" fill="currentColor" viewBox="0 0 24 24"><path d="M7 14l5-5 5 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                <span>+4</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="files-preview">
                        <div class="files-header">
                            <h4 data-en="Recent Files" data-ar="الملفات الحديثة">Recent Files</h4>
                            <span style="color: var(--primary); font-size: 14px; font-weight: 600; cursor: pointer;" data-en="View All" data-ar="عرض الكل">View All →</span>
                        </div>
                        <div class="file-item">
                            <div class="file-info">
                                <div class="file-icon" style="background: linear-gradient(135deg, #667eea, #764ba2);">
                                    <svg width="20" height="20" fill="white" viewBox="0 0 24 24"><path d="M14 2H6c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V8l-6-6z"/></svg>
                                </div>
                                <div class="file-details">
                                    <h5 data-en="Annual Report 2024.pdf" data-ar="التقرير السنوي 2024.pdf">Annual Report 2024.pdf</h5>
                                    <span>2.4 MB • PDF</span>
                                </div>
                            </div>
                        </div>
                        <div class="file-item">
                            <div class="file-info">
                                <div class="file-icon" style="background: linear-gradient(135deg, #f093fb, #f5576c);">
                                    <svg width="20" height="20" fill="white" viewBox="0 0 24 24"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2z"/></svg>
                                </div>
                                <div class="file-details">
                                    <!-- student -> operational -->
                                    <h5 data-en="Operational Records.xlsx" data-ar="سجلات عمليات.xlsx">Operational Records.xlsx</h5>
                                    <span>847 KB • Excel</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="floating-card" style="top: 10%; right: -20px;">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <div style="width: 40px; height: 40px; background: linear-gradient(135deg, var(--green), var(--accent)); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                            <svg width="24" height="24" fill="white" viewBox="0 0 24 24"><path d="M9 16.2l-3.5-3.5L4 14.2l5 5 9-9-1.4-1.4z"/></svg>
                        </div>
                        <div>
                            <div style="font-size: 12px; color: var(--text-light); margin-bottom: 2px;">Security</div>
                            <div style="font-weight: 700; color: var(--text);">256-bit Encryption</div>
                        </div>
                    </div>
                </div>
                
                <div class="floating-card" style="bottom: 20%; left: -20px;">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <div style="width: 40px; height: 40px; background: linear-gradient(135deg, var(--blue), var(--purple)); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                            <svg width="24" height="24" fill="white" viewBox="0 0 24 24"><path d="M20.5 11H19V7c0-1.1-.9-2-2-2h-4V3.5C13 2.12 11.88 1 10.5 1S8 2.12 8 3.5V5H4c-1.1 0-2 .9-2 2v3.8h1.5c1.5 0 2.7 1.2 2.7 2.7S5 16.2 3.5 16.2H2V20c0 1.1.9 2 2 2h3.8v-1.5c0-1.5 1.2-2.7 2.7-2.7 1.5 0 2.7 1.2 2.7 2.7V22H17c1.1 0 2-.9 2-2v-4h1.5c1.38 0 2.5-1.12 2.5-2.5S21.88 11 20.5 11z"/></svg>
                        </div>
                        <div>
                            <div style="font-size: 12px; color: var(--text-light); margin-bottom: 2px;">Integration</div>
                            <div style="font-weight: 700; color: var(--text);">API Available</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- COUNTERS SECTION (ADDED RIGHT BELOW HERO) -->
        <div class="counters container-wide" id="countersSection" aria-hidden="false" style="margin-top:32px;">
            <div class="counter-card" data-target="149">
                <div class="counter-value" data-format="int">0</div>
                <div class="counter-label">Schools</div>
            </div>
            <div class="counter-card" data-target="585000">
                <div class="counter-value" data-format="k-plus">0</div>
                <div class="counter-label">Files</div>
            </div>
            <div class="counter-card" data-target="3700">
                <div class="counter-value" data-format="k-plus-decimal">0</div>
                <div class="counter-label">Users (avg 25 / school)</div>
            </div>
        </div>
    </div>
</section>

<section id="features">
    <div class="container">
        <div class="section-header">
            <h2 class="gradient-text" data-en="Powerful Features for Modern Schools" data-ar="ميزات قوية للمدارس الحديثة">Powerful Features for Modern Schools</h2>
            <p data-en="Everything you need to digitize and streamline your institution's document management in one comprehensive platform." data-ar="كل ما تحتاجه لرقمنة وتبسيط إدارة مستندات مؤسستك في منصة واحدة شاملة.">
                Everything you need to digitize and streamline your institution's document management in one comprehensive platform.
            </p>
        </div>
        
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon" style="background: linear-gradient(135deg, #667eea, #764ba2);">
                    <svg fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
                </div>
                <h3 data-en="Bank-Level Security" data-ar="أمان على مستوى البنوك">Bank-Level Security</h3>
                <p data-en="256-bit encryption, multi-factor authentication, and continuous security monitoring to protect your sensitive administrative data." data-ar="تشفير 256 بت، مصادقة متعددة العوامل، ومراقبة أمنية مستمرة لحماية بياناتك الإدارية الحساسة.">
                    256-bit encryption, multi-factor authentication, and continuous security monitoring to protect your sensitive administrative data.
                </p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon" style="background: linear-gradient(135deg, #f093fb, #f5576c);">
                    <svg fill="currentColor" viewBox="0 0 24 24"><path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z"/></svg>
                </div>
                <h3 data-en="Smart Organization" data-ar="تنظيم ذكي">Smart Organization</h3>
                <p data-en="AI-powered categorization, custom tags, and intelligent search to find any document in seconds." data-ar="تصنيف بالذكاء الاصطناعي، علامات مخصصة، وبحث ذكي للعثور على أي مستند في ثوانٍ.">
                    AI-powered categorization, custom tags, and intelligent search to find any document in seconds.
                </p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon" style="background: linear-gradient(135deg, #4facfe, #00f2fe);">
                    <svg fill="currentColor" viewBox="0 0 24 24"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5s-3 1.34-3 3 1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg>
                </div>
                <h3 data-en="Seamless Collaboration" data-ar="تعاون سلس">Seamless Collaboration</h3>
                <p data-en="Real-time file sharing, version control, and team workflows designed for administrators and staff." data-ar="مشاركة الملفات في الوقت الفعلي، التحكم في الإصدارات، وسير عمل الفريق المصمم للإداريين والموظفين.">
                    Real-time file sharing, version control, and team workflows designed for administrators and staff.
                </p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon" style="background: linear-gradient(135deg, #fa709a, #fee140);">
                    <svg fill="currentColor" viewBox="0 0 24 24"><path d="M19.14 12.94c.04-.3.06-.61.06-.94 0-.32-.02-.64-.07-.94l2.03-1.58c.18-.14.23-.41.12-.61l-1.92-3.32c-.12-.22-.37-.29-.59-.22l-2.39.96c-.5-.38-1.03-.7-1.62-.94l-.36-2.54c-.04-.24-.24-.41-.48-.41h-3.84c-.24 0-.43.17-.47.41l-.36 2.54c-.59.24-1.13.57-1.62.94l-2.39-.96c-.22-.08-.47 0-.59.22L2.74 8.87c-.12.21-.08.47.12.61l2.03 1.58c-.05.3-.09.63-.09.94s.02.64.07.94l-2.03 1.58c-.18.14-.23.41-.12.61l1.92 3.32c.12.22.37.29.59.22l2.39-.96c.5.38 1.03.7 1.62.94l.36 2.54c.05.24.24.41.48.41h3.84c.24 0 .44-.17.47-.41l.36-2.54c.59-.24 1.13-.56 1.62-.94l2.39.96c.22.08.47 0 .59-.22l1.92-3.32c.12-.22.07-.47-.12-.61l-2.01-1.58zM12 15.6c-1.98 0-3.6-1.62-3.6-3.6s1.62-3.6 3.6-3.6 3.6 1.62 3.6 3.6-1.62 3.6-3.6 3.6z"/></svg>
                </div>
                <h3 data-en="Automated Workflows" data-ar="سير عمل آلي">Automated Workflows</h3>
                <p data-en="Streamline repetitive tasks with custom automation rules, scheduled reports, and smart notifications." data-ar="تبسيط المهام المتكررة مع قواعد الأتمتة المخصصة، والتقارير المجدولة، والإشعارات الذكية.">
                    Streamline repetitive tasks with custom automation rules, scheduled reports, and smart notifications.
                </p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon" style="background: linear-gradient(135deg, #a8edea, #fed6e3);">
                    <svg fill="currentColor" viewBox="0 0 24 24"><path d="M3 17v2h6v-2H3zM3 5v2h10V5H3zm10 16v-2h8v-2h-8v-2h-2v6h2zM7 9v2H3v2h4v2h2V9H7zm14 4v-2H11v2h10zm-6-4h2V7h4V5h-4V3h-2v6z"/></svg>
                </div>
                <h3 data-en="Custom Permissions" data-ar="صلاحيات مخصصة">Custom Permissions</h3>
                <p data-en="Granular access controls for teachers, supervisors, administrators, and secretaries with role-based permissions." data-ar="تحكم دقيق في الوصول للمعلمين والمشرفين والإداريين والسكرتاريين مع صلاحيات قائمة على الأدوار.">
                    Granular access controls for teachers, supervisors, administrators, and secretaries with role-based permissions.
                </p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon" style="background: linear-gradient(135deg, #ffecd2, #fcb69f);">
                    <svg fill="currentColor" viewBox="0 0 24 24"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zM9 17H7v-7h2v7zm4 0h-2V7h2v10zm4 0h-2v-4h2v4z"/></svg>
                </div>
                <h3 data-en="Detailed Analytics" data-ar="تحليلات مفصلة">Detailed Analytics</h3>
                <p data-en="Comprehensive insights into document usage, user activity, and storage trends to optimize your operations." data-ar="رؤى شاملة حول استخدام المستندات ونشاط المستخدمين واتجاهات التخزين لتحسين عملياتك.">
                    Comprehensive insights into document usage, user activity, and storage trends to optimize your operations.
                </p>
            </div>
        </div>
    </div>
</section>

<section id="scenarios">
    <div class="container">
        <div class="section-header">
            <h2 class="gradient-text" data-en="Built for Every Administrative Scenario" data-ar="مصمم لكل سيناريو إداري">Built for Every Administrative Scenario</h2>
            <p data-en="From operations to administrative excellence, Scholder adapts to the needs of teachers, supervisors, secretaries and administrators." data-ar="من العمليات إلى التميز الإداري، سكولدر يتكيف مع احتياجات المعلمين والمشرفين والسكرتاريين والإداريين.">
                From operations to administrative excellence, Scholder adapts to the needs of teachers, supervisors, secretaries and administrators.
            </p>
        </div>
        
        <div class="scenarios-container">
            <div class="scenarios-grid">
                <div class="scenario-card">
                    <div class="scenario-header">
                        <div class="scenario-icon" style="background: linear-gradient(135deg, var(--primary), var(--accent));">
                            <svg fill="currentColor" viewBox="0 0 24 24"><path d="M5 13.18v4L12 21l7-3.82v-4L12 17l-7-3.82zM12 3L1 9l11 6 9-4.91V17h2V9L12 3z"/></svg>
                        </div>
                        <div>
                            <h4 data-en="Records Management" data-ar="إدارة السجلات">Records Management</h4>
                        </div>
                    </div>
                    <p data-en="Centralize contracts, institutional forms, and administrative histories with secure, instant access for authorized personnel." data-ar="مركزية العقود والنماذج والسجلات الإدارية مع وصول آمن وفوري للموظفين المصرح لهم.">
                        Centralize contracts, institutional forms, and administrative histories with secure, instant access for authorized personnel.
                    </p>
                </div>
                
                <div class="scenario-card">
                    <div class="scenario-header">
                        <div class="scenario-icon" style="background: linear-gradient(135deg, var(--orange), var(--red));">
                            <svg fill="currentColor" viewBox="0 0 24 24"><path d="M17 12h-5v5h5v-5zM16 1v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2h-1V1h-2zm3 18H5V8h14v11z"/></svg>
                        </div>
                        <div>
                            <h4 data-en="Event & Meeting Ops" data-ar="تشغيل الفعاليات والاجتماعات">Event & Meeting Ops</h4>
                        </div>
                    </div>
                    <p data-en="Coordinate events, meetings, and activities with shared calendars and central document repositories." data-ar="تنسيق الفعاليات والاجتماعات والأنشطة مع التقويمات المشتركة ومستودعات المستندات المركزية.">
                        Coordinate events, meetings, and activities with shared calendars and central document repositories.
                    </p>
                </div>
                
                <div class="scenario-card">
                    <div class="scenario-header">
                        <div class="scenario-icon" style="background: linear-gradient(135deg, var(--green), var(--accent));">
                            <svg fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1.41 16.09V20h-2.67v-1.93c-1.71-.36-3.16-1.46-3.27-3.4h1.96c.1.81.45 1.61 1.67 1.61 1.16 0 1.6-.64 1.6-1.46 0-.84-.68-1.22-1.88-1.54-2.63-.71-3.84-1.58-3.84-3.31 0-1.51 1.22-2.65 2.96-3.01V5h2.67v1.95c1.86.45 2.79 1.86 2.85 3.39H14.3c-.05-1.11-.64-1.63-1.63-1.63-1.01 0-1.46.59-1.46 1.28 0 .64.55 1.04 1.84 1.37 2.78.72 3.97 1.64 3.97 3.52 0 1.58-1.17 2.83-3.01 3.21z"/></svg>
                        </div>
                        <div>
                            <h4 data-en="Financial Documents" data-ar="المستندات المالية">Financial Documents</h4>
                        </div>
                    </div>
                    <p data-en="Manage budgets, invoices, and financial reports with audit trails and compliance-ready documentation." data-ar="إدارة الميزانيات والفواتير والتقارير المالية مع سجلات التدقيق والوثائق الجاهزة للامتثال.">
                        Manage budgets, invoices, and financial reports with audit trails and compliance-ready documentation.
                    </p>
                </div>
                
                <div class="scenario-card">
                    <div class="scenario-header">
                        <div class="scenario-icon" style="background: linear-gradient(135deg, var(--blue), var(--purple));">
                            <svg fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                        </div>
                        <div>
                            <h4 data-en="Quality & Compliance" data-ar="الجودة والامتثال">Quality & Compliance</h4>
                        </div>
                    </div>
                    <p data-en="Track accreditation documents, policy updates, and compliance reports in one organized system." data-ar="تتبع وثائق الاعتماد وتحديثات السياسات وتقارير الامتثال في نظام واحد منظم.">
                        Track accreditation documents, policy updates, and compliance reports in one organized system.
                    </p>
                </div>
                
                <div class="scenario-card">
                    <div class="scenario-header">
                        <div class="scenario-icon" style="background: linear-gradient(135deg, #f093fb, #f5576c);">
                            <svg fill="currentColor" viewBox="0 0 24 24"><path d="M9 11H7v2h2v-2zm4 0h-2v2h2v-2zm4 0h-2v2h2v-2zm2-7h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V9h14v11z"/></svg>
                        </div>
                        <div>
                            <h4 data-en="HR & Staff Records" data-ar="الموارد البشرية والسجلات">HR & Staff Records</h4>
                        </div>
                    </div>
                    <p data-en="Store contracts, certifications, and staff records securely while maintaining privacy and accessibility." data-ar="تخزين العقود والشهادات وسجلات الموظفين بأمان مع الحفاظ على الخصوصية وإمكانية الوصول.">
                        Store contracts, certifications, and staff records securely while maintaining privacy and accessibility.
                    </p>
                </div>
                
                <div class="scenario-card">
                    <div class="scenario-header">
                        <div class="scenario-icon" style="background: linear-gradient(135deg, #4facfe, #00f2fe);">
                            <svg fill="currentColor" viewBox="0 0 24 24"><path d="M19 3h-4.18C14.4 1.84 13.3 1 12 1c-1.3 0-2.4.84-2.82 2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 0c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zm2 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/></svg>
                        </div>
                        <div>
                            <h4 data-en="Resource Planning" data-ar="تخطيط الموارد">Resource Planning</h4>
                        </div>
                    </div>
                    <p data-en="Collaborate on resources, plans, and materials across departments with easy file sharing and version control." data-ar="التعاون حول الموارد والخطط والمواد عبر الأقسام مع مشاركة الملفات وإدارة الإصدارات بسهولة.">
                        Collaborate on resources, plans, and materials across departments with easy file sharing and version control.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Pricing and other sections kept intact (student references removed earlier) -->
<section id="pricing">
    <div class="container">
        <div class="pricing-header">
            <h2 class="gradient-text" data-en="Simple, Transparent Pricing" data-ar="تسعير بسيط وشفاف">Simple, Transparent Pricing</h2>
            <p data-en="Choose the perfect plan for your institution's size and needs. All plans include core features and support." data-ar="اختر الخطة المثالية لحجم مؤسستك واحتياجاتها. جميع الخطط تتضمن الميزات الأساسية والدعم.">
                Choose the perfect plan for your institution's size and needs. All plans include core features and support.
            </p>
            <div class="billing-toggle-wrapper">
                <span class="billing-label monthly-label active" data-en="Monthly" data-ar="شهري">Monthly</span>
                <div class="billing-toggle" id="billingToggle"></div>
                <span class="billing-label yearly-label" data-en="Yearly" data-ar="سنوي">Yearly</span>
                <span class="save-badge" data-en="Save 20%" data-ar="وفر 20%">Save 20%</span>
            </div>
        </div>
        
        <div class="pricing-grid">
            <div class="price-card">
                <h3 data-en="Starter" data-ar="البداية">Starter</h3>
                <p class="subtitle" data-en="Perfect for small institutions" data-ar="مثالي للجهات الصغيرة">Perfect for small institutions</p>
                <div class="price">
                    <span class="price-currency">$</span>
                    <span class="price-value" data-monthly="49" data-yearly="470">49</span>
                    <span class="price-period" data-en="/month" data-ar="/شهر">/month</span>
                </div>
                <ul class="features-list">
                    <li>
                        <div class="check-icon">
                            <svg fill="currentColor" viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                        </div>
                        <span data-en="Up to 100 users" data-ar="حتى 100 مستخدم">Up to 100 users</span>
                    </li>
                    <li>
                        <div class="check-icon">
                            <svg fill="currentColor" viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                        </div>
                        <span data-en="500 GB storage" data-ar="500 جيجابايت تخزين">500 GB storage</span>
                    </li>
                    <li>
                        <div class="check-icon">
                            <svg fill="currentColor" viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                        </div>
                        <span data-en="Basic support" data-ar="دعم أساسي">Basic support</span>
                    </li>
                </ul>
                <button class="btn-choose">Choose Starter</button>
            </div>

            <div class="price-card featured">
                <div class="popular-badge" data-en="Most Popular" data-ar="الأكثر شعبية">Most Popular</div>
                <h3 data-en="Pro" data-ar="المحترف">Pro</h3>
                <p class="subtitle" data-en="For growing institutions" data-ar="للمؤسسات النامية">For growing institutions</p>
                <div class="price">
                    <span class="price-currency">$</span>
                    <span class="price-value" data-monthly="199" data-yearly="1990">199</span>
                    <span class="price-period" data-en="/month" data-ar="/شهر">/month</span>
                </div>
                <ul class="features-list">
                    <li>
                        <div class="check-icon">
                            <svg fill="currentColor" viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                        </div>
                        <span data-en="Up to 1000 users" data-ar="حتى 1000 مستخدم">Up to 1000 users</span>
                    </li>
                    <li>
                        <div class="check-icon">
                            <svg fill="currentColor" viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                        </div>
                        <span data-en="5 TB storage" data-ar="5 تيرابايت تخزين">5 TB storage</span>
                    </li>
                    <li>
                        <div class="check-icon">
                            <svg fill="currentColor" viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                        </div>
                        <span data-en="Priority support & onboarding" data-ar="دعم onboarding أولوية">Priority support & onboarding</span>
                    </li>
                </ul>
                <button class="btn-choose">Choose Pro</button>
            </div>

            
        </div>
    </div>
</section>

<section id="coverage">
    <div class="container">
        <div class="section-header">
            <h2 class="gradient-text" data-en="Regional Coverage" data-ar="التغطية الإقليمية">Regional Coverage</h2>
            <p data-en="We operate in multiple countries across the region with localized support and compliance." data-ar="نحن نعمل في عدة دول عبر المنطقة مع دعم محلي ومتوافق.">
                We operate in multiple countries across the region with localized support and compliance.
            </p>
        </div>

        <div class="coverage-map">
            <div class="map-container">
                <!-- simplified illustrative SVG map -->
                <svg class="map-svg" viewBox="0 0 800 400" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <rect width="100%" height="100%" fill="transparent"/>
                    <!-- placeholder shapes to represent map -->
                    <g fill="none" stroke="rgba(0,0,0,0.06)">
                        <path d="M50 300C150 200 250 250 350 200C450 150 550 200 650 150C750 100 750 50 750 50" />
                    </g>
                </svg>

                <div class="country-marker" style="left: 20%; top: 40%;">
                    <div class="country-tooltip">Palestine</div>
                </div>
                <div class="country-marker" style="left: 38%; top: 32%;">
                    <div class="country-tooltip">Jordan</div>
                </div>
                <div class="country-marker" style="left: 58%; top: 28%;">
                    <div class="country-tooltip">Qatar</div>
                </div>
            </div>

            <div class="map-legend">
                <div><div class="legend-indicator" style="background: var(--accent)"></div><div class="legend-text">Active Coverage</div></div>
                <div><div class="legend-indicator" style="background: var(--primary)"></div><div class="legend-text">Pilot Regions</div></div>
            </div>
        </div>
    </div>
</section>

<footer>
    <div class="container">
        <div class="footer-grid">
            <div class="footer-brand">
                <h3 class="logo-text">Scholder</h3>
                <p>Secure cloud file management for institutions — designed for administrators, teachers, supervisors and secretaries.</p>
                <a class="footer-email" href="mailto:hello@scholder.app">hello@scholder.app</a>
            </div>
            <div class="footer-section">
                <h4>Product</h4>
                <div class="location-name">Files • Reports • API</div>
            </div>
            <div class="footer-section">
                <h4>Company</h4>
                <div class="location-name">About • Careers • Contact</div>
            </div>
            <div class="footer-section">
                <h4>Support</h4>
                <div class="location-name">Docs • Help Center • Security</div>
            </div>
        </div>
        <div class="footer-bottom">
            <p>© <span id="year"></span> Scholder. All rights reserved.</p>
        </div>
    </div>
</footer>

<script>
    // Small utilities and behavior scripts (kept lightweight and inline)

    // Mobile menu toggle
    const mobileToggle = document.getElementById('mobileMenuToggle');
    const navLinks = document.querySelector('.nav-links');
    if (mobileToggle) {
        mobileToggle.addEventListener('click', () => {
            navLinks.classList.toggle('active');
        });
    }

    // Year in footer
    document.getElementById('year').textContent = new Date().getFullYear();

    // Language toggle (basic; swaps dir & simple text replacement using data-en/data-ar)
    const langBtns = document.querySelectorAll('.lang-btn');
    function setLang(lang) {
        document.documentElement.lang = lang;
        if (lang === 'ar') {
            document.documentElement.dir = 'rtl';
        } else {
            document.documentElement.dir = 'ltr';
        }
        // swap text based on data attributes
        document.querySelectorAll('[data-en]').forEach(el => {
            const en = el.getAttribute('data-en');
            const ar = el.getAttribute('data-ar');
            if (lang === 'ar' && ar) el.textContent = ar;
            else if (lang === 'en' && en) el.textContent = en;
        });
        langBtns.forEach(b => b.classList.toggle('active', b.getAttribute('data-lang') === lang));
    }
    langBtns.forEach(btn => {
        btn.addEventListener('click', () => setLang(btn.getAttribute('data-lang')));
    });

    // Fade-in animation for feature / scenario cards on load (optional)
    function revealOnLoad() {
        document.querySelectorAll('.feature-card').forEach((el, i) => {
            setTimeout(() => el.classList.add('animate-in'), 120 * i);
        });
        document.querySelectorAll('.scenario-card').forEach((el, i) => {
            setTimeout(() => el.classList.add('animate-in'), 140 * i);
        });
        document.querySelectorAll('.price-card').forEach((el, i) => {
            setTimeout(() => el.classList.add('animate-in'), 160 * i);
        });
    }
    // Run reveal on DOMContentLoaded
    document.addEventListener('DOMContentLoaded', revealOnLoad);

    // COUNTER LOGIC (Intersection Observer + Count-up with formatting)
    (function() {
        const countersSection = document.getElementById('countersSection');
        const counterCards = countersSection ? countersSection.querySelectorAll('.counter-card') : [];

        function formatValue(value, format) {
            if (format === 'int') return String(value);
            if (format === 'k-plus') {
                // show as K+ (rounded to nearest thousand as K)
                if (value >= 1000) {
                    const k = Math.round(value / 1000);
                    return k + 'K+';
                }
                return String(value);
            }
            if (format === 'k-plus-decimal') {
                if (value >= 1000) {
                    const dec = (value / 1000);
                    // one decimal if needed (e.g., 3.7K)
                    const display = dec % 1 === 0 ? String(dec) : dec.toFixed(1);
                    return display + 'K+';
                }
                return String(value);
            }
            return String(value);
        }

        function animateCount(el, target, format) {
            const duration = 1500; // ms
            const start = performance.now();
            const initial = 0;
            function step(now) {
                const progress = Math.min((now - start) / duration, 1);
                const eased = 1 - Math.pow(1 - progress, 3); // easeOutCubic-ish
                const current = Math.floor(initial + (target - initial) * eased);
                el.textContent = formatValue(current, format);
                if (progress < 1) {
                    requestAnimationFrame(step);
                } else {
                    // final display (ensure exact final format)
                    el.textContent = formatValue(target, format);
                }
            }
            requestAnimationFrame(step);
        }

        if (counterCards.length) {
            const observer = new IntersectionObserver((entries, obs) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        // animate each card
                        counterCards.forEach((card, idx) => {
                            const cardEl = card.querySelector('.counter-value');
                            const rawTarget = parseInt(card.getAttribute('data-target'), 10) || 0;
                            const format = cardEl.getAttribute('data-format') || 'int';
                            card.classList.add('animate-in');
                            // small delay per card for nicer stagger
                            setTimeout(() => animateCount(cardEl, rawTarget, format), idx * 120);
                        });
                        obs.disconnect();
                    }
                });
            }, { threshold: 0.25 });
            observer.observe(countersSection);
        }
    })();

    // Small floating decorative SVG elements injection (keeps original style)
    (function createFloating() {
        const container = document.getElementById('floatingElements');
        const shapes = [
            { w: 280, h: 280, left: '5%', top: '10%', rot: 15, color: 'rgba(79,70,229,0.06)' },
            { w: 220, h: 220, left: '70%', top: '65%', rot: -20, color: 'rgba(20,184,166,0.06)' },
            { w: 180, h: 180, left: '45%', top: '5%', rot: 45, color: 'rgba(244,114,182,0.04)' }
        ];
        shapes.forEach(s => {
            const el = document.createElement('div');
            el.className = 'float-element';
            el.style.width = s.w + 'px';
            el.style.height = s.h + 'px';
            el.style.left = s.left;
            el.style.top = s.top;
            el.style.transform = 'rotate(' + s.rot + 'deg)';
            el.style.background = s.color;
            el.style.borderRadius = '24px';
            container.appendChild(el);
        });
    })();

</script>
</body>
</html>
