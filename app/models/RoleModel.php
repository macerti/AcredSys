<?php

class RoleModel
{
    public function all(): array
    {
        return db()->query('SELECT * FROM system_roles ORDER BY label_en')->fetchAll();
    }

    public function getUserRoleIds(string $userId): array
    {
        $stmt = db()->prepare('SELECT system_role_id FROM user_organization_roles WHERE user_id = :user_id AND is_active = 1');
        $stmt->execute([':user_id' => $userId]);
        return array_map('intval', array_column($stmt->fetchAll(), 'system_role_id'));
    }

    public function syncUserRoles(string $userId, array $roleIds): void
    {
        $pdo = db();
        $orgId = $this->resolveOrganizationId();
        $pdo->beginTransaction();

        try {
            $delete = $pdo->prepare('DELETE FROM user_organization_roles WHERE user_id = :user_id AND organization_id = :organization_id');
            $delete->execute([
                ':user_id' => $userId,
                ':organization_id' => $orgId,
            ]);

            if (!empty($roleIds)) {
                $insert = $pdo->prepare(
                    'INSERT INTO user_organization_roles (user_id, organization_id, system_role_id, is_active, assigned_by)
                     VALUES (:user_id, :organization_id, :system_role_id, 1, :assigned_by)'
                );
                foreach ($roleIds as $roleId) {
                    $insert->execute([
                        ':user_id' => $userId,
                        ':organization_id' => $orgId,
                        ':system_role_id' => $roleId,
                        ':assigned_by' => $_SESSION['user_id'] ?? null,
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
        $stmt = db()->prepare(
            'SELECT sr.name
             FROM system_roles sr
             JOIN user_organization_roles uor ON uor.system_role_id = sr.id
             WHERE uor.user_id = :user_id AND uor.is_active = 1'
        );
        $stmt->execute([':user_id' => $userId]);
        return array_column($stmt->fetchAll(), 'name');
    }

    private function resolveOrganizationId(): int
    {
        if (!empty($_SESSION['organization_id'])) {
            return (int) $_SESSION['organization_id'];
        }

        $row = db()->query('SELECT id FROM organizations ORDER BY id ASC LIMIT 1')->fetch();
        if (!$row) {
            throw new RuntimeException('No organization found. Please create an organization first.');
        }

        return (int) $row['id'];
    }
}
