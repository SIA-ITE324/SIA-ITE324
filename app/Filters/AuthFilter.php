<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (!session()->get('is_logged_in')) {
            return redirect()->to('/login')->with('error', 'Please login to continue.');
        }

        // Check role-based access if arguments are provided
        if ($arguments) {
            $userRole = session()->get('user_role');
            $allowedRoles = is_array($arguments) ? $arguments : [$arguments];
            
            if (!in_array($userRole, $allowedRoles)) {
                if ($userRole === 'admin') {
                    return redirect()->to('/admin/dashboard');
                } elseif ($userRole === 'staff') {
                    return redirect()->to('/staff/dashboard');
                } elseif ($userRole === 'customer') {
                    return redirect()->to('/customer/dashboard');
                } else {
                    return redirect()->to('/login')->with('error', 'Invalid user role.');
                }
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing here
    }
}
