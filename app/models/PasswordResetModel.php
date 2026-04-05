<?php

class PasswordResetModel
{
    public function create(string $userId, string $token, string $expiresAt): bool
    {
        $sql = 'INSERT INTO password_resets (id, user_id, reset_token, expires_at, used)
                VALUES (UUID(), :user_id, :reset_token, :expires_at, 0)';

        $stmt = db()->prepare($sql);
        return $stmt->execute([
            ':user_id' => $userId,
            ':reset_token' => $token,
            ':expires_at' => $expiresAt,
        ]);
    }

    public function findValid(string $token): ?array
    {
        $sql = 'SELECT * FROM password_resets
                WHERE reset_token = :reset_token
                  AND used = 0
                  AND expires_at > NOW()
                ORDER BY created_at DESC
                LIMIT 1';

        $stmt = db()->prepare($sql);
        $stmt->execute([':reset_token' => $token]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function markUsed(string $id): bool
    {
        $stmt = db()->prepare('UPDATE password_resets SET used = 1 WHERE id = :id');
        return $stmt->execute([':id' => $id]);
    }
}
