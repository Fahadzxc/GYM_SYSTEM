<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'RFID Scanner') ?> - Gym Management System</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .scanner-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            padding: 40px;
            max-width: 600px;
            width: 100%;
            text-align: center;
        }

        .scanner-title {
            font-size: 32px;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 10px;
        }

        .scanner-subtitle {
            font-size: 16px;
            color: #7f8c8d;
            margin-bottom: 40px;
        }

        .rfid-input-container {
            position: relative;
            margin-bottom: 30px;
        }

        .rfid-input {
            width: 100%;
            padding: 20px;
            font-size: 24px;
            text-align: center;
            border: 3px solid #3498db;
            border-radius: 15px;
            outline: none;
            transition: all 0.3s ease;
            letter-spacing: 2px;
            font-weight: 600;
            background-color: #f8f9fa;
        }

        .rfid-input:focus {
            border-color: #2ecc71;
            box-shadow: 0 0 20px rgba(46, 204, 113, 0.3);
            background-color: white;
        }

        .rfid-input::placeholder {
            color: #bdc3c7;
            font-size: 18px;
            letter-spacing: 1px;
        }

        .status-message {
            min-height: 60px;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-weight: 500;
            display: none;
            animation: slideIn 0.3s ease;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .status-success {
            background-color: #d4edda;
            color: #155724;
            border: 2px solid #c3e6cb;
            display: block;
        }

        .status-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 2px solid #f5c6cb;
            display: block;
        }

        .status-info {
            background-color: #d1ecf1;
            color: #0c5460;
            border: 2px solid #bee5eb;
            display: block;
        }

        .member-info {
            background-color: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-top: 20px;
            text-align: left;
            display: none;
        }

        .member-info.show {
            display: block;
            animation: slideIn 0.3s ease;
        }

        .member-info-item {
            margin-bottom: 10px;
            font-size: 16px;
        }

        .member-info-label {
            font-weight: 600;
            color: #2c3e50;
            display: inline-block;
            width: 120px;
        }

        .member-info-value {
            color: #34495e;
        }

        .scan-icon {
            font-size: 64px;
            margin-bottom: 20px;
            color: #3498db;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
                opacity: 1;
            }
            50% {
                transform: scale(1.1);
                opacity: 0.8;
            }
        }

        .loading {
            display: none;
            margin-top: 20px;
        }

        .loading.show {
            display: block;
        }

        .spinner {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #3498db;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 0 auto;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .back-link {
            display: inline-block;
            margin-top: 30px;
            color: #3498db;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .back-link:hover {
            color: #2980b9;
            text-decoration: underline;
        }

        .instructions {
            background-color: #fff3cd;
            border: 2px solid #ffc107;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 30px;
            text-align: left;
            font-size: 14px;
            color: #856404;
        }

        .instructions-title {
            font-weight: 600;
            margin-bottom: 8px;
            font-size: 16px;
        }

        .instructions-list {
            list-style-position: inside;
            margin-left: 10px;
        }

        .instructions-list li {
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    <div class="scanner-container">
        <div class="scan-icon">📱</div>
        <h1 class="scanner-title">RFID Attendance Scanner</h1>
        <p class="scanner-subtitle">Scan your RFID card to record attendance</p>

        <div class="instructions">
            <div class="instructions-title">📋 How it works:</div>
            <ul class="instructions-list">
                <li>Make sure the RFID input field below is focused (click on it if needed)</li>
                <li>Place your RFID card near the USB RFID reader</li>
                <li>The RFID code will be automatically typed into the field</li>
                <li>Attendance will be recorded automatically</li>
            </ul>
        </div>

        <form id="rfidForm" onsubmit="return false;">
            <div class="rfid-input-container">
                <input 
                    type="text" 
                    id="rfidInput" 
                    name="rfid_code" 
                    class="rfid-input" 
                    placeholder="Waiting for RFID scan..."
                    autocomplete="off"
                    autofocus
                >
            </div>

            <div id="statusMessage" class="status-message"></div>

            <div id="loading" class="loading">
                <div class="spinner"></div>
            </div>

            <div id="memberInfo" class="member-info"></div>
        </form>

        <a href="<?= base_url('/dashboard') ?>" class="back-link">← Back to Dashboard</a>
    </div>

    <script>
        // ============================================
        // RFID Scanner JavaScript
        // ============================================
        
        const rfidInput = document.getElementById('rfidInput');
        const statusMessage = document.getElementById('statusMessage');
        const memberInfo = document.getElementById('memberInfo');
        const loading = document.getElementById('loading');
        const form = document.getElementById('rfidForm');

        // Configuration: Update this URL if your route is different
        const SCAN_ENDPOINT = '<?= base_url('/rfid/scan') ?>';

        // Track if we're currently processing a scan
        let isProcessing = false;
        let scanTimeout = null;

        // Auto-focus the input field when page loads
        window.addEventListener('load', function() {
            rfidInput.focus();
        });

        // Handle RFID input
        rfidInput.addEventListener('input', function(e) {
            // Clear any existing timeout
            if (scanTimeout) {
                clearTimeout(scanTimeout);
            }

            // Wait for user to finish typing (RFID readers typically send data quickly)
            // Adjust timeout if your RFID reader sends data slowly
            scanTimeout = setTimeout(function() {
                const rfidCode = rfidInput.value.trim();
                
                if (rfidCode.length > 0 && !isProcessing) {
                    processRFIDScan(rfidCode);
                }
            }, 300); // 300ms delay - adjust if needed for your RFID reader
        });

        // Handle Enter key (if user manually types and presses Enter)
        rfidInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                const rfidCode = rfidInput.value.trim();
                
                if (rfidCode.length > 0 && !isProcessing) {
                    processRFIDScan(rfidCode);
                }
            }
        });

        /**
         * Process RFID scan via AJAX
         */
        function processRFIDScan(rfidCode) {
            if (isProcessing) {
                return; // Prevent duplicate submissions
            }

            isProcessing = true;
            
            // Show loading indicator
            loading.classList.add('show');
            hideStatusMessage();
            hideMemberInfo();

            // Create FormData
            const formData = new FormData();
            formData.append('rfid_code', rfidCode);

            // Send AJAX request
            fetch(SCAN_ENDPOINT, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                return response.json().then(data => {
                    return { status: response.status, data: data };
                });
            })
            .then(result => {
                loading.classList.remove('show');
                isProcessing = false;

                if (result.data.success) {
                    showSuccess(result.data.message, result.data.member_data);
                } else {
                    showError(result.data.message || 'An error occurred');
                }
            })
            .catch(error => {
                loading.classList.remove('show');
                isProcessing = false;
                console.error('Error:', error);
                showError('Network error. Please check your connection and try again.');
            })
            .finally(() => {
                // Clear input and refocus after a short delay
                setTimeout(() => {
                    rfidInput.value = '';
                    rfidInput.focus();
                }, 1000);
            });
        }

        /**
         * Show success message
         */
        function showSuccess(message, memberData) {
            statusMessage.className = 'status-message status-success';
            statusMessage.textContent = message;
            statusMessage.style.display = 'block';

            if (memberData) {
                showMemberInfo(memberData);
            }
        }

        /**
         * Show error message
         */
        function showError(message) {
            statusMessage.className = 'status-message status-error';
            statusMessage.textContent = message;
            statusMessage.style.display = 'block';
        }

        /**
         * Show member information
         */
        function showMemberInfo(memberData) {
            memberInfo.innerHTML = `
                <div class="member-info-item">
                    <span class="member-info-label">Member ID:</span>
                    <span class="member-info-value">${escapeHtml(memberData.id || 'N/A')}</span>
                </div>
                <div class="member-info-item">
                    <span class="member-info-label">Name:</span>
                    <span class="member-info-value">${escapeHtml(memberData.name || 'N/A')}</span>
                </div>
                <div class="member-info-item">
                    <span class="member-info-label">Type:</span>
                    <span class="member-info-value">${escapeHtml(memberData.user_type || 'N/A')}</span>
                </div>
                ${memberData.scan_time ? `
                <div class="member-info-item">
                    <span class="member-info-label">Scan Time:</span>
                    <span class="member-info-value">${escapeHtml(memberData.scan_time)}</span>
                </div>
                ` : ''}
            `;
            memberInfo.classList.add('show');
        }

        /**
         * Hide status message
         */
        function hideStatusMessage() {
            statusMessage.style.display = 'none';
            statusMessage.className = 'status-message';
        }

        /**
         * Hide member info
         */
        function hideMemberInfo() {
            memberInfo.classList.remove('show');
            memberInfo.innerHTML = '';
        }

        /**
         * Escape HTML to prevent XSS
         */
        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        // Keep input focused (useful for RFID scanners)
        document.addEventListener('click', function(e) {
            // If clicking outside the input, refocus it
            if (e.target !== rfidInput && !rfidInput.contains(e.target)) {
                setTimeout(() => rfidInput.focus(), 100);
            }
        });

        // Handle visibility change (when user switches tabs and comes back)
        document.addEventListener('visibilitychange', function() {
            if (!document.hidden) {
                rfidInput.focus();
            }
        });
    </script>
</body>
</html>

