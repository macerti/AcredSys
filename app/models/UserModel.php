<?php

class UserModel
{
    public function create(string $email, string $passwordHash): bool
    {
        $sql = 'INSERT INTO users (id, email, password_hash, is_active, is_verified) VALUES (UUID(), :email, :password_hash, 1, 0)';
        $stmt = db()->prepare($sql);
        return $stmt->execute([
            ':email' => $email,
            ':password_hash' => $passwordHash,
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

    public function allWithRoles(): array
    {
        $sql = 'SELECT u.*, GROUP_CONCAT(r.name ORDER BY r.name SEPARATOR ", ") AS roles
                FROM users u
                LEFT JOIN user_roles ur ON ur.user_id = u.id
                LEFT JOIN roles r ON r.id = ur.role_id
                GROUP BY u.id
                ORDER BY u.created_at DESC';

        return db()->query($sql)->fetchAll();
    }

    public function update(string $id, string $email, bool $isActive, bool $isVerified): bool
    {
        $sql = 'UPDATE users
                SET email = :email, is_active = :is_active, is_verified = :is_verified
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
