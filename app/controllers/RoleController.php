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
        require_module_access('roles');
        $organizationId = require_org_context();
        $roles = $this->roles->all();
        $users = $this->users->allWithRoles($organizationId);
        require __DIR__ . '/../views/roles/index.php';
    }

    public function updateUserRoles(): void
    {
        require_module_access('roles');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !verify_csrf()) {
            set_flash('error', 'Invalid request.');
            redirect('index.php?page=roles');
        }

        $organizationId = (int) ($_POST['organization_id'] ?? current_organization_id() ?? 0);
        $sessionOrgId = require_org_context();
        $userId = (string) ($_POST['user_id'] ?? '');
        $roleIds = array_map('intval', $_POST['role_ids'] ?? []);

        if ($organizationId !== $sessionOrgId) {
            set_flash('error', 'Organization mismatch.');
            redirect('index.php?page=roles');
        }

        if ($userId === '') {
            set_flash('error', 'Invalid user selected.');
            redirect('index.php?page=roles');
        }

        if (!$this->roles->userBelongsToOrganization($userId, $organizationId)) {
            set_flash('error', 'User does not belong to this organization.');
            redirect('index.php?page=roles');
        }

        $this->roles->syncUserRoles($userId, $roleIds, $organizationId);

        if ($userId === (string) current_user_id()) {
            $_SESSION['roles'] = $this->roles->getRoleNamesForUser($userId, $organizationId);
            $_SESSION['permissions'] = $this->roles->getPermissionsForUser($userId, $organizationId);
        }

        set_flash('success', 'Roles updated successfully.');
        redirect('index.php?page=roles');
    }
}
