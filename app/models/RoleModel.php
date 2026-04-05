<?php

class RoleModel
{
    public function all(): array
    {
        return db()->query('SELECT * FROM roles ORDER BY name')->fetchAll();
    }

    public function getUserRoleIds(string $userId): array
    {
        $stmt = db()->prepare('SELECT role_id FROM user_roles WHERE user_id = :user_id');
        $stmt->execute([':user_id' => $userId]);
        return array_map('intval', array_column($stmt->fetchAll(), 'role_id'));
    }

    public function syncUserRoles(string $userId, array $roleIds): void
    {
        $pdo = db();
        $pdo->beginTransaction();

        try {
            $delete = $pdo->prepare('DELETE FROM user_roles WHERE user_id = :user_id');
            $delete->execute([':user_id' => $userId]);

            if (!empty($roleIds)) {
                $insert = $pdo->prepare('INSERT INTO user_roles (user_id, role_id) VALUES (:user_id, :role_id)');
                foreach ($roleIds as $roleId) {
                    $insert->execute([
                        ':user_id' => $userId,
                        ':role_id' => $roleId,
                    ]);
                }
            }

            $pdo->commit();
        } catch (Throwable $e) {
            $pdo->rollBack();
            throw $e;
        }
    }

    public function getRoleNamesForUser(string $userId): array
    {
        $stmt = db()->prepare('SELECT r.name FROM roles r JOIN user_roles ur ON ur.role_id = r.id WHERE ur.user_id = :user_id');
        $stmt->execute([':user_id' => $userId]);
        return array_column($stmt->fetchAll(), 'name');
    }
}
