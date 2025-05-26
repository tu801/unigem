<?php

/**
 * generate gravatar link
 * @param $user
 * @param null $size
 * @return string
 */
function getGravatarLink($user, ?int $size = null): string
{
    $userConfig = config('Users');
    $config = config('Acp');

    // Default from Gravatar
    if ($userConfig->useGravatar) {
        $hash = md5(strtolower(trim($user->id)));

        return "https://www.gravatar.com/avatar/{$hash}?" . http_build_query([
            's' => ($size ?? 60),
            'd' => $userConfig->gravatarDefault,
        ]);
    } else {
        return base_url("{$config->templatePath}assets/img/avatar.png");
    }
}

if (!function_exists('getUserAvatar')) {
    /**
     * get the user avatar link if exist
     * else return the gravatar link
     * @return string
     */
    function getUserAvatar($user): string
    {
        $avatarLink = '';
        $userConfig = config('Users');
        $config = config('Acp');

        if (!empty($user->avatar)) {
            $date = new \DateTime($user->created_at);
            $avtReal = $config->uploadPath . $userConfig->avatarDirectory . '/' . $date->format('Y') . '/' . $user->avatar;
            if (!file_exists($avtReal)) {
                $avatarLink = getGravatarLink($user);
            } else {
                $avatarLink = base_url($avtReal);
            }
        } else {
            $avatarLink = getGravatarLink($user);
        }
        return $avatarLink;
    }
}