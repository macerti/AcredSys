<?php

class UserModel
{
    public function create(string $email, string $passwordHash, string $firstName, string $lastName, string $preferredLocale = 'en'): bool
    {
        $sql = 'INSERT INTO users (email, password_hash, first_name, last_name, preferred_locale, is_active)
                VALUES (:email, :password_hash, :first_name, :last_name, :preferred_locale, 1)';
        $stmt = db()->prepare($sql);
        return $stmt->execute([
            ':email' => $email,
            ':password_hash' => $passwordHash,
            ':first_name' => $firstName,
            ':last_name' => $lastName,
            ':preferred_locale' => in_array($preferredLocale, ['en', 'fr'], true) ? $preferredLocale : 'en',
        ]);
    }

    public function findByEmail(string $email): ?array
    {
        $stmt = db()->prepare('SELECT * FROM users WHERE email = :email LIMIT 1');
        $stmt->execute([':email' => $email]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function findById(string $id): ?array
    {
        $stmt = db()->prepare('SELECT * FROM users WHERE id = :id LIMIT 1');
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function allWithRoles(int $organizationId): array
    {
        $sql = 'SELECT u.*, GROUP_CONCAT(sr.name ORDER BY sr.name SEPARATOR ", ") AS roles
                FROM users u
                JOIN user_organization_roles uor ON uor.user_id = u.id AND uor.organization_id = :organization_id AND uor.is_active = 1
                LEFT JOIN system_roles sr ON sr.id = uor.system_role_id
                GROUP BY u.id
                ORDER BY u.created_at DESC';

        $stmt = db()->prepare($sql);
        $stmt->execute([':organization_id' => $organizationId]);
        return $stmt->fetchAll();
    }

    public function update(string $id, string $email, bool $isActive, bool $isVerified): bool
    {
        $sql = 'UPDATE users
                SET email = :email,
                    is_active = :is_active,
                    email_verified_at = CASE
                        WHEN :is_verified = 1 THEN COALESCE(email_verified_at, NOW())
                        ELSE NULL
                    END
                WHERE id = :id';

        $stmt = db()->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':email' => $email,
            ':is_active' => $isActive ? 1 : 0,
            ':is_verified' => $isVerified ? 1 : 0,
        ]);
    }

    public function updatePassword(string $id, string $passwordHash): bool
    {
        $stmt = db()->prepare('UPDATE users SET password_hash = :password_hash WHERE id = :id');
        return $stmt->execute([':id' => $id, ':password_hash' => $passwordHash]);
    }

    public function delete(string $id): bool
    {
        $stmt = db()->prepare('DELETE FROM users WHERE id = :id');
        return $stmt->execute([':id' => $id]);
    }
}
