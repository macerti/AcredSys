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
        require_auth();
        $users = $this->users->allWithRoles();
        require __DIR__ . '/../views/dashboard/index.php';
    }
}
