<?php
require_once __DIR__ . '/../app/bootstrap.php';

$page = $_GET['page'] ?? 'login';

$auth = new AuthController();
$dashboard = new DashboardController();
$userCtrl = new UserController();
$profile = new ProfileController();
$roles = new RoleController();

try {
    switch ($page) {
        case 'register':
            $auth->register();
            break;
        case 'login':
            $auth->login();
            break;
        case 'logout':
            $auth->logout();
            break;
        case 'forgot-password':
            $auth->forgotPassword();
            break;
        case 'reset-password':
            $auth->resetPassword();
            break;
        case 'dashboard':
            $dashboard->index();
            break;
        case 'profile':
            $profile->edit();
            break;
        case 'roles':
            $roles->index();
            break;
        case 'roles-update':
            $roles->updateUserRoles();
            break;
        case 'user-create':
            $userCtrl->create();
            break;
        case 'user-update':
            $userCtrl->update();
            break;
        case 'user-delete':
            $userCtrl->delete();
            break;
        default:
            set_flash('error', 'Page not found.');
            redirect('index.php?page=login');
    }
} catch (Throwable $e) {
    http_response_code(500);
    $title = 'Error';
    require __DIR__ . '/../app/views/layout/header.php';
    echo '<section class="card"><h2>Something went wrong</h2><p>' . e($e->getMessage()) . '</p></section>';
    require __DIR__ . '/../app/views/layout/footer.php';
}
