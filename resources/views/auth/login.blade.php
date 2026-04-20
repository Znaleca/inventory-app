<!DOCTYPE html>
<html lang="en" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="">
    <title>IMISS Inventory — BGHMC</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=fira-code:400,500,600|plus-jakarta-sans:300,400,500,600,700,800"
        rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #3b82f6;
            --primary-dark: #1e40af;
            --primary-light: #60a5fa;
            --secondary: #6366f1;
            --accent: #10b981;
            --dark-bg: #0f172a;
            --light-bg: #f8fafc;
            --card-dark: #1e293b;
            --text-light: #e2e8f0;
            --text-muted: #94a3b8;
        }

        html,
        body {
            height: 100%;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(135deg, var(--dark-bg) 0%, #1a2942 100%);
            color: var(--text-light);
            overflow: hidden;
        }

        .container {
            display: flex;
            min-height: 100vh;
            width: 100%;
            position: relative;
        }

        /* ==================== LEFT PANEL ==================== */
        .left-panel {
            flex: 0 0 58%;
            padding: 4rem;
            background: linear-gradient(135deg, var(--dark-bg) 0%, #1a3a52 100%);
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            overflow: hidden;
        }

        .left-panel::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(59, 130, 246, 0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(59, 130, 246, 0.03) 1px, transparent 1px);
            background-size: 40px 40px;
            z-index: 1;
            pointer-events: none;
        }

        .accent-bar-top {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, #3b82f6, #6366f1, #10b981);
            z-index: 5;
        }

        .corner-node {
            position: absolute;
            width: 12px;
            height: 12px;
            border: 1.5px solid rgba(59, 130, 246, 0.4);
            z-index: 5;
        }

        .corner-node.top-right {
            top: 2rem;
            right: 2rem;
            background: rgba(59, 130, 246, 0.1);
        }

        .corner-node.bottom-left {
            bottom: 2rem;
            left: 2rem;
            background: rgba(99, 102, 241, 0.1);
        }

        .scan-line {
            position: absolute;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, transparent, rgba(59, 130, 246, 0.5), transparent);
            z-index: 2;
            animation: scan 5s linear infinite;
        }

        @keyframes scan {
            0% {
                transform: translateY(-100%);
                opacity: 0;
            }

            10% {
                opacity: 1;
            }

            90% {
                opacity: 1;
            }

            100% {
                transform: translateY(100vh);
                opacity: 0;
            }
        }

        .content {
            position: relative;
            z-index: 10;
        }

        .branding {
            margin-bottom: 3rem;
        }

        .branding-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .branding-bar {
            width: 3px;
            height: 32px;
            background: linear-gradient(180deg, #3b82f6, #6366f1);
        }

        .branding-text p {
            margin: 0;
            font-size: 10px;
            font-family: 'Fira Code', monospace;
            font-weight: 600;
            letter-spacing: 0.3em;
            text-transform: uppercase;
        }

        .branding-text p:first-child {
            color: #60a5fa;
            margin-bottom: 0.25rem;
        }

        .branding-text p:last-child {
            color: #64748b;
            font-size: 9px;
            letter-spacing: 0.15em;
        }

        .hero-section {
            margin-bottom: 3rem;
        }

        .version-badge {
            font-family: 'Fira Code', monospace;
            font-size: 10px;
            color: rgba(96, 165, 250, 0.7);
            letter-spacing: 0.3em;
            text-transform: uppercase;
            margin-bottom: 1.5rem;
            display: block;
        }

        .hero-title {
            font-size: 4rem;
            font-weight: 900;
            line-height: 0.9;
            letter-spacing: -0.02em;
            margin-bottom: 1.5rem;
        }

        .hero-title span.primary {
            color: var(--text-light);
        }

        .hero-title span.accent {
            color: var(--primary);
        }

        .hero-desc {
            max-width: 480px;
            color: var(--text-muted);
            font-size: 1rem;
            line-height: 1.6;
            font-weight: 500;
            border-left: 2px solid rgba(59, 130, 246, 0.3);
            padding-left: 1rem;
            margin-bottom: 2rem;
        }

        .hero-desc span.highlight {
            color: var(--primary-light);
        }

        .info-boxes {
            display: flex;
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .info-box {
            border: 1px solid rgba(71, 85, 105, 0.6);
            padding: 1rem;
            background: rgba(30, 41, 59, 0.4);
            backdrop-filter: blur(10px);
            min-width: 140px;
            transition: all 0.3s ease;
        }

        .info-box:hover {
            border-color: var(--primary);
            background: rgba(59, 130, 246, 0.1);
        }

        .info-box-label {
            font-family: 'Fira Code', monospace;
            font-size: 9px;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.2em;
            margin-bottom: 0.5rem;
            display: block;
        }

        .info-box-value {
            font-family: 'Fira Code', monospace;
            font-size: 12px;
            color: var(--text-light);
            font-weight: 700;
        }

        .status-footer {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            border: 1px solid rgba(71, 85, 105, 0.6);
            background: rgba(30, 41, 59, 0.5);
            padding: 0.75rem 1rem;
            width: fit-content;
            backdrop-filter: blur(10px);
        }

        .status-indicator {
            width: 8px;
            height: 8px;
            background: var(--accent);
            border-radius: 50%;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
                box-shadow: 0 0 0 0 var(--accent);
            }

            50% {
                box-shadow: 0 0 0 6px rgba(16, 185, 129, 0);
            }
        }

        .status-text {
            font-family: 'Fira Code', monospace;
            font-size: 10px;
            color: var(--text-light);
            text-transform: uppercase;
            letter-spacing: 0.2em;
        }

        .ip-badge {
            font-family: 'Fira Code', monospace;
            font-size: 9px;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.2em;
            margin-top: 0.75rem;
        }

        /* ==================== RIGHT PANEL ==================== */
        .right-panel {
            flex: 0 0 42%;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            position: relative;
            overflow: hidden;
        }

        .right-panel::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(148, 163, 184, 0.05) 1px, transparent 1px),
                linear-gradient(90deg, rgba(148, 163, 184, 0.05) 1px, transparent 1px);
            background-size: 40px 40px;
            z-index: 1;
        }

        .auth-badge {
            position: absolute;
            top: 1.5rem;
            right: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            border: 1px solid #cbd5e1;
            background: white;
            padding: 0.5rem 0.75rem;
            border-radius: 0.375rem;
            z-index: 20;
        }

        .auth-badge-dot {
            width: 6px;
            height: 6px;
            background: var(--accent);
            border-radius: 50%;
            animation: pulse 2s infinite;
        }

        .auth-badge-text {
            font-family: 'Fira Code', monospace;
            font-size: 10px;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.2em;
            font-weight: 600;
        }

        .form-wrapper {
            width: 100%;
            max-width: 520px;
            position: relative;
            z-index: 10;
        }

        /* ==================== 3D PAPER BOX ==================== */
        .paper-box-container {
            perspective: 1200px;
        }

        .paper-box {
            background: white;
            border-radius: 8px;
            box-shadow:
                0 20px 60px rgba(0, 0, 0, 0.12),
                0 0 0 1px rgba(59, 130, 246, 0.1),
                inset 0 1px 0 rgba(255, 255, 255, 0.6);
            position: relative;
        }

        .paper-box-top {
            position: absolute;
            top: -12px;
            left: 0;
            right: 0;
            height: 24px;
            background: linear-gradient(180deg, white 0%, #f8fafc 100%);
            border-radius: 8px 8px 0 0;
            border: 1px solid #e2e8f0;
            border-bottom: none;
            z-index: 2;
        }

        .paper-box-top::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(90deg, transparent 0%, rgba(59, 130, 246, 0.05) 50%, transparent 100%);
            border-radius: 8px 8px 0 0;
        }

        .paper-box-accent {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, #3b82f6, #6366f1);
            border-radius: 8px 8px 0 0;
            z-index: 3;
        }

        .paper-box-content {
            position: relative;
            padding: 2rem;
            padding-top: 2.5rem;
        }

        /* ==================== FORM STYLES ==================== */
        .form-header {
            margin-bottom: 2rem;
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            margin-bottom: 1.5rem;
            padding-bottom: 1.25rem;
            border-bottom: 1px dashed #e2e8f0;
        }

        .form-header-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            flex-shrink: 0;
        }

        .form-header-text p {
            margin: 0;
            font-size: 10px;
            font-family: 'Fira Code', monospace;
            color: var(--primary);
            text-transform: uppercase;
            letter-spacing: 0.25em;
            margin-bottom: 0.5rem;
            font-weight: 600;
        }

        .form-header-text h2 {
            margin: 0;
            font-size: 1.25rem;
            font-weight: 900;
            color: #0f172a;
            letter-spacing: -0.02em;
        }

        .form-title-section {
            margin-bottom: 1.5rem;
            padding-top: 1rem;
        }

        .form-title-section h1 {
            font-size: 1.75rem;
            font-weight: 900;
            color: #0f172a;
            letter-spacing: -0.02em;
            margin-bottom: 0.5rem;
        }

        .form-subtitle {
            font-family: 'Fira Code', monospace;
            font-size: 10px;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.2em;
        }

        .alert {
            margin-bottom: 1.5rem;
            border: 1px solid #fecaca;
            background: #fef2f2;
            padding: 1rem;
            position: relative;
            border-left: 3px solid #ef4444;
        }

        .alert-header {
            font-family: 'Fira Code', monospace;
            font-size: 10px;
            color: #dc2626;
            text-transform: uppercase;
            letter-spacing: 0.2em;
            margin-bottom: 0.5rem;
            font-weight: 600;
        }

        .alert-message {
            font-size: 0.875rem;
            color: #991b1b;
            font-weight: 500;
        }

        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-label {
            font-family: 'Fira Code', monospace;
            font-size: 10px;
            color: #64748b;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.2em;
            margin-bottom: 0.75rem;
            display: block;
        }

        .form-input-wrapper {
            position: relative;
        }

        .form-input-icon {
            position: absolute;
            left: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            width: 18px;
            height: 18px;
            color: #94a3b8;
            display: flex;
            align-items: center;
            justify-content: center;
            pointer-events: none;
        }

        .form-input {
            width: 100%;
            padding: 0.875rem 1rem 0.875rem 2.75rem;
            background: #f8fafc;
            border: 1.5px solid #e2e8f0;
            border-radius: 6px;
            font-size: 0.875rem;
            font-weight: 500;
            color: #0f172a;
            font-family: 'Fira Code', monospace;
            transition: all 0.3s ease;
        }

        .form-input::placeholder {
            color: #cbd5e1;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .form-input:focus {
            outline: none;
            background: white;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .form-submit {
            width: 100%;
            padding: 1rem;
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
            border: none;
            border-radius: 6px;
            font-weight: 700;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.2em;
            font-family: 'Fira Code', monospace;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            position: relative;
            overflow: hidden;
            margin-top: 1rem;
        }

        .form-submit::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.2);
            transition: left 0.3s ease;
        }

        .form-submit:hover::before {
            left: 100%;
        }

        .form-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(59, 130, 246, 0.4);
        }

        .form-submit svg {
            width: 16px;
            height: 16px;
            transition: transform 0.3s ease;
        }

        .form-submit:hover svg {
            transform: translateX(3px);
        }

        .form-footer {
            margin-top: 1.5rem;
            padding-top: 1.25rem;
            border-top: 1px dashed #e2e8f0;
            text-align: center;
        }

        .form-footer-text {
            font-family: 'Fira Code', monospace;
            font-size: 9px;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.2em;
        }

        /* ==================== TECH SUPPORT BOX ==================== */
        .tech-support-box {
            margin-top: 1.5rem;
            padding: 1.25rem;
            background: linear-gradient(135deg, #f0f9ff 0%, #f0f4ff 100%);
            border: 1px solid rgba(59, 130, 246, 0.2);
            border-radius: 6px;
            position: relative;
            overflow: hidden;
        }

        .tech-support-box::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
        }

        .tech-support-header {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1rem;
        }

        .tech-support-icon {
            width: 32px;
            height: 32px;
            background: var(--primary);
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1rem;
        }

        .tech-support-title {
            font-weight: 700;
            color: var(--primary-dark);
            font-size: 0.875rem;
        }

        .tech-tools {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.75rem;
        }

        .tool-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 0.75rem;
            background: white;
            border: 1px solid rgba(59, 130, 246, 0.15);
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.2s ease;
            font-size: 0.75rem;
            color: var(--primary-dark);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.15em;
        }

        .tool-item:hover {
            background: white;
            border-color: var(--primary);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.15);
            transform: translateY(-2px);
        }

        .tool-item i {
            font-size: 0.875rem;
            color: var(--primary);
        }

        .box-bottom-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 1.5rem;
            padding-top: 1rem;
            border-top: 1px dashed #cbd5e1;
        }

        .footer-date,
        .footer-ip {
            font-family: 'Fira Code', monospace;
            font-size: 9px;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.2em;
        }

        /* ==================== RESPONSIVE ==================== */
        @media (max-width: 1024px) {
            .left-panel {
                display: none;
            }

            .right-panel {
                flex: 1;
                background: linear-gradient(135deg, var(--dark-bg) 0%, #1a3a52 100%);
            }

            .right-panel::before {
                background-image:
                    linear-gradient(rgba(59, 130, 246, 0.05) 1px, transparent 1px),
                    linear-gradient(90deg, rgba(59, 130, 246, 0.05) 1px, transparent 1px);
            }

            .auth-badge {
                border-color: rgba(71, 85, 105, 0.6);
                background: rgba(30, 41, 59, 0.5);
            }

            .auth-badge-text {
                color: var(--text-light);
            }

            .paper-box {
                background: rgba(255, 255, 255, 0.95);
            }

            .form-title-section h1,
            .form-header-text h2 {
                color: var(--text-light);
            }

            .form-input {
                background: rgba(30, 41, 59, 0.5);
                border-color: rgba(71, 85, 105, 0.6);
                color: var(--text-light);
            }

            .form-input::placeholder {
                color: rgba(148, 163, 184, 0.7);
            }

            .form-input:focus {
                background: rgba(30, 41, 59, 0.7);
                border-color: var(--primary);
            }

            .form-label,
            .form-subtitle,
            .alert-header {
                color: var(--text-muted);
            }

            .tool-item {
                background: rgba(30, 41, 59, 0.5);
                border-color: rgba(59, 130, 246, 0.2);
                color: var(--primary-light);
            }

            .tool-item:hover {
                background: rgba(59, 130, 246, 0.1);
            }

            .tech-support-box {
                background: rgba(59, 130, 246, 0.08);
                border-color: rgba(59, 130, 246, 0.15);
            }
        }

        @media (max-width: 640px) {
            .left-panel {
                padding: 2rem;
            }

            .hero-title {
                font-size: 2.5rem;
            }

            .form-wrapper {
                max-width: 100%;
            }

            .paper-box-content {
                padding: 1.5rem;
                padding-top: 2rem;
            }

            .info-boxes {
                flex-wrap: wrap;
            }

            .info-box {
                flex: 1;
                min-width: 120px;
            }

            .tech-tools {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <div class="container">

        <!-- ======================== LEFT PANEL ======================== -->
        <div class="left-panel">
            <div class="accent-bar-top"></div>
            <div class="corner-node top-right"></div>
            <div class="corner-node bottom-left"></div>
            <div class="scan-line"></div>

            <div class="content">
                <!-- Branding -->
                <div class="branding">
                    <div class="branding-header">
                        <div class="branding-bar"></div>
                        <div class="branding-text">
                            <p>System://BGHMC</p>
                            <p>Bataan General Hospital &amp; Medical Center</p>
                        </div>
                    </div>
                </div>

                <!-- Hero Section -->
                <div class="hero-section">
                    <span class="version-badge">v1.0 // Inventory Module</span>
                    <h1 class="hero-title">
                        <span class="primary">IMISS</span><br>
                        <span class="accent">INVENTORY</span>
                    </h1>
                    <p class="hero-desc">
                        Centralized system for managing
                        <span class="highlight">technical devices</span>,
                        <span class="highlight">equipment</span>, and
                        <span class="highlight">consumables</span>
                        with real-time tracking and secure access.
                    </p>

                    <div class="info-boxes">
                        <div class="info-box">
                            <span class="info-box-label">Module</span>
                            <span class="info-box-value">IMISS.v1</span>
                        </div>
                        <div class="info-box">
                            <span class="info-box-label">Facility</span>
                            <span class="info-box-value">BGHMC</span>
                        </div>
                        <div class="info-box">
                            <span class="info-box-label">Access</span>
                            <span class="info-box-value">RESTRICTED</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Status Footer -->
            <div class="content">
                <div class="status-footer">
                    <span class="status-indicator"></span>
                    <span class="status-text">SYS.ONLINE &amp; SECURE</span>
                </div>
                <p class="ip-badge">Gateway: 192.168.1.1</p>
            </div>
        </div>

        <!-- ======================== RIGHT PANEL ======================== -->
        <div class="right-panel">
            <div class="auth-badge">
                <span class="auth-badge-dot"></span>
                <span class="auth-badge-text">Auth.Portal</span>
            </div>

            <div class="form-wrapper">
                <div class="paper-box-container">
                    <div class="paper-box">
                        <div class="paper-box-top"></div>
                        <div class="paper-box-accent"></div>

                        <div class="paper-box-content">
                            <!-- Form Header -->
                            <div class="form-header">
                                <img src="{{ asset('favicon.ico') }}" alt="IMISS" style="width: 40px; height: 40px; object-fit: contain;">
                                <div class="form-header-text">
                                    <p>System://Auth</p>
                                    <h2>IMISS <span style="color: #94a3b8; font-weight: 400;">Inventory</span></h2>
                                </div>
                            </div>

                            <!-- Title Section -->
                            <div class="form-title-section">
                                <h1>Sign In</h1>
                                <p class="form-subtitle">// Authenticate to continue</p>
                            </div>

                            <!-- Error Alert -->
                            @if ($errors->any())
                            <div class="alert">
                                <p class="alert-header">// Error</p>
                                @foreach ($errors->all() as $error)
                                <p class="alert-message">{{ $error }}</p>
                                @endforeach
                            </div>
                            @endif

                            <!-- Login Form -->
                            <form method="POST" action="{{ route('login') }}" class="login-form">
                                @csrf
                                <!-- Bio ID Input -->
                                <div class="form-group">
                                    <label class="form-label">Bio_ID / Username</label>
                                    <div class="form-input-wrapper">
                                        <div class="form-input-icon">
                                            <i class="fas fa-user"></i>
                                        </div>
                                        <input type="text" name="bio_id" class="form-input" placeholder="Enter credentials" required autofocus>
                                    </div>
                                </div>

                                <!-- Password Input -->
                                <div class="form-group">
                                    <label class="form-label">Password</label>
                                    <div class="form-input-wrapper">
                                        <div class="form-input-icon">
                                            <i class="fas fa-lock"></i>
                                        </div>
                                        <input type="password" name="password" class="form-input" placeholder="••••••••" required>
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <button type="submit" class="form-submit">
                                    <span>Login</span>
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                            d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                    </svg>
                                </button>
                            </form>



                            <!-- Footer -->
                            <div class="form-footer">
                                <p class="form-footer-text">BGHMC-IMISS // Restricted Access</p>
                            </div>

                            <!-- Bottom Footer -->
                            <div class="box-bottom-footer">
                                <span class="footer-date" id="currentDate"></span>
                                <span class="footer-ip" id="currentIp">0.0.0.0</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        // Set current date
        document.getElementById('currentDate').textContent = new Date().toISOString().split('T')[0];

        // Get IP (simulated)
        document.getElementById('currentIp').textContent = '192.168.1.' + Math.floor(Math.random() * 254 + 1);
    </script>
</body>

</html>