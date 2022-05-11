<?php

namespace App\TestsProvider\Entity;

use App\Entity\User;

class UserProvider {
    public static function getRegularUser($username = 'John', $password = '1234'): User {
        $user = new User();
        $user->setUsername($username);
        $user->setPassword($password);
        $user->setRoles(['ROLE_USER']);
        return $user;
    }

    public static function getAdminUser($username = 'admin', $password = '1234'): User {
        $user = new User();
        $user->setUsername($username);
        $user->setPassword($password);
        $user->setRoles(['ROLE_ADMIN']);
        return $user;
    }
}