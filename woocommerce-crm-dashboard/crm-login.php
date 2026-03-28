<?php
/**
 * Shortcode for Custom CRM Login & Registration
 * Usage: Add [woocrm_auth] to any WordPress page!
 */

if (!defined('ABSPATH')) exit;

add_shortcode('woocrm_auth', 'woocrm_render_auth_page');

function woocrm_render_auth_page() {
    $message = '';
    
    // --- 1. HANDLE REGISTRATION (Data goes into Database) ---
    if (isset($_POST['woocrm_action']) && $_POST['woocrm_action'] === 'register') {
        $email = sanitize_email($_POST['email']);
        $pass = $_POST['password'];
        $name = sanitize_text_field($_POST['name']);
        
        if (email_exists($email) || username_exists($email)) {
            $message = '<div class="text-red-400 bg-red-500/10 p-4 rounded-xl border border-red-500/30 mb-6 text-sm font-medium"><i class="fas fa-exclamation-circle mr-2"></i>Email already registered.</div>';
        } else {
            // Create user securely in the WordPress database
            $user_id = wp_create_user($email, $pass, $email);
            if (!is_wp_error($user_id)) {
                wp_update_user(['ID' => $user_id, 'first_name' => $name, 'role' => 'customer']);
                $message = '<div class="text-emerald-400 bg-emerald-500/10 p-4 rounded-xl border border-emerald-500/30 mb-6 text-sm font-medium"><i class="fas fa-check-circle mr-2"></i>Account saved to database! You can now log in.</div>';
            } else {
                $message = '<div class="text-red-400 bg-red-500/10 p-4 rounded-xl border border-red-500/30 mb-6 text-sm font-medium">Database Error: ' . $user_id->get_error_message() . '</div>';
            }
        }
    }
    
    // --- 2. HANDLE LOGIN ---
    if (isset($_POST['woocrm_action']) && $_POST['woocrm_action'] === 'login') {
        $creds = [
            'user_login'    => sanitize_email($_POST['email']),
            'user_password' => $_POST['password'],
            'remember'      => true
        ];
        $user = wp_signon($creds, false);
        if (is_wp_error($user)) {
            $message = '<div class="text-red-400 bg-red-500/10 p-4 rounded-xl border border-red-500/30 mb-6 text-sm font-medium"><i class="fas fa-times-circle mr-2"></i>Invalid credentials.</div>';
        } else {
            // Logged in! Redirecting to WooCRM dashboard
            wp_redirect(admin_url('admin.php?page=woocrm-dashboard'));
            exit;
        }
    }

    ob_start();
    ?>
    
    <!-- Include Tailwind & FontAwesome just to ensure styles match if theme doesn't have it -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <style>
        .glass-auth { background: rgba(17, 24, 39, 0.6); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); }
        .auth-input { transition: all 0.3s ease; }
        .auth-input:focus { transform: translateY(-1px); box-shadow: 0 4px 20px -2px rgba(79, 70, 229, 0.2); }
    </style>

    <div class="min-h-[80vh] flex items-center justify-center p-4" style="background-image: radial-gradient(circle at 50% 0%, rgba(79, 70, 229, 0.1), transparent 50%);">
        <div class="glass-auth border border-slate-700/50 rounded-3xl p-8 max-w-md w-full shadow-2xl relative overflow-hidden">
            <!-- Glow effect -->
            <div class="absolute -top-20 -right-20 w-40 h-40 bg-indigo-500/20 rounded-full blur-[50px] pointer-events-none"></div>
            
            <div class="text-center mb-8 relative z-10">
                <div class="w-16 h-16 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl mx-auto flex items-center justify-center shadow-lg shadow-indigo-500/20 mb-4">
                    <i class="fas fa-layer-group text-2xl text-white"></i>
                </div>
                <h2 class="text-2xl font-bold text-white tracking-tight">Access Portal</h2>
                <p class="text-slate-400 text-sm mt-1">Log in or create a new account</p>
            </div>

            <?php echo $message; ?>

            <!-- Tabs -->
            <div class="flex p-1 bg-slate-800/50 rounded-xl mb-6 relative z-10">
                <button onclick="switchTab('login')" id="tab-login" class="flex-1 py-2 text-sm font-semibold rounded-lg bg-slate-700 text-white shadow transition-all">Log In</button>
                <button onclick="switchTab('register')" id="tab-register" class="flex-1 py-2 text-sm font-semibold rounded-lg text-slate-400 hover:text-white transition-all">Create Account</button>
            </div>

            <!-- Login Form -->
            <form method="POST" id="form-login" class="space-y-4 relative z-10">
                <input type="hidden" name="woocrm_action" value="login">
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1.5 uppercase tracking-wider">Email Address</label>
                    <div class="relative">
                        <i class="fas fa-envelope absolute left-4 top-1/2 transform -translate-y-1/2 text-slate-500"></i>
                        <input type="email" name="email" required class="auth-input w-full bg-slate-900 border border-slate-700 rounded-xl py-3 pl-10 pr-4 text-white focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" placeholder="admin@store.com">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1.5 uppercase tracking-wider">Password</label>
                    <div class="relative">
                        <i class="fas fa-lock absolute left-4 top-1/2 transform -translate-y-1/2 text-slate-500"></i>
                        <input type="password" name="password" required class="auth-input w-full bg-slate-900 border border-slate-700 rounded-xl py-3 pl-10 pr-4 text-white focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" placeholder="••••••••">
                    </div>
                </div>
                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-500 text-white font-semibold py-3 rounded-xl transition-all shadow-lg shadow-indigo-500/25 mt-2 hover:-translate-y-0.5">
                    Secure Login <i class="fas fa-arrow-right ml-2"></i>
                </button>
            </form>

            <!-- Register Form (Hidden by default) -->
            <form method="POST" id="form-register" class="space-y-4 relative z-10 hidden">
                <input type="hidden" name="woocrm_action" value="register">
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1.5 uppercase tracking-wider">Full Name</label>
                    <div class="relative">
                        <i class="fas fa-user absolute left-4 top-1/2 transform -translate-y-1/2 text-slate-500"></i>
                        <input type="text" name="name" required class="auth-input w-full bg-slate-900 border border-slate-700 rounded-xl py-3 pl-10 pr-4 text-white focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" placeholder="John Doe">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1.5 uppercase tracking-wider">Email Address</label>
                    <div class="relative">
                        <i class="fas fa-envelope absolute left-4 top-1/2 transform -translate-y-1/2 text-slate-500"></i>
                        <input type="email" name="email" required class="auth-input w-full bg-slate-900 border border-slate-700 rounded-xl py-3 pl-10 pr-4 text-white focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" placeholder="john@example.com">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1.5 uppercase tracking-wider">Create Password</label>
                    <div class="relative">
                        <i class="fas fa-lock absolute left-4 top-1/2 transform -translate-y-1/2 text-slate-500"></i>
                        <input type="password" name="password" required minlength="6" class="auth-input w-full bg-slate-900 border border-slate-700 rounded-xl py-3 pl-10 pr-4 text-white focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" placeholder="••••••••">
                    </div>
                </div>
                <button type="submit" class="w-full bg-slate-800 hover:bg-slate-700 text-white font-semibold py-3 rounded-xl transition-all border border-slate-600 mt-2">
                    Create Account & Save
                </button>
            </form>
        </div>
    </div>

    <script>
        function switchTab(tab) {
            const loginForm = document.getElementById('form-login');
            const registerForm = document.getElementById('form-register');
            const tabLogin = document.getElementById('tab-login');
            const tabRegister = document.getElementById('tab-register');

            if (tab === 'login') {
                loginForm.classList.remove('hidden');
                registerForm.classList.add('hidden');
                tabLogin.className = 'flex-1 py-2 text-sm font-semibold rounded-lg bg-slate-700 text-white shadow transition-all';
                tabRegister.className = 'flex-1 py-2 text-sm font-semibold rounded-lg text-slate-400 hover:text-white transition-all';
            } else {
                loginForm.classList.add('hidden');
                registerForm.classList.remove('hidden');
                tabRegister.className = 'flex-1 py-2 text-sm font-semibold rounded-lg bg-slate-700 text-white shadow transition-all';
                tabLogin.className = 'flex-1 py-2 text-sm font-semibold rounded-lg text-slate-400 hover:text-white transition-all';
            }
        }
    </script>
    <?php
    return ob_get_clean();
}
