<?php
/**
 * Admin Login Page
 * PT Komodo Industrial Indonesia
 */

define('APP_ACCESS', true);

// Load configurations
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';

// Initialize session
initSession();

// Check if already logged in
if (isLoggedIn()) {
    redirect(ADMIN_URL . '/index.php');
}

// Handle login form submission
if (isPost()) {
    $username = post('username');
    $password = post('password');
    $remember = post('remember') === 'on';
    
    // Validate inputs
    $errors = [];
    
    if (empty($username)) {
        $errors[] = 'Username is required';
    }
    
    if (empty($password)) {
        $errors[] = 'Password is required';
    }
    
    if (empty($errors)) {
        // Attempt login
        $result = login($username, $password);
        
        if ($result['success']) {
            // Set remember me if checked
            if ($remember) {
                setRememberMe($result['user']['id'], true);
            }
            
            setFlash('success', 'Welcome back, ' . $result['user']['full_name'] . '!');
            redirect(ADMIN_URL . '/index.php');
        } else {
            $errors[] = $result['error'];
        }
    }
}

// Check remember me token
if (!isLoggedIn()) {
    if (checkRememberMe()) {
        redirect(ADMIN_URL . '/index.php');
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | <?php echo APP_NAME; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        body {
            font-family: 'Inter', sans-serif;
        }
        
        .gradient-bg {
            background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
        }
        
        .login-card {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
    </style>
</head>
<body class="gradient-bg min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <!-- Logo -->
        <div class="text-center mb-8">
            <h1 class="text-5xl font-bold text-white mb-2">
                <span class="text-green-500">KII</span>
            </h1>
            <p class="text-gray-400 text-sm">Admin Panel</p>
        </div>

        <!-- Login Card -->
        <div class="login-card rounded-2xl p-8 shadow-2xl">
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-white mb-2">Welcome Back</h2>
                <p class="text-gray-400 text-sm">Please sign in to continue</p>
            </div>

            <!-- Error Messages -->
            <?php if (!empty($errors)): ?>
                <div class="bg-red-500 bg-opacity-10 border border-red-500 text-red-500 rounded-lg p-4 mb-6">
                    <div class="flex items-start">
                        <i class="fas fa-exclamation-circle mt-0.5 mr-3"></i>
                        <div>
                            <?php foreach ($errors as $error): ?>
                                <p><?php echo htmlspecialchars($error); ?></p>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Flash Messages -->
            <?php displayFlash(); ?>

            <!-- Login Form -->
            <form method="POST" action="" class="space-y-6">
                <!-- Username Field -->
                <div>
                    <label for="username" class="block text-sm font-medium text-gray-300 mb-2">
                        Username
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-user text-gray-500"></i>
                        </div>
                        <input type="text" 
                               id="username" 
                               name="username" 
                               value="<?php echo htmlspecialchars(post('username', '')); ?>"
                               class="w-full pl-10 pr-4 py-3 bg-gray-800 border border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent text-white placeholder-gray-500"
                               placeholder="Enter your username"
                               required
                               autocomplete="username">
                    </div>
                </div>

                <!-- Password Field -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-300 mb-2">
                        Password
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-500"></i>
                        </div>
                        <input type="password" 
                               id="password" 
                               name="password" 
                               class="w-full pl-10 pr-12 py-3 bg-gray-800 border border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent text-white placeholder-gray-500"
                               placeholder="Enter your password"
                               required
                               autocomplete="current-password">
                        <button type="button" 
                                onclick="togglePassword()"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-gray-400">
                            <i class="fas fa-eye" id="toggleIcon"></i>
                        </button>
                    </div>
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between">
                    <label class="flex items-center">
                        <input type="checkbox" 
                               name="remember" 
                               class="w-4 h-4 rounded border-gray-600 bg-gray-800 text-green-600 focus:ring-green-500 focus:ring-offset-0">
                        <span class="ml-2 text-sm text-gray-400">Remember me</span>
                    </label>
                    <a href="#" class="text-sm text-green-500 hover:text-green-400">Forgot password?</a>
                </div>

                <!-- Submit Button -->
                <button type="submit" 
                        class="w-full py-3 px-4 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition duration-200 flex items-center justify-center">
                    <i class="fas fa-sign-in-alt mr-2"></i>
                    Sign In
                </button>
            </form>

            <!-- Default Credentials Info (Remove in production) -->
            <div class="mt-6 p-4 bg-gray-800 bg-opacity-50 rounded-lg border border-gray-700">
                <p class="text-xs text-gray-400 text-center mb-2">
                    <i class="fas fa-info-circle mr-1"></i> Default Credentials
                </p>
                <div class="text-xs text-gray-500 text-center space-y-1">
                    <p><strong>Username:</strong> admin</p>
                    <p><strong>Password:</strong> admin123</p>
                </div>
            </div>
        </div>

        <!-- Back to Website -->
        <div class="mt-6 text-center">
            <a href="<?php echo PUBLIC_URL; ?>/index.php" class="text-sm text-gray-400 hover:text-green-500 inline-flex items-center">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Website
            </a>
        </div>

        <!-- Footer -->
        <div class="mt-8 text-center text-xs text-gray-500">
            <p>&copy; <?php echo date('Y'); ?> <?php echo APP_NAME; ?>. All rights reserved.</p>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }

        // Auto-hide flash messages after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('[role="alert"]');
            alerts.forEach(function(alert) {
                alert.style.transition = 'opacity 0.5s';
                alert.style.opacity = '0';
                setTimeout(function() {
                    alert.remove();
                }, 500);
            });
        }, 5000);
    </script>
</body>
</html>