<?php

class SimpleAuth extends SimpleBaseController {
    
    private $userModel;
    
    public function __construct() {
        parent::__construct();
        $this->userModel = new SimpleUserModel();
    }
    
    public function login() {
        // If user is already logged in, redirect to dashboard
        if ($this->isLoggedIn()) {
            $role = $this->getCurrentUserRole();
            if ($role === 'admin') {
                $this->redirect(BASE_URL . 'admin/dashboard');
            } else {
                $this->redirect(BASE_URL . 'home');
            }
        }
        
        $data = [
            'page_title' => 'Login - Fleur',
        ];
        
        return $this->view('auth/login', $data);
    }
    
    public function attemptLogin() {
        $rules = [
            'email' => 'required|email',
            'password' => 'required|min_length[8]',
        ];
        
        $data = $_POST;
        $errors = $this->validate($data, $rules);
        
        if (!empty($errors)) {
            session()->set('errors', $errors);
            return $this->redirect(BASE_URL . 'login');
        }
        
        $email = $data['email'];
        $password = $data['password'];
        
        // Verify user credentials
        if (!$this->userModel->verifyPassword($email, $password)) {
            session()->set('error', 'Invalid email or password.');
            return $this->redirect(BASE_URL . 'login');
        }
        
        // Get user details
        $user = $this->userModel->getUserByEmail($email);
        
        // Check if user is active
        if ($user['status'] !== 'active') {
            session()->set('error', 'Your account is not active. Please contact support.');
            return $this->redirect(BASE_URL . 'login');
        }
        
        // Update last login
        $this->userModel->updateLastLogin($user['id']);
        
        // Set session data
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];
        $_SESSION['user_role'] = $user['role'];
        $_SESSION['is_logged_in'] = true;
        $_SESSION['login_time'] = date('Y-m-d H:i:s');
        
        // Redirect based on role
        if ($user['role'] === 'admin') {
            session()->set('success', 'Welcome back, ' . $user['first_name'] . '!');
            return $this->redirect(BASE_URL . 'admin/dashboard');
        } else {
            session()->set('success', 'Welcome back, ' . $user['first_name'] . '!');
            return $this->redirect(BASE_URL . 'home');
        }
    }
    
    public function register() {
        // If user is already logged in, redirect to dashboard
        if ($this->isLoggedIn()) {
            $this->redirect(BASE_URL . 'home');
        }
        
        $data = [
            'page_title' => 'Register - Fleur',
        ];
        
        return $this->view('auth/register', $data);
    }
    
    public function attemptRegister() {
        $rules = [
            'first_name' => 'required|min_length[2]',
            'last_name' => 'required|min_length[2]',
            'email' => 'required|email',
            'password' => 'required|min_length[8]',
            'password_confirm' => 'required',
        ];
        
        $data = $_POST;
        $errors = $this->validate($data, $rules);
        
        // Check if passwords match
        if ($data['password'] !== $data['password_confirm']) {
            $errors['password_confirm'] = 'Passwords do not match';
        }
        
        // Check if email already exists
        $existingUser = $this->userModel->getUserByEmail($data['email']);
        if ($existingUser) {
            $errors['email'] = 'This email address is already registered.';
        }
        
        if (!empty($errors)) {
            session()->set('errors', $errors);
            session()->set('old_input', $data);
            return $this->redirect(BASE_URL . 'register');
        }
        
        // Create user
        $userData = [
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'role' => 'customer',
            'status' => 'active',
        ];
        
        $userId = $this->userModel->createUser($userData);
        
        if ($userId) {
            // Auto-login after registration
            $user = $this->userModel->getUserById($userId);
            $this->userModel->updateLastLogin($userId);
            
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];
            $_SESSION['user_role'] = $user['role'];
            $_SESSION['is_logged_in'] = true;
            $_SESSION['login_time'] = date('Y-m-d H:i:s');
            
            session()->set('success', 'Registration successful! Welcome to Fleur!');
            return $this->redirect(BASE_URL . 'home');
        } else {
            session()->set('error', 'Registration failed. Please try again.');
            return $this->redirect(BASE_URL . 'register');
        }
    }
    
    public function logout() {
        if ($this->isLoggedIn()) {
            session_destroy();
        }
        
        return $this->redirect(BASE_URL . 'login');
    }
}
?>
