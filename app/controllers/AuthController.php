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

            $namePart = preg_replace('/[^a-zA-Z0-9]/', ' ', (string) strstr($email, '@', true));
            $namePart = trim((string) $namePart);
            $firstName = $namePart !== '' ? ucfirst(strtolower(explode(' ', $namePart)[0])) : 'User';
            $lastName = 'Account';

            $this->users->create($email, password_hash($password, PASSWORD_DEFAULT), $firstName, $lastName);
            set_flash('success', 'Registration successful. Ask your administrator to assign your organization and role before login.');
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

            if (($_POST['action'] ?? '') === 'select-org') {
                $this->completeOrganizationSelection();
                return;
            }

            $email = trim($_POST['email'] ?? '');
            $password = (string) ($_POST['password'] ?? '');
            $user = $this->users->findByEmail($email);

            if (!$user || !$user['is_active'] || !password_verify($password, $user['password_hash'])) {
                set_flash('error', 'Invalid credentials or inactive account.');
                redirect('index.php?page=login');
            }

            $organizationIds = $this->roles->getOrganizationIdsForUser((string) $user['id']);
            if (count($organizationIds) === 0) {
                set_flash('error', 'No active organization membership found for your account.');
                redirect('index.php?page=login');
            }

            if (count($organizationIds) === 1) {
                $this->establishSession($user, (int) $organizationIds[0]);
                return;
            }

            $_SESSION['pending_login_user_id'] = $user['id'];
            $_SESSION['pending_login_org_ids'] = $organizationIds;
            set_flash('info', 'Select an organization to continue.');
            redirect('index.php?page=login');
        }

        require __DIR__ . '/../views/auth/login.php';
    }

    private function completeOrganizationSelection(): void
    {
        $pendingUserId = $_SESSION['pending_login_user_id'] ?? null;
        $pendingOrgIds = $_SESSION['pending_login_org_ids'] ?? [];
        $organizationId = (int) ($_POST['organization_id'] ?? 0);

        if (!$pendingUserId || empty($pendingOrgIds) || !in_array($organizationId, $pendingOrgIds, true)) {
            set_flash('error', 'Invalid organization selection. Please login again.');
            redirect('index.php?page=login');
        }

        $user = $this->users->findById((string) $pendingUserId);
        if (!$user || !$this->roles->userBelongsToOrganization((string) $user['id'], $organizationId)) {
            set_flash('error', 'Organization access could not be validated.');
            redirect('index.php?page=login');
        }

        $this->establishSession($user, $organizationId);
    }

    private function establishSession(array $user, int $organizationId): void
    {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_name'] = trim(($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? ''));
        $_SESSION['organization_id'] = $organizationId;
        $_SESSION['roles'] = $this->roles->getRoleNamesForUser((string) $user['id'], $organizationId);
        $_SESSION['permissions'] = $this->roles->getPermissionsForUser((string) $user['id'], $organizationId);
        unset($_SESSION['pending_login_user_id'], $_SESSION['pending_login_org_ids']);

        $token = bin2hex(random_bytes(32));
        $_SESSION['session_token'] = $token;
        $this->sessions->create((string) $user['id'], $token, date('Y-m-d H:i:s', strtotime('+1 day')));

        set_flash('success', 'Welcome back!');
        redirect('index.php?page=dashboard');
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
                $this->resets->create((string) $user['id'], $token, $expiresAt);

                $resetLink = 'https://app.macerti.com/index.php?page=reset-password&token=' . urlencode($token);
                $emailSent = MailService::sendPasswordResetEmail($email, $token, $resetLink);

                if ($emailSent) {
                    set_flash('success', 'Check your email for password reset instructions.');
                } else {
                    set_flash('warning', 'Password reset token created but email failed to send. Token: ' . $token);
                }
            } else {
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

        if (!empty($token)) {
            $reset = $this->resets->findValid($token);
            if (!$reset) {
                set_flash('error', 'Invalid or expired reset link. Please request a new one.');
                redirect('index.php?page=forgot-password');
            }
        }

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
                redirect('index.php?page=reset-password&token=' . urlencode($token));
            }

            $this->users->updatePassword((string) $reset['user_id'], password_hash($password, PASSWORD_DEFAULT));
            $this->resets->markUsed((string) $reset['id']);
            set_flash('success', 'Password reset successful. Please login.');
            redirect('index.php?page=login');
        }

        require __DIR__ . '/../views/auth/reset_password.php';
    }
}
