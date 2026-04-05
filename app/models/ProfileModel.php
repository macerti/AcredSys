<?php

class ProfileModel
{
    public function findByUserId(string $userId): ?array
    {
        $stmt = db()->prepare('SELECT * FROM user_profiles WHERE user_id = :user_id LIMIT 1');
        $stmt->execute([':user_id' => $userId]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function upsert(string $userId, ?string $displayName, ?string $avatarUrl, ?string $bio, string $publicId): bool
    {
        $sql = 'INSERT INTO user_profiles (user_id, display_name, avatar_url, bio, public_id)
                VALUES (:user_id, :display_name, :avatar_url, :bio, :public_id)
                ON DUPLICATE KEY UPDATE
                    display_name = VALUES(display_name),
                    avatar_url = VALUES(avatar_url),
                    bio = VALUES(bio),
                    public_id = VALUES(public_id)';

        $stmt = db()->prepare($sql);
        return $stmt->execute([
            ':user_id' => $userId,
            ':display_name' => $displayName,
            ':avatar_url' => $avatarUrl,
            ':bio' => $bio,
            ':public_id' => $publicId,
        ]);
    }
}
