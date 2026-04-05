<?php

class SessionModel
{
    public function create(string $userId, string $token, string $expiresAt): bool
    {
        $stmt = db()->prepare('INSERT INTO sessions (id, user_id, token, expires_at) VALUES (UUID(), :user_id, :token, :expires_at)');
        return $stmt->execute([
            ':user_id' => $userId,
            ':token' => $token,
            ':expires_at' => $expiresAt,
        ]);
    }

    public function deleteByToken(string $token): bool
    {
        $stmt = db()->prepare('DELETE FROM sessions WHERE token = :token');
        return $stmt->execute([':token' => $token]);
    }
}
