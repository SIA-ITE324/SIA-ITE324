<?php

namespace App\Controllers;

class Dashboard extends BaseController
{
    /**
     * Display dashboard index (login required)
     */
    public function index()
    {
        if (!session()->get('user_id')) {
            return redirect()->to(base_url('auth/login'))->with('error', 'Please login first.');
        }
        return view('dashboard/index');
    }
}
