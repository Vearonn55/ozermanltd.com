<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Services\Auth\AuthService;

class AuthMiddleware
{
    public function handle(): ?array
    {
        $auth = new AuthService();
        $user = $auth->user();

        if ($user === null) {
            flash('error', 'Please sign in to continue.');
            redirect('/admin/login');
        }

        return $user;
    }
}
