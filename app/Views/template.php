<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $this->renderSection('title') ?> - Gym Management System</title>
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            height: 100vh;
            overflow: hidden;
        }

        .container {
            display: flex;
            height: 100vh;
        }

        .left-side {
            flex: 1;
            background-color: #011936;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .gym-logo {
            width: 500px;
            height: 400px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
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
            width: 450px;
            height: 360px;
            object-fit: contain;
            filter: drop-shadow(0 0 30px rgba(255, 215, 0, 1.0));
            background: transparent;
            mix-blend-mode: screen;
        }

        .right-side {
            flex: 1;
            background-color: #FFF6E0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 40px;
        }

        .admin-logo {
            width: 60px;
            height: 60px;
            background-color: #011936;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 10px;
            border: 3px solid #011936;
        }

        .admin-logo::before {
            content: '🏋️';
            font-size: 30px;
        }

        .admin-title {
            font-size: 24px;
            font-weight: bold;
            color: #011936;
            margin-bottom: 10px;
            letter-spacing: 2px;
        }

        .divider {
            width: 100px;
            height: 2px;
            background-color: #011936;
            margin-bottom: 20px;
        }

        .welcome-text {
            font-size: 16px;
            color: #011936;
            margin-bottom: 30px;
            font-weight: 500;
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
            color: #011936;
            font-weight: 500;
            font-size: 14px;
        }

        .form-input {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #011936;
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
            background-color: #011936;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
        }

        .login-btn:hover {
            background-color: #0a2447;
            transform: translateY(-2px);
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