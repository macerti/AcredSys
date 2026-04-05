<?php

class AuthController
{
    private UserModel $users;
    private RoleModel $roles;
    private PasswordResetModel $resets;
    private SessionModel $sessions;

    public function __construct()
    {
        $this->users = new UserModel();
        $this->roles = new RoleModel();
        $this->resets = new PasswordResetModel();
        $this->sessions = new SessionModel();
    }

    public function register(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!verify_csrf()) {
                set_flash('error', 'Invalid CSRF token.');
                redirect('index.php?page=register');
            }

            $email = trim($_POST['email'] ?? '');
            $password = (string) ($_POST['password'] ?? '');

            if (!validate_email($email) || strlen($password) < 8) {
                set_flash('error', 'Use a valid email and password of at least 8 characters.');
                redirect('index.php?page=register');
            }

            if ($this->users->findByEmail($email)) {
                set_flash('error', 'Email is already registered.');
                redirect('index.php?page=register');
            }

            $this->users->create($email, password_hash($password, PASSWORD_DEFAULT));
            $created = $this->users->findByEmail($email);

            if ($created) {
                $allRoles = $this->roles->all();
                $userRoleId = null;
                foreach ($allRoles as $role) {
                    if ($role['name'] === 'user') {
                        $userRoleId = (int) $role['id'];
                    }
                }
                if ($userRoleId !== null) {
                    $this->roles->syncUserRoles($created['id'], [$userRoleId]);
                }
            }

            set_flash('success', 'Registration successful. You can now login.');
            redirect('index.php?page=login');
        }

        require __DIR__ . '/../views/auth/register.php';
    }

    public function login(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!verify_csrf()) {
                set_flash('error', 'Invalid CSRF token.');
                redirect('index.php?page=login');
            }

            $email = trim($_POST['email'] ?? '');
            $password = (string) ($_POST['password'] ?? '');
            $user = $this->users->findByEmail($email);

            if (!$user || !$user['is_active'] || !password_verify($password, $user['password_hash'])) {
                set_flash('error', 'Invalid credentials or inactive account.');
                redirect('index.php?page=login');
            }

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['roles'] = $this->roles->getRoleNamesForUser($user['id']);

            $token = bin2hex(random_bytes(32));
            $_SESSION['session_token'] = $token;
            $this->sessions->create($user['id'], $token, date('Y-m-d H:i:s', strtotime('+1 day')));

            set_flash('success', 'Welcome back!');
            redirect('index.php?page=dashboard');
        }

        require __DIR__ . '/../views/auth/login.php';
    }

    public function logout(): void
    {
        if (isset($_SESSION['session_token'])) {
            $this->sessions->deleteByToken($_SESSION['session_token']);
        }

        $_SESSION = [];
        session_destroy();
        session_start();
        set_flash('success', 'You have been logged out.');
        redirect('index.php?page=login');
    }

    public function forgotPassword(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!verify_csrf()) {
                set_flash('error', 'Invalid CSRF token.');
                redirect('index.php?page=forgot-password');
            }
    
            $email = trim($_POST['email'] ?? '');
            $user = $this->users->findByEmail($email);
    
            if ($user) {
                $token = bin2hex(random_bytes(24));
                $expiresAt = date('Y-m-d H:i:s', strtotime('+1 hour'));
                $this->resets->create($user['id'], $token, $expiresAt);
    
                // ✅ Send actual email via MailService
                $resetLink = 'https://app.macerti.com/index.php?page=reset-password&token=' . urlencode($token);
                $emailSent = MailService::sendPasswordResetEmail($email, $token, $resetLink);
    
                if ($emailSent) {
                    set_flash('success', 'Check your email for password reset instructions.');
                } else {
                    set_flash('warning', 'Password reset token created but email failed to send. Token: ' . $token);
                }
            } else {
                // Security: Don't reveal if email exists
                set_flash('success', 'If that email exists in our system, you\'ll receive a password reset link shortly.');
            }
    
            redirect('index.php?page=forgot-password');
        }
    
        require __DIR__ . '/../views/auth/forgot_password.php';
    }

        public function resetPassword(): void
    {
        $token = trim($_GET['token'] ?? '');
        $reset = null;

        // Validate token from URL on GET request
        if (!empty($token)) {
            $reset = $this->resets->findValid($token);
            if (!$reset) {
                set_flash('error', 'Invalid or expired reset link. Please request a new one.');
                redirect('index.php?page=forgot-password');
            }
        }

        // Handle POST (password reset form submission)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!verify_csrf()) {
                set_flash('error', 'Invalid CSRF token.');
                redirect('index.php?page=reset-password');
            }

            $token = trim($_POST['token'] ?? '');
            $password = (string) ($_POST['password'] ?? '');
            $reset = $this->resets->findValid($token);

            if (!$reset || strlen($password) < 8) {
                set_flash('error', 'Invalid token or weak password (min 8 chars).');
                redirect('index.php?page=reset-password?token=' . urlencode($token));
            }

            $this->users->updatePassword($reset['user_id'], password_hash($password, PASSWORD_DEFAULT));
            $this->resets->markUsed($reset['id']);
            set_flash('success', 'Password reset successful. Please login.');
            redirect('index.php?page=login');
        }

        // If we got here with valid token, show only password form
        require __DIR__ . '/../views/auth/reset_password.php';
    }
}
