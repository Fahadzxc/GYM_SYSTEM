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
            position: relative;
        }

        .tab-btn:hover {
            background: #e9ecef;
        }

        .tab-btn.active {
            color: #667eea;
            background: white;
        }

        .tab-btn.active::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: #667eea;
        }

        /* Tab Content */
        .tab-content {
            display: none;
            padding: 40px;
            animation: fadeIn 0.3s ease;
        }

        .tab-content.active {
            display: block;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* RFID Scanner Styles */
        .scanner-section {
            text-align: center;
        }

        .scan-icon {
            font-size: 64px;
            margin-bottom: 20px;
            color: #3498db;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.1); opacity: 0.8; }
        }

        .scanner-title {
            font-size: 28px;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 10px;
        }

        .scanner-subtitle {
            font-size: 14px;
            color: #7f8c8d;
            margin-bottom: 30px;
        }

        .rfid-input {
            width: 100%;
            padding: 15px;
            font-size: 20px;
            text-align: center;
            border: 3px solid #3498db;
            border-radius: 10px;
            outline: none;
            transition: all 0.3s ease;
            letter-spacing: 2px;
            font-weight: 600;
            background-color: #f8f9fa;
            margin-bottom: 20px;
        }

        .rfid-input:focus {
            border-color: #2ecc71;
            box-shadow: 0 0 20px rgba(46, 204, 113, 0.3);
            background-color: white;
        }

        /* Admin Login Styles */
        .login-section {
            text-align: center;
        }

        .school-logo {
            margin-bottom: 20px;
        }

        .school-logo img {
            max-width: 120px;
            height: auto;
        }

        .admin-title {
            font-size: 24px;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 10px;
        }

        .welcome-text {
            font-size: 14px;
            color: #7f8c8d;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #34495e;
            font-size: 14px;
        }

        .form-input {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 14px;
            transition: border-color 0.3s ease;
        }

        .form-input:focus {
            border-color: #667eea;
            outline: none;
        }

        .login-btn {
            width: 100%;
            padding: 15px;
            background-color: #667eea;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .login-btn:hover {
            background-color: #5a67d8;
        }

        .status-message {
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: none;
            font-size: 14px;
        }

        .status-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            display: block;
        }

        .status-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            display: block;
        }

        .member-info {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
            margin-top: 15px;
            text-align: left;
            display: none;
            font-size: 14px;
        }

        .member-info.show {
            display: block;
        }

        .member-info-item {
            margin-bottom: 8px;
        }

        .member-info-label {
            font-weight: 600;
            color: #2c3e50;
            display: inline-block;
            width: 100px;
        }

        @media (max-width: 768px) {
            .main-container {
                max-width: 95%;
            }
            .tab-content {
                padding: 25px;
            }
        }
    </style>
</head>
<body>
    <div class="main-container">
        <div class="tab-nav">
            <button class="tab-btn active" data-tab="rfid">RFID Scanner</button>
            <button class="tab-btn" data-tab="admin">Admin Login</button>
        </div>

        <!-- RFID Scanner Tab -->
        <div id="rfid-tab" class="tab-content active">
            <div class="scanner-section">
                <div class="scan-icon">📱</div>
                <h1 class="scanner-title">RFID Attendance Scanner</h1>
                <p class="scanner-subtitle">Scan your RFID card to record attendance</p>

                <form id="rfidForm">
                    <input 
                        type="text" 
                        id="rfidInput" 
                        class="rfid-input" 
                        placeholder="Waiting for RFID scan..."
                        autocomplete="off"
                        autofocus
                    >
                    <div id="statusMessage" class="status-message"></div>
                    <div id="memberInfo" class="member-info"></div>
                </form>
            </div>
        </div>

        <!-- Admin Login Tab -->
        <div id="admin-tab" class="tab-content">
            <div class="login-section">
                <div class="school-logo">
                    <img src="<?= base_url('assets/images/rmmc-logo.png') ?>" alt="RMMC Logo">
                </div>
                <h1 class="admin-title">ADMIN LOGIN</h1>
                <p class="welcome-text">Welcome back!</p>
                
                <?php if (session()->getFlashdata('error')): ?>
                    <div class="status-message status-error">
                        <?= session()->getFlashdata('error') ?>
                    </div>
                <?php endif; ?>
                
                <?php if (session()->getFlashdata('success')): ?>
                    <div class="status-message status-success">
                        <?= session()->getFlashdata('success') ?>
                    </div>
                <?php endif; ?>
                
                <form action="<?= base_url('/authenticate') ?>" method="POST">
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
                            required
                        >
                    </div>
                    
                    <div class="form-group">
                        <label for="password" class="form-label">Password</label>
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            class="form-input" 
                            placeholder="Enter your password"
                            required
                        >
                    </div>
                    
                    <button type="submit" class="login-btn">LOGIN</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Success Sound - Your Custom MP3 File -->
    <audio id="successSound" preload="auto">
        <source src="<?= base_url('assets/sounds/ElevenLabs_Text_to_Speech_audio.mp3') ?>" type="audio/mpeg">
    </audio>

    <script>
        // ============================================
        // Sound Effects
        // SUCCESS = Your custom MP3 file
        // ERROR = Generated beep sound
        // ============================================

        const successSound = document.getElementById('successSound');
        let isSoundPlaying = false; // Prevent double play

        // Audio context for error beep
        let audioCtx = null;
        
        function ensureAudioContext() {
            if (!audioCtx) {
                audioCtx = new (window.AudioContext || window.webkitAudioContext)();
            }
            if (audioCtx.state === 'suspended') {
                audioCtx.resume();
            }
            return audioCtx;
        }

        // SUCCESS SOUND - Your custom MP3 file (with double-play protection)
        function playSuccessSound() {
            if (isSoundPlaying) {
                console.log('Sound already playing, skipping...');
                return;
            }
            console.log('Playing success sound (custom MP3)...');
            isSoundPlaying = true;
            successSound.currentTime = 0;
            successSound.play().catch(e => console.log('Success sound error:', e.message));
            
            // Reset flag when sound ends
            successSound.onended = function() {
                isSoundPlaying = false;
            };
            
            // Fallback reset after 5 seconds (in case onended doesn't fire)
            setTimeout(() => { isSoundPlaying = false; }, 5000);
        }

        // ERROR SOUND - Beep only
        function playErrorSound() {
            console.log('Playing error beep...');
            try {
                const ctx = ensureAudioContext();
                const oscillator = ctx.createOscillator();
                const gainNode = ctx.createGain();

                oscillator.connect(gainNode);
                gainNode.connect(ctx.destination);

                oscillator.frequency.value = 400;  // Error beep frequency
                oscillator.type = 'square';        // Square wave for buzzer sound
                gainNode.gain.value = 0.3;         // Volume

                oscillator.start(ctx.currentTime);
                oscillator.stop(ctx.currentTime + 0.3);  // 300ms beep
            } catch (e) {
                console.error('Error beep failed:', e);
            }
        }

        // Tab switching
        document.addEventListener('DOMContentLoaded', function() {
            const tabButtons = document.querySelectorAll('.tab-btn');
            const tabContents = document.querySelectorAll('.tab-content');
            const rfidInput = document.getElementById('rfidInput');

            tabButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const tabId = this.dataset.tab;
                    
                    tabButtons.forEach(btn => btn.classList.remove('active'));
                    tabContents.forEach(content => content.classList.remove('active'));
                    
                    this.classList.add('active');
                    document.getElementById(tabId + '-tab').classList.add('active');
                    
                    if (tabId === 'rfid') {
                        setTimeout(() => rfidInput.focus(), 100);
                    }
                });
            });

            // RFID Scanner functionality
            const statusMessage = document.getElementById('statusMessage');
            const memberInfo = document.getElementById('memberInfo');
            const rfidForm = document.getElementById('rfidForm');
            let isProcessing = false;
            let scanTimeout = null;

            rfidInput.addEventListener('input', function() {
                if (scanTimeout) clearTimeout(scanTimeout);
                
                scanTimeout = setTimeout(() => {
                    const rfidCode = rfidInput.value.trim();
                    if (rfidCode.length > 0 && !isProcessing) {
                        processRFIDScan(rfidCode);
                    }
                }, 300);
            });

            rfidForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const rfidCode = rfidInput.value.trim();
                if (rfidCode.length > 0 && !isProcessing) {
                    processRFIDScan(rfidCode);
                }
            });

            function processRFIDScan(rfidCode) {
                if (isProcessing) return;
                isProcessing = true;

                statusMessage.style.display = 'none';
                memberInfo.classList.remove('show');

                const formData = new FormData();
                formData.append('rfid_code', rfidCode);

                fetch('<?= base_url('/rfid/scan') ?>', {
                    method: 'POST',
                    body: formData,
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                .then(response => {
                    console.log('Response status:', response.status);
                    return response.json();
                })
                .then(data => {
                    isProcessing = false;
                    console.log('Response data:', data);
                    if (data.success) {
                        playSuccessSound(); // Play success sound
                        showSuccess(data.message, data.member_data);
                    } else {
                        playErrorSound(); // Play error sound
                        showError(data.message || 'An error occurred');
                        // Log error details for debugging
                        if (data.errors) {
                            console.error('Validation errors:', data.errors);
                        }
                        if (data.debug_data) {
                            console.error('Debug data:', data.debug_data);
                        }
                    }
                })
                .catch(error => {
                    isProcessing = false;
                    playErrorSound(); // Play error sound for network error
                    console.error('Network Error:', error);
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
                    memberInfo.innerHTML = `
                        <div class="member-info-item">
                            <span class="member-info-label">Member ID:</span>
                            <span>${escapeHtml(memberData.id || 'N/A')}</span>
                        </div>
                        <div class="member-info-item">
                            <span class="member-info-label">Name:</span>
                            <span>${escapeHtml(memberData.name || 'N/A')}</span>
                        </div>
                        <div class="member-info-item">
                            <span class="member-info-label">Type:</span>
                            <span>${escapeHtml(memberData.user_type || 'N/A')}</span>
                        </div>
                    `;
                    memberInfo.classList.add('show');
                }
            }

            function showError(message) {
                statusMessage.className = 'status-message status-error';
                statusMessage.textContent = message;
                statusMessage.style.display = 'block';
            }

            function escapeHtml(text) {
                const div = document.createElement('div');
                div.textContent = text;
                return div.innerHTML;
            }

            // Auto-focus RFID input
            rfidInput.focus();
        });
    </script>
</body>
</html>