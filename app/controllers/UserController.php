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
        require_auth();

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

        $this->users->create($email, password_hash($password, PASSWORD_DEFAULT));
        set_flash('success', 'User created successfully.');
        redirect('index.php?page=dashboard');
    }

    public function update(): void
    {
        require_auth();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !verify_csrf()) {
            set_flash('error', 'Invalid request.');
            redirect('index.php?page=dashboard');
        }

        $id = $_POST['id'] ?? '';
        $email = trim($_POST['email'] ?? '');
        $isActive = isset($_POST['is_active']);
        $isVerified = isset($_POST['is_verified']);

        if (!$id || !validate_email($email)) {
            set_flash('error', 'Invalid user input.');
            redirect('index.php?page=dashboard');
        }

        $this->users->update($id, $email, $isActive, $isVerified);
        set_flash('success', 'User updated successfully.');
        redirect('index.php?page=dashboard');
    }

    public function delete(): void
    {
        require_auth();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !verify_csrf()) {
            set_flash('error', 'Invalid request.');
            redirect('index.php?page=dashboard');
        }

        $id = $_POST['id'] ?? '';
        if (!$id) {
            set_flash('error', 'Missing user id.');
            redirect('index.php?page=dashboard');
        }

        $this->users->delete($id);
        set_flash('success', 'User deleted successfully.');
        redirect('index.php?page=dashboard');
    }
}
