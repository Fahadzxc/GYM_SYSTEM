<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gym Management System</title>
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

        .main-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            max-width: 600px;
            width: 100%;
            overflow: hidden;
        }

        /* Tab Navigation */
        .tab-nav {
            display: flex;
            background: #f8f9fa;
            border-bottom: 2px solid #e9ecef;
        }

        .tab-btn {
            flex: 1;
            padding: 20px;
            background: transparent;
            border: none;
            font-size: 16px;
            font-weight: 600;
            color: #6c757d;
            cursor: pointer;
            transition: all 0.3s ease;
            border-bottom: 3px solid transparent;
        }

        .tab-btn:hover {
            background: #e9ecef;
            color: #495057;
        }

        .tab-btn.active {
            color: #667eea;
            border-bottom-color: #667eea;
            background: white;
        }

        /* Tab Content */
        .tab-content {
            display: none;
            padding: 40px;
        }

        .tab-content.active {
            display: block;
        }

        /* RFID Scanner Styles */
        .scanner-title {
            font-size: 32px;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 10px;
            text-align: center;
        }

        .scanner-subtitle {
            font-size: 16px;
            color: #7f8c8d;
            margin-bottom: 40px;
            text-align: center;
        }

        .scan-icon {
            font-size: 64px;
            margin-bottom: 20px;
            color: #3498db;
            animation: pulse 2s infinite;
            text-align: center;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.1); opacity: 0.8; }
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

        .status-message {
            min-height: 60px;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-weight: 500;
            display: none;
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

        .loading {
            display: none;
            margin-top: 20px;
            text-align: center;
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

        /* Admin Login Styles */
        .admin-title {
            font-size: 28px;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 10px;
            text-align: center;
        }

        .welcome-text {
            color: #7f8c8d;
            margin-bottom: 30px;
            text-align: center;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #2c3e50;
        }

        .form-input {
            width: 100%;
            padding: 15px;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            font-size: 16px;
            transition: all 0.3s ease;
        }

        .form-input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .login-btn {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }

        .error-message {
            color: #dc3545;
            font-size: 14px;
            margin-top: 5px;
            display: none;
        }

        .alert {
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 2px solid #f5c6cb;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 2px solid #c3e6cb;
        }
    </style>
</head>
<body>
    <div class="main-container">
        <!-- Tab Navigation -->
        <div class="tab-nav">
            <button class="tab-btn active" onclick="switchTab('rfid')">📱 RFID Scanner</button>
            <button class="tab-btn" onclick="switchTab('admin')">🔐 Admin Login</button>
        </div>

        <!-- RFID Scanner Tab -->
        <div id="rfid-tab" class="tab-content active">
            <div class="scan-icon">📱</div>
            <h1 class="scanner-title">RFID Attendance Scanner</h1>
            <p class="scanner-subtitle">Scan your RFID card to record attendance</p>

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
        </div>

        <!-- Admin Login Tab -->
        <div id="admin-tab" class="tab-content">
            <h1 class="admin-title">ADMIN LOGIN</h1>
            <p class="welcome-text">Welcome back!</p>
            
            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-error">
                    <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>
            
            <?php 
            $validationErrors = session()->getFlashdata('errors');
            if ($validationErrors && is_array($validationErrors)): 
                foreach ($validationErrors as $field => $error): 
                    $errorMsg = is_array($error) ? implode(', ', $error) : $error;
            ?>
                <div class="alert alert-error"><?= esc($errorMsg) ?></div>
            <?php 
                endforeach;
            endif; 
            ?>
            
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success">
                    <?= session()->getFlashdata('success') ?>
                </div>
            <?php endif; ?>
            
            <form class="form-container" action="<?= base_url('/authenticate') ?>" method="POST" onsubmit="return validateForm()">
                <?= csrf_field() ?>
                
                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        class="form-input" 
                        placeholder="Enter your email"
                        value="<?= old('email') ?>"
                        autocomplete="email"
                        required
                    >
                    <div id="email-error" class="error-message"></div>
                </div>
                
                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        class="form-input" 
                        placeholder="Enter your password"
                        autocomplete="current-password"
                        required
                    >
                    <div id="password-error" class="error-message"></div>
                </div>
                
                <button type="submit" class="login-btn">LOGIN</button>
            </form>
        </div>
    </div>

    <script>
        // Tab Switching
        function switchTab(tab) {
            // Hide all tabs
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.remove('active');
            });
            
            // Remove active class from all buttons
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            
            // Show selected tab
            document.getElementById(tab + '-tab').classList.add('active');
            
            // Add active class to clicked button
            event.target.classList.add('active');
            
            // Focus RFID input if switching to RFID tab
            if (tab === 'rfid') {
                setTimeout(() => {
                    document.getElementById('rfidInput').focus();
                }, 100);
            }
        }

        // ============================================
        // RFID Scanner JavaScript
        // ============================================
        
        const rfidInput = document.getElementById('rfidInput');
        const statusMessage = document.getElementById('statusMessage');
        const memberInfo = document.getElementById('memberInfo');
        const loading = document.getElementById('loading');
        const SCAN_ENDPOINT = '<?= base_url('/rfid/scan') ?>';

        let isProcessing = false;
        let scanTimeout = null;

        window.addEventListener('load', function() {
            if (document.getElementById('rfid-tab').classList.contains('active')) {
                rfidInput.focus();
            }
        });

        rfidInput.addEventListener('input', function(e) {
            if (scanTimeout) {
                clearTimeout(scanTimeout);
            }

            scanTimeout = setTimeout(function() {
                const rfidCode = rfidInput.value.trim();
                
                if (rfidCode.length > 0 && !isProcessing) {
                    processRFIDScan(rfidCode);
                }
            }, 300);
        });

        rfidInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                const rfidCode = rfidInput.value.trim();
                
                if (rfidCode.length > 0 && !isProcessing) {
                    processRFIDScan(rfidCode);
                }
            }
        });

        function processRFIDScan(rfidCode) {
            if (isProcessing) return;

            isProcessing = true;
            loading.classList.add('show');
            hideStatusMessage();
            hideMemberInfo();

            const formData = new FormData();
            formData.append('rfid_code', rfidCode);

            fetch(SCAN_ENDPOINT, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json().then(data => ({ status: response.status, data: data })))
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
                setTimeout(() => {
                    rfidInput.value = '';
                    rfidInput.focus();
                }, 1000);
            });
        }

        function showSuccess(message, memberData) {
            statusMessage.className = 'status-message status-success';
            statusMessage.textContent = message;
            statusMessage.style.display = 'block';

            if (memberData) {
                showMemberInfo(memberData);
            }
        }

        function showError(message) {
            statusMessage.className = 'status-message status-error';
            statusMessage.textContent = message;
            statusMessage.style.display = 'block';
        }

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

        function hideStatusMessage() {
            statusMessage.style.display = 'none';
            statusMessage.className = 'status-message';
        }

        function hideMemberInfo() {
            memberInfo.classList.remove('show');
            memberInfo.innerHTML = '';
        }

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        // ============================================
        // Admin Login Validation
        // ============================================
        
        function validateForm() {
            const email = document.getElementById('email');
            const password = document.getElementById('password');
            const emailError = document.getElementById('email-error');
            const passwordError = document.getElementById('password-error');
            
            let isValid = true;
            
            emailError.style.display = 'none';
            passwordError.style.display = 'none';
            
            if (!email.value.trim()) {
                emailError.textContent = 'Email is required';
                emailError.style.display = 'block';
                isValid = false;
            } else if (!isValidEmail(email.value)) {
                emailError.textContent = 'Please enter a valid email address';
                emailError.style.display = 'block';
                isValid = false;
            }
            
            if (!password.value.trim()) {
                passwordError.textContent = 'Password is required';
                passwordError.style.display = 'block';
                isValid = false;
            }
            
            return isValid;
        }
        
        function isValidEmail(email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        }
    </script>
</body>
</html>

