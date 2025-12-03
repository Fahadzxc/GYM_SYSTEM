<?= $this->extend('template') ?>

<?= $this->section('title') ?>
Admin Login
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container">
    <div class="left-side">
        <div class="gym-logo">
            <div class="logo-placeholder">
                <img src="<?= base_url('assets/images/bodybuilder.png') ?>" alt="Bodybuilder Logo" class="bodybuilder-image">
            </div>
        </div>
    </div>
    
    <div class="right-side">
        <div class="school-logo">
            <img src="<?= base_url('assets/images/rmmc-logo.png') ?>" alt="Ramon Magsaysay Memorial Colleges Logo">
        </div>
        <h1 class="admin-title">ADMIN LOGIN</h1>
        <p class="welcome-text">Welcome back!</p>
        
        <?php if (session()->getFlashdata('error')): ?>
            <div style="background-color: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; margin-bottom: 20px; border: 1px solid #f5c6cb;">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>
        
        <?php 
        $errors = session()->getFlashdata('errors');
        if ($errors && is_array($errors)): 
            foreach ($errors as $field => $error): 
                if (is_array($error)) {
                    $error = implode(', ', $error);
                }
        ?>
            <div style="background-color: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; margin-bottom: 20px; border: 1px solid #f5c6cb;">
                <?= $error ?>
            </div>
        <?php 
            endforeach;
        endif; 
        ?>
        
        <?php if (session()->getFlashdata('success')): ?>
            <div style="background-color: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin-bottom: 20px; border: 1px solid #c3e6cb;">
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
                >
                <div id="email-error" class="error-message"></div>
                <?php 
                $validationErrors = session()->getFlashdata('errors');
                if (isset($validationErrors['email'])): 
                    $emailError = is_array($validationErrors['email']) ? implode(', ', $validationErrors['email']) : $validationErrors['email'];
                ?>
                    <div class="error-message" style="display: block; color: #721c24; font-size: 12px; margin-top: 5px;"><?= $emailError ?></div>
                <?php endif; ?>
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
                >
                <div id="password-error" class="error-message"></div>
                <?php 
                $validationErrors = session()->getFlashdata('errors');
                if (isset($validationErrors['password'])): 
                    $passwordError = is_array($validationErrors['password']) ? implode(', ', $validationErrors['password']) : $validationErrors['password'];
                ?>
                    <div class="error-message" style="display: block; color: #721c24; font-size: 12px; margin-top: 5px;"><?= $passwordError ?></div>
                <?php endif; ?>
            </div>
            
            <button type="submit" class="login-btn">LOGIN</button>
        </form>
        
    </div>
</div>
<?= $this->endSection() ?>