<?php

class DashboardController
{
    private UserModel $users;

    public function __construct()
    {
        $this->users = new UserModel();
    }

    public function index(): void
    {
        require_module_access('dashboard');
        $organizationId = require_org_context();
        $users = $this->users->allWithRoles($organizationId);
        require __DIR__ . '/../views/dashboard/index.php';
    }
}
