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
            margin: 0;
            padding: 0;
        }

        .main-container {
            display: flex;
            height: 100vh;
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

        /* Sidebar Styles */
        .sidebar {
            width: 250px;
            background-color: #081A3E;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            z-index: 1000;
            display: flex;
            flex-direction: column;
            border-radius: 0 15px 15px 0;
        }

        .sidebar-header {
            padding: 25px 20px;
            text-align: center;
        }

        .logo-placeholder {
            width: 70px;
            height: 70px;
            background-color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
            color: #081A3E;
            font-weight: 700;
            font-size: 14px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .sidebar-nav {
            flex: 1;
            padding: 20px 15px;
        }

        .nav-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .nav-item {
            margin-bottom: 8px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 15px 20px;
            color: white;
            text-decoration: none;
            transition: all 0.3s ease;
            font-size: 15px;
            font-weight: 500;
            border-radius: 25px;
            margin: 0 5px;
        }

        .nav-link:hover {
            background-color: rgba(255, 215, 0, 0.2);
            color: #FFD700;
        }

        .nav-item.active .nav-link {
            background-color: #F3C85D;
            color: #081A3E;
            font-weight: 700;
        }

        .nav-icon {
            margin-right: 15px;
            font-size: 18px;
            width: 20px;
            text-align: center;
        }

        .sidebar-footer {
            padding: 20px 15px;
        }

        .logout-btn {
            display: flex;
            align-items: center;
            padding: 15px 20px;
            background-color: #dc3545;
            color: white;
            text-decoration: none;
            transition: all 0.3s ease;
            font-size: 15px;
            font-weight: 600;
            border-radius: 25px;
            margin: 0 5px;
        }

        .logout-btn:hover {
            background-color: #c82333;
            transform: translateY(-1px);
        }

        /* Main Content Area */
        .main-content {
            flex: 1;
            margin-left: 250px;
            background-color: #FFF2DC;
            padding: 40px;
            overflow-y: auto;
            min-height: 100vh;
        }

        .content-header {
            margin-bottom: 35px;
        }

        .content-title {
            font-size: 32px;
            font-weight: 800;
            color: #2c3e50;
            margin: 0;
            letter-spacing: 1px;
        }

        .content-panel {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.08);
            border: 1px solid #0A2E73;
            max-width: 100%;
            margin: 0 auto;
        }

        /* Table Styles */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 25px;
            background-color: white;
        }

        .data-table th {
            background-color: white;
            color: #000;
            font-weight: 600;
            padding: 15px 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
            font-size: 14px;
        }

        .data-table td {
            padding: 15px 12px;
            border-bottom: 1px solid #ddd;
            color: #000;
            font-size: 14px;
            text-align: left;
            vertical-align: middle;
        }

        .data-table tr:hover {
            background-color: #f8f9fa;
        }

        .data-table tr:last-child td {
            border-bottom: none;
        }

        .status-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            display: inline-block;
        }

        .dot-green {
            background-color: #28a745;
        }

        .dot-red {
            background-color: #dc3545;
        }

        /* Manage Users Styles */
        .content-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 35px;
        }

        .add-user-btn {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .add-user-btn:hover {
            background-color: #218838;
            transform: translateY(-1px);
        }

        .action-btn {
            padding: 6px 12px;
            border: none;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            margin-right: 5px;
            transition: all 0.3s ease;
        }

        .edit-btn {
            background-color: #007bff;
            color: white;
        }

        .edit-btn:hover {
            background-color: #0056b3;
        }

        .delete-btn {
            background-color: #dc3545;
            color: white;
        }

        .delete-btn:hover {
            background-color: #c82333;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 2000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            background-color: #FFF2DC;
            margin: 5% auto;
            padding: 0;
            border-radius: 15px;
            width: 90%;
            max-width: 600px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            position: relative;
        }

        .modal-header {
            padding: 25px 30px 20px 30px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-header h2 {
            margin: 0;
            color: #2c3e50;
            font-size: 24px;
            font-weight: 700;
        }

        .close {
            color: #aaa;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            line-height: 1;
        }

        .close:hover {
            color: #000;
        }

        .modal-body {
            padding: 30px;
        }

        .form-row {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
        }

        .form-group {
            flex: 1;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #2c3e50;
            font-weight: 600;
            font-size: 14px;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #0A2E73;
            border-radius: 8px;
            font-size: 14px;
            transition: border-color 0.3s ease;
            background-color: white;
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #0A2E73;
            box-shadow: 0 0 0 2px rgba(10, 46, 115, 0.2);
        }

        .modal-footer {
            padding: 20px 30px 30px 30px;
            text-align: right;
        }

        .add-user-submit-btn {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .add-user-submit-btn:hover {
            background-color: #218838;
            transform: translateY(-1px);
        }

        .add-user-submit-btn:disabled {
            background-color: #6c757d;
            cursor: not-allowed;
            transform: none;
        }


        .data-table tbody tr:hover {
            background-color: #f8f9fa;
        }

        .action-btn {
            padding: 5px 10px;
            margin: 0 2px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
            transition: all 0.3s ease;
        }

        .edit-btn {
            background-color: #007bff;
            color: white;
        }

        .edit-btn:hover {
            background-color: #0056b3;
        }

        .delete-btn {
            background-color: #dc3545;
            color: white;
        }

        .delete-btn:hover {
            background-color: #c82333;
        }

        .close-edit {
            color: #aaa;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            line-height: 1;
        }

        .close-edit:hover {
            color: #000;
        }

        .save-user-btn {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .save-user-btn:hover {
            background-color: #218838;
            transform: translateY(-1px);
        }

        .save-user-btn:disabled {
            background-color: #6c757d;
            cursor: not-allowed;
            transform: none;
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