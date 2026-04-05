<?php

class ProfileController
{
    private ProfileModel $profiles;

    public function __construct()
    {
        $this->profiles = new ProfileModel();
    }

    public function edit(): void
    {
        require_auth();
        $userId = current_user_id();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!verify_csrf()) {
                set_flash('error', 'Invalid CSRF token.');
                redirect('index.php?page=profile');
            }

            $displayName = trim($_POST['display_name'] ?? '');
            $avatarUrl = trim($_POST['avatar_url'] ?? '');
            $bio = trim($_POST['bio'] ?? '');
            $publicId = trim($_POST['public_id'] ?? '') ?: generate_public_id();

            $this->profiles->upsert(
                $userId,
                $displayName !== '' ? $displayName : null,
                $avatarUrl !== '' ? $avatarUrl : null,
                $bio !== '' ? $bio : null,
                $publicId
            );

            set_flash('success', 'Profile saved successfully.');
            redirect('index.php?page=profile');
        }

        $profile = $this->profiles->findByUserId($userId);
        if (!$profile) {
            $profile = [
                'display_name' => '',
                'avatar_url' => '',
                'bio' => '',
                'public_id' => generate_public_id(),
            ];
        }

        require __DIR__ . '/../views/profile/edit.php';
    }
}
