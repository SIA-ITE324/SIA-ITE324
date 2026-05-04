<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\I18n\Time;

class Auth extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    /**
     * Show login form
     */
    public function login()
    {
        // If user is already logged in, redirect to dashboard
        if (session()->get('is_logged_in')) {
            $role = session()->get('user_role');
            if ($role === 'admin') {
                return redirect()->to('/admin/dashboard');
            } elseif ($role === 'staff') {
                return redirect()->to('/staff/dashboard');
            } elseif ($role === 'customer') {
                return redirect()->to('/customer/dashboard');
            }
        }

        return view('auth/login');
    }

    /**
     * Process login
     */
    public function attemptLogin()
    {
        // Check login attempts
        $ipAddress = $this->request->getIPAddress();
        if ($this->checkLoginAttempts($ipAddress)) {
            return redirect()->back()->withInput()->with('error', 'Too many login attempts. Please try again later.');
        }

        $rules = [
            'email' => 'required|valid_email',
            'password' => 'required|min_length[8]',
        ];

        if (!$this->validate($rules)) {
            $this->recordFailedAttempt($ipAddress);
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        $remember = $this->request->getPost('remember') ?? false;

        // Verify user credentials
        if (!$this->userModel->verifyPassword($email, $password)) {
            $this->recordFailedAttempt($ipAddress);
            return redirect()->back()->withInput()->with('error', 'Invalid email or password.');
        }

        // Get user details
        $user = $this->userModel->getUserByEmail($email);

        // Check if user is active
        if ($user['status'] !== 'active') {
            return redirect()->back()->withInput()->with('error', 'Your account is not active. Please contact support.');
        }

        // Clear failed attempts on successful login
        $this->clearFailedAttempts($ipAddress);

        // Update last login
        $this->userModel->updateLastLogin($user['id']);

        // Set session data
        $sessionData = [
            'user_id' => $user['id'],
            'user_email' => $user['email'],
            'user_name' => $user['first_name'] . ' ' . $user['last_name'],
            'user_role' => $user['role'],
            'is_logged_in' => true,
            'login_time' => Time::now(),
        ];

        if ($remember) {
            // Set remember cookie for 30 days
            $sessionData['remember_me'] = true;
            $sessionData['expires'] = Time::now()->addDays(30);
            
            // Store in localStorage for frontend
            setcookie('rememberedEmail', $email, time() + (86400 * 30), '/');
            setcookie('rememberedName', $user['first_name'], time() + (86400 * 30), '/');
        }

        session()->set($sessionData);

        // Log activity
        $this->logActivity('login', 'user', $user['id'], 'User logged in');

        // Redirect based on role
        if ($user['role'] === 'admin') {
            return redirect()->to('/admin/dashboard')->with('success', 'Welcome back, ' . $user['first_name'] . '!');
        } elseif ($user['role'] === 'staff') {
            return redirect()->to('/staff/dashboard')->with('success', 'Welcome back, ' . $user['first_name'] . '!');
        } else {
            return redirect()->to('/customer/dashboard')->with('success', 'Welcome back, ' . $user['first_name'] . '!');
        }
    }

    /**
     * Send magic link
     */
    public function sendMagicLink()
    {
        $email = $this->request->getPost('email');
        
        if (!$email) {
            return $this->response->setJSON(['success' => false, 'message' => 'Email is required']);
        }

        $user = $this->userModel->getUserByEmail($email);
        
        if (!$user) {
            // Don't reveal if email exists for security
            return $this->response->setJSON(['success' => true, 'message' => 'Magic link sent if email exists']);
        }

        // Generate magic link token
        $token = bin2hex(random_bytes(32));
        $expiresAt = Time::now()->addMinutes(15); // Magic link expires in 15 minutes

        // Store magic link token
        $db = \Config\Database::connect();
        $db->table('magic_links')->insert([
            'user_id' => $user['id'],
            'token' => $token,
            'expires_at' => $expiresAt->toDateTimeString(),
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        // TODO: Send email with magic link
        // For now, return success
        $magicLink = site_url('/auth/magic-login/' . $token);
        
        // Log activity
        $this->logActivity('magic_link_sent', 'user', $user['id'], 'Magic link sent to email');

        return $this->response->setJSON([
            'success' => true, 
            'message' => 'Magic link sent! Check your email.',
            'debug_link' => $magicLink // Remove in production
        ]);
    }

    /**
     * Process magic link login
     */
    public function magicLogin($token)
    {
        $db = \Config\Database::connect();
        
        // Check if token exists and is not expired
        $magicLink = $db->table('magic_links')
            ->where('token', $token)
            ->where('expires_at >', date('Y-m-d H:i:s'))
            ->get()
            ->getRow();

        if (!$magicLink) {
            return redirect()->to('/login')->with('error', 'Invalid or expired magic link.');
        }

        // Get user
        $user = $this->userModel->find($magicLink->user_id);
        
        if (!$user || $user['status'] !== 'active') {
            return redirect()->to('/login')->with('error', 'Account not found or inactive.');
        }

        // Delete used magic link
        $db->table('magic_links')->delete(['id' => $magicLink->id]);

        // Update last login
        $this->userModel->updateLastLogin($user['id']);

        // Set session data
        session()->set([
            'user_id' => $user['id'],
            'user_email' => $user['email'],
            'user_name' => $user['first_name'] . ' ' . $user['last_name'],
            'user_role' => $user['role'],
            'is_logged_in' => true,
            'login_time' => Time::now(),
        ]);

        // Log activity
        $this->logActivity('magic_link_login', 'user', $user['id'], 'User logged in via magic link');

        return redirect()->to('/customer/dashboard')->with('success', 'Welcome back, ' . $user['first_name'] . '!');
    }

    /**
     * Guest order tracking
     */
    public function trackOrder()
    {
        $orderId = $this->request->getGet('order_id');
        $email = $this->request->getGet('email');

        if (!$orderId || !$email) {
            return redirect()->to('/login')->with('error', 'Order ID and email are required.');
        }

        // Get order from database
        $db = \Config\Database::connect();
        $order = $db->table('orders')
            ->where('order_number', $orderId)
            ->where('customer_email', $email)
            ->get()
            ->getRow();

        if (!$order) {
            return redirect()->to('/login')->with('error', 'Order not found or email does not match.');
        }

        // Store order in session for guest tracking
        session()->set([
            'guest_order' => $order,
            'is_guest_tracking' => true,
        ]);

        return redirect()->to('/track-order-details');
    }

    /**
     * Show guest order tracking details
     */
    public function guestOrderDetails()
    {
        if (!session()->get('is_guest_tracking') || !session()->get('guest_order')) {
            return redirect()->to('/login');
        }

        $order = session()->get('guest_order');
        
        // Get order items
        $db = \Config\Database::connect();
        $items = $db->table('order_items')
            ->where('order_id', $order->id)
            ->get()
            ->getResultArray();

        return view('guest_order_tracking', [
            'order' => $order,
            'items' => $items,
        ]);
    }

    /**
     * Check login attempts
     */
    private function checkLoginAttempts($ipAddress)
    {
        $db = \Config\Database::connect();
        $attempts = $db->table('login_attempts')
            ->where('ip_address', $ipAddress)
            ->where('created_at >', date('Y-m-d H:i:s', strtotime('-15 minutes')))
            ->countAllResults();

        return $attempts >= 3;
    }

    /**
     * Record failed login attempt
     */
    private function recordFailedAttempt($ipAddress)
    {
        $db = \Config\Database::connect();
        $db->table('login_attempts')->insert([
            'ip_address' => $ipAddress,
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }

    /**
     * Clear failed login attempts
     */
    private function clearFailedAttempts($ipAddress)
    {
        $db = \Config\Database::connect();
        $db->table('login_attempts')->where('ip_address', $ipAddress)->delete();
    }

    /**
     * Show registration form
     */
    public function register()
    {
        // If user is already logged in, redirect to dashboard
        if (session()->get('is_logged_in')) {
            return $this->redirectToDashboard();
        }

        return view('auth/register');
    }

    /**
     * Process registration
     */
    public function attemptRegister()
    {
        $rules = [
            'first_name' => 'required|min_length[2]|max_length[100]',
            'last_name' => 'required|min_length[2]|max_length[100]',
            'email' => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[8]',
            'password_confirm' => 'required|matches[password]',
            'phone' => 'permit_empty|max_length[20]',
            'address' => 'permit_empty',
            'city' => 'permit_empty|max_length[100]',
            'state' => 'permit_empty|max_length[100]',
            'postal_code' => 'permit_empty|max_length[20]',
            'country' => 'permit_empty|max_length[100]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $userData = [
            'first_name' => $this->request->getPost('first_name'),
            'last_name' => $this->request->getPost('last_name'),
            'email' => $this->request->getPost('email'),
            'password' => $this->request->getPost('password'),
            'phone' => $this->request->getPost('phone'),
            'role' => 'customer', // Default role for registration
            'status' => 'active',
            'address' => $this->request->getPost('address'),
            'city' => $this->request->getPost('city'),
            'state' => $this->request->getPost('state'),
            'postal_code' => $this->request->getPost('postal_code'),
            'country' => $this->request->getPost('country'),
            'email_verified' => true, // Auto-verify for simplicity
        ];

        // Insert user
        $userId = $this->userModel->insert($userData);

        if (!$userId) {
            return redirect()->back()->withInput()->with('error', 'Registration failed. Please try again.');
        }

        // Log activity
        $this->logActivity('register', 'user', $userId, 'New user registered');

        // Auto-login after registration
        $user = $this->userModel->find($userId);
        $this->userModel->updateLastLogin($userId);

        session()->set([
            'user_id' => $user['id'],
            'user_email' => $user['email'],
            'user_name' => $user['first_name'] . ' ' . $user['last_name'],
            'user_role' => $user['role'],
            'is_logged_in' => true,
            'login_time' => Time::now(),
        ]);

        return redirect()->to('/customer/dashboard')->with('success', 'Registration successful! Welcome to Fleur!');
    }

    /**
     * Logout user
     */
    public function logout()
    {
        if (session()->get('is_logged_in')) {
            // Log activity
            $this->logActivity('logout', 'user', session()->get('user_id'), 'User logged out');
        }

        // Clear session data
        session()->destroy();

        return redirect()->to('/login')->with('success', 'You have been logged out successfully.');
    }

    /**
     * Show forgot password form
     */
    public function forgotPassword()
    {
        return view('auth/forgot_password');
    }

    /**
     * Process forgot password
     */
    public function processForgotPassword()
    {
        $rules = [
            'email' => 'required|valid_email',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $email = $this->request->getPost('email');
        $user = $this->userModel->getUserByEmail($email);

        if (!$user) {
            // Don't reveal if email exists or not for security
            return redirect()->to('/login')->with('success', 'If your email exists in our system, you will receive a password reset link.');
        }

        // Generate password reset token
        $token = $this->userModel->generatePasswordResetToken($email);

        // TODO: Send email with reset link
        // For now, just show success message
        $this->logActivity('password_reset_request', 'user', $user['id'], 'Password reset requested');

        return redirect()->to('/login')->with('success', 'If your email exists in our system, you will receive a password reset link.');
    }

    /**
     * Show reset password form
     */
    public function resetPassword($token)
    {
        $user = $this->userModel->verifyPasswordResetToken($token);

        if (!$user) {
            return redirect()->to('/login')->with('error', 'Invalid or expired reset token.');
        }

        return view('auth/reset_password', ['token' => $token]);
    }

    /**
     * Process password reset
     */
    public function processResetPassword()
    {
        $rules = [
            'token' => 'required',
            'password' => 'required|min_length[8]',
            'password_confirm' => 'required|matches[password]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $token = $this->request->getPost('token');
        $password = $this->request->getPost('password');

        $user = $this->userModel->verifyPasswordResetToken($token);

        if (!$user) {
            return redirect()->to('/login')->with('error', 'Invalid or expired reset token.');
        }

        // Update password
        $this->userModel->update($user['id'], ['password' => $password]);

        // Clear reset token
        $this->userModel->clearPasswordResetToken($user['id']);

        // Log activity
        $this->logActivity('password_reset', 'user', $user['id'], 'Password reset successful');

        return redirect()->to('/login')->with('success', 'Your password has been reset successfully. Please login with your new password.');
    }

    /**
     * Redirect to dashboard based on user role
     */
    private function redirectToDashboard()
    {
        $role = session()->get('user_role');
        if ($role === 'admin') {
            return redirect()->to('/admin/dashboard');
        } elseif ($role === 'staff') {
            return redirect()->to('/staff/dashboard');
        } elseif ($role === 'customer') {
            return redirect()->to('/customer/dashboard');
        }
        return redirect()->to('/login');
    }

    /**
     * Log user activity
     */
    private function logActivity($action, $entityType, $entityId, $description)
    {
        $db = \Config\Database::connect();
        
        $data = [
            'user_id' => session()->get('user_id'),
            'action' => $action,
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'description' => $description,
            'ip_address' => $this->request->getIPAddress(),
            'user_agent' => $this->request->getUserAgent(),
            'created_at' => date('Y-m-d H:i:s'),
        ];

        $db->table('activity_logs')->insert($data);
    }
}
