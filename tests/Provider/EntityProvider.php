<?php

use App\Entity\Post;
use App\Entity\User;

class EntityProvider {
    public static function getUser(): User {
        $user = new User();
        $user->setUsername('test');
        $user->setPassword('test');
        $user->setRoles(['ROLE_USER']);
        return $user;
    }

    public static function getPost(): Post {
        $post = new Post();
        $post->setTitle('test');
        $post->setContent('test');
        $post->setAuthor(self::getUser());
        return $post;
    }
}