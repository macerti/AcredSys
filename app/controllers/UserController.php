<?php

class UserController
{
    private UserModel $users;

    public function __construct()
    {
        $this->users = new UserModel();
    }

    public function create(): void
    {
        require_module_access('users');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !verify_csrf()) {
            set_flash('error', 'Invalid request.');
            redirect('index.php?page=dashboard');
        }

        $email = trim($_POST['email'] ?? '');
        $password = (string) ($_POST['password'] ?? '');

        if (!validate_email($email) || strlen($password) < 8) {
            set_flash('error', 'Invalid email or password too short.');
            redirect('index.php?page=dashboard');
        }

        $namePart = preg_replace('/[^a-zA-Z0-9]/', ' ', (string) strstr($email, '@', true));
        $namePart = trim((string) $namePart);
        $firstName = $namePart !== '' ? ucfirst(strtolower(explode(' ', $namePart)[0])) : 'User';
        $lastName = 'Account';

        $this->users->create($email, password_hash($password, PASSWORD_DEFAULT), $firstName, $lastName);
        set_flash('success', 'User created successfully. Assign role(s) in Role Management for this organization.');
        redirect('index.php?page=dashboard');
    }

    public function update(): void
    {
        require_module_access('users');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !verify_csrf()) {
            set_flash('error', 'Invalid request.');
            redirect('index.php?page=dashboard');
        }

        $id = (string) ($_POST['id'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $isActive = isset($_POST['is_active']);
        $isVerified = isset($_POST['is_verified']);

        if ($id === '' || !validate_email($email)) {
            set_flash('error', 'Invalid user input.');
            redirect('index.php?page=dashboard');
        }

        $this->users->update($id, $email, $isActive, $isVerified);
        set_flash('success', 'User updated successfully.');
        redirect('index.php?page=dashboard');
    }

    public function delete(): void
    {
        require_module_access('users');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !verify_csrf()) {
            set_flash('error', 'Invalid request.');
            redirect('index.php?page=dashboard');
        }

        $id = (string) ($_POST['id'] ?? '');
        if ($id === '') {
            set_flash('error', 'Missing user id.');
            redirect('index.php?page=dashboard');
        }

        $this->users->delete($id);
        set_flash('success', 'User deleted successfully.');
        redirect('index.php?page=dashboard');
    }
}
