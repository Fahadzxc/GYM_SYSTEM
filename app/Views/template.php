<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $this->renderSection('title') ?> - Gym Management System</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', 'Inter', sans-serif;
            height: 100vh;
            overflow: hidden;
        }

        .container {
            display: flex;
            height: 100vh;
        }

        .left-side {
            flex: 1;
            background: radial-gradient(ellipse at center, #001233 0%, #000814 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .left-side::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at 50% 50%, rgba(255, 215, 0, 0.1) 0%, transparent 70%);
            pointer-events: none;
        }

        .gym-logo {
            width: 750px;
            height: 600px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            filter: drop-shadow(0 0 50px rgba(255, 215, 0, 0.3));
        }

        .logo-placeholder {
            width: 100%;
            height: 100%;
            background-color: transparent;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }
        
        .bodybuilder-image {
            width: 675px;
            height: 540px;
            object-fit: contain;
            background: transparent;
            filter: 
                drop-shadow(0 0 20px rgba(255, 215, 0, 0.8))
                drop-shadow(0 0 40px rgba(255, 215, 0, 0.6))
                drop-shadow(0 0 60px rgba(255, 215, 0, 0.4))
                brightness(1.1)
                contrast(1.2)
                saturate(1.1);
            transition: all 0.3s ease;
        }

        .bodybuilder-image:hover {
            filter: 
                drop-shadow(0 0 25px rgba(255, 215, 0, 1.0))
                drop-shadow(0 0 50px rgba(255, 215, 0, 0.8))
                drop-shadow(0 0 75px rgba(255, 215, 0, 0.6))
                brightness(1.2)
                contrast(1.3)
                saturate(1.2);
            transform: scale(1.02);
        }

        .right-side {
            flex: 1;
            background-color: #FFF6E5;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 40px;
        }

        .school-logo {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1px auto;
            position: relative;
            overflow: hidden;
            padding: 0;
        }

        .school-logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            border-radius: 50%;
            display: block;
        }

        .admin-title {
            font-size: 24px;
            font-weight: 600;
            color: #001233;
            margin-bottom: 10px;
            margin-top: 10px;
            letter-spacing: 2px;
            text-align: center;
        }

        .divider {
            width: 80px;
            height: 1px;
            background-color: #001233;
            margin: 0 auto 15px auto;
        }

        .welcome-text {
            font-size: 14px;
            color: #001233;
            margin-bottom: 30px;
            font-weight: 400;
            text-align: center;
            position: relative;
        }

        .welcome-text::before,
        .welcome-text::after {
            content: '';
            position: absolute;
            top: 50%;
            width: 60px;
            height: 1px;
            background-color: #001233;
        }

        .welcome-text::before {
            left: -70px;
        }

        .welcome-text::after {
            right: -70px;
        }

        .form-container {
            width: 100%;
            max-width: 350px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            color: #001233;
            font-weight: 600;
            font-size: 14px;
        }

        .form-input {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #001233;
            border-radius: 25px;
            font-size: 14px;
            outline: none;
            transition: all 0.3s ease;
            background-color: #FFFFFF;
        }

        .form-input:focus {
            border-color: #FFD700;
            box-shadow: 0 0 0 3px rgba(255, 215, 0, 0.1);
        }

        .login-btn {
            width: 100%;
            padding: 12px;
            background-color: #001233;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
        }

        .login-btn:hover {
            background-color: #002855;
            transform: translateY(-1px);
        }

        .login-btn:active {
            transform: translateY(0);
        }

        .error-message {
            color: #dc3545;
            font-size: 12px;
            margin-top: 5px;
            display: none;
        }

        /* Premium Bodybuilder Animations */
        @keyframes neonPulse {
            0%, 100% {
                filter: 
                    drop-shadow(0 0 20px rgba(255, 215, 0, 0.8))
                    drop-shadow(0 0 40px rgba(255, 215, 0, 0.6))
                    drop-shadow(0 0 60px rgba(255, 215, 0, 0.4))
                    brightness(1.1)
                    contrast(1.2)
                    saturate(1.1);
            }
            50% {
                filter: 
                    drop-shadow(0 0 30px rgba(255, 215, 0, 1.0))
                    drop-shadow(0 0 60px rgba(255, 215, 0, 0.8))
                    drop-shadow(0 0 90px rgba(255, 215, 0, 0.6))
                    brightness(1.3)
                    contrast(1.4)
                    saturate(1.3);
            }
        }

        .bodybuilder-image {
            animation: neonPulse 3s ease-in-out infinite;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .container {
                flex-direction: column;
            }
            
            .left-side {
                flex: 0 0 40%;
                min-height: 40vh;
            }
            
            .right-side {
                flex: 1;
                padding: 20px;
            }
            
            .gym-logo {
                width: 120px;
                height: 120px;
            }
            
            .muscle-silhouette {
                width: 80px;
                height: 80px;
            }
            
            .muscle-silhouette::after {
                font-size: 25px;
            }
        }

        @media (max-width: 480px) {
            .left-side {
                flex: 0 0 30%;
            }
            
            .admin-title {
                font-size: 20px;
            }
            
            .form-container {
                max-width: 280px;
            }
        }
    </style>

    <?= $this->renderSection('styles') ?>
</head>
<body>
    <?= $this->renderSection('content') ?>
    
    <script>
        // Global JavaScript functions
        function validateForm() {
            const email = document.getElementById('email');
            const password = document.getElementById('password');
            const emailError = document.getElementById('email-error');
            const passwordError = document.getElementById('password-error');
            
            let isValid = true;
            
            // Reset error messages
            emailError.style.display = 'none';
            passwordError.style.display = 'none';
            
            // Validate email
            if (!email.value.trim()) {
                emailError.textContent = 'Email is required';
                emailError.style.display = 'block';
                isValid = false;
            } else if (!isValidEmail(email.value)) {
                emailError.textContent = 'Please enter a valid email address';
                emailError.style.display = 'block';
                isValid = false;
            }
            
            // Validate password
            if (!password.value.trim()) {
                passwordError.textContent = 'Password is required';
                passwordError.style.display = 'block';
                isValid = false;
            }
            
            if (!isValid) {
                alert('Please fill in all required fields correctly.');
                return false;
            }
            
            return true;
        }
        
        function isValidEmail(email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        }
        
        // Add input event listeners for real-time validation
        document.addEventListener('DOMContentLoaded', function() {
            const emailInput = document.getElementById('email');
            const passwordInput = document.getElementById('password');
            
            if (emailInput) {
                emailInput.addEventListener('input', function() {
                    const errorElement = document.getElementById('email-error');
                    if (this.value.trim() && errorElement.style.display === 'block') {
                        errorElement.style.display = 'none';
                    }
                });
            }
            
            if (passwordInput) {
                passwordInput.addEventListener('input', function() {
                    const errorElement = document.getElementById('password-error');
                    if (this.value.trim() && errorElement.style.display === 'block') {
                        errorElement.style.display = 'none';
                    }
                });
            }
        });
    </script>
    
    <?= $this->renderSection('scripts') ?>
</body>
</html>