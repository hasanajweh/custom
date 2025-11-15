<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Offline - Scholder</title>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.1.0/fonts/remixicon.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .offline-container {
            background: white;
            border-radius: 24px;
            padding: 60px 40px;
            text-align: center;
            max-width: 500px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }

        .offline-icon {
            font-size: 80px;
            color: #EF4444;
            margin-bottom: 24px;
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }

        h1 {
            font-size: 32px;
            color: #1F2937;
            margin-bottom: 16px;
            font-weight: 800;
        }

        p {
            font-size: 16px;
            color: #6B7280;
            margin-bottom: 32px;
            line-height: 1.6;
        }

        .retry-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 16px 32px;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .retry-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
        }

        .retry-btn:active {
            transform: translateY(0);
        }

        .features {
            margin-top: 40px;
            text-align: left;
            background: #F9FAFB;
            padding: 24px;
            border-radius: 16px;
        }

        .feature-item {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 16px;
            color: #374151;
            font-size: 14px;
        }

        .feature-item:last-child {
            margin-bottom: 0;
        }

        .feature-item i {
            font-size: 24px;
            color: #10B981;
        }

        .connection-status {
            margin-top: 24px;
            font-size: 14px;
            color: #9CA3AF;
        }

        .connection-status.online {
            color: #10B981;
        }

        .connection-status i {
            margin-right: 6px;
        }
    </style>
</head>
<body>
<div class="offline-container">
    <div class="offline-icon">
        <i class="ri-wifi-off-line"></i>
    </div>

    <h1>You're Offline</h1>
    <p>
        It looks like you've lost your internet connection. Don't worry,
        you can still access recently viewed content.
    </p>

    <button onclick="window.location.reload()" class="retry-btn">
        <i class="ri-refresh-line"></i>
        Try Again
    </button>

    <div class="features">
        <div class="feature-item">
            <i class="ri-check-line"></i>
            <span>Recently viewed pages are available offline</span>
        </div>
        <div class="feature-item">
            <i class="ri-check-line"></i>
            <span>Your work is saved locally</span>
        </div>
        <div class="feature-item">
            <i class="ri-check-line"></i>
            <span>Changes will sync when you're back online</span>
        </div>
    </div>

    <div class="connection-status" id="connectionStatus">
        <i class="ri-wifi-off-line"></i>
        <span>Waiting for connection...</span>
    </div>
</div>

<script>
    // Check connection status
    function updateConnectionStatus() {
        const statusEl = document.getElementById('connectionStatus');

        if (navigator.onLine) {
            statusEl.className = 'connection-status online';
            statusEl.innerHTML = '<i class="ri-wifi-line"></i><span>Back online! Reloading...</span>';
            setTimeout(() => window.location.reload(), 1000);
        } else {
            statusEl.className = 'connection-status';
            statusEl.innerHTML = '<i class="ri-wifi-off-line"></i><span>Waiting for connection...</span>';
        }
    }

    window.addEventListener('online', updateConnectionStatus);
    window.addEventListener('offline', updateConnectionStatus);

    // Check immediately
    updateConnectionStatus();

    // Check periodically
    setInterval(updateConnectionStatus, 3000);
</script>
</body>
</html>
