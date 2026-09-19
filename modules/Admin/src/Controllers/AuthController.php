<?php

declare(strict_types=1);

namespace Admin\Controllers;

use Admin\Services\Auth\AuthService;
use Admin\Support\RateLimiter;

class AuthController
{
    public function showLogin(): void
    {
        $auth = new AuthService();
        if ($auth->check()) {
            redirect(admin_url());
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
            redirect(admin_url('login'));
        }

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $limiter = RateLimiter::forAdmin();
        $limitKey = 'login:' . $ip;
        $max = (int) admin_config('login_rate_limit', 5);
        $window = (int) admin_config('login_rate_window_seconds', 900);

        if ($limiter->tooManyAttempts($limitKey, $max, $window)) {
            $retry = $limiter->retryAfterSeconds($limitKey, $window);
            flash('error', 'Too many login attempts. Try again in ' . max(1, (int) ceil($retry / 60)) . ' minute(s).');
            redirect(admin_url('login'));
        }

        if ($email === '' || $password === '') {
            $limiter->hit($limitKey, $window);
            flash('error', 'Email and password are required.');
            redirect(admin_url('login'));
        }

        $auth = new AuthService();
        $error = $auth->attempt($email, $password);
        if ($error !== null) {
            $limiter->hit($limitKey, $window);
            flash('error', $error);
            redirect(admin_url('login'));
        }

        $limiter->clear($limitKey);
        redirect(admin_url());
    }

    public function logout(): void
    {
        (new AuthService())->logout();
        flash('success', 'You have been signed out.');
        redirect(admin_url('login'));
    }
}
