<?php

declare(strict_types=1);

namespace Admin\Controllers;

use Admin\Repositories\UserRepository;

class UserController extends BaseAdminController
{
    private UserRepository $repo;

    public function __construct()
    {
        $this->repo = new UserRepository();
    }

    public function index(): void
    {
        $this->authorize('super_admin');
        $this->render('users.index', [
            'pageTitle' => 'Users',
            'items' => $this->repo->all(),
        ]);
    }

    public function create(): void
    {
        $this->authorize('super_admin');
        $this->render('users.form', [
            'pageTitle' => 'New User',
            'item' => null,
            'roles' => array_keys(admin_config('roles', [])),
        ]);
    }

    public function store(): void
    {
        $this->authorize('super_admin');
        $this->verifyCsrf();

        $name = trim((string) ($_POST['name'] ?? ''));
        $email = trim((string) ($_POST['email'] ?? ''));
        $password = (string) ($_POST['password'] ?? '');
        $role = (string) ($_POST['role'] ?? 'editor');
        $status = (string) ($_POST['status'] ?? 'active');

        if ($name === '' || $email === '' || $password === '') {
            flash('error', 'Name, email, and password are required.');
            redirect(admin_url('users/create'));
        }

        if ($this->repo->findByEmail($email)) {
            flash('error', 'Email already in use.');
            redirect(admin_url('users/create'));
        }

        if (!isset(admin_config('roles', [])[$role])) {
            $role = 'editor';
        }

        $id = $this->repo->create([
            'name' => $name,
            'email' => $email,
            'password_hash' => password_hash($password, PASSWORD_BCRYPT),
            'role' => $role,
            'status' => in_array($status, ['active', 'inactive', 'suspended'], true) ? $status : 'active',
        ]);

        $this->logActivity('create', 'user', $id);
        flash('success', 'User created.');
        redirect(admin_url('users'));
    }

    public function edit(int $id): void
    {
        $this->authorize('super_admin');
        $item = $this->repo->find($id);
        if (!$item) {
            flash('error', 'User not found.');
            redirect(admin_url('users'));
        }

        $this->render('users.form', [
            'pageTitle' => 'Edit User',
            'item' => $item,
            'roles' => array_keys(admin_config('roles', [])),
        ]);
    }

    public function update(int $id): void
    {
        $actor = $this->authorize('super_admin');
        $this->verifyCsrf();

        $item = $this->repo->find($id);
        if (!$item) {
            flash('error', 'User not found.');
            redirect(admin_url('users'));
        }

        $name = trim((string) ($_POST['name'] ?? ''));
        $email = trim((string) ($_POST['email'] ?? ''));
        $role = (string) ($_POST['role'] ?? 'editor');
        $status = (string) ($_POST['status'] ?? 'active');
        $password = (string) ($_POST['password'] ?? '');

        if ($name === '' || $email === '') {
            flash('error', 'Name and email are required.');
            redirect(admin_url('users/' . $id . '/edit'));
        }

        $existing = $this->repo->findByEmail($email);
        if ($existing && (int) $existing['id'] !== $id) {
            flash('error', 'Email already in use.');
            redirect(admin_url('users/' . $id . '/edit'));
        }

        if (!isset(admin_config('roles', [])[$role])) {
            $role = $item['role'];
        }

        if (!in_array($status, ['active', 'inactive', 'suspended'], true)) {
            $status = $item['status'];
        }

        // Prevent locking yourself out of super_admin.
        if ((int) $actor['id'] === $id) {
            $role = 'super_admin';
            $status = 'active';
        }

        $data = [
            'name' => $name,
            'email' => $email,
            'role' => $role,
            'status' => $status,
        ];

        if ($password !== '') {
            $data['password_hash'] = password_hash($password, PASSWORD_BCRYPT);
        }

        $this->repo->update($id, $data);

        if (in_array($status, ['inactive', 'suspended'], true)) {
            $this->repo->revokeSessions($id);
            $this->logActivity('suspend', 'user', $id, ['status' => $status]);
        } else {
            $this->logActivity('update', 'user', $id);
        }

        flash('success', 'User updated.');
        redirect(admin_url('users'));
    }

    public function destroy(int $id): void
    {
        $actor = $this->authorize('super_admin');
        $this->verifyCsrf();

        if ((int) $actor['id'] === $id) {
            flash('error', 'You cannot delete your own account.');
            redirect(admin_url('users'));
        }

        $this->repo->delete($id);
        $this->logActivity('delete', 'user', $id);
        flash('success', 'User deleted.');
        redirect(admin_url('users'));
    }
}
