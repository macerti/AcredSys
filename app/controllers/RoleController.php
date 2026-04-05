<?php

class RoleController
{
    private RoleModel $roles;
    private UserModel $users;

    public function __construct()
    {
        $this->roles = new RoleModel();
        $this->users = new UserModel();
    }

    public function index(): void
    {
        require_auth();
        $roles = $this->roles->all();
        $users = $this->users->allWithRoles();
        require __DIR__ . '/../views/roles/index.php';
    }

    public function updateUserRoles(): void
    {
        require_auth();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !verify_csrf()) {
            set_flash('error', 'Invalid request.');
            redirect('index.php?page=roles');
        }

        $userId = $_POST['user_id'] ?? '';
        $roleIds = array_map('intval', $_POST['role_ids'] ?? []);

        if (!$userId) {
            set_flash('error', 'Invalid user selected.');
            redirect('index.php?page=roles');
        }

        $this->roles->syncUserRoles($userId, $roleIds);

        if ($userId === current_user_id()) {
            $_SESSION['roles'] = $this->roles->getRoleNamesForUser($userId);
        }

        set_flash('success', 'Roles updated successfully.');
        redirect('index.php?page=roles');
    }
}
