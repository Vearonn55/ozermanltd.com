<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Services\Auth\AuthService;

class AuthController
{
    public function showLogin(): void
    {
        $auth = new AuthService();
        if ($auth->check()) {
            redirect('/admin');
        }

        admin_guest_view('auth.login', [
            'pageTitle' => 'Sign In',
        ]);
    }

    public function login(): void
    {
        $token = $_POST['_csrf'] ?? '';
        if (!verify_csrf($token)) {
            flash('error', 'Invalid security token.');
            redirect('/admin/login');
        }

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($email === '' || $password === '') {
            flash('error', 'Email and password are required.');
            redirect('/admin/login');
        }

        $auth = new AuthService();
        $error = $auth->attempt($email, $password);
        if ($error !== null) {
            flash('error', $error);
            redirect('/admin/login');
        }

        redirect('/admin');
    }

    public function logout(): void
    {
        (new AuthService())->logout();
        flash('success', 'You have been signed out.');
        redirect('/admin/login');
    }
}
