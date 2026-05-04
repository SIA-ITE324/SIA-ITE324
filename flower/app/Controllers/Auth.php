<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    protected UserModel $userModel;

    public function __construct()
    {
        $this->userModel = model(UserModel::class);
    }

    /**
     * Display login page
     */
    public function login()
    {
        if (session()->get('user_id')) {
            return redirect()->to(base_url('dashboard'));
        }
        return view('auth/login');
    }

    /**
     * Process login form - verify against database
     */
    public function authenticate()
    {
        $email    = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        if (empty($email) || empty($password)) {
            return redirect()->back()->with('error', 'Please enter both email and password.');
        }

        $user = $this->userModel->verifyLogin($email, $password);

        if (!$user) {
            return redirect()->back()->with('error', 'Invalid email or password.');
        }

        session()->set([
            'user_id'  => $user['id'],
            'user_name' => $user['name'],
            'user_email' => $user['email'],
        ]);

        return redirect()->to(base_url('dashboard'))->with('success', 'Welcome, ' . $user['name'] . '!');
    }

    /**
     * Logout - clear session and redirect to login
     */
    public function logout()
    {
        session()->destroy();
        return redirect()->to(base_url('auth/login'))->with('success', 'You have been logged out.');
    }
}
