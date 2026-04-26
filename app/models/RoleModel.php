<?php

class RoleModel
{
    public function all(): array
    {
        return db()->query('SELECT * FROM system_roles ORDER BY label_en')->fetchAll();
    }

    public function getUserRoleIds(string $userId, int $organizationId): array
    {
        $stmt = db()->prepare('SELECT system_role_id FROM user_organization_roles WHERE user_id = :user_id AND organization_id = :organization_id AND is_active = 1');
        $stmt->execute([
            ':user_id' => $userId,
            ':organization_id' => $organizationId,
        ]);
        return array_map('intval', array_column($stmt->fetchAll(), 'system_role_id'));
    }

    public function syncUserRoles(string $userId, array $roleIds, int $organizationId): void
    {
        $pdo = db();
        $pdo->beginTransaction();

        try {
            $delete = $pdo->prepare('DELETE FROM user_organization_roles WHERE user_id = :user_id AND organization_id = :organization_id');
            $delete->execute([
                ':user_id' => $userId,
                ':organization_id' => $organizationId,
            ]);

            if (!empty($roleIds)) {
                $insert = $pdo->prepare(
                    'INSERT INTO user_organization_roles (user_id, organization_id, system_role_id, is_active, assigned_by)
                     VALUES (:user_id, :organization_id, :system_role_id, 1, :assigned_by)'
                );

                foreach ($roleIds as $roleId) {
                    $insert->execute([
                        ':user_id' => $userId,
                        ':organization_id' => $organizationId,
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

    public function getRoleNamesForUser(string $userId, int $organizationId): array
    {
        $stmt = db()->prepare(
            'SELECT sr.name
             FROM system_roles sr
             JOIN user_organization_roles uor ON uor.system_role_id = sr.id
             WHERE uor.user_id = :user_id AND uor.organization_id = :organization_id AND uor.is_active = 1'
        );
        $stmt->execute([
            ':user_id' => $userId,
            ':organization_id' => $organizationId,
        ]);

        return array_column($stmt->fetchAll(), 'name');
    }

    public function getOrganizationIdsForUser(string $userId): array
    {
        $stmt = db()->prepare('SELECT DISTINCT organization_id FROM user_organization_roles WHERE user_id = :user_id AND is_active = 1 ORDER BY organization_id ASC');
        $stmt->execute([':user_id' => $userId]);
        return array_map('intval', array_column($stmt->fetchAll(), 'organization_id'));
    }

    public function userBelongsToOrganization(string $userId, int $organizationId): bool
    {
        $stmt = db()->prepare('SELECT 1 FROM user_organization_roles WHERE user_id = :user_id AND organization_id = :organization_id AND is_active = 1 LIMIT 1');
        $stmt->execute([
            ':user_id' => $userId,
            ':organization_id' => $organizationId,
        ]);

        return (bool) $stmt->fetchColumn();
    }

    public function getPermissionsForUser(string $userId, int $organizationId): array
    {
        $stmt = db()->prepare(
            'SELECT sr.*
             FROM system_roles sr
             JOIN user_organization_roles uor ON uor.system_role_id = sr.id
             WHERE uor.user_id = :user_id AND uor.organization_id = :organization_id AND uor.is_active = 1'
        );
        $stmt->execute([
            ':user_id' => $userId,
            ':organization_id' => $organizationId,
        ]);
        $roles = $stmt->fetchAll();

        $permissions = [];
        foreach ($roles as $role) {
            foreach ($role as $column => $value) {
                if ((str_starts_with($column, 'can_') || str_starts_with($column, 'access_')) && (int) $value === 1) {
                    $module = str_replace('_', '-', preg_replace('/^(can_|access_)/', '', $column));
                    $permissions[$module] = true;
                }
            }
        }

        return array_keys($permissions);
    }
}
